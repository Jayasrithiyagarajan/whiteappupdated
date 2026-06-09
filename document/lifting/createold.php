<?php
include_once('../../inc/function.php');
include_once('../../file/config.php');

// Ensure `project_no` is set in the request
if (isset($_GET['project_no']) && !empty($_GET['project_no'])) {
     $project_no = $_GET['project_no'];
    $query = "
    SELECT 
        p.project_no, p.customer_name, p.customer_email, p.customer_mobile, p.inspector_name,
        c.checklist_no, c.inspection_date, c.crane_serial_no, c.capacity_swl,
        r.report_no, r.sticker_number_issued,
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
    } else {
        $data = null;
    }
} else {
    echo "Invalid or missing project ID.";
    exit;
}

// Get the current year
$currentYear = date('Y');
?>


<!-- Main Content -->
<div class="main-content">
    <div class="container-fluid">
        <div class="card bg-transparent pb-3">
            <div class="card-body bg-white">
                <div class="row">
                    <div class="col-6">
                        <h4 class="pl-2 pt-3 pb-2 font-20">LIFTING GEARS CERTIFICATE</h4>
                    </div>
                    <div class="col-6 text-right">
                        <button type="button" class="btn">View List</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        
        <form action="save_form.php" method="POST">
        <div class="row">
            <div class="col-lg-6">
                <div class="form-element py-30 mb-30">
                    <h4 class="font-20 mb-30">Header Data</h4>                    
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Date of Report</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="date" name="date_of_report" class="theme-input-style" required>
                            </div>
                        </div>                        

                        <!-- <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Certificate No</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" name="certificate_no[]" class="theme-input-style certificate-no" value="24403-1" readonly>
                            </div>
                        </div> -->

                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Report No</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" name="report_no" value="<?php echo $data['report_no'] ?? ''; ?>" class="theme-input-style" readonly required>
                            </div>
                        </div>

                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">JRN</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" name="jrn" class="theme-input-style" required>
                            </div>
                        </div>

                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold mb-2">Color Code (if required)</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" name="color_code" class="theme-input-style" required>
                                <!--<select name="" class="theme-input-style">-->
                                <!--    <option value="A">A</option>-->
                                <!--    <option value="B">B</option>-->
                                <!--    <option value="C">C</option>-->
                                <!--    <option value="D">D</option>-->
                                <!--    <option value="E">E</option>-->
                                <!--</select>-->
                            </div>
                        </div>

                        <!--<div class="form-row mb-20">-->
                        <!--    <div class="col-sm-4">-->
                        <!--        <label class="font-14 bold mb-2">Applicable Standard(s)</label>-->
                        <!--    </div>-->
                        <!--    <div class="col-sm-8">-->
                        <!--        <select name="applicable_standards" class="theme-input-style">-->
                        <!--            <option value="ASME B30.9"> ASME B30.9</option>-->
                        <!--            <option value="ASME B30.26">ASME B30.26</option>-->
                        <!--            <option value="ASME B30.20">ASME B30.20</option>-->
                        <!--            <option value="ASME B30.10">ASME B30.10</option>-->
                        <!--            <option value="ASME B30.30">ASME B30.30</option>-->
                        <!--        </select>-->
                                <!--<input type="text" name="applicable_standards" class="theme-input-style">-->
                        <!--    </div>-->
                        <!--</div>-->

                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold mb-2">Project ID</label>
                            </div>
                            <div class="col-sm-8">
                            <input type="text" class="theme-input-style" name="project_no" value="<?php echo $data['project_no'] ?? ''; ?>" placeholder="Project ID" readonly>
                            </div>
                        </div>
                        <!-- <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold mb-2">Company Name</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" name="companyName" class="theme-input-style" readonly>
                            </div>
                        </div> -->
                    
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-element py-30 mb-30" style="height: 488px;">
                    <h4 class="font-20 mb-30">Customer Information / Inspector</h4>

                    <div class="form-row mb-20">
                        <div class="col-sm-4">
                            <label class="font-14 bold">Customer Name</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" name="customer_name" value="<?php echo $data['customer_name'] ?? ''; ?>" class="theme-input-style" readonly required>
                        </div>
                    </div>
                    
                     

                    <div class="form-row mb-20">
                        <div class="col-sm-4">
                            <label class="font-14 bold">Customer Email</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="email" value="<?php echo $data['customer_email'] ?? ''; ?>" name="customer_email" class="theme-input-style" readonly required>
                        </div>
                    </div>

                    <div class="form-row mb-20">
                        <div class="col-sm-4">
                            <label class="font-14 bold">Mobile</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="number" value="<?php echo $data['customer_mobile'] ?? ''; ?>" name="mobile" class="theme-input-style" readonly required>
                        </div>
                    </div>

                    <div class="form-row mb-20">
                        <div class="col-sm-4">
                            <label class="font-14 bold">Inspector</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" value="<?php echo $data['inspector_name'] ?? ''; ?>" name="inspector" class="theme-input-style" readonly required>
                        </div>
                    </div>


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
                <label class="font-14 bold">Quality Controller </label>
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


            <div id="form-container">
                <div class="form-section">
                    <!-- General Information A -->

            <!-- General Information A -->
            <div class="row">
            <div class="col-lg-12">
                <div class="form-element py-30 multiple-column">
                    <h4 class="font-20 mb-20">A. GENERAL INFORMATION</h4>

                    <div class="row">
                        <div class="col-lg-6">
