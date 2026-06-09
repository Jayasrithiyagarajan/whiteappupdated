<?php
include_once('../../inc/function.php');
include_once('../../file/config.php');

// Fetch the existing data based on the project_no passed in the URL
if (isset($_GET['project_no'])) {
    $project_no = $_GET['project_no'];
    $query = "SELECT * FROM liquid_penetrant_inspection WHERE project_no = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $project_no);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
}
?>

<!-- Main Content -->
<div class="main-content">
    <div class="container-fluid">
        <div class="card bg-transparent pb-3">
            <div class="card-body bg-white ">
                <div class="row">
                    <div class="col-6">
                        <h4 class="pl-2 pt-3 pb-2 font-20">EDIT LIQUID PENETRANT INSPECTION CERTIFICATE</h4>
                    </div>
                    <div class="col-6 text-right">
                        <a href="index.php" class="btn btn-primary" target="_blank">View List</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <form action="update.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="project_no" value="<?php echo $data['project_no']; ?>">
            <div class="row">
                <div class="col-lg-6">
                    <!-- Header Data -->
                    <div class="form-element py-30 mb-30">
                        <h4 class="font-20 mb-30">Header Data</h4>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Date of Inspection</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="date" class="theme-input-style" name="inspection_date" value="<?php echo $data['inspection_date']; ?>" placeholder="Date of Inspection" readonly>
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Certificate No</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" name="certificate_no" value="<?php echo $data['certificate_no']; ?>" placeholder="Certificate No" readonly>
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Report No</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" name="report_no" value="<?php echo $data['report_no']; ?>" placeholder="Report No" readonly>
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">JRN</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" name="jrn" value="<?php echo $data['jrn']; ?>" placeholder="JRN" >
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Project ID</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" name="project_no" value="<?php echo $data['project_no']; ?>" placeholder="Project No" readonly>
                            </div>
                        </div>
                        
                        
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">SITE/LOCATION</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" name="location" value="<?php echo $data['location']; ?>" placeholder="Site/Location">
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">NEXT INSPECTION DATE</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="date" class="theme-input-style" name="next_inspection_date" value="<?php echo $data['next_inspection_date']; ?>" placeholder="Next Inspection Date">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <!-- Customer Information / Inspector -->
                    <div class="form-element py-30 mb-30" style="height: 530px;">
                        <h4 class="font-20 mb-30">Customer Information / Inspector</h4>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Customer Name</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" name="customer_name" value="<?php echo $data['customer_name']; ?>" placeholder="Customer Name" readonly>
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Customer Email</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="email" class="theme-input-style" name="customer_email" value="<?php echo $data['customer_email']; ?>" placeholder="Type Email Address" readonly>
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Mobile</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="number" class="theme-input-style" name="mobile" value="<?php echo $data['mobile']; ?>" placeholder="Contact Number" readonly>
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Inspector</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" name="inspector" value="<?php echo $data['inspector']; ?>" placeholder="Inspector Name" readonly>
                            </div>
                        </div>
                        <!-- Add Technical Manager Dropdown -->
                         <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Technical Manager</label>
                            </div>
                            <div class="col-sm-8">
                                <select class="theme-input-style" name="technical_manager">
                                    <option value="Venancio Z. Vera" <?php echo ($data['technical_manager'] ?? '') === 'Venancio Z. Vera' ? 'selected' : ''; ?>>Venancio Z. Vera</option>
                                    <option value="Mohammed Fathy" <?php echo ($data['technical_manager'] ?? '') === 'Mohammed Fathy' ? 'selected' : ''; ?>>Mohammed Fathy</option>
                                    <option value="Khaled A. Alghamdi" <?php echo ($data['technical_manager'] ?? '') === 'Khaled A. Alghamdi' ? 'selected' : ''; ?>>Khaled A. Alghamdi</option>
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
                                    <option value="Veera" <?php echo ($data['quality_controller'] ?? '') === 'Veera' ? 'selected' : ''; ?>>Veera</option>
                                    <option value="Sathish" <?php echo ($data['quality_controller'] ?? '') === 'Sathish' ? 'selected' : ''; ?>>Sathish</option>
                                    <option value="Samuel Bhatti" <?php echo ($data['quality_controller'] ?? '') === 'Samuel Bhatti' ? 'selected' : ''; ?>>Samuel Bhatti</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Testing Preparation -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-element py-30 mb-30">
                        <h4 class="font-20 mb-30">Testing Preparation</h4>
                        
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Material</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" name="material" value="<?php echo $data['material']; ?>" placeholder="Material">
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Surface Temperature</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" name="surface_temperature" value="<?php echo $data['surface_temperature']; ?>" placeholder="Surface Temperature">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Testing Tools -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-element py-30 mb-30">
                        <h4 class="font-20 mb-30">Testing Tools</h4>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Technique/Procedure</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" name="technique_procedure" value="<?php echo $data['technique_procedure']; ?>" placeholder="Technique/Procedure">
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Brand</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" name="brand" value="<?php echo $data['brand']; ?>" placeholder="Brand">
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Penetrant</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" name="penetrant" value="<?php echo $data['penetrant']; ?>" placeholder="Penetrant">
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Penetrant Apply</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" name="penetrant_apply" value="<?php echo $data['penetrant_apply']; ?>" placeholder="Penetrant Apply">
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Dwell Time</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" name="dwell_time" value="<?php echo $data['dwell_time']; ?>" placeholder="Dwell Time">
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Cleaner</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" name="cleaner" value="<?php echo $data['cleaner']; ?>" placeholder="Cleaner">
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Remove Apply</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" name="remove_apply" value="<?php echo $data['remove_apply']; ?>" placeholder="Remove Apply">
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Developer</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" name="developer" value="<?php echo $data['developer']; ?>" placeholder="Developer">
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Developer Apply</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" name="developer_apply" value="<?php echo $data['developer_apply']; ?>" placeholder="Developer Apply">
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Developing Time</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" name="developing_time" value="<?php echo $data['developing_time']; ?>" placeholder="Developing Time">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Testing Result -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-element py-30 mb-30">
                        <h4 class="font-20 mb-30">Testing Result</h4>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Description</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" name="description" value="<?php echo $data['description']; ?>" placeholder="Description">
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Item Checked</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" name="item_checked" value="<?php echo $data['item_checked']; ?>" placeholder="Item Checked">
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Results</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" name="results" value="<?php echo $data['results']; ?>" placeholder="Results">
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Condition</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" name="condition_new" value="<?php echo $data['condition_new']; ?>" placeholder="Condition">
                            </div>
                        </div>
                        
                        <!-- Add this section anywhere in your form where you want the image upload fields to appear -->
