<?php
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
//session_start();
include_once('../../inc/function.php');
include('../../file/config.php');

function get_encoded_url($id) {
    $base = "/whiteapp/profileupdate/documentcontroller/document controller.php";
    return str_replace(' ', '%20', $base . "?id=" . $id . "#c_pass");
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}

$requested_id = intval($_POST['id'] ?? 0);
if ($_SESSION['user_id'] !== $requested_id) {
    $_SESSION['error'] = "Unauthorized access!";
    header("Location: " . get_encoded_url($_SESSION['user_id']));
    exit();
}

// Get form data
$old_password = $_POST['old_password'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Validate inputs
if (empty($old_password) || empty($new_password) || empty($confirm_password)) {
    $_SESSION['error'] = "All password fields are required!";
    header("Location: " . get_encoded_url($_SESSION['user_id']));
    exit();
}

if ($new_password !== $confirm_password) {
    $_SESSION['error'] = "New password and confirmation password don't match!";
    header("Location: " . get_encoded_url($_SESSION['user_id']));
    exit();
}

if (strlen($new_password) < 8) {
    $_SESSION['error'] = "Password must be at least 8 characters long!";
    header("Location: " . get_encoded_url($_SESSION['user_id']));
    exit();
}

try {
    // Get current password hash from new_users table
    $stmt = $conn->prepare("SELECT password FROM new_users WHERE id = ?");
    $stmt->bind_param("i", $requested_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        $_SESSION['error'] = "User not found!";
        header("Location: " . get_encoded_url($_SESSION['user_id']));
        exit();
    }
    
    $user = $result->fetch_assoc();
    
    // Verify old password
    if (!password_verify($old_password, $user['password'])) {
        $_SESSION['error'] = "Old password is incorrect!";
        header("Location: " . get_encoded_url($_SESSION['user_id']));
        exit();
    }
    
    // Hash the new password
    $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
    
    // Update password in new_users table
    $stmt = $conn->prepare("UPDATE new_users SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $new_password_hash, $requested_id);
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to update password: " . $stmt->error);
    }
    
    // If you have a separate users table, update it as well
    // First get the user_id from new_users
    $stmt = $conn->prepare("SELECT user_id FROM new_users WHERE id = ?");
    $stmt->bind_param("i", $requested_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc();
    
    if ($user_data) {
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
        $stmt->bind_param("ss", $new_password_hash, $user_data['user_id']);
        $stmt->execute();
    }
    
    $_SESSION['success'] = "Password updated successfully!";
    header("Location: " . get_encoded_url($_SESSION['user_id']));
    exit();

} catch (Exception $e) {
    $_SESSION['error'] = "Error changing password: " . $e->getMessage();
    error_log("Password change error: " . $e->getMessage());
    header("Location: " . get_encoded_url($_SESSION['user_id']));
    exit();
}
ob_end_flush();
?>