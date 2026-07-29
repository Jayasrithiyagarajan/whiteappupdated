<?php
include_once('../../file/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['project_no'])) {

    mysqli_begin_transaction($conn);

    try {

        /* ================= BASIC ================= */
        $id               = $_POST['id'] ?? null;
        $project_no       = $_POST['project_no'] ?? null;
        $location         = $_POST['location'] ?? null;
        $inspection_date  = $_POST['inspection_date'] ?? null;
        $next_inspection  = $_POST['next_inspection_date'] ?? null;

        /* ================= CRANE ================= */
        $boom_min = $_POST['boom_min'] ?? null;
        $boom_max = $_POST['boom_max'] ?? null;

        /* ================= LMI ================= */
        $lmi_make        = $_POST['lmi_make'] ?? null;
        $lmi_model_type  = $_POST['lmi_model_type'] ?? null;
        $lmi_type        = $_POST['lmi_type'] ?? null;
        $lmi_serial      = $_POST['lmi_serial'] ?? null;

        /* ================= LOAD CELL ================= */
        $lc_make        = $_POST['lc_make'] ?? null;
        $lc_model_type  = $_POST['lc_model_type'] ?? null;
        $lc_type        = $_POST['lc_type'] ?? null;
        $lc_serial      = $_POST['lc_serial'] ?? null;
        $lc_capacity    = $_POST['lc_capacity'] ?? null;
        $lc_accuracy    = $_POST['lc_accuracy'] ?? null;
        $lc_cert_no     = $_POST['lc_cert_no'] ?? null;

        /* ================= BOOM LENGTH ================= */
        $boom_len_min_actual = $_POST['boom_len_min_actual'] ?? null;
        $boom_len_min_lmi    = $_POST['boom_len_min_lmi'] ?? null;
        $boom_len_min_remark = $_POST['boom_len_min_remark'] ?? null;

        $boom_len_mid_actual = $_POST['boom_len_mid_actual'] ?? null;
        $boom_len_mid_lmi    = $_POST['boom_len_mid_lmi'] ?? null;
        $boom_len_mid_remark = $_POST['boom_len_mid_remark'] ?? null;

        $boom_len_max_actual = $_POST['boom_len_max_actual'] ?? null;
        $boom_len_max_lmi    = $_POST['boom_len_max_lmi'] ?? null;
        $boom_len_max_remark = $_POST['boom_len_max_remark'] ?? null;

        /* ================= BOOM ANGLE ================= */
        $angle_min_actual = $_POST['angle_min_actual'] ?? null;
        $angle_min_lmi    = $_POST['angle_min_lmi'] ?? null;
        $angle_min_remark = $_POST['angle_min_remark'] ?? null;

        $angle_mid_actual = $_POST['angle_mid_actual'] ?? null;
        $angle_mid_lmi    = $_POST['angle_mid_lmi'] ?? null;
        $angle_mid_remark = $_POST['angle_mid_remark'] ?? null;

        $angle_max_actual = $_POST['angle_max_actual'] ?? null;
        $angle_max_lmi    = $_POST['angle_max_lmi'] ?? null;
        $angle_max_remark = $_POST['angle_max_remark'] ?? null;

        /* ================= RADIUS LOAD (WITH LENGTH) ================= */
        $radius_main_length_3m  = $_POST['radius_main_length_3m'] ?? null;
        $radius_main_chart      = $_POST['radius_main_chart'] ?? null;
        $radius_main_lmi        = $_POST['radius_main_lmi'] ?? null;
        $radius_main_remark     = $_POST['radius_main_remark'] ?? null;

        $radius_main_length_24m = $_POST['radius_main_length_24m'] ?? null;
        $radius_24_chart        = $_POST['radius_24_chart'] ?? null;
        $radius_24_lmi          = $_POST['radius_24_lmi'] ?? null;
        $radius_24_remark       = $_POST['radius_24_remark'] ?? null;

        $radius_aux_length      = $_POST['radius_aux_length'] ?? null;
        $radius_aux_chart       = $_POST['radius_aux_chart'] ?? null;
        $radius_aux_lmi         = $_POST['radius_aux_lmi'] ?? null;
        $radius_aux_remark      = $_POST['radius_aux_remark'] ?? null;

        /* ================= LOAD CELL CALIBRATION ================= */
        $load_actual   = $_POST['load_actual'] ?? null;
        $load_standard = $_POST['load_standard'] ?? null;
        $load_lmi      = $_POST['load_lmi'] ?? null;
        $load_remark   = $_POST['load_remark'] ?? null;

        /* ================= SAFETY & APPROVAL ================= */
        $anti_two_block     = $_POST['anti_two_block'] ?? null;
        $overload_lockout   = $_POST['overload_lockout'] ?? null;
        $technical_manager  = $_POST['technical_manager'] ?? null;
        $quality_controller = $_POST['quality_controller'] ?? null;

        $updated_at = date('Y-m-d H:i:s');

        /* ================= UPDATE ================= */
        $sql = "UPDATE lmi_certificates SET
            location = ?,
            inspection_date = ?, next_inspection_date = ?,
            boom_min = ?, boom_max = ?,

            lmi_make = ?, lmi_model_type = ?, lmi_type = ?, lmi_serial = ?,

            lc_make = ?, lc_model_type = ?, lc_type = ?, lc_serial = ?,
            lc_capacity = ?, lc_accuracy = ?, lc_cert_no = ?,

            boom_len_min_actual = ?, boom_len_min_lmi = ?, boom_len_min_remark = ?,
            boom_len_mid_actual = ?, boom_len_mid_lmi = ?, boom_len_mid_remark = ?,
            boom_len_max_actual = ?, boom_len_max_lmi = ?, boom_len_max_remark = ?,

            angle_min_actual = ?, angle_min_lmi = ?, angle_min_remark = ?,
            angle_mid_actual = ?, angle_mid_lmi = ?, angle_mid_remark = ?,
            angle_max_actual = ?, angle_max_lmi = ?, angle_max_remark = ?,

            radius_main_length_3m = ?, radius_main_chart = ?, radius_main_lmi = ?, radius_main_remark = ?,
            radius_main_length_24m = ?, radius_24_chart = ?, radius_24_lmi = ?, radius_24_remark = ?,
            radius_aux_length = ?, radius_aux_chart = ?, radius_aux_lmi = ?, radius_aux_remark = ?,

            load_actual = ?, load_standard = ?, load_lmi = ?, load_remark = ?,

            anti_two_block = ?, overload_lockout = ?,
            technical_manager = ?, quality_controller = ?
        WHERE id = ? AND project_no = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            str_repeat('s', 54) . "is",

            $location, $inspection_date, $next_inspection,
            $boom_min, $boom_max,

            $lmi_make, $lmi_model_type, $lmi_type, $lmi_serial,

            $lc_make, $lc_model_type, $lc_type, $lc_serial,
            $lc_capacity, $lc_accuracy, $lc_cert_no,

            $boom_len_min_actual, $boom_len_min_lmi, $boom_len_min_remark,
            $boom_len_mid_actual, $boom_len_mid_lmi, $boom_len_mid_remark,
            $boom_len_max_actual, $boom_len_max_lmi, $boom_len_max_remark,

            $angle_min_actual, $angle_min_lmi, $angle_min_remark,
            $angle_mid_actual, $angle_mid_lmi, $angle_mid_remark,
            $angle_max_actual, $angle_max_lmi, $angle_max_remark,

            $radius_main_length_3m, $radius_main_chart, $radius_main_lmi, $radius_main_remark,
            $radius_main_length_24m, $radius_24_chart, $radius_24_lmi, $radius_24_remark,
            $radius_aux_length, $radius_aux_chart, $radius_aux_lmi, $radius_aux_remark,

            $load_actual, $load_standard, $load_lmi, $load_remark,

            $anti_two_block, $overload_lockout,
            $technical_manager, $quality_controller,

            $id, $project_no
        );

        $stmt->execute();

        /* ================= STATUS UPDATE ================= */
        $status = $conn->prepare(
            "UPDATE project_info
             SET certificatestatus = 'LMI Certificate Updated'
             WHERE project_no = ?"
        );
        $status->bind_param("s", $project_no);
        $status->execute();

        /* ================= NOTIFICATION ================= */
        $msg = "LMI Certificate updated and sent for QC review.";
        $notify = $conn->prepare(
            "INSERT INTO project_notifications
            (project_no, notification_message, quality_controller, created_at)
            VALUES (?, ?, 'pending', ?)"
        );
        $notify->bind_param("sss", $project_no, $msg, $updated_at);
        $notify->execute();

        mysqli_commit($conn);

        header("Location: index.php?msg=" . urlencode("LMI Certificate updated successfully"));
        exit;

    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo "Error updating LMI Certificate: " . $e->getMessage();
    }
}
?>
