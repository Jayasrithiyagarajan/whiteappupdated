<?php
include_once('../../inc/function.php');
include_once('../../file/config.php'); // include your database connection

// Fetch existing data (assuming the certificate_no is passed via GET)
$project_no = $_GET['project_no']; // Get the certificate number from URL
$query = "SELECT * FROM mobile_crane_loadtest WHERE project_no = '$project_no'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result); // Fetch data into an associative array
?>


            <!-- Main Content -->
            <div class="main-content">
                <div class="container-fluid">
                    <div class="card bg-transparent pb-3">
                        <div class="card-body bg-white ">
                            <div class="row">
                               <div class="col-6">
                                   
                        <h4 class="pl-2 pt-3 pb-2 font-20">EDIT MOBILE CRANE WITH LOAD TEST</h4>
                                </div>
                        <div class="col-6 text-right">
                       <button type="button" class="btn" >View List</button>               
                        </div>
                    </div>
                    </div>
                    </div>

            </div>
                <div class="container-fluid">
                <form action="update_mobile_load_test_certificate.php" method="POST">
                <input type="hidden" name="project_no" value="<?php echo $data['project_no']; ?>" />
                 <div class="row">
                        <div class="col-lg-6">
                            <!-- Base Horizontal Form -->
                            <div class="form-element py-30 mb-30">
                                <h4 class="font-20 mb-30">Header Data</h4>
                                <!-- Form -->
                             <!-- Form Row -->
                                   <div class="form-row mb-20">
                                        <div class="col-sm-4">
                                            <label class="font-14 bold">Date of Thorough Examination</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="date" class="theme-input-style" placeholder="Date of Thorough Examination" name="examination_date" value="<?php echo $data['examination_date']; ?>" required>
                                        </div>
                                    </div>
                                    <!-- End Form Row -->

                                    <!-- Form Row -->
                                    <div class="form-row mb-20">
                                        <div class="col-sm-4">
                                            <label class="font-14 bold">Date of Report</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="date" class="theme-input-style" placeholder="Date of Report" name="report_date" value="<?php echo $data['report_date']; ?>" required>
                                        </div>
                                    </div>
                                    <!-- End Form Row -->
                                    
                                    <!-- Form Row -->
                                    <div class="form-row mb-20">
                                        <div class="col-sm-4">
                                            <label class="font-14 bold">Report No</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" class="theme-input-style" placeholder="Report No" name="report_no" value="<?php echo $data['report_no']; ?>" readonly required>
                                        </div>
                                    </div>
                                    <!-- End Form Row -->

                                    <!-- Form Row -->
                                    <div class="form-row mb-20">
                                        <div class="col-sm-4">
                                            <label class="font-14 bold">Sticker No</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" class="theme-input-style" placeholder="Sticker No" name="sticker_no" value="<?php echo $data['sticker_no']; ?>" readonly required>
                                        </div>
                                    </div>
                               
                                <!-- End Form -->

                                <!-- Form Row -->
                                <div class="form-row mb-20">
                                        <div class="col-sm-4">
                                            <label class="font-14 bold">Project ID</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" class="theme-input-style" placeholder="Project ID" name="project_no" value="<?php echo $data['project_no']; ?>" readonly required>
                                        </div>
                                    </div>
                               
                                <!-- End Form -->


                                <!-- Form Row -->
                               
                                <!-- End Form -->
                            </div>
                            <!-- End Horizontal Form -->
                        </div>
                        <div class="col-lg-6">
                            <!-- Base Horizontal Form With Icons -->
                            <div class="form-element py-30 mb-30">
                                <h4 class="font-20 mb-30">Customer Information / Inspector </h4>

                                <!-- Form -->
                                

                                    <!-- Form Row -->
                                    <div class="form-row mb-20">
                                        <div class="col-sm-4">
                                            <label class="font-14 bold">Customer Name</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <img src="../../assets/img/svg/user3.svg" alt="" class="svg">
                                                    </div>
                                                </div>
                                                
                                                <input type="text" class="form-control pl-1" placeholder="Type Your Name"  name="customer_name" value="<?php echo $data['customer_name']; ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Form Row -->

                                    <!-- Form Row -->
                                    <div class="form-row mb-20">
                                        <div class="col-sm-4">
                                            <label class="font-14 bold">Customer Email</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <img src="../../assets/img/svg/mail3.svg" alt="" class="svg">
                                                    </div>
                                                </div>
                                                <input type="email" class="form-control pl-1" placeholder="Type Email Address" name="customer_email" value="<?php echo $data['customer_email']; ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Form Row -->

                                    <!-- Form Row -->
                                    <div class="form-row mb-20">
                                        <div class="col-sm-4">
                                            <label class="font-14 bold">Mobile</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <img src="../../assets/img/svg/mobile3.svg" alt="" class="svg">
                                                    </div>
                                                </div>
                                                <input type="number" class="form-control pl-1" placeholder="Contact Number" name="customer_mobile" value="<?php echo $data['customer_mobile']; ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Form Row -->

                                    <!-- Form Row -->
                                    <div class="form-row mb-20">
                                        <div class="col-sm-4">
                                            <label class="font-14 bold">Inspector</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <img src="../../assets/img/svg/key3.svg" alt="" class="svg">
                                                    </div>
                                                </div>
                                                <input type="text" class="form-control pl-1" placeholder="Inspector name"  name="inspector_name" value="<?php echo $data['inspector_name']; ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Form Row -->  
                                    
                                    
                                    <!-- Technical Manager Dropdown -->
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


                                
                                <!-- End Form -->
                            </div>
                            <!-- End Horizontal Form With Icons -->
                        </div>                       


