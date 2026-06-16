<?php
include_once('../../inc/function.php');
include_once('../../file/config.php');

// Fetch the record ID from the URL
$project_no = $_GET['project_no'] ?? null;

if ($project_no) {
    // Fetch the existing record from the database
    $sql = "SELECT * FROM rocking_test_certificate WHERE project_no = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $project_no);
    $stmt->execute();
    $result = $stmt->get_result();
    $certificate = $result->fetch_assoc();
    $stmt->close();
    
    if (!$certificate) {
        // Redirect if no record found
        header("Location: index.php");
        exit();
    }
} else {
    // Redirect or handle the case where no ID is provided
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Rocking Test Certificate</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
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

        .theme-input-style, .form-control, .input-style, select, textarea {
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

        .theme-input-style:focus, .form-control:focus, .input-style:focus, select:focus, textarea:focus {
            background: rgba(255, 255, 255, 0.9) !important;
            border-color: #4facfe !important;
            box-shadow: 0 0 0 4px rgba(79, 172, 254, 0.1) !important;
            outline: none;
        }

        .btn-primary, .btn-generate, .btn.long {
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
            cursor: pointer;
        }

        .btn-primary:hover, .btn-generate:hover, .btn.long:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(0, 198, 255, 0.4) !important;
            color: white !important;
        }

        .label-style, label {
            color: #4a5568;
            font-weight: 600;
            margin-bottom: 10px !important;
            display: block;
            font-size: 14px;
        }

        .form-row {
            margin-bottom: 25px !important;
        }

        .card-title, h4, h5 {
            font-size: 1.25rem;
            font-family: 'Outfit', sans-serif;
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
        @media (max-width: 991px) {
            .form-element {
                padding: 25px 20px !important;
                height: auto !important;
            }
            
            .font-20 {
                font-size: 18px !important;
            }

            .btn-generate, .btn-primary, .btn.long {
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
</head>
<body>

<div class="main-content">
    <div class="container-fluid">
        <div class="glass-header">
            <div class="row align-items-center">
                <div class="col-6">
                    <h1 class="font-20">EDIT ROCKING TEST CERTIFICATE</h1>
                </div>
                <div class="col-6 text-right">
                    <a href="index.php" class="btn-primary" target="_blank">View Registry</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <form action="update.php" method="POST">
            <input type="hidden" name="project_no" value="<?php echo htmlspecialchars($certificate['project_no'] ?? ''); ?>">
            
            <div class="row equal-height">
                <!-- Header Data Section -->
                <div class="col-lg-6">
                    <div class="form-element">
                        <h4 class="card-title">Header Data</h4>
                        <div class="form-row">
                            <label class="label-style">Date of Inspection</label>
                            <input type="date" class="theme-input-style" name="inspection_date" value="<?php echo htmlspecialchars($certificate['inspection_date'] ?? ''); ?>" required>
                        </div>
                        <div class="form-row">
                            <label class="label-style">Certificate No</label>
                            <input type="text" class="theme-input-style" name="certificate_no" value="<?php echo htmlspecialchars($certificate['certificate_no'] ?? ''); ?>" readonly>
                        </div>
                        <div class="form-row">
                            <label class="label-style">Report No</label>
                            <input type="text" class="theme-input-style" name="report_no" value="<?php echo htmlspecialchars($certificate['report_no'] ?? ''); ?>" required>
                        </div>
                        <div class="form-row">
                            <label class="label-style">JRN</label>
                            <input type="text" class="theme-input-style" name="jrn" value="<?php echo htmlspecialchars($certificate['jrn'] ?? ''); ?>" required>
                        </div>
                        <div class="form-row">
                            <label class="label-style">Project ID</label>
                            <input type="text" class="theme-input-style" name="project_no" value="<?php echo htmlspecialchars($certificate['project_no'] ?? ''); ?>" required readonly>
                        </div>
                        <div class="form-row">
                            <label class="label-style">Site / Location</label>
                            <input type="text" class="theme-input-style" name="location" value="<?php echo htmlspecialchars($certificate['location'] ?? ''); ?>" required>
                        </div>
                        <div class="form-row">
                            <label class="label-style">Next Inspection Date</label>
                            <input type="date" class="theme-input-style" name="next_inspection_date" value="<?php echo htmlspecialchars($certificate['next_inspection_date'] ?? ''); ?>" required>
                        </div>
                        <div class="form-row">
                            <label class="label-style">Date of Report</label>
                            <input type="date" class="theme-input-style" name="report_date" value="<?php echo htmlspecialchars($certificate['report_date'] ?? ''); ?>">
                        </div>
                        <div class="form-row">
                            <label class="label-style">Color Code</label>
                            <input type="text" class="theme-input-style" name="color_code" value="<?php echo htmlspecialchars($certificate['color_code'] ?? ''); ?>">
                        </div>
                        <div class="form-row">
                            <label class="label-style">Applicable Standards</label>
                            <input type="text" class="theme-input-style" name="applicable_standards" value="<?php echo htmlspecialchars($certificate['applicable_standards'] ?? ''); ?>">
                        </div>
                    </div>
                </div>

                <!-- Customer & Inspector Information -->
                <div class="col-lg-6">
                    <div class="form-element">
                        <h4 class="card-title">Customer Information / Inspector</h4>
                        <div class="form-row">
                            <label class="label-style">Customer Name</label>
                            <input type="text" class="theme-input-style" name="customer_name" value="<?php echo htmlspecialchars($certificate['customer_name'] ?? ''); ?>" required>
                        </div>
                        <div class="form-row">
                            <label class="label-style">Customer Email</label>
                            <input type="email" class="theme-input-style" name="customer_email" value="<?php echo htmlspecialchars($certificate['customer_email'] ?? ''); ?>" required>
                        </div>
                        <div class="form-row">
                            <label class="label-style">Mobile</label>
                            <input type="text" class="theme-input-style" name="mobile" value="<?php echo htmlspecialchars($certificate['mobile'] ?? ''); ?>" required>
                        </div>
                        <div class="form-row">
                            <label class="label-style">Inspector Name</label>
                            <input type="text" class="theme-input-style" name="inspector" value="<?php echo htmlspecialchars($certificate['inspector'] ?? ''); ?>" required>
                        </div>
                        <div class="form-row">
                            <label class="label-style">Technical Manager</label>
                            <select class="theme-input-style" name="technical_manager">
                                <option value="Venancio Z. Vera" <?php echo ($certificate['technical_manager'] ?? '') === 'Venancio Z. Vera' ? 'selected' : ''; ?>>Venancio Z. Vera</option>
                                <option value="Mohammed Fathy" <?php echo ($certificate['technical_manager'] ?? '') === 'Mohammed Fathy' ? 'selected' : ''; ?>>Mohammed Fathy</option>
                                <option value="Khaled A. Alghamdi" <?php echo ($certificate['technical_manager'] ?? '') === 'Khaled A. Alghamdi' ? 'selected' : ''; ?>>Khaled A. Alghamdi</option>
                            </select>
                        </div>
                        <div class="form-row">
                            <label class="label-style">Quality Controller</label>
                            <select class="theme-input-style" name="quality_controller">
                                <option value="Veera" <?php echo ($certificate['quality_controller'] ?? '') === 'Veera' ? 'selected' : ''; ?>>Veera</option>
                                <option value="Sathish" <?php echo ($certificate['quality_controller'] ?? '') === 'Sathish' ? 'selected' : ''; ?>>Sathish</option>
                                <option value="Samuel Bhatti" <?php echo ($certificate['quality_controller'] ?? '') === 'Samuel Bhatti' ? 'selected' : ''; ?>>Samuel Bhatti</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Inspection Details Section -->
            <div class="row equal-height mt-4">
                <div class="col-12">
                    <div class="form-element">
                        <h4 class="card-title">Inspection Details</h4>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label class="label-style">Inspected Item Type</label>
                                    <input type="text" class="theme-input-style" name="inspected_item_type" value="<?php echo htmlspecialchars($certificate['inspected_item_type'] ?? ''); ?>" required>
                                </div>
                                <div class="form-row">
                                    <label class="label-style">Identification No.</label>
                                    <input type="text" class="theme-input-style" name="identification_no" value="<?php echo htmlspecialchars($certificate['identification_no'] ?? ''); ?>" required>
                                </div>
                                <div class="form-row">
                                    <label class="label-style">Quantity</label>
                                    <input type="text" class="theme-input-style" name="quantity" value="<?php echo htmlspecialchars($certificate['quantity'] ?? ''); ?>">
                                </div>
                                <div class="form-row">
                                    <label class="label-style">WLL/SWL</label>
                                    <input type="text" class="theme-input-style" name="wll_swl" value="<?php echo htmlspecialchars($certificate['wll_swl'] ?? ''); ?>">
                                </div>
                                <div class="form-row">
                                    <label class="label-style">Reason for Examination</label>
                                    <select class="theme-input-style" name="reason_for_exam">
                                        <option value="A" <?php echo ($certificate['reason_for_exam'] ?? '') == 'A' ? 'selected' : ''; ?>>3 Monthly: A</option>
                                        <option value="B" <?php echo ($certificate['reason_for_exam'] ?? '') == 'B' ? 'selected' : ''; ?>>6 Monthly: B</option>
                                        <option value="C" <?php echo ($certificate['reason_for_exam'] ?? '') == 'C' ? 'selected' : ''; ?>>12 Monthly: C</option>
                                        <option value="D" <?php echo ($certificate['reason_for_exam'] ?? '') == 'D' ? 'selected' : ''; ?>>Written Scheme: D</option>
                                        <option value="E" <?php echo ($certificate['reason_for_exam'] ?? '') == 'E' ? 'selected' : ''; ?>>Exceptional Circumstance: E</option>
                                    </select>
                                </div>
                                <div class="form-row">
                                    <label class="label-style">Status</label>
                                    <select class="theme-input-style" name="status">
                                        <option value="ND" <?php echo ($certificate['status'] ?? '') == 'ND' ? 'selected' : ''; ?>>ND - No Defect</option>
                                        <option value="SDR" <?php echo ($certificate['status'] ?? '') == 'SDR' ? 'selected' : ''; ?>>SDR - See Defect Report</option>
                                        <option value="NF" <?php echo ($certificate['status'] ?? '') == 'NF' ? 'selected' : ''; ?>>NF - Not Found</option>
                                    </select>
                                </div>
                                <div class="form-row">
                                    <label class="label-style">Safe To Use</label>
                                    <select class="theme-input-style" name="safe_to_use">
                                        <option value="Yes" <?php echo ($certificate['safe_to_use'] ?? '') == 'Yes' ? 'selected' : ''; ?>>Yes</option>
                                        <option value="No" <?php echo ($certificate['safe_to_use'] ?? '') == 'No' ? 'selected' : ''; ?>>No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label class="label-style">Description</label>
                                    <textarea class="theme-input-style" name="description" rows="4" required><?php echo htmlspecialchars($certificate['description'] ?? ''); ?></textarea>
                                </div>
                                <div class="form-row">
                                    <label class="label-style">Last Examination Date</label>
                                    <input type="text" class="theme-input-style" name="last_exam_date" value="<?php echo htmlspecialchars($certificate['last_exam_date'] ?? ''); ?>">
                                </div>
                                <div class="form-row">
                                    <label class="label-style">This Examination Date</label>
                                    <input type="date" class="theme-input-style" name="this_exam_date" value="<?php echo htmlspecialchars($certificate['this_exam_date'] ?? ''); ?>">
                                </div>
                                <div class="form-row">
                                    <label class="label-style">Next Examination Date</label>
                                    <input type="date" class="theme-input-style" name="next_exam_date" value="<?php echo htmlspecialchars($certificate['next_exam_date'] ?? ''); ?>">
                                </div>
                                <div class="form-row">
                                    <label class="label-style">Grease Sample Condition After Analyzing</label>
                                    <input type="text" class="theme-input-style" name="grease_condition" value="<?php echo htmlspecialchars($certificate['grease_condition'] ?? ''); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Test Grid Data -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="form-element">
                        <h4 class="card-title">Grease Sample & Measurements</h4>

                        <div class="mb-4">
                            <h5 class="bold mb-3" style="color: #2d3748;">Last Measured Limits to be compared</h5>
                            <div class="grid-4-cols">
                                <div><label class="label-style">AFT (mm)</label><input type="text" class="theme-input-style" name="last_aft" value="<?php echo htmlspecialchars($certificate['last_aft'] ?? ''); ?>"></div>
                                <div><label class="label-style">STBD (mm)</label><input type="text" class="theme-input-style" name="last_stbd" value="<?php echo htmlspecialchars($certificate['last_stbd'] ?? ''); ?>"></div>
                                <div><label class="label-style">FORWARD (mm)</label><input type="text" class="theme-input-style" name="last_forward" value="<?php echo htmlspecialchars($certificate['last_forward'] ?? ''); ?>"></div>
                                <div><label class="label-style">PORT SIDE (mm)</label><input type="text" class="theme-input-style" name="last_port_side" value="<?php echo htmlspecialchars($certificate['last_port_side'] ?? ''); ?>"></div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5 class="bold mb-3" style="color: #2d3748;">Actual Deviation Measured by Dial Gauge Readings</h5>
                            <div class="grid-4-cols">
                                <div><label class="label-style">AFT (mm)</label><input type="text" class="theme-input-style" name="actual_aft" value="<?php echo htmlspecialchars($certificate['actual_aft'] ?? ''); ?>"></div>
                                <div><label class="label-style">STBD (mm)</label><input type="text" class="theme-input-style" name="actual_stbd" value="<?php echo htmlspecialchars($certificate['actual_stbd'] ?? ''); ?>"></div>
                                <div><label class="label-style">FORWARD (mm)</label><input type="text" class="theme-input-style" name="actual_forward" value="<?php echo htmlspecialchars($certificate['actual_forward'] ?? ''); ?>"></div>
                                <div><label class="label-style">PORT SIDE (mm)</label><input type="text" class="theme-input-style" name="actual_port_side" value="<?php echo htmlspecialchars($certificate['actual_port_side'] ?? ''); ?>"></div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5 class="bold mb-3" style="color: #2d3748;">Permitted Limits to be Compared</h5>
                            <div class="grid-4-cols">
                                <div><label class="label-style">AFT (mm)</label><input type="text" class="theme-input-style" name="permitted_aft" value="<?php echo htmlspecialchars($certificate['permitted_aft'] ?? ''); ?>"></div>
                                <div><label class="label-style">STBD (mm)</label><input type="text" class="theme-input-style" name="permitted_stbd" value="<?php echo htmlspecialchars($certificate['permitted_stbd'] ?? ''); ?>"></div>
                                <div><label class="label-style">FORWARD (mm)</label><input type="text" class="theme-input-style" name="permitted_forward" value="<?php echo htmlspecialchars($certificate['permitted_forward'] ?? ''); ?>"></div>
                                <div><label class="label-style">PORT SIDE (mm)</label><input type="text" class="theme-input-style" name="permitted_port_side" value="<?php echo htmlspecialchars($certificate['permitted_port_side'] ?? ''); ?>"></div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5 class="bold mb-3" style="color: #2d3748;">Result/OK or Defect of SGOCC</h5>
                            <div class="grid-4-cols">
                                <div><label class="label-style">AFT</label><input type="text" class="theme-input-style" name="result_aft" value="<?php echo htmlspecialchars($certificate['result_aft'] ?? ''); ?>"></div>
                                <div><label class="label-style">STBD</label><input type="text" class="theme-input-style" name="result_stbd" value="<?php echo htmlspecialchars($certificate['result_stbd'] ?? ''); ?>"></div>
                                <div><label class="label-style">FORWARD</label><input type="text" class="theme-input-style" name="result_forward" value="<?php echo htmlspecialchars($certificate['result_forward'] ?? ''); ?>"></div>
                                <div><label class="label-style">PORT SIDE</label><input type="text" class="theme-input-style" name="result_port_side" value="<?php echo htmlspecialchars($certificate['result_port_side'] ?? ''); ?>"></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="text-center mt-5 mb-5">
                <button type="submit" name="update_certificate" class="btn long">Update Certificate</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>

<?php include_once('../../inc/footer.php'); ?>