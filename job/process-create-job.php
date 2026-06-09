<?php
include_once('../file/config.php');
require '../inc/PHPMailer/src/PHPMailer.php';
require '../inc/PHPMailer/src/SMTP.php';
require '../inc/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $project_no = $conn->real_escape_string($_POST['project_no']);
    $customer_id = $conn->real_escape_string($_POST['customer_id']);
    $equipment_type = $conn->real_escape_string($_POST['equipment_type']);
    $sticker_status = $conn->real_escape_string($_POST['sticker_status']);
    $equipment_location = $conn->real_escape_string($_POST['equipment_location']);
    $equipment_id = $conn->real_escape_string($_POST['equipment_id']);
    $checklist_type = isset($_POST['checklist_type']) ? $conn->real_escape_string($_POST['checklist_type']) : '';
    $inspector_name = $conn->real_escape_string($_POST['inspector_name']);
    $customer_email = $conn->real_escape_string($_POST['email']);
    $customer_mobile = $conn->real_escape_string($_POST['mobile']);
    $creation_date_post = isset($_POST['creation_date']) ? $conn->real_escape_string($_POST['creation_date']) : date('Y-m-d');
    $creation_date = $creation_date_post . ' ' . date('H:i:s');
    $inspection_type = isset($_POST['inspection_type']) ? $conn->real_escape_string($_POST['inspection_type']) : '';

    // Fetch customer name
    $customerQuery = $conn->prepare("SELECT customer_name FROM customers WHERE id = ?");
    $customerQuery->bind_param("i", $customer_id);
    $customerQuery->execute();
    $customerQuery->bind_result($customer_name);
    $customerQuery->fetch();
    $customerQuery->close();

    if (!$customer_name) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Customer not found'
        ]);
        exit();
    }

    // Insert project
    $stmt = $conn->prepare("INSERT INTO project_info (project_no, creation_date, equipment_type, sticker_status, 
                          equipment_location, equipment_id, customer_id, customer_name, customer_email, 
                          customer_mobile, inspector_name, checklist_type, inspection_type)
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $stmt->bind_param("ssssssissssss", $project_no, $creation_date, $equipment_type, $sticker_status,
                     $equipment_location, $equipment_id, $customer_id, $customer_name, $customer_email,
                     $customer_mobile, $inspector_name, $checklist_type, $inspection_type);

    if ($stmt->execute()) {
        // Add notification
        $notification_message = "New project $project_no created for $customer_name";
        $notif_stmt = $conn->prepare("INSERT INTO project_notifications (project_no, inspector_name, 
                                    customer_name, Notification_message) 
                                    VALUES (?, ?, ?, ?)");
        $notif_stmt->bind_param("ssss", $project_no, $inspector_name, $customer_name, $notification_message);
        $notif_stmt->execute();
        $notif_stmt->close();

        // Send email
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
            $mail->addAddress($customer_email, $customer_name);
            $mail->addCC('support@appcims.com');

            // Add inspector email if available
            $inspector_email = '';
            $inspector_query = $conn->prepare("SELECT email FROM inspectors WHERE inspector_name = ?");
            $inspector_query->bind_param("s", $inspector_name);
            $inspector_query->execute();
            $inspector_query->bind_result($inspector_email);
            $inspector_query->fetch();
            $inspector_query->close();
            
            if (!empty($inspector_email)) {
                $mail->addCC($inspector_email);
            }

            $mail->isHTML(true);
            $mail->Subject = "New Project Created - $project_no";
            $mail->Body = "
<p>Hello $customer_name,</p>

<p>A new project has been created with the following details:</p>

<ul>
  <li><strong>Project Number:</strong> $project_no</li>
  <li><strong>Date of Creation:</strong> $creation_date</li>
  <li><strong>Equipment Id:</strong> $equipment_id</li>
  <li><strong>Checklist Type:</strong> $checklist_type</li>
  <li><strong>Customer Name:</strong> $customer_name</li>
  <li><strong>Inspector:</strong> $inspector_name</li>
  <li><strong>Location:</strong> $equipment_location</li>
  <li><strong>Inspection Type:</strong> $inspection_type</li>
</ul>

<p>If you need any further assistance, please do not hesitate to contact us.</p>

<p>Regards,<br>
Admin Team<br>
Email: support@appcims.com<br>
Contact: +966 13 814 6861 / 2 Ext:110</p>
";
            $mail->send();
        } catch (Exception $e) {
            error_log("Mail error: " . $mail->ErrorInfo);
        }

        echo json_encode([
            'status' => 'success',
            'message' => "Project created successfully!",
            'project_no' => $project_no
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Database error: ' . $stmt->error
        ]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method'
    ]);
}
?>