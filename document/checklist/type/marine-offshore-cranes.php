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
    <title>INSPECTION CHECKLIST FOR MARINE & OFFSHORE CRANES  </title>
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
            <strong>INSPECTION CHECKLIST FOR MARINE & OFFSHORE CRANES  </strong>
        </td>
    </tr>
    <tr>
        <td>FRM.0601-1.4</td>
        <td>Revision 02</td>
        <td><b>Issue Date: </b>30/SEP/2020</td>
    </tr>
    <tr>
        <td class="left-align"><b>Prepared By</b><br>Operations Manager</td>
        <td  class="left-align"><b>Reviewed & Approved By</b><br>Managing Director</td>
   
   <td><img src="../../code.png" width="80px" height="80px" alt="" /></td>
</tr>
</table>
            <!-- <table class="table table-bordered">
                <tbody>
				
				<tr>
                <td colspan="3" style="text-align: center;"><strong>INSPECTION CHECKLIST FOR MARINE & OFFSHORE CRANES </strong></td>
				</tr>
            <tr>
                <td style="width: 25%; text-align: center;"><strong>FRM.0601-1.4</strong></td>
                <td style="width: 25%; text-align: center;"> <strong>Revision 02</strong></td>
                
                <td style="width: 25%; text-align: center;"> <strong>Issue Date: 30/SEP/2020</strong></td>
            </tr>
			</tbody>
			</table> -->
			
			</div>

        <h4>MARINE & OFFSHORE CRANES</h4>
        <h4>ASME B30.2-2016, ASME B30.3-2016, ASME B30.4-2015, ASME B30.5-2018, ASME B30.8-2015, ASME B30.16-2017, ASME B30.17-2015, ASME B30.22-2016, API SPEC 2C-2012, API RP 2D-2015</h4>
		
        
		 <!--<button class="btn btn-primary no-print" onclick="preparePrint()">Print View</button>-->

<form method="post" action="./update_checklist.php" id="checklistForm">
    <input type="hidden" name="checklist_no" value="<?php echo $row['checklist_id'] ?>" />
         <div class="table-responsive">
            <table class="table table-bordered">               
            <tr>
                <th style="width: 25%;">REPORT NO</th>
                <td style="width: 25%;"><input type="text" name="report_no" value="<?php echo htmlspecialchars($row['report_no'] ?? ''); ?>" class="form-control" style="font-weight: bold; border: none; background: transparent;"></td>
                <th style="width: 25%;">INSPECTION DATE</th>
                <td style="width: 25%;"><input type="date" name="inspection_date" value="<?php echo htmlspecialchars($row['inspection_date'] ?? ''); ?>" class="form-control" style="font-weight: bold; border: none; background: transparent;"></td>
            </tr>
            <tr>
                <th>CLIENT’S NAME</th>
                <td><input type="text" name="header_client_name" value="<?php echo htmlspecialchars($row['client_name'] ?? ''); ?>" class="form-control" style="font-weight: bold; border: none; background: transparent;"></td>
                <th>INSPECTED BY</th>
                <td><input type="text" name="inspected_by" value="<?php echo htmlspecialchars($row['inspected_by'] ?? ''); ?>" class="form-control" style="font-weight: bold; border: none; background: transparent;"></td>
            </tr>
            <tr>
                <th>LOCATION</th>
                <td><input type="text" name="location" value="<?php echo htmlspecialchars($row['location'] ?? ''); ?>" class="form-control" style="font-weight: bold; border: none; background: transparent;"></td>
                <th>STICKER NO.</th>
                <td><input type="text" name="sticker_no" value="<?php echo htmlspecialchars($row['sticker_no'] ?? ''); ?>" class="form-control" style="font-weight: bold; border: none; background: transparent;"></td>
            </tr>
            <tr>
                <th>CRANE ASSET NO:</th>
                <td><input type="text" name="equipment_no" value="<?php echo htmlspecialchars($row['equipment_no'] ?? ''); ?>" class="form-control" style="font-weight: bold; border: none; background: transparent;"></td>
                <th>CRANE SERIAL NO.:</th>
                <td><input type="text" name="crane_serial_no" value="<?php echo htmlspecialchars($row['crane_serial_no'] ?? ''); ?>" class="form-control" style="font-weight: bold; border: none; background: transparent;"></td>
            </tr>
            <tr>
                <th>EQUIPMENT TYPE</th>
                <td><input type="text" name="equipmenttype" value="<?php echo htmlspecialchars($row['equipmenttype'] ?? ''); ?>" class="form-control" style="font-weight: bold; border: none; background: transparent;"></td>
                <th>CAPACITY (SWL)</th>
                <td><input type="text" name="capacity_swl" value="<?php echo htmlspecialchars($row['capacity_swl'] ?? ''); ?>" class="form-control" style="font-weight: bold; border: none; background: transparent;"></td>
            </tr>
        </table>
</div>
        


        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="thead-dark">
                <tr>
                    <th style="text-align: center;">S.N</th>
                    <th style="text-align: center;">ACCEPTANCE CRITERIA</th>
                    <th style="text-align: center;">REFERENCE</th>					
                    <th style="text-align: center;" colspan="3">RESULT</th>                    
                    <th style="text-align: center;">REMARKS</th>
                </tr>
			<tr>
                    <th style="text-align: center;">1</th>
                    <th style="text-align: center;">GENERAL REQUIREMENTS</th>
					<th style="text-align: center;"> </th>                    
                    <th style="text-align: center;">PASS</th>
                    <th style="text-align: center;">FAIL</th>
                    <th style="text-align: center;">NA</th>
                    <th> </th>
                </tr>
				
				</thead> 
                <tbody>

 <tr>
                <td><strong>1.1</strong></td>
                <td><strong> Equipment documentation is available </strong></td>
				<td style="text-align: center;"><strong>ASME B30.5
Sec.8-2.1.5
 </strong></td>
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
                <td><strong>Previous inspection reports are checked </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5
Sec.8-2.4.5
 </strong></td>
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
                <td><strong> Operator is certified or qualified for the specific type of equipment.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.5
Sec.5-3.1.3.3.1
ASME B30.8 
Sec.8-3.1.2
  </strong></td>
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
                <td><strong> Crane manufacturer name, address, serial number and power rates are marked or tagged </strong></td>
				<td style="text-align: center;"><strong> ASME B30.22
Sec. 22-2.1.4n
  </strong></td>
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
                <td><strong>Operator manuals are available</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8
Sec.8-1.2.2
  </strong></td>
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
                <td><strong>A sign is posted warning the operator not to rely solely on any automatic device as a substitute for safe operating practice</strong></td>
				<td style="text-align: center;"><strong>CIMS QHSE-06</strong></td>
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
                <td><strong>Rated capacity of crane is marked </strong></td>
				<td style="text-align: center;"><strong>ASME B30.22 Sec. 22-1.1.3a</strong></td>
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
                <td><strong>A durable rating chart with legible letters and figures is provided and fixed at a location visible to the operator while seated at his control station</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8
Sec.8-1.1.3
</strong></td>
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
 <tr>
                <td><strong>1.9</strong></td>
                <td><strong>Erection and dismantling procedures are based on manufacturer recommendations</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8
Sec.8-1.1.2 (a)
 </strong></td>
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
                <td><strong>1.10</strong></td>
                <td><strong>Main structure components are free of defects (bolts, pins, mast sections for cracks, bent structural members, etc.)</strong></td>
				<td style="text-align: center;"><strong> ASME B30.3
Sec.3-1.1.2
 </strong></td>
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
                <td><strong>1.11</strong></td>
                <td><strong>Flags and/or markers with high visibility are placed and visible to the operator</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
Sec.3-1.1.4 (f)
</strong></td>
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
                <td><strong>1.12</strong></td>
                <td><strong> Essential precautionary or warning notes relative to limitations on equipment, operating procedures, and stability factors are provided on the rating chart or the operating manual</strong></td>
				<td style="text-align: center;"><strong> ASME B30.8
Sec.8-1.1.3 (d)
  </strong></td>
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
                    <th style="text-align: center;">2</th>
                    <th style="text-align: center;">INSPECTION POINTS</th>
					<th style="text-align: center;"> </th>
                    
                    <th style="text-align: center;">PASS</th>
                    <th style="text-align: center;">FAIL</th>
                    <th style="text-align: center;">NA</th>
                    <th> </th>
                </tr>
				
 <tr>
                <td><strong>2.1</strong></td>
                <td><strong> Access ladders to cab are provided (platform surfaces to be skid resistant) </strong></td>
				<td style="text-align: center;"><strong>ASME B30.4
