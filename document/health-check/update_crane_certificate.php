<?php
// Enable output buffering to prevent any premature output
ob_start();

// Enable error reporting for debugging
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// Include database connection and functions
include_once('../../inc/function.php');
include_once('../../file/config.php');

// Check if the form has been submitted
if (isset($_POST['update'])) {
    // Fetch form data
    $id = $_POST['id'];
    $inspection_date = $_POST['inspection_date'];
    $certificate_no = $_POST['certificate_no'];
    $report_no = $_POST['report_no'];
    $jrn = $_POST['jrn'];
    $project_no = $_POST['project_no'];
    // $companyName = $_POST['companyName'];
    $customer_name = $_POST['customer_name'];
    $customer_email = $_POST['customer_email'];
    $mobile = $_POST['mobile'];
    $inspector = $_POST['inspector'];
    $technical_manager = $_POST['technical_manager'];
    $quality_controller = $_POST['quality_controller'];
    $vessel_name_location = $_POST['vessel_name_location'];
    $manufacturer = $_POST['manufacturer'];
    $crane_type = $_POST['crane_type'];
    $asset_number = $_POST['asset_number'];
    $serial_number = $_POST['serial_number'];
    $model = $_POST['model'];
    $manufacturing_year = $_POST['manufacturing_year'];
    $address = $_POST['address'];
    $capacity_swl = $_POST['capacity_swl'];
    $previous_test_date = $_POST['previous_test_date'];
    $crane_structure_condition = $_POST['crane_structure_condition'];
    $swinging_slewing_function = $_POST['swinging_slewing_function'];
    $hydraulic_pneumatic_system = $_POST['hydraulic_pneumatic_system'];
    $wire_ropes_condition = $_POST['wire_ropes_condition'];
    $boom_lifting_extending_retracting = $_POST['boom_lifting_extending_retracting'];
    $emergency_boom_lowering = $_POST['emergency_boom_lowering'];
    $auto_moment_limiter = $_POST['auto_moment_limiter'];
    $anti_two_block = $_POST['anti_two_block'];
    $winch_drum_lock_pawls = $_POST['winch_drum_lock_pawls'];
    $hook_block_assembly = $_POST['hook_block_assembly'];
    $boom_angle_indicator = $_POST['boom_angle_indicator'];
    $emergency_shutdown = $_POST['emergency_shutdown'];
    $latest_inspection_date = $_POST['latest_inspection_date'];

      // Get the current timestamp for updated_at
    $updated_at = date('Y-m-d H:i:s');

    // Build update query
    $sql = "UPDATE crane_health_check_certificate SET
                inspection_date = '$inspection_date',
                certificate_no = '$certificate_no',
                report_no = '$report_no',
                jrn = '$jrn',
                project_no = '$project_no',                
                customer_name = '$customer_name',
                customer_email = '$customer_email',
                mobile = '$mobile',
                inspector = '$inspector',
                technical_manager = '$technical_manager',
                quality_controller = '$quality_controller',
                vessel_name_location = '$vessel_name_location',
                manufacturer = '$manufacturer',
                crane_type = '$crane_type',
                asset_number = '$asset_number',
                serial_number = '$serial_number',
                model = '$model',
                manufacturing_year = '$manufacturing_year',
                address = '$address',
                capacity_swl = '$capacity_swl',
                previous_test_date = '$previous_test_date',
                crane_structure_condition = '$crane_structure_condition',
                swinging_slewing_function = '$swinging_slewing_function',
                hydraulic_pneumatic_system = '$hydraulic_pneumatic_system',
                wire_ropes_condition = '$wire_ropes_condition',
                boom_lifting_extending_retracting = '$boom_lifting_extending_retracting',
                emergency_boom_lowering = '$emergency_boom_lowering',
                auto_moment_limiter = '$auto_moment_limiter',
                anti_two_block = '$anti_two_block',
                winch_drum_lock_pawls = '$winch_drum_lock_pawls',
                hook_block_assembly = '$hook_block_assembly',
                boom_angle_indicator = '$boom_angle_indicator',
                emergency_shutdown = '$emergency_shutdown',
                latest_inspection_date = '$latest_inspection_date',
                updated_at = '$updated_at' -- Update the timestamp

            WHERE project_no = '$project_no'";

    // Execute query and check if update is successful
    if ($conn->query($sql) === TRUE) {
        // Redirect to the list page after successful update
        header("Location: index.php?msg=Health check updated successfully");
        exit(); // Ensure the script stops executing after redirect
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// Close the output buffer and flush it
ob_end_flush();
?>
