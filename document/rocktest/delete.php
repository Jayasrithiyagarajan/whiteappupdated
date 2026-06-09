<?php
include_once('../../file/config.php');

if (isset($_POST['project_no'])) {
    $project_no = $_POST['project_no'];

    $delete_sql = "DELETE FROM rocking_test_certificate WHERE project_no = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("s", $project_no);

    if ($delete_stmt->execute()) {
        // Update project status back to Pending so it can be recreated if needed
        $update_sql = "UPDATE project_info SET certificatestatus = 'Pending' WHERE project_no = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("s", $project_no);
        $update_stmt->execute();
        $update_stmt->close();
        
        echo json_encode(["success" => true, "message" => "Certificate deleted successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error deleting certificate: " . $conn->error]);
    }
    $delete_stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid project ID."]);
}
?>
