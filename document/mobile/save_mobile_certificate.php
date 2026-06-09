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
    $common_text_area = $_POST['common_text_area'];
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
    $defect = $_POST['defect'];
    $defect_future = $_POST['defect_future'];
    $date_defect = $_POST['date_defect'];
    $repair_details = $_POST['repair_details'];
    $test_particulars = $_POST['test_particulars'];
    $equipment_fit = $_POST['equipment_fit'];
    $latest_date_exam = $_POST['latest_date_exam'];
    $boom_length = $_POST['boom_length'];
    $boom_angle = $_POST['boom_angle'];
    $swl_test_weight = $_POST['swl_test_weight'];
    $radius = $_POST['radius'];
    $comments = $_POST['comments'];
    $boom_lifting = $_POST['boom_lifting'];
    $m_winch_hoist = $_POST['m_winch_hoist'];
    $aux_winch_hoist = $_POST['aux_winch_hoist'];
    $boom_extending = $_POST['boom_extending'];
    $outriggers = $_POST['outriggers'];
    $swings_slew = $_POST['swings_slew'];
    $hydraulic_system = $_POST['hydraulic_system'];
    $auto_moment_limiter = $_POST['auto_moment_limiter'];
    $swing_winch_brake = $_POST['swing_winch_brake'];
    $winch_drum_lock = $_POST['winch_drum_lock'];
    $leveling_device = $_POST['leveling_device'];
    $hook_block_assembly = $_POST['hook_block_assembly'];
    $boom_angle_indicator = $_POST['boom_angle_indicator'];
    $wind_speed_indicator = $_POST['wind_speed_indicator'];
    $created_at = date('Y-m-d H:i:s');


     // Check if certificate already exists for this project
     $check_query = "SELECT * FROM mobile_crane_loadtest WHERE project_no = ?";
     $check_stmt = $conn->prepare($check_query);
     $check_stmt->bind_param("s", $project_no);
     $check_stmt->execute();
     $check_result = $check_stmt->get_result();

     if ($check_result->num_rows > 0) {
         throw new Exception("A mobile crane load test certificate already exists for this project.");
     }


    // Prepare SQL statement
    $sql = "INSERT INTO mobile_crane_loadtest (
                examination_date, report_date, report_no, sticker_no, project_no, customer_name, customer_email, customer_mobile, inspector_name, technical_manager, quality_controller, 
                employer_address, equipment_description, manufacturer, model, 
                equipment_id, equipment_serial_no, certificate_no, jrn, common_text_area,  
                premises_address, safe_working_load, manufacture_date, last_exam_date, 
                first_examination, installed_correctly, interval_6_months, interval_12_months, examination_scheme, exceptional_circumstances, identification_any_part, defect, defect_future, date_defect, repair_details, test_particulars, equipment_fit, latest_date_exam, boom_length, boom_angle, swl_test_weight, radius, comments, boom_lifting, m_winch_hoist, aux_winch_hoist, boom_extending, outriggers, swings_slew, hydraulic_system, auto_moment_limiter, swing_winch_brake, winch_drum_lock, leveling_device, hook_block_assembly, boom_angle_indicator, wind_speed_indicator, created_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    // Prepare statement
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param('ssssssssssssssssssssssssssssssssssssssssssssssssssssssssss',
    $examination_date, $report_date, $report_no, $sticker_no, $project_no,
	$customer_name, $customer_email, $customer_mobile, $inspector_name, $technical_manager,
	$quality_controller, $employer_address, $equipment_description, $manufacturer, $model,
    $equipment_id, $equipment_serial_no, $certificate_no, $jrn, $common_text_area, 
    $premises_address, $safe_working_load, $manufacture_date, $last_exam_date, $first_examination,
	$installed_correctly, $interval_6_months, $interval_12_months, $examination_scheme, $exceptional_circumstances,
	$identification_any_part, $defect, $defect_future, $date_defect, $repair_details,
	$test_particulars, $equipment_fit, $latest_date_exam, $boom_length, $boom_angle,
	$swl_test_weight, $radius, $comments, $boom_lifting, $m_winch_hoist,
	$aux_winch_hoist, $boom_extending, $outriggers, $swings_slew, $hydraulic_system,
	$auto_moment_limiter, $swing_winch_brake, $winch_drum_lock, $leveling_device, $hook_block_assembly,
	$boom_angle_indicator, $wind_speed_indicator, $created_at
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
        $notification_message = "Mobile Crane Load Test Certificate $certificate_no for project $project_no is ready for QC review";
        $currentDateTime = date('Y-m-d H:i:s');
        
        $notification_query = "INSERT INTO project_notifications 
                     (project_no, notification_message, quality_controller, created_at) 
                     VALUES (?, ?, 'pending', ?)";
        $notification_stmt = $conn->prepare($notification_query);
        $notification_stmt->bind_param("sss", $project_no, $notification_message, $currentDateTime);

        
        if (!$notification_stmt->execute()) {
            throw new Exception("Failed to add QC notification: " . $notification_stmt->error);
        }
        // ========== END OF NEW CODE ==========

        // Commit transaction
        mysqli_commit($conn);

        $msg = "Mobile crane load test certificate created successfully, project status updated, and QC notified.";
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