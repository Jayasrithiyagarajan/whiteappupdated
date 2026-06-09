<?php
include_once('../file/config.php'); // Include database connection

// Include PHPMailer classes
require '../inc/PHPMailer/src/PHPMailer.php';
require '../inc/PHPMailer/src/SMTP.php';
require '../inc/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Define the base upload directory
$upload_dir = './uploads/'; // Relative path to the uploads folder

if (isset($_POST['save_inspector'])) {
    $inspector_id = $_POST['inspector_id'];
    $inspector_name = $_POST['inspector_name'];
    $email = $_POST['email'];
    $handle_crane = isset($_POST['handle_crane']) ? serialize($_POST['handle_crane']) : null;
    $leea_number = $_POST['leea_number'];
    $mobile = $_POST['mobile'];
    $plain_password = $_POST['password']; // for email display
    $password = password_hash($plain_password, PASSWORD_DEFAULT);
    $address = $_POST['address'];
    $city = $_POST['city'];

    // Create a folder for the inspector by name
    $inspector_folder = preg_replace('/\s+/', '_', strtolower($inspector_name));
    $target_dir = $upload_dir . $inspector_folder . '/images/';

    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $profile_photo = 'profile_image.jpg';
    $signature_photo = 'signature_image.jpg';

    $profile_photo_path = $target_dir . $profile_photo;
    if (!move_uploaded_file($_FILES['profile_photo']['tmp_name'], $profile_photo_path)) {
        die("Error uploading profile photo.");
    }

    $signature_photo_path = $target_dir . $signature_photo;
    if (!move_uploaded_file($_FILES['signature_photo']['tmp_name'], $signature_photo_path)) {
        die("Error uploading signature photo.");
    }

    $sql = "INSERT INTO inspectors (inspector_id, inspector_name, email, handle_crane, leea_number, mobile, password, address, city, profile_photo, signature_photo)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssss", $inspector_id, $inspector_name, $email, $handle_crane, $leea_number, $mobile, $password, $address, $city, $profile_photo, $signature_photo);

    if ($stmt->execute()) {
        $username = $inspector_name;

        $user_sql = "INSERT INTO users (username, password, role, id) VALUES (?, ?, 'inspector', '2')";
        $user_stmt = $conn->prepare($user_sql);
        $user_stmt->bind_param("ss", $username, $password);

        $new_users_sql = "INSERT INTO new_users (user_id, username, email, mobile, password, profile_photo, address, city, role, created_at)
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'inspector', NOW())";
        $new_users_stmt = $conn->prepare($new_users_sql);

        $profile_photo_db_path = $inspector_folder . '/images/' . $profile_photo;

        $new_users_stmt->bind_param("ssssssss", 
            $inspector_id,
            $inspector_name, 
            $email, 
            $mobile, 
            $password, 
            $profile_photo_db_path, 
            $address, 
            $city
        );

        $user_success = $user_stmt->execute();
        $new_users_success = $new_users_stmt->execute();

        if ($user_success && $new_users_success) {
            // Setup PHPMailer
            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host = 'mail.appcims.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'admin@appcims.com';
                $mail->Password = 'Makeitbetter@2025';
                $mail->SMTPSecure = 'ssl'; // or 'tls' if required
                $mail->Port = 465;

                $mail->setFrom('admin@appcims.com', 'Admin Team');
                $mail->addAddress($email, $inspector_name);
                $mail->addCC('support@appcims.com');

                $mail->isHTML(true);
                $mail->Subject = "Welcome to the Inspection System";

                $mail->Body = "
                <html>
                <head><title>Inspector Account Created</title></head>
                <body>
                  <p>Dear $inspector_name,</p>
                  <p>Your account has been successfully created. Below are your login details:</p>
                  <ul>
                    <li><strong>Username:</strong> $username</li>
                    <li><strong>Email:</strong> $email</li>
                    <li><strong>Password:</strong> $plain_password</li>
                  </ul>
                  <p>Please keep this information safe and do not share it with others.</p>
                  <p>If you need any further assistance, please do not hesitate to contact us.<br>
          Regards,<br>
          Admin Team<br>
          Email: support@appcims.com<br>
          Contact:+966 13 814 6861 / 2 Ext:110</p>
                </body>
                </html>";

                $mail->send();
                echo "<script>alert('Inspector added and email sent successfully!'); window.location.href = './all-inspector.php';</script>";
            } catch (Exception $e) {
                error_log("Mailer Error: " . $mail->ErrorInfo);
                echo "<script>alert('Inspector added but email failed to send.'); window.location.href = './all-inspector.php';</script>";
            }

        } else {
            echo "Error with user tables: " . $user_stmt->error . " " . $new_users_stmt->error;
        }

        $user_stmt->close();
        $new_users_stmt->close();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>