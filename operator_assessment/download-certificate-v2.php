<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('pcre.backtrack_limit', '5000000');
ob_start();

require_once('../vendor/autoload.php');
include_once('../file/config.php');

$assessment_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($assessment_id === 0) { ob_end_clean(); die("Invalid assessment ID"); }

$sql = "SELECT oa.*, c.customer_name as client_name, c.profile_photo as client_logo,
               nu.username as inspector_name, nu.signature_photo as inspector_signature
        FROM operator_assessments oa
        LEFT JOIN customers c  ON oa.client_id   = c.cus_id
        LEFT JOIN new_users nu ON oa.inspector_id = nu.user_id
        WHERE oa.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $assessment_id);
$stmt->execute();
$assessment = $stmt->get_result()->fetch_assoc();
if (!$assessment) { ob_end_clean(); die("Assessment not found"); }

$photo_stmt = $conn->prepare("SELECT file_path FROM operator_documents WHERE assessment_id=? AND document_type='PHOTO' LIMIT 1");
$photo_stmt->bind_param("i", $assessment_id);
$photo_stmt->execute();
$photo = $photo_stmt->get_result()->fetch_assoc();

$eq_stmt = $conn->prepare("SELECT * FROM operator_equipment WHERE assessment_id=? ORDER BY equipment_number ASC");
$eq_stmt->bind_param("i", $assessment_id);
$eq_stmt->execute();
$equipments = $eq_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

/* ---- Mapped Variables ---- */
$cert_no         = $assessment['assessment_no'];
$name            = ucwords(strtolower($assessment['operator_name']));
$iqama           = $assessment['operator_id_passport'];
$company         = strtoupper($assessment['client_name']);
$program         = ucwords(strtolower($assessment['training_program']));
$completion_date = date('d F Y', strtotime($assessment['date_of_assessment'] ?? $assessment['date']));
$renewal_date    = $assessment['date_of_expiry'] ? date('d F Y', strtotime($assessment['date_of_expiry'])) : 'N/A';
$instructor      = ucwords(strtolower($assessment['inspector_name']));

/* ---- Absolute Paths ---- */
function abs_path($rel) { $p = realpath($rel); return $p ? str_replace('\\','/',$p) : ''; }
$bg_path            = abs_path(__DIR__.'/../document/certificate_bg_v2.png');
$logo_path          = abs_path(__DIR__.'/../document/logo.png');
$qr_path            = abs_path(__DIR__.'/../document/code.png');
$partner_badge_path = abs_path(__DIR__.'/../document/trusted_partner_badge.png');
$gold_seal_path     = abs_path(__DIR__.'/../document/gold_seal.png');

$client_logo = $logo_path;
if (!empty($assessment['client_logo'])) {
    $r = abs_path(__DIR__.'/../'.$assessment['client_logo']) ?: abs_path(__DIR__.'/'.$assessment['client_logo']);
    if ($r) $client_logo = $r;
}
$photo_path = abs_path(__DIR__.'/../assets/img/avatar/avatar-1.png');
if ($photo && !empty($photo['file_path'])) {
    $r = abs_path(__DIR__.'/../'.$photo['file_path']) ?: abs_path(__DIR__.'/'.$photo['file_path']);
    if ($r) $photo_path = $r;
}
$insp_sig = '';
if (!empty($assessment['inspector_signature'])) {
    $r = abs_path(__DIR__.'/../'.$assessment['inspector_signature']);
    if ($r) $insp_sig = $r;
}
$mgr_sig = abs_path(__DIR__.'/../document/uploads/Khaled A. Alghamdi.jpg');

$seal_path = $gold_seal_path ?: $partner_badge_path;

$designation = 'Crane Operator';
foreach ($equipments as $eq) {
    if (!empty($eq['equipment_type'])) {
        $t = trim($eq['equipment_type']);
        if ($designation === 'Crane Operator') {
            if (stripos($t,'mobile')   !== false) $designation = 'Mobile Crane Operator';
            if (stripos($t,'forklift') !== false) $designation = 'Forklift Operator';
        }
    }
}

