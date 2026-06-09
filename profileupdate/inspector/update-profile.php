<?php
session_start();
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once('../../inc/function.php');
include('../../file/config.php');

function get_encoded_url($id) {
    $base = "/whiteapp/profileupdate/inspector/inspector.php";
    return str_replace(' ', '%20', $base . "?id=" . $id);
}

// Store form data in session before processing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['form_data'] = $_POST;
}

// Enable detailed error logging
file_put_contents('profile_update.log', "[" . date('Y-m-d H:i:s') . "] Starting profile update\n", FILE_APPEND);

if (!$conn) {
    $error = "Database connection failed: " . $conn->connect_error;
    file_put_contents('profile_update.log', "[" . date('Y-m-d H:i:s') . "] $error\n", FILE_APPEND);
    $_SESSION['error'] = "Database connection failed";
    header("Location: " . get_encoded_url($_POST['id']));
    exit();
}

// Get current user data
try {
    $current_user_stmt = $conn->prepare("SELECT * FROM new_users WHERE id = ?");
    $current_user_stmt->bind_param("i", $_POST['id']);
    $current_user_stmt->execute();
    $current_user_result = $current_user_stmt->get_result();
    $current_user_data = $current_user_result->fetch_assoc();
    $current_user_stmt->close();
    
    if (!$current_user_data) {
        throw new Exception("User not found in database");
    }
} catch (Exception $e) {
    file_put_contents('profile_update.log', "[" . date('Y-m-d H:i:s') . "] Error fetching user data: " . $e->getMessage() . "\n", FILE_APPEND);
    $_SESSION['error'] = "Error loading your profile data";
    header("Location: " . get_encoded_url($_POST['id']));
    exit();
}

// Collect and sanitize form data
$data = [
    'user_id' => trim($_POST['inspector_id'] ?? $current_user_data['user_id'] ?? ''),
    'username' => trim($_POST['inspector_name'] ?? $current_user_data['username'] ?? ''),
    'email' => trim($_POST['email'] ?? ''),
    'emp_id' => trim($_POST['emp_id'] ?? ''),
    'mobile' => trim($_POST['mobile'] ?? ''),
    'address' => trim($_POST['address'] ?? ''),
    'city' => trim($_POST['city'] ?? ''),
    'role' => $_SESSION['role'] ?? $current_user_data['role'] ?? ''
];

// Validate required fields
$required_fields = [
    'user_id' => 'User ID',
    'username' => 'Username',
    'email' => 'Email',
    'mobile' => 'Mobile Number',
    'address' => 'Address',
    'city' => 'City',
    'role' => 'Role'
];

$missing_fields = [];
foreach ($required_fields as $field => $name) {
    if (empty($data[$field])) {
        $missing_fields[] = $name;
    }
}

if (!empty($missing_fields)) {
    $_SESSION['error'] = "Required fields missing: " . implode(', ', $missing_fields);
    header("Location: " . get_encoded_url($_POST['id']));
    exit();
}

if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "Invalid email format";
    header("Location: " . get_encoded_url($_POST['id']));
    exit();
}

if (!preg_match('/^[0-9]{10,15}$/', $data['mobile'])) {
    $_SESSION['error'] = "Invalid mobile number format (10-15 digits required)";
    header("Location: " . get_encoded_url($_POST['id']));
    exit();
}

// Handle file uploads
$base_upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/whiteapp/inspector/uploads/';
$inspector_name = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $data['username']));
$user_image_dir = $base_upload_dir . $inspector_name . '/images/';

// Create directory if it doesn't exist
if (!is_dir($user_image_dir)) {
    if (!mkdir($user_image_dir, 0755, true)) {
        $error = "Failed to create directory: $user_image_dir";
        file_put_contents('profile_update.log', "[" . date('Y-m-d H:i:s') . "] $error\n", FILE_APPEND);
        $_SESSION['error'] = "Failed to create upload directory";
        header("Location: " . get_encoded_url($_POST['id']));
        exit();
    }
}

// Handle profile photo
$profile_photo_path = 'uploads/' . $inspector_name . '/images/profile_image.jpg';
if (isset($_POST['remove_photo']) && $_POST['remove_photo'] == '1') {
    $old_profile_path = $user_image_dir . 'profile_image.jpg';
    if (file_exists($old_profile_path)) {
        if (!unlink($old_profile_path)) {
            file_put_contents('profile_update.log', "[" . date('Y-m-d H:i:s') . "] Failed to delete old profile photo: $old_profile_path\n", FILE_APPEND);
        }
    }
    $profile_photo_path = null;
}

