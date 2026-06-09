<?php
include_once('../../file/config.php');

if (!isset($_POST['update_mpi'])) {
    exit('Invalid request');
}

mysqli_begin_transaction($conn);

try {

    $project_no = $_POST['project_no'];

    /* ===== Arrays ===== */
    $cert_id                = $_POST['cert_id'];
    $certificate_no         = $_POST['certificate_no'];
    $location               = $_POST['location'];
    $inspection_date        = $_POST['inspection_date'];
    $next_inspection_date   = $_POST['next_inspection_date'];
    $inspected_item         = $_POST['inspected_item'];
    $serial_numbers         = $_POST['serial_numbers'];
    $manufacturer           = $_POST['manufacturer'];
    $standards              = $_POST['standards'];
    $swl                    = $_POST['swl'];
    $mpi_equip_type         = $_POST['mpi_equip_type'];
    $current                = $_POST['current'];
    $prod_spacing           = $_POST['prod_spacing'];
    $contrast_paint         = $_POST['contrast_paint'];
    $ink                    = $_POST['ink'];
    $particle_medium        = $_POST['particle_medium'];
    $yoke_sn                = $_POST['yoke_sn'];
    $model_no               = $_POST['model_no'];
    $calibration_expiry     = $_POST['calibration_expiry_date'];
    $result                 = $_POST['result'];
    $comments               = $_POST['comments'];

    $remove_images = $_POST['remove_images'] ?? [];

    $upload_dir = '../../uploads/mpi_certificates/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    /* ===== PREPARED UPDATE ===== */
    $sql = "UPDATE mpi_certificates SET
        location=?,
        inspection_date=?,
        next_inspection_date=?,
        inspected_item=?,
        serial_numbers=?,
        manufacturer=?,
        standards=?,
        swl=?,
        mpi_equip_type=?,
        current=?,
        prod_spacing=?,
        contrast_paint=?,
        ink=?,
        particle_medium=?,
        yoke_sn=?,
        model_no=?,
        calibration_expiry_date=?,
        result=?,
        comments=?,
        images=?
        WHERE id=?";

    $stmt = $conn->prepare($sql);

    /* ===== LOOP CERTIFICATES ===== */
    foreach ($cert_id as $i => $id) {

        /* ---- LOAD EXISTING IMAGES ---- */
        $imgStmt = $conn->prepare("SELECT images FROM mpi_certificates WHERE id=?");
        $imgStmt->bind_param("i", $id);
        $imgStmt->execute();
        $row = $imgStmt->get_result()->fetch_assoc();
        $existing_images = json_decode($row['images'], true) ?? [];
        $imgStmt->close();

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

        /* ---- BIND & EXECUTE ---- */
        $stmt->bind_param(
            "ssssssssssssssssssssi",
            $location_v,
            $inspection_date_v,
            $next_inspection_v,
            $inspected_item_v,
            $serial_numbers_v,
            $manufacturer_v,
            $standards_v,
            $swl_v,
            $mpi_equip_type_v,
            $current_v,
            $prod_spacing_v,
            $contrast_paint_v,
            $ink_v,
            $particle_medium_v,
            $yoke_sn_v,
            $model_no_v,
            $calibration_expiry_v,
            $result_v,
            $comments_v,
            $images_json,
            $id_v
        );

        if (!$stmt->execute()) {
            throw new Exception("Update failed for certificate ID {$id_v}");
        }
    }

    $stmt->close();

    mysqli_commit($conn);

    header("Location: edit_mpi_certificate.php?project_no=" .
           urlencode($project_no) . "&msg=Updated successfully");
    exit;

} catch (Exception $e) {

    mysqli_rollback($conn);
    error_log($e->getMessage());
    echo "Error: " . $e->getMessage();

} finally {
    mysqli_close($conn);
}
