<?php
include_once('../file/config.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

// Update QC notifications for the current quality controller
$user_id = $_SESSION['user_id'];
$update_query = "UPDATE project_notifications 
                 SET quality_controller = NULL 
                 WHERE quality_controller = 'pending' OR quality_controller = ?";
$stmt = $conn->prepare($update_query);
$stmt->bind_param("s", $user_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update QC notifications']);
}

$stmt->close();
$conn->close();
?>