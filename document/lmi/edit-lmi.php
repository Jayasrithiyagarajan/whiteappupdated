<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once('../../inc/function.php');
include_once('../../file/config.php');

if (!isset($_GET['project_no']) || empty($_GET['project_no'])) {
    die("Invalid Project No");
}

$project_no = $_GET['project_no'];

/* ================= FETCH EXISTING LMI ================= */
$lmiQ = $conn->prepare("SELECT * FROM lmi_certificates WHERE project_no = ?");
$lmiQ->bind_param("s", $project_no);
$lmiQ->execute();
$lmi = $lmiQ->get_result()->fetch_assoc();

if (!$lmi) {
    die("LMI Certificate not found");
}

/* ================= FETCH PROJECT DATA ================= */
$sql = "
SELECT 
    p.project_no,
    p.customer_name,
    p.equipment_location,
    p.inspector_name,
    r.report_no
FROM project_info p
LEFT JOIN reports r ON p.project_no = r.project_no
WHERE p.project_no = ?
LIMIT 1
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $project_no);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit LMI Certificate</title>
<link rel="stylesheet" href="<?= $url ?>assets/css/style.css">
<link rel="stylesheet" href="<?= $url ?>assets/css/bootstrap.min.css">
</head>

<body>
<div class="main-content container-fluid">

<form action="update-lmi.php" method="POST">

<input type="hidden" name="id" value="<?= $lmi['id'] ?>">
<input type="hidden" name="project_no" value="<?= $project_no ?>">

<!-- ================= HEADER ================= -->
<div class="form-element mb-20">
<h4>Edit LMI Certificate</h4>
<div class="row">

<div class="col-md-6">
<label>Certificate No</label>
<input class="theme-input-style bg-auto" value="<?= $lmi['certificate_no'] ?>" readonly>
</div>

<div class="col-md-6">
<label>Report No</label>
<input class="theme-input-style bg-auto" value="<?= $lmi['report_no'] ?>" readonly>
</div>

<div class="col-md-6">
<label>Customer Name</label>
<input class="theme-input-style bg-auto" value="<?= $lmi['customer_name'] ?>" readonly>
</div>

<div class="col-md-6">
<label>Location</label>
<input class="theme-input-style" name="location" value="<?= $lmi['location'] ?>">
</div>

<div class="col-md-6">
<label>Inspection Date</label>
<input type="date" class="theme-input-style" name="inspection_date" value="<?= $lmi['inspection_date'] ?>">
</div>

<div class="col-md-6">
<label>Next Inspection Date</label>
<input type="date" class="theme-input-style" name="next_inspection_date" value="<?= $lmi['next_inspection_date'] ?>">
</div>

</div>
</div>

<!-- ================= CRANE DETAILS ================= -->
<div class="form-element mb-20">
<h5>Crane Details</h5>
<div class="row">

<?php
$craneFields = [
    'crane_make'=>'Manufacturer',
    'crane_model'=>'Model',
    'crane_type'=>'Type',
    'crane_capacity'=>'Capacity',
    'crane_serial_no'=>'Serial No',
    'crane_id_no'=>'ID No'
];
foreach ($craneFields as $k=>$v):
?>
<div class="col-md-4">
<label><?= $v ?></label>
<input class="theme-input-style bg-auto" value="<?= $lmi[$k] ?>" readonly>
</div>
<?php endforeach; ?>

<div class="col-md-6">
<label>Boom Min (m)</label>
<input class="theme-input-style" name="boom_min" value="<?= $lmi['boom_min'] ?>">
</div>

<div class="col-md-6">
<label>Boom Max (m)</label>
<input class="theme-input-style" name="boom_max" value="<?= $lmi['boom_max'] ?>">
</div>

</div>
</div>

<!-- ================= LMI DETAILS ================= -->
<div class="form-element mb-20">
<h5>Load Moment Indicator (LMI)</h5>
<div class="row">

<div class="col-md-4">
<label>Manufacturer</label>
<input class="theme-input-style" name="lmi_make" value="<?= $lmi['lmi_make'] ?>">
</div>

<div class="col-md-4">
<label>Model</label>
<input class="theme-input-style" name="lmi_model_type" value="<?= $lmi['lmi_model_type'] ?>">
</div>

<div class="col-md-4">
<label>Type</label>
<input class="theme-input-style" name="lmi_type" value="<?= $lmi['lmi_type'] ?>">
</div>

<div class="col-md-4">
<label>Serial No</label>
<input class="theme-input-style" name="lmi_serial" value="<?= $lmi['lmi_serial'] ?>">
</div>

</div>
</div>

<!-- ================= LOAD CELL ================= -->
<div class="form-element mb-20">
<h5>Standard Load Cell</h5>
<div class="row">

