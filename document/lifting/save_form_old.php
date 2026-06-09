<?php
// include_once('../../file/config.php');  // Database connection

if (isset($_POST['save_data_lifting'])) {
    // Database connection
    include_once('../../file/config.php');  // Database connection
// Start transaction
mysqli_begin_transaction($conn);

try {
    // Loop through the dynamic fields
    $date_of_report = $_POST['date_of_report'];
    $report_no = $_POST['report_no'];
    $jrn = $_POST['jrn'];
    $color_code = $_POST['color_code'];
    $project_no = $_POST['project_no'];    
    $customer_name = $_POST['customer_name'];
    $customer_email = $_POST['customer_email'];
    $mobile = $_POST['mobile'];
    $inspector = $_POST['inspector'];
    $technical_manager = $_POST['technical_manager'];
    $quality_controller = $_POST['quality_controller'];
    $created_at = date('Y-m-d H:i:s');

    // Handle dynamic fields
    $certificate_no = $_POST['certificate_no'];
    $applicable_standards = $_POST['applicable_standards'];
    $employer_name_address = $_POST['employer_name_address'];
    $identification_no = $_POST['identification_no'];
    $wll_swl = $_POST['wll_swl'];
    $qty = $_POST['qty'];
    $type = $_POST['type'];
    $date_last_examination = $_POST['date_last_examination'];
    $description = $_POST['description'];
    // $manufacturer = $_POST['manufacturer'];
    // $size = $_POST['size'];
    // $length = $_POST['length'];
    // $color = $_POST['color'];
    // $ply = $_POST['ply'];
    $address_of_premises = $_POST['address_of_premises'];
    $next_examination_date = $_POST['next_examination_date'];
    $reason_for_examination = $_POST['reason_for_examination'];
    $date_of_this_examination = $_POST['date_of_this_examination'];
    $test_details = $_POST['test_details'];
    $status = $_POST['status'];
    $safe_to_use = $_POST['safe_to_use'];
    

    // Flag to check if any insert was successful
    $insert_successful = false;
    $certificate_numbers = []; // To store all created certificate numbers

    foreach ($certificate_no as $index => $cert_no) {
        // Prepare SQL to insert data
        $sql = "INSERT INTO lifting_gear_certificates (
            date_of_report,
            certificate_no,
            report_no,
            jrn,
            color_code,
            project_no,            
            customer_name,
            customer_email,
            mobile,
            inspector,
            technical_manager,
            quality_controller,
            created_at,
            employer_name_address,
            applicable_standards,
            identification_no,
            wll_swl,
            qty,
            type,
            date_last_examination,
            description,
            address_of_premises,
            next_examination_date,
            reason_for_examination,
            date_of_this_examination,
            test_details,
            status,
            safe_to_use
        ) VALUES (
            '$date_of_report',
            '$cert_no',
            '$report_no',
            '$jrn',
            '$color_code',
            '$project_no',
            '$customer_name',
            '$customer_email',
            '$mobile',
            '$inspector',
            '$technical_manager',
            '$quality_controller',
            '$created_at',            
            '{$employer_name_address[$index]}',
            '{$applicable_standards[$index]}',
            '{$identification_no[$index]}',
            '{$wll_swl[$index]}',
            '{$qty[$index]}',
            '{$type[$index]}',
            '{$date_last_examination[$index]}',
            '{$description[$index]}',
            '{$address_of_premises[$index]}',
            '{$next_examination_date[$index]}',
            '{$reason_for_examination[$index]}',
            '{$date_of_this_examination[$index]}',
            '{$test_details[$index]}',
            '{$status[$index]}',
            '{$safe_to_use[$index]}'
        )";
        // Execute query
        if (!mysqli_query($conn, $sql)) {
            throw new Exception("Error inserting certificate $cert_no: " . mysqli_error($conn));
        }
        
        $certificate_numbers[] = $cert_no;
        $insert_successful = true;
    }

    // If insertion was successful, update the project status
    if ($insert_successful) {
        $update_query = "UPDATE project_info SET certificatestatus = 'Certificate Created' WHERE project_no = '$project_no'";
        
        if (!mysqli_query($conn, $update_query)) {
            throw new Exception("Error updating project status: " . mysqli_error($conn));
        }

        // ========== NEW CODE FOR QUALITY CONTROLLER NOTIFICATION ==========
        // Create notification for quality controller
        $certificate_list = implode(", ", $certificate_numbers);
        $notification_message = "Lifting gear certificates ($certificate_list) for project $project_no are ready for QC review";
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
    }

    // Commit transaction
    mysqli_commit($conn);
    
    $msg = "Lifting gear certificates created successfully, project status updated, and QC notified.";
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
    // Close the database connection
    mysqli_close($conn);
}
}
?>