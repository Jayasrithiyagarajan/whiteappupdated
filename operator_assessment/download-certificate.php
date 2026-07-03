<?php
error_reporting(0);
ini_set('display_errors', 0);
ob_start();

require_once('../vendor/autoload.php');
include_once('../inc/function.php');

$assessment_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($assessment_id === 0) {
    ob_end_clean();
    die("Invalid assessment ID");
}

$sql = "SELECT 
            oa.*,
            c.customer_name as client_name,
            nu.username as inspector_name,
            nu.signature_photo as inspector_signature
        FROM operator_assessments oa
        LEFT JOIN customers c ON oa.client_id = c.cus_id
        LEFT JOIN new_users nu ON oa.inspector_id = nu.user_id
        WHERE oa.id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $assessment_id);
$stmt->execute();
$result = $stmt->get_result();
$assessment = $result->fetch_assoc();

if (!$assessment) {
    ob_end_clean();
    die("Assessment not found");
}

$photo_sql = "SELECT file_path FROM operator_documents 
              WHERE assessment_id = ? 
              AND document_type = 'PHOTO' 
              LIMIT 1";
$photo_stmt = $conn->prepare($photo_sql);
$photo_stmt->bind_param("i", $assessment_id);
$photo_stmt->execute();
$photo_result = $photo_stmt->get_result();
$photo = $photo_result->fetch_assoc();
$operator_photo = $photo ? $photo['file_path'] : '../assets/img/avatar/avatar-1.png';

$equipment_sql = "SELECT * FROM operator_equipment 
                  WHERE assessment_id = ? 
                  ORDER BY equipment_number ASC";
$equipment_stmt = $conn->prepare($equipment_sql);
$equipment_stmt->bind_param("i", $assessment_id);
$equipment_stmt->execute();
$equipment_result = $equipment_stmt->get_result();
$equipments = [];
while ($eq = $equipment_result->fetch_assoc()) {
    $equipments[] = $eq;
}

// Map database fields to template
$cert_no = $assessment['assessment_no'];
$name = strtoupper($assessment['operator_name']);
$iqama = $assessment['operator_id_passport'];
$company = strtoupper($assessment['client_name']);
$location = strtoupper($assessment['location']);
$program = strtoupper($assessment['training_program']);
$completion_date = date('M. d, Y', strtotime($assessment['date_of_assessment'] ?? $assessment['date']));
$renewal_date = ($assessment['date_of_expiry'] ? date('M. d, Y', strtotime($assessment['date_of_expiry'])) : 'N/A');
$instructor = $assessment['inspector_name'];

// Compile equipment details for certificate body text
$eq_details = [];
foreach ($equipments as $eq) {
    $parts = [];
    if (!empty($eq['equipment_type'])) {
        $parts[] = trim($eq['equipment_type']);
    }
    if (!empty($eq['manufacturer'])) {
        $parts[] = trim($eq['manufacturer']);
    }
    if (!empty($eq['model'])) {
        $parts[] = trim($eq['model']);
    }
    
    $eq_str = implode(', ', $parts);
    if (!empty($eq['capacity'])) {
        if (!empty($eq_str)) {
            $eq_str .= ': ' . trim($eq['capacity']);
        } else {
            $eq_str = trim($eq['capacity']);
        }
    }
    if (!empty($eq_str)) {
        $eq_details[] = $eq_str;
    }
}
$equipment_text = implode('; ', $eq_details);
if (empty($equipment_text)) {
    $equipment_text = $program;
}

