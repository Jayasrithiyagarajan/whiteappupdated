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
<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap');

    :root {
        --glass-bg: rgba(255, 255, 255, 0.85);
        --glass-border: rgba(255, 255, 255, 0.5);
        --primary-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
        font-family: 'Outfit', sans-serif;
    }

    .main-content {
        padding-top: 20px;
    }

    .glass-header {
        background: var(--glass-bg);
        backdrop-filter: blur(15px);
        border: 1px solid var(--glass-border);
        border-radius: 20px;
        padding: 20px;
        margin-bottom: 30px;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.05);
        position: sticky;
        top: 20px;
        z-index: 1000;
    }

    .form-element {
        background: var(--glass-bg) !important;
        backdrop-filter: blur(15px);
        border: 1px solid var(--glass-border) !important;
        border-radius: 25px !important;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.08) !important;
        padding: 30px !important;
        margin-bottom: 30px !important;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .cert-card {
        background: var(--glass-bg) !important;
        backdrop-filter: blur(15px);
        border: 1px solid var(--glass-border) !important;
        border-radius: 25px !important;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.08) !important;
        margin-bottom: 30px !important;
        overflow: hidden;
    }

    .cert-header {
        background: rgba(79, 172, 254, 0.1);
        padding: 15px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid var(--glass-border);
    }

    .cert-body {
        padding: 25px;
    }

    .font-20 {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        color: #1a202c;
        letter-spacing: -0.5px;
        margin: 0;
    }

    .theme-input-style, .form-control, .custom-select {
        background: rgba(255, 255, 255, 0.6) !important;
        border: 1px solid rgba(0, 0, 0, 0.08) !important;
        border-radius: 12px !important;
        padding: 10px 15px !important;
        font-family: 'Outfit', sans-serif;
        height: auto !important;
        line-height: 1.5 !important;
        transition: all 0.3s ease !important;
        width: 100%;
        color: #2d3748;
    }

    .theme-input-style:focus, .form-control:focus, .custom-select:focus {
        background: rgba(255, 255, 255, 0.9) !important;
        border-color: #4facfe !important;
        box-shadow: 0 0 0 4px rgba(79, 172, 254, 0.1) !important;
        outline: none;
    }

    .btn-primary, .btn-brand, .btn.long, .btn-success, .btn-outline-primary {
        background: var(--primary-gradient) !important;
        border: none !important;
        border-radius: 15px !important;
        padding: 10px 20px !important;
        font-weight: 600 !important;
        font-family: 'Outfit', sans-serif;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: white !important;
        box-shadow: 0 4px 15px rgba(0, 198, 255, 0.3) !important;
        transition: all 0.3s ease !important;
    }

    .btn-outline-primary {
        background: rgba(79, 172, 254, 0.1) !important;
        color: #4facfe !important;
    }

    .btn-outline-secondary {
        background: rgba(0, 0, 0, 0.05) !important;
        border: none !important;
        border-radius: 15px !important;
        padding: 10px 20px !important;
        font-weight: 600 !important;
        color: #4a5568 !important;
    }

    .btn-link.text-danger {
        color: #e53e3e !important;
        font-weight: 600;
        text-decoration: none !important;
    }

    .btn-link.toggle {
        color: #4facfe !important;
        font-weight: 600;
        text-decoration: none !important;
    }

    label, .field-label {
        color: #4a5568;
        font-weight: 600;
        margin-bottom: 8px !important;
        margin-top: 15px !important;
        display: block;
        font-size: 13px;
    }

    .section-title {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        color: #1a202c;
        margin-top: 25px;
        margin-bottom: 15px;
        display: block;
        border-bottom: 2px solid rgba(79, 172, 254, 0.3);
        padding-bottom: 8px;
    }

    .img-preview img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 15px;
        border: 2px solid var(--glass-border);
    }

    .img-box {
        position: relative;
        margin: 10px;
    }

    .remove-img {
        position: absolute;
        top: -10px;
        right: -10px;
        width: 25px;
        height: 25px;
        border-radius: 50%;
        background: #e53e3e;
        color: #fff;
        border: none;
        font-size: 14px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }
    
    .remove-existing-img-checkbox {
        position: absolute;
        bottom: 5px;
        left: 5px;
        background: rgba(255, 255, 255, 0.9);
        padding: 2px 5px;
        border-radius: 5px;
        font-size: 12px;
        font-weight: bold;
    }

    /* Mobile Responsiveness */
    @media (max-width: 991px) {
        .glass-header {
            flex-direction: column;
            gap: 15px;
            text-align: center;
        }
        .form-element {
            padding: 25px 20px !important;
        }
    }
