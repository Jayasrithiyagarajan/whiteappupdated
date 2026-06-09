<?php
session_start();
include_once('../inc/function.php');

// Get assessment ID from URL
$assessment_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($assessment_id === 0) {
    $_SESSION['error_msg'] = "Invalid assessment ID";
    header("Location: assessment-list.php");
    exit();
}

// Fetch assessment details
$sql = "SELECT 
            oa.*,
            c.customer_name as client_name,
            nu.username as inspector_name
        FROM operator_assessments oa
        LEFT JOIN customers c ON oa.client_id = c.cus_id
        LEFT JOIN new_users nu ON oa.inspector_id = nu.user_id
        WHERE oa.id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $assessment_id);
$stmt->execute();
$result = $stmt->get_result();
$assessment = $result->fetch_assoc();

if (!$assessment) {
    $_SESSION['error_msg'] = "Assessment not found";
    header("Location: assessment-list.php");
    exit();
}

// Fetch existing equipment if any
$equipment_sql = "SELECT * FROM operator_equipment WHERE assessment_id = ? ORDER BY equipment_number ASC";
$equipment_stmt = $conn->prepare($equipment_sql);
$equipment_stmt->bind_param("i", $assessment_id);
$equipment_stmt->execute();
$equipment_result = $equipment_stmt->get_result();
$existing_equipment = [];
while ($eq = $equipment_result->fetch_assoc()) {
    $existing_equipment[$eq['equipment_number']] = $eq;
}

// Fetch existing documents
$docs_sql = "SELECT * FROM operator_documents WHERE assessment_id = ?";
$docs_stmt = $conn->prepare($docs_sql);
$docs_stmt->bind_param("i", $assessment_id);
$docs_stmt->execute();
$docs_result = $docs_stmt->get_result();
$existing_docs = [];
while ($doc = $docs_result->fetch_assoc()) {
    $existing_docs[$doc['document_type']] = $doc;
}
?>

