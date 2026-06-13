<?php
include_once('../../file/config.php'); // Include your database connection

if (isset($_POST['project_no'])) {
    $project_no = $_POST['project_no'];

    // Prepare the SQL query to delete the record
    $sql = "DELETE FROM liquid_penetrant_inspection WHERE project_no = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $project_no);

    if ($stmt->execute()) {
        // Update the project_info table
        $update_query = "UPDATE project_info SET certificatestatus = 'Pending' WHERE project_no = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("s", $project_no);
        $update_stmt->execute();
        $update_stmt->close();
        
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