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

$inspection_date = date("d-m-Y", strtotime($lmi['inspection_date']));
$next_inspection = date("d-m-Y", strtotime($lmi['next_inspection_date']));

$inspector_signature_img = "../../inspector/uploads/$inspector_name/images/signature_image.jpg";
$authenticating_signature_img = "../uploads/$technical_manager_name.jpg";

/* ================= LEEA NUMBER ================= */
$leea_number = "N/A";
$leea_query = "SELECT leea_number FROM inspectors WHERE inspector_name = ? LIMIT 1";
$leea_stmt = $conn->prepare($leea_query);
$leea_stmt->bind_param("s", $lmi['inspector']);
$leea_stmt->execute();
$res = $leea_stmt->get_result();
if ($res->num_rows > 0) {
    $leea_number = $res->fetch_assoc()['leea_number'];
}
$leea_stmt->close();

/* ================= HTML ================= */
$html = <<<HTML
<!DOCTYPE html>
<html>
<head>
<style>
body {
    font-family: Arial, sans-serif;
    margin: 5px;
    padding: 8px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 4px;
}

th, td {
    border: 1px solid #000;
    padding: 4px;
    font-size: 9px;
    vertical-align: middle;
}

/* LABEL & VALUE WIDTH CONTROL */
.label {
    width: 18%;
    background-color: #bfdaef;
    font-weight: bold;
}

.value {
    width: 32%;
}

.label-small {
    width: 12%;
    background-color: #bfdaef;
    font-weight: bold;
}

.value-wide {
    width: 21%;
}

.section-title {
    background-color: #bfdaef;
    font-weight: bold;
    text-align: center;
}

.header img {
    width: 100%;
    height: 60px;
    object-fit: cover;
}

.footer img {
    width: 100%;
    height: 45px;
    object-fit: contain;
}

.sign {
    height: 55px;
    max-width: 100px;
    object-fit: contain;
    display: block;
    margin: auto;
}

.leea {
    width: 65px;
    float: left;
}

.qrcode {
    width: 60px;
    float: right;
}

.section-heading {
    font-size: 10px;
    font-weight: bold;
    text-align: center;
    text-transform: uppercase;
    color: #1f1f1f;
    border-bottom: 1px solid #000;
    padding-bottom: 3px;
    margin: 6px 0 3px 0;
}

</style>
</head>

<body>
<div class="container">

<div class="header">
    <img src="../head1.jpg">
</div>

<img src="../leea.png" class="leea">
<img src="../code.png" class="qrcode">

<h3 style="text-align:center;">CERTIFICATE OF CRANE LMI CALIBRATION</h3>

<table>
<tr>
    <td class="label">CERTIFICATE NO.</td>
    <td class="value">{$lmi['certificate_no']}</td>
    <td class="label">REPORT NO.</td>
    <td class="value">{$lmi['report_no']}</td>
</tr>
<tr>
    <td class="label">CUSTOMER NAME</td>
    <td colspan="3" class="value">{$lmi['customer_name']}</td>
</tr>
<tr>
    <td class="label">LOCATION</td>
    <td colspan="3" class="value">{$lmi['location']}</td>
</tr>
<tr>
    <td class="label">INSPECTION DATE</td>
    <td class="value">$inspection_date</td>
    <td class="label">NEXT INSPECTION</td>
    <td class="value">$next_inspection</td>
</tr>
</table>

<div class="section-heading">
    CRANE DETAILS</div>
<table>
<tr>
    <td class="label">MANUFACTURER</td>
    <td class="value">{$lmi['crane_make']} | {$lmi['crane_id_no']}</td>
    <td class="label">MODEL</td>
    <td class="value">{$lmi['crane_model']}</td>
</tr>
<tr>
    <td class="label">TYPE</td>
    <td class="value">{$lmi['crane_type']}</td>
    <td class="label">CAPACITY</td>
    <td class="value">{$lmi['crane_capacity']}</td>
</tr>
<tr>
    <td class="label">SERIAL NO.</td>
    <td class="value">{$lmi['crane_serial_no']}</td>
    <td class="label">BOOM LENGTH</td>
    <td class="value">MIN {$lmi['boom_min']} / MAX {$lmi['boom_max']}</td>
</tr>
</table>

<div class="section-heading">LOAD MOMENT INDICATOR DETAILS</div>
<table>
<tr>
    <td class="label-small">MAKE</td>
    <td class="value-wide">{$lmi['lmi_make']}</td>
    <td class="label-small">MODEL</td>
    <td class="value-wide">{$lmi['lmi_model_type']}</td>
    <td class="label-small">SERIAL</td>
    <td class="value-wide">{$lmi['lmi_serial']}</td>
</tr>
</table>

<div class="section-heading">
    STANDARD LOAD CELL DETAILS</div>
<table>
<tr>
    <td class="label-small">MAKE</td>
    <td class="value-wide">{$lmi['lc_make']}</td>
    <td class="label-small">MODEL</td>
    <td class="value-wide">{$lmi['lc_model_type']}</td>
    <td class="label-small">SERIAL</td>
    <td class="value-wide">{$lmi['lc_serial']}</td>
</tr>
<tr>
    <td class="label-small">CAPACITY</td>
    <td class="value-wide">{$lmi['lc_capacity']}</td>
    <td class="label-small">ACCURACY</td>
    <td class="value-wide">{$lmi['lc_accuracy']}</td>
    <td class="label-small">CERT NO.</td>
    <td class="value-wide">{$lmi['lc_cert_no']}</td>
</tr>
</table>

<div class="section-heading">SAFETY CUT OFF AND ALARMS</div>
<table>
<tr>
    <td class="label">Anti Two Block</td>
    <td class="value">{$lmi['anti_two_block']}</td>
</tr>
<tr>
    <td class="label">Overload Lockout</td>
    <td class="value">{$lmi['overload_lockout']}</td>
</tr>
</table>

<table>
<tr>
    <th class="section-title">INSPECTED BY</th>
    <th class="section-title">LEEA</th>
    <th class="section-title">QC</th>
    <th class="section-title">APPROVED</th>
    <th class="section-title">SEAL</th>
</tr>
<tr>
    <td style="text-align:center;">{$lmi['inspector']}</td>
    <td style="text-align:center;">LEEA $leea_number</td>
    <td rowspan="2"><img src="../qcpass.png" class="sign"></td>
    <td style="text-align:center;">ASNT LEVEL III</td>
    <td rowspan="2"><img src="../seal.jpeg" class="sign"></td>
</tr>
<tr>
    <td colspan="2"><img src="$inspector_signature_img" class="sign"></td>
    <td><img src="$authenticating_signature_img" class="sign"></td>
</tr>
</table>

<div class="footer">
    <img src="../footer.jpg">
</div>

</div>
</body>
</html>
HTML;

/* ================= MPDF ================= */
$mpdf = new \Mpdf\Mpdf([
    'margin_left' => 3,
    'margin_right' => 3,
    'margin_top' => 5,
    'margin_bottom' => 5
]);

$mpdf->WriteHTML($html);
$mpdf->SetWatermarkImage('../logoold.png', 0.15, '', 'center');
$mpdf->showWatermarkImage = true;

$mpdf->Output("LMI_Certificate_$project_no.pdf", "D");