if (!empty($_FILES['profile_photo']['name']) && $_FILES['profile_photo']['error'] == UPLOAD_ERR_OK) {
    $profile_tmp = $_FILES['profile_photo']['tmp_name'];
    $profile_target = $user_image_dir . 'profile_image.jpg';
    
    $valid_extensions = ['jpg', 'jpeg', 'png'];
    $profile_ext = strtolower(pathinfo($_FILES['profile_photo']['name'], PATHINFO_EXTENSION));
    
    if (!in_array($profile_ext, $valid_extensions)) {
        $_SESSION['error'] = "Invalid profile image format. Only JPG, JPEG, PNG are allowed.";
        header("Location: " . get_encoded_url($_POST['id']));
        exit();
    }
    
    if (file_exists($profile_target)) {
        unlink($profile_target);
    }
    
    if ($profile_ext !== 'jpg' && $profile_ext !== 'jpeg') {
        try {
            if ($profile_ext === 'png') {
                $image = imagecreatefrompng($profile_tmp);
            }
            
            if ($image !== false) {
                imagejpeg($image, $profile_target, 90);
                imagedestroy($image);
            } else {
                throw new Exception("Failed to create image from uploaded file");
            }
        } catch (Exception $e) {
            file_put_contents('profile_update.log', "[" . date('Y-m-d H:i:s') . "] Profile image conversion error: " . $e->getMessage() . "\n", FILE_APPEND);
            $_SESSION['error'] = "Failed to process profile image";
            header("Location: " . get_encoded_url($_POST['id']));
            exit();
        }
    } else {
        if (!move_uploaded_file($profile_tmp, $profile_target)) {
            $error = "Profile photo upload failed: " . $_FILES['profile_photo']['error'];
            file_put_contents('profile_update.log', "[" . date('Y-m-d H:i:s') . "] $error\n", FILE_APPEND);
            $_SESSION['error'] = "Failed to upload profile photo";
            header("Location: " . get_encoded_url($_POST['id']));
            exit();
        }
    }
    $profile_photo_path = 'uploads/' . $inspector_name . '/images/profile_image.jpg';
}

// Handle signature photo
$signature_photo_path = 'uploads/' . $inspector_name . '/images/signature_image.jpg';
if (isset($_POST['remove_signature']) && $_POST['remove_signature'] == '1') {
    $old_signature_path = $user_image_dir . 'signature_image.jpg';
    if (file_exists($old_signature_path)) {
        if (!unlink($old_signature_path)) {
            file_put_contents('profile_update.log', "[" . date('Y-m-d H:i:s') . "] Failed to delete old signature photo: $old_signature_path\n", FILE_APPEND);
        }
    }
    $signature_photo_path = null;
}

if (!empty($_FILES['signature_photo']['name']) && $_FILES['signature_photo']['error'] == UPLOAD_ERR_OK) {
    $signature_tmp = $_FILES['signature_photo']['tmp_name'];
    $signature_target = $user_image_dir . 'signature_image.jpg';
    
    $valid_extensions = ['jpg', 'jpeg', 'png'];
    $signature_ext = strtolower(pathinfo($_FILES['signature_photo']['name'], PATHINFO_EXTENSION));
    
    if (!in_array($signature_ext, $valid_extensions)) {
        $_SESSION['error'] = "Invalid signature image format. Only JPG, JPEG, PNG are allowed.";
        header("Location: " . get_encoded_url($_POST['id']));
        exit();
    }
    
    if (file_exists($signature_target)) {
        unlink($signature_target);
    }
    
    if ($signature_ext !== 'jpg' && $signature_ext !== 'jpeg') {
        try {
            if ($signature_ext === 'png') {
                $image = imagecreatefrompng($signature_tmp);
            }
            
            if ($image !== false) {
                imagejpeg($image, $signature_target, 90);
                imagedestroy($image);
            } else {
                throw new Exception("Failed to create image from uploaded file");
            }
        } catch (Exception $e) {
            file_put_contents('profile_update.log', "[" . date('Y-m-d H:i:s') . "] Signature image conversion error: " . $e->getMessage() . "\n", FILE_APPEND);
            $_SESSION['error'] = "Failed to process signature image";
            header("Location: " . get_encoded_url($_POST['id']));
            exit();
        }
    } else {
        if (!move_uploaded_file($signature_tmp, $signature_target)) {
            $error = "Signature photo upload failed: " . $_FILES['signature_photo']['error'];
            file_put_contents('profile_update.log', "[" . date('Y-m-d H:i:s') . "] $error\n", FILE_APPEND);
            $_SESSION['error'] = "Failed to upload signature photo";
            header("Location: " . get_encoded_url($_POST['id']));
            exit();
        }
    }
    $signature_photo_path = 'uploads/' . $inspector_name . '/images/signature_image.jpg';
}

