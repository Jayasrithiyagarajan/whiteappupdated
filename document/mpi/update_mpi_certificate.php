<?php
include_once('../../file/config.php');

if (!isset($_POST['update_mpi'])) {
    exit('Invalid request');
}

mysqli_begin_transaction($conn);

try {

    $project_no = $_POST['project_no'];

    /* ===== Fetch Static Info For New Inserts ===== */
    $stmtFetchStatic = $conn->prepare("SELECT date_of_report, report_no, jrn, customer_name, customer_email, mobile, inspector, technical_manager, quality_controller FROM mpi_certificates WHERE project_no = ? LIMIT 1");
    $stmtFetchStatic->bind_param("s", $project_no);
    $stmtFetchStatic->execute();
    $staticData = $stmtFetchStatic->get_result()->fetch_assoc();
    $stmtFetchStatic->close();

    $created_at = date('Y-m-d H:i:s');

    /* ===== Arrays ===== */
    $cert_id                = $_POST['cert_id'] ?? [];
    $certificate_no         = $_POST['certificate_no'] ?? [];
    $location               = $_POST['location'] ?? [];
    $inspection_date        = $_POST['inspection_date'] ?? [];
    $next_inspection_date   = $_POST['next_inspection_date'] ?? [];
    $inspected_item         = $_POST['inspected_item'] ?? [];
    $serial_numbers         = $_POST['serial_numbers'] ?? [];
    $manufacturer           = $_POST['manufacturer'] ?? [];
    $standards              = $_POST['standards'] ?? [];
    $swl                    = $_POST['swl'] ?? [];
    $mpi_equip_type         = $_POST['mpi_equip_type'] ?? [];
    $current                = $_POST['current'] ?? [];
    $prod_spacing           = $_POST['prod_spacing'] ?? [];
    $contrast_paint         = $_POST['contrast_paint'] ?? [];
    $ink                    = $_POST['ink'] ?? [];
    $particle_medium        = $_POST['particle_medium'] ?? [];
    $yoke_sn                = $_POST['yoke_sn'] ?? [];
    $model_no               = $_POST['model_no'] ?? [];
    $calibration_expiry     = $_POST['calibration_expiry_date'] ?? [];
    $result                 = $_POST['result'] ?? [];
    $comments               = $_POST['comments'] ?? [];

    $remove_images = $_POST['remove_images'] ?? [];

    $upload_dir = '../../uploads/mpi_certificates/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    /* ===== HANDLE DELETIONS ===== */
    if (!empty($_POST['deleted_certs'])) {
        $delSql = "DELETE FROM mpi_certificates WHERE id = ?";
        $stmtDel = $conn->prepare($delSql);
        foreach ($_POST['deleted_certs'] as $delId) {
            $stmtDel->bind_param("i", $delId);
            $stmtDel->execute();
        }
        $stmtDel->close();
    }

    /* ===== PREPARED STATEMENTS ===== */
    $updateSql = "UPDATE mpi_certificates SET
        location=?, inspection_date=?, next_inspection_date=?, inspected_item=?, serial_numbers=?, manufacturer=?, standards=?, swl=?,
        mpi_equip_type=?, current=?, prod_spacing=?, contrast_paint=?, ink=?, particle_medium=?, yoke_sn=?, model_no=?, calibration_expiry_date=?, result=?, comments=?, images=?
        WHERE id=?";
    $stmtUpdate = $conn->prepare($updateSql);

    $insertSql = "INSERT INTO mpi_certificates (
        project_no, date_of_report, report_no, jrn, customer_name, customer_email, mobile, inspector, technical_manager, quality_controller, created_at,
        certificate_no, location, inspection_date, next_inspection_date, inspected_item, serial_numbers, manufacturer, standards, swl,
        mpi_equip_type, current, contrast_paint, particle_medium, calibration_expiry_date, brand, prod_spacing, ink, yoke_sn, model_no, result, comments, images
    ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $stmtInsert = $conn->prepare($insertSql);

    /* ===== LOOP CERTIFICATES ===== */
    foreach ($cert_id as $i => $id) {

        /* ---- LOAD EXISTING IMAGES ---- */
        $existing_images = [];
        if (!empty($id)) {
            $imgStmt = $conn->prepare("SELECT images FROM mpi_certificates WHERE id=?");
            $imgStmt->bind_param("i", $id);
            $imgStmt->execute();
            $row = $imgStmt->get_result()->fetch_assoc();
            $existing_images = json_decode($row['images'], true) ?? [];
            $imgStmt->close();
        }

        /* ---- REMOVE SELECTED IMAGES ---- */
        if (isset($remove_images[$i])) {
            foreach ($remove_images[$i] as $img) {
                if (($key = array_search($img, $existing_images)) !== false) {
                    unset($existing_images[$key]);
                    $file = $upload_dir . $img;
                    if (file_exists($file)) {
                        unlink($file);
                    }
                }
            }
        }

        /* ---- ADD NEW IMAGES ---- */
        if (isset($_FILES['new_images']['name'][$i])) {
            foreach ($_FILES['new_images']['name'][$i] as $j => $name) {
                if ($_FILES['new_images']['error'][$i][$j] === UPLOAD_ERR_OK) {
                    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                    if (!in_array($ext, ['jpg','jpeg','png','webp'])) continue;

                    $newName = uniqid('mpi_') . "_{$certificate_no[$i]}." . $ext;
                    move_uploaded_file(
                        $_FILES['new_images']['tmp_name'][$i][$j],
                        $upload_dir . $newName
                    );
                    $existing_images[] = $newName;
                }
            }
        }

        $images_json = empty($existing_images)
            ? null
            : json_encode(array_values($existing_images));

        /* ---- VARIABLES (NO EXPRESSIONS!) ---- */
        $cert_no_v              = $certificate_no[$i] ?? null;
        $location_v             = $location[$i] ?? null;
        $inspection_date_v      = $inspection_date[$i] ?? null;
        $next_inspection_v      = $next_inspection_date[$i] ?? null;
        $inspected_item_v       = $inspected_item[$i] ?? null;
        $serial_numbers_v       = $serial_numbers[$i] ?? null;
        $manufacturer_v         = $manufacturer[$i] ?? null;
        $standards_v            = $standards[$i] ?? null;
        $swl_v                  = $swl[$i] ?? null;
        $mpi_equip_type_v       = $mpi_equip_type[$i] ?? null;
        $current_v              = $current[$i] ?? null;
        $brand_v                = $_POST['brand'][$i] ?? null;
        $prod_spacing_v         = $prod_spacing[$i] ?? null;
        $contrast_paint_v       = $contrast_paint[$i] ?? null;
        $ink_v                  = $ink[$i] ?? null;
        $particle_medium_v      = $particle_medium[$i] ?? null;
        $yoke_sn_v              = $yoke_sn[$i] ?? null;
        $model_no_v             = $model_no[$i] ?? null;
        $calibration_expiry_v   = $calibration_expiry[$i] ?? null;
        $result_v               = $result[$i] ?? null;
        $comments_v             = $comments[$i] ?? null;
        $id_v                   = (int)$id;

        if (empty($id)) {
            /* ---- BIND & EXECUTE INSERT ---- */
            $date_of_report_v = $staticData['date_of_report'] ?? null;
            $report_no_v = $staticData['report_no'] ?? null;
            $jrn_v = $staticData['jrn'] ?? null;
            $customer_name_v = $staticData['customer_name'] ?? null;
            $customer_email_v = $staticData['customer_email'] ?? null;
            $mobile_v = $staticData['mobile'] ?? null;
            $inspector_v = $staticData['inspector'] ?? null;
            $technical_manager_v = $staticData['technical_manager'] ?? null;
            $quality_controller_v = $staticData['quality_controller'] ?? null;

            $stmtInsert->bind_param(
                "sssssssssssssssssssssssssssssssss",
                $project_no, $date_of_report_v, $report_no_v, $jrn_v, $customer_name_v, $customer_email_v, $mobile_v, $inspector_v, $technical_manager_v, $quality_controller_v, $created_at,
                $cert_no_v, $location_v, $inspection_date_v, $next_inspection_v, $inspected_item_v, $serial_numbers_v, $manufacturer_v, $standards_v, $swl_v,
                $mpi_equip_type_v, $current_v, $contrast_paint_v, $particle_medium_v, $calibration_expiry_v, $brand_v, $prod_spacing_v, $ink_v, $yoke_sn_v, $model_no_v, $result_v, $comments_v, $images_json
            );
            if (!$stmtInsert->execute()) {
                throw new Exception("Insert failed for certificate {$cert_no_v}");
            }
        } else {
            /* ---- BIND & EXECUTE UPDATE ---- */
            $stmtUpdate->bind_param(
                "ssssssssssssssssssssi",
                $location_v, $inspection_date_v, $next_inspection_v, $inspected_item_v, $serial_numbers_v, $manufacturer_v, $standards_v, $swl_v,
                $mpi_equip_type_v, $current_v, $prod_spacing_v, $contrast_paint_v, $ink_v, $particle_medium_v, $yoke_sn_v, $model_no_v, $calibration_expiry_v, $result_v, $comments_v, $images_json, $id_v
            );
            if (!$stmtUpdate->execute()) {
                throw new Exception("Update failed for certificate ID {$id_v}");
            }
        }
    }

    $stmtUpdate->close();
    $stmtInsert->close();

    mysqli_commit($conn);

    header("Location: index.php?msg=Updated successfully");
    exit;

} catch (Exception $e) {

    mysqli_rollback($conn);
    error_log($e->getMessage());
    echo "Error: " . $e->getMessage();

} finally {
    mysqli_close($conn);
}
