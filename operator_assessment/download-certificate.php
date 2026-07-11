<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
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

/* â”€â”€ Mapped Variables â”€â”€ */
$cert_no         = $assessment['assessment_no'];
$name            = ucwords(strtolower($assessment['operator_name']));
$iqama           = $assessment['operator_id_passport'];
$company         = strtoupper($assessment['client_name']);
$program         = ucwords(strtolower($assessment['training_program']));
$completion_date = date('d M Y', strtotime($assessment['date_of_assessment'] ?? $assessment['date']));
$renewal_date    = $assessment['date_of_expiry'] ? date('d M Y', strtotime($assessment['date_of_expiry'])) : 'N/A';
$instructor      = ucwords(strtolower($assessment['inspector_name']));

/* â”€â”€ Absolute Paths â”€â”€ */
function abs_path($rel) { $p = realpath($rel); return $p ? str_replace('\\','/',$p) : ''; }
$bg_path            = abs_path(__DIR__.'/../document/certificate_bg.png');
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

/* â”€â”€ Equipment â”€â”€ */
$designation = 'Crane Operator';
$eq_html     = '';
foreach ($equipments as $eq) {
    $parts = [];
    if (!empty($eq['equipment_type'])) {
        $t = trim($eq['equipment_type']);
        $parts[] = $t;
        if ($designation === 'Crane Operator') {
            if (stripos($t,'mobile')   !== false) $designation = 'Mobile Crane Operator';
            if (stripos($t,'forklift') !== false) $designation = 'Forklift Operator';
        }
    }
    if (!empty($eq['manufacturer'])) $parts[] = trim($eq['manufacturer']);
    if (!empty($eq['model']))        $parts[] = trim($eq['model']);
    $s = implode(', ', $parts);
    if (!empty($eq['capacity']))     $s .= ' (SWL: '.trim($eq['capacity']).')';
    if ($s) $eq_html .= '<div style="padding:1.5px 0;line-height:1.55;"><span style="color:#15803D;font-weight:800;font-size:8pt;">&#10003;</span><span style="font-size:7.5pt;color:#1E3A5F;font-weight:600;"> '.htmlspecialchars($s).'</span></div>';
}
if (!$eq_html) $eq_html = '<div style="padding:1.5px 0;line-height:1.55;"><span style="color:#15803D;font-weight:800;font-size:8pt;">&#10003;</span><span style="font-size:7.5pt;color:#1E3A5F;font-weight:600;"> '.htmlspecialchars($program).'</span></div>';

/* â”€â”€ Validity â”€â”€ */
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

/* â”€â”€ Assessment Standard â”€â”€ */
$std = 'CIMS-SOP-GEN-2026';
if (stripos($designation,'crane')   !== false) $std = 'CIMS-SOP-CRN-2026';
if (stripos($designation,'forklift')!== false) $std = 'CIMS-SOP-FLT-2026';

/* ====================================================================
   HTML  â€”  A4-L  297 Ã— 210 mm
   Zone A (Photo panel):    left 0   â€“ 72mm
   Zone B (Main content):   left 72  â€“ 234mm   width=162mm
   Zone B (Right panel):    left 234 â€“ 297mm   width=63mm
   ==================================================================== */
