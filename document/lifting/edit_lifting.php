<?php
include_once('../../inc/function.php');
include_once('../../file/config.php');

if (!isset($_GET['project_no']) || empty($_GET['project_no'])) {
    die("Invalid or missing project ID.");
}

$project_no = $_GET['project_no'];

$query = "
SELECT 
    p.project_no,
    p.customer_name,
    p.customer_email,
    p.customer_mobile,
    p.inspector_name,
    r.report_no,
    c.city
FROM project_info p
LEFT JOIN reports r ON r.project_no = p.project_no
LEFT JOIN customers c ON c.customer_name = p.customer_name
WHERE p.project_no = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $project_no);
$stmt->execute();
$projectData = $stmt->get_result()->fetch_assoc();

if (!$projectData) {
    die("No data found for the given project ID.");
}

$certificateQuery = "
SELECT *
FROM lifting_gear_certificates
WHERE project_no = ?
ORDER BY id ASC
";
$certificateStmt = $conn->prepare($certificateQuery);
$certificateStmt->bind_param("s", $project_no);
$certificateStmt->execute();
$certificateResult = $certificateStmt->get_result();

$certificates = [];
while ($row = $certificateResult->fetch_assoc()) {
    $certificates[] = $row;
}

if (empty($certificates)) {
    die("No lifting gear certificates found for this project.");
}

$firstCertificate = $certificates[0];
$currentYear = date('Y');
?>

<style>
.lifting-page {
    --lifting-accent: #4285f4;
    --lifting-accent-soft: rgba(66, 133, 244, 0.08);
    --lifting-border: #e6ebf2;
    --lifting-muted: #6b7280;
}

.lifting-hero {
    background: linear-gradient(135deg, rgba(66, 133, 244, 0.08), rgba(40, 167, 69, 0.06));
    border: 1px solid var(--lifting-border);
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 30px;
}

.lifting-hero-title {
    font-size: 30px;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 8px;
}

.lifting-hero-copy {
    color: var(--lifting-muted);
    margin-bottom: 16px;
}

.lifting-chip-group {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.lifting-chip {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 14px;
    border-radius: 999px;
    background: #fff;
    border: 1px solid var(--lifting-border);
    color: #495057;
    font-weight: 600;
}

.lifting-summary {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 16px;
}

.lifting-summary-card {
    border: 1px solid var(--lifting-border);
    border-radius: 12px;
    padding: 16px 18px;
    background: #fff;
}

.lifting-summary-label {
    font-size: 13px;
    font-weight: 600;
    color: var(--lifting-muted);
    margin-bottom: 6px;
}

.lifting-summary-value {
    font-size: 24px;
    font-weight: 700;
    color: #1f2937;
    line-height: 1.1;
}

.lifting-section-note {
    color: var(--lifting-muted);
    font-size: 13px;
    margin-top: 6px;
}

.lifting-table-shell {
    border: 1px solid var(--lifting-border);
    border-radius: 12px;
    overflow: hidden;
    background: #fff;
}

.lifting-toolbar {
    padding: 20px;
    border-bottom: 1px solid var(--lifting-border);
    background: #f8fafc;
}

.lifting-toolbar .theme-input-style {
    min-height: 44px;
}

.lifting-toolbar .btn {
    min-height: 44px;
}

.lifting-inline-note {
    color: var(--lifting-muted);
    font-size: 13px;
}

.lifting-table {
    margin-bottom: 0;
}

.lifting-table thead th {
    background: #f1f5f9;
    color: #374151;
    font-size: 12px;
    font-weight: 700;
    white-space: nowrap;
    border-color: var(--lifting-border);
}

.lifting-table td {
    border-color: var(--lifting-border);
    vertical-align: top;
}

.lifting-table .theme-input-style {
    min-width: 110px;
}

.lifting-table textarea.theme-input-style {
    min-height: 74px;
    resize: vertical;
}

.lifting-row-no {
    font-weight: 700;
    color: var(--lifting-accent);
}

.lifting-cert-preview {
    font-size: 12px;
    color: var(--lifting-muted);
    margin-top: 6px;
}

.lifting-save-bar {
    position: sticky;
    bottom: 20px;
    z-index: 5;
    padding: 16px 20px;
    border: 1px solid var(--lifting-border);
    border-radius: 12px;
    background: #fff;
    box-shadow: 0 12px 32px rgba(15, 23, 42, 0.08);
}

.lifting-save-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 7px 14px;
    border-radius: 999px;
    background: var(--lifting-accent-soft);
    color: #1d4ed8;
    font-weight: 600;
    margin-bottom: 8px;
}

