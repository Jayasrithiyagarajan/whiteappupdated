<?php
include_once('../../file/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_lmi'])) {

    mysqli_begin_transaction($conn);

    try {

        /* ================= BASIC DETAILS ================= */
        $project_no           = $_POST['project_no'];
        $certificate_no       = $_POST['certificate_no'];
        $report_no            = $_POST['report_no'];
        $customer_name        = $_POST['customer_name'];
        $location             = $_POST['location'];
        $inspection_date      = $_POST['inspection_date'] ?? null;
        $next_inspection_date = $_POST['next_inspection_date'] ?? null;

        /* ================= CRANE DETAILS ================= */
        $crane_make       = $_POST['crane_make'] ?? null;
        $crane_model      = $_POST['crane_model'] ?? null;
        $crane_type       = $_POST['crane_type'] ?? null;
        $crane_capacity   = $_POST['crane_capacity'] ?? null;
        $crane_serial_no  = $_POST['crane_serial_no'] ?? null;
        $crane_id_no      = $_POST['crane_id_no'] ?? null;
        $boom_min         = $_POST['boom_min'] ?? null;
        $boom_max         = $_POST['boom_max'] ?? null;

        /* ================= LMI DETAILS ================= */
        $lmi_make        = $_POST['lmi_make'];
        $lmi_model_type  = $_POST['lmi_model_type'];
        $lmi_type        = $_POST['lmi_type'];
        $lmi_serial      = $_POST['lmi_serial'];

        /* ================= LOAD CELL DETAILS ================= */
        $lc_make        = $_POST['lc_make'];
        $lc_model_type  = $_POST['lc_model_type'];
        $lc_type        = $_POST['lc_type'];
        $lc_serial      = $_POST['lc_serial'];
        $lc_capacity    = $_POST['lc_capacity'];
        $lc_accuracy    = $_POST['lc_accuracy'];
        $lc_cert_no     = $_POST['lc_cert_no'];

        /* ================= BOOM LENGTH CALIBRATION ================= */
        $boom_len_min_actual = $_POST['boom_len_min_actual'];
        $boom_len_min_lmi    = $_POST['boom_len_min_lmi'];
        $boom_len_min_remark = $_POST['boom_len_min_remark'];

        $boom_len_mid_actual = $_POST['boom_len_mid_actual'];
        $boom_len_mid_lmi    = $_POST['boom_len_mid_lmi'];
        $boom_len_mid_remark = $_POST['boom_len_mid_remark'];

        $boom_len_max_actual = $_POST['boom_len_max_actual'];
        $boom_len_max_lmi    = $_POST['boom_len_max_lmi'];
        $boom_len_max_remark = $_POST['boom_len_max_remark'];

        /* ================= BOOM ANGLE CALIBRATION ================= */
        $angle_min_actual = $_POST['angle_min_actual'];
        $angle_min_lmi    = $_POST['angle_min_lmi'];
        $angle_min_remark = $_POST['angle_min_remark'];

        $angle_mid_actual = $_POST['angle_mid_actual'];
        $angle_mid_lmi    = $_POST['angle_mid_lmi'];
        $angle_mid_remark = $_POST['angle_mid_remark'];

        $angle_max_actual = $_POST['angle_max_actual'];
        $angle_max_lmi    = $_POST['angle_max_lmi'];
        $angle_max_remark = $_POST['angle_max_remark'];

        /* ================= RADIUS LOAD COMPARISON ================= */
        /* ================= RADIUS LOAD LENGTH ================= */
 $radius_main_length_3m  = $_POST['main_length_3m'] ?? null;
 $radius_main_length_24m = $_POST['main_length_24m'] ?? null;
 $radius_aux_length      = $_POST['aux_length'] ?? null;

        
        $radius_main_chart  = $_POST['main_3m_chart'] ?? '';
        $radius_main_lmi    = $_POST['main_3m_lmi'] ?? '';
        $radius_main_remark = $_POST['main_3m_remark'] ?? '';

        $radius_24_chart  = $_POST['main_24m_chart'] ?? '';
        $radius_24_lmi    = $_POST['main_24m_lmi'] ?? '';
        $radius_24_remark = $_POST['main_24m_remark'] ?? '';

        $radius_aux_chart  = $_POST['aux_chart'] ?? '';
        $radius_aux_lmi    = $_POST['aux_lmi'] ?? '';
        $radius_aux_remark = $_POST['aux_remark'] ?? '';

        /* ================= LOAD CELL CALIBRATION ================= */
        $load_actual   = $_POST['load_actual'];
        $load_standard = $_POST['load_standard'];
        $load_lmi      = $_POST['load_lmi'];
        $load_remark   = $_POST['load_remark'];

        /* ================= SAFETY ================= */
        $anti_two_block   = $_POST['anti_two_block'];
        $overload_lockout = $_POST['overload_lockout'];

        /* ================= SIGNATURE ================= */
        $inspector         = $_POST['inspector'];
        $technical_manager = $_POST['technical_manager'];
        $quality_controller = $_POST['quality_controller'];

        $created_at = date('Y-m-d H:i:s');

        /* ================= DUPLICATE CHECK ================= */
        $check = $conn->prepare(
            "SELECT id FROM lmi_certificates WHERE certificate_no = ? OR project_no = ?"
        );
        $check->bind_param("ss", $certificate_no, $project_no);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            throw new Exception("LMI Certificate already exists for this project.");
        }

        /* ================= INSERT CERTIFICATE ================= */
        $sql = "INSERT INTO lmi_certificates (
            project_no, certificate_no, report_no, customer_name, location,
            inspection_date, next_inspection_date,
            crane_make, crane_model, crane_type, crane_capacity,
            crane_serial_no, crane_id_no, boom_min, boom_max,
            lmi_make, lmi_model_type, lmi_type, lmi_serial,
            lc_make, lc_model_type, lc_type, lc_serial, lc_capacity, lc_accuracy, lc_cert_no,

            boom_len_min_actual, boom_len_min_lmi, boom_len_min_remark,
            boom_len_mid_actual, boom_len_mid_lmi, boom_len_mid_remark,
            boom_len_max_actual, boom_len_max_lmi, boom_len_max_remark,

            angle_min_actual, angle_min_lmi, angle_min_remark,
            angle_mid_actual, angle_mid_lmi, angle_mid_remark,
            angle_max_actual, angle_max_lmi, angle_max_remark,

            radius_main_length_3m, radius_main_chart, radius_main_lmi, radius_main_remark,
radius_main_length_24m, radius_24_chart, radius_24_lmi, radius_24_remark,
radius_aux_length, radius_aux_chart, radius_aux_lmi, radius_aux_remark,


            load_actual, load_standard, load_lmi, load_remark,
            anti_two_block, overload_lockout,
            inspector, technical_manager, quality_controller, created_at
        ) VALUES (" . rtrim(str_repeat('?,', 66), ',') . ")";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            str_repeat('s', 66),
            $project_no, $certificate_no, $report_no, $customer_name, $location,
            $inspection_date, $next_inspection_date,
            $crane_make, $crane_model, $crane_type, $crane_capacity,
            $crane_serial_no, $crane_id_no, $boom_min, $boom_max,
            $lmi_make, $lmi_model_type, $lmi_type, $lmi_serial,
            $lc_make, $lc_model_type, $lc_type, $lc_serial, $lc_capacity, $lc_accuracy, $lc_cert_no,

            $boom_len_min_actual, $boom_len_min_lmi, $boom_len_min_remark,
            $boom_len_mid_actual, $boom_len_mid_lmi, $boom_len_mid_remark,
            $boom_len_max_actual, $boom_len_max_lmi, $boom_len_max_remark,

            $angle_min_actual, $angle_min_lmi, $angle_min_remark,
            $angle_mid_actual, $angle_mid_lmi, $angle_mid_remark,
            $angle_max_actual, $angle_max_lmi, $angle_max_remark,

            $radius_main_length_3m,