<div class="form-group">
                        <label for="certificate_no">Certificate No:</label>
                        <input 
                            type="text" 
                            name="certificate_no[]" 
                            class="certificate-no theme-input-style" 
                            readonly 
                            value="CLC-001-<?php echo $currentYear . '-' . htmlspecialchars($project_no); ?>"
                        >
                    </div>


<div class="form-group">
    <label class="font-14 bold mb-2">Applicable Standard(s)</label>
    <select name="applicable_standards[]" class="theme-input-style" required>
        <option value="">-- Select --</option>
        <option value="ASME B30.9"> ASME B30.9</option>
                                    <option value="ASME B30.26">ASME B30.26</option>
                                    <option value="ASME B30.20">ASME B30.20</option>
                                    <option value="ASME B30.10">ASME B30.10</option>
                                    <option value="ASME B30.30">ASME B30.30</option>
    </select>
</div>

<!--<div class="form-row mb-20">-->
<!--                            <div class="col-sm-4">-->
<!--                                <label class="font-14 bold mb-2">Applicable Standard(s)</label>-->
<!--                            </div>-->
<!--                            <div class="col-sm-8">-->
<!--                                <select name="applicable_standards" class="theme-input-style">-->
<!--                                    <option value="ASME B30.9"> ASME B30.9</option>-->
<!--                                    <option value="ASME B30.26">ASME B30.26</option>-->
<!--                                    <option value="ASME B30.20">ASME B30.20</option>-->
<!--                                    <option value="ASME B30.10">ASME B30.10</option>-->
<!--                                    <option value="ASME B30.30">ASME B30.30</option>-->
<!--                                </select>-->
                                <!--<input type="text" name="applicable_standards" class="theme-input-style">-->
