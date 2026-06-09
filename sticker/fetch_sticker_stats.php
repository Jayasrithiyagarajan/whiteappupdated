<?php
session_start();
include_once('../file/config.php');

$user = $_SESSION['username'];
$role = $_SESSION['role'];

// Get Filters
$filter_inspector = $_POST['filter_inspector'] ?? '';
$filter_date      = $_POST['filter_date'] ?? '';
$filter_project   = $_POST['filter_project'] ?? '';
$filter_expiry    = $_POST['filter_expiry'] ?? '';
$filter_status    = $_POST['filter_status'] ?? '';

// Base WHERE
$where = " WHERE 1=1 ";
$params = [];
$types = "";

// Role Restriction
if ($role === 'inspector') {
    $where .= " AND assign_inspector = ? ";
    $params[] = $user;
    $types .= "s";
}

// Apply Filters
if (!empty($filter_inspector)) {
    $where .= " AND assign_inspector = ? ";
    $params[] = $filter_inspector;
    $types .= "s";
}

if (!empty($filter_date)) {
    $where .= " AND DATE(created_at) = ? ";
    $params[] = $filter_date;
    $types .= "s";
}

if (!empty($filter_project)) {
    $where .= " AND project_no LIKE ? ";
    $params[] = "%$filter_project%";
    $types .= "s";
}

if (!empty($filter_status)) {
    if ($filter_status === 'Unused') {
        $where .= " AND (project_no IS NULL OR project_no = '') ";
    } elseif ($filter_status === 'Passed') {
        $where .= " AND sticker_status = 'Passed' AND (project_no IS NOT NULL AND project_no != '') ";
    } elseif ($filter_status === 'Failed') {
        $where .= " AND sticker_status = 'Failed' AND (project_no IS NOT NULL AND project_no != '') ";
    }
}

function countQuery($conn, $sql, $types, $params) {
    $stmt = $conn->prepare($sql);
    if ($params) $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $cnt = $stmt->get_result()->fetch_assoc()['cnt'] ?? 0;
    $stmt->close();
    return (int)$cnt;
}

// Total
$total = countQuery($conn, "SELECT COUNT(*) AS cnt FROM stickers $where", $types, $params);

// Unused
$unused = countQuery($conn, "SELECT COUNT(*) AS cnt FROM stickers $where AND (project_no IS NULL OR project_no = '')", $types, $params);

// Passed
$passed = countQuery($conn, "SELECT COUNT(*) AS cnt FROM stickers $where AND sticker_status = 'Passed' AND (project_no IS NOT NULL AND project_no != '')", $types, $params);

// Failed
$failed = countQuery($conn, "SELECT COUNT(*) AS cnt FROM stickers $where AND sticker_status = 'Failed' AND (project_no IS NOT NULL AND project_no != '')", $types, $params);

echo json_encode([
    'total'  => $total,
    'unused' => $unused,
    'passed' => $passed,
    'failed' => $failed
]);
?>