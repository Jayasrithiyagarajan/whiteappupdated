<?php
session_start();
include_once('../file/config.php');

$user = $_SESSION['username'];
$role = $_SESSION['role'];

$inspectorFilter = $_POST['filter_inspector'] ?? '';
$clientFilter    = $_POST['filter_client'] ?? '';
$dateFrom        = $_POST['filter_date_from'] ?? '';
$dateTo          = $_POST['filter_date_to'] ?? '';
$yearFilter      = $_POST['filter_year'] ?? '';

// Base Query
$where = " WHERE 1=1 ";
$params = [];
$types = "";

// Role-based filtering
if ($role === 'customer') {
    $where .= " AND customer_name = ? ";
    $params[] = $user;
    $types .= "s";
} elseif (!in_array($role, ['admin','reviewer','quality controller','document controller'])) {
    $where .= " AND inspector_name = ? ";
    $params[] = $user;
    $types .= "s";
}

// --- APPLY FILTERS ---
if (!empty($inspectorFilter)) {
    $where .= " AND inspector_name = ? ";
    $params[] = $inspectorFilter;
    $types .= "s";
}
if (!empty($clientFilter)) {
    $where .= " AND customer_name = ? ";
    $params[] = $clientFilter;
    $types .= "s";
}
if (!empty($dateFrom)) {
    $where .= " AND DATE(creation_date) >= ? ";
    $params[] = $dateFrom;
    $types .= "s";
}
if (!empty($dateTo)) {
    $where .= " AND DATE(creation_date) <= ? ";
    $params[] = $dateTo;
    $types .= "s";
}
if (!empty($yearFilter)) {
    $where .= " AND YEAR(creation_date) = ? ";
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
$sqlActive = "SELECT COUNT(*) as cnt FROM project_info p $where 
              AND ( (SELECT MAX(next_inspection_due_date) FROM reports r WHERE r.project_no = p.project_no) >= CURDATE() 
              OR (SELECT MAX(next_inspection_due_date) FROM reports r WHERE r.project_no = p.project_no) IS NULL )";
$stmt = $conn->prepare($sqlActive);
if($params) $stmt->bind_param($types, ...$params);
$stmt->execute();
$active = $stmt->get_result()->fetch_assoc()['cnt'] ?? 0;
$stmt->close();

// 3. Expired
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
