<?php
include_once('../../inc/function.php');
include_once('../../file/config.php');

// Ensure `project_no` is set in the request
if (isset($_GET['project_no']) && !empty($_GET['project_no'])) {
    $project_no = $_GET['project_no'];

    $query = "
    SELECT 
        p.project_no, p.customer_name, p.customer_email, p.customer_mobile, p.inspector_name,
        c.checklist_no, c.inspection_date, c.crane_serial_no, c.capacity_swl, c.equipment_no, c.vessel_name, c.year_model,
        r.report_no, r.jrn, r.manufacturer, r.type, r.model, r.next_inspection_due_date
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

        // Generate certificate number logic
        // Generate certificate number logic
// $currentYear = date('Y');

// $certQuery = "SELECT certificate_no FROM crane_health_check_certificate WHERE certificate_no LIKE 'CHC-$currentYear-%' ORDER BY id DESC LIMIT 1";
// $certResult = $conn->query($certQuery);

// if ($certResult->num_rows > 0) {
//     $lastCert = $certResult->fetch_assoc()['certificate_no'];

//     // Extract the numeric part
//     preg_match('/CHC-' . $currentYear . '-(\d+)/', $lastCert, $matches);
//     $nextNumber = isset($matches[1]) ? (int)$matches[1] + 1 : 1;
// } else {
//     $nextNumber = 1; // Start with 1 if no certificates for this year
// }

// // Format the new certificate number
// $newCertificateNo = sprintf("CHC-%s-%03d", $currentYear, $nextNumber);




// Generate certificate number logic
$currentYear = date('Y');

// Get the count of existing certificates for the current year
$certQuery = "SELECT COUNT(*) AS count FROM crane_health_check_certificate WHERE certificate_no LIKE 'CHC-%-$currentYear-%'";
$certResult = $conn->query($certQuery);
$certCount = 0;

if ($certResult && $row = $certResult->fetch_assoc()) {
    $certCount = (int)$row['count'];
}

// Increment for the new certificate
$nextNumber = $certCount + 1;

// Format the number with leading zeros
$formattedNumber = str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

// Generate the certificate number in the required format
$newCertificateNo = "CHC-{$formattedNumber}-{$currentYear}-{$project_no}";



        // Display or use the certificate number as needed
        // echo "<h3>Generated Certificate Number: $newCertificateNo</h3>";

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
    }

    .btn-primary:hover, .btn.long:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 25px rgba(0, 198, 255, 0.4) !important;
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
                    <h4 class="font-20">CRANE HEALTH CHECK CERTIFICATE</h4>
                </div>
                <div class="col-6 text-right">
                    <a href="index.php" class="btn btn-primary">View List</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <form action="save_crane_certificate.php" method="POST">
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
                                <input type="date" class="theme-input-style" name="inspection_date" value="<?php echo $data['inspection_date'] ?? ''; ?>" placeholder="Date of Inspection">
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Certificate No</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" name="certificate_no" placeholder="Certificate No" value="<?= $newCertificateNo ?>" readonly>
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Report No</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" name="report_no" value="<?php echo $data['report_no'] ?? ''; ?>" placeholder="Report No" readonly>
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">JRN</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" name="jrn" value="<?php echo htmlspecialchars($data['jrn'] ?? ''); ?>"  placeholder="JRN" required>
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Project ID</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" name="project_no" value="<?php echo $data['project_no'] ?? ''; ?>" placeholder="Project ID" readonly>
                            </div>
                        </div>
                        <!-- <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Company Name</label>
                            </div>
                            <div class="col-sm-8">

                                <input type="text" class="theme-input-style" name="companyName" placeholder="Company Name">
                            </div>
                        </div> -->



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
                                <input type="text" class="theme-input-style" name="customer_name" value="<?php echo $data['customer_name'] ?? ''; ?>" placeholder="Customer Name" readonly>
                                <!-- <input type="text" class="theme-input-style" name="" placeholder=""> -->
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Customer Email</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="email" class="theme-input-style" name="customer_email" value="<?php echo $data['customer_email'] ?? ''; ?>" placeholder="Type Email Address" readonly>
                                <!-- <input type="" class="theme-input-style" name="" placeholder=""> -->
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Mobile</label>
                            </div>
                            <div class="col-sm-8">

                                <input type="number" class="theme-input-style" name="mobile" value="<?php echo $data['customer_mobile'] ?? ''; ?>" placeholder="Contact Number" readonly>
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Inspector</label>
                            </div>
                            <div class="col-sm-8">
                                <!-- <input type="" class="theme-input-style" name="" placeholder=""> -->
                                <input type="text" class="theme-input-style" name="inspector" value="<?php echo $data['inspector_name'] ?? ''; ?>" placeholder="Inspector Name" readonly>
                            </div>
                        </div>
                        <!-- Add Technical Manager Dropdown -->
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Technical Manager</label>
                            </div>
                            <div class="col-sm-8">
                                <select class="theme-input-style" name="technical_manager">
                                    <option value="Venancio Z. Vera">Venancio Z. Vera</option>
                                    <option value="Mohammed Fathy">Mohammed Fathy</option>
                                    <option value="Khaled A. Alghamdi">Khaled A. Alghamdi</option>
                                </select>
                            </div>
                        </div>
                        
                        
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Quality Controller</label>
                            </div>
                            <div class="col-sm-8">
                                <select class="theme-input-style" name="quality_controller">
                                    <option value="Samuel Bhatti">Samuel Bhatti</option>
                                    <option value="Veera">Veera</option>
                                    <option value="Sathish">Sathish</option>
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
                                    <input type="text" class="theme-input-style" name="vessel_name_location" value="<?php echo $data['vessel_name'] ?? ''; ?>"  placeholder="Vessel Name & Location">
                                </div>
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Manufacturer</label>
                                    <input type="text" class="theme-input-style" name="manufacturer" value="<?php echo $data['manufacturer'] ?? ''; ?>" placeholder="Manufacturer">
                                </div>
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Type of Crane</label>
                                    <input type="text" class="theme-input-style" name="crane_type" value="<?php echo $data['type'] ?? ''; ?>" placeholder="Type of Crane">
                                </div>
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Asset Number</label>
                                    <input type="text" class="theme-input-style" name="asset_number" value="<?php echo $data['equipment_no'] ?? ''; ?>"  placeholder="Asset Number">
                                </div>
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Serial Number</label>
                                    <input type="text" class="theme-input-style" name="serial_number" value="<?php echo $data['crane_serial_no'] ?? ''; ?>" placeholder="Serial Number">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Model</label>
                                    <input type="text" class="theme-input-style" name="model" value="<?php echo $data['model'] ?? ''; ?>" placeholder="Model">
                                </div>
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Manufacturing Year</label>
                                    <input type="text" class="theme-input-style" name="manufacturing_year" value="<?php echo $data['year_model'] ?? ''; ?>" placeholder="Manufacturing Year">
                                </div>
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Address</label>
                                    <input type="text" class="theme-input-style" name="address" placeholder="Address">
                                </div>
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Capacity (SWL)</label>
                                    <input type="text" class="theme-input-style" name="capacity_swl" value="<?php echo $data['capacity_swl'] ?? ''; ?>" placeholder="Capacity (SWL)">
                                </div>
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Date of Previous Test of Crane</label>
                                    <input type="text" class="theme-input-style" name="previous_test_date" placeholder="Date of Previous Test of Crane">
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

                        <!-- Form -->
                        <!-- <form action="#" method="POST"> -->

                        <div class="row">
                            <div class="col-lg-6">
                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Crane Structure Condition:</label>
                                    <!-- Crane Structure Condition -->
                                    <select class="form-control" name="crane_structure_condition">
                                        <option value="SATISFACTORY">SATISFACTORY</option>
                                        <option value="NA">N/A</option>
                                    </select>
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Swinging / Slewing Function: </label>
                                    <!-- Swinging / Slewing Function -->
                                    <select class="form-control" name="swinging_slewing_function">
                                        <option value="SATISFACTORY">SATISFACTORY</option>
                                        <option value="NA">N/A</option>
                                    </select>
                                </div>
                                <!-- End Form Group -->
                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Hydraulic & Pneumatic System</label>
                                    <!-- Hydraulic & Pneumatic System -->
                                    <select class="form-control" name="hydraulic_pneumatic_system">
                                        <option value="SATISFACTORY">SATISFACTORY</option>
                                        <option value="NA">N/A</option>
                                    </select>
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Wire Ropes Condition:</label>
                                    <!-- Wire Ropes Condition -->
                                    <select class="form-control" name="wire_ropes_condition">
                                        <option value="SATISFACTORY">SATISFACTORY</option>
                                        <option value="NA">N/A</option>
                                    </select>
                                </div>
                                <!-- End Form Group -->
                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Boom Lifting, Extending & Retracting:</label>
                                    <!-- Boom Lifting, Extending & Retracting -->
                                    <select class="form-control" name="boom_lifting_extending_retracting">
                                        <option value="SATISFACTORY">SATISFACTORY</option>
                                        <option value="NA">N/A</option>
                                    </select>
                                </div>
                                <!-- End Form Group -->
                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Emergency Boom Lowering:</label>
                                    <!-- Emergency Boom Lowering -->
                                    <select class="form-control" name="emergency_boom_lowering">
                                        <option value="SATISFACTORY">SATISFACTORY</option>
                                        <option value="NA">N/A</option>
                                    </select>
                                </div>
                                <!-- End Form Group -->
                            </div>
                            <div class="col-lg-6">
                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Auto Moment Limiter (LMI):</label>
                                    <!-- Auto Moment Limiter (LMI) -->
                                    <select class="form-control" name="auto_moment_limiter">
                                        <option value="SATISFACTORY">SATISFACTORY</option>
                                        <option value="NA">N/A</option>
                                    </select>
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Anti-Two-Block (A2B) Function:</label>
                                    <!-- Anti-Two-Block (A2B) Function -->
                                    <select class="form-control" name="anti_two_block">
                                        <option value="SATISFACTORY">SATISFACTORY</option>
                                        <option value="NA">N/A</option>
                                    </select>
                                </div>
                                <!-- End Form Group -->
                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2"> Winch Drum Lock / Pawls:</label>
                                    <!-- Winch Drum Lock / Pawls -->
                                    <select class="form-control" name="winch_drum_lock_pawls">
                                        <option value="SATISFACTORY">SATISFACTORY</option>
                                        <option value="NA">N/A</option>
                                    </select>
                                </div>
                                <!-- End Form Group -->

                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Hook Block Assembly:</label>
                                    <!-- Hook Block Assembly -->
                                    <select class="form-control" name="hook_block_assembly">
                                        <option value="SATISFACTORY">SATISFACTORY</option>
                                        <option value="NA">N/A</option>
                                    </select>
                                </div>
                                <!-- End Form Group -->
                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Boom Angle Indicator:</label>
                                    <!-- Boom Angle Indicator -->
                                    <select class="form-control" name="boom_angle_indicator">
                                        <option value="SATISFACTORY">SATISFACTORY</option>
                                        <option value="NA">N/A</option>
                                    </select>
                                </div>
                                <!-- End Form Group -->
                                <!-- Form Group -->
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Emergency Shutdown:</label>
                                    <!-- Emergency Shutdown -->
                                    <select class="form-control" name="emergency_shutdown">
                                        <option value="SATISFACTORY">SATISFACTORY</option>
                                        <option value="NA">N/A</option>
                                    </select>
                                </div>
                                
                                <div class="form-group mt-3">
    <label class="font-14 bold mb-2">Latest Date for Next Inspection:</label>
    <input type="date" class="form-control" value="<?php echo $data['next_inspection_due_date'] ?? ''; ?>" name="latest_inspection_date">
</div>
                                <!-- End Form Group -->
                            </div>

                        </div>
                        
                        
                        
                        
                         <!--The latest date by which the next inspection-->



                        <!-- Form Row -->
                        <!-- <div class="form-row">
                                        <div class="col-12 text-center mt-4">
                                            <button type="submit" class="btn long">Save</button>
                                        </div>
                                    </div> -->
                        <!-- End Form Row -->
                        <!-- </form> -->
                        <!-- End Form -->
                    </div>
                    <!-- End Horizontal Form With Icons -->
                </div>

            </div>

            <!-- Entire Save Button -->
            <div class="form-row">
                <div class="col-12 text-center mt-4">
                    <button type="submit" class="btn long" name="save_all">Save All</button>
                </div>
            </div>
        </form>
    </div>

</div>

<?php include_once('../../inc/footer.php'); ?>