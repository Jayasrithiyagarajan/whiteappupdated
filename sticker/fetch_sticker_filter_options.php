<?php
session_start();
include_once('../file/config.php');

$user = $_SESSION['username'];
$role = $_SESSION['role'];

$inspectors = [];

$sql = "SELECT DISTINCT assign_inspector FROM stickers";
if ($role === 'inspector') {
    $sql .= " WHERE assign_inspector = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user);
} else {
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $inspectors[] = $row['assign_inspector'];
}

echo json_encode(['inspectors' => $inspectors]);
?>