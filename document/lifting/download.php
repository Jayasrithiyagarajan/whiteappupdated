<?php
require_once('../../vendor/autoload.php');
include_once('../../file/config.php'); // Database connection

$project_no = $_GET['project_no'] ?? '';

$sql = "SELECT * FROM lifting_gear_certificates WHERE project_no = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $project_no);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("No certificates found for the given project ID.");
}

$base_dir = __DIR__ . '/../../inspector/uploads/';
$url_base = '../../inspector/uploads/';

$mpdf = new \Mpdf\Mpdf([
    'orientation' => 'L',
    'margin_left' => 5,
    'margin_right' => 5,
    'margin_top' => 5,
    'margin_bottom' => 5,
    'margin_header' => 5,
    'margin_footer' => 5
]);

$mpdf->SetWatermarkImage('../logo.png', 0.2, '', 'center', [50, 30]); // width: 50mm, height: 30mm
// $mpdf->SetWatermarkImage('../logo.png', 0.2, 'center', [90, 70]);
$mpdf->showWatermarkImage = true;

// $mpdf->SetWatermarkImage(
//     '../logo.png',
//     0.2,
//     ['position' => 'absolute', 'x' => '50%', 'y' => '50%', 'w' => 90, 'h' => 70],
//     ['show' => true]
// );


