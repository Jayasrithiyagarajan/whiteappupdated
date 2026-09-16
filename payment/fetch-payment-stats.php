<?php
header('Content-Type: application/json');
session_start();
include_once('../file/config.php');

$user_role = $_SESSION['role'] ?? '';
$user_name = $_SESSION['username'] ?? '';

if (!in_array($user_role, ['admin', 'inspector'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$filter_year = trim($_REQUEST['filter_year'] ?? '2026');

$whereConditions = [];
if ($user_role === 'inspector') {
    $whereConditions[] = "inspector_name = '" . $conn->real_escape_string($user_name) . "'";
    $whereConditions[] = "LOWER(payment_mode) = 'cash'";
}

if ($filter_year !== '' && $filter_year !== 'all') {
    $fy = $conn->real_escape_string($filter_year);
    $whereConditions[] = "creation_date >= '$fy-01-01 00:00:00' AND creation_date <= '$fy-12-31 23:59:59'";
}

$baseWhere = (count($whereConditions) > 0) ? " WHERE " . implode(" AND ", $whereConditions) : "";

$totalProjects = $conn->query("SELECT COUNT(*) as cnt FROM project_info $baseWhere")->fetch_assoc()['cnt'] ?? 0;

if ($user_role === 'inspector') {
    $creditProjects = 0;
    $cashProjects = $totalProjects;
} else {
    $creditConds = $whereConditions;
    $creditConds[] = "LOWER(payment_mode) = 'credit'";
    $creditWhere = " WHERE " . implode(" AND ", $creditConds);
    $creditProjects = $conn->query("SELECT COUNT(*) as cnt FROM project_info $creditWhere")->fetch_assoc()['cnt'] ?? 0;

    $cashConds = $whereConditions;
    $cashConds[] = "LOWER(payment_mode) = 'cash'";
    $cashWhere = " WHERE " . implode(" AND ", $cashConds);
    $cashProjects = $conn->query("SELECT COUNT(*) as cnt FROM project_info $cashWhere")->fetch_assoc()['cnt'] ?? 0;
}

echo json_encode([
    'status' => 'success',
    'total'  => number_format($totalProjects),
    'credit' => number_format($creditProjects),
    'cash'   => number_format($cashProjects)
]);