<!-- <body part> -->



<div class="col-lg-6">
                            <!-- Base Horizontal Form -->
                            <div class="form-element py-30 mb-30">
                                <!-- <h4 class="font-20 mb-30">Header Data</h4> -->
                                <!-- Form -->
                              


<!-- Form Row -->
<div class="form-row mb-20">
                                        <div class="col-sm-4">
                                            <label class="font-14 bold">Name and Address of employer for whom the thorough examination was made:</label>
                                        </div>
                                        <div class="col-sm-8">
                                        <input type="text" class="theme-input-style" placeholder="Name and Address of employer:" name="employer_address" value="<?php echo $data['employer_address']; ?>">
                                        </div>
                                    </div>
                                    <!-- End Form Row -->
                                    <!-- <h4 class="font-20 mb-30">Customer Information / Inspector </h4> -->

                                                                       
                                    <!-- Form Row -->
                                    <div class="form-row mb-20">
                                        <div class="col-sm-4">
                                            <label class="font-14 bold">Description and Identification of the equipment:</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" class="theme-input-style" placeholder="Description and Identification of the equipment:" name="equipment_description" value="<?php echo $data['equipment_description']; ?>">
                                        </div>
                                    </div>
                                    <!-- End Form Row -->
                                    
                                    <!-- Form Row -->
                                    <div class="form-row mb-20">
                                        <div class="col-sm-4">
                                            <label class="font-14 bold">Manufacturer</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" class="theme-input-style" placeholder="Manufacturer" name="manufacturer" value="<?php echo $data['manufacturer']; ?>">
                                        </div>
                                    </div>

                                    <div class="form-row mb-20">
                                        <div class="col-sm-4">
                                            <label class="font-14 bold">Model:</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" class="theme-input-style" placeholder="Model:" name="model" value="<?php echo $data['model']; ?>">
                                        </div>
                                    </div>

                                    <div class="form-row mb-20">
                                        <div class="col-sm-4">
                                            <label class="font-14 bold">Equipment ID No.: </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" class="theme-input-style" placeholder="Equipment ID No"  name="equipment_id" value="<?php echo $data['equipment_id']; ?>">
                                        </div>
                                    </div>

                                    <div class="form-row mb-20">
                                        <div class="col-sm-4">
                                            <label class="font-14 bold">Equipment Serial No.:</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" class="theme-input-style" placeholder="Equipment Serial No" name="equipment_serial_no" value="<?php echo $data['equipment_serial_no']; ?>">
                                        </div>
                                    </div>

                                    <div class="form-row mb-20">
                                        <div class="col-sm-4">
                                            <label class="font-14 bold"> Certificate No.:
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" class="theme-input-style" placeholder="Enter Certificate No" name="certificate_no" value="<?php echo $data['certificate_no']; ?>" readonly>
                                        </div>
                                    </div>


                                    <div class="form-row mb-20">
                                        <div class="col-sm-4">
                                            <label class="font-14 bold"> JRN:</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" class="theme-input-style" placeholder="Enter JRN" name="jrn" value="<?php echo $data['jrn']; ?>">
                                        </div>
                                    </div>    
                                    
                                    
                                    <div class="form-row mb-20">
                                        <div class="col-sm-4">
                                           <label class="font-14 bold">Common Text Area:</label>
                                        </div>
                                        <div class="col-sm-8">
                                          <textarea class="theme-input-style" placeholder="Enter text" name="common_text_area" rows="4" required> <?php echo htmlspecialchars($data['common_text_area']); ?></textarea>
                                         </div>
                                    </div>
                                <!-- End Form -->
                            </div>
                            <!-- End Horizontal Form -->
                        </div>
						
						
						<div class="col-lg-6">
                            <!-- Base Horizontal Form With Icons -->
                            <div class="form-element py-30 mb-30" style="height: 638px;">
                                <!-- <h4 class="font-20 mb-30">Customer Information / Inspector </h4> -->

                                <!-- Form -->
                                    <!-- Form Row -->
                                    <div class="form-row mb-20">
                                        <div class="col-sm-4">
                                            <label class="font-14 bold">Address of premises at which the examination was made:</label>
                                        </div>
                                        
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <img src="../../assets/img/svg/user3.svg" alt="" class="svg">
                                                    </div>
                                                </div>
                                                <input type="text" class="form-control pl-1" placeholder="Type Address of premises" name="premises_address" value="<?php echo $data['premises_address']; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Form Row -->

                                    <!-- Form Row -->
                                    <div class="form-row mb-20">
                                        <div class="col-sm-4">
                                            <label class="font-14 bold"> Safe Working Load:</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" class="theme-input-style" placeholder="Enter Safe Working Load" name="safe_working_load" value="<?php echo $data['safe_working_load']; ?>">
                                        </div>
                                    </div>
                                    
                                    <!-- End Form Row -->

                                    <!-- Additional Row 1 -->
