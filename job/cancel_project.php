<?php
require_once '../file/config.php';

header('Content-Type: application/json');

$projectNo = $_GET['project_no'] ?? $_POST['project_no'] ?? '';

if (empty($projectNo)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid project number']);
    exit;
}

$sql = "UPDATE project_info SET project_status = 'Cancelled' WHERE project_no = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'SQL prepare failed: ' . $conn->error]);
    exit;
}

$stmt->bind_param("s", $projectNo);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(['status' => 'success', 'message' => 'Project status changed to Cancelled successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Project not found or status already cancelled']);
}

$stmt->close();
$conn->close();
