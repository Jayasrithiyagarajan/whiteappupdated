<?php
include_once('../../file/config.php'); // Include the database connection

if (isset($_POST['project_no'])) {
    $project_no = $_POST['project_no'];

    // Start a transaction to ensure both actions (deleting and updating) are completed atomically
    $conn->begin_transaction();

    try {
        // SQL query to delete the record from the crane_health_check_certificate table
        $sql = "DELETE FROM crane_health_check_certificate WHERE project_no = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $project_no);

        if ($stmt->execute()) {
            // SQL query to update the status in the project_info table to 'Pending'
            $update_query = "UPDATE project_info SET certificatestatus = 'Pending' WHERE project_no = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param("s", $project_no);

            if ($update_stmt->execute()) {
                // Commit the transaction if both queries are successful
                $conn->commit();
                echo "Certificate deleted successfully, and project status updated to Pending.";
            } else {
                // Rollback if the update query fails
                $conn->rollback();
                echo "Error updating project status: " . $conn->error;
            }
        } else {
            // Rollback if the delete query fails
            $conn->rollback();
            echo "Error deleting certificate: " . $conn->error;
        }

        // Close the prepared statements
        $stmt->close();
        $update_stmt->close();
    } catch (Exception $e) {
        // Rollback in case of any exception
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    } finally {
        // Close the connection
        $conn->close();
    }
}
?>
