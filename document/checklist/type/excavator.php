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
    <title>FRM.0601-2.1 (rev.01) - Excavator</title>
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
            <img src="../../logo.png"  alt="CIMS Logo" width="100"> <!-- Replace 'logo.png' with actual image path -->
        </td>
        <td colspan="3" class="no-border">
            <span class="main-title">CRANE INSPECTION & MAINTENANCE SERVICES</span><br>
            A DIVISION OF AL-KHOBAR GATE INTERNATIONAL TRADING EST.
        </td>
    </tr>
    <tr>
        <td colspan="3" class="">
            <strong>INSPECTION CHECKLIST FOR EXCAVATOR</strong>
        </td>
    </tr>
    <tr>
        <td>FRM.0601-2.1</td>
        <td>Revision 01</td>
        <td><b>Issue Date: </b>24/Dec/2020</td>
    </tr>
    <tr>
        <td class="left-align"><b>Prepared By</b><br>Operations Manager</td>
        <td  class="left-align"><b>Reviewed & Approved By</b><br>Managing Director</td>
   
   <td><img src="../../code.png" width="80px" height="80px" alt="" /></td>
</tr>
</table>
            
			
			</div>

        <h4>HYDRAULIC EXCAVATOR</h4>
        <h4>(EARTH MOVING MACHINERY) </h4>
        <h4>DS/EN 474-1:2006+A6:2019</h4>
		 <!--<button class="btn btn-primary no-print" onclick="preparePrint()">Print View</button>-->

         <form method="post" action="./update_checklist.php" id="checklistForm">
