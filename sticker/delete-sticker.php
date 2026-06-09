<?php
include_once('../file/config.php');

if (isset($_GET['id'])) {
    $sticker_id = (int) $_GET['id'];

    // Prepare and execute delete query
    $sql = "DELETE FROM stickers WHERE sticker_start_no = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $sticker_id);

    if ($stmt->execute()) {
        echo "<script>
                alert('Sticker deleted successfully.');
                window.location.href = 'sticker-list.php'; // Redirect to the sticker list page
              </script>";
    } else {
        echo "<script>
                alert('Error deleting sticker: " . $stmt->error . "');
                window.location.href = 'sticker-list.php'; // Redirect to the sticker list page
              </script>";
    }

    $stmt->close();
}

$conn->close();
?>
