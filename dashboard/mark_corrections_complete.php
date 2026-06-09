<?php
include_once('../file/config.php');
include_once('../inc/function.php');

// Check if request is valid
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['project_no'])) {
    die(json_encode(['success' => false, 'message' => 'Invalid request']));
}

$projectNo = $_POST['project_no'];
$inspector = $_POST['inspector'];

// 1. Update project status
$updateQuery = "UPDATE project_info SET 
                corrections_completed = TRUE,
                correction_completed_date = NOW(),
                needs_correction = FALSE
                WHERE project_no = '$projectNo'";
mysqli_query($conn, $updateQuery);

// 2. Find who requested the corrections (the reviewer)
$reviewerQuery = "SELECT reviewed_by FROM project_reviews 
                 WHERE project_no = '$projectNo'
                 ORDER BY review_date DESC LIMIT 1";
$reviewerResult = mysqli_query($conn, $reviewerQuery);
$reviewer = $reviewerResult->fetch_assoc()['reviewed_by'];

// 3. Create notification for reviewer
$message = "Corrections completed for project $projectNo by inspector $inspector";
$notificationQuery = "INSERT INTO project_notifications 
                     (project_no, inspector_name, Notification_message, notification_type, created_at)
                     VALUES ('$projectNo', '$reviewer', '$message', 'completion', NOW())";
mysqli_query($conn, $notificationQuery);

// 4. Mark original correction notification as read
$markReadQuery = "UPDATE project_notifications SET is_read = TRUE
                 WHERE project_no = '$projectNo' 
                 AND notification_type = 'correction'";
mysqli_query($conn, $markReadQuery);

// 5. Optional: Send email to reviewer
// sendNotificationEmail($reviewerEmail, "Corrections Completed", $message);

echo json_encode(['success' => true, 'message' => 'Reviewer notified']);
?>