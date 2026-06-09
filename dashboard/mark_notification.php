<?php
include_once('../file/config.php');

if (isset($_GET['id'])) {
    $notification_id = $_GET['id'];
    $update_query = "UPDATE project_notifications SET reviewer = NULL WHERE id = ?";
    
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("i", $notification_id);
    
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }

    $stmt->close();
}
?>