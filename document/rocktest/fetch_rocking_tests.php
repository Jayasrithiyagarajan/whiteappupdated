<?php
session_start();
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

/* ===============================
   DataTables Parameters
================================ */
$draw   = intval($_POST['draw'] ?? 1);
$start  = intval($_POST['start'] ?? 0);
$length = intval($_POST['length'] ?? 10);
$search = $_POST['search']['value'] ?? '';

$orderColumnIndex = $_POST['order'][0]['column'] ?? 7;
$orderDir         = $_POST['order'][0]['dir'] ?? 'desc';

/* ===============================
   Column mapping (IMPORTANT)
================================ */
$columns = [
    0 => 'rt.project_no',
    1 => 'rt.certificate_no',
    2 => 'rt.inspected_item_type',
    3 => 'rt.identification_no',
    4 => 'rt.inspector',
    5 => 'rt.customer_name',
    6 => 'rt.location',
    7 => 'rt.this_exam_date'
];

$orderColumn = $columns[$orderColumnIndex] ?? 'rt.this_exam_date';

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
    $where .= " AND rt.inspector = '" . $conn->real_escape_string($username) . "'";
}

if (!empty($filterInspector)) {
    $where .= " AND rt.inspector = '" . $conn->real_escape_string($filterInspector) . "'";
}
if (!empty($filterDate)) {
    $where .= " AND DATE(rt.this_exam_date) = '" . $conn->real_escape_string($filterDate) . "'";
}
if (!empty($filterClient)) {
    $where .= " AND rt.customer_name = '" . $conn->real_escape_string($filterClient) . "'";
}
if (!empty($filterYear)) {
    $where .= " AND YEAR(rt.this_exam_date) = '" . $conn->real_escape_string($filterYear) . "'";
}

if ($filterStatus === 'Active') {
    $where .= " AND (rt.next_exam_date >= CURDATE() OR rt.next_exam_date IS NULL OR rt.next_exam_date = '0000-00-00')";
} elseif ($filterStatus === 'Expired') {
    $where .= " AND rt.next_exam_date < CURDATE() AND rt.next_exam_date != '0000-00-00' AND rt.next_exam_date IS NOT NULL";
}

/* ===============================
   Search (Global)
 ================================ */
if (!empty($search)) {
    $safe = $conn->real_escape_string($search);
    $where .= " AND (
        rt.project_no LIKE '%$safe%' OR
        rt.certificate_no LIKE '%$safe%' OR
        rt.inspected_item_type LIKE '%$safe%' OR
        rt.identification_no LIKE '%$safe%' OR
        rt.inspector LIKE '%$safe%' OR
        rt.customer_name LIKE '%$safe%' OR
        rt.location LIKE '%$safe%' OR
        DATE_FORMAT(rt.this_exam_date,'%Y-%m-%d') LIKE '%$safe%'
    )";
}

/* ===============================
   KPI COUNTS (FAST & OPTIMIZED)
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
LIMIT $start, $length
";

$result = $conn->query($dataSql);

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

    /* Action buttons */
    $actions = '
        <div class="action-icons">
            <a href="view.php?project_no='.$row['project_no'].'" class="view-icon" title="View">
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
    
    $actions .= '
            <a href="javascript:void(0)" class="delete-icon" onclick="deleteRow(\''.$row['project_no'].'\')" title="Delete" style="color: #e11d48; background: #ffe4e6;">
                <i class="fa fa-trash"></i>
            </a>
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
        "this_exam_date"        => date('d-m-Y', strtotime($row['this_exam_date'])),
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
