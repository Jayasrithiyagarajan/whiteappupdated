<?php
// Include the database connection file
include_once('../../file/config.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_all'])) {
    // Start transaction
    mysqli_begin_transaction($conn);

    try {
        // Collect all form data
        $inspection_date = $_POST['inspection_date'];
        $certificate_no = $_POST['certificate_no'];
        $report_no = $_POST['report_no'];
        $jrn = $_POST['jrn'];
        $project_no = $_POST['project_no'];
        // $reference_no = $_POST['reference_no'];
        $location = $_POST['location'];
        $next_inspection_date = $_POST['next_inspection_date'];
        $customer_name = $_POST['customer_name'];
        $customer_email = $_POST['customer_email'];
        $mobile = $_POST['mobile'];
        $inspector = $_POST['inspector'];
        $technical_manager = $_POST['technical_manager'];
        $quality_controller = $_POST['quality_controller'];
        $report_date = $_POST['report_date'];
        $color_code = $_POST['color_code'];
        $applicable_standards = $_POST['applicable_standards'];
        // $employer_address = $_POST['employer_address'];
        // $premises_address = $_POST['premises_address'];
        $inspected_item_type = $_POST['inspected_item_type'];
        $identification_no = $_POST['identification_no'];
        $quantity = $_POST['quantity'];
        $description = $_POST['description'];
        $wll_swl = $_POST['wll_swl'];
        $last_exam_date = $_POST['last_exam_date'];
        $this_exam_date = $_POST['this_exam_date'];
        $next_exam_date = $_POST['next_exam_date'];
        $reason_for_exam = $_POST['reason_for_exam'];
        // $details_of_test = $_POST['details_of_test'];
        $status = $_POST['status'];
        $safe_to_use = $_POST['safe_to_use'];
        $grease_condition = $_POST['grease_condition'];
        $last_aft = $_POST['last_aft'];
        $last_stbd = $_POST['last_stbd'];
        $last_forward = $_POST['last_forward'];
        $last_port_side = $_POST['last_port_side'];
        $actual_aft = $_POST['actual_aft'];
        $actual_stbd = $_POST['actual_stbd'];
        $actual_forward = $_POST['actual_forward'];
        $actual_port_side = $_POST['actual_port_side'];
        $permitted_aft = $_POST['permitted_aft'];
        $permitted_stbd = $_POST['permitted_stbd'];
        $permitted_forward = $_POST['permitted_forward'];
        $permitted_port_side = $_POST['permitted_port_side'];
        $result_aft = $_POST['result_aft'];
        $result_stbd = $_POST['result_stbd'];
        $result_forward = $_POST['result_forward'];
        $result_port_side = $_POST['result_port_side'];
        $created_at = date('Y-m-d H:i:s');

        // Check if certificate already exists (both certificate_no and project_no)
        $check_query = "SELECT * FROM rocking_test_certificate WHERE certificate_no = ? OR project_no = ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param("ss", $certificate_no, $project_no);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            throw new Exception("Certificate already exists for this project or with this certificate number.");
        }

        // Prepare the SQL statement
        $sql = "INSERT INTO rocking_test_certificate (
            inspection_date, certificate_no, report_no, jrn, project_no, location, next_inspection_date,
            customer_name, customer_email, mobile, inspector, technical_manager, quality_controller, report_date, color_code, applicable_standards,
            inspected_item_type, identification_no, quantity, description, wll_swl, last_exam_date,
            this_exam_date, next_exam_date, reason_for_exam, status, safe_to_use, grease_condition, last_aft, last_stbd,
            last_forward, last_port_side, actual_aft, actual_stbd, actual_forward, actual_port_side, permitted_aft, permitted_stbd,
            permitted_forward, permitted_port_side, result_aft, result_stbd, result_forward, result_port_side, created_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
        )";

        // Prepare and bind parameters
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "sssssssssssssssssssssssssssssssssssssssssssss",
            $inspection_date, $certificate_no, $report_no, $jrn, $project_no, $location, $next_inspection_date,
            $customer_name, $customer_email, $mobile, $inspector, $technical_manager, $quality_controller, $report_date, $color_code, $applicable_standards,
            $inspected_item_type, $identification_no, $quantity, $description, $wll_swl, $last_exam_date,
            $this_exam_date, $next_exam_date, $reason_for_exam, $status, $safe_to_use, $grease_condition, $last_aft, $last_stbd,
            $last_forward, $last_port_side, $actual_aft, $actual_stbd, $actual_forward, $actual_port_side, $permitted_aft, $permitted_stbd,
            $permitted_forward, $permitted_port_side, $result_aft, $result_stbd, $result_forward, $result_port_side, $created_at
        );

        // Execute the statement
        if (!$stmt->execute()) {
            throw new Exception("Error inserting certificate: " . $stmt->error);
        }

        // Update the status in the project_info table
        $update_query = "UPDATE project_info SET certificatestatus = 'Certificate Created' WHERE project_no = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("s", $project_no);

        if (!$update_stmt->execute()) {
            throw new Exception("Error updating project status: " . $update_stmt->error);
        }

        // ========== QC NOTIFICATION ==========
        // Create notification for quality controller
        $notification_message = "Certificate $certificate_no for project $project_no is ready for QC review";
        $currentDateTime = date('Y-m-d H:i:s');
        
        $notification_query = "INSERT INTO project_notifications 
                             (project_no, notification_message, quality_controller, created_at) 
                             VALUES (?, ?, 'pending', ?)";
        $notification_stmt = $conn->prepare($notification_query);
        $notification_stmt->bind_param("sss", $project_no, $notification_message, $currentDateTime);
        
        if (!$notification_stmt->execute()) {
            throw new Exception("Failed to add QC notification: " . $notification_stmt->error);
        }
        // ========== END QC NOTIFICATION ==========

        // Commit transaction
        mysqli_commit($conn);

        $msg = "New record created successfully, project status updated, and QC notified.";
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
        if (isset($check_stmt)) {
            $check_stmt->close();
        }
        if (isset($stmt)) {
            $stmt->close();
        }
        if (isset($update_stmt)) {
            $update_stmt->close();
        }
        if (isset($notification_stmt)) {
            $notification_stmt->close();
        }
    }
}
?>