// Update database
try {
    $conn->begin_transaction();

    $sql = "UPDATE new_users SET 
            user_id = ?,
            username = ?, 
            email = ?, 
            emp_id = ?, 
            mobile = ?, 
            address = ?, 
            city = ?,
            role = ?";
    
    $params = [
        $data['user_id'],
        $data['username'],
        $data['email'],
        $data['emp_id'],
        $data['mobile'],
        $data['address'],
        $data['city'],
        $data['role']
    ];
    $param_types = "ssssssss";
    
    if ($profile_photo_path !== null || isset($_POST['remove_photo'])) {
        $sql .= ", profile_photo = ?";
        $params[] = $profile_photo_path;
        $param_types .= "s";
    }
    
    if ($signature_photo_path !== null || isset($_POST['remove_signature'])) {
        $sql .= ", signature_photo = ?";
        $params[] = $signature_photo_path;
        $param_types .= "s";
    }
    
    $sql .= " WHERE id = ?";
    $params[] = $_POST['id'];
    $param_types .= "i";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param($param_types, ...$params);
    
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    $stmt->close();
    
    // Update inspectors table
    $inspector_sql = "UPDATE inspectors SET
                     inspector_name = ?,
                     email = ?,
                     mobile = ?,
                     address = ?,
                     city = ?";
    
    $inspector_params = [
        $data['username'],
        $data['email'],
        $data['mobile'],
        $data['address'],
        $data['city']
    ];
    $inspector_param_types = "sssss";
    
    if ($profile_photo_path !== null || isset($_POST['remove_photo'])) {
        $inspector_sql .= ", profile_photo = ?";
        $inspector_params[] = $profile_photo_path;
        $inspector_param_types .= "s";
    }
    
    if ($signature_photo_path !== null || isset($_POST['remove_signature'])) {
        $inspector_sql .= ", signature_photo = ?";
        $inspector_params[] = $signature_photo_path;
        $inspector_param_types .= "s";
    }
    
    $inspector_sql .= " WHERE inspector_id = ?";
    $inspector_params[] = $data['user_id'];
    $inspector_param_types .= "s";
    
    $inspector_stmt = $conn->prepare($inspector_sql);
    if (!$inspector_stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    $inspector_stmt->bind_param($inspector_param_types, ...$inspector_params);
    
    if (!$inspector_stmt->execute()) {
        throw new Exception("Failed to update inspector table: " . $inspector_stmt->error);
    }
    $inspector_stmt->close();
    
    $conn->commit();
    
    // Update session data
    $_SESSION['username'] = $data['username'];
    $_SESSION['role'] = $data['role'];
    if ($profile_photo_path !== null) {
        $_SESSION['profile_photo'] = $profile_photo_path;
    } elseif (isset($_POST['remove_photo'])) {
        unset($_SESSION['profile_photo']);
    }
    if ($signature_photo_path !== null) {
        $_SESSION['signature_photo'] = $signature_photo_path;
    } elseif (isset($_POST['remove_signature'])) {
        unset($_SESSION['signature_photo']);
    }
    
    $_SESSION['success'] = "Profile updated successfully!";
    // Clear form data from session on success
    unset($_SESSION['form_data']);
    file_put_contents('profile_update.log', "[" . date('Y-m-d H:i:s') . "] Profile updated successfully for user ID " . $_POST['id'] . "\n", FILE_APPEND);
    header("Location: " . get_encoded_url($_POST['id']));
    exit();

} catch (Exception $e) {
    $conn->rollback();
    $error = "Profile update error: " . $e->getMessage();
    file_put_contents('profile_update.log', "[" . date('Y-m-d H:i:s') . "] $error\n", FILE_APPEND);
    $_SESSION['error'] = "An error occurred while updating your profile. Please try again.";
    header("Location: " . get_encoded_url($_POST['id']));
    exit();
}
ob_end_flush();
?>