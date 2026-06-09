<?php
include_once('../../inc/function.php');
include_once('../../file/config.php');

if (!isset($_GET['project_no']) || empty($_GET['project_no'])) {
    die("Invalid Project No");
}

$project_no = $_GET['project_no'];

/* ================= AUTO FETCH ================= */
$sql = "
SELECT 
    p.project_no,
    p.customer_name,
    p.equipment_location,
    p.inspector_name,
    r.report_no,
    r.date_of_inspection,
    r.next_inspection_due_date,
    r.manufacturer,
    r.model,
    r.type,
    r.capacity,
    r.equipment_id_no,
    c.crane_serial_no
FROM project_info p
LEFT JOIN reports r ON p.project_no = r.project_no
LEFT JOIN checklist_information c ON p.project_no = c.project_no
WHERE p.project_no = ?
LIMIT 1
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $project_no);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

/* ================= CERTIFICATE NO ================= */
$year = date('Y');
$countQ = $conn->query(
    "SELECT COUNT(*) c FROM lmi_certificates 
     WHERE certificate_no LIKE 'LMI-%-$year-%'"
);
$count = $countQ->fetch_assoc()['c'] + 1;
$certificate_no = "LMI-" . str_pad($count, 3, '0', STR_PAD_LEFT) . "-$year-$project_no";
?>

