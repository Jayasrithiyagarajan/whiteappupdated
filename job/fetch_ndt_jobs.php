<?php
session_start();
include_once('../file/config.php');

$user = $_SESSION['username'];
$role = $_SESSION['role'];

// DataTables parameters
$draw   = intval($_POST['draw'] ?? 0);
$start  = intval($_POST['start'] ?? 0);
$length = intval($_POST['length'] ?? 10);
$search = $_POST['search']['value'] ?? '';

// Filter parameters
$statusFilter    = $_POST['status_filter'] ?? '';
$inspectorFilter = $_POST['filter_inspector'] ?? '';
$clientFilter    = $_POST['filter_client'] ?? '';
$dateFrom        = $_POST['filter_date_from'] ?? '';
$dateTo          = $_POST['filter_date_to'] ?? '';
$yearFilter      = $_POST['filter_year'] ?? '';
$expiryFilter    = $_POST['filter_expiry_status'] ?? '';

// Columns mapping
$columns = [
    0 => "p.project_no",
    1 => "p.creation_date",
    2 => "p.checklist_status",
    3 => "p.report_status",
    4 => "p.review_status",
    5 => "p.certificatestatus",
    6 => "p.customer_name",
    7 => "p.project_status",
    8 => "p.checklist_type",
    9 => "p.equipment_type",
    10 => "p.equipment_id",
    11 => "p.equipment_location",
    12 => "p.inspector_name",
    13 => "p.project_no"
];

// Base Condition
$where = " WHERE p.equipment_type = 'NDT Equipment' ";
$params = [];
$types = "";

// Role-based filtering
if ($role === 'customer') {
    $where .= " AND p.customer_name = ? ";
    $params[] = $user;
    $types .= "s";
} elseif (!in_array($role, ['admin','reviewer','quality controller','document controller'])) {
    $where .= " AND p.inspector_name = ? ";
    $params[] = $user;
    $types .= "s";
}

// Apply Filters
if (!empty($inspectorFilter)) {
    $where .= " AND p.inspector_name = ? ";
    $params[] = $inspectorFilter;
    $types .= "s";
}
if (!empty($clientFilter)) {
    $where .= " AND p.customer_name = ? ";
    $params[] = $clientFilter;
    $types .= "s";
}
if (!empty($dateFrom)) {
    $where .= " AND DATE(p.creation_date) >= ? ";
    $params[] = $dateFrom;
    $types .= "s";
}
if (!empty($dateTo)) {
    $where .= " AND DATE(p.creation_date) <= ? ";
    $params[] = $dateTo;
    $types .= "s";
}
if (!empty($yearFilter)) {
    $where .= " AND YEAR(p.creation_date) = ? ";
    $params[] = $yearFilter;
    $types .= "s";
}
if (!empty($expiryFilter)) {
    if ($expiryFilter === 'Expired') {
        $where .= " AND (SELECT MAX(next_inspection_due_date) FROM reports r WHERE r.project_no = p.project_no) < CURDATE() ";
    } elseif ($expiryFilter === 'Active') {
        $where .= " AND ((SELECT MAX(next_inspection_due_date) FROM reports r WHERE r.project_no = p.project_no) >= CURDATE() 
                    OR (SELECT MAX(next_inspection_due_date) FROM reports r WHERE r.project_no = p.project_no) IS NULL) ";
    }
}
if (!empty($statusFilter)) {
    $where .= " AND p.project_status = ? ";
    $params[] = $statusFilter;
    $types .= "s";
}

// Global Search
if (!empty($search)) {
    $searchWildcard = "%{$search}%";
    $where .= " AND (
        p.project_no LIKE ? OR p.customer_name LIKE ? OR p.inspector_name LIKE ? OR 
        p.equipment_id LIKE ? OR p.equipment_location LIKE ? OR p.checklist_type LIKE ? OR 
        p.project_status LIKE ? OR DATE_FORMAT(p.creation_date, '%d-%m-%Y') LIKE ?
    ) ";
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $types .= "ssssssss";
}

// Total Records
$totalSql = "SELECT COUNT(*) as total FROM project_info p WHERE p.equipment_type = 'NDT Equipment'";
$totalParams = []; $totalTypes = "";
if ($role === 'customer') {
    $totalSql .= " AND p.customer_name = ? "; $totalParams[] = $user; $totalTypes .= "s";
} elseif (!in_array($role, ['admin','reviewer','quality controller','document controller'])) {
    $totalSql .= " AND p.inspector_name = ? "; $totalParams[] = $user; $totalTypes .= "s";
}

