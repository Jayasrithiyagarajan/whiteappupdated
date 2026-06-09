<?php
include_once('../file/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $projectNo = $_POST['projectNo'];
    $checklistNo = $_POST['checklistNo'] ?? null;
    $checklistType = $_POST['checklistType'] ?? null;
    $reportNo = $_POST['reportNo'] ?? null;
    $reviewStatus = $_POST['reviewStatus'];
    $commentBox = $_POST['commentBox'] ?? '';

    // Start transaction
    $conn->begin_transaction();

    try {
        // 1. Insert review into reviewer table
        $query = "INSERT INTO reviewer (project_no, checklist_no, checklist_type, report_no, review_status, comment) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssss", $projectNo, $checklistNo, $checklistType, $reportNo, $reviewStatus, $commentBox);

        if (!$stmt->execute()) {
            throw new Exception("Failed to insert review: " . $stmt->error);
        }

        // 2. Update review_status in project_info
        $updateQuery = "UPDATE project_info SET review_status = ? WHERE project_no = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("ss", $reviewStatus, $projectNo);

        if (!$updateStmt->execute()) {
            throw new Exception("Failed to update review status: " . $updateStmt->error);
        }

        // 3. Handle notifications
        $currentDateTime = date('Y-m-d H:i:s');
        
        if ($reviewStatus === 'Completed') {
            // Notification for document controller
            $notificationMessage = "Project $projectNo review completed. Document controller can now create document.";
            
            $notificationQuery = "INSERT INTO project_notifications 
                                 (project_no, notification_message, document_controller, created_at) 
                                 VALUES (?, ?, 'pending', ?)";
            $notificationStmt = $conn->prepare($notificationQuery);
            $notificationStmt->bind_param("sss", $projectNo, $notificationMessage, $currentDateTime);
            
            if (!$notificationStmt->execute()) {
                throw new Exception("Failed to add document controller notification: " . $notificationStmt->error);
            }
            $notificationStmt->close();
        } 
        elseif ($reviewStatus === 'Corrections Needed' || $reviewStatus === 'Rejected') {
            // Notification for inspector with comments
            $notificationMessage = "Project $projectNo requires corrections. ";
            $notificationMessage .= "Reviewer comments: " . (empty($commentBox) ? "No additional comments" : $commentBox);
            
            // Get inspector name from checklist
            $inspectorQuery = "SELECT inspected_by FROM checklist_information WHERE project_no = ?";
            $inspectorStmt = $conn->prepare($inspectorQuery);
            $inspectorStmt->bind_param("s", $projectNo);
            $inspectorStmt->execute();
            $inspectorResult = $inspectorStmt->get_result();
            $inspector = $inspectorResult->fetch_assoc()['inspected_by'];
            
            // Use inspector_name column for inspector notifications
            $notificationQuery = "INSERT INTO project_notifications 
                                 (project_no, notification_message, inspector_name, created_at) 
                                 VALUES (?, ?, ?, ?)";
            $notificationStmt = $conn->prepare($notificationQuery);
            $notificationStmt->bind_param("ssss", $projectNo, $notificationMessage, $inspector, $currentDateTime);
            
            if (!$notificationStmt->execute()) {
                throw new Exception("Failed to add inspector notification: " . $notificationStmt->error);
            }
            $notificationStmt->close();
        }

        $conn->commit();
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    } finally {
        if (isset($stmt)) $stmt->close();
        if (isset($updateStmt)) $updateStmt->close();
        $conn->close();
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>