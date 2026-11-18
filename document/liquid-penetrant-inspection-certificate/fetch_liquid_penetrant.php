<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include_once('../../file/config.php');

if (!function_exists('getInitialColor')) {
    function getInitialColor($name) {
        $colors = [
            '#4f46e5', '#7c3aed', '#db2777', '#ea580c', '#c026d3',
            '#0891b2', '#15803d', '#b45309', '#be123c', '#4338ca'
        ];
        $hash = crc32($name ?? 'A');
        return $colors[$hash % count($colors)];
    }
}

if (!isset($_SESSION['username'])) {
    echo json_encode([
        "draw" => 0,
        "recordsTotal" => 0,
        "recordsFiltered" => 0,
        "data" => []
    ]);
    exit;
}

$role     = $_SESSION['role'] ?? '';
$username = $_SESSION['username'] ?? '';

$draw   = intval($_POST['draw'] ?? $_GET['draw'] ?? 1);
$start  = intval($_POST['start'] ?? $_GET['start'] ?? 0);
$length = intval($_POST['length'] ?? $_GET['length'] ?? 10);
$search = trim($_POST['search']['value'] ?? $_POST['search'] ?? $_GET['search']['value'] ?? $_GET['search'] ?? "");

/* FILTER PARAMETERS */
$filterInspector = $_POST['filter_inspector'] ?? $_GET['filter_inspector'] ?? '';
$filterDate      = $_POST['filter_date'] ?? $_GET['filter_date'] ?? '';
$filterDateFrom  = $_POST['filter_date_from'] ?? $_GET['filter_date_from'] ?? '';
$filterDateTo    = $_POST['filter_date_to'] ?? $_GET['filter_date_to'] ?? '';
$filterClient    = $_POST['filter_client'] ?? $_GET['filter_client'] ?? '';
$filterStatus    = $_POST['filter_status'] ?? $_GET['filter_status'] ?? '';
$filterYear      = $_POST['filter_year'] ?? $_GET['filter_year'] ?? '';
$isExport        = (isset($_POST['export']) && $_POST['export'] === 'true') || (isset($_GET['export']) && $_GET['export'] === 'true');

/* ---------- WHERE ---------- */
$where = " WHERE 1=1 ";

if ($role === 'inspector') {
    $where .= " AND lpi.inspector = '" . mysqli_real_escape_string($conn, $username) . "'";
}

$dateField = "COALESCE(NULLIF(lpi.inspection_date, '0000-00-00'), NULLIF(DATE(lpi.created_at), '0000-00-00'))";

if (!empty($filterDateFrom)) {
    $where .= " AND $dateField >= '" . mysqli_real_escape_string($conn, $filterDateFrom) . "' ";
}
if (!empty($filterDateTo)) {
    $where .= " AND $dateField <= '" . mysqli_real_escape_string($conn, $filterDateTo) . "' ";
}
if (empty($filterDateFrom) && empty($filterDateTo) && !empty($filterDate)) {
    $where .= " AND $dateField = '" . mysqli_real_escape_string($conn, $filterDate) . "' ";
}

if (!empty($filterInspector)) {
    $where .= " AND lpi.inspector = '" . mysqli_real_escape_string($conn, $filterInspector) . "' ";
}
if (!empty($filterClient)) {
    $where .= " AND lpi.customer_name = '" . mysqli_real_escape_string($conn, $filterClient) . "' ";
}
if (!empty($filterYear)) {
    $where .= " AND YEAR($dateField) = '" . mysqli_real_escape_string($conn, $filterYear) . "' ";
}

if ($filterStatus === 'Active') {
    $where .= " AND (lpi.next_inspection_date >= CURDATE() OR lpi.next_inspection_date IS NULL OR lpi.next_inspection_date = '0000-00-00')";
} elseif ($filterStatus === 'Expired') {
    $where .= " AND lpi.next_inspection_date < CURDATE() AND lpi.next_inspection_date != '0000-00-00' AND lpi.next_inspection_date IS NOT NULL";
}

if ($search !== "") {
    $safe = mysqli_real_escape_string($conn, $search);
    $keywords = explode(" ", $safe);
    foreach ($keywords as $word) {
        $where .= " AND (
            lpi.project_no LIKE '%$word%' OR
            lpi.certificate_no LIKE '%$word%' OR
            lpi.description LIKE '%$word%' OR
            lpi.item_checked LIKE '%$word%' OR
            lpi.inspector LIKE '%$word%' OR
            lpi.customer_name LIKE '%$word%' OR
            lpi.location LIKE '%$word%' OR
            DATE_FORMAT($dateField,'%d-%m-%Y') LIKE '%$word%' OR
            DATE_FORMAT($dateField,'%Y-%m-%d') LIKE '%$word%'
        )";
    }
}

/* ---------- KPI ---------- */
$kpiQuery = "
SELECT
    COUNT(*) total,
    SUM(CASE WHEN next_inspection_date >= CURDATE() OR next_inspection_date IS NULL OR next_inspection_date = '0000-00-00' THEN 1 ELSE 0 END) active,
    SUM(CASE WHEN next_inspection_date < CURDATE() AND next_inspection_date != '0000-00-00' AND next_inspection_date IS NOT NULL THEN 1 ELSE 0 END) expired
