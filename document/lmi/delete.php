<?php
include_once('../../file/config.php'); 

if (isset($_POST['project_no'])) {
    $project_no = $_POST['project_no'];

    $conn->begin_transaction();

    try {
        // Delete from lmi_certificates
        $sql = "DELETE FROM lmi_certificates WHERE project_no = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $project_no);

        if ($stmt->execute()) {
            // Update project_info status
            $update_query = "UPDATE project_info SET certificatestatus = 'Pending' WHERE project_no = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param("s", $project_no);

            if ($update_stmt->execute()) {
                $conn->commit();
                echo "LMI Certificate deleted successfully.";
            } else {
                $conn->rollback();
                echo "Error updating project status: " . $conn->error;
            }
        } else {
            $conn->rollback();
            echo "Error deleting certificate: " . $conn->error;
        }

        if(isset($stmt)) $stmt->close();
        if(isset($update_stmt)) $update_stmt->close();
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    } finally {
        $conn->close();
    }
}
?>
