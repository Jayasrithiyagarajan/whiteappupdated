<?php
include_once('../file/config.php'); // Include your database configuration

$stickerNo = $_GET['stickerNo'] ?? '';

// Assume $conn is your database connection from config.php
$query = $conn->prepare("SELECT * FROM stickers WHERE sticker_number = ?");
$query->bind_param("s", $stickerNo);
$query->execute();
$result = $query->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Sticker number is wrong']);
}
?>