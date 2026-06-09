<?php
// Include your database connection file
include_once('../../file/config.php');

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Start transaction
    mysqli_begin_transaction($conn);

    try {
    // Collect form data
    $examination_date = $_POST['examination_date'];
    $report_date = $_POST['report_date'];
    $report_no = $_POST['report_no'];
    $sticker_no = $_POST['sticker_no'];
    $project_no = $_POST['project_no'];
    $customer_name = $_POST['customer_name'];
    $customer_email = $_POST['customer_email'];
    $customer_mobile = $_POST['customer_mobile'];
    $inspector_name = $_POST['inspector_name'];
    $technical_manager = $_POST['technical_manager'];
    $quality_controller = $_POST['quality_controller'];
    $employer_address = $_POST['employer_address'];
    $equipment_description = $_POST['equipment_description'];
    $manufacturer = $_POST['manufacturer'];
    $model = $_POST['model'];
    $equipment_id = $_POST['equipment_id'];
    $equipment_serial_no = $_POST['equipment_serial_no'];
    $certificate_no = $_POST['certificate_no'];
    $jrn = $_POST['jrn'];
    $premises_address = $_POST['premises_address'];
    $safe_working_load = $_POST['safe_working_load'];
    $manufacture_date = $_POST['manufacture_date'];
    $last_exam_date = $_POST['last_exam_date'];
    $first_examination = $_POST['first_examination'];
    $installed_correctly = $_POST['installed_correctly'];
    $interval_6_months = $_POST['interval_6_months'];
    $interval_12_months = $_POST['interval_12_months'];
    $examination_scheme = $_POST['examination_scheme'];
    $exceptional_circumstances = $_POST['exceptional_circumstances'];
    $identification_any_part = $_POST['identification_any_part'];
    $common_text_area = $_POST['common_text_area'];
    $defect = $_POST['defect'];
    $defect_future = $_POST['defect_future'];
    $date_defect = $_POST['date_defect'];
    $repair_details = $_POST['repair_details'];
    $test_particulars = $_POST['test_particulars'];
    $equipment_fit = $_POST['equipment_fit'];
    // $name_qualifications_person = $_POST['name_qualifications_person'];
    // $report_making_person_qualifications = $_POST['report_making_person_qualifications'];
    // $authenticating_person_name = $_POST['authenticating_person_name'];
    $latest_date_exam = $_POST['latest_date_exam'];
    // $name_address_of_employer = $_POST['name_address_of_employer'];
    $created_at = date('Y-m-d H:i:s');
    
    // Check if certificate already exists for this project
    $check_query = "SELECT * FROM loadtest_certificate WHERE project_no = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("s", $project_no);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        throw new Exception("A load test certificate already exists for this project.");
    }

    // Prepare SQL statement
    $sql = "INSERT INTO loadtest_certificate (
                examination_date, report_date, report_no, sticker_no, project_no, customer_name, customer_email,
                customer_mobile, inspector_name, technical_manager, quality_controller,
                employer_address, equipment_description, manufacturer, model, 
                equipment_id, equipment_serial_no, certificate_no, jrn, 
                premises_address, safe_working_load, manufacture_date, last_exam_date, 
                first_examination, installed_correctly, interval_6_months, interval_12_months,
                examination_scheme, exceptional_circumstances, identification_any_part, common_text_area, defect, defect_future,
                date_defect, repair_details, test_particulars, equipment_fit, latest_date_exam,
                created_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    // Prepare statement
    $stmt = $conn->prepare($sql);
    
    // Bind parameters
    $stmt->bind_param('sssssssssssssssssssssssssssssssssssssss', 
        $examination_date, $report_date, $report_no, $sticker_no, $project_no, 
        $customer_name, $customer_email, $customer_mobile, $inspector_name, $technical_manager, $quality_controller,
        $employer_address, $equipment_description, $manufacturer, $model,
        $equipment_id, $equipment_serial_no, $certificate_no, $jrn,
        $premises_address, $safe_working_load,
        $manufacture_date, $last_exam_date, $first_examination, $installed_correctly,
        $interval_6_months, $interval_12_months, $examination_scheme, $exceptional_circumstances, $identification_any_part, $common_text_area, $defect, $defect_future,
        $date_defect, $repair_details, $test_particulars, $equipment_fit, $latest_date_exam,
        $created_at 
    );

    // Execute the query
    if (!$stmt->execute()) {
        throw new Exception("Error inserting certificate: " . $stmt->error);
    }

    // Update project status
    $update_query = "UPDATE project_info SET certificatestatus = 'Certificate Created' WHERE project_no = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param('s', $project_no);
    
    if (!$update_stmt->execute()) {
        throw new Exception("Error updating project status: " . $update_stmt->error);
    }

    // ========== NEW CODE FOR QUALITY CONTROLLER NOTIFICATION ==========
    // Create notification for quality controller
    $notification_message = "Load Test Certificate $certificate_no for project $project_no is ready for QC review";
    $currentDateTime = date('Y-m-d H:i:s');
    
    $notification_query = "INSERT INTO project_notifications 
                         (project_no, certificate_no, notification_message, quality_controller, created_at) 
                         VALUES (?, ?, ?, 'pending', ?)";
    $notification_stmt = $conn->prepare($notification_query);
    $notification_stmt->bind_param("ssss", $project_no, $certificate_no, $notification_message, $currentDateTime);
    
    if (!$notification_stmt->execute()) {
        throw new Exception("Failed to add QC notification: " . $notification_stmt->error);
    }
    // ========== END OF NEW CODE ==========

    // Commit transaction
    mysqli_commit($conn);

    $msg = "Load test certificate created successfully, project status updated, and QC notified.";
    header('Location: index.php?msg=' . urlencode($msg));
    exit();

} catch (Exception $e) {
    // Rollback transaction
    mysqli_rollback($conn);

    // Log the error (optional)
    error_log($e->getMessage());

    // Display error message
    echo "Error: " . $e->getMessage();
} finally {
    // Close the statements
    if (isset($check_stmt)) $check_stmt->close();
    if (isset($stmt)) $stmt->close();
    if (isset($update_stmt)) $update_stmt->close();
    if (isset($notification_stmt)) $notification_stmt->close();
    // Close the connection
    $conn->close();
}
}
?>