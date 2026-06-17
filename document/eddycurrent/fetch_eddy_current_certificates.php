<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) session_start();
include_once('../../file/config.php');

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

/* 🔥 FILTER PARAMETERS */
$filterInspector = $_POST['filter_inspector'] ?? '';
$filterDate = $_POST['filter_date'] ?? '';
$filterClient = $_POST['filter_client'] ?? '';
$filterStatus = $_POST['filter_status'] ?? '';
$filterYear = $_POST['filter_year'] ?? '';

/* ===============================
   Role-based WHERE clause
 ================================ */
$where = " WHERE 1=1 ";

if ($role === 'inspector') {
    $where .= " AND ec.inspector = '" . mysqli_real_escape_string($conn, $username) . "'";
}

if (!empty($filterInspector)) {
    $where .= " AND ec.inspector = '" . mysqli_real_escape_string($conn, $filterInspector) . "'";
}
if (!empty($filterDate)) {
    $where .= " AND DATE(ec.inspection_date) = '" . mysqli_real_escape_string($conn, $filterDate) . "'";
}
if (!empty($filterClient)) {
    $where .= " AND ec.customer_name = '" . mysqli_real_escape_string($conn, $filterClient) . "'";
}
if (!empty($filterYear)) {
    $where .= " AND YEAR(ec.inspection_date) = '" . mysqli_real_escape_string($conn, $filterYear) . "'";
}

if ($filterStatus === 'Active') {
    $where .= " AND (ec.next_inspection_date >= CURDATE() OR ec.next_inspection_date IS NULL OR ec.next_inspection_date = '0000-00-00')";
} elseif ($filterStatus === 'Expired') {
    $where .= " AND ec.next_inspection_date < CURDATE() AND ec.next_inspection_date != '0000-00-00' AND ec.next_inspection_date IS NOT NULL";
}

/* ===============================
   Search (Global)
 ================================ */
if ($search) {
    $safe = mysqli_real_escape_string($conn, $search);
    $where .= " AND (
        ec.project_no LIKE '%$safe%' OR
        ec.certificate_no LIKE '%$safe%' OR
        ec.inspected_item LIKE '%$safe%' OR
        ec.serial_no LIKE '%$safe%' OR
        ec.inspector LIKE '%$safe%' OR
        ec.customer_name LIKE '%$safe%' OR
        ec.location LIKE '%$safe%'
    )";
}

/* ===============================
   KPI COUNTS (FAST & OPTIMIZED)
 ================================ */
$kpiSql = "
SELECT
    COUNT(*) AS total,
    SUM(CASE WHEN next_inspection_date >= CURDATE() OR next_inspection_date IS NULL OR next_inspection_date = '0000-00-00' THEN 1 ELSE 0 END) AS active,
    SUM(CASE WHEN next_inspection_date < CURDATE() AND next_inspection_date != '0000-00-00' AND next_inspection_date IS NOT NULL THEN 1 ELSE 0 END) AS expired
FROM eddy_current_inspection ec
$where
";
$kpiRow = $conn->query($kpiSql)->fetch_assoc();

/* ===============================
   Total Records
 ================================ */
$totalRecordsQuery = "SELECT COUNT(*) AS cnt FROM eddy_current_inspection ec";
$totalRecords = $conn->query($totalRecordsQuery)->fetch_assoc()['cnt'];

$filteredRecordsQuery = "SELECT COUNT(*) AS cnt FROM eddy_current_inspection ec $where";
$filteredRecords = $conn->query($filteredRecordsQuery)->fetch_assoc()['cnt'];

/* ===============================
   Export Mode
 ================================ */
$isExport = isset($_POST['export']) && $_POST['export'] === 'true';

/* ===============================
   ORDER
 ================================ */
$idx = intval($_POST['order'][0]['column'] ?? 7);
$dir = ($_POST['order'][0]['dir'] ?? 'desc') === 'asc' ? 'ASC' : 'DESC';
$columns = [
    0 => 'CAST(SUBSTRING(ec.project_no,5) AS UNSIGNED)',
    1 => 'ec.certificate_no',
    2 => 'ec.inspected_item',
    3 => 'ec.serial_no',
    4 => 'ec.inspector',
    5 => 'ec.customer_name',
    6 => 'ec.location',
    7 => 'ec.inspection_date',
];
$orderCol = $columns[$idx] ?? 'ec.inspection_date';

$sql = "
SELECT
    ec.*,
    pi.project_status
