<?php
require_once('../../vendor/autoload.php');
include_once('../../file/config.php'); // Include your database configuration file

// Check if project_no is provided in the query string
if (isset($_GET['project_no'])) {
    $project_no = $_GET['project_no'];

    // Fetch data from the database
    $sql = "SELECT * FROM liquid_penetrant_inspection WHERE project_no = ?";
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

$inspector_name = $row['inspector']; // Inspector name from eddy_current_inspection

// Set default LEEA number
$leea_number = "12345";

// Fetch LEEA number from inspectors table
$leea_query = "SELECT leea_number FROM inspectors WHERE inspector_name = ? LIMIT 1";
$leea_stmt = $conn->prepare($leea_query);

if ($leea_stmt) {
    $leea_stmt->bind_param("s", $inspector_name);
    $leea_stmt->execute();
    $leea_result = $leea_stmt->get_result();

    if ($leea_result && $leea_result->num_rows > 0) {
        $leea_row = $leea_result->fetch_assoc();
        $leea_number = htmlspecialchars($leea_row['leea_number']);
    }
    $leea_stmt->close();
}

// Assign fetched data to variables
$certificate_no = $row['certificate_no'];
$report_no = $row['report_no'];
$customer_name = $row['customer_name'];
$inspector = $row['inspector'];
$technical_manager = $row['technical_manager'];
$quality_controller = $row['quality_controller'];
$location = $row['location'];
$inspection_date_raw = $row['inspection_date']; // Original date from DB
$inspection_date = date("d-m-Y", strtotime($row['inspection_date']));
$next_inspection_date_raw = $row['next_inspection_date']; // Original date from DB
$next_inspection_date = date("d-m-Y", strtotime($row['next_inspection_date']));
$material = $row['material'];
$surface_temperature = $row['surface_temperature'];
$technique_procedure = $row['technique_procedure'];
$brand = $row['brand'];
$penetrant = $row['penetrant'];
$penetrant_apply = $row['penetrant_apply'];
$dwell_time = $row['dwell_time'];
$cleaner = $row['cleaner'];
$remove_apply = $row['remove_apply'];
$developer = $row['developer'];
$developer_apply = $row['developer_apply'];
$developing_time = $row['developing_time'];
$description = $row['description'];
$item_checked = $row['item_checked'];
$results = $row['results'];
$condition_new = $row['condition_new'];


$image1 = !empty($row['image_1']) ? $row['image_1'] : ($project_no . '_image1.jpg');
$image2 = !empty($row['image_2']) ? $row['image_2'] : ($project_no . '_image2.jpg');
$image3 = !empty($row['image_3']) ? $row['image_3'] : ($project_no . '_image3.jpg');


// Convert inspector and technical manager names to lowercase
$inspector_name = strtolower(str_replace(' ', '_', $row['inspector']));

$technical_manager_name = $row['technical_manager'];
$quality_controller_name = $row['quality_controller'];

// Define the correct paths as per your requirement
$inspector_signature_img = "../../inspector/uploads/$inspector_name/images/signature_image.jpg";
$authenticating_signature_img = "../uploads/$technical_manager_name.jpg";
$quality_controller_img = "../uploads/qc/$quality_controller_name.jpeg";



// Check if the files exist, otherwise show a default image
if (!file_exists($inspector_signature_img)) {
    $inspector_signature_img = "../default-signature.png"; // Set a fallback image if not found
}
if (!file_exists($authenticating_signature_img)) {
    $authenticating_signature_img = "../default-signature.png"; // Set a fallback image if not found
}



// Create an instance of the mPDF class with landscape orientation and minimal margins
$mpdf = new \Mpdf\Mpdf([
    'margin_left' => 5,
    'margin_right' => 5,
    'margin_top' => 5,
    'margin_bottom' => 5,
    'margin_header' => 5,
    'margin_footer' => 5
]);

// HTML content
$html = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liquid Penetrant Inspection Certificate</title>
    <style>
        .certificate-title {
            text-align: center;
            margin: 8px;
        }
        p {
            font-size: 12px;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 10px;
            padding: 10px;
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
            margin-bottom: 7px;            
        }
        th, td {
            padding: 5px;
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
            width: 40px;
            height: 40px;
        }
        .qrcode {
            width: 70px;
            height: 70px;
            float: right;
            margin-top: 0;
        }
        .leea {
            width: 69px;
            height: 58px;
            float: left;
            margin-top: 0;
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
            <h3 class="certificate-title"><strong> LIQUID PENETRANT INSPECTION CERTIFICATE</strong></h3>
        </div>
        <table>
            <tr>
                <td class="text-center section-title" style="width: 25%;">CERTIFICATE NO.</td>
                <td style="width: 25%;"><strong>$certificate_no</strong></td>
                <td class="text-center section-title" style="width: 25%;">REFERENCE NO.</td>
                <td style="width: 25%;"><strong>$report_no</strong></td>
            </tr>
            <tr>
                <td class="section-title">CUSTOMER NAME</td>
                <td colspan="3" ><strong>$customer_name</strong></td>
            </tr>
            <tr>
                <td class="text-center section-title">SITE/LOCATION</td>
                <td style="text-transform: uppercase;" colspan="3"><strong>$location</strong></td>
            </tr>
            <tr>
                <td class="text-center section-title">INSPECTION DATE</td>
                <td><strong>$inspection_date</strong></td>
                <td class="text-center section-title">NEXT INSPECTION DATE</td>
                <td><strong>$next_inspection_date</strong></td>
            </tr>
        </table>

        <table>
            <tr>
                <td class="text-center section-title" style="text-align: center;" colspan="4">TESTING PREPARATION</td>
            </tr>
            <tr>
                <td class="text-center section-title" style="width: 25%;">STANDARD</td>
                <td><strong>ASTM E1417; ASTM E165</strong></td>
                <td class="text-center section-title" style="width: 25%;">MATERIAL</td>
                <td style="text-transform: uppercase;"><strong>$material</strong></td>
            </tr>
            <tr>
                <td class="text-center section-title">SURFACE TEMPERATURE</td>
                <td style="text-transform: uppercase;" colspan="3"><strong>$surface_temperature</strong></td>
            </tr>
        </table>

        <table>
            <tr>
                <td class="text-center section-title" style="text-align: center;" colspan="6">TESTING TOOLS</td>
            </tr>
            <tr>
                <td colspan="2" style="width: 25%;" class="section-title">TECHNIQUE/PROCEDURE</td>
                <td style="text-transform: uppercase;"><strong>$technique_procedure</strong></td>
                <td colspan="2" class="section-title">BRAND</td>
                <td style="text-transform: uppercase;"><strong>$brand</strong></td>
            </tr>
            <tr>
                <td class="text-center section-title">PENETRANT</td>
                <td style="text-transform: uppercase;"><strong>$penetrant</strong></td>
                <td class="text-center section-title">PENETRANT APPLY</td>
                <td style="text-transform: uppercase;"><strong>$penetrant_apply</strong></td>
                <td class="text-center section-title">DWELL TIME</td>
                <td style="text-transform: uppercase;"><strong>$dwell_time</strong></td>
            </tr>
            <tr>
                <td colspan="2" class="section-title">CLEANER</td>
                <td style="text-transform: uppercase;"><strong>$cleaner</strong></td>
                <td colspan="2" class="section-title">REMOVE APPLY</td>
                <td style="text-transform: uppercase;"><strong>$remove_apply</strong></td>
            </tr>
            <tr>
                <td class="section-title">DEVELOPER</td>
                <td style="text-transform: uppercase;"><strong>$developer</strong></td>
                <td class="text-center section-title">DEVELOPER APPLY</td>
                <td style="text-transform: uppercase;"><strong>$developer_apply</strong></td>
                <td class="text-center section-title">DEVELOPING TIME</td>
                <td style="text-transform: uppercase;"><strong>$developing_time</strong></td>
            </tr>
        </table>

        <table>
            <tr>
                <td class="text-center section-title" style="text-align: center;" colspan="4">TESTING RESULT</td>
            </tr>
            <tr>
                <td style="width: 25%;" class="text-center section-title" style="text-align: center;">DESCRIPTION</td>
                <td class="text-center section-title" style="text-align: center;">ITEM CHECKED</td>
                <td class="text-center section-title" style="text-align: center;">RESULTS</td>
                <td class="text-center section-title" style="text-align: center;">CONDITION</td>
            </tr>
            <tr style="height: 100px;">
                <td style="text-transform: uppercase; text-align: center;"><strong>$description</strong></td>
                <td style="text-transform: uppercase; text-align: center;"><strong>$item_checked</strong></td>
                <td style="text-transform: uppercase; text-align: center;"><strong>$results</strong></td>
                <td style="text-transform: uppercase; text-align: center;"><strong>$condition_new</strong></td>
            </tr>
        </table>

        <table style="width: 100%; border-collapse: collapse;">
    <tr style="height: 250px;"> 
        <td style="width: 33.33%; text-align: center; vertical-align: middle;">
            <img src="uploads/$image1" alt="Image 1" style="height: 200px; width: 200px;" />
        </td>

        <td style="width: 33.33%; text-align: center; vertical-align: middle;">
            <img src="uploads/$image2" alt="Image 2" style="height: 200px; width: 200px;" />
        </td>

        <td style="width: 33.33%; text-align: center; vertical-align: middle;">
            <img src="uploads/$image3" alt="Image 3" style="height: 200px; width: 200px;" />
        </td>
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
	<td style="text-align: center; vertical-align: middle;" rowspan="2">
    <img src="../qcpass.png" class="sign" style="display: block; margin: 0 auto;">
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
	  <img src="$inspector_signature_img" class="sign" alt="Inspector Signature">
	</td>
	
	<td  style="text-align: center;">
	<img src="$authenticating_signature_img" class="sign" alt="Authenticating Person Signature">
	</td>
	</tr>
    </tbody>
  </table>
</div>    

        <div class="footer">
            <img src="../foot.jpg" alt="Footer Image">
        </div>
    </div>
</body>
</html>
HTML;

// Add the HTML content to the PDF
$mpdf->WriteHTML($html);

// Add watermark image
// $mpdf->SetWatermarkImage('../logo.png', 0.3, '', [70, 100]);
$mpdf->showWatermarkImage = true;

// Output the PDF to the browser for display
$mpdf->Output('liquid_penetrant_inspection_certificate.pdf', 'I');

$conn->close();
?>