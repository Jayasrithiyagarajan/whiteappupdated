<?php
ob_start();
include_once('../../inc/function.php');
include_once('../../file/config.php'); // Include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $project_no = $_POST['project_no'];
    $date_of_report = $_POST['date_of_report'];
    $certificate_no = $_POST['certificate_no'];
    $report_no = $_POST['report_no'];
    $jrn = $_POST['jrn'];
    $color_code = $_POST['color_code'];
    $applicable_standards = $_POST['applicable_standards'];
    $companyName = $_POST['companyName'];
    $customer_name = $_POST['customer_name'];
    $customer_email = $_POST['customer_email'];
    $mobile = $_POST['mobile'];
    $inspector = $_POST['inspector'];
    $technical_manager = $_POST['technical_manager'];
    $employer_name_address = $_POST['employer_name_address'];
    $identification_no = $_POST['identification_no'];
    $wll_swl = $_POST['wll_swl'];
    $qty = $_POST['qty'];
    $type = $_POST['type'];
    $date_last_examination = $_POST['date_last_examination'];
    $manufacturer = $_POST['manufacturer'];
    $size = $_POST['size'];
    $length = $_POST['length'];
    $color = $_POST['color'];
    $ply = $_POST['ply'];
    $address_of_premises = $_POST['address_of_premises'];
    $next_examination_date = $_POST['next_examination_date'];
    $reason_for_examination = $_POST['reason_for_examination'];
    $date_of_this_examination = $_POST['date_of_this_examination'];
    $test_details = $_POST['test_details'];
    $status = $_POST['status'];
    $safe_to_use = $_POST['safe_to_use'];

    // Prepare the SQL update query
    $query = "UPDATE lifting_gear_certificates SET 
        date_of_report = '$date_of_report',
        certificate_no = '$certificate_no',
        report_no = '$report_no',
        jrn = '$jrn',
        color_code = '$color_code',
        applicable_standards = '$applicable_standards',
        companyName = '$companyName',
        customer_name = '$customer_name',
        customer_email = '$customer_email',
        mobile = '$mobile',
        inspector = '$inspector',
        technical_manager = '$technical_manager',
        employer_name_address = '$employer_name_address',
        identification_no = '$identification_no',
        wll_swl = '$wll_swl',
        qty = '$qty',
        type = '$type',
        date_last_examination = '$date_last_examination',
        manufacturer = '$manufacturer',
        size = '$size',
        length = '$length',
        color = '$color',
        ply = '$ply',
        address_of_premises = '$address_of_premises',
        next_examination_date = '$next_examination_date',
        reason_for_examination = '$reason_for_examination',
        date_of_this_examination = '$date_of_this_examination',
        test_details = '$test_details',
        status = '$status',
        safe_to_use = '$safe_to_use'
        WHERE project_no = '$project_no'";

    // Execute the query
    if (mysqli_query($conn, $query)) {
        // Success message or redirect
        header("Location: index.php?message=Record updated successfully");
        exit;
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request method.";
}

// Close the database connection
// mysqli_close($conn);
// Close the output buffer and flush it
ob_end_flush();
?>
