<?php
include_once('../../inc/function.php');
include_once('../../file/config.php');

// Ensure `project_no` is set in the request
if (isset($_GET['project_no']) && !empty($_GET['project_no'])) {
    $project_no = $_GET['project_no'];
    $query = "
    SELECT 
        p.project_no, p.customer_name, p.customer_email, p.customer_mobile, p.inspector_name,
        c.checklist_no, c.inspection_date, c.crane_serial_no, c.capacity_swl,
        r.report_no, r.sticker_number_issued, r.next_inspection_due_date,
        cu.city
    FROM 
        project_info p
    LEFT JOIN 
        checklist_information c ON p.project_no = c.project_no
    LEFT JOIN 
        reports r ON p.project_no = r.project_no
    LEFT JOIN 
        customers cu ON cu.customer_name = p.customer_name
    WHERE 
        p.project_no = ?
";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $project_no);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();

        // Generate certificate number logic
        // $currentYear = date('Y');
        // $certQuery = "SELECT certificate_no FROM eddy_current_inspection ORDER BY id DESC LIMIT 1";
        // $certResult = $conn->query($certQuery);
        // if ($certResult->num_rows > 0) {
        //     $lastCert = $certResult->fetch_assoc()['certificate_no'];
        //     // Extract the numeric part
        //     preg_match('/EC-(\d+)-\d{4}/', $lastCert, $matches);
        //     $nextNumber = isset($matches[1]) ? (int)$matches[1] + 1 : 1;
        // } else {
        //     $nextNumber = 1; // Start with 1 if no previous certificates exist
        // }
        // // Format the new certificate number
        // $newCertificateNo = sprintf("EC-%03d-%s", $nextNumber, $currentYear);
        // Display or use the certificate number as needed
        // echo "<h3>Generated Certificate Number: $newCertificateNo</h3>";     
        
        // Generate certificate number in format CEC-001-2025-project_id
$currentYear = date('Y');

// Count existing certificates for the current year
$certQuery = "SELECT COUNT(*) AS count FROM eddy_current_inspection WHERE certificate_no LIKE 'CEC-%-$currentYear-%'";
$certResult = $conn->query($certQuery);
$certCount = 0;

if ($certResult && $row = $certResult->fetch_assoc()) {
    $certCount = (int)$row['count'];
}

// Next certificate number
$nextNumber = $certCount + 1;

// Format with leading zeros to make it 3 digits
$formattedNumber = str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

// Final certificate number
$newCertificateNo = "CEC-{$formattedNumber}-{$currentYear}-{$project_no}";
    } else {
        $data = null;
    }
} else {
    echo "Invalid or missing project ID.";
    exit;
}
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
</style>

