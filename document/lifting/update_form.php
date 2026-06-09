<?php
ob_start();
include_once('../../inc/function.php');
include_once('../../file/config.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Invalid request method.";
    exit;
}

mysqli_begin_transaction($conn);

try {
    $project_no = $_POST['project_no'];
    $inspector = $_POST['inspector'];
    $mobile = $_POST['mobile'];
    $color_code = $_POST['color_code'];
    $report_no = $_POST['report_no'];
    $date_of_report = $_POST['date_of_report'];
    $jrn = $_POST['jrn'];
    $customer_name = $_POST['customer_name'];
    $customer_email = $_POST['customer_email'];
    $technical_manager = $_POST['technical_manager'];
    $quality_controller = $_POST['quality_controller'];
    $applicable_standards = $_POST['applicable_standards'];
    $employer_name_address = $_POST['employer_name_address'];
    $address_of_premises = $_POST['address_of_premises'];
    $next_examination_date = $_POST['next_examination_date'];
    $reason_for_examination = $_POST['reason_for_examination'];
    $date_of_this_examination = $_POST['date_of_this_examination'];

    $certificateRows = [];

    if (!empty($_POST['certificate_rows_json'])) {
        $decodedRows = json_decode($_POST['certificate_rows_json'], true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($decodedRows)) {
            throw new Exception("Invalid certificate row data submitted.");
        }

        $certificateRows = $decodedRows;
    } else {
        $certificateNos = $_POST['certificate_no'] ?? [];
        $identification_nos = $_POST['identification_no'] ?? [];
        $wll_swls = $_POST['wll_swl'] ?? [];
        $qtys = $_POST['qty'] ?? [];
        $types = $_POST['type'] ?? [];
        $date_last_examinations = $_POST['date_last_examination'] ?? [];
        $descriptions = $_POST['description'] ?? [];
        $test_detailss = $_POST['test_details'] ?? [];
        $statuss = $_POST['status'] ?? [];
        $safe_to_uses = $_POST['safe_to_use'] ?? [];

        foreach ($certificateNos as $index => $certificateNo) {
            $certificateRows[] = [
                'certificate_no' => $certificateNo,
                'identification_no' => $identification_nos[$index] ?? '',
                'wll_swl' => $wll_swls[$index] ?? '',
                'qty' => $qtys[$index] ?? '',
                'type' => $types[$index] ?? '',
                'date_last_examination' => $date_last_examinations[$index] ?? '',
                'description' => $descriptions[$index] ?? '',
                'test_details' => $test_detailss[$index] ?? '',
                'status' => $statuss[$index] ?? '',
                'safe_to_use' => $safe_to_uses[$index] ?? '',
            ];
        }
    }

    if (empty($certificateRows)) {
        throw new Exception("No certificate rows submitted.");
    }

    $deleteQuery = "DELETE FROM lifting_gear_certificates WHERE project_no = ?";
    $deleteStmt = $conn->prepare($deleteQuery);
    $deleteStmt->bind_param("s", $project_no);
    $deleteStmt->execute();

    $insertQuery = "
        INSERT INTO lifting_gear_certificates (
            project_no,
            certificate_no,
            employer_name_address,
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
            safe_to_use,
            inspector,
            mobile,
            color_code,
            applicable_standards,
            report_no,
            date_of_report,
            jrn,
            customer_name,
            customer_email,
            technical_manager,
            quality_controller
        ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
    ";

    $insertStmt = $conn->prepare($insertQuery);
    if (!$insertStmt) {
        throw new Exception("Insert Query Error: " . $conn->error);
    }

    foreach ($certificateRows as $row) {
        $certificateNo = trim($row['certificate_no'] ?? '');

        if ($certificateNo === '') {
            throw new Exception("Certificate number missing in one or more rows.");
        }

        $insertStmt->bind_param(
            "sssssssssssssssssssssssssss",
            $project_no,
            $certificateNo,
            $employer_name_address,
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
            $row['safe_to_use'],
            $inspector,
            $mobile,
            $color_code,
            $applicable_standards,
            $report_no,
            $date_of_report,
            $jrn,
            $customer_name,
            $customer_email,
            $technical_manager,
            $quality_controller
        );

        if (!$insertStmt->execute()) {
            throw new Exception("Insert Query Execution Failed: " . $insertStmt->error);
        }
    }

    mysqli_commit($conn);
    $conn->close();

    header("Location: index.php?msg=" . urlencode("Certificates updated successfully."));
    exit;
} catch (Exception $e) {
    mysqli_rollback($conn);
    echo "Error: " . $e->getMessage();
}

ob_end_flush();
