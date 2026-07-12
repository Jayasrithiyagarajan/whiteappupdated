<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('pcre.backtrack_limit', '5000000');
ob_start();

// Prevent browser caching of PDF
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

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
$bg_path            = abs_path(__DIR__.'/../document/bg.png');
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

/* ---- Equipment details (div-based to avoid mPDF table bugs) ---- */
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

$eq_table = '<div style="text-align:center; margin-top:2mm; line-height:2;">';
foreach ($eq_items as $k => $it) {
    $eq_table .= '<span style="display:inline-block; background:#FBF8F0; border:0.6pt solid #E4D3A3; padding:1.2mm 2.5mm; margin:1mm; font-size:7pt; color:#23314D; font-weight:600; border-radius:1.5mm;">';
    $eq_table .= '<span style="color:#2E7D32; font-weight:700;">&#10003;</span> '.$it.'</span> ';
}
$eq_table .= '</div>';

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

$insp_img = $insp_sig ? "<img src='$insp_sig' style='height:28px;object-fit:contain;'>" : "<div style='height:28px;'></div>";
$mgr_img  = $mgr_sig  ? "<img src='$mgr_sig' style='height:28px;object-fit:contain;'>"  : "<div style='height:28px;'></div>";
$e_generated = date('Y-m-d H:i:s');

/* ====================================================================
   OPERATOR CERTIFICATE OF COMPETENCY - A4 Landscape (297 x 210 mm)
   Background: wave-corner frame (blue top-left / bottom-right).
   Safe zones: top-right & bottom-left corners are clean.
   Palette: Navy #0F3D73 | Gold #C89B3C | Green #2E7D32
   mPDF render rules: collapse tables, no border-spacing, no radius on TD,
   no spacer cells, no nested position:absolute.
   ==================================================================== */
