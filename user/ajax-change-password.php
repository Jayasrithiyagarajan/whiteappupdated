<?php
session_start();
include '../file/config.php';

if (!isset($_SESSION['username'])) {
    echo 'Unauthorized';
    exit;
}

if (empty($_POST['user_id']) || empty($_POST['password'])) {
    echo 'Invalid request';
    exit;
}

$userId   = (int) $_POST['user_id'];
$password = $_POST['password'];

if (strlen($password) < 6) {
    echo 'Password must be at least 6 characters';
    exit;
}

/* CHECK USER */
$check = $conn->prepare("
    SELECT user_id 
    FROM new_users 
    WHERE user_id = ?
    LIMIT 1
");
$check->bind_param("i", $userId);
$check->execute();

if ($check->get_result()->num_rows === 0) {
    echo 'User not found';
    exit;
}

/* UPDATE PASSWORD */
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$update = $conn->prepare("
    UPDATE new_users 
    SET password = ?
    WHERE user_id = ?
");
$update->bind_param("si", $hashedPassword, $userId);

if ($update->execute()) {
    echo 'success';
} else {
    echo 'Failed to update password';
}
