<?php
require_once('../../vendor/autoload.php');
include_once('../../file/config.php');

if (!isset($_GET['project_no'])) {
    die("Project number not provided");
}

$project_no = $_GET['project_no'];

/* ================= FETCH LMI DATA ================= */
$sql = "SELECT * FROM lmi_certificates WHERE project_no = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $project_no);
$stmt->execute();
$lmi = $stmt->get_result()->fetch_assoc();

if (!$lmi) {
    die("No LMI Certificate found for this project");
}

$inspector_name = strtolower(str_replace(' ', '_', $lmi['inspector']));
$technical_manager_name = strtolower(str_replace(' ', '_', $lmi['technical_manager']));

/* ================= DATE FORMAT ================= */
$inspection_date = date("d-m-Y", strtotime($lmi['inspection_date']));
$next_inspection = date("d-m-Y", strtotime($lmi['next_inspection_date']));

$inspector_signature_img = "../../inspector/uploads/$inspector_name/images/signature_image.jpg";
// Define the path to the technical manager's signature
$authenticating_signature_img = "../uploads/$technical_manager_name.jpg";

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


/* ================= MPDF ================= */
// $mpdf = new \Mpdf\Mpdf([
//     'orientation' => 'P',
//     'margin_left' => 12,
//     'margin_right' => 12,
//     'margin_top' => 15,
//     'margin_bottom' => 15
// ]);