</style>

<div class="main-content">
    <form action="update_mpi_certificate.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="project_no" value="<?= htmlspecialchars($project_no) ?>">
        <div class="container-fluid">
            <!-- Sticky Header -->
            <div class="glass-header d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="font-20">Edit MPI Certificates</h1>
                    <div class="text-muted small mt-1">
                        <strong>Project:</strong> <?= htmlspecialchars($project_no) ?> |
                        <strong>Report:</strong> <?= htmlspecialchars($header['report_no'] ?? '-') ?>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <button type="button" class="btn btn-outline-secondary" onclick="collapseAll()">Collapse All</button>
                    <button type="submit" name="update_mpi" class="btn btn-success">Update All Certificates</button>
                </div>
            </div>

            <div id="certContainer">
                <?php foreach ($certificates as $i => $cert): 
                  $images = json_decode($cert['images'], true) ?? [];
                ?>
                <div class="cert-card">
                    <div class="cert-header">
                        <strong class="cert-title font-20" style="font-size: 16px;"><?= htmlspecialchars($cert['certificate_no']) ?></strong>
                        <div class="d-flex gap-3">
                            <button type="button" class="btn btn-link toggle" onclick="toggleCard(this)">Toggle View</button>
                        </div>
                    </div>
                    <div class="cert-body">
                        <input type="hidden" name="cert_id[]" value="<?= $cert['id'] ?>">
                        <input type="hidden" name="certificate_no[]" value="<?= htmlspecialchars($cert['certificate_no']) ?>">
                        
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="section-title">A. General Information</div>
                                <label class="field-label">Location</label>
                                <input name="location[]" class="theme-input-style mb-2" value="<?= htmlspecialchars($cert['location']) ?>">
                                <label class="field-label">Inspection Date</label>
                                <input type="date" name="inspection_date[]" class="theme-input-style mb-2" value="<?= $cert['inspection_date'] ?>">
                                <label class="field-label">Next Inspection Date</label>
                                <input type="date" name="next_inspection_date[]" class="theme-input-style mb-2" value="<?= $cert['next_inspection_date'] ?>">
                            </div>
                            <div class="col-lg-6">
                                <div class="section-title">B. Equipment Details</div>
                                <label class="field-label">Inspected Item</label>
                                <input name="inspected_item[]" class="theme-input-style mb-2" value="<?= htmlspecialchars($cert['inspected_item']) ?>">
                                <label class="field-label">Serial Numbers</label>
                                <input name="serial_numbers[]" class="theme-input-style mb-2" value="<?= htmlspecialchars($cert['serial_numbers']) ?>">
                                <label class="field-label">Manufacturer / Equip No</label>
                                <input name="manufacturer[]" class="theme-input-style mb-2" value="<?= htmlspecialchars($cert['manufacturer']) ?>">
                                <label class="field-label">Applicable Standards</label>
                                <input name="standards[]" class="theme-input-style mb-2" value="<?= htmlspecialchars($cert['standards']) ?>">
                                <label class="field-label">SWL</label>
                                <input name="swl[]" class="theme-input-style mb-2" value="<?= htmlspecialchars($cert['swl']) ?>">
                            </div>
                        </div>

                        <div class="section-title">C. Testing Tools</div>
                        <div class="row">
                            <div class="col-lg-4">
                                <label class="field-label">MPI Equipment Type</label>
                                <input name="mpi_equip_type[]" class="theme-input-style mb-2 tt" value="<?= htmlspecialchars($cert['mpi_equip_type']) ?>">
                                <label class="field-label">Brand</label>
                                <input name="brand[]" class="theme-input-style mb-2 tt" value="<?= htmlspecialchars($cert['brand']) ?>">
                                <label class="field-label">Current</label>
                                <input name="current[]" class="theme-input-style mb-2 tt" value="<?= htmlspecialchars($cert['current']) ?>">
                                <label class="field-label">Prod Spacing</label>
                                <input name="prod_spacing[]" class="theme-input-style mb-2 tt" value="<?= htmlspecialchars($cert['prod_spacing']) ?>">
                            </div>
                            <div class="col-lg-4">
                                <label class="field-label">Contrast Paint</label>
                                <input name="contrast_paint[]" class="theme-input-style mb-2 tt" value="<?= htmlspecialchars($cert['contrast_paint']) ?>">
                                <label class="field-label">Ink</label>
                                <input name="ink[]" class="theme-input-style mb-2 tt" value="<?= htmlspecialchars($cert['ink']) ?>">
                                <label class="field-label">Particle Medium</label>
                                <input name="particle_medium[]" class="theme-input-style mb-2 tt" value="<?= htmlspecialchars($cert['particle_medium']) ?>">
                            </div>
                            <div class="col-lg-4">
                                <label class="field-label">Yoke Serial No</label>
                                <input name="yoke_sn[]" class="theme-input-style mb-2 tt" value="<?= htmlspecialchars($cert['yoke_sn']) ?>">
                                <label class="field-label">Model No</label>
                                <input name="model_no[]" class="theme-input-style mb-2 tt" value="<?= htmlspecialchars($cert['model_no']) ?>">
                                <label class="field-label">Calibration Expiry Date</label>
                                <input type="date" name="calibration_expiry_date[]" class="theme-input-style mb-2 tt" value="<?= $cert['calibration_expiry_date'] ?>">
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-lg-6">
                                <div class="section-title">D. Images</div>
                                
                                <div class="img-preview d-flex flex-wrap mb-3">
                                <?php foreach ($images as $img): ?>
                                  <div class="img-box">
                                    <img src="../../uploads/mpi_certificates/<?= htmlspecialchars($img) ?>">
                                    <div class="remove-existing-img-checkbox">
                                      <input class="form-check-input"
                                             type="checkbox"
                                             name="remove_images[<?= $i ?>][]"
                                             value="<?= htmlspecialchars($img) ?>" id="rm_<?= $i ?>_<?= htmlspecialchars($img) ?>">
                                      <label class="form-check-label text-danger" for="rm_<?= $i ?>_<?= htmlspecialchars($img) ?>">Remove</label>
                                    </div>
                                  </div>
                                <?php endforeach; ?>
                                </div>

                                <label class="field-label">Upload Additional Images</label>
                                <input type="file" name="new_images[<?= $i ?>][]" class="theme-input-style img-input" multiple>
                                <div class="img-preview-new d-flex flex-wrap mt-3"></div>
                            </div>
                            <div class="col-lg-6">
                                <div class="section-title">E. Result</div>
                                <label class="field-label">Result Status</label>
                                <select name="result[]" class="custom-select mb-2">
                                    <option value="">Select Result</option>
                                    <option value="PASS" <?= $cert['result']=='PASS'?'selected':'' ?>>PASS</option>
                                    <option value="FAIL" <?= $cert['result']=='FAIL'?'selected':'' ?>>FAIL</option>
                                </select>
                                <label class="field-label">Comments / Action Required</label>
                                <textarea name="comments[]" class="theme-input-style" rows="2"><?= htmlspecialchars($cert['comments']) ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Bottom Update Button -->
            <div class="text-center my-4">
                <button type="submit" name="update_mpi" class="btn btn-primary btn-lg">
                    💾 Update Certificates
                </button>
            </div>
        </div>
    </form>
