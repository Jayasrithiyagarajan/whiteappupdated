<?php
include '../file/config.php';

require '../inc/PHPMailer/src/PHPMailer.php';
require '../inc/PHPMailer/src/SMTP.php';
require '../inc/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_name = $_POST['customer_name'];
    $email = $_POST['email'];
    $rep_name = $_POST['rep_name'];
    $mobile = $_POST['mobile'];
    $plain_password = $_POST['password'];
    $password = password_hash($plain_password, PASSWORD_DEFAULT);
    $address = $_POST['address'];
    $city = $_POST['city'];
    $date_of_adding = $_POST['date_of_adding'];
    $reference_by = $_POST['reference_by'];
    $notes = $_POST['notes'];
    $info_correct = isset($_POST['info_correct']) ? 1 : 0;

    $uploadDir = '../uploads/profile_photos/';
    $signatureDir = '../uploads/signatures/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
    if (!is_dir($signatureDir)) mkdir($signatureDir, 0777, true);

    // Check for existing email or mobile
    $checkSql = "SELECT cus_id FROM customers WHERE email = ? OR mobile = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("ss", $email, $mobile);
    $checkStmt->execute();
    $checkStmt->store_result();
    if ($checkStmt->num_rows > 0) {
        echo "Error: Customer with this email or mobile number already exists.";
        $checkStmt->close();
        $conn->close();
        exit();
    }
    $checkStmt->close();

    // Generate new customer ID (CUS001, CUS002, etc.)
    $result = $conn->query("SELECT cus_id FROM customers ORDER BY cus_id DESC LIMIT 1");
    $row = $result->fetch_assoc();
    if ($row && isset($row['cus_id'])) {
        $last_cus_id = $row['cus_id'];
        $last_number = (int)substr($last_cus_id, 3); // Remove 'CUS'
        $numeric_id = $last_number + 1;
    } else {
        $numeric_id = 1;
    }
    $new_id = 'CUS' . str_pad($numeric_id, 3, '0', STR_PAD_LEFT);

    // Handle uploads
    $filePath = null;
    $signaturePath = null;

    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === 0) {
        $filePath = $uploadDir . $new_id . '.png';
        move_uploaded_file($_FILES['profile_photo']['tmp_name'], $filePath);
    }

    if (isset($_FILES['signature_photo']) && $_FILES['signature_photo']['error'] === 0) {
        $signaturePath = $signatureDir . $new_id . '.png';
        move_uploaded_file($_FILES['signature_photo']['tmp_name'], $signaturePath);
    }

    $conn->begin_transaction();

    try {
        // Insert into customers
        $customer_sql = "INSERT INTO customers (cus_id, customer_name, email, rep_name, mobile, password, address, city, info_correct, date_of_adding, reference_by, notes, profile_photo, signature_photo)
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($customer_sql);
        $stmt->bind_param("ssssssssssssss", $new_id, $customer_name, $email, $rep_name, $mobile, $password, $address, $city, $info_correct, $date_of_adding, $reference_by, $notes, $filePath, $signaturePath);
        $stmt->execute();
        $stmt->close();

        // Insert into users
        $user_sql = "INSERT INTO users (username, password, role, id) VALUES (?, ?, 'customer', '6')";
        $user_stmt = $conn->prepare($user_sql);
        $user_stmt->bind_param("ss", $customer_name, $password);
        $user_stmt->execute();
        $user_stmt->close();

        // Insert into new_users
        $new_user_sql = "INSERT INTO new_users (user_id, username, email, emp_id, mobile, password, address, city, role, created_at, profile_photo, date_of_adding, reference_by, notes)
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'customer', NOW(), ?, ?, ?, ?)";
        $new_user_stmt = $conn->prepare($new_user_sql);
        // Types: 1.user_id (s), 2.username (s), 3.email (s), 4.emp_id (s), 5.mobile (s), 6.password (s), 7.address (s), 8.city (s), 9.profile_photo (s), 10.date_of_adding (s), 11.reference_by (s), 12.notes (s)
        // Total variables needed: 12
        $new_user_stmt->bind_param("ssssssssssss", $new_id, $customer_name, $email, $rep_name, $mobile, $password, $address, $city, $filePath, $date_of_adding, $reference_by, $notes);
        $new_user_stmt->execute();
        $new_user_stmt->close();

        $conn->commit();

        // Send Email
        $subject = "Welcome to Our Service - Your Account Details";
        $message = "
        <html>
        <head><title>Customer Account Created</title></head>
        <body>
          <p>Dear $customer_name,</p>
          <p>Your account has been successfully created. Below are your login details:</p>
          <ul>
            <li><strong>Username:</strong> $customer_name</li>
            <li><strong>Email:</strong> $email</li>
            <li><strong>Password:</strong> $plain_password</li>
            <li><strong>Role:</strong> customer</li>
          </ul>
          <p>You can now log in using the link below:</p>
          <p><a href='https://appcims.com/whiteapp/index.php' target='_blank'>https://appcims.com/whiteapp/index.php</a></p>
          <p>Please keep this information safe and do not share it.</p>
          <p>If you need any further assistance, please do not hesitate to contact us.<br>
          Regards,<br>
          Admin Team<br>
          Email: support@appcims.com<br>
          Contact:+966 13 814 6861 / 2 Ext:110</p>
        </body>
        </html>";

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'mail.appcims.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'admin@appcims.com';
            $mail->Password = 'Makeitbetter@2025';
            $mail->SMTPSecure = 'ssl'; // or 'tls'
            $mail->Port = 465;

            $mail->setFrom('admin@appcims.com', 'Admin Team');
            $mail->addAddress($email);
            $mail->addCC('support@appcims.com');

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $message;

            $mail->send();
            echo "<script>alert('Customer added successfully and email sent!'); window.location.href = 'customer-list.php';</script>";
        } catch (Exception $e) {
            error_log("Email Error: " . $mail->ErrorInfo);
            echo "<script>alert('Customer added, but email failed to send.'); window.location.href = 'customer-list.php';</script>";
        }
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }

    $conn->close();
}
?>