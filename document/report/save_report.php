<?php
ob_start(); // Start output buffering

// Include the database connection file
include_once('../../file/config.php'); // Assuming your database connection is here

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $project_no = $_POST['project_no'];
    // $jrn = $_POST['jrn'];
    $checklist_no = $_POST['checklist_no'];
    $report_no = $_POST['report_no'];   
    $issued_company = $_POST['issued_company'];
    $client_company_name = $_POST['client_company_name'];
    //$client_company_address = $_POST['client_company_address'];
    $manufacturer = $_POST['manufacturer'];
    $model = $_POST['model'];
    $type = $_POST['type'];
    $prev_sticker_no = $_POST['prev_sticker_no'];
    $issued_by = $_POST['issued_by'];
    $capacity = $_POST['capacity'];
    $equipment_id_no = $_POST['equipment_id_no'];
    $equipment_serial_no = $_POST['equipment_serial_no'];
    $location = $_POST['location'];
    $date_of_inspection = $_POST['date_of_inspection'];
    $next_inspection_due_date = $_POST['next_inspection_due_date'];
    $inspection_status = $_POST['inspection_status'];
    $sticker_number_issued = $_POST['sticker_number_issued'];
    $no_of_equipments_inspected = $_POST['no_of_equipments_inspected'];
    $created_at = date('Y-m-d H:i:s');
    
    // Combine deficiencies and corrective actions into an array
    $deficiency = $_POST['deficiency'];
    $corrective_action = $_POST['corrective_action'];

    // $deficiencyData = [];
    // foreach ($deficiencies as $index => $deficiency) {
    //     $corrective_action = $corrective_actions[$index];
    //     $deficiencyData[] = [
    //         'deficiency' => $deficiency,
    //         'corrective_action' => $corrective_action,
    //     ];
    // }

    // Encode the deficiency data as JSON
    // $deficiencyJson = json_encode($deficiencyData);

    // Validate Date Format (optional but recommended)
    if (!strtotime($date_of_inspection) || !strtotime($next_inspection_due_date)) {
        echo "Invalid date format provided.";
        exit;
    }

    // SQL Query using Prepared Statement to insert data into the 'reports' table
    $insertQuery = "INSERT INTO reports (
        project_no, checklist_no, report_no, issued_company, client_company_name, manufacturer, model, type, prev_sticker_no, issued_by, capacity, 
        equipment_id_no, equipment_serial_no, location, date_of_inspection, next_inspection_due_date, 
        inspection_status, sticker_number_issued, no_of_equipments_inspected, created_at, deficiency, corrective_action) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare statement
    $stmt = $conn->prepare($insertQuery);

    // Bind parameters
    $stmt->bind_param("ssssssssssssssssssssss", 
        $project_no, $checklist_no, $report_no, $issued_company, $client_company_name, $manufacturer, $model, $type, $prev_sticker_no, $issued_by, $capacity,
        $equipment_id_no, $equipment_serial_no, $location, $date_of_inspection, $next_inspection_due_date, 
        $inspection_status, $sticker_number_issued, $no_of_equipments_inspected, $created_at, $deficiency, $corrective_action);

    // Execute statement and check for errors
    if ($stmt->execute()) {
        // Update the project status in 'project_info' table
        $updateQuery = "UPDATE project_info SET report_status = 'Generated' WHERE project_no = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("s", $project_no);
        $updateStmt->execute();
        $updateStmt->close();

        // Verify if the sticker_number_issued exists in the stickers table
        $stickerCheckQuery = "SELECT * FROM stickers WHERE sticker_start_no = ?";
        $stickerCheckStmt = $conn->prepare($stickerCheckQuery);
        $stickerCheckStmt->bind_param("s", $sticker_number_issued);
        $stickerCheckStmt->execute();
        $stickerResult = $stickerCheckStmt->get_result();

        if ($stickerResult->num_rows > 0) {
            // Sticker exists, update the existing row in the stickers table
            $stickerUpdateQuery = "UPDATE stickers 
                                   SET project_no = ?, inspection_date = ?, next_inspection_due_date = ?, equipment_no = ?, equipment_serial_no = ?, sticker_status = ?, status = 'Active'
                                   WHERE sticker_start_no = ?";
            $stickerUpdateStmt = $conn->prepare($stickerUpdateQuery);
            $stickerUpdateStmt->bind_param("sssssss", $project_no, $date_of_inspection, $next_inspection_due_date, $equipment_id_no, $equipment_serial_no, $inspection_status, $sticker_number_issued);
            $stickerUpdateStmt->execute();
            $stickerUpdateStmt->close();
        }

        $stickerCheckStmt->close();

        // Insert notification for reviewers
        $notification_message = "Project $project_no is ready for reviewing.";
        $insertNotificationQuery = "INSERT INTO project_notifications (project_no, notification_message, reviewer, created_at) VALUES (?, ?, NULL, ?)";
        $notificationStmt = $conn->prepare($insertNotificationQuery);
        $notificationStmt->bind_param("sss", $project_no, $notification_message, $created_at);

        if ($notificationStmt->execute()) {
            echo "Report created, project updated, sticker updated, and notification sent.";
        } else {
            error_log("Error inserting notification: " . $notificationStmt->error);
            echo "Error sending notification.";
        }

        $notificationStmt->close();
        
        // Redirect with success message
        header('Location: index.php?msg=' . urlencode("Report created successfully."));
        exit;
    } else {
        error_log("Error inserting data: " . $stmt->error);
        echo "Error saving report. Please try again.";
    }

    // Close statements and connection
    $stmt->close();
    $conn->close();
}
ob_end_flush();
?>