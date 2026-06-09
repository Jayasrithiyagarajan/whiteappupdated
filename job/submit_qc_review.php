<?php
include_once('../file/config.php');
session_start();

// Load PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../inc/PHPMailer/src/PHPMailer.php';
require '../inc/PHPMailer/src/SMTP.php';
require '../inc/PHPMailer/src/Exception.php';


// Check if user is quality controller
if ($_SESSION['role'] !== 'quality controller') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

// Get form data
$projectNo = $_POST['qcProjectNo'] ?? '';
$checklistNo = $_POST['qcChecklistNo'] ?? '';
$checklistType = $_POST['qcChecklistType'] ?? '';
$reportNo = $_POST['qcReportNo'] ?? '';
$certificateType = $_POST['qcCertificateType'] ?? '';
$reviewStatus = $_POST['qcReviewStatus'] ?? '';

// New fields for detailed review
$checklistReviewStatus = $_POST['checklistReviewStatus'] ?? '';
$checklistComments = $_POST['checklistComments'] ?? '';
$reportReviewStatus = $_POST['reportReviewStatus'] ?? '';
$reportComments = $_POST['reportComments'] ?? '';
$certificateReviewStatus = $_POST['certificateReviewStatus'] ?? '';
$certificateComments = $_POST['certificateComments'] ?? '';
$reviewedBy = $_SESSION['username'] ?? '';

// Initialize notifications array
$notifications = [
    'inspector' => false,
    'document_controller' => false
];

// Start transaction
$conn->begin_transaction();

