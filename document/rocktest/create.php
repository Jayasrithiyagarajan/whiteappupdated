<?php
include_once('../../inc/function.php');
include_once('../../file/config.php');

// Ensure `project_no` is set in the request
if (isset($_GET['project_no']) && !empty($_GET['project_no'])) {
    $project_no = $_GET['project_no'];

    $query = "
    SELECT 
        p.project_no, p.customer_name, p.customer_email, p.customer_mobile, p.inspector_name, p.equipment_location,
        c.checklist_no, c.inspection_date, c.crane_serial_no, c.capacity_swl, c.equipmenttype,
        r.report_no, r.jrn, r.date_of_inspection, r.next_inspection_due_date, r.manufacturer, r.model
    FROM 
        project_info p
    LEFT JOIN 
        checklist_information c ON p.project_no = c.project_no
    LEFT JOIN 
        reports r ON p.project_no = r.project_no
    WHERE 
        p.project_no = ?
    ORDER BY 
        r.date_of_inspection DESC
    LIMIT 1
    ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $project_no);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();

        // Generate certificate number in format CRT-001-2025-project_id
        $currentYear = date('Y');

        // Count existing certificates for the current year
        $certQuery = "SELECT COUNT(*) AS count FROM rocking_test_certificate WHERE certificate_no LIKE 'CRT-%-$currentYear-%'";
        $certResult = $conn->query($certQuery);
        $certCount = 0;

        if ($certResult && $row = $certResult->fetch_assoc()) {
            $certCount = (int)$row['count'];
        }

        // Next certificate number
        $nextNumber = $certCount + 1;

        // Format the number with leading zeros (e.g., 001, 002)
        $formattedNumber = str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        // Final certificate number
        $newCertificateNo = "CRT-{$formattedNumber}-{$currentYear}-{$project_no}";

    } else {
        $data = null;
    }
} else {
    echo "Invalid or missing project ID.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rocking Test Certificate | Creation</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<!-- Main Content -->
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
    }

    .form-element {
        background: var(--glass-bg) !important;
        backdrop-filter: blur(15px);
        border: 1px solid var(--glass-border) !important;
        border-radius: 25px !important;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.08) !important;
        padding: 40px !important;
        margin-bottom: 30px !important;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%; /* Ensure card fills column height */
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
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        color: #1a202c;
        letter-spacing: -0.5px;
        margin: 0;
    }

    .theme-input-style, .form-control, .input-style {
        background: rgba(255, 255, 255, 0.6) !important;
        border: 1px solid rgba(0, 0, 0, 0.08) !important;
        border-radius: 12px !important;
        padding: 12px 18px !important;
        font-family: 'Outfit', sans-serif;
        height: auto !important;
        line-height: 1.5 !important;
        transition: all 0.3s ease !important;
        width: 100%;
        color: #2d3748;
    }

    .theme-input-style:focus, .form-control:focus, .input-style:focus {
        background: rgba(255, 255, 255, 0.9) !important;
        border-color: #4facfe !important;
        box-shadow: 0 0 0 4px rgba(79, 172, 254, 0.1) !important;
        outline: none;
    }

    .btn-primary, .btn-generate {
        background: var(--primary-gradient) !important;
        border: none !important;
        border-radius: 15px !important;
        padding: 14px 35px !important;
        font-weight: 600 !important;
        font-family: 'Outfit', sans-serif;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: white !important;
        box-shadow: 0 4px 15px rgba(0, 198, 255, 0.3) !important;
        transition: all 0.3s ease !important;
        display: inline-block;
        text-decoration: none;
    }

    .btn-primary:hover, .btn-generate:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 25px rgba(0, 198, 255, 0.4) !important;
        color: white !important;
    }

    .label-style {
        color: #4a5568;
        font-weight: 600;
        margin-bottom: 10px !important;
        display: block;
        font-size: 14px;
    }

    .form-row {
        margin-bottom: 25px !important;
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 25px;
        display: block;
        border-bottom: 2px solid rgba(79, 172, 254, 0.3);
        padding-bottom: 10px;
    }

    .grid-4-cols {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1.5rem;
    }

    /* Mobile Responsiveness */
    @media (max-width: 768px) {
        .form-element {
            padding: 25px 20px !important;
        }
        
        .font-20 {
            font-size: 18px !important;
        }

        .btn-generate {
            width: 100% !important;
        }

        .glass-header {
            padding: 15px !important;
        }
    }
