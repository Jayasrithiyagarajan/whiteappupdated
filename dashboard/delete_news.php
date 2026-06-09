<?php
include_once('../file/config.php');

header('Content-Type: application/json'); // Ensure the response is JSON

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    if (!empty($id)) {
        // Check if the news item exists
        $check_stmt = $conn->prepare("SELECT id FROM news WHERE id = ?");
        $check_stmt->bind_param("i", $id);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            // Delete the news item
            $delete_stmt = $conn->prepare("DELETE FROM news WHERE id = ?");
            $delete_stmt->bind_param("i", $id);
            if ($delete_stmt->execute()) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Database error: Could not delete news.']);
            }
            $delete_stmt->close();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'News item not found.']);
        }
        $check_stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid news ID.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>