while ($row = $result->fetch_assoc()) {
    $identification_no = nl2br($row['identification_no']);
    $description = nl2br($row['description']);
    
    $date_of_report = date("d-m-Y", strtotime($row['date_of_report']));
    //$date_last_examination = date("d-m-Y", strtotime($row['date_last_examination']));
    $date_of_this_examination = date("d-m-Y", strtotime($row['date_of_this_examination']));
    $next_examination_date = date("d-m-Y", strtotime($row['next_examination_date']));
    
    // Inspector Signature
    // Inspector Signature - Improved version
// Inspector Signature
$inspector_name = $row['inspector'];
$inspector_foldername = strtolower(str_replace(' ', '_', $inspector_name)); // Converts "Bubu A" to "bubu_a"
$inspector_signature_path = '../../inspector/uploads/' . $inspector_foldername . '/images/signature_image.jpg';

// Check if signature exists, otherwise use default
if (!file_exists($inspector_signature_path)) {
    $inspector_signature_path = '../assets/img/avatar/default-signature.png';
}

// Make sure path uses forward slashes
$inspector_signature_path = str_replace('\\', '/', $inspector_signature_path);
$inspector_signature_html = "<img src='" . htmlspecialchars($inspector_signature_path) . "' class='sign' alt='Inspector Signature'>";


    // Technical Manager Signature
    // $technical_manager_name = $row['technical_manager'];
    // $technical_manager_signature_path = "../uploads/{$technical_manager_name}.png";
    // if (file_exists(__DIR__ . "/../uploads/{$technical_manager_name}.png")) {
    //     $technical_manager_signature_html = "<img src='{$technical_manager_signature_path}' class='sign' alt='Technical Manager Signature'>";
    // } else {
    //     $technical_manager_signature_html = "<img src='../assets/img/avatar/default-signature.png' class='sign' alt='Default Signature'>";
    // }
    
    $technical_manager_name = $row['technical_manager'];
$uploads_dir = __DIR__ . "/../uploads/";
$web_path_base = "../uploads/";
$default_signature = "<img src='../assets/img/avatar/default-signature.png' class='sign' alt='Default Signature'>";

// List of allowed extensions to check
$extensions = ['png', 'jpg', 'jpeg'];

$technical_manager_signature_html = $default_signature;

foreach ($extensions as $ext) {
    $file_path = "{$uploads_dir}{$technical_manager_name}.{$ext}";
    if (file_exists($file_path)) {
        $technical_manager_signature_html = "<img src='{$web_path_base}{$technical_manager_name}.{$ext}' class='sign' alt='Technical Manager Signature'>";
        break; // Stop at the first match
    }
}


    // Quality Controller Signature
    $qc_name = $row['quality_controller'];
    $qc_signature_path = "../uploads/qc/{$qc_name}.jpeg";
    if (!file_exists(__DIR__ . "/../uploads/qc/{$qc_name}.jpeg")) {
        $qc_signature_path = "../assets/img/avatar/default-signature.png";
    }

    // Optional: LEEA Number if available
    //$leea_number = $row['leea_number'] ?? '';
    
    // Get LEEA number for the inspector
// Get LEEA number for the inspector
$leea_number = '';
$leea_sql = "SELECT leea_number FROM inspectors WHERE inspector_name = ?";
$leea_stmt = $conn->prepare($leea_sql);
$leea_stmt->bind_param('s', $inspector_name); // FIXED HERE
$leea_stmt->execute();
$leea_result = $leea_stmt->get_result();

if ($leea_result->num_rows > 0) {
    $leea_row = $leea_result->fetch_assoc();
    $leea_number = $leea_row['leea_number'];
}
$leea_stmt->close();


// HTML content
$html = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lifting Gears Certificate</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.4;
            margin: 0;
            padding: 0;
            font-size: 10px;
        }
        .container-fluid {
            width: 100%;
            margin: auto;
            padding: 10px;
        }
        h1, h2 {
            text-align: center;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 4px 0;
        }
        .no-border {
            border: none !important;
        }
        th, td {
            border: 1px solid black;
            padding: 4px;
            text-align: left;
        }
        th {
            background-color: #c8d7f1;
            text-align: center;
        }
        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }
        .signature {
            text-align: center;
        }
        .signature p {
            margin: 0;
        }
        .signature span {
            display: block;
            margin-top: 30px;
            border-top: 1px solid #000;
        }
        .footer {
            text-align: center;
        }
        .sign {
    height: 60px;
    max-width: 100px;
    object-fit: contain;
    display: block;
    margin: 0 auto;
        }
        .qrcode {
      width: 70px;
      height: 70px;
      float: right;
      margin-top: -55px;
    }
    .leea {
      width: 65px;
      height: 62px;
      float: left;
      margin-top: -55px;
    }
    
    
    
    /* Increase width for specific columns */
    .table-responsive .table thead tr th:nth-child(1), /* Identification No./Serial No. */
    .table-responsive .table tbody tr td:nth-child(1) {
        width: 150px; /* Adjust this value as needed */
    }
    
    
    
    .table-responsive .table thead tr th:nth-child(4), /* Description */
    .table-responsive .table tbody tr td:nth-child(4) {
        width: 200px; /* Adjust this value as needed */
    }
    
    
    
        @media (max-width: 768px) {
            .signature-section {
                flex-direction: column;
                align-items: center;
            }
            .signature {
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid mt-5">
    
    <div class="header">
      <img src="../header1.jpg" alt="Header Image" style="width: 100%; height: 140px; object-fit: cover;">

    </div>
    <img src="../leea.png" class="leea" alt="Leea">
    <img src="../code.png" class="qrcode" alt="Qr Code">
    
    
     <table>
        <tr>
            <td colspan="7" style="border-top: none; border-left: none;"> </td>
            <td colspan="7">Job. Ref. No.: <strong>{$row['jrn']} </strong></td>
            <td colspan="5">Certificate No.: <strong> {$row['certificate_no']} </strong></td>
        </tr>
        <tr>
            <td colspan="3">Report No.: <strong>{$row['report_no']} </strong></td>
            <td colspan="4">Date of Report: <strong> {$date_of_report} </strong></td>
            <td colspan="4">Color Code (if required): <em><strong> {$row['color_code']} </strong></em></td>
            <td colspan="8">Applicable Standard(s): <strong>{$row['applicable_standards']} </strong></td>
        </tr>
        
        </table>
    
       
        
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <td colspan="6" style="text-align: center;padding-top:0px">
                        Name & Address of the employer for whom the examination was made<br/><br/>
                        <span style="font-size: 14px; text-transform: uppercase; color: #244b90; font-weight:bold; ">    {$row['customer_name']}</span>
                        </td>
                        <td colspan="4" style="text-align: center;padding-top:0px">
                        Address of the premises at which the examination was made<br/><br/>
                        <span style="font-size: 14px; text-transform: uppercase; color: #244b90;font-weight:bold; "> {$row['address_of_premises']}</span>
                        </td>
                        <td colspan="2" style="text-align: left;">Status:<br/>
                         <strong>   ND</strong>-No Defect<br/>
                         <span>   <strong>SDR</strong></span>-See Defect Report<br/>
                         <span>   <strong>NF</strong></span>- Not Found 
                        </td>
                    </tr>
                    <tr> 
                        <th>Identification No./Serial No.</th>
                        <th>QTY.</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>WLL or SWL</th>
                        <th>Date of Last Examination</th>
                        <th>Date of this Examination</th>
                        <th>Latest date of the next examination</th>
                        <th>Reason for Examination (See Below)</th>
                        <th>Details of test</th>
                        <th>Status (See Above)</th>
                        <th>Safe to Use (Yes or No)</th>
                    </tr>
                </thead>
                <tbody>
                <tr style="height: 250px; ">                
                <td style="text-align: center;"><strong>
                <strong>$identification_no</strong>
                </strong> </td>
                <td style="text-align: center;"><strong> {$row['qty']}</strong> </td>
                <td> <strong> {$row['type']} </strong> </td>
                <td>
                <strong>$description</strong>
                </td>
                <td style="text-align: center;"> <strong> {$row['wll_swl']} </strong> </td>
                <td style="text-align: center;"> <strong>{$row['date_last_examination']} </strong> </td>
                <td style="text-align: center;"> <strong> {$date_of_this_examination}</strong> </td>
                <td style="text-align: center;"> <strong> {$next_examination_date}  </strong> </td>
                <td style="text-align: center; text-transform: uppercase;"> <strong> {$row['reason_for_examination']}</strong> </td>
                <td style="text-align: center; text-transform: uppercase;"> <strong> {$row['test_details']} </strong> </td>
                <td style="text-align: center;"> <strong> {$row['status']} </strong> </td>
                <td style="text-align: center;"> <strong> {$row['safe_to_use']} </strong></td>
            </tr>
                    <tr>
                        <td colspan="2" style="text-align: center;">Reason for Examination</td>
                        <td colspan="2" style="text-align: center;">3 Monthly: A</td>
                        <td colspan="2" style="text-align: center;">6 Monthly: B</td>
                        <td colspan="2" style="text-align: center;">12 Monthly: C</td>
                        <td colspan="2" style="text-align: center;">Written Scheme: D</td>
                        <td colspan="2" style="text-align: center;">Exceptional Circumstance: E</td>
                    </tr>
                    
                    
                </tbody>
            </table>
        </div>
        
        
        
        
        <div class="table-responsive keep-together">
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
	<strong>
	{$row['inspector']}
	</strong>
	</td>	
	<td style="text-align: center;">
	<strong>
	LEEA No: $leea_number
	</strong>
	</td>	
	<td style="text-align: center;" rowspan="2">
	<strong>
	<img src="../qcpass.png" class="sign" alt="Default Signature">
	</strong>
	</td>
	
	<td style="text-align: center;">
	<strong>
	TECHNICAL MANAGER
          </strong>
	</td>	
	
	<td rowspan="2"  style="text-align: center;">
	<img src="../seal.jpeg" class="sign" alt="Default Signature">
	</td>
	</tr>
	
	<tr>
	<td colspan="2" style="text-align: center;">
    <img src="{$inspector_signature_path}" class="sign" alt="Inspector Signature">
</td>
	
	<td style="text-align:center;">
	{$technical_manager_signature_html}
	</td>
	
	</tr>
    </tbody>
  </table>
</div>    
        
        
        <div class="table-responsive">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th colspan="12" style="text-align: center;text-transform:uppercase;">
                            <strong>Overseas Full Member of Lifting Equipment Engineers Association (LEEA, United Kingdom) <span style="color:red;">662</span></strong>
                        </th>
                    </tr>
                </tbody>
            </table>
            <div class="footer">
                <img src="../footer.jpg" alt="Footer Image" style="width: 100%; height: 90px;">

            </div>
        </div>
    </div>
</body>
</html>

HTML;

// Add the HTML content to the PDF
$mpdf->AddPage();
$mpdf->WriteHTML($html);
}

// Write the HTML content to the PDF
// $mpdf->WriteHTML($html);

// Add watermark image

// Output the PDF to the browser for download
// $mpdf->Output('lifting_gears_certificate.pdf', 'D');

$filename = "Below the Hook_certificate_" . $project_no . ".pdf";
$mpdf->Output($filename, 'D'); // Force download


$stmt->close();
$conn->close();

?>
<!---->