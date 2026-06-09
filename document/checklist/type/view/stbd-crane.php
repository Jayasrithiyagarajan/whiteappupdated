<?php
include_once('./view-fetch.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRANE HEALTH CHECK INSPECTION CHECKLIST FOR OFFSHORE PEDESTAL CRANES & FLOATING CRANES </title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link href="../style.css" rel="stylesheet">


    <style>
    
     /* Add this to your existing styles */
.table-bordered,
.table-bordered th,
.table-bordered td {
    border: 1px solid black !important;
}

.table thead th {
    border-bottom: 1px solid black !important;
}
        /* Hide elements with the "no-print" class when printing */
        @media print {
            .no-print {
                display: none !important;
            }
        }

        /* .large-checkbox {
    width: 20px;
    height: 20px;
} */
        /* Custom checkbox styling */
        .custom-checkbox {
            appearance: none;
            width: 24px;
            height: 26px;
            border: 2px solid #ccc;
            border-radius: 3px;
            display: inline-block;
            vertical-align: middle;
            margin: 0;
            outline: none;
            cursor: not-allowed;
            /* Indicates it's disabled */
            position: relative;
        }

        /* Checked state with blue background */
        /* .custom-checkbox:checked { */
        /* background-color: #007bff;  */
        /* Blue background */
        /* border-color: #007bff; */
        /* Match the border with the background */
        /* } */

        .custom-checkbox:checked::after {
            content: '';
            position: absolute;
            top: 3px;
            left: 7px;
            width: 5px;
            height: 10px;
            border: 2px solid blue;
            /* Checkmark in blue */
            border-width: 0 3px 3px 0;
            transform: rotate(45deg);
        }

        /* Ensure styles are applied when printing */
        /*@media print {*/
            /* .custom-checkbox {
        border-color: #007bff;
        background-color: #007bff;
    } */

            /* Ensure styles are applied when printing */
   @media print {
    /* Ensure background colors print */
    body * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    
    /* Table header styles */
    .table thead th {
        background-color: #c0d6e8 !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
        border-color: #454d55 !important;
    }
    
    /* Specifically style the dark headers */
    .thead-dark th {
        background-color: #c0d6e8 !important;
        border-color: #454d55 !important;
    }
    
    /* For all th elements */
    th {
        background-color: #c0d6e8 !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    
    .custom-checkbox:checked::after {
        border-color: blue;
    }
    
    /* For printing table borders */
    .table-bordered,
    .table-bordered th,
    .table-bordered td {
        border-color: black !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    
    .table thead th {
        border-bottom: 1px solid black !important;
    }
    
    /* Hide non-print elements */
    .no-print {
        display: none !important;
    }
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
                    
                    <td>FRM.0601-1.0	</td>
                    <td>Revision 02	</td>
                    <td>Issue Date: 08/OCT/2020</td>
                </tr>
                <tr>
                    <td class="left-align">Prepared By<br>Operations Manager</td>
                    <td class="left-align">Reviewed & Approved By<br>Managing Director</td>

                    <td><img src="../../../code.png" width="80px" height="80px" alt="" /></td>
                </tr>
            </table>
        </div>

        <h4>PEDESTAL CRANES, FLOATING CRANES & FLOATING DERRICKS, ARTICULATING BOOM CRANES

 </h4>
        <h4>ASME B30.4-2015, ASME B30.8-2015, ASME B30.22-2016, API SPEC 2C-2012, API RP 2D-2014  </h4>
        <!--<button class="btn btn-primary no-print" onclick="preparePrint()">Print View</button>-->

        <div class="table-responsive">
            <table class="table table-bordered">
                <tr>
                    <th style="width: 25%; background-color: #c0d6e8 !important;">VESSEL NAME:</th>
                    <td style="width: 25%;"><strong> <?php echo htmlspecialchars($row['vessel_name']); ?></strong></td>
                    <th style="width: 25%; background-color: #c0d6e8 !important;">REPORT NO: </th>
                    <td style="width: 25%;"><strong> <?php echo htmlspecialchars($row['report_no']); ?></strong></td>
                </tr>
                <tr>
                    <th style="background-color: #c0d6e8 !important;">LOCATION</th>
                    <td><strong> <?php echo htmlspecialchars($row['location']); ?></strong></td>
                    <th style="background-color: #c0d6e8 !important;">INSPECTION DATE</th>
                    <td><strong> <?php echo htmlspecialchars($row['inspection_date']); ?></strong></td>
                </tr>

                <tr>                    
                    <th style="background-color: #c0d6e8 !important;">EQUIPMENT NO</th>
                    <td><strong> <?php echo htmlspecialchars($row['equipment_no']); ?></strong></td>
                    <th style="background-color: #c0d6e8 !important;">EQUIPMENT TYPE</th>
                    <td><strong> <?php echo htmlspecialchars($row['equipmenttype']); ?></strong></td>
                </tr>

                
                <tr>
                    <th style="background-color: #c0d6e8 !important;">MANUFACTURER:</th>
                    <td><strong> <?php echo htmlspecialchars($row['manufacturer']); ?></strong></td>
                    <th style="background-color: #c0d6e8 !important;">YEAR MODEL:</th>
                    <td><strong> <?php echo htmlspecialchars($row['year_model']); ?></strong></td>
                </tr>
                <tr>
                    <th style="background-color: #c0d6e8 !important;">MODEL NO.:</th>
                    <td><strong> <?php echo htmlspecialchars($row['model_no']); ?></strong></td>
                    <th style="background-color: #c0d6e8 !important;">CAPACITY (SWL):</th>
                    <td><strong> <?php echo htmlspecialchars($row['capacity_swl']); ?></strong></td>
                </tr>
                <tr>                    
                    <th style="background-color: #c0d6e8 !important;">EQUIP.SERIAL NO.:</th>
                    <td><strong> <?php echo htmlspecialchars($row['crane_serial_no']); ?></strong></td>
                    <th style="background-color: #c0d6e8 !important;">CLIENT’S NAME</th>
                    <td><strong><?php echo htmlspecialchars($row['client_name']); ?></strong></td>
                </tr>             
            </table>
        </div>

        <form method="post" action="?">
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
                            <th style="text-align: center;">REQUIRED DOCUMENTS</th>
                            <th style="text-align: center;">PASS</th>
                            <th style="text-align: center;">FAIL</th>
                            <th style="text-align: center;">NA</th>
                            <th> </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>1.1</strong></td>
                            <td><strong> Owner’s Manual or Technical Manual. </strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[0] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[0] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[0] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[0]; ?>" disabled>
                            </td>
                        </tr>

                        <tr>
                            <td><strong>1.2</strong></td>
                            <td><strong>Crane Log Book Records. </strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[1] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[1] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[1] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[1]" value="<?php echo $chek_remark[1]; ?>" disabled>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>1.3</strong></td>
                            <td><strong>Preventive Maintenance Schedule or Planned Maintenance as per Manufacturer’s recommendation records. </strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[2] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[2] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[2] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[2]; ?>" disabled>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>1.4</strong></td>
                            <td><strong>Crane Maintenance and Repair Records. </strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[3] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[3] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[3] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[3]; ?>" disabled>
                            </td>
                        </tr>


                        <tr>
                            <td><strong>1.5</strong></td>
                            <td><strong>Slew/Swing Gear and Pinion Clearances Report. </strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[4] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[4] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[4] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[4]; ?>" disabled>
                            </td>
                        </tr>


                        <tr>
                            <td><strong>1.6</strong></td>
                            <td><strong>Operator’s Daily Pre-Operational Inspection Checklists. </strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[5] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[5] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[5] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[5]; ?>" disabled>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>1.7</strong></td>
                            <td><strong>Previous Inspection Reports are available & deficiencies were already rectified. </strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[6] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[6] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[6] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <!--<input type="text" name="remarks[0]" value="<?php echo $chek_remark[6]; ?>" disabled>-->
                                <textarea name="remarks[0]" disabled rows="3" style="width: 100%;"><?php echo $chek_remark[6]; ?></textarea>

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
                            <td><strong>Crane Class Certificates. </strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[7] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[7] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[7] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[7]; ?>" disabled>
                            </td>
                        </tr>
                        </tbody>
<thead>

                        <tr>
                            <th style="text-align: center;">2.2</th>
                            <th style="text-align: center;">ROPE Manufacturer’s Test Certificates</th>
                            <th style="text-align: center;">PASS</th>
                            <th style="text-align: center;">FAIL</th>
                            <th style="text-align: center;">NA</th>
                            <th>REMARKS / RECOMMENDATIONS </th>
                        </tr>
                        </thead>
                        <tbody>

                        <tr>
                            <td><strong>2.2.1</strong></td>
                            <td><strong>Main Load Hoist Rope </strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[8] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[8] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[8] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                 <!--<input type="text" name="remarks[0]" value="<?php echo $chek_remark[8]; ?>" disabled style="height: 120px;">-->
                                <textarea name="remarks[0]" disabled style="height: 60px; width: 100%;"><?php echo $chek_remark[8]; ?></textarea>
                            </td>
                        </tr>

                        <tr>
                            <td><strong>2.2.2</strong></td>
                            <td><strong>No. 1 Auxiliary Load Hoist Rope </strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[9] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[9] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[9] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <!--<input type="text" name="remarks[0]" value="<?php echo $chek_remark[9]; ?>" disabled style="height: 120px;">-->
                                <textarea name="remarks[0]" disabled style="height: 60px; width: 100%;"><?php echo $chek_remark[9]; ?></textarea>

                            </td>
                        </tr>

                        <tr>
                            <td><strong>2.2.3</strong></td>
                            <td><strong>No. 2 Auxiliary Hoist Rope </strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[10] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[10] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[10] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[10]; ?>" disabled>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>2.2.4</strong></td>
                            <td><strong>Boom Hoist Rope </strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[11] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[11] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[11] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <!--<input type="text" name="remarks[0]" value="<?php echo $chek_remark[11]; ?>" disabled>-->
                                <textarea name="remarks[0]" disabled rows="3" style="width: 100%;"><?php echo $chek_remark[11]; ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>2.2.5</strong></td>
                            <td><strong>Pendant Rope</strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[12] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[12] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[12] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <!--<input type="text" name="remarks[0]" value="<?php echo $chek_remark[12]; ?>" disabled>-->
                                <textarea name="remarks[0]" disabled rows="3" style="width: 100%;"><?php echo $chek_remark[12]; ?></textarea>
                            </td>
                        </tr>


                        <tr>
                            <td><strong>2.3</strong></td>
                            <td><strong> Crane Load Test Certificates. </strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[13] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[13] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[13] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[13]; ?>" disabled>
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
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[14] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[14] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[14] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[14]; ?>" disabled>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>2.4.2</strong></td>
                            <td><strong>Main Hook Blocks </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[15] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[15] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[15] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[15]; ?>" disabled>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>2.4.3</strong></td>
                            <td><strong>Auxiliary Hook Blocks </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[16] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[16] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[16] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[16]; ?>" disabled>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>2.5</strong></td>
                            <td><strong>Operator Certificate for the type/model of crane.</strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[17] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[17] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[17] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                             <!--<input type="text" name="remarks[0]" value="<?php echo $chek_remark[17]; ?>" disabled style="height: 120px;">-->
                                <textarea name="remarks[0]" disabled style="height: 60px; width: 100%;"><?php echo $chek_remark[17]; ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>2.6</strong></td>
                            <td><strong>LMI/RCL/SLI/AML Calibration Certificates. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[18] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[18] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[18] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                  <!--<input type="text" name="remarks[0]" value="<?php echo $chek_remark[18]; ?>" disabled style="height: 120px;">-->
                                <textarea name="remarks[0]" disabled style="height: 60px; width: 100%;"><?php echo $chek_remark[18]; ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>2.7</strong></td>
                            <td><strong>Boom Rocking Test Certificates.</strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[19] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[19] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[19] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <!--<input type="text" name="remarks[0]" value="<?php echo $chek_remark[19]; ?>" disabled>-->
                                <!--<input type="text" name="remarks[0]" value="<?php echo $chek_remark[19]; ?>" disabled>-->
                                <textarea name="remarks[0]" disabled style="height: 60px; width: 100%;"><?php echo $chek_remark[19]; ?></textarea>

                            </td>
                        </tr>


                        <tr>
                            <th style="text-align: center;">3</th>
                            <th style="text-align: center;">MARKING AND SAFETY DECALS</th>
                            <th style="text-align: center;">PASS</th>
                            <th style="text-align: center;">FAIL</th>
                            <th style="text-align: center;">NA</th>
                            <th> REMARKS / RECOMMENDATIONS</th>
                        </tr>

                        <tr>
                            <td><strong>3.1</strong></td>
                            <td><strong>Crane asset number/identification is stenciled prominently.</strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[20] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[20] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[20] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[20]; ?>" disabled>
                            </td>
                        </tr>



                        <tr>
                            <td><strong>3.2</strong></td>
                            <td><strong> Crane’s SWL is prominently stenciled/marked.</strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[21] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[21] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[21] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[21]; ?>" disabled>
                            </td>
                        </tr>



                        <tr>
                            <th style="text-align: center;">3.3</th>
                            <th style="text-align: center;">Hook Blocks’ SWL and weights are stenciled on the items. </th>
                            <th style="text-align: center;">PASS</th>
                            <th style="text-align: center;">FAIL</th>
                            <th style="text-align: center;">NA</th>
                            <th> REMARKS / RECOMMENDATIONS</th>
                        </tr>

                        <tr>
                            <td><strong>3.3.1</strong></td>
                            <td><strong>Main Hook Block </strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[22] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[22] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[22] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[22]; ?>" disabled>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>3.3.2</strong></td>
                            <td><strong>Auxiliary Hook Block</strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[23] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[23] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[23] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[23]; ?>" disabled>
                            </td>
                        </tr>


                        <tr>
                            <td><strong>3.4</strong></td>
                            <td><strong> WARNING SIGN: Operator Should Not Rely Solely on Any Automatic Device as a Substitute for Safe Operating Practice, is posted inside the cabin’s wall or control panel.</strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[24] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[24] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[24] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[24]; ?>" disabled>
                            </td>
                        </tr>

                        <tr>
                            <td><strong>3.5</strong></td>
                            <td><strong>CRANE’S DATA PLATE (Crane Manufacturer Name, Model, Serial Number, and Year of Manufacture) is available and posted or stamped on the crane structure.</td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[25] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[25] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[25] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[25]; ?>" disabled>
                            </td>
                        </tr>

                        <tr>
                            <td><strong>3.6</strong></td>
                            <td><strong>Warning Decal stating: “Warning! Switch Limit must be tested before the start of Lifting Operation and NO Personnel is allowed to By-pass the Crane Limit at any time.
                                </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[26] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[26] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[26] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[26]; ?>" disabled>
                            </td>
                        </tr>
                        <tr>
                        <td><strong>3.7</strong></td>
                        <td><strong> Hand signal decal is posted on the pedestal or mast and cabin. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[27] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[27] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[27] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[27]; ?>" disabled>
                            </td>
                        </tr>
                        <tr>
                        <td><strong>3.8</strong></td>
                        <td><strong>Load rating charts and range diagrams are posted on the wall inside the cabin. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[28] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[28] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[28] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[28]; ?>" disabled>
                            </td>
                        </tr>


                        <tr>
                        <td><strong>3.9</strong></td>
                        <td><strong>Labels of the directional control levers are marked legibly. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[29] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[29] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[29] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[29]; ?>" disabled>
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
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[30] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[30] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[30] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[30]; ?>" disabled>
                            </td>
                        </tr>
                        <tr>
                        <td><strong>4.1.2 </strong></td>
                        <td><strong> Lattice Boom: Chords, Lacings, Splices, and bridle have no bent, corroded, deformed, damaged, and dents. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[31] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[31] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[31] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[31]; ?>" disabled>
                            </td>
                        </tr>
                        <tr>
                        <td><strong>4.1.3 </strong></td>
                        <td><strong>Knuckle Boom </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[32] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[32] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[32] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[32]; ?>" disabled>
                            </td>
                        </tr>
						
						
						<tr>
                        <td><strong>4.2 </strong></td>
                        <td><strong>The boom assembly has no signs of corrosion, distortion, deformation, cracks, & wear. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[33] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[33] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[33] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[33]; ?>" disabled>
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
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[34] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[34] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[34] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[34]; ?>" disabled>
                            </td>
                        </tr>
                        <tr>
                        <td><strong>4.3.2</strong></td>
                        <td><strong> Boom Telescopic </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[35] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[35] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[35] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[35]; ?>" disabled>
                            </td>
                        </tr>
                        <tr>
                        <td><strong>4.3.3 </strong></td>
                        <td><strong>Boom Articulating </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[36] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[36] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[36] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[36]; ?>" disabled>
                            </td>
                        </tr>

                        <tr>
                        <td><strong>4.4 </strong></td>
                        <td><strong>The HOLDING VALVES of boom lifting, telescoping, and articulating/ knuckling are in good working condition and have no signs of boom dropping. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[37] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[37] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[37] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[37]; ?>" disabled>
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
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[38] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[38] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[38] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[38]; ?>" disabled>
                            </td>
                        </tr>

                        <tr>
                        <td><strong>4.5.2 </strong></td>
                        <td><strong>Main Load Hoist </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[39] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[39] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[39] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[39]; ?>" disabled>
                            </td>
                        </tr>

                        <tr>
                        <td><strong>4.5.3 </strong></td>
                        <td><strong>Auxiliary Load Hoist </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[40] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[40] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[40] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[40]; ?>" disabled>
                            </td>
                        </tr>

                        <tr>
                        <td><strong>4.6 </strong></td>
                        <td><strong>No leakages are visible on the hydraulic hoses, fittings, valves, & manifolds. </strong></td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[41] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[41] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[41] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[41]; ?>" disabled>
                            </td>
                        </tr>
                        
                        <tr>
                        <td><strong>4.7 </strong></td>
                        <td><strong>Nothing was deformed on the tubing, fittings, & other related components. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[42] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[42] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[42] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[42]; ?>" disabled>
                            </td>
                        </tr>

                        <tr>
                        <td><strong>4.8 </strong></td>
                        <td><strong>Boom angle indicator is provided and working properly.</strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[43] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[43] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[43] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[43]; ?>" disabled>
                            </td>
                        </tr>

                        <tr>
                        <td><strong>4.9 </strong></td>
                        <td><strong>BOOM BACK STOPS: Fixed bumper, Shock absorbing bumper, or Hydraulic bumper, is provided and in good condition. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[44] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[44] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[44] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[44]; ?>" disabled>
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
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[45] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[45] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[45] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[45]; ?>" disabled>
                            </td>
                        </tr>


                        <tr>
                        <td><strong>4.10.2 </strong></td>
                        <td><strong> Main Hoist Drum </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[46] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[46] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[46] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[47]; ?>" disabled>
                            </td>
                        </tr>

                        <tr>
                        <td><strong>4.10.3 </strong></td>
                        <td><strong>Auxiliary Hoist Drum </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[47] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[47] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[47] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[47]; ?>" disabled>
                            </td>
                        </tr>

                        <tr>
                        <td><strong>4.11 </strong></td>
                        <td><strong>Automatic Boom Back Stops: Maximum boom angle </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[48] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[48] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[48] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[48]; ?>" disabled>
                            </td>
                        </tr>

                        <tr>
                        <td><strong>4.12 </strong></td>
                        <td><strong>Automatic stop limit for minimum boom angle </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[49] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[49] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[49] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[49]; ?>" disabled>
                            </td>
                        </tr>

                        <tr>
                        <td><strong>4.13 </strong></td>
                        <td><strong>Minimum Boom Length & Maximum Boom Length </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[50] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[50] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[50] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[50]; ?>" disabled>
                            </td>
                        </tr>

                        <tr>
                        <td><strong>4.14 </strong></td>
                        <td><strong>Boom cradle is provided and can secure the boom at rest. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[51] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[51] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[51] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[51]; ?>" disabled>
                            </td>
                        </tr>
                        <tr>
                        <td><strong>4.15</strong></td>
                        <td><strong>Sheaves are free from deformation, dent, bent, or damage and their bearings sufficiently lubricated. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[52] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[52] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[52] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[52]; ?>" disabled>
                            </td>
                        </tr>

                        <tr>
                        <td><strong>4.16</strong></td>
                        <td><strong>Aviation or pilot light is provided and is working. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[53] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[53] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[53] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[53]; ?>" disabled>
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
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[54] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[54] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[54] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[54]; ?>" disabled>
                            </td>
                        </tr>


                        <tr>
                        <td><strong>5.2 </strong></td>
                        <td><strong>Base Structure/Pedestal/mast’s welds and joints are free from corrosion and cracks.</strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[55] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[55] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[55] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[55]; ?>" disabled>
                            </td>
                        </tr>


                        <tr>
                        <td><strong>5.3 </strong></td>
                        <td><strong>Pins, bearings, shafts, gears, and locking devices are free from distortion, cracks and corrosion. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[56] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[56] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[56] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[56]; ?>" disabled>
                            </td>
                        </tr>

                        <tr>
                        <td><strong>5.4</strong></td>
                        <td><strong>Swing brakes operate and can restrict further movement of the rotating structure. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[57] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[57] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[57] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[57]; ?>" disabled>
                            </td>
                        </tr>

                        <tr>
                        <td><strong>5.5</strong></td>
                        <td><strong>Swing positive locking device is provided and can lock the structure from further movement.</strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[58] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[58] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[58] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[58]; ?>" disabled>
                            </td>
                        </tr>

                        <tr>
                        <td><strong>5.6 </strong></td>
                        <td><strong>Swing brake is adjustable to compensate its wear. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[59] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[59] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[59] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[59]; ?>" disabled>
                            </td>
                        </tr>


                        <tr>
                        <td><strong>5.7 </strong></td>
                        <td><strong>All swing moving parts are sufficiently lubricated. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[60] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[60] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[60] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[60]; ?>" disabled>
                            </td>
                        </tr>


                        <tr>
                        <td><strong>5.8 </strong></td>
                        <td><strong>Platforms and walkways are skid resistant. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[61] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[61] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[61] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[61]; ?>" disabled>
                            </td>
                        </tr>

                        <tr>
                        <td><strong>5.9 </strong></td>
                        <td><strong>Access ladders, & guard rails are free from rust, damage, & corrosion </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[62] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[62] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[62] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[62]; ?>" disabled>
                            </td>
                        </tr>


                        <tr>
                            <th style="text-align: center;">6</th>
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
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[63] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[63] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[63] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[63]; ?>" disabled>
                            </td>
                        </tr>

                        <tr>
                        <td><strong>6.2</strong></td>
                        <td><strong> Electrical wirings and related equipment are free of damages. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[64] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[64] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[64] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[64]; ?>" disabled>
                            </td>
                        </tr>

                        <tr>
                        <td><strong>6.3 </strong></td>
                        <td><strong>Manholes and hatches’ covers are provided to protect personnel from accidental fall. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[65] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[65] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[65] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[65]; ?>" disabled>
                            </td>
                        </tr>

                        <tr>
                        <td><strong>6.4 </strong></td>
                        <td><strong>Electrical and hydraulic motors & pumps are in good working condition. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[66] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[66] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[66] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[66]; ?>" disabled>
                            </td>
                        </tr>

                        <tr>
                        <td><strong>6.5 </strong></td>
                        <td><strong>Hydraulic hoses, fittings, tubes, and manifold joints have no evidence of leakages and not damage. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[67] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[67] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[67] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[67]; ?>" disabled>
                            </td>
                        </tr>

                        <tr>
                        <td><strong>6.6</strong></td>
                        <td><strong>Hydraulic/pneumatic cylinders, pumps and motors have no leaks and working properly. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[68] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[68] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[68] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[68]; ?>" disabled>
                            </td>
                        </tr>

                        <tr>
                        <td><strong>6.7</strong></td>
                        <td><strong>Engine power driven motors and pumps are working properly and have no signs of leaks. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[69] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[69] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[69] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[69]; ?>" disabled>
                            </td>
                        </tr>

                        <tr>
                        <td><strong>6.8</strong></td>
                        <td><strong>Machinery compartment is free from spills and obstruction. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[70] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[70] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[70] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[70]; ?>" disabled>
                            </td>
                        </tr>

                        <tr>
                        <td><strong>6.9</strong></td>
                        <td><strong>Fire extinguisher is provided in the compartment with minimum rating of 10BC. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[71] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[71] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[71] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[71]; ?>" disabled>
                            </td>
                        </tr>

                        <tr>
                        <td><strong>6.10</strong></td>
                        <td><strong>An emergency lowering system, if provided, shall be checked for proper function. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[72] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[72] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[72] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[72]; ?>" disabled>
                            </td>
                        </tr>
						
						
						 <tr>
                            <th style="text-align: center;">7</th>
                            <th style="text-align: center;">CABIN</th>
                            <th style="text-align: center;">PASS</th>
                            <th style="text-align: center;">FAIL</th>
                            <th style="text-align: center;">NA</th>
                            <th>REMARKS / RECOMMENDATIONS </th>
                        </tr>

                        <tr>
                            <td><strong>7.1</strong></td>
                            <td><strong>Portable fire extinguisher is provided. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[73] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[73] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[73] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[73]; ?>" disabled>
                            </td>
                        </tr>


                        <tr>
                            <td><strong>7.2</strong></td>
                            <td><strong>Toolbox with basic tools are available. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[74] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[74] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[74] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[74]; ?>" disabled>
                            </td>
                        </tr>

                        <tr>
                            <td><strong>7.3</strong></td>
                            <td><strong>An Emergency stop button shall be available and working effectively. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[75] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[75] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[75] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[75]; ?>" disabled>
                            </td>
                        </tr>

                        <tr>
                            <td><strong>7.4</strong></td>
                            <td><strong>Wipers are installed and working properly. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[76] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[76] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[76] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[76]; ?>" disabled>
                            </td>
                        </tr>


                        <tr>
                            <td><strong>7.5</strong></td>
                            <td><strong>An audible warning device is provided for any errors in the system. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[77] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[77] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[77] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[77]; ?>" disabled>
                            </td>
                        </tr>

                        <tr>
                            <td><strong>7.6</strong></td>
                            <td><strong>Cabin has a good housekeeping. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[78] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[78] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[78] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[78]; ?>" disabled>
                            </td>
                        </tr>

                        <tr>
                            <td><strong>7.7</strong></td>
                            <td><strong>Cabin door is open outward or sliding backward. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[79] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[79] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[79] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[79]; ?>" disabled>
                            </td>
                        </tr>

                        <tr>
                            <td><strong>7.8</strong></td>
                            <td><strong>Windshield is of Safety glazing glass. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[80] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[80] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[80] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[80]; ?>" disabled>
                            </td>
                        </tr>

                        <tr>
                            <td><strong>7.9</strong></td>
                            <td><strong>Operator seat condition (torn seat or back cushions). </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[81] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[81] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[81] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[81]; ?>" disabled>
                            </td>
                        </tr>

                        <tr>
                            <td><strong>7.10</strong></td>
                            <td><strong>Cabin A/C is provided and is working. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[82] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[82] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[82] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[82]; ?>" disabled>
                            </td>
                        </tr>
						
						<tr>
                            <td><strong>7.11</strong></td>
                            <td><strong>The Installed anemometer is working. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[83] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[83] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[83] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[83]; ?>" disabled>
                            </td>
                        </tr>
						
						<tr>
                            <td><strong>7.12</strong></td>
                            <td><strong>The installed view camera is working </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[84] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[84] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[84] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[84]; ?>" disabled>
                            </td>
                        </tr>
						
						<tr>
                            <td><strong>7.13</strong></td>
                            <td><strong>All applicable indicators like but not limited to; oil or water temperature gauge, hydraulic oil pressure gauge, etc. are working correctly. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[85] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[85] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[85] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[85]; ?>" disabled>
                            </td>
                        </tr>
						
						
						
						<tr>
                            <th style="text-align: center;">8</th>
                            <th style="text-align: center;">HOIST ROPES</th>
                            <th rowspan="2" style="text-align: center;">PASS</th>
                            <th rowspan="2" style="text-align: center;">FAIL</th>
                            <th rowspan="2" style="text-align: center;">NA</th>
                            <th rowspan="2">REMARKS / RECOMMENDATIONS </th>
                        </tr>
						
						<tr>
                            <th style="text-align: center;">8.1</th>
                            <th style="text-align: center;">Remaining rope on drum is at least two full wraps when the boom hoist or load hoist is at its lowest angle or maximum pay-out respectively.</th>
                            
                        </tr>

                        <tr>
                            <td><strong>8.1.1</strong></td>
                            <td><strong>Boom Hoist </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[86] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[86] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[86] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[86]; ?>" disabled>
                            </td>
                        </tr>

                        <tr>
                            <td><strong>8.1.2</strong></td>
                            <td><strong>Main Load Hoist </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[87] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[87] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[87] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[87]; ?>" disabled>
                            </td>
                        </tr>

                        <tr>
                            <td><strong>8.1.3</strong></td>
                            <td><strong>Auxiliary Load Hoist </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[88] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[88] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[88] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[88]; ?>" disabled>
                            </td>
                        </tr>

<tr>
                            <th style="text-align: center;">8.2</th>
                            <th style="text-align: center;">Wire rope shall be free from bird caging, corrosion, crushing, kinking, un-stranding, core protrusion, main strand displacement, evidence of heat damage, or any other damage.
							</th>
                            <th style="text-align: center;">PASS</th>
                            <th style="text-align: center;">FAIL</th>
                            <th style="text-align: center;">NA</th>
                            <th>REMARKS / RECOMMENDATIONS </th>
                        </tr>

                        <tr>
                            <td><strong>8.2.1</strong></td>
                            <td><strong>Boom Hoist </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[89] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[89] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[89] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[89]; ?>" disabled>
                            </td>
                        </tr>


                        <tr>
                            <td><strong>8.2.2</strong></td>
                            <td><strong>Main Load Hoist </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[90] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[90] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[90] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[90]; ?>" disabled>
                            </td>
                        </tr>

                        <tr>
                            <td><strong>8.2.3</strong></td>
                            <td><strong>Auxiliary Load Hoist </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[91] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[91] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[91] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[91]; ?>" disabled>
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
                            <td><strong>8.3.1</strong></td>
                            <td><strong>Boom Hoist </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[92] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[92] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[92] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[92]; ?>" disabled>
                            </td>
                        </tr>


                        <tr>
                            <td><strong>8.3.2</strong></td>
                            <td><strong>Main Load Hoist </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[93] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[93] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[93] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[93]; ?>" disabled>
                            </td>
                        </tr>

                        <tr>
                            <td><strong>8.3.3</strong></td>
                            <td><strong>Auxiliary Load Hoist </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[94] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[94] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[94] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[94]; ?>" disabled>
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
                            <td><strong>8.4.1</strong></td>
                            <td><strong>Boom Hoist </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[95] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[95] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[95] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[95]; ?>" disabled>
                            </td>
                        </tr>


                        <tr>
                            <td><strong>8.4.2</strong></td>
                            <td><strong>Main Load Hoist </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[96] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[96] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[96] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[96]; ?>" disabled>
                            </td>
                        </tr>

                        <tr>
                            <td><strong>8.4.3</strong></td>
                            <td><strong>Auxiliary Load Hoist </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[97] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[97] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[97] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[97]; ?>" disabled>
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
                            <td><strong>8.5.1</strong></td>
                            <td><strong>Boom Hoist </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[98] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[98] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[98] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[98]; ?>" disabled>
                            </td>
                        </tr>


                        <tr>
                            <td><strong>8.5.2</strong></td>
                            <td><strong>Main Load Hoist </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[99] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[99] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[99] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[99]; ?>" disabled>
                            </td>
                        </tr>

                        <tr>
                            <td><strong>8.5.3</strong></td>
                            <td><strong>Auxiliary Load Hoist </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[100] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[100] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[100] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[100]; ?>" disabled>
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
                            <td><strong>8.6.1</strong></td>
                            <td><strong>Boom Hoist </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[101] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[101] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[101] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[101]; ?>" disabled>
                            </td>
                        </tr>


                        <tr>
                            <td><strong>8.6.2</strong></td>
                            <td><strong>Main Load Hoist </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[102] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[102] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[102] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[102]; ?>" disabled>
                            </td>
                        </tr>

                        <tr>
                            <td><strong>8.6.3</strong></td>
                            <td><strong>Auxiliary Load Hoist </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[103] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[103] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[103] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[103]; ?>" disabled>
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
                            <td><strong>8.7.1</strong></td>
                            <td><strong>Boom Hoist </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[104] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[104] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[104] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[104]; ?>" disabled>
                            </td>
                        </tr>


                        <tr>
                            <td><strong>8.7.2</strong></td>
                            <td><strong>Main Load Hoist </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[105] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[105] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[105] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[105]; ?>" disabled>
                            </td>
                        </tr>

                        <tr>
                            <td><strong>8.7.3</strong></td>
                            <td><strong>Auxiliary Load Hoist </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[106] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[106] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[106] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[106]; ?>" disabled>
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
                            <td><strong>8.8.1</strong></td>
                            <td><strong>Boom Hoist </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[107] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[107] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[107] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[107]; ?>" disabled>
                            </td>
                        </tr>


                        <tr>
                            <td><strong>8.8.2</strong></td>
                            <td><strong>Main Load Hoist </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[108] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[108] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[108] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[108]; ?>" disabled>
                            </td>
                        </tr>

                        <tr>
                            <td><strong>8.8.3</strong></td>
                            <td><strong>Auxiliary Load Hoist </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[109] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[109] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[109] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[109]; ?>" disabled>
                            </td>
                        </tr>							

                        <tr>
                            <td><strong>8.9</strong></td>
                            <td><strong>Anti-two-blocking system (A2B) is working correctly. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[110] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[110] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[110] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[110]; ?>" disabled>
                            </td>
                        </tr>
						
						
						<tr>
                            <th style="text-align: center;">9</th>
                            <th style="text-align: center;">HOOKS</th>
                            <th style="text-align: center;">PASS</th>
                            <th style="text-align: center;">FAIL</th>
                            <th style="text-align: center;">NA</th>
                            <th>REMARKS / RECOMMENDATIONS </th>
                        </tr>

                        <tr>
                            <td><strong>9.1</strong></td>
                            <td><strong>Labeling and manufacturer data are available & legible. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[111] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[111] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[111] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[111]; ?>" disabled>
                            </td>
                        </tr>

                        <tr>
                            <td><strong>9.2</strong></td>
                            <td><strong>Hook is not bent or twisted. Maximum bending or twisting is not to exceed 10 degrees from plane of unbent hook. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[112] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[112] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[112] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[112]; ?>" disabled>
                            </td>
                        </tr>

                        <tr>
                            <td><strong>9.3</strong></td>
                            <td><strong>Hook has no crack, nick, gouge, or excessive wear. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[113] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[113] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[113] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[113]; ?>" disabled>
                            </td>
                        </tr>


                        <tr>
                            <td><strong>9.4</strong></td>
                            <td><strong>Hook is not distorted in the throat opening. Maximum allowable throat opening is 15% compared to new hook or as per manufacturer recommendation. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[114] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[114] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[114] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[114]; ?>" disabled>
                            </td>
                        </tr>


                        <tr>
                            <td><strong>9.5</strong></td>
                            <td><strong>Hook latch is operative. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[115] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[115] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[115] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[115]; ?>" disabled>
                            </td>
                        </tr>


                        <tr>
                            <td><strong>9.6</strong></td>
                            <td><strong>Hook is rotating freely. </strong></td>

                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS"
                                    <?php echo $selected_results[116] == "PASS" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL"
                                    <?php echo $selected_results[116] == "FAIL" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td class="checkbox-cell">
                                <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA"
                                    <?php echo $selected_results[116] == "NA" ? 'checked' : ''; ?> disabled class="custom-checkbox">
                            </td>
                            <td>
                                <input type="text" name="remarks[0]" value="<?php echo $chek_remark[116]; ?>" disabled>
                            </td>
                        </tr>

                       

                        



                </table>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th colspan="3" style="text-align: center;">REMARKS / RECOMMENDATIONS: </th>
                        </tr>
                        <tr>
                            <td style="height: 120px;" colspan="3">
                                <!--<?php echo htmlspecialchars($row['recommendations']); ?>-->
                                <?php echo htmlspecialchars($recommendations); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
			
			
			
			
			<!-- Example of how to display revision data in your HTML -->
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
            <!--<tr>-->
            <!--    <td>-->
            <!--        <?php echo htmlspecialchars($revision_data['revision_1'] ?? ''); ?>-->
            <!--    </td>-->
            <!--    <td>-->
            <!--        <?php echo htmlspecialchars($revision_data['type_revision_1'] ?? ''); ?>-->
            <!--    </td>-->
            <!--    <td>-->
            <!--        <?php echo htmlspecialchars($revision_data['revision_1_date'] ?? ''); ?>-->
            <!--    </td>-->
            <!--</tr>-->
            <!--<tr>-->
            <!--    <td>-->
            <!--        <?php echo htmlspecialchars($revision_data['revision_2'] ?? ''); ?>-->
            <!--    </td>-->
            <!--    <td>-->
            <!--        <?php echo htmlspecialchars($revision_data['type_revision_2'] ?? ''); ?>-->
            <!--    </td>-->
            <!--    <td>-->
            <!--        <?php echo htmlspecialchars($revision_data['revision_2_date'] ?? ''); ?>-->
            <!--    </td>-->
            <!--</tr>-->
        </tbody>
    </table>
</div>


            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 25%;">INSPECTOR’S NAME:</th>
                        <td style="width: 25%;">
                            <strong>
                                <?php echo htmlspecialchars($row['inspected_by']); ?>
                            </strong>
                        </td>
                        <th style="width: 25%;">CLIENT’S REP. NAME:</th>
                        <td style="width: 25%;">

                            <?php echo htmlspecialchars($client_name); ?>

                        </td>
                    </tr>
                    <tr>
                        <th>SIGNATURE & DATE:</th>
                         <td>
<?php 
$inspector_name = $row['inspected_by'];
$sql = "SELECT signature_photo FROM inspectors WHERE inspector_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $inspector_name);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $inspector = $result->fetch_assoc();

    // Use the actual domain and interpolate the variable correctly
    $image_url = $url2 . '/inspector/uploads/' . preg_replace('/\s+/', '_', strtolower($inspector_name)) . '/images/signature_image.jpg';

    // Just display the image; add onerror fallback in the <img> tag
    echo "<img src=\"$image_url\" alt=\"Inspector Signature\" style=\"max-width: 100px; max-height: 50px;\">";
} else {
    echo "Inspector not found.";
}
?>
</td>
                        <th>SIGNATURE & DATE:</th>
                        <td> <img src="../../../uploads/<?php echo htmlspecialchars($project_no); ?>.png?t=<?php echo time(); ?>" height="50px" width="100px" alt="Client Signature">
                        </td>
                    </tr>
                </table>
            </div>


            <div class="col-12 d-flex justify-content-center mt-4">
                <a href="../../index.php" class="mr-4 btn btn-primary no-print">Back</a>
                <button type="submit" onclick="window.print()" class="btn btn-primary no-print">Print</button>
            </div>
        </form>
    </div>

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

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>