<?php
$lc = [
    'lc_make'=>'Make',
    'lc_model_type'=>'Model',
    'lc_type'=>'Type',
    'lc_serial'=>'Serial No',
    'lc_capacity'=>'Capacity',
    'lc_accuracy'=>'Accuracy',
    'lc_cert_no'=>'Certificate No'
];
foreach ($lc as $k=>$v):
?>
<div class="col-md-4">
<label><?= $v ?></label>
<input class="theme-input-style" name="<?= $k ?>" value="<?= $lmi[$k] ?>">
</div>
<?php endforeach; ?>

</div>
</div>

<!-- ================= RADIUS LOAD ================= -->
<div class="form-element mb-20">
<h5>Radius Load Comparison</h5>

<table class="table table-bordered text-center">
<tr>
<th>Radius</th>
<th>Length</th>
<th>As per Load Chart</th>
<th>LMI Reading</th>
<th>Remarks</th>
</tr>

<tr>
<td rowspan="2"><strong>MAIN</strong></td>
<td><input name="radius_main_length_3m" value="<?= $lmi['radius_main_length_3m'] ?>"></td>
<td><input name="radius_main_chart" value="<?= $lmi['radius_main_chart'] ?>"></td>
<td><input name="radius_main_lmi" value="<?= $lmi['radius_main_lmi'] ?>"></td>
<td>
<select name="radius_main_remark">
<option <?= $lmi['radius_main_remark']=='Ok'?'selected':'' ?>>Ok</option>
<option <?= $lmi['radius_main_remark']=='NA'?'selected':'' ?>>NA</option>
</select>
</td>
</tr>

<tr>
<td><input name="radius_main_length_24m" value="<?= $lmi['radius_main_length_24m'] ?>"></td>
<td><input name="radius_24_chart" value="<?= $lmi['radius_24_chart'] ?>"></td>
<td><input name="radius_24_lmi" value="<?= $lmi['radius_24_lmi'] ?>"></td>
<td>
<select name="radius_24_remark">
<option <?= $lmi['radius_24_remark']=='Ok'?'selected':'' ?>>Ok</option>
<option <?= $lmi['radius_24_remark']=='NA'?'selected':'' ?>>NA</option>
</select>
</td>
</tr>

<tr>
<td><strong>AUX</strong></td>
<td><input name="radius_aux_length" value="<?= $lmi['radius_aux_length'] ?>"></td>
<td><input name="radius_aux_chart" value="<?= $lmi['radius_aux_chart'] ?>"></td>
<td><input name="radius_aux_lmi" value="<?= $lmi['radius_aux_lmi'] ?>"></td>
<td>
<select name="radius_aux_remark">
<option <?= $lmi['radius_aux_remark']=='Ok'?'selected':'' ?>>Ok</option>
<option <?= $lmi['radius_aux_remark']=='NA'?'selected':'' ?>>NA</option>
</select>
</td>
</tr>

</table>
</div>

<!-- ================= SAFETY & APPROVAL ================= -->
<div class="form-element mb-20">
<h5>Safety & Approval</h5>

<div class="row">
<div class="col-md-6">
<label>Anti Two Block Condition</label>
<input class="theme-input-style" name="anti_two_block" value="<?= $lmi['anti_two_block'] ?>">
</div>

<div class="col-md-6">
<label>Over Load & Lockout</label>
<input class="theme-input-style" name="overload_lockout" value="<?= $lmi['overload_lockout'] ?>">
</div>

<div class="col-md-12">
<label>Inspector</label>
<input class="theme-input-style" value="<?= $lmi['inspector'] ?>" readonly>
</div>
</div>
</div>

<!-- ================= APPROVAL ================= -->
<div class="form-element mb-20">

<div class="form-row mb-20">
<div class="col-sm-4"><label class="bold">Technical Manager</label></div>
<div class="col-sm-8">
<select class="theme-input-style" name="technical_manager">
<?php
$tm = ["Venancio Z. Vera","Mohammed Fathy","Khaled A. Alghamdi"];
foreach ($tm as $v):
?>
<option value="<?= $v ?>" <?= ($lmi['technical_manager']==$v?'selected':'') ?>><?= $v ?></option>
<?php endforeach; ?>
</select>
</div>
</div>

<div class="form-row mb-20">
<div class="col-sm-4"><label class="bold">Quality Controller</label></div>
<div class="col-sm-8">
<select class="theme-input-style" name="quality_controller">
<?php
$qc = ["Samuel Bhatti","Veera","Sathish"];
foreach ($qc as $v):
?>
<option value="<?= $v ?>" <?= ($lmi['quality_controller']==$v?'selected':'') ?>><?= $v ?></option>
<?php endforeach; ?>
</select>
</div>
</div>

</div>

<div class="text-center">
<button class="btn btn-primary">Update LMI Certificate</button>
</div>

<p class="text-center">FRM.0712.1 (Rev.00)</p>

</form>
</div>

<?php include_once('../../inc/footer.php'); ?>
</body>
</html>
