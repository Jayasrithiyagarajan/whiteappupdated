<?php
include './config.php';

require '../inc/PHPMailer/src/PHPMailer.php';
require '../inc/PHPMailer/src/SMTP.php';
require '../inc/PHPMailer/src/Exception.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


//use PHPMailer\PHPMailer\PHPMailer;
//use PHPMailer\PHPMailer\Exception;

//require '../vendor/autoload.php'; // Adjust if needed

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_id = $conn->real_escape_string($_POST['customer_id']);
    $equipment_type = $conn->real_escape_string($_POST['equipment_type']);
    $sticker_status = $conn->real_escape_string($_POST['sticker_status']);
    $equipment_location = $conn->real_escape_string($_POST['equipment_location']);
    $equipment_id = $conn->real_escape_string($_POST['equipment_id']);
    $checklist_type = $conn->real_escape_string($_POST['checklist_type']);
    $inspector_name = $conn->real_escape_string($_POST['inspector_name']);

    // Fetch customer details
    $customerQuery = $conn->prepare("SELECT customer_name, email, mobile FROM customers WHERE id = ?");
    $customerQuery->bind_param("i", $customer_id);
    $customerQuery->execute();
    $customerQuery->bind_result($customer_name, $customer_email, $customer_mobile);

    if (!$customerQuery->fetch()) {
        header("Location: ../job/create-job.php?status=error&message=" . urlencode("Customer not found. Please select a valid customer."));
        exit();
    }
    $customerQuery->close();

    // Auto-generate project number
    $project_no_query = "SELECT MAX(CAST(SUBSTRING(project_no, 5) AS UNSIGNED)) AS max_project_no FROM project_info";
    $result = $conn->query($project_no_query);
    $row = $result->fetch_assoc();
    $last_project_no = $row['max_project_no'];
    $project_no = "CIMS" . str_pad(($last_project_no ? $last_project_no + 1 : 1), 3, "0", STR_PAD_LEFT);

    // Insert into project_info
    $creation_date = date('Y-m-d H:i:s');
    $stmt = $conn->prepare("INSERT INTO project_info (project_no, creation_date, equipment_type, sticker_status, equipment_location, equipment_id, customer_id, customer_name, customer_email, customer_mobile, inspector_name, checklist_type)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "ssssssisssss",
        $project_no,
        $creation_date,
        $equipment_type,
        $sticker_status,
        $equipment_location,
        $equipment_id,
        $customer_id,
        $customer_name,
        $customer_email,
        $customer_mobile,
        $inspector_name,
        $checklist_type
    );

    if ($stmt->execute()) {
        // Notification message
        $notification_message = "A new project ($project_no) has been created for customer $customer_name and assigned to inspector $inspector_name.";

        // Insert into project_notifications
        $notif_stmt = $conn->prepare("INSERT INTO project_notifications (project_no, inspector_name, customer_name, Notification_message, reviewer, document_controller, quality_controller) 
                                      VALUES (?, ?, ?, ?, NULL, NULL, NULL)");
        $notif_stmt->bind_param("ssss", $project_no, $inspector_name, $customer_name, $notification_message);
        $notif_stmt->execute();
        $notif_stmt->close();

        // Fetch inspector email
        $inspector_email = '';
        $inspector_query = $conn->prepare("SELECT email FROM inspectors WHERE inspector_name = ?");
        $inspector_query->bind_param("s", $inspector_name);
        $inspector_query->execute();
        $inspector_query->bind_result($inspector_email);
        $inspector_query->fetch();
        $inspector_query->close();

        // Email Configuration
        $subject = "New Project Created - $project_no";
        $message = "
Hello $customer_name,

A new project has been created with the following details:

Project Number: $project_no
Date of Creation: " . date('d-m-Y', strtotime($creation_date)) . "
Equipment Id: $equipment_id
Checklist Type: $checklist_type
Customer Name: $customer_name
Inspector: $inspector_name
Location: $equipment_location

If you need any further assistance, please do not hesitate to contact us.

Regards,
Admin Team

Email: support@appcims.com
Contact:+966 13 814 6861 / 2 Ext:110
";

        // Send email via SMTP using PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'mail.appcims.com'; // Replace with your SMTP host (e.g. smtp.gmail.com)
            $mail->SMTPAuth = true;
            $mail->Username = 'admin@appcims.com'; // Replace with your SMTP username
            $mail->Password = 'Makeitbetter@2025'; // Replace with your SMTP password or app password
            $mail->SMTPSecure = 'ssl'; // Or 'ssl' if required
            $mail->Port = 465; // Or 465 for SSL

            $mail->setFrom('admin@appcims.com', 'Admin Team');
            $mail->addAddress($customer_email, $customer_name);
            $mail->addCC('support@appcims.com');
            if (!empty($inspector_email)) {
                $mail->addCC($inspector_email);
            }

            $mail->isHTML(false); // Set to true if using HTML
            $mail->Subject = $subject;
            $mail->Body = $message;

            $mail->send();
        } catch (Exception $e) {
            error_log("Mail error [project: $project_no]: {$mail->ErrorInfo}");
        }

        // Redirect to success page
        header("Location: ../job/overall-job-list.php?status=success");
        exit();

    } else {
        error_log("Database Insert Error: " . $stmt->error);
        header("Location: ../job/create-job.php?status=error&message=" . urlencode("Failed to create project. Please try again."));
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>