<?php
include_once('../file/config.php');

$projectNo = $_POST['qcProjectNo'];

// Update project status to Completed
$query = "UPDATE project_info SET project_status = 'Completed' WHERE project_no = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $projectNo);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error updating project status']);
}
?>