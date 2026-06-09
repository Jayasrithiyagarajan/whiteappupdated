<?php
session_start();
include '../file/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_assessment'])) {
    $assessment_id = (int)$_POST['assessment_id'];
    $license_number = mysqli_real_escape_string($conn, $_POST['license_number']);

    $no_of_equipment = (int)$_POST['no_of_equipment'];
    $training_program = mysqli_real_escape_string($conn, $_POST['training_program']);
    $date_of_assessment = $_POST['date_of_assessment'];
    $date_of_expiry = $_POST['date_of_expiry'];

    // Create upload directory if it doesn't exist
    $uploadDir = '../uploads/operator_assessments/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Start transaction
    $conn->begin_transaction();

    try {
        // Update main assessment table
        $update_sql = "UPDATE operator_assessments 
                       SET license_number = ?, 
                           training_program = ?,
                           date_of_assessment = ?, 
                           date_of_expiry = ?,
                           no_of_equipment = ?,
                           status = 'COMPLETED',
                           updated_at = NOW()
                       WHERE id = ?";
        
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ssssii", $license_number, $training_program, $date_of_assessment, $date_of_expiry, $no_of_equipment, $assessment_id);
        $stmt->execute();
        $stmt->close();

        // Delete existing equipment records for this assessment
        $delete_equipment_sql = "DELETE FROM operator_equipment WHERE assessment_id = ?";
        $delete_stmt = $conn->prepare($delete_equipment_sql);
        $delete_stmt->bind_param("i", $assessment_id);
        $delete_stmt->execute();
        $delete_stmt->close();

        // Insert equipment details
        if (isset($_POST['equipment']) && is_array($_POST['equipment'])) {
            $equipment_sql = "INSERT INTO operator_equipment 
                             (assessment_id, equipment_number, equipment_type, manufacturer, model, capacity) 
                             VALUES (?, ?, ?, ?, ?, ?)";
            $equipment_stmt = $conn->prepare($equipment_sql);

            foreach ($_POST['equipment'] as $eq_num => $eq_data) {
                if ($eq_num > $no_of_equipment) continue; // Only process selected number of equipment

                $equipment_type = mysqli_real_escape_string($conn, $eq_data['equipment_type']);
                $manufacturer = mysqli_real_escape_string($conn, $eq_data['manufacturer']);
                $model = mysqli_real_escape_string($conn, $eq_data['model']);
                $capacity = mysqli_real_escape_string($conn, $eq_data['capacity']);
                
                $equipment_stmt->bind_param("iissss", 
                    $assessment_id, 
                    $eq_num, 
                    $equipment_type, 
                    $manufacturer, 
                    $model, 
                    $capacity
                );
                $equipment_stmt->execute();
            }
            $equipment_stmt->close();
        }

        // Handle file uploads
        $document_types = [
            'doc_iqama_passport' => 'IQAMA_PASSPORT',
            'doc_license' => 'LICENSE',
            'doc_photo' => 'PHOTO',
            'doc_medical' => 'MEDICAL_CERT',
            'doc_previous_cert' => 'PREVIOUS_CERT',
            'doc_additional' => 'ADDITIONAL'
        ];

        $document_sql = "INSERT INTO operator_documents 
                        (assessment_id, document_type, file_path, original_filename) 
                        VALUES (?, ?, ?, ?)
                        ON DUPLICATE KEY UPDATE 
                        file_path = VALUES(file_path), 
                        original_filename = VALUES(original_filename)";
        
        $doc_stmt = $conn->prepare($document_sql);

        foreach ($document_types as $input_name => $doc_type) {
            $file_processed = false;
            $original_filename = "";
            $file_path = "";

            // Check for standard file upload
            if (isset($_FILES[$input_name]) && $_FILES[$input_name]['error'] === 0) {
                $file = $_FILES[$input_name];
                $original_filename = $file['name'];
                $file_extension = pathinfo($original_filename, PATHINFO_EXTENSION);
                $new_filename = $assessment_id . '_' . $doc_type . '_' . time() . '.' . $file_extension;
                $file_path = $uploadDir . $new_filename;
                
                if (move_uploaded_file($file['tmp_name'], $file_path)) {
                    $file_processed = true;
                }
            } 
            // Check for captured photo (only for PHOTO type)
            else if ($doc_type === 'PHOTO' && isset($_POST['captured_photo']) && !empty($_POST['captured_photo'])) {
                $captured_data = $_POST['captured_photo'];
                if (preg_match('/^data:image\/(\w+);base64,/', $captured_data, $type)) {
                    $captured_data = substr($captured_data, strpos($captured_data, ',') + 1);
                    $type = strtolower($type[1]); // jpg, png, etc
                    if (!in_array($type, ['jpg', 'jpeg', 'png'])) $type = 'jpg';
                    
                    $captured_data = base64_decode($captured_data);
                    if ($captured_data !== false) {
                        $original_filename = "captured_photo_" . time() . "." . $type;
                        $new_filename = $assessment_id . '_' . $doc_type . '_' . time() . '.' . $type;
                        $file_path = $uploadDir . $new_filename;
                        
                        if (file_put_contents($file_path, $captured_data)) {
                            $file_processed = true;
                        }
                    }
                }
            }

            if ($file_processed) {
                // Check if document already exists
                $check_sql = "SELECT id FROM operator_documents 
                             WHERE assessment_id = ? AND document_type = ?";
                $check_stmt = $conn->prepare($check_sql);
                $check_stmt->bind_param("is", $assessment_id, $doc_type);
                $check_stmt->execute();
                $check_result = $check_stmt->get_result();
                
                if ($check_result->num_rows > 0) {
                    // Update existing document
                    $update_doc_sql = "UPDATE operator_documents 
                                      SET file_path = ?, original_filename = ?, uploaded_at = NOW()
                                      WHERE assessment_id = ? AND document_type = ?";
                    $update_doc_stmt = $conn->prepare($update_doc_sql);
                    $update_doc_stmt->bind_param("ssis", $file_path, $original_filename, $assessment_id, $doc_type);
                    $update_doc_stmt->execute();
                    $update_doc_stmt->close();
                } else {
                    // Insert new document
                    $insert_doc_sql = "INSERT INTO operator_documents 
                                      (assessment_id, document_type, file_path, original_filename) 
                                      VALUES (?, ?, ?, ?)";
                    $insert_doc_stmt = $conn->prepare($insert_doc_sql);
                    $insert_doc_stmt->bind_param("isss", $assessment_id, $doc_type, $file_path, $original_filename);
                    $insert_doc_stmt->execute();
                    $insert_doc_stmt->close();
                }
                
                $check_stmt->close();
            }
        }

        $doc_stmt->close();

        // Commit transaction
        $conn->commit();

        $_SESSION['success_msg'] = "Assessment details saved successfully! Please proceed to the written exam.";
        header("Location: written-exam.php?id=" . $assessment_id);
        exit();

    } catch (Exception $e) {
        // Rollback on error
        $conn->rollback();
        $_SESSION['error_msg'] = "Error saving assessment: " . $e->getMessage();
        header("Location: fill-assessment.php?id=" . $assessment_id);
        exit();
    }

    $conn->close();
} else {
    header("Location: assessment-list.php");
    exit();
}
?>
