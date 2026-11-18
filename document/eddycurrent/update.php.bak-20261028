<?php
include_once('../../file/config.php');

if (isset($_POST['update_all'])) {
    // Collect form data
    $inspection_date = $_POST['inspection_date'];
    $certificate_no = $_POST['certificate_no'];
    $report_no = $_POST['report_no'];
    $jrn = $_POST['jrn'];
    $project_no = $_POST['project_no'];
    $location = $_POST['location'];
    $next_inspection_date = $_POST['next_inspection_date'];
    $customer_name = $_POST['customer_name'];
    $customer_email = $_POST['customer_email'];
    $mobile = $_POST['mobile'];
    $inspector = $_POST['inspector'];
    $technical_manager = $_POST['technical_manager'];
    $quality_controller = $_POST['quality_controller']; 
    $inspected_item = $_POST['inspected_item'];
    $type_of_joint = $_POST['type_of_joint'];
    $inspection_method = $_POST['inspection_method'];
    $other_inspection_method = $_POST['other_inspection_method'];
    $calibration_details = $_POST['calibration_details'];
    $gain = $_POST['gain'];
    $probe_frequency = $_POST['probe_frequency'];
    $device_maker = $_POST['device_maker'];
    $model = $_POST['model'];
    $serial_no = $_POST['serial_no'];
    $cable_type = $_POST['cable_type'];
    $sensor_type = $_POST['sensor_type'];
    $ref_block_type = $_POST['ref_block_type'];
    $ref_block_type_mm = isset($_POST['ref_block_type_mm']) ? implode(',', $_POST['ref_block_type_mm']) : '';
    $material = $_POST['material'];
    $description_of_inspection = $_POST['description_of_inspection'];
    $inspection_result = $_POST['inspection_result'];
    $reason = $_POST['reason'];
    $other_reason = $_POST['other_reason'];

    // Fetch old image names using certificate_no
    $stmtOld = $conn->prepare("SELECT image_1, image_2, image_3 FROM eddy_current_inspection WHERE certificate_no = ?");
    $stmtOld->bind_param("s", $certificate_no);
    $stmtOld->execute();
    $stmtOld->bind_result($existing_1, $existing_2, $existing_3);
    $stmtOld->fetch();
    $stmtOld->close();

    // Helper for image uploads with certificate_no naming
    function handleImageUpload($fileInput, $existingFile, $certificate_no) {
        $uploadDir = 'uploads/';
        
        if (!isset($_FILES[$fileInput]) || $_FILES[$fileInput]['error'] === UPLOAD_ERR_NO_FILE) {
            return $existingFile;
        }
        
        if ($_FILES[$fileInput]['error'] !== UPLOAD_ERR_OK) {
            return $existingFile;
        }
        
        // Delete the old file if it exists
        if ($existingFile && file_exists($uploadDir . $existingFile)) {
            unlink($uploadDir . $existingFile);
        }
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $imageNumber = substr($fileInput, -1);
        $ext = pathinfo($_FILES[$fileInput]['name'], PATHINFO_EXTENSION);
        // Use certificate number for uniqueness
        $safe_cert_no = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $certificate_no);
        $newFilename = $safe_cert_no . '_image' . $imageNumber . '.' . $ext;
        $targetPath = $uploadDir . $newFilename;
        
        if (move_uploaded_file($_FILES[$fileInput]['tmp_name'], $targetPath)) {
            return $newFilename;
        }
        
        return $existingFile;
    }

    // Process uploaded images
    $image_1 = handleImageUpload('image_1', $existing_1, $certificate_no);
    $image_2 = handleImageUpload('image_2', $existing_2, $certificate_no);
    $image_3 = handleImageUpload('image_3', $existing_3, $certificate_no);

    // Update query using certificate_no as identifier
    $sql = "UPDATE eddy_current_inspection SET 
            inspection_date = ?, report_no = ?, jrn = ?,             
            location = ?, next_inspection_date = ?, 
            customer_name = ?, customer_email = ?, mobile = ?, 
            inspector = ?, technical_manager = ?, quality_controller = ?, inspected_item = ?, 
            type_of_joint = ?, inspection_method = ?, other_inspection_method = ?,
            calibration_details = ?, gain = ?, probe_frequency = ?, 
            device_maker = ?, model = ?, serial_no = ?, cable_type = ?, 
            sensor_type = ?, ref_block_type = ?, ref_block_type_mm = ?, material = ?, 
            image_1 = ?, image_2 = ?, image_3 = ?, 
            description_of_inspection = ?, inspection_result = ?, reason = ?, other_reason = ?
            WHERE certificate_no = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssssssssssssssssssssssssss", 
        $inspection_date, $report_no, $jrn, 
        $location, $next_inspection_date, 
        $customer_name, $customer_email, $mobile, 
        $inspector, $technical_manager, $quality_controller,
        $inspected_item, $type_of_joint, $inspection_method, $other_inspection_method,
        $calibration_details, $gain, $probe_frequency, 
        $device_maker, $model, $serial_no, $cable_type, 
        $sensor_type, $ref_block_type, $ref_block_type_mm, $material, 
        $image_1, $image_2, $image_3, 
        $description_of_inspection, $inspection_result, $reason, $other_reason,
        $certificate_no
    );

    if ($stmt->execute()) {
        $msg = "Eddy Current Inspection Certificate updated successfully.";
        header('Location: index.php?msg=' . urlencode($msg));
        exit();
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>