$radius_main_chart, $radius_main_lmi, $radius_main_remark,

$radius_main_length_24m,
$radius_24_chart, $radius_24_lmi, $radius_24_remark,

$radius_aux_length,
$radius_aux_chart, $radius_aux_lmi, $radius_aux_remark,


            $load_actual, $load_standard, $load_lmi, $load_remark,
            $anti_two_block, $overload_lockout,
            $inspector, $technical_manager, $quality_controller, $created_at
        );

        $stmt->execute();

        /* ================= UPDATE PROJECT STATUS ================= */
        $update = $conn->prepare(
            "UPDATE project_info 
             SET certificatestatus = 'Certificate Created'
             WHERE project_no = ?"
        );
        $update->bind_param("s", $project_no);
        $update->execute();

        /* ================= QC NOTIFICATION ================= */
        /* ================= QC NOTIFICATION ================= */
$notification_message = "LMI Certificate ($certificate_no) created and pending QC review.";
$created_at = date('Y-m-d H:i:s');

$notify = $conn->prepare(
    "INSERT INTO project_notifications
     (project_no, notification_message, quality_controller, created_at)
     VALUES (?, ?, 'pending', ?)"
);

$notify->bind_param("sss", $project_no, $notification_message, $created_at);

if (!$notify->execute()) {
    throw new Exception("Failed to add QC notification: " . $notify->error);
}


        mysqli_commit($conn);

        header("Location: index.php?msg=" . urlencode("LMI Certificate created & QC notified"));
        exit;

    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo "Error: " . $e->getMessage();
    }
}
?>
