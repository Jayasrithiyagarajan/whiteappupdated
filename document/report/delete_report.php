<?php
include_once('../../file/config.php'); // Include your database connection

if (isset($_POST['project_no'])) {
    $project_no = $_POST['project_no'];

    // SQL query to delete the record
    $sql = "DELETE FROM reports WHERE project_no = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $project_no);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $conn->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "error" => "No project_no provided"]);
}
?>