<!-- Main Content -->
<div class="main-content">
    <div class="container-fluid">
        <div class="card bg-transparent pb-3">
            <div class="card-body bg-white">
                <div class="row">
                    <div class="col-6">
                        <h4 class="pl-2 pt-3 pb-2 font-20">Fill Operator Assessment Details</h4>
                    </div>
                    <div class="col-6 text-right">
                        <a href="./assessment-list.php" class="btn btn-secondary">Back to List</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <form action="update-assessment.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="assessment_id" value="<?php echo $assessment_id; ?>">
            
            <!-- Read-only Admin Fields -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Assessment Information (Created by Admin)</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label class="font-14 bold mb-2">Assessment No</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($assessment['assessment_no']); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label class="font-14 bold mb-2">Date</label>
                                <input type="text" class="form-control" value="<?php echo date('d-M-Y', strtotime($assessment['date'])); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label class="font-14 bold mb-2">Operator Name</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($assessment['operator_name']); ?>" readonly>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label class="font-14 bold mb-2">Client</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($assessment['client_name']); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label class="font-14 bold mb-2">Location</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($assessment['location']); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label class="font-14 bold mb-2">Operating Location</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($assessment['operating_location']); ?>" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Inspector Fillable Fields -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Operator Details (To be filled by Inspector)</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label class="font-14 bold mb-2">Name (As per IQAMA or Passport)</label>
                                <input type="text" class="form-control" name="operator_name_confirm" 
                                       value="<?php echo htmlspecialchars($assessment['operator_name']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label class="font-14 bold mb-2">Saudi Arabia Heavy Equipment License No</label>
                                <input type="text" class="form-control" name="license_number" 
                                       value="<?php echo htmlspecialchars($assessment['license_number'] ?? ''); ?>" required>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label class="font-14 bold mb-2">IQAMA or Passport No</label>
                                <input type="text" class="form-control" name="operator_id_passport_confirm" 
                                       value="<?php echo htmlspecialchars($assessment['operator_id_passport']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label class="font-14 bold mb-2">Training Program</label>
                                <input type="text" class="form-control" name="training_program" 
                                       value="<?php echo htmlspecialchars($assessment['training_program'] ?? ''); ?>" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Equipment Details -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Equipment Details</h5>
                    <div class="d-flex align-items-center">
                        <label class="mb-0 mr-2 text-white bold font-14">No. of Equipment:</label>
                        <select name="no_of_equipment" id="no_of_equipment" class="form-control form-control-sm" style="width: 70px;">
                            <?php for ($n = 1; $n <= 5; $n++): ?>
                                <option value="<?php echo $n; ?>" <?php echo ($assessment['no_of_equipment'] == $n) ? 'selected' : ''; ?>>
                                    <?php echo $n; ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <?php for ($i = 1; $i <= 5; $i++): 
                        $isVisible = ($i <= ($assessment['no_of_equipment'] ?: 1));
                    ?>
                        <div class="equipment-section mb-4 p-3 equipment-row-<?php echo $i; ?> <?php echo $isVisible ? '' : 'd-none'; ?>" 
                             style="border: 1px solid #dee2e6; border-radius: 5px;" data-index="<?php echo $i; ?>">
                            <h6 class="font-weight-bold mb-3">Equipment <?php echo $i; ?></h6>
                            <div class="row">
                                <div class="col-lg-3 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label class="font-14 bold mb-2">Equipment Type</label>
                                        <input type="text" class="form-control" name="equipment[<?php echo $i; ?>][equipment_type]" 
                                               value="<?php echo htmlspecialchars($existing_equipment[$i]['equipment_type'] ?? ''); ?>" 
                                               placeholder="Enter Equipment Type" required>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label class="font-14 bold mb-2">Manufacturer</label>
                                        <input type="text" class="form-control" name="equipment[<?php echo $i; ?>][manufacturer]" 
                                               value="<?php echo htmlspecialchars($existing_equipment[$i]['manufacturer'] ?? ''); ?>" 
                                               placeholder="Enter Manufacturer" required>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label class="font-14 bold mb-2">Model</label>
                                        <input type="text" class="form-control" name="equipment[<?php echo $i; ?>][model]" 
                                               value="<?php echo htmlspecialchars($existing_equipment[$i]['model'] ?? ''); ?>" 
                                               placeholder="Enter Model" required>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label class="font-14 bold mb-2">Capacity</label>
                                        <input type="text" class="form-control" name="equipment[<?php echo $i; ?>][capacity]" 
                                               value="<?php echo htmlspecialchars($existing_equipment[$i]['capacity'] ?? ''); ?>" 
                                               placeholder="Enter Capacity" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>

            <!-- File Uploads -->
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">Document Uploads</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label class="font-14 bold mb-2">1. IQAMA or Passport <span class="text-danger">*</span></label>
                                <input type="file" class="form-control" name="doc_iqama_passport" accept="image/*,application/pdf">
                                <?php if (isset($existing_docs['IQAMA_PASSPORT'])): ?>
                                    <small class="text-success">✓ File uploaded</small>
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label class="font-14 bold mb-2">2. Saudi Arabia Heavy Equipment License <span class="text-danger">*</span></label>
                                <input type="file" class="form-control" name="doc_license" accept="image/*,application/pdf">
                                <?php if (isset($existing_docs['LICENSE'])): ?>
                                    <small class="text-success">✓ File uploaded</small>
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label class="font-14 bold mb-2">3. Photo (Upload or Capture) <span class="text-danger">*</span></label>
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <input type="file" class="form-control" name="doc_photo" id="doc_photo" accept="image/*" capture="camera">
                                    <button type="button" class="btn btn-primary ml-2" id="startCamera" data-toggle="modal" data-target="#cameraModal">
                                        <i class="fas fa-camera"></i> Capture
                                    </button>
                                </div>
                                <input type="hidden" name="captured_photo" id="captured_photo">
                                <div id="photoPreview" class="mt-2 d-none">
                                    <img src="" id="capturedImage" class="img-thumbnail" style="max-height: 150px;">
                                    <button type="button" class="btn btn-sm btn-danger ml-2" id="removePhoto">Remove</button>
                                </div>
                                <?php if (isset($existing_docs['PHOTO'])): ?>
                                    <small class="text-success">✓ File uploaded</small>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label class="font-14 bold mb-2">4. Medical Certificates <span class="text-danger">*</span></label>
                                <input type="file" class="form-control" name="doc_medical" accept="image/*,application/pdf">
                                <?php if (isset($existing_docs['MEDICAL_CERT'])): ?>
                                    <small class="text-success">✓ File uploaded</small>
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label class="font-14 bold mb-2">5. Previous Operator Certificate (Optional)</label>
                                <input type="file" class="form-control" name="doc_previous_cert" accept="image/*,application/pdf">
                                <?php if (isset($existing_docs['PREVIOUS_CERT'])): ?>
                                    <small class="text-success">✓ File uploaded</small>
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label class="font-14 bold mb-2">6. Additional File Uploads (Optional)</label>
                                <input type="file" class="form-control" name="doc_additional" accept="image/*,application/pdf">
                                <?php if (isset($existing_docs['ADDITIONAL'])): ?>
                                    <small class="text-success">✓ File uploaded</small>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assessment Dates -->
            <div class="card mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">Assessment Dates</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label class="font-14 bold mb-2">Date of Assessment <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="date_of_assessment" 
                                       value="<?php echo $assessment['date_of_assessment'] ?? date('Y-m-d'); ?>" required>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label class="font-14 bold mb-2">Date of Expiry <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="date_of_expiry" 
                                       value="<?php echo $assessment['date_of_expiry'] ?? ''; ?>" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="form-row">
                <div class="col-12 text-center mt-4 mb-5">
                    <button type="submit" class="btn btn-success btn-lg" name="save_assessment">
                        <i class="fas fa-save"></i> Save Assessment
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Camera Modal -->
<div class="modal fade" id="cameraModal" tabindex="-1" role="dialog" aria-labelledby="cameraModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cameraModalLabel">Capture Operator Photo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <div id="cameraWrapper" class="mb-3">
                    <video id="video" width="100%" height="auto" autoplay playsinline class="rounded border"></video>
                    <canvas id="canvas" class="d-none"></canvas>
                </div>
                <div id="previewWrapper" class="mb-3 d-none">
                    <img id="capturePreview" class="img-fluid rounded border">
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" id="retakePhoto" style="display: none;">Retake</button>
                <button type="button" class="btn btn-primary" id="captureBtn">Take Snapshot</button>
                <button type="button" class="btn btn-success" id="usePhoto" style="display: none;">Use This Photo</button>
            </div>
        </div>
    </div>