<div class="row">
    <div class="col-lg-12">
        <div class="form-element py-30 mb-30">
            <h4 class="font-20 mb-30">Images</h4>
            
            <!-- Image 1 -->
            <div class="form-row mb-20">
                <div class="col-sm-4">
                    <label class="font-14 bold">Image 1</label>
                </div>
                <div class="col-sm-8">
                    <input type="file" class="theme-input-style" name="image_1" accept="image/*">
                    <?php if (!empty($data['image_1'])): ?>
                        <div class="mt-2">
                            <small>Current Image:</small>
                            <a href="uploads/<?php echo $data['image_1']; ?>" target="_blank" class="d-block">
                                <img src="uploads/<?php echo $data['image_1']; ?>" style="max-width: 200px; max-height: 150px;" class="img-thumbnail">
                            </a>
                            <small class="text-muted">Leave blank to keep current image</small>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Image 2 -->
            <div class="form-row mb-20">
                <div class="col-sm-4">
                    <label class="font-14 bold">Image 2</label>
                </div>
                <div class="col-sm-8">
                    <input type="file" class="theme-input-style" name="image_2" accept="image/*">
                    <?php if (!empty($data['image_2'])): ?>
                        <div class="mt-2">
                            <small>Current Image:</small>
                            <a href="uploads/<?php echo $data['image_2']; ?>" target="_blank" class="d-block">
                                <img src="uploads/<?php echo $data['image_2']; ?>" style="max-width: 200px; max-height: 150px;" class="img-thumbnail">
                            </a>
                            <small class="text-muted">Leave blank to keep current image</small>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Image 3 -->
            <div class="form-row mb-20">
                <div class="col-sm-4">
                    <label class="font-14 bold">Image 3</label>
                </div>
                <div class="col-sm-8">
                    <input type="file" class="theme-input-style" name="image_3" accept="image/*">
                    <?php if (!empty($data['image_3'])): ?>
                        <div class="mt-2">
                            <small>Current Image:</small>
                            <a href="uploads/<?php echo $data['image_3']; ?>" target="_blank" class="d-block">
                                <img src="uploads/<?php echo $data['image_3']; ?>" style="max-width: 200px; max-height: 150px;" class="img-thumbnail">
                            </a>
                            <small class="text-muted">Leave blank to keep current image</small>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="row">
                <div class="col-lg-12">
                    
                </div>
            </div>

           

            <!-- Save Button -->
            <div class="form-row">
                <div class="col-12 text-center mt-4">
                    <button type="submit" class="btn long" name="update_all">Update Certificate</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include_once('../../inc/footer.php'); ?>