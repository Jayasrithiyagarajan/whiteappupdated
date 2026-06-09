<?php
session_start();

// Check if session is valid
if (isset($_SESSION['session_id'])) {
    $session_id = $_SESSION['session_id'];
    // $role_id = $_SESSION['role'];
    $stmt = $conn->prepare("SELECT user_id FROM user_sessions WHERE session_id = ?");
    $stmt->bind_param("s", $session_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Valid session
        $stmt->bind_result($user_id);
        $stmt->fetch();

        // User is logged in, can access protected pages
    } else {
        // Invalid session, redirect to login
        header("Location: ../index.php");
        exit;
    }
} else {
    // No session ID, redirect to login
    header("Location: ../index.php");
    exit;
}
?>
