<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 0);
// header('Content-Type: application/json');

session_start();
include_once('../../file/config.php');

// Helper function to generate consistent color for initials
function getInitialColor($name) {
    $colors = [
        '#4f46e5', '#7c3aed', '#db2777', '#ea580c', '#c026d3',
        '#0891b2', '#15803d', '#b45309', '#be123c', '#4338ca'
    ];
    $hash = crc32($name ?? 'A');
    return $colors[$hash % count($colors)];
}

$role     = $_SESSION['role'] ?? '';
$username = $_SESSION['username'] ?? '';

$draw   = intval($_POST['draw'] ?? 1);
$start  = intval($_POST['start'] ?? 0);
$length = intval($_POST['length'] ?? 10);
$search = $_POST['search']['value'] ?? '';

/* ================= SORTING ================= */
$orderColumnIndex = $_POST['order'][0]['column'] ?? 1;
$orderDir         = ($_POST['order'][0]['dir'] ?? 'desc') === 'asc' ? 'ASC' : 'DESC';

/* 🔥 FILTER PARAMETERS */
/* ================= FILTER PARAMETERS ================= */
$filterInspector = $_POST['filter_inspector'] ?? '';
$filterDate      = $_POST['filter_date'] ?? '';
$filterClient    = $_POST['filter_client'] ?? '';
$filterStatus    = $_POST['filter_status'] ?? '';
$filterYear      = $_POST['filter_year'] ?? '';

/*
 Column map (ONLY real DB columns)
*/
$columns = [
    0 => 'chc.project_no',
    1 => 'chc.project_no',
    2 => 'chc.certificate_no',
    3 => 'chc.vessel_name_location',
    4 => 'chc.serial_number',
    5 => 'chc.inspector',
    6 => 'chc.customer_name',
    7 => 'chc.asset_number',
    8 => 'chc.created_at',
    9 => 'r.next_inspection_due_date'
];

$orderBy = $columns[$orderColumnIndex] ?? 'chc.created_at';

/*
 🔥 NUMERIC SORT FOR PROJECT NO (CIMS50000)
*/
if ($orderColumnIndex == 0 || $orderColumnIndex == 1) {
    $orderSql = "CAST(SUBSTRING(chc.project_no, 5) AS UNSIGNED) $orderDir";
} else {
    $orderSql = "$orderBy $orderDir";
}

/* ================= WHERE ================= */
/* ================= WHERE ================= */
$where = "WHERE 1=1";

if ($role === 'inspector') {
    $where .= " AND chc.inspector = '".$conn->real_escape_string($username)."'";
}

if (!empty($filterInspector)) {
    $where .= " AND chc.inspector = '".$conn->real_escape_string($filterInspector)."'";
}
if (!empty($filterDate)) {
    $where .= " AND DATE(chc.created_at) = '".$conn->real_escape_string($filterDate)."'";
}
if (!empty($filterClient)) {
    $where .= " AND chc.customer_name = '".$conn->real_escape_string($filterClient)."'";
}
if (!empty($filterYear)) {
    $where .= " AND YEAR(chc.created_at) = '".$conn->real_escape_string($filterYear)."'";
}
if ($filterStatus === 'Active') {
    $where .= " AND (r.next_inspection_due_date >= CURDATE() OR r.next_inspection_due_date IS NULL)";
} elseif ($filterStatus === 'Expired') {
    $where .= " AND r.next_inspection_due_date < CURDATE() AND r.next_inspection_due_date IS NOT NULL";
}

if ($search !== '') {
    $s = $conn->real_escape_string($search);
    $where .= " AND (
        chc.project_no LIKE '%$s%' OR
        chc.certificate_no LIKE '%$s%' OR
        chc.vessel_name_location LIKE '%$s%' OR
        chc.serial_number LIKE '%$s%' OR
        chc.inspector LIKE '%$s%' OR
        chc.customer_name LIKE '%$s%' OR
        chc.asset_number LIKE '%$s%' OR
        DATE_FORMAT(chc.created_at,'%d-%m-%Y') LIKE '%$s%'
    )";
}