<!DOCTYPE html>
<html>
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

    .theme-input-style, .form-control, .input-style, .theme-input-style.bg-auto {
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

    .btn-primary {
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

    .btn-primary:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 25px rgba(0, 198, 255, 0.4) !important;
        color: white !important;
    }

    label, .label-style {
        color: #4a5568;
        font-weight: 600;
        margin-bottom: 10px !important;
        display: block;
        font-size: 14px;
    }

    .col-md-6, .col-md-4, .col-md-12 {
        margin-bottom: 20px;
    }

    .card-title, h4, h5 {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 25px;
        display: block;
        border-bottom: 2px solid rgba(79, 172, 254, 0.3);
        padding-bottom: 10px;
    }

    .table {
        background: rgba(255, 255, 255, 0.4);
        border-radius: 15px;
        overflow: hidden;
        border-collapse: separate;
        border-spacing: 0;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .table th {
        background: rgba(79, 172, 254, 0.1);
        color: #2d3748;
        font-weight: 700;
        border: none !important;
        padding: 15px !important;
    }

    .table td {
        padding: 12px !important;
        border-color: rgba(0, 0, 0, 0.05) !important;
        vertical-align: middle !important;
    }

    .table input, .table select {
        background: rgba(255, 255, 255, 0.8) !important;
        border: 1px solid rgba(0, 0, 0, 0.1) !important;
        border-radius: 8px !important;
        padding: 8px 12px !important;
        width: 100%;
        font-family: 'Outfit', sans-serif;
    }

    /* Mobile Responsiveness */
    @media (max-width: 768px) {
        .form-element {
            padding: 25px 20px !important;
        }
        
        .font-20 {
            font-size: 18px !important;
        }

        .btn-primary {
            width: 100% !important;
        }

        .glass-header {
            padding: 15px !important;
        }

        .table-responsive {
            border: none;
        }
    }
</style>

<div class="main-content">
    <div class="container-fluid">
        <div class="glass-header">
            <div class="row align-items-center">
                <div class="col-6">
                    <h1 class="font-20">LMI CERTIFICATE CREATION</h1>
                </div>
                <div class="col-6 text-right">
                    <a href="index.php" class="btn-primary">View Registry</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <form action="save-lmi.php" method="POST">
            <input type="hidden" name="project_no" value="<?= $project_no ?>">

            <!-- ================= HEADER DATA ================= -->
            <div class="row equal-height">
                <div class="col-lg-12">
                    <div class="form-element">
                        <h4>General Information</h4>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Certificate No</label>
                                <input class="theme-input-style bg-auto" name="certificate_no" value="<?= $certificate_no ?>" readonly>
                            </div>
                            <div class="col-md-4">
                                <label>Report No</label>
                                <input class="theme-input-style bg-auto" name="report_no" value="<?= $data['report_no'] ?>" readonly>
                            </div>
                            <div class="col-md-4">
                                <label>Customer Name</label>
                                <input class="theme-input-style bg-auto" name="customer_name" value="<?= $data['customer_name'] ?>" readonly>
                            </div>
                            <div class="col-md-4">
                                <label>Location</label>
                                <input class="theme-input-style" name="location" value="<?= $data['equipment_location'] ?>">
                            </div>
                            <div class="col-md-4">
                                <label>Inspection Date</label>
                                <input type="date" class="theme-input-style" name="inspection_date" value="<?= $data['date_of_inspection'] ?>">
                            </div>
                            <div class="col-md-4">
                                <label>Next Inspection Date</label>
                                <input type="date" class="theme-input-style" name="next_inspection_date" value="<?= $data['next_inspection_due_date'] ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ================= CRANE & LMI DETAILS ================= -->
            <div class="row equal-height">
                <div class="col-lg-6">
                    <div class="form-element">
                        <h5>Crane Details</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Manufacturer</label>
                                <input class="theme-input-style bg-auto" name="crane_make" value="<?= $data['manufacturer'] ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label>Model</label>
                                <input class="theme-input-style bg-auto" name="crane_model" value="<?= $data['model'] ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label>Type</label>
                                <input class="theme-input-style bg-auto" name="crane_type" value="<?= $data['type'] ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label>Capacity</label>
                                <input class="theme-input-style bg-auto" name="crane_capacity" value="<?= $data['capacity'] ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label>Serial No</label>
                                <input class="theme-input-style bg-auto" name="crane_serial_no" value="<?= $data['crane_serial_no'] ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label>ID No</label>
                                <input class="theme-input-style bg-auto" name="crane_id_no" value="<?= $data['equipment_id_no'] ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label>Boom Min (m)</label>
                                <input class="theme-input-style" name="boom_min">
                            </div>
                            <div class="col-md-6">
                                <label>Boom Max (m)</label>
                                <input class="theme-input-style" name="boom_max">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-element">
                        <h5>Load Moment Indicator (LMI)</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Manufacturer</label>
                                <input class="theme-input-style" name="lmi_make" value="TADANO">
                            </div>
                            <div class="col-md-6">
                                <label>Model</label>
                                <input class="theme-input-style" name="lmi_model_type">
                            </div>
                            <div class="col-md-6">
                                <label>Type</label>
                                <input class="theme-input-style" name="lmi_type">
                            </div>
                            <div class="col-md-6">
                                <label>Serial No</label>
                                <input class="theme-input-style" name="lmi_serial">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ================= LOAD CELL ================= -->
            <div class="row equal-height">
                <div class="col-lg-12">
                    <div class="form-element">
                        <h5>Standard Load Cell</h5>
                        <div class="row">
                            <div class="col-md-3"><label>Make</label><input class="theme-input-style" name="lc_make"></div>
                            <div class="col-md-3"><label>Model</label><input class="theme-input-style" name="lc_model_type"></div>
                            <div class="col-md-3"><label>Type</label><input class="theme-input-style" name="lc_type"></div>
                            <div class="col-md-3"><label>Serial No</label><input class="theme-input-style" name="lc_serial"></div>
                            <div class="col-md-3"><label>Capacity</label><input class="theme-input-style" name="lc_capacity"></div>
                            <div class="col-md-3"><label>Accuracy</label><input class="theme-input-style" name="lc_accuracy"></div>
                            <div class="col-md-6"><label>Certificate No</label><input class="theme-input-style" name="lc_cert_no"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ================= CALIBRATION TABLES ================= -->
            <div class="row equal-height">
                <div class="col-lg-6">
                    <div class="form-element">
                        <h5>Boom Length Calibration</h5>
                        <div class="table-responsive">
                            <table class="table text-center">
                                <thead>
                                    <tr><th>Position</th><th>Actual</th><th>LMI</th><th>Remarks</th></tr>
                                </thead>
                                <tbody>
                                    <?php foreach(['min'=>'Min','mid'=>'Medium','max'=>'Max'] as $k=>$v): ?>
                                    <tr>
                                        <td class="font-weight-bold"><?= $v ?></td>
                                        <td><input name="boom_len_<?= $k ?>_actual"></td>
                                        <td><input name="boom_len_<?= $k ?>_lmi"></td>
                                        <td>
                                            <select name="boom_len_<?= $k ?>_remark">
                                                <option>Ok</option><option>NA</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-element">
                        <h5>Main Boom Angle Calibration</h5>
                        <div class="table-responsive">
                            <table class="table text-center">
                                <thead>
                                    <tr><th>Position</th><th>Actual</th><th>LMI</th><th>Remarks</th></tr>
                                </thead>
                                <tbody>
                                    <?php foreach(['min'=>'Min','mid'=>'Medium','max'=>'Max'] as $k=>$v): ?>
                                    <tr>
                                        <td class="font-weight-bold"><?= $v ?></td>
                                        <td><input name="angle_<?= $k ?>_actual"></td>
                                        <td><input name="angle_<?= $k ?>_lmi"></td>
                                        <td>
                                            <select name="angle_<?= $k ?>_remark">
                                                <option>Ok</option><option>NA</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ================= RADIUS LOAD ================= -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-element">
                        <h5>Radius Load Comparison</h5>
                        <div class="table-responsive">
                            <table class="table text-center">
                                <thead>
                                    <tr>
                                        <th>Radius</th>
                                        <th>Length</th>
                                        <th>As per Load Chart</th>
                                        <th>LMI Reading</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td rowspan="2"><strong>MAIN</strong></td>
                                        <td><input type="text" name="main_length_3m" value="3 Mtr"></td>
                                        <td><input type="text" name="main_3m_chart" value="28 Ton"></td>
                                        <td><input type="text" name="main_3m_lmi" value="28 Ton"></td>
                                        <td><select name="main_3m_remark"><option>Ok</option><option>NA</option></select></td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" name="main_length_24m" value="24 Mtr"></td>
                                        <td><input type="text" name="main_24m_chart" value="3.6 Ton"></td>
                                        <td><input type="text" name="main_24m_lmi" value="3.6 Ton"></td>
                                        <td><select name="main_24m_remark"><option>Ok</option><option>NA</option></select></td>
                                    </tr>
                                    <tr>
                                        <td><strong>AUX</strong></td>
                                        <td><input type="text" name="aux_length" value="3 - 36 Mtr"></td>
                                        <td><input type="text" name="aux_chart" value="5.6 Ton"></td>
                                        <td><input type="text" name="aux_lmi" value="5.6 Ton"></td>
                                        <td><select name="aux_remark"><option>Ok</option><option>NA</option></select></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ================= LOAD CELL CALIBRATION ================= -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-element">
                        <h5>Load Cell Calibration</h5>
                        <div class="table-responsive">
                            <table class="table text-center">
                                <thead>
                                    <tr><th>Actual Load</th><th>Standard</th><th>LMI</th><th>Remarks</th></tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input name="load_actual"></td>
                                        <td><input name="load_standard"></td>
                                        <td><input name="load_lmi"></td>
                                        <td><select name="load_remark"><option>Ok</option><option>NA</option></select></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ================= SAFETY & APPROVAL ================= -->
            <div class="row equal-height">
                <div class="col-lg-6">
                    <div class="form-element">
                        <h5>Safety & Verification</h5>
                        <div class="form-row">
                            <label>Anti Two Block Condition</label>
                            <input class="theme-input-style" name="anti_two_block" value="OK, Tested and Verified">
                        </div>
                        <div class="form-row">
                            <label>Over Load & Lockout</label>
                            <input class="theme-input-style" name="overload_lockout" value="OK, Tested">
                        </div>
                        <div class="form-row">
                            <label>Inspector</label>
                            <input class="theme-input-style" name="inspector" value="<?= $data['inspector_name'] ?>">
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-element">
                        <h5>Final Approval</h5>
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

            <div class="text-center mt-5 mb-5">
                <button class="btn-primary" name="save_lmi">Generate LMI Certificate</button>
                <p class="mt-3 text-muted" style="font-size: 12px;">FRM.0712.1 (Rev.00)</p>
            </div>

        </form>
    </div>
</div>

<?php include_once('../../inc/footer.php'); ?>
</body>
</html>