</style>

<div class="main-content">
    <div class="container-fluid">
        <div class="glass-header">
            <div class="row align-items-center">
                <div class="col-6">
                    <h1 class="font-20">ROCKING TEST CERTIFICATE</h1>
                </div>
                <div class="col-6 text-right">
                    <a href="index.php" class="btn-primary">View Registry</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <form action="display.php" method="POST">
            <div class="row equal-height">
                <!-- Header Data Section -->
                <div class="col-lg-6">
                    <div class="form-element">
                        <h4 class="card-title">Header Data</h4>
                        <div class="form-row">
                            <label class="label-style">Date of Inspection</label>
                            <input type="date" class="input-style" name="inspection_date" value="<?php echo $data['inspection_date'] ?? ''; ?>" readonly>
                        </div>
                        <div class="form-row">
                            <label class="label-style">Certificate No</label>
                            <input type="text" class="input-style" name="certificate_no" value="<?= $newCertificateNo ?>" readonly>
                        </div>
                        <div class="form-row">
                            <label class="label-style">Report No</label>
                            <input type="text" class="input-style" name="report_no" value="<?php echo $data['report_no'] ?? ''; ?>" readonly>
                        </div>
                        <div class="form-row">
                            <label class="label-style">JRN</label>
                            <input type="text" class="input-style" value="<?php echo $data['jrn'] ?? ''; ?>" name="jrn" placeholder="Enter JRN" required>
                        </div>
                        <div class="form-row">
                            <label class="label-style">Project ID</label>
                            <input type="text" class="input-style" name="project_no" value="<?php echo $data['project_no'] ?? ''; ?>" readonly>
                        </div>
                        <div class="form-row">
                            <label class="label-style">Site / Location</label>
                            <input type="text" class="input-style" name="location" value="<?php echo $data['equipment_location'] ?? ''; ?>" readonly>
                        </div>
                        <div class="form-row">
                            <label class="label-style">Next Inspection Date</label>
                            <input type="date" class="input-style" name="next_inspection_date" value="<?php echo $data['next_inspection_due_date'] ?? ''; ?>">
                        </div>
                    </div>
                </div>

                <!-- Customer Information Section -->
                <div class="col-lg-6">
                    <div class="form-element">
                        <h4 class="card-title">Customer & Inspector Info</h4>
                        <div class="form-row">
                            <label class="label-style">Customer Name</label>
                            <input type="text" class="input-style" name="customer_name" value="<?php echo $data['customer_name'] ?? ''; ?>" readonly>
                        </div>
                        <div class="form-row">
                            <label class="label-style">Customer Email</label>
                            <input type="email" class="input-style" name="customer_email" value="<?php echo $data['customer_email'] ?? ''; ?>" readonly>
                        </div>
                        <div class="form-row">
                            <label class="label-style">Mobile</label>
                            <input type="text" class="input-style" name="mobile" value="<?php echo $data['customer_mobile'] ?? ''; ?>" readonly>
                        </div>
                        <div class="form-row">
                            <label class="label-style">Inspector</label>
                            <input type="text" class="input-style" name="inspector" value="<?php echo $data['inspector_name'] ?? ''; ?>" readonly>
                        </div>
                        <div class="form-row">
                            <label class="label-style">Technical Manager</label>
                            <select class="input-style" name="technical_manager">
                                <option value="Venancio Z. Vera">Venancio Z. Vera</option>
                                <option value="Mohammed Fathy">Mohammed Fathy</option>
                                <option value="Khaled A. Alghamdi">Khaled A. Alghamdi</option>
                            </select>
                        </div>
                        <div class="form-row">
                            <label class="label-style">Quality Controller</label>
                            <select class="input-style" name="quality_controller">
                                <option value="Samuel Bhatti">Samuel Bhatti</option>
                                <option value="Veera">Veera</option>
                                <option value="Sathish">Sathish</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row equal-height">
                <!-- Additional Details Section -->
                <div class="col-lg-6">
                    <div class="form-element">
                        <h4 class="card-title">Administrative Details</h4>
                        <div class="form-row">
                            <label class="label-style">Date of Report</label>
                            <input type="date" class="input-style" name="report_date" value="<?php echo $data['date_of_inspection'] ?? ''; ?>">
                        </div>
                        <div class="form-row">
                            <label class="label-style">Color Code</label>
                            <input type="text" class="input-style" name="color_code" placeholder="Enter color code">
                        </div>
                        <div class="form-row">
                            <label class="label-style">Applicable Standards</label>
                            <input type="text" class="input-style" name="applicable_standards" placeholder="Enter standards">
                        </div>
                    </div>
                </div>

                <!-- Inspection Details Section -->
                <div class="col-lg-6">
                    <div class="form-element">
                        <h4 class="card-title">Inspection Details</h4>
                        <div class="row">
                            <div class="col-md-6 form-row">
                                <label class="label-style">Inspected Item Type</label>
                                <input type="text" class="input-style" name="inspected_item_type" value="<?php echo $data['equipmenttype'] ?? ''; ?>">
                            </div>
                            <div class="col-md-6 form-row">
                                <label class="label-style">Identification No</label>
                                <input type="text" class="input-style" name="identification_no">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-row">
                                <label class="label-style">Quantity</label>
                                <input type="text" class="input-style" name="quantity">
                            </div>
                            <div class="col-md-6 form-row">
                                <label class="label-style">WLL / SWL</label>
                                <input type="text" class="input-style" name="wll_swl" value="<?php echo $data['capacity_swl'] ?? ''; ?>">
                            </div>
                        </div>

                        <div class="form-row">
                            <label class="label-style">Description</label>
                            <textarea class="input-style" name="description" rows="3"><?php 
                                echo 
                                    (isset($data['manufacturer']) && $data['manufacturer'] !== '' 
                                        ? 'Manufacturer: ' . $data['manufacturer'] . '. ' 
                                        : '') 
                                    . 
                                    (isset($data['model']) && $data['model'] !== '' 
                                        ? 'Model: ' . $data['model'] 
                                        : '');
                            ?></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-row">
                                <label class="label-style">Last Exam Date</label>
                                <input type="text" class="input-style" name="last_exam_date">
                            </div>
                        </div>
                        <div class="row">                                
                            <div class="col-md-6 form-row">
                                <label class="label-style">This Exam Date</label>
                                <input type="date" class="input-style" name="this_exam_date" value="<?php echo $data['date_of_inspection'] ?? ''; ?>">
                            </div>
                            <div class="col-md-6 form-row">
                                <label class="label-style">Next Exam Date</label>
                                <input type="date" class="input-style" name="next_exam_date" value="<?php echo $data['next_inspection_due_date'] ?? ''; ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 form-row">
                                <label class="label-style">Reason for Exam</label>
                                <select class="input-style" name="reason_for_exam">
                                    <option value="A">3 Monthly: A</option>
                                    <option value="B">6 Monthly: B</option>
                                    <option value="C">12 Monthly: C</option>
                                    <option value="D">Written Scheme: D</option>
                                    <option value="E">Exceptional Circumstance: E</option>
                                </select>
                            </div>
                            <div class="col-md-4 form-row">
                                <label class="label-style">Status</label>
                                <select class="input-style" name="status">
                                    <option value="ND">ND - No Defect</option>
                                    <option value="SDR">SDR - See Defect Report</option>
                                    <option value="NF">NF - Not Found</option>
                                </select>
                            </div>
                            <div class="col-md-4 form-row">
                                <label class="label-style">Safe to Use</label>
                                <select class="input-style" name="safe_to_use">
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="form-element">
                        <h4 class="card-title">Grease Sample & Measurements</h4>
                        <div class="form-row">
                            <label class="label-style">Grease Sample Condition After Analyzing</label>
                            <input type="text" class="input-style" name="grease_condition" placeholder="Enter condition results">
                        </div>

                        <hr class="my-4" style="border-top: 1px solid rgba(0,0,0,0.1);">

                        <div class="mb-4">
                            <h5 class="bold mb-3" style="color: #2d3748;">Last Measured Limits (to be compared)</h5>
                            <div class="grid-4-cols">
                                <div>
                                    <label class="label-style">AFT (mm)</label>
                                    <input type="text" class="input-style" name="last_aft" placeholder="0.00">
                                </div>
                                <div>
                                    <label class="label-style">STBD (mm)</label>
                                    <input type="text" class="input-style" name="last_stbd" placeholder="0.00">
                                </div>
                                <div>
                                    <label class="label-style">FORWARD (mm)</label>
                                    <input type="text" class="input-style" name="last_forward" placeholder="0.00">
                                </div>
                                <div>
                                    <label class="label-style">PORT SIDE (mm)</label>
                                    <input type="text" class="input-style" name="last_port_side" placeholder="0.00">
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5 class="bold mb-3" style="color: #2d3748;">Actual Deviation Measured by Dial Gauge</h5>
                            <div class="grid-4-cols">
                                <div>
                                    <label class="label-style">AFT (mm)</label>
                                    <input type="text" class="input-style" name="actual_aft" placeholder="0.00">
                                </div>
                                <div>
                                    <label class="label-style">STBD (mm)</label>
                                    <input type="text" class="input-style" name="actual_stbd" placeholder="0.00">
                                </div>
                                <div>
                                    <label class="label-style">FORWARD (mm)</label>
                                    <input type="text" class="input-style" name="actual_forward" placeholder="0.00">
                                </div>
                                <div>
                                    <label class="label-style">PORT SIDE (mm)</label>
                                    <input type="text" class="input-style" name="actual_port_side" placeholder="0.00">
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5 class="bold mb-3" style="color: #2d3748;">Permitted Limits to be Compared</h5>
                            <div class="grid-4-cols">
                                <div>
                                    <label class="label-style">AFT (mm)</label>
                                    <input type="text" class="input-style" name="permitted_aft" placeholder="0.00">
                                </div>
                                <div>
                                    <label class="label-style">STBD (mm)</label>
                                    <input type="text" class="input-style" name="permitted_stbd" placeholder="0.00">
                                </div>
                                <div>
                                    <label class="label-style">FORWARD (mm)</label>
                                    <input type="text" class="input-style" name="permitted_forward" placeholder="0.00">
                                </div>
                                <div>
                                    <label class="label-style">PORT SIDE (mm)</label>
                                    <input type="text" class="input-style" name="permitted_port_side" placeholder="0.00">
                                </div>
                            </div>
                        </div>

                        <div>
                            <h5 class="bold mb-3" style="color: #2d3748;">Result / OK or Defect of SGOCC</h5>
                            <div class="grid-4-cols">
                                <div>
                                    <label class="label-style">AFT</label>
                                    <input type="text" class="input-style" name="result_aft" placeholder="OK/Defect">
                                </div>
                                <div>
                                    <label class="label-style">STBD</label>
                                    <input type="text" class="input-style" name="result_stbd" placeholder="OK/Defect">
                                </div>
                                <div>
                                    <label class="label-style">FORWARD</label>
                                    <input type="text" class="input-style" name="result_forward" placeholder="OK/Defect">
                                </div>
                                <div>
                                    <label class="label-style">PORT SIDE</label>
                                    <input type="text" class="input-style" name="result_port_side" placeholder="OK/Defect">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-5 mb-5">
                <button type="submit" class="btn-generate" name="save_all">
                    Generate Certificate
                </button>
            </div>
        </form>
    </div>
</div>

<?php include_once('../../inc/footer.php'); ?>
