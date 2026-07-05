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
$completion_date = date('d F Y', strtotime($assessment['date_of_assessment'] ?? $assessment['date']));
$renewal_date = ($assessment['date_of_expiry'] ? date('d F Y', strtotime($assessment['date_of_expiry'])) : 'N/A');
$instructor = $assessment['inspector_name'];

// Compile equipment details as a clean bulleted list for the description block
$eq_bullets_html = '';
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
            $eq_str .= ' (SWL: ' . trim($eq['capacity']) . ')';
        } else {
            $eq_str = 'SWL: ' . trim($eq['capacity']);
        }
    }
    if (!empty($eq_str)) {
        $eq_bullets_html .= '<li style="margin-bottom: 3px;">' . htmlspecialchars($eq_str) . '</li>';
    }
}
if (empty($eq_bullets_html)) {
    $eq_bullets_html = '<li style="margin-bottom: 3px;">' . htmlspecialchars($program) . '</li>';
}

$html = '
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
    @import url(\'https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap\');
    @page {
        margin: 0;
        padding: 0;
    }
    body {
        margin: 0;
        padding: 0;
        font-family: \'Poppins\', Arial, sans-serif;
        color: #1E293B;
        background-color: #FFFFFF;
    }
    .certificate-wrapper {
        position: absolute;
        top: 10mm;
        left: 10mm;
        width: 190mm;
        height: 277mm;
        border: 1px solid #C5A059;
        padding: 4px;
        box-sizing: border-box;
    }
    .certificate-middle {
        height: 265mm;
        border: 3px solid #0B2240;
        padding: 4px;
        box-sizing: border-box;
    }
    .certificate-inner {
        position: relative;
        height: 253mm;
        border: 1px solid #C5A059;
        padding: 12mm 15mm;
        box-sizing: border-box;
        background-image: url(\'../document/logo.png\');
        background-repeat: no-repeat;
        background-position: center center;
        background-image-opacity: 0.03;
        background-image-resize: 4;
    }
    .bullet-list {
        margin: 0;
        padding-left: 20px;
        font-size: 9pt;
        color: #64748B;
        line-height: 1.8;
    }
</style>
</head>
<body>
<div class="certificate-wrapper">
    <div class="certificate-middle">
        <div class="certificate-inner">
            
            <!-- Header Section (Logo, Centered Title, Web) -->
            <div style="text-align: center; margin-bottom: 2mm;">
                <img src="../document/logo.png" style="width: 75px; height: auto; display: block; margin: 0 auto;">
                <div style="font-size: 8pt; font-weight: 700; color: #C5A059; letter-spacing: 3px; text-transform: uppercase; margin-top: 2mm;">Crane Inspection & Marine Services</div>
                <div style="font-size: 6.5pt; color: #94A3B8; font-weight: 600; letter-spacing: 1.5px; text-transform: uppercase; margin-top: 0.5mm;">WWW.CIMS.COM.SA</div>
            </div>

            <!-- Certificate Title Block -->
            <div style="text-align: center; margin-bottom: 5mm; margin-top: 3mm;">
                <div style="font-size: 20pt; font-weight: 700; color: #0B2240; letter-spacing: 0.5px; text-transform: uppercase; line-height: 1.1;">Certificate of Training</div>
                <div style="font-size: 8pt; font-weight: 600; color: #64748B; letter-spacing: 1.5px; text-transform: uppercase; margin-top: 1mm;">& Competency Assessment</div>
                <div style="width: 70px; height: 1.5px; background-color: #C5A059; margin: 3.5mm auto 0 auto;"></div>
            </div>

            <div style="border-bottom: 1px solid #E2E8F0; margin-bottom: 5mm;"></div>

            <!-- Candidate Information Section -->
            <div style="background-color: #FAF9F6; border: 1px solid #EAE6DF; border-left: 4px solid #C5A059; padding: 6mm 10mm; text-align: center; border-radius: 4px; margin-bottom: 6mm;">
                <div style="font-size: 8pt; font-weight: 600; color: #64748B; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 4px;">This is to certify that</div>
                <div style="font-size: 20pt; font-weight: 700; color: #0B2240; margin-bottom: 4px; letter-spacing: 0.5px;">' . htmlspecialchars($name) . '</div>
                <div style="font-size: 8pt; font-weight: 600; color: #64748B; letter-spacing: 1.5px; text-transform: uppercase; margin: 4px 0;">employed by</div>
                <div style="font-size: 13pt; font-weight: 700; color: #C5A059; letter-spacing: 0.5px;">' . htmlspecialchars($company) . '</div>
            </div>

            <!-- Side-by-Side Cards (QR Code Left, Photo Right) -->
            <table style="width: 100%; border: none; border-collapse: collapse; margin-bottom: 6mm;">
                <tr>
                    <!-- QR Code Card -->
                    <td style="width: 50%; vertical-align: top; border: none; padding: 0; text-align: left;">
                        <img src="../document/code.png" style="width: 76px; height: 76px; display: block; margin-bottom: 2px;">
                        <div style="font-size: 6.5pt; color: #94A3B8; font-weight: 500; letter-spacing: 0.5px; text-transform: uppercase; padding-left: 2px;">Scan to Verify</div>
                    </td>
                    
                    <!-- Candidate Photo Card -->
                    <td style="width: 50%; vertical-align: top; border: none; padding: 0; text-align: right;">
                        <img src="' . $operator_photo . '" style="width: 80px; height: 90px; object-fit: cover; border-radius: 3px; display: inline-block; border: 1px solid #EAE6DF;">
                    </td>
                </tr>
            </table>

            <!-- Certificate Description Block -->
            <div style="margin-bottom: 6mm; padding: 0 2mm;">
                <div style="font-size: 9pt; font-weight: 500; color: #64748B; margin-bottom: 6px; letter-spacing: 0.3px; line-height: 1.6;">
                    Successfully completed the Training and Competency Assessment for:
                </div>
                <ul class="bullet-list" style="list-style-type: square; margin-bottom: 8px;">
                    ' . $eq_bullets_html . '
                </ul>
                <div style="font-size: 9pt; color: #64748B; margin-top: 8px; font-weight: 400; line-height: 1.6; text-align: justify;">
                    This certificate is awarded in recognition of having demonstrated the required theoretical knowledge and practical proficiency in accordance with the applicable safety standards and company assessment criteria.
                </div>
            </div>

            <!-- Three-Column Information Bar -->
            <table style="width: 100%; border-collapse: collapse; border-radius: 4px; border: 1px solid #EAE6DF; background-color: #FAF9F6; margin-bottom: 8mm;">
                <tr>
                    <td style="width: 33.3%; padding: 4mm 3mm; text-align: center; border-right: 1px solid #EAE6DF; vertical-align: middle;">
                        <div style="font-size: 7.5pt; color: #64748B; text-transform: uppercase; letter-spacing: 0.8px; font-weight: 600; margin-bottom: 2px;">Passport / ID Number</div>
                        <div style="font-size: 10.5pt; font-weight: 700; color: #0B2240;">' . htmlspecialchars($iqama) . '</div>
                    </td>
                    <td style="width: 33.4%; padding: 4mm 3mm; text-align: center; border-right: 1px solid #EAE6DF; vertical-align: middle;">
                        <div style="font-size: 7.5pt; color: #64748B; text-transform: uppercase; letter-spacing: 0.8px; font-weight: 600; margin-bottom: 2px;">Certificate Number</div>
                        <div style="font-size: 10.5pt; font-weight: 700; color: #0B2240;">' . htmlspecialchars($cert_no) . '</div>
                    </td>
                    <td style="width: 33.3%; padding: 4mm 3mm; text-align: center; vertical-align: middle;">
                        <div style="font-size: 7.5pt; color: #64748B; text-transform: uppercase; letter-spacing: 0.8px; font-weight: 600; margin-bottom: 2px;">Issued / Expiry Date</div>
                        <div style="font-size: 9.5pt; font-weight: 700; color: #0B2240;">' . htmlspecialchars($completion_date) . ' <span style="font-weight: 400; color: #64748B;">/</span> ' . htmlspecialchars($renewal_date) . '</div>
                    </td>
                </tr>
            </table>

            <!-- Signatures Section -->
            <table style="width: 100%; border: none; border-collapse: collapse; margin-top: 4mm;">
                <tr>
                    <!-- Assessor -->
                    <td style="width: 45%; vertical-align: bottom; border: none; text-align: left; padding-left: 2mm;">
                        <div style="height: 45px; text-align: left; vertical-align: bottom; padding-bottom: 3px;">';
if ($assessment['inspector_signature']) {
    $html .= '<img src="../' . htmlspecialchars($assessment['inspector_signature']) . '" style="height: 40px; object-fit: contain;">';
} else {
    $html .= '<div style="height: 40px;"></div>';
}
$html .= '              </div>
                        <div style="border-top: 1.5px solid #0B2240; width: 180px; margin-bottom: 4px;"></div>
                        <div style="color: #0B2240; font-weight: 700; font-size: 9.5pt; line-height: 1.2;">' . htmlspecialchars($instructor) . '</div>
                        <div style="color: #C5A059; font-weight: 600; font-size: 8pt; text-transform: uppercase; letter-spacing: 0.5px;">Assessor / Inspector</div>
                    </td>
                    
                    <!-- Space -->
                    <td style="width: 10%; border: none; text-align: center; vertical-align: bottom;">
                    </td>
                    
                    <!-- Operations Manager -->
                    <td style="width: 45%; vertical-align: bottom; border: none; text-align: right; padding-right: 2mm;">
                        <div style="height: 45px; text-align: right; vertical-align: bottom; padding-bottom: 3px;">';
$manager_sig_path = '../document/uploads/Khaled A. Alghamdi.jpg';
if (file_exists($manager_sig_path)) {
    $html .= '<img src="' . $manager_sig_path . '" style="height: 40px; object-fit: contain;">';
} else {
    $html .= '<div style="height: 40px;"></div>';
}
$html .= '              </div>
                        <div style="border-top: 1.5px solid #0B2240; width: 180px; display: inline-block; margin-bottom: 4px;"></div>
                        <div style="color: #0B2240; font-weight: 700; font-size: 9.5pt; line-height: 1.2;">Eng. Khalid A. Alghamdi</div>
                        <div style="color: #C5A059; font-weight: 600; font-size: 8pt; text-transform: uppercase; letter-spacing: 0.5px;">Operations Manager</div>
                    </td>
                </tr>
            </table>

            <!-- Footer Image -->
            <div style="position: absolute; bottom: -12mm; left: -15mm; width: 189mm; text-align: center; line-height: 0;">
                <img src="../document/foot.jpg" style="width: 100%; height: auto; display: block;">
            </div>

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
$mpdf->setAutoPageBreak(false);
$mpdf->WriteHTML($html);

if (ob_get_length()) ob_end_clean();

$mpdf->Output('Operator_Certificate_' . $cert_no . '.pdf', \Mpdf\Output\Destination::DOWNLOAD);
?>