<?php
include_once('../../file/config.php'); // Include your database configuration file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Start transaction
    mysqli_begin_transaction($conn);

    try {
        // Retrieve form data
        $project_no = $_POST['project_no'];
        $inspection_date = $_POST['inspection_date'];
        $certificate_no = $_POST['certificate_no'];
        $report_no = $_POST['report_no'];
        $jrn = $_POST['jrn'];
        $location = $_POST['location'];
        $next_inspection_date = $_POST['next_inspection_date'];
        $customer_name = $_POST['customer_name'];
        $customer_email = $_POST['customer_email'];
        $mobile = $_POST['mobile'];
        $inspector = $_POST['inspector'];
        $technical_manager = $_POST['technical_manager'];
        $quality_controller = $_POST['quality_controller'];
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
        
        // Function to handle image uploads
        function handleImageUpload($file, $project_no, $field_name, $existing_filename, $target_dir = 'uploads/') {
            // If no new file was uploaded, return the existing filename
            if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
                return $existing_filename;
            }
            
            // If there's an error with the upload (other than no file selected)
            if ($file['error'] !== UPLOAD_ERR_OK) {
                throw new Exception("Error uploading file for {$field_name}");
            }
            
            // Create directory if it doesn't exist
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0755, true);
            }
            
            // Delete old file if it exists
            if (!empty($existing_filename) && file_exists($target_dir . $existing_filename)) {
                unlink($target_dir . $existing_filename);
            }
            
            // Generate new filename
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = $project_no . '_' . $field_name . '.' . $ext;
            $target_path = $target_dir . $filename;
            
            // Move uploaded file
            if (!move_uploaded_file($file['tmp_name'], $target_path)) {
                throw new Exception("Failed to upload image: " . $file['name']);
            }
            
            return $filename;
        }
        
        // First, get the existing image filenames from the database
        $get_images_query = "SELECT image_1, image_2, image_3 FROM liquid_penetrant_inspection WHERE project_no = ?";
        $get_images_stmt = $conn->prepare($get_images_query);
        $get_images_stmt->bind_param("s", $project_no);
        $get_images_stmt->execute();
        $get_images_stmt->bind_result($existing_image_1, $existing_image_2, $existing_image_3);
        $get_images_stmt->fetch();
        $get_images_stmt->close();
        
        // Handle image uploads
        $image_1 = handleImageUpload($_FILES['image_1'], $project_no, 'image1', $existing_image_1);
        $image_2 = handleImageUpload($_FILES['image_2'], $project_no, 'image2', $existing_image_2);
        $image_3 = handleImageUpload($_FILES['image_3'], $project_no, 'image3', $existing_image_3);
        
        // Prepare the SQL query to update the record
        $query = "UPDATE liquid_penetrant_inspection SET
                    inspection_date = ?,
                    certificate_no = ?,
                    report_no = ?,
                    jrn = ?,
                    location = ?,
                    next_inspection_date = ?,
                    customer_name = ?,
                    customer_email = ?,
                    mobile = ?,
                    inspector = ?,
                    technical_manager = ?,
                    quality_controller = ?,
                    material = ?,
                    surface_temperature = ?,
                    technique_procedure = ?,
                    brand = ?,
                    penetrant = ?,
                    penetrant_apply = ?,
                    dwell_time = ?,
                    cleaner = ?,
                    remove_apply = ?,
                    developer = ?,
                    developer_apply = ?,
                    developing_time = ?,
                    description = ?,
                    item_checked = ?,
                    results = ?,
                    condition_new = ?,
                    image_1 = ?,
                    image_2 = ?,
                    image_3 = ?
                  WHERE project_no = ?";
        
        // Prepare and execute the statement
        $stmt = $conn->prepare($query);
        $stmt->bind_param(
            "ssssssssssssssssssssssssssssssss",
            $inspection_date,
            $certificate_no,
            $report_no,
            $jrn,
            $location,
            $next_inspection_date,
            $customer_name,
            $customer_email,
            $mobile,
            $inspector,
            $technical_manager,
            $quality_controller,
            $material,
            $surface_temperature,
            $technique_procedure,
            $brand,
            $penetrant,
            $penetrant_apply,
            $dwell_time,
            $cleaner,
            $remove_apply,
            $developer,
            $developer_apply,
            $developing_time,
            $description,
            $item_checked,
            $results,
            $condition_new,
            $image_1,
            $image_2,
            $image_3,
            $project_no
        );
        
        if (!$stmt->execute()) {
            throw new Exception("Error updating record: " . $stmt->error);
        }
        
        // Commit transaction
        mysqli_commit($conn);
        
        $msg = "Data updated successfully.";
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
        if (isset($stmt) && $stmt instanceof mysqli_stmt && $stmt->error === '') {
    $stmt->close();
        }
        if (isset($get_images_stmt)) {
            $get_images_stmt->close();
        }
        // Close the connection
        $conn->close();
    }
} else {
    // If the form is not submitted, redirect
    header("Location: index.php");
    exit();
}
?>