<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once('../file/config.php');
require_once('../vendor/autoload.php');

use Mpdf\Mpdf;

// ✅ Step 1: Get project ID
$project_id = isset($_GET['project_id']) ? $_GET['project_id'] : '';
if (empty($project_id)) {
    die("❌ Project ID missing!");
}

// ✅ Step 2: Fetch survey data
$query = "SELECT * FROM customer_survey_report WHERE project_id = ? LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $project_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    die("⚠️ No survey found for this project ID ($project_id).");
}

// ✅ Step 3: Build HTML for PDF
ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Customer Satisfaction Survey - CIMS</title>
<style>
body { font-family: Helvetica, Arial, sans-serif; font-size:12px; margin:0; padding:0; color:#000; }
.document { width:100%; padding:15px; }
table { width:100%; border-collapse: collapse; }
td, th { border:1px solid #0000009c; padding:5px; text-align:center; font-size:12px; }
th { background:#dbe4f0; }
.head6 { font-weight:bold; background-color:#dbe4f0; text-align:left; }
.param-header { text-align:left !important; }
.comments-box { border:1px solid #0000009c; min-height:80px; padding:10px; white-space: pre-wrap; }
.check-symbol { font-size:16px; color:green; }
.footer-table td { font-size:9px; border:1px solid #0000009c; }
.meta-table { margin-bottom:15px; }
.customer-table, .question-table { margin-bottom:15px; }
.signature-table { margin-top:15px; margin-bottom:15px; }
</style>
</head>
<body>
<div class="document">

<!-- Header -->
<table class="meta-table">
<tr>
  <td rowspan="4"><img src="../document/checklist/logo.png" height="100"></td>
  <td colspan="4" style="font-size:17px; font-weight:bold; text-transform:uppercase;">CRANE INSPECTION & MAINTENANCE SERVICES<br>
  <span style="font-size:12px;">A DIVISION OF AL-KHOBAR GATE INTERNATIONAL TRADING EST.</span></td>
  <td rowspan="4"><img src="../document/code.png" height="100"></td>
</tr>
<tr><td colspan="4" style="font-size:16px; font-weight:bold; text-transform:uppercase;">CUSTOMER SATISFACTION SURVEY</td></tr>
</table>

<!-- Customer Info -->
<table class="customer-table">
<tr>
  <td class="head6" style="width: 20%;">CUSTOMER/CLIENT NAME & ADDRESS</td>
  <td colspan="2"><?= htmlspecialchars($data['client_name'] ?? '') ?></td>
  <td class="head6">DATE</td>
  <td><?= htmlspecialchars($data['survey_date'] ?? '') ?></td>
</tr>
<tr>
  <td class="head6">CONTACT PERSON</td>
  <td colspan="2"><?= htmlspecialchars($data['contact_person'] ?? '') ?></td>
  <td class="head6">EMAIL</td>
  <td><?= htmlspecialchars($data['email'] ?? '') ?></td>
</tr>
<tr>
  <td class="head6">YEARS OF BUSINESS</td>
  <td colspan="2"><?= htmlspecialchars($data['years_business'] ?? '') ?></td>
  <td class="head6">TEL. NO</td>
  <td><?= htmlspecialchars($data['telephone'] ?? '') ?></td>
</tr>
<tr>
  <td class="head6">PRESENT STATUS</td>
  <td colspan="2">
    <span class="check-symbol"><?= ($data['status']=='new')?'☑':'☐'; ?></span> NEW CLIENT
  </td>
  <td colspan="2">
    <span class="check-symbol"><?= ($data['status']=='existing')?'☑':'☐'; ?></span> EXISTING CLIENT
  </td>
</tr>
</table>

<!-- Questions -->
<table class="question-table">
<tr>
  <th width="5%">NO</th>
  <th width="55%">QUESTIONS</th>
  <th width="20%">RESPONSE</th>
  <th width="20%">REMARKS</th>
</tr>
<?php
$questions = [
  ['no'=>1, 'q'=>'Inspector’s attention to safety procedures?', 'field'=>'qualification_card', 'remark'=>'qualification_remarks'],
  ['no'=>2, 'q'=>'Was the inspector thorough and effective?', 'field'=>'response_time', 'remark'=>'response_remarks'],
  ['no'=>3, 'q'=>'Did the inspector arrive on time?', 'field'=>'ppe', 'remark'=>'ppe_remarks'],
  ['no'=>4, 'q'=>'Inspector’s professionalism and communication?', 'field'=>'aramco_standards', 'remark'=>'aramco_remarks'],
  ['no'=>5, 'q'=>'Overall satisfaction (coordination, reply, quality, etc.)?', 'field'=>'overall_satisfaction', 'remark'=>'overall_satisfaction_remarks'],
];

foreach ($questions as $q) {
    $yes_checked = ($data[$q['field']] == 'yes') ? '☑' : '☐';
    $no_checked  = ($data[$q['field']] == 'no')  ? '☑' : '☐';
    echo "<tr>
        <td>{$q['no']}</td>
        <td class='param-header'>{$q['q']}</td>
        <td>
            YES: <span class='check-symbol'>$yes_checked</span> &nbsp;&nbsp; 
            NO: <span class='check-symbol'>$no_checked</span>
        </td>
        <td>".htmlspecialchars($data[$q['remark']] ?? '')."</td>
    </tr>";
}
?>
</table>

<!-- Comments -->
<div>
  <b>COMMENTS / REMARKS:</b>
  <div class="comments-box"><?= nl2br(htmlspecialchars($data['comments'] ?? '')); ?></div>
</div>

<!-- Signature -->
<table class="signature-table" style="margin-top:15px;">
<tr>
<th>FEEDBACK BY:</th>
<th>SIGNATURE:</th></tr>
<tr>
<td>
<strong>
<?= htmlspecialchars($data['evaluated_by'] ?? '') ?>
</strong>
</td>
<td>
<?php
// Handle signature display - updated to match your database field
if (!empty($data['signature_filename'])) {
    // Build path to signature file
    $signature_file = __DIR__ . '/uploads/' . $data['signature_filename'];
    
    // Alternative path if above doesn't work
    if (!file_exists($signature_file)) {
        $signature_file = __DIR__ . '/uploads/signatures/' . $data['signature_filename'];
    }
    
    if (file_exists($signature_file)) {
        $signature_file = str_replace('\\', '/', $signature_file);
        echo '<img src="file://' . $signature_file . '" style="width:120px;">';
    } else {
        echo '<small>Signature file not found: ' . $data['signature_filename'] . '</small>';
    }
} else {
    echo '<small>No signature provided</small>';
}
?>
</td>
</tr>
</table>

<!-- Footer -->
<table class="footer-table" style="margin-top:20px; width:100%; border-collapse:collapse; font-family:Helvetica, Arial, sans-serif;">
<tr>
  <td style="background:#f2f2f2; padding:10px; width:100%; border-top:3px solid #1a4f7a; font-size:11px; text-align:center;">
    <span style="font-weight:bold; color:#1a4f7a;">For any enquiry or concern, please contact:</span><br>
    TEL.: 013 814 6861 - 013 814 6862 Ext.110 - Fax: 013 814 6863
    <br><br>
    <span style="font-size:12px; font-weight:bold; color:#1a4f7a; padding:3px 6px; border-radius:4px;">
Email: office@cims.com.sa - info@cims.com.sa
      

    </span>
  </td>
</tr>
</table>

</div>
</body>
</html>

<?php
$html = ob_get_clean();
$mpdf = new Mpdf([
    'format'=>'A4',
    'orientation'=>'P',
    'margin_left' => 10,
    'margin_right' => 10,
    'margin_top' => 10,
    'margin_bottom' => 10,
    'margin_header' => 5,
    'margin_footer' => 5
]);

$mpdf->SetTitle("Customer Satisfaction Survey - " . $project_id);
$mpdf->SetAuthor("CIMS");
$mpdf->WriteHTML($html);
$filename = "Customer_Survey_{$project_id}.pdf";
$mpdf->Output($filename, 'D');
exit;
?>