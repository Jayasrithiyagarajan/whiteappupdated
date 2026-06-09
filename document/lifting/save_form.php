<?php
if (isset($_POST['save_data_lifting'])) {

    include_once('../../file/config.php');

    mysqli_begin_transaction($conn);

    try {

        /* ================= HEADER / COMMON FIELDS ================= */
        $date_of_report        = $_POST['date_of_report'];
        $report_no             = $_POST['report_no'];
        $jrn                   = $_POST['jrn'];
        $color_code            = $_POST['color_code'];
        $project_no            = $_POST['project_no'];
        $customer_name         = $_POST['customer_name'];
        $customer_email        = $_POST['customer_email'];
        $mobile                = $_POST['mobile'];
        $inspector             = $_POST['inspector'];
        $technical_manager     = $_POST['technical_manager'];
        $quality_controller    = $_POST['quality_controller'];
        $created_at            = date('Y-m-d H:i:s');

        /* ================= SINGLE COMMON FIELDS ================= */
        $applicable_standards     = $_POST['applicable_standards'];
        $employer_name_address    = $_POST['employer_name_address'];
        $address_of_premises      = $_POST['address_of_premises'];
        $next_examination_date    = $_POST['next_examination_date'];
        $reason_for_examination   = $_POST['reason_for_examination'];
        $date_of_this_examination = $_POST['date_of_this_examination'];

        /* ================= ROW DATA ================= */
        $certificateRows = [];

        if (!empty($_POST['certificate_rows_json'])) {
            $decodedRows = json_decode($_POST['certificate_rows_json'], true);

            if (json_last_error() !== JSON_ERROR_NONE || !is_array($decodedRows)) {
                throw new Exception("Invalid certificate row data submitted.");
            }

            $certificateRows = $decodedRows;
        } else {
            $certificate_no        = $_POST['certificate_no'] ?? [];
            $identification_no     = $_POST['identification_no'] ?? [];
            $wll_swl               = $_POST['wll_swl'] ?? [];
            $qty                   = $_POST['qty'] ?? [];
            $type                  = $_POST['type'] ?? [];
            $date_last_examination = $_POST['date_last_examination'] ?? [];
            $description           = $_POST['description'] ?? [];
            $test_details          = $_POST['test_details'] ?? [];
            $status                = $_POST['status'] ?? [];
            $safe_to_use           = $_POST['safe_to_use'] ?? [];

            foreach ($certificate_no as $index => $cert_no) {
                $certificateRows[] = [
                    'certificate_no' => $cert_no,
                    'identification_no' => $identification_no[$index] ?? '',
                    'wll_swl' => $wll_swl[$index] ?? '',
                    'qty' => $qty[$index] ?? '',
                    'type' => $type[$index] ?? '',
                    'date_last_examination' => $date_last_examination[$index] ?? '',
                    'description' => $description[$index] ?? '',
                    'test_details' => $test_details[$index] ?? '',
                    'status' => $status[$index] ?? '',
                    'safe_to_use' => $safe_to_use[$index] ?? '',
                ];
            }
        }

        if (empty($certificateRows)) {
            throw new Exception("No certificate rows submitted.");
        }

        /* ================= PREPARED INSERT ================= */
        $sql = "INSERT INTO lifting_gear_certificates (
            date_of_report,
            certificate_no,
            report_no,
            jrn,
            color_code,
            project_no,
            customer_name,
            customer_email,
            mobile,
            inspector,
            technical_manager,
            quality_controller,
            created_at,
            employer_name_address,
            applicable_standards,
            identification_no,
            wll_swl,
            qty,
            type,
            date_last_examination,
            description,
            address_of_premises,
            next_examination_date,
            reason_for_examination,
            date_of_this_examination,
            test_details,
            status,
            safe_to_use
        ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        $certificate_numbers = [];

        foreach ($certificateRows as $row) {
            $cert_no = trim($row['certificate_no'] ?? '');

            if ($cert_no === '') {
                throw new Exception("Certificate number missing in one or more rows.");
            }

            $stmt->bind_param(
                "ssssssssssssssssssssssssssss",
                $date_of_report,
                $cert_no,
                $report_no,
                $jrn,
                $color_code,
                $project_no,
                $customer_name,
                $customer_email,
                $mobile,
                $inspector,
                $technical_manager,
                $quality_controller,
                $created_at,
                $employer_name_address,
                $applicable_standards,
                $row['identification_no'],
                $row['wll_swl'],
                $row['qty'],
                $row['type'],
                $row['date_last_examination'],
                $row['description'],
                $address_of_premises,
                $next_examination_date,
                $reason_for_examination,
                $date_of_this_examination,
                $row['test_details'],
                $row['status'],
                $row['safe_to_use']
            );

            if (!$stmt->execute()) {
                throw new Exception("Insert failed for certificate $cert_no: " . $stmt->error);
            }

            $certificate_numbers[] = $cert_no;
        }

        /* ================= UPDATE PROJECT STATUS ================= */
        $update = $conn->prepare(
            "UPDATE project_info 
             SET certificatestatus = 'Certificate Created' 
             WHERE project_no = ?"
        );
        $update->bind_param("s", $project_no);

        if (!$update->execute()) {
            throw new Exception("Project status update failed");
        }

        /* ================= QC NOTIFICATION ================= */
        $certificate_list = implode(", ", $certificate_numbers);
        $notification_msg = "Lifting gear certificates ($certificate_list) for project $project_no are ready for QC review";
        $now = date('Y-m-d H:i:s');

        $notify = $conn->prepare(
            "INSERT INTO project_notifications 
             (project_no, notification_message, quality_controller, created_at)
             VALUES (?, ?, 'pending', ?)"
        );
        $notify->bind_param("sss", $project_no, $notification_msg, $now);

        if (!$notify->execute()) {
            throw new Exception("QC notification failed");
        }

        /* ================= COMMIT ================= */
        mysqli_commit($conn);

        header("Location: index.php?msg=" . urlencode("Certificates created successfully and QC notified."));
        exit;

    } catch (Exception $e) {

        mysqli_rollback($conn);
        error_log($e->getMessage());
        echo "Error: " . $e->getMessage();

    } finally {
        $conn->close();
    }
}
?>
