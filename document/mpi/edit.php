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
        r.report_no,
        r.location,
        r.date_of_inspection,
        r.next_inspection_due_date
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

$year = date('Y');

// Calculate max index to generate new certificate numbers
$max_index = 0;
foreach($certificates as $c) {
    if(preg_match('/CMPI-(\d+)-/', $c['certificate_no'], $m)) {
        $max_index = max($max_index, (int)$m[1]);
    }
}
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
    <form action="update_mpi_certificate.php" method="POST" enctype="multipart/form-data" id="editForm">
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
                    <button type="button" class="btn btn-outline-primary" onclick="addCertificate()">+ Add Certificate</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="collapseAll()">Collapse All</button>
                    <button type="submit" name="update_mpi" class="btn btn-success">Save Changes</button>
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
                            <button type="button" class="btn btn-link text-danger" onclick="removeExistingCert(this, <?= $cert['id'] ?>)">Remove</button>
                        </div>
                    </div>
                    <div class="cert-body">
                        <input type="hidden" name="cert_id[<?= $i ?>]" value="<?= $cert['id'] ?>">
                        <input type="hidden" name="certificate_no[<?= $i ?>]" value="<?= htmlspecialchars($cert['certificate_no']) ?>">
                        
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="section-title">A. General Information</div>
                                <label class="field-label">Location</label>
                                <input name="location[<?= $i ?>]" class="theme-input-style mb-2" value="<?= htmlspecialchars($cert['location']) ?>">
                                <label class="field-label">Inspection Date</label>
                                <input type="date" name="inspection_date[<?= $i ?>]" class="theme-input-style mb-2" value="<?= $cert['inspection_date'] ?>">
                                <label class="field-label">Next Inspection Date</label>
                                <input type="date" name="next_inspection_date[<?= $i ?>]" class="theme-input-style mb-2" value="<?= $cert['next_inspection_date'] ?>">
                            </div>
                            <div class="col-lg-6">
                                <div class="section-title">B. Equipment Details</div>
                                <label class="field-label">Inspected Item</label>
                                <input name="inspected_item[<?= $i ?>]" class="theme-input-style mb-2" value="<?= htmlspecialchars($cert['inspected_item']) ?>">
                                <label class="field-label">Serial Numbers</label>
                                <input name="serial_numbers[<?= $i ?>]" class="theme-input-style mb-2" value="<?= htmlspecialchars($cert['serial_numbers']) ?>">
                                <label class="field-label">Manufacturer / Equip No</label>
                                <input name="manufacturer[<?= $i ?>]" class="theme-input-style mb-2" value="<?= htmlspecialchars($cert['manufacturer']) ?>">
                                <label class="field-label">Applicable Standards</label>
                                <input name="standards[<?= $i ?>]" class="theme-input-style mb-2" value="<?= htmlspecialchars($cert['standards']) ?>">
                                <label class="field-label">SWL</label>
                                <input name="swl[<?= $i ?>]" class="theme-input-style mb-2" value="<?= htmlspecialchars($cert['swl']) ?>">
                            </div>
                        </div>

                        <div class="section-title">C. Testing Tools</div>
                        <div class="row">
                            <div class="col-lg-4">
                                <label class="field-label">MPI Equipment Type</label>
                                <input name="mpi_equip_type[<?= $i ?>]" class="theme-input-style mb-2 tt" value="<?= htmlspecialchars($cert['mpi_equip_type']) ?>">
                                <label class="field-label">Brand</label>
                                <input name="brand[<?= $i ?>]" class="theme-input-style mb-2 tt" value="<?= htmlspecialchars($cert['brand']) ?>">
                                <label class="field-label">Current</label>
                                <input name="current[<?= $i ?>]" class="theme-input-style mb-2 tt" value="<?= htmlspecialchars($cert['current']) ?>">
                                <label class="field-label">Prod Spacing</label>
                                <input name="prod_spacing[<?= $i ?>]" class="theme-input-style mb-2 tt" value="<?= htmlspecialchars($cert['prod_spacing']) ?>">
                            </div>
                            <div class="col-lg-4">
                                <label class="field-label">Contrast Paint</label>
                                <input name="contrast_paint[<?= $i ?>]" class="theme-input-style mb-2 tt" value="<?= htmlspecialchars($cert['contrast_paint']) ?>">
                                <label class="field-label">Ink</label>
                                <input name="ink[<?= $i ?>]" class="theme-input-style mb-2 tt" value="<?= htmlspecialchars($cert['ink']) ?>">
                                <label class="field-label">Particle Medium</label>
                                <input name="particle_medium[<?= $i ?>]" class="theme-input-style mb-2 tt" value="<?= htmlspecialchars($cert['particle_medium']) ?>">
                            </div>
                            <div class="col-lg-4">
                                <label class="field-label">Yoke Serial No</label>
                                <input name="yoke_sn[<?= $i ?>]" class="theme-input-style mb-2 tt" value="<?= htmlspecialchars($cert['yoke_sn']) ?>">
                                <label class="field-label">Model No</label>
                                <input name="model_no[<?= $i ?>]" class="theme-input-style mb-2 tt" value="<?= htmlspecialchars($cert['model_no']) ?>">
                                <label class="field-label">Calibration Expiry Date</label>
                                <input type="date" name="calibration_expiry_date[<?= $i ?>]" class="theme-input-style mb-2 tt" value="<?= $cert['calibration_expiry_date'] ?>">
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-lg-6">
                                <div class="section-title">D. Images</div>
                                
                                <div class="img-preview d-flex flex-wrap mb-3">
                                <?php foreach ($images as $img): ?>
                                  <div class="img-box">
                                    <img src="../../uploads/mpi_certificates/<?= htmlspecialchars($img) ?>">
                                    <button type="button" class="remove-img" onclick="removeExistingImage('<?= $i ?>', '<?= htmlspecialchars($img) ?>', this)">×</button>
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
                                <select name="result[<?= $i ?>]" class="custom-select mb-2">
                                    <option value="">Select Result</option>
                                    <option value="PASS" <?= $cert['result']=='PASS'?'selected':'' ?>>PASS</option>
                                    <option value="FAIL" <?= $cert['result']=='FAIL'?'selected':'' ?>>FAIL</option>
                                </select>
                                <label class="field-label">Comments / Action Required</label>
                                <textarea name="comments[<?= $i ?>]" class="theme-input-style" rows="2"><?= htmlspecialchars($cert['comments']) ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Bottom Update Button -->
            <div class="text-center my-4">
                <button type="submit" name="update_mpi" class="btn btn-primary btn-lg">
                    💾 Save Changes
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Certificate Template for NEW Certificates -->
<template id="certTemplate">
    <div class="cert-card">
        <div class="cert-header">
            <strong class="cert-title font-20" style="font-size: 16px;"></strong>
            <div class="d-flex gap-3">
                <button type="button" class="btn btn-link toggle" onclick="toggleCard(this)">Toggle View</button>
                <button type="button" class="btn btn-link text-danger remove-new-cert">Remove</button>
            </div>
        </div>
        <div class="cert-body">
            <input type="hidden" class="cert-id-input">
            <input type="hidden" class="cert-no-input">
            
            <div class="row">
                <div class="col-lg-6">
                    <div class="section-title">A. General Information</div>
                    <label class="field-label">Location</label>
                    <input class="theme-input-style mb-2 loc-input">
                    <label class="field-label">Inspection Date</label>
                    <input type="date" class="theme-input-style mb-2 date-input">
                    <label class="field-label">Next Inspection Date</label>
                    <input type="date" class="theme-input-style mb-2 next-date-input">
                </div>
                <div class="col-lg-6">
                    <div class="section-title">B. Equipment Details</div>
                    <label class="field-label">Inspected Item</label>
                    <input class="theme-input-style mb-2 item-input">
                    <label class="field-label">Serial Numbers</label>
                    <input class="theme-input-style mb-2 sn-input">
                    <label class="field-label">Manufacturer / Equip No</label>
                    <input class="theme-input-style mb-2 mfr-input">
                    <label class="field-label">Applicable Standards</label>
                    <input class="theme-input-style mb-2 std-input" value="ASTM E 709 & BS EN 9934-1:2016">
                    <label class="field-label">SWL</label>
                    <input class="theme-input-style mb-2 swl-input">
                </div>
            </div>

            <div class="section-title">C. Testing Tools</div>
            <div class="row">
                <div class="col-lg-4">
                    <label class="field-label">MPI Equipment Type</label>
                    <input class="theme-input-style mb-2 tt-equip">
                    <label class="field-label">Brand</label>
                    <input class="theme-input-style mb-2 tt-brand">
                    <label class="field-label">Current</label>
                    <input class="theme-input-style mb-2 tt-curr">
                    <label class="field-label">Prod Spacing</label>
                    <input class="theme-input-style mb-2 tt-prod">
                </div>
                <div class="col-lg-4">
                    <label class="field-label">Contrast Paint</label>
                    <input class="theme-input-style mb-2 tt-paint">
                    <label class="field-label">Ink</label>
                    <input class="theme-input-style mb-2 tt-ink">
                    <label class="field-label">Particle Medium</label>
                    <input class="theme-input-style mb-2 tt-particle">
                </div>
                <div class="col-lg-4">
                    <label class="field-label">Yoke Serial No</label>
                    <input class="theme-input-style mb-2 tt-yoke">
                    <label class="field-label">Model No</label>
                    <input class="theme-input-style mb-2 tt-model">
                    <label class="field-label">Calibration Expiry Date</label>
                    <input type="date" class="theme-input-style mb-2 tt-cal">
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-lg-6">
                    <div class="section-title">D. Images</div>
                    <label class="field-label">Upload Inspection Images</label>
                    <input type="file" class="theme-input-style img-input" multiple>
                    <div class="img-preview-new d-flex flex-wrap mt-3"></div>
                </div>
                <div class="col-lg-6">
                    <div class="section-title">E. Result</div>
                    <label class="field-label">Result Status</label>
                    <select class="custom-select mb-2 res-input">
                        <option value="">Select Result</option>
                        <option value="PASS">PASS</option>
                        <option value="FAIL">FAIL</option>
                    </select>
                    <label class="field-label">Comments / Action Required</label>
                    <textarea class="theme-input-style com-input" rows="2"></textarea>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
