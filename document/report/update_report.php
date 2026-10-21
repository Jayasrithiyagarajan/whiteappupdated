<?php
include_once('../../inc/function.php');
include_once('../../file/config.php');
session_start(); // if not already handled in function.php

// Check session or login status
if (!isset($_SESSION['user_id'])) {
    die("Session expired. Please log in again.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $report_no = mysqli_real_escape_string($conn, $_POST['report_no']);
    $model = mysqli_real_escape_string($conn, $_POST['model']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $prev_sticker_no = mysqli_real_escape_string($conn, $_POST['prev_sticker_no']);
    $issued_company = mysqli_real_escape_string($conn, $_POST['issued_company']);
    $date_of_inspection = isset($_POST['date_of_inspection']) ? mysqli_real_escape_string($conn, $_POST['date_of_inspection']) : '';
    $next_inspection_due_date = mysqli_real_escape_string($conn, $_POST['next_inspection_due_date']);
    $inspection_status = mysqli_real_escape_string($conn, $_POST['inspection_status']);
    $deficiency = mysqli_real_escape_string($conn, $_POST['deficiency']);
    $corrective_action = mysqli_real_escape_string($conn, $_POST['corrective_action']);
    $no_of_equipments_inspected = mysqli_real_escape_string($conn, $_POST['no_of_equipments_inspected']);
    $jrn = isset($_POST['jrn']) ? mysqli_real_escape_string($conn, $_POST['jrn']) : '';

    $project_no = isset($_POST['project_no']) ? mysqli_real_escape_string($conn, $_POST['project_no']) : '';
    $manufacturer = isset($_POST['manufacturer']) ? mysqli_real_escape_string($conn, $_POST['manufacturer']) : '';
    $capacity_swl = isset($_POST['capacity_swl']) ? mysqli_real_escape_string($conn, $_POST['capacity_swl']) : '';
    $equipment_id = isset($_POST['equipment_id']) ? mysqli_real_escape_string($conn, $_POST['equipment_id']) : '';
    $equipment_serial_no = isset($_POST['equipment_serial_no']) ? mysqli_real_escape_string($conn, $_POST['equipment_serial_no']) : '';

    $update_query = "
        UPDATE reports SET 
            model = '$model',
            type = '$type',
            prev_sticker_no = '$prev_sticker_no',
            issued_company = '$issued_company',
            next_inspection_due_date = '$next_inspection_due_date',
            inspection_status = '$inspection_status',
            deficiency = '$deficiency',
            corrective_action = '$corrective_action',
            no_of_equipments_inspected = '$no_of_equipments_inspected',
            jrn = '$jrn',
            capacity = '$capacity_swl',
            equipment_serial_no = '$equipment_serial_no',
            equipment_id_no = '$equipment_id'
        WHERE report_no = '$report_no'
    ";

    if (mysqli_query($conn, $update_query)) {
        if (!empty($project_no)) {
            $update_checklist = "
                UPDATE checklist_information SET
                    manufacturer = '$manufacturer',
                    capacity_swl = '$capacity_swl',
                    crane_serial_no = '$equipment_serial_no'
                WHERE project_no = '$project_no'
            ";
            mysqli_query($conn, $update_checklist);

            $update_project = "
                UPDATE project_info SET
                    equipment_id = '$equipment_id'
                WHERE project_no = '$project_no'
            ";
            mysqli_query($conn, $update_project);
        }

        // Resolve sticker number
        $sticker_number_issued = '';
        if (!empty($_POST['sticker_number_issued'])) {
            $sticker_number_issued = trim($_POST['sticker_number_issued']);
        } elseif (!empty($_POST['sticker_number_issued_hidden'])) {
            $sticker_number_issued = trim($_POST['sticker_number_issued_hidden']);
        }

        if (empty($sticker_number_issued) || in_array(strtolower($sticker_number_issued), ['na', 'n/a'])) {
            $rep_stmt = $conn->prepare("SELECT sticker_number_issued FROM reports WHERE report_no = ? LIMIT 1");
            if ($rep_stmt) {
                $rep_stmt->bind_param("s", $report_no);
                $rep_stmt->execute();
                $rep_res = $rep_stmt->get_result();
                if ($r_row = $rep_res->fetch_assoc()) {
                    if (!empty($r_row['sticker_number_issued'])) {
                        $sticker_number_issued = trim($r_row['sticker_number_issued']);
                    }
                }
                $rep_stmt->close();
            }
        }

        if ((empty($sticker_number_issued) || in_array(strtolower($sticker_number_issued), ['na', 'n/a'])) && !empty($project_no)) {
            $chk_stmt = $conn->prepare("SELECT sticker_no FROM checklist_information WHERE project_no = ? LIMIT 1");
            if ($chk_stmt) {
                $chk_stmt->bind_param("s", $project_no);
                $chk_stmt->execute();
                $chk_res = $chk_stmt->get_result();
                if ($c_row = $chk_res->fetch_assoc()) {
                    if (!empty($c_row['sticker_no'])) {
                        $sticker_number_issued = trim($c_row['sticker_no']);
                    }
                }
                $chk_stmt->close();
            }
        }

        // Update stickers table - by sticker_start_no if valid sticker number exists
        $has_valid_sticker_no = (!empty($sticker_number_issued) && !in_array(strtolower($sticker_number_issued), ['na', 'n/a']));
        if ($has_valid_sticker_no) {
            if (!empty($date_of_inspection)) {
                $stk_update = $conn->prepare("
                    UPDATE stickers 
                    SET sticker_status = ?, 
                        inspection_date = ?, 
                        next_inspection_due_date = ?, 
                        equipment_no = ?, 
                        equipment_serial_no = ?, 
                        project_no = ?, 
                        status = 'Active' 
                    WHERE sticker_start_no = ?
                ");
                if ($stk_update) {
                    $stk_update->bind_param("sssssss", $inspection_status, $date_of_inspection, $next_inspection_due_date, $equipment_id, $equipment_serial_no, $project_no, $sticker_number_issued);
                    $stk_update->execute();
                    $stk_update->close();
                }
            } else {
                $stk_update = $conn->prepare("
                    UPDATE stickers 
                    SET sticker_status = ?, 
                        next_inspection_due_date = ?, 
                        equipment_no = ?, 
                        equipment_serial_no = ?, 
                        project_no = ?, 
                        status = 'Active' 
                    WHERE sticker_start_no = ?
                ");
                if ($stk_update) {
                    $stk_update->bind_param("ssssss", $inspection_status, $next_inspection_due_date, $equipment_id, $equipment_serial_no, $project_no, $sticker_number_issued);
                    $stk_update->execute();
                    $stk_update->close();
                }
            }
        }

        // Also ensure stickers linked to this project_no in stickers table are updated
        if (!empty($project_no)) {
            if (!empty($date_of_inspection)) {
                $stk_proj_update = $conn->prepare("
                    UPDATE stickers 
                    SET sticker_status = ?, 
                        inspection_date = ?, 
                        next_inspection_due_date = ?, 
                        equipment_no = ?, 
                        equipment_serial_no = ?, 
                        status = 'Active' 
                    WHERE project_no = ?
                ");
                if ($stk_proj_update) {
                    $stk_proj_update->bind_param("ssssss", $inspection_status, $date_of_inspection, $next_inspection_due_date, $equipment_id, $equipment_serial_no, $project_no);
                    $stk_proj_update->execute();
                    $stk_proj_update->close();
                }
            } else {
                $stk_proj_update = $conn->prepare("
                    UPDATE stickers 
                    SET sticker_status = ?, 
                        next_inspection_due_date = ?, 
                        equipment_no = ?, 
                        equipment_serial_no = ?, 
                        status = 'Active' 
                    WHERE project_no = ?
                ");
                if ($stk_proj_update) {
                    $stk_proj_update->bind_param("sssss", $inspection_status, $next_inspection_due_date, $equipment_id, $equipment_serial_no, $project_no);
                    $stk_proj_update->execute();
                    $stk_proj_update->close();
                }
            }
        }

        // Handle Receiver Signature Image Upload
        if (!empty($project_no) && isset($_FILES['receiver_sign']) && $_FILES['receiver_sign']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $file_tmp = $_FILES['receiver_sign']['tmp_name'];
            $file_dest = $upload_dir . $project_no . '.png';
            
            // Move the uploaded file and overwrite the existing one
            move_uploaded_file($file_tmp, $file_dest);
        }
        
        header("Location: index.php"); // optionally redirect
    } else {
        echo "Error updating report: " . mysqli_error($conn);
    }
}
?>