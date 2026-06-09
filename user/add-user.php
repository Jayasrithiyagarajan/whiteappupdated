<?php
include_once('../file/config.php'); // Database connection

// Include PHPMailer
require '../inc/PHPMailer/src/PHPMailer.php';
require '../inc/PHPMailer/src/SMTP.php';
require '../inc/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $emp_id = $_POST['emp_id'];
    $mobile = $_POST['mobile'];
    $plain_password = $_POST['password'];
    $password = password_hash($plain_password, PASSWORD_BCRYPT);
    $address = $_POST['address'];
    $city = $_POST['city'];
    $role = $_POST['role'];

    // Handle profile photo upload
    $profile_photo = '';
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/profile_photos/';
        $newFileName = $username . '.png';
        $uploadFile = $uploadDir . $newFileName;

        $fileType = pathinfo($uploadFile, PATHINFO_EXTENSION);
        $allowedTypes = ['png', 'jpg', 'jpeg', 'gif'];
        if (!in_array(strtolower($fileType), $allowedTypes)) {
            echo "<script>alert('Error: Only image files (png, jpg, jpeg, gif) are allowed'); window.history.back();</script>";
            exit();
        }

        if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $uploadFile)) {
            $profile_photo = $uploadFile;
        } else {
            echo "<script>alert('Error: Could not upload profile photo'); window.history.back();</script>";
            exit();
        }
    }

    // Insert into new_users
    $sql = "INSERT INTO new_users (user_id, username, email, emp_id, mobile, password, address, city, role, profile_photo) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssss", $user_id, $username, $email, $emp_id, $mobile, $password, $address, $city, $role, $profile_photo);

    if ($stmt->execute()) {
        // Determine role_id
        $role_id = 0;
        switch ($role) {
            case 'admin': $role_id = 1; break;
            case 'reviewer': $role_id = 3; break;
            case 'document controller': $role_id = 4; break;
            case 'quality controller': $role_id = 5; break;
            default: $role_id = 0;
        }

        // Insert into users
        $sql_users = "INSERT INTO users (username, password, role, id) VALUES (?, ?, ?, ?)";
        $stmt_users = $conn->prepare($sql_users);
        $stmt_users->bind_param("sssi", $username, $password, $role, $role_id);

        if ($stmt_users->execute()) {
            // Send email via SMTP using PHPMailer
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'mail.appcims.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'admin@appcims.com';
                $mail->Password = 'Makeitbetter@2025';
                $mail->SMTPSecure = 'ssl';
                $mail->Port = 465;

                $mail->setFrom('admin@appcims.com', 'Admin Team');
                $mail->addAddress($email, $username);
                $mail->addCC('support@appcims.com');

                $mail->isHTML(true);
                $mail->Subject = "Your Account Details - System Access";
                $mail->Body = "
                    <html>
                    <body>
                    <p>Hello <strong>$username</strong>,</p>
                    <p>Your account has been successfully created. Below are your login details:</p>
                    <ul>
                        <li><strong>Username:</strong> $username</li>
                        <li><strong>Email:</strong> $email</li>
                        <li><strong>Password:</strong> $plain_password</li>
                        <li><strong>Role:</strong> $role</li>
                    </ul>
                    <p>You can log in to your account using the link below:</p>
                    <p><a href='https://appcims.com/whiteapp/' target='_blank'>https://appcims.com/whiteapp/</a></p>
                    <p>Please keep this information safe and secure.</p>
                    <p>If you need any further assistance, please do not hesitate to contact us.<br>
          Regards,<br>
          Admin Team<br>
          Email: support@appcims.com<br>
          Contact:+966 13 814 6861 / 2 Ext:110</p>
                    </body>
                    </html>
                ";

                $mail->send();
                header("Location: all-user.php");
                exit();
            } catch (Exception $e) {
                error_log("Mail error: " . $mail->ErrorInfo);
                echo "<script>alert('User created, but email could not be sent'); window.location.href='all-user.php';</script>";
            }
        } else {
            echo "<script>alert('Error: Could not save user in users table'); window.history.back();</script>";
        }
        $stmt_users->close();
    } else {
        echo "<script>alert('Error: Could not save user in new_users table'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
?>