<?php
include_once('../../file/config.php');  // Include your database connection file

if (isset($_POST['save_all'])) {
    // Start transaction
    mysqli_begin_transaction($conn);

    try {
        // Retrieve form data
        $inspection_date = $_POST['inspection_date'];
        $certificate_no = $_POST['certificate_no'];
        $report_no = $_POST['report_no'];
        $jrn = $_POST['jrn'];
        $project_no = $_POST['project_no'];
        $customer_name = $_POST['customer_name'];
        $customer_email = $_POST['customer_email'];
        $mobile = $_POST['mobile'];
        $inspector = $_POST['inspector'];
        $technical_manager = $_POST['technical_manager'];
        $quality_controller = $_POST['quality_controller'];
        $vessel_name_location = $_POST['vessel_name_location'];
        $manufacturer = $_POST['manufacturer'];
        $crane_type = $_POST['crane_type'];
        $asset_number = $_POST['asset_number'];
        $serial_number = $_POST['serial_number'];
        $model = $_POST['model'];
        $manufacturing_year = $_POST['manufacturing_year'];
        $address = $_POST['address'];
        $capacity_swl = $_POST['capacity_swl'];
        $previous_test_date = $_POST['previous_test_date'];
        $crane_structure_condition = $_POST['crane_structure_condition'];
        $swinging_slewing_function = $_POST['swinging_slewing_function'];
        $hydraulic_pneumatic_system = $_POST['hydraulic_pneumatic_system'];
        $wire_ropes_condition = $_POST['wire_ropes_condition'];
        $boom_lifting_extending_retracting = $_POST['boom_lifting_extending_retracting'];
        $emergency_boom_lowering = $_POST['emergency_boom_lowering'];
        $auto_moment_limiter = $_POST['auto_moment_limiter'];
        $anti_two_block = $_POST['anti_two_block'];
        $winch_drum_lock_pawls = $_POST['winch_drum_lock_pawls'];
        $hook_block_assembly = $_POST['hook_block_assembly'];
        $boom_angle_indicator = $_POST['boom_angle_indicator'];
        $emergency_shutdown = $_POST['emergency_shutdown'];
        $latest_inspection_date = $_POST['latest_inspection_date'];
        $created_at = date('Y-m-d H:i:s');

        // Check if certificate already exists
                
        // Check if certificate already exists
        $check_query = "SELECT * FROM crane_health_check_certificate WHERE certificate_no = '$certificate_no' OR project_no = '$project_no'";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            throw new Exception("Certificate already exists for this project or with this certificate number.");
        }

        // Insert into the database
        $query = "INSERT INTO crane_health_check_certificate (
            inspection_date, certificate_no, report_no, jrn, project_no, customer_name, customer_email, mobile, inspector, technical_manager, quality_controller, vessel_name_location, manufacturer, crane_type, asset_number, serial_number, model, manufacturing_year, address, capacity_swl, previous_test_date, crane_structure_condition, swinging_slewing_function, hydraulic_pneumatic_system, wire_ropes_condition, boom_lifting_extending_retracting, emergency_boom_lowering, auto_moment_limiter, anti_two_block, winch_drum_lock_pawls, hook_block_assembly, boom_angle_indicator, emergency_shutdown, latest_inspection_date, created_at
        ) VALUES (
            '$inspection_date', '$certificate_no', '$report_no', '$jrn', '$project_no', '$customer_name', '$customer_email', '$mobile', '$inspector', '$technical_manager', '$quality_controller', '$vessel_name_location', '$manufacturer', '$crane_type', '$asset_number', '$serial_number', '$model', '$manufacturing_year', '$address', '$capacity_swl', '$previous_test_date', '$crane_structure_condition', '$swinging_slewing_function', '$hydraulic_pneumatic_system', '$wire_ropes_condition', '$boom_lifting_extending_retracting', '$emergency_boom_lowering', '$auto_moment_limiter', '$anti_two_block', '$winch_drum_lock_pawls', '$hook_block_assembly', '$boom_angle_indicator', '$emergency_shutdown', '$latest_inspection_date', '$created_at'
        )";

        if (!mysqli_query($conn, $query)) {
            throw new Exception("Error inserting certificate: " . mysqli_error($conn));
        }

        // Update the status in the project_info table
        $update_query = "UPDATE project_info SET certificatestatus = 'Certificate Created' WHERE project_no = '$project_no'";

        if (!mysqli_query($conn, $update_query)) {
            throw new Exception("Error updating project status: " . mysqli_error($conn));
        }

        // ========== NEW CODE FOR QUALITY CONTROLLER NOTIFICATION ==========
        // Create notification for quality controller
        $notification_message = "Certificate $certificate_no for project $project_no is ready for QC review";
        $currentDateTime = date('Y-m-d H:i:s');
        
        $notification_query = "INSERT INTO project_notifications 
                       (project_no, notification_message, quality_controller, created_at) 
                       VALUES (?, ?, ?, ?)";
$notification_stmt = $conn->prepare($notification_query);
$quality_controller = 'pending';
$notification_stmt->bind_param("ssss", $project_no, $notification_message, $quality_controller, $currentDateTime);

        
        
        // $notification_query = "INSERT INTO project_notifications 
        //                      (project_no, notification_message, created_at) 
        //                      VALUES (?, ?, 'pending', ?)";
        // $notification_stmt = $conn->prepare($notification_query);
        // $notification_stmt->bind_param("sss", $project_no, $notification_message, $currentDateTime);
        
        if (!$notification_stmt->execute()) {
            throw new Exception("Failed to add QC notification: " . $notification_stmt->error);
        }
        // ========== END OF NEW CODE ==========

        // Commit transaction
        mysqli_commit($conn);

        $msg = "Health check created successfully, project status updated, and QC notified.";
        header('Location: index.php?msg=' . urlencode($msg));
        exit();
    } catch (Exception $e) {
        // Rollback transaction
        mysqli_rollback($conn);

        // Log the error (optional)
        error_log($e->getMessage());

        // Display error message
        echo "Error: " . $e->getMessage();
    }
}
?>