/* ---- Equipment List ---- */
$eq_items = [];
foreach ($equipments as $eq) {
    $parts = [];
    if (!empty($eq['equipment_type'])) $parts[] = trim($eq['equipment_type']);
    if (!empty($eq['manufacturer']))   $parts[] = trim($eq['manufacturer']);
    if (!empty($eq['model']))          $parts[] = trim($eq['model']);
    $s = implode(' &middot; ', array_map('htmlspecialchars', $parts));
    if (!empty($eq['capacity'])) $s .= ' <span style="color:#A87F2E;">(SWL '.htmlspecialchars(trim($eq['capacity'])).')</span>';
    if ($s !== '') $eq_items[] = $s;
}
if (empty($eq_items)) $eq_items[] = htmlspecialchars($program);

$eq_chips = '<div style="text-align:center; margin-top:2mm; line-height:2.2;">';
foreach ($eq_items as $k => $it) {
    $eq_chips .= '<span style="display:inline-block; background:#FBF8F0; border:0.6pt solid #C5A059; padding:1.2mm 2.8mm; margin:1mm; font-size:7.5pt; color:#0B2B5C; font-weight:600; border-radius:1.5mm;">';
    $eq_chips .= '<span style="color:#2E7D32; font-weight:700;">&#10003;</span> '.$it.'</span> ';
}
$eq_chips .= '</div>';

/* ---- Validity ---- */
$validity = '2 Years';
if ($assessment['date_of_assessment'] && $assessment['date_of_expiry']) {
    $days = abs(strtotime($assessment['date_of_expiry']) - strtotime($assessment['date_of_assessment'])) / 86400;
    $yrs  = round($days / 365);
    if ($yrs >= 1) $validity = $yrs.' '.($yrs>1?'Years':'Year');
    else {
        $mos = round($days / 30);
        $validity = $mos.' '.($mos>1?'Months':'Month');
    }
}

/* ---- Assessment Standard ---- */
$std = 'CIMS-SOP-GEN-2026';
if (stripos($designation,'crane')   !== false) $std = 'CIMS-SOP-CRN-2026';
if (stripos($designation,'forklift')!== false) $std = 'CIMS-SOP-FLT-2026';

/* ---- Pre-escaped values ---- */
$e_cert   = htmlspecialchars($cert_no);
$e_name   = htmlspecialchars(strtoupper($name));
$e_id     = htmlspecialchars($iqama);
$e_desig  = htmlspecialchars($designation);
$e_std    = htmlspecialchars($std);
$e_prog   = htmlspecialchars($program);
$e_issue  = htmlspecialchars($completion_date);
$e_expiry = htmlspecialchars($renewal_date);
$e_valid  = htmlspecialchars($validity);
$e_instr  = $instructor ? htmlspecialchars($instructor) : '&mdash;';
$verify_url = 'verify.cims-global.org';
$e_generated = date('Y-m-d H:i:s');

$insp_img = $insp_sig ? "<img src='$insp_sig' style='height:28px;object-fit:contain;'>" : "<div style='height:28px;'></div>";
$mgr_img  = $mgr_sig  ? "<img src='$mgr_sig' style='height:28px;object-fit:contain;'>"  : "<div style='height:28px;'></div>";

/* ====================================================================
   OPERATOR CERTIFICATE OF COMPETENCY V2 - A4 Landscape (297 x 210 mm)
   Layout: Structured Master Table Grid (100% stable).
   Colors: Dark Navy #0B2B5C | Gold #C5A059 | Accent Cream #F9F8F5
   ==================================================================== */