/* ================= TOTAL ================= */
$total = $conn->query("
    SELECT COUNT(*) cnt
    FROM crane_health_check_certificate
")->fetch_assoc()['cnt'];

/* ================= FILTERED ================= */
$filtered = $conn->query("
    SELECT COUNT(*) cnt
    FROM crane_health_check_certificate chc
    LEFT JOIN reports r ON r.project_no = chc.project_no AND r.report_no = chc.report_no
    $where
")->fetch_assoc()['cnt'];

/* ================= KPI ================= */
$kpi = $conn->query("
    SELECT
        COUNT(*) total,
        SUM(CASE WHEN r.next_inspection_due_date >= CURDATE() OR r.next_inspection_due_date IS NULL THEN 1 ELSE 0 END) active,
        SUM(CASE WHEN r.next_inspection_due_date < CURDATE() THEN 1 ELSE 0 END) expired
    FROM crane_health_check_certificate chc
    LEFT JOIN reports r ON r.project_no = chc.project_no AND r.report_no = chc.report_no
    $where
")->fetch_assoc();

/* ================= DATA ================= */
$isExport = isset($_POST['export']) && $_POST['export'] === 'true';

$sql = "
SELECT
    chc.project_no,
    chc.certificate_no,
    chc.vessel_name_location,
    chc.serial_number,
    chc.inspector,
    chc.customer_name,
    chc.asset_number,
    chc.created_at,
    r.next_inspection_due_date,
    pi.project_status
FROM crane_health_check_certificate chc
LEFT JOIN reports r ON r.project_no = chc.project_no AND r.report_no = chc.report_no
LEFT JOIN project_info pi ON chc.project_no = pi.project_no
$where
ORDER BY $orderSql
";

if (!$isExport) {
    $sql .= " LIMIT $start, $length";
}

$res = $conn->query($sql);

if (!$res) {
    if ($isExport) {
        die("Query Error: " . $conn->error);
    }
    echo json_encode([
        "draw" => $draw,
        "recordsTotal" => 0,
        "recordsFiltered" => 0,
        "kpi" => ["total"=>0,"active"=>0,"expired"=>0],
        "data" => [],
        "error" => $conn->error
    ]);
    exit;
}

if ($isExport) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=Health_Check_Certificates_' . date('Ymd_His') . '.csv');
    $output = fopen('php://output', 'w');
    
    // CSV Header
    fputcsv($output, ['Project No', 'Certificate No', 'Inspected Item', 'Serial No', 'Inspector', 'Client', 'Location', 'Date', 'Status']);

    while ($r = $res->fetch_assoc()) {
        $status = 'Active';
        if ($r['next_inspection_due_date'] && strtotime($r['next_inspection_due_date']) < time()) {
            $status = 'Expired';
        }

        fputcsv($output, [
            $r['project_no'],
            $r['certificate_no'],
            $r['vessel_name_location'],
            $r['serial_number'],
            strip_tags($r['inspector']), // Clear any HTML if it exists in DB, though normally it's added in JS
            $r['customer_name'],
            $r['asset_number'],
            date('d-m-Y', strtotime($r['created_at'])),
            $status
        ]);
    }
    fclose($output);
    exit;
}

$data = [];
while ($r = $res->fetch_assoc()) {

    $sortNo = (int)preg_replace('/\D/', '', $r['project_no']);
    $inspectorName = trim($r['inspector'] ?? '');
    $initial = !empty($inspectorName) ? strtoupper(substr($inspectorName, 0, 1)) : 'N';

    $status = 'Active';
    $badgeClass = 'badge-success';
    if ($r['next_inspection_due_date'] && strtotime($r['next_inspection_due_date']) < time()) {
        $status = 'Expired';
        $badgeClass = 'badge-danger';
    }

    $data[] = [
        "project_no" => $r['project_no'],
        "project_no_sort" => $sortNo,
        "certificate_no" => $r['certificate_no'],
        "vessel_name_location" => $r['vessel_name_location'],
        "serial_number" => $r['serial_number'],
        
        // ✅ Fixed and Improved Inspector Column
        "inspector" => '
            <div style="display:flex; gap:8px; align-items:center;">
                <div class="avatar-circle" style="background-color: ' . getInitialColor($r['inspector']) . ';">
                    ' . $initial . '
                </div>
                <span>' . htmlspecialchars($inspectorName) . '</span>
            </div>
        ',
        
        "customer_name" => $r['customer_name'],
        "asset_number" => $r['asset_number'],
        "created_at" => date('d-m-Y', strtotime($r['created_at'])),
        "status" => "<span class='status-badge $badgeClass'>$status</span>",
        "actions" => '
            <div class="action-icons">
                <a href="view.php?project_no=' . $r['project_no'] . '" class="view-icon" target="_blank" title="View">
                    <i class="fa fa-eye"></i>
                </a>
                <a href="download.php?project_no=' . $r['project_no'] . '" class="download-icon" title="Download">
                    <i class="fa fa-download"></i>
                </a>
                ' . ((($role === 'document controller' || $role === 'inspector') && $r['project_status'] !== 'Completed') ? '
                <a href="edit.php?project_no=' . $r['project_no'] . '" class="edit-icon" title="Edit" style="color: #b45309; background: #fef3c7;">
                    <i class="fa fa-edit"></i>
                </a>
                ' : '') . '
                ' . ($r['project_status'] !== 'Completed' ? '
                <a href="javascript:void(0)" class="delete-icon" onclick="deleteRow(\'' . $r['project_no'] . '\')" title="Delete" style="color: #e11d48; background: #ffe4e6;">
                    <i class="fa fa-trash"></i>
                </a>
                ' : '') . '
            </div>
        '
    ];
}

/* ================= RESPONSE ================= */
echo json_encode([
    "draw" => $draw,
    "recordsTotal" => (int)$total,
    "recordsFiltered" => (int)$filtered,
    "kpi" => [
        "total" => (int)$kpi['total'],
        "active" => (int)$kpi['active'],
        "expired" => (int)$kpi['expired']
    ],
    "data" => $data
]);
?>
