<?php
include_once('../file/config.php');

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['stickerNo'])) {
    $stickerNo = trim($_POST['stickerNo']);
    
    // Query the `stickers` table to check status
    $stmt = $conn->prepare("SELECT status FROM stickers WHERE sticker_start_no = ?");
    $stmt->bind_param("s", $stickerNo);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        echo json_encode(["status" => $row['status']]); // Return status
    } else {
        echo json_encode(["status" => "not_found"]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => "error"]);
}
?>
