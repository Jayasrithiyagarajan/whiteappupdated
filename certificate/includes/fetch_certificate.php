<?php
require_once __DIR__.'/helper.php';

$assessment_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($assessment_id === 0) {
    die("Invalid assessment ID");
}

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

if (!$assessment) {
    die("Assessment not found");
}

$photo_stmt = $conn->prepare("SELECT file_path FROM operator_documents WHERE assessment_id=? AND document_type='PHOTO' LIMIT 1");
$photo_stmt->bind_param("i", $assessment_id);
$photo_stmt->execute();
$photo = $photo_stmt->get_result()->fetch_assoc();

$base_url = 'http://localhost/whiteappupdated/';
$photo_path = $base_url . 'assets/img/avatar/avatar-1.png';
if ($photo && !empty($photo['file_path'])) {
    $fp = $photo['file_path'];
    if (strpos($fp, '../') === 0) $fp = substr($fp, 3);
    $photo_path = $base_url . $fp;
}

$equipment = require __DIR__.'/fetch_equipment.php';
$company = require __DIR__.'/fetch_company.php';
$signatures = require __DIR__.'/fetch_signatures.php';

$designation = 'Crane Operator';
foreach ($equipment as $eq) {
    $t = $eq['type'];
    if ($designation === 'Crane Operator') {
        if (stripos($t,'mobile') !== false) $designation = 'Mobile Crane Operator';
        if (stripos($t,'forklift') !== false) $designation = 'Forklift Operator';
    }
}

$std = 'Company Assessment Criteria & Applicable Safety Standards';
// Use provided image values if possible, else standard fallback
$issue_date = date('d F Y', strtotime($assessment['date_of_assessment'] ?? $assessment['date'] ?? 'now'));
$expiry_date = !empty($assessment['date_of_expiry']) ? date('d F Y', strtotime($assessment['date_of_expiry'])) : 'N/A';

$validity = '2 Years';
if (!empty($assessment['date_of_assessment']) && !empty($assessment['date_of_expiry'])) {
    $days = abs(strtotime($assessment['date_of_expiry']) - strtotime($assessment['date_of_assessment'])) / 86400;
    $yrs  = round($days / 365);
    if ($yrs >= 1) $validity = $yrs.' '.($yrs>1?'Years':'Year');
    else {
        $mos = round($days / 30);
        $validity = $mos.' '.($mos>1?'Months':'Month');
    }
}

return [
    'certificate_no'      => $assessment['assessment_no'] ?? '',
    'validation_no'       => $assessment['assessment_no'] ?? '',
    'candidate_name'      => ucwords(strtolower($assessment['operator_name'] ?? '')),
    'passport'            => $assessment['operator_id_passport'] ?? '',
    'designation'         => $designation,
    'photo'               => $photo_path,
    'company'             => $company,
    'training_program'    => ucwords(strtolower($assessment['training_program'] ?? 'Training & Competency Assessment')),
    'assessment_standard' => $std,
    'status'              => 'VALID',
    'issue_date'          => $issue_date,
    'expiry_date'         => $expiry_date,
    'validity'            => $validity,
    'qr'                  => abs_path(__DIR__.'/../../document/code.png'),
    'signatures'          => $signatures,
    'equipment'           => $equipment,
    'vessel_location'     => 'AL-KHOBAR, SAUDI ARABIA', // Adjust from db if exists
    'renewal_due'         => 'Before ' . $expiry_date,
    'verify_url'          => 'verify.cims-global.org',
    'generated_at'        => date('Y-m-d H:i:s')
];
