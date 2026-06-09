<?php
session_start();
include('../file/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../index.php");
        exit();
    }

    $cus_id = $_POST['cus_id'];
    $customer_name = $_POST['customer_name'];
    $email = $_POST['email'];
    $rep_name = $_POST['rep_name'];
    $mobile = $_POST['mobile'];
    $address = $_POST['address'];
    $city = $_POST['city'];

    $uploadDir = '../uploads/profile_photos/';
    $signatureDir = '../uploads/signatures/';
    $profilePath = null;
    $signaturePath = null;

    // Ensure directories exist
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
    if (!is_dir($signatureDir)) mkdir($signatureDir, 0777, true);

    // Handle file uploads
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] == 0) {
        $profileExt = pathinfo($_FILES['profile_photo']['name'], PATHINFO_EXTENSION);
        $profilePath = $uploadDir . $cus_id . '_profile.' . $profileExt;
        move_uploaded_file($_FILES['profile_photo']['tmp_name'], $profilePath);
    }

    if (isset($_FILES['signature_photo']) && $_FILES['signature_photo']['error'] == 0) {
        $signatureExt = pathinfo($_FILES['signature_photo']['name'], PATHINFO_EXTENSION);
        $signaturePath = $signatureDir . $cus_id . '_signature.' . $signatureExt;
        move_uploaded_file($_FILES['signature_photo']['tmp_name'], $signaturePath);
    }

    // Password update logic
    $hashed_password = null;
    $password_changed = false;
    
    if (!empty($_POST['old_password']) && !empty($_POST['new_password']) && !empty($_POST['retype_password'])) {
        // Verify old password against new_users table since that's where auth happens
        $sql = "SELECT password FROM new_users WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $cus_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            $_SESSION['error_message'] = 'User not found!';
            header("Location: ../dashboard/customer_new.php");
            exit;
        }
        
        $user = $result->fetch_assoc();
        $stmt->close();

        if (!password_verify($old_password, $user['password'])) {
            $_SESSION['error_message'] = 'Old password is incorrect!';
            header("Location: ../dashboard/customer_new.php");
            exit;
        }

        if ($new_password !== $retype_password) {
            $_SESSION['error_message'] = 'New password and retype password do not match!';
            header("Location: ../dashboard/customer_new.php");
            exit;
        }

        if (strlen($new_password) < 8) {
            $_SESSION['error_message'] = 'Password must be at least 8 characters long!';
            header("Location: ../dashboard/customer_new.php");
            exit;
        }

        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $password_changed = true;
    }

    // Begin transaction
    $conn->begin_transaction();
    try {
        // 1. Update customers table
        $sql_customers = "UPDATE customers SET customer_name=?, email=?, rep_name=?, mobile=?, address=?, city=?";
        $params_customers = [$customer_name, $email, $rep_name, $mobile, $address, $city];
        $types_customers = "ssssss";

        if ($profilePath) {
            $sql_customers .= ", profile_photo=?";
            $params_customers[] = $profilePath;
            $types_customers .= "s";
        }

        if ($signaturePath) {
            $sql_customers .= ", signature_photo=?";
            $params_customers[] = $signaturePath;
            $types_customers .= "s";
        }

        $sql_customers .= " WHERE cus_id=?";
        $params_customers[] = $cus_id;
        $types_customers .= "s";

        $stmt_customers = $conn->prepare($sql_customers);
        if (!$stmt_customers) {
            throw new Exception("Prepare failed for customers: " . $conn->error);
        }
        $stmt_customers->bind_param($types_customers, ...$params_customers);
        $stmt_customers->execute();
        $stmt_customers->close();

        // 2. Update new_users table
        $sql_new_users = "UPDATE new_users SET username=?, email=?";
        $params_new_users = [$customer_name, $email];
        $types_new_users = "ss";

        if ($profilePath) {
            $sql_new_users .= ", profile_photo=?";
            $params_new_users[] = $profilePath;
            $types_new_users .= "s";
        }

        if ($password_changed) {
            $sql_new_users .= ", password=?";
            $params_new_users[] = $hashed_password;
            $types_new_users .= "s";
        }

        $sql_new_users .= " WHERE user_id=?";
        $params_new_users[] = $cus_id;
        $types_new_users .= "s";

        $stmt_new_users = $conn->prepare($sql_new_users);
        if (!$stmt_new_users) {
            throw new Exception("Prepare failed for new_users: " . $conn->error);
        }
        $stmt_new_users->bind_param($types_new_users, ...$params_new_users);
        $stmt_new_users->execute();
        $stmt_new_users->close();

        // Commit transaction
        $conn->commit();
        
        $_SESSION['success_message'] = 'Profile updated successfully!';
        header("Location: ../dashboard/customer_new.php");
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        error_log("Error updating customer: " . $e->getMessage());
        $_SESSION['error_message'] = 'Error updating profile: ' . $e->getMessage();
        header("Location: ../dashboard/customer_new.php");
        exit;
    }
} else {
    header("Location: ../dashboard/customer_new.php");
    exit;
}
?>