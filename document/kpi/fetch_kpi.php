<?php
session_start();
include_once('../../file/config.php');

if (strtolower($_SESSION['role'] ?? '') !== 'admin') {
    http_response_code(403);
    echo json_encode([
        "draw" => intval($_POST['draw'] ?? 0),
        "recordsTotal" => 0,
        "recordsFiltered" => 0,
        "data" => []
    ]);
    exit;
}

$user = $_SESSION['username'];
$role = $_SESSION['role'];

// DataTables parameters
$draw   = intval($_POST['draw'] ?? 0);
$start  = intval($_POST['start'] ?? 0);
$length = intval($_POST['length'] ?? 10);
$search = $_POST['search']['value'] ?? '';

// Filter parameters
$filterInspector = $_POST['filter_inspector'] ?? '';
$filterClient    = $_POST['filter_client'] ?? '';
$filterDateFrom  = $_POST['filter_date_from'] ?? '';
$filterDateTo    = $_POST['filter_date_to'] ?? '';
$filterEquipment = $_POST['filter_equipment'] ?? '';
$filterLocation  = $_POST['filter_location'] ?? '';

// Columns mapping for ordering
$columns = [
    0 => "project_no",
    1 => "creation_date",
    2 => "customer_name",
    3 => "equipment_location",
    4 => "equipment_id",
    5 => "equipment_type",
    6 => "inspector_name",
    7 => "sticker_status",
    8 => "inspection_type"
];

$where = " WHERE 1=1 ";

// Role-based filtering: Inspectors only see their own projects?
// Assuming admins see all, inspectors see theirs. Adjust if needed based on 'fetch_reports.php' logic.
if (trim(strtolower($role)) === 'inspector') {
     $where .= " AND inspector_name='".mysqli_real_escape_string($conn,$user)."' ";
}

// Apply Filters
if (!empty($filterInspector)) {
    $where .= " AND inspector_name LIKE '%".mysqli_real_escape_string($conn, $filterInspector)."%' ";
}
if (!empty($filterClient)) {
    $where .= " AND customer_name LIKE '%".mysqli_real_escape_string($conn, $filterClient)."%' ";
}
if (!empty($filterDateFrom)) {
    $where .= " AND DATE(creation_date) >= '".mysqli_real_escape_string($conn, $filterDateFrom)."' ";
}
if (!empty($filterDateTo)) {
    $where .= " AND DATE(creation_date) <= '".mysqli_real_escape_string($conn, $filterDateTo)."' ";
}
if (!empty($filterEquipment)) {
    $where .= " AND equipment_id LIKE '%".mysqli_real_escape_string($conn, $filterEquipment)."%' ";
}
if (!empty($filterLocation)) {
    $where .= " AND equipment_location LIKE '%".mysqli_real_escape_string($conn, $filterLocation)."%' ";
}

// Global Search
if (!empty($search)) {
    $search = mysqli_real_escape_string($conn, $search);
    $where .= " AND (
        project_no LIKE '%$search%' OR
        customer_name LIKE '%$search%' OR
        inspector_name LIKE '%$search%' OR
        equipment_id LIKE '%$search%' OR
        equipment_location LIKE '%$search%' OR
        equipment_type LIKE '%$search%'
    ) ";
}

// Total records (without filters)
$totalSql = "SELECT COUNT(*) as total FROM project_info";
// If role restriction applies, add it to total count too
if (trim(strtolower($role)) === 'inspector') {
     $totalSql .= " WHERE inspector_name='".mysqli_real_escape_string($conn,$user)."'";
}

$totalResult = $conn->query($totalSql);
$recordsTotal = $totalResult->fetch_assoc()['total'] ?? 0;

// Filtered records
$filteredSql = "SELECT COUNT(*) as total FROM project_info $where";
$filteredResult = $conn->query($filteredSql);
$recordsFiltered = $filteredResult->fetch_assoc()['total'] ?? 0;

// Ordering
$orderColumnIndex = $_POST['order'][0]['column'] ?? 0;
// Default to project_no desc if index 0 or not found
$orderColumn = $columns[$orderColumnIndex] ?? "project_no";
$orderDir = $_POST['order'][0]['dir'] ?? 'desc';

// Special sorting for Project No if it has text prefix (e.g. CIMS001)
// Attempt to sort by numeric part if using "project_no"
if ($orderColumn === 'project_no') {
     $orderBy = " ORDER BY CAST(SUBSTRING(project_no, 5) AS UNSIGNED) $orderDir ";
} else {
     $orderBy = " ORDER BY $orderColumn $orderDir ";
}

// Fetch Data
$sql = "SELECT * FROM project_info $where $orderBy LIMIT $start, $length";
$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    
    // Format Date
    $date = date('d-m-Y', strtotime($row['creation_date']));

    // Status Badge (Sticker Status)
    $stickerStatus = $row['sticker_status'];
    $stickerBadge = ($stickerStatus == 'Yes') 
        ? '<span class="badge badge-success">Yes</span>' 
        : '<span class="badge badge-warning">No</span>';

    $data[] = [
        $row['project_no'],
        $date,
        $row['customer_name'],
        $row['equipment_location'],
        $row['equipment_id'],
        $row['equipment_type'],
        $row['inspector_name'],
        $stickerBadge,
        $row['inspection_type']
    ];
}

echo json_encode([
    "draw" => $draw,
    "recordsTotal" => $recordsTotal,
    "recordsFiltered" => $recordsFiltered,
    "data" => $data
]);
?>