$html = '<!DOCTYPE html><html><head><meta charset="UTF-8">
<style>
@import url(\'https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;800;900&family=Great+Vibes&family=Inter:wght@300;400;500;600;700&display=swap\');
@page { size:A4-L; margin:0; }
body { margin:0; padding:0; font-family:\'Inter\',Arial,sans-serif; background:#fff; color:#1A2B4A; }

/* â”€â”€ HEADER â”€â”€ */
.hdr       { position:absolute; left:70mm; top:8mm; width:160mm; }
.hdr-co    { font-family:\'Cinzel\',serif; font-size:13.5pt; font-weight:700; color:#123B78;
             text-transform:uppercase; letter-spacing:1px; margin:0; line-height:1.25; }
.hdr-sub   { font-size:7pt; font-weight:500; color:#123B78; text-transform:uppercase;
             letter-spacing:.7px; margin:4px 0 0; }
.hdr-slg   { font-size:6.5pt; font-weight:700; color:#C89A2B; text-transform:uppercase;
             letter-spacing:2.5px; margin:4px 0 0; }
.hdr-iso   { font-size:5.8pt; color:#6B7280; margin:4px 0 0; }

/* â”€â”€ CERT NO BOX â”€â”€ */
.cno-box   { position:absolute; left:247mm; top:7mm; width:43mm; background:#123B78;
             border:2px solid #C89A2B; border-radius:3px; text-align:center; padding:3mm 2mm 4mm; }
.cno-lbl   { font-size:4.8pt; font-weight:700; color:#C89A2B; text-transform:uppercase; letter-spacing:1.2px; }
.cno-sep   { height:1px; border-top:1px solid #3D5A8A; margin:2.5px 10px; }
.cno-val   { font-family:\'Cinzel\',serif; font-size:11pt; font-weight:700; color:#FBBF24;
             letter-spacing:1px; margin-top:2px; }

/* â”€â”€ EMBLEM â”€â”€ */
.emblem-box { position:absolute; left:239mm; top:38mm; width:49mm; text-align:center; }
.emblem-img { width:42mm; height:42mm; object-fit:contain; }

/* â”€â”€ PHOTO â”€â”€ */
.ph-outer  { position:absolute; left:9mm; top:37mm; width:55mm; }
.ph-frame  { border:2.5px solid #C89A2B; border-radius:6px; overflow:hidden;
             width:53mm; height:65mm; background:#fff; }
.ph-img    { width:53mm; height:65mm; object-fit:cover; display:block; }
.ph-badge  { margin-top:2.5mm; width:53mm; background:#123B78; border:1.5px solid #C89A2B;
             border-radius:4px; color:#fff; font-size:6.5pt; font-weight:700;
             text-align:center; padding:2.5mm 0; letter-spacing:.8px; }

/* â”€â”€ SEAL â”€â”€ */
.seal-box  { position:absolute; left:7mm; top:148mm; width:57mm; text-align:center; }
.seal-img  { width:52mm; height:52mm; object-fit:contain; }

/* â”€â”€ TITLE â”€â”€ */
.ttl-blk   { position:absolute; left:68mm; top:38mm; width:168mm; text-align:center; }
.ttl-h1    { font-family:\'Cinzel\',serif; font-size:25pt; font-weight:900; color:#123B78;
             text-transform:uppercase; letter-spacing:5px; margin:0; line-height:1.2; }
.ttl-orn   { color:#C89A2B; font-size:8pt; letter-spacing:5px; margin:2.5mm 0 0; }

/* â”€â”€ CERTIFY ROW â”€â”€ */
.cert-row  { position:absolute; left:68mm; top:63mm; width:168mm; }
.cert-tbl  { width:88%; margin:0 auto; border-collapse:collapse; }
.cert-tbl td { border:none; padding:0; vertical-align:middle; }
.cert-line { border-top:1px solid #C89A2B; height:1px; display:block; }
.cert-txt  { white-space:nowrap; font-size:7pt; font-weight:600; color:#C89A2B;
             text-transform:uppercase; letter-spacing:3px; padding:0 5mm; }

/* â”€â”€ CANDIDATE NAME â”€â”€ */
.name-row  { position:absolute; left:68mm; top:68mm; width:168mm; text-align:center; }
.name-txt  { font-family:\'Great Vibes\',cursive; font-size:44pt; color:#123B78;
             margin:0; line-height:1.1; }

/* â”€â”€ DESCRIPTION â”€â”€ */
.desc-row  { position:absolute; left:73mm; top:98mm; width:158mm; text-align:center; }
.desc-txt  { font-size:7.8pt; color:#4A5568; line-height:1.8; margin:0; font-weight:400; }

/* â”€â”€ INFO GRID â”€â”€ */
.grid-wrap { position:absolute; left:68mm; top:115mm; width:168mm; }
.gtbl      { width:100%; border-collapse:collapse; border:1.5px solid #C89A2B; background:#fff; }
.gtbl td   { border:1px solid #E8D99A; padding:2.2mm 2.8mm; vertical-align:top; }
.g-lbl     { font-size:5.3pt; color:#C89A2B; font-weight:700; text-transform:uppercase;
             letter-spacing:1.2px; margin-bottom:2px; }
.g-val     { font-size:8.5pt; color:#123B78; font-weight:700; line-height:1.2; }
.g-val-lg  { font-size:9pt; color:#123B78; font-weight:700; line-height:1.2; }
.passed    { display:inline-block; background:#15803D; color:#fff; font-size:6.5pt;
             font-weight:700; padding:.6mm 2.5mm; border-radius:2px;
             text-transform:uppercase; letter-spacing:.5px; }

/* â”€â”€ DATES â”€â”€ */
.dates-wrap { position:absolute; left:68mm; top:155mm; width:168mm; }
.dtbl       { width:100%; border-collapse:collapse; border:1px solid #E8D99A; background:#FDFCF7; }
.dtbl td    { border:1px solid #E8D99A; padding:2.8mm 1mm; text-align:center; width:25%; }
.d-lbl      { font-size:5.3pt; color:#C89A2B; font-weight:700; text-transform:uppercase;
              letter-spacing:1px; margin-bottom:2px; }
.d-val      { font-size:9pt; color:#123B78; font-weight:700; }

/* â”€â”€ QR BOX â”€â”€ */
.qr-box    { position:absolute; left:237mm; top:88mm; width:52mm; background:#fff;
             border:1.5px solid #C89A2B; border-radius:4px; text-align:center; padding:3mm 2mm; }
.qr-ttl    { font-family:\'Cinzel\',serif; font-size:5.5pt; font-weight:700; color:#123B78;
             text-transform:uppercase; letter-spacing:1px; margin-bottom:1mm; line-height:1.5; }
.qr-sub    { font-size:4.5pt; color:#9CA3AF; letter-spacing:.5px; margin-bottom:2mm; }
.qr-img    { width:37mm; height:37mm; object-fit:contain; }
.qr-ftr    { font-size:4.5pt; color:#6B7280; margin-top:2mm; line-height:1.6; }
.qr-no     { font-size:7pt; font-weight:700; color:#C89A2B; margin-top:2.5mm; letter-spacing:.5px; }

/* â”€â”€ SIGNATURES â”€â”€ */
.sigs-wrap { position:absolute; left:68mm; top:171mm; width:168mm; }
.stbl      { width:100%; border-collapse:collapse; }
.stbl td   { border:none; padding:0; vertical-align:bottom; }
.sig-i     { height:28px; }
.sig-img   { height:26px; object-fit:contain; }
.sig-ln-l  { border-top:1px solid #C89A2B; width:120px; margin:1.5mm 0; }
.sig-ln-r  { border-top:1px solid #C89A2B; width:120px; margin:1.5mm 0 1.5mm auto; }
.sig-lbl   { font-size:5.5pt; font-weight:700; color:#9CA3AF; text-transform:uppercase; letter-spacing:.8px; }
.sig-name  { font-size:8.5pt; font-weight:700; color:#123B78; }
</style>
</head><body>

<!-- BACKGROUND -->
<div style="position:absolute;left:0;top:0;width:297mm;height:210mm;z-index:-100;">
  <img src="'.$bg_path.'" style="width:297mm;height:210mm;display:block;">
</div>

<!-- FULL-WIDTH GOLD RULES -->
<div style="position:absolute;left:0;top:33mm;width:297mm;height:1px;border-top:1.5px solid #C89A2B;"></div>
<div style="position:absolute;left:0;top:34.5mm;width:297mm;height:1px;border-top:.5px solid #C89A2B;"></div>
<div style="position:absolute;left:0;top:197mm;width:297mm;height:1px;border-top:1px solid #C89A2B;"></div>

<!-- VERTICAL DIVIDER (left panel separator) -->
<div style="position:absolute;left:67mm;top:37mm;width:1px;height:158mm;border-left:1px solid #E8D99A;"></div>

<!-- HEADER -->
<div class="hdr">
  <table style="border:none;border-collapse:collapse;width:100%;">
    <tr>
      <td style="border:none;padding:0;width:72px;vertical-align:top;padding-top:2px;">
        <img style="width:64px;height:64px;object-fit:contain;" src="'.$client_logo.'">
      </td>
      <td style="border:none;padding:0 0 0 12px;vertical-align:top;">
        <div class="hdr-co">CRANE INSPECTION &amp; MAINTENANCE SERVICES</div>
        <div class="hdr-sub">Approved Training &amp; Assessment Center</div>
        <div class="hdr-slg">Safety &bull; Quality &bull; Excellence</div>
        <div class="hdr-iso">ISO 9001:2015 Certified &nbsp;|&nbsp; Reg No: CIMS/2026/006</div>
      </td>
    </tr>
  </table>
</div>

<!-- CERTIFICATE NO -->
<div class="cno-box">
  <div class="cno-lbl">Certificate No.</div>
  <div class="cno-sep"></div>
  <div class="cno-val">'.htmlspecialchars($cert_no).'</div>
</div>

<!-- EMBLEM -->
<div class="emblem-box">
  <img class="emblem-img" src="'.$gold_seal_path.'">
</div>

<!-- PHOTO FRAME -->
<div class="ph-outer">
  <div class="ph-frame">
    <img class="ph-img" src="'.$photo_path.'">
  </div>
  <div class="ph-badge">&#9733; &nbsp;Certified Operator&nbsp; &#9733;</div>
</div>

<!-- SEAL -->
<div class="seal-box">
  <img class="seal-img" src="'.$partner_badge_path.'">
</div>

<!-- CERTIFICATE TITLE -->
<div class="ttl-blk">
  <h1 class="ttl-h1">Operator Certificate</h1>
  <div class="ttl-orn">&#8212; &#10022; &#8212;</div>
</div>

<!-- THIS IS TO CERTIFY THAT -->
<div class="cert-row">
  <table class="cert-tbl">
    <tr>
      <td style="width:38%;"><span class="cert-line"></span></td>
      <td><span class="cert-txt">This is to certify that</span></td>
      <td style="width:38%;"><span class="cert-line"></span></td>
    </tr>
  </table>
</div>

<!-- CANDIDATE NAME -->
<div class="name-row">
  <p class="name-txt">'.htmlspecialchars($name).'</p>
</div>

<!-- DESCRIPTION -->
<div class="desc-row">
  <p class="desc-txt">has successfully completed the required training &amp; assessment and demonstrated<br>
  the necessary competence in accordance with the standards and requirements<br>of our training program.</p>
</div>

<!-- INFO GRID -->
<div class="grid-wrap">
<table class="gtbl">
  <tr>
    <td style="width:22%;">
      <div class="g-lbl">Operator ID</div>
      <div class="g-val">'.htmlspecialchars($iqama).'</div>
    </td>
    <td style="width:37%;">
      <div class="g-lbl">Designation</div>
      <div class="g-val-lg">'.htmlspecialchars($designation).'</div>
    </td>
    <td style="width:27%;">
      <div class="g-lbl">Assessment Standard</div>
      <div class="g-val">'.htmlspecialchars($std).'</div>
    </td>
    <td style="width:14%; text-align:center; vertical-align:middle;">
      <div class="g-lbl">Status</div>
      <div><span class="passed">PASSED</span></div>
    </td>
  </tr>
  <tr>
    <td colspan="2" style="border-top:1.5px solid #C89A2B;">
      <div class="g-lbl">Training Program</div>
      <div class="g-val-lg">'.htmlspecialchars($program).'</div>
    </td>
    <td colspan="2" style="border-top:1.5px solid #C89A2B; background:#FDFCF8;">
      <div class="g-lbl">Assessment Period</div>
      <div class="g-val">'.htmlspecialchars($completion_date).' &nbsp;&mdash;&nbsp; '.htmlspecialchars($renewal_date).'</div>
    </td>
  </tr>
  <tr>
    <td colspan="4" style="border-top:1.5px solid #C89A2B; background:#F9F8F3; padding:2mm 2.8mm;">
      <div class="g-lbl" style="color:#123B78; margin-bottom:3px;">Equipment / Category Certified For</div>
      '.$eq_html.'
    </td>
  </tr>
</table>
</div>

<!-- DATES BAR -->
<div class="dates-wrap">
<table class="dtbl">
  <tr>
    <td><div class="d-lbl">Date of Issue</div><div class="d-val">'.htmlspecialchars($completion_date).'</div></td>
    <td><div class="d-lbl">Expiry Date</div><div class="d-val">'.htmlspecialchars($renewal_date).'</div></td>
    <td><div class="d-lbl">Validity</div><div class="d-val">'.htmlspecialchars($validity).'</div></td>
    <td style="border-right:none;"><div class="d-lbl">Renewal Due</div><div class="d-val">Before '.htmlspecialchars($renewal_date).'</div></td>
  </tr>
</table>
</div>

<!-- QR VERIFICATION -->
<div class="qr-box">
  <div class="qr-ttl">Scan to Verify<br>Certificate Authenticity</div>
  <div class="qr-sub">Official Verification Portal</div>
  <img class="qr-img" src="'.$qr_path.'">
  <div class="qr-ftr">This certificate is valid<br>only with official verification</div>
  <div class="qr-no">'.htmlspecialchars($cert_no).'</div>
</div>

<!-- SIGNATURES -->
<div class="sigs-wrap">
<table class="stbl">
<tr>
  <td style="width:42%; text-align:left; vertical-align:bottom;">
    <div class="sig-i">';
if ($insp_sig) $html .= '<img class="sig-img" src="'.$insp_sig.'">';
else            $html .= '<div style="height:26px;"></div>';
$html .= '</div>
    <div class="sig-ln-l"></div>
    <div class="sig-lbl">Authorized Signatory</div>
    <div class="sig-name">'.htmlspecialchars($instructor).'</div>
  </td>
  <td style="width:16%; text-align:center;"></td>
  <td style="width:42%; text-align:right; vertical-align:bottom;">
    <div class="sig-i" style="text-align:right;">';
if ($mgr_sig) $html .= '<img class="sig-img" src="'.$mgr_sig.'">';
else          $html .= '<div style="height:26px;"></div>';
$html .= '</div>
    <div class="sig-ln-r"></div>
    <div class="sig-lbl" style="text-align:right;">Operations Manager</div>
    <div class="sig-name" style="text-align:right;">Eng. Khalid A. Alghamdi</div>
  </td>
</tr>
</table>
</div>

</body></html>';

try {
    $mpdf = new \Mpdf\Mpdf([
        'format'=>'A4-L','margin_left'=>0,'margin_right'=>0,
        'margin_top'=>0,'margin_bottom'=>0,'margin_header'=>0,'margin_footer'=>0,
        'img_dpi'=>96,'tempDir'=>__DIR__.'/../tmp',
    ]);
    $mpdf->SetDisplayMode('fullpage');
    $mpdf->setAutoPageBreak(false);
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
