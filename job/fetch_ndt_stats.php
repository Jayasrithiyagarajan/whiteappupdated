<?php
session_start();
include_once('../file/config.php');

$user = $_SESSION['username'];
$role = $_SESSION['role'];

// Filter parameters (To update stats based on applied filters?)
// User request: "Counts of TOTAL, ACTIVE, EXPIRED"
// Usually stats cards show the global state or filtered state?
// Assuming filtered state because user said "FILTER BY..."
// But often "Total" means *all* relevant to user. "Active/Expired" might respect filters or be the big numbers.
// Let's make them respect the filters *except* the status filter itself to allow toggling.
// Actually, standard dashboards often show global stats at top.
// However, if I filter by "Client A", I want to see how many active/expired jobs Client A has.
// So I will apply filters.

$inspectorFilter = $_POST['filter_inspector'] ?? '';
$clientFilter    = $_POST['filter_client'] ?? '';
$dateFrom        = $_POST['filter_date_from'] ?? '';
$dateTo          = $_POST['filter_date_to'] ?? '';
$yearFilter      = $_POST['filter_year'] ?? '';
// Note: We do NOT apply 'status' or 'expiry' filters to the stats themselves usually, 
// because the stats *are* the breakdown of those statuses.
// But we DO apply Inspector, Client, Date, Year.

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

// --- APPLY FILTERS ---
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

// 1. Total
$sqlTotal = "SELECT COUNT(*) as cnt FROM project_info p $where";
$stmt = $conn->prepare($sqlTotal);
if($params) $stmt->bind_param($types, ...$params);
$stmt->execute();
$total = $stmt->get_result()->fetch_assoc()['cnt'] ?? 0;
$stmt->close();

// 2. Active
// Active = Max Due Date >= CURDATE OR No Due Date (Pending)
// Using subquery logic
$sqlActive = "SELECT COUNT(*) as cnt FROM project_info p $where 
              AND ( (SELECT MAX(next_inspection_due_date) FROM reports r WHERE r.project_no = p.project_no) >= CURDATE() 
              OR (SELECT MAX(next_inspection_due_date) FROM reports r WHERE r.project_no = p.project_no) IS NULL )";
$stmt = $conn->prepare($sqlActive);
if($params) $stmt->bind_param($types, ...$params);
$stmt->execute();
$active = $stmt->get_result()->fetch_assoc()['cnt'] ?? 0;
$stmt->close();

// 3. Expired
// Expired = Max Due Date < CURDATE
$sqlExpired = "SELECT COUNT(*) as cnt FROM project_info p $where 
               AND (SELECT MAX(next_inspection_due_date) FROM reports r WHERE r.project_no = p.project_no) < CURDATE()";
$stmt = $conn->prepare($sqlExpired);
if($params) $stmt->bind_param($types, ...$params);
$stmt->execute();
$expired = $stmt->get_result()->fetch_assoc()['cnt'] ?? 0;
$stmt->close();

echo json_encode([
    'total' => $total,
    'active' => $active,
    'expired' => $expired
]);
?>