$html = <<<HTML
<!DOCTYPE html><html><head><meta charset="UTF-8">
<style>
@page { 
    size: A4-L; 
    margin: 0; 
    background-image: url('$bg_path');
    background-image-resize: 4;
}
body { 
    margin:0; 
    padding: 12mm; 
    font-family:'dejavusanscondensed',sans-serif; 
    color:#23314D;
}
.master-table {
    width: 100%;
    border-collapse: collapse;
}
.hdr-co { font-family:'dejavuserif',serif; font-size:9.5pt; font-weight:700; color:#0B2B5C; line-height:1.2; }
.hdr-sub { font-size:6pt; color:#C5A059; font-weight:700; text-transform:uppercase; letter-spacing:.5px; margin-top:0.5mm; }
.hdr-iso { font-size:5pt; color:#7A8296; margin-top:0.5mm; }
.acc-badge { display:inline-block; font-size:5pt; font-weight:700; color:#fff; background:#2E7D32; padding:0.5mm 1.5mm; border-radius:1mm; margin-right:1mm; }

.ttl-h1 { font-family:'dejavuserif',serif; font-size:22pt; font-weight:700; color:#0B2B5C; text-transform:uppercase; letter-spacing:5px; margin:0; }
.ttl-sub { font-size:6.6pt; font-weight:700; color:#C5A059; letter-spacing:4px; text-transform:uppercase; margin:2mm 0 0; }
.ttl-orn { color:#C5A059; font-size:8pt; letter-spacing:3px; margin:1.5mm 0 0; }

.cno-box { background:#0B2B5C; border:1.2pt solid #C5A059; border-radius:2mm; text-align:center; padding:2.5mm 2mm 3mm; }
.cno-lbl { font-size:4.8pt; font-weight:700; color:#C5A059; text-transform:uppercase; letter-spacing:1.4px; }
.cno-sep { border-top:0.4pt solid #3A5C86; margin:1.8mm 6mm; }
.cno-val { font-family:'dejavuserif',serif; font-size:12pt; font-weight:700; color:#EBD08A; letter-spacing:1.2px; }

.ph-card { background:#fff; border:1pt solid #C5A059; border-radius:2.5mm; padding:1.8mm; text-align:center; }
.ph-img { width:42mm; height:48mm; object-fit:cover; border-radius:1.5mm; }
.ph-badge { background:#0B2B5C; border:0.8pt solid #C5A059; border-radius:2mm; color:#fff; text-align:center; font-size:6.4pt; font-weight:700; letter-spacing:.6px; padding:1.8mm 0; margin-top:2mm; }

.certifies { font-size:7pt; font-weight:700; color:#C5A059; text-transform:uppercase; letter-spacing:3px; }
.name-txt { font-family:'dejavuserif',serif; font-size:28pt; font-weight:700; color:#0B2B5C; letter-spacing:3px; text-transform:uppercase; margin:1.5mm 0; }
.desig-txt { font-family:'dejavuserif',serif; font-size:11.5pt; font-style:italic; color:#C5A059; letter-spacing:1px; }

.desc-txt { font-size:7.4pt; color:#4A5568; line-height:1.75; margin:0; text-align:center; }

/* ---- METADATA GRID ---- */
.meta-tbl { width: 100%; border-collapse: collapse; border: 0.6pt solid #C5A059; margin-top:3mm; background:#fff; }
.meta-tbl td { border: 0.6pt solid #E5DFD3; padding: 2.2mm 2mm; text-align: center; width: 25%; }
.meta-lbl { font-size:5pt; color:#C5A059; font-weight:700; text-transform:uppercase; letter-spacing:1px; margin-bottom:1mm; }
.meta-val { font-family:'dejavuserif',serif; font-size:8.5pt; color:#0B2B5C; font-weight:700; }

/* ---- SIGNATURES ---- */
.sig-tbl { width: 100%; border-collapse: collapse; margin-top: 5mm; }
.sig-box-l { text-align: left; vertical-align: bottom; }
.sig-box-r { text-align: right; vertical-align: bottom; }
.sig-line { border-top: 0.7pt solid #C5A059; width: 48mm; margin-top: 1.5mm; }
.sig-role { font-size:5.6pt; font-weight:700; color:#C5A059; text-transform:uppercase; letter-spacing:1px; margin-top:1.5mm; }
.sig-name { font-family:'dejavuserif',serif; font-size:8.5pt; font-weight:700; color:#0B2B5C; margin-top:1mm; }
.sig-desg { font-size:5.4pt; color:#7A8296; margin-top:0.5mm; }

.qr-card { background:#fff; border:1pt solid #C5A059; border-radius:2.5mm; text-align:center; padding:2.5mm 2mm; }
.qr-ttl { font-family:'dejavuserif',serif; font-size:6pt; font-weight:700; color:#0B2B5C; text-transform:uppercase; letter-spacing:1px; margin-bottom:1.5mm; }
.qr-img { width:28mm; height:28mm; object-fit:contain; }
.qr-scan { font-size:5.4pt; color:#0B2B5C; font-weight:700; letter-spacing:.5px; margin-top:1.5mm; }
.qr-ftr { font-size:4.6pt; color:#6B7280; margin-top:0.5mm; }

.dvb-card { background:#0B2B5C; border:0.8pt solid #C5A059; border-radius:2.5mm; text-align:center; padding:2.2mm 2mm; margin-top:2.5mm; }
.dvb-t1 { color:#EBD08A; font-size:5pt; font-weight:700; text-transform:uppercase; letter-spacing:1.2px; }
.dvb-t2 { color:#fff; font-size:5.2pt; margin-top:1mm; line-height:1.5; }

.footer-bar { text-align: center; border-top: 0.8pt solid #C5A059; padding-top: 2.2mm; margin-top: 6mm; }
.footer-txt { font-size:4.8pt; color:#C5A059; letter-spacing:1.4px; text-transform:uppercase; }
</style>
</head><body>

<table class="master-table">
  <!-- ROW 1: HEADER -->
  <tr>
    <td style="width: 35%; vertical-align: middle;">
      <table style="border:none; border-collapse:collapse; width:100%;">
        <tr>
          <td style="border:none; padding:0; width:16mm; vertical-align:top;">
            <img style="width:15mm; height:15mm; object-fit:contain;" src="$client_logo">
          </td>
          <td style="border:none; padding:0 0 0 3mm; vertical-align:top;">
            <div class="hdr-co">Crane Inspection &amp;<br>Maintenance Services</div>
            <div class="hdr-sub">Intl. Operator Training &amp; Assessment Authority</div>
            <div class="hdr-iso">ISO 9001:2015 &nbsp;&middot;&nbsp; ISO 45001:2018 &nbsp;&middot;&nbsp; Reg. CIMS/2026/006</div>
          </td>
        </tr>
      </table>
      <div style="margin-top:2mm;">
        <span class="acc-badge">ISO Certified</span>
        <span class="acc-badge">Govt. Approved</span>
        <span class="acc-badge">Accredited</span>
      </div>
    </td>
    <td style="width: 40%; text-align: center; vertical-align: middle;">
      <div class="ttl-h1">Operator Certificate</div>
      <div class="ttl-sub">Certificate of Competency</div>
      <div class="ttl-orn">&#8212;&nbsp; &#9670; &nbsp;&#8212;</div>
    </td>
    <td style="width: 25%; vertical-align: middle; text-align: right;">
      <div class="cno-box">
        <div class="cno-lbl">Certificate No.</div>
        <div class="cno-sep"></div>
        <div class="cno-val">$e_cert</div>
      </div>
    </td>
  </tr>

  <!-- ROW 2: DIVIDER LINE -->
  <tr>
    <td colspan="3" style="padding: 3mm 0;">
      <div style="border-top: 0.8pt solid #C5A059; border-bottom: 0.3pt solid #EBD08A; height: 1px;"></div>
    </td>
  </tr>

  <!-- ROW 3: BODY MAIN GRID -->
  <tr>
    <!-- LEFT PANEL (Photo & Seal) -->
    <td style="width: 25%; vertical-align: top; padding-right: 4mm;">
      <div class="ph-card">
        <img class="ph-img" src="$photo_path">
        <div class="ph-badge"><span class="vtick">&#10003;</span>&nbsp; Certified Operator &nbsp;<span class="vtick">&#9733;</span></div>
      </div>
      
      <div style="text-align: center; margin-top: 6mm;">
        <img style="width: 32mm; height: 32mm; object-fit: contain;" src="$seal_path">
      </div>
    </td>

    <!-- CENTER PANEL (Details, Metadata Grid, Equipment, Signatures) -->
    <td style="width: 50%; vertical-align: top; padding: 0 4mm;">
      <!-- Title & Candidate Name -->
      <div style="text-align: center;">
        <div class="certifies">This Certifies That</div>
        <div class="name-txt">$e_name</div>
        <div style="width: 58mm; border-top: 1.2pt solid #C5A059; margin: 1mm auto 2mm;"></div>
        <div class="desig-txt">$e_desig</div>
      </div>

      <!-- Description Statement -->
      <div style="margin-top: 4mm;">
        <p class="desc-txt">Has successfully completed the required operator training, practical assessment, and competency evaluation in accordance with international safety standards.</p>
      </div>

      <!-- Metadata Grid -->
      <table class="meta-tbl">
        <tr>
          <td><div class="meta-lbl">Operator ID</div><div class="meta-val">$e_id</div></td>
          <td><div class="meta-lbl">Standard</div><div class="meta-val">$e_std</div></td>
          <td><div class="meta-lbl">Issued Date</div><div class="meta-val">$e_issue</div></td>
          <td><div class="meta-lbl">Valid Until</div><div class="meta-val">$e_expiry</div></td>
        </tr>
      </table>

      <!-- Equipment block -->
      <div style="margin-top: 4mm; text-align: center;">
        <div style="font-size:5.6pt; color:#0B2B5C; font-weight:700; text-transform:uppercase; letter-spacing:1.6px; margin-bottom:1.5mm;">Equipment / Category Certified</div>
        $eq_chips
      </div>

      <!-- Signatures Grid -->
      <table class="sig-tbl">
        <tr>
          <!-- Inspector signature -->
          <td class="sig-box-l" style="width: 50%;">
            <div style="text-align: left; padding-left: 2mm;">$insp_img</div>
            <div class="sig-line" style="margin-right: auto; margin-left: 0;"></div>
            <div class="sig-role">Authorized Signatory</div>
            <div class="sig-name">$e_instr</div>
            <div class="sig-desg">Assessor / Inspector</div>
          </td>
          <!-- Operations Manager signature -->
          <td class="sig-box-r" style="width: 50%;">
            <div style="text-align: right; padding-right: 2mm;">$mgr_img</div>
            <div class="sig-line" style="margin-left: auto; margin-right: 0;"></div>
            <div class="sig-role">Operations Manager</div>
            <div class="sig-name">Eng. Khalid A. Alghamdi</div>
            <div class="sig-desg">Operations Department</div>
          </td>
        </tr>
      </table>
    </td>

    <!-- RIGHT PANEL (QR Verification & Badge) -->
    <td style="width: 25%; vertical-align: top; padding-left: 4mm;">
      <div class="qr-card">
        <div style="color:#C5A059; font-size:10pt; font-weight:bold; margin-bottom:1mm;">&#10003;</div>
        <div class="qr-ttl">Certificate Verification</div>
        <img class="qr-img" src="$qr_path">
        <div class="qr-scan">Scan to Verify</div>
        <div class="qr-ftr">$verify_url</div>
        <div class="qr-no" style="margin-top:1.5mm;">$e_cert</div>
      </div>

      <div class="dvb-card">
        <div class="dvb-t1">&#10003; Digitally Verified</div>
        <div class="dvb-t2">Authenticated by CIMS Global<br>Secure Credential Registry</div>
      </div>
    </td>
  </tr>

  <!-- ROW 4: FOOTER BAR -->
  <tr>
    <td colspan="3" class="footer-bar">
      <div class="footer-txt">Secure Serial: $e_cert &nbsp;&middot;&nbsp; Verification Portal &nbsp;&middot;&nbsp; Anti-Fraud Security Features (Generated: $e_generated)</div>
    </td>
  </tr>
</table>

</body></html>
HTML;

try {
    $mpdf = new \Mpdf\Mpdf([
        'format'=>'A4-L','margin_left'=>0,'margin_right'=>0,
        'margin_top'=>0,'margin_bottom'=>0,'margin_header'=>0,'margin_footer'=>0,
        'img_dpi'=>96,'tempDir'=>__DIR__.'/../tmp',
        'default_font'=>'dejavusanscondensed',
    ]);
    $mpdf->SetDisplayMode('fullpage');
    $mpdf->setAutoPageBreak(false);

    /* Faint diagonal security watermark (native mPDF) */
    $mpdf->SetWatermarkText('CERTIFIED');
    $mpdf->showWatermarkText   = true;
    $mpdf->watermarkTextAlpha  = 0.035;
    $mpdf->watermark_font      = 'dejavuserif';

    $mpdf->WriteHTML($html);
    if (ob_get_length()) ob_end_clean();
    $mpdf->Output('Operator_Certificate_'.$cert_no.'.pdf', \Mpdf\Output\Destination::DOWNLOAD);
} catch (\Throwable $e) {
    if (ob_get_length()) ob_end_clean();
    http_response_code(500);
    $logMsg = date('Y-m-d H:i:s').' | '.$e->getMessage().' | File:'.$e->getFile().' Line:'.$e->getLine()."\n";
    file_put_contents(__DIR__.'/../tmp/cert_error.log', $logMsg, FILE_APPEND);
    echo '<pre style="color:red;font-size:14px;padding:20px;">';
    echo '<b>Certificate Error:</b> '.htmlspecialchars($e->getMessage())."<br><br>";
    echo htmlspecialchars($e->getTraceAsString());
    echo '</pre>';
}
?>