<!--                            </div>-->
<!--                        </div>-->

                            <div class="form-group">
                                <label class="font-14 bold mb-2">Name & Address of Employer</label>
                                <input type="text" name="employer_name_address[]" value="<?php echo $data['customer_name'] ?? ''; ?>"  class="theme-input-style">
                            </div>

                            <!--<div class="form-group">-->
                            <!--    <label class="font-14 bold mb-2">Identification No./Serial No.</label>-->
                            <!--    <input type="text" name="identification_no[]" class="theme-input-style">-->
                            <!--</div>-->


                            <div class="form-group">
                            <label class="font-14 bold mb-2">Identification No./Serial No.</label>
                            <textarea name="identification_no[]" class="theme-input-style" rows="5"></textarea>
                            </div>
                            

                            <div class="form-group">
                                <label class="font-14 bold mb-2">Working Load Limit / Safe Working Load (WLL/SWL)</label>
                                <input type="text" name="wll_swl[]" class="theme-input-style">
                            </div>

                            <div class="form-group">
                                <label class="font-14 bold mb-2">QTY</label>
                                <input type="text" name="qty[]" class="theme-input-style">
                            </div>

                            <div class="form-group">
                                <label class="font-14 bold mb-2">Type</label>
                                <input type="text" name="type[]" class="theme-input-style">
                            </div>

                            <div class="form-group">
                                <label class="font-14 bold mb-2">Date of Last Examination</label>
                                <input type="text" name="date_last_examination[]" class="theme-input-style">
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                            <label class="font-14 bold mb-2">Description</label>
                            <textarea name="description[]" class="theme-input-style" rows="5"></textarea>
                            </div>

                            <!--<div class="form-group">-->
                            <!--    <label class="font-14 bold mb-2">Manufacturer</label>-->
                            <!--    <input type="text" name="manufacturer[]" class="theme-input-style">-->
                            <!--</div>-->

                            <!--<div class="form-group">-->
                            <!--    <label class="font-14 bold mb-2">Size</label>-->
                            <!--    <input type="text" name="size[]" class="theme-input-style">-->
                            <!--</div>-->

                            <!--<div class="form-group">-->
                            <!--    <label class="font-14 bold mb-2">Length</label>-->
                            <!--    <input type="text" name="length[]" class="theme-input-style">-->
                            <!--</div>-->

                            <!--<div class="form-group">-->
                            <!--    <label class="font-14 bold mb-2">Color</label>-->
                            <!--    <input type="text" name="color[]" class="theme-input-style">-->
                            <!--</div>-->

                            <!--<div class="form-group">-->
                            <!--    <label class="font-14 bold mb-2">No. of PLY (if any)</label>-->
                            <!--    <input type="text" name="ply[]" class="theme-input-style">-->
                            <!--</div>-->
                        </div>
                    </div>
                </div>
            </div>

            <!-- General Information B -->
            <div class="col-lg-12">
                <div class="form-element py-30 multiple-column">
                    <h4 class="font-20 mb-20">B. GENERAL INFORMATION</h4>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="font-14 bold mb-2">Address of Premises</label>
                                <input type="text" name="address_of_premises[]" value="<?php echo $data['city'] ?? ''; ?>" class="theme-input-style">
                            </div>


 <div class="form-group">
                                <label class="font-14 bold mb-2">Date of this Examination</label>
                                <input type="date" name="date_of_this_examination[]" class="theme-input-style">
                            </div>

                            <div class="form-group">
                                <label class="font-14 bold mb-2"> Latest date of the next examination</label>
                                <input type="date" name="next_examination_date[]" class="theme-input-style">
                            </div>

                            <!--<div class="form-group">-->
                            <!--    <label class="font-14 bold mb-2">Reason for Examination</label>-->
                            <!--    <input type="text" name="reason_for_examination[]" class="theme-input-style">-->
                            <!--</div>-->
                            
                            <div class="form-group">
    <label class="font-14 bold mb-2">Reason for Examination</label>
    <select name="reason_for_examination[]" class="theme-input-style" required>
        <option value="">-- Select Reason --</option>
        <option value="A">A</option>
        <option value="B">B</option>
        <option value="C">C</option>
        <option value="D">D</option>
        <option value="E">E</option>
    </select>
</div>

                           
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="font-14 bold mb-2">Details of Any Test Applied</label>
                                <textarea name="test_details[]" class="theme-input-style"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- General Information C -->
            <div class="col-lg-12">
                <div class="form-element py-30 multiple-column">
                    <h4 class="font-20 mb-20">C. GENERAL INFORMATION</h4>

                    <div class="row">
                        <!--<div class="col-lg-6">-->
                        <!--    <div class="form-group">-->
                        <!--        <label class="font-14 bold mb-2">Status</label>-->
                        <!--        <input type="text" name="status[]" class="theme-input-style">-->
                        <!--    </div>-->
                        <!--</div>-->

                        <!--<div class="col-lg-6">-->
                        <!--    <div class="form-group">-->
                        <!--        <label class="font-14 bold mb-2">Safe to Use</label>-->
                        <!--        <input type="text" name="safe_to_use[]" class="theme-input-style">-->
                        <!--    </div>-->
                        <!--</div>-->
                        
                        
                        <div class="col-lg-6">
    <div class="form-group">
        <label class="font-14 bold mb-2">Status</label>
        <select name="status[]" class="theme-input-style">
            <option value="">-- Select Status --</option>
            <option value="ND">ND - No Defect</option>
            <option value="SDR">SDR - See Defect Report</option>
            <option value="NF">NF - Not Found</option>
        </select>
    </div>