<div class="main-content">
    <!-- Header -->
    <div class="container-fluid">
        <div class="glass-header">
            <div class="row align-items-center">
                <div class="col-md-6 col-12">
                    <h1 class="font-20 d-flex align-items-center">
                        <i class="fa-solid fa-file-waveform mr-3" style="color: var(--accent-color);"></i>
                        EDDY CURRENT CERTIFICATE CREATION
                    </h1>
                </div>
                <div class="col-md-6 col-12 text-md-right text-left mt-md-0 mt-3">
                    <a href="index.php" class="btn-primary">
                        <i class="fa-solid fa-list-check mr-2"></i> View Registry
                    </a>
                </div>
            </div>
        </div>

        <!-- Bulk Generator Control -->
        <div class="glass-header mb-4" style="background: rgba(255, 255, 255, 0.7); border-radius: 20px;">
            <div class="row align-items-center">
                <div class="col-lg-8 col-md-7 col-12 d-flex align-items-center flex-wrap">
                    <label class="mr-3 mb-md-0 mb-2 font-weight-bold" style="color: #2d3748; white-space: nowrap; font-size: 15px;">
                        <i class="fa-solid fa-clone text-primary mr-1"></i> Number of Certificates to Create:
                    </label>
                    <input type="number" id="bulk-cert-count" class="theme-input-style mr-3 mb-md-0 mb-2" value="1" min="1" max="100" style="width: 100px; padding: 8px 15px !important; display: inline-block;">
                    <button type="button" id="bulk-generate-btn" class="btn-primary mb-md-0 mb-2" style="padding: 10px 25px !important; border-radius: 12px !important; font-size: 14px;">
                        <i class="fa-solid fa-arrows-rotate mr-1"></i> Generate
                    </button>
                </div>
                <div class="col-lg-4 col-md-5 col-12 text-md-right text-left mt-md-0 mt-3">
                    <span class="p-2 d-inline-block" style="font-size: 14px; border-radius: 10px; background: rgba(79, 172, 254, 0.1); color: var(--accent-color); font-weight: 700; border: 1px solid rgba(79, 172, 254, 0.2);">
                        Total Loaded: <span id="total-cert-badge">1</span> / 100
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Forms Container -->
    <div class="container-fluid">
        <form action="display.php" method="POST" enctype="multipart/form-data">
            
            <div id="certificate-container">
                <!-- First Certificate Entry -->
                <div class="certificate-entry position-relative mb-4">
                    
                    <!-- Dynamic Entry Header Accordion Trigger -->
                    <div class="cert-accordion-header mb-0">
                        <div class="row align-items-center">
                            <div class="col-8 d-flex align-items-center">
                                <i class="fa-solid fa-chevron-down cert-chevron mr-3" style="transition: transform 0.3s ease; color: var(--accent-color); font-size: 18px;"></i>
                                <h5 class="m-0 font-weight-bold d-inline-block" style="color: #2d3748; border: none; padding: 0; margin: 0; font-size: 17px;">
                                    Certificate #<span class="cert-index">1</span> 
                                    <span class="cert-summary-no ml-3" style="font-size: 13px; font-weight: 500; color: #718096; background: rgba(0,0,0,0.05); padding: 4px 10px; border-radius: 8px;">(<?= $newCertificateNo ?>)</span>
                                </h5>
                            </div>
                            <div class="col-4 text-right remove-cert-btn" style="display: none;">
                                <button type="button" class="btn-primary remove-cert" style="background: linear-gradient(135deg, #ff0844 0%, #ffb199 100%) !important; box-shadow: 0 4px 15px rgba(255, 8, 68, 0.2) !important; padding: 8px 18px !important; border-radius: 12px !important; font-size: 13px !important; font-weight: 600;">
                                    <i class="fa-solid fa-trash-can mr-1"></i> Remove
                                </button>
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
                                            <input type="text" class="theme-input-style cert-no bg-auto" name="certificate_no[]" value="<?= $newCertificateNo ?>" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Report No <i class="fa-solid fa-lock text-muted ml-1" title="Locked Parameter"></i></label>
                                            <input type="text" class="theme-input-style bg-auto" name="report_no[]" value="<?php echo $data['report_no'] ?? ''; ?>" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Project ID <i class="fa-solid fa-lock text-muted ml-1" title="Locked Parameter"></i></label>
                                            <input type="text" class="theme-input-style proj-no bg-auto" name="project_no[]" value="<?php echo $data['project_no'] ?? ''; ?>" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label>JRN<span class="required-asterisk">*</span></label>
                                            <input type="text" class="theme-input-style" name="jrn[]" placeholder="JRN" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Date of Inspection</label>
                                            <input type="date" class="theme-input-style" name="inspection_date[]" value="<?php echo $data['inspection_date'] ?? ''; ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Next Inspection Date</label>
                                            <input type="date" class="theme-input-style" name="next_inspection_date[]" value="<?php echo $data['next_inspection_due_date'] ?? ''; ?>">
                                        </div>
                                        <div class="col-md-12">
                                            <label>Site/Location</label>
                                            <input type="text" class="theme-input-style" name="location[]" value="<?php echo $data['city'] ?? ''; ?>" placeholder="Site/Location">
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
                                            <input type="text" class="theme-input-style" name="customer_name[]" value="<?php echo $data['customer_name'] ?? ''; ?>" placeholder="Customer Name">
                                        </div>
                                        <div class="col-md-12">
                                            <label>Customer Email</label>
                                            <input type="email" class="theme-input-style" name="customer_email[]" value="<?php echo $data['customer_email'] ?? ''; ?>" placeholder="Type Email Address">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Mobile</label>
                                            <input type="number" class="theme-input-style" name="mobile[]" value="<?php echo $data['customer_mobile'] ?? ''; ?>" placeholder="Contact Number">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Inspector</label>
                                            <input type="text" class="theme-input-style" name="inspector[]" value="<?php echo $data['inspector_name'] ?? ''; ?>" placeholder="Inspector Name">
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
                                                    <input type="file" class="theme-input-style" name="image_1[]" accept="image/*">
                                                </div>
                                                <div class="img-field-2 mb-3">
                                                    <label>Upload Image 2</label>
                                                    <input type="file" class="theme-input-style" name="image_2[]" accept="image/*">
                                                </div>
                                                <div class="img-field-3 mb-3" style="display: none;">
                                                    <label>Upload Image 3</label>
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
                        
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="text-center mt-5 mb-5">
                <button type="button" class="btn-secondary" id="add-more-btn" style="margin-right: 15px;">
                    <i class="fa-solid fa-circle-plus mr-2"></i> Add More Certificates
                </button>
                <button type="submit" class="btn-primary" name="save_all">
                    <i class="fa-solid fa-floppy-disk mr-2"></i> Save All Certificates
                </button>
            </div>
        </form>
    </div>
