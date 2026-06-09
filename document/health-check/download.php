<?php
require_once('../../vendor/autoload.php'); // Adjust the path as necessary
include_once('../../file/config.php');  // Include your database connection file

// Fetch the record based on report_no
$project_no = $_GET['project_no'];  // Assuming report_no is passed via URL

$query = "SELECT * FROM crane_health_check_certificate WHERE project_no = '$project_no'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);  // Fetch record into $row array
} else {
    echo "No record found!";
    exit;
}


// Inspector Signature - Corrected version
$inspector_name = $row['inspector'];
// Fetch LEEA number from inspector table
$leea_query = "SELECT leea_number FROM inspectors WHERE inspector_name = '$inspector_name' LIMIT 1";
$leea_result = mysqli_query($conn, $leea_query);
$leea_number = "12345"; // Default value if not found

if ($leea_result && mysqli_num_rows($leea_result) > 0) {
    $leea_row = mysqli_fetch_assoc($leea_result);
    $leea_number = htmlspecialchars($leea_row['leea_number']);
}


$base_dir = __DIR__ . '/../../inspector/uploads/'; // Use server filesystem path

$inspector_folders = [
    $inspector_name, // Original case
    strtolower($inspector_name), // Lowercase version
    strtoupper($inspector_name), // Uppercase version
    ucfirst(strtolower($inspector_name)), // First letter capitalized
];

$signature_found = false;
$inspector_signature_path = '';

foreach ($inspector_folders as $folder) {
    $folder_formatted = strtolower(str_replace(' ', '_', $folder));
    $potential_path = $base_dir . $folder_formatted . '/images/signature_image.jpg';
    
    if (file_exists($potential_path)) {
        $inspector_signature_path = $url2 . 'inspector/uploads/' . $folder_formatted . '/images/signature_image.jpg';
        $signature_found = true;
        break;
    }
}


if ($signature_found) {
    $inspector_signature_html = '<img src="' . htmlspecialchars($inspector_signature_path) . '" class="sign" alt="Signature Image">';
} else {
    $inspector_signature_html = '<img src="../assets/img/avatar/default-signature.png" class="sign" alt="Default Signature">';
}


// Technical Manager Signature
// $technical_manager_name = $row['technical_manager'];
// $technical_manager_signature_path = "../uploads/{$technical_manager_name}.png";
// if (file_exists($technical_manager_signature_path)) {
//     $technical_manager_signature_html = '<img src="' . htmlspecialchars($technical_manager_signature_path) . '" class="sign" alt="Technical Manager Signature">';
// } else {
//     // Use a placeholder signature image if the actual one is not found
//     $technical_manager_signature_html = '<img src="../sign.jpg" class="sign" alt="Default Signature">';
// }


$technical_manager_name = $row['technical_manager'];
$upload_dir = "../uploads/";
$allowed_extensions = ['jpg', 'png', 'jpeg'];
$technical_manager_signature_path = '';
$technical_manager_signature_html = '';

// Loop through allowed extensions and check which file exists
foreach ($allowed_extensions as $ext) {
    $path = $upload_dir . $technical_manager_name . '.' . $ext;
    if (file_exists($path)) {
        $technical_manager_signature_path = $path;
        break;
    }
}

// Generate HTML
if ($technical_manager_signature_path !== '') {
    $technical_manager_signature_html = '<img src="' . htmlspecialchars($technical_manager_signature_path) . '" class="sign" alt="Technical Manager Signature">';
} else {
    // Use a placeholder signature image if the actual one is not found
    $technical_manager_signature_html = '<img src="../sign.jpg" class="sign" alt="Default Signature">';
}



// QC Signature
$qc_name = $row['quality_controller'];
$qc_signature_path = "../uploads/qc/{$qc_name}.jpeg";
if (file_exists($qc_signature_path)) {
    $qc_signature_html = '<img src="' . htmlspecialchars($qc_signature_path) . '" class="sign" alt="Technical Manager Signature">';
} else {
    // Use a placeholder signature image if the actual one is not found
    $qc_signature_html = '<img src="../sign.jpg" class="sign" alt="Default Signature">';
}         

$formatted_inspection_date = date('d-m-Y', strtotime($row['inspection_date']));

$formatted_latest_inspection_date = date('d-m-Y', strtotime($row['latest_inspection_date']));


