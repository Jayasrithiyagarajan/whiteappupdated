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

/* ===============================
   DataTables Parameters
================================ */
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

/* ===============================
   WHERE clause
 ================================ */
$where = " WHERE 1=1 ";

if ($role === 'inspector') {
    $where .= " AND rt.inspector = '" . mysqli_real_escape_string($conn, $username) . "'";
}

$dateField = "COALESCE(NULLIF(rt.this_exam_date, '0000-00-00'), NULLIF(rt.inspection_date, '0000-00-00'), NULLIF(DATE(rt.created_at), '0000-00-00'))";

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
    $where .= " AND rt.inspector = '" . mysqli_real_escape_string($conn, $filterInspector) . "' ";
}
if (!empty($filterClient)) {
    $where .= " AND rt.customer_name = '" . mysqli_real_escape_string($conn, $filterClient) . "' ";
}
if (!empty($filterYear)) {
    $where .= " AND YEAR($dateField) = '" . mysqli_real_escape_string($conn, $filterYear) . "' ";
}

if ($filterStatus === 'Active') {
    $where .= " AND (rt.next_exam_date >= CURDATE() OR rt.next_exam_date IS NULL OR rt.next_exam_date = '0000-00-00')";
} elseif ($filterStatus === 'Expired') {
    $where .= " AND rt.next_exam_date < CURDATE() AND rt.next_exam_date != '0000-00-00' AND rt.next_exam_date IS NOT NULL";
}

/* ===============================
   Search (Global)
 ================================ */
if ($search !== "") {
    $safe = mysqli_real_escape_string($conn, $search);
    $keywords = explode(" ", $safe);
    foreach ($keywords as $word) {
        $where .= " AND (
            rt.project_no LIKE '%$word%' OR
            rt.certificate_no LIKE '%$word%' OR
            rt.inspected_item_type LIKE '%$word%' OR
            rt.identification_no LIKE '%$word%' OR
            rt.inspector LIKE '%$word%' OR
            rt.customer_name LIKE '%$word%' OR
            rt.location LIKE '%$word%' OR
            DATE_FORMAT($dateField,'%d-%m-%Y') LIKE '%$word%' OR
            DATE_FORMAT($dateField,'%Y-%m-%d') LIKE '%$word%'
        )";
    }
}

/* ===============================
   KPI COUNTS
 ================================ */
$kpiSql = "
SELECT
    COUNT(*) AS total,
    SUM(CASE WHEN next_exam_date >= CURDATE() OR next_exam_date IS NULL OR next_exam_date = '0000-00-00' THEN 1 ELSE 0 END) AS active,
    SUM(CASE WHEN next_exam_date < CURDATE() AND next_exam_date != '0000-00-00' AND next_exam_date IS NOT NULL THEN 1 ELSE 0 END) AS expired
FROM rocking_test_certificate rt
$where
";
$kpiRow = $conn->query($kpiSql)->fetch_assoc();

/* ===============================
   Total Records
 ================================ */
$totalRecordsQuery = "SELECT COUNT(*) AS cnt FROM rocking_test_certificate rt";
$totalRecords = $conn->query($totalRecordsQuery)->fetch_assoc()['cnt'];

$filteredRecordsQuery = "SELECT COUNT(*) AS cnt FROM rocking_test_certificate rt $where";
$filteredRecords = $conn->query($filteredRecordsQuery)->fetch_assoc()['cnt'];

/* ===============================
   Column mapping & Sorting
 ================================ */
$orderColumnIndex = intval($_POST['order'][0]['column'] ?? 0);
$orderDir         = (($_POST['order'][0]['dir'] ?? 'desc') === 'asc') ? 'ASC' : 'DESC';

$columns = [
    0 => 'CAST(SUBSTRING(rt.project_no, 5) AS UNSIGNED)',
    1 => 'rt.certificate_no',
    2 => 'rt.inspected_item_type',
    3 => 'rt.identification_no',
    4 => 'rt.inspector',
    5 => 'rt.customer_name',
    6 => 'rt.location',
    7 => $dateField,
    8 => 'rt.next_exam_date'
];

$orderColumn = $columns[$orderColumnIndex] ?? 'CAST(SUBSTRING(rt.project_no, 5) AS UNSIGNED)';

/* ===============================
   Main Data Query
 ================================ */
$dataSql = "
SELECT
    rt.*,
    pi.project_status
FROM rocking_test_certificate rt
LEFT JOIN project_info pi ON pi.project_no = rt.project_no
$where
ORDER BY $orderColumn $orderDir
";

if (!$isExport) {
    $dataSql .= " LIMIT $start, $length";
}

$result = $conn->query($dataSql);

