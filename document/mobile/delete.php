<?php
include_once('../../file/config.php'); // Include the database connection

if (isset($_POST['project_no'])) {
    $project_no = $_POST['project_no'];

    // SQL query to delete the record
    $delete_sql = "DELETE FROM mobile_crane_certificate WHERE project_no = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("s", $project_no);

    if ($delete_stmt->execute()) {
        // If deletion is successful, update certificate status in project_info table
        $update_sql = "UPDATE project_info SET certificate_status = 'Pending' WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("s", $project_no);

        if ($update_stmt->execute()) {
            echo "Certificate deleted and status updated to Pending.";
        } else {
            echo "Certificate deleted but failed to update status: " . $conn->error;
        }

        $update_stmt->close();
    } else {
        echo "Error deleting certificate: " . $conn->error;
    }

    $delete_stmt->close();
    $conn->close();
} else {
    echo "Error: Invalid project ID.";
}
?>