<div class="form-row mb-20">
    <div class="col-sm-4">
        <label class="font-14 bold">Date of Manufacture (if known):</label>
    </div>
    <div class="col-sm-8">
        <input type="text" class="theme-input-style" name="manufacture_date" value="<?php echo $data['manufacture_date']; ?>">
    </div>
</div>
<!-- End Additional Row 1 -->

<!-- Additional Row 2 -->
<div class="form-row mb-20">
    <div class="col-sm-4">
        <label class="font-14 bold">Date of Last Thorough Examination:</label>
    </div>
    <div class="col-sm-8">
        <input type="text" class="theme-input-style" name="last_exam_date" value="<?php echo $data['last_exam_date']; ?>">
    </div>
</div>
<!-- End Additional Row 2 -->
                                    
                                
                                <!-- End Form -->
                            </div>
                            <!-- End Horizontal Form With Icons -->
                        </div>     


                        <!-- end -->




                     
                     
                      
                      <!-- start -->
                      
                <div class="col-lg-6"> 
    <!-- Base Horizontal Form -->
    <div class="form-element py-30 mb-30" style="height: 367px;">
        <!-- <h4 class="font-20 mb-30">Header Data</h4> -->
        <!-- Form -->
        
        <!-- Additional Input Row 3 (First Examination) -->
        <div class="form-row mb-20">
            <div class="col-sm-6">
                <label class="font-14 bold">Is this the first examination after installation or assembly at a new site or location?</label>
            </div>
            <div class="col-sm-6">
                <label>
                    <input type="radio" name="first_examination" value="yes" <?php echo ($data['first_examination'] == 'yes') ? 'checked' : ''; ?>> YES
                </label>
                <label>
                    <input type="radio" name="first_examination" value="no" <?php echo ($data['first_examination'] == 'no') ? 'checked' : ''; ?>> NO
                </label>
            </div>
        </div>

        <!-- Additional Input Row 4 (Equipment Installation) -->
        <div class="form-row mb-20">
            <div class="col-sm-6">
                <label class="font-14 bold">If the answer to the above question is YES, has the equipment been installed correctly?</label>
            </div>
            <div class="col-sm-6">
                <label>
                    <input type="radio" name="installed_correctly" value="yes" <?php echo ($data['installed_correctly'] == 'yes') ? 'checked' : ''; ?>> YES
                </label>
                <label>
                    <input type="radio" name="installed_correctly" value="no" <?php echo ($data['installed_correctly'] == 'no') ? 'checked' : ''; ?>> NO
                </label>
            </div>
        </div>
                                  
        <!-- End Form -->
    </div>
    <!-- End Horizontal Form -->