const year = <?= $year ?>;
const project = "<?= htmlspecialchars($project_no) ?>";
let maxIndex = <?= $max_index ?>;
let cardCounter = <?= count($certificates) ?>;
const DEF_LOC = "<?= htmlspecialchars($header['location'] ?? '') ?>";
const DEF_INSP = "<?= htmlspecialchars($header['date_of_inspection'] ?? '') ?>";
const DEF_NEXT = "<?= htmlspecialchars($header['next_inspection_due_date'] ?? '') ?>";

function toggleCard(btn) {
    const body = btn.closest('.cert-card').querySelector('.cert-body');
    body.style.display = body.style.display === 'none' ? 'block' : 'none';
}

function collapseAll() {
    document.querySelectorAll('.cert-body').forEach(b => b.style.display = 'none');
}

function removeExistingImage(certIndex, imageName, btn) {
    if(!confirm('Are you sure you want to remove this image?')) return;
    const form = document.getElementById('editForm');
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'remove_images[' + certIndex + '][]';
    input.value = imageName;
    form.appendChild(input);
    btn.closest('.img-box').remove();
}

function removeExistingCert(btn, id) {
    if(!confirm('Are you sure you want to permanently remove this certificate? This action happens upon saving.')) return;
    const form = document.getElementById('editForm');
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'deleted_certs[]';
    input.value = id;
    form.appendChild(input);
    btn.closest('.cert-card').remove();
}