.lifting-save-note {
    color: var(--lifting-muted);
    margin-bottom: 0;
}

@media (max-width: 767px) {
    .lifting-table thead {
        display: none;
    }

    .lifting-table,
    .lifting-table tbody,
    .lifting-table tr,
    .lifting-table td {
        display: block;
        width: 100%;
    }

    .lifting-table tr {
        margin-bottom: 16px;
        border-bottom: 1px solid var(--lifting-border);
    }

    .lifting-table td {
        border: none;
        padding: 10px 12px;
    }

    .lifting-table td::before {
        content: attr(data-label);
        display: block;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--lifting-muted);
        margin-bottom: 6px;
    }

    .lifting-save-bar {
        position: static;
    }
}
</style>

<div class="main-content lifting-page">
    <div class="container-fluid">
        <div class="card bg-transparent pb-3">
            <div class="card-body bg-white">
                <div class="row align-items-center">
                    <div class="col-6">
                        <h4 class="pl-2 pt-3 pb-2 font-20">EDIT LIFTING GEAR CERTIFICATE</h4>
                    </div>
                    <div class="col-6 text-right">
                        <a href="index.php" class="btn btn-primary" target="_blank">View List</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <form method="POST" action="update_form.php" id="liftingCertificateEditForm">
            <input type="hidden" name="project_no" value="<?= htmlspecialchars($projectData['project_no']) ?>">
            <input type="hidden" name="certificate_rows_json" id="certificateRowsJson">

            <div class="lifting-hero">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <div class="lifting-hero-title">Edit Certificates Faster</div>
                        <p class="lifting-hero-copy">
                            Same theme and workflow as the create page, with all existing certificate data loaded for quick bulk editing.
                        </p>
                        <div class="lifting-chip-group">
                            <div class="lifting-chip"><i class="fa fa-pen"></i> Edit existing rows</div>
                            <div class="lifting-chip"><i class="fa fa-copy"></i> Clone last row</div>
                            <div class="lifting-chip"><i class="fa fa-shield-alt"></i> Safe large-batch update</div>
                        </div>
                    </div>
                    <div class="col-lg-4 mt-4 mt-lg-0">
                        <div class="lifting-summary">
                            <div class="lifting-summary-card">
                                <div class="lifting-summary-label">Project No</div>
                                <div class="lifting-summary-value"><?= htmlspecialchars($projectData['project_no']) ?></div>
                            </div>
                            <div class="lifting-summary-card">
                                <div class="lifting-summary-label">Rows Loaded</div>
                                <div class="lifting-summary-value" id="certificateCount"><?= count($certificates) ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="form-element py-30 mb-30">
                        <h4 class="font-20 mb-20">Header Data</h4>
                        <div class="lifting-section-note mb-20">Shared information applied to all certificates in this project.</div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label class="font-14 bold mb-2">Date of Report</label>
                                <input type="date" name="date_of_report" class="theme-input-style" value="<?= htmlspecialchars($firstCertificate['date_of_report'] ?? '') ?>" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-14 bold mb-2">Report No</label>
                                <input type="text" name="report_no" class="theme-input-style" value="<?= htmlspecialchars($firstCertificate['report_no'] ?? $projectData['report_no']) ?>" readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-14 bold mb-2">Project No</label>
                                <input type="text" class="theme-input-style" value="<?= htmlspecialchars($projectData['project_no']) ?>" readonly>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label class="font-14 bold mb-2">JRN</label>
                                <input type="text" name="jrn" class="theme-input-style" value="<?= htmlspecialchars($firstCertificate['jrn'] ?? '') ?>" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-14 bold mb-2">Color Code</label>
                                <input type="text" name="color_code" class="theme-input-style" value="<?= htmlspecialchars($firstCertificate['color_code'] ?? '') ?>">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-14 bold mb-2">Applicable Standards</label>
                                <select name="applicable_standards" class="theme-input-style" required>
                                    <option value="">Select standard</option>
                                    <option value="ASME B30.9" <?= ($firstCertificate['applicable_standards'] ?? '') === 'ASME B30.9' ? 'selected' : '' ?>>ASME B30.9</option>
                                    <option value="ASME B30.26" <?= ($firstCertificate['applicable_standards'] ?? '') === 'ASME B30.26' ? 'selected' : '' ?>>ASME B30.26</option>
                                    <option value="ASME B30.20" <?= ($firstCertificate['applicable_standards'] ?? '') === 'ASME B30.20' ? 'selected' : '' ?>>ASME B30.20</option>
                                    <option value="ASME B30.10" <?= ($firstCertificate['applicable_standards'] ?? '') === 'ASME B30.10' ? 'selected' : '' ?>>ASME B30.10</option>
                                    <option value="ASME B30.30" <?= ($firstCertificate['applicable_standards'] ?? '') === 'ASME B30.30' ? 'selected' : '' ?>>ASME B30.30</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="font-14 bold mb-2">Employer Name &amp; Address</label>
                                <input type="text" name="employer_name_address" class="theme-input-style" value="<?= htmlspecialchars($firstCertificate['employer_name_address'] ?? $projectData['customer_name']) ?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="font-14 bold mb-2">Address of Premises</label>
                                <input type="text" name="address_of_premises" class="theme-input-style" value="<?= htmlspecialchars($firstCertificate['address_of_premises'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label class="font-14 bold mb-2">Date of This Examination</label>
                                <input type="date" name="date_of_this_examination" class="theme-input-style" value="<?= htmlspecialchars($firstCertificate['date_of_this_examination'] ?? '') ?>">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-14 bold mb-2">Next Examination Date</label>
                                <input type="date" name="next_examination_date" class="theme-input-style" value="<?= htmlspecialchars($firstCertificate['next_examination_date'] ?? '') ?>">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="font-14 bold mb-2">Reason for Examination</label>
                                <select name="reason_for_examination" class="theme-input-style" required>
                                    <option value="">Select reason</option>
                                    <option value="A" <?= ($firstCertificate['reason_for_examination'] ?? '') === 'A' ? 'selected' : '' ?>>A</option>
                                    <option value="B" <?= ($firstCertificate['reason_for_examination'] ?? '') === 'B' ? 'selected' : '' ?>>B</option>
                                    <option value="C" <?= ($firstCertificate['reason_for_examination'] ?? '') === 'C' ? 'selected' : '' ?>>C</option>
                                    <option value="D" <?= ($firstCertificate['reason_for_examination'] ?? '') === 'D' ? 'selected' : '' ?>>D</option>
                                    <option value="E" <?= ($firstCertificate['reason_for_examination'] ?? '') === 'E' ? 'selected' : '' ?>>E</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-element py-30 mb-30">
                        <h4 class="font-20 mb-20">Customer Information / Inspector</h4>
                        <div class="lifting-section-note mb-20">Project and approval details for the update batch.</div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="font-14 bold mb-2">Customer Name</label>
                                <input type="text" name="customer_name" class="theme-input-style" value="<?= htmlspecialchars($firstCertificate['customer_name'] ?? $projectData['customer_name']) ?>" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="font-14 bold mb-2">Email</label>
                                <input type="email" name="customer_email" class="theme-input-style" value="<?= htmlspecialchars($firstCertificate['customer_email'] ?? $projectData['customer_email']) ?>" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="font-14 bold mb-2">Mobile</label>
                                <input type="text" name="mobile" class="theme-input-style" value="<?= htmlspecialchars($firstCertificate['mobile'] ?? $projectData['customer_mobile']) ?>" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="font-14 bold mb-2">Inspector</label>
                                <input type="text" name="inspector" class="theme-input-style" value="<?= htmlspecialchars($firstCertificate['inspector'] ?? $projectData['inspector_name']) ?>" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="font-14 bold mb-2">Technical Manager</label>
                                <select name="technical_manager" class="theme-input-style" required>
                                    <option value="">Select technical manager</option>
                                    <option value="Venancio Z. Vera" <?= ($firstCertificate['technical_manager'] ?? '') === 'Venancio Z. Vera' ? 'selected' : '' ?>>Venancio Z. Vera</option>
                                    <option value="Mohammed Fathy" <?= ($firstCertificate['technical_manager'] ?? '') === 'Mohammed Fathy' ? 'selected' : '' ?>>Mohammed Fathy</option>
                                    <option value="Khaled A. Alghamdi" <?= ($firstCertificate['technical_manager'] ?? '') === 'Khaled A. Alghamdi' ? 'selected' : '' ?>>Khaled A. Alghamdi</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="font-14 bold mb-2">Quality Controller</label>
                                <select name="quality_controller" class="theme-input-style" required>
                                    <option value="">Select quality controller</option>
                                    <option value="Samuel Bhatti" <?= ($firstCertificate['quality_controller'] ?? '') === 'Samuel Bhatti' ? 'selected' : '' ?>>Samuel Bhatti</option>
                                    <option value="Veera" <?= ($firstCertificate['quality_controller'] ?? '') === 'Veera' ? 'selected' : '' ?>>Veera</option>
                                    <option value="Sathish" <?= ($firstCertificate['quality_controller'] ?? '') === 'Sathish' ? 'selected' : '' ?>>Sathish</option>
                                </select>
                            </div>
                        </div>

                        <div class="lifting-summary mt-3">
                            <div class="lifting-summary-card">
                                <div class="lifting-summary-label">Report No</div>
                                <div class="lifting-summary-value"><?= htmlspecialchars($firstCertificate['report_no'] ?? $projectData['report_no']) ?></div>
                            </div>
                            <div class="lifting-summary-card">
                                <div class="lifting-summary-label">Current Rows</div>
                                <div class="lifting-summary-value" id="summaryRowCount"><?= count($certificates) ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-element py-30 mb-30">
                <h4 class="font-20 mb-20">Certificate Details</h4>
                <div class="lifting-section-note mb-20">
                    Edit the current certificates, add more rows, or clone the last edited row for fast updates.
                </div>

                <div class="lifting-table-shell">
                    <div class="lifting-toolbar">
                        <div class="row align-items-end">
                            <div class="col-md-2 col-sm-6 mb-15">
                                <label class="font-14 bold mb-2">Rows</label>
                                <input type="number" class="theme-input-style" id="bulkCount" value="1" min="1" max="100">
                            </div>
                            <div class="col-md-3 col-sm-6 mb-15">
                                <label class="font-14 bold mb-2">Mode</label>
                                <select class="theme-input-style" id="bulkMode">
                                    <option value="clone">Clone last row</option>
                                    <option value="blank">Add blank rows</option>
                                </select>
                            </div>
                            <div class="col-md-7 mb-15 d-flex flex-wrap align-items-center">
                                <button type="button" id="bulkAddRows" class="btn btn-primary mr-2 mb-2">
                                    <i class="fa fa-layer-group mr-1"></i>Add Rows
                                </button>
                                <button type="button" id="addSingleRow" class="btn mr-2 mb-2" style="background:#eef4ff;color:#2c5cc5;">
                                    <i class="fa fa-plus mr-1"></i>Add One
                                </button>
                                <span class="lifting-inline-note mb-2">Fill one row, then clone it if the next certificates are similar.</span>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered lifting-table" id="certificateTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Certificate No</th>
                                    <th>Identification No</th>
                                    <th>Description</th>
                                    <th>WLL/SWL</th>
                                    <th>Qty</th>
                                    <th>Type</th>
                                    <th>Date Last Exam</th>
                                    <th>Test Details</th>
                                    <th>Status</th>
                                    <th>Safe</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($certificates as $index => $gear): ?>
                                    <tr>
                                        <td data-label="#" class="lifting-row-no"><?= $index + 1 ?></td>
                                        <td data-label="Certificate No">
                                            <input type="text" class="theme-input-style certificate-number" data-field="certificate_no" value="<?= htmlspecialchars($gear['certificate_no'] ?? '') ?>" readonly>
                                            <div class="lifting-cert-preview">Auto-generated from project and row number.</div>
                                        </td>
                                        <td data-label="Identification No">
                                            <textarea class="theme-input-style" data-field="identification_no" placeholder="Serial number or identification"><?= htmlspecialchars($gear['identification_no'] ?? '') ?></textarea>
                                        </td>
                                        <td data-label="Description">
                                            <textarea class="theme-input-style" data-field="description" placeholder="Equipment description"><?= htmlspecialchars($gear['description'] ?? '') ?></textarea>
                                        </td>
                                        <td data-label="WLL/SWL">
                                            <input type="text" class="theme-input-style" data-field="wll_swl" value="<?= htmlspecialchars($gear['wll_swl'] ?? '') ?>" placeholder="Example: 2T">
                                        </td>
                                        <td data-label="Qty">
                                            <input type="number" class="theme-input-style" data-field="qty" min="1" value="<?= htmlspecialchars($gear['qty'] ?? '') ?>" placeholder="1">
                                        </td>
                                        <td data-label="Type">
                                            <input type="text" class="theme-input-style" data-field="type" value="<?= htmlspecialchars($gear['type'] ?? '') ?>" placeholder="Sling, hook, shackle">
                                        </td>
                                        <td data-label="Date Last Exam">
                                            <input type="date" class="theme-input-style" data-field="date_last_examination" value="<?= htmlspecialchars($gear['date_last_examination'] ?? '') ?>">
                                        </td>
                                        <td data-label="Test Details">
                                            <textarea class="theme-input-style" data-field="test_details" placeholder="Applied test details"><?= htmlspecialchars($gear['test_details'] ?? '') ?></textarea>
                                        </td>
                                        <td data-label="Status">
                                            <select class="theme-input-style" data-field="status">
                                                <option value="">--</option>
                                                <option value="ND" <?= ($gear['status'] ?? '') === 'ND' ? 'selected' : '' ?>>ND</option>
                                                <option value="SDR" <?= ($gear['status'] ?? '') === 'SDR' ? 'selected' : '' ?>>SDR</option>
                                                <option value="NF" <?= ($gear['status'] ?? '') === 'NF' ? 'selected' : '' ?>>NF</option>
                                            </select>
                                        </td>
                                        <td data-label="Safe">
                                            <select class="theme-input-style" data-field="safe_to_use">
                                                <option value="">--</option>
                                                <option value="YES" <?= ($gear['safe_to_use'] ?? '') === 'YES' ? 'selected' : '' ?>>YES</option>
                                                <option value="NO" <?= ($gear['safe_to_use'] ?? '') === 'NO' ? 'selected' : '' ?>>NO</option>
                                            </select>
                                        </td>
                                        <td data-label="Action">
                                            <button type="button" class="btn btn-danger btn-sm removeRow">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="lifting-save-bar d-flex flex-wrap justify-content-between align-items-center mb-30">
                <div class="mb-2 mb-md-0">
                    <div class="lifting-save-pill">
                        <i class="fa fa-check-circle"></i>
                        <span><strong id="footerCount"><?= count($certificates) ?></strong> certificate row ready to update</span>
                    </div>
                    <p class="lifting-save-note">
                        Tip: keep the shared fields at the top in sync, then update the row-level equipment details below.
                    </p>
                </div>
                <button type="submit" class="btn long">
                    <i class="fa fa-save mr-2"></i>Update Certificates
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const currentYear = <?= json_encode($currentYear) ?>;
const projectNo = <?= json_encode($project_no) ?>;
const maxCertificates = 100;
const tableBody = document.querySelector("#certificateTable tbody");
const form = document.getElementById("liftingCertificateEditForm");
const certificateRowsJson = document.getElementById("certificateRowsJson");
const certificateCount = document.getElementById("certificateCount");
const summaryRowCount = document.getElementById("summaryRowCount");
const footerCount = document.getElementById("footerCount");

function getCertificateNumber(index) {
    return `CLC-${String(index + 1).padStart(3, "0")}-${currentYear}-${projectNo}`;
}

function refreshRowMeta() {
    document.querySelectorAll("#certificateTable tbody tr").forEach((row, index) => {
        row.querySelector(".lifting-row-no").textContent = index + 1;
        row.querySelector('[data-field="certificate_no"]').value = getCertificateNumber(index);
    });

    const total = tableBody.rows.length;
    certificateCount.textContent = total;
    summaryRowCount.textContent = total;
    footerCount.textContent = total;
}

function createRow(sourceRow = null, cloneValues = false) {
    const baseRow = sourceRow || tableBody.rows[0];
    const row = baseRow.cloneNode(true);

    row.querySelectorAll("[data-field]").forEach((field) => {
        if (field.dataset.field === "certificate_no") {
            field.value = "";
            return;
        }

        if (cloneValues) {
            return;
        }

        if (field.tagName === "SELECT") {
            field.selectedIndex = 0;
        } else {
            field.value = "";
        }
    });

    return row;
}

function addRows(count, mode) {
    const currentRows = tableBody.rows.length;
    const availableSlots = maxCertificates - currentRows;
    const safeCount = Math.max(0, Math.min(count, availableSlots));

    if (safeCount === 0) {
        alert(`You can keep up to ${maxCertificates} certificates in one batch.`);
        return;
    }

    const sourceRow = tableBody.rows[tableBody.rows.length - 1];

    for (let i = 0; i < safeCount; i++) {
        tableBody.appendChild(createRow(sourceRow, mode === "clone"));
    }

    refreshRowMeta();
}

document.getElementById("addSingleRow").addEventListener("click", () => addRows(1, "blank"));

document.getElementById("bulkAddRows").addEventListener("click", () => {
    const count = parseInt(document.getElementById("bulkCount").value, 10) || 1;
    const mode = document.getElementById("bulkMode").value;
    addRows(count, mode);
});

document.addEventListener("click", (event) => {
    const removeButton = event.target.closest(".removeRow");
    if (!removeButton) {
        return;
    }

    if (tableBody.rows.length === 1) {
        alert("At least one certificate row is required.");
        return;
    }

    removeButton.closest("tr").remove();
    refreshRowMeta();
});

form.addEventListener("submit", (event) => {
    const rows = Array.from(tableBody.rows).map((row) => {
        const rowData = {};

        row.querySelectorAll("[data-field]").forEach((field) => {
            rowData[field.dataset.field] = field.value.trim();
        });

        return rowData;
    });

    if (!rows.length) {
        event.preventDefault();
        alert("Add at least one certificate row before updating.");
        return;
    }

    certificateRowsJson.value = JSON.stringify(rows);
});

refreshRowMeta();
</script>

<?php include_once('../../inc/footer.php'); ?>
