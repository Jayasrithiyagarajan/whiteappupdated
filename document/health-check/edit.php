<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once('../../inc/function.php');
include_once('../../file/config.php');  

if (!isset($_SESSION['username'])) {
    header("Location: ../../index.php");
    exit;
}

$role = $_SESSION['role'] ?? '';
if ($role === 'admin') {
    die("Access denied. Admins are not allowed to edit certificates.");
}

// Fetch the record based on report_no
if (isset($_GET['project_no'])) {
    $project_no = $_GET['project_no']; // Assuming report_no is passed via URL

    $query = "SELECT * FROM crane_health_check_certificate WHERE project_no = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $project_no);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();  // Fetch record into $row array
    } else {
        echo "No record found!";
        exit; // Stop further execution
    }
} else {
    echo "Invalid request! No Project ID provided.";
    exit; // Stop further execution
}
?>

<!-- Main Content -->
<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap');

    :root {
        --glass-bg: rgba(255, 255, 255, 0.85);
        --glass-border: rgba(255, 255, 255, 0.5);
        --primary-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .main-content {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
        padding-top: 20px;
        font-family: 'Outfit', sans-serif;
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
    }

    .theme-input-style, .form-control {
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

    .theme-input-style:focus, .form-control:focus {
        background: rgba(255, 255, 255, 0.9) !important;
        border-color: #4facfe !important;
        box-shadow: 0 0 0 4px rgba(79, 172, 254, 0.1) !important;
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
        box-shadow: 0 4px 15px rgba(0, 198, 255, 0.3) !important;
        transition: all 0.3s ease !important;
        width: auto;
        color: white !important;
    }

    .btn-primary:hover, .btn.long:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 25px rgba(0, 198, 255, 0.4) !important;
        color: white !important;
    }

    label.bold {
        color: #4a5568;
        font-weight: 600;
        margin-bottom: 10px !important;
        display: block;
    }

    .form-group {
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
    @media (max-width: 768px) {
        .form-element {
            padding: 25px 20px !important;
        }
        
        .font-20 {
            font-size: 18px !important;
        }

        .btn.long {
            width: 100% !important;
        }

        .glass-header {
            padding: 15px !important;
        }

        .form-row > .col-sm-4 {
            margin-bottom: 5px;
        }
    }
</style>

<div class="main-content">
    <div class="container-fluid">
        <div class="glass-header">
            <div class="row align-items-center">
                <div class="col-6">
                    <h4 class="font-20">EDIT CRANE HEALTH CHECK CERTIFICATE</h4>
                </div>
                <div class="col-6 text-right">
                    <a href="index.php" class="btn btn-primary">View List</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <form action="update_crane_certificate.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
            <div class="row equal-height">
                <div class="col-lg-6">
                    <!-- Header Data -->
                    <div class="form-element py-30 mb-30">
                        <h4 class="font-20 mb-30">Header Data</h4>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Date of Inspection</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="date" class="theme-input-style" name="inspection_date" value="<?php echo htmlspecialchars($row['inspection_date']); ?>" placeholder="Date of Inspection" readonly>
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Certificate No</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" name="certificate_no" value="<?php echo htmlspecialchars($row['certificate_no']); ?>" placeholder="Certificate No" readonly>
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Report No</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" name="report_no" value="<?php echo htmlspecialchars($row['report_no']); ?>" placeholder="Report No" readonly>
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">JRN</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" name="jrn" value="<?php echo htmlspecialchars($row['jrn']); ?>" placeholder="JRN" readonly>
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Project ID</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" name="project_no" value="<?php echo htmlspecialchars($row['project_no']); ?>" placeholder="Project ID" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <!-- Customer Information / Inspector -->
                    <div class="form-element py-30 mb-30">
                        <h4 class="font-20 mb-30">Customer Information / Inspector</h4>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Customer Name</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" name="customer_name" value="<?php echo htmlspecialchars($row['customer_name']); ?>" placeholder="Customer Name" readonly>
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Customer Email</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="email" class="theme-input-style" name="customer_email" value="<?php echo htmlspecialchars($row['customer_email']); ?>" placeholder="Customer Email" readonly>
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Mobile</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="number" class="theme-input-style" name="mobile" value="<?php echo htmlspecialchars($row['mobile']); ?>" placeholder="Mobile" readonly>
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Inspector</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" name="inspector" value="<?php echo htmlspecialchars($row['inspector']); ?>" placeholder="Inspector Name" readonly>
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Technical Manager</label>
                            </div>
                            <div class="col-sm-8">
                                <select class="theme-input-style" name="technical_manager">
                                    <option value="Venancio Z. Vera" <?php echo ($row['technical_manager'] ?? '') === 'Venancio Z. Vera' ? 'selected' : ''; ?>>Venancio Z. Vera</option>
                                    <option value="Mohammed Fathy" <?php echo ($row['technical_manager'] ?? '') === 'Mohammed Fathy' ? 'selected' : ''; ?>>Mohammed Fathy</option>
                                    <option value="Khaled A. Alghamdi" <?php echo ($row['technical_manager'] ?? '') === 'Khaled A. Alghamdi' ? 'selected' : ''; ?>>Khaled A. Alghamdi</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Quality Controller</label>
                            </div>
                            <div class="col-sm-8">
                                <select class="theme-input-style" name="quality_controller">
                                    <option value="Veera" <?php echo ($row['quality_controller'] ?? '') === 'Veera' ? 'selected' : ''; ?>>Veera</option>
                                    <option value="Sathish" <?php echo ($row['quality_controller'] ?? '') === 'Sathish' ? 'selected' : ''; ?>>Sathish</option>
                                    <option value="Samuel Bhatti" <?php echo ($row['quality_controller'] ?? '') === 'Samuel Bhatti' ? 'selected' : ''; ?>>Samuel Bhatti</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-12">
                    <!-- General Information -->
                    <div class="form-element py-30 multiple-column">
                        <h4 class="font-20 mb-20">A. GENERAL INFORMATION</h4>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Vessel Name & Location</label>
                                    <input type="text" class="theme-input-style" name="vessel_name_location" value="<?php echo htmlspecialchars($row['vessel_name_location']); ?>" placeholder="Vessel Name & Location">
                                </div>
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Manufacturer</label>
                                    <input type="text" class="theme-input-style" name="manufacturer" value="<?php echo htmlspecialchars($row['manufacturer']); ?>" placeholder="Manufacturer">
                                </div>
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Type of Crane</label>
                                    <input type="text" class="theme-input-style" name="crane_type" value="<?php echo htmlspecialchars($row['crane_type']); ?>" placeholder="Type of Crane">
                                </div>
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Asset Number</label>
                                    <input type="text" class="theme-input-style" name="asset_number" value="<?php echo htmlspecialchars($row['asset_number']); ?>" placeholder="Asset Number">
                                </div>
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Serial Number</label>
                                    <input type="text" class="theme-input-style" name="serial_number" value="<?php echo htmlspecialchars($row['serial_number']); ?>" placeholder="Serial Number">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Model</label>
                                    <input type="text" class="theme-input-style" name="model" value="<?php echo htmlspecialchars($row['model']); ?>" placeholder="Model">
                                </div>
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Manufacturing Year</label>
                                    <input type="text" class="theme-input-style" name="manufacturing_year" value="<?php echo htmlspecialchars($row['manufacturing_year']); ?>" placeholder="Manufacturing Year">
                                </div>
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Address</label>
                                    <input type="text" class="theme-input-style" name="address" value="<?php echo htmlspecialchars($row['address']); ?>" placeholder="Address">
                                </div>
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Capacity (SWL)</label>
                                    <input type="text" class="theme-input-style" name="capacity_swl" value="<?php echo htmlspecialchars($row['capacity_swl']); ?>" placeholder="Capacity (SWL)">
                                </div>
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Date of Previous Test of Crane</label>
                                    <input type="text" class="theme-input-style" name="previous_test_date" value="<?php echo htmlspecialchars($row['previous_test_date']); ?>" placeholder="Date of Previous Test of Crane">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <!-- Base Horizontal Form With Icons -->
                    <div class="form-element py-30 multiple-column">
                        <h4 class="font-20 mb-20">B. GENERAL INFORMATION</h4>
                        <div class="row"> 
                            <div class="col-lg-6">
                                <!-- Crane Structure Condition -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Crane Structure Condition:</label>
                                    <select class="form-control" name="crane_structure_condition">
                                        <option value="SATISFACTORY" <?php echo ($row['crane_structure_condition'] == 'SATISFACTORY') ? 'selected' : ''; ?>>SATISFACTORY</option>
                                        <option value="NA" <?php echo ($row['crane_structure_condition'] == 'NA') ? 'selected' : ''; ?>>N/A</option>
                                    </select>
                                </div>

                                <!-- Swinging / Slewing Function -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Swinging / Slewing Function:</label>
                                    <select class="form-control" name="swinging_slewing_function">
                                        <option value="SATISFACTORY" <?php echo ($row['swinging_slewing_function'] == 'SATISFACTORY') ? 'selected' : ''; ?>>SATISFACTORY</option>
                                        <option value="NA" <?php echo ($row['swinging_slewing_function'] == 'NA') ? 'selected' : ''; ?>>N/A</option>
                                    </select>
                                </div>

                                <!-- Hydraulic & Pneumatic System -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Hydraulic & Pneumatic System:</label>
                                    <select class="form-control" name="hydraulic_pneumatic_system">
                                        <option value="SATISFACTORY" <?php echo ($row['hydraulic_pneumatic_system'] == 'SATISFACTORY') ? 'selected' : ''; ?>>SATISFACTORY</option>
                                        <option value="NA" <?php echo ($row['hydraulic_pneumatic_system'] == 'NA') ? 'selected' : ''; ?>>N/A</option>
                                    </select>
                                </div>

                                <!-- Wire Ropes Condition -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Wire Ropes Condition:</label>
                                    <select class="form-control" name="wire_ropes_condition">
                                        <option value="SATISFACTORY" <?php echo ($row['wire_ropes_condition'] == 'SATISFACTORY') ? 'selected' : ''; ?>>SATISFACTORY</option>
                                        <option value="NA" <?php echo ($row['wire_ropes_condition'] == 'NA') ? 'selected' : ''; ?>>N/A</option>
                                    </select>
                                </div>

                                <!-- Boom Lifting, Extending & Retracting -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Boom Lifting, Extending & Retracting:</label>
                                    <select class="form-control" name="boom_lifting_extending_retracting">
                                        <option value="SATISFACTORY" <?php echo ($row['boom_lifting_extending_retracting'] == 'SATISFACTORY') ? 'selected' : ''; ?>>SATISFACTORY</option>
                                        <option value="NA" <?php echo ($row['boom_lifting_extending_retracting'] == 'NA') ? 'selected' : ''; ?>>N/A</option>
                                    </select>
                                </div>

                                <!-- Emergency Boom Lowering -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Emergency Boom Lowering:</label>
                                    <select class="form-control" name="emergency_boom_lowering">
                                        <option value="SATISFACTORY" <?php echo ($row['emergency_boom_lowering'] == 'SATISFACTORY') ? 'selected' : ''; ?>>SATISFACTORY</option>
                                        <option value="NA" <?php echo ($row['emergency_boom_lowering'] == 'NA') ? 'selected' : ''; ?>>N/A</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <!-- Auto Moment Limiter (LMI) -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Auto Moment Limiter (LMI):</label>
                                    <select class="form-control" name="auto_moment_limiter">
                                        <option value="SATISFACTORY" <?php echo ($row['auto_moment_limiter'] == 'SATISFACTORY') ? 'selected' : ''; ?>>SATISFACTORY</option>
                                        <option value="NA" <?php echo ($row['auto_moment_limiter'] == 'NA') ? 'selected' : ''; ?>>N/A</option>
                                    </select>
                                </div>

                                <!-- Anti-Two-Block (A2B) Function -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Anti-Two-Block (A2B) Function:</label>
                                    <select class="form-control" name="anti_two_block">
                                        <option value="SATISFACTORY" <?php echo ($row['anti_two_block'] == 'SATISFACTORY') ? 'selected' : ''; ?>>SATISFACTORY</option>
                                        <option value="NA" <?php echo ($row['anti_two_block'] == 'NA') ? 'selected' : ''; ?>>N/A</option>
                                    </select>
                                </div>

                                <!-- Winch Drum Lock / Pawls -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Winch Drum Lock / Pawls:</label>
                                    <select class="form-control" name="winch_drum_lock_pawls">
                                        <option value="SATISFACTORY" <?php echo ($row['winch_drum_lock_pawls'] == 'SATISFACTORY') ? 'selected' : ''; ?>>SATISFACTORY</option>
                                        <option value="NA" <?php echo ($row['winch_drum_lock_pawls'] == 'NA') ? 'selected' : ''; ?>>N/A</option>
                                    </select>
                                </div>

                                <!-- Hook Block Assembly -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Hook Block Assembly:</label>
                                    <select class="form-control" name="hook_block_assembly">
                                        <option value="SATISFACTORY" <?php echo ($row['hook_block_assembly'] == 'SATISFACTORY') ? 'selected' : ''; ?>>SATISFACTORY</option>
                                        <option value="NA" <?php echo ($row['hook_block_assembly'] == 'NA') ? 'selected' : ''; ?>>N/A</option>
                                    </select>
                                </div>

                                <!-- Boom Angle Indicator -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Boom Angle Indicator:</label>
                                    <select class="form-control" name="boom_angle_indicator">
                                        <option value="SATISFACTORY" <?php echo ($row['boom_angle_indicator'] == 'SATISFACTORY') ? 'selected' : ''; ?>>SATISFACTORY</option>
                                        <option value="NA" <?php echo ($row['boom_angle_indicator'] == 'NA') ? 'selected' : ''; ?>>N/A</option>
                                    </select>
                                </div>

                                <!-- Emergency Shutdown -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Emergency Shutdown:</label>
                                    <select class="form-control" name="emergency_shutdown">
                                        <option value="SATISFACTORY" <?php echo ($row['emergency_shutdown'] == 'SATISFACTORY') ? 'selected' : ''; ?>>SATISFACTORY</option>
                                        <option value="NA" <?php echo ($row['emergency_shutdown'] == 'NA') ? 'selected' : ''; ?>>N/A</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Latest Date for Next Inspection</label>
                                    <input type="date" class="theme-input-style" name="latest_inspection_date" value="<?php echo htmlspecialchars($row['latest_inspection_date']); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-5 mb-5">
                <button type="submit" name="update" class="btn long">Update Certificate</button>
            </div>
        </form>
    </div>
</div>

<?php include_once('../../inc/footer.php'); ?>