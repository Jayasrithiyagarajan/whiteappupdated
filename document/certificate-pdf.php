<?php
require_once('../vendor/autoload.php');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "3partyapp";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM certificates WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_GET['id']);
$stmt->execute();
$result = $stmt->get_result();
$certificate = $result->fetch_assoc();

if ($certificate) {
    // Create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // Set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Your Company');
    $pdf->SetTitle('Crane Health Check Certificate');
    $pdf->SetSubject('Certificate');
    $pdf->SetKeywords('TCPDF, PDF, certificate, test, crane');

    // Set default header data
    $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

    // Set header and footer fonts
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    // Set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // Set margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    // Set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    // Set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // Add a page
    $pdf->AddPage();

    // Set font
    $pdf->SetFont('helvetica', '', 12);

    // HTML content
    $html = "
    <h1 style='text-align: center;'>CRANE HEALTH CHECK CERTIFICATE</h1>
    <h2 style='text-align: center;'>FOR OFFSHORE PEDESTAL CRANES AND FLOATING CRANES</h2>
    <table cellspacing='0' cellpadding='1' border='1'>
        <tr>
            <td>Date of Inspection:</td><td>{$certificate['inspection_date']}</td>
            <td>Report No.:</td><td>{$certificate['report_no']}</td>
        </tr>
        <tr>
            <td>Certificate No.:</td><td>{$certificate['certificate_no']}</td>
            <td>JRN:</td><td>{$certificate['jrn']}</td>
        </tr>
    </table>
    <h3>A. GENERAL INFORMATION</h3>
    <table cellspacing='0' cellpadding='1' border='1'>
        <tr><td>Vessel Name & Location:</td><td>{$certificate['vessel_name']} {$certificate['location']}</td></tr>
        <tr><td>Company Name:</td><td>{$certificate['company_name']}</td></tr>
        <tr><td>Manufacturer:</td><td>{$certificate['manufacturer']}</td></tr>
        <tr><td>Type of Crane:</td><td>{$certificate['crane_type']}</td></tr>
        <tr><td>Model:</td><td>{$certificate['model']}</td></tr>
        <tr><td>Manufacturing Year:</td><td>{$certificate['manufacturing_year']}</td></tr>
        <tr><td>Asset Number:</td><td>{$certificate['asset_number']}</td></tr>
        <tr><td>Serial Number:</td><td>{$certificate['serial_number']}</td></tr>
        <tr><td>Capacity (SWL):</td><td>{$certificate['capacity']}</td></tr>
        <tr><td>Date of Previous Test of Crane:</td><td>{$certificate['previous_test_date']}</td></tr>
    </table>
    <h3>B. GENERAL INFORMATION</h3>
    <table cellspacing='0' cellpadding='1' border='1'>
        <tr><td>OPERATION</td><td>COMMENTS</td></tr>
        <tr><td>Crane Structure Condition</td><td>{$certificate['crane_structure_condition']}</td></tr>
        <tr><td>Auto Moment Limiter (LMI)</td><td>{$certificate['auto_moment_limiter']}</td></tr>
        <tr><td>Swinging / Slewing Function</td><td>{$certificate['swinging_slewing_function']}</td></tr>
        <tr><td>Anti-Two-Block (A2B) Function</td><td>{$certificate['anti_two_block_function']}</td></tr>
        <tr><td>Hydraulic & Pneumatic System</td><td>{$certificate['hydraulic_pneumatic_system']}</td></tr>
        <tr><td>Winch Drum Lock / Pawls</td><td>{$certificate['winch_drum_lock_pawls']}</td></tr>
        <tr><td>Wire Ropes Condition</td><td>{$certificate['wire_ropes_condition']}</td></tr>
        <tr><td>Hook Block Assembly</td><td>{$certificate['hook_block_assembly']}</td></tr>
        <tr><td>Boom Lifting Extending & Retracting</td><td>{$certificate['boom_lifting_extending']}</td></tr>
        <tr><td>Boom Angle Indicator</td><td>{$certificate['boom_angle_indicator']}</td></tr>
        <tr><td>Emergency Boom Lowering</td><td>{$certificate['emergency_boom_lowering']}</td></tr>
        <tr><td>Emergency Shutdown</td><td>{$certificate['emergency_shutdown']}</td></tr>
    </table>
    <p>We hereby certify that the above Crane has been duly Inspected (Health Check) as per the Manufacturer’s Recommendation or based on ASME B30.3 – 2016 B30.4 – 2015 B30.5 – 2018 B30.7 – 2016 B30.8 – 2015 B30.9 – 2018 B30.10 – 2015 B30.22 – 2016 API SPECS 2C – 2012 and API RP 2D – 2014.</p>
    <p>The latest date by which the next inspection shall be carried out: <strong>{$certificate['next_inspection_date']}</strong></p>
    <table cellspacing='0' cellpadding='1' border='1'>
        <tr>
            <td>INSPECTED BY</td><td>{$certificate['inspected_by']}</td>
            <td>SIGNATURE:</td><td>___________________</td>
        </tr>
        <tr>
            <td>APPROVED BY:</td><td>{$certificate['approved_by']}</td>
            <td>SIGNATURE:</td><td>___________________</td>
        </tr>
    </table>
    ";

    // Output the HTML content
    $pdf->writeHTML($html, true, false, true, false, '');

    // Close and output PDF document
    $pdf->Output('crane_certificate.pdf', 'I');
} else {
    echo "Certificate not found.";
}

$stmt->close();
$conn->close();
?>
