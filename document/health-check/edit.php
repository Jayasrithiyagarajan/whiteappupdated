<?php 
include_once('../../inc/function.php');
include_once('../../file/config.php');  

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
<div class="main-content">
    <div class="container-fluid">
        <div class="card bg-transparent pb-3">
            <div class="card-body bg-white ">
                <div class="row">
                    <div class="col-6">
                        <h4 class="pl-2 pt-3 pb-2 font-20">EDIT CRANE HEALTH CHECK CERTIFICATE</h4>
                    </div>
                    <div class="col-6 text-right">
                        <a href="index.php" class="btn btn-primary" target="_blank">View List</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <form action="update_crane_certificate.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
            <div class="row">
                <div class="col-lg-6">
                    <!-- Header Data -->
                    <div class="form-element py-30 mb-30"  style="height: 470px;">
                        <h4 class="font-20 mb-30">Header Data</h4>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Date of Inspection</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="date" class="theme-input-style" name="inspection_date" value="<?php echo $row['inspection_date']; ?>" placeholder="Date of Inspection" readonly>
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Certificate No</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" name="certificate_no" value="<?php echo $row['certificate_no']; ?>" placeholder="Certificate No" readonly>
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Report No</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" name="report_no" value="<?php echo $row['report_no']; ?>" placeholder="Report No" readonly>
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">JRN</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" name="jrn" value="<?php echo $row['jrn']; ?>" placeholder="JRN" readonly>
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Project ID</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" name="project_no" value="<?php echo $row['project_no']; ?>" placeholder="Project ID" readonly>
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
                                <input type="text" class="theme-input-style" name="customer_name" value="<?php echo $row['customer_name']; ?>" placeholder="Customer Name" readonly>
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Customer Email</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="email" class="theme-input-style" name="customer_email" value="<?php echo $row['customer_email']; ?>" placeholder="Customer Email" readonly>
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Mobile</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="number" class="theme-input-style" name="mobile" value="<?php echo $row['mobile']; ?>" placeholder="Mobile" readonly>
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Inspector</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" name="inspector" value="<?php echo $row['inspector']; ?>" placeholder="Inspector Name" readonly>
                            </div>
                        </div>

                         <!-- Technical Manager Dropdown -->
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
                        
                        
                        
                        <!-- Technical Manager Dropdown -->
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
            
            <!-- General Information and other sections will follow similar to the create.php file -->
            <!-- Pre-fill all fields with data from $certificate_data -->


            <div class="row">
                <div class="col-lg-12">
                    <!-- General Information -->
                    <div class="form-element py-30 multiple-column">
                        <h4 class="font-20 mb-20">A. GENERAL INFORMATION</h4>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Vessel Name & Location</label>
                                    <input type="text" class="theme-input-style" name="vessel_name_location" value="<?php echo $row['vessel_name_location']; ?>"  placeholder="Vessel Name & Location">
                                </div>
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Manufacturer</label>
                                    <input type="text" class="theme-input-style" name="manufacturer" value="<?php echo $row['manufacturer']; ?>" placeholder="Manufacturer">
                                </div>
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Type of Crane</label>
                                    <input type="text" class="theme-input-style" name="crane_type" value="<?php echo $row['crane_type']; ?>" placeholder="Type of Crane">
                                </div>
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Asset Number</label>
                                    <input type="text" class="theme-input-style" name="asset_number" value="<?php echo $row['asset_number']; ?>" placeholder="Asset Number">
                                </div>
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Serial Number</label>
                                    <input type="text" class="theme-input-style" name="serial_number" value="<?php echo $row['serial_number']; ?>" placeholder="Serial Number">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Model</label>
                                    <input type="text" class="theme-input-style" name="model" value="<?php echo $row['model']; ?>" placeholder="Model">
                                </div>
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Manufacturing Year</label>
                                    <input type="text" class="theme-input-style" name="manufacturing_year" value="<?php echo $row['manufacturing_year']; ?>" placeholder="Manufacturing Year">
                                </div>
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Address</label>
                                    <input type="text" class="theme-input-style" name="address" value="<?php echo $row['address']; ?>" placeholder="Address">
                                </div>
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Capacity (SWL)</label>
                                    <input type="text" class="theme-input-style" name="capacity_swl" value="<?php echo $row['capacity_swl']; ?>" placeholder="Capacity (SWL)">
                                </div>
                                <div class="form-group">
                                    <label class="font-14 bold mb-2">Date of Previous Test of Crane</label>
                                    <input type="text" class="theme-input-style" name="previous_test_date" value="<?php echo $row['previous_test_date']; ?>" placeholder="Date of Previous Test of Crane">
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
                                    <input type="date" class="theme-input-style" name="latest_inspection_date" value="<?php echo $row['latest_inspection_date']; ?>" placeholder="Date of Previous Test of Crane">
                                </div>
        
    </div>
</div>


                               

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
            
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-element py-30">
                        <button type="submit" name="update" class="btn btn-success">Update Certificate</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<?php include_once('../../inc/footer.php'); ?>