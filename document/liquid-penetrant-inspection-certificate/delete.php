<?php
include_once('../../file/config.php'); // Include your database connection

if (isset($_POST['project_no'])) {
    $project_no = $_POST['project_no'];

    // Prepare the SQL query to delete the record
    $sql = "DELETE FROM liquid_penetrant_inspection WHERE project_no = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $project_no);

    if ($stmt->execute()) {
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