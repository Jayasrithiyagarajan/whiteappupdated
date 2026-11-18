<?php
include_once('../../inc/function.php');
include_once('../../file/config.php');

function eh($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function stop_page($html) {
    echo '<div class="main-content"><div class="container-fluid" style="max-width:760px;margin:60px auto;font-family:sans-serif;">' . $html
       . '<p><a href="index.php">Back to the register</a></p></div></div>';
    include_once('../../inc/footer.php');
    exit;
}

// The register links here with ?project_no=...&certificate_no=... (certificate_no only decides which one opens first).
$project_no = trim($_GET['project_no'] ?? '');
$focus_cert = trim($_GET['certificate_no'] ?? '');

if ($project_no === '' && $focus_cert !== '') {
    $stmtF = $conn->prepare("SELECT project_no FROM eddy_current_inspection WHERE certificate_no = ? LIMIT 1");
    $stmtF->bind_param("s", $focus_cert);
    $stmtF->execute();
    $stmtF->bind_result($project_no);
    $stmtF->fetch();
    $stmtF->close();
}
if ($project_no === '') {
    stop_page('<h3>Certificate not found.</h3>');
}

// Same rule as the register: completed projects cannot be edited.
$stmtS = $conn->prepare("SELECT project_status FROM project_info WHERE project_no = ? LIMIT 1");
$stmtS->bind_param("s", $project_no);
$stmtS->execute();
$stmtS->bind_result($project_status);
$stmtS->fetch();
$stmtS->close();
if ($project_status === 'Completed') {
    stop_page('<h3>This project is completed, so its certificates can no longer be edited.</h3>');
}

// All certificates of the project (an accidental identical copy is shown once)
$stmt = $conn->prepare("SELECT * FROM eddy_current_inspection WHERE project_no = ? ORDER BY certificate_no ASC");
$stmt->bind_param("s", $project_no);
$stmt->execute();
$res = $stmt->get_result();
$certs = [];
$duplicates = 0;
while ($row = $res->fetch_assoc()) {
    if (isset($certs[$row['certificate_no']])) { $duplicates++; continue; }
    $certs[$row['certificate_no']] = $row;
}
$stmt->close();
$certs = array_values($certs);
if (!$certs) {
    stop_page('<h3>No certificates found for project ' . eh($project_no) . '.</h3><p><a href="create.php?project_no=' . urlencode($project_no) . '">Create certificates</a></p>');
}

// Next free certificate sequence for new certificates (the server re-checks when saving)
$currentYear = date('Y');
$like = "CEC-%-{$currentYear}-%";
$stmtM = $conn->prepare("SELECT COALESCE(MAX(CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(certificate_no, '-', 2), '-', -1) AS UNSIGNED)), 0) AS mx FROM eddy_current_inspection WHERE certificate_no LIKE ?");
$stmtM->bind_param("s", $like);
$stmtM->execute();
$maxSeq = (int)$stmtM->get_result()->fetch_assoc()['mx'];
$stmtM->close();

$json_flags = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR;
?>

<!-- Main Content -->
<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap');

    :root {
        --glass-bg: rgba(255, 255, 255, 0.85);
        --glass-border: rgba(255, 255, 255, 0.5);
        --primary-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        --secondary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --accent-color: #4facfe;
    }

    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%) !important;
        min-height: 100vh;
        font-family: 'Outfit', sans-serif !important;
    }

    .main-content, .main-content *:not(i), .theme-input-style, .form-control, .btn {
        font-family: 'Outfit', sans-serif !important;
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
    }

    .form-element {
        background: var(--glass-bg) !important;
        backdrop-filter: blur(15px);
        border: 1px solid var(--glass-border) !important;
        border-radius: 25px !important;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.08) !important;
        padding: 45px 35px !important;
        margin-bottom: 30px !important;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .row.equal-height {
        display: flex;
        flex-wrap: wrap;
    }

    .row.equal-height > [class*='col-'] {
        display: flex;
        flex-direction: column;
    }

    .form-element:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 40px 0 rgba(31, 38, 135, 0.12) !important;
    }

    .font-20 {
        font-family: 'Outfit', sans-serif !important;
        font-weight: 700;
        color: #1a202c;
        letter-spacing: -0.5px;
        margin: 0;
    }

    .theme-input-style, .form-control, .input-style {
        background: rgba(255, 255, 255, 0.6) !important;
        border: 1.5px solid rgba(0, 0, 0, 0.08) !important;
        border-radius: 12px !important;
        padding: 12px 18px !important;
        font-family: 'Outfit', sans-serif !important;
        height: auto !important;
        line-height: 1.5 !important;
        transition: all 0.3s ease !important;
        width: 100%;
        color: #2d3748;
        font-size: 15px;
    }

    .theme-input-style:focus, .form-control:focus, .input-style:focus {
        background: rgba(255, 255, 255, 0.9) !important;
        border-color: var(--accent-color) !important;
        box-shadow: 0 0 0 4px rgba(79, 172, 254, 0.15) !important;
        outline: none;
    }

    /* Auto-fill indicator */
    .theme-input-style.bg-auto {
        background: rgba(0, 0, 0, 0.02) !important;
        cursor: not-allowed;
        border: 1.5px dashed rgba(0, 0, 0, 0.12) !important;
        color: #718096 !important;
    }

    .btn-primary {
        background: var(--primary-gradient) !important;
        border: none !important;
        border-radius: 15px !important;
        padding: 14px 35px !important;
        font-weight: 600 !important;
        font-family: 'Outfit', sans-serif !important;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: white !important;
        box-shadow: 0 4px 15px rgba(0, 198, 255, 0.3) !important;
        transition: all 0.3s ease !important;
        display: inline-block;
        text-decoration: none;
    }

    .btn-primary:hover {
        transform: scale(1.03);
        box-shadow: 0 8px 25px rgba(0, 198, 255, 0.4) !important;
        color: white !important;
    }

    .btn-secondary {
        background: var(--secondary-gradient) !important;
        border: none !important;
        border-radius: 15px !important;
        padding: 14px 35px !important;
        font-weight: 600 !important;
        font-family: 'Outfit', sans-serif !important;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: white !important;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3) !important;
        transition: all 0.3s ease !important;
        display: inline-block;
        text-decoration: none;
    }

    .btn-secondary:hover {
        transform: scale(1.03);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4) !important;
        color: white !important;
    }

    label, .label-style {
        color: #4a5568;
        font-weight: 600;
        margin-bottom: 8px !important;
        display: block;
        font-size: 14px;
    }

    .required-asterisk {
        color: #e53e3e;
        margin-left: 3px;
        font-weight: bold;
    }

    .col-md-6, .col-md-4, .col-md-12, .col-md-3 {
        margin-bottom: 20px;
    }

    .card-title, h4, h5 {
        font-family: 'Outfit', sans-serif !important;
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        border-bottom: 2px solid rgba(79, 172, 254, 0.2);
        padding-bottom: 12px;
    }

    h4 i, h5 i {
        color: var(--accent-color);
        margin-right: 10px;
        font-size: 18px;
    }

    /* Accordion Custom styling */
    .cert-accordion-header {
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: 20px;
        padding: 22px 28px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 8px 24px 0 rgba(31, 38, 135, 0.04);
    }
    .cert-accordion-header:hover {
        background: rgba(255, 255, 255, 0.95);
        box-shadow: 0 10px 30px 0 rgba(31, 38, 135, 0.08);
    }
    .cert-chevron {
        transition: transform 0.3s ease;
    }

    /* Toggle checkmark pills */
    .pill-checkbox-container {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        padding-top: 5px;
    }
    .pill-checkbox {
        cursor: pointer;
        margin: 0 !important;
    }
    .pill-checkbox input {
        display: none;
    }
    .pill-checkbox span {
        display: inline-block;
        padding: 12px 22px;
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.5);
        border: 1.5px solid rgba(0, 0, 0, 0.06);
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        color: #4a5568;
        user-select: none;
    }
    .pill-checkbox:hover span {
        background: rgba(255, 255, 255, 0.85);
        border-color: var(--accent-color);
    }
    .pill-checkbox input:checked + span {
        background: var(--primary-gradient);
        color: white;
        border-color: transparent;
        box-shadow: 0 4px 15px rgba(0, 198, 255, 0.3);
        transform: translateY(-2px);
    }

    /* Helper note */
    .helper-text {
        font-size: 12px;
        color: #718096;
        margin-top: 6px;
    }

    /* Mobile Responsiveness */
    @media (max-width: 768px) {
        .form-element {
            padding: 30px 20px !important;
        }
        
        .font-20 {
            font-size: 18px !important;
        }

        .btn-primary, .btn-secondary {
            width: 100% !important;
            margin-bottom: 10px;
        }

        .glass-header {
            padding: 15px !important;
        }

        .cert-accordion-header {
            padding: 15px 20px !important;
        }
    }

    /* Save progress / saved state */
    .certificate-entry.saved-entry .cert-accordion-header { border-left: 6px solid #38a169; }
    .saved-badge { margin-left: 12px; font-size: 12px; font-weight: 700; color: #276749; background: #c6f6d5; padding: 4px 10px; border-radius: 8px; }
    #save-overlay { position: fixed; inset: 0; background: rgba(15, 23, 42, 0.55); z-index: 99999; display: none; align-items: center; justify-content: center; }
    #save-overlay .box { background: #fff; border-radius: 20px; padding: 32px 40px; text-align: center; min-width: 320px; box-shadow: 0 20px 60px rgba(0,0,0,.3); }
    #save-overlay .bar { height: 10px; background: #e2e8f0; border-radius: 6px; overflow: hidden; margin-top: 18px; }
    #save-overlay .bar > div { height: 100%; width: 0; background: var(--primary-gradient); transition: width .2s ease; }
</style>
<style>
    .certificate-entry.new-entry .cert-accordion-header { border-left: 6px solid #4facfe; }
    .new-badge { margin-left: 12px; font-size: 12px; font-weight: 700; color: #2b6cb0; background: #bee3f8; padding: 4px 10px; border-radius: 8px; }
    .changed-badge { margin-left: 12px; font-size: 12px; font-weight: 700; color: #975a16; background: #fefcbf; padding: 4px 10px; border-radius: 8px; }
    .current-img img { max-width: 160px; max-height: 110px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); margin-bottom: 8px; display: block; }
    .current-img small { display: block; color: #718096; margin-bottom: 4px; }
    .dup-warning { background: #fffaf0; border: 1px solid #fbd38d; color: #744210; border-radius: 14px; padding: 12px 18px; margin-bottom: 20px; }
</style>

<div class="main-content">
    <div class="container-fluid">
        <div class="glass-header">
            <div class="row align-items-center">
                <div class="col-md-7 col-12">
                    <h1 class="font-20 d-flex align-items-center">
                        <i class="fa-solid fa-file-pen mr-3" style="color: var(--accent-color);"></i>
                        EDIT EDDY CURRENT CERTIFICATES
                    </h1>
                    <div style="color:#718096;font-size:14px;margin-top:6px;">Project <?= eh($project_no) ?></div>
                </div>
                <div class="col-md-5 col-12 text-md-right text-left mt-md-0 mt-3">
                    <a href="index.php" class="btn-primary"><i class="fa-solid fa-list-check mr-2"></i> View Registry</a>
                </div>
            </div>
        </div>

        <?php if ($duplicates > 0): ?>
        <div class="dup-warning">
            <i class="fa-solid fa-triangle-exclamation mr-2"></i>
            <?= (int)$duplicates ?> identical copy(ies) of a certificate number exist for this project (saved twice by mistake).
            They are shown once here - delete the extra copies from the register.
        </div>
        <?php endif; ?>

        <!-- Add controls (same idea as the create page, plus Clone Last / Blank like the lifting certificate) -->
        <div class="glass-header mb-4" style="background: rgba(255, 255, 255, 0.7); border-radius: 20px;">
            <div class="row align-items-center">
                <div class="col-lg-8 col-md-7 col-12 d-flex align-items-center flex-wrap">
                    <label class="mr-3 mb-md-0 mb-2 font-weight-bold" style="color:#2d3748;white-space:nowrap;font-size:15px;">
                        <i class="fa-solid fa-clone text-primary mr-1"></i> Add Certificates:
                    </label>
                    <input type="number" id="bulk-add-count" class="theme-input-style mr-2 mb-md-0 mb-2" value="1" min="1" max="100" style="width:90px;padding:8px 15px !important;display:inline-block;">
                    <select id="bulk-add-mode" class="theme-input-style mr-3 mb-md-0 mb-2" style="width:170px;padding:8px 15px !important;display:inline-block;">
                        <option value="clone">Clone Last</option>
                        <option value="blank">Blank (keep project details)</option>
                    </select>
                    <button type="button" id="bulk-add-btn" class="btn-primary mb-md-0 mb-2" style="padding:10px 25px !important;border-radius:12px !important;font-size:14px;">
                        <i class="fa-solid fa-plus mr-1"></i> Add
                    </button>
                </div>
                <div class="col-lg-4 col-md-5 col-12 text-md-right text-left mt-md-0 mt-3">
                    <span class="p-2 d-inline-block" style="font-size:14px;border-radius:10px;background:rgba(79,172,254,.1);color:var(--accent-color);font-weight:700;border:1px solid rgba(79,172,254,.2);">
                        Total: <span id="total-cert-badge">0</span> / 100
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <form id="edit-form" onsubmit="return false;" novalidate>
            <div id="certificate-container"></div>

            <div class="text-center mt-5 mb-5">
                <button type="button" class="btn-secondary" id="add-more-btn" style="margin-right: 15px;">
                    <i class="fa-solid fa-circle-plus mr-2"></i> Add More Certificates
                </button>
                <button type="submit" class="btn-primary" id="save-all-btn">
                    <i class="fa-solid fa-floppy-disk mr-2"></i> Save All Changes
                </button>
            </div>
        </form>
    </div>
</div>

<template id="entry-template">
<div class="certificate-entry position-relative mb-4">
                    
                    <!-- Dynamic Entry Header Accordion Trigger -->
                    <div class="cert-accordion-header mb-0">
                        <div class="row align-items-center">
                            <div class="col-7 d-flex align-items-center">
                                <i class="fa-solid fa-chevron-down cert-chevron mr-3" style="transition: transform 0.3s ease; color: var(--accent-color); font-size: 18px;"></i>
                                <h5 class="m-0 font-weight-bold d-inline-block" style="color: #2d3748; border: none; padding: 0; margin: 0; font-size: 17px;">
                                    Certificate #<span class="cert-index">1</span> 
                                    <span class="cert-summary-no ml-3" style="font-size: 13px; font-weight: 500; color: #718096; background: rgba(0,0,0,0.05); padding: 4px 10px; border-radius: 8px;">()</span>
                                </h5>
                            </div>
                            <div class="col-5 text-right">
                                <button type="button" class="btn-primary save-cert" title="Save only this certificate" style="padding: 8px 18px !important; border-radius: 12px !important; font-size: 13px !important; font-weight: 600; margin-right: 8px;">
                                    <i class="fa-solid fa-floppy-disk mr-1"></i> Save
                                </button>
                                <span class="remove-cert-btn" style="display: none;">
                                <button type="button" class="btn-primary remove-cert" style="background: linear-gradient(135deg, #ff0844 0%, #ffb199 100%) !important; box-shadow: 0 4px 15px rgba(255, 8, 68, 0.2) !important; padding: 8px 18px !important; border-radius: 12px !important; font-size: 13px !important; font-weight: 600;">
                                    <i class="fa-solid fa-trash-can mr-1"></i> Remove
                                </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Collapsible Certificate Body -->
                    <div class="cert-body" style="padding-top: 25px;">
                        
                        <!-- Row 1: Header Data & Customer Details -->
                        <div class="row equal-height">
                            <!-- Card 1: General Information / Header Data -->
                            <div class="col-lg-6">
                                <div class="form-element">
                                    <h4><i class="fa-solid fa-file-invoice"></i> General Information</h4>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Certificate No <i class="fa-solid fa-lock text-muted ml-1" title="Locked Parameter"></i></label>
                                            <input type="text" class="theme-input-style cert-no bg-auto" name="certificate_no[]" value="" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Report No <i class="fa-solid fa-lock text-muted ml-1" title="Locked Parameter"></i></label>
                                            <input type="text" class="theme-input-style bg-auto" name="report_no[]" value="" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Project ID <i class="fa-solid fa-lock text-muted ml-1" title="Locked Parameter"></i></label>
                                            <input type="text" class="theme-input-style proj-no bg-auto" name="project_no[]" value="" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label>JRN<span class="required-asterisk">*</span></label>
                                            <input type="text" class="theme-input-style" name="jrn[]" placeholder="JRN" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Date of Inspection</label>
                                            <input type="date" class="theme-input-style" name="inspection_date[]" value="">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Next Inspection Date</label>
                                            <input type="date" class="theme-input-style" name="next_inspection_date[]" value="">
                                        </div>
                                        <div class="col-md-12">
                                            <label>Site/Location</label>
                                            <input type="text" class="theme-input-style" name="location[]" value="" placeholder="Site/Location">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Card 2: Customer Info / Inspector -->
                            <div class="col-lg-6">
                                <div class="form-element">
                                    <h4><i class="fa-solid fa-user-tie"></i> Customer & Inspector Details</h4>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>Customer Name</label>
                                            <input type="text" class="theme-input-style" name="customer_name[]" value="" placeholder="Customer Name">
                                        </div>
                                        <div class="col-md-12">
                                            <label>Customer Email</label>
                                            <input type="email" class="theme-input-style" name="customer_email[]" value="" placeholder="Type Email Address">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Mobile</label>
                                            <input type="tel" class="theme-input-style" name="mobile[]" value="" placeholder="Contact Number">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Inspector</label>
                                            <input type="text" class="theme-input-style" name="inspector[]" value="" placeholder="Inspector Name">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Technical Manager</label>
                                            <select class="theme-input-style" name="technical_manager[]">
                                                <option value="Venancio Z. Vera">Venancio Z. Vera</option>
                                                <option value="Mohammed Fathy">Mohammed Fathy</option>
                                                <option value="Khaled A. Alghamdi">Khaled A. Alghamdi</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Quality Controller</label>
                                            <select class="theme-input-style" name="quality_controller[]">
                                                <option value="Samuel Bhatti">Samuel Bhatti</option>
                                                <option value="Veera">Veera</option>
                                                <option value="Sathish">Sathish</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Row 2: Inspection Details (Full Width) -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-element">
                                    <h4><i class="fa-solid fa-magnifying-glass-chart"></i> Inspection Details</h4>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Inspected Item</label>
                                            <input type="text" class="theme-input-style" name="inspected_item[]" placeholder="Inspected Item">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Inspected Item's Serial No</label>
                                            <input type="text" class="theme-input-style" name="type_of_joint[]" placeholder="Type of Joint">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Inspection Method</label>
                                            <select class="theme-input-style inspection-method" name="inspection_method[]">
                                                <option value="surface">Surface</option>
                                                <option value="weld">Weld</option>
                                                <option value="coatingthickness">Coating Thickness</option>
                                                <option value="other">Other</option>
                                            </select>
                                            <div class="other-method-div mt-2" style="display: none;">
                                                <input type="text" class="theme-input-style" name="other_inspection_method[]" placeholder="Please specify other method">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Calibration Details</label>
                                            <input type="text" class="theme-input-style" name="calibration_details[]" placeholder="Calibration Details">
                                        </div>
                                        <div class="col-md-4">
                                            <label>Gain</label>
                                            <input type="text" class="theme-input-style" name="gain[]" placeholder="Gain">
                                        </div>
                                        <div class="col-md-4">
                                            <label>Probe Frequency</label>
                                            <input type="text" class="theme-input-style" name="probe_frequency[]" placeholder="Probe frequency">
                                        </div>
                                        <div class="col-md-4">
                                            <label>Device Maker</label>
                                            <input type="text" class="theme-input-style" name="device_maker[]" placeholder="Device Maker">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Model</label>
                                            <input type="text" class="theme-input-style" name="model[]" placeholder="Model">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Device Maker's Serial No.</label>
                                            <input type="text" class="theme-input-style" name="serial_no[]" placeholder="Serial No">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Row 3: Instrument Configuration & Reference Block -->
                        <div class="row equal-height">
                            <!-- Card 4: Instrument Configuration -->
                            <div class="col-lg-6">
                                <div class="form-element">
                                    <h4><i class="fa-solid fa-sliders"></i> Instrument Configuration</h4>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>Cable Type</label>
                                            <select class="theme-input-style" name="cable_type[]">
                                                <option value="bnc">BNC</option>
                                                <option value="lemo">LEMO</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                            <label>Sensor Type</label>
                                            <select class="theme-input-style" name="sensor_type[]">
                                                <option value="absoluteprobe">Absolute Probe</option>
                                                <option value="coil">Coil</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                            <label>Material</label>
                                            <select class="theme-input-style" name="material[]">
                                                <option value="ferromagnetic">Ferromagnetic: Carbon Steel</option>
                                                <option value="nonferromagnetic">Non-Ferromagnetic</option>
                                                <option value="mtl">MTL. THK.</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Card 5: Reference Block Calibration -->
                            <div class="col-lg-6">
                                <div class="form-element">
                                    <h4><i class="fa-solid fa-cube"></i> Reference Block Calibration</h4>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>Ref. Block Type</label>
                                            <select class="theme-input-style" name="ref_block_type[]">
                                                <option value="notchblock">Notch Block</option>
                                                <option value="notchdepth">Notch Depth</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12 mt-2">
                                            <label>Select MM (Tick all that apply)</label>
                                            <div class="pill-checkbox-container">
                                                <label class="pill-checkbox">
                                                    <input type="checkbox" name="ref_block_type_mm[0][]" value="5mm">
                                                    <span>0.5 mm</span>
                                                </label>
                                                <label class="pill-checkbox">
                                                    <input type="checkbox" name="ref_block_type_mm[0][]" value="10mm">
                                                    <span>1.0 mm</span>
                                                </label>
                                                <label class="pill-checkbox">
                                                    <input type="checkbox" name="ref_block_type_mm[0][]" value="20mm">
                                                    <span>2.0 mm</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Row 4: Images & Inspection Results (Full Width) -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-element">
                                    <h4><i class="fa-solid fa-images"></i> Images & Results</h4>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label style="color: var(--accent-color); font-weight: bold;">Number of Images to Upload</label>
                                            <select class="theme-input-style num-images" name="num_images[]">
                                                <option value="1">1 Image</option>
                                                <option value="2" selected>2 Images</option>
                                                <option value="3">3 Images</option>
                                            </select>
                                        </div>
                                        
                                        <!-- Image Fields Wrapper -->
                                        <div class="col-md-12">
                                            <div class="image-upload-wrapper mt-3">
                                                <div class="img-field-1 mb-3">
                                                    <label>Upload Image 1</label>
                                                    <div class="current-img" data-img="1"></div>
                                                    <input type="file" class="theme-input-style" name="image_1[]" accept="image/*">
                                                </div>
                                                <div class="img-field-2 mb-3">
                                                    <label>Upload Image 2</label>
                                                    <div class="current-img" data-img="2"></div>
                                                    <input type="file" class="theme-input-style" name="image_2[]" accept="image/*">
                                                </div>
                                                <div class="img-field-3 mb-3" style="display: none;">
                                                    <label>Upload Image 3</label>
                                                    <div class="current-img" data-img="3"></div>
                                                    <input type="file" class="theme-input-style" name="image_3[]" accept="image/*">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-12">
                                            <label>Description of Inspection</label>
                                            <input type="text" class="theme-input-style" name="description_of_inspection[]" placeholder="Description of inspection">
                                        </div>
                                        
                                        <div class="col-md-12 mt-4">
                                            <h5>Inspection Result</h5>
                                        </div>
                                        
                                        <div class="col-md-12">
                                            <label>Inspection Result Status</label>
                                            <select class="theme-input-style inspection-result" name="inspection_result[]">
                                                <option value="">Select an option</option>
                                                <option value="noSurfaceIndication">No surface indication found at the time of inspection</option>
                                                <option value="notAcceptable">NOT ACCEPTABLE DUE TO:</option>
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-12 not-acceptable-div mt-3" style="display: none;">
                                            <label>Reason for Rejection</label>
                                            <select class="theme-input-style reason-select" name="reason[]">
                                                <option value="">Select a reason</option>
                                                <option value="crack">Crack</option>
                                                <option value="wear">Wear</option>
                                                <option value="other">Other</option>
                                            </select>
                                            <input type="text" name="other_reason[]" class="theme-input-style mt-3 other-reason-input" placeholder="Please specify reason" style="display: none;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-right mt-3">
                            <span class="save-status mr-3" style="font-size:13px;font-weight:600;"></span>
                            <button type="button" class="btn-primary save-cert" style="padding: 10px 26px !important; border-radius: 12px !important; font-weight: 600;">
                                <i class="fa-solid fa-floppy-disk mr-1"></i> Save this certificate
                            </button>
                        </div>
                        
                    </div>
                </div>
</template>

<?php include_once('../../inc/footer.php'); ?>

<script>
(function () {
    const MAX = 100;
    const PROJECT_NO = <?= json_encode($project_no) ?>;
    const YEAR       = <?= json_encode($currentYear) ?>;
    const FOCUS_CERT = <?= json_encode($focus_cert) ?>;
    const EXISTING   = <?= json_encode($certs, $json_flags) ?>;
    let seq = <?= (int)$maxSeq ?>;

    const container = document.getElementById('certificate-container');
    const template  = document.getElementById('entry-template');
    const form      = document.getElementById('edit-form');
    const saveBtn   = document.getElementById('save-all-btn');
    const addBtn    = document.getElementById('add-more-btn');
    const bulkBtn   = document.getElementById('bulk-add-btn');
    const saveHtml  = saveBtn.innerHTML;
    const removed   = [];            // existing certificates removed on screen, deleted when Save is pressed
    let saving = false;

    // fields that were read-only on the old edit page - they stay locked for saved certificates
    const LOCKED = ['inspection_date', 'jrn', 'customer_name', 'customer_email', 'mobile', 'inspector'];
    // item specific fields cleared by "Blank" (project details are kept)
    const ITEM_FIELDS = ['inspected_item', 'type_of_joint', 'calibration_details', 'gain', 'probe_frequency', 'device_maker',
                         'model', 'serial_no', 'description_of_inspection', 'inspection_result', 'reason', 'other_reason', 'other_inspection_method'];
    const TEXT_FIELDS = ['certificate_no', 'report_no', 'project_no', 'jrn', 'inspection_date', 'next_inspection_date', 'location',
        'customer_name', 'customer_email', 'mobile', 'inspector', 'technical_manager', 'quality_controller', 'inspected_item',
        'type_of_joint', 'inspection_method', 'other_inspection_method', 'calibration_details', 'gain', 'probe_frequency',
        'device_maker', 'model', 'serial_no', 'cable_type', 'sensor_type', 'material', 'ref_block_type',
        'description_of_inspection', 'inspection_result', 'reason', 'other_reason'];

    const pad = n => String(n).padStart(3, '0');
    const field = (entry, name) => entry.querySelector('[name="' + name + '[]"]');
    const cleanDate = v => (!v || v === '0000-00-00') ? '' : v;

    function setField(entry, name, value) {
        const el = field(entry, name);
        if (!el) return;
        value = (value === null || value === undefined) ? '' : String(value);
        if (/date/.test(name)) value = cleanDate(value);
        if (el.tagName === 'SELECT' && value !== '' && !Array.from(el.options).some(o => o.value === value)) {
            const opt = document.createElement('option');      // keep a stored value that is no longer in the list
            opt.value = value; opt.textContent = value;
            el.appendChild(opt);
        }
        el.value = value;
    }

    function readValues(entry) {
        const v = {};
        TEXT_FIELDS.forEach(n => { const el = field(entry, n); v[n] = el ? el.value : ''; });
        v.ref_block_type_mm = Array.from(entry.querySelectorAll('input[type="checkbox"]:checked')).map(c => c.value).join(',');
        return v;
    }

    function summaryText(entry) {
        const no  = field(entry, 'certificate_no').value || '';
        const jrn = field(entry, 'jrn').value || '';
        return '(' + no + (jrn ? ' | JRN: ' + jrn : '') + ')';
    }

    function badge(entry, cls, text) {
        entry.querySelectorAll('.new-badge, .changed-badge').forEach(b => b.remove());
        if (!text) return;
        const b = document.createElement('span');
        b.className = cls; b.textContent = text;
        entry.querySelector('.cert-summary-no').after(b);
    }

    function refresh() {
        const entries = container.querySelectorAll('.certificate-entry');
        entries.forEach((entry, i) => {
            entry.querySelector('.cert-index').textContent = i + 1;
            entry.querySelector('.remove-cert-btn').style.display = entries.length > 1 ? 'inline-block' : 'none';
            entry.querySelectorAll('.pill-checkbox input[type="checkbox"]').forEach(cb => { cb.name = 'ref_block_type_mm[' + i + '][]'; });
            entry.querySelector('.cert-summary-no').textContent = summaryText(entry);
        });
        document.getElementById('total-cert-badge').textContent = entries.length;
    }

    function expandOnly(entry) {
        container.querySelectorAll('.certificate-entry').forEach(en => {
            en.querySelector('.cert-body').style.display = 'none';
            en.querySelector('.cert-chevron').style.transform = 'rotate(-90deg)';
        });
        entry.querySelector('.cert-body').style.display = 'block';
        entry.querySelector('.cert-chevron').style.transform = 'rotate(0deg)';
    }

    function markDirty(entry) {
        if (entry.dataset.dirty === '1') return;
        entry.dataset.dirty = '1';
        if (entry.dataset.existing === '1') badge(entry, 'changed-badge', 'Edited');
    }

    function wireEntry(entry) {
        // accordion
        entry.querySelector('.cert-accordion-header').addEventListener('click', function (e) {
            if (e.target.closest('.remove-cert, .save-cert')) return;
            const body = entry.querySelector('.cert-body');
            const collapsed = body.style.display === 'none';
            body.style.display = collapsed ? 'block' : 'none';
            entry.querySelector('.cert-chevron').style.transform = collapsed ? 'rotate(0deg)' : 'rotate(-90deg)';
        });
        // conditional blocks
        const result = entry.querySelector('.inspection-result'), na = entry.querySelector('.not-acceptable-div');
        result.addEventListener('change', () => { na.style.display = result.value === 'notAcceptable' ? 'block' : 'none'; });
        const reason = entry.querySelector('.reason-select'), other = entry.querySelector('.other-reason-input');
        reason.addEventListener('change', () => { other.style.display = reason.value === 'other' ? 'block' : 'none'; });
        const method = entry.querySelector('.inspection-method'), omDiv = entry.querySelector('.other-method-div');
        method.addEventListener('change', () => { omDiv.style.display = method.value === 'other' ? 'block' : 'none'; });
        const num = entry.querySelector('.num-images');
        num.addEventListener('change', () => {
            const c = parseInt(num.value, 10);
            [1, 2, 3].forEach(n => { entry.querySelector('.img-field-' + n).style.display = c >= n ? 'block' : 'none'; });
        });
        // summary + dirty tracking
        entry.addEventListener('input',  () => { markDirty(entry); entry.querySelector('.cert-summary-no').textContent = summaryText(entry); });
        entry.addEventListener('change', () => { markDirty(entry); });
        // save this certificate only
        entry.querySelectorAll('.save-cert').forEach(b => b.addEventListener('click', () => saveOne(entry)));
        // remove
        entry.querySelector('.remove-cert').addEventListener('click', function () {
            const isExisting = entry.dataset.existing === '1';
            const msg = isExisting
                ? 'Remove certificate ' + entry.dataset.certNo + '? It is deleted from the database when you press Save All Changes.'
                : 'Remove this new certificate?';
            if (!confirm(msg)) return;
            if (isExisting) removed.push(entry.dataset.certNo);
            entry.remove();
            refresh();
        });
    }

    function showCurrentImages(entry, d) {
        [1, 2, 3].forEach(n => {
            const box = entry.querySelector('.current-img[data-img="' + n + '"]');
            box.innerHTML = '';
            const file = d && d['image_' + n];
            if (file) {
                const img = document.createElement('img');
                img.src = 'uploads/' + encodeURIComponent(file); img.alt = 'Image ' + n;
                const note = document.createElement('small');
                note.textContent = 'Current image - choose a file below only to replace it';
                box.appendChild(note); box.appendChild(img);
            }
        });
    }

    function populate(entry, d) {
        TEXT_FIELDS.forEach(n => { if (d[n] !== undefined) setField(entry, n, d[n]); });
        const mm = String(d.ref_block_type_mm || '').split(',').map(x => x.trim()).filter(Boolean);
        entry.querySelectorAll('.pill-checkbox input[type="checkbox"]').forEach(cb => { cb.checked = mm.includes(cb.value); });
    }

    function buildEntry(d, opts) {
        const entry = template.content.firstElementChild.cloneNode(true);
        wireEntry(entry);
        populate(entry, d);

        // image count: show as many upload slots as the certificate has images (min. 2 like the create page)
        let nImg = 2;
        if (opts.existing) { nImg = d.image_3 ? 3 : (d.image_2 ? 2 : (d.image_1 ? 1 : 2)); }
        entry.querySelector('.num-images').value = String(nImg);
        showCurrentImages(entry, opts.existing ? d : null);

        if (opts.existing) {
            entry.dataset.existing = '1';
            entry.dataset.certNo = d.certificate_no;
            LOCKED.forEach(n => { const el = field(entry, n); if (el && el.value !== '') { el.readOnly = true; el.classList.add('bg-auto'); } });
        } else {
            entry.classList.add('new-entry');
        }
        container.appendChild(entry);

        ['inspection-result', 'reason-select', 'inspection-method', 'num-images'].forEach(c => {
            entry.querySelector('.' + c).dispatchEvent(new Event('change'));
        });
        entry.dataset.dirty = '0';
        if (!opts.existing) { entry.dataset.dirty = '1'; badge(entry, 'new-badge', 'New - not saved yet'); }
        return entry;
    }

    function addCertificates(count, mode) {
        const entries = container.querySelectorAll('.certificate-entry');
        const room = MAX - entries.length;
        const n = Math.max(0, Math.min(count, room));
        if (n === 0) { alert('You can keep up to ' + MAX + ' certificates in one project.'); return; }

        const last = entries[entries.length - 1];
        const base = readValues(last);
        if (mode === 'blank') ITEM_FIELDS.forEach(k => { base[k] = ''; });
        if (mode === 'blank') base.ref_block_type_mm = '';
        if (mode === 'blank') { base.inspection_method = 'surface'; }

        let newest = null;
        for (let i = 0; i < n; i++) {
            const d = Object.assign({}, base);
            seq++;
            d.certificate_no = 'CEC-' + pad(seq) + '-' + YEAR + '-' + PROJECT_NO;
            d.project_no = PROJECT_NO;
            newest = buildEntry(d, { existing: false });
            newest.dataset.token = (window.crypto && crypto.randomUUID) ? crypto.randomUUID() : 't' + Date.now().toString(36) + Math.random().toString(36).slice(2);
        }
        refresh();
        expandOnly(newest);
        newest.scrollIntoView({ behavior: 'smooth' });
    }

    document.getElementById('bulk-add-btn').addEventListener('click', () => {
        addCertificates(parseInt(document.getElementById('bulk-add-count').value, 10) || 1, document.getElementById('bulk-add-mode').value);
    });
    addBtn.addEventListener('click', () => addCertificates(1, 'clone'));

    // ---------- Save: one request per certificate ----------
    const overlayEl = document.createElement('div');
    overlayEl.id = 'save-overlay';
    overlayEl.style.cssText = 'position:fixed;inset:0;background:rgba(15,23,42,.55);z-index:99999;display:none;align-items:center;justify-content:center;';
    overlayEl.innerHTML = '<div style="background:#fff;border-radius:20px;padding:32px 40px;text-align:center;min-width:320px;box-shadow:0 20px 60px rgba(0,0,0,.3);">' +
        '<h4 id="so-title" style="border:none;justify-content:center;margin:0 0 6px;padding:0;">Saving…</h4><div id="so-sub" style="color:#718096;font-size:14px;"></div>' +
        '<div style="height:10px;background:#e2e8f0;border-radius:6px;overflow:hidden;margin-top:18px;"><div id="so-bar" style="height:100%;width:0;background:var(--primary-gradient);transition:width .2s;"></div></div></div>';
    document.body.appendChild(overlayEl);
    function overlay(show, title, sub, pct) {
        overlayEl.style.display = show ? 'flex' : 'none';
        if (title !== undefined) document.getElementById('so-title').textContent = title;
        if (sub !== undefined) document.getElementById('so-sub').textContent = sub;
        if (pct !== undefined) document.getElementById('so-bar').style.width = pct + '%';
    }
    function setBusy(b) {
        saving = b;
        [saveBtn, addBtn, bulkBtn].forEach(x => x.disabled = b);
        container.querySelectorAll('.save-cert').forEach(x => x.disabled = b);
        saveBtn.innerHTML = b ? '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Saving…' : saveHtml;
    }

    function buildFormData(entry, flag) {
        const fd = new FormData();
        fd.append(flag, '1');
        if (flag === 'save_one') fd.append('client_token', entry.dataset.token || '');
        entry.querySelectorAll('input, select, textarea').forEach(el => {
            if (!el.name || el.type === 'checkbox') return;
            const key = el.name.replace(/\[\]$/, '');
            if (el.type === 'file') { if (el.files && el.files[0]) fd.append(key, el.files[0]); return; }
            fd.append(key, el.value);
        });
        fd.append('ref_block_type_mm', Array.from(entry.querySelectorAll('input[type="checkbox"]:checked')).map(c => c.value).join(','));
        return fd;
    }

    async function post(url, body) {
        const res = await fetch(url, { method: 'POST', body: body, credentials: 'same-origin' });
        const text = await res.text();
        let data;
        try { data = JSON.parse(text); }
        catch (_) { throw new Error('Unexpected reply from the server: ' + text.replace(/<[^>]*>/g, ' ').trim().slice(0, 200)); }
        if (res.status === 401 || data.expired) { const e = new Error(data.message || 'Your login session has expired.'); e.expired = true; throw e; }
        if (!(data.ok || data.success)) throw new Error(data.message || 'Save failed.');
        return data;
    }

    // ---------- save ONE certificate (used by the card Save buttons and by Save All Changes) ----------
    async function saveEntry(entry) {
        const isExisting = entry.dataset.existing === '1';
        let data;
        if (isExisting) {
            data = await post('update.php', buildFormData(entry, 'update_one'));
        } else {
            data = await post('display.php', buildFormData(entry, 'save_one'));
            field(entry, 'certificate_no').value = data.certificate_no;     // the server may have picked a different number
            entry.dataset.existing = '1';
            entry.dataset.certNo = data.certificate_no;
            entry.classList.remove('new-entry');
            LOCKED.forEach(n => { const el = field(entry, n); if (el && el.value !== '') { el.readOnly = true; el.classList.add('bg-auto'); } });
        }
        if (data.images) {                                                   // show what is stored now, clear the file pickers
            const d = {}; data.images.forEach((f, i) => { d['image_' + (i + 1)] = f; });
            showCurrentImages(entry, d);
            entry.querySelectorAll('input[type="file"]').forEach(f => { f.value = ''; });
        }
        entry.dataset.dirty = '0';
        badge(entry, '', '');
        entry.querySelector('.cert-summary-no').textContent = summaryText(entry);
    }

    function flash(entry, ok, text) {
        const st = entry.querySelector('.save-status');
        st.style.color = ok ? '#276749' : '#c53030';
        st.textContent = text;
        clearTimeout(entry._flash);
        if (ok) entry._flash = setTimeout(() => { st.textContent = ''; }, 4000);
    }

    async function saveOne(entry) {
        if (saving) return;
        if (!field(entry, 'jrn').value.trim()) {
            expandOnly(entry); field(entry, 'jrn').focus();
            flash(entry, false, 'JRN is required.');
            return;
        }
        const btns = entry.querySelectorAll('.save-cert');
        const html = Array.from(btns).map(b => b.innerHTML);
        setBusy(true);
        btns.forEach(b => { b.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i> Saving…'; });
        try {
            await saveEntry(entry);
            flash(entry, true, 'Saved ✓ ' + new Date().toLocaleTimeString());
        } catch (err) {
            flash(entry, false, err.message);
            if (err.expired) { alert(err.message); }
        } finally {
            setBusy(false);
            btns.forEach((b, i) => { b.innerHTML = html[i]; });
        }
    }

    // ---------- keep the login alive while the form is open ----------
    let sessionWarned = false;
    async function keepAlive() {
        try {
            const r = await fetch('ping.php', { credentials: 'same-origin', cache: 'no-store' });
            if (r.status === 401 && !sessionWarned) {
                sessionWarned = true;
                const bar = document.createElement('div');
                bar.style.cssText = 'position:fixed;top:0;left:0;right:0;z-index:100000;background:#c53030;color:#fff;padding:12px 20px;text-align:center;font-weight:600;';
                bar.innerHTML = 'Your login session expired. Your entries on this page are kept - <a href="../../index.php" target="_blank" style="color:#fff;text-decoration:underline;">log in again in a new tab</a>, then press Save.';
                document.body.appendChild(bar);
            } else if (r.ok) { sessionWarned = false; }
        } catch (_) {}
    }
    setInterval(keepAlive, 4 * 60 * 1000);
    keepAlive();

    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        if (saving) return;

        const entries = Array.from(container.querySelectorAll('.certificate-entry'));
        if (!entries.length) { alert('At least one certificate is required.'); return; }
        for (const entry of entries) {
            if (!field(entry, 'jrn').value.trim()) {
                expandOnly(entry); field(entry, 'jrn').focus();
                alert('Certificate ' + field(entry, 'certificate_no').value + ': JRN is required.');
                return;
            }
        }

        const work = entries.filter(en => en.dataset.dirty === '1');
        const total = work.length + removed.length;
        if (total === 0) { window.location.href = 'index.php'; return; }

        setBusy(true);
        let done = 0;
        try {
            for (const entry of work) {
                overlay(true, 'Saving ' + (done + 1) + ' of ' + total + '…', field(entry, 'certificate_no').value, Math.round(done / total * 100));
                try {
                    await saveEntry(entry);
                } catch (err) {
                    expandOnly(entry);
                    throw new Error('Certificate ' + field(entry, 'certificate_no').value + ': ' + err.message);
                }
                done++;
            }
            while (removed.length) {
                const certNo = removed[0];
                overlay(true, 'Saving ' + (done + 1) + ' of ' + total + '…', 'Deleting ' + certNo, Math.round(done / total * 100));
                const body = new FormData(); body.append('certificate_no', certNo);
                try { await post('delete.php', body); }
                catch (err) { throw new Error('Could not delete ' + certNo + ': ' + err.message); }
                removed.shift();
                done++;
            }
        } catch (err) {
            overlay(false); setBusy(false);
            alert(err.message + '\n\n' + done + ' of ' + total + ' change(s) saved so far. Fix the problem and press Save again - saved changes are not repeated.');
            return;
        }
        overlay(true, 'All changes saved', 'Opening the register…', 100);
        saving = false;
        window.location.href = 'index.php?msg=' + encodeURIComponent('Eddy Current certificates updated successfully.');
    });

    window.addEventListener('beforeunload', function (e) {
        if (saving) { e.preventDefault(); e.returnValue = ''; }
    });

    // ---------- start ----------
    EXISTING.forEach(d => buildEntry(d, { existing: true }));
    refresh();
    const first = container.querySelector('.certificate-entry[data-cert-no="' + (FOCUS_CERT || '').replace(/"/g, '') + '"]')
               || container.querySelector('.certificate-entry');
    expandOnly(first);
    if (FOCUS_CERT) first.scrollIntoView();
})();
</script>
