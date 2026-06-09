<?php
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include_once('../../inc/function.php');
include('../../file/config.php');

// Verify database connection
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

function get_encoded_url($id) {
    $base = "/whiteapp/profileupdate/inspector/inspector.php";
    return str_replace(' ', '%20', $base . "?id=" . $id . "#c_pass");
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}

$session_user_id = intval($_SESSION['user_id']);

// Debug: Verify session ID
error_log("Session user_id: " . $session_user_id);

// Get form data
$old_password = $_POST['old_password'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Validate inputs
if (empty($old_password) || empty($new_password) || empty($confirm_password)) {
    $_SESSION['error'] = "All password fields are required!";
    header("Location: " . get_encoded_url($session_user_id));
    exit();
}

if ($new_password !== $confirm_password) {
    $_SESSION['error'] = "New password and confirmation password don't match!";
    header("Location: " . get_encoded_url($session_user_id));
    exit();
}

if (strlen($new_password) < 8) {
    $_SESSION['error'] = "Password must be at least 8 characters long!";
    header("Location: " . get_encoded_url($session_user_id));
    exit();
}

try {
    // Begin transaction
    $conn->begin_transaction();

    // 1. Get user details from new_users (using id which matches user_sessions.user_id)
    $stmt = $conn->prepare("SELECT id, password FROM new_users WHERE id = ?");
    $stmt->bind_param("i", $session_user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception("User account not found in new_users table!");
    }
    
    $user = $result->fetch_assoc();
    $user_id = $user['id']; // This matches user_sessions.user_id
    
    // Verify old password
    if (!password_verify($old_password, $user['password'])) {
        throw new Exception("Old password is incorrect!");
    }
    
    // Hash the new password
    $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
    
    // 2. Update password in new_users table
    $update_user = $conn->prepare("UPDATE new_users SET password = ? WHERE id = ?");
    $update_user->bind_param("si", $new_password_hash, $user_id);
    
    if (!$update_user->execute()) {
        throw new Exception("Failed to update password in new_users: " . $update_user->error);
    }
    
    // 3. Update password in inspectors table (using user_id which matches new_users.id)
    $update_inspector = $conn->prepare("UPDATE inspectors SET password = ? WHERE inspector_id = ?");
    $update_inspector->bind_param("si", $new_password_hash, $user_id);
    
    if (!$update_inspector->execute()) {
        throw new Exception("Failed to update password in inspectors: " . $update_inspector->error);
    }
    
    // 4. Optional: Update user_sessions if needed
    // $update_session = $conn->prepare("UPDATE user_sessions SET last_update = NOW() WHERE user_id = ?");
    // $update_session->bind_param("i", $user_id);
    // $update_session->execute();
    
    // Commit transaction
    $conn->commit();
    
    $_SESSION['success'] = "Password updated successfully!";
    header("Location: " . get_encoded_url($user_id));
    exit();

} catch (Exception $e) {
    $conn->rollback();
    $_SESSION['error'] = "Error changing password: " . $e->getMessage();
    error_log("Password change error: " . $e->getMessage());
    error_log("SQL error: " . ($conn->error ?? 'No error'));
    header("Location: " . get_encoded_url($session_user_id));
    exit();
}
ob_end_flush();
?>