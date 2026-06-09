<?php
require_once('../../vendor/autoload.php');
include_once('../../file/config.php'); // include your database connection

// Get the project number from the query parameter
$project_no = $_GET['project_no'] ?? '';

// Fetch all certificates based on the project number
$sql = "SELECT * FROM eddy_current_inspection WHERE project_no = ? ORDER BY certificate_no ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $project_no);
$stmt->execute();
$result = $stmt->get_result();

// If no records are found
if ($result->num_rows == 0) {
    die("No certificates found for the given project number.");
}

// Create an instance of the mPDF class with minimal margins
$mpdf = new \Mpdf\Mpdf([
    'margin_left' => 5,
    'margin_right' => 5,
    'margin_top' => 5,
    'margin_bottom' => 5,
    'margin_header' => 5,
    'margin_footer' => 5
]);

$first = true;

while ($row = $result->fetch_assoc()) {
    if (!$first) {
        $mpdf->AddPage();
    }
    $first = false;

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
    $site_location = strtoupper($row['location']);
    $inspection_date = date("d-m-Y", strtotime($row['inspection_date']));
    $inspector = $row['inspector'];
    $technical_manager = $row['technical_manager'];
    $quality_controller = $row['quality_controller'];
    $next_inspection_date = date("d-m-Y", strtotime($row['next_inspection_date']));
    $inspected_item = strtoupper($row['inspected_item']);
    $type_of_joint = strtoupper($row['type_of_joint']);
    $inspection_method = $row['inspection_method'];
    $other_inspection_method = $row['other_inspection_method'];
    $calibration_details = $row['calibration_details'];
    $gain = strtoupper($row['gain']);
    $probe_frequency = strtoupper($row['probe_frequency']);
    $cable_type = $row['cable_type'];
    $sensor_type = $row['sensor_type'];
    $ref_block_type = $row['ref_block_type'];
    $ref_block_type_mm = $row['ref_block_type_mm'];
    $material = $row['material'];
    $device_maker = strtoupper($row['device_maker']);
    $model = strtoupper($row['model']);
    $serial_no = $row['serial_no'];
    $description_of_inspection = strtoupper($row['description_of_inspection']);
    $inspection_result = $row['inspection_result'];
    $reason = $row['reason'];
    $otherreasoncomment = strtoupper($row['other_reason']);

    // Signatures
    $inspector_name_slug = strtolower(str_replace(' ', '_', $row['inspector']));
    $inspector_signature_img = "../../inspector/uploads/$inspector_name_slug/images/signature_image.jpg";
    $authenticating_signature_img = "../uploads/$technical_manager.jpg";
    $quality_controller_img = "../uploads/qc/$quality_controller.jpeg";

    if (!file_exists($inspector_signature_img)) { $inspector_signature_img = "../default-signature.png"; }
    if (!file_exists($authenticating_signature_img)) { $authenticating_signature_img = "../default-signature.png"; }

    // Icons
    $checkedIcon = '<img src="../checkmark.png" width="12" height="12" alt="Checked">';
    $uncheckedIcon = '<img src="../uncheckmark.png" width="13" height="13" alt="Unchecked">';

    $inspection_method_surface = ($inspection_method === 'surface') ? $checkedIcon : $uncheckedIcon;
    $inspection_method_weld = ($inspection_method === 'weld') ? $checkedIcon : $uncheckedIcon;
    $inspection_method_coatingthickness = ($inspection_method === 'coatingthickness') ? $checkedIcon : $uncheckedIcon;
    $inspection_method_other = ($inspection_method === 'other') ? $checkedIcon : $uncheckedIcon;

    $cable_type_bnc = ($cable_type === 'bnc') ? $checkedIcon : $uncheckedIcon;
    $cable_type_lemo = ($cable_type === 'lemo') ? $checkedIcon : $uncheckedIcon;

    $sensor_type_absoluteprobe = ($sensor_type === 'absoluteprobe') ? $checkedIcon : $uncheckedIcon;
    $sensor_type_coil = ($sensor_type === 'coil') ? $checkedIcon : $uncheckedIcon;

    $ref_block_type_notchblock = ($ref_block_type === 'notchblock') ? $checkedIcon : $uncheckedIcon;
    $ref_block_type_notchdepth = ($ref_block_type === 'notchdepth') ? $checkedIcon : $uncheckedIcon;

    $ref_block_type_mm_values = explode(',', $ref_block_type_mm);
    $ref_block_type_5mm = in_array('5mm', $ref_block_type_mm_values) ? $checkedIcon : $uncheckedIcon;
    $ref_block_type_10mm = in_array('10mm', $ref_block_type_mm_values) ? $checkedIcon : $uncheckedIcon;
    $ref_block_type_20mm = in_array('20mm', $ref_block_type_mm_values) ? $checkedIcon : $uncheckedIcon;

    $material_ferromagnetic = ($material === 'ferromagnetic') ? $checkedIcon : $uncheckedIcon;
    $material_nonferromagnetic = ($material === 'nonferromagnetic') ? $checkedIcon : $uncheckedIcon;
    $material_mtl = ($material === 'mtl') ? $checkedIcon : $uncheckedIcon;

    $inspection_result_noSurfaceIndication = ($inspection_result === 'noSurfaceIndication') ? $checkedIcon : $uncheckedIcon;
    $inspection_result_notAcceptable = ($inspection_result === 'notAcceptable') ? $checkedIcon : $uncheckedIcon;

    $reason_crack = ($reason === 'crack') ? $checkedIcon : $uncheckedIcon;
    $reason_wear = ($reason === 'wear') ? $checkedIcon : $uncheckedIcon;
    $reason_other = ($reason === 'other') ? $checkedIcon : $uncheckedIcon;

    $image1 = $row['image_1'];
    $image2 = $row['image_2'];
    $image3 = $row['image_3'];

    // Dynamic Image Table Generation
    $images_to_show = [];
    if (!empty($image1)) $images_to_show[] = $image1;
    if (!empty($image2)) $images_to_show[] = $image2;
    if (!empty($image3)) $images_to_show[] = $image3;

    $image_count = count($images_to_show);
    $image_table_html = '';

    if ($image_count > 0) {
        $td_width = ($image_count == 1) ? '100%' : (($image_count == 2) ? '50%' : '33.33%');
        $image_table_html = '<table style="width: 100%; border-collapse: collapse;"><tr style="height: 250px;">';
        foreach ($images_to_show as $img) {
            $image_table_html .= '<td style="width: ' . $td_width . '; text-align: center; vertical-align: middle;">';
            $image_table_html .= '<img src="uploads/' . $img . '" alt="Image" style="height: 200px; width: 200px;" />';
            $image_table_html .= '</td>';
        }
        $image_table_html .= '</tr></table>';
    }

    // HTML content
    $html = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        .certificate-title { text-align: center; margin: 4px; }
        p { font-size: 10px; }
        body { font-family: Arial, sans-serif; margin: 5px; padding: 8px; line-height: 1.4; }
        .container { max-width: 800px; margin: auto; padding: 10px; }
        h1, h3 { text-align: center; font-size: 12px; margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 4px; }
        th, td { padding: 4px; border: 1px solid #000; text-align: left; font-size: 10px; }
        .section-title { background-color: #bfdaef; font-size: 10px; }
        .header, .footer { text-align: center; }
        .header img, .footer img { max-width: 100%; }
        .sign { height: 60px; max-width: 100px; object-fit: contain; display: block; margin: 0 auto; }
        .leea { width: 69px; height: 58px; float: left; }
        .qrcode { width: 60px; height: 60px; float: right; }
        .checkbox-icon { display: inline-block; width: 12px; height: 12px; vertical-align: middle; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header"><img src="../head.jpg" alt="Header Image"></div>
        <img src="../leea.png" class="leea" alt="Leea">
        <img src="../code.png" class="qrcode" alt="Qr Code">
        <div class="text-center"><h3 class="certificate-title"><strong>EDDY CURRENT INSPECTION CERTIFICATE</strong></h3></div>
        <table>
            <tr><td class="section-title" style="width: 25%;">CERTIFICATE NO.</td><td style="width: 25%;"><strong>$certificate_no</strong></td><td class="section-title" style="width: 25%;">REPORT NO.</td><td style="width: 25%;"><strong>$report_no</strong></td></tr>
            <tr><td class="section-title">CUSTOMER NAME</td><td colspan="3"><strong>$customer_name</strong></td></tr>
            <tr><td class="section-title">SITE/LOCATION</td><td colspan="3"><strong>$site_location</strong></td></tr>
            <tr><td class="section-title">INSPECTION DATE</td><td><strong>$inspection_date</strong></td><td class="section-title">NEXT INSPECTION DATE</td><td><strong>$next_inspection_date</strong></td></tr>
        </table>
        <table>
            <tr><td colspan="2" class="section-title">INSPECTED ITEM</td><td><strong>$inspected_item</strong></td><td class="section-title">SERIAL NO.</td><td colspan="3"><strong>$type_of_joint</strong></td></tr>
            <tr><td colspan="2" class="section-title">SPECIFICATION</td><td colspan="5"><strong>ASME V (ART III); BS EN ISO 17643</strong></td></tr>
            <tr>
                <td colspan="2" class="section-title">INSPECTION METHOD</td>
                <td><span class="checkbox-icon">$inspection_method_surface</span> <strong>Surface</strong></td>
                <td><span class="checkbox-icon">$inspection_method_weld</span> <strong>Weld</strong></td>
                <td><span class="checkbox-icon">$inspection_method_coatingthickness</span> <strong>Coating Thickness</strong></td>
                <td colspan="2"><span class="checkbox-icon">$inspection_method_other</span> <strong>Other</strong> <strong>$other_inspection_method</strong></td>
            </tr>
            <tr><td colspan="2" class="section-title">CALIBRATION DETAILS</td><td><strong>$calibration_details</strong></td><td colspan="2" class="section-title">H GAIN</td><td colspan="2"><strong>$gain</strong></td></tr>
            <tr><td colspan="2" class="section-title">PROBE FREQUENCY</td><td colspan="2"><strong>$probe_frequency</strong></td><td colspan="3"></td></tr>
            <tr>
                <td colspan="2" class="section-title">CABLE TYPE</td>
                <td><span class="checkbox-icon">$cable_type_bnc</span> <strong>BNC</strong></td>
                <td><span class="checkbox-icon">$cable_type_lemo</span> <strong>LEMO</strong></td>
                <td class="section-title">SENSOR TYPE</td>
                <td><span class="checkbox-icon">$sensor_type_absoluteprobe</span> <strong>Absolute Probe</strong></td>
                <td><span class="checkbox-icon">$sensor_type_coil</span> <strong>Coil</strong></td>
            </tr>
            <tr>
                <td colspan="2" class="section-title">REF. BLOCK TYPE</td>
                <td><span class="checkbox-icon">$ref_block_type_notchblock</span> <strong>Notch Block</strong></td>
                <td><span class="checkbox-icon">$ref_block_type_notchdepth</span> <strong>Notch Depth</strong></td>
                <td><span class="checkbox-icon">$ref_block_type_5mm</span> <strong>0.5 mm</strong></td>
                <td><span class="checkbox-icon">$ref_block_type_10mm</span> <strong>1.0 mm</strong></td>
                <td><span class="checkbox-icon">$ref_block_type_20mm</span> <strong>2.0 mm</strong></td>
            </tr>
            <tr>
                <td colspan="2" class="section-title">MATERIAL</td>
                <td colspan="2"><span class="checkbox-icon">$material_ferromagnetic</span> <strong>Ferromagnetic: Carbon Steel</strong></td>
                <td colspan="2"><span class="checkbox-icon">$material_nonferromagnetic</span> <strong>Non-Ferromagnetic</strong></td>
                <td><span class="checkbox-icon">$material_mtl</span> <strong>MTL. THK.</strong></td>
            </tr>
            <tr><td colspan="2" class="section-title">DEVICE MAKER</td><td><strong>$device_maker</strong></td><td class="section-title">MODEL</td><td><strong>$model</strong></td><td class="section-title">SERIAL NO.</td><td><strong>$serial_no</strong></td></tr>
        </table>
        $image_table_html
        <table>
            <tr><td class="section-title" style="width: 25%;" colspan="1">DESCRIPTION OF INSPECTION</td><td colspan="4"><strong>$description_of_inspection</strong></td></tr>
            <tr><td class="section-title" colspan="5">INSPECTION RESULTS</td></tr>
            <tr>
                <td colspan="2" rowspan="2"><span class="checkbox-icon">$inspection_result_noSurfaceIndication</span> <strong>No surface indication found at the time of inspection</strong></td>
                <td class="section-title" colspan="3"><span class="checkbox-icon">$inspection_result_notAcceptable</span> <strong>NOT ACCEPTABLE DUE TO:</strong></td>
            </tr>
            <tr>
                <td><span class="checkbox-icon">$reason_crack</span> <strong>Crack</strong></td>
                <td><span class="checkbox-icon">$reason_wear</span> <strong>Wear</strong></td>
                <td><span class="checkbox-icon">$reason_other</span> <strong>Other: $otherreasoncomment</strong></td>
            </tr>
        </table>
        <div class="table-responsive keep-together" style="margin-top: 4px;">
            <table>
                <thead>
                    <tr><th class="section-title text-center" colspan="2" style="text-align: center;">INSPECTED BY</th><th class="section-title text-center" style="text-align: center;">QUALITY REVIEWED</th><th class="section-title text-center" style="text-align: center;">APPROVED BY</th><th class="section-title text-center" style="text-align: center;">SEAL</th></tr>
                </thead>
                <tbody>
                    <tr><td style="text-align: center;"><strong>$inspector</strong></td><td style="text-align: center;"><strong>LEEA No: $leea_number</strong></td><td style="text-align: center; vertical-align: middle;" rowspan="2"><img src="../qcpass.png" class="sign"></td><td style="text-align: center;"><strong>ASNT NDT LEVEL 3</strong></td><td rowspan="2" style="text-align: center;"><img src="../seal.jpeg" class="sign"></td></tr>
                    <tr style="height: 200px;"><td colspan="2" style="text-align: center;"><img src="$inspector_signature_img" class="sign"></td><td style="text-align: center;"><img src="$authenticating_signature_img" class="sign"></td></tr>
                </tbody>
            </table>
        </div>
        <div class="footer"><img src="../foot.jpg" alt="Footer Image"></div>
    </div>
</body>
</html>
HTML;
    $mpdf->WriteHTML($html);
}

// Output the PDF to the browser for download
$filename = "eddy_current_inspection_project_" . $project_no . ".pdf";
$mpdf->Output($filename, 'D');

$stmt->close();
$conn->close();
?>