FROM eddy_current_inspection ec
LEFT JOIN project_info pi ON ec.project_no = pi.project_no
$where
ORDER BY $orderCol $dir
";

if (!$isExport) {
    $sql .= " LIMIT $start, $length";
}

$res = $conn->query($sql);

if (!$res) {
    if ($isExport) { die('Query Error: '.$conn->error); }
    header('Content-Type: application/json');
    echo json_encode(['draw'=>$draw,'recordsTotal'=>0,'recordsFiltered'=>0,'kpi'=>$kpiRow,'data'=>[],'error'=>$conn->error]);
    exit;
}

/* ===============================
   Export CSV
 ================================ */
if ($isExport) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="eddy_current_certificates_'.date('Ymd_His').'.csv"');
    $out = fopen('php://output','w');
    fputcsv($out, ['Project No','Certificate No','Inspected Item','Serial No','Inspector','Client','Location','Inspection Date','Status']);
    while ($r = $res->fetch_assoc()) {
        $isExpired = !empty($r['next_inspection_date']) && $r['next_inspection_date'] != '0000-00-00' && strtotime($r['next_inspection_date']) < time();
        fputcsv($out, [
            $r['project_no'], $r['certificate_no'], $r['inspected_item'],
            $r['serial_no'], $r['inspector'], $r['customer_name'],
            $r['location'],
            $r['inspection_date'] ? date('d M Y', strtotime($r['inspection_date'])) : '',
            $isExpired ? 'Expired' : 'Active'
        ]);
    }
    fclose($out);
    exit;
}

$data = [];
while ($r = $res->fetch_assoc()) {
    $inspectorName = trim($r['inspector'] ?? '');
    $initial = !empty($inspectorName) ? strtoupper(substr($inspectorName, 0, 1)) : 'U';

    $status = 'Active';
    $badgeClass = 'badge-success';
    
    if (!empty($r['next_inspection_date']) && $r['next_inspection_date'] != '0000-00-00' && strtotime($r['next_inspection_date']) < time()) {
        $status = 'Expired';
        $badgeClass = 'badge-danger';
    }

    $actions = '
        <div class="action-icons">
            <a href="view.php?project_no='.$r['project_no'].'" class="view-icon" title="View" target="_blank">
                <i class="fa fa-eye"></i>
            </a>
            <a href="download.php?project_no='.$r['project_no'].'" class="download-icon" title="Download">
                <i class="fa fa-download"></i>
            </a>
    ';

    if (($role === 'document controller' || $role === 'inspector' || $role === 'admin') && $r['project_status'] !== 'Completed') {
        $actions .= '
            <a href="edit.php?project_no='.$r['project_no'].'" class="edit-icon" title="Edit" style="color: #b45309; background: #fef3c7;">
                <i class="fa fa-edit"></i>
            </a>
        ';
    }
    
    if ($r['project_status'] !== 'Completed') {
        $actions .= '
                <a href="javascript:void(0)" class="delete-icon" onclick="deleteRow(\''.$r['project_no'].'\')" title="Delete" style="color: #e11d48; background: #ffe4e6;">
                    <i class="fa fa-trash"></i>
                </a>';
    }
    $actions .= '
        </div>
    ';

    $data[] = [
        "project_no"        => $r['project_no'],
        "certificate_no"    => $r['certificate_no'],
        "inspected_item"    => $r['inspected_item'],
        "serial_no"         => $r['serial_no'],
        "inspector"         => '
            <div style="display:flex; gap:8px; align-items:center;">
                <div class="avatar-circle" style="background-color: ' . getInitialColor($r['inspector']) . ';">
                    ' . $initial . '
                </div>
                <span>' . htmlspecialchars($inspectorName) . '</span>
            </div>',
        "customer_name"     => $r['customer_name'],
        "location"          => $r['location'],
        "inspection_date"   => date('d-m-Y', strtotime($r['inspection_date'])),
        "status"            => "<span class='status-badge $badgeClass'>$status</span>",
        "action"            => $actions
    ];
}

echo json_encode([
    "draw"            => $draw,
    "recordsTotal"    => (int)$totalRecords,
    "recordsFiltered" => (int)$filteredRecords,
    "kpi" => [
        "total"   => (int)$kpiRow['total'],
        "active"  => (int)$kpiRow['active'],
        "expired" => (int)$kpiRow['expired']
    ],
    "data" => $data
]);
?>
