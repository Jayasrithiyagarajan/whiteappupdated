<?php
include_once('./get-checklist.php');
// Ensure $row is accessible
if (!isset($row) || empty($row)) {
    echo "No checklist data available.";
    exit;
}

$saved_results = isset($row['result']) ? explode(',', $row['result']) : [];
$saved_remarks = isset($row['checklist_remark']) ? explode(',', $row['checklist_remark']) : [];

function isChecked($itemIndex, $value, $saved_results) {
    $idx = $itemIndex - 1;
    if (isset($saved_results[$idx]) && trim($saved_results[$idx]) === $value) {
        return 'checked';
    }
    return '';
}

function getRemark($itemIndex, $saved_remarks) {
    $idx = $itemIndex - 1;
    return isset($saved_remarks[$idx]) ? htmlspecialchars($saved_remarks[$idx]) : '';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VISUAL INSPECTION CHECKLIST FOR ARC WELDING EQUIPMENT </title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link href="style.css" rel="stylesheet">
    <style>
        .large-checkbox {
            width: 20px;
            height: 20px;
        }

        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }

        .modal-content { 
            position: relative;
            top: 50%;
            transform: translateY(-50%);
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="table-responsive">
            <table class="w-100">
                <tr>
                    <td rowspan="4" class="logo-cell ">
                        <img src="../../logo.png" alt="CIMS Logo" width="100"> <!-- Replace 'logo.png' with actual image path -->
                    </td>
                    <td colspan="3" class="no-border">
                        <span class="main-title">CRANE INSPECTION & MAINTENANCE SERVICES</span><br>
                        A DIVISION OF AL-KHOBAR GATE INTERNATIONAL TRADING EST.
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="">
                        <strong>CRANE HEALTH CHECK INSPECTION CHECKLIST FOR OFFSHORE PEDESTAL CRANES & FLOATING CRANES</strong>
                    </td>
                </tr>
                <tr>
                    <td>FRM.0601-1.0</td>
                    <td>Revision 02	</td>
                    <td>Issue Date: 08/OCT/2020</td>
                </tr>
                <tr>
                    <td class="left-align">Prepared By<br>Operations Manager</td>
                    <td class="left-align">Reviewed & Approved By<br>Managing Director</td>
                    <td><img src="../../code.png" width="80px" height="80px" alt="" /></td>
                </tr>
            </table>
        </div>

        <h4>        PEDESTAL CRANES, FLOATING CRANES & FLOATING DERRICKS, ARTICULATING BOOM CRANES </h4>
        <h4>ASME B30.4-2015, ASME B30.8-2015, ASME B30.22-2016, API SPEC 2C-2012, API RP 2D-2014  </h4>




        <form method="post" action="./update_checklist.php" id="checklistForm">
<div class="table-responsive">
            <table class="table table-bordered">


                <tr>
                    <th style="width: 25%;">VESSEL NAME</th>
                    <td style="width: 25%;"><input type="text" name="vessel_name" value="<?php echo htmlspecialchars($row['vessel_name'] ?? ''); ?>" class="form-control" style="font-weight: bold; border: none; background: transparent;"></td>
                    <th style="width: 25%;">REPORT NO</th>
                    <td style="width: 25%;"><input type="text" name="report_no" value="<?php echo htmlspecialchars($row['report_no'] ?? ''); ?>" class="form-control" style="font-weight: bold; border: none; background: transparent;"></td>
                </tr>
                <!--<tr>-->
                <!--    <th>CLIENT’S NAME</th>-->
                <!--    <td><input type="text" name="header_client_name" value="<?php echo htmlspecialchars($row['client_name'] ?? ''); ?>" class="form-control" style="font-weight: bold; border: none; background: transparent;"></td>-->
                <!--    <th>INSPECTED BY</th>-->
                <!--    <td><input type="text" name="inspected_by" value="<?php echo htmlspecialchars($row['inspected_by'] ?? ''); ?>" class="form-control" style="font-weight: bold; border: none; background: transparent;"></td>-->
                <!--</tr>-->
                <tr>
                    <th>LOCATION</th>
                    <td><input type="text" name="location" value="<?php echo htmlspecialchars($row['location'] ?? ''); ?>" class="form-control" style="font-weight: bold; border: none; background: transparent;"></td>
                    <th>INSPECTION DATE</th>
                    <td><input type="date" name="inspection_date" value="<?php echo htmlspecialchars($row['inspection_date'] ?? ''); ?>" class="form-control" style="font-weight: bold; border: none; background: transparent;"></td>
                </tr>
                <tr>
                    <th>EQUIPMENT NO</th>
                    <td><input type="text" name="equipment_no" value="<?php echo htmlspecialchars($row['equipment_no'] ?? ''); ?>" class="form-control" style="font-weight: bold; border: none; background: transparent;"></td>
                    <th>EQUIPMENT TYPE:</th>
                    <td><input type="text" name="equipmenttype" value="<?php echo htmlspecialchars($row['equipmenttype'] ?? ''); ?>" class="form-control" style="font-weight: bold; border: none; background: transparent;"></td>
                </tr>
                
                
                <tr>
                    <th>MANUFACTURER: </th>
                    <td><input type="text" name="manufacturer" value="<?php echo htmlspecialchars($row['manufacturer'] ?? ''); ?>" class="form-control" style="font-weight: bold; border: none; background: transparent;"></td>
                    <th>YEAR MODEL:</th>
                    <td><input type="text" name="year_model" value="<?php echo htmlspecialchars($row['year_model'] ?? ''); ?>" class="form-control" style="font-weight: bold; border: none; background: transparent;"></td>
                </tr>
                
                <tr>
                    <th>MODEL NO.: </th>
                    <td><input type="text" name="model_no" value="<?php echo htmlspecialchars($row['model_no'] ?? ''); ?>" class="form-control" style="font-weight: bold; border: none; background: transparent;"></td>
                    <th>CAPACITY (SWL):</th>
                    <td><input type="text" name="capacity_swl" value="<?php echo htmlspecialchars($row['capacity_swl'] ?? ''); ?>" class="form-control" style="font-weight: bold; border: none; background: transparent;"></td>
                </tr>
                
                 <tr>
                    <th>EQUIP. SERIAL NO.</th>
                    <td><input type="text" name="crane_serial_no" value="<?php echo htmlspecialchars($row['crane_serial_no'] ?? ''); ?>" class="form-control" style="font-weight: bold; border: none; background: transparent;"></td>
                    <th>CLIENT’S NAME:</th>
                    <td><input type="text" name="header_client_name" value="<?php echo htmlspecialchars($row['client_name'] ?? ''); ?>" class="form-control" style="font-weight: bold; border: none; background: transparent;"></td>
                </tr>
            </table>
        </div>



        
            <input type="hidden" name="checklist_no" value="<?php echo $row['checklist_id'] ?>" />

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th style="text-align: center;">S.N</th>
                            <th style="text-align: center;">ACCEPTANCE CRITERIA</th>
                            <th style="text-align: center;" colspan="3">RESULT</th>
                            <th style="text-align: center;">REMARKS/ RECOMMENDATIONS</th>
                        </tr>
                        <tr>
                            <th style="text-align: center;">1</th>
                            <th style="text-align: center;">REQUIRED DOCUMENTS </th>
                            <th style="text-align: center;">PASS</th>
                            <th style="text-align: center;">FAIL</th>
                            <th style="text-align: center;">NA</th>
                            <th> </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>1.1</strong></td>
                            <td><strong>Owner’s Manual or Technical Manual.</strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[1][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(1, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[1][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(1, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[1][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(1, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[1]" value="<?php echo getRemark(1, $saved_remarks); ?>">
                            </td>
                        </tr>

                        <tr>
                            <td><strong>1.2</strong></td>
                            <td><strong>Crane Log Book Records. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[2][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(2, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[2][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(2, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[2][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(2, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[2]" value="<?php echo getRemark(2, $saved_remarks); ?>">
                            </td>
                        </tr>
                        <tr>
                            <td><strong>1.3</strong></td>
                            <td><strong>Preventive Maintenance Schedule or Planned Maintenance as per Manufacturer’s recommendation records.
                                </strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[3][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(3, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[3][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(3, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[3][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(3, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[3]" value="<?php echo getRemark(3, $saved_remarks); ?>">
                            </td>
                        </tr>
                        <tr>
                            <td><strong>1.4</strong></td>
                            <td><strong>Crane Maintenance and Repair Records.</strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[4][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(4, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[4][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(4, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[4][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(4, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[4]" value="<?php echo getRemark(4, $saved_remarks); ?>">
                            </td>
                        </tr>

                        <tr>
                            <td><strong>1.5</strong></td>
                            <td><strong> Slew/Swing Gear and Pinion Clearances Report.
                                </strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[5][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(5, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[5][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(5, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[5][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(5, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[5]" value="<?php echo getRemark(5, $saved_remarks); ?>">
                            </td>
                        </tr>

                        <tr>
                            <td><strong>1.6</strong></td>
                            <td><strong> Operator’s Daily Pre-Operational Inspection Checklists.
                                </strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[6][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(6, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[6][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(6, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[6][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(6, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[6]" value="<?php echo getRemark(6, $saved_remarks); ?>">
                            </td>
                        </tr>
                        <tr>
                            <td><strong>1.7</strong></td>
                            <td><strong> Previous Inspection Reports are available & deficiencies were already rectified.</strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[7][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(7, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[7][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(7, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[7][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(7, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <!--<input type="text" name="checklist_remark[7]" value="<?php echo getRemark(7, $saved_remarks); ?>">-->
                                <textarea name="checklist_remark[7]" rows="3" style="width: 100%; resize: vertical;"></textarea>

                            </td>
                        </tr>
                        <tr>
                            <th style="text-align: center;">2</th>
                            <th style="text-align: center;">CERTIFICATES</th>
                            <th style="text-align: center;">PASS</th>
                            <th style="text-align: center;">FAIL</th>
                            <th style="text-align: center;">NA</th>
                            <th>REMARKS / RECOMMENDATIONS </th>
                        </tr>

                        <tr>
                            <td><strong>2.1</strong></td>
                            <td><strong>Crane Class Certificates.</strong></td>
                            <!-- <td style="text-align: center;"><strong> </strong></td> -->
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[8][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(8, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[8][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(8, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[8][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(8, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[8]" value="<?php echo getRemark(8, $saved_remarks); ?>">
                            </td>
                        </tr>

                        <tr>
                            <th style="text-align: center;">2.2</th>
                            <th style="text-align: center;">ROPE Manufacturer’s Test Certificates</th>
                            <th style="text-align: center;">PASS</th>
                            <th style="text-align: center;">FAIL</th>
                            <th style="text-align: center;">NA</th>
                            <th>REMARKS / RECOMMENDATIONS </th>
                        </tr>

                        <tr>
                            <td><strong>2.2.1</strong></td>
                            <td><strong> Main Load Hoist Rope Ø  </strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[9][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(9, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[9][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(9, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[9][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(9, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[9]" value="<?php echo getRemark(9, $saved_remarks); ?>">
                            </td>
                        </tr>
                        <tr>
                            <td><strong>2.2.2</strong></td>
                            <td><strong> No. 1 Auxiliary Load Hoist Rope Ø </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[10][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(10, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[10][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(10, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[10][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(10, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[10]" value="<?php echo getRemark(10, $saved_remarks); ?>">
                            </td>
                        </tr>
                        <tr>
                            <td><strong>2.2.3 </strong></td>
                            <td><strong>No. 2 Auxiliary Hoist Rope
                                </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[11][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(11, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[11][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(11, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[11][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(11, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[11]" value="<?php echo getRemark(11, $saved_remarks); ?>">
                            </td>
                        </tr>
                        <tr>
                            <td><strong>2.2.4</strong></td>
                            <td><strong>Boom Hoist Rope</strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[12][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(12, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[12][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(12, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[12][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(12, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[12]" value="<?php echo getRemark(12, $saved_remarks); ?>">
                            </td>
                        </tr>

                        <tr>
                            <td><strong>2.2.5</strong></td>
                            <td><strong> Pendant Rope </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[13][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(13, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[13][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(13, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[13][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(13, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[13]" value="<?php echo getRemark(13, $saved_remarks); ?>">
                            </td>
                        </tr>

                        <tr>
                            <td><strong>2.3</strong></td>
                            <td><strong> Crane Load Test Certificates. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[14][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(14, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[14][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(14, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[14][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(14, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[14]" value="<?php echo getRemark(14, $saved_remarks); ?>">
                            </td>
                        </tr>
                        <tr>
                            <th style="text-align: center;">2.4</th>
                            <th style="text-align: center;">NDT/MPI Certificates:</th>
                            <th style="text-align: center;">PASS</th>
                            <th style="text-align: center;">FAIL</th>
                            <th style="text-align: center;">NA</th>
                            <th> REMARKS / RECOMMENDATIONS</th>
                        </tr>

                        <tr>
                            <td><strong>2.4.1</strong></td>
                            <td><strong>Crane Structure Welds </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[15][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(15, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[15][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(15, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[15][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(15, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[15]" value="<?php echo getRemark(15, $saved_remarks); ?>">
                            </td>
                        </tr>

                        <tr>
                            <td><strong>2.4.2</strong></td>
                            <td><strong>Main Hook Blocks </strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[16][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(16, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[16][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(16, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[16][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(16, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[16]" value="<?php echo getRemark(16, $saved_remarks); ?>">
                            </td>
                        </tr>



                        <tr>
                            <td><strong>2.4.3 </strong></td>
                            <td><strong> Auxiliary Hook Blocks </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[17][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(17, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[17][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(17, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[17][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(17, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[17]" value="<?php echo getRemark(17, $saved_remarks); ?>">
                            </td>
                        </tr>

                        <tr>
                            <td><strong>2.5</strong></td>
                            <td><strong> Operator Certificate for the type/model of crane. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[18][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(18, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[18][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(18, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[18][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(18, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[18]" value="<?php echo getRemark(18, $saved_remarks); ?>">
                            </td>
                        </tr>
                        <tr>
                            <td><strong>2.6 </strong></td>
                            <td><strong>LMI/RCL/SLI/AML Calibration Certificates. </strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[19][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(19, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[19][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(19, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[19][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(19, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[19]" value="<?php echo getRemark(19, $saved_remarks); ?>">
                            </td>
                        </tr>
                        <tr>
                            <td><strong>2.7 </strong></td>
                            <td><strong>Boom Rocking Test Certificates. </strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[20][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(20, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[20][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(20, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[20][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(20, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[20]" value="<?php echo getRemark(20, $saved_remarks); ?>">
                            </td>
                        </tr>

                        <tr>
                            <th style="text-align: center;">3 </th>
                            <th style="text-align: center;">MARKING AND SAFETY DECALS</th>
                            <th style="text-align: center;">PASS</th>
                            <th style="text-align: center;">FAIL</th>
                            <th style="text-align: center;">NA</th>
                            <th> REMARKS / RECOMMENDATIONS</th>
                        </tr>

                        <tr>
                            <td><strong>3.1 </strong></td>
                            <td><strong>Crane asset number/identification is stenciled prominently.</strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[21][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(21, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[21][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(21, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[21][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(21, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[21]" value="<?php echo getRemark(21, $saved_remarks); ?>">
                            </td>
                        </tr>
                        <tr>
                            <td><strong>3.2</strong></td>
                            <td><strong>Crane’s SWL is prominently stenciled/marked.</strong></td>
                            <!-- <td style="text-align: center;"><strong> </strong></td> -->
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[22][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(22, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[22][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(22, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[22][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(22, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[22]" value="<?php echo getRemark(22, $saved_remarks); ?>">
                            </td>
                        </tr>


                        <tr>
                            <th style="text-align: center;">3.3 </th>
                            <th style="text-align: center;">Hook Blocks’ SWL and weights are stenciled on the items. </th>
                            <th style="text-align: center;">PASS</th>
                            <th style="text-align: center;">FAIL</th>
                            <th style="text-align: center;">NA</th>
                            <th> REMARKS / RECOMMENDATIONS</th>
                        </tr>

                        <tr>
                            <td><strong>3.3.1 </strong></td>
                            <td><strong>Main Hook Block </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[23][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(23, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[23][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(23, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[23][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(23, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[23]" value="<?php echo getRemark(23, $saved_remarks); ?>">
                            </td>
                        </tr>

                        <tr>
                            <td><strong>3.3.2 </strong></td>
                            <td><strong>Auxiliary Hook Block</strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[24][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(24, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[24][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(24, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[24][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(24, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[24]" value="<?php echo getRemark(24, $saved_remarks); ?>">
                            </td>
                        </tr>

                        <tr>
                            <td><strong>3.4 </strong></td>
                            <td><strong> WARNING SIGN: Operator Should Not Rely Solely on Any Automatic Device as a Substitute for Safe Operating Practice, is posted inside the cabin’s wall or control panel.</strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[25][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(25, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[25][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(25, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[25][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(25, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[25]" value="<?php echo getRemark(25, $saved_remarks); ?>">
                            </td>
                        </tr>

                        <tr>
                            <td><strong>3.5 </strong></td>
                            <td><strong>CRANE’S DATA PLATE (Crane Manufacturer Name, Model, Serial Number, and Year of Manufacture) is available and posted or stamped on the crane structure.
                                </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[26][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(26, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[26][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(26, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[26][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(26, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[26]" value="<?php echo getRemark(26, $saved_remarks); ?>">
                            </td>
                        </tr>
                        <tr>
                            <td><strong>3.6 </strong></td>
                            <td><strong>Warning Decal stating: “Warning! Switch Limit must be tested before the start of Lifting Operation and NO Personnel is allowed to By-pass the Crane Limit at any time.
                                </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[27][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(27, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[27][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(27, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[27][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(27, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[27]" value="<?php echo getRemark(27, $saved_remarks); ?>">
                            </td>
                        </tr>



                        <tr>
                            <td><strong>3.7</strong></td>
                            <td><strong> Hand signal decal is posted on the pedestal or mast and cabin. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[28][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(28, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[28][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(28, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[28][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(28, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[28]" value="<?php echo getRemark(28, $saved_remarks); ?>">
                            </td>
                        </tr>

                        <tr>
                            <td><strong>3.8</strong></td>
                            <td><strong>Load rating charts and range diagrams are posted on the wall inside the cabin. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[29][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(29, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[29][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(29, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[29][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(29, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[29]" value="<?php echo getRemark(29, $saved_remarks); ?>">
                            </td>
                        </tr>
                        <tr>
                            <td><strong>3.9</strong></td>
                            <td><strong>Labels of the directional control levers are marked legibly. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[30][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(30, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[30][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(30, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[30][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(30, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[30]" value="<?php echo getRemark(30, $saved_remarks); ?>">
                            </td>
                        </tr>
                        <tr>
                            <th style="text-align: center;">4</th>
                            <th style="text-align: center;">VISUAL INSPECTION & FUNCTIONAL TEST</th>
                            <th rowspan="2" style="text-align: center;">PASS</th>
                            <th rowspan="2" style="text-align: center;">FAIL</th>
                            <th rowspan="2" style="text-align: center;">NA</th>
                            <th rowspan="2">REMARKS / RECOMMENDATIONS </th>
                        </tr>
						
						
						<tr>
                            <th style="text-align: center;">4.1</th>
                            <th style="text-align: center;">BOOM STRUCTURE: There have no signs of excessive wear in the boom pivot shafts, boom cylinder anchor bushings & shafts, & boom telescopic wear surfaces & strips/pads.</th>
                            
                            
                            
                            
                        </tr>

                        

                        <tr>
                            <td><strong> 4.1.1 </strong></td>
                            <td><strong>Main Boom </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[31][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(31, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[31][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(31, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[31][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(31, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[31]" value="<?php echo getRemark(31, $saved_remarks); ?>">
                            </td>
                        </tr>


                        <tr>
                            <td><strong>4.1.2 </strong></td>
                            <td><strong> Lattice Boom: Chords, Lacings, Splices, and bridle have no bent, corroded, deformed, damaged, and dents. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[32][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(32, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[32][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(32, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[32][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(32, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[32]" value="<?php echo getRemark(32, $saved_remarks); ?>">
                            </td>
                        </tr>


                        <tr>
                            <td><strong>4.1.3 </strong></td>
                            <td><strong>Knuckle Boom </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[33][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(33, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[33][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(33, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[33][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(33, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[33]" value="<?php echo getRemark(33, $saved_remarks); ?>">
                            </td>
                        </tr>



                        <tr>
                            <td><strong>4.2 </strong></td>
                            <td><strong>The boom assembly has no signs of corrosion, distortion, deformation, cracks, & wear. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[34][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(34, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[34][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(34, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[34][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(34, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[34]" value="<?php echo getRemark(34, $saved_remarks); ?>">
                            </td>
                        </tr>


                        <tr>
                            <th style="text-align: center;">4.3</th>
                            <th style="text-align: center;">HYDRAULIC CYLINDERS are properly working and no signs of leakages; There is no noticeable boom dropping:</th>
                            <th style="text-align: center;">PASS</th>
                            <th style="text-align: center;">FAIL</th>
                            <th style="text-align: center;">NA</th>
                            <th>REMARKS / RECOMMENDATIONS </th>
                        </tr>




                        <tr>
                            <td><strong>4.3.1 </strong></td>
                            <td><strong>Boom Lift </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[35][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(35, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[35][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(35, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[35][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(35, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[35]" value="<?php echo getRemark(35, $saved_remarks); ?>">
                            </td>
                        </tr>


                        <tr>
                            <td><strong>4.3.2</strong></td>
                            <td><strong> Boom Telescopic </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[36][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(36, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[36][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(36, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[36][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(36, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[36]" value="<?php echo getRemark(36, $saved_remarks); ?>">
                            </td>
                        </tr>



                        <tr>
                            <td><strong>4.3.3 </strong></td>
                            <td><strong>Boom Articulating </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[37][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(37, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[37][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(37, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[37][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(37, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[37]" value="<?php echo getRemark(37, $saved_remarks); ?>">
                            </td>
                        </tr>


                        <tr>
                            <td><strong>4.4 </strong></td>
                            <td><strong>The HOLDING VALVES of boom lifting, telescoping, and articulating/ knuckling are in good working condition and have no signs of boom dropping. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[38][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(38, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[38][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(38, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[38][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(38, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[38]" value="<?php echo getRemark(38, $saved_remarks); ?>">
                            </td>
                        </tr>


                        <tr>
                            <th style="text-align: center;">4.5</th>
                            <th style="text-align: center;">HOISTING OPERATION: Properly working including their brakes.</th>
                            <th style="text-align: center;">PASS</th>
                            <th style="text-align: center;">FAIL</th>
                            <th style="text-align: center;">NA</th>
                            <th>REMARKS / RECOMMENDATIONS </th>
                        </tr>


                        <tr>
                            <td><strong>4.5.1 </strong></td>
                            <td><strong>Boom Hoist </strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[39][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(39, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[39][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(39, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[39][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(39, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[39]" value="<?php echo getRemark(39, $saved_remarks); ?>">
                            </td>
                        </tr>
                        <tr>
                            <td><strong>4.5.2 </strong></td>
                            <td><strong>Main Load Hoist </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[40][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(40, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[40][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(40, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[40][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(40, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[40]" value="<?php echo getRemark(40, $saved_remarks); ?>">
                            </td>
                        </tr>


                        <tr>
                            <td><strong>4.5.3 </strong></td>
                            <td><strong>Auxiliary Load Hoist </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[41][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(41, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[41][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(41, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[41][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(41, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[41]" value="<?php echo getRemark(41, $saved_remarks); ?>">
                            </td>
                        </tr>


                        <tr>
                            <td><strong>4.6 </strong></td>
                            <td><strong>No leakages are visible on the hydraulic hoses, fittings, valves, & manifolds. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[42][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(42, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[42][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(42, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[42][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(42, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[42]" value="<?php echo getRemark(42, $saved_remarks); ?>">
                            </td>
                        </tr>


                        <tr>
                            <td><strong>4.7 </strong></td>
                            <td><strong>Nothing was deformed on the tubing, fittings, & other related components. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[43][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(43, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[43][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(43, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[43][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(43, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[43]" value="<?php echo getRemark(43, $saved_remarks); ?>">
                            </td>
                        </tr>


                        <tr>
                            <td><strong>4.8 </strong></td>
                            <td><strong>Boom angle indicator is provided and working properly.</strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[44][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(44, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[44][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(44, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[44][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(44, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[44]" value="<?php echo getRemark(44, $saved_remarks); ?>">
                            </td>
                        </tr>


                        <tr>
                            <td><strong>4.9 </strong></td>
                            <td><strong>BOOM BACK STOPS: Fixed bumper, Shock absorbing bumper, or Hydraulic bumper, is provided and in good condition. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[45][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(45, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[45][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(45, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[45][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(45, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[45]" value="<?php echo getRemark(45, $saved_remarks); ?>">
                            </td>
                        </tr>



<tr>
                            <th style="text-align: center;">4.10</th>
                            <th style="text-align: center;">WINCH DRUM’S LOCK (PAWLS) is in good condition & properly functioning (as applicable): </th>
                            <th style="text-align: center;">PASS</th>
                            <th style="text-align: center;">FAIL</th>
                            <th style="text-align: center;">NA</th>
                            <th>REMARKS / RECOMMENDATIONS </th>
                        </tr>

                        


                        <tr>
                            <td><strong>4.10.1 </strong></td>
                            <td><strong>Boom Hoist Drum </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[46][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(46, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[46][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(46, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[46][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(46, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[46]" value="<?php echo getRemark(46, $saved_remarks); ?>">
                            </td>
                        </tr>


                        <tr>
                            <td><strong>4.10.2 </strong></td>
                            <td><strong> Main Hoist Drum </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[47][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(47, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[47][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(47, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[47][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(47, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[47]" value="<?php echo getRemark(47, $saved_remarks); ?>">
                            </td>
                        </tr>


                        <tr>
                            <td><strong>4.10.3 </strong></td>
                            <td><strong>Auxiliary Hoist Drum </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[48][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(48, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[48][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(48, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[48][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(48, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[48]" value="<?php echo getRemark(48, $saved_remarks); ?>">
                            </td>
                        </tr>


                        <tr>
                            <td><strong>4.11 </strong></td>
                            <td><strong>Automatic Boom Back Stops: Maximum boom angle </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[49][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(49, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[49][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(49, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[49][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(49, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[49]" value="<?php echo getRemark(49, $saved_remarks); ?>">
                            </td>
                        </tr>


                        <tr>
                            <td><strong>4.12 </strong></td>
                            <td><strong>Automatic stop limit for minimum boom angle</strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[50][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(50, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[50][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(50, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[50][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(50, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[50]" value="<?php echo getRemark(50, $saved_remarks); ?>">
                            </td>
                        </tr>


                        <tr>
                            <td><strong>4.13 </strong></td>
                            <td><strong>Minimum Boom Length & Maximum Boom Length </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[51][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(51, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[51][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(51, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[51][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(51, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[51]" value="<?php echo getRemark(51, $saved_remarks); ?>">
                            </td>
                        </tr>


                        <tr>
                            <td><strong>4.14 </strong></td>
                            <td><strong>Boom cradle is provided and can secure the boom at rest. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[52][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(52, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[52][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(52, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[52][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(52, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[52]" value="<?php echo getRemark(52, $saved_remarks); ?>">
                            </td>
                        </tr>


                        <tr>
                            <td><strong>4.15</strong></td>
                            <td><strong>Sheaves are free from deformation, dent, bent, or damage and their bearings sufficiently lubricated. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[53][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(53, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[53][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(53, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[53][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(53, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[53]" value="<?php echo getRemark(53, $saved_remarks); ?>">
                            </td>
                        </tr>


                        <tr>
                            <td><strong>4.16</strong></td>
                            <td><strong>Aviation or pilot light is provided and is working. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[54][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(54, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[54][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(54, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[54][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(54, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[54]" value="<?php echo getRemark(54, $saved_remarks); ?>">
                            </td>
                        </tr>



                        <tr>
                            <th style="text-align: center;">5</th>
                            <th style="text-align: center;">CRANE STRUCTURE AND SWING COMPONENTS</th>
                            <th style="text-align: center;">PASS</th>
                            <th style="text-align: center;">FAIL</th>
                            <th style="text-align: center;">NA</th>
                            <th>REMARKS / RECOMMENDATIONS </th>
                        </tr>

                        <tr>
                            <td><strong>5.1</strong></td>
                            <td><strong>Base Structure/Pedestal/mast has no signs of loose bolts and fasteners.</strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[55][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(55, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[55][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(55, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[55][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(55, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[55]" value="<?php echo getRemark(55, $saved_remarks); ?>">
                            </td>
                        </tr>

                        <tr>
                            <td><strong>5.2 </strong></td>
                            <td><strong>Base Structure/Pedestal/mast’s welds and joints are free from corrosion and cracks.</strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[56][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(56, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[56][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(56, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[56][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(56, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[56]" value="<?php echo getRemark(56, $saved_remarks); ?>">
                            </td>
                        </tr>

                        <tr>
                            <td><strong>5.3 </strong></td>
                            <td><strong>Pins, bearings, shafts, gears, and locking devices are free from distortion, cracks and corrosion. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[57][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(57, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[57][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(57, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[57][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(57, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[57]" value="<?php echo getRemark(57, $saved_remarks); ?>">
                            </td>
                        </tr>


                        <tr>
                            <td><strong>5.4</strong></td>
                            <td><strong>Swing brakes operate and can restrict further movement of the rotating structure. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[58][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(58, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[58][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(58, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[58][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(58, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[58]" value="<?php echo getRemark(58, $saved_remarks); ?>">
                            </td>
                        </tr>


                        <tr>
                            <td><strong>5.5</strong></td>
                            <td><strong>Swing positive locking device is provided and can lock the structure from further movement.</strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[59][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(59, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[59][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(59, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[59][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(59, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[59]" value="<?php echo getRemark(59, $saved_remarks); ?>">
                            </td>
                        </tr>


                        <tr>
                            <td><strong>5.6 </strong></td>
                            <td><strong>Swing brake is adjustable to compensate its wear. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[60][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(60, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[60][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(60, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[60][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(60, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[60]" value="<?php echo getRemark(60, $saved_remarks); ?>">
                            </td>
                        </tr>


                        <tr>
                            <td><strong>5.7 </strong></td>
                            <td><strong>All swing moving parts are sufficiently lubricated. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[61][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(61, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[61][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(61, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[61][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(61, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[61]" value="<?php echo getRemark(61, $saved_remarks); ?>">
                            </td>
                        </tr>


                        <tr>
                            <td><strong>5.8 </strong></td>
                            <td><strong>Platforms and walkways are skid resistant. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[62][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(62, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[62][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(62, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[62][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(62, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[62]" value="<?php echo getRemark(62, $saved_remarks); ?>">
                            </td>
                        </tr>


                        <tr>
                            <td><strong>5.9 </strong></td>
                            <td><strong>Access ladders, & guard rails are free from rust, damage, & corrosion </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[63][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(63, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[63][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(63, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[63][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(63, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[63]" value="<?php echo getRemark(63, $saved_remarks); ?>">
                            </td>
                        </tr>



                        <tr>
                            <th style="text-align: center;">6 </th>
                            <th style="text-align: center;">MACHINERY POWER, ELECTRICAL COMPONENTS & HYDRAULIC COMPONENTS</th>
                            <th style="text-align: center;">PASS</th>
                            <th style="text-align: center;">FAIL</th>
                            <th style="text-align: center;">NA</th>
                            <th>REMARKS / RECOMMENDATIONS </th>
                        </tr>


                        <tr>
                            <td><strong>6.1 </strong></td>
                            <td><strong>Work areas, companion ways, access ladders, are equipped with anti-slip surface materials. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[64][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(64, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[64][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(64, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[64][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(64, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[64]" value="<?php echo getRemark(64, $saved_remarks); ?>">
                            </td>
                        </tr>


                        <tr>
                            <td><strong>6.2</strong></td>
                            <td><strong> Electrical wirings and related equipment are free of damages. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[65][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(65, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[65][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(65, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[65][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(65, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[65]" value="<?php echo getRemark(65, $saved_remarks); ?>">
                            </td>
                        </tr>


                        <tr>
                            <td><strong>6.3 </strong></td>
                            <td><strong>Manholes and hatches’ covers are provided to protect personnel from accidental fall. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[66][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(66, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[66][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(66, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[66][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(66, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[66]" value="<?php echo getRemark(66, $saved_remarks); ?>">
                            </td>
                        </tr>


                        <tr>
                            <td><strong>6.4 </strong></td>
                            <td><strong>Electrical and hydraulic motors & pumps are in good working condition. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[67][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(67, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[67][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(67, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[67][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(67, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[67]" value="<?php echo getRemark(67, $saved_remarks); ?>">
                            </td>
                        </tr>



                        <tr>
                            <td><strong>6.5 </strong></td>
                            <td><strong>Hydraulic hoses, fittings, tubes, and manifold joints have no evidence of leakages and not damage. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[68][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(68, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[68][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(68, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[68][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(68, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[68]" value="<?php echo getRemark(68, $saved_remarks); ?>">
                            </td>
                        </tr>


                        <tr>
                            <td><strong>6.6</strong></td>
                            <td><strong>Hydraulic/pneumatic cylinders, pumps and motors have no leaks and working properly. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[69][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(69, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[69][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(69, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[69][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(69, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[69]" value="<?php echo getRemark(69, $saved_remarks); ?>">
                            </td>
                        </tr>

                        <tr>
                            <td><strong>6.7</strong></td>
                            <td><strong>Engine power driven motors and pumps are working properly and have no signs of leaks. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[70][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(70, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[70][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(70, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[70][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(70, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[70]" value="<?php echo getRemark(70, $saved_remarks); ?>">
                            </td>
                        </tr>

                        <tr>
                            <td><strong>6.8</strong></td>
                            <td><strong>Machinery compartment is free from spills and obstruction. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[71][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(71, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[71][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(71, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[71][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(71, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[71]" value="<?php echo getRemark(71, $saved_remarks); ?>">
                            </td>
                        </tr>

                        <tr>
                            <td><strong>6.9</strong></td>
                            <td><strong>Fire extinguisher is provided in the compartment with minimum rating of 10BC. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[72][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(72, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[72][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(72, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[72][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(72, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[72]" value="<?php echo getRemark(72, $saved_remarks); ?>">
                            </td>
                        </tr>


                        <tr>
                            <td><strong>6.10</strong></td>
                            <td><strong>An emergency lowering system, if provided, shall be checked for proper function. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[73][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(73, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[73][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(73, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[73][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(73, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[73]" value="<?php echo getRemark(73, $saved_remarks); ?>">
                            </td>
                        </tr>



                        <tr>
                            <th style="text-align: center;">7 </th>
                            <th style="text-align: center;">CABIN</th>
                            <th style="text-align: center;">PASS</th>
                            <th style="text-align: center;">FAIL</th>
                            <th style="text-align: center;">NA</th>
                            <th>REMARKS / RECOMMENDATIONS </th>
                        </tr>


                        <tr>
                            <td><strong>7.1 </strong></td>
                            <td><strong>Portable fire extinguisher is provided. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[74][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(74, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[74][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(74, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[74][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(74, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[74]" value="<?php echo getRemark(74, $saved_remarks); ?>">
                            </td>
                        </tr>


                        <tr>
                            <td><strong>7.2 </strong></td>
                            <td><strong>Toolbox with basic tools are available. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[75][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(75, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[75][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(75, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[75][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(75, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[75]" value="<?php echo getRemark(75, $saved_remarks); ?>">
                            </td>
                        </tr>


                        <tr>
                            <td><strong>7.3 </strong></td>
                            <td><strong>An Emergency stop button shall be available and working effectively. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[76][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(76, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[76][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(76, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[76][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(76, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[76]" value="<?php echo getRemark(76, $saved_remarks); ?>">
                            </td>
                        </tr>


                        <tr>
                            <td><strong>7.4 </strong></td>
                            <td><strong>Wipers are installed and working properly. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[77][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(77, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[77][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(77, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[77][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(77, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[77]" value="<?php echo getRemark(77, $saved_remarks); ?>">
                            </td>
                        </tr>

                        <tr>
                            <td><strong>7.5 </strong></td>
                            <td><strong>An audible warning device is provided for any errors in the system. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[78][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(78, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[78][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(78, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[78][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(78, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[78]" value="<?php echo getRemark(78, $saved_remarks); ?>">
                            </td>
                        </tr>

                        <tr>
                            <td><strong>7.6 </strong></td>
                            <td><strong>Cabin has a good housekeeping. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[79][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(79, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[79][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(79, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[79][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(79, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[79]" value="<?php echo getRemark(79, $saved_remarks); ?>">
                            </td>
                        </tr>

                        <tr>
                            <td><strong>7.7 </strong></td>
                            <td><strong>Cabin door is open outward or sliding backward. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[80][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(80, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[80][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(80, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[80][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(80, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[80]" value="<?php echo getRemark(80, $saved_remarks); ?>">
                            </td>
                        </tr>

                        <tr>
                            <td><strong>7.8</strong></td>
                            <td><strong> Windshield is of Safety glazing glass. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[81][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(81, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[81][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(81, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[81][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(81, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[81]" value="<?php echo getRemark(81, $saved_remarks); ?>">
                            </td>
                        </tr>

                        <tr>
                            <td><strong>7.9 </strong></td>
                            <td><strong>Operator seat condition (torn seat or back cushions). </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[82][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(82, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[82][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(82, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[82][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(82, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[82]" value="<?php echo getRemark(82, $saved_remarks); ?>">
                            </td>
                        </tr>

                        <tr>
                            <td><strong>7.10 </strong></td>
                            <td><strong>Cabin A/C is provided and is working. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[83][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(83, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[83][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(83, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[83][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(83, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[83]" value="<?php echo getRemark(83, $saved_remarks); ?>">
                            </td>
                        </tr>

                        <tr>
                            <td><strong>7.11 </strong></td>
                            <td><strong>The Installed anemometer is working. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[84][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(84, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[84][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(84, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[84][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(84, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[84]" value="<?php echo getRemark(84, $saved_remarks); ?>">
                            </td>
                        </tr>

                        <tr>
                            <td><strong>7.12 </strong></td>
                            <td><strong>The installed view camera is working </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[85][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(85, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[85][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(85, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[85][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(85, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[85]" value="<?php echo getRemark(85, $saved_remarks); ?>">
                            </td>
                        </tr>
                        <tr>
                            <td><strong>7.13 </strong></td>
                            <td><strong> All applicable indicators like but not limited to; oil or water temperature gauge, hydraulic oil pressure gauge, etc. are working correctly. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[86][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(86, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[86][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(86, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[86][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(86, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[86]" value="<?php echo getRemark(86, $saved_remarks); ?>">
                            </td>
                        </tr>

                        <tr>
                            <th style="text-align: center;">8</th>
                            <th style="text-align: center;">HOIST ROPES</th>
                            <th rowspan="2" style="text-align: center;">PASS</th>
                            <th rowspan="2" style="text-align: center;">FAIL</th>
                            <th rowspan="2" style="text-align: center;">NA</th>
                            <th rowspan="2"> REMARKS / RECOMMENDATIONS </th>
                        </tr>
						
						
						<tr>
                            <th style="text-align: center;">8.1</th>
                            <th style="text-align: center;">Remaining rope on drum is at least two full wraps when the boom hoist or load hoist is at its lowest angle or maximum pay-out respectively.</th>                          
                            
                            
                            
                        </tr>
						
						


                        <tr>
                            <td><strong>8.1.1 </strong></td>
                            <td><strong>Boom Hoist </strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[87][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(87, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[87][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(87, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[87][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(87, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[87]" value="<?php echo getRemark(87, $saved_remarks); ?>">
                            </td>
                        </tr>

                        <tr>
                            <td><strong>8.1.2 </strong></td>
                            <td><strong>Main Load Hoist </strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[88][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(88, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[88][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(88, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[88][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(88, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[88]" value="<?php echo getRemark(88, $saved_remarks); ?>">
                            </td>
                        </tr>


                        <tr>
                            <td><strong>8.1.3 </strong></td>
                            <td><strong>Auxiliary Load Hoist </strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[89][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(89, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[89][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(89, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[89][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(89, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[89]" value="<?php echo getRemark(89, $saved_remarks); ?>">
                            </td>
                        </tr>

                        <tr>
                            <th style="text-align: center;">8.2</th>
                            <th style="text-align: center;">Wire rope shall be free from bird caging, corrosion, crushing, kinking, un-stranding, core protrusion, main strand displacement, evidence of heat damage, or any other damage.</th>
                            <th style="text-align: center;">PASS</th>
                            <th style="text-align: center;">FAIL</th>
                            <th style="text-align: center;">NA</th>
                            <th>REMARKS / RECOMMENDATIONS </th>
                        </tr>





                        <tr>
                            <td><strong>                        8.2.1	 </strong></td>
                            <td><strong>Boom Hoist</strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[90][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(90, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[90][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(90, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[90][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(90, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[90]" value="<?php echo getRemark(90, $saved_remarks); ?>">
                            </td>
                        </tr>


                        <tr>
                            <td><strong>8.2.2	 </strong></td>
                            <td><strong>Main Load Hoist</strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[91][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(91, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[91][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(91, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[91][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(91, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[91]" value="<?php echo getRemark(91, $saved_remarks); ?>">
                            </td>
                        </tr>


                        <tr>
                            <td><strong>8.2.3	 </strong></td>
                            <td><strong>
                            Auxiliary Load Hoist  </strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[92][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(92, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[92][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(92, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[92][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(92, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[92]" value="<?php echo getRemark(92, $saved_remarks); ?>">
                            </td>
                        </tr>



                        <tr>
                            <th style="text-align: center;">8.3</th>
                            <th style="text-align: center;">The rope does not have more than two broken wires in 1 lay in sections beyond end connections or more than 1 broken wire at an end connection (for standing ropes).</th>
                            <th style="text-align: center;">PASS</th>
                            <th style="text-align: center;">FAIL</th>
                            <th style="text-align: center;">NA</th>
                            <th>REMARKS / RECOMMENDATIONS </th>
                        </tr>



                        
	

                        <tr>
                            <td><strong>                        8.3.1	 </strong></td>
                            <td><strong>Boom Hoist</strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[93][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(93, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[93][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(93, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[93][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(93, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[93]" value="<?php echo getRemark(93, $saved_remarks); ?>">
                            </td>
                        </tr>


                        <tr>
                            <td><strong>8.3.2	 </strong></td>
                            <td><strong>Main Load Hoist</strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[94][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(94, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[94][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(94, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[94][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(94, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[94]" value="<?php echo getRemark(94, $saved_remarks); ?>">
                            </td>
                        </tr>


                        <tr>
                            <td><strong>8.3.3	 </strong></td>
                            <td><strong>
                            Auxiliary Load Hoist  </strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[95][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(95, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[95][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(95, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[95][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(95, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[95]" value="<?php echo getRemark(95, $saved_remarks); ?>">
                            </td>
                        </tr>



                        <tr>
                            <th style="text-align: center;">8.4</th>
                            <th style="text-align: center;">The rope does not have more than 6 randomly distributed broken wires in 1 lay or 3 in 1 strand is 1 lay (for running ropes).</th>
                            <th style="text-align: center;">PASS</th>
                            <th style="text-align: center;">FAIL</th>
                            <th style="text-align: center;">NA</th>
                            <th>REMARKS / RECOMMENDATIONS </th>
                        </tr>



                        
	

                        <tr>
                            <td><strong>                        8.4.1	 </strong></td>
                            <td><strong>Boom Hoist</strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[96][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(96, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[96][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(96, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[96][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(96, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[96]" value="<?php echo getRemark(96, $saved_remarks); ?>">
                            </td>
                        </tr>


                        <tr>
                            <td><strong>8.4.2	 </strong></td>
                            <td><strong>Main Load Hoist</strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[97][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(97, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[97][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(97, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[97][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(97, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[97]" value="<?php echo getRemark(97, $saved_remarks); ?>">
                            </td>
                        </tr>


                        <tr>
                            <td><strong>8.4.3	 </strong></td>
                            <td><strong>
                            Auxiliary Load Hoist  </strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[98][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(98, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[98][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(98, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[98][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(98, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[98]" value="<?php echo getRemark(98, $saved_remarks); ?>">
                            </td>
                        </tr>



                        <tr>
                            <th style="text-align: center;">8.5</th>
                            <th style="text-align: center;">The rope dead end is correctly terminated as per the applicable standard.</th>
                            <th style="text-align: center;">PASS</th>
                            <th style="text-align: center;">FAIL</th>
                            <th style="text-align: center;">NA</th>
                            <th>REMARKS / RECOMMENDATIONS </th>
                        </tr>



                        
	

                        <tr>
                            <td><strong>                        8.5.1	 </strong></td>
                            <td><strong>Boom Hoist</strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[99][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(99, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[99][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(99, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[99][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(99, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[99]" value="<?php echo getRemark(99, $saved_remarks); ?>">
                            </td>
                        </tr>


                        <tr>
                            <td><strong>8.5.2</strong></td>
                            <td><strong>Main Load Hoist</strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[100][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(100, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[100][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(100, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[100][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(100, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[100]" value="<?php echo getRemark(100, $saved_remarks); ?>">
                            </td>
                        </tr>


                        <tr>
                            <td><strong>8.5.3	 </strong></td>
                            <td><strong>
                            Auxiliary Load Hoist  </strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[101][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(101, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[101][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(101, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[101][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(101, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[101]" value="<?php echo getRemark(101, $saved_remarks); ?>">
                            </td>
                        </tr>



                        <tr>
                            <th style="text-align: center;">8.6</th>
                            <th style="text-align: center;">The rope (Boom & Load hoist) is correctly and securely anchored on the drum.</th>
                            <th style="text-align: center;">PASS</th>
                            <th style="text-align: center;">FAIL</th>
                            <th style="text-align: center;">NA</th>
                            <th>REMARKS / RECOMMENDATIONS </th>
                        </tr>



                        
	

                        <tr>
                            <td><strong>                        8.6.1	 </strong></td>
                            <td><strong>Boom Hoist</strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[102][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(102, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[102][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(102, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[102][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(102, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[102]" value="<?php echo getRemark(102, $saved_remarks); ?>">
                            </td>
                        </tr>


                        <tr>
                            <td><strong>8.6.2	 </strong></td>
                            <td><strong>Main Load Hoist</strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[103][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(103, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[103][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(103, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[103][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(103, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[103]" value="<?php echo getRemark(103, $saved_remarks); ?>">
                            </td>
                        </tr>


                        <tr>
                            <td><strong>8.6.3	 </strong></td>
                            <td><strong>
                            Auxiliary Load Hoist  </strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[104][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(104, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[104][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(104, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[104][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(104, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[104]" value="<?php echo getRemark(104, $saved_remarks); ?>">
                            </td>
                        </tr>



                        <tr>
                            <th style="text-align: center;">8.7</th>
                            <th style="text-align: center;">The wire rope is correctly & adequately lubricated.</th>
                            <th style="text-align: center;">PASS</th>
                            <th style="text-align: center;">FAIL</th>
                            <th style="text-align: center;">NA</th>
                            <th>REMARKS / RECOMMENDATIONS </th>
                        </tr>



                        
	

                        <tr>
                            <td><strong>                        8.7.1	 </strong></td>
                            <td><strong>Boom Hoist</strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[105][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(105, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[105][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(105, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[105][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(105, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[105]" value="<?php echo getRemark(105, $saved_remarks); ?>">
                            </td>
                        </tr>


                        <tr>
                            <td><strong>8.7.2	 </strong></td>
                            <td><strong>Main Load Hoist</strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[106][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(106, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[106][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(106, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[106][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(106, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[106]" value="<?php echo getRemark(106, $saved_remarks); ?>">
                            </td>
                        </tr>


                        <tr>
                            <td><strong>8.7.3	 </strong></td>
                            <td><strong>
                            Auxiliary Load Hoist  </strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[107][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(107, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[107][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(107, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[107][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(107, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[107]" value="<?php echo getRemark(107, $saved_remarks); ?>">
                            </td>
                        </tr>


                        <tr>
                            <th style="text-align: center;">8.8</th>
                            <th style="text-align: center;">Drum Hoist brakes: Boom & Load Hoist (Main & auxiliary) are operational.</th>
                            <th style="text-align: center;">PASS</th>
                            <th style="text-align: center;">FAIL</th>
                            <th style="text-align: center;">NA</th>
                            <th>REMARKS / RECOMMENDATIONS </th>
                        </tr>                     
	                    <tr>
                            <td>
                                <strong>
                                8.8.1
                            </strong></td>
                            <td><strong>Boom Hoist</strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[108][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(108, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[108][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(108, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[108][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(108, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[108]" value="<?php echo getRemark(108, $saved_remarks); ?>">
                            </td>
                        </tr>
                        <tr>
                            <td><strong>8.8.2	 </strong></td>
                            <td><strong>Main Load Hoist</strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[109][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(109, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[109][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(109, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[109][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(109, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[109]" value="<?php echo getRemark(109, $saved_remarks); ?>">
                            </td>
                        </tr>
                        <tr>
                            <td><strong>8.8.3	 </strong></td>
                            <td><strong>
                            Auxiliary Load Hoist  </strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[110][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(110, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[110][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(110, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[110][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(110, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[110]" value="<?php echo getRemark(110, $saved_remarks); ?>">
                            </td>
                        </tr>


                        <tr>
                            <td><strong>8.9	 </strong></td>
                            <td><strong>
                            
                            Anti-two-blocking system (A2B) is working correctly.
                        </strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[111][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(111, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[111][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(111, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[111][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(111, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[111]" value="<?php echo getRemark(111, $saved_remarks); ?>">
                            </td>
                        </tr>


                        <tr>
                            <th style="text-align: center;">9</th>
                            <th style="text-align: center;"> HOOKS</th>
                            <th style="text-align: center;">PASS</th>
                            <th style="text-align: center;">FAIL</th>
                            <th style="text-align: center;">NA</th>
                            <th>REMARKS / RECOMMENDATIONS </th>
                        </tr>
						
						<tr>
                            <td><strong>9.1	 </strong></td>
                            <td><strong>
                            
                            Labeling and manufacturer data are available & legible.
                        </strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[112][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(112, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[112][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(112, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[112][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(112, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[112]" value="<?php echo getRemark(112, $saved_remarks); ?>">
                            </td>
                        </tr>
						
						
						<tr>
                            <td><strong>9.2	 </strong></td>
                            <td><strong>
                            
                            Hook is not bent or twisted. Maximum bending or twisting is not to exceed 10 degrees from plane of unbent hook.
                        </strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[113][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(113, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[113][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(113, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[113][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(113, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[113]" value="<?php echo getRemark(113, $saved_remarks); ?>">
                            </td>
                        </tr>
						
						<tr>
                            <td><strong>9.3	 </strong></td>
                            <td><strong>
                            
                            Hook has no crack, nick, gouge, or excessive wear.
                        </strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[114][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(114, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[114][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(114, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[114][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(114, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[114]" value="<?php echo getRemark(114, $saved_remarks); ?>">
                            </td>
                        </tr>
						
						
						<tr>
                            <td><strong>9.4	 </strong></td>
                            <td><strong>
                            
                            Hook is not distorted in the throat opening. Maximum allowable throat opening is 15% compared to new hook or as per manufacturer recommendation.
                        </strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[115][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(115, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[115][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(115, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[115][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(115, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[115]" value="<?php echo getRemark(115, $saved_remarks); ?>">
                            </td>
                        </tr>
						
						
						<tr>
                            <td><strong>9.5	 </strong></td>
                            <td><strong>
                            
                            Hook latch is operative.
                        </strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[116][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(116, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[116][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(116, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[116][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(116, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[116]" value="<?php echo getRemark(116, $saved_remarks); ?>">
                            </td>
                        </tr>
						
						<tr>
                            <td><strong>9.6	 </strong></td>
                            <td><strong>
                            
                            Hook is rotating freely.
                        </strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[117][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(117, 'PASS', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[117][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(117, 'FAIL', $saved_results); ?>>
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="result[117][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(117, 'NA', $saved_results); ?>>
                            </td>
                            <td>
                                <input type="text" name="checklist_remark[117]" value="<?php echo getRemark(117, $saved_remarks); ?>">
                            </td>
                        </tr>


                </table>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th colspan="3" style="text-align: center;">REMARKS / RECOMMENDATIONS: </td>
                        </tr>
                        <tr>
                            <td style="height: 120px;" colspan="3">
                                <textarea style="width: 100%; height: 100%; box-sizing: border-box;" name="recommendations">

                                </textarea>
                            </td>

                        </tr>
                    </tbody>
                </table>

            </div>


<div class="table-responsive">
    <table class="table table-bordered">
        <tbody>
            <tr>
                <th style="text-align: center;">REVISION NO.</th>
                <th style="text-align: center;">TYPE OF REVISION</th>
                <th style="text-align: center;">DATE</th>
            </tr>
            <tr>
                <td>
                    00	
                </td>
                <td>
                    New Checklist	
                </td>
                <td>
                    Feb. 09, 2020
                </td>
            </tr>
            <tr>
                <td>
                    01	
                </td>
                <td>
                    Addition of Inspection Criteria’s and Line Items	
                </td>
                <td>
                    Oct. 08, 2020
                </td>
            </tr>
        </tbody>
    </table>
</div>


<!--<div class="table-responsive">-->
<!--    <table class="table table-bordered">-->
<!--        <tbody>-->
<!--            <tr>-->
<!--                <th style="text-align: center;">REVISION NO.</th>-->
<!--                <th style="text-align: center;">TYPE OF REVISION</th>-->
<!--                <th style="text-align: center;">DATE</th>-->
<!--            </tr>-->
<!--            <tr>-->
<!--                <td>-->
<!--                    <textarea style="width: 100%; box-sizing: border-box; resize: vertical;" name="revision_1" rows="2"></textarea>-->
<!--                </td>-->
<!--                <td>-->
<!--                    <textarea style="width: 100%; box-sizing: border-box; resize: vertical;" name="type_revision_1" rows="2"></textarea>-->
<!--                </td>-->
<!--                <td>-->
<!--                    <input type="date" class="form-control" name="revision_1_date">-->
<!--                </td>-->
<!--            </tr>-->
<!--            <tr>-->
<!--                <td>-->
<!--                    <textarea style="width: 100%; box-sizing: border-box; resize: vertical;" name="revision_2" rows="2"></textarea>-->
<!--                </td>-->
<!--                <td>-->
<!--                    <textarea style="width: 100%; box-sizing: border-box; resize: vertical;" name="type_revision_2" rows="2"></textarea>-->
<!--                </td>-->
<!--                <td>-->
<!--                    <input type="date" class="form-control" name="revision_2_date">-->
<!--                </td>-->
<!--            </tr>-->
<!--        </tbody>-->
<!--    </table>-->
<!--</div>-->



            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 25%;">INSPECTOR’S NAME:</th>
                        <td style="width: 25%;">
                            <input type="text" name="inspected_by" value="<?php echo htmlspecialchars($row['inspected_by'] ?? ''); ?>" class="form-control" style="font-weight: bold; border: none; background: transparent;">
                        </td>
                        <th style="width: 25%;">CLIENT’S REP. NAME:</th>
                        <td style="width: 25%;" onclick="openModal()">
                            <span id="clientNameDisplay"><?php echo !empty($row['client_rep_name']) ? htmlspecialchars($row['client_rep_name']) : 'Click to enter'; ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th>SIGNATURE & DATE:</th>
                        <td>
<?php
if (!empty($row['inspected_by'])) {
    $inspector_name = $row['inspected_by'];

    // Query inspectors table
    $sql = "SELECT signature_photo FROM inspectors WHERE inspector_name = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("s", $inspector_name);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $inspector = $result->fetch_assoc();
            // $image_path = 'https://appcims.com/whiteapp/inspector/uploads/' . str_replace(' ', '_', strtolower($inspector_name)) . '/images/signature_image.jpg';
            $image_path = 'http://localhost/whiteappupdated/inspector/uploads/' . str_replace(' ', '_', strtolower($inspector_name)) . '/images/signature_image.jpg';
            
            echo "<img src='$image_path' alt='Inspector Signature' style='max-width: 100px; max-height: 50px;'>";
        } else {
            echo "Inspector not found.";
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
} else {
    echo "Inspector's name is not available.";
}
?>
</td>
                        <th>SIGNATURE & DATE:</th>
                        <td style="width: 25%;" onclick="openModal()">
                            <img id="clientSignatureDisplay" src="<?php echo !empty($row['client_signature']) ? "../../uploads/" . htmlspecialchars($row['client_signature']) . '?t=' . time() : ''; ?>" alt="Click to add signature" style="max-width: 100px; max-height: 50px; cursor: pointer; <?php echo empty($row['client_signature']) ? 'display:none;' : ''; ?>">
                            <?php if(empty($row['client_signature'])): ?>
                                <span id="signaturePlaceholder">Click to add signature</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>


           <!-- Modal for Client's Name, Phone, and Signature -->
<div id="clientSignatureModal" class="modal" style="display: none;">
    <div class="modal-content" style="padding: 20px; width: 400px; margin: auto; background: #fff; border-radius: 8px;">
        <span class="close" onclick="closeModal()" style="cursor: pointer; float: right;">&times;</span>
        <h3>Enter Client's Details</h3>
        
            <div>
                <label for="clientName">Client's Name:</label>
                <input type="text" id="clientName" name="client_name" value="<?php echo htmlspecialchars($row['client_rep_name'] ?? ''); ?>" required style="width: 100%; padding: 5px; margin-bottom: 15px;">
            </div>
            <div>
    <label for="clientPhone">Client's Phone:</label>
    <input type="tel" id="clientPhone" name="client_phone" value="<?php echo !empty($row['client_phone']) ? htmlspecialchars($row['client_phone']) : '+966'; ?>" 
           style="width: 100%; padding: 5px; margin-bottom: 15px;" required>
</div>
            <div>
                <label>Signature:</label>
                <canvas id="signaturePad" style="border: 1px solid #ccc; width: 100%; height: 150px;"></canvas>
                <button type="button" onclick="clearSignature()" style="margin-top: 10px;">Clear Signature</button>
            </div>
            <div style="margin-top: 15px;">
                <button type="button" onclick="saveClientDetails()">Save</button>
            </div>
        
    </div>
</div>

<input type="hidden" name="client_name" id="hiddenClientName" value="<?php echo htmlspecialchars($row['client_rep_name'] ?? ''); ?>">
<input type="hidden" name="client_phone" id="hiddenClientPhone" value="<?php echo htmlspecialchars($row['client_phone'] ?? ''); ?>">
<input type="hidden" name="client_signature" id="hiddenClientSignature">


            <div class="col-12">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>

    <?php
    // Close the connection at the very end
    $conn->close();
    ?>


    <script>
        function preparePrint() {
            // Change the headers before printing
            document.querySelectorAll('#data-table thead tr th').forEach((th, index) => {
                if (index % 4 === 0) {
                    th.textContent = 'Print Header Set ' + (Math.floor(index / 4) + 1);
                } else {
                    th.textContent = 'Print Column ' + index;
                }
            });
            // Trigger print dialog
            window.print();
        }
    </script>


    <script>
        document.getElementById('checklistForm').addEventListener('submit', function(event) {
            const checkboxes = document.querySelectorAll('input[type="checkbox"]');
            const remarks = document.querySelectorAll('input[type="text"]');
            let isValid = true;

            // Check if at least one checkbox is selected for each question
            const resultGroups = {};
            checkboxes.forEach(checkbox => {
                const name = checkbox.name;
                if (!resultGroups[name]) resultGroups[name] = false;
                if (checkbox.checked) resultGroups[name] = true;
            });

            for (const group in resultGroups) {
                if (!resultGroups[group]) {
                    isValid = false;
                    alert(`Please select a result for ${group}`);
                    break;
                }
            }

            // Collect remarks: Optional validation - empty remarks result in an empty array
            const remarksArray = [];
            remarks.forEach(remark => {
                if (remark.value.trim() !== '') {
                    remarksArray.push(remark.value.trim()); // Push non-empty remarks to the array
                } else {
                    remarksArray.push(''); // Push an empty string for empty remarks
                }
            });

            console.log('Remarks Array:', remarksArray); // Log the remarks for debugging

            // Prevent form submission if validation fails
            if (!isValid) {
                event.preventDefault();
            }
        });
    </script>



    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const checklistForm = document.getElementById("checklistForm");

            if (checklistForm) {
                // Ensure only one checkbox is selected per row for the result field
                checklistForm.addEventListener("change", function(event) {
                    if (event.target.type === "checkbox" && event.target.name.startsWith("result")) {
                        const currentRow = event.target.closest("tr");
                        const checkboxes = currentRow.querySelectorAll("input[type='checkbox'][name='" + event.target.name + "']");

                        checkboxes.forEach(checkbox => {
                            if (checkbox !== event.target) {
                                checkbox.checked = false; // Uncheck other checkboxes in the same group
                            }
                        });
                    }
                });
            }
        });
    </script>



    <script>
        let signaturePad;

        // Ensure SignaturePad is loaded and ready
        function openModal() {
            if (typeof SignaturePad !== "undefined") {
                document.getElementById("clientSignatureModal").style.display = "block";

                const canvas = document.getElementById("signaturePad");
                canvas.width = canvas.offsetWidth;
                canvas.height = canvas.offsetHeight;

                signaturePad = new SignaturePad(canvas);

                // If there's an existing signature, load it
                const existingSignature = document.getElementById("hiddenClientSignature").value;
                if (existingSignature) {
                    signaturePad.fromDataURL(existingSignature);
                }
            } else {
                console.error("SignaturePad library is not loaded.");
            }
        }

        function closeModal() {
            document.getElementById("clientSignatureModal").style.display = "none";
        }

        function clearSignature() {
            if (signaturePad) {
                signaturePad.clear();
            }
        }

        function saveClientDetails() {
    if (!signaturePad) {
        alert("Signature pad is not initialized. Please try again.");
        return;
    }

    if (signaturePad.isEmpty()) {
        alert("Please provide a signature.");
        return;
    }

    const clientName = document.getElementById("clientName").value;
    if (!clientName) {
        alert("Please enter the client's name.");
        return;
    }

    const clientPhone = document.getElementById("clientPhone").value;
    
    const signatureData = signaturePad.toDataURL(); // Base64 format
    document.getElementById("clientNameDisplay").innerText = clientName;
    document.getElementById("clientSignatureDisplay").src = signatureData;
    document.getElementById("clientSignatureDisplay").style.display = 'block';
    if(document.getElementById("signaturePlaceholder")) {
        document.getElementById("signaturePlaceholder").style.display = 'none';
    }

    // Set hidden inputs for submission
    document.getElementById("hiddenClientName").value = clientName;
    document.getElementById("hiddenClientPhone").value = clientPhone;
    document.getElementById("hiddenClientSignature").value = signatureData;

    closeModal();
}

function saveClientDetailsNonBlocking() {
    const clientName = document.getElementById("clientName").value;
    const clientPhone = document.getElementById("clientPhone").value;
    
    document.getElementById("clientNameDisplay").innerText = clientName;
    document.getElementById("hiddenClientName").value = clientName;
    document.getElementById("hiddenClientPhone").value = clientPhone;
    
    // Check if signature pad has data, if so update hidden signature
    if (signaturePad && !signaturePad.isEmpty()) {
        const signatureData = signaturePad.toDataURL();
        document.getElementById("clientSignatureDisplay").src = signatureData;
        document.getElementById("clientSignatureDisplay").style.display = 'block';
        if(document.getElementById("signaturePlaceholder")) {
            document.getElementById("signaturePlaceholder").style.display = 'none';
        }
        document.getElementById("hiddenClientSignature").value = signatureData;
    }

    closeModal();
}
// Update the save button in modal to use the non-blocking version if we already have a signature
document.querySelector('#clientSignatureModal button[onclick="saveClientDetails()"]').setAttribute('onclick', 'saveClientDetailsNonBlocking()');
    </script>
    
    
    <script>
    const phoneInput = document.getElementById('clientPhone');

    phoneInput.addEventListener('input', () => {
        if (!phoneInput.value.startsWith('+966')) {
            phoneInput.value = '+966';
        }
    });

    phoneInput.addEventListener('keydown', (e) => {
        const cursorPosition = phoneInput.selectionStart;
        if (cursorPosition <= 4 && (e.key === 'Backspace' || e.key === 'Delete')) {
            e.preventDefault(); // Prevent deleting "+966"
        }
    });
</script>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>