<div class="table-responsive">
         <table class="table table-bordered">
                
                <tr>
                    <th style="width: 25%;">CLIENT’S NAME</th>
                    <td style="width: 25%;"><input type="text" name="header_client_name" value="<?php echo htmlspecialchars($row['client_name'] ?? ''); ?>" class="form-control" style="font-weight: bold; border: none; background: transparent;"></td>
                    <th style="width: 25%;">INSPECTION DATE</th>
                    <td style="width: 25%;"><input type="date" name="inspection_date" value="<?php echo htmlspecialchars($row['inspection_date'] ?? ''); ?>" class="form-control" style="font-weight: bold; border: none; background: transparent;"></td>
                </tr>
                <tr>
                    <th>LOCATION</th>
                    <td><input type="text" name="location" value="<?php echo htmlspecialchars($row['location'] ?? ''); ?>" class="form-control" style="font-weight: bold; border: none; background: transparent;"></td>
                    <th>REPORT NO</th>
                    <td><input type="text" name="report_no" value="<?php echo htmlspecialchars($row['report_no'] ?? ''); ?>" class="form-control" style="font-weight: bold; border: none; background: transparent;"></td>
                </tr>
                <tr>
                    <th>EQUIPMENT NO</th>
                    <td><input type="text" name="equipment_no" value="<?php echo htmlspecialchars($row['equipment_no'] ?? ''); ?>" class="form-control" style="font-weight: bold; border: none; background: transparent;"></td>
                    <th>STICKER NO.</th>
                    <td><input type="text" name="sticker_no" value="<?php echo htmlspecialchars($row['sticker_no'] ?? ''); ?>" class="form-control" style="font-weight: bold; border: none; background: transparent;"></td>
                </tr>
                <tr>
                    <th>EQUIPMENT TYPE</th>
                    <td><input type="text" name="equipmenttype" value="<?php echo htmlspecialchars($row['equipmenttype'] ?? ''); ?>" class="form-control" style="font-weight: bold; border: none; background: transparent;"></td>
                    <th>EQUIP. SERIAL NO.</th>
                    <td><input type="text" name="crane_serial_no" value="<?php echo htmlspecialchars($row['crane_serial_no'] ?? ''); ?>" class="form-control" style="font-weight: bold; border: none; background: transparent;"></td>
                </tr>
                <tr>
                    <th>MANUFACTURER:</th>
                    <td><input type="text" name="manufacturer" value="<?php echo htmlspecialchars($row['manufacturer'] ?? ''); ?>" class="form-control" style="font-weight: bold; border: none; background: transparent;"></td>
                    <th>YEAR MODEL:</th>
                    <td><input type="text" name="year_model" value="<?php echo htmlspecialchars($row['year_model'] ?? ''); ?>" class="form-control" style="font-weight: bold; border: none; background: transparent;"></td>
                </tr>
                <tr>
                    <th>EQUIP. MODEL NO.:</th>
                    <td><input type="text" name="model_no" value="<?php echo htmlspecialchars($row['model_no'] ?? ''); ?>" class="form-control" style="font-weight: bold; border: none; background: transparent;"></td>
                    <th>CAPACITY:</th>
                    <td><input type="text" name="capacity_swl" value="<?php echo htmlspecialchars($row['capacity_swl'] ?? ''); ?>" class="form-control" style="font-weight: bold; border: none; background: transparent;"></td>
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
                    <th style="text-align: center;">REMARKS</th>
                </tr>
				
				<tr>
                    <th style="text-align: center;">1</th>
                    <th style="text-align: center;">RATINGS & MARKINGS</th>
                    
                    <th style="text-align: center;">PASS</th>
                    <th style="text-align: center;">FAIL</th>
                    <th style="text-align: center;">N/A</th>
                    <th> </th>
                </tr>
				</thead>
 
                <tbody>

 <tr>
                <td><strong>1.1</strong></td>
                <td><strong>Documentation is available.  </strong></td>
                <td class="checkbox-cell">
    <input type="checkbox" name="result[1][]" id="checkbox1_1" value="PASS" class="large-checkbox" <?php echo isChecked(1, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[1][]" id="checkbox1_2" value="FAIL" class="large-checkbox" <?php echo isChecked(1, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[1][]" id="checkbox1_3" value="NA" class="large-checkbox" <?php echo isChecked(1, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[1]" value="<?php echo getRemark(1, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
            <tr>
                <td><strong>1.2</strong></td>
                <td><strong> Equipment asset ID Number is prominently marked.</strong></td>
                <td class="checkbox-cell">
    <input type="checkbox" name="result[2][]" id="checkbox2_1" value="PASS" class="large-checkbox" <?php echo isChecked(2, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[2][]" id="checkbox2_2" value="FAIL" class="large-checkbox" <?php echo isChecked(2, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[2][]" id="checkbox2_3" value="NA" class="large-checkbox" <?php echo isChecked(2, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[2]" value="<?php echo getRemark(2, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
            <tr>
                <td><strong>1.3</strong></td>
                <td><strong> Nameplate, caution, and instruction markings are available on the equipment.</strong></td>
                <td class="checkbox-cell">
    <input type="checkbox" name="result[3][]" id="checkbox3_1" value="PASS" class="large-checkbox" <?php echo isChecked(3, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[3][]" id="checkbox3_2" value="FAIL" class="large-checkbox" <?php echo isChecked(3, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[3][]" id="checkbox3_3" value="NA" class="large-checkbox" <?php echo isChecked(3, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[3]" value="<?php echo getRemark(3, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
            <tr>
                <td><strong>1.4</strong></td>
                <td><strong>All controls are marked for identification of function. </strong></td>
                <td class="checkbox-cell">
    <input type="checkbox" name="result[4][]" id="checkbox4_1" value="PASS" class="large-checkbox" <?php echo isChecked(4, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[4][]" id="checkbox4_2" value="FAIL" class="large-checkbox" <?php echo isChecked(4, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[4][]" id="checkbox4_3" value="NA" class="large-checkbox" <?php echo isChecked(4, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[4]" value="<?php echo getRemark(4, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
            <tr>
                <td><strong>1.5</strong></td>
                <td><strong>All safety & warning decals are posted. </strong></td>
                <td class="checkbox-cell">
    <input type="checkbox" name="result[5][]" id="checkbox5_1" value="PASS" class="large-checkbox" <?php echo isChecked(5, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[5][]" id="checkbox5_2" value="FAIL" class="large-checkbox" <?php echo isChecked(5, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[5][]" id="checkbox5_3" value="NA" class="large-checkbox" <?php echo isChecked(5, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[5]" value="<?php echo getRemark(5, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
            <tr>
                <td><strong>1.6</strong></td>
                <td><strong>Nameplate (Manufacturer, capacity, serial number, and model) of Blade, Ripper, Towing winch are available. </strong></td>
                <td class="checkbox-cell">
    <input type="checkbox" name="result[6][]" id="checkbox6_1" value="PASS" class="large-checkbox" <?php echo isChecked(6, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[6][]" id="checkbox6_2" value="FAIL" class="large-checkbox" <?php echo isChecked(6, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[6][]" id="checkbox6_3" value="NA" class="large-checkbox" <?php echo isChecked(6, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[6]" value="<?php echo getRemark(6, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
            <tr>
                <td><strong>1.7</strong></td>
                <td><strong> The machine is operated by Qualified Operator. </strong></td>
                <td class="checkbox-cell">
    <input type="checkbox" name="result[7][]" id="checkbox7_1" value="PASS" class="large-checkbox" <?php echo isChecked(7, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[7][]" id="checkbox7_2" value="FAIL" class="large-checkbox" <?php echo isChecked(7, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[7][]" id="checkbox7_3" value="NA" class="large-checkbox" <?php echo isChecked(7, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[7]" value="<?php echo getRemark(7, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
            <tr>
                <td><strong>1.8</strong></td>
                <td><strong> Fire extinguisher is provided and has valid inspection tag.  </strong></td>
                <td class="checkbox-cell">
    <input type="checkbox" name="result[8][]" id="checkbox8_1" value="PASS" class="large-checkbox" <?php echo isChecked(8, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[8][]" id="checkbox8_2" value="FAIL" class="large-checkbox" <?php echo isChecked(8, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[8][]" id="checkbox8_3" value="NA" class="large-checkbox" <?php echo isChecked(8, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[8]" value="<?php echo getRemark(8, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			</tbody>
			
			<thead class="thead-dark">
            <tr>
                <th style="text-align: center;">2</th>
                <th style="text-align: center;">VISUAL INSPECTION & FUNCTIONAL TEST</th>
                <th style="text-align: center;">PASS</th>
                <th style="text-align: center;">FAIL</th>
                <th style="text-align: center;">N/A</th>
                <th style="text-align: center;">REMARKS</th>
            </tr>
			</thead>
			<tbody>
			
			<tr>
                <td><strong>2.1</strong></td>
                <td><strong> Cab Mirrors, Glasses, etc. condition  </strong></td>
                <td class="checkbox-cell">
    <input type="checkbox" name="result[9][]" id="checkbox9_1" value="PASS" class="large-checkbox" <?php echo isChecked(9, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[9][]" id="checkbox9_2" value="FAIL" class="large-checkbox" <?php echo isChecked(9, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[9][]" id="checkbox9_3" value="NA" class="large-checkbox" <?php echo isChecked(9, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[9]" value="<?php echo getRemark(9, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
            <tr>
                <td><strong>2.2</strong></td>
                <td><strong>Bucket is in good condition  </strong></td>
                <td class="checkbox-cell">
    <input type="checkbox" name="result[10][]" id="checkbox10_1" value="PASS" class="large-checkbox" <?php echo isChecked(10, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[10][]" id="checkbox10_2" value="FAIL" class="large-checkbox" <?php echo isChecked(10, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[10][]" id="checkbox10_3" value="NA" class="large-checkbox" <?php echo isChecked(10, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[10]" value="<?php echo getRemark(10, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
            <tr>
                <td><strong>2.3</strong></td>
                <td><strong>  No excessive corrosion on frames, anchorages, structures are present. </strong></td>
                <td class="checkbox-cell">
    <input type="checkbox" name="result[11][]" id="checkbox11_1" value="PASS" class="large-checkbox" <?php echo isChecked(11, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[11][]" id="checkbox11_2" value="FAIL" class="large-checkbox" <?php echo isChecked(11, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[11][]" id="checkbox11_3" value="NA" class="large-checkbox" <?php echo isChecked(11, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[11]" value="<?php echo getRemark(11, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
            <tr>
                <td><strong>2.4</strong></td>
                <td><strong>Lift cylinders are operating correctly & without hydraulic oil leaks.  </strong></td>
                <td class="checkbox-cell">
    <input type="checkbox" name="result[12][]" id="checkbox12_1" value="PASS" class="large-checkbox" <?php echo isChecked(12, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[12][]" id="checkbox12_2" value="FAIL" class="large-checkbox" <?php echo isChecked(12, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[12][]" id="checkbox12_3" value="NA" class="large-checkbox" <?php echo isChecked(12, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[12]" value="<?php echo getRemark(12, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
            <tr>
                <td><strong>2.5</strong></td>
                <td><strong> Dipper cylinders are operating correctly & without hydraulic oil leaks. </strong></td>
                <td class="checkbox-cell">
    <input type="checkbox" name="result[13][]" id="checkbox13_1" value="PASS" class="large-checkbox" <?php echo isChecked(13, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[13][]" id="checkbox13_2" value="FAIL" class="large-checkbox" <?php echo isChecked(13, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[13][]" id="checkbox13_3" value="NA" class="large-checkbox" <?php echo isChecked(13, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[13]" value="<?php echo getRemark(13, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
            <tr>
                <td><strong>2.6</strong></td>
                <td><strong> Steering controls are operating correctly   </strong></td>
                <td class="checkbox-cell">
    <input type="checkbox" name="result[14][]" id="checkbox14_1" value="PASS" class="large-checkbox" <?php echo isChecked(14, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[14][]" id="checkbox14_2" value="FAIL" class="large-checkbox" <?php echo isChecked(14, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[14][]" id="checkbox14_3" value="NA" class="large-checkbox" <?php echo isChecked(14, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[14]" value="<?php echo getRemark(14, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
            <tr>
                <td><strong>2.7</strong></td>
                <td><strong> ROPS or overhead guard is provided and can withstand the drop test based on the applicable table or rated capacity. </strong></td>
                <td class="checkbox-cell">
    <input type="checkbox" name="result[15][]" id="checkbox15_1" value="PASS" class="large-checkbox" <?php echo isChecked(15, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[15][]" id="checkbox15_2" value="FAIL" class="large-checkbox" <?php echo isChecked(15, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[15][]" id="checkbox15_3" value="NA" class="large-checkbox" <?php echo isChecked(15, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[15]" value="<?php echo getRemark(15, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
            <tr>
                <td><strong>2.8</strong></td>
                <td><strong> Track Chain/ Link condition is good  </strong></td>
                <td class="checkbox-cell">
    <input type="checkbox" name="result[16][]" id="checkbox16_1" value="PASS" class="large-checkbox" <?php echo isChecked(16, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[16][]" id="checkbox16_2" value="FAIL" class="large-checkbox" <?php echo isChecked(16, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[16][]" id="checkbox16_3" value="NA" class="large-checkbox" <?php echo isChecked(16, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[16]" value="<?php echo getRemark(16, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
            <tr>
                <td><strong>2.9</strong></td> 
                <td><strong>  Track Grouser Plates condition is good    </strong></td>
                <td class="checkbox-cell">
    <input type="checkbox" name="result[17][]" id="checkbox17_1" value="PASS" class="large-checkbox" <?php echo isChecked(17, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[17][]" id="checkbox17_2" value="FAIL" class="large-checkbox" <?php echo isChecked(17, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[17][]" id="checkbox17_3" value="NA" class="large-checkbox" <?php echo isChecked(17, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[17]" value="<?php echo getRemark(17, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
            <tr>
                <td><strong>2.10</strong></td>
                <td><strong>  Track Sprocket and Rollers condition is good  </strong></td>
                <td class="checkbox-cell">
    <input type="checkbox" name="result[18][]" id="checkbox18_1" value="PASS" class="large-checkbox" <?php echo isChecked(18, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[18][]" id="checkbox18_2" value="FAIL" class="large-checkbox" <?php echo isChecked(18, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[18][]" id="checkbox18_3" value="NA" class="large-checkbox" <?php echo isChecked(18, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[18]" value="<?php echo getRemark(18, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
            <tr>
                <td><strong>2.11</strong></td>
                <td><strong> Track Idler condition is good  </strong></td>
                <td class="checkbox-cell">
    <input type="checkbox" name="result[19][]" id="checkbox19_1" value="PASS" class="large-checkbox" <?php echo isChecked(19, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[19][]" id="checkbox19_2" value="FAIL" class="large-checkbox" <?php echo isChecked(19, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[19][]" id="checkbox19_3" value="NA" class="large-checkbox" <?php echo isChecked(19, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[19]" value="<?php echo getRemark(19, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
            <tr>
                <td><strong>2.12</strong></td>
                <td><strong> Safety belt is provided.   </strong></td>
                <td class="checkbox-cell">
    <input type="checkbox" name="result[20][]" id="checkbox20_1" value="PASS" class="large-checkbox" <?php echo isChecked(20, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[20][]" id="checkbox20_2" value="FAIL" class="large-checkbox" <?php echo isChecked(20, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[20][]" id="checkbox20_3" value="NA" class="large-checkbox" <?php echo isChecked(20, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[20]" value="<?php echo getRemark(20, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
            <tr>
                <td><strong>2.13</strong></td>
                <td><strong> All control levers are within reach of operator during the normal operating conditions.  </strong></td>
                <td class="checkbox-cell">
    <input type="checkbox" name="result[21][]" id="checkbox21_1" value="PASS" class="large-checkbox" <?php echo isChecked(21, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[21][]" id="checkbox21_2" value="FAIL" class="large-checkbox" <?php echo isChecked(21, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[21][]" id="checkbox21_3" value="NA" class="large-checkbox" <?php echo isChecked(21, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[21]" value="<?php echo getRemark(21, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
            <tr>
                <td><strong>2.14</strong></td>
                <td><strong>All hydraulic hoses are free of tears, and no signs of leaks on their hose fittings.  </strong></td>
                <td class="checkbox-cell">
    <input type="checkbox" name="result[22][]" id="checkbox22_1" value="PASS" class="large-checkbox" <?php echo isChecked(22, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[22][]" id="checkbox22_2" value="FAIL" class="large-checkbox" <?php echo isChecked(22, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[22][]" id="checkbox22_3" value="NA" class="large-checkbox" <?php echo isChecked(22, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[22]" value="<?php echo getRemark(22, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
            <tr>
                <td><strong>2.15</strong></td>
                <td><strong>Hydraulic oil tank level is correct and tank is securely fastened, and no signs of oil leakages.   </strong></td>
                <td class="checkbox-cell">
    <input type="checkbox" name="result[23][]" id="checkbox23_1" value="PASS" class="large-checkbox" <?php echo isChecked(23, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[23][]" id="checkbox23_2" value="FAIL" class="large-checkbox" <?php echo isChecked(23, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[23][]" id="checkbox23_3" value="NA" class="large-checkbox" <?php echo isChecked(23, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[23]" value="<?php echo getRemark(23, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
            <tr>
                <td><strong>2.16</strong></td>
                <td><strong>Fuel tank is secured & not leaking.  </strong></td>
                <td class="checkbox-cell">
    <input type="checkbox" name="result[24][]" id="checkbox24_1" value="PASS" class="large-checkbox" <?php echo isChecked(24, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[24][]" id="checkbox24_2" value="FAIL" class="large-checkbox" <?php echo isChecked(24, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[24][]" id="checkbox24_3" value="NA" class="large-checkbox" <?php echo isChecked(24, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[24]" value="<?php echo getRemark(24, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
            <tr>
                <td><strong>2.17</strong></td>
                <td><strong> Steering & transmission oil levels are correct & not leaking.  </strong></td>
                <td class="checkbox-cell">
    <input type="checkbox" name="result[25][]" id="checkbox25_1" value="PASS" class="large-checkbox" <?php echo isChecked(25, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[25][]" id="checkbox25_2" value="FAIL" class="large-checkbox" <?php echo isChecked(25, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[25][]" id="checkbox25_3" value="NA" class="large-checkbox" <?php echo isChecked(25, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[25]" value="<?php echo getRemark(25, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
            <tr>
                <td><strong>2.18</strong></td>
                <td><strong> Lubrication points are accessible.  </strong></td>
                <td class="checkbox-cell">
    <input type="checkbox" name="result[26][]" id="checkbox26_1" value="PASS" class="large-checkbox" <?php echo isChecked(26, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[26][]" id="checkbox26_2" value="FAIL" class="large-checkbox" <?php echo isChecked(26, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[26][]" id="checkbox26_3" value="NA" class="large-checkbox" <?php echo isChecked(26, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[26]" value="<?php echo getRemark(26, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
            <tr>
                <td><strong>2.19</strong></td>
                <td><strong>No deterioration of Hydraulic hoses & fittings or leakage of oil. </strong></td>
                <td class="checkbox-cell">
    <input type="checkbox" name="result[27][]" id="checkbox27_1" value="PASS" class="large-checkbox" <?php echo isChecked(27, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[27][]" id="checkbox27_2" value="FAIL" class="large-checkbox" <?php echo isChecked(27, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[27][]" id="checkbox27_3" value="NA" class="large-checkbox" <?php echo isChecked(27, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[27]" value="<?php echo getRemark(27, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
            <tr>
                <td><strong>2.20</strong></td>
                <td><strong>Access and Step Ladders </strong></td>
                <td class="checkbox-cell">
    <input type="checkbox" name="result[28][]" id="checkbox28_1" value="PASS" class="large-checkbox" <?php echo isChecked(28, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[28][]" id="checkbox28_2" value="FAIL" class="large-checkbox" <?php echo isChecked(28, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[28][]" id="checkbox28_3" value="NA" class="large-checkbox" <?php echo isChecked(28, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[28]" value="<?php echo getRemark(28, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
            <tr>
                <td><strong>2.21</strong></td>
                <td><strong> No indication of loose, damaged, or missing components including supports and anchorages on under carriage    </strong></td>
                <td class="checkbox-cell">
    <input type="checkbox" name="result[29][]" id="checkbox29_1" value="PASS" class="large-checkbox" <?php echo isChecked(29, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[29][]" id="checkbox29_2" value="FAIL" class="large-checkbox" <?php echo isChecked(29, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[29][]" id="checkbox29_3" value="NA" class="large-checkbox" <?php echo isChecked(29, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[29]" value="<?php echo getRemark(29, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
            <tr>
                <td><strong>2.22</strong></td>
                <td><strong>Control & drive mechanisms are properly adjusted and without excessive wear.  </strong></td>
                <td class="checkbox-cell">
    <input type="checkbox" name="result[30][]" id="checkbox30_1" value="PASS" class="large-checkbox" <?php echo isChecked(30, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[30][]" id="checkbox30_2" value="FAIL" class="large-checkbox" <?php echo isChecked(30, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[30][]" id="checkbox30_3" value="NA" class="large-checkbox" <?php echo isChecked(30, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[30]" value="<?php echo getRemark(30, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
            <tr>
                <td><strong>2.23</strong></td>
                <td><strong>Seat and back cushions are not torn.  </strong></td>
                <td class="checkbox-cell">
    <input type="checkbox" name="result[31][]" id="checkbox31_1" value="PASS" class="large-checkbox" <?php echo isChecked(31, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[31][]" id="checkbox31_2" value="FAIL" class="large-checkbox" <?php echo isChecked(31, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[31][]" id="checkbox31_3" value="NA" class="large-checkbox" <?php echo isChecked(31, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[31]" value="<?php echo getRemark(31, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
            <tr>
                <td><strong>2.24</strong></td>
                <td><strong>No damage tubing, piping, electrical cables, or hoses, and their fittings. </strong></td>
                <td class="checkbox-cell">
    <input type="checkbox" name="result[32][]" id="checkbox32_1" value="PASS" class="large-checkbox" <?php echo isChecked(32, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[32][]" id="checkbox32_2" value="FAIL" class="large-checkbox" <?php echo isChecked(32, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[32][]" id="checkbox32_3" value="NA" class="large-checkbox" <?php echo isChecked(32, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[32]" value="<?php echo getRemark(32, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			
			<tr>
                <td><strong>2.25</strong></td>
                <td><strong>Front & Rear Windshields are in good condition & Wiper Motor Assembly is working. </strong></td>
                <td class="checkbox-cell">
    <input type="checkbox" name="result[33][]" id="checkbox33_1" value="PASS" class="large-checkbox" <?php echo isChecked(33, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[33][]" id="checkbox33_2" value="FAIL" class="large-checkbox" <?php echo isChecked(33, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[33][]" id="checkbox33_3" value="NA" class="large-checkbox" <?php echo isChecked(33, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[33]" value="<?php echo getRemark(33, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			
			<tr>
                <td><strong>2.26</strong></td>
                <td><strong> Limit Switches are properly working. </strong></td>
                <td class="checkbox-cell">
    <input type="checkbox" name="result[34][]" id="checkbox34_1" value="PASS" class="large-checkbox" <?php echo isChecked(34, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[34][]" id="checkbox34_2" value="FAIL" class="large-checkbox" <?php echo isChecked(34, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[34][]" id="checkbox34_3" value="NA" class="large-checkbox" <?php echo isChecked(34, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[34]" value="<?php echo getRemark(34, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			
			</tbody>
			
			<thead class="thead-dark">
            <tr>
                <th style="text-align: center;">3</th>
                <th style="text-align: center;"> ENGINE & ELECTRICAL SYSTEM</th>
                <th style="text-align: center;">PASS</th>
                <th style="text-align: center;">FAIL</th>
                <th style="text-align: center;">N/A</th>
                <th style="text-align: center;">REMARKS</th>
            </tr>
		</thead>
		<tbody>
 
 <tr>
                <td><strong>3.1</strong></td>
                <td><strong>  Engine oil level is correct </strong></td>
                <td class="checkbox-cell">
    <input type="checkbox" name="result[35][]" id="checkbox35_1" value="PASS" class="large-checkbox" <?php echo isChecked(35, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[35][]" id="checkbox35_2" value="FAIL" class="large-checkbox" <?php echo isChecked(35, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[35][]" id="checkbox35_3" value="NA" class="large-checkbox" <?php echo isChecked(35, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[35]" value="<?php echo getRemark(35, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
            <tr>
                <td><strong>3.2</strong></td>
                <td><strong>Engine has no excessive smoke, & engine oil leak. </strong></td>
                <td class="checkbox-cell">
    <input type="checkbox" name="result[36][]" id="checkbox36_1" value="PASS" class="large-checkbox" <?php echo isChecked(36, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[36][]" id="checkbox36_2" value="FAIL" class="large-checkbox" <?php echo isChecked(36, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[36][]" id="checkbox36_3" value="NA" class="large-checkbox" <?php echo isChecked(36, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[36]" value="<?php echo getRemark(36, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
            <tr>
                <td><strong>3.3</strong></td>
                <td><strong> Fuel is not leaking. </strong></td>
                <td class="checkbox-cell">
    <input type="checkbox" name="result[37][]" id="checkbox37_1" value="PASS" class="large-checkbox" <?php echo isChecked(37, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[37][]" id="checkbox37_2" value="FAIL" class="large-checkbox" <?php echo isChecked(37, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[37][]" id="checkbox37_3" value="NA" class="large-checkbox" <?php echo isChecked(37, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[37]" value="<?php echo getRemark(37, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
            <tr>
                <td><strong>3.4</strong></td>
                <td><strong>Engine has no loss of power.   </strong></td>
                <td class="checkbox-cell">
    <input type="checkbox" name="result[38][]" id="checkbox38_1" value="PASS" class="large-checkbox" <?php echo isChecked(38, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[38][]" id="checkbox38_2" value="FAIL" class="large-checkbox" <?php echo isChecked(38, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[38][]" id="checkbox38_3" value="NA" class="large-checkbox" <?php echo isChecked(38, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[38]" value="<?php echo getRemark(38, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
            <tr>
                <td><strong>3.5</strong></td>
                <td><strong>Fan, Alternator, & steering belts tension are not loose.   </strong></td>
                <td class="checkbox-cell">
    <input type="checkbox" name="result[39][]" id="checkbox39_1" value="PASS" class="large-checkbox" <?php echo isChecked(39, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[39][]" id="checkbox39_2" value="FAIL" class="large-checkbox" <?php echo isChecked(39, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[39][]" id="checkbox39_3" value="NA" class="large-checkbox" <?php echo isChecked(39, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[39]" value="<?php echo getRemark(39, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
            <tr>
                <td><strong>3.6</strong></td>
                <td><strong>  Instrument Panel Indicator Lights are functioning correctly.  </strong></td>
                <td class="checkbox-cell">
    <input type="checkbox" name="result[40][]" id="checkbox40_1" value="PASS" class="large-checkbox" <?php echo isChecked(40, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[40][]" id="checkbox40_2" value="FAIL" class="large-checkbox" <?php echo isChecked(40, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[40][]" id="checkbox40_3" value="NA" class="large-checkbox" <?php echo isChecked(40, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[40]" value="<?php echo getRemark(40, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
            <tr>
                <td><strong>3.7</strong></td>
                <td><strong> Strobe light or rotating beacon light is working.  </strong></td>
                <td class="checkbox-cell">
    <input type="checkbox" name="result[41][]" id="checkbox41_1" value="PASS" class="large-checkbox" <?php echo isChecked(41, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[41][]" id="checkbox41_2" value="FAIL" class="large-checkbox" <?php echo isChecked(41, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[41][]" id="checkbox41_3" value="NA" class="large-checkbox" <?php echo isChecked(41, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[41]" value="<?php echo getRemark(41, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
            <tr>
                <td><strong>3.8</strong></td>
                <td><strong>Head light & working lights are not broken and are functioning correctly. </strong></td>
                <td class="checkbox-cell">
    <input type="checkbox" name="result[42][]" id="checkbox42_1" value="PASS" class="large-checkbox" <?php echo isChecked(42, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[42][]" id="checkbox42_2" value="FAIL" class="large-checkbox" <?php echo isChecked(42, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[42][]" id="checkbox42_3" value="NA" class="large-checkbox" <?php echo isChecked(42, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[42]" value="<?php echo getRemark(42, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
            <tr>
                <td><strong>3.9</strong></td>
                <td><strong> Back-Up (Reverse) Light and alarm are working. </strong></td>
                <td class="checkbox-cell">
    <input type="checkbox" name="result[43][]" id="checkbox43_1" value="PASS" class="large-checkbox" <?php echo isChecked(43, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[43][]" id="checkbox43_2" value="FAIL" class="large-checkbox" <?php echo isChecked(43, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[43][]" id="checkbox43_3" value="NA" class="large-checkbox" <?php echo isChecked(43, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[43]" value="<?php echo getRemark(43, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
            <tr>
                <td><strong>3.10</strong></td>
                <td><strong>Horn is working. </strong></td>
                <td class="checkbox-cell">
    <input type="checkbox" name="result[44][]" id="checkbox44_1" value="PASS" class="large-checkbox" <?php echo isChecked(44, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[44][]" id="checkbox44_2" value="FAIL" class="large-checkbox" <?php echo isChecked(44, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[44][]" id="checkbox44_3" value="NA" class="large-checkbox" <?php echo isChecked(44, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[44]" value="<?php echo getRemark(44, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong> 3.11</strong></td>
                <td><strong>Battery water/electrolyte level is correct.  </strong></td>
                <td class="checkbox-cell">
    <input type="checkbox" name="result[45][]" id="checkbox45_1" value="PASS" class="large-checkbox" <?php echo isChecked(45, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[45][]" id="checkbox45_2" value="FAIL" class="large-checkbox" <?php echo isChecked(45, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[45][]" id="checkbox45_3" value="NA" class="large-checkbox" <?php echo isChecked(45, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[45]" value="<?php echo getRemark(45, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>3.12 </strong></td>
                <td><strong> Radiator coolant level is correct and no sign of water leakage. </strong></td>
                <td class="checkbox-cell">
    <input type="checkbox" name="result[46][]" id="checkbox46_1" value="PASS" class="large-checkbox" <?php echo isChecked(46, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[46][]" id="checkbox46_2" value="FAIL" class="large-checkbox" <?php echo isChecked(46, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[46][]" id="checkbox46_3" value="NA" class="large-checkbox" <?php echo isChecked(46, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[46]" value="<?php echo getRemark(46, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>3.13 </strong></td>
                <td><strong> Housekeeping is satisfactory  </strong></td>
                <td class="checkbox-cell">
    <input type="checkbox" name="result[47][]" id="checkbox47_1" value="PASS" class="large-checkbox" <?php echo isChecked(47, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[47][]" id="checkbox47_2" value="FAIL" class="large-checkbox" <?php echo isChecked(47, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[47][]" id="checkbox47_3" value="NA" class="large-checkbox" <?php echo isChecked(47, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[47]" value="<?php echo getRemark(47, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			
			</tbody>
			
			 <thead class="thead-dark">
            <tr>
                <th style="text-align: center;">4</th>
                <th style="text-align: center;">   ATTACHMENTS/IMPLEMENTS</th>
                <th style="text-align: center;">PASS</th>
                <th style="text-align: center;">FAIL</th>
                <th style="text-align: center;">N/A</th>
                <th style="text-align: center;">REMARKS</th>
            </tr>
			</thead>
			
			<tbody>
            
			<tr>
                <td><strong>4.1</strong></td>
                <td><strong>Bucket condition is good </strong></td>
                <td class="checkbox-cell">
    <input type="checkbox" name="result[48][]" id="checkbox48_1" value="PASS" class="large-checkbox" <?php echo isChecked(48, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[48][]" id="checkbox48_2" value="FAIL" class="large-checkbox" <?php echo isChecked(48, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[48][]" id="checkbox48_3" value="NA" class="large-checkbox" <?php echo isChecked(48, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[48]" value="<?php echo getRemark(48, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
            <tr>
                <td><strong>4.2</strong></td>
                <td><strong> Grappler condition is good</strong></td>
                <td class="checkbox-cell">
    <input type="checkbox" name="result[49][]" id="checkbox49_1" value="PASS" class="large-checkbox" <?php echo isChecked(49, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[49][]" id="checkbox49_2" value="FAIL" class="large-checkbox" <?php echo isChecked(49, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[49][]" id="checkbox49_3" value="NA" class="large-checkbox" <?php echo isChecked(49, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[49]" value="<?php echo getRemark(49, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
            <tr>
                <td><strong>4.3</strong></td>
                <td><strong> Rock Breaker condition is good </strong></td>
                <td class="checkbox-cell">
    <input type="checkbox" name="result[50][]" id="checkbox50_1" value="PASS" class="large-checkbox" <?php echo isChecked(50, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[50][]" id="checkbox50_2" value="FAIL" class="large-checkbox" <?php echo isChecked(50, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[50][]" id="checkbox50_3" value="NA" class="large-checkbox" <?php echo isChecked(50, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[50]" value="<?php echo getRemark(50, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
            
			</tbody>
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



<!-- <script>
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

// Check if all remark fields are filled
if (isValid) {
remarks.forEach(remark => {
    if (remark.value.trim() === '') {
        isValid = false;
        alert('Please fill in all remarks.');
        remark.focus();
        return false;
    }
});
}

// Prevent form submission if validation fails
if (!isValid) {
event.preventDefault();
}
});
</script> -->



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

document.addEventListener("DOMContentLoaded", function () {
const checklistForm = document.getElementById("checklistForm");

if (checklistForm) {
// Ensure only one checkbox is selected per row for the result field
checklistForm.addEventListener("change", function (event) {
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