</div>

<?php include_once('../../inc/footer.php'); ?>

<script>
    // Configuration
    const MAX_CERTIFICATES = 100;
    const project_no = "<?= $project_no ?>";
    const base_cert_no = "CEC-"; // Prefix
    const year = "<?= $currentYear ?>";
    let cert_counter = <?= $nextNumber ?>;

    function initAccordion(entry) {
        const header = entry.querySelector('.cert-accordion-header');
        const body = entry.querySelector('.cert-body');
        const chevron = entry.querySelector('.cert-chevron');
        
        header.addEventListener('click', function(e) {
            // Do not toggle expand/collapse if clicking remove button
            if (e.target.closest('.remove-cert')) return;
            
            const isCollapsed = body.style.display === 'none';
            body.style.display = isCollapsed ? 'block' : 'none';
            chevron.style.transform = isCollapsed ? 'rotate(0deg)' : 'rotate(-90deg)';
        });
    }

    function initHeaderSummary(entry) {
        const jrnInput = entry.querySelector('input[name="jrn[]"]');
        const certNoInput = entry.querySelector('.cert-no');
        const summarySpan = entry.querySelector('.cert-summary-no');
        
        function updateSummary() {
            const certNo = certNoInput.value || '';
            const jrn = jrnInput.value || '';
            summarySpan.textContent = `(${certNo}${jrn ? ' | JRN: ' + jrn : ''})`;
        }
        
        jrnInput.addEventListener('input', updateSummary);
        certNoInput.addEventListener('input', updateSummary);
        updateSummary();
    }

    function initEntryEvents(entry) {
        // Inspection Result toggle
        const resultSelect = entry.querySelector('.inspection-result');
        const notAcceptableDiv = entry.querySelector('.not-acceptable-div');
        resultSelect.addEventListener('change', function() {
            notAcceptableDiv.style.display = this.value === "notAcceptable" ? "block" : "none";
        });

        // Reason toggle
        const reasonSelect = entry.querySelector('.reason-select');
        const otherReasonInput = entry.querySelector('.other-reason-input');
        reasonSelect.addEventListener('change', function() {
            otherReasonInput.style.display = this.value === "other" ? "block" : "none";
        });

        // Inspection Method toggle
        const methodSelect = entry.querySelector('.inspection-method');
        const otherMethodDiv = entry.querySelector('.other-method-div');
        methodSelect.addEventListener('change', function() {
            otherMethodDiv.style.display = this.value === "other" ? "block" : "none";
        });

        // Image count toggle
        const numImagesSelect = entry.querySelector('.num-images');
        numImagesSelect.addEventListener('change', function() {
            const count = parseInt(this.value);
            entry.querySelector('.img-field-1').style.display = count >= 1 ? "block" : "none";
            entry.querySelector('.img-field-2').style.display = count >= 2 ? "block" : "none";
            entry.querySelector('.img-field-3').style.display = count >= 3 ? "block" : "none";
        });

        // Remove button
        const removeBtn = entry.querySelector('.remove-cert');
        removeBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to remove this certificate entry?')) {
                entry.remove();
                updateIndexes();
            }
        });
    }

    function updateIndexes() {
        const entries = document.querySelectorAll('.certificate-entry');
        entries.forEach((entry, index) => {
            entry.querySelector('.cert-index').textContent = index + 1;
            entry.querySelector('.remove-cert-btn').style.display = entries.length > 1 ? "block" : "none";
            
            // Fix checkbox name for each clone
            const checkboxes = entry.querySelectorAll('.pill-checkbox input[type="checkbox"]');
            checkboxes.forEach(cb => {
                cb.name = `ref_block_type_mm[${index}][]`;
            });

            // Update real-time summary values
            const jrnInput = entry.querySelector('input[name="jrn[]"]');
            const certNoInput = entry.querySelector('.cert-no');
            const summarySpan = entry.querySelector('.cert-summary-no');
            const certNo = certNoInput.value || '';
            const jrn = jrnInput.value || '';
            summarySpan.textContent = `(${certNo}${jrn ? ' | JRN: ' + jrn : ''})`;
        });

        // Synchronize numeric input value and badge counter
        document.getElementById('total-cert-badge').textContent = entries.length;
        document.getElementById('bulk-cert-count').value = entries.length;
    }

    // Bulk Generation Logic
    document.getElementById('bulk-generate-btn').addEventListener('click', function() {
        const targetCount = parseInt(document.getElementById('bulk-cert-count').value);
        
        if (isNaN(targetCount) || targetCount < 1 || targetCount > MAX_CERTIFICATES) {
            alert(`Please enter a valid number between 1 and ${MAX_CERTIFICATES}.`);
            return;
        }
        
        const container = document.getElementById('certificate-container');
        let currentCount = document.querySelectorAll('.certificate-entry').length;
        
        if (targetCount === currentCount) {
            alert(`You already have exactly ${targetCount} certificates loaded.`);
            return;
        }
        
        if (targetCount > currentCount) {
            const itemsToAdd = targetCount - currentCount;
            const firstEntry = document.querySelectorAll('.certificate-entry')[0];
            
            // Collapse all existing certificate bodies
            document.querySelectorAll('.certificate-entry').forEach(entry => {
                entry.querySelector('.cert-body').style.display = 'none';
                entry.querySelector('.cert-chevron').style.transform = 'rotate(-90deg)';
            });
            
            for (let i = 0; i < itemsToAdd; i++) {
                const newEntry = firstEntry.cloneNode(true);
                const firstInputs = firstEntry.querySelectorAll('input, select, textarea');
                const newInputs = newEntry.querySelectorAll('input, select, textarea');
                
                firstInputs.forEach((source, idx) => {
                    const target = newInputs[idx];
                    if (source.type === 'file') {
                        target.value = '';
                        return;
                    }
                    if (target.classList.contains('cert-no')) {
                        return;
                    }
                    if (source.type === 'checkbox' || source.type === 'radio') {
                        target.checked = source.checked;
                    } else {
                        target.value = source.value;
                    }
                });
                
                cert_counter++;
                const formattedCounter = String(cert_counter).padStart(3, '0');
                const newCertNo = `${base_cert_no}${formattedCounter}-${year}-${project_no}`;
                newEntry.querySelector('.cert-no').value = newCertNo;
                
                // Keep new clones collapsed initially
                newEntry.querySelector('.cert-body').style.display = 'none';
                newEntry.querySelector('.cert-chevron').style.transform = 'rotate(-90deg)';
                
                container.appendChild(newEntry);
                initAccordion(newEntry);
                initHeaderSummary(newEntry);
                initEntryEvents(newEntry);
                
                newEntry.querySelectorAll('select').forEach(select => {
                    select.dispatchEvent(new Event('change'));
                });
                newEntry.querySelectorAll('input[type="radio"], input[type="checkbox"]').forEach(input => {
                    input.dispatchEvent(new Event('change'));
                });
            }
            
            // Expand the last newly added entry
            const allEntries = document.querySelectorAll('.certificate-entry');
            const lastEntry = allEntries[allEntries.length - 1];
            lastEntry.querySelector('.cert-body').style.display = 'block';
            lastEntry.querySelector('.cert-chevron').style.transform = 'rotate(0deg)';
            
            updateIndexes();
            lastEntry.scrollIntoView({ behavior: 'smooth' });
            
        } else {
            const itemsToRemove = currentCount - targetCount;
            if (confirm(`Are you sure you want to permanently remove the last ${itemsToRemove} certificate entries? Any typed data inside them will be lost.`)) {
                const allEntries = document.querySelectorAll('.certificate-entry');
                for (let i = 0; i < itemsToRemove; i++) {
                    const entryToRemove = allEntries[allEntries.length - 1 - i];
                    entryToRemove.remove();
                    cert_counter--; // Keep generator counter in sync
                }
                
                // Expand the new last remaining entry
                const remainingEntries = document.querySelectorAll('.certificate-entry');
                const lastRemaining = remainingEntries[remainingEntries.length - 1];
                lastRemaining.querySelector('.cert-body').style.display = 'block';
                lastRemaining.querySelector('.cert-chevron').style.transform = 'rotate(0deg)';
                
                updateIndexes();
            }
        }
    });

    document.getElementById('add-more-btn').addEventListener('click', function() {
        const container = document.getElementById('certificate-container');
        const entries = document.querySelectorAll('.certificate-entry');
        
        if (entries.length >= MAX_CERTIFICATES) {
            alert(`Maximum limit of ${MAX_CERTIFICATES} certificates reached.`);
            return;
        }

        const firstEntry = entries[0];
        const newEntry = firstEntry.cloneNode(true);

        // Map values from firstEntry to newEntry
        const firstInputs = firstEntry.querySelectorAll('input, select, textarea');
        const newInputs = newEntry.querySelectorAll('input, select, textarea');

        firstInputs.forEach((source, idx) => {
            const target = newInputs[idx];
            
            // Skip file inputs (must be empty)
            if (source.type === 'file') {
                target.value = '';
                return;
            }
            
            // Skip certificate number (will be auto-generated)
            if (target.classList.contains('cert-no')) {
                return;
            }

            // Copy value / checked state
            if (source.type === 'checkbox' || source.type === 'radio') {
                target.checked = source.checked;
            } else {
                target.value = source.value;
            }
        });

        // Increment certificate number
        cert_counter++;
        const formattedCounter = String(cert_counter).padStart(3, '0');
        const newCertNo = `${base_cert_no}${formattedCounter}-${year}-${project_no}`;
        newEntry.querySelector('.cert-no').value = newCertNo;

        // Visual Accordion logic for clones: Collapse all other certificate bodies, keep new expanded
        document.querySelectorAll('.certificate-entry').forEach(entry => {
            entry.querySelector('.cert-body').style.display = 'none';
            entry.querySelector('.cert-chevron').style.transform = 'rotate(-90deg)';
        });

        newEntry.querySelector('.cert-body').style.display = 'block';
        newEntry.querySelector('.cert-chevron').style.transform = 'rotate(0deg)';

        container.appendChild(newEntry);
        initAccordion(newEntry);
        initHeaderSummary(newEntry);
        initEntryEvents(newEntry);
        updateIndexes();
        
        // Trigger change events to ensure UI logic (image fields, "Other" inputs) updates correctly
        newEntry.querySelectorAll('select').forEach(select => {
            select.dispatchEvent(new Event('change'));
        });
        newEntry.querySelectorAll('input[type="radio"], input[type="checkbox"]').forEach(input => {
            input.dispatchEvent(new Event('change'));
        });

        // Scroll to new entry
        newEntry.scrollIntoView({ behavior: 'smooth' });
    });

    // Initialize first entry
    document.querySelectorAll('.certificate-entry').forEach(entry => {
        initAccordion(entry);
        initHeaderSummary(entry);
        initEntryEvents(entry);
    });
    updateIndexes();
</script>