try {
    // 1. Save the main QC review record
    $query = "INSERT INTO qc_controller_reviews (
                project_no, checklist_no, checklist_type, report_no, 
                certificate_type, review_status, checklist_review_status,
                checklist_comments, report_review_status, report_comments,
                certificate_review_status, certificate_comments, reviewed_by
              ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param(
        "sssssssssssss", 
        $projectNo, $checklistNo, $checklistType, $reportNo,
        $certificateType, $reviewStatus, $checklistReviewStatus,
        $checklistComments, $reportReviewStatus, $reportComments,
        $certificateReviewStatus, $certificateComments, $reviewedBy
    );
    $stmt->execute();

    // 2. Update project status based on review
    if ($reviewStatus === 'Completed') {
        $updateQuery = "UPDATE project_info SET project_status = 'Completed' WHERE project_no = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("s", $projectNo);
        $updateStmt->execute();
        
        // Fetch email addresses and equipment details
$emailQuery = "SELECT 
                   ci.client_name, ci.equipment_type, 
                   ci.sticker_no, ci.inspected_by, 
                   u.email AS inspector_email,
                   pi.customer_email
               FROM checklist_information ci
               LEFT JOIN new_users u ON ci.inspected_by = u.username
               LEFT JOIN project_info pi ON ci.project_no = pi.project_no
               WHERE ci.project_no = ?";

 $emailStmt = $conn->prepare($emailQuery);
        $emailStmt->bind_param("s", $projectNo);
        $emailStmt->execute();
        $emailResult = $emailStmt->get_result();

        if ($emailResult->num_rows > 0) {
            $row = $emailResult->fetch_assoc();
            $clientEmail = $row['customer_email'] ?? '';
            $clientName = $row['client_name'] ?? 'Client';
            $equipmentType = $row['equipment_type'] ?? '';
            $stickerNo = $row['sticker_no'] ?? '';
            $inspectorEmail = $row['inspector_email'] ?? '';
            $adminEmail = 'admin@appcims.com';

            // Determine document link
            if ($equipmentType === 'NDT Equipment') {
                $link = "https://appcims.com/whiteapp/job/form.php?projectNo=" . urlencode($projectNo);
            } elseif ($equipmentType === 'Lifting Equipment') {
                $link = "https://appcims.com/whiteapp/job/form.php?stickerNo=" . urlencode($stickerNo);
            } else {
                $link = "https://appcims.com/whiteapp/job/";
            }

            // Compose the email body
            $subject = "Project $projectNo Marked as Completed";
            $message = "
                Dear $clientName,<br><br>
                The project <strong>$projectNo</strong> has been marked as <strong>Completed</strong>.<br>
                You can download the related documents using the link below:<br><br>
                <a href=\"$link\">$link</a><br><br>
                If you need any further assistance, please do not hesitate to contact us.<br><br>

Regards,<br>
Admin Team<br>
Email: support@appcims.com<br>
Contact:+966 13 814 6861 / 2 Ext:110
            ";

            // Send email using PHPMailer (SMTP)
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'mail.appcims.com'; // Replace with your SMTP host
                $mail->SMTPAuth = true;
                $mail->Username = 'admin@appcims.com'; // Your SMTP username
                $mail->Password = 'Makeitbetter@2025'; // Your SMTP password
                $mail->SMTPSecure = 'ssl'; // 'ssl' also valid if using port 465
                $mail->Port = 465;

                $mail->setFrom('admin@appcims.com', 'CIMS Notifications');
                $mail->addAddress($clientEmail, $clientName);
                if (!empty($adminEmail)) $mail->addCC($adminEmail);
                if (!empty($inspectorEmail)) $mail->addCC($inspectorEmail);

                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body    = $message;

                $mail->send();
            } catch (Exception $e) {
                error_log("Email Error: " . $mail->ErrorInfo);
            }
        }

    }

    // 3. Handle corrections and notifications
    // Checklist corrections
    if ($checklistReviewStatus === 'Corrections Needed') {
        // Update checklist status
        $updateChecklist = "UPDATE project_info SET checklist_status = 'Corrections Needed' WHERE project_no = ?";
        $stmtChecklist = $conn->prepare($updateChecklist);
        $stmtChecklist->bind_param("s", $projectNo);
        $stmtChecklist->execute();

        // Log correction request
        $logQuery = "INSERT INTO correction_logs (
                        project_no, document_type, comments, 
                        requested_by, requested_at
                     ) VALUES (?, 'checklist', ?, ?, NOW())";
        $logStmt = $conn->prepare($logQuery);
        $logStmt->bind_param("sss", $projectNo, $checklistComments, $reviewedBy);
        $logStmt->execute();

        $notifications['inspector'] = true;
    }

    // Report corrections
    if ($reportReviewStatus === 'Corrections Needed') {
        // Update report status
        $updateReport = "UPDATE project_info SET report_status = 'Corrections Needed' WHERE project_no = ?";
        $stmtReport = $conn->prepare($updateReport);
        $stmtReport->bind_param("s", $projectNo);
        $stmtReport->execute();

        // Log correction request
        $logQuery = "INSERT INTO correction_logs (
                        project_no, document_type, comments, 
                        requested_by, requested_at
                     ) VALUES (?, 'report', ?, ?, NOW())";
        $logStmt = $conn->prepare($logQuery);
        $logStmt->bind_param("sss", $projectNo, $reportComments, $reviewedBy);
        $logStmt->execute();

        $notifications['inspector'] = true;
    }

    // Certificate corrections
    if ($certificateReviewStatus === 'Corrections Needed') {
        // Update certificate status
        $updateCert = "UPDATE project_info SET certificatestatus = 'Corrections Needed' WHERE project_no = ?";
        $stmtCert = $conn->prepare($updateCert);
        $stmtCert->bind_param("s", $projectNo);
        $stmtCert->execute();

        // Log correction request
        $logQuery = "INSERT INTO correction_logs (
                        project_no, document_type, comments, 
                        requested_by, requested_at
                     ) VALUES (?, 'certificate', ?, ?, NOW())";
        $logStmt = $conn->prepare($logQuery);
        $logStmt->bind_param("sss", $projectNo, $certificateComments, $reviewedBy);
        $logStmt->execute();

        $notifications['document_controller'] = true;
    }

    // Commit transaction
    $conn->commit();

    // 4. Send notifications if needed
    if ($notifications['inspector']) {
        // Get inspector details
        $inspectorQuery = "SELECT inspected_by FROM checklist_information WHERE project_no = ?";
        $inspectorStmt = $conn->prepare($inspectorQuery);
        $inspectorStmt->bind_param("s", $projectNo);
        $inspectorStmt->execute();
        $inspectorResult = $inspectorStmt->get_result();

        if ($inspectorResult->num_rows > 0) {
            $inspector = $inspectorResult->fetch_assoc()['inspected_by'];
            // Get customer name for notification
            $customerQuery = "SELECT client_name FROM checklist_information WHERE project_no = ?";
            $customerStmt = $conn->prepare($customerQuery);
            $customerStmt->bind_param("s", $projectNo);
            $customerStmt->execute();
            $customerResult = $customerStmt->get_result();
            $customerName = $customerResult->fetch_assoc()['client_name'] ?? 'the customer';

            // Send notification to inspector
            $message = "Corrections needed for project $projectNo ($customerName) - Checklist/Report";
            $notifQuery = "INSERT INTO project_notifications (
                            project_no, inspector_name, customer_name, 
                            Notification_message, reviewer, document_controller, 
                            quality_controller, created_at
                          ) VALUES (?, ?, ?, ?, NULL, NULL, ?, NOW())";
            $notifStmt = $conn->prepare($notifQuery);
            $notifStmt->bind_param("sssss", $projectNo, $inspector, $customerName, $message, $reviewedBy);
            $notifStmt->execute();
        }
    }

    if ($notifications['document_controller']) {
        // Find document controller users
        $dcQuery = "SELECT username FROM users WHERE role = 'document controller'";
        $dcResult = $conn->query($dcQuery);
        
        // Get customer name for notification
        $customerQuery = "SELECT client_name FROM checklist_information WHERE project_no = ?";
        $customerStmt = $conn->prepare($customerQuery);
        $customerStmt->bind_param("s", $projectNo);
        $customerStmt->execute();
        $customerResult = $customerStmt->get_result();
        $customerName = $customerResult->fetch_assoc()['client_name'] ?? 'the customer';

        while ($dc = $dcResult->fetch_assoc()) {
            // Send notification to each document controller
            $message = "Certificate corrections needed for project $projectNo ($customerName)";
            $notifQuery = "INSERT INTO project_notifications (
                            project_no, inspector_name, customer_name, 
                            Notification_message, reviewer, document_controller, 
                            quality_controller, created_at
                          ) VALUES (?, NULL, ?, ?, NULL, ?, ?, NOW())";
            $notifStmt = $conn->prepare($notifQuery);
            $notifStmt->bind_param("sssss", $projectNo, $customerName, $message, $dc['username'], $reviewedBy);
            $notifStmt->execute();
        }
    }

    // Return success response
    echo json_encode([
        'success' => true,
        'reviewStatus' => $reviewStatus,
        'notifications' => $notifications
    ]);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode([
        'success' => false,
        'message' => 'Error processing QC review: ' . $e->getMessage()
    ]);
}
?>