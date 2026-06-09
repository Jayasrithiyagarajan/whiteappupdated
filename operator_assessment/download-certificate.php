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
    .certificate {
        width: 100%;
        height: 100%;
        position: relative;
        background: #fff;
    }
    .header-img img {
        width: 100%;
    }
    .content {
        padding: 35px 50px;
    }
    .title {
        text-align: center;
        font-weight: 800;
        font-size: 22pt;
        margin: 25px 0;
        letter-spacing: 0.5px;
        text-decoration: underline;
    }
    .cert-no-container {
        text-align: center;
        margin-bottom: 20px;
    }
    .cert-no {
        display: inline-block;
        border: 2px solid #000;
        padding: 6px 14px;
        font-weight: 700;
        font-size: 14pt;
    }
    .main-content {
        width: 100%;
        margin-top: 15px;
    }
    .details {
        font-size: 15pt;
        line-height: 2;
        vertical-align: top;
    }
    .details strong {
        font-weight: 700;
    }
    .photo-box {
        border: 3px solid #000;
        padding: 5px;
        text-align: right;
        vertical-align: top;
        width: 160px;
    }
    .photo-box img {
        width: 150px;
        height: 190px;
        object-fit: cover;
    }
    .program {
        text-align: center;
        margin-top: 40px;
        font-size: 16pt;
    }
    .program strong {
        display: block;
        margin-top: 8px;
        font-size: 18pt;
    }
    .dates {
        margin-top: 25px;
        font-size: 15pt;
        line-height: 1.6;
    }
    .signature {
        margin-top: 50px;
        font-size: 15pt;
    }
    .stamp {
        position: absolute;
        right: 220px;
        bottom: 260px;
    }
    .stamp img {
        width: 80px;
    }
    .footer-img {
        position: absolute;
        bottom: 0;
        width: 100%;
    }
    .footer-img img {
        width: 100%;
    }
</style>
</head>
<body>
<div class="certificate">
    <div class="header-img">
        <img src="../document/headnew1.png">
    </div>

    <div class="content">
        <div class="title">
            CERTIFICATE OF COMPETENCE
        </div>

        <div class="cert-no-container">
            <div class="cert-no">
                Certificate No.: ' . htmlspecialchars($cert_no) . '
            </div>
        </div>

        <table class="main-content">
            <tr>
                <td class="details">
                    <strong>Name:</strong> ' . htmlspecialchars($name) . '<br>
                    <strong>Iqama / ID No:</strong> ' . htmlspecialchars($iqama) . '<br>
                    <strong>Company:</strong> ' . htmlspecialchars($company) . '<br>
                    <strong>Operating Location:</strong> ' . htmlspecialchars($location) . '
                </td>
                <td class="photo-box">
                    <img src="' . $operator_photo . '">
                </td>
            </tr>
        </table>

        <div class="program">
            For the successful completion of the following Training Program:
            <strong>' . htmlspecialchars($program) . '</strong>
        </div>

        <div class="dates">
            Completion Date: <strong>' . htmlspecialchars($completion_date) . '</strong><br>
            Recommended Renewal Date: <strong>' . htmlspecialchars($renewal_date) . '</strong>
        </div>

        <div class="signature">
            Trainer / Instructor:<br>';
if ($assessment['inspector_signature']) {
    $html .= '<img src="../' . htmlspecialchars($assessment['inspector_signature']) . '" style="height: 60px;"><br>';
}
$html .= '<strong>' . htmlspecialchars($instructor) . '</strong>
        </div>
    </div>

    <div class="stamp">
        <img src="../document/seal.jpeg">
    </div>

    <div class="footer-img">
        <img src="../document/footnew1.png">
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