if (!$result) {
    if ($isExport) { die('Query Error: ' . $conn->error); }
    header('Content-Type: application/json');
    echo json_encode([
        'draw' => $draw,
        'recordsTotal' => 0,
        'recordsFiltered' => 0,
        'kpi' => $kpiRow,
        'data' => [],
        'error' => $conn->error
    ]);
    exit;
}

/* ===============================
   Export CSV
 ================================ */
if ($isExport) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="rocking_test_certificates_'.date('Ymd_His').'.csv"');
    $out = fopen('php://output','w');
    fputcsv($out, ['Project No','Certificate No','Item Type','Identification No','Inspector','Client','Location','Inspection Date','Status']);
    while ($row = $result->fetch_assoc()) {
        $rawDate = (!empty($row['this_exam_date']) && $row['this_exam_date'] !== '0000-00-00') ? $row['this_exam_date'] : ((!empty($row['inspection_date']) && $row['inspection_date'] !== '0000-00-00') ? $row['inspection_date'] : ($row['created_at'] ?? ''));
        $dispDate = (!empty($rawDate) && $rawDate !== '0000-00-00') ? date('d-m-Y', strtotime($rawDate)) : '-';

        $status = 'Active';
        if (!empty($row['next_exam_date']) && $row['next_exam_date'] != '0000-00-00' && strtotime($row['next_exam_date']) < time()) {
            $status = 'Expired';
        }

        fputcsv($out, [
            $row['project_no'],
            $row['certificate_no'],
            $row['inspected_item_type'],
            $row['identification_no'],
            $row['inspector'],
            $row['customer_name'],
            $row['location'],
            $dispDate,
            $status
        ]);
    }
    fclose($out);
    exit;
}

$data = [];

while ($row = $result->fetch_assoc()) {

    $inspectorName = trim($row['inspector'] ?? '');
    $initial = !empty($inspectorName) ? strtoupper(substr($inspectorName, 0, 1)) : 'U';

    $status = 'Active';
    $badgeClass = 'badge-success';
    
    if (!empty($row['next_exam_date']) && $row['next_exam_date'] != '0000-00-00' && strtotime($row['next_exam_date']) < time()) {
        $status = 'Expired';
        $badgeClass = 'badge-danger';
    }

    $rawDate = (!empty($row['this_exam_date']) && $row['this_exam_date'] !== '0000-00-00') ? $row['this_exam_date'] : ((!empty($row['inspection_date']) && $row['inspection_date'] !== '0000-00-00') ? $row['inspection_date'] : ($row['created_at'] ?? ''));
    $dispDate = (!empty($rawDate) && $rawDate !== '0000-00-00') ? date('d-m-Y', strtotime($rawDate)) : '-';

    /* Action buttons */
    $actions = '
        <div class="action-icons">
            <a href="view.php?project_no='.$row['project_no'].'" class="view-icon" title="View" target="_blank">
                <i class="fa fa-eye"></i>
            </a>
            <a href="download.php?project_no='.$row['project_no'].'" class="download-icon" title="Download">
                <i class="fa fa-download"></i>
            </a>
    ';

    if (($role === 'document controller' || $role === 'inspector' || $role === 'admin') && $row['project_status'] !== 'Completed') {
        $actions .= '
            <a href="edit.php?project_no='.$row['project_no'].'" class="edit-icon" title="Edit" style="color: #b45309; background: #fef3c7;">
                <i class="fa fa-edit"></i>
            </a>
        ';
    }
    
    if ($row['project_status'] !== 'Completed') {
        $actions .= '
                <a href="javascript:void(0)" class="delete-icon" onclick="deleteRow(\''.$row['project_no'].'\')" title="Delete" style="color: #e11d48; background: #ffe4e6;">
                    <i class="fa fa-trash"></i>
                </a>';
    }
    $actions .= '
        </div>
    ';

    $data[] = [
        "project_no"            => $row['project_no'],
        "certificate_no"        => $row['certificate_no'],
        "inspected_item_type"   => $row['inspected_item_type'],
        "identification_no"     => $row['identification_no'],
        "inspector"             => '
            <div style="display:flex; gap:8px; align-items:center;">
                <div class="avatar-circle" style="background-color: ' . getInitialColor($row['inspector']) . ';">
                    ' . $initial . '
                </div>
                <span>' . htmlspecialchars($inspectorName) . '</span>
            </div>',
        "customer_name"         => $row['customer_name'],
        "location"              => $row['location'],
        "this_exam_date"        => $dispDate,
        "status"                => "<span class='status-badge $badgeClass'>$status</span>",
        "action"                => $actions
    ];
}

/* ===============================
   Final JSON Response
 ================================ */
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