function addCertificate() {
    cardCounter++;
    maxIndex++;
    
    const tpl = document.getElementById('certTemplate').content.cloneNode(true);
    const card = tpl.querySelector('.cert-card');
    
    const certNo = `CMPI-${String(maxIndex).padStart(3,'0')}-${year}-${project}`;
    
    card.querySelector('.cert-title').innerText = certNo;
    
    // Set names dynamically
    card.querySelector('.cert-id-input').name = `cert_id[${cardCounter}]`;
    card.querySelector('.cert-id-input').value = ""; // empty ID = insert
    card.querySelector('.cert-no-input').name = `certificate_no[${cardCounter}]`;
    card.querySelector('.cert-no-input').value = certNo;
    
    card.querySelector('.loc-input').name = `location[${cardCounter}]`;
    card.querySelector('.loc-input').value = DEF_LOC;
    card.querySelector('.date-input').name = `inspection_date[${cardCounter}]`;
    card.querySelector('.date-input').value = DEF_INSP;
    card.querySelector('.next-date-input').name = `next_inspection_date[${cardCounter}]`;
    card.querySelector('.next-date-input').value = DEF_NEXT;
    
    card.querySelector('.item-input').name = `inspected_item[${cardCounter}]`;
    card.querySelector('.sn-input').name = `serial_numbers[${cardCounter}]`;
    card.querySelector('.mfr-input').name = `manufacturer[${cardCounter}]`;
    card.querySelector('.std-input').name = `standards[${cardCounter}]`;
    card.querySelector('.swl-input').name = `swl[${cardCounter}]`;
    
    card.querySelector('.tt-equip').name = `mpi_equip_type[${cardCounter}]`;
    card.querySelector('.tt-brand').name = `brand[${cardCounter}]`;
    card.querySelector('.tt-curr').name = `current[${cardCounter}]`;
    card.querySelector('.tt-prod').name = `prod_spacing[${cardCounter}]`;
    card.querySelector('.tt-paint').name = `contrast_paint[${cardCounter}]`;
    card.querySelector('.tt-ink').name = `ink[${cardCounter}]`;
    card.querySelector('.tt-particle').name = `particle_medium[${cardCounter}]`;
    card.querySelector('.tt-yoke').name = `yoke_sn[${cardCounter}]`;
    card.querySelector('.tt-model').name = `model_no[${cardCounter}]`;
    card.querySelector('.tt-cal').name = `calibration_expiry_date[${cardCounter}]`;
    
    const imgInput = card.querySelector('.img-input');
    imgInput.name = `new_images[${cardCounter}][]`;
    imgInput._files = [];
    
    card.querySelector('.res-input').name = `result[${cardCounter}]`;
    card.querySelector('.com-input').name = `comments[${cardCounter}]`;

    // Copy testing tools from first cert if exists
    const firstCert = document.querySelector('.cert-card:not(#certTemplate .cert-card)');
    if (firstCert) {
        card.querySelector('.tt-equip').value = firstCert.querySelector('[name^="mpi_equip_type"]').value || '';
        card.querySelector('.tt-brand').value = firstCert.querySelector('[name^="brand"]').value || '';
        card.querySelector('.tt-curr').value = firstCert.querySelector('[name^="current"]').value || '';
        card.querySelector('.tt-prod').value = firstCert.querySelector('[name^="prod_spacing"]').value || '';
        card.querySelector('.tt-paint').value = firstCert.querySelector('[name^="contrast_paint"]').value || '';
        card.querySelector('.tt-ink').value = firstCert.querySelector('[name^="ink"]').value || '';
        card.querySelector('.tt-particle').value = firstCert.querySelector('[name^="particle_medium"]').value || '';
        card.querySelector('.tt-yoke').value = firstCert.querySelector('[name^="yoke_sn"]').value || '';
        card.querySelector('.tt-model').value = firstCert.querySelector('[name^="model_no"]').value || '';
        card.querySelector('.tt-cal').value = firstCert.querySelector('[name^="calibration_expiry_date"]').value || '';
    }

    card.querySelector('.remove-new-cert').onclick = () => {
        card.remove();
    };

    document.getElementById('certContainer').appendChild(card);
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
  // Make sure it's an uploaded preview image, not an existing db image
  if (e.target.hasAttribute('onclick')) return; 
  
  const input = e.target.closest('.img-preview-new').previousElementSibling;
  input._files.splice(e.target.dataset.i, 1);
  const dt = new DataTransfer();
  input._files.forEach(f => dt.items.add(f));
  input.files = dt.files;
  renderPreview(input);
});
</script>

<?php include_once('../../inc/footer.php'); ?>
