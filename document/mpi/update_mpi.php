<?php
// Enable output buffering to prevent any premature output
ob_start();
include_once('../../inc/function.php');
include_once('../../file/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Static fields (shared across all certificates for this project)
    $project_no = $_POST['project_no'];
    $inspector = $_POST['inspector'];
    $mobile = $_POST['mobile'];
    $report_no = $_POST['report_no'];
    $date_of_report = $_POST['date_of_report'];
    $jrn = $_POST['jrn'];
    $customer_name = $_POST['customer_name'];
    $customer_email = $_POST['customer_email'];
    $technical_manager = $_POST['technical_manager'];
    $quality_controller = $_POST['quality_controller'];

    // Update static fields in all records for this project
    $updateStaticQuery = "
        UPDATE mpi_certificates 
        SET 
            inspector = ?, 
            mobile = ?, 
            report_no = ?,
            date_of_report = ?,
            jrn = ?,
            customer_name = ?,
            customer_email = ?,
            technical_manager = ?,
            quality_controller = ?
        WHERE 
            project_no = ?
    ";
    
    $updateStaticStmt = $conn->prepare($updateStaticQuery);
    if (!$updateStaticStmt) {
        die("Update Query Error: " . $conn->error);
    }
    
    $updateStaticStmt->bind_param(
        "ssssssssss", 
        $inspector, 
        $mobile, 
        $report_no, 
        $date_of_report, 
        $jrn, 
        $customer_name, 
        $customer_email, 
        $technical_manager, 
        $quality_controller, 
        $project_no
    );

    if (!$updateStaticStmt->execute()) {
        die("Update Query Execution Failed: " . $updateStaticStmt->error);
    }

    // Process dynamic fields (individual certificate data)
    if (isset($_POST['certificate_no'])) {
        $certificateNos = $_POST['certificate_no'];
        $locations = $_POST['location'];
        $inspection_dates = $_POST['inspection_date'];
        $next_inspection_dates = $_POST['next_inspection_date'];
        $inspected_items = $_POST['inspected_item'];
        $serial_numbers = $_POST['serial_numbers'];
        $manufacturers = $_POST['manufacturer'];
        $standards = $_POST['standards'];
        $swls = $_POST['swl'];
        $mpi_equip_types = $_POST['mpi_equip_type'];
        $currents = $_POST['current'];
        $contrast_paints = $_POST['contrast_paint'];
        $particle_mediums = $_POST['particle_medium'];
        $calibration_expiry_dates = $_POST['calibration_expiry_date'];
        $brands = $_POST['brand'];
        $prod_spacings = $_POST['prod_spacing'];
        $inks = $_POST['ink'];
        $yoke_sns = $_POST['yoke_sn'];
        $model_nos = $_POST['model_no'];
        $results = $_POST['result'];
        $comments = $_POST['comments'];

        // First delete all existing records for this project
        $deleteQuery = "DELETE FROM mpi_certificates WHERE project_no = ?";
        $deleteStmt = $conn->prepare($deleteQuery);
        if (!$deleteStmt) {
            die("Delete Query Error: " . $conn->error);
        }
        $deleteStmt->bind_param("s", $project_no);
        if (!$deleteStmt->execute()) {
            die("Delete Query Execution Failed: " . $deleteStmt->error);
        }

        // Insert new records with updated data
        $insertQuery = "
            INSERT INTO mpi_certificates 
                (project_no, certificate_no, location, inspection_date, next_inspection_date, 
                inspected_item, serial_numbers, manufacturer, standards, swl, 
                mpi_equip_type, current, contrast_paint, particle_medium, calibration_expiry_date, 
                brand, prod_spacing, ink, yoke_sn, model_no, 
                result, comments,
                inspector, mobile, report_no, date_of_report, jrn, 
                customer_name, customer_email, technical_manager, quality_controller)
            VALUES 
                (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ";

        $insertStmt = $conn->prepare($insertQuery);
        if (!$insertStmt) {
            die("Insert Query Error: " . $conn->error);
        }

        foreach ($certificateNos as $index => $certificateNo) {
            $insertStmt->bind_param(
                "sssssssssssssssssssssssssssssss",
                $project_no,
                $certificateNo,
                $locations[$index],
                $inspection_dates[$index],
                $next_inspection_dates[$index],
                $inspected_items[$index],
                $serial_numbers[$index],
                $manufacturers[$index],
                $standards[$index],
                $swls[$index],
                $mpi_equip_types[$index],
                $currents[$index],
                $contrast_paints[$index],
                $particle_mediums[$index],
                $calibration_expiry_dates[$index],
                $brands[$index],
                $prod_spacings[$index],
                $inks[$index],
                $yoke_sns[$index],
                $model_nos[$index],
                $results[$index],
                $comments[$index],
                $inspector,
                $mobile,
                $report_no,
                $date_of_report,
                $jrn,
                $customer_name,
                $customer_email,
                $technical_manager,
                $quality_controller
            );

            if (!$insertStmt->execute()) {
                die("Insert Query Execution Failed: " . $insertStmt->error);
            }
        }
    }

    // Close connection before redirecting
    $conn->close();

    // Redirect to index.php
    header("Location: index.php");
    exit();
} else {
    echo "Invalid request method.";
}

// Close the output buffer and flush it
ob_end_flush();
?>