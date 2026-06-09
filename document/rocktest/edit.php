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
    $certificate = $result->fetch_assoc(); // Changed from $data to $certificate
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
</head>
<body>
<div class="main-content">
    <div class="container-fluid">
        <div class="card bg-transparent pb-3">
            <div class="card-body bg-white">
                <div class="row">
                    <div class="col-6">
                        <h4 class="pl-2 pt-3 pb-2 font-20">Edit Rocking Test Certificate</h4>
                    </div>
                    <div class="col-6 text-right">
                        <a href="index.php" class="btn btn-primary" target="_blank">View List</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <form action="update.php" method="POST">
            <!-- Hidden field for certificate ID -->
            <input type="hidden" name="project_no" value="<?php echo $certificate['project_no']; ?>">
            
            <!-- Header Data -->
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
                                <input type="date" class="theme-input-style" name="inspection_date" value="<?php echo $certificate['inspection_date'] ?? ''; ?>" placeholder="Date of Inspection">
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Certificate No</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" name="certificate_no" placeholder="Certificate No" value="<?php echo $certificate['certificate_no'] ?? ''; ?>" readonly>
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Report No</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" name="report_no" value="<?php echo $certificate['report_no'] ?? ''; ?>" placeholder="Report No">
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">JRN</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" value="<?php echo $certificate['jrn'] ?? ''; ?>" name="jrn" placeholder="JRN">
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Project ID</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" name="project_no" value="<?php echo $certificate['project_no'] ?? ''; ?>" placeholder="Project No">
                            </div>
                        </div>

                        <!--<div class="form-row mb-20">-->
                        <!--    <div class="col-sm-4">-->
                        <!--        <label class="font-14 bold">REFERENCE NO.</label>-->
                        <!--    </div>-->
                        <!--    <div class="col-sm-8">-->
                        <!--        <input type="text" class="theme-input-style" name="reference_no" placeholder="Reference No" value="<?php echo $certificate['reference_no'] ?? ''; ?>">-->
                        <!--    </div>-->
                        <!--</div>-->
                        
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">SITE/LOCATION</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" name="location" placeholder="Site/Location" value="<?php echo $certificate['location'] ?? ''; ?>">
                            </div>
                        </div>
                       
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">NEXT INSPECTION DATE</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="date" class="theme-input-style" name="next_inspection_date" placeholder="Next Inspection Date" value="<?php echo $certificate['next_inspection_date'] ?? ''; ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <!-- Customer Information / Inspector -->
                    <div class="form-element py-30 mb-30" style="height: 660px;">
                        <h4 class="font-20 mb-30">Customer Information / Inspector</h4>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Customer Name</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" name="customer_name" value="<?php echo $certificate['customer_name'] ?? ''; ?>" placeholder="Customer Name">
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Customer Email</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="email" class="theme-input-style" name="customer_email" value="<?php echo $certificate['customer_email'] ?? ''; ?>" placeholder="Type Email Address">
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Mobile</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="number" class="theme-input-style" name="mobile" value="<?php echo $certificate['mobile'] ?? ''; ?>" placeholder="Contact Number">
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Inspector</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" name="inspector" value="<?php echo $certificate['inspector'] ?? ''; ?>" placeholder="Inspector Name">
                            </div>
                        </div>
                        <!-- Add Technical Manager Dropdown -->
                        <!-- Technical Manager Dropdown -->
                         <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Technical Manager</label>
                            </div>
                            <div class="col-sm-8">
                                <select class="theme-input-style" name="technical_manager">
                                    <option value="Venancio Z. Vera" <?php echo ($certificate['technical_manager'] ?? '') === 'Venancio Z. Vera' ? 'selected' : ''; ?>>Venancio Z. Vera</option>
                                    <option value="Mohammed Fathy" <?php echo ($certificate['technical_manager'] ?? '') === 'Mohammed Fathy' ? 'selected' : ''; ?>>Mohammed Fathy</option>
                                    <option value="Khaled A. Alghamdi" <?php echo ($certificate['technical_manager'] ?? '') === 'Khaled A. Alghamdi' ? 'selected' : ''; ?>>Khaled A. Alghamdi</option>
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
                                    <option value="Veera" <?php echo ($certificate['quality_controller'] ?? '') === 'Veera' ? 'selected' : ''; ?>>Veera</option>
                                    <option value="Sathish" <?php echo ($certificate['quality_controller'] ?? '') === 'Sathish' ? 'selected' : ''; ?>>Sathish</option>
                                    <option value="Samuel Bhatti" <?php echo ($certificate['quality_controller'] ?? '') === 'Samuel Bhatti' ? 'selected' : ''; ?>>Samuel Bhatti</option>
                                </select>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="form-element py-30 mb-30">
                        <h4 class="font-20 mb-30">Header Data</h4>
                        
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">DATE OF REPORT</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="date" class="theme-input-style" name="report_date" placeholder="Date of Report" value="<?php echo $certificate['report_date'] ?? ''; ?>">
                            </div>
                        </div>

                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">COLOR CODE</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" name="color_code" placeholder="Enter Color code" value="<?php echo $certificate['color_code'] ?? ''; ?>">
                            </div>
                        </div>

                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">APPLICABLE STNDARDS</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" name="applicable_standards" placeholder="Enter applicable standards" value="<?php echo $certificate['applicable_standards'] ?? ''; ?>">
                            </div>
                        </div>

                        <!--<div class="form-row mb-20">-->
                        <!--    <div class="col-sm-4">-->
                        <!--        <label class="font-14 bold">EMPLOYER NAME & ADDRESS</label>-->
                        <!--    </div>-->
                        <!--    <div class="col-sm-8">-->
                        <!--        <input type="text" class="theme-input-style" name="employer_address" placeholder="Employer Name & Address" value="<?php echo $certificate['employer_address'] ?? ''; ?>">-->
                        <!--    </div>-->
                        <!--</div>-->
                        <!--<div class="form-row mb-20">-->
                        <!--    <div class="col-sm-4">-->
                        <!--        <label class="font-14 bold">PREMISES ADDRESS</label>-->
                        <!--    </div>-->
                        <!--    <div class="col-sm-8">-->
                        <!--        <input type="text" class="theme-input-style" name="premises_address" placeholder="Premises Address" value="<?php echo $certificate['premises_address'] ?? ''; ?>">-->
                        <!--    </div>-->
                        <!--</div>-->
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-element py-30 mb-30">
                        <h4 class="font-20 mb-30">Inspection Details</h4>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">INSPECTED ITEM TYPE</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" name="inspected_item_type" placeholder="Inspected Item Type" value="<?php echo $certificate['inspected_item_type'] ?? ''; ?>">
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">IDENTIFICATION NO.</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" name="identification_no" placeholder="Identification No" value="<?php echo $certificate['identification_no'] ?? ''; ?>">
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">QUANTITY</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" name="quantity" placeholder="Quantity" value="<?php echo $certificate['quantity'] ?? ''; ?>">
                            </div>
                        </div>
                        <div class="form-row mb-20">
    <div class="col-sm-4">
        <label class="font-14 bold">DESCRIPTION</label>
    </div>
    <div class="col-sm-8">
        <textarea class="theme-input-style" name="description" placeholder="Description" rows="4"><?php echo $certificate['description'] ?? ''; ?></textarea>
    </div>