Sec.4-1.15.2a)
 </strong></td>
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
                <td><strong>2.2</strong></td>
                <td><strong>Foot walks and ladders are provided where appropriate (foot walks to be minimum of 18" wide, with slip resistant surface) </strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
Sec.3-1.18.1
 </strong></td>
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
                <td><strong>2.3</strong></td>
                <td><strong> Navigational lights are provided</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8
Sec.8-1.4.3 (g)
  </strong></td>
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
                <td><strong>2.4</strong></td>
                <td><strong> Flame arrester on fill and vent lines of gasoline tank is provided</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8
Sec.8-1.4.3 (d)
  </strong></td>
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
                <td><strong>2.5</strong></td>
                <td><strong>Fuel tank is equipped with self-closing filler cap</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8
Sec.8-1.4.3 (c)
  </strong></td>
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
                <td><strong>2.6</strong></td>
                <td><strong>A fire extinguisher is provided in the cab (minimum rating is 10 BC)</strong></td>
				<td style="text-align: center;"><strong>ASME B30.4 
Sec.4-1.15.4, ASME
B30.3, Sec.3.1.18.4
</strong></td>
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
                <td><strong>2.7</strong></td>
                <td><strong>Exhaust piping is guarded or insulated (where personnel could contact it) </strong></td>
				<td style="text-align: center;"><strong>ASME B30.4	
Sec.4-1.16.4, ASME
B30.3 Sec.3.1.18.4
</strong></td>
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
                <td><strong>2.8</strong></td>
                <td><strong>Fuel filler pipes are located or protected so as to avoid spillage</strong></td>
				<td style="text-align: center;"><strong>ASME B30.4 
Sec.4-1.16.7, ASME B30.3 Sec.3.1.18.8

</strong></td>
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
                <td><strong>2.9</strong></td>
                <td><strong>Guards are fitted to exposed moving parts and can support 90 kg without deformation</strong></td>
				<td style="text-align: center;"><strong>ASME B30.4
Sec. 4-1.16.2, ASME
B30.3 Sec.3.1.18.2
 </strong></td>
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
                <td><strong>2.10</strong></td>
                <td><strong>Limiting devices are fully operational for trolley travel, boom luffing, upper hoist limit, crane travel, lifted load and radius</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
Sec. 3-1.11, ASME
B30.4 Sec.4.1.9
 </strong></td>
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
                <td><strong>2.11</strong></td>
                <td><strong>Slewing (swing) motion of the crane is smooth for both start and stop</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3 
Sec. 3-1.6.1, ASME
B30.4 Sec.4.1.6.1
 </strong></td>
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
                <td><strong>2.12</strong></td>
                <td><strong>Slewing brakes are operational and can be set to hold the position without further operator action</strong></td>
				<td style="text-align: center;"><strong> ASME B30.3	
Sec. 3-1.6.1b, ASME B30.4 Sec.4.1.6.1 (b)
 </strong></td>
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
                <td><strong>2.13</strong></td>
                <td><strong> Counterweight values are correct</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
Sec.3-1.14, ASME
B30.4 Sec.4.1.12
  </strong></td>
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
                <td><strong>2.14</strong></td>
                <td><strong> Counterweight is fixed in place and (for movable weights uncontrolled movement is not possible) </strong></td>
				<td style="text-align: center;"><strong> ASME B30.3
Sec.3-1.14, ASME
B30.4 Sec.4.1.12
  </strong></td>
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
                <td><strong>2.15</strong></td>
                <td><strong>Welded members/Joints are free of defects (cracks, corrosion, etc.)</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
Sec.3-1.18.5, ASME B30.4 (4.2.1.4a1)
  </strong></td>
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
                <td><strong>2.16</strong></td>
                <td><strong>A metal receptacle is in place for storage of small hand tools and lubricating equipment (in cab or machinery housing)</strong></td>
				<td style="text-align: center;"><strong>ASME B30.4
Sec.4-1.15.3 ASME
B30.3, Sec.3.1.17.3
</strong></td>
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
                <td><strong>2.17</strong></td>
                <td><strong>Vertical clearance between crane lowest point and floor level of boat or barge is at least 7 feet (if less than 7 feet, barricading is to be provided to protect personnel from rotating Sections) </strong></td>
				<td style="text-align: center;"><strong>ASME B30.8
Sec.8-1.5.
</strong></td>
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
                <td><strong>2.18</strong></td>
                <td><strong>All controls are within the reach of operator and clearly marked with functions and modes of </strong></td>
				<td style="text-align: center;"><strong>ASME B30.8
Sec.8-1.8.1 (a)
</strong></td>
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
                <td><strong>2.19</strong></td>
                <td><strong>Remote control of the crane is properly working and crane stops if control signal becomes ineffective (if fitted)</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8
Sec.8-1.8.1 (b)
 </strong></td>
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
                <td><strong>2.20</strong></td>
                <td><strong>Electrical motors disconnect on power failure</strong></td>
				<td style="text-align: center;"><strong> ASME B30.8
Sec.8-1.8.1 (c)
 </strong></td>
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
                <td><strong>2.21</strong></td>
                <td><strong>Electrical motors do not start on power return </strong></td>
				<td style="text-align: center;"><strong>ASME B30.8
Sec.8-1.8.1 (c)
 </strong></td>
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
                <td><strong>2.22</strong></td>
                <td><strong>Over-speeding preventing device is provided for electric motor operated cranes </strong></td>
				<td style="text-align: center;"><strong>ASME B30.8
Sec.8-1.8.1 (d)
 </strong></td>
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
			<tr>
                <td><strong>2.23</strong></td>
                <td><strong> Power plant controls are provided to
•	Control start, stop and lock in off position
•	Control engine speed
•	Stop diesel engines in emergency, and
Shift selective transmissions
</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8
Sec.8-1.8.2
  </strong></td>
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
                <td><strong>2.24</strong></td>
                <td><strong> Control forces are acceptable and within the capabilities of operator</strong></td>
				<td style="text-align: center;"><strong> ASME B30.8
Sec.8-1.8.3 (a)
  </strong></td>
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
                <td><strong>2.25</strong></td>
                <td><strong>Controls’ travel distances are:
•	Hand levers not greater than 14” (356 mm) from neutral position on two- way and not greater than 24 (610 mm) on one-way levers
Foot pedals not greater than 10” (254 mm)
</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8
Sec.8-1.8.3 (a)
  </strong></td>
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
                <td><strong>2.26</strong></td>
                <td><strong>Clutch is operating properly and within the reach of operator station</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8
Sec.8-1.8.4
</strong></td>
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
                <td><strong>2.27</strong></td>
                <td><strong>Electrical control panels are fixed, protected and free of any damages </strong></td>
				<td style="text-align: center;"><strong>ASME B30.8
Sec.8-1.8.5
</strong></td>
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
                <td><strong>2.28</strong></td>
                <td><strong>Resistors and connectors are corrosion free, protected, ventilated and installed to prevent the accumulation of combustible matter near hot parts</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8
Sec.8-1.8.6 (a)
</strong></td>
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
                <td><strong>2.29</strong></td>
                <td><strong>Resistor units are supported and do not vibrate</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8
Sec.8-1.8.6 (b)
 </strong></td>
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
                <td><strong>2.30</strong></td>
                <td><strong>Cab is provided with safety glazing glass</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8
Sec.8-1.8.6 (b)
 </strong></td>
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
                <td><strong>2.31</strong></td>
                <td><strong>Cab door swings out to open or slides rearward for sliding door type (door operation is restrained during machine operation) </strong></td>
				<td style="text-align: center;"><strong>ASME B30.8
Sec.8-1.8.10.1 (c)
 </strong></td>
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
                <td><strong>2.32</strong></td>
                <td><strong>Cab wipers are in good condition and operating </strong></td>
				<td style="text-align: center;"><strong>ASME B30.8
Sec.8-1.8.10.1 (c)
 </strong></td>
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
                <td><strong>2.33</strong></td>
                <td><strong> A clear passageway is provided from operator's position to outside the cab</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8
Sec.8-1.8.10.1 (d)
  </strong></td>
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
                <td><strong>2.34</strong></td>
                <td><strong> Platform to cab walkway is skid resistance </strong></td>
				<td style="text-align: center;"><strong> ASME B30.8
Sec.8-1.8.10.2 (a)
  </strong></td>
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
                <td><strong>2.35</strong></td>
                <td><strong>Handholds/steps are provided to facilitate access to the cab</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8
Sec.8-1.8.10.3
  </strong></td>
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
			<tr>
                <td><strong>2.36</strong></td>
                <td><strong>Communication method is one of the following:
•	Hand signals (standard chart is posted in visible location to the operator)
•	Radio/telephone
•	Bell signal
Special signals (to be understood and agreed upon by operator and signalperson)
</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8, 
Sec.8-3.3.1 
Sec.8-3-3.2 
Sec.8-3-3.3 
Sec.8-3-3.4
</strong></td>
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
                <td><strong>2.37</strong></td>
                <td><strong>Load trolley can brake in travel mode (automatically in case of power loss and drive rope breakage) </strong></td>
				<td style="text-align: center;"><strong>ASME B30.4
Sec.4-1.5.4
</strong></td>
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
                <td><strong>2.38</strong></td>
                <td><strong>Clutch is in good condition and protected against weather and oil contaminations</strong></td>
				<td style="text-align: center;"><strong>ASME B30.4
Sec.4-1.16.5, ASME
B30.3 Sec.3.1.18.6
</strong></td>
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
 <tr>
                <td><strong>2.39</strong></td>
                <td><strong>Brakes are protected from weather and other industrial liquids</strong></td>
				<td style="text-align: center;"><strong>ASME B30.4
Sec.4-1.8.3
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[51][]" id="checkbox51_1" value="PASS" class="large-checkbox" <?php echo isChecked(51, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[51][]" id="checkbox51_2" value="FAIL" class="large-checkbox" <?php echo isChecked(51, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[51][]" id="checkbox51_3" value="NA" class="large-checkbox" <?php echo isChecked(51, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[51]" value="<?php echo getRemark(51, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			
			<tr>
                <td><strong>2.40</strong></td>
                <td><strong>Wire rope clips are correctly located and secured</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8. 
Sec.8-2.4.2 (b-5)
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[52][]" id="checkbox52_1" value="PASS" class="large-checkbox" <?php echo isChecked(52, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[52][]" id="checkbox52_2" value="FAIL" class="large-checkbox" <?php echo isChecked(52, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[52][]" id="checkbox52_3" value="NA" class="large-checkbox" <?php echo isChecked(52, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[52]" value="<?php echo getRemark(52, $saved_remarks); ?>" class="form-control">
</td>
            </tr>	
<tr>
                <td><strong>2.41</strong></td>
                <td><strong> The rope does not have more than 6 randomly distributed broken wires in 1 lay or 3 in 1 strand in 1 lay (for running ropes)</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8, 
Sec.8-2.4.3 (b-1)
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[53][]" id="checkbox53_1" value="PASS" class="large-checkbox" <?php echo isChecked(53, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[53][]" id="checkbox53_2" value="FAIL" class="large-checkbox" <?php echo isChecked(53, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[53][]" id="checkbox53_3" value="NA" class="large-checkbox" <?php echo isChecked(53, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[53]" value="<?php echo getRemark(53, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			
			<tr>
                <td><strong>2.42</strong></td>
                <td><strong>The rope does not have more than 6 randomly distributed broken wires in 1 lay or 3 in 1 strand in 1 lay (for running ropes)</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8,
 Sec.8-2.4.3(b-2)
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[54][]" id="checkbox54_1" value="PASS" class="large-checkbox" <?php echo isChecked(54, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[54][]" id="checkbox54_2" value="FAIL" class="large-checkbox" <?php echo isChecked(54, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[54][]" id="checkbox54_3" value="NA" class="large-checkbox" <?php echo isChecked(54, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[54]" value="<?php echo getRemark(54, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>2.43</strong></td>
                <td><strong>The rope wear does not exceed 1/3 of the original diameter (for running ropes)</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8,
 Sec.8-2.4.3 (b-3)
  </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[55][]" id="checkbox55_1" value="PASS" class="large-checkbox" <?php echo isChecked(55, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[55][]" id="checkbox55_2" value="FAIL" class="large-checkbox" <?php echo isChecked(55, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[55][]" id="checkbox55_3" value="NA" class="large-checkbox" <?php echo isChecked(55, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[55]" value="<?php echo getRemark(55, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>2.44</strong></td>
                <td><strong>The rope does not have kinking, crunching, bird caging, evidence of heat damage, upstanding, core corrosion, main strand displacement or any other damages</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8, Sec.8-2.4.3 (b4/5)
Sec.8-2.4.1 (a)
  </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[56][]" id="checkbox56_1" value="PASS" class="large-checkbox" <?php echo isChecked(56, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[56][]" id="checkbox56_2" value="FAIL" class="large-checkbox" <?php echo isChecked(56, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[56][]" id="checkbox56_3" value="NA" class="large-checkbox" <?php echo isChecked(56, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[56]" value="<?php echo getRemark(56, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>2.45</strong></td>
                <td><strong>The ropes do not have more than two broken wires in 1 lay in sections beyond end connections or more than 1 broken wire at an end connection (for standing ropes)</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8, 
Sec.8 2.4.3 (b-7)
  </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[57][]" id="checkbox57_1" value="PASS" class="large-checkbox" <?php echo isChecked(57, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[57][]" id="checkbox57_2" value="FAIL" class="large-checkbox" <?php echo isChecked(57, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[57][]" id="checkbox57_3" value="NA" class="large-checkbox" <?php echo isChecked(57, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[57]" value="<?php echo getRemark(57, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>2.46</strong></td>
                <td><strong>Wire rope is not corroded</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8. 
Sec.8-2.4.1(b)
</strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[58][]" id="checkbox58_1" value="PASS" class="large-checkbox" <?php echo isChecked(58, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[58][]" id="checkbox58_2" value="FAIL" class="large-checkbox" <?php echo isChecked(58, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[58][]" id="checkbox58_3" value="NA" class="large-checkbox" <?php echo isChecked(58, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[58]" value="<?php echo getRemark(58, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>2.47</strong></td>
                <td><strong>Rope lubrication is adequate</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8, 
Sec.8-2.4.6 (e)
</strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[59][]" id="checkbox59_1" value="PASS" class="large-checkbox" <?php echo isChecked(59, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[59][]" id="checkbox59_2" value="FAIL" class="large-checkbox" <?php echo isChecked(59, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[59][]" id="checkbox59_3" value="NA" class="large-checkbox" <?php echo isChecked(59, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[59]" value="<?php echo getRemark(59, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			
      <tr>
                <td><strong>2.48</strong></td>
                <td><strong>Sheaves and drums are not cracked or show worn surfaces</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8, 
Sec.8-2.1.3 (a3)
</strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[60][]" id="checkbox60_1" value="PASS" class="large-checkbox" <?php echo isChecked(60, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[60][]" id="checkbox60_2" value="FAIL" class="large-checkbox" <?php echo isChecked(60, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[60][]" id="checkbox60_3" value="NA" class="large-checkbox" <?php echo isChecked(60, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[60]" value="<?php echo getRemark(60, $saved_remarks); ?>" class="form-control">
</td>
            </tr>  
 <tr>
                <td><strong>2.49</strong></td>
                <td><strong>Bolts and rivets around the structure are tight</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8, 
Sec.8-2.1.3 (a2)
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[61][]" id="checkbox61_1" value="PASS" class="large-checkbox" <?php echo isChecked(61, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[61][]" id="checkbox61_2" value="FAIL" class="large-checkbox" <?php echo isChecked(61, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[61][]" id="checkbox61_3" value="NA" class="large-checkbox" <?php echo isChecked(61, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[61]" value="<?php echo getRemark(61, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			
			<tr>
                <td><strong>2.50</strong></td>
                <td><strong>Clutch system parts, linings, pawls and ratchet are not excessively worn</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8, 
Sec.8-2.1.2 (a11)
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[62][]" id="checkbox62_1" value="PASS" class="large-checkbox" <?php echo isChecked(62, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[62][]" id="checkbox62_2" value="FAIL" class="large-checkbox" <?php echo isChecked(62, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[62][]" id="checkbox62_3" value="NA" class="large-checkbox" <?php echo isChecked(62, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[62]" value="<?php echo getRemark(62, $saved_remarks); ?>" class="form-control">
</td>
            </tr>	
<tr>
                <td><strong>2.51</strong></td>
                <td><strong> Brake system parts are not excessively worn</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8, 
Sec.8-2.1.2 (a5)
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[63][]" id="checkbox63_1" value="PASS" class="large-checkbox" <?php echo isChecked(63, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[63][]" id="checkbox63_2" value="FAIL" class="large-checkbox" <?php echo isChecked(63, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[63][]" id="checkbox63_3" value="NA" class="large-checkbox" <?php echo isChecked(63, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[63]" value="<?php echo getRemark(63, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			
			<tr>
                <td><strong>2.52</strong></td>
                <td><strong>Rope reeving is in compliance with manufacturer's recommendations </strong></td>
				<td style="text-align: center;"><strong>ASME B30.8, 
Sec.8-2.1.2 (a7) 
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[64][]" id="checkbox64_1" value="PASS" class="large-checkbox" <?php echo isChecked(64, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[64][]" id="checkbox64_2" value="FAIL" class="large-checkbox" <?php echo isChecked(64, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[64][]" id="checkbox64_3" value="NA" class="large-checkbox" <?php echo isChecked(64, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[64]" value="<?php echo getRemark(64, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>2.53</strong></td>
                <td><strong> Sheave sizes are not less than 18 times the nominal rope diameter for load hoist</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8
Sec.8-1.9.5 (b)
  </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[65][]" id="checkbox65_1" value="PASS" class="large-checkbox" <?php echo isChecked(65, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[65][]" id="checkbox65_2" value="FAIL" class="large-checkbox" <?php echo isChecked(65, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[65][]" id="checkbox65_3" value="NA" class="large-checkbox" <?php echo isChecked(65, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[65]" value="<?php echo getRemark(65, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>2.54</strong></td>
                <td><strong> Sheave sizes are not less than 16 times the nominal rope diameter for load blocks.</strong></td>
				<td style="text-align: center;"><strong> ASME B30.9
Sec.8-1.9.5 (c)
  </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[66][]" id="checkbox66_1" value="PASS" class="large-checkbox" <?php echo isChecked(66, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[66][]" id="checkbox66_2" value="FAIL" class="large-checkbox" <?php echo isChecked(66, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[66][]" id="checkbox66_3" value="NA" class="large-checkbox" <?php echo isChecked(66, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[66]" value="<?php echo getRemark(66, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>2.55</strong></td>
                <td><strong>Eye splices are in accordance with the manufacturer's recommendations and rope thimbles are used</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8
Sec.8-1.9.3 (a)
  </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[67][]" id="checkbox67_1" value="PASS" class="large-checkbox" <?php echo isChecked(67, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[67][]" id="checkbox67_2" value="FAIL" class="large-checkbox" <?php echo isChecked(67, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[67][]" id="checkbox67_3" value="NA" class="large-checkbox" <?php echo isChecked(67, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[67]" value="<?php echo getRemark(67, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>2.56</strong></td>
                <td><strong>Brakes and clutches are provided with adjustments to compensate for wear</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8
Sec.8-1.6.1 (b)
</strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[68][]" id="checkbox68_1" value="PASS" class="large-checkbox" <?php echo isChecked(68, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[68][]" id="checkbox68_2" value="FAIL" class="large-checkbox" <?php echo isChecked(68, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[68][]" id="checkbox68_3" value="NA" class="large-checkbox" <?php echo isChecked(68, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[68]" value="<?php echo getRemark(68, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>2.57</strong></td>
                <td><strong>Boom hoist drum is provided with an auxiliary a locking device controllable from the operator’s station to hold the drum from rotating in the lowering direction and to hold the rated load</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8
Sec.8-1.6.1 (f)
</strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[69][]" id="checkbox69_1" value="PASS" class="large-checkbox" <?php echo isChecked(69, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[69][]" id="checkbox69_2" value="FAIL" class="large-checkbox" <?php echo isChecked(69, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[69][]" id="checkbox69_3" value="NA" class="large-checkbox" <?php echo isChecked(69, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[69]" value="<?php echo getRemark(69, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			
      <tr>
                <td><strong>2.58</strong></td>
                <td><strong>Boom hoist drum has at least 2 full wraps remaining on the drum when the boom is at lowest position</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8
Sec.8-1.6.1 (g-1)
</strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[70][]" id="checkbox70_1" value="PASS" class="large-checkbox" <?php echo isChecked(70, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[70][]" id="checkbox70_2" value="FAIL" class="large-checkbox" <?php echo isChecked(70, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[70][]" id="checkbox70_3" value="NA" class="large-checkbox" <?php echo isChecked(70, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[70]" value="<?php echo getRemark(70, $saved_remarks); ?>" class="form-control">
</td>
            </tr>  
 <tr>
                <td><strong>2.59</strong></td>
                <td><strong>The load hoist mechanism is provided with a suitable clutching or power engaging device, unless directly coupled</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8
Sec.8-1.6.2
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[71][]" id="checkbox71_1" value="PASS" class="large-checkbox" <?php echo isChecked(71, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[71][]" id="checkbox71_2" value="FAIL" class="large-checkbox" <?php echo isChecked(71, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[71][]" id="checkbox71_3" value="NA" class="large-checkbox" <?php echo isChecked(71, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[71]" value="<?php echo getRemark(71, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			
			<tr>
                <td><strong>2.60</strong></td>
                <td><strong>Over hoist limit devices are operable</strong></td>
				<td style="text-align: center;"><strong> ASME B30.8
Sec.8-2.2.1 (a)
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[72][]" id="checkbox72_1" value="PASS" class="large-checkbox" <?php echo isChecked(72, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[72][]" id="checkbox72_2" value="FAIL" class="large-checkbox" <?php echo isChecked(72, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[72][]" id="checkbox72_3" value="NA" class="large-checkbox" <?php echo isChecked(72, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[72]" value="<?php echo getRemark(72, $saved_remarks); ?>" class="form-control">
</td>
            </tr>	
<tr>
                <td><strong>2.61</strong></td>
                <td><strong>All welded members and joints have no cracks, distortions, corrosion or other defects</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8
Sec.8-1.2.1 (c)
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[73][]" id="checkbox73_1" value="PASS" class="large-checkbox" <?php echo isChecked(73, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[73][]" id="checkbox73_2" value="FAIL" class="large-checkbox" <?php echo isChecked(73, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[73][]" id="checkbox73_3" value="NA" class="large-checkbox" <?php echo isChecked(73, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[73]" value="<?php echo getRemark(73, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			
			<tr>
                <td><strong>2.62</strong></td>
                <td><strong>Boom stops are provided and in good condition (one of the following; fixed or telescopic bumper, shock absorbing umber, hydraulic boom elevation cylinder, or derrick masts)</strong></td>
				<td style="text-align: center;"><strong> ASME B30.8
Sec.8-1.11 (a)
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[74][]" id="checkbox74_1" value="PASS" class="large-checkbox" <?php echo isChecked(74, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[74][]" id="checkbox74_2" value="FAIL" class="large-checkbox" <?php echo isChecked(74, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[74][]" id="checkbox74_3" value="NA" class="large-checkbox" <?php echo isChecked(74, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[74]" value="<?php echo getRemark(74, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>2.63</strong></td>
                <td><strong> Boom angle indicator is provided and readable from operator's cab</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8
Sec.8-1.11 (b)
  </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[75][]" id="checkbox75_1" value="PASS" class="large-checkbox" <?php echo isChecked(75, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[75][]" id="checkbox75_2" value="FAIL" class="large-checkbox" <?php echo isChecked(75, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[75][]" id="checkbox75_3" value="NA" class="large-checkbox" <?php echo isChecked(75, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[75]" value="<?php echo getRemark(75, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>2.64</strong></td>
                <td><strong>Boom hoist disconnect, shutoff, or hydraulic relief is provided to stop the boom hoist automatically when the boom reaches a predetermined angle</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8
Sec.8-1.11.1 (d)
  </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[76][]" id="checkbox76_1" value="PASS" class="large-checkbox" <?php echo isChecked(76, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[76][]" id="checkbox76_2" value="FAIL" class="large-checkbox" <?php echo isChecked(76, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[76][]" id="checkbox76_3" value="NA" class="large-checkbox" <?php echo isChecked(76, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[76]" value="<?php echo getRemark(76, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>2.65</strong></td>
                <td><strong>Cords and lacings have no damage</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8
Sec.8-2.1.2. (a2)
  </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[77][]" id="checkbox77_1" value="PASS" class="large-checkbox" <?php echo isChecked(77, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[77][]" id="checkbox77_2" value="FAIL" class="large-checkbox" <?php echo isChecked(77, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[77][]" id="checkbox77_3" value="NA" class="large-checkbox" <?php echo isChecked(77, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[77]" value="<?php echo getRemark(77, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>2.66</strong></td>
                <td><strong>Guy ropes are correctly tensioned</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8
Sec.8-2.1.2. (a9)
</strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[78][]" id="checkbox78_1" value="PASS" class="large-checkbox" <?php echo isChecked(78, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[78][]" id="checkbox78_2" value="FAIL" class="large-checkbox" <?php echo isChecked(78, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[78][]" id="checkbox78_3" value="NA" class="large-checkbox" <?php echo isChecked(78, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[78]" value="<?php echo getRemark(78, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>2.67</strong></td>
                <td><strong>Derrick mast fittings and connections are in compliance with the manufacturer's </strong></td>
				<td style="text-align: center;"><strong>ASME B30.8
Sec.8-2.1.2. (a10)
</strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[79][]" id="checkbox79_1" value="PASS" class="large-checkbox" <?php echo isChecked(79, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[79][]" id="checkbox79_2" value="FAIL" class="large-checkbox" <?php echo isChecked(79, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[79][]" id="checkbox79_3" value="NA" class="large-checkbox" <?php echo isChecked(79, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[79]" value="<?php echo getRemark(79, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			
      <tr>
                <td><strong>2.68</strong></td>
                <td><strong>Clutch system parts, linings, pawls and ratchet are not excessively worn</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8
Sec.8-2.1.3 (a5)
</strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[80][]" id="checkbox80_1" value="PASS" class="large-checkbox" <?php echo isChecked(80, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[80][]" id="checkbox80_2" value="FAIL" class="large-checkbox" <?php echo isChecked(80, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[80][]" id="checkbox80_3" value="NA" class="large-checkbox" <?php echo isChecked(80, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[80]" value="<?php echo getRemark(80, $saved_remarks); ?>" class="form-control">
</td>
            </tr>  
 <tr>
                <td><strong>2.69</strong></td>
                <td><strong>Load, boom angle, and other indicators, over their full range, are accurately functioning</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8
Sec.8-2.1.3 (a6)
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[81][]" id="checkbox81_1" value="PASS" class="large-checkbox" <?php echo isChecked(81, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[81][]" id="checkbox81_2" value="FAIL" class="large-checkbox" <?php echo isChecked(81, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[81][]" id="checkbox81_3" value="NA" class="large-checkbox" <?php echo isChecked(81, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[81]" value="<?php echo getRemark(81, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			
			<tr>
                <td><strong>2.70</strong></td>
                <td><strong>Chain drive sprockets do not show excessive wear and is not stretched</strong></td>
				<td style="text-align: center;"><strong> ASME B30.8
Sec.8-2.1.3 (a8)
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[82][]" id="checkbox82_1" value="PASS" class="large-checkbox" <?php echo isChecked(82, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[82][]" id="checkbox82_2" value="FAIL" class="large-checkbox" <?php echo isChecked(82, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[82][]" id="checkbox82_3" value="NA" class="large-checkbox" <?php echo isChecked(82, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[82]" value="<?php echo getRemark(82, $saved_remarks); ?>" class="form-control">
</td>
            </tr>	
<tr>
                <td><strong>2.71</strong></td>
                <td><strong> Gudgeon pins do not have cracks, wear or distortion </strong></td>
				<td style="text-align: center;"><strong>ASME B30.8
Sec.8-2.1.3 (a11)
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[83][]" id="checkbox83_1" value="PASS" class="large-checkbox" <?php echo isChecked(83, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[83][]" id="checkbox83_2" value="FAIL" class="large-checkbox" <?php echo isChecked(83, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[83][]" id="checkbox83_3" value="NA" class="large-checkbox" <?php echo isChecked(83, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[83]" value="<?php echo getRemark(83, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			
			<tr>
                <td><strong>2.72</strong></td>
                <td><strong>Supports do not have any defects and have continued ability to sustain the imposed loads </strong></td>
				<td style="text-align: center;"><strong> ASME B30.8
Sec.8-2.1.3 (a12)
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[84][]" id="checkbox84_1" value="PASS" class="large-checkbox" <?php echo isChecked(84, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[84][]" id="checkbox84_2" value="FAIL" class="large-checkbox" <?php echo isChecked(84, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[84][]" id="checkbox84_3" value="NA" class="large-checkbox" <?php echo isChecked(84, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[84]" value="<?php echo getRemark(84, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>2.73</strong></td>
                <td><strong> Hydraulic and pneumatic hoses, fittings and tubes are not damaged and not leaking</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8
Sec.8-2.1.3 (a13)
  </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[85][]" id="checkbox85_1" value="PASS" class="large-checkbox" <?php echo isChecked(85, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[85][]" id="checkbox85_2" value="FAIL" class="large-checkbox" <?php echo isChecked(85, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[85][]" id="checkbox85_3" value="NA" class="large-checkbox" <?php echo isChecked(85, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[85]" value="<?php echo getRemark(85, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>2.74</strong></td>
                <td><strong> Hydraulic/Pneumatic pumps and motors are correctly functioning </strong></td>
				<td style="text-align: center;"><strong> ASME B30.8
Sec.8-2.1.3 (a14)
  </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[86][]" id="checkbox86_1" value="PASS" class="large-checkbox" <?php echo isChecked(86, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[86][]" id="checkbox86_2" value="FAIL" class="large-checkbox" <?php echo isChecked(86, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[86][]" id="checkbox86_3" value="NA" class="large-checkbox" <?php echo isChecked(86, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[86]" value="<?php echo getRemark(86, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>2.75</strong></td>
                <td><strong>Hydraulic/Pneumatic pumps and motors have no leaks, excess vibrations, unusual noises, loss of pressure or loss of speed</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8
Sec.8-2.1.3 (a14)
  </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[87][]" id="checkbox87_1" value="PASS" class="large-checkbox" <?php echo isChecked(87, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[87][]" id="checkbox87_2" value="FAIL" class="large-checkbox" <?php echo isChecked(87, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[87][]" id="checkbox87_3" value="NA" class="large-checkbox" <?php echo isChecked(87, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[87]" value="<?php echo getRemark(87, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>2.76</strong></td>
                <td><strong>Hydraulic/Pneumatic valves and motors are correctly functioning</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8
Sec.8-2.1.3 (a15)
</strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[88][]" id="checkbox88_1" value="PASS" class="large-checkbox" <?php echo isChecked(88, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[88][]" id="checkbox88_2" value="FAIL" class="large-checkbox" <?php echo isChecked(88, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[88][]" id="checkbox88_3" value="NA" class="large-checkbox" <?php echo isChecked(88, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[88]" value="<?php echo getRemark(88, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>2.77</strong></td>
                <td><strong>Hydraulic/Pneumatic valves and motors have no leaks, corrosion, excess vibrations, unusual noises or loss of pressure</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8
Sec.8-2.1.3 (a15)
</strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[89][]" id="checkbox89_1" value="PASS" class="large-checkbox" <?php echo isChecked(89, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[89][]" id="checkbox89_2" value="FAIL" class="large-checkbox" <?php echo isChecked(89, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[89][]" id="checkbox89_3" value="NA" class="large-checkbox" <?php echo isChecked(89, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[89]" value="<?php echo getRemark(89, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			
      <tr>
                <td><strong>2.78</strong></td>
                <td><strong>Hydraulic/ Pneumatic cylinders do not leak (external and internal) at seals and welded joints or any other locations</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8
Sec.8-2.1.3 (a16)
</strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[90][]" id="checkbox90_1" value="PASS" class="large-checkbox" <?php echo isChecked(90, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[90][]" id="checkbox90_2" value="FAIL" class="large-checkbox" <?php echo isChecked(90, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[90][]" id="checkbox90_3" value="NA" class="large-checkbox" <?php echo isChecked(90, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[90]" value="<?php echo getRemark(90, $saved_remarks); ?>" class="form-control">
</td>
            </tr>  
 <tr>
                <td><strong>2.79</strong></td>
                <td><strong>Hydraulic/ Pneumatic cylinders are free of scores, dents or nicks on piston rods or cylinder barrels and loose/deformed rod eyes or connecting joints</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8
Sec.8-2.1.3 (a16)
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[91][]" id="checkbox91_1" value="PASS" class="large-checkbox" <?php echo isChecked(91, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[91][]" id="checkbox91_2" value="FAIL" class="large-checkbox" <?php echo isChecked(91, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[91][]" id="checkbox91_3" value="NA" class="large-checkbox" <?php echo isChecked(91, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[91]" value="<?php echo getRemark(91, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			
			<tr>
                <td><strong>2.80</strong></td>
                <td><strong>Hydraulic filters are clean and free from debris such as rubber or metal particles</strong></td>
				<td style="text-align: center;"><strong> ASME B30.8
Sec.8-2.1.3 (a17)
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[92][]" id="checkbox92_1" value="PASS" class="large-checkbox" <?php echo isChecked(92, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[92][]" id="checkbox92_2" value="FAIL" class="large-checkbox" <?php echo isChecked(92, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[92][]" id="checkbox92_3" value="NA" class="large-checkbox" <?php echo isChecked(92, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[92]" value="<?php echo getRemark(92, $saved_remarks); ?>" class="form-control">
</td>
            </tr>	
<tr>
                <td><strong>2.81</strong></td>
                <td><strong>Boom cradle is available and can secure the boom if required</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8
Sec.8-3.2.6 (b)
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[93][]" id="checkbox93_1" value="PASS" class="large-checkbox" <?php echo isChecked(93, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[93][]" id="checkbox93_2" value="FAIL" class="large-checkbox" <?php echo isChecked(93, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[93][]" id="checkbox93_3" value="NA" class="large-checkbox" <?php echo isChecked(93, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[93]" value="<?php echo getRemark(93, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			
			<tr>
                <td><strong>2.82</strong></td>
                <td><strong>Pins, bearings, shafts, gears, rollers, and locking devices are free of wear, cracks, and distortion</strong></td>
				<td style="text-align: center;"><strong> ASME B30.8
Sec.8-2.1.3 (a4)
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[94][]" id="checkbox94_1" value="PASS" class="large-checkbox" <?php echo isChecked(94, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[94][]" id="checkbox94_2" value="FAIL" class="large-checkbox" <?php echo isChecked(94, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[94][]" id="checkbox94_3" value="NA" class="large-checkbox" <?php echo isChecked(94, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[94]" value="<?php echo getRemark(94, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>2.83</strong></td>
                <td><strong> The swing mechanism is controlling the swing of the rated load under all operating conditions</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8
Sec.8-1.7.1
  </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[95][]" id="checkbox95_1" value="PASS" class="large-checkbox" <?php echo isChecked(95, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[95][]" id="checkbox95_2" value="FAIL" class="large-checkbox" <?php echo isChecked(95, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[95][]" id="checkbox95_3" value="NA" class="large-checkbox" <?php echo isChecked(95, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[95]" value="<?php echo getRemark(95, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>2.84</strong></td>
                <td><strong> Swing braking operates and capable of restrict the movement of the rotating structure </strong></td>
				<td style="text-align: center;"><strong> ASME B30.8
Sec.8-1.7.2 (a)
  </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[96][]" id="checkbox96_1" value="PASS" class="large-checkbox" <?php echo isChecked(96, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[96][]" id="checkbox96_2" value="FAIL" class="large-checkbox" <?php echo isChecked(96, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[96][]" id="checkbox96_3" value="NA" class="large-checkbox" <?php echo isChecked(96, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[96]" value="<?php echo getRemark(96, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>2.85</strong></td>
                <td><strong>Swing brake locking device is provided and capable of locking the rotation of rotating structure</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8
Sec.8-1.7.2 (b)
  </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[97][]" id="checkbox97_1" value="PASS" class="large-checkbox" <?php echo isChecked(97, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[97][]" id="checkbox97_2" value="FAIL" class="large-checkbox" <?php echo isChecked(97, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[97][]" id="checkbox97_3" value="NA" class="large-checkbox" <?php echo isChecked(97, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[97]" value="<?php echo getRemark(97, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>2.86</strong></td>
                <td><strong>Swing brakes are adjustable to compensate for wear</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8
Sec.8-1.7.2 (d)
</strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[98][]" id="checkbox98_1" value="PASS" class="large-checkbox" <?php echo isChecked(98, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[98][]" id="checkbox98_2" value="FAIL" class="large-checkbox" <?php echo isChecked(98, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[98][]" id="checkbox98_3" value="NA" class="large-checkbox" <?php echo isChecked(98, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[98]" value="<?php echo getRemark(98, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>2.87</strong></td>
                <td><strong>All swing moving parts are lubricated </strong></td>
				<td style="text-align: center;"><strong>ASME B30.8
Sec.8-2.3.4
</strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[99][]" id="checkbox99_1" value="PASS" class="large-checkbox" <?php echo isChecked(99, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[99][]" id="checkbox99_2" value="FAIL" class="large-checkbox" <?php echo isChecked(99, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[99][]" id="checkbox99_3" value="NA" class="large-checkbox" <?php echo isChecked(99, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[99]" value="<?php echo getRemark(99, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			
      <tr>
                <td><strong>2.88</strong></td>
                <td><strong>Exhaust pipes are insulated and gases are properly discharged away from the operator's cab</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8
Sec.8-1.10.5
</strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[100][]" id="checkbox100_1" value="PASS" class="large-checkbox" <?php echo isChecked(100, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[100][]" id="checkbox100_2" value="FAIL" class="large-checkbox" <?php echo isChecked(100, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[100][]" id="checkbox100_3" value="NA" class="large-checkbox" <?php echo isChecked(100, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[100]" value="<?php echo getRemark(100, $saved_remarks); ?>" class="form-control">
</td>
            </tr>  
 <tr>
                <td><strong>2.89</strong></td>
                <td><strong>A portable fire extinguisher is in place inside cab and outside machinery (minimum rating of 10 BC)</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8
Sec.8-1.4.3 (a)
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[101][]" id="checkbox101_1" value="PASS" class="large-checkbox" <?php echo isChecked(101, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[101][]" id="checkbox101_2" value="FAIL" class="large-checkbox" <?php echo isChecked(101, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[101][]" id="checkbox101_3" value="NA" class="large-checkbox" <?php echo isChecked(101, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[101]" value="<?php echo getRemark(101, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			
			<tr>
                <td><strong>2.90</strong></td>
                <td><strong>A tool box with basic maintenance tools is placed inside the cab</strong></td>
				<td style="text-align: center;"><strong> ASME B30.8
Sec.8-1.4.3 (b)
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[102][]" id="checkbox102_1" value="PASS" class="large-checkbox" <?php echo isChecked(102, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[102][]" id="checkbox102_2" value="FAIL" class="large-checkbox" <?php echo isChecked(102, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[102][]" id="checkbox102_3" value="NA" class="large-checkbox" <?php echo isChecked(102, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[102]" value="<?php echo getRemark(102, $saved_remarks); ?>" class="form-control">
</td>
            </tr>	
<tr>
                <td><strong>2.91</strong></td>
                <td><strong>An audible warning device is in place and within reach of the operator</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8
Sec.8-1.4.3 (c)
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[103][]" id="checkbox103_1" value="PASS" class="large-checkbox" <?php echo isChecked(103, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[103][]" id="checkbox103_2" value="FAIL" class="large-checkbox" <?php echo isChecked(103, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[103][]" id="checkbox103_3" value="NA" class="large-checkbox" <?php echo isChecked(103, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[103]" value="<?php echo getRemark(103, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			
			<tr>
                <td><strong>2.92</strong></td>
                <td><strong>The load moment indicator (LMI) is installed and properly working </strong></td>
				<td style="text-align: center;"><strong> ASME B30.8
Sec.8-1.4.3 (f)
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[104][]" id="checkbox104_1" value="PASS" class="large-checkbox" <?php echo isChecked(104, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[104][]" id="checkbox104_2" value="FAIL" class="large-checkbox" <?php echo isChecked(104, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[104][]" id="checkbox104_3" value="NA" class="large-checkbox" <?php echo isChecked(104, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[104]" value="<?php echo getRemark(104, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>2.93</strong></td>
                <td><strong> The cab and operating enclosures have good housekeeping</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8
Sec.8-3.4.4 (b)
  </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[105][]" id="checkbox105_1" value="PASS" class="large-checkbox" <?php echo isChecked(105, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[105][]" id="checkbox105_2" value="FAIL" class="large-checkbox" <?php echo isChecked(105, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[105][]" id="checkbox105_3" value="NA" class="large-checkbox" <?php echo isChecked(105, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[105]" value="<?php echo getRemark(105, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>2.94</strong></td>
                <td><strong> Control levers and/or pedals functions are labeled </strong></td>
				<td style="text-align: center;"><strong> ASME B30.4
Sec.4- 1.13.1(a)   
ASME B30.3 Sec.3.1.15.1(b)
  </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[106][]" id="checkbox106_1" value="PASS" class="large-checkbox" <?php echo isChecked(106, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[106][]" id="checkbox106_2" value="FAIL" class="large-checkbox" <?php echo isChecked(106, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[106][]" id="checkbox106_3" value="NA" class="large-checkbox" <?php echo isChecked(106, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[106]" value="<?php echo getRemark(106, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>2.95</strong></td>
                <td><strong>Engine exhaust gases are piped and discharged away from the operator cab</strong></td>
				<td style="text-align: center;"><strong>ASME B30.4
Sec.4-1.16.4
ASME B30.3  Sec.3.1.18.4
  </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[107][]" id="checkbox107_1" value="PASS" class="large-checkbox" <?php echo isChecked(107, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[107][]" id="checkbox107_2" value="FAIL" class="large-checkbox" <?php echo isChecked(107, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[107][]" id="checkbox107_3" value="NA" class="large-checkbox" <?php echo isChecked(107, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[107]" value="<?php echo getRemark(107, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>2.96</strong></td>
                <td><strong>Operator cab has a safety glazing glass</strong></td>
				<td style="text-align: center;"><strong>ASME B30.4	
Sec.4- 1.15.1(e) ASME B30.3 Sec.3.1.17.1(e)
</strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[108][]" id="checkbox108_1" value="PASS" class="large-checkbox" <?php echo isChecked(108, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[108][]" id="checkbox108_2" value="FAIL" class="large-checkbox" <?php echo isChecked(108, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[108][]" id="checkbox108_3" value="NA" class="large-checkbox" <?php echo isChecked(108, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[108]" value="<?php echo getRemark(108, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>2.97</strong></td>
                <td><strong>Cab lighting (artificial or natural) is sufficient to enable the operator to observe the controls </strong></td>
				<td style="text-align: center;"><strong>ASME B30.
Sec.4- 1.15.1(g)
ASME B30.3 Sec.3.1.17.1(g)
</strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[109][]" id="checkbox109_1" value="PASS" class="large-checkbox" <?php echo isChecked(109, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[109][]" id="checkbox109_2" value="FAIL" class="large-checkbox" <?php echo isChecked(109, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[109][]" id="checkbox109_3" value="NA" class="large-checkbox" <?php echo isChecked(109, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[109]" value="<?php echo getRemark(109, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			
      <tr>
                <td><strong>2.98</strong></td>
                <td><strong>Emergency stop is effective (when there is a remote-control device malfunction)</strong></td>
				<td style="text-align: center;"><strong>ASME B30.4
Sec.4- 1.13.1(f)   
ASME B30.3 Sec.3.1.15.1(g)
</strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[110][]" id="checkbox110_1" value="PASS" class="large-checkbox" <?php echo isChecked(110, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[110][]" id="checkbox110_2" value="FAIL" class="large-checkbox" <?php echo isChecked(110, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[110][]" id="checkbox110_3" value="NA" class="large-checkbox" <?php echo isChecked(110, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[110]" value="<?php echo getRemark(110, $saved_remarks); ?>" class="form-control">
</td>
            </tr>  
 <tr>
                <td><strong>2.99</strong></td>
                <td><strong>Simultaneous activation of controls is not possible when more than one operator station (remote control) is provided</strong></td>
				<td style="text-align: center;"><strong>ASME B30.4
Sec.4.1.13.1(g)
ASME B30.3 Sec.3.1.15.1(h)
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[111][]" id="checkbox111_1" value="PASS" class="large-checkbox" <?php echo isChecked(111, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[111][]" id="checkbox111_2" value="FAIL" class="large-checkbox" <?php echo isChecked(111, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[111][]" id="checkbox111_3" value="NA" class="large-checkbox" <?php echo isChecked(111, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[111]" value="<?php echo getRemark(111, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
		<tr>
                    <th style="text-align: center;">3</th>
                    <th style="text-align: center;">INSPECTION POINTS</th>
					<th style="text-align: center;"> </th>
                    
                    <th style="text-align: center;">PASS</th>
                    <th style="text-align: center;">FAIL</th>
                    <th style="text-align: center;">NA</th>
                    <th> </th>
                </tr>
<tr>
                <td><strong>3.0</strong></td>
                <td><strong>Pilot lights at boom tip, jib tip and topmost sheave are working and in good condition</strong></td>
				<td style="text-align: center;"><strong>ASME B30.4 Sec.4.2.1.4(a8)
 ASME B30.3 Sec.3.2.1.4(a8)
  </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[112][]" id="checkbox112_1" value="PASS" class="large-checkbox" <?php echo isChecked(112, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[112][]" id="checkbox112_2" value="FAIL" class="large-checkbox" <?php echo isChecked(112, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[112][]" id="checkbox112_3" value="NA" class="large-checkbox" <?php echo isChecked(112, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[112]" value="<?php echo getRemark(112, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
 <tr>
                <td><strong>3.1</strong></td>
                <td><strong> Remaining rope on the drum (load hoist and/or boom hoist) in the extreme low position is at least 2 full wraps </strong></td>
				<td style="text-align: center;"><strong>ASME B30.4
Sec. 4- 1.5.2 (b1)
ASME B30.3 Sec.3.1.5.2 (a)
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[113][]" id="checkbox113_1" value="PASS" class="large-checkbox" <?php echo isChecked(113, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[113][]" id="checkbox113_2" value="FAIL" class="large-checkbox" <?php echo isChecked(113, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[113][]" id="checkbox113_3" value="NA" class="large-checkbox" <?php echo isChecked(113, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[113]" value="<?php echo getRemark(113, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			
			<tr>
                <td><strong>3.2</strong></td>
                <td><strong>The drum end of the rope is attached to the drum as per manufacturer recommendations </strong></td>
				<td style="text-align: center;"><strong>ASME B30.4
Sec.4- 1.5.2b2  
ASME B30.3 Sec.3.1.5.2b
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[114][]" id="checkbox114_1" value="PASS" class="large-checkbox" <?php echo isChecked(114, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[114][]" id="checkbox114_2" value="FAIL" class="large-checkbox" <?php echo isChecked(114, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[114][]" id="checkbox114_3" value="NA" class="large-checkbox" <?php echo isChecked(114, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[114]" value="<?php echo getRemark(114, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>3.3</strong></td>
                <td><strong> Hoist brakes are working and can provide 125% or more of the full load hoisting torque and can provide controlled lowering speeds</strong></td>
				<td style="text-align: center;"><strong>ASME B30.4
Sec.4 1.4.3(a)
ASME B30.3 Sec.3.1.5.3(b)
  </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[115][]" id="checkbox115_1" value="PASS" class="large-checkbox" <?php echo isChecked(115, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[115][]" id="checkbox115_2" value="FAIL" class="large-checkbox" <?php echo isChecked(115, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[115][]" id="checkbox115_3" value="NA" class="large-checkbox" <?php echo isChecked(115, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[115]" value="<?php echo getRemark(115, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>3.4</strong></td>
                <td><strong> Relief valves in the hydraulic pneumatic circuits limit the circuit pressure for the correct rated load conditions</strong></td>
				<td style="text-align: center;"><strong>ASME B30.4
Sec.4- 1.16.8(a)   
ASME B30.3 3.1.18.9a)
  </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[116][]" id="checkbox116_1" value="PASS" class="large-checkbox" <?php echo isChecked(116, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[116][]" id="checkbox116_2" value="FAIL" class="large-checkbox" <?php echo isChecked(116, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[116][]" id="checkbox116_3" value="NA" class="large-checkbox" <?php echo isChecked(116, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[116]" value="<?php echo getRemark(116, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>3.5</strong></td>
                <td><strong>Rope is free of damages
•	Max of 12 randomly broken wires in 1 lay
•	4 broken wires in 1 strand of 1 lay
•	1 broken wire protruding from the core (2 for rotation resistant ropes)
•	Wear of 1/3 of the original diameter of outside individual wires
Kinking, crushing, bird caging or other distortion
</strong></td>
				<td style="text-align: center;"><strong>ASME B30.4
Sec.4- 2.4.2a2a
ASME B30.3
Sec.3.2.4.2a2a
  </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[117][]" id="checkbox117_1" value="PASS" class="large-checkbox" <?php echo isChecked(117, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[117][]" id="checkbox117_2" value="FAIL" class="large-checkbox" <?php echo isChecked(117, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[117][]" id="checkbox117_3" value="NA" class="large-checkbox" <?php echo isChecked(117, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[117]" value="<?php echo getRemark(117, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>3.6</strong></td>
                <td><strong>Sheaves are smooth in their grooves and well lubricated</strong></td>
				<td style="text-align: center;"><strong>ASME B30.4
Sec.41.4.4(a)
ASME B30.3 Se.3.1.5.4(a)
</strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[118][]" id="checkbox118_1" value="PASS" class="large-checkbox" <?php echo isChecked(118, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[118][]" id="checkbox118_2" value="FAIL" class="large-checkbox" <?php echo isChecked(118, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[118][]" id="checkbox118_3" value="NA" class="large-checkbox" <?php echo isChecked(118, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[118]" value="<?php echo getRemark(118, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>3.7</strong></td>
                <td><strong>Sheave are fitted with close fittings guards when liable to unload momentarily </strong></td>
				<td style="text-align: center;"><strong>ASME B30.4
Sec.4-1.4.4b  
ASME B30.3 Sec.3.1.5.4b
</strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[119][]" id="checkbox119_1" value="PASS" class="large-checkbox" <?php echo isChecked(119, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[119][]" id="checkbox119_2" value="FAIL" class="large-checkbox" <?php echo isChecked(119, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[119][]" id="checkbox119_3" value="NA" class="large-checkbox" <?php echo isChecked(119, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[119]" value="<?php echo getRemark(119, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			
      <tr>
                <td><strong>3.8</strong></td>
                <td><strong>Lower load block sheaves have close fitting guards</strong></td>
				<td style="text-align: center;"><strong>ASME B30.4
Sec.4-1.4.4e  
ASME B30.3 Sec.3.1.5.4e
</strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[120][]" id="checkbox120_1" value="PASS" class="large-checkbox" <?php echo isChecked(120, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[120][]" id="checkbox120_2" value="FAIL" class="large-checkbox" <?php echo isChecked(120, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[120][]" id="checkbox120_3" value="NA" class="large-checkbox" <?php echo isChecked(120, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[120]" value="<?php echo getRemark(120, $saved_remarks); ?>" class="form-control">
</td>
            </tr>  
 <tr>
                <td><strong>3.9</strong></td>
                <td><strong>Over hoist limit (anti-2-Block) device is operational</strong></td>
				<td style="text-align: center;"><strong>ASME B30.4	
Sec.4-1.9.1	
ASME B30.3 Sec.3.1.5.1f/3.1.11.1b
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[121][]" id="checkbox121_1" value="PASS" class="large-checkbox" <?php echo isChecked(121, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[121][]" id="checkbox121_2" value="FAIL" class="large-checkbox" <?php echo isChecked(121, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[121][]" id="checkbox121_3" value="NA" class="large-checkbox" <?php echo isChecked(121, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[121]" value="<?php echo getRemark(121, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			
			<tr>
                <td><strong>3.10</strong></td>
                <td><strong>Lower over travel limiting devices are fitted where hook is in areas not visible to the operators</strong></td>
				<td style="text-align: center;"><strong>ASME B30.4
Sec.4-1.9.2
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[122][]" id="checkbox122_1" value="PASS" class="large-checkbox" <?php echo isChecked(122, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[122][]" id="checkbox122_2" value="FAIL" class="large-checkbox" <?php echo isChecked(122, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[122][]" id="checkbox122_3" value="NA" class="large-checkbox" <?php echo isChecked(122, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[122]" value="<?php echo getRemark(122, $saved_remarks); ?>" class="form-control">
</td>
            </tr>	
<tr>
                <td><strong>3.11</strong></td>
                <td><strong>Standing ropes are not fiber core or rotation resistant ropes</strong></td>
				<td style="text-align: center;"><strong>ASME B30.4
Sec.4-1.10.1
ASME B30.3
Sec.3.1.12.1
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[123][]" id="checkbox123_1" value="PASS" class="large-checkbox" <?php echo isChecked(123, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[123][]" id="checkbox123_2" value="FAIL" class="large-checkbox" <?php echo isChecked(123, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[123][]" id="checkbox123_3" value="NA" class="large-checkbox" <?php echo isChecked(123, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[123]" value="<?php echo getRemark(123, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			
			<tr>
                <td><strong>3.12</strong></td>
                <td><strong>Sagged or poured sockets (pendant ropes) are in good condition</strong></td>
				<td style="text-align: center;"><strong> ASME B30.4
Sec.4-1.10.5
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[124][]" id="checkbox124_1" value="PASS" class="large-checkbox" <?php echo isChecked(124, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[124][]" id="checkbox124_2" value="FAIL" class="large-checkbox" <?php echo isChecked(124, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[124][]" id="checkbox124_3" value="NA" class="large-checkbox" <?php echo isChecked(124, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[124]" value="<?php echo getRemark(124, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>3.13</strong></td>
                <td><strong> Welded members/Joints are free of defects (cracks, corrosion, etc.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.4
Sec.4-2.3.4
ASME B30.3
Sec.3.1.18.5
  </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[125][]" id="checkbox125_1" value="PASS" class="large-checkbox" <?php echo isChecked(125, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[125][]" id="checkbox125_2" value="FAIL" class="large-checkbox" <?php echo isChecked(125, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[125][]" id="checkbox125_3" value="NA" class="large-checkbox" <?php echo isChecked(125, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[125]" value="<?php echo getRemark(125, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>3.14</strong></td>
                <td><strong> Boom is free of damage/deformation (cracks, corrosion, dents, etc. </strong></td>
				<td style="text-align: center;"><strong> ASME B30.4
Sec.4- 2.1.4(a1)   ASME B30.3
Sec.3.2.1.4(1)
  </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[126][]" id="checkbox126_1" value="PASS" class="large-checkbox" <?php echo isChecked(126, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[126][]" id="checkbox126_2" value="FAIL" class="large-checkbox" <?php echo isChecked(126, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[126][]" id="checkbox126_3" value="NA" class="large-checkbox" <?php echo isChecked(126, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[126]" value="<?php echo getRemark(126, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>3.15</strong></td>
                <td><strong>Boom stops are in good working condition (fixed, telescopic, shock absorbing or hydraulic)</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
Sec.3-1.5.1(f)
  </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[127][]" id="checkbox127_1" value="PASS" class="large-checkbox" <?php echo isChecked(127, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[127][]" id="checkbox127_2" value="FAIL" class="large-checkbox" <?php echo isChecked(127, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[127][]" id="checkbox127_3" value="NA" class="large-checkbox" <?php echo isChecked(127, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[127]" value="<?php echo getRemark(127, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>3.16</strong></td>
                <td><strong>Luffing boom brake can provide 125% or more of the full load torque</strong></td>
				<td style="text-align: center;"><strong>ASME B30.4
Sec.4- 1.5.1(c)   ASME B30.3 Sec.3.15.3(a)
</strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[128][]" id="checkbox128_1" value="PASS" class="large-checkbox" <?php echo isChecked(128, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[128][]" id="checkbox128_2" value="FAIL" class="large-checkbox" <?php echo isChecked(128, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[128][]" id="checkbox128_3" value="NA" class="large-checkbox" <?php echo isChecked(128, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[128]" value="<?php echo getRemark(128, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>3.17</strong></td>
                <td><strong>Wind velocity measuring device (Anemometer) is rotating freely and sending a signal to the display in the cab</strong></td>
				<td style="text-align: center;"><strong>ASME B30.4
Sec.4-1.16.6
ASME B30.3
Sec.3.1.18.7
</strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[129][]" id="checkbox129_1" value="PASS" class="large-checkbox" <?php echo isChecked(129, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[129][]" id="checkbox129_2" value="FAIL" class="large-checkbox" <?php echo isChecked(129, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[129][]" id="checkbox129_3" value="NA" class="large-checkbox" <?php echo isChecked(129, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[129]" value="<?php echo getRemark(129, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			
      <tr>
                <td><strong>3.18</strong></td>
                <td><strong>Stabilizers are undamaged and secure </strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
Sec.3- 1.3.3(a)
</strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[130][]" id="checkbox130_1" value="PASS" class="large-checkbox" <?php echo isChecked(130, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[130][]" id="checkbox130_2" value="FAIL" class="large-checkbox" <?php echo isChecked(130, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[130][]" id="checkbox130_3" value="NA" class="large-checkbox" <?php echo isChecked(130, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[130]" value="<?php echo getRemark(130, $saved_remarks); ?>" class="form-control">
</td>
            </tr>  
 <tr>
                <td><strong>3.19</strong></td>
                <td><strong>Travel rails are level and secure</strong></td>
				<td style="text-align: center;"><strong>AASME B30.4
Sec.4- 1.1.1(c)
ASME B30.3   Sec.3.1.1.1(c)
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[131][]" id="checkbox131_1" value="PASS" class="large-checkbox" <?php echo isChecked(131, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[131][]" id="checkbox131_2" value="FAIL" class="large-checkbox" <?php echo isChecked(131, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[131][]" id="checkbox131_3" value="NA" class="large-checkbox" <?php echo isChecked(131, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[131]" value="<?php echo getRemark(131, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			
			<tr>
                <td><strong>3.20</strong></td>
                <td><strong>Rails are grounded (for electrically powered cranes)</strong></td>
				<td style="text-align: center;"><strong> ASME B30.4
Sec. 4- 1.1.1(f)
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[132][]" id="checkbox132_1" value="PASS" class="large-checkbox" <?php echo isChecked(132, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[132][]" id="checkbox132_2" value="FAIL" class="large-checkbox" <?php echo isChecked(132, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[132][]" id="checkbox132_3" value="NA" class="large-checkbox" <?php echo isChecked(132, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[132]" value="<?php echo getRemark(132, $saved_remarks); ?>" class="form-control">
</td>
            </tr>	
<tr>
                <td><strong>3.21</strong></td>
                <td><strong>Stops or buffers are correctly adjusted for simultaneous contact with both sides of the travel base (stops to be fitted not less that 1 meter inboard of last rail support) </strong></td>
				<td style="text-align: center;"><strong>ASME B30.4
Sec.4- 1.1.1(g)
ASME B30.3 Sec.3.1.1.3(i)
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[133][]" id="checkbox133_1" value="PASS" class="large-checkbox" <?php echo isChecked(133, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[133][]" id="checkbox133_2" value="FAIL" class="large-checkbox" <?php echo isChecked(133, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[133][]" id="checkbox133_3" value="NA" class="large-checkbox" <?php echo isChecked(133, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[133]" value="<?php echo getRemark(133, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			
			<tr>
                <td><strong>3.22</strong></td>
                <td><strong>Rail sweeps are in good condition</strong></td>
				<td style="text-align: center;"><strong>ASME B30.4	
Sec.4- 1.7.2(a) 
ASME B30.3   Sec.3.1.7.29(a)
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[134][]" id="checkbox134_1" value="PASS" class="large-checkbox" <?php echo isChecked(134, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[134][]" id="checkbox134_2" value="FAIL" class="large-checkbox" <?php echo isChecked(134, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[134][]" id="checkbox134_3" value="NA" class="large-checkbox" <?php echo isChecked(134, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[134]" value="<?php echo getRemark(134, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>3.23</strong></td>
                <td><strong> Means to limit drop of a travel truck are in place and in good condition (in case of axle or wheel failure)</strong></td>
				<td style="text-align: center;"><strong>ASME B30.4
Sec.4- 1.7.2(c)
ASME B30.3 Sec.3.1.7.2(c)
  </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[135][]" id="checkbox135_1" value="PASS" class="large-checkbox" <?php echo isChecked(135, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[135][]" id="checkbox135_2" value="FAIL" class="large-checkbox" <?php echo isChecked(135, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[135][]" id="checkbox135_3" value="NA" class="large-checkbox" <?php echo isChecked(135, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[135]" value="<?php echo getRemark(135, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>3.24</strong></td>
                <td><strong> Truck wheels are guarded</strong></td>
				<td style="text-align: center;"><strong> ASME B30.4
Sec.4- 1.7.2(b)
ASME B30.3 Sec.3.1.7.2(b)
  </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[136][]" id="checkbox136_1" value="PASS" class="large-checkbox" <?php echo isChecked(136, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[136][]" id="checkbox136_2" value="FAIL" class="large-checkbox" <?php echo isChecked(136, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[136][]" id="checkbox136_3" value="NA" class="large-checkbox" <?php echo isChecked(136, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[136]" value="<?php echo getRemark(136, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>3.25</strong></td>
                <td><strong>Travel brakes can lock the wheels
</strong></td>
				<td style="text-align: center;"><strong>ASME B30.4
Sec.4- 1.7.3(a)
 ASME B30.3 Sec.3.1.7.3(a)
  </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[137][]" id="checkbox137_1" value="PASS" class="large-checkbox" <?php echo isChecked(137, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[137][]" id="checkbox137_2" value="FAIL" class="large-checkbox" <?php echo isChecked(137, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[137][]" id="checkbox137_3" value="NA" class="large-checkbox" <?php echo isChecked(137, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[137]" value="<?php echo getRemark(137, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>3.26</strong></td>
                <td><strong>Stairs/access ladders are secure and in good condition</strong></td>
				<td style="text-align: center;"><strong>ASME B30.4
Sec.4- 1.15.2(a) ASME B30.3 Sec.3.1.17.2(a)
</strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[138][]" id="checkbox138_1" value="PASS" class="large-checkbox" <?php echo isChecked(138, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[138][]" id="checkbox138_2" value="FAIL" class="large-checkbox" <?php echo isChecked(138, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[138][]" id="checkbox138_3" value="NA" class="large-checkbox" <?php echo isChecked(138, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[138]" value="<?php echo getRemark(138, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>3.27</strong></td>
                <td><strong>The machinery and electrical equipment are located clear of deck loading areas</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8
Sec.8-1.3.1 (a)
</strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[139][]" id="checkbox139_1" value="PASS" class="large-checkbox" <?php echo isChecked(139, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[139][]" id="checkbox139_2" value="FAIL" class="large-checkbox" <?php echo isChecked(139, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[139][]" id="checkbox139_3" value="NA" class="large-checkbox" <?php echo isChecked(139, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[139]" value="<?php echo getRemark(139, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			
      <tr>
                <td><strong>3.28</strong></td>
                <td><strong>Work areas, companion ways and ladders are equipped with anti-slip surface materials</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8
Sec.8-1.3.1 (b)
</strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[140][]" id="checkbox140_1" value="PASS" class="large-checkbox" <?php echo isChecked(140, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[140][]" id="checkbox140_2" value="FAIL" class="large-checkbox" <?php echo isChecked(140, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[140][]" id="checkbox140_3" value="NA" class="large-checkbox" <?php echo isChecked(140, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[140]" value="<?php echo getRemark(140, $saved_remarks); ?>" class="form-control">
</td>
            </tr>  
 <tr>
                <td><strong>3.29</strong></td>
                <td><strong>Electrical wiring and equipment are free of damages and of the specified type for shipboard</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8
Sec.8-1.3.1 (c)
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[141][]" id="checkbox141_1" value="PASS" class="large-checkbox" <?php echo isChecked(141, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[141][]" id="checkbox141_2" value="FAIL" class="large-checkbox" <?php echo isChecked(141, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[141][]" id="checkbox141_3" value="NA" class="large-checkbox" <?php echo isChecked(141, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[141]" value="<?php echo getRemark(141, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			
			<tr>
                <td><strong>3.30</strong></td>
                <td><strong>Manholes and hatches are not less than 15"x22"and raised above the deck to prevent accidental entry of spilled liquids</strong></td>
				<td style="text-align: center;"><strong>ASME B30.8
Sec.8-1.3.3
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[142][]" id="checkbox142_1" value="PASS" class="large-checkbox" <?php echo isChecked(142, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[142][]" id="checkbox142_2" value="FAIL" class="large-checkbox" <?php echo isChecked(142, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[142][]" id="checkbox142_3" value="NA" class="large-checkbox" <?php echo isChecked(142, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[142]" value="<?php echo getRemark(142, $saved_remarks); ?>" class="form-control">
</td>
            </tr>	
<tr>
                <td><strong>3.31</strong></td>
                <td><strong>Engine speed control is working satisfactorily </strong></td>
				<td style="text-align: center;"><strong>ASME B30.4 Sec.4.1.13.2(b2) 
ASME B30.3 Sec3.1.15.1(a2)
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[143][]" id="checkbox143_1" value="PASS" class="large-checkbox" <?php echo isChecked(143, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[143][]" id="checkbox143_2" value="FAIL" class="large-checkbox" <?php echo isChecked(143, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[143][]" id="checkbox143_3" value="NA" class="large-checkbox" <?php echo isChecked(143, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[143]" value="<?php echo getRemark(143, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			
			<tr>
                <td><strong>3.32</strong></td>
                <td><strong>Transmission selector can be operated satisfactorily </strong></td>
				<td style="text-align: center;"><strong>ASME B30.4 Sec.4.1.13.2(b4) 
ASME B30.3 Sec.3.1.15.1(a4
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[144][]" id="checkbox144_1" value="PASS" class="large-checkbox" <?php echo isChecked(144, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[144][]" id="checkbox144_2" value="FAIL" class="large-checkbox" <?php echo isChecked(144, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[144][]" id="checkbox144_3" value="NA" class="large-checkbox" <?php echo isChecked(144, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[144]" value="<?php echo getRemark(144, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>3.33</strong></td>
                <td><strong> Engine emergency stop switch is working properly</strong></td>
				<td style="text-align: center;"><strong>ASME B30.4 (4.1.13.2b3) 
ASME B30.3 Sec.3.1.15.1(a3)
  </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[145][]" id="checkbox145_1" value="PASS" class="large-checkbox" <?php echo isChecked(145, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[145][]" id="checkbox145_2" value="FAIL" class="large-checkbox" <?php echo isChecked(145, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[145][]" id="checkbox145_3" value="NA" class="large-checkbox" <?php echo isChecked(145, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[145]" value="<?php echo getRemark(145, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>3.34</strong></td>
                <td><strong> Pedals and hand levers are easily operated and well-functioning </strong></td>
				<td style="text-align: center;"><strong> ASME B30.4
Sec.4- 1.13.3(a) ASME B30.3 Sec.3.1.15.3(a)
  </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[146][]" id="checkbox146_1" value="PASS" class="large-checkbox" <?php echo isChecked(146, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[146][]" id="checkbox146_2" value="FAIL" class="large-checkbox" <?php echo isChecked(146, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[146][]" id="checkbox146_3" value="NA" class="large-checkbox" <?php echo isChecked(146, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[146]" value="<?php echo getRemark(146, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>3.35</strong></td>
                <td><strong>Travel distance extremes of hand levers and pedals are acceptable
•	14”-24” for hand levers
10” for pedals
</strong></td>
				<td style="text-align: center;"><strong>ASME B30.4	
Sec.4- 1.13.3(b)   
ASME B.30.3
Sec.3.1.15.3(b)
  </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[147][]" id="checkbox147_1" value="PASS" class="large-checkbox" <?php echo isChecked(147, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[147][]" id="checkbox147_2" value="FAIL" class="large-checkbox" <?php echo isChecked(147, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[147][]" id="checkbox147_3" value="NA" class="large-checkbox" <?php echo isChecked(147, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[147]" value="<?php echo getRemark(147, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>3.36</strong></td>
                <td><strong>Electrical crane has a main disconnect switch at or near the initial base of the crane
</strong></td>
				<td style="text-align: center;"><strong>ASME B30.4
Sec.4- 1.14.1(a) ASME B30.3 Sec.3.1.16.1(a)
</strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[148][]" id="checkbox148_1" value="PASS" class="large-checkbox" <?php echo isChecked(148, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[148][]" id="checkbox148_2" value="FAIL" class="large-checkbox" <?php echo isChecked(148, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[148][]" id="checkbox148_3" value="NA" class="large-checkbox" <?php echo isChecked(148, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[148]" value="<?php echo getRemark(148, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>3.37</strong></td>
                <td><strong>Electrical equipment is so located or guarded that live parts are not exposed to inadvertent contact under normal operating conditions </strong></td>
				<td style="text-align: center;"><strong>ASME B30.4
Sec.4- 1.14.1(b)  
 ASME B30.3 Sec.3.1.16.1(b)
</strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[149][]" id="checkbox149_1" value="PASS" class="large-checkbox" <?php echo isChecked(149, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[149][]" id="checkbox149_2" value="FAIL" class="large-checkbox" <?php echo isChecked(149, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[149][]" id="checkbox149_3" value="NA" class="large-checkbox" <?php echo isChecked(149, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[149]" value="<?php echo getRemark(149, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			
      <tr>
                <td><strong>3.38</strong></td>
                <td><strong>Electrical equipment is protected from dirt, grease, oil, moisture and other weather conditions</strong></td>
				<td style="text-align: center;"><strong>ASME B30.4
Sec.4- 1.14.1(c)   ASME B30.3 Sec.3.1.16.1(c)
</strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[150][]" id="checkbox150_1" value="PASS" class="large-checkbox" <?php echo isChecked(150, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[150][]" id="checkbox150_2" value="FAIL" class="large-checkbox" <?php echo isChecked(150, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[150][]" id="checkbox150_3" value="NA" class="large-checkbox" <?php echo isChecked(150, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[150]" value="<?php echo getRemark(150, $saved_remarks); ?>" class="form-control">
</td>
            </tr>  
 <tr>
                <td><strong>3.39</strong></td>
                <td><strong>An overload device is in place for each motor</strong></td>
				<td style="text-align: center;"><strong>ASME B30.4
Sec.4- 1.14.1(g) ASME B30.3 Sec.3.1.16.1(g)
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[151][]" id="checkbox151_1" value="PASS" class="large-checkbox" <?php echo isChecked(151, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[151][]" id="checkbox151_2" value="FAIL" class="large-checkbox" <?php echo isChecked(151, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[151][]" id="checkbox151_3" value="NA" class="large-checkbox" <?php echo isChecked(151, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[151]" value="<?php echo getRemark(151, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			
			<tr>
                <td><strong>3.40</strong></td>
                <td><strong>Lighting protection is in place</strong></td>
				<td style="text-align: center;"><strong>ASME B30.4
Sec.4- 1.14.1(h) ASME B30.3 Sec.3.1.16.1(h)
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[152][]" id="checkbox152_1" value="PASS" class="large-checkbox" <?php echo isChecked(152, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[152][]" id="checkbox152_2" value="FAIL" class="large-checkbox" <?php echo isChecked(152, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[152][]" id="checkbox152_3" value="NA" class="large-checkbox" <?php echo isChecked(152, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[152]" value="<?php echo getRemark(152, $saved_remarks); ?>" class="form-control">
</td>
            </tr>	
<tr>
                <td><strong>3.41</strong></td>
                <td><strong> Resistor units are supported to minimize vibrations</strong></td>
				<td style="text-align: center;"><strong>ASME B30.4
Sec.4- 1.14.2(b) ASME B30.3 Sec.3.1.16.2(a)
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[153][]" id="checkbox153_1" value="PASS" class="large-checkbox" <?php echo isChecked(153, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[153][]" id="checkbox153_2" value="FAIL" class="large-checkbox" <?php echo isChecked(153, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[153][]" id="checkbox153_3" value="NA" class="large-checkbox" <?php echo isChecked(153, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[153]" value="<?php echo getRemark(153, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			
			<tr>
                <td><strong>3.42</strong></td>
                <td><strong>Resistor parts are not suffering from corrosion</strong></td>
				<td style="text-align: center;"><strong>ASME B30.4
Sec.41.14.2(a)
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[154][]" id="checkbox154_1" value="PASS" class="large-checkbox" <?php echo isChecked(154, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[154][]" id="checkbox154_2" value="FAIL" class="large-checkbox" <?php echo isChecked(154, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[154][]" id="checkbox154_3" value="NA" class="large-checkbox" <?php echo isChecked(154, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[154]" value="<?php echo getRemark(154, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>3.43</strong></td>
                <td><strong>A separate circuit is in place for the lifting magnet alone (if magnet is used)</strong></td>
				<td style="text-align: center;"><strong>ASME B30.4
Sec.4- 1.9.3(a)
  </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[155][]" id="checkbox155_1" value="PASS" class="large-checkbox" <?php echo isChecked(155, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[155][]" id="checkbox155_2" value="FAIL" class="large-checkbox" <?php echo isChecked(155, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[155][]" id="checkbox155_3" value="NA" class="large-checkbox" <?php echo isChecked(155, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[155]" value="<?php echo getRemark(155, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>3.44</strong></td>
                <td><strong>An indication light is in place to magnet is energized (if magnet is used)</strong></td>
				<td style="text-align: center;"><strong>ASME B30.4
Sec.4- 1.9.3(c)
  </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[156][]" id="checkbox156_1" value="PASS" class="large-checkbox" <?php echo isChecked(156, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[156][]" id="checkbox156_2" value="FAIL" class="large-checkbox" <?php echo isChecked(156, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[156][]" id="checkbox156_3" value="NA" class="large-checkbox" <?php echo isChecked(156, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[156]" value="<?php echo getRemark(156, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>3.45</strong></td>
                <td><strong>Labeling and manufacturer data are available and legible</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10
(10-2.1.1)
  </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[157][]" id="checkbox157_1" value="PASS" class="large-checkbox" <?php echo isChecked(157, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[157][]" id="checkbox157_2" value="FAIL" class="large-checkbox" <?php echo isChecked(157, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[157][]" id="checkbox157_3" value="NA" class="large-checkbox" <?php echo isChecked(157, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[157]" value="<?php echo getRemark(157, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>3.46</strong></td>
                <td><strong>Hook's weight is clearly marked/printed on the hook</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10
(10-1.1.1)
</strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[158][]" id="checkbox158_1" value="PASS" class="large-checkbox" <?php echo isChecked(158, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[158][]" id="checkbox158_2" value="FAIL" class="large-checkbox" <?php echo isChecked(158, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[158][]" id="checkbox158_3" value="NA" class="large-checkbox" <?php echo isChecked(158, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[158]" value="<?php echo getRemark(158, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>3.47</strong></td>
                <td><strong>Safe working load is clearly marked on the hook</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10
(10-2.1.1)
</strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[159][]" id="checkbox159_1" value="PASS" class="large-checkbox" <?php echo isChecked(159, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[159][]" id="checkbox159_2" value="FAIL" class="large-checkbox" <?php echo isChecked(159, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[159][]" id="checkbox159_3" value="NA" class="large-checkbox" <?php echo isChecked(159, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[159]" value="<?php echo getRemark(159, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			
      <tr>
                <td><strong>3.48</strong></td>
                <td><strong>Hook is not bent or twisted
•	Max. bending or twisting not to exceed 10 degrees from plane of unbent hook or as per manufacturer recommendations
</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10
(10.1.2.1.3c1)
</strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[160][]" id="checkbox160_1" value="PASS" class="large-checkbox" <?php echo isChecked(160, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[160][]" id="checkbox160_2" value="FAIL" class="large-checkbox" <?php echo isChecked(160, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[160][]" id="checkbox160_3" value="NA" class="large-checkbox" <?php echo isChecked(160, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[160]" value="<?php echo getRemark(160, $saved_remarks); ?>" class="form-control">
</td>
            </tr>  
 <tr>
                <td><strong>3.49</strong></td>
                <td><strong>Hook is not distorted in the throat opening
•	Max. allowable throat opening is 15% compared to new hook, or as per manufacturer recommendations
</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10
(10.1.2.1.3c2)
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[161][]" id="checkbox161_1" value="PASS" class="large-checkbox" <?php echo isChecked(161, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[161][]" id="checkbox161_2" value="FAIL" class="large-checkbox" <?php echo isChecked(161, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[161][]" id="checkbox161_3" value="NA" class="large-checkbox" <?php echo isChecked(161, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[161]" value="<?php echo getRemark(161, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			
			<tr>
                <td><strong>3.50</strong></td>
                <td><strong>Maximum wear in the hook bowl is not exceeding 10% (compared to new hook) or as per manufacturer recommendations</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10
(10.1.2.1.3c3)
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[162][]" id="checkbox162_1" value="PASS" class="large-checkbox" <?php echo isChecked(162, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[162][]" id="checkbox162_2" value="FAIL" class="large-checkbox" <?php echo isChecked(162, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[162][]" id="checkbox162_3" value="NA" class="large-checkbox" <?php echo isChecked(162, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[162]" value="<?php echo getRemark(162, $saved_remarks); ?>" class="form-control">
</td>
            </tr>	
<tr>
                <td><strong>3.51</strong></td>
                <td><strong> Hook is not cracked, gouged or shows nicks</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10
(10.1.2.1.2c3)
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[163][]" id="checkbox163_1" value="PASS" class="large-checkbox" <?php echo isChecked(163, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[163][]" id="checkbox163_2" value="FAIL" class="large-checkbox" <?php echo isChecked(163, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[163][]" id="checkbox163_3" value="NA" class="large-checkbox" <?php echo isChecked(163, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[163]" value="<?php echo getRemark(163, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			
			<tr>
                <td><strong>3.52</strong></td>
                <td><strong>Hook can lock (if it is a self-locking hook) </strong></td>
				<td style="text-align: center;"><strong>ASME B30.10
(10.1.2.1.3c4)
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[164][]" id="checkbox164_1" value="PASS" class="large-checkbox" <?php echo isChecked(164, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[164][]" id="checkbox164_2" value="FAIL" class="large-checkbox" <?php echo isChecked(164, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[164][]" id="checkbox164_3" value="NA" class="large-checkbox" <?php echo isChecked(164, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[164]" value="<?php echo getRemark(164, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>3.53</strong></td>
                <td><strong>Hook latch is operative</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10
(10.1.2.1.3c5)
  </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[165][]" id="checkbox165_1" value="PASS" class="large-checkbox" <?php echo isChecked(165, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[165][]" id="checkbox165_2" value="FAIL" class="large-checkbox" <?php echo isChecked(165, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[165][]" id="checkbox165_3" value="NA" class="large-checkbox" <?php echo isChecked(165, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[165]" value="<?php echo getRemark(165, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>3.54</strong></td>
                <td><strong> Hook is free to rotate</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10
Sec.10-1.2.
  </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[166][]" id="checkbox166_1" value="PASS" class="large-checkbox" <?php echo isChecked(166, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[166][]" id="checkbox166_2" value="FAIL" class="large-checkbox" <?php echo isChecked(166, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[166][]" id="checkbox166_3" value="NA" class="large-checkbox" <?php echo isChecked(166, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[166]" value="<?php echo getRemark(166, $saved_remarks); ?>" class="form-control">
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
                    <?php echo htmlspecialchars($row['recommendations']); ?>
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

