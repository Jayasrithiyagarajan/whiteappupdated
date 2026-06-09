<?php
session_start();
require '../file/config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);

    // Fetch current data to preserve existing files if no new ones are uploaded
    $sql = "SELECT profile_photo, signature FROM new_users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $current_user = $result->fetch_assoc();

    $profile_photo_path = $current_user['profile_photo'];
    $signature_path = $current_user['signature'];

    $target_dir = "../uploads/profiles/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Handle Profile Photo Upload
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] == 0) {
        $ext = pathinfo($_FILES['profile_photo']['name'], PATHINFO_EXTENSION);
        $new_name = "profile_" . $user_id . "_" . time() . "." . $ext;
        $target_file = $target_dir . $new_name;
        if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $target_file)) {
            $profile_photo_path = "../profile/../uploads/profiles/" . $new_name; // Adjusting path for consistent access
            // In many setups, it's safer to store relative to root
            $profile_photo_path = "../uploads/profiles/" . $new_name;
        }
    }

    // Handle Signature Upload
    if (isset($_FILES['signature_photo']) && $_FILES['signature_photo']['error'] == 0) {
        $ext = pathinfo($_FILES['signature_photo']['name'], PATHINFO_EXTENSION);
        $new_name = "signature_" . $user_id . "_" . time() . "." . $ext;
        $target_file = $target_dir . $new_name;
        if (move_uploaded_file($_FILES['signature_photo']['tmp_name'], $target_file)) {
            $signature_path = "../uploads/profiles/" . $new_name;
        }
    }

    // Update Database
    $update_sql = "UPDATE new_users SET fullname = ?, email = ?, phone_number = ?, profile_photo = ?, signature = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sssssi", $fullname, $email, $phone_number, $profile_photo_path, $signature_path, $user_id);

    if ($update_stmt->execute()) {
        $_SESSION['success_msg'] = "Profile updated successfully!";
    } else {
        $_SESSION['error_msg'] = "Error updating profile: " . $conn->error;
    }

    header("Location: user-profile.php#edit");
    exit();
}
?>