FROM liquid_penetrant_inspection lpi
$where";
$kpi = $conn->query($kpiQuery)->fetch_assoc();

/* ---------- DATA COUNT ---------- */
$totalRecordsQuery = "SELECT COUNT(*) cnt FROM liquid_penetrant_inspection lpi";
$totalRecords = $conn->query($totalRecordsQuery)->fetch_assoc()['cnt'];

$filteredRecordsQuery = "SELECT COUNT(*) cnt FROM liquid_penetrant_inspection lpi $where";
$filteredRecords = $conn->query($filteredRecordsQuery)->fetch_assoc()['cnt'];

/* ---------- SORTING ---------- */
$orderColumnIndex = intval($_POST['order'][0]['column'] ?? 0);
$orderDir = (($_POST['order'][0]['dir'] ?? 'desc') === 'asc') ? 'ASC' : 'DESC';

$columns = [
    0 => 'CAST(SUBSTRING(lpi.project_no, 5) AS UNSIGNED)',
    1 => 'lpi.certificate_no',
    2 => 'lpi.description',
    3 => 'lpi.item_checked',
    4 => 'lpi.inspector',
    5 => 'lpi.customer_name',
    6 => 'lpi.location',
    7 => $dateField,
    8 => 'lpi.next_inspection_date'
];
$orderCol = $columns[$orderColumnIndex] ?? 'CAST(SUBSTRING(lpi.project_no, 5) AS UNSIGNED)';

/* ---------- DATA ---------- */
$sql = "
SELECT
    lpi.*,
    pi.project_status
FROM liquid_penetrant_inspection lpi
LEFT JOIN project_info pi ON pi.project_no = lpi.project_no
$where
ORDER BY $orderCol $orderDir
";

if (!$isExport) {
    $sql .= " LIMIT $start, $length";
}

$res = $conn->query($sql);

if (!$res) {
    if ($isExport) { die('Query Error: ' . $conn->error); }
    header('Content-Type: application/json');
    echo json_encode([
        'draw' => $draw,
        'recordsTotal' => 0,
        'recordsFiltered' => 0,
        'kpi' => $kpi,
        'data' => [],
        'error' => $conn->error
    ]);
    exit;
}

/* ---------- EXPORT CSV ---------- */
if ($isExport) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="lpi_certificates_'.date('Ymd_His').'.csv"');
    $out = fopen('php://output','w');
    fputcsv($out, ['Project No','Certificate No','Description','Item Checked','Inspector','Client','Location','Inspection Date','Status']);
    while ($r = $res->fetch_assoc()) {
        $rawDate = (!empty($r['inspection_date']) && $r['inspection_date'] !== '0000-00-00') ? $r['inspection_date'] : ($r['created_at'] ?? '');
        $dispDate = (!empty($rawDate) && $rawDate !== '0000-00-00') ? date('d-m-Y', strtotime($rawDate)) : '-';

        $status = 'Active';
        if (!empty($r['next_inspection_date']) && $r['next_inspection_date'] != '0000-00-00' && strtotime($r['next_inspection_date']) < time()) {
            $status = 'Expired';
        }

        fputcsv($out, [
            $r['project_no'],
            $r['certificate_no'],
            $r['description'],
            $r['item_checked'],
            $r['inspector'],
            $r['customer_name'],
            $r['location'],
            $dispDate,
            $status
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

    $rawDate = (!empty($r['inspection_date']) && $r['inspection_date'] !== '0000-00-00') ? $r['inspection_date'] : ($r['created_at'] ?? '');
    $dispDate = (!empty($rawDate) && $rawDate !== '0000-00-00') ? date('d-m-Y', strtotime($rawDate)) : '-';

    /* Action buttons */
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
        "project_no"     => $r['project_no'],
        "certificate_no" => $r['certificate_no'],
        "description"    => $r['description'],
        "item_checked"   => $r['item_checked'],
        "inspector"      => '
            <div style="display:flex; gap:8px; align-items:center;">
                <div class="avatar-circle" style="background-color: ' . getInitialColor($r['inspector']) . ';">
                    ' . $initial . '
                </div>
                <span>' . htmlspecialchars($inspectorName) . '</span>
            </div>',
        "customer_name"   => $r['customer_name'],
        "location"        => $r['location'],
        "inspection_date" => $dispDate,
        "status"          => "<span class='status-badge $badgeClass'>$status</span>",
        "actions"         => $actions
    ];
}

/* ---------- RESPONSE ---------- */
echo json_encode([
    "draw" => $draw,
    "recordsTotal" => (int)$totalRecords,
    "recordsFiltered" => (int)$filteredRecords,
    "kpi" => [
        "total"   => (int)$kpi['total'],
        "active"  => (int)$kpi['active'],
        "expired" => (int)$kpi['expired']
    ],
    "data" => $data
]);
?>