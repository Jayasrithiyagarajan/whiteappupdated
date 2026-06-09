<?php
include_once('../../inc/function.php');
include_once('../../file/config.php');

if (!isset($_GET['project_no'])) {
    die('Project No missing');
}

$project_no = $_GET['project_no'];

/* ===== Project + Header Info ===== */
$stmt = $conn->prepare("
    SELECT 
        p.project_no,
        p.customer_name,
        p.customer_email,
        p.customer_mobile,
        p.inspector_name,
        r.report_no
    FROM project_info p
    LEFT JOIN reports r ON r.project_no = p.project_no
    WHERE p.project_no = ?
");
$stmt->bind_param("s", $project_no);
$stmt->execute();
$header = $stmt->get_result()->fetch_assoc();

/* ===== Fetch Existing Certificates ===== */
$certStmt = $conn->prepare("
    SELECT *
    FROM mpi_certificates
    WHERE project_no = ?
    ORDER BY id ASC
");
$certStmt->bind_param("s", $project_no);
$certStmt->execute();
$certificates = $certStmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit MPI Certificates</title>
<link rel="stylesheet" href="../../assets/css/bootstrap.min.css">

<style>
body { background:#f4f6f9; }
.cert-card { border-left:5px solid #6f42c1; }
.section-title { font-weight:600;color:#6f42c1;margin-top:20px; }
.img-preview img {
  width:80px;height:80px;object-fit:cover;
  border-radius:4px;border:1px solid #ccc;
}
.img-box { position:relative;margin:6px; }
.remove-img {
  position:absolute;top:-6px;right:-6px;
  width:22px;height:22px;border-radius:50%;
  background:#dc3545;color:#fff;border:none;
}
</style>
</head>

<body>

<form action="update_mpi_certificate.php" method="POST" enctype="multipart/form-data">

<input type="hidden" name="project_no" value="<?= htmlspecialchars($project_no) ?>">
<div class="main-content">

<!-- ===== Sticky Header ===== -->
<div class="sticky-top bg-white border-bottom shadow-sm">
  <div class="container-fluid py-2 d-flex justify-content-between">
    <div>
      <strong>Project:</strong> <?= htmlspecialchars($project_no) ?> |
      <strong>Report:</strong> <?= htmlspecialchars($header['report_no'] ?? '-') ?>
    </div>
    <button type="submit" name="update_mpi" class="btn btn-primary btn-sm">
      💾 Update All
    </button>
  </div>
</div>

<div class="container-fluid mt-3">

<?php foreach ($certificates as $i => $cert): 
  $images = json_decode($cert['images'], true) ?? [];
?>

<div class="card mb-3 cert-card">
<div class="card-header">
<strong><?= htmlspecialchars($cert['certificate_no']) ?></strong>
</div>

<div class="card-body">

<input type="hidden" name="cert_id[]" value="<?= $cert['id'] ?>">
<input type="hidden" name="certificate_no[]" value="<?= htmlspecialchars($cert['certificate_no']) ?>">

<!-- A. General -->
<div class="section-title">A. General Information</div>
<input name="location[]" class="form-control mb-2"
       value="<?= htmlspecialchars($cert['location']) ?>">
<input type="date" name="inspection_date[]" class="form-control mb-2"
       value="<?= $cert['inspection_date'] ?>">
<input type="date" name="next_inspection_date[]" class="form-control mb-2"
       value="<?= $cert['next_inspection_date'] ?>">

<!-- B. Equipment -->
<div class="section-title">B. Equipment Details</div>
<input name="inspected_item[]" class="form-control mb-2"
       value="<?= htmlspecialchars($cert['inspected_item']) ?>">
<input name="serial_numbers[]" class="form-control mb-2"
       value="<?= htmlspecialchars($cert['serial_numbers']) ?>">
<input name="manufacturer[]" class="form-control mb-2"
       value="<?= htmlspecialchars($cert['manufacturer']) ?>">
<input name="standards[]" class="form-control mb-2"
       value="<?= htmlspecialchars($cert['standards']) ?>">
<input name="swl[]" class="form-control mb-2"
       value="<?= htmlspecialchars($cert['swl']) ?>">

<!-- C. Testing Tools -->
<div class="section-title">C. Testing Tools</div>
<input name="mpi_equip_type[]" class="form-control mb-2"
       value="<?= htmlspecialchars($cert['mpi_equip_type']) ?>">
<input name="brand[]" class="form-control mb-2"
       value="<?= htmlspecialchars($cert['brand']) ?>">
<input name="current[]" class="form-control mb-2"
       value="<?= htmlspecialchars($cert['current']) ?>">
<input name="prod_spacing[]" class="form-control mb-2"
       value="<?= htmlspecialchars($cert['prod_spacing']) ?>">
<input name="contrast_paint[]" class="form-control mb-2"
       value="<?= htmlspecialchars($cert['contrast_paint']) ?>">
<input name="ink[]" class="form-control mb-2"
       value="<?= htmlspecialchars($cert['ink']) ?>">
<input name="particle_medium[]" class="form-control mb-2"
       value="<?= htmlspecialchars($cert['particle_medium']) ?>">
<input name="yoke_sn[]" class="form-control mb-2"
       value="<?= htmlspecialchars($cert['yoke_sn']) ?>">
<input name="model_no[]" class="form-control mb-2"
       value="<?= htmlspecialchars($cert['model_no']) ?>">
<input type="date" name="calibration_expiry_date[]" class="form-control mb-2"
       value="<?= $cert['calibration_expiry_date'] ?>">

<!-- D. Existing Images -->
<div class="section-title">D. Existing Images</div>
<div class="img-preview d-flex flex-wrap">
<?php foreach ($images as $img): ?>
  <div class="img-box">
    <img src="../../uploads/mpi_certificates/<?= htmlspecialchars($img) ?>">
    <div class="form-check">
      <input class="form-check-input"
             type="checkbox"
             name="remove_images[<?= $i ?>][]"
             value="<?= htmlspecialchars($img) ?>">
      <label class="form-check-label">Remove</label>
    </div>
  </div>
<?php endforeach; ?>
</div>

<!-- Add New Images -->
<input type="file" name="new_images[<?= $i ?>][]" multiple class="form-control mt-2">

<!-- E. Result -->
<div class="section-title">E. Result</div>
<select name="result[]" class="form-control mb-2">
<option value="">Select</option>
<option value="PASS" <?= $cert['result']=='PASS'?'selected':'' ?>>PASS</option>
<option value="FAIL" <?= $cert['result']=='FAIL'?'selected':'' ?>>FAIL</option>
</select>

<input name="comments[]" class="form-control"
       value="<?= htmlspecialchars($cert['comments']) ?>">

</div>
</div>

<?php endforeach; ?>

<div class="text-center my-4">
<button type="submit" name="update_mpi" class="btn btn-primary btn-lg">
💾 Update Certificates
</button>
</div>

</div>
</div>
</form>

</body>
</html>
