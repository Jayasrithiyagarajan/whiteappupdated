<?php
// Include the database connection file
include_once('../../file/config.php');

// Check if the form is submitted
if (isset($_POST['save_all'])) {
    // Start transaction
    mysqli_begin_transaction($conn);

    try {
        // Collect all form data arrays
        $inspection_dates = $_POST['inspection_date'];
        $certificate_nos = $_POST['certificate_no'];
        $report_nos = $_POST['report_no'];
        $jrns = $_POST['jrn'];
        $project_nos = $_POST['project_no'];
        $locations = $_POST['location'];
        $next_inspection_dates = $_POST['next_inspection_date'];
        $customer_names = $_POST['customer_name'];
        $customer_emails = $_POST['customer_email'];
        $mobiles = $_POST['mobile'];
        $inspectors = $_POST['inspector'];
        $technical_managers = $_POST['technical_manager'];
        $quality_controllers = $_POST['quality_controller'];
        $inspected_items = $_POST['inspected_item'];
        $type_of_joints = $_POST['type_of_joint'];
        $inspection_methods = $_POST['inspection_method'];
        $other_inspection_methods = $_POST['other_inspection_method'];
        $calibration_details_arr = $_POST['calibration_details'];
        $gains = $_POST['gain'];
        $probe_frequencies = $_POST['probe_frequency'];
        $device_makers = $_POST['device_maker'];
        $models = $_POST['model'];
        $serial_nos = $_POST['serial_no'];
        $cable_types = $_POST['cable_type'];
        $sensor_types = $_POST['sensor_type'];
        $ref_block_types = $_POST['ref_block_type'];
        $ref_block_type_mm_bulk = isset($_POST['ref_block_type_mm']) ? $_POST['ref_block_type_mm'] : [];
        $materials = $_POST['material'];
        $description_of_inspections = $_POST['description_of_inspection'];
        $inspection_results = $_POST['inspection_result'];
        $reasons = $_POST['reason'];
        $other_reasons = $_POST['other_reason'];
        $created_at = date('Y-m-d H:i:s');
        
        // Upload image helper (updated for array handling)
        function uploadImageBulk($file_array, $index, $project_no, $suffix, $cert_no, $target_dir = 'uploads/') {
            if (!isset($file_array['name'][$index]) || $file_array['error'][$index] !== UPLOAD_ERR_OK) {
                return null;
            }

            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0755, true);
            }

            $ext = pathinfo($file_array['name'][$index], PATHINFO_EXTENSION);
            // Use certificate number in filename to ensure uniqueness across certificates in the same project
            $filename = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $cert_no) . '_' . $suffix . '.' . $ext;
            $target_path = $target_dir . $filename;

            if (!move_uploaded_file($file_array['tmp_name'][$index], $target_path)) {
                throw new Exception("Failed to upload image: " . $file_array['name'][$index]);
            }

            return $filename;
        }

        // Loop through each certificate
        foreach ($certificate_nos as $i => $cert_no) {
            $project_no = $project_nos[$i];
            
            // Upload images for this specific certificate
            $img_1 = uploadImageBulk($_FILES['image_1'], $i, $project_no, 'image1', $cert_no);
            $img_2 = uploadImageBulk($_FILES['image_2'], $i, $project_no, 'image2', $cert_no);
            $img_3 = uploadImageBulk($_FILES['image_3'], $i, $project_no, 'image3', $cert_no);

            // Ref block type mm handling
            $mm_current = isset($ref_block_type_mm_bulk[$i]) ? $ref_block_type_mm_bulk[$i] : [];
            $ref_block_mm_str = implode(',', $mm_current);

            // Insert into the database
            $sql = "INSERT INTO eddy_current_inspection (
                inspection_date, certificate_no, report_no, jrn, project_no, location, next_inspection_date, 
                customer_name, customer_email, mobile, inspector, technical_manager, quality_controller, inspected_item, type_of_joint, 
                inspection_method, other_inspection_method, calibration_details, gain, probe_frequency, device_maker, model, serial_no, cable_type, sensor_type, 
                ref_block_type, ref_block_type_mm, material, image_1, image_2, image_3, description_of_inspection, inspection_result, 
                reason, other_reason, created_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param(
                "ssssssssssssssssssssssssssssssssssss",
                $inspection_dates[$i], $cert_no, $report_nos[$i], $jrns[$i], $project_no, $locations[$i], $next_inspection_dates[$i],
                $customer_names[$i], $customer_emails[$i], $mobiles[$i], $inspectors[$i], $technical_managers[$i], $quality_controllers[$i], $inspected_items[$i], $type_of_joints[$i], 
                $inspection_methods[$i], $other_inspection_methods[$i], $calibration_details_arr[$i], $gains[$i], $probe_frequencies[$i], $device_makers[$i], $models[$i], $serial_nos[$i], $cable_types[$i], $sensor_types[$i],
                $ref_block_types[$i], $ref_block_mm_str, $materials[$i], $img_1, $img_2, $img_3, $description_of_inspections[$i], $inspection_results[$i],
                $reasons[$i], $other_reasons[$i], $created_at
            );

            if (!$stmt->execute()) {
                throw new Exception("Error inserting certificate #$cert_no: " . $stmt->error);
            }
            $stmt->close();
        }

        // Update the project_info table to mark the status (once for the whole project)
        $update_query = "UPDATE project_info SET certificatestatus = 'Certificate Created' WHERE project_no = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("s", $project_nos[0]);

        if (!$update_stmt->execute()) {
            throw new Exception("Error updating project status: " . $update_stmt->error);
        }
        $update_stmt->close();

        // Commit transaction
        mysqli_commit($conn);

        $count = count($certificate_nos);
        $msg = "$count Eddy Current Inspection Certificate(s) created successfully.";
        header('Location: index.php?msg=' . urlencode($msg));
        exit();

    } catch (Exception $e) {
        // Rollback transaction
        mysqli_rollback($conn);
        error_log($e->getMessage());
        echo "Error: " . $e->getMessage();
    } finally {
        $conn->close();
    }
}
?>