<?php
include_once('../../inc/function.php');
include_once('../../file/config.php');

if (!isset($_GET['project_no'])) {
    die('Project No missing');
}

$project_no = $_GET['project_no'];

$stmt = $conn->prepare("
    SELECT 
        p.project_no,
        p.customer_name,
        p.customer_email,
        p.customer_mobile,
        p.inspector_name,
        r.report_no, r.jrn,
        r.location,
        r.date_of_inspection,
        r.next_inspection_due_date,
        r.created_at
    FROM project_info p
    LEFT JOIN reports r ON r.project_no = p.project_no
    WHERE p.project_no = ?
");
$stmt->bind_param("s", $project_no);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

$year = date('Y');
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
    <form action="save_mpi_certificate.php" method="POST" enctype="multipart/form-data">
        <div class="container-fluid">
            <!-- Sticky Header -->
            <div class="glass-header d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="font-20">MPI Certificate Builder</h1>
                    <div class="text-muted small mt-1">
                        <strong>Project:</strong> <?= htmlspecialchars($project_no) ?> |
                        <strong>Report:</strong> <?= htmlspecialchars($data['report_no'] ?? '-') ?>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="mr-3">
                        <span class="badge badge-primary p-2" style="border-radius: 10px;">Certificates: <span id="certCount">0</span></span>
                    </div>
                    <button type="submit" name="save_mpi" class="btn btn-success">Save All Certificates</button>
                </div>
            </div>

            <!-- Header Info -->
            <div class="form-element">
                <h4>General Header Data</h4>
                <div class="row">
                    <div class="col-md-3">
                        <label>Date of Report</label>
                        <input type="date" name="date_of_report" class="theme-input-style" value="<?= htmlspecialchars(!empty($data['created_at']) ? date('Y-m-d', strtotime($data['created_at'])) : '') ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label>Report No</label>
                        <input type="text" name="report_no" class="theme-input-style" value="<?= htmlspecialchars($data['report_no'] ?? '') ?>" readonly>
                    </div>
                    <div class="col-md-3">
                        <label>JRN</label>
                        <input type="text" name="jrn" value="<?php echo htmlspecialchars($data['jrn'] ?? ''); ?>"  class="theme-input-style" placeholder="Enter JRN" required>
                    </div>
                    <div class="col-md-3">
                        <label>Project No</label>
                        <input type="text" name="project_no" class="theme-input-style" value="<?= htmlspecialchars($project_no) ?>" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label>Customer Name</label>
                        <input type="text" name="customer_name" class="theme-input-style" value="<?= htmlspecialchars($data['customer_name'] ?? '') ?>" readonly>
                    </div>
                    <div class="col-md-4">
                        <label>Email</label>
                        <input type="email" name="customer_email" class="theme-input-style" value="<?= htmlspecialchars($data['customer_email'] ?? '') ?>" readonly>
                    </div>
                    <div class="col-md-4">
                        <label>Mobile</label>
                        <input type="text" name="mobile" class="theme-input-style" value="<?= htmlspecialchars($data['customer_mobile'] ?? '') ?>" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label>Inspector</label>
                        <input type="text" name="inspector" class="theme-input-style" value="<?= htmlspecialchars($data['inspector_name'] ?? '') ?>" readonly>
                    </div>
                    <div class="col-md-4">
                        <label>Technical Manager</label>
                        <select name="technical_manager" class="custom-select">
                            <option value="">Select</option>
                            <option>Venancio Z. Vera</option>
                            <option>Mohammed Fathy</option>
                            <option>Khaled A. Alghamdi</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Quality Controller</label>
                        <select name="quality_controller" class="custom-select">
                            <option value="">Select</option>
                            <option>Samuel Bhatti</option>
                            <option>Veera</option>
                            <option>Sathish</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Action Bar -->
            <div class="form-element text-center">
                <div class="d-flex justify-content-center align-items-center gap-2 flex-wrap">
                    <button type="button" class="btn btn-outline-primary" onclick="addBulk(1)">+1 Certificate</button>
                    <button type="button" class="btn btn-outline-primary" onclick="addBulk(10)">+10 Certificates</button>
                    <button type="button" class="btn btn-outline-primary" onclick="addBulk(50)">+50 Certificates</button>
                    <span class="mx-3 text-muted">or</span>
                    <input type="number" id="customCertCount" class="theme-input-style" style="width:100px;" min="1" max="500" placeholder="Qty">
                    <button type="button" class="btn btn-success" onclick="addCustom()">Add</button>
                    <button type="button" class="btn btn-outline-secondary ml-3" onclick="collapseAll()">Collapse All</button>
                </div>
            </div>

            <div id="certContainer"></div>
        </div>
    </form>
</div>

<!-- Certificate Template -->
<template id="certTemplate">
    <div class="cert-card">
        <div class="cert-header">
            <strong class="cert-title font-20" style="font-size: 16px;"></strong>
            <div class="d-flex gap-3">
                <button type="button" class="btn btn-link toggle">Toggle View</button>
                <button type="button" class="btn btn-link text-danger remove-cert">Remove</button>
            </div>
        </div>
        <div class="cert-body">
            <input type="hidden" name="certificate_no[]">
            
            <div class="row">
                <div class="col-lg-6">
                    <div class="section-title">A. General Information</div>
                    <label class="field-label">Location</label>
                    <input name="location[]" class="theme-input-style mb-2">
                    <label class="field-label">Inspection Date</label>
                    <input type="date" name="inspection_date[]" class="theme-input-style mb-2">
                    <label class="field-label">Next Inspection Date</label>
                    <input type="date" name="next_inspection_date[]" class="theme-input-style mb-2">
                </div>
                <div class="col-lg-6">
                    <div class="section-title">B. Equipment Details</div>
                    <label class="field-label">Inspected Item</label>
                    <input name="inspected_item[]" class="theme-input-style mb-2">
                    <label class="field-label">Serial Numbers</label>
                    <input name="serial_numbers[]" class="theme-input-style mb-2">
                    <label class="field-label">Manufacturer / Equip No</label>
                    <input name="manufacturer[]" class="theme-input-style mb-2">
                    <label class="field-label">Applicable Standards</label>
                    <input name="standards[]" class="theme-input-style mb-2" value="ASTM E 709 & BS EN 9934-1:2016">
                    <label class="field-label">SWL</label>
                    <input name="swl[]" class="theme-input-style mb-2">
                </div>
            </div>

            <div class="section-title">C. Testing Tools</div>
            <div class="row">
                <div class="col-lg-4">
                    <label class="field-label">MPI Equipment Type</label>
                    <input name="mpi_equip_type[]" class="theme-input-style mb-2 tt">
                    <label class="field-label">Brand</label>
                    <input name="brand[]" class="theme-input-style mb-2 tt">
                    <label class="field-label">Current</label>
                    <input name="current[]" class="theme-input-style mb-2 tt">
                    <label class="field-label">Prod Spacing</label>
                    <input name="prod_spacing[]" class="theme-input-style mb-2 tt">
                </div>
                <div class="col-lg-4">
                    <label class="field-label">Contrast Paint</label>
                    <input name="contrast_paint[]" class="theme-input-style mb-2 tt">
                    <label class="field-label">Ink</label>
                    <input name="ink[]" class="theme-input-style mb-2 tt">
                    <label class="field-label">Particle Medium</label>
                    <input name="particle_medium[]" class="theme-input-style mb-2 tt">
                </div>
                <div class="col-lg-4">
                    <label class="field-label">Yoke Serial No</label>
                    <input name="yoke_sn[]" class="theme-input-style mb-2 tt">
                    <label class="field-label">Model No</label>
                    <input name="model_no[]" class="theme-input-style mb-2 tt">
                    <label class="field-label">Calibration Expiry Date</label>
                    <input type="date" name="calibration_expiry_date[]" class="theme-input-style mb-2 tt">
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-lg-6">
                    <div class="section-title">D. Images</div>
                    <label class="field-label">Upload Inspection Images</label>
                    <input type="file" class="theme-input-style img-input" multiple>
                    <div class="img-preview d-flex flex-wrap mt-3"></div>
                </div>
                <div class="col-lg-6">
                    <div class="section-title">E. Result</div>
                    <label class="field-label">Result Status</label>
                    <select name="result[]" class="custom-select mb-2">
                        <option value="">Select Result</option>
                        <option value="PASS">PASS</option>
                        <option value="FAIL">FAIL</option>
                    </select>
                    <label class="field-label">Comments / Action Required</label>
                    <textarea name="comments[]" class="theme-input-style" rows="2"></textarea>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
let count = 0;
const year = <?= $year ?>;
const project = "<?= $project_no ?>";
const DEF_LOC = "<?= htmlspecialchars($data['location'] ?? '') ?>";
const DEF_INSP = "<?= htmlspecialchars($data['date_of_inspection'] ?? '') ?>";
const DEF_NEXT = "<?= htmlspecialchars($data['next_inspection_due_date'] ?? '') ?>";

function updateCount() {
  const el = document.getElementById('certCount');
  if (el) el.innerText = document.querySelectorAll('.cert-card').length;
}

function renderPreview(input) {
  const preview = input.nextElementSibling;
  if (!preview) return;
  preview.innerHTML = '';
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

function renumberCertificates() {
  const cards = document.querySelectorAll('.cert-card');
  cards.forEach((card, index) => {
    const newIndex = index + 1;
    const certNo = `CMPI-${String(newIndex).padStart(3,'0')}-${year}-${project}`;
    
    // Update Title
    card.querySelector('.cert-title').innerText = certNo;
    
    // Update Hidden Input
    card.querySelector('[name="certificate_no[]"]').value = certNo;
    
    // Update Image Input Name (Crucial for backend indexing)
    const imgInput = card.querySelector('.img-input');
    imgInput.name = `image[${index}][]`;
  });
  
  // Update the global count to reflect current state
  count = cards.length;
  updateCount();
}

function addCertificate() {
  const tpl = document.getElementById('certTemplate').content.cloneNode(true);
  const card = tpl.querySelector('.cert-card');

  // Image input initial setup
  const imgInput = card.querySelector('.img-input');
  imgInput._files = [];

  // Prefill general info
  card.querySelector('[name="location[]"]').value = DEF_LOC;
  card.querySelector('[name="inspection_date[]"]').value = DEF_INSP;
  card.querySelector('[name="next_inspection_date[]"]').value = DEF_NEXT;

  /* ================= COPY TESTING TOOLS ================= */
  const firstCert = document.querySelector('.cert-card');
  if (firstCert) {
    const sourceTT = firstCert.querySelectorAll('.tt');
    const targetTT = card.querySelectorAll('.tt');
    sourceTT.forEach((src, i) => {
      if (targetTT[i]) targetTT[i].value = src.value;
    });
  }

  // Toggle behavior
  card.querySelector('.toggle').onclick = () => {
    const body = card.querySelector('.cert-body');
    body.style.display = body.style.display === 'none' ? 'block' : 'none';
  };

  card.querySelector('.remove-cert').onclick = () => {
    card.remove();
    renumberCertificates();
  };

  document.getElementById('certContainer').appendChild(card);
  renumberCertificates(); // Triggers numbering for the new card
}

function addBulk(n){ for(let i=0;i<n;i++) addCertificate(); }
function addCustom(){
  const inp = document.getElementById('customCertCount');
  const n = parseInt(inp.value, 10);
  if (!n || n < 1) { alert('Please enter a valid number.'); inp.focus(); return; }
  if (n > 500) { alert('Maximum 500 at a time.'); inp.focus(); return; }
  addBulk(n);
  inp.value = '';
}
function collapseAll(){
  document.querySelectorAll('.cert-body').forEach(b=>b.style.display='none');
}

/* IMAGE HANDLING */
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
  const input = e.target.closest('.img-preview').previousElementSibling;
  input._files.splice(e.target.dataset.i, 1);
  const dt = new DataTransfer();
  input._files.forEach(f => dt.items.add(f));
  input.files = dt.files;
  renderPreview(input);
});
</script>

<?php include_once('../../inc/footer.php'); ?>
