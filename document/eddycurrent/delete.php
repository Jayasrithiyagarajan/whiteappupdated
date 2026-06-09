<?php
include_once('../../file/config.php'); // Include your database connection

if (isset($_POST['certificate_no'])) {
    $certificate_no = $_POST['certificate_no'];

    // Prepare the SQL query to delete the record
    $sql = "DELETE FROM eddy_current_inspection WHERE certificate_no = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $certificate_no);

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