</div>

<div class="col-lg-6">
    <div class="form-group">
        <label class="font-14 bold mb-2">Safe to Use</label>
        <select name="safe_to_use[]" class="theme-input-style">
            <option value="">-- Select Option --</option>
            <option value="YES">YES</option>
            <option value="NO">NO</option>
        </select>
    </div>
</div>

                        
                    </div>                    
                </div>          
            </div>                   
            </div>
            </div>
            </div>

            <!-- Add More Button -->
    <div class="text-center mb-4">
        <button id="add-form-btn" class="btn btn-primary">
            <i class="fa fa-plus"></i> Add More
        </button>
    </div>
            <div class="form-group text-center mt-3">
                        <button type="submit" class="btn long" name="save_data_lifting">Save All</button>
                    </div>
            </form>        
    </div>
</div>
<!-- FontAwesome for Icons -->
<link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
/>

<!-- JavaScript -->
// <script>
// let certificateCounter = 1; // Starting counter for certificate numbers
// const currentYear = <?php echo $currentYear; ?>; // Use the PHP variable
// const projectNo = '<?php echo $project_no; ?>'; // Use the PHP variable

// document.getElementById("add-form-btn").addEventListener("click", function (e) {
//     e.preventDefault(); // Prevent default form submission

//     const formContainer = document.getElementById("form-container");
//     const formSection = document.querySelector(".form-section");
//     const clonedForm = formSection.cloneNode(true); // Clone the form section

//     // Increment the certificate counter
//     certificateCounter++;
//     const certField = clonedForm.querySelector(".certificate-no");

//     if (certField) {
//         // Generate the new certificate number in the format CLC-XXX-YYYY-project_no
//         const newCertificateNo = `CLC-${String(certificateCounter).padStart(3, "0")}-${currentYear}-${projectNo}`;
//         certField.value = newCertificateNo;
//     }

//     // Clear other input values in the cloned form except for the certificate number
//     clonedForm.querySelectorAll("input, textarea").forEach((input) => {
//         if (!input.classList.contains("certificate-no")) {
//             input.value = "";
//         }
//     });

//     // Append the cloned form to the container
//     formContainer.appendChild(clonedForm);
// });
// </script>

<script>
   
let certificateCounter = 1; // Starting counter for certificate numbers
const currentYear = <?php echo $currentYear; ?>; // Use the PHP variable
const projectNo = '<?php echo $project_no; ?>'; // Use the PHP variable

document.getElementById("add-form-btn").addEventListener("click", function (e) {
    e.preventDefault(); // Prevent default form submission

    const formContainer = document.getElementById("form-container");
    const formSections = document.querySelectorAll(".form-section");
    const lastFormSection = formSections[formSections.length - 1];
    const clonedForm = lastFormSection.cloneNode(true); // Clone the last form section

    // Get values from the last form section that we want to copy
    const lastEmployerAddress = lastFormSection.querySelector('input[name="employer_name_address[]"]').value;
    const lastPremisesAddress = lastFormSection.querySelector('input[name="address_of_premises[]"]').value;

    // Increment the certificate counter
    certificateCounter++;
    const certField = clonedForm.querySelector(".certificate-no");

    if (certField) {
        // Generate the new certificate number in the format CLC-XXX-YYYY-project_no
        const newCertificateNo = `CLC-${String(certificateCounter).padStart(3, "0")}-${currentYear}-${projectNo}`;
        certField.value = newCertificateNo;
    }

    // Clear other input values in the cloned form except for the certificate number and copied fields
    clonedForm.querySelectorAll("input, textarea, select").forEach((input) => {
        if (!input.classList.contains("certificate-no")) {
            // Preserve the employer and premises addresses
            if (input.name === "employer_name_address[]") {
                input.value = lastEmployerAddress;
            } else if (input.name === "address_of_premises[]") {
                input.value = lastPremisesAddress;
            } else {
                input.value = "";
            }
            
            // For select elements, reset to first option
            if (input.tagName === 'SELECT') {
                input.selectedIndex = 0;
            }
        }
    });

    // Append the cloned form to the container
    formContainer.appendChild(clonedForm);
});
</script>


<?php include_once('../../inc/footer.php'); ?>