</div>

						
						<!-- end -->
						
						
						<div class="col-lg-6">
    <!-- Base Horizontal Form With Icons -->
    <div class="form-element py-30 mb-30">
        <h4 class="font-20 mb-30">Was the examination carried out:</h4>

        <!-- Form -->
        <!-- Additional Input Row 5 (Interval of 6 months) -->
        <div class="form-row mb-20">
            <div class="col-sm-6">
                <label class="font-14 bold">Within an interval of 6 months?</label>
            </div>
            <div class="col-sm-6">
                <label>
                    <input type="radio" name="interval_6_months" value="yes" <?php echo ($data['interval_6_months'] == 'yes') ? 'checked' : ''; ?>> YES
                </label>
                <label>
                    <input type="radio" name="interval_6_months" value="no" <?php echo ($data['interval_6_months'] == 'no') ? 'checked' : ''; ?>> NO
                </label>
            </div>
        </div>

        <!-- Additional Input Row 6 (Interval of 12 months) -->
        <div class="form-row mb-20">
            <div class="col-sm-6">
                <label class="font-14 bold">Within an interval of 12 months?</label>
            </div>
            <div class="col-sm-6">
                <label>
                    <input type="radio" name="interval_12_months" value="yes" <?php echo ($data['interval_12_months'] == 'yes') ? 'checked' : ''; ?>> YES
                </label>
                <label>
                    <input type="radio" name="interval_12_months" value="no" <?php echo ($data['interval_12_months'] == 'no') ? 'checked' : ''; ?>> NO
                </label>
            </div>
        </div>

        <!-- Additional Input Row 7 (Examination Scheme) -->
        <div class="form-row mb-20">
            <div class="col-sm-6">
                <label class="font-14 bold">In accordance with an examination scheme?</label>
            </div>
            <div class="col-sm-6">
                <label>
                    <input type="radio" name="examination_scheme" value="yes" <?php echo ($data['examination_scheme'] == 'yes') ? 'checked' : ''; ?>> YES
                </label>
                <label>
                    <input type="radio" name="examination_scheme" value="no" <?php echo ($data['examination_scheme'] == 'no') ? 'checked' : ''; ?>> NO
                </label>
            </div>
        </div>

        <!-- Additional Input Row 8 (Exceptional Circumstances) -->
        <div class="form-row mb-20">
            <div class="col-sm-6">
                <label class="font-14 bold">After the occurrence of exceptional circumstances?</label>
            </div>
            <div class="col-sm-6">
                <label>
                    <input type="radio" name="exceptional_circumstances" value="yes" <?php echo ($data['exceptional_circumstances'] == 'yes') ? 'checked' : ''; ?>> YES
                </label>
                <label>
                    <input type="radio" name="exceptional_circumstances" value="no" <?php echo ($data['exceptional_circumstances'] == 'no') ? 'checked' : ''; ?>> NO
                </label>
            </div>
        </div>                                  
        <!-- End Form -->
    </div>
    <!-- End Horizontal Form With Icons -->
</div>
    


                        <!-- end -->