$html = <<<HTML
<!DOCTYPE html><html><head><meta charset="UTF-8">
<style>
@page { size:A4-L; margin:0; }
body { margin:0; padding:0; font-family:'dejavusanscondensed',sans-serif; color:#23314D; }

/* ---- HEADER ---- */
.hdr-left { position:absolute; left:17mm; top:12mm; width:92mm; }
.hdr-co   { font-family:'dejavuserif',serif; font-size:9pt; font-weight:700; color:#0F3D73; line-height:1.2; }
.hdr-sub  { font-size:6pt; color:#A87F2E; letter-spacing:.5px; margin-top:0.5mm; }
.hdr-iso  { font-size:5pt; color:#7A8296; margin-top:0.5mm; }
.acc-badge{ display:inline-block; font-size:5pt; font-weight:700; color:#fff; background:#2E7D32; 
            padding:0.6mm 1.5mm; border-radius:1mm; margin-right:1mm; }

.ttl-wrap { position:absolute; left:110mm; top:12mm; width:110mm; text-align:center; }
.ttl-h1   { font-family:'dejavuserif',serif; font-size:22pt; font-weight:700; color:#0F3D73;
            text-transform:uppercase; letter-spacing:6px; margin:0; line-height:1.05; }
.ttl-sub  { font-size:6.6pt; font-weight:700; color:#A87F2E; letter-spacing:4px;
            text-transform:uppercase; margin:2.4mm 0 0; }
.ttl-orn  { color:#C89B3C; font-size:8pt; letter-spacing:3px; margin:1.8mm 0 0; }

.cno-box { position:absolute; left:232mm; top:12mm; width:48mm; background:#0F3D73;
           border:1.2pt solid #C89B3C; border-radius:2mm; text-align:center; padding:2.4mm 2mm 3mm; }
.cno-lbl { font-size:4.8pt; font-weight:700; color:#C89B3C; text-transform:uppercase; letter-spacing:1.4px; }
.cno-sep { border-top:0.4pt solid #3A5C86; margin:2mm 9mm; }
.cno-val { font-family:'dejavuserif',serif; font-size:12.5pt; font-weight:700; color:#EBD08A;
           letter-spacing:1.2px; margin-top:0.6mm; }

.hdr-rule-a { position:absolute; left:17mm; top:36mm; width:263mm; border-top:0.8pt solid #C89B3C; }
.hdr-rule-b { position:absolute; left:17mm; top:37mm; width:263mm; border-top:0.3pt solid #EBD08A; }

/* ---- LEFT COLUMN ---- */
.ph-card { position:absolute; left:17mm; top:45mm; width:48mm; background:#fff;
           border:1pt solid #C89B3C; border-radius:2.5mm; padding:1.8mm; }
.ph-img  { width:44.4mm; height:52mm; object-fit:cover; display:block; border-radius:1.5mm; }
.ph-badge{ position:absolute; left:17mm; top:101mm; width:48mm; background:#0F3D73;
           border:0.8pt solid #C89B3C; border-radius:2mm; color:#fff; text-align:center;
           font-size:6.4pt; font-weight:700; letter-spacing:.6px; padding:1.8mm 0; }
.ph-badge .vtick { color:#EBD08A; }
.seal-box { position:absolute; left:17mm; top:135mm; width:48mm; text-align:center; }
.seal-img { width:40mm; height:40mm; object-fit:contain; }

/* ---- CENTER COLUMN ---- */
.certifies { position:absolute; left:72mm; top:45mm; width:153mm; text-align:center;
             font-size:6.6pt; font-weight:700; color:#A87F2E; text-transform:uppercase; letter-spacing:3px; }
.name-row  { position:absolute; left:72mm; top:50mm; width:153mm; text-align:center; }
.name-txt  { font-family:'dejavuserif',serif; font-size:31pt; font-weight:700; color:#0F3D73;
             letter-spacing:4px; text-transform:uppercase; margin:0; line-height:1.05; }
.name-ul   { width:58mm; margin:2.4mm auto 0; border-top:1.2pt solid #C89B3C; }
.desig-txt { position:absolute; left:72mm; top:70mm; width:153mm; text-align:center;
             font-family:'dejavuserif',serif; font-size:11pt; font-style:italic; color:#A87F2E; letter-spacing:1px; }
.div-top   { position:absolute; left:104mm; top:79mm; width:89mm; border-top:0.5pt solid #C89B3C; }
.desc-row  { position:absolute; left:72mm; top:81mm; width:153mm; text-align:center; }
.desc-txt  { font-size:7.4pt; color:#4A5568; line-height:1.7; margin:0; }
.div-bot   { position:absolute; left:104mm; top:98mm; width:89mm; border-top:0.5pt solid #C89B3C; }
.info-strip{ position:absolute; left:72mm; top:101mm; width:153mm; text-align:center;
             font-size:6pt; color:#A87F2E; letter-spacing:.6px; text-transform:uppercase; }
.info-strip b { color:#0F3D73; font-size:6.4pt; }
.info-strip .dot { color:#C89B3C; }

/* ---- EQUIPMENT ---- */
.equip-wrap { position:absolute; left:72mm; top:111mm; width:153mm; text-align:center; }
.equip-hd { font-size:5.6pt; color:#0F3D73; font-weight:700; text-transform:uppercase;
            letter-spacing:1.6px; margin-bottom:2mm; }

/* ---- RIGHT COLUMN ---- */
.qr-card { position:absolute; left:232mm; top:45mm; width:48mm; background:#fff;
           border:1pt solid #C89B3C; border-radius:2.5mm; text-align:center; padding:2.6mm 2mm 3mm; }
.qr-ttl  { font-family:'dejavuserif',serif; font-size:6pt; font-weight:700; color:#0F3D73;
           text-transform:uppercase; letter-spacing:1px; margin:0.4mm 0 1.4mm; }
.qr-img  { width:32mm; height:32mm; object-fit:contain; }
.qr-scan { font-size:5.4pt; color:#0F3D73; font-weight:700; letter-spacing:.6px; margin-top:1.4mm; }
.qr-ftr  { font-size:4.6pt; color:#6B7280; margin-top:0.6mm; line-height:1.5; }
.qr-no   { font-size:7pt; font-weight:700; color:#A87F2E; letter-spacing:.6px; margin-top:1.2mm; }
.dvb-card{ position:absolute; left:232mm; top:118mm; width:48mm; background:#0F3D73;
           border:0.8pt solid #C89B3C; border-radius:2.5mm; text-align:center; padding:2.2mm 2mm; }
.dvb-t1  { color:#EBD08A; font-size:5pt; font-weight:700; text-transform:uppercase; letter-spacing:1.2px; }
.dvb-t2  { color:#fff; font-size:5.2pt; margin-top:1mm; line-height:1.5; }

/* ---- FOOTER ---- */
.sec-line { position:absolute; left:17mm; top:194mm; width:263mm; border-top:0.8pt solid #C89B3C; }
.sec-txt  { position:absolute; left:17mm; top:195.4mm; width:263mm; text-align:center;
            font-size:4.8pt; color:#A87F2E; letter-spacing:1.4px; text-transform:uppercase; }
</style>
</head><body>

<!-- HEADER : LEFT -->
<div class="hdr-left">
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
  <div style="margin-top:1.5mm;">
    <span class="acc-badge">ISO Certified</span>
    <span class="acc-badge">Govt. Approved</span>
    <span class="acc-badge">Accredited</span>
  </div>
</div>

<!-- BACKGROUND ARTWORK (unmodified) -->
<div style="position:absolute;left:0;top:0;width:297mm;height:210mm;z-index:-100;">
  <img src="$bg_path" style="width:297mm;height:210mm;display:block;">
</div>

<!-- TITLE -->
<div class="ttl-wrap">
  <div class="ttl-h1">Operator Certificate</div>
  <div class="ttl-sub">Certificate of Competency</div>
  <div class="ttl-orn">&#8212;&nbsp; &#9670; &nbsp;&#8212;</div>
</div>

<!-- CERT NO -->
<div class="cno-box">
  <div class="cno-lbl">Certificate No.</div>
  <div class="cno-sep"></div>
  <div class="cno-val">$e_cert</div>
</div>

<!-- HEADER RULES -->
<div class="hdr-rule-a"></div>
<div class="hdr-rule-b"></div>

<!-- LEFT COLUMN -->
<!-- PHOTO -->
<div class="ph-card"><img class="ph-img" src="$photo_path"></div>
<div class="ph-badge"><span class="vtick">&#10003;</span>&nbsp; Certified Operator &nbsp;<span class="vtick">&#9733;</span></div>

<!-- SEAL -->
<div class="seal-box"><img class="seal-img" src="$seal_path"></div>

<!-- CENTER COLUMN -->
<!-- CENTER : CERTIFIES / NAME / DESIGNATION -->
<div class="certifies">This Certifies That</div>
<div class="name-row">
  <div class="name-txt">$e_name</div>
  <div class="name-ul"></div>
</div>
<div class="desig-txt">$e_desig</div>

<!-- CENTER : STATEMENT -->
<div class="div-top"></div>
<div class="desc-row">
  <p class="desc-txt">Has successfully completed the required operator training, practical assessment,
  and competency evaluation in accordance with international safety standards.</p>
</div>
<div class="div-bot"></div>

<!-- CENTER : COMPACT DATA STRIP -->
<div class="info-strip">
  Operator ID <b>$e_id</b> &nbsp;<span class="dot">&#9670;</span>&nbsp;
  Standard <b>$e_std</b> &nbsp;<span class="dot">&#9670;</span>&nbsp;
  Issued <b>$e_issue</b> &nbsp;<span class="dot">&#9670;</span>&nbsp;
  Valid Until <b>$e_expiry</b>
</div>

<!-- EQUIPMENT -->
<div class="equip-wrap">
  <div class="equip-hd">Equipment / Category Certified</div>
  $eq_table
</div>

<!-- RIGHT COLUMN -->
<!-- RIGHT : VERIFICATION -->
<div class="qr-card">
  <div style="color:#C89B3C;font-size:9pt;">&#10003;</div>
  <div class="qr-ttl">Certificate Verification</div>
  <img class="qr-img" src="$qr_path">
  <div class="qr-scan">Scan to Verify</div>
  <div class="qr-ftr">$verify_url</div>
  <div class="qr-no">$e_cert</div>
</div>
<div class="dvb-card">
  <div class="dvb-t1">&#10003; Digitally Verified</div>
  <div class="dvb-t2">Authenticated by CIMS Global<br>Secure Credential Registry</div>
</div>

<!-- BOTTOM LEFT : INSPECTOR SIGNATORY -->
<div style="position:absolute; left:72mm; top:156mm; width:72mm; text-align:left;">
  <div style="text-align:left;">$insp_img</div>
  <div style="border-top:0.7pt solid #C89B3C; width:52mm; margin:1mm 0;"></div>
  <div style="font-size:5.6pt; font-weight:700; color:#A87F2E; text-transform:uppercase; letter-spacing:1px;">Authorized Signatory</div>
  <div style="font-family:'dejavuserif',serif; font-size:8.5pt; font-weight:700; color:#0F3D73;">$e_instr</div>
  <div style="font-size:5.4pt; color:#7A8296;">Assessor / Inspector</div>
</div>

<!-- BOTTOM RIGHT : OPERATIONS MANAGER -->
<div style="position:absolute; left:153mm; top:156mm; width:72mm; text-align:right;">
  <div style="text-align:right;">$mgr_img</div>
  <div style="border-top:0.7pt solid #C89B3C; width:52mm; margin:1mm 0 1mm auto;"></div>
  <div style="font-size:5.6pt; font-weight:700; color:#A87F2E; text-transform:uppercase; letter-spacing:1px;">Operations Manager</div>
  <div style="font-family:'dejavuserif',serif; font-size:8.5pt; font-weight:700; color:#0F3D73;">Eng. Khalid A. Alghamdi</div>
  <div style="font-size:5.4pt; color:#7A8296;">Operations Department</div>
</div>

<!-- FOOTER -->
<div class="sec-line"></div>
<div class="sec-txt">Secure Serial: $e_cert &nbsp;&middot;&nbsp; Verification Portal &nbsp;&middot;&nbsp; Anti-Fraud Security Features (Generated: $e_generated)</div>

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
    $mpdf->watermarkTextAlpha  = 0.045;
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
