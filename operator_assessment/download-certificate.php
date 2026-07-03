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
    }
    .certificate-wrapper {
        position: absolute;
        top: 8mm;
        left: 8mm;
        width: 194mm;
        height: 281mm;
        border: 1px solid #003366;
        padding: 1.5mm;
        box-sizing: border-box;
    }
    .certificate-middle {
        height: 100%;
        border: 4px solid #003366;
        padding: 1.5mm;
        box-sizing: border-box;
    }
    .certificate-inner {
        height: 100%;
        border: 1px solid #003366;
        padding: 10mm 9mm;
        box-sizing: border-box;
        background-image: url(\'../document/logo.png\');
        background-repeat: no-repeat;
        background-position: center center;
        background-image-opacity: 0.06;
        background-image-resize: 4;
    }
</style>
</head>
<body>
<div class="certificate-wrapper">
    <div class="certificate-middle">
        <div class="certificate-inner">
            
            <!-- Top Header Section (CIMS Logo left, Title Center) -->
            <table style="width: 100%; border: none; border-collapse: collapse; margin-bottom: 25px; font-family: Arial, sans-serif;">
                <tr>
                    <td style="width: 15%; text-align: left; vertical-align: middle; border: none; padding: 0;">
                        <img src="../document/logo.png" style="width: 90px; height: auto;"><br>
                        <span style="font-size: 6.5pt; color: #003366; font-weight: bold; display: block; margin-top: 3px; letter-spacing: 0.3px; padding-left: 2px;">www.cims.com.sa</span>
                    </td>
                    <td style="width: 70%; text-align: center; vertical-align: middle; border: none; padding: 0;">
                        <div style="font-size: 21pt; font-weight: bold; color: #009EDB; letter-spacing: 0.5px; white-space: nowrap;">CERTIFICATE OF TRAINING</div>
                    </td>
                    <td style="width: 15%; vertical-align: middle; border: none; padding: 0;"></td>
                </tr>
            </table>

            <!-- Main Dynamic Content Row (QR Code, Certified Info, Photo) -->
            <table style="width: 100%; border: none; border-collapse: collapse; margin-bottom: 20px;">
                <tr>
                    <td style="width: 25%; vertical-align: top; border: none; padding: 0; text-align: left; padding-top: 15px;">
                        <img src="../document/code.png" style="width: 90px; height: 90px; border: 1px solid #ccc; padding: 2px;">
                    </td>
                    <td style="width: 50%; text-align: center; vertical-align: top; border: none; padding-top: 15px;">
                        <div style="font-size: 11pt; font-weight: bold; color: #00B0F0; margin-bottom: 18px; letter-spacing: 0.5px; font-family: Arial, sans-serif;">THIS IS TO CERTIFY THAT</div>
                        <div style="font-size: 18pt; font-weight: bold; color: #FF0000; margin-bottom: 18px; font-family: Arial, sans-serif;">' . htmlspecialchars($name) . '</div>
                        <div style="font-size: 11pt; font-weight: bold; color: #00B0F0; margin-bottom: 18px; font-family: Arial, sans-serif;">OF</div>
                        <div style="font-size: 14pt; font-weight: bold; color: #000000; font-family: Arial, sans-serif;">' . htmlspecialchars($company) . '</div>
                    </td>
                    <td style="width: 25%; vertical-align: top; text-align: right; border: none; padding: 0; padding-top: 15px;">
                        <img src="' . $operator_photo . '" style="width: 110px; height: 130px; object-fit: cover; border: 1px solid #ccc; padding: 2px;">
                    </td>
                </tr>
            </table>

            <!-- Course Description Sentence -->
            <div style="text-align: center; font-size: 12pt; line-height: 1.8; color: #000000; margin: 40px 10px; font-family: Arial, sans-serif; font-weight: bold;">
                Has successfully achieved the high standards required for assessment in <span style="color: #FF0000; font-weight: bold;">' . htmlspecialchars($equipment_text) . '</span> and is awarded this certificate of achievement as evidence of successful completion of the course and associated practical and theoretical assessments.
            </div>

            <!-- Certificate No -->
            <div style="text-align: center; color: #FF0000; font-weight: bold; font-size: 10pt; margin: 40px 0; letter-spacing: 0.5px; font-family: Arial, sans-serif;">
                CERTIFICATE NO. ' . htmlspecialchars($cert_no) . '
            </div>

            <!-- Footer Details & Signatures -->
            <table style="width: 100%; border: none; border-collapse: collapse; margin-top: 50px; font-family: Arial, sans-serif;">
                <!-- Row 1: Passport & Issued Date -->
                <tr>
                    <td style="width: 50%; vertical-align: top; border: none; padding-left: 10px; text-align: left; padding-bottom: 45px;">
                        <div style="color: #00B0F0; font-weight: bold; font-size: 9.5pt; margin-bottom: 5px;">Passport Number</div>
                        <div style="color: #000000; font-weight: bold; font-size: 11pt;">' . htmlspecialchars($iqama) . '</div>
                    </td>
                    <td style="width: 50%; vertical-align: top; border: none; padding-right: 10px; text-align: right; padding-bottom: 45px;">
                        <div style="color: #00B0F0; font-weight: bold; font-size: 9.5pt; margin-bottom: 5px;">Issued Date</div>
                        <div style="color: #000000; font-weight: bold; font-size: 11pt;">' . htmlspecialchars($completion_date) . '</div>
                    </td>
                </tr>
                <!-- Row 2: Signatures & Names -->
                <tr>
                    <td style="width: 50%; vertical-align: bottom; border: none; padding-left: 10px; text-align: left;">
                        <div style="height: 50px; text-align: left; vertical-align: bottom; padding-bottom: 5px;">';
if ($assessment['inspector_signature']) {
    $html .= '<img src="../' . htmlspecialchars($assessment['inspector_signature']) . '" style="height: 45px; object-fit: contain;">';
} else {
    $html .= '<div style="height: 45px;"></div>';
}
$html .= '              </div>';
$html .= '              <div style="color: #000000; font-weight: bold; font-size: 11pt; margin-bottom: 3px;">' . htmlspecialchars($instructor) . '</div>';
$html .= '              <div style="color: #00B0F0; font-weight: bold; font-size: 9.5pt;">Assessor</div>';
$html .= '          </td>';
$html .= '          
                    <td style="width: 50%; vertical-align: bottom; border: none; padding-right: 10px; text-align: right;">
                        <div style="height: 50px; text-align: right; vertical-align: bottom; padding-bottom: 5px;">';
$manager_sig_path = '../document/uploads/Khaled A. Alghamdi.jpg';
if (file_exists($manager_sig_path)) {
    $html .= '<img src="' . $manager_sig_path . '" style="height: 45px; object-fit: contain;">';
} else {
    $html .= '<div style="height: 45px;"></div>';
}
$html .= '              </div>
                        <div style="color: #000000; font-weight: bold; font-size: 11pt; margin-bottom: 3px;">Eng. Khalid A. Alghamdi</div>
                        <div style="color: #00B0F0; font-weight: bold; font-size: 9.5pt;">Operations Manager</div>
                    </td>
                </tr>
            </table>

        </div>
    </div>
</div>
</body>
</html>
';

$mpdf = new \Mpdf\Mpdf([
    'format' => 'A4',
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
$mpdf->WriteHTML($html);

if (ob_get_length()) ob_end_clean();

$mpdf->Output('Operator_Certificate_' . $cert_no . '.pdf', \Mpdf\Output\Destination::DOWNLOAD);
?>