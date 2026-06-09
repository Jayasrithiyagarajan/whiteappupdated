<?php
session_start();
include_once('../file/config.php');

$user = $_SESSION['username'] ?? null;
$role = $_SESSION['role'] ?? null;

if (!$user) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$draw   = intval($_POST['draw'] ?? 0);
$start  = intval($_POST['start'] ?? 0);
$length = intval($_POST['length'] ?? 10);
$search = $_POST['search']['value'] ?? '';

/* Filters */
$filterDate = $_POST['filter_date'] ?? '';
$filterLocation = $_POST['filter_location'] ?? '';
$filterStatus = $_POST['filter_status'] ?? '';

$columns = [
    "oa.assessment_no",
    "oa.date",
    "oa.operator_name",
    "oa.location",
    "oa.status",
    "oa.exam_status",
    "oa.signals_status",
    "oa.id" // Actions
];

$where = " WHERE 1 ";

// Role access control: If exact inspector
if ($role === 'inspector') {
    $where .= " AND oa.inspector_id = (SELECT user_id FROM new_users WHERE username = '".mysqli_real_escape_string($conn, $user)."') ";
}

/* Apply filters */
if (!empty($filterDate)) {
    $where .= " AND oa.date = '".mysqli_real_escape_string($conn, $filterDate)."' ";
}
if (!empty($filterLocation)) {
    $where .= " AND oa.location LIKE '%".mysqli_real_escape_string($conn, $filterLocation)."%' ";
}
if (!empty($filterStatus)) {
    $where .= " AND oa.status = '".mysqli_real_escape_string($conn, $filterStatus)."' ";
}

/* Global search */
if ($search !== '') {
    $searchStr = mysqli_real_escape_string($conn, $search);
    $where .= " AND (
        oa.assessment_no LIKE '%$searchStr%' OR
        oa.operator_name LIKE '%$searchStr%' OR
        oa.operator_id_passport LIKE '%$searchStr%' OR
        oa.location LIKE '%$searchStr%'
    )";
}

/* Get total counts */
$totalWhere = " WHERE 1 ";
if ($role === 'inspector') {
    $totalWhere .= " AND inspector_id = (SELECT user_id FROM new_users WHERE username = '".mysqli_real_escape_string($conn, $user)."') ";
}
$totalSql = "SELECT COUNT(*) as total FROM operator_assessments oa " . $totalWhere;
$totalRes = $conn->query($totalSql)->fetch_assoc()['total'];

$filteredSql = "SELECT COUNT(*) as total FROM operator_assessments oa $where";
$filteredRes = $conn->query($filteredSql)->fetch_assoc()['total'];

/* Ordering */
$idx = $_POST['order'][0]['column'] ?? 0;
$dir = ($_POST['order'][0]['dir'] ?? 'desc') === 'asc' ? 'ASC' : 'DESC';
$orderCol = $columns[$idx] ?? 'oa.id';

/* Fetch Data */
$sql = "SELECT oa.id, oa.assessment_no, oa.date, oa.operator_name, oa.location, oa.status, oa.exam_status, oa.signals_status 
        FROM operator_assessments oa 
        $where 
        ORDER BY $orderCol $dir 
        LIMIT $start, $length";

$res = $conn->query($sql);
$data = [];

function getStatusBadge($status) {
    if ($status === null || $status === '') return "<span class='badge badge-secondary'>N/A</span>";
    if ($status === 'COMPLETED' || $status === 'PASSED') return "<span class='badge badge-success'>$status</span>";
    if ($status === 'IN_PROGRESS') return "<span class='badge badge-warning'>$status</span>";
    if ($status === 'FAILED') return "<span class='badge badge-danger'>$status</span>";
    if ($status === 'PENDING') return "<span class='badge badge-info'>$status</span>";
    return "<span class='badge badge-secondary'>$status</span>";
}

while ($r = $res->fetch_assoc()) {
    $data[] = [
        "<strong>" . htmlspecialchars((string)$r['assessment_no']) . "</strong>",
        $r['date'] ? date('d-M-Y', strtotime($r['date'])) : "N/A",
        htmlspecialchars((string)$r['operator_name']),
        htmlspecialchars((string)$r['location']),
        getStatusBadge($r['status']),
        getStatusBadge($r['exam_status']),
        getStatusBadge($r['signals_status']),
        "<a href='view-assessment1.php?id={$r['id']}' class='btn btn-sm btn-info' target='_blank'><i class='fas fa-eye'></i></a>"
    ]; // I'll use view-assessment1.php as action. 
}

echo json_encode([
    "draw" => $draw,
    "recordsTotal" => $totalRes,
    "recordsFiltered" => $filteredRes,
    "data" => $data
]);
