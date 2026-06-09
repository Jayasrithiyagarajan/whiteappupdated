<?php
include_once('../../file/config.php'); // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $checklist_no = $_POST['checklist_no']; // Get checklist number from POST

    if (!empty($checklist_no)) {
        // Prepare the SQL DELETE query
        $sql = "DELETE FROM checklist_information WHERE checklist_no = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to prepare the statement.',
                'sql_error' => $conn->error
            ]);
            exit;
        }

        // Bind the checklist number to the query
        $stmt->bind_param("s", $checklist_no);

        if ($stmt->execute() && $stmt->affected_rows > 0) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to delete the record.',
                'sql_error' => $stmt->error
            ]);
        }

        $stmt->close(); // Close the statement
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid checklist number.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}

$conn->close(); // Close the connection
?>
