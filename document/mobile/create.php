<?php
include_once('../../inc/function.php');
include_once('../../file/config.php');

if (isset($_GET['project_no']) && !empty($_GET['project_no'])) {
    $project_no = $_GET['project_no'];
    $query = "
    SELECT 
        p.project_no, p.customer_name, p.customer_email, p.customer_mobile, p.inspector_name,
        c.checklist_no, c.inspection_date, c.crane_serial_no, c.capacity_swl, c.model_no, c.manufacturer, c.equipment_no, c.equipmenttype, c.year_model,
        r.report_no, r.sticker_number_issued, r.location, r.date_of_inspection, r.prev_sticker_no, r.next_inspection_due_date
    FROM 
        project_info p
    LEFT JOIN 
        checklist_information c ON p.project_no = c.project_no
    LEFT JOIN 
        reports r ON p.project_no = r.project_no
    WHERE 
        p.project_no = ?
    ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $project_no);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        $currentYear = date('Y');

        $certQuery = "SELECT COUNT(*) AS count FROM mobile_crane_loadtest WHERE certificate_no LIKE 'CMC-%-" . $currentYear . "-%'";
        $certResult = $conn->query($certQuery);
        $certCount = 0;

        if ($certResult && $row = $certResult->fetch_assoc()) {
            $certCount = (int)$row['count'];
        }

        $nextNumber = $certCount + 1;
        $formattedNumber = str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        $newCertificateNo = "CMC-{$formattedNumber}-{$currentYear}-{$project_no}";
    } else {
        $data = null;
    }
} else {
    echo "Invalid or missing project ID.";
    exit;
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

    .btn-primary, .btn.long {
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

    .btn-primary:hover, .btn.long:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 25px rgba(0, 198, 255, 0.4) !important;
        color: white !important;
    }

    label, .bold {
        color: #4a5568;
        font-weight: 600;
        margin-bottom: 12px !important;
        margin-top: 22px !important;
        display: block;
        font-size: 14px;
    }

    h4 + .form-row label, 
    h4 + .question-row .question-text,
    .form-element > .form-row:first-child label,
    .form-element > .question-row:first-child .question-text,
    .form-group:first-child label {
        margin-top: 0 !important;
    }

    .form-row {
        margin-bottom: 25px !important;
    }

    h4, h5 {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 25px;
        display: block;
        border-bottom: 2px solid rgba(79, 172, 254, 0.3);
        padding-bottom: 10px;
    }

    /* Neat Radio Alignment */
    .question-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 15px 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.03);
        gap: 20px;
    }

    .question-row:last-child {
        border-bottom: none;
    }

    .question-text {
        flex: 1;
        margin-bottom: 0 !important;
        margin-top: 22px !important;
        font-size: 14px;
        line-height: 1.6;
    }

    h4 + .question-row .question-text {
        margin-top: 0 !important;
    }

    .options-group {
        display: flex;
        gap: 25px;
        align-items: center;
        white-space: nowrap;
    }

    .option-item {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 0 !important;
        cursor: pointer;
        font-weight: 600;
        color: #4a5568;
        transition: color 0.2s ease;
    }

    .option-item input[type="radio"] {
        margin: 0;
        transform: scale(1.2);
    }

    /* Mobile Responsiveness */
    @media (max-width: 991px) {
        .question-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }
        .options-group {
            width: 100%;
            justify-content: flex-start;
        }
        .form-element {
            padding: 25px 20px !important;
        }
        .font-20 {
            font-size: 18px !important;
        }
        .btn-primary, .btn.long {
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
                    <h1 class="font-20">MOBILE CRANE WITH LOAD TEST</h1>
                </div>
                <div class="col-6 text-right">
                    <a href="index.php" class="btn-primary">View List</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <form action="save_mobile_certificate.php" method="POST" enctype="multipart/form-data">
            <!-- Header & Customer Pairs -->
            <div class="row equal-height">
                <div class="col-lg-6">
                    <div class="form-element">
                        <h4>Header Data</h4>
                        <div class="form-row">
                            <label>Date of Thorough Examination</label>
                            <input type="date" class="theme-input-style" value="<?= $data['date_of_inspection'] ?? '' ?>" name="examination_date" required>
                        </div>
                        <div class="form-row">
                            <label>Date of Report</label>
                            <input type="date" class="theme-input-style" value="<?= $data['date_of_inspection'] ?? '' ?>" name="report_date" required>
                        </div>
                        <div class="form-row">
                            <label>Report No</label>
                            <input type="text" class="theme-input-style" placeholder="Report No" value="<?php echo $data['report_no'] ?? ''; ?>" name="report_no" readonly required>
                        </div>
                        <div class="form-row">
                            <label>Sticker No</label>
                            <input type="text" class="theme-input-style" placeholder="Sticker No" value="<?php echo $data['sticker_number_issued'] ?? ''; ?>" name="sticker_no" readonly required>
                        </div>
                        <div class="form-row">
                            <label>Project ID</label>
                            <input type="text" class="theme-input-style" placeholder="Project ID" value="<?php echo $data['project_no'] ?? ''; ?>" name="project_no" required readonly>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-element">
                        <h4>Customer & Inspector Info</h4>
                        <div class="form-row">
                            <label>Customer Name</label>
                            <input type="text" class="theme-input-style" value="<?php echo $data['customer_name'] ?? ''; ?>" name="customer_name" readonly required>
                        </div>
                        <div class="form-row">
                            <label>Customer Email</label>
                            <input type="email" class="theme-input-style" value="<?php echo $data['customer_email'] ?? ''; ?>" name="customer_email" readonly required>
                        </div>
                        <div class="form-row">
                            <label>Mobile</label>
                            <input type="text" class="theme-input-style" value="<?php echo $data['customer_mobile'] ?? ''; ?>" name="customer_mobile" readonly required>
                        </div>
                        <div class="form-row">
                            <label>Inspector</label>
                            <input type="text" class="theme-input-style" value="<?php echo $data['inspector_name'] ?? ''; ?>" name="inspector_name" required readonly>
                        </div>
                        <div class="form-row">
                            <label>Technical Manager</label>
                            <select class="theme-input-style" name="technical_manager">
                                <option value="Venancio Z. Vera">Venancio Z. Vera</option>
                                <option value="Mohammed Fathy">Mohammed Fathy</option>
                                <option value="Khaled A. Alghamdi">Khaled A. Alghamdi</option>
                            </select>
                        </div>
                        <div class="form-row">
                            <label>Quality Controller</label>
                            <select class="theme-input-style" name="quality_controller">
                                <option value="Samuel Bhatti">Samuel Bhatti</option>
                                <option value="Veera">Veera</option>
                                <option value="Sathish">Sathish</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Equipment Details Pairs -->
            <div class="row equal-height">
                <div class="col-lg-6">
                    <div class="form-element">
                        <h4>Equipment Details</h4>
                        <div class="form-row">
                            <label>Employer Address</label>
                            <input type="text" class="theme-input-style" value="<?php echo $data['customer_name'] ?? ''; ?>" name="employer_address" readonly required>
                        </div>
                        <div class="form-row">
                            <label>Description and Identification of the equipment:</label>
                            <input type="text" class="theme-input-style" value="<?php echo $data['equipmenttype'] ?? ''; ?>" name="equipment_description">
                        </div>
                        <div class="form-row">
                            <label>Manufacturer</label>
                            <input type="text" class="theme-input-style" value="<?php echo $data['manufacturer'] ?? ''; ?>" name="manufacturer">
                        </div>
                        <div class="form-row">
                            <label>Model</label>
                            <input type="text" class="theme-input-style" value="<?php echo $data['model_no'] ?? ''; ?>" name="model">
                        </div>
                        <div class="form-row">
                            <label>Equipment ID No.</label>
                            <input type="text" class="theme-input-style" value="<?php echo $data['equipment_no'] ?? ''; ?>" name="equipment_id">
                        </div>
                        <div class="form-row">
                            <label>Equipment Serial No.</label>
                            <input type="text" class="theme-input-style" value="<?php echo $data['crane_serial_no'] ?? ''; ?>" name="equipment_serial_no">
                        </div>
                        <div class="form-row">
                            <label>Certificate No.</label>
                            <input type="text" class="theme-input-style" value="<?= $newCertificateNo ?>" name="certificate_no" readonly>
                        </div>
                        <div class="form-row">
                            <label>JRN</label>
                            <input type="text" class="theme-input-style" placeholder="Enter JRN" name="jrn" required>
                        </div>
                        <div class="form-row">
                            <label>Common Text Area</label>
                            <textarea class="theme-input-style" name="common_text_area" rows="4" required></textarea>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-element">
                        <h4>Premises & Capacity</h4>
                        <div class="form-row">
                            <label>Address of premises at which the examination was made:</label>
                            <input type="text" class="theme-input-style" value="<?php echo $data['location'] ?? ''; ?>" name="premises_address" readonly required>
                        </div>
                        <div class="form-row">
                            <label>Safe Working Load</label>
                            <input type="text" class="theme-input-style" value="<?php echo $data['capacity_swl'] ?? ''; ?>" name="safe_working_load">
                        </div>
                        <div class="form-row">
                            <label>Date of Manufacture</label>
                            <input type="text" class="theme-input-style" value="<?php echo $data['year_model'] ?? ''; ?>" name="manufacture_date">
                        </div>
                        <div class="form-row">
                            <label>Date of Last Thorough Examination</label>
                            <input type="text" class="theme-input-style" name="last_exam_date" value="<?php echo ($data['issued_company'] ?? '') . ' ' . ($data['prev_sticker_no'] ?? ''); ?>">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Examination Conditions -->
            <div class="row equal-height">
                <div class="col-lg-6">
                    <div class="form-element">
                        <h4>Examination Type</h4>
                        <div class="question-row">
                            <label class="question-text">Is this the first examination after installation or assembly at a new site or location?</label>
                            <div class="options-group">
                                <label class="option-item"><input type="radio" name="first_examination" value="yes"> YES</label>
                                <label class="option-item"><input type="radio" name="first_examination" value="no" checked> NO</label>
                            </div>
                        </div>
                        <div class="question-row">
                            <label class="question-text">If YES, has the equipment been installed correctly?</label>
                            <div class="options-group">
                                <label class="option-item"><input type="radio" name="installed_correctly" value="yes"> YES</label>
                                <label class="option-item"><input type="radio" name="installed_correctly" value="no" checked> NO</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-element">
                        <h4>Was the examination carried out:</h4>
                        <div class="question-row">
                            <label class="question-text">Within an interval of 6 months?</label>
                            <div class="options-group">
                                <label class="option-item"><input type="radio" name="interval_6_months" value="yes"> YES</label>
                                <label class="option-item"><input type="radio" name="interval_6_months" value="no" checked> NO</label>
                            </div>
                        </div>
                        <div class="question-row">
                            <label class="question-text">Within an interval of 12 months?</label>
                            <div class="options-group">
                                <label class="option-item"><input type="radio" name="interval_12_months" value="yes" checked> YES</label>
                                <label class="option-item"><input type="radio" name="interval_12_months" value="no"> NO</label>
                            </div>
                        </div>
                        <div class="question-row">
                            <label class="question-text">In accordance with an examination scheme?</label>
                            <div class="options-group">
                                <label class="option-item"><input type="radio" name="examination_scheme" value="yes" checked> YES</label>
                                <label class="option-item"><input type="radio" name="examination_scheme" value="no"> NO</label>
                            </div>
                        </div>
                        <div class="question-row">
                            <label class="question-text">After the occurrence of exceptional circumstances?</label>
                            <div class="options-group">
                                <label class="option-item"><input type="radio" name="exceptional_circumstances" value="yes"> YES</label>
                                <label class="option-item"><input type="radio" name="exceptional_circumstances" value="no" checked> NO</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- General Information (A) -->
            <div class="row">
                <div class="col-12">
                    <div class="form-element">
                        <h4>A. GENERAL INFORMATION</h4>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Identification of any part found to have a defect which is or could become a danger to persons and a description of the defect (If none state NONE)</label>
                                    <textarea class="theme-input-style" name="identification_any_part" rows="3"> NONE </textarea>
                                </div>
                                <div class="form-group">
                                    <label>Is the above a defect which is of immediate danger to persons</label>
                                    <select class="theme-input-style" name="defect">
                                        <option value="yes">Yes</option>
                                        <option value="no" selected>No</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Is the above a defect which is not yet but could become a danger to persons:</label>
                                    <select class="theme-input-style" name="defect_future">
                                        <option value="yes">Yes</option>
                                        <option value="no" selected>No</option>    
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>If YES, state the date by when:</label>
                                    <input type="text" class="theme-input-style" name="date_defect">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Particulars of any repair, renewal or alteration required to remedy the defect identified above:</label>
                                    <textarea class="theme-input-style" name="repair_details" rows="3"> N/A </textarea>
                                </div>
                                <div class="form-group">
                                    <label>Particulars of any tests carried out as part of the examination: (If none state NONE) (SEE ATTACHED PAGE 2)</label>
                                    <textarea class="theme-input-style" name="test_particulars" rows="3">SEE ATTACHED PAGE 2</textarea>
                                </div>
                                <div class="form-group">
                                    <label>IS THIS EQUIPMENT FIT FOR PURPOSE?</label>
                                    <select class="theme-input-style" name="equipment_fit">
                                        <option value="yes" selected>Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Latest date by which next thorough examination must be carried out:</label>
                                    <input type="date" class="theme-input-style" name="latest_date_exam" value="<?php echo $data['next_inspection_due_date'] ?? ''; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Load Test (B) -->
            <div class="row">
                <div class="col-12">
                    <div class="form-element">
                        <h4>B. LOAD TEST</h4>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Boom Length (m)</label>
                                    <input type="text" class="theme-input-style" placeholder="Enter Boom Length (m)" name="boom_length">
                                </div>
                                <div class="form-group">
                                    <label>Boom Angle (°)</label>
                                    <input type="text" class="theme-input-style" placeholder="Enter Boom Angle (°)" name="boom_angle">
                                </div>
                                <div class="form-group">
                                    <label>SWL/Test Weight</label>
                                    <input type="text" class="theme-input-style" placeholder="Enter SWL/Test Weight" name="swl_test_weight">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label> Radius (m) </label>
                                    <input type="text" class="theme-input-style" placeholder="Enter Radius (m)" name="radius">
                                </div>
                                <div class="form-group">
                                    <label>COMMENTS</label>
                                    <input type="text" class="theme-input-style" placeholder="Enter Comments" value="Satisfactory" name="comments">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Result of Inspection (C) -->
            <div class="row">
                <div class="col-12">
                    <div class="form-element">
                        <h4>C. RESULT OF INSPECTION</h4>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Boom Lifting</label>
                                    <select class="theme-input-style" name="boom_lifting">
                                        <option value="GOOD">GOOD</option>
                                        <option value="N/A">N/A</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>M. Winch Hoist</label>
                                    <select class="theme-input-style" name="m_winch_hoist">
                                        <option value="GOOD">GOOD</option>
                                        <option value="N/A">N/A</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Aux. Winch Hoist</label>
                                    <select class="theme-input-style" name="aux_winch_hoist">
                                        <option value="N/A">N/A</option>
                                        <option value="GOOD">GOOD</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Boom Extending</label>
                                    <select class="theme-input-style" name="boom_extending">
                                        <option value="GOOD">GOOD</option>
                                        <option value="N/A">N/A</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Outriggers</label>
                                    <select class="theme-input-style" name="outriggers">
                                        <option value="GOOD">GOOD</option>
                                        <option value="N/A">N/A</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Swings / Slew</label>
                                    <select class="theme-input-style" name="swings_slew">
                                        <option value="GOOD">GOOD</option>
                                        <option value="N/A">N/A</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Hydraulic System</label>
                                    <select class="theme-input-style" name="hydraulic_system">
                                        <option value="GOOD">GOOD</option>
                                        <option value="N/A">N/A</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Auto Moment Limiter</label>
                                    <select class="theme-input-style" name="auto_moment_limiter">
                                        <option value="GOOD">GOOD</option>
                                        <option value="N/A">N/A</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Swing & Winch Brake</label>
                                    <select class="theme-input-style" name="swing_winch_brake">
                                        <option value="GOOD">GOOD</option>
                                        <option value="N/A">N/A</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Winch Drum Lock (Pawl)</label>
                                    <select class="theme-input-style" name="winch_drum_lock">
                                        <option value="GOOD">GOOD</option>
                                        <option value="N/A">N/A</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Leveling Device</label>
                                    <select class="theme-input-style" name="leveling_device">
                                        <option value="GOOD">GOOD</option>
                                        <option value="N/A">N/A</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Hook Block Assembly</label>
                                    <select class="theme-input-style" name="hook_block_assembly">
                                        <option value="GOOD">GOOD</option>
                                        <option value="N/A">N/A</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Boom Angle Indicator</label>
                                    <select class="theme-input-style" name="boom_angle_indicator">
                                        <option value="GOOD">GOOD</option>
                                        <option value="N/A">N/A</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Wind Speed Indicator (Anemometer)</label>
                                    <select class="theme-input-style" name="wind_speed_indicator">
                                        <option value="GOOD">GOOD</option>
                                        <option value="N/A">N/A</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-5 mb-5">
                <button type="submit" class="btn long" value="Save Certificate">Save All</button>
            </div>
        </form>
    </div>
</div>

<?php include_once('../../inc/footer.php'); ?>