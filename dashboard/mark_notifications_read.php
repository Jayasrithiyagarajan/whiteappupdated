<?php
include_once('../file/config.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

// Update notifications for the current document controller
$user_id = $_SESSION['user_id'];
$update_query = "UPDATE project_notifications 
                 SET document_controller = NULL 
                 WHERE document_controller = 'pending' OR document_controller = ?";
$stmt = $conn->prepare($update_query);
$stmt->bind_param("s", $user_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update notifications']);
}

$stmt->close();
$conn->close();
?>