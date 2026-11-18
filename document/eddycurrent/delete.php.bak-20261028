<?php
include_once('../../file/config.php'); // Include your database connection

if (isset($_POST['certificate_no'])) {
    $certificate_no = $_POST['certificate_no'];

    // Fetch project_no before deleting
    $project_no = null;
    $proj_query = "SELECT project_no FROM eddy_current_inspection WHERE certificate_no = ?";
    $proj_stmt = $conn->prepare($proj_query);
    $proj_stmt->bind_param("s", $certificate_no);
    $proj_stmt->execute();
    $proj_stmt->bind_result($project_no);
    $proj_stmt->fetch();
    $proj_stmt->close();

    // Prepare the SQL query to delete the record
    $sql = "DELETE FROM eddy_current_inspection WHERE certificate_no = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $certificate_no);

    if ($stmt->execute()) {
        if ($project_no) {
            // Update the project_info table
            $update_query = "UPDATE project_info SET certificatestatus = 'Pending' WHERE project_no = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param("s", $project_no);
            $update_stmt->execute();
            $update_stmt->close();
        }
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>