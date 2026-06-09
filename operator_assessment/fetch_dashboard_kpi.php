<?php
session_start();
include_once('../file/config.php');

$logged_in_user = $_SESSION['username'] ?? null;
$userRole       = $_SESSION['role'] ?? null;

if (!$logged_in_user) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$where_clause = " 1 = 1 ";
$params = [];
$types = "";

if ($userRole === 'inspector') {
    // Only see their assessments
    $where_clause .= " AND inspector_id = (SELECT user_id FROM new_users WHERE username = ?) ";
    $params[] = $logged_in_user;
    $types .= "s";
}

$data = [
    'total' => 0,
    'exam_passed' => 0,
    'exam_failed' => 0,
    'signals_passed' => 0,
    'signals_failed' => 0
];

// Query
$sql = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN exam_status = 'PASSED' THEN 1 ELSE 0 END) as exam_passed,
    SUM(CASE WHEN exam_status = 'FAILED' THEN 1 ELSE 0 END) as exam_failed,
    SUM(CASE WHEN signals_status = 'PASSED' THEN 1 ELSE 0 END) as signals_passed,
    SUM(CASE WHEN signals_status = 'FAILED' THEN 1 ELSE 0 END) as signals_failed
FROM operator_assessments
WHERE $where_clause";

$stmt = $conn->prepare($sql);
if ($types) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $data['total'] = (int)$row['total'];
    $data['exam_passed'] = (int)$row['exam_passed'];
    $data['exam_failed'] = (int)$row['exam_failed'];
    $data['signals_passed'] = (int)$row['signals_passed'];
    $data['signals_failed'] = (int)$row['signals_failed'];
}

echo json_encode($data);