$html = '
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Crane Health Check Certificate</title>
  <style>
    .certificate-title {
      text-align: center;
      margin: 4px;
    }
    p {
      font-size: 10px;
    }
    .newpara{
     font-size: 8px;
    }
    body {
      font-family: Arial, sans-serif;
      margin: 5px;
      padding: 8px;
      line-height: 1.4;
    }
    .container {
      max-width: 800px;
      margin: auto;
      padding: 10px;
    }
    h1, h3 {
      text-align: center;
      font-size: 12px;
      margin: 5px 0;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 3px;
    }
    th, td {
      padding: 3px;
      border: 1px solid #000;
      text-align: left;
      font-size: 10px;
    }
    .section-title {
      background-color: #bfdaef;
      font-size: 11px;
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
      width: 30px;
      height: 30px;
    }
    .qrcode {
      width: 60px;
      height: 60px;
      float: right;
      margin-top: 0;
    }
    .leea {
      width: 69px;
      height: 58px;
      float: left;
      margin-top: 0;
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
      <img src="../head.jpg" alt="Header Image">
    </div>
    <img src="../leea.png" class="leea" alt="Leea">
    <img src="../code.png" class="qrcode" alt="Qr Code">
    <div class="text-center">
      <h3 class="certificate-title"><strong>CRANE HEALTH CHECK CERTIFICATE <br />
      FOR OFFSHORE PEDESTAL CRANES AND FLOATING CRANES</strong></h3>
    </div>
    
    <div class="table-responsive">
      <table class="content-table">
        <tbody>
          <tr>
            <td class="text-center section-title" style="text-align: center; width: 25%;">Date of Inspection:</td>
            <td style="text-align: center;"> <strong> ' . htmlspecialchars($formatted_inspection_date) . ' </strong> </td>
            <td class="text-center section-title" style="text-align: center; width: 25%;">Report No.:</td>
            <td style="text-align: center;"> <strong> ' . htmlspecialchars($row['report_no']) . ' </strong></td>
          </tr>
          <tr>
            <td class="text-center section-title" style="text-align: center; width: 25%;">Certificate No.:</td>
            <td style="text-align: center;"><strong> ' . htmlspecialchars($row['certificate_no']) . ' </strong></td>
            <td class="text-center section-title" style="text-align: center; width: 25%;">JRN:</td>
            <td style="text-align: center;"><strong> ' . htmlspecialchars($row['jrn']) . ' </strong></td>
          </tr>
        </tbody>
      </table>
    </div>
    
    <div class="table-responsive">
      <table class="content-table">
        <tbody>
          <tr>
            <th colspan="2" class="text-center section-title" style="text-align: center;">A. GENERAL INFORMATION</th>
          </tr>
          <tr>
            <td style="width: 40%;">Vessel Name & Location</td>
            <td style="width: 60%;">
            <strong>
            ' . htmlspecialchars($row['vessel_name_location']) . '
            </strong>
            </td>
          </tr>
          <tr>
            <td>Company Name</td>
            <td> <strong> ' . htmlspecialchars($row['customer_name']) . ' </strong> </td>
          </tr>
          <tr>
            <td>Manufacturer</td>
            <td><strong> ' . htmlspecialchars($row['manufacturer']) . ' </strong></td>
          </tr>
          <tr>
            <td>Type of Crane</td>
            <td> <strong> ' . htmlspecialchars($row['crane_type']) . ' </strong> </td>
          </tr>
          <tr>
            <td>Model</td>
            <td><strong> ' . htmlspecialchars($row['model']) . ' </strong></td>
          </tr>
          <tr>
            <td>Manufacturing Year</td>
            <td><strong> ' . htmlspecialchars($row['manufacturing_year']) . '  </strong></td>
          </tr>
          <tr>
            <td>Asset Number</td>
            <td><strong>  ' . htmlspecialchars($row['asset_number']) . '   </strong></td>
          </tr>
          <tr>
            <td>Serial Number</td>
            <td><strong> ' . htmlspecialchars($row['serial_number']) . '  </strong></td>
          </tr>
          <tr>
            <td>Capacity (SWL)</td>
            <td><strong> ' . htmlspecialchars($row['capacity_swl']) . '  </strong></td>
          </tr>
          <tr>
            <td>Date of Previous Test of Crane</td>
            <td><strong> ' . htmlspecialchars($row['previous_test_date']) . ' </strong></td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="table-responsive">
      <table class="content-table">
        <thead>
          <tr>
            <th colspan="4" class="text-center section-title" style="text-align: center;">B. GENERAL INFORMATION</th>
          </tr>
          <tr class="section-title">
            <th class="text-center">Operation</th>
            <th class="text-center">Comments</th>
            <th class="text-center">Safety Devices</th>
            <th class="text-center">Comments</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td> Crane Structure Condition: </td>
            <td><strong> ' . htmlspecialchars($row['crane_structure_condition']) . ' </strong> </td>
            <td> Auto Moment Limiter (LMI): </td>
            <td><strong> ' . htmlspecialchars($row['auto_moment_limiter']) . ' </strong> </td>
          </tr>
          <tr>
            <td> Swinging / Slewing Function: </td>
            <td><strong> ' . htmlspecialchars($row['swinging_slewing_function']) . '</strong> </td>
            <td> Anti-Two-Block (A2B) Function: </td>
            <td><strong> ' . htmlspecialchars($row['anti_two_block']) . '</strong> </td>
          </tr>
          <tr>
            <td> Hydraulic & Pneumatic System: </td>
            <td><strong> ' . htmlspecialchars($row['hydraulic_pneumatic_system']) . '</strong> </td>
            <td> Winch Drum Lock / Pawls: </td>
            <td><strong> ' . htmlspecialchars($row['winch_drum_lock_pawls']) . '</strong> </td>
          </tr>
          <tr>
            <td> Wire Ropes Condition: </td>
            <td><strong>' . htmlspecialchars($row['wire_ropes_condition']) . '</strong> </td>
            <td> Hook Block Assembly: </td>
            <td><strong>  ' . htmlspecialchars($row['hook_block_assembly']) . '</strong> </td>
          </tr>
          <tr>
            <td> Boom Lifting, Extending & Retracting: </td>
            <td><strong> ' . htmlspecialchars($row['boom_lifting_extending_retracting']) . '</strong> </td>
            <td> Boom Angle Indicator: </td>
            <td><strong> ' . htmlspecialchars($row['boom_angle_indicator']) . '</strong> </td>
          </tr>
          <tr>
            <td> Emergency Boom Lowering: </td>
            <td><strong> ' . htmlspecialchars($row['emergency_boom_lowering']) . '</strong> </td>
            <td> Emergency Shutdown: </td>
            <td><strong> ' . htmlspecialchars($row['emergency_shutdown']) . '  </strong> </td>
          </tr>
        </tbody>
      </table>
    </div>
    <p style="text-align: center;">
    <strong>
        We hereby certify that the above Crane has been duly Inspected (Health Check) as per the Manufacturer’s Recommendation or based on ASME B30.3 – 2019, B30.4 – 2020, B30.5 – 2021, B30.7 – 2021, B30.8 – 2020, B30.9 – 2021, B30.10 – 2019, B30.22 – 2016, API SPECS 2C – 2020, and API RP 2D – 2014.
    </strong></p>
    <p style="text-align: center;"><strong>
      The latest date by which the next inspection shall be carried out: <br>
      <span style="color: red;">
      <strong>(' . htmlspecialchars($formatted_latest_inspection_date) . ')</strong>
      </span>
    </strong></p>
    
    
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
	<strong>' . htmlspecialchars($row['inspector']) . '</strong>
	</td>	
	<td style="text-align: center;">
	<strong>
	LEEA No: ' . $leea_number . '
	</strong>
	</td>	
	<td style="text-align: center;" rowspan="2">
	<img src="../qcpass.png" class="sign" alt="Default Signature">
	</td>
	<td style="text-align: center;">
	<strong>
	Technical Manager
    </strong>
	</td>	
	<td rowspan="2"  style="text-align: center;">
	<img src="../seal.jpeg" class="sign" alt="Default Signature">
	</td>
	</tr>
	
	<tr>
	<td colspan="2"  style="text-align: center;">
	  ' . $inspector_signature_html . '
	</td>
	
	<td  style="text-align: center;">
	' . $technical_manager_signature_html . '
	</td>
	</tr>
    </tbody>
  </table>
</div>    
    
    

    <p class=""><strong>
      This certificate contained herein is the good-faith opinion of CIMS – KGEIT as to the Visual Condition of the crane inspected. This Certificate in no way represents any guarantee, expressed, or implied as to the classification, fitness for use of merchantability of the crane, and in no event shall CIMS – KGEIT be held liable for any damage as result of its use.
    </strong></p>
    <div class="table-responsive keep-together">
      <table class="content-table">
        <tbody>
          <tr>
            <td class="text-center section-title" style="text-align: center;">
              <strong><i>OVERSEAS FULL MEMBER OF LIFTING EQUIPMENT ENGINEERS ASSOCIATION (LEEA, UNITED KINGDOM) CERT. # 662</i></strong>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <p class="newpara" style="text-align: center; color: red;">
    FRM.0602.1.3(Rev.01)
    </p>
    
    <div class="footer">
      <img src="../foot.jpg" alt="Footer Image">
    </div>
  </div>
</body>
</html>
';

$mpdf = new \Mpdf\Mpdf([
    'margin_left' => 5,
    'margin_right' => 5,
    'margin_top' => 5,
    'margin_bottom' => 5,
    'margin_header' => 5,
    'margin_footer' => 5
]);

// Add watermark text
// $mpdf->SetWatermarkText('DRAFT');
// $mpdf->showWatermarkText = true;

// Add watermark image
$mpdf->SetWatermarkImage('../logo.png', 0.3, '', [70, 100]);
$mpdf->showWatermarkImage = true;

$mpdf->WriteHTML($html);
// $mpdf->Output('health-check.pdf', \Mpdf\Output\Destination::DOWNLOAD);

// Build dynamic filename
$filename = "Crane_Health_Check_Certificate_" . $project_no . ".pdf";

// Output as download
$mpdf->Output($filename, \Mpdf\Output\Destination::DOWNLOAD);