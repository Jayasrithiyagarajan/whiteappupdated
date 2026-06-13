<?php
session_start();
include_once('../../file/config.php');

$data = json_decode(file_get_contents("php://input"), true);
$projectNo = $data['project_no'];

if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'document controller') {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

$stmt = $conn->prepare("DELETE FROM mpi_certificates WHERE project_no=?");
$stmt->bind_param("s", $projectNo);

if ($stmt->execute()) {
    $update_stmt = $conn->prepare("UPDATE project_info SET certificatestatus = 'Pending' WHERE project_no = ?");
    $update_stmt->bind_param("s", $projectNo);
    $update_stmt->execute();
    $update_stmt->close();
    echo json_encode(["success" => true, "message" => "Certificate deleted"]);
}
else {
    echo json_encode(["success" => false, "message" => "Delete failed"]);
}
