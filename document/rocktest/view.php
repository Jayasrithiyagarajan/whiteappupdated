<?php
require_once('../../vendor/autoload.php');
include_once('../../file/config.php'); // Include your database configuration file

// Check if project_no is provided in the query string
if (isset($_GET['project_no'])) {
    $project_no = $_GET['project_no'];
    // Fetch data from the database
    $sql = "SELECT * FROM rocking_test_certificate WHERE project_no = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $project_no);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc(); // Fetch the data as an associative array
    } else {
        die("No record found for project number: $project_no");
    }

    $stmt->close();
} else {
    die("Project number not provided.");
}

// Assign fetched data to variables
$certificate_no = $row['certificate_no'];
$report_no = $row['report_no'];
$jrn = $row['jrn'];
$date_of_report = $row['report_date'];
$formatted_date_of_report = date("d-m-Y", strtotime($date_of_report));
$color_code = $row['color_code'];
$customer_name = $row['customer_name'];
$location =  $row['location'];
// $employer_name_address = $row['employer_address'];
// $premises_address = $row['premises_address'];
$applicable_standards = $row['applicable_standards'];
$inspected_item_type = $row['inspected_item_type'];
$identification_no = $row['identification_no'];
$quantity = $row['quantity'];
$description = $row['description'];
$wll_swl = $row['wll_swl'];
$last_examination_date = $row['last_exam_date'];
$this_examination_date = $row['this_exam_date'];
$formatted_this_examination_date = date("d-m-Y", strtotime($this_examination_date));
$next_examination_date = $row['next_exam_date'];
$formatted_next_examination_date = date("d-m-Y", strtotime($next_examination_date));
$reason_for_examination = $row['reason_for_exam'];
// $details_of_test = $row['details_of_test'];
$status = $row['status'];
$safe_to_use = $row['safe_to_use'];
$grease_condition = $row['grease_condition'];
$last_aft = $row['last_aft'];
$last_stbd = $row['last_stbd'];
$last_forward = $row['last_forward'];
$last_port_side = $row['last_port_side'];
$actual_aft = $row['actual_aft'];
$actual_stbd = $row['actual_stbd'];
$actual_forward = $row['actual_forward'];
$actual_port_side = $row['actual_port_side'];
$permitted_aft = $row['permitted_aft'];
$permitted_stbd = $row['permitted_stbd'];
$permitted_forward = $row['permitted_forward'];
$permitted_port_side = $row['permitted_port_side'];
$result_aft = $row['result_aft'];
$result_stbd = $row['result_stbd'];
$result_forward = $row['result_forward'];
$result_port_side = $row['result_port_side'];
$inspector = $row['inspector'];
$technical_manager = $row['technical_manager'];
$quality_controller = $row['quality_controller'];


// Get LEEA number for the inspector
$leea_number = '';
$leea_sql = "SELECT leea_number FROM inspectors WHERE inspector_name = ?";
$leea_stmt = $conn->prepare($leea_sql);
$leea_stmt->bind_param('s', $inspector);
$leea_stmt->execute();
$leea_result = $leea_stmt->get_result();

if ($leea_result->num_rows > 0) {
    $leea_row = $leea_result->fetch_assoc();
    $leea_number = $leea_row['leea_number'];
}
$leea_stmt->close();

// Signature image base64
$inspector_folder = strtolower(str_replace(' ', '_', $inspector));
$inspectorSignPath = "../../inspector/uploads/{$inspector_folder}/images/signature_image.jpg";
$technicalManagerSignPath = "../uploads/" . $technical_manager . ".jpg";
// $uploadDir = "../uploads/";
// $allowedExtensions = ['png', 'jpg', 'jpeg'];
// $technicalManagerSignPath = '';
// foreach ($allowedExtensions as $ext) {
//     $path = $uploadDir . $technical_manager . "." . $ext;
//     if (file_exists($path)) {
//         $technicalManagerSignPath = $path;
//         break;
//     }
// }

if (empty($technicalManagerSignPath)) {
    // Use a fallback image if no valid signature is found
    $technicalManagerSignPath = "../sign.jpg";
}



$qualityControllerSignPath = "../uploads/qc/" . $quality_controller . ".jpeg";