</div>

<script>
function toggleCard(btn) {
    const body = btn.closest('.cert-card').querySelector('.cert-body');
    body.style.display = body.style.display === 'none' ? 'block' : 'none';
}

function collapseAll() {
    document.querySelectorAll('.cert-body').forEach(b => b.style.display = 'none');
}

function renderPreview(input) {
  const preview = input.nextElementSibling;
  if (!preview) return;
  preview.innerHTML = '';
  if(!input._files) return;
  input._files.forEach((file, i) => {
    const r = new FileReader();
    r.onload = e => {
      preview.insertAdjacentHTML('beforeend',
        `<div class="img-box">
          <img src="${e.target.result}">
          <button type="button" class="remove-img" data-i="${i}">×</button>
        </div>`);
    };
    r.readAsDataURL(file);
  });
}

/* NEW IMAGE HANDLING */
document.addEventListener('change', e => {
  if (!e.target.classList.contains('img-input')) return;
  const input = e.target;
  if (!input._files) input._files = [];
  input._files = input._files.concat([...input.files]);
  const dt = new DataTransfer();
  input._files.forEach(f => dt.items.add(f));
  input.files = dt.files;
  renderPreview(input);
});

document.addEventListener('click', e => {
  if (!e.target.classList.contains('remove-img')) return;
  const input = e.target.closest('.img-preview-new').previousElementSibling;
  input._files.splice(e.target.dataset.i, 1);
  const dt = new DataTransfer();
  input._files.forEach(f => dt.items.add(f));
  input.files = dt.files;
  renderPreview(input);
});
</script>

<?php include_once('../../inc/footer.php'); ?>