/* ================= HTML ================= */
$html = <<<HTML
<!DOCTYPE html>
<html>
<head>
<style>
 .certificate-title {
            text-align: center;
            margin: 1px;
        }
        p {
            font-size: 8px;
            margin: 0 2px;
        }

        td {
    font-weight: bold;
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
            padding: 8px;
        }
        h1, h3 {
            text-align: center;
            font-size: 12px;
            margin: 3px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 3px;
        }
        th, td {
            padding: 4px;
            border: 1px solid #000;
            text-align: left;
            font-size: 10px;
        }
        .section-title {
            background-color: #bfdaef;
            font-size: 10px;
        }
        .header, .footer {
            text-align: center;
        }
        
        .footer img {
    max-width: 100%;
    height: 45px;   /* reduce height */
    object-fit: contain;
}


        .header img {
    width: 100%;          /* full width */
    height: 60px;         /* reduce header height (adjust as needed) */
    object-fit: cover;    /* fills width without distortion */
    display: block;
}

        .sign {
            height: 55px;
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
        .checkbox-icon {
            display: inline-block;
            width: 12px;
            height: 12px;
            vertical-align: middle;
        }
        .new{
            background-color: #4CAF50;            
        }
</style>
</head>
<body>

<div class="container">

        <div class="header">
            <img src="../head1.jpg" alt="Header Image">
        </div>        
        <img src="../leea.png" class="leea" alt="Leea">  
        <img src="../code.png" class="qrcode" alt="Qr Code">
        <div class="text-center">
            <h3 class="certificate-title"><strong>CERTIFICATE OF CRANE LMI CALIBRATION</strong></h3>
        </div>

<table>
<tr>
<td class="section-title" style="width: 25%;"><b>CERTIFICATE NO.</b></td>
<td style="width: 25%;">{$lmi['certificate_no']}</td>
<td style="width: 25%;" class="section-title"><b>REPORT NO.</b></td>
<td style="width: 25%;">{$lmi['report_no']}</td>
</tr>
<tr>
<td class="section-title"><b>CUSTOMER NAME</b></td>
<td colspan="3">{$lmi['customer_name']}</td>
</tr>
<tr>
<td class="section-title"><b>LOCATION</b></td>
<td colspan="3">{$lmi['location']}</td>
</tr>
<tr>
<td class="section-title"><b>INSPECTION DATE</b></td>
<td>$inspection_date</td>
<td class="section-title"><b>NEXT INSPECTION DATE</b></td>
<td>$next_inspection</td>
</tr>
</table>

<p style="text-align: center; font-size: 10px; margin: 1px 0;">
    <b>CRANE DETAILS</b>
</p>
<table>     
<tr>
<td class="section-title" style="width: 25%;"><b>MANUFACTURER</b></td>
<td style="width: 25%;">{$lmi['crane_make']}</td>
<td class="section-title" style="width: 25%;">ID NO</td>
<td style="width: 25%;">  {$lmi['crane_id_no']}</td>
<td style="width: 25%;" class="section-title"><b>MODEL</b></td>
<td style="width: 25%;">{$lmi['crane_model']}</td>
</tr>
<tr>
<td class="section-title"><b>TYPE</b></td>
<td colspan="2">{$lmi['crane_type']}</td>
<td class="section-title"><b>CAPACITY</b></td>
<td colspan="2">{$lmi['crane_capacity']}</td>
</tr>
<tr>
<td class="section-title"><b>SERIAL NO.</b></td>
<td>{$lmi['crane_serial_no']}</td>
<td class="section-title"><b>BOOM LENGTH</b></td>
<td>MIN: {$lmi['boom_min']} m | MAX: {$lmi['boom_max']} m</td>
</tr>
</table>
<p style="text-align: center; font-size: 10px; margin: 1px 0;">
    <b>LOAD MOMENT INDICATOR DETAILS</b>
</p>
<table>

<tr>
<td class="section-title"><b>MAKE</b></td>
<td>{$lmi['lmi_make']}</td>
<td class="section-title"><b>MODEL</b></td>
<td>{$lmi['lmi_model_type']}</td>
<td class="section-title"><b> TYPE</b></td>
<td>{$lmi['lmi_model_type']}</td>
<td class="section-title"><b>SERIAL NO.</b></td>
<td>{$lmi['lmi_serial']}</td>

</tr>


</table>

<p style="text-align: center; font-size: 10px; margin: 1px 0;">
    <b>STANDARD LOAD CELL DETAILS (USED FOR CALIBRATION)</b>
</p>

<table>

<tr><td class="section-title"><b>MAKE</b></td><td>{$lmi['lc_make']}</td><td class="section-title"><b>MODEL | TYPE</b></td><td>{$lmi['lc_model_type']}</td> <td class="section-title"><b>SERIAL NO.</b></td> <td>{$lmi['lc_serial']}</td></tr>
<tr><td class="section-title"><b>CAPACITY</b></td><td>{$lmi['lc_capacity']}</td> <td class="section-title"><b>ACCURACY</b></td><td>{$lmi['lc_accuracy']}</td><td class="section-title"><b>CERTIFICATE NO.</b></td><td>{$lmi['lc_cert_no']}</td></tr>

</table>

<p style="text-align: center; font-size: 10px; margin: 1px 0;">
    <b>CALIBRATION TABLE</b>
</p>
<table>
<tr class="center">
    <th style="width: 25%; text-align: center;" class="section-title">
    BOOM LENGTH
</th>
<th style="width: 25%; text-align: center;" class="section-title">ACTUAL</th>
<th style="width: 25%; text-align: center;" class="section-title">LMI READING</th>
<th style="width: 25%; text-align: center;" class="section-title">REMARKS</th>
</tr>
<tr>
<td style="text-align: center;">Min</td>
<td style="text-align: center;">{$lmi['boom_len_min_actual']}</td>
<td style="text-align: center;">{$lmi['boom_len_min_lmi']}</td>
<td style="text-align: center;">{$lmi['boom_len_min_remark']}</td></tr>
<tr>
    <td style="text-align: center;">Medium</td>
    <td style="text-align: center;">{$lmi['boom_len_mid_actual']}</td>
    <td style="text-align: center;">{$lmi['boom_len_mid_lmi']}</td>
    <td style="text-align: center;">{$lmi['boom_len_mid_remark']}</td></tr>
<tr>
    <td style="text-align: center;">Max</td>
    <td style="text-align: center;">{$lmi['boom_len_max_actual']}</td>
    <td style="text-align: center;">{$lmi['boom_len_max_lmi']}</td>
    <td style="text-align: center;">{$lmi['boom_len_max_remark']}</td></tr>
</table>


<table>
<tr class="center"><th style="width: 25%;" class="section-title">MAIN BOOM ANGLE</th><th style="width: 25%;" class="section-title">ACTUAL</th><th style="width: 25%;" class="section-title">LMI READING</th><th style="width: 25%;" class="section-title">REMARKS</th></tr>
<tr><td>Min</td><td>{$lmi['angle_min_actual']}</td><td>{$lmi['angle_min_lmi']}</td><td>{$lmi['angle_min_remark']}</td></tr>
<tr><td>Medium</td><td>{$lmi['angle_mid_actual']}</td><td>{$lmi['angle_mid_lmi']}</td><td>{$lmi['angle_mid_remark']}</td></tr>
<tr><td>Max</td><td>{$lmi['angle_max_actual']}</td><td>{$lmi['angle_max_lmi']}</td><td>{$lmi['angle_max_remark']}</td></tr>
</table>


<table>
<tr class="center"><th style="width: 25%;" colspan="2" class="section-title">RADIUS LOAD COMPARISON</th><th style="width: 25%;" class="section-title">AS PER LOAD CHART</th><th style="width: 25%;" class="section-title">LMI READING</th><th style="width: 25%;" class="section-title">REMARKS</th></tr>
<tr><td rowspan="2">Main</td>
<td>3 Mtr</td>
<td>{$lmi['radius_main_chart']}</td>
<td>{$lmi['radius_main_lmi']}</td>
<td>{$lmi['radius_main_remark']}</td></tr>
<tr>
    <td>24 Mtr</td>
    <td>{$lmi['radius_24_chart']}</td>
    <td>{$lmi['radius_24_lmi']}</td><td>{$lmi['radius_24_remark']}</td></tr>
<tr><td>Aux</td><td>{$lmi['radius_aux_chart']}</td><td>3 - 36 Mtr<td>{$lmi['radius_aux_lmi']}</td><td>{$lmi['radius_aux_remark']}</td></tr>
</table>

<p style="text-align: center; font-size: 10px; margin: 1px 0;">
    <b>LOAD CELL CALIBRATION</b>
</p>
<table>

<tr class="center">
    <th class="section-title" style="text-align: center;">ACTUAL LOAD</th>
    <th class="section-title" style="text-align: center;">READING IN STANDARD</th>
    <th class="section-title" style="text-align: center;">READING IN LMI</th>
    <th class="section-title" style="text-align: center;">REMARKS</th></tr>
<tr>
<td style="text-align: center;">{$lmi['load_actual']}</td>
<td style="text-align: center;">{$lmi['load_standard']}</td>
<td style="text-align: center;">{$lmi['load_lmi']}</td>
<td style="text-align: center;">{$lmi['load_remark']}</td>
</tr>
</table>
<p style="text-align: center; text-decoration: underline; font-size: 10px; margin: 1px 0;">
    <b>SAFETY CUT OFF AND ALARMS</b>
</p>

<table>
<tr><td><b>Anti-two Block Condition:</b></td> <td> {$lmi['anti_two_block']}</td></tr>
<tr><td><b>Over Load & Lockout:</b></td> <td> {$lmi['overload_lockout']}</td></tr>
</table>

<p style="text-align: center; color: red;"><b>This Certificate will void if any major repair occurred.</b></p>





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
	<strong>{$lmi['inspector']}</strong>
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
	
	<tr style="height: 120px;">
	<td colspan="2"  style="text-align: center;">
	  <img src="$inspector_signature_img" class="sign" alt="Inspector Signature">
	</td>
	<td  style="text-align: center;">
	    <img src="$inspector_signature_img" class="sign" alt="Technical Manager Signature">
	</td>
	</tr>
    </tbody>
  </table>
</div>    


<p style="text-align: center; color: red;"><b>FRM.0712.1 (Rev.00)</b></p>


 <div class="footer">
            <img src="../footer.jpg" alt="Footer Image">
        </div>

</div>

</body>
</html>
HTML;

$mpdf = new \Mpdf\Mpdf([
    'margin_left' => 3,
    'margin_right' => 3,
    'margin_top' => 2,
    'margin_bottom' => 2,
    'margin_header' => 2,
    'margin_footer' => 3
]);

$mpdf->WriteHTML($html);
$mpdf->SetWatermarkImage('../logoold.png', 0.15, '', 'center');
$mpdf->showWatermarkImage = true;

$filename = "LMI_Certificate_" . $project_no . ".pdf";
$mpdf->Output($filename, 'I');
