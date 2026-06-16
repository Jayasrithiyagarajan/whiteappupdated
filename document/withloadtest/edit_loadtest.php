<?php
include_once('../../inc/function.php');
include_once('../../file/config.php'); // include your database connection

// Fetch existing data (assuming the certificate_no is passed via GET)
$project_no = $_GET['project_no']; // Get the certificate number from URL
$query = "SELECT * FROM withload WHERE project_no = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $project_no);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc(); // Fetch data into an associative array
$stmt->close();
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

    .theme-input-style, .form-control, .input-style, select {
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

    .theme-input-style:focus, .form-control:focus, .input-style:focus, select:focus {
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
        margin-top: 22px !important; /* Added space above labels */
        display: block;
        font-size: 14px;
    }

    /* Ensure the first label in a section doesn't have extra top space */
    h4 + .form-row label, 
    h4 + .question-row .question-text,
    .form-element > .form-row:first-child label,
    .form-element > .question-row:first-child .question-text {
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

    input[type="radio"] {
        margin-right: 8px;
        transform: scale(1.2);
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
        margin-top: 0 !important;
        font-size: 14px;
        line-height: 1.6;
        font-weight: 600;
        color: #4a5568;
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
        margin-top: 0 !important;
        cursor: pointer;
        font-weight: 600;
        color: #4a5568;
        transition: color 0.2s ease;
    }

    .option-item:hover {
        color: #4facfe;
    }

    .option-item input[type="radio"] {
        margin: 0;
        cursor: pointer;
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
            height: auto !important;
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

        .glass-header .row {
            flex-direction: column;
            gap: 15px;
            text-align: center;
        }

        .glass-header .col-6 {
            max-width: 100%;
            flex: 0 0 100%;
            text-align: center !important;
        }
    }
</style>

<div class="main-content">
    <div class="container-fluid">
        <div class="glass-header">
            <div class="row align-items-center">
                <div class="col-6">
                    <h1 class="font-20">EDIT WITH LOAD TEST</h1>
                </div>
                <div class="col-6 text-right">
                    <a href="index.php" class="btn-primary" target="_blank">View List</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <form action="update_with_load.php" method="POST">
            <input type="hidden" name="project_no" value="<?php echo htmlspecialchars($project_no); ?>" />

            <!-- Header & Customer Pairs -->
            <div class="row equal-height">
                <div class="col-lg-6">
                    <div class="form-element">
                        <h4>Header Data</h4>
                        <div class="form-row">
                            <label>Date of Thorough Examination</label>
                            <input type="date" class="theme-input-style" value="<?php echo htmlspecialchars($data['examination_date'] ?? ''); ?>" name="examination_date" required>
                        </div>
                        <div class="form-row">
                            <label>Date of Report</label>
                            <input type="date" class="theme-input-style" value="<?php echo htmlspecialchars($data['report_date'] ?? ''); ?>" name="report_date" required>
                        </div>
                        <div class="form-row">
                            <label>Report No</label>
                            <input type="text" class="theme-input-style" placeholder="Report No" value="<?php echo htmlspecialchars($data['report_no'] ?? ''); ?>" name="report_no" required readonly>
                        </div>
                        <div class="form-row">
                            <label>Sticker No</label>
                            <input type="text" class="theme-input-style" placeholder="Sticker No" value="<?php echo htmlspecialchars($data['sticker_no'] ?? ''); ?>" name="sticker_no" required readonly>
                        </div>
                        <div class="form-row">
                            <label>Project ID</label>
                            <input type="text" class="theme-input-style" placeholder="Project ID" value="<?php echo htmlspecialchars($data['project_no'] ?? ''); ?>" name="project_no" required readonly>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-element">
                        <h4>Customer & Inspector Info</h4>
                        <div class="form-row">
                            <label>Customer Name</label>
                            <input type="text" class="theme-input-style" placeholder="Type Customer Name" value="<?php echo htmlspecialchars($data['customer_name'] ?? ''); ?>" name="customer_name" required readonly>
                        </div>
                        <div class="form-row">
                            <label>Customer Email</label>
                            <input type="email" class="theme-input-style" placeholder="Type Email Address" value="<?php echo htmlspecialchars($data['customer_email'] ?? ''); ?>" name="customer_email" required readonly>
                        </div>
                        <div class="form-row">
                            <label>Mobile</label>
                            <input type="number" class="theme-input-style" placeholder="Contact Number" value="<?php echo htmlspecialchars($data['customer_mobile'] ?? ''); ?>" name="customer_mobile" required readonly>
                        </div>
                        <div class="form-row">
                            <label>Inspector</label>
                            <input type="text" class="theme-input-style" placeholder="Inspector name" value="<?php echo htmlspecialchars($data['inspector_name'] ?? ''); ?>" name="inspector_name" required readonly>
                        </div>
                        <div class="form-row">
                            <label>Technical Manager</label>
                            <select class="theme-input-style" name="technical_manager">
                                <option value="Venancio Z. Vera" <?php echo (($data['technical_manager'] ?? '') === 'Venancio Z. Vera') ? 'selected' : ''; ?>>Venancio Z. Vera</option>
                                <option value="Mohammed Fathy" <?php echo (($data['technical_manager'] ?? '') === 'Mohammed Fathy') ? 'selected' : ''; ?>>Mohammed Fathy</option>
                                <option value="Khaled A. Alghamdi" <?php echo (($data['technical_manager'] ?? '') === 'Khaled A. Alghamdi') ? 'selected' : ''; ?>>Khaled A. Alghamdi</option>
                            </select>
                        </div>
                        <div class="form-row">
                            <label>Quality Controller</label>
                            <select class="theme-input-style" name="quality_controller">
                                <option value="Samuel Bhatti" <?php echo (($data['quality_controller'] ?? '') === 'Samuel Bhatti') ? 'selected' : ''; ?>>Samuel Bhatti</option>
                                <option value="Veera" <?php echo (($data['quality_controller'] ?? '') === 'Veera') ? 'selected' : ''; ?>>Veera</option>
                                <option value="Sathish" <?php echo (($data['quality_controller'] ?? '') === 'Sathish') ? 'selected' : ''; ?>>Sathish</option>
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
                            <input type="text" class="theme-input-style" placeholder="Name and Address of employer:" value="<?php echo htmlspecialchars($data['employer_address'] ?? ''); ?>" name="employer_address" required>
                        </div>
                        <div class="form-row">
                            <label>Description and Identification of the equipment:</label>
                            <input type="text" class="theme-input-style" placeholder="Description and Identification" value="<?php echo htmlspecialchars($data['equipment_description'] ?? ''); ?>" name="equipment_description" required>
                        </div>
                        <div class="form-row">
                            <label>Manufacturer</label>
                            <input type="text" class="theme-input-style" placeholder="Manufacturer" value="<?php echo htmlspecialchars($data['manufacturer'] ?? ''); ?>" name="manufacturer" required>
                        </div>
                        <div class="form-row">
                            <label>Model</label>
                            <input type="text" class="theme-input-style" placeholder="Model:" value="<?php echo htmlspecialchars($data['model'] ?? ''); ?>" name="model" required>
                        </div>
                        <div class="form-row">
                            <label>Equipment ID No.</label>
                            <input type="text" class="theme-input-style" placeholder="Equipment ID No" value="<?php echo htmlspecialchars($data['equipment_id'] ?? ''); ?>" name="equipment_id" required>
                        </div>
                        <div class="form-row">
                            <label>Equipment Serial No.</label>
                            <input type="text" class="theme-input-style" placeholder="Equipment Serial No" value="<?php echo htmlspecialchars($data['equipment_serial_no'] ?? ''); ?>" name="equipment_serial_no" required>
                        </div>
                        <div class="form-row">
                            <label>Certificate No.</label>
                            <input type="text" class="theme-input-style" placeholder="Enter Certificate No" value="<?php echo htmlspecialchars($data['certificate_no'] ?? ''); ?>" name="certificate_no" required>
                        </div>
                        <div class="form-row">
                            <label>JRN</label>
                            <input type="text" class="theme-input-style" placeholder="Enter JRN" value="<?php echo htmlspecialchars($data['jrn'] ?? ''); ?>" name="jrn" required>
                        </div>
                        <div class="form-row">
                            <label>Common Text Area</label>
                            <textarea class="theme-input-style" placeholder="Enter text" name="common_text_area" rows="4" required><?php echo htmlspecialchars($data['common_text_area'] ?? ''); ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-element">
                        <h4>Premises & Capacity</h4>
                        <div class="form-row">
                            <label>Address of premises at which the examination was made:</label>
                            <input type="text" class="theme-input-style" placeholder="Type Address of premises" value="<?php echo htmlspecialchars($data['premises_address'] ?? ''); ?>" name="premises_address" required>
                        </div>
                        <div class="form-row">
                            <label>Safe Working Load</label>
                            <input type="text" class="theme-input-style" placeholder="Enter Safe Working Load" value="<?php echo htmlspecialchars($data['safe_working_load'] ?? ''); ?>" name="safe_working_load" required>
                        </div>
                        <div class="form-row">
                            <label>Date of Manufacture (if known):</label>
                            <input type="text" class="theme-input-style" value="<?php echo htmlspecialchars($data['manufacture_date'] ?? ''); ?>" name="manufacture_date">
                        </div>
                        <div class="form-row">
                            <label>Date of Last Thorough Examination:</label>
                            <input type="text" class="theme-input-style" name="last_exam_date" value="<?php echo htmlspecialchars($data['last_exam_date'] ?? ''); ?>">
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
                                <label class="option-item"><input type="radio" name="first_examination" value="yes" <?php echo (($data['first_examination'] ?? '') == 'yes') ? 'checked' : ''; ?>> YES</label>
                                <label class="option-item"><input type="radio" name="first_examination" value="no" <?php echo (($data['first_examination'] ?? '') == 'no') ? 'checked' : ''; ?>> NO</label>
                            </div>
                        </div>
                        <div class="question-row">
                            <label class="question-text">If YES, has the equipment been installed correctly?</label>
                            <div class="options-group">
                                <label class="option-item"><input type="radio" name="installed_correctly" value="yes" <?php echo (($data['installed_correctly'] ?? '') == 'yes') ? 'checked' : ''; ?>> YES</label>
                                <label class="option-item"><input type="radio" name="installed_correctly" value="no" <?php echo (($data['installed_correctly'] ?? '') == 'no') ? 'checked' : ''; ?>> NO</label>
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
                                <label class="option-item"><input type="radio" name="interval_6_months" value="yes" <?php echo (($data['interval_6_months'] ?? '') == 'yes') ? 'checked' : ''; ?>> YES</label>
                                <label class="option-item"><input type="radio" name="interval_6_months" value="no" <?php echo (($data['interval_6_months'] ?? '') == 'no') ? 'checked' : ''; ?>> NO</label>
                            </div>
                        </div>
                        <div class="question-row">
                            <label class="question-text">Within an interval of 12 months?</label>
                            <div class="options-group">
                                <label class="option-item"><input type="radio" name="interval_12_months" value="yes" <?php echo (($data['interval_12_months'] ?? '') == 'yes') ? 'checked' : ''; ?>> YES</label>
                                <label class="option-item"><input type="radio" name="interval_12_months" value="no" <?php echo (($data['interval_12_months'] ?? '') == 'no') ? 'checked' : ''; ?>> NO</label>
                            </div>
                        </div>
                        <div class="question-row">
                            <label class="question-text">In accordance with an examination scheme?</label>
                            <div class="options-group">
                                <label class="option-item"><input type="radio" name="examination_scheme" value="yes" <?php echo (($data['examination_scheme'] ?? '') == 'yes') ? 'checked' : ''; ?>> YES</label>
                                <label class="option-item"><input type="radio" name="examination_scheme" value="no" <?php echo (($data['examination_scheme'] ?? '') == 'no') ? 'checked' : ''; ?>> NO</label>
                            </div>
                        </div>
                        <div class="question-row">
                            <label class="question-text">After the occurrence of exceptional circumstances?</label>
                            <div class="options-group">
                                <label class="option-item"><input type="radio" name="exceptional_circumstances" value="yes" <?php echo (($data['exceptional_circumstances'] ?? '') == 'yes') ? 'checked' : ''; ?>> YES</label>
                                <label class="option-item"><input type="radio" name="exceptional_circumstances" value="no" <?php echo (($data['exceptional_circumstances'] ?? '') == 'no') ? 'checked' : ''; ?>> NO</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Information -->
            <div class="row">
                <div class="col-12">
                    <div class="form-element">
                        <h4>A. GENERAL INFORMATION</h4>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Identification of any part found to have a defect which is or could become a danger to persons and a description of the defect (If none state NONE)</label>
                                    <textarea class="theme-input-style" placeholder="Enter details" name="identification_any_part" rows="3" required><?php echo htmlspecialchars($data['identification_any_part'] ?? ''); ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Is the above a defect which is of immediate danger to persons</label>
                                    <select class="theme-input-style" name="defect">
                                        <option value="">Select</option>
                                        <option value="yes" <?php echo (($data['defect'] ?? '') == 'yes') ? 'selected' : ''; ?>>Yes</option>
                                        <option value="no" <?php echo (($data['defect'] ?? '') == 'no') ? 'selected' : ''; ?>>No</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Is the above a defect which is not yet but could become a danger to persons:</label>
                                    <select class="theme-input-style" name="defect_future">
                                        <option value="">Select</option>
                                        <option value="yes" <?php echo (($data['defect_future'] ?? '') == 'yes') ? 'selected' : ''; ?>>Yes</option>
                                        <option value="no" <?php echo (($data['defect_future'] ?? '') == 'no') ? 'selected' : ''; ?>>No</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>If YES, state the date by when:</label>
                                    <input type="text" class="theme-input-style" name="date_defect" value="<?php echo htmlspecialchars($data['date_defect'] ?? ''); ?>">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Particulars of any repair, renewal or alteration required to remedy the defect identified above:</label>
                                    <textarea class="theme-input-style" placeholder="Enter details" name="repair_details" rows="3" required><?php echo htmlspecialchars($data['repair_details'] ?? ''); ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Particulars of any tests carried out as part of the examination: (If none state NONE)</label>
                                    <textarea class="theme-input-style" placeholder="Enter details" name="test_particulars" rows="3" required><?php echo htmlspecialchars($data['test_particulars'] ?? ''); ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label>IS THIS EQUIPMENT FIT FOR PURPOSE?</label>
                                    <select class="theme-input-style" name="equipment_fit">
                                        <option value="">Select</option>
                                        <option value="yes" <?php echo (($data['equipment_fit'] ?? '') == 'yes') ? 'selected' : ''; ?>>Yes</option>
                                        <option value="no" <?php echo (($data['equipment_fit'] ?? '') == 'no') ? 'selected' : ''; ?>>No</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Latest date by which next thorough examination must be carried out:</label>
                                    <input type="date" class="theme-input-style" name="latest_date_exam" value="<?php echo htmlspecialchars($data['latest_date_exam'] ?? ''); ?>" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-5 mb-5">
                <button type="submit" class="btn long">Update Certificate</button>
            </div>
        </form>
    </div>
</div>

<?php include_once('../../inc/footer.php'); ?>