<!-- Section 1 -->                      

                        <div class="col-lg-12">    
    <div class="form-element py-30 multiple-column">
        <h4 class="font-20 mb-20">A. GENERAL INFORMATION</h4>
        <!-- Form -->
        <div class="row">    
            <div class="col-lg-6">
                <div class="form-group">
                    <label class="font-14 bold mb-2">Identification of any part found to have a defect which is or could become a danger to persons and a description of the defect (If none state NONE)</label>
                    <textarea class="theme-input-style" placeholder="Enter details" name="identification_any_part"><?php echo htmlspecialchars($data['identification_any_part']); ?></textarea>
                </div>

                <div class="form-group">
                    <label class="font-14 bold mb-2">Is the above a defect which is of immediate danger to persons</label>
                    <select class="theme-input-style" name="defect">
                        <option value="yes" <?php echo ($data['defect'] == 'yes') ? 'selected' : ''; ?>>Yes</option>
                        <option value="no" <?php echo ($data['defect'] == 'no') ? 'selected' : ''; ?>>No</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="font-14 bold mb-2">Is the above a defect which is not yet but could become a danger to persons: (If YES state the date by when)</label>
                    <input type="text" class="theme-input-style" name="date_defect" value="<?php echo htmlspecialchars($data['date_defect']); ?>">
                </div>

                <div class="form-group">
                    <label class="font-14 bold mb-2">Particulars of any repair, renewal or alteration required to remedy the defect identified above:</label>
                    <textarea class="theme-input-style" placeholder="Enter details" name="repair_details"><?php echo htmlspecialchars($data['repair_details']); ?></textarea>
                </div>

                <div class="form-group">
                    <label class="font-14 bold mb-2">Particulars of any tests carried out as part of the examination: (If none state NONE) (SEE ATTACHED PAGE 2)</label>
                    <textarea class="theme-input-style" placeholder="Enter details" name="test_particulars"><?php echo htmlspecialchars($data['test_particulars']); ?></textarea>
                </div>

                <div class="form-group">
                    <label class="font-14 bold mb-2">IS THIS EQUIPMENT FIT FOR PURPOSE?</label>
                    <select class="theme-input-style" name="equipment_fit">
                        <option value="yes" <?php echo ($data['equipment_fit'] == 'yes') ? 'selected' : ''; ?>>Yes</option>
                        <option value="no" <?php echo ($data['equipment_fit'] == 'no') ? 'selected' : ''; ?>>No</option>
                    </select>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group">
                    <label class="font-14 bold mb-2">Latest date by which next thorough examination must be carried out:</label>
                    <input type="date" class="theme-input-style" name="latest_date_exam" value="<?php echo htmlspecialchars($data['latest_date_exam']); ?>">
                </div>
            </div>
        </div>
        <!-- End Form -->
    </div>
</div>
<!-- end of Section 1 -->



<!-- Section 2 -->

<div class="col-lg-12">    
    <div class="form-element py-30 multiple-column">
        <h4 class="font-20 mb-20">B. LOAD TEST</h4>
        <!-- Form -->
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <label class="font-14 bold mb-2">Boom Length (m)</label>
                    <input type="text" class="theme-input-style" placeholder="Enter Boom Length (m)" name="boom_length" value="<?php echo htmlspecialchars($data['boom_length']); ?>">
                </div>
                <div class="form-group">
                    <label class="font-14 bold mb-2">Boom Angle (°)</label>
                    <input type="text" class="theme-input-style" placeholder="Enter Boom Angle (°)" name="boom_angle" value="<?php echo htmlspecialchars($data['boom_angle']); ?>">
                </div>
                <div class="form-group">
                    <label class="font-14 bold mb-2">SWL/Test Weight</label>
                    <input type="text" class="theme-input-style" placeholder="Enter SWL/Test Weight" name="swl_test_weight" value="<?php echo htmlspecialchars($data['swl_test_weight']); ?>">
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group">
                    <label class="font-14 bold mb-2">Radius (m)</label>
                    <input type="text" class="theme-input-style" placeholder="Enter Radius (m)" name="radius" value="<?php echo htmlspecialchars($data['radius']); ?>">
                </div>
                <div class="form-group">
                    <label class="font-14 bold mb-2">COMMENTS</label>
                    <input type="text" class="theme-input-style" placeholder="Enter Comments" name="comments" value="<?php echo htmlspecialchars($data['comments']); ?>">
                </div>
            </div>
        </div>
        <!-- End Form -->
    </div>
    <!-- End Horizontal Form With Icons -->
