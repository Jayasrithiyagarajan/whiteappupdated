<?php
include_once('../../file/config.php'); // Include your database configuration file

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_all'])) {
    // Start transaction
    mysqli_begin_transaction($conn);

    try {
        // Retrieve form data
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
        // $standard = $_POST['standard'];
        $material = $_POST['material'];
        $surface_temperature = $_POST['surface_temperature'];
        $technique_procedure = $_POST['technique_procedure'];
        $brand = $_POST['brand'];
        $penetrant = $_POST['penetrant'];
        $penetrant_apply = $_POST['penetrant_apply'];
        $dwell_time = $_POST['dwell_time'];
        $cleaner = $_POST['cleaner'];
        $remove_apply = $_POST['remove_apply'];
        $developer = $_POST['developer'];
        $developer_apply = $_POST['developer_apply'];
        $developing_time = $_POST['developing_time'];
        $description = $_POST['description'];
        $item_checked = $_POST['item_checked'];
        $results = $_POST['results'];
        $condition_new = $_POST['condition_new'];
        // $description_1 = $_POST['description_1'];
        // $description_2 = $_POST['description_2'];
        // $description_3 = $_POST['description_3'];
        
        
        // Upload image helper
        function uploadImage($file, $project_no, $suffix, $target_dir = 'uploads/') {
            if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
                return null;
            }

            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0755, true);
            }

            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = $project_no . '_' . $suffix . '.' . $ext;
            $target_path = $target_dir . $filename;

            if (!move_uploaded_file($file['tmp_name'], $target_path)) {
                throw new Exception("Failed to upload image: " . $file['name']);
            }

            return $filename;
        }

        // Upload images
        $image_1 = uploadImage($_FILES['image_1'], $project_no, 'image1');
        $image_2 = uploadImage($_FILES['image_2'], $project_no, 'image2');
        $image_3 = uploadImage($_FILES['image_3'], $project_no, 'image3');

        // Check for duplication based on project_no
        $check_query = "SELECT * FROM liquid_penetrant_inspection WHERE project_no = ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param("s", $project_no);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            throw new Exception("A certificate already exists for this project.");
        }

        // Insert data into the database
        $sql = "INSERT INTO liquid_penetrant_inspection (
            inspection_date, certificate_no, report_no, jrn, project_no, location, next_inspection_date, 
            customer_name, customer_email, mobile, inspector, technical_manager, quality_controller, material, surface_temperature, 
            technique_procedure, brand, penetrant, penetrant_apply, dwell_time, cleaner, remove_apply, developer, developer_apply, 
            developing_time, description, item_checked, results, condition_new, image_1, image_2, image_3, created_at
        ) VALUES (
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW()
        )";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "ssssssssssssssssssssssssssssssss", 
            $inspection_date, $certificate_no, $report_no, $jrn, $project_no, $location, $next_inspection_date, 
            $customer_name, $customer_email, $mobile, $inspector, $technical_manager, $quality_controller, $material, $surface_temperature, 
            $technique_procedure, $brand, $penetrant, $penetrant_apply, $dwell_time, $cleaner, $remove_apply, $developer, $developer_apply, 
            $developing_time, $description, $item_checked, $results, $condition_new, 
            $image_1, $image_2, $image_3
        );

        if (!$stmt->execute()) {
            throw new Exception("Error inserting certificate: " . $stmt->error);
        }

        // Update the project_info table to mark the certificate as created
        $update_query = "UPDATE project_info SET certificatestatus = 'Certificate Created' WHERE project_no = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("s", $project_no);

        if (!$update_stmt->execute()) {
            throw new Exception("Error updating project status: " . $update_stmt->error);
        }


        // ========== NEW CODE FOR QUALITY CONTROLLER NOTIFICATION ==========
        // Create notification for quality controller
        $notification_message = "Liquid Penetrant Inspection Certificate $certificate_no for project $project_no is ready for QC review";
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

        $msg = "Data saved successfully, and project status updated.";
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
        // Close the connection
        $conn->close();
    }
}
?>