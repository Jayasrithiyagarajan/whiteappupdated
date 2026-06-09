<?php
// Enable output buffering to prevent any premature output
ob_start();

// Enable error reporting for debugging
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// Include database connection and functions
include_once('../../inc/function.php');
include_once('../../file/config.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $project_no = $_POST['project_no'];
     // The report_no field to identify the record
    // Collect other form data...
    $examination_date = $_POST['examination_date'];
    $report_date = $_POST['report_date'];
    $sticker_no = $_POST['sticker_no'];
    $report_no = $_POST['report_no'];    
    $customer_name = $_POST['customer_name'];
    $customer_email = $_POST['customer_email'];
    $customer_mobile = $_POST['customer_mobile'];
    $inspector_name = $_POST['inspector_name'];
    $technical_manager = $_POST['technical_manager'];
    $quality_controller = $_POST['quality_controller'];
    $employer_address = $_POST['employer_address'];
    $equipment_description = $_POST['equipment_description'];
    $manufacturer = $_POST['manufacturer'];
    $model = $_POST['model'];
    $equipment_id = $_POST['equipment_id'];
    $equipment_serial_no = $_POST['equipment_serial_no'];
    $certificate_no = $_POST['certificate_no'];
    $jrn = $_POST['jrn'];
    $common_text_area = $_POST['common_text_area'];
    $premises_address = $_POST['premises_address'];
    $safe_working_load = $_POST['safe_working_load'];
    $manufacture_date = $_POST['manufacture_date'];
    $last_exam_date = $_POST['last_exam_date'];
    $first_examination = $_POST['first_examination'];
    $installed_correctly = $_POST['installed_correctly'];
    $interval_6_months = $_POST['interval_6_months'];
    $interval_12_months = $_POST['interval_12_months'];
    $examination_scheme = $_POST['examination_scheme'];
    $exceptional_circumstances = $_POST['exceptional_circumstances'];
    $identification_any_part = $_POST['identification_any_part'];
    $defect = $_POST['defect'];
    $date_defect = $_POST['date_defect'];
    $repair_details = $_POST['repair_details'];
    $test_particulars = $_POST['test_particulars'];
    $equipment_fit = $_POST['equipment_fit'];
    $latest_date_exam = $_POST['latest_date_exam'];
    $boom_length = $_POST['boom_length'];
    $boom_angle = $_POST['boom_angle'];
    $swl_test_weight = $_POST['swl_test_weight'];
    $radius = $_POST['radius'];
    $comments = $_POST['comments'];
    $boom_lifting = $_POST['boom_lifting'];
    $m_winch_hoist = $_POST['m_winch_hoist'];
    $aux_winch_hoist = $_POST['aux_winch_hoist'];
    $boom_extending = $_POST['boom_extending'];
    $outriggers = $_POST['outriggers'];
    $swings_slew = $_POST['swings_slew'];
    $hydraulic_system = $_POST['hydraulic_system'];
    $auto_moment_limiter = $_POST['auto_moment_limiter'];
    $swing_winch_brake = $_POST['swing_winch_brake'];
    $winch_drum_lock = $_POST['winch_drum_lock'];
    $leveling_device = $_POST['leveling_device'];
    $hook_block_assembly = $_POST['hook_block_assembly'];
    $boom_angle_indicator = $_POST['boom_angle_indicator'];
    $wind_speed_indicator = $_POST['wind_speed_indicator'];

    // Prepare SQL statement for updating the record based on report_no
    $sql = "UPDATE mobile_crane_loadtest SET
                examination_date = ?, 
                report_date = ?,
                sticker_no = ?,
                report_no = ?,                
                customer_name = ?, 
                customer_email = ?,
                customer_mobile = ?,
                inspector_name = ?,
                technical_manager = ?,
                quality_controller = ?,
                employer_address = ?,
                equipment_description = ?, 
                manufacturer = ?,
                model = ?,
                equipment_id = ?,
                equipment_serial_no = ?,
                certificate_no = ?,
                jrn = ?,
                common_text_area = ?,
                premises_address = ?, 
                safe_working_load = ?,
                manufacture_date = ?,
                last_exam_date = ?,
                first_examination = ?, 
                installed_correctly = ?,
                interval_6_months = ?,
                interval_12_months = ?,
                examination_scheme = ?, 
                exceptional_circumstances = ?,
                identification_any_part = ?,
                defect = ?,
                date_defect = ?, 
                repair_details = ?,
                test_particulars = ?,
                equipment_fit = ?,
                latest_date_exam = ?, 
                boom_length = ?,
                boom_angle = ?,
                swl_test_weight = ?,
                radius = ?, 
                comments = ?,
                boom_lifting = ?,
                m_winch_hoist = ?,
                aux_winch_hoist = ?,
                boom_extending = ?, 
                outriggers = ?,
                swings_slew = ?,
                hydraulic_system = ?,
                auto_moment_limiter = ?,
                swing_winch_brake = ?, 
                winch_drum_lock = ?,
                leveling_device = ?,
                hook_block_assembly = ?,
                boom_angle_indicator = ?, 
                wind_speed_indicator = ? 
            WHERE project_no = ?";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    // Bind parameters
    $stmt->bind_param('ssssssssssssssssssssssssssssssssssssssssssssssssssssssss', 
        $examination_date, 
        $report_date,
        $sticker_no,
        $report_no,
        $customer_name,
        $customer_email,
        $customer_mobile,
        $inspector_name,
        $technical_manager,
        $quality_controller,
        $employer_address,
        $equipment_description,
        $manufacturer,
        $model,
        $equipment_id,
        $equipment_serial_no,
        $certificate_no,
        $jrn,
        $common_text_area,
        $premises_address,
        $safe_working_load,
        $manufacture_date,
        $last_exam_date,
        $first_examination,
        $installed_correctly,
        $interval_6_months,
        $interval_12_months,
        $examination_scheme,
        $exceptional_circumstances,
        $identification_any_part,
        $defect,
        $date_defect,
        $repair_details,
        $test_particulars,
        $equipment_fit,
        $latest_date_exam,
        $boom_length,
        $boom_angle,
        $swl_test_weight,
        $radius,
        $comments,
        $boom_lifting,
        $m_winch_hoist,
        $aux_winch_hoist,
        $boom_extending,
        $outriggers,
        $swings_slew,
        $hydraulic_system,
        $auto_moment_limiter,
        $swing_winch_brake,
        $winch_drum_lock,
        $leveling_device,
        $hook_block_assembly,
        $boom_angle_indicator,
        $wind_speed_indicator,
        $project_no
    );

    // Execute the query
    if ($stmt->execute()) {
        // Success message or redirect
        header("Location: index.php?message=Record updated successfully");
        exit;
    } else {
        // Handle error
        echo "Error updating record: " . htmlspecialchars($stmt->error);
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}

// Clear output buffer
ob_end_flush();
?>