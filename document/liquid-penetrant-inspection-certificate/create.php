<?php
include_once('../../inc/function.php');
include_once('../../file/config.php');

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
        $currentYear = date('Y');

        $certQuery = "SELECT COUNT(*) AS count FROM liquid_penetrant_inspection WHERE certificate_no LIKE 'CLP-%-$currentYear-%'";
        $certResult = $conn->query($certQuery);
        $certCount = 0;

        if ($certResult && $row = $certResult->fetch_assoc()) {
            $certCount = (int)$row['count'];
        }

        $nextNumber = $certCount + 1;
        $formattedNumber = str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        $newCertificateNo = "CLP-{$formattedNumber}-{$currentYear}-{$project_no}";
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

    /* Mobile Responsiveness */
    @media (max-width: 991px) {
        .form-element {
            padding: 25px 20px !important;
        }
        .font-20 {
            font-size: 18px !important;
        }
        .btn-primary, .btn.long {
            width: 100% !important;
        }
    }
</style>

<div class="main-content">
    <div class="container-fluid">
        <div class="glass-header">
            <div class="row align-items-center">
                <div class="col-8">
                    <h1 class="font-20">LIQUID PENETRANT INSPECTION CERTIFICATE</h1>
                </div>
                <div class="col-4 text-right">
                    <a href="index.php" class="btn-primary" target="_blank">View List</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <form action="display.php" method="POST" enctype="multipart/form-data">
            <!-- Header & Customer Pairs -->
            <div class="row equal-height">
                <div class="col-lg-6">
                    <div class="form-element">
                        <h4>Header Data</h4>
                        <div class="form-row">
                            <label>Date of Inspection</label>
                            <input type="date" class="theme-input-style" name="inspection_date" value="<?php echo $data['inspection_date'] ?? ''; ?>" readonly>
                        </div>
                        <div class="form-row">
                            <label>Certificate No</label>
                            <input type="text" class="theme-input-style" name="certificate_no" value="<?= $newCertificateNo ?>" readonly>
                        </div>
                        <div class="form-row">
                            <label>Report No</label>
                            <input type="text" class="theme-input-style" name="report_no" value="<?php echo $data['report_no'] ?? ''; ?>" readonly>
                        </div>
                        <div class="form-row">
                            <label>JRN</label>
                            <input type="text" class="theme-input-style" name="jrn" placeholder="JRN">
                        </div>
                        <div class="form-row">
                            <label>Project ID</label>
                            <input type="text" class="theme-input-style" name="project_no" value="<?php echo $data['project_no'] ?? ''; ?>" readonly>
                        </div>
                        <div class="form-row">
                            <label>SITE/LOCATION</label>
                            <input type="text" class="theme-input-style" name="location" value="<?php echo $data['city'] ?? ''; ?>" placeholder="Site/Location">
                        </div>
                        <div class="form-row">
                            <label>NEXT INSPECTION DATE</label>
                            <input type="date" class="theme-input-style" name="next_inspection_date" value="<?php echo $data['next_inspection_due_date'] ?? ''; ?>" readonly>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-element">
                        <h4>Customer Information / Inspector</h4>
                        <div class="form-row">
                            <label>Customer Name</label>
                            <input type="text" class="theme-input-style" name="customer_name" value="<?php echo $data['customer_name'] ?? ''; ?>" readonly>
                        </div>
                        <div class="form-row">
                            <label>Customer Email</label>
                            <input type="email" class="theme-input-style" name="customer_email" value="<?php echo $data['customer_email'] ?? ''; ?>" readonly>
                        </div>
                        <div class="form-row">
                            <label>Mobile</label>
                            <input type="number" class="theme-input-style" name="mobile" value="<?php echo $data['customer_mobile'] ?? ''; ?>" readonly>
                        </div>
                        <div class="form-row">
                            <label>Inspector</label>
                            <input type="text" class="theme-input-style" name="inspector" value="<?php echo $data['inspector_name'] ?? ''; ?>" readonly>
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

            <!-- Testing Preparation & Tools -->
            <div class="row">
                <div class="col-12">
                    <div class="form-element">
                        <h4>Testing Preparation & Tools</h4>
                        <div class="row">
                            <div class="col-lg-6">
                                <h5>Preparation</h5>
                                <div class="form-group">
                                    <label>Material</label>
                                    <input type="text" class="theme-input-style" name="material" placeholder="Material">
                                </div>
                                <div class="form-group">
                                    <label>Surface Temperature</label>
                                    <input type="text" class="theme-input-style" name="surface_temperature" placeholder="Surface Temperature">
                                </div>
                                <h5 class="mt-4">Tools & Procedure</h5>
                                <div class="form-group">
                                    <label>Technique/Procedure</label>
                                    <input type="text" class="theme-input-style" name="technique_procedure" placeholder="Technique/Procedure">
                                </div>
                                <div class="form-group">
                                    <label>Brand</label>
                                    <input type="text" class="theme-input-style" name="brand" placeholder="Brand">
                                </div>
                                <div class="form-group">
                                    <label>Penetrant</label>
                                    <input type="text" class="theme-input-style" name="penetrant" placeholder="Penetrant">
                                </div>
                                <div class="form-group">
                                    <label>Penetrant Apply</label>
                                    <input type="text" class="theme-input-style" name="penetrant_apply" placeholder="Penetrant Apply">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <h5>Additional Details</h5>
                                <div class="form-group">
                                    <label>Dwell Time</label>
                                    <input type="text" class="theme-input-style" name="dwell_time" placeholder="Dwell Time">
                                </div>
                                <div class="form-group">
                                    <label>Cleaner</label>
                                    <input type="text" class="theme-input-style" name="cleaner" placeholder="Cleaner">
                                </div>
                                <div class="form-group">
                                    <label>Remove Apply</label>
                                    <input type="text" class="theme-input-style" name="remove_apply" placeholder="Remove Apply">
                                </div>
                                <div class="form-group">
                                    <label>Developer</label>
                                    <input type="text" class="theme-input-style" name="developer" placeholder="Developer">
                                </div>
                                <div class="form-group">
                                    <label>Developer Apply</label>
                                    <input type="text" class="theme-input-style" name="developer_apply" placeholder="Developer Apply">
                                </div>
                                <div class="form-group">
                                    <label>Developing Time</label>
                                    <input type="text" class="theme-input-style" name="developing_time" placeholder="Developing Time">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Testing Result -->
            <div class="row">
                <div class="col-12">
                    <div class="form-element">
                        <h4>Testing Result</h4>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Description</label>
                                    <input type="text" class="theme-input-style" name="description" placeholder="Description">
                                </div>
                                <div class="form-group">
                                    <label>Item Checked</label>
                                    <input type="text" class="theme-input-style" name="item_checked" placeholder="Item Checked">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Results</label>
                                    <input type="text" class="theme-input-style" name="results" placeholder="Results">
                                </div>
                                <div class="form-group">
                                    <label>Condition</label>
                                    <input type="text" class="theme-input-style" name="condition_new" placeholder="Condition">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Image Uploads -->
            <div class="row">
                <div class="col-12">
                    <div class="form-element">
                        <h4>Upload Inspection Images</h4>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>IMAGE 1</label>
                                    <input type="file" class="theme-input-style" name="image_1" accept="image/*">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>IMAGE 2</label>
                                    <input type="file" class="theme-input-style" name="image_2" accept="image/*">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>IMAGE 3</label>
                                    <input type="file" class="theme-input-style" name="image_3" accept="image/*">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Button -->
            <div class="text-center mt-5 mb-5">
                <button type="submit" class="btn long" name="save_all">Generate Certificate</button>
            </div>
        </form>
    </div>
</div>

<?php include_once('../../inc/footer.php'); ?>