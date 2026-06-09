<?php
include_once('../file/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $news_text = $_POST['news_text'];

    if (!empty($news_text)) {
        $stmt = $conn->prepare("INSERT INTO news (news_text) VALUES (?)");
        $stmt->bind_param("s", $news_text);
        if ($stmt->execute()) {
            $new_id = $stmt->insert_id; // Get the ID of the newly inserted news item
            echo json_encode(['status' => 'success', 'id' => $new_id]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error saving news.']);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'News text is empty.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>