</div>

<?php include_once('../inc/footer.php'); ?>

<style>
.card-header {
    font-weight: bold;
}

.equipment-section {
    background-color: #f8f9fa;
}

.form-control:read-only {
    background-color: #e9ecef;
    cursor: not-allowed;
}

.text-danger {
    color: #dc3545;
}

.text-success {
    color: #28a745;
    font-weight: bold;
}
</style>

<script>
document.querySelector("form").addEventListener("submit", function (event) {
    let valid = true;

    // Validate required fields
    document.querySelectorAll("input[required], select[required]").forEach((input) => {
        if (!input.value.trim() && !document.getElementById('captured_photo').value) {
            valid = false;
            input.style.border = "2px solid red";
        } else {
            input.style.border = "";
        }
    });

    if (!valid) {
        event.preventDefault();
        alert("Please fill out all required fields!");
    }
});

// Camera Capture Logic
let stream = null;
const video = document.getElementById('video');
const canvas = document.getElementById('canvas');
const captureBtn = document.getElementById('captureBtn');
const retakeBtn = document.getElementById('retakePhoto');
const useBtn = document.getElementById('usePhoto');
const cameraWrapper = document.getElementById('cameraWrapper');
const previewWrapper = document.getElementById('previewWrapper');
const capturePreview = document.getElementById('capturePreview');
const capturedInput = document.getElementById('captured_photo');
const photoPreview = document.getElementById('photoPreview');
const capturedImage = document.getElementById('capturedImage');
const removePhotoBtn = document.getElementById('removePhoto');
const fileInput = document.getElementById('doc_photo');

$('#cameraModal').on('shown.bs.modal', function () {
    startCamera();
});

$('#cameraModal').on('hidden.bs.modal', function () {
    stopCamera();
    resetCameraUI();
});

async function startCamera() {
    try {
        stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" }, audio: false });
        video.srcObject = stream;
    } catch (err) {
        console.error("Error accessing camera: ", err);
        alert("Could not access camera. Please check permissions.");
        $('#cameraModal').modal('hide');
    }
}

function stopCamera() {
    if (stream) {
        stream.getTracks().forEach(track => track.stop());
    }
}

function resetCameraUI() {
    cameraWrapper.classList.remove('d-none');
    previewWrapper.classList.add('d-none');
    captureBtn.style.display = 'inline-block';
    retakeBtn.style.display = 'none';
    useBtn.style.display = 'none';
}

captureBtn.addEventListener('click', () => {
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    canvas.getContext('2d').drawImage(video, 0, 0);
    
    const dataUrl = canvas.toDataURL('image/jpeg', 0.8);
    capturePreview.src = dataUrl;
    
    cameraWrapper.classList.add('d-none');
    previewWrapper.classList.remove('d-none');
    captureBtn.style.display = 'none';
    retakeBtn.style.display = 'inline-block';
    useBtn.style.display = 'inline-block';
});

retakeBtn.addEventListener('click', () => {
    resetCameraUI();
});

useBtn.addEventListener('click', () => {
    const dataUrl = capturePreview.src;
    capturedInput.value = dataUrl;
    capturedImage.src = dataUrl;
    photoPreview.classList.remove('d-none');
    fileInput.value = ''; // Clear file input if a photo is captured
    $('#cameraModal').modal('hide');
});

removePhotoBtn.addEventListener('click', () => {
    capturedInput.value = '';
    photoPreview.classList.add('d-none');
});

// Equipment dynamic rows logic
const eqCountSelector = document.getElementById('no_of_equipment');
eqCountSelector.addEventListener('change', function() {
    const count = parseInt(this.value);
    updateEquipmentRows(count);
});

function updateEquipmentRows(count) {
    document.querySelectorAll('[class*="equipment-row-"]').forEach(row => {
        const index = parseInt(row.getAttribute('data-index'));
        if (index <= count) {
            row.classList.remove('d-none');
            row.querySelectorAll('input').forEach(input => input.setAttribute('required', 'required'));
        } else {
            row.classList.add('d-none');
            row.querySelectorAll('input').forEach(input => input.removeAttribute('required'));
        }
    });
}

// Set initial required states
updateEquipmentRows(parseInt(eqCountSelector.value));

fileInput.addEventListener('change', () => {
    if (fileInput.value) {
        capturedInput.value = '';
        photoPreview.classList.add('d-none');
    }
});
</script>
