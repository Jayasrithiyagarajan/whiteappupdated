<?php
include_once('../../file/config.php');

if (!isset($_POST['save_mpi'])) {
    exit('Invalid request');
}

// Start transaction
mysqli_begin_transaction($conn);

try {

    /* ================= STATIC FIELDS ================= */
    $date_of_report     = $_POST['date_of_report']     ?? null;
    $report_no          = $_POST['report_no']          ?? null;
    $jrn                = $_POST['jrn']                ?? null;
    $project_no         = $_POST['project_no']         ?? null;
    $customer_name      = $_POST['customer_name']      ?? null;
    $customer_email     = $_POST['customer_email']     ?? null;
    $mobile             = $_POST['mobile']             ?? null;
    $inspector          = $_POST['inspector']          ?? null;
    $technical_manager  = $_POST['technical_manager']  ?? null;
    $quality_controller = $_POST['quality_controller'] ?? null;
    $created_at         = date('Y-m-d H:i:s');

    /* ================= DYNAMIC ARRAYS ================= */
    $certificate_no          = $_POST['certificate_no']          ?? [];
    $location                = $_POST['location']                ?? [];
    $inspection_date         = $_POST['inspection_date']         ?? [];
    $next_inspection_date    = $_POST['next_inspection_date']    ?? [];
    $inspected_item          = $_POST['inspected_item']          ?? [];
    $serial_numbers          = $_POST['serial_numbers']          ?? [];
    $manufacturer            = $_POST['manufacturer']            ?? [];
    $standards               = $_POST['standards']               ?? [];
    $swl                     = $_POST['swl']                     ?? [];
    $mpi_equip_type          = $_POST['mpi_equip_type']          ?? [];
    $current                 = $_POST['current']                 ?? [];
    $contrast_paint          = $_POST['contrast_paint']          ?? [];
    $particle_medium         = $_POST['particle_medium']         ?? [];
    $calibration_expiry_date = $_POST['calibration_expiry_date'] ?? [];
    $brand                   = $_POST['brand']                   ?? [];
    $prod_spacing            = $_POST['prod_spacing']            ?? [];
    $ink                     = $_POST['ink']                     ?? [];
    $yoke_sn                 = $_POST['yoke_sn']                 ?? [];
    $model_no                = $_POST['model_no']                ?? [];
    $result                  = $_POST['result']                  ?? [];
    $comments                = $_POST['comments']                ?? [];

    if (empty($certificate_no)) {
        throw new Exception("No certificates submitted");
    }

    /* ================= UPLOAD DIRECTORY ================= */
    $upload_dir = '../../uploads/mpi_certificates/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    /* ================= PREPARED INSERT ================= */
    $sql = "INSERT INTO mpi_certificates (
        date_of_report,
        report_no,
        jrn,
        project_no,
        customer_name,
        customer_email,
        mobile,
        inspector,
        technical_manager,
        quality_controller,
        created_at,
        certificate_no,
        location,
        inspection_date,
        next_inspection_date,
        inspected_item,
        serial_numbers,
        manufacturer,
        standards,
        swl,
        mpi_equip_type,
        current,
        contrast_paint,
        particle_medium,
        calibration_expiry_date,
        brand,
        prod_spacing,
        ink,
        yoke_sn,
        model_no,
        result,
        comments,
        images
    ) VALUES (
        ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?
    )";

    $stmt = $conn->prepare($sql);
    $certificate_numbers = [];

    /* ================= LOOP CERTIFICATES ================= */
    foreach ($certificate_no as $i => $cert_no) {

        /* ---------- IMAGE UPLOAD ---------- */
        $uploaded_images = [];

        if (isset($_FILES['image']['name'][$i])) {
            foreach ($_FILES['image']['name'][$i] as $j => $name) {
                if ($_FILES['image']['error'][$i][$j] === UPLOAD_ERR_OK) {
                    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                    $allowed = ['jpg','jpeg','png','webp'];

                    if (!in_array($ext, $allowed)) {
                        continue;
                    }

                    $new_name = uniqid('mpi_') . "_{$cert_no}." . $ext;
                    $dest = $upload_dir . $new_name;

                    if (move_uploaded_file($_FILES['image']['tmp_name'][$i][$j], $dest)) {
                        $uploaded_images[] = $new_name;
                    }
                }
            }
        }

        $images_json = !empty($uploaded_images) ? json_encode($uploaded_images) : null;

        /* ---------- PREPARE VARIABLES ---------- */
        $location_v             = $location[$i]                ?? null;
        $inspection_date_v      = $inspection_date[$i]         ?? null;
        $next_inspection_date_v = $next_inspection_date[$i]    ?? null;
        $inspected_item_v       = $inspected_item[$i]          ?? null;
        $serial_numbers_v       = $serial_numbers[$i]          ?? null;
        $manufacturer_v         = $manufacturer[$i]            ?? null;
        $standards_v            = $standards[$i]               ?? null;
        $swl_v                  = $swl[$i]                     ?? null;
        $mpi_equip_type_v       = $mpi_equip_type[$i]          ?? null;
        $current_v              = $current[$i]                 ?? null;
        $contrast_paint_v       = $contrast_paint[$i]          ?? null;
        $particle_medium_v      = $particle_medium[$i]         ?? null;
        $calibration_expiry_v   = $calibration_expiry_date[$i] ?? null;
        $brand_v                = $brand[$i]                   ?? null;
        $prod_spacing_v         = $prod_spacing[$i]            ?? null;
        $ink_v                  = $ink[$i]                     ?? null;
        $yoke_sn_v              = $yoke_sn[$i]                 ?? null;
        $model_no_v             = $model_no[$i]                ?? null;
        $result_v               = $result[$i]                  ?? null;
        $comments_v             = $comments[$i]                ?? null;

        /* ---------- BIND & EXECUTE ---------- */
        $stmt->bind_param(
            "sssssssssssssssssssssssssssssssss",
            $date_of_report,
            $report_no,
            $jrn,
            $project_no,
            $customer_name,
            $customer_email,
            $mobile,
            $inspector,
            $technical_manager,
            $quality_controller,
            $created_at,
            $cert_no,
            $location_v,
            $inspection_date_v,
            $next_inspection_date_v,
            $inspected_item_v,
            $serial_numbers_v,
            $manufacturer_v,
            $standards_v,
            $swl_v,
            $mpi_equip_type_v,
            $current_v,
            $contrast_paint_v,
            $particle_medium_v,
            $calibration_expiry_v,
            $brand_v,
            $prod_spacing_v,
            $ink_v,
            $yoke_sn_v,
            $model_no_v,
            $result_v,
            $comments_v,
            $images_json
        );

        if (!$stmt->execute()) {
            throw new Exception("Insert failed for certificate {$cert_no}");
        }

        $certificate_numbers[] = $cert_no;
    }

    $stmt->close();

    /* ================= UPDATE PROJECT STATUS ================= */
    $upd = $conn->prepare(
        "UPDATE project_info SET certificatestatus='Certificate Created' WHERE project_no=?"
    );
    $upd->bind_param("s", $project_no);
    $upd->execute();
    $upd->close();

    /* ================= QC NOTIFICATION ================= */
    $msg = "MPI certificates (" . implode(', ', $certificate_numbers) .
           ") for project {$project_no} are ready for QC review";

    $now = date('Y-m-d H:i:s');
    $note = $conn->prepare(
        "INSERT INTO project_notifications
         (project_no, notification_message, quality_controller, created_at)
         VALUES (?, ?, 'pending', ?)"
    );
    $note->bind_param("sss", $project_no, $msg, $now);
    $note->execute();
    $note->close();

    mysqli_commit($conn);

    header("Location: index.php?msg=" . urlencode("MPI certificates created successfully"));
    exit;

} catch (Exception $e) {

    mysqli_rollback($conn);
    error_log($e->getMessage());
    echo "Error: " . $e->getMessage();

} finally {
    mysqli_close($conn);
}
