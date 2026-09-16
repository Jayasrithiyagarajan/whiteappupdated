<?php
header('Content-Type: application/json');
session_start();

include_once('../file/config.php');

$user_role = $_SESSION['role'] ?? '';
if ($user_role !== 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

$project_no   = trim($_POST['project_no'] ?? '');
$payment_mode = trim($_POST['payment_mode'] ?? '');

if (empty($project_no)) {
    echo json_encode(['status' => 'error', 'message' => 'Project ID is required']);
    exit;
}

if (empty($payment_mode)) {
    echo json_encode(['status' => 'error', 'message' => 'Payment mode is required']);
    exit;
}

$stmt = $conn->prepare("UPDATE project_info SET payment_mode = ? WHERE project_no = ?");
$stmt->bind_param("ss", $payment_mode, $project_no);

if ($stmt->execute()) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Payment mode updated successfully for project ' . htmlspecialchars($project_no)
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to update payment mode: ' . $stmt->error
    ]);
}

$stmt->close();