</div>

                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">WLL/SWL</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" name="wll_swl" placeholder="WLL/SWL" value="<?php echo $certificate['wll_swl'] ?? ''; ?>">
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">LAST EXAMINATION DATE</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" name="last_exam_date" placeholder="Last Examination Date" value="<?php echo $certificate['last_exam_date'] ?? ''; ?>">
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">THIS EXAMINATION DATE</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="date" class="theme-input-style" name="this_exam_date" placeholder="This Examination Date" value="<?php echo $certificate['this_exam_date'] ?? ''; ?>">
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">NEXT EXAMINATION DATE</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="date" class="theme-input-style" name="next_exam_date" placeholder="Next Examination Date" value="<?php echo $certificate['next_exam_date'] ?? ''; ?>">
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">REASON FOR EXAMINATION</label>
                            </div>
                            <div class="col-sm-8">
                                <select class="theme-input-style" name="reason_for_exam">
                                    <option value="A" <?php echo ($certificate['reason_for_exam'] ?? '') == 'A' ? 'selected' : ''; ?>>3 Monthly: A</option>
                                    <option value="B" <?php echo ($certificate['reason_for_exam'] ?? '') == 'B' ? 'selected' : ''; ?>>6 Monthly: B</option>
                                    <option value="C" <?php echo ($certificate['reason_for_exam'] ?? '') == 'C' ? 'selected' : ''; ?>>12 Monthly: C</option>
                                    <option value="D" <?php echo ($certificate['reason_for_exam'] ?? '') == 'D' ? 'selected' : ''; ?>>Written Scheme: D</option>
                                    <option value="E" <?php echo ($certificate['reason_for_exam'] ?? '') == 'E' ? 'selected' : ''; ?>>Exceptional Circumstance: E</option>
                                </select>
                            </div>
                        </div>
                        <!--<div class="form-row mb-20">-->
                        <!--    <div class="col-sm-4">-->
                        <!--        <label class="font-14 bold">DETAILS OF TEST</label>-->
                        <!--    </div>-->
                        <!--    <div class="col-sm-8">-->
                        <!--        <input type="text" class="theme-input-style" name="details_of_test" placeholder="Details of Test" value="<?php echo $certificate['details_of_test'] ?? ''; ?>">-->
                        <!--    </div>-->
                        <!--</div>-->
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">STATUS</label>
                            </div>
                            <div class="col-sm-8">
                                <select class="theme-input-style" name="status">
                                    <option value="ND" <?php echo ($certificate['status'] ?? '') == 'ND' ? 'selected' : ''; ?>>ND - No Defect</option>
                                    <option value="SDR" <?php echo ($certificate['status'] ?? '') == 'SDR' ? 'selected' : ''; ?>>SDR - See Defect Report</option>
                                    <option value="NF" <?php echo ($certificate['status'] ?? '') == 'NF' ? 'selected' : ''; ?>>NF - Not Found</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">SAFE TO USE</label>
                            </div>
                            <div class="col-sm-8">
                                <select class="theme-input-style" name="safe_to_use">
                                    <option value="Yes" <?php echo ($certificate['safe_to_use'] ?? '') == 'Yes' ? 'selected' : ''; ?>>Yes</option>
                                    <option value="No" <?php echo ($certificate['safe_to_use'] ?? '') == 'No' ? 'selected' : ''; ?>>No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <!-- Grease Sample Condition -->
                    <div class="form-element py-30 mb-30">
                        <h4 class="font-20 mb-30">Grease Sample Condition After Analyzing</h4>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold">Condition</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="theme-input-style" name="grease_condition" placeholder="grease condition" value="<?php echo $certificate['grease_condition'] ?? ''; ?>">
                                <!--<select class="theme-input-style" name="grease_condition">-->
                                <!--    <option value="OK" <?php echo ($certificate['grease_condition'] ?? '') == 'OK' ? 'selected' : ''; ?>>OK</option>-->
                                <!--    <option value="Not OK" <?php echo ($certificate['grease_condition'] ?? '') == 'Not OK' ? 'selected' : ''; ?>>Not OK</option>-->
                                <!--</select>-->
                            </div>
                        </div>
                    </div>

                    <!-- Last Measured Limits -->
                    <div class="form-element py-30 mb-30">
                        <h4 class="font-20 mb-30">Test Positions</h4>
                        <h4 class="font-20 mb-30">Last Measured Limits to be compared</h4>
                        <div class="form-row mb-20">
                            <div class="col-sm-3">
                                <label class="font-14 bold">AFT (mm)</label>
                                <input type="text" class="theme-input-style" name="last_aft" placeholder="AFT" value="<?php echo $certificate['last_aft'] ?? ''; ?>">
                            </div>
                            <div class="col-sm-3">
                                <label class="font-14 bold">STBD (mm)</label>
                                <input type="text" class="theme-input-style" name="last_stbd" placeholder="STBD" value="<?php echo $certificate['last_stbd'] ?? ''; ?>">
                            </div>
                            <div class="col-sm-3">
                                <label class="font-14 bold">FORWARD (mm)</label>
                                <input type="text" class="theme-input-style" name="last_forward" placeholder="FORWARD" value="<?php echo $certificate['last_forward'] ?? ''; ?>">
                            </div>
                            <div class="col-sm-3">
                                <label class="font-14 bold">PORT SIDE (mm)</label>
                                <input type="text" class="theme-input-style" name="last_port_side" placeholder="PORT SIDE" value="<?php echo $certificate['last_port_side'] ?? ''; ?>">
                            </div>
                        </div>
                    </div>

                    <!-- Actual Deviation Measured by Dial Gauge Readings -->
                    <div class="form-element py-30 mb-30">
                        <h4 class="font-20 mb-30">Actual Deviation Measured by Dial Gauge Readings</h4>
                        <div class="form-row mb-20">
                            <div class="col-sm-3">
                                <label class="font-14 bold">AFT (mm)</label>
                                <input type="text" class="theme-input-style" name="actual_aft" placeholder="AFT" value="<?php echo $certificate['actual_aft'] ?? ''; ?>">
                            </div>
                            <div class="col-sm-3">
                                <label class="font-14 bold">STBD (mm)</label>
                                <input type="text" class="theme-input-style" name="actual_stbd" placeholder="STBD" value="<?php echo $certificate['actual_stbd'] ?? ''; ?>">
                            </div>
                            <div class="col-sm-3">
                                <label class="font-14 bold">FORWARD (mm)</label>
                                <input type="text" class="theme-input-style" name="actual_forward" placeholder="FORWARD" value="<?php echo $certificate['actual_forward'] ?? ''; ?>">
                            </div>
                            <div class="col-sm-3">
                                <label class="font-14 bold">PORT SIDE (mm)</label>
                                <input type="text" class="theme-input-style" name="actual_port_side" placeholder="PORT SIDE" value="<?php echo $certificate['actual_port_side'] ?? ''; ?>">
                            </div>
                        </div>
                    </div>

                    <!-- Permitted Limits to be Compared -->
                    <div class="form-element py-30 mb-30">
                        <h4 class="font-20 mb-30">Permitted Limits to be Compared</h4>
                        <div class="form-row mb-20">
                            <div class="col-sm-3">
                                <label class="font-14 bold">AFT (mm)</label>
                                <input type="text" class="theme-input-style" name="permitted_aft" placeholder="AFT" value="<?php echo $certificate['permitted_aft'] ?? ''; ?>">
                            </div>
                            <div class="col-sm-3">
                                <label class="font-14 bold">STBD (mm)</label>
                                <input type="text" class="theme-input-style" name="permitted_stbd" placeholder="STBD" value="<?php echo $certificate['permitted_stbd'] ?? ''; ?>">
                            </div>
                            <div class="col-sm-3">
                                <label class="font-14 bold">FORWARD (mm)</label>
                                <input type="text" class="theme-input-style" name="permitted_forward" placeholder="FORWARD" value="<?php echo $certificate['permitted_forward'] ?? ''; ?>">
                            </div>
                            <div class="col-sm-3">
                                <label class="font-14 bold">PORT SIDE (mm)</label>
                                <input type="text" class="theme-input-style" name="permitted_port_side" placeholder="PORT SIDE" value="<?php echo $certificate['permitted_port_side'] ?? ''; ?>">
                            </div>
                        </div>
                    </div>

                    <!-- Result/OK or Defect of SGOCC -->
                    <div class="form-element py-30 mb-30">
                        <h4 class="font-20 mb-30">Result/OK or Defect of SGOCC</h4>
                        
                        <div class="form-row mb-20">
                            <div class="col-sm-3">
                                <label class="font-14 bold">AFT (mm)</label>
                                <input type="text" class="theme-input-style" name="result_aft" placeholder="AFT" value="<?php echo $certificate['result_aft'] ?? ''; ?>">
                            </div>
                            <div class="col-sm-3">
                                <label class="font-14 bold">STBD (mm)</label>
                                <input type="text" class="theme-input-style" name="result_stbd" placeholder="STBD" value="<?php echo $certificate['result_stbd'] ?? ''; ?>">
                            </div>
                            <div class="col-sm-3">
                                <label class="font-14 bold">FORWARD (mm)</label>
                                <input type="text" class="theme-input-style" name="result_forward" placeholder="FORWARD" value="<?php echo $certificate['result_forward'] ?? ''; ?>">
                            </div>
                            <div class="col-sm-3">
                                <label class="font-14 bold">PORT SIDE (mm)</label>
                                <input type="text" class="theme-input-style" name="result_port_side" placeholder="PORT SIDE" value="<?php echo $certificate['result_port_side'] ?? ''; ?>">
                            </div>
                        </div>
                        
                        
                        <!--<div class="form-row mb-20">-->
                        <!--    <div class="col-sm-3">-->
                        <!--        <label class="font-14 bold">AFT</label>-->
                        <!--        <select class="theme-input-style" name="result_aft">-->
                        <!--            <option value="OK" <?php echo ($certificate['result_aft'] ?? '') == 'OK' ? 'selected' : ''; ?>>OK</option>-->
                        <!--            <option value="Defect" <?php echo ($certificate['result_aft'] ?? '') == 'Defect' ? 'selected' : ''; ?>>Defect</option>-->
                        <!--        </select>-->
                        <!--    </div>-->
                        <!--    <div class="col-sm-3">-->
                        <!--        <label class="font-14 bold">STBD</label>-->
                        <!--        <select class="theme-input-style" name="result_stbd">-->
                        <!--            <option value="OK" <?php echo ($certificate['result_stbd'] ?? '') == 'OK' ? 'selected' : ''; ?>>OK</option>-->
                        <!--            <option value="Defect" <?php echo ($certificate['result_stbd'] ?? '') == 'Defect' ? 'selected' : ''; ?>>Defect</option>-->
                        <!--        </select>-->
                        <!--    </div>-->
                        <!--    <div class="col-sm-3">-->
                        <!--        <label class="font-14 bold">FORWARD</label>-->
                        <!--        <select class="theme-input-style" name="result_forward">-->
                        <!--            <option value="OK" <?php echo ($certificate['result_forward'] ?? '') == 'OK' ? 'selected' : ''; ?>>OK</option>-->
                        <!--            <option value="Defect" <?php echo ($certificate['result_forward'] ?? '') == 'Defect' ? 'selected' : ''; ?>>Defect</option>-->
                        <!--        </select>-->
                        <!--    </div>-->
                        <!--    <div class="col-sm-3">-->
                        <!--        <label class="font-14 bold">PORT SIDE</label>-->
                        <!--        <select class="theme-input-style" name="result_port_side">-->
                        <!--            <option value="OK" <?php echo ($certificate['result_port_side'] ?? '') == 'OK' ? 'selected' : ''; ?>>OK</option>-->
                        <!--            <option value="Defect" <?php echo ($certificate['result_port_side'] ?? '') == 'Defect' ? 'selected' : ''; ?>>Defect</option>-->
                        <!--        </select>-->
                        <!--    </div>-->
                        <!--</div>-->
                    </div>
                </div>
            </div>            

            <!-- Save Button -->
            <div class="form-row">
                <div class="col-12 text-center mt-4">
                    <button type="submit" class="btn long" name="update_certificate">Update Certificate</button>
                </div>
            </div>
        </form>
    </div>
</div>
</body>
</html>

<?php include_once('../../inc/footer.php'); ?>