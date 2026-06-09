<?php
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include_once('../../inc/function.php');
include('../../file/config.php');

function get_encoded_url($id) {
    $base = "/whiteapp/profileupdate/reviewer/reviewer.php";
    return str_replace(' ', '%20', $base . "?id=" . $id);
}

// Database connection check
if (!$conn) {
    $_SESSION['error'] = "Database connection failed";
    header("Location: " . get_encoded_url($_SESSION['user_id']));
    exit();
}

// Authentication check
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}

$requested_id = intval($_POST['id'] ?? 0);
if ($_SESSION['user_id'] !== $requested_id) {
    $_SESSION['error'] = "Unauthorized access";
    header("Location: ../../index.php");
    exit();
}

// Get current user data including profile photo
$current_user_stmt = $conn->prepare("SELECT username, profile_photo, role FROM new_users WHERE id = ?");
$current_user_stmt->bind_param("i", $requested_id);
$current_user_stmt->execute();
$current_user_result = $current_user_stmt->get_result();
$current_user_data = $current_user_result->fetch_assoc();
$current_user_stmt->close();

// Collect and sanitize form data
$data = [
    'user_id' => trim($_POST['user_id'] ?? ''),
    'username' => trim($_POST['username'] ?? $current_user_data['username'] ?? ''),
    'email' => trim($_POST['email'] ?? ''),
    'emp_id' => trim($_POST['emp_id'] ?? ''),
    'mobile' => trim($_POST['mobile'] ?? ''),
    'address' => trim($_POST['address'] ?? ''),
    'city' => trim($_POST['city'] ?? ''),
    'role' => trim($_POST['role'] ?? $current_user_data['role'] ?? '')
];

// Initialize profile photo path with current value
$profile_photo_path = $current_user_data['profile_photo'] ?? null;

// Handle photo removal if checkbox is checked
if (isset($_POST['remove_photo']) && $_POST['remove_photo'] == '1') {
    if (!empty($profile_photo_path)) {
        $absolute_path = $_SERVER['DOCUMENT_ROOT'] . '/whiteapp/' . ltrim($profile_photo_path, '/');
        if (file_exists($absolute_path)) {
            unlink($absolute_path);
        }
        $profile_photo_path = null;
    }
}

// Handle file upload only if a new file was provided
$file_uploaded = isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK;
if ($file_uploaded) {
    $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/whiteapp/uploads/profile_photos/';
    
    // Create directory if it doesn't exist
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    // Validate file
    $allowed_types = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif'];
    $max_size = 2 * 1024 * 1024; // 2MB

    if (!array_key_exists($_FILES['profile_photo']['type'], $allowed_types)) {
        $_SESSION['error'] = "Only JPG, PNG, and GIF files are allowed";
        header("Location: " . get_encoded_url($requested_id));
        exit();
    }

    if ($_FILES['profile_photo']['size'] > $max_size) {
        $_SESSION['error'] = "File size must be less than 2MB";
        header("Location: " . get_encoded_url($requested_id));
        exit();
    }

    // Generate filename as username.png
    $sanitized_username = preg_replace('/[^a-zA-Z0-9]/', '_', $data['username']);
    $file_name = strtolower($sanitized_username) . '.png';
    $target_path = $upload_dir . $file_name;

    // Delete old photo if exists (only if we're uploading a new one)
    if (!empty($profile_photo_path)) {
        $old_absolute_path = $_SERVER['DOCUMENT_ROOT'] . '/whiteapp/' . ltrim($profile_photo_path, '/');
        if (file_exists($old_absolute_path) && basename($old_absolute_path) != $file_name) {
            unlink($old_absolute_path);
        }
    }

    // Convert image to PNG if needed and save
    try {
        $image_info = getimagesize($_FILES['profile_photo']['tmp_name']);
        switch ($image_info['mime']) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($_FILES['profile_photo']['tmp_name']);
                break;
            case 'image/png':
                $image = imagecreatefrompng($_FILES['profile_photo']['tmp_name']);
                break;
            case 'image/gif':
                $image = imagecreatefromgif($_FILES['profile_photo']['tmp_name']);
                break;
            default:
                throw new Exception("Unsupported image type");
        }

        // Save as PNG
        if (!imagepng($image, $target_path, 9)) {
            throw new Exception("Failed to save image");
        }
        imagedestroy($image);

        $profile_photo_path = 'uploads/profile_photos/' . $file_name;
    } catch (Exception $e) {
        $_SESSION['error'] = "Error processing image: " . $e->getMessage();
        header("Location: " . get_encoded_url($requested_id));
        exit();
    }
}

// Validate required fields
$required_fields = [
    'user_id' => 'User ID',
    'username' => 'Username',
    'email' => 'Email',
    'emp_id' => 'Employee ID',
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
    header("Location: " . get_encoded_url($requested_id));
    exit();
}

// Validate email format
if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "Invalid email format";
    header("Location: " . get_encoded_url($requested_id));
    exit();
}

// Validate mobile number
if (!preg_match('/^[0-9]{10,15}$/', $data['mobile'])) {
    $_SESSION['error'] = "Invalid mobile number format (10-15 digits required)";
    header("Location: " . get_encoded_url($requested_id));
    exit();
}

// Update database
// Update database
try {
    $sql = "UPDATE new_users SET 
            user_id = ?,
            username = ?, 
            email = ?, 
            emp_id = ?, 
            mobile = ?, 
            address = ?, 
            city = ?,
            role = ?,
            profile_photo = ?
            WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param("sssssssssi", 
        $data['user_id'],
        $data['username'],
        $data['email'],
        $data['emp_id'],
        $data['mobile'],
        $data['address'],
        $data['city'],
        $data['role'],
        $profile_photo_path, // This will be either the new path, old path, or null
        $requested_id
    );
    
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    
    // Update session data
    $_SESSION['username'] = $data['username'];
    $_SESSION['role'] = $data['role'];
    if ($profile_photo_path !== null) {
        $_SESSION['profile_photo'] = $profile_photo_path;
    } elseif (isset($_POST['remove_photo'])) {
        unset($_SESSION['profile_photo']);
    }
    
    $_SESSION['success'] = "Profile updated successfully!";
    header("Location: " . get_encoded_url($requested_id));
    exit();

} catch (Exception $e) {
    error_log("Profile update error: " . $e->getMessage());
    $_SESSION['error'] = "An error occurred while updating your profile. Please try again.";
    header("Location: " . get_encoded_url($requested_id));
    exit();
}
ob_end_flush();
?>