$html = '
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
    @page {
        margin: 0;
        padding: 0;
    }
    body {
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
        color: #1D2939;
    }
    .certificate-wrapper {
        position: absolute;
        top: 8mm;
        left: 8mm;
        width: 194mm;
        height: 280mm;
        border: 1px solid #003366;
        padding: 4px;
        box-sizing: border-box;
    }
    .certificate-middle {
        height: 269mm;
        border: 4px solid #003366;
        padding: 4px;
        box-sizing: border-box;
    }
    .certificate-inner {
        height: 258mm;
        border: 1px solid #003366;
        padding: 30px 25px;
        box-sizing: border-box;
        background-image: url(\'../document/logo.png\');
        background-repeat: no-repeat;
        background-position: center center;
        background-image-opacity: 0.06;
        background-image-resize: 4;
    }
    .rule {
        border-bottom: 0.75pt solid #D0D5DD;
    }
</style>
</head>
<body>
<div class="certificate-wrapper">

    <table style="width: 100%; table-layout: fixed; border: none; border-collapse: collapse;">
        <tr>
            <td style="width: 22%; text-align: left; vertical-align: middle; border: none; padding: 0; overflow: hidden;">
                <img src="../document/logo.png" style="width: 70px; height: auto;">
            </td>
            <td style="width: 56%; text-align: center; vertical-align: middle; border: none; padding: 0; overflow: hidden;">
                <div style="font-size: 16pt; font-weight: bold; color: #0F2A4A; letter-spacing: 0.8px;">CERTIFICATE OF TRAINING</div>
            </td>
            <td style="width: 22%; text-align: right; vertical-align: middle; border: none; padding: 0; overflow: hidden;">
                <span style="font-size: 8pt; color: #667085; font-weight: bold;">www.cims.com.sa</span>
            </td>
        </tr>
    </table>

    <div class="rule" style="margin: 5mm 0 6mm 0;"></div>

    <table style="width: 100%; table-layout: fixed; border: none; border-collapse: collapse;">
        <tr>
            <td style="width: 22%; vertical-align: middle; border: none; padding: 0; text-align: left; overflow: hidden;">
                <img src="../document/code.png" style="width: 78px; height: 78px;">
            </td>
            <td style="width: 56%; text-align: center; vertical-align: middle; border: none; padding: 0 4mm; box-sizing: border-box; overflow: hidden; word-wrap: break-word;">
                <div style="font-size: 10pt; font-weight: bold; color: #667085; letter-spacing: 1.5px; text-transform: uppercase; margin-bottom: 8px;">This certifies that</div>
                <div style="font-size: 18pt; font-weight: bold; color: #0F2A4A; margin-bottom: 8px; word-wrap: break-word;">' . htmlspecialchars($name) . '</div>
                <div style="font-size: 9.5pt; font-weight: bold; color: #667085; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 4px;">of</div>
                <div style="font-size: 13pt; font-weight: bold; color: #1D2939; word-wrap: break-word;">' . htmlspecialchars($company) . '</div>
            </td>
            <td style="width: 22%; vertical-align: middle; text-align: right; border: none; padding: 0; overflow: hidden;">
                <img src="' . $operator_photo . '" style="width: 95px; height: 115px; object-fit: cover; border: 0.75pt solid #D0D5DD;">
            </td>
        </tr>
    </table>

    <div style="text-align: center; font-size: 11pt; line-height: 1.7; color: #344054; margin: 6mm 6mm 6mm 6mm; font-weight: normal;">
        Has successfully achieved the high standards required for assessment in <span style="color: #0F2A4A; font-weight: bold;">' . htmlspecialchars($equipment_text) . '</span> and is awarded this certificate of achievement as evidence of successful completion of the course and associated practical and theoretical assessments.
    </div>

    <div style="text-align: center; color: #667085; font-weight: bold; font-size: 9pt; letter-spacing: 1px; margin: 0 0 6mm 0;">
        CERTIFICATE NO. <span style="color: #0F2A4A;">' . htmlspecialchars($cert_no) . '</span>
    </div>

    <div class="rule" style="margin: 0 0 6mm 0;"></div>

    <table style="width: 100%; table-layout: fixed; border: none; border-collapse: collapse;">
        <tr>
            <td style="width: 50%; vertical-align: top; border: none; padding: 0; text-align: left; padding-bottom: 8mm; overflow: hidden;">
                <div style="color: #667085; font-weight: bold; font-size: 8.5pt; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Passport Number</div>
                <div style="color: #1D2939; font-weight: bold; font-size: 11pt; word-wrap: break-word;">' . htmlspecialchars($iqama) . '</div>
            </td>
            <td style="width: 50%; vertical-align: top; border: none; padding: 0; text-align: right; padding-bottom: 8mm; overflow: hidden;">
                <div style="color: #667085; font-weight: bold; font-size: 8.5pt; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Issued Date</div>
                <div style="color: #1D2939; font-weight: bold; font-size: 11pt; word-wrap: break-word;">' . htmlspecialchars($completion_date) . '</div>
            </td>
        </tr>
        <tr>
            <td style="width: 50%; vertical-align: bottom; border: none; padding: 0; text-align: left; overflow: hidden;">
                <div style="height: 42px; text-align: left; vertical-align: bottom; padding-bottom: 4px;">';
if ($assessment['inspector_signature']) {
    $html .= '<img src="../' . htmlspecialchars($assessment['inspector_signature']) . '" style="height: 40px; object-fit: contain;">';
} else {
    $html .= '<div style="height: 40px;"></div>';
}
$html .= '              </div>';
$html .= '              <div style="color: #1D2939; font-weight: bold; font-size: 10pt; margin-bottom: 2px; word-wrap: break-word;">' . htmlspecialchars($instructor) . '</div>';
$html .= '              <div style="color: #667085; font-weight: bold; font-size: 8.5pt; text-transform: uppercase; letter-spacing: 0.5px;">Assessor</div>';
$html .= '          </td>';
$html .= '
            <td style="width: 50%; vertical-align: bottom; border: none; padding: 0; text-align: right; overflow: hidden;">
                <div style="height: 42px; text-align: right; vertical-align: bottom; padding-bottom: 4px;">';
$manager_sig_path = '../document/uploads/Khaled A. Alghamdi.jpg';
if (file_exists($manager_sig_path)) {
    $html .= '<img src="' . $manager_sig_path . '" style="height: 40px; object-fit: contain;">';
} else {
    $html .= '<div style="height: 40px;"></div>';
}
$html .= '              </div>
                <div style="color: #1D2939; font-weight: bold; font-size: 10pt; margin-bottom: 2px; word-wrap: break-word;">Eng. Khalid A. Alghamdi</div>
                <div style="color: #667085; font-weight: bold; font-size: 8.5pt; text-transform: uppercase; letter-spacing: 0.5px;">Operations Manager</div>
            </td>
        </tr>
    </table>

</div>
</body>
</html>
';

$mpdf = new \Mpdf\Mpdf([
    'format' => [210, 297],
    'margin_left' => 0,
    'margin_right' => 0,
    'margin_top' => 0,
    'margin_bottom' => 0,
    'margin_header' => 0,
    'margin_footer' => 0,
    'img_dpi' => 96,
    'tempDir' => __DIR__ . '/../tmp'
]);

$mpdf->SetDisplayMode('fullpage');
$mpdf->setAutoPageBreak(false);
$mpdf->WriteHTML($html);

if (ob_get_length()) ob_end_clean();

$mpdf->Output('Operator_Certificate_' . $cert_no . '.pdf', \Mpdf\Output\Destination::DOWNLOAD);
?>