</div>

<!-- end of section 2 -->





<!-- start of section 3 -->
<div class="col-lg-12">     
    <div class="form-element py-30 multiple-column">
        <h4 class="font-20 mb-20">C. RESULT OF INSPECTION</h4>
        <!-- Form -->
        <div class="row">
            <div class="col-lg-6">
                <!-- Boom Lifting -->
                <div class="form-group">
                    <label class="font-14 bold mb-2">Boom Lifting</label>
                    <select class="theme-input-style" name="boom_lifting">
                        <option value="GOOD" <?php echo ($data['boom_lifting'] == 'GOOD') ? 'selected' : ''; ?>>GOOD</option>
                        <option value="N/A" <?php echo ($data['boom_lifting'] == 'N/A') ? 'selected' : ''; ?>>N/A</option>
                    </select>
                </div>
                
                <!-- M. Winch Hoist -->
                <div class="form-group">
                    <label class="font-14 bold mb-2">M. Winch Hoist</label>
                    <select class="theme-input-style" name="m_winch_hoist">
                        <option value="GOOD" <?php echo ($data['m_winch_hoist'] == 'GOOD') ? 'selected' : ''; ?>>GOOD</option>
                        <option value="N/A" <?php echo ($data['m_winch_hoist'] == 'N/A') ? 'selected' : ''; ?>>N/A</option>
                    </select>
                </div>

                <!-- Aux. Winch Hoist -->
                <div class="form-group">
                    <label class="font-14 bold mb-2">Aux. Winch Hoist</label>
                    <select class="theme-input-style" name="aux_winch_hoist">
                        <option value="GOOD" <?php echo ($data['aux_winch_hoist'] == 'GOOD') ? 'selected' : ''; ?>>GOOD</option>
                        <option value="N/A" <?php echo ($data['aux_winch_hoist'] == 'N/A') ? 'selected' : ''; ?>>N/A</option>
                    </select>
                </div>

                <!-- Boom Extending -->
                <div class="form-group">
                    <label class="font-14 bold mb-2">Boom Extending</label>
                    <select class="theme-input-style" name="boom_extending">
                        <option value="GOOD" <?php echo ($data['boom_extending'] == 'GOOD') ? 'selected' : ''; ?>>GOOD</option>
                        <option value="N/A" <?php echo ($data['boom_extending'] == 'N/A') ? 'selected' : ''; ?>>N/A</option>
                    </select>
                </div>

                <!-- Outriggers -->
                <div class="form-group">
                    <label class="font-14 bold mb-2">Outriggers</label>
                    <select class="theme-input-style" name="outriggers">
                        <option value="GOOD" <?php echo ($data['outriggers'] == 'GOOD') ? 'selected' : ''; ?>>GOOD</option>
                        <option value="N/A" <?php echo ($data['outriggers'] == 'N/A') ? 'selected' : ''; ?>>N/A</option>
                    </select>
                </div>

                <!-- Swings / Slew -->
                <div class="form-group">
                    <label class="font-14 bold mb-2">Swings / Slew</label>
                    <select class="theme-input-style" name="swings_slew">
                        <option value="GOOD" <?php echo ($data['swings_slew'] == 'GOOD') ? 'selected' : ''; ?>>GOOD</option>
                        <option value="N/A" <?php echo ($data['swings_slew'] == 'N/A') ? 'selected' : ''; ?>>N/A</option>
                    </select>
                </div>

                <!-- Hydraulic System -->
                <div class="form-group">
                    <label class="font-14 bold mb-2">Hydraulic System</label>
                    <select class="theme-input-style" name="hydraulic_system">
                        <option value="GOOD" <?php echo ($data['hydraulic_system'] == 'GOOD') ? 'selected' : ''; ?>>GOOD</option>
                        <option value="N/A" <?php echo ($data['hydraulic_system'] == 'N/A') ? 'selected' : ''; ?>>N/A</option>
                    </select>
                </div>
            </div>

            <div class="col-lg-6">
                <!-- Auto Moment Limiter -->
                <div class="form-group">
                    <label class="font-14 bold mb-2">Auto Moment Limiter</label>
                    <select class="theme-input-style" name="auto_moment_limiter">
                        <option value="GOOD" <?php echo ($data['auto_moment_limiter'] == 'GOOD') ? 'selected' : ''; ?>>GOOD</option>
                        <option value="N/A" <?php echo ($data['auto_moment_limiter'] == 'N/A') ? 'selected' : ''; ?>>N/A</option>
                    </select>
                </div>

                <!-- Swing & Winch Brake -->
                <div class="form-group">
                    <label class="font-14 bold mb-2">Swing & Winch Brake</label>
                    <select class="theme-input-style" name="swing_winch_brake">
                        <option value="GOOD" <?php echo ($data['swing_winch_brake'] == 'GOOD') ? 'selected' : ''; ?>>GOOD</option>
                        <option value="N/A" <?php echo ($data['swing_winch_brake'] == 'N/A') ? 'selected' : ''; ?>>N/A</option>
                    </select>
                </div>

                <!-- Winch Drum Lock (Pawl) -->
                <div class="form-group">
                    <label class="font-14 bold mb-2">Winch Drum Lock (Pawl)</label>
                    <select class="theme-input-style" name="winch_drum_lock">
                        <option value="GOOD" <?php echo ($data['winch_drum_lock'] == 'GOOD') ? 'selected' : ''; ?>>GOOD</option>
                        <option value="N/A" <?php echo ($data['winch_drum_lock'] == 'N/A') ? 'selected' : ''; ?>>N/A</option>
                    </select>
                </div>

                <!-- Leveling Device -->
                <div class="form-group">
                    <label class="font-14 bold mb-2">Leveling Device</label>
                    <select class="theme-input-style" name="leveling_device">
                        <option value="GOOD" <?php echo ($data['leveling_device'] == 'GOOD') ? 'selected' : ''; ?>>GOOD</option>
                        <option value="N/A" <?php echo ($data['leveling_device'] == 'N/A') ? 'selected' : ''; ?>>N/A</option>
                    </select>
                </div>

                <!-- Hook Block Assembly -->
                <div class="form-group">
                    <label class="font-14 bold mb-2">Hook Block Assembly</label>
                    <select class="theme-input-style" name="hook_block_assembly">
                        <option value="GOOD" <?php echo ($data['hook_block_assembly'] == 'GOOD') ? 'selected' : ''; ?>>GOOD</option>
                        <option value="N/A" <?php echo ($data['hook_block_assembly'] == 'N/A') ? 'selected' : ''; ?>>N/A</option>
                    </select>
                </div>

                <!-- Boom Angle Indicator -->
                <div class="form-group">
                    <label class="font-14 bold mb-2">Boom Angle Indicator</label>
                    <select class="theme-input-style" name="boom_angle_indicator">
                        <option value="GOOD" <?php echo ($data['boom_angle_indicator'] == 'GOOD') ? 'selected' : ''; ?>>GOOD</option>
                        <option value="N/A" <?php echo ($data['boom_angle_indicator'] == 'N/A') ? 'selected' : ''; ?>>N/A</option>
                    </select>
                </div>

                <!-- Wind Speed Indicator (Anemometer) -->
                <div class="form-group">
                    <label class="font-14 bold mb-2">Wind Speed Indicator (Anemometer)</label>
                    <select class="theme-input-style" name="wind_speed_indicator">
                        <option value="GOOD" <?php echo ($data['wind_speed_indicator'] == 'GOOD') ? 'selected' : ''; ?>>GOOD</option>
                        <option value="N/A" <?php echo ($data['wind_speed_indicator'] == 'N/A') ? 'selected' : ''; ?>>N/A</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <!-- End Horizontal Form With Icons -->
</div>



<!-- End of section 3 -->

                        </div>
                        
                    </div>

                    <div class="form-group text-center mt-3">
                    <button type="submit" class="btn long" name="update_mobile_load_test_certificate">Update All</button>
                    </div>

                    </form>    
                </div>
            </div>
            <!-- End Main Content -->

            <?php 
        include_once('../../inc/footer.php');
        ?>
        