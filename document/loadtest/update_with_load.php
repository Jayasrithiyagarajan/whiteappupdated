<?php
// Include your database connection file
include_once('../../file/config.php');

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $project_no = $_POST['project_no']; // Assuming certificate_id is passed for updating the correct record
    $examination_date = $_POST['examination_date'];
    $report_date = $_POST['report_date'];
    $report_no = $_POST['report_no'];
    $sticker_no = $_POST['sticker_no'];    
    // $serial_numbers = $_POST['serial_numbers'];
    // $company_name = $_POST['company_name'];
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
    // $width = $_POST['width'];
    // $thickness = $_POST['thickness'];
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
    $defect_future = $_POST['defect_future'];
    $date_defect = $_POST['date_defect'];
    $repair_details = $_POST['repair_details'];
    $test_particulars = $_POST['test_particulars'];
    $equipment_fit = $_POST['equipment_fit'];
    // $name_qualifications_person = $_POST['name_qualifications_person'];
    // $report_making_person_qualifications = $_POST['report_making_person_qualifications'];
    // $authenticating_person_name = $_POST['authenticating_person_name'];
    $latest_date_exam = $_POST['latest_date_exam'];
    // $name_address_of_employer = $_POST['name_address_of_employer'];

    // Prepare SQL statement for updating
    $sql = "UPDATE loadtest_certificate SET
                examination_date = ?, report_date = ?, report_no = ?, sticker_no = ?, 
                customer_name = ?, customer_email = ?, customer_mobile = ?, inspector_name = ?, technical_manager = ?, quality_controller = ?,
                employer_address = ?, equipment_description = ?, manufacturer = ?, model = ?, equipment_id = ?, equipment_serial_no = ?, certificate_no = ?, jrn = ?, common_text_area = ?,
                premises_address = ?, safe_working_load = ?, manufacture_date = ?, last_exam_date = ?, first_examination = ?, installed_correctly = ?, 
                interval_6_months = ?, interval_12_months = ?, examination_scheme = ?, exceptional_circumstances = ?, identification_any_part = ?, 
                defect = ?, defect_future = ?, date_defect = ?, repair_details = ?, test_particulars = ?, equipment_fit = ?, latest_date_exam = ? 
            WHERE project_no = ?";

    // Prepare statement
    $stmt = $conn->prepare($sql);
    
    // Bind parameters (exclude project_no from the values part, add it at the end)
    $stmt->bind_param('ssssssssssssssssssssssssssssssssssssss', 
        $examination_date, $report_date, $report_no, $sticker_no, $customer_name, $customer_email, $customer_mobile, $inspector_name, $technical_manager, $quality_controller,
        $employer_address, $equipment_description, $manufacturer, $model,
        $equipment_id, $equipment_serial_no, $certificate_no, $jrn, $common_text_area,
        $premises_address, $safe_working_load,
        $manufacture_date, $last_exam_date, $first_examination, $installed_correctly,
        $interval_6_months, $interval_12_months, $examination_scheme, $exceptional_circumstances, $identification_any_part, $defect, $defect_future,
        $date_defect, $repair_details, $test_particulars, $equipment_fit, $latest_date_exam, 
        $project_no // Add project_no here as the last parameter
    );

    // Execute the query
    if ($stmt->execute()) {
        $msg = "Loadtest updated successfully";
        header('Location: index.php?msg=' . $msg); 
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the connection
    $stmt->close();
    $conn->close();
}
?>