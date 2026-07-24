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
        padding: 25px;
        margin-bottom: 30px;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.05);
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

    .form-element:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 40px 0 rgba(31, 38, 135, 0.12) !important;
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
        color: #2d3748;
    }

    .theme-input-style:focus, .form-control:focus, .custom-select:focus {
        background: rgba(255, 255, 255, 0.9) !important;
        border-color: #4facfe !important;
        box-shadow: 0 0 0 4px rgba(79, 172, 254, 0.1) !important;
        outline: none;
    }

    .btn-primary, .btn-brand, .btn.long, .btn-success {
        background: var(--primary-gradient) !important;
        border: none !important;
        border-radius: 15px !important;
        padding: 12px 25px !important;
        font-weight: 600 !important;
        font-family: 'Outfit', sans-serif;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: white !important;
        box-shadow: 0 4px 15px rgba(0, 198, 255, 0.3) !important;
        transition: all 0.3s ease !important;
    }

    .btn-soft {
        background: rgba(79, 172, 254, 0.1) !important;
        color: #4facfe !important;
        border: none !important;
        border-radius: 15px !important;
        padding: 12px 25px !important;
        font-weight: 600 !important;
        transition: all 0.3s ease !important;
    }

    .btn-soft:hover {
        background: rgba(79, 172, 254, 0.2) !important;
        transform: translateY(-2px);
    }

    .btn-primary:hover, .btn-brand:hover, .btn.long:hover, .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 198, 255, 0.4) !important;
        color: white !important;
    }

    label, .form-label {
        color: #4a5568;
        font-weight: 600;
        margin-bottom: 8px !important;
        margin-top: 15px !important;
        display: block;
        font-size: 13px;
    }

    .form-element h4 {
        border-bottom: 2px solid rgba(79, 172, 254, 0.3);
        padding-bottom: 10px;
        margin-bottom: 20px;
    }

    /* Mini Stat Grid */
    .mini-stat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
        margin-top: 20px;
    }

    .mini-stat {
        padding: 15px;
        border-radius: 15px;
        background: rgba(79, 172, 254, 0.05);
        border: 1px solid rgba(79, 172, 254, 0.1);
    }

    .mini-stat-label {
        font-size: 12px;
        color: #718096;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .mini-stat-value {
        font-size: 16px;
        font-weight: 700;
        color: #2d3748;
        margin-top: 5px;
    }

    /* Table Styling */
    .table-container {
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    #certificateTable {
        margin-bottom: 0;
    }

    #certificateTable thead th {
        background: rgba(79, 172, 254, 0.1);
        border: none;
        color: #4a5568;
        font-weight: 700;
        font-size: 12px;
        text-transform: uppercase;
        padding: 15px;
    }

    #certificateTable td {
        padding: 12px;
        vertical-align: middle;
        background: rgba(255, 255, 255, 0.3);
        border-color: rgba(0, 0, 0, 0.03);
    }

    .cert-preview {
        font-size: 11px;
        color: #a0aec0;
        margin-top: 5px;
    }

    /* Mobile Sticky Footer */
    .sticky-actions {
        background: var(--glass-bg);
        backdrop-filter: blur(20px);
        border-top: 1px solid var(--glass-border);
        padding: 20px;
        position: sticky;
        bottom: 0;
        z-index: 1000;
        margin: 30px -15px -30px -15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-radius: 20px 20px 0 0;
        box-shadow: 0 -10px 30px rgba(0,0,0,0.05);
    }

    @media (max-width: 768px) {
        .sticky-actions {
            flex-direction: column;
            gap: 15px;
            text-align: center;
        }
    }

    /* Set column min-widths for better readability when sentences are long */
    #certificateTable th:nth-child(2), #certificateTable td:nth-child(2) { min-width: 260px; } /* Certificate No */
    #certificateTable th:nth-child(3), #certificateTable td:nth-child(3) { min-width: 220px; } /* Identification No */
    #certificateTable th:nth-child(4), #certificateTable td:nth-child(4) { min-width: 280px; } /* Description */
    #certificateTable th:nth-child(5), #certificateTable td:nth-child(5) { min-width: 120px; } /* WLL/SWL */
    #certificateTable th:nth-child(6), #certificateTable td:nth-child(6) { min-width: 80px; }  /* Qty */
    #certificateTable th:nth-child(7), #certificateTable td:nth-child(7) { min-width: 150px; } /* Type */
    #certificateTable th:nth-child(8), #certificateTable td:nth-child(8) { min-width: 160px; } /* Applicable Standards */
    #certificateTable th:nth-child(9), #certificateTable td:nth-child(9) { min-width: 160px; } /* Date Last Exam */
    #certificateTable th:nth-child(10), #certificateTable td:nth-child(10) { min-width: 280px; } /* Test Details */
    #certificateTable th:nth-child(11), #certificateTable td:nth-child(11) { min-width: 100px; } /* Status */
    #certificateTable th:nth-child(12), #certificateTable td:nth-child(12) { min-width: 100px; } /* Safe */
    #certificateTable th:nth-child(13), #certificateTable td:nth-child(13) { min-width: 80px; }  /* Action */

    /* Make textareas and inputs take full width of cell */
    #certificateTable .theme-input-style, 
    #certificateTable .custom-select {
        width: 100% !important;
    }
    
    #certificateTable textarea.theme-input-style {
        height: 60px !important;
        resize: vertical;
    }
</style>

<div class="main-content">
    <div class="container-fluid">
        <form method="POST" action="update_form.php" id="liftingCertificateEditForm">
            <input type="hidden" name="project_no" value="<?= htmlspecialchars($projectData['project_no']) ?>">
            <input type="hidden" name="certificate_rows_json" id="certificateRowsJson">

            <div class="glass-header">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="font-20 mb-2">Edit Lifting Gear Certificate Builder</h1>
                        <p class="text-muted mb-0">Edit batch certificates quickly using cloning and shared report parameters.</p>
                    </div>
                    <div class="col-md-4 text-right">
                        <div class="mini-stat-grid mt-0">
                            <div class="mini-stat text-left">
                                <div class="mini-stat-label">Rows Loaded</div>
                                <div class="mini-stat-value" id="certificateCount"><?= count($certificates) ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row equal-height">
                <div class="col-lg-6">
                    <div class="form-element">
                        <h4>Report Setup</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Date of Report</label>
                                <input type="date" name="date_of_report" class="theme-input-style" value="<?= htmlspecialchars($firstCertificate['date_of_report'] ?? '') ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Report No</label>
                                <input type="text" name="report_no" class="theme-input-style" value="<?= htmlspecialchars($firstCertificate['report_no'] ?? $projectData['report_no']) ?>" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Project No</label>
                                <input type="text" class="theme-input-style" value="<?= htmlspecialchars($projectData['project_no']) ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">JRN</label>
                                <input type="text" name="jrn" class="theme-input-style" value="<?= htmlspecialchars($firstCertificate['jrn'] ?? '') ?>" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Color Code</label>
                                <input type="text" name="color_code" class="theme-input-style" value="<?= htmlspecialchars($firstCertificate['color_code'] ?? '') ?>" placeholder="Optional">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-element">
                        <h4>Premises & Schedule</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Employer Name & Address</label>
                                <input type="text" name="employer_name_address" class="theme-input-style" value="<?= htmlspecialchars($firstCertificate['employer_name_address'] ?? $projectData['customer_name']) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Address of Premises</label>
                                <input type="text" name="address_of_premises" class="theme-input-style" value="<?= htmlspecialchars($firstCertificate['address_of_premises'] ?? $projectData['city']) ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Date of This Examination</label>
                                <input type="date" name="date_of_this_examination" class="theme-input-style" value="<?= htmlspecialchars($firstCertificate['date_of_this_examination'] ?? '') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Next Examination Date</label>
                                <input type="date" name="next_examination_date" class="theme-input-style" value="<?= htmlspecialchars($firstCertificate['next_examination_date'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Reason for Examination</label>
                                <select name="reason_for_examination" class="custom-select" required>
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
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="form-element">
                        <h4>Customer & Approval Team</h4>
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label">Customer Name</label>
                                <input type="text" name="customer_name" class="theme-input-style" value="<?= htmlspecialchars($firstCertificate['customer_name'] ?? $projectData['customer_name']) ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Customer Email</label>
                                <input type="email" name="customer_email" class="theme-input-style" value="<?= htmlspecialchars($firstCertificate['customer_email'] ?? $projectData['customer_email']) ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Customer Mobile</label>
                                <input type="text" name="mobile" class="theme-input-style" value="<?= htmlspecialchars($firstCertificate['mobile'] ?? $projectData['customer_mobile']) ?>" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label">Inspector</label>
                                <input type="text" name="inspector" class="theme-input-style" value="<?= htmlspecialchars($firstCertificate['inspector'] ?? $projectData['inspector_name']) ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Technical Manager</label>
                                <select name="technical_manager" class="custom-select" required>
                                    <option value="">Select manager</option>
                                    <option value="Venancio Z. Vera" <?= ($firstCertificate['technical_manager'] ?? '') === 'Venancio Z. Vera' ? 'selected' : '' ?>>Venancio Z. Vera</option>
                                    <option value="Mohammed Fathy" <?= ($firstCertificate['technical_manager'] ?? '') === 'Mohammed Fathy' ? 'selected' : '' ?>>Mohammed Fathy</option>
                                    <option value="Khaled A. Alghamdi" <?= ($firstCertificate['technical_manager'] ?? '') === 'Khaled A. Alghamdi' ? 'selected' : '' ?>>Khaled A. Alghamdi</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Quality Controller</label>
                                <select name="quality_controller" class="custom-select" required>
                                    <option value="">Select controller</option>
                                    <option value="Samuel Bhatti" <?= ($firstCertificate['quality_controller'] ?? '') === 'Samuel Bhatti' ? 'selected' : '' ?>>Samuel Bhatti</option>
                                    <option value="Veera" <?= ($firstCertificate['quality_controller'] ?? '') === 'Veera' ? 'selected' : '' ?>>Veera</option>
                                    <option value="Sathish" <?= ($firstCertificate['quality_controller'] ?? '') === 'Sathish' ? 'selected' : '' ?>>Sathish</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-element">
                <div class="row align-items-end mb-4">
                    <div class="col-md-6">
                        <h4 class="mb-0 border-0">Certificate Details</h4>
                    </div>
                    <div class="col-md-6 text-right">
                        <div class="d-inline-flex align-items-end gap-3">
                            <div class="text-left mr-3">
                                <label class="form-label mt-0">Bulk Add</label>
                                <div class="input-group" style="width: 250px;">
                                    <input type="number" class="theme-input-style" id="bulkCount" value="1" min="1" max="100" style="width: 60px;">
                                    <select class="custom-select" id="bulkMode">
                                        <option value="clone">Clone Last</option>
                                        <option value="blank">Blank</option>
                                    </select>
                                    <div class="input-group-append">
                                        <button type="button" id="bulkAddRows" class="btn btn-brand">Add</button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" id="addSingleRow" class="btn btn-soft">Add One</button>
                        </div>
                    </div>
                </div>

                <div class="table-container">
                    <div class="table-responsive">
                        <table class="table table-hover" id="certificateTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Certificate No</th>
                                    <th>Identification No</th>
                                    <th>Description</th>
                                    <th>WLL/SWL</th>
                                    <th>Qty</th>
                                    <th>Type</th>
                                    <th>Applicable Standards</th>
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
                                    <td class="row-number"><?= $index + 1 ?></td>
                                    <td>
                                        <input type="text" class="theme-input-style" data-field="certificate_no" value="<?= htmlspecialchars($gear['certificate_no'] ?? '') ?>" readonly>
                                        <div class="cert-preview">Auto-generated</div>
                                    </td>
                                    <td><textarea class="theme-input-style" data-field="identification_no"><?= htmlspecialchars($gear['identification_no'] ?? '') ?></textarea></td>
                                    <td><textarea class="theme-input-style" data-field="description"><?= htmlspecialchars($gear['description'] ?? '') ?></textarea></td>
                                    <td><input type="text" class="theme-input-style" data-field="wll_swl" value="<?= htmlspecialchars($gear['wll_swl'] ?? '') ?>"></td>
                                    <td><input type="number" class="theme-input-style" data-field="qty" min="1" value="<?= htmlspecialchars($gear['qty'] ?? '') ?>"></td>
                                    <td><input type="text" class="theme-input-style" data-field="type" value="<?= htmlspecialchars($gear['type'] ?? '') ?>"></td>
                                    <td>
                                        <select class="custom-select" data-field="applicable_standards">
                                            <option value="">Select standard</option>
                                            <option value="ASME B30.9" <?= ($gear['applicable_standards'] ?? '') === 'ASME B30.9' ? 'selected' : '' ?>>ASME B30.9</option>
                                            <option value="ASME B30.26" <?= ($gear['applicable_standards'] ?? '') === 'ASME B30.26' ? 'selected' : '' ?>>ASME B30.26</option>
                                            <option value="ASME B30.20" <?= ($gear['applicable_standards'] ?? '') === 'ASME B30.20' ? 'selected' : '' ?>>ASME B30.20</option>
                                            <option value="ASME B30.10" <?= ($gear['applicable_standards'] ?? '') === 'ASME B30.10' ? 'selected' : '' ?>>ASME B30.10</option>
                                            <option value="ASME B30.30" <?= ($gear['applicable_standards'] ?? '') === 'ASME B30.30' ? 'selected' : '' ?>>ASME B30.30</option>
                                        </select>
                                    </td>
                                    <td><input type="text" class="theme-input-style" data-field="date_last_examination" value="<?= htmlspecialchars($gear['date_last_examination'] ?? '') ?>"></td>
                                    <td><textarea class="theme-input-style" data-field="test_details"><?= htmlspecialchars($gear['test_details'] ?? '') ?></textarea></td>
                                    <td>
                                        <select class="custom-select" data-field="status">
                                            <option value="">--</option>
                                            <option value="ND" <?= ($gear['status'] ?? '') === 'ND' ? 'selected' : '' ?>>ND</option>
                                            <option value="SDR" <?= ($gear['status'] ?? '') === 'SDR' ? 'selected' : '' ?>>SDR</option>
                                            <option value="NF" <?= ($gear['status'] ?? '') === 'NF' ? 'selected' : '' ?>>NF</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="custom-select" data-field="safe_to_use">
                                            <option value="">--</option>
                                            <option value="YES" <?= ($gear['safe_to_use'] ?? '') === 'YES' ? 'selected' : '' ?>>YES</option>
                                            <option value="NO" <?= ($gear['safe_to_use'] ?? '') === 'NO' ? 'selected' : '' ?>>NO</option>
                                        </select>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm removeRow"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="sticky-actions">
                <div>
                    <span class="badge badge-info p-2" id="footerCount"><?= count($certificates) ?></span> 
                    <span class="ml-2 font-weight-bold">Certificates ready to update</span>
                </div>
                <button type="submit" name="save_data_lifting" class="btn btn-success btn-lg">
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
const certificateCountText = document.getElementById("certificateCount");
const footerCountText = document.getElementById("footerCount");

function getCertificateNumber(index) {
    return `CLC-${String(index + 1).padStart(3, "0")}-${currentYear}-${projectNo}`;
}

function refreshRowMeta() {
    document.querySelectorAll("#certificateTable tbody tr").forEach((row, index) => {
        row.querySelector(".row-number").textContent = index + 1;
        row.querySelector('[data-field="certificate_no"]').value = getCertificateNumber(index);
    });

    const total = tableBody.rows.length;
    certificateCountText.textContent = total;
    footerCountText.textContent = total;
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
        const shouldClone = mode === "clone";
        tableBody.appendChild(createRow(sourceRow, shouldClone));
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
    if (!removeButton) return;

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
        alert("Add at least one certificate row before saving.");
        return;
    }

    certificateRowsJson.value = JSON.stringify(rows);
});

refreshRowMeta();
</script>

<?php include_once('../../inc/footer.php'); ?>