// Load Base64 images if they exist
// $inspectorSignatureImg = file_exists($inspectorSignPath) 
//     ? '<img src="data:image/jpeg;base64,' . base64_encode(file_get_contents($inspectorSignPath)) . '" class="sign" alt="Inspector Signature">'
//     : "Signature not available";

// $technicalManagerSignatureImg = file_exists($technicalManagerSignPath) 
//     ? '<img src="data:image/jpeg;base64,' . base64_encode(file_get_contents($technicalManagerSignPath)) . '" class="sign" alt="Technical Manager Signature">'
//     : "Signature not available";

// $qualityControllerSignatureImg = file_exists($qualityControllerSignPath) 
//     ? '<img src="data:image/jpeg;base64,' . base64_encode(file_get_contents($qualityControllerSignPath)) . '" class="sign" alt="Quality Controller Signature">'
//     : "Signature not available";


// Create an instance of the mPDF class with landscape orientation and minimal margins
$mpdf = new \Mpdf\Mpdf([
    'orientation' => 'L',
    'margin_left' => 5,
    'margin_right' => 5,
    'margin_top' => 1,
    'margin_bottom' => 1,
    'margin_header' => 3,
    'margin_footer' => 3
]);

// HTML content
$html = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rocking Test Certificate</title>
    <style>
        .certificate-title {
            text-align: center;
            margin-top: -10px;
        }
        p {
            font-size: 11px;
        }
        body {
            font-family: Arial, sans-serif;
            
        }
        .container {
            max-width: 800px;
            margin: auto;
            padding: 10px;
        }
        h1, h3 {
            text-align: center;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1px;
        }
        th, td {
            padding: 4px;
            border: 1px solid #000;
            text-align: left;
            font-size: 10px;
        }
        .section-title {
            background-color: #bfdaef;
            font-weight: bold;
            font-size: 10px;
            text-align: center;
        }
        .header, .footer {
            text-align: center;
        }
        .header img, .footer img {
            max-width: 100%;
        }
        .sign {
    height: 60px;
    max-width: 100px;
    object-fit: contain;
    display: block;
    margin: 0 auto;
        }
        .seal {
            width: 40px;
            height: 40px;
        }
        .qrcode {
      width: 70px;
      height: 70px;
      float: right;
      margin-top: -40px;
    }
    .leea {
      width: 65px;
      height: 62px;
      float: left;
      margin-top: -40px;
    }
        @media (max-width: 600px) {
            table {
                font-size: 8px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <img src="../header1.jpg" alt="Header Image" style="width: 100%; height: 125px;">
    </div>
    <img src="../leea.png" class="leea" alt="Leea">
    <img src="../code.png" class="qrcode" alt="Qr Code">
    
   <div class="text-center">
        <p class="certificate-title"><strong> ROCKING TEST (CERTIFICATE OF THOROUGH EXAMINATION)
        </strong></p>
    </div>
    <table>
        <tr>
            <td colspan="7" style="border-top: none; border-left: none;"> </td>
            <td colspan="7">Job. Ref. No.: <strong>$jrn</strong></td>
            <td colspan="5">Certificate No.: <strong>$certificate_no</strong></td>
        </tr>
        <tr>
            <td colspan="3">Report No.: <strong>$report_no</strong></td>
            <td colspan="4">Date of Report: <strong>$formatted_date_of_report</strong></td>
            <td colspan="4">Color Code (if required): <em><strong> $color_code</strong></em></td>
            <td colspan="8">Applicable Standard(s): <strong>$applicable_standards</strong> </td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: center;">
    Name &amp; Address of the employer for whom the examination was made:<br/>
    <strong style="color: #244b90; font-size: 12px; text-transform: uppercase;">$customer_name</strong>
</td>
<td colspan="7" style="text-align: center;">
    Address of the premises at which the examination was made:<br/>
    <strong style="color: #244b90; font-size: 12px; text-transform: uppercase;">$location</strong>
</td>

            <td colspan="5">
                Status:<b>ND</b>-No Defect<br>
                <b>SDR</b>-See Defect Report
                <b>NF</b>- Not Found
            </td>
        </tr>
        <tr>
            <td colspan="19">INSPECTED ITEM TYPE: <strong style="color: #244b90; font-size: 12px; text-transform: uppercase;">$inspected_item_type</strong></td>
        </tr>
        <tr class="section-title">
            <td style="text-align: center;"><strong>Identification No./Serial No.</strong></td>
            <td style="text-align: center;"><strong>QTY.</strong></td>
            <td colspan="3" style="text-align: center;"><strong>Description</strong></td>
            <td style="text-align: center;"><strong>WLL or SWL</strong></td>
            <td style="text-align: center;"><strong>Date of Last Examination</strong></td>
            <td colspan="2" style="text-align: center;"><strong>Date of this Examination</strong></td>
            <td colspan="3" style="text-align: center;"><strong>Latest date of the next examination</strong></td>
            <td colspan="2" style="text-align: center;"><strong>Reason for Examination (See Below)</strong></td>
            <td style="text-align: center;"><strong>Details of test</strong></td>
            <td style="text-align: center;"><strong>Status (See Above)</strong></td>
            <td style="text-align: center;"><strong>Safe to Use (Yes or No)</strong></td>
        </tr>
        <tr style="height: 200px;">
            <td style="text-align: center;"><strong>$identification_no</strong></td>
            <td style="text-align: center;"><strong>$quantity</strong></td>
            <td colspan="3"><strong>$description</strong></td>
            <td style="text-align: center;"><strong>$wll_swl</strong></td>
            <td style="text-align: center;"><strong>$last_examination_date</strong></td>
            <td colspan="2" style="text-align: center;"><strong>$formatted_this_examination_date</strong></td>
            <td colspan="3" style="text-align: center;"><strong>$formatted_next_examination_date</strong></td>
            <td colspan="2" style="text-align: center;"><strong>$reason_for_examination</strong></td>
            <td style="text-align: center;"><strong>ROCKING TEST AS THE BELOW TABLE</strong></td>
            <td style="text-align: center;"><strong>$status</strong></td>
            <td style="text-align: center; text-transform: uppercase;"><strong>$safe_to_use</strong></td>
        </tr>
        <tr>
            <td colspan="5" style="text-align: center;">Grease Sample Condition After Analyzing: </td>
            <td colspan="12"> <strong style="text-transform: uppercase;">$grease_condition</strong></td>
        </tr>
        <tr>
            <td  class="section-title" colspan="5" style="text-align: center;">Test Positions</td>
            <td  class="section-title" colspan="3" style="text-align: center;"><strong>AFT</strong></td>
            <td  class="section-title" colspan="3" style="text-align: center;"><strong>STBD</strong></td>
            <td  class="section-title" colspan="3" style="text-align: center;"><strong>FORWARD</strong></td>
            <td  class="section-title" colspan="3" style="text-align: center;"><strong>PORT SIDE</strong></td>
        </tr>
        <tr>
            <td colspan="5" style="text-align: center;">Last Measured Limits to be compared</td>
            <td colspan="3" style="text-align: center;"><strong>$last_aft</strong></td>
            <td colspan="3" style="text-align: center;"><strong>$last_stbd</strong></td>
            <td colspan="3" style="text-align: center;"><strong>$last_forward</strong></td>
            <td colspan="3" style="text-align: center;"><strong>$last_port_side</strong></td>
        </tr>
        <tr>
            <td colspan="5" style="text-align: center;"> Actual Deviation Measured by Dial Gauge Readings </td>
            <td colspan="3" style="text-align: center;"><strong>$actual_aft</strong></td>
            <td colspan="3" style="text-align: center;"><strong>$actual_stbd</strong></td>
            <td colspan="3" style="text-align: center;"><strong>$actual_forward</strong></td>
            <td colspan="3" style="text-align: center;"><strong>$actual_port_side</strong></td>
        </tr>
        <tr>
            <td colspan="5" style="text-align: center;"> Permitted Limits to be Compared </td>
            <td colspan="3" style="text-align: center;"><strong>$permitted_aft</strong></td>
            <td colspan="3" style="text-align: center;"><strong>$permitted_stbd</strong></td>
            <td colspan="3" style="text-align: center;"><strong>$permitted_forward</strong></td>
            <td colspan="3" style="text-align: center;"><strong>$permitted_port_side</strong></td>
        </tr>
        <tr>
            <td colspan="5" style="text-align: center;"> Result/OK or Defect of SGOCC <br>
            Required actions for each result is cleared below
        </td>
            <td colspan="3" style="text-align: center;"><strong>$result_aft</strong></td>
            <td colspan="3" style="text-align: center;"><strong>$result_stbd</strong></td>
            <td colspan="3" style="text-align: center;"><strong>$result_forward</strong></td>
            <td colspan="3" style="text-align: center;"><strong>$result_port_side</strong></td>
        </tr>
        <tr>
            <td colspan="4" style="text-align: center;"><strong>OK: ACCEPTED</strong></td>
            <td colspan="6" style="text-align: center;"><strong>DEFECT: REJECTED &amp; NEEDS REPLACEMENT</strong></td>
            <td colspan="8" style="text-align: center;"><strong>SGOCC: SHORTENING GEAR OIL (LUBRICATION) CHARGING CYCLE</strong></td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;">Reason for Examination</td>
            <td colspan="3" style="text-align: center;">3 Monthly: <strong>A</strong></td>
            <td colspan="3" style="text-align: center;">6 Monthly: <strong>B</strong></td>
            <td colspan="2" style="text-align: center;">12 Monthly: <strong>C</strong></td>
            <td colspan="2" style="text-align: center;">Written Scheme: <strong>D</strong></td>
            <td colspan="6" style="text-align: center;">Exceptional Circumstance: <strong>E</strong></td>
        </tr>
    </table>
    
    
      <div class="table-responsive keep-together" >
  <table class="content-table">
    <thead>
      <tr>
        <th class="section-title text-center" colspan="2" style="text-align: center;">INSPECTED BY</th>
        <th class="section-title text-center" style="text-align: center;">QUALITY REVIEWED</th>
        <th class="section-title text-center" style="text-align: center;">APPROVED BY</th>
        <th class="section-title text-center" style="text-align: center;">SEAL</th>
      </tr>
    </thead>
    <tbody>
    
	<tr>
	<td style="text-align: center;">
	<strong>$inspector</strong>
	</td>	
	<td style="text-align: center;">
	<strong>
	LEEA No: $leea_number
	</strong>
	</td>	
	
	<td style="text-align: center;" rowspan="2">
 	<img src="../qcpass.png" class="sign"  alt="Quality Controller Signature">
	</td>
	
	<td style="text-align: center;">
	<strong>
	ASNT NDT LEVEL 3
	</strong>
	</td>	
	
	<td rowspan="2"  style="text-align: center;">
	<img src="../seal.jpeg" class="sign" alt="Default Signature">
	</td>
	</tr>
	
	<tr style="height: 200px;">
	<td colspan="2"  style="text-align: center;">
    <img src="$inspectorSignPath" class="sign"  alt="Inspector Signature">
	</td>
	
	
	
	
	<td  style="text-align: center;">
<img src="$technicalManagerSignPath" alt="Technical Manager Signature" class="sign">

	</td>
	
	</tr>
    </tbody>
  </table>
</div>    

    <div class="footer" style="margin-top: 5px;">
        <img src="../footer.jpg" alt="Footer Image" style="width: 100%; height: 70px;">
    </div>
</div>
</body>
</html>
HTML;

// Add the HTML content to the PDF
$mpdf->WriteHTML($html);

// Add watermark image
// $mpdf->SetWatermarkImage('../logo.png', 0.5, '', [70, 100]);
// $mpdf->SetWatermarkImage('../logo.png', 0.5, '', 'center');
$mpdf->SetWatermarkImage('../logo.png', 0.2, '', 'center', [50, 30]); // width: 50mm, height: 30mm
$mpdf->showWatermarkImage = true;


//$mpdf->showWatermarkImage = true;

// Output the PDF to the browser for download
$filename = "rocking_test_certificate_" . $project_no . ".pdf";
$mpdf->Output($filename, 'I');  // Force download with project number in filename

// $mpdf->Output('rocking_test_certificate.pdf', 'I');

$conn->close();
