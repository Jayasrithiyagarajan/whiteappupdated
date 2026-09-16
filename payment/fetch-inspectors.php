<?php
header('Content-Type: application/json');
session_start();
include_once('../file/config.php');

$user_role = $_SESSION['role'] ?? '';
if (!in_array($user_role, ['admin', 'inspector'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$inspectors = [];

// Try fetching from inspectors table
$res = $conn->query("SELECT inspector_name FROM inspectors ORDER BY inspector_name ASC");
if ($res && $res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {
        if (!empty($row['inspector_name']) && !in_array($row['inspector_name'], $inspectors)) {
            $inspectors[] = $row['inspector_name'];
        }
    }
}

// Fallback / complement from project_info table
$res2 = $conn->query("SELECT DISTINCT inspector_name FROM project_info WHERE inspector_name IS NOT NULL AND inspector_name != '' ORDER BY inspector_name ASC");
if ($res2 && $res2->num_rows > 0) {
    while ($row = $res2->fetch_assoc()) {
        if (!empty($row['inspector_name']) && !in_array($row['inspector_name'], $inspectors)) {
            $inspectors[] = $row['inspector_name'];
        }
    }
}

sort($inspectors);

echo json_encode([
    'status' => 'success',
    'inspectors' => $inspectors
]);