$totalStmt = $conn->prepare($totalSql);
if(!empty($totalParams)) $totalStmt->bind_param($totalTypes, ...$totalParams);
$totalStmt->execute();
$recordsTotal = $totalStmt->get_result()->fetch_assoc()['total'] ?? 0;
$totalStmt->close();

// Filtered Records
$filteredSql = "SELECT COUNT(*) as total FROM project_info p $where";
$filteredStmt = $conn->prepare($filteredSql);
if(!empty($params)) $filteredStmt->bind_param($types, ...$params);
$filteredStmt->execute();
$recordsFiltered = $filteredStmt->get_result()->fetch_assoc()['total'] ?? 0;
$filteredStmt->close();

// Ordering
$orderColumnIndex = $_POST['order'][0]['column'] ?? 0;
$orderColumn = $columns[$orderColumnIndex] ?? "p.creation_date";
$orderDir = $_POST['order'][0]['dir'] ?? 'desc';
$orderBy = " ORDER BY $orderColumn $orderDir ";

// Fetch Data
$sql = "SELECT p.*, 
       (SELECT MAX(next_inspection_due_date) FROM reports r WHERE r.project_no = p.project_no) as due_date
       FROM project_info p 
       $where 
       $orderBy 
       LIMIT ?, ?";
$params[] = $start;
$params[] = $length;
$types .= "ii";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

$data = [];

// ==================== STATUS BADGE FUNCTION (Consistent with other pages) ====================
function getStatusBadge($status) {
    if (empty($status)) {
        return '<span class="status-badge pending">Pending</span>';
    }

    $originalStatus = trim($status);
    $statusLower = strtolower($originalStatus);
    $class = 'pending';

    if (in_array($statusLower, ['completed', 'active', 'approved', 'finished', 'pass', 'created', 'generated', 'certificate created'])) {
        $class = 'badge-success';
    } elseif (in_array($statusLower, ['expired', 'rejected', 'cancelled', 'fail'])) {
        $class = 'badge-danger';
    } elseif (in_array($statusLower, ['in-progress', 'review', 'under-review', 'ongoing', 'processing'])) {
        $class = 'badge-warning';   // or you can use a custom class if needed
    } elseif ($statusLower === 'draft') {
        $class = 'pending';
    }

    return '<span class="status-badge ' . $class . '">' . htmlspecialchars($originalStatus) . '</span>';
}

// ==================== BUILD TABLE ROWS ====================
while ($row = $result->fetch_assoc()) {
    
    $projectNo = '<strong>#' . htmlspecialchars($row['project_no']) . '</strong>';
    $date = date('d-m-Y', strtotime($row['creation_date']));
    
    $checklistType = ucwords(str_replace(['-','_'], ' ', $row['checklist_type']));
    $location = ucfirst($row['equipment_location'] ?? '');

    // Avatar for Inspector (Same as Lifting Gear)
    $initial = strtoupper(substr($row['inspector_name'] ?? 'U', 0, 1));
    $initialClass = "initial-" . $initial;
    $inspectorHtml = "
        <div style='display:flex;gap:8px;align-items:center'>
            <div class='avatar-circle $initialClass'>$initial</div>
            " . htmlspecialchars($row['inspector_name']) . "
        </div>";

    $actionBtn = "<a href='job-details.php?id={$row['project_no']}' target='_blank' class='btn btn-sm btn-outline-primary'>View</a>";

    $data[] = [
        $projectNo,
        $date,
        getStatusBadge($row['checklist_status']),
        getStatusBadge($row['report_status']),
        getStatusBadge($row['review_status']),
        getStatusBadge($row['certificatestatus']),
        htmlspecialchars($row['customer_name']),
        getStatusBadge($row['project_status']),
        $actionBtn,
        $checklistType,
        htmlspecialchars($row['equipment_type']),
        htmlspecialchars($row['equipment_id']),
        $location,
        $inspectorHtml
    ];
}

echo json_encode([
    "draw" => $draw,
    "recordsTotal" => $recordsTotal,
    "recordsFiltered" => $recordsFiltered,
    "data" => $data
]);
?>