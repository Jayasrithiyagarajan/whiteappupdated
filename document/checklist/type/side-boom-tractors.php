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
    <title>INSPECTION CHECKLIST FOR SIDE BOOM TRACTORS </title>
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
            <strong>INSPECTION CHECKLIST FOR SIDE BOOM TRACTORS </strong>
        </td>
    </tr>
    <tr>
        <td>FRM.0601-1.11</td>
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
                <td colspan="3" style="text-align: center;"><strong>INSPECTION CHECKLIST FOR SIDE BOOM TRACTORS </strong></td>
				</tr>
            <tr>
                <td style="width: 25%; text-align: center;"><strong>FRM.0601-1.11</strong></td>
                <td style="width: 25%; text-align: center;"> <strong>Revision 02</strong></td>
                
                <td style="width: 25%; text-align: center;"> <strong>Issue Date: 30/SEP/2020</strong></td>
            </tr>
			</tbody>
			</table> -->
			
			</div>

        <h4>SIDE BOOM TRACTORS</h4>
        <h4>ASME B30.14-2015</h4>
		
        
		 <!--<button class="btn btn-primary no-print" onclick="preparePrint()">Print View</button>-->

         <form method="post" action="./update_checklist.php" id="checklistForm">
<div class="table-responsive">
            <table class="table table-bordered">
				<tr>
                <th style="width: 25%;">REPORT NO</th>
                <td style="width: 25%;">
                    <input type="text" name="report_no" value="<?php echo htmlspecialchars($row['report_no'] ?? ''); ?>" class="form-control" style="font-weight: bold; border: none; background: transparent;">
                    
                </td>
                <th style="width: 25%;">INSPECTION DATE</th>
                <td style="width: 25%;">
                    <input type="date" name="inspection_date" value="<?php echo htmlspecialchars($row['inspection_date'] ?? ''); ?>" class="form-control" style="font-weight: bold; border: none; background: transparent;"></td>
            </tr>
            <tr>
                <th>CLIENT’S NAME</th>
                <td>
                    <input type="text" name="header_client_name" value="<?php echo htmlspecialchars($row['client_name'] ?? ''); ?>" class="form-control" style="font-weight: bold; border: none; background: transparent;"></td>
                <th>INSPECTED BY</th>
                <td>
                    <input type="text" name="inspected_by" value="<?php echo htmlspecialchars($row['inspected_by'] ?? ''); ?>" class="form-control" style="font-weight: bold; border: none; background: transparent;"></td>
            </tr>
            <tr>
                <th>LOCATION</th>
                <td>
                    <input type="text" name="location" value="<?php echo htmlspecialchars($row['location'] ?? ''); ?>" class="form-control" style="font-weight: bold; border: none; background: transparent;">
                </td>
                <th>STICKER NO.</th>
                <td>
                    <input type="text" name="sticker_no" value="<?php echo htmlspecialchars($row['sticker_no'] ?? ''); ?>" class="form-control" style="font-weight: bold; border: none; background: transparent;">
                    </td>
            </tr>
            <tr>
                <th>EQUIPMENT NO</th>
                <td>
                <input type="text" name="equipment_no" value="<?php echo htmlspecialchars($row['equipment_no'] ?? ''); ?>" class="form-control" style="font-weight: bold; border: none; background: transparent;">
                </td>
                <th>EQUIP.SERIAL NO.:</th>
                <td>
                <input type="text" name="crane_serial_no" value="<?php echo htmlspecialchars($row['crane_serial_no'] ?? ''); ?>" class="form-control" style="font-weight: bold; border: none; background: transparent;">
                </td>
            </tr>
            <tr>
                <th>EQUIPMENT TYPE</th>
                <td>
                <input type="text" name="equipmenttype" value="<?php echo htmlspecialchars($row['equipmenttype'] ?? ''); ?>" class="form-control" style="font-weight: bold; border: none; background: transparent;">
                </td>
                <th>CAPACITY (SWL)</th>
                <td>
                    <input type="text" name="capacity_swl" value="<?php echo htmlspecialchars($row['capacity_swl'] ?? ''); ?>" class="form-control" style="font-weight: bold; border: none; background: transparent;">
                    </td>
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
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.5
 </strong></td>
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
                <td><strong>Previous inspection reports are checked </strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.5
 </strong></td>
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
                <td><strong>Operator is certified or qualified for the specific type of equipment.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec.3.1.1(a)(1-3)</strong></td>
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
                <td><strong>Load rating chart is applicable to the configured boom and is legible</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.1.3
  </strong></td>
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
                <td><strong>An operating manual is available</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.1.3 (c)
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
                <td><strong>A sign is posted warning the operator not to rely solely on any automatic device as a substitute for safe operating practice</strong></td>
				<td style="text-align: center;"><strong>CIMS QHSE 06</strong></td>
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
                <td><strong>Rated capacity of crane is marked</strong></td>
				<td style="text-align: center;"><strong>CIMS QHSE 06
</strong></td>
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
    <input type="text" name="checklist_remark[7]" value="<?php echo getRemark(7, $saved_remarks); ?>">
</td>
            </tr>
			
      <tr>
                <td><strong>1.8</strong></td>
                <td><strong>A fire extinguisher with minimum rating of 10 BC is installed in the cab or at the machinery housing</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 3.4.4 (a)

</strong></td>
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
                    <th style="text-align: center;">2</th>
                    <th style="text-align: center;">GENERAL INSPECTION POINTS</th>
					<th style="text-align: center;"> </th>
                    
                    <th style="text-align: center;">PASS</th>
                    <th style="text-align: center;">FAIL</th>
                    <th style="text-align: center;">NA</th>
                    <th> </th>
                </tr>
				 <tr>
                <td><strong>2.0</strong></td>
                <td><strong>Electrical apparatus is working correctly without excessive dirt, deterioration or moisture accumulation</strong></td>
				<td style="text-align: center;"><strong> ASME B30.2, Sec.1.2.1
 </strong></td>
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
                <td><strong>2.1</strong></td>
                <td><strong>Control levers are all operable from the operator station</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.3.1&2
 </strong></td>
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
                <td><strong>2.2</strong></td>
                <td><strong>Control pedals are functioning correctly</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.3.1&2
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
                <td><strong>2.3</strong></td>
                <td><strong>All controls are labeled as to their functions and within reach of operator</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.4.1
  </strong></td>
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
                <td><strong>2.4</strong></td>
                <td><strong>Guards are fitted and secured to cover exposed moving parts</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.7.5
  </strong></td>
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
                <td><strong>2.5</strong></td>
                <td><strong>Guards are fitted and secured to cover exposed moving parts</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.7.5
  </strong></td>
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
                <td><strong>2.6</strong></td>
                <td><strong>All moving parts that require lubrication are lubricated and accessible</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.3.4 (b)
</strong></td>
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
                <td><strong>2.7</strong></td>
                <td><strong>Boom angle indicator is functioning correctly</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.2 (c2)
</strong></td>
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
                <td><strong>2.8</strong></td>
                <td><strong>Boom hoist shut off is functioning correctly</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.2 (c2)
</strong></td>
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
                <td><strong>2.9</strong></td>
                <td><strong>Load indicator is functioning correctly</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.2 (c2)
 </strong></td>
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
                <td><strong>2.10</strong></td>
                <td><strong>Capacity indicator is functioning correctly</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.2 (c2)
 </strong></td>
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
                <td><strong>2.11</strong></td>
                <td><strong>Ignition system is operating correctly</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.4.3 (a)
 </strong></td>
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
                <td><strong>2.12</strong></td>
                <td><strong>Emergency shut down is operating satisfactorily</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.4.3 (c)
 </strong></td>
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
                <td><strong>2.13</strong></td>
                <td><strong>Engine throttle is functioning satisfactorily</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec.1.4.3 (b)
  </strong></td>
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
                <td><strong>2.14</strong></td>
                <td><strong> Battery is secured and ventilated</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.3 (f)
  </strong></td>
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
                <td><strong>2.15</strong></td>
                <td><strong>Engine gauges are functioning correctly</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.2 (c7)
  </strong></td>
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
                <td><strong>2.16</strong></td>
                <td><strong>Exhaust system is not corroded, and is guarded or insulated where necessary to prevent personal contact with hot surfaces</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.7.2
</strong></td>
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
                <td><strong>2.17</strong></td>
                <td><strong>Engine oil level is adequate.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.3.1
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
                <td><strong>2.18</strong></td>
                <td><strong>Hydraulic systems are not leaking</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.2 (c3)
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
                <td><strong>2.19</strong></td>
                <td><strong>Oil and fuel tanks do not leak</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.2 (c3)
 </strong></td>
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
                <td><strong>2.20</strong></td>
                <td><strong>Hydraulic/Pneumatic systems are not leaking (pumps, valves, lines, etc.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.2 (c3
 </strong></td>
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
                <td><strong>2.21</strong></td>
                <td><strong>Hook assembly labeling and manufacturer data is clearly marked</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10
Sec. 1.1.1
 </strong></td>
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
                <td><strong>2.22</strong></td>
                <td><strong>Hook's weight is clearly marked/printed on the hook</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10
Sec. 1.1.1
 </strong></td>
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
                <td><strong>2.23</strong></td>
                <td><strong> Safe working load of hook is clearly marked on the item
</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10
Sec. 1.1.1
  </strong></td>
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
                <td><strong>2.24</strong></td>
                <td><strong>Hook does not show defects such as nicks, cracks and gouges</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10
Sec. 1.2.1.2 (c3)
  </strong></td>
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
                <td><strong>2.25</strong></td>
                <td><strong>Hook is not bent or twisted
Max. bending or twisting not to exceed 10 degrees from plane of unbent hook or as per manufacturer recommendations
</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10 Sec.10-1.2.1.3(c1)
  </strong></td>
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
                <td><strong>2.26</strong></td>
                <td><strong>Hook is not distorted in the throat opening
Max. allowable throat opening is 15% compared to new hook, or as per manufacturer recommendations
</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10
Sec. 1.2.1.3 (c2)
</strong></td>
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
                <td><strong>2.27</strong></td>
                <td><strong>Maximum wear in the hook bowl is not exceeding 10% (compared to new hook) or as per manufacturer recommendations</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10
Sec. 1.2.1.3 (c3)
</strong></td>
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
                <td><strong>2.28</strong></td>
                <td><strong>Hook is not cracked, gouged or shows nicks</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10
Sec. 1.2.1.2 (c3)
</strong></td>
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
                <td><strong>2.29</strong></td>
                <td><strong>Hook can lock (if it is a self-locking hook)</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10
Sec. 1.2.1.3 (c4)
 </strong></td>
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
                <td><strong>2.30</strong></td>
                <td><strong>Hook latch is operative</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10
Sec. 1.2.1.3 (c5)
 </strong></td>
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
                <td><strong>2.31</strong></td>
                <td><strong>Upper and lower structures are free of defective/corroded welds</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.7.4
 </strong></td>
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
                <td><strong>2.32</strong></td>
                <td><strong>Boom mounting ears are not cracked</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.3(d)
 </strong></td>
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
                <td><strong>2.33</strong></td>
                <td><strong> Boom bushings and pins are not worn and are secure</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.3 (d)
  </strong></td>
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
                <td><strong>2.34</strong></td>
                <td><strong>Machinery deck frame is undamaged</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.3 (a)
  </strong></td>
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
                <td><strong>2.35</strong></td>
                <td><strong>Boom hoist cylinder mount is secure</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.2.1
  </strong></td>
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
                <td><strong>2.36</strong></td>
                <td><strong>A-frame frontage is undamaged
</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.7.3 (a)
</strong></td>
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
                <td><strong>2.37</strong></td>
                <td><strong>A-frame back legs are undamaged</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.7.3 (a)
</strong></td>
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
                <td><strong>2.38</strong></td>
                <td><strong>Float mast is undamaged</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.7.3 (d)
</strong></td>
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
                <td><strong>2.39</strong></td>
                <td><strong>Sheave grooves are free from surface defects and lubricated</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.5.4 (a)
 </strong></td>
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
                <td><strong>2.40</strong></td>
                <td><strong>Rope Carrying sheaves have close-fittings guards or other suitable devices</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.5.4 (b)
 </strong></td>
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
                <td><strong>2.41</strong></td>
                <td><strong>Lower block sheaves have close-fittings guards</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14 
Sec. 1.5.4(c)
 </strong></td>
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
                <td><strong>2.42</strong></td>
                <td><strong>Inner bridle frame is undamaged</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
 Sec. 2.1.3(a)
 </strong></td>
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
                <td><strong>2.43</strong></td>
                <td><strong>Inner bridle frame sheaves are smooth in their grooves and lubricated</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.4 (a)
  </strong></td>
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
                <td><strong>2.44</strong></td>
                <td><strong>Bearings and bushings are undamaged, secure and lubricated</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.3 (d)
  </strong></td>
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
                <td><strong>2.45</strong></td>
                <td><strong>Outer bridle frame is undamaged</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.3 (a)
  </strong></td>
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
                <td><strong>2.46</strong></td>
                <td><strong>Outer bridle frame sheaves are smooth in their grooves and lubricated</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.5.4
</strong></td>
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
                <td><strong>2.47</strong></td>
                <td><strong>Boom stops are fitted and undamaged</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14 Sec.1.7.1 (a)
Sec. 2.2.1 (3)
</strong></td>
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
                <td><strong>2.48</strong></td>
                <td><strong>Load hoist brake function is satisfactory</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.2.2 (b)
</strong></td>
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
                <td><strong>2.49</strong></td>
                <td><strong>Load lifting and lowering operations are satisfactory</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.2.2 (a)
 </strong></td>
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
                <td><strong>2.50</strong></td>
                <td><strong>Two full wraps, at least, of rope remain on the drum when the hook is in its extreme low working position</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.2.2 (a2)
 </strong></td>
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
                <td><strong>2.51</strong></td>
                <td><strong>Rope ends are anchored to the drum by clamps or as per manufacturer recommendations</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.2.2 (a2)
 </strong></td>
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
                <td><strong>2.52</strong></td>
                <td><strong>Drums are provided with a guidance or other means to prevent rope from jumping off the drum</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.2.2 (a2)
 </strong></td>
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
                <td><strong>2.53</strong></td>
                <td><strong>Power controlled lowering is operational and meets the speed and load criteria as per manufacturers specifications</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.2.2 (c)
  </strong></td>
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
                <td><strong>2.54</strong></td>
                <td><strong>Positive braking means is available, controllable by the operator, to prevent drum rotation in the lowering direction</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.2.2 (a4)
  </strong></td>
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
                <td><strong>2.55</strong></td>
                <td><strong>The rope does not have more than 6 randomly distributed broken wires in 1 lay or 3 in 1 strand in 1 lay (for running ropes)</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.4.2 (b1)
  </strong></td>
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
                <td><strong>2.56</strong></td>
                <td><strong>The ropes does not have more than two broken wires in 1 lay in sections beyond end connections or more than 1 broken wire at an end connection (for standing </strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.4.2 (b6)
</strong></td>
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
                <td><strong>2.57</strong></td>
                <td><strong>The rope wear does not exceed 1/3 of the original diameter</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.4.2 (b2)
</strong></td>
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
                <td><strong>2.58</strong></td>
                <td><strong>The rope does not have kinking, crushing, bird caging, evidence of heat damage, unstranding, core corrosion, main strand displacement or any other damages</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14 Sec. 2.4.1 (a)
Sec. 2.4.2 (b3)
</strong></td>
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
                <td><strong>2.59</strong></td>
                <td><strong>Boom hoist mechanism (raising and lowering) is properly operating</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.2.1
 </strong></td>
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
                <td><strong>2.60</strong></td>
                <td><strong>Boom hoist brake and clutch are correctly operating</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.2.1 (a)
 </strong></td>
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
                <td><strong>2.61</strong></td>
                <td><strong>Boom hoist brake and clutch have adjustments to compensate for wear</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.2.1 (b)
 </strong></td>
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
                <td><strong>2.62</strong></td>
                <td><strong>Locking pawl and ratchet is in good working condition to prevent inadvertent lowering of the boom</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.2.1 (c)
 </strong></td>
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
                <td><strong>2.63</strong></td>
                <td><strong>Two full wraps, at least, of rope remain on the drum when the hook is in its extreme low working position)</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.2.2 (a2)
  </strong></td>
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
                <td><strong>2.64</strong></td>
                <td><strong>Rope ends are anchored to the drum by clamps or as per manufacturer recommendations</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.2.2 (a2)
  </strong></td>
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
                <td><strong>2.65</strong></td>
                <td><strong>Drums are provided with a guidance or other means to prevent rope from jumping off the drum</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.2.2 (a2)
  </strong></td>
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
                <td><strong>2.66</strong></td>
                <td><strong>The rope does not have more than 6 randomly distributed broken wires in 1 lay or 3 in 1 strand in 1 lay (for running ropes)</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.4.2 (b1)
</strong></td>
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
                <td><strong>2.67</strong></td>
                <td><strong>The ropes does not have more than two broken wires in 1 lay in sections beyond end connections or more than 1 broken wire at an end connection (for standing </strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.4.2 (b6)
</strong></td>
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
                <td><strong>2.68</strong></td>
                <td><strong>The rope wear does not exceed 1/3 of the original diameter</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.4.2 (b2)
</strong></td>
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
                <td><strong>2.69</strong></td>
                <td><strong>The rope does not have kinking, crushing, bird caging, evidence of heat damage, unstranding, core corrosion, main strand displacement or any other damages</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.4.1 (a)
Sec. 2.4.2 (b3)
 </strong></td>
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
                <td><strong>2.70</strong></td>
                <td><strong>Boom hoist cylinder and associated hydraulic system is operating correctly</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.2 (c3)

 </strong></td>
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
                <td><strong>2.71</strong></td>
                <td><strong>Boom hoist cylinder seals are not leaking</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.2 (c3)
 </strong></td>
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
                <td><strong>2.72</strong></td>
                <td><strong>Track shoes are all in place and not loose or broken</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.3 (a, b, d)
 </strong></td>
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
                <td><strong>2.73</strong></td>
                <td><strong>Track rollers and followers are turning and not loose, worn or seized</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.3 (a, b, d)
  </strong></td>
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
                <td><strong>2.74</strong></td>
                <td><strong>End tumblers are serviceable</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.3 (a, b, d)
  </strong></td>
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
                <td><strong>2.75</strong></td>
                <td><strong>Track tension is sufficient for ground conditions</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14 S
Sec. 2.1.3 (g)
  </strong></td>
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
                <td><strong>2.76</strong></td>
                <td><strong>Drive chain is not stretched excessively</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.3 (g)
</strong></td>
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
                <td><strong>2.77</strong></td>
                <td><strong>Drive gearbox operates without excessive noise or leakage</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.3.3 (1)
</strong></td>
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
                <td><strong>2.78</strong></td>
                <td><strong>End sprockets are not excessively worn at the teeth</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14 S
Sec. 2.1.3 (g)
</strong></td>
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
                <td><strong>2.79</strong></td>
                <td><strong>Frame to axle fastenings are secured and not loose</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.3 (b)
 </strong></td>
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
                <td><strong>2.80</strong></td>
                <td><strong>Stabilizer struts are undamaged and secure</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.3 (g)
 </strong></td>
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
                <td><strong>2.81</strong></td>
                <td><strong>Propelling forward is accepted</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.2.2 (a)
 </strong></td>
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
                <td><strong>2.82</strong></td>
                <td><strong>Propelling backward is accepted</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.2.2 (a)
 </strong></td>
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
                <td><strong>2.83</strong></td>
                <td><strong>Transmission gearbox functioning well and gears are operational</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.4.3 (d)
  </strong></td>
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
                <td><strong>2.84</strong></td>
                <td><strong>Steering to the right is operational</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.3 (h)
  </strong></td>
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
                <td><strong>2.85</strong></td>
                <td><strong>Steering to the left is operational</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.3 (h)
  </strong></td>
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
                <td><strong>2.86</strong></td>
                <td><strong>Travel locks are operational and remain in engagement</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.3 (h)
</strong></td>
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
                <td><strong>2.87</strong></td>
                <td><strong>All safety limit devices are operational</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.3.2
</strong></td>
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
                <td><strong>2.88</strong></td>
                <td><strong>Cab glass is made of safety glazing material</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.6.1 (c)
</strong></td>
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
                <td><strong>2.89</strong></td>
                <td><strong>Screen wipers are in good operational condition</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.6.1 (d)
 </strong></td>
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
                <td><strong>2.90</strong></td>
                <td><strong>Road mirrors are fitted and undamaged</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.6.1 (d)
 </strong></td>
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
                <td><strong>2.91</strong></td>
                <td><strong>Fire extinguisher is available in the cab (minimum rating is 10BC)</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 3.4.4
 </strong></td>
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
                <td><strong>2.92</strong></td>
                <td><strong>Gauges and instrumentations are in good working condition</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.2 (c7)
 </strong></td>
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
                <td><strong>2.93</strong></td>
                <td><strong>Brakes are in working condition</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.3 (e)
  </strong></td>
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
                <td><strong>2.94</strong></td>
                <td><strong>Parking brake is in working condition</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.3 (e)
  </strong></td>
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
                <td><strong>2.95</strong></td>
                <td><strong>Clutch is in good working condition</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.3.3
  </strong></td>
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
                <td><strong>2.96</strong></td>
                <td><strong>Gear selector is properly working</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.4.3 (d)
</strong></td>
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
                <td><strong>2.97</strong></td>
                <td><strong>Back-up alarm is operable</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.2 (c2)
</strong></td>
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
                <td><strong>2.98</strong></td>
                <td><strong>Headlights are working</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.2 (c2)
</strong></td>
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
                <td><strong>2.99</strong></td>
                <td><strong>Brake lights are working</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.2 (c2)
 </strong></td>
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
                <td><strong>Back-up lights are working</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.2 (c2)
  </strong></td>
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
                <td><strong>3.1</strong></td>
                <td><strong>Turn signals are working</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.2 (c2)
 </strong></td>
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
                <td><strong>3.2</strong></td>
                <td><strong>Ignition system is operating correctly</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.4.3 (a)
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
                <td><strong>3.3</strong></td>
                <td><strong>Throttle is operating correctly</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.4.3 (b)
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
                <td><strong>3.4</strong></td>
                <td><strong>Battery is secure and ventilated</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.3 (f)
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
                <td><strong>3.5</strong></td>
                <td><strong>Gauges are in working condition
</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.2 (c7)
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
                <td><strong>3.6</strong></td>
                <td><strong>Engine exhaust system is not corroded, and is guarded or insulated where necessary to prevent personal contact with hot surfaces</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.7.2
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
                <td><strong>3.7</strong></td>
                <td><strong>Oil level is sufficient</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.3.1
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
                <td><strong>3.8</strong></td>
                <td><strong>Torque converter is in working condition</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.3.3
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
 <tr>
                <td><strong>3.9</strong></td>
                <td><strong>Main transmission is in working condition</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.3.3
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[118][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(118, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[118][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(118, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[118][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(118, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[118]" value="<?php echo getRemark(118, $saved_remarks); ?>">
</td>
            </tr>
			
			<tr>
                <td><strong>3.10</strong></td>
                <td><strong>Auxiliary transmission is in working condition</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.3.3
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[119][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(119, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[119][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(119, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[119][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(119, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[119]" value="<?php echo getRemark(119, $saved_remarks); ?>">
</td>
            </tr>	
<tr>
                <td><strong>3.11</strong></td>
                <td><strong>Tires and rims are undamaged and serviceable</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.3 (I)
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[120][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(120, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[120][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(120, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[120][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(120, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[120]" value="<?php echo getRemark(120, $saved_remarks); ?>">
</td>
            </tr>
			
			<tr>
                <td><strong>3.12</strong></td>
                <td><strong>Outrigger beam sections are not corroded, cracked or damaged</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.7.3 (a)
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[121][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(121, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[121][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(121, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[121][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(121, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[121]" value="<?php echo getRemark(121, $saved_remarks); ?>">
</td>
            </tr>
			<tr>
                <td><strong>3.13</strong></td>
                <td><strong>Outrigger pads are securely fitted and undamaged</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.7.3 (d)
  </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[122][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(122, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[122][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(122, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[122][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(122, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[122]" value="<?php echo getRemark(122, $saved_remarks); ?>">
</td>
            </tr>
			<tr>
                <td><strong>3.14</strong></td>
                <td><strong>Pad pins are locked and undamaged</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.7.3
  </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[123][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(123, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[123][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(123, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[123][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(123, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[123]" value="<?php echo getRemark(123, $saved_remarks); ?>">
</td>
            </tr>
			<tr>
                <td><strong>3.15</strong></td>
                <td><strong>Extension cylinders are working correctly and undamaged</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.7.3
  </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[124][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(124, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[124][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(124, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[124][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(124, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[124]" value="<?php echo getRemark(124, $saved_remarks); ?>">
</td>
            </tr>
			<tr>
                <td><strong>3.16</strong></td>
                <td><strong>Vertical cylinders are working correctly and undamaged</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.7.3
</strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[125][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(125, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[125][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(125, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[125][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(125, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[125]" value="<?php echo getRemark(125, $saved_remarks); ?>">
</td>
            </tr>
			<tr>
                <td><strong>3.17</strong></td>
                <td><strong>Boom tip section is undamaged, has no cracks or distortion</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.3 (a)
</strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[126][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(126, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[126][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(126, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[126][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(126, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[126]" value="<?php echo getRemark(126, $saved_remarks); ?>">
</td>
            </tr>
			
      <tr>
                <td><strong>3.18</strong></td>
                <td><strong>Boom extension cylinder is properly working with no signs of leaks)</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.2 (c3)
</strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[127][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(127, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[127][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(127, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[127][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(127, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[127]" value="<?php echo getRemark(127, $saved_remarks); ?>">
</td>
            </tr>  
 <tr>
                <td><strong>3.19</strong></td>
                <td><strong>Guide sheaves are not worn and are well lubricated</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.3 (c)
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[128][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(128, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[128][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(128, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[128][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(128, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[128]" value="<?php echo getRemark(128, $saved_remarks); ?>">
</td>
            </tr>
			
			<tr>
                <td><strong>3.20</strong></td>
                <td><strong>Main sheaves are smooth in their grooves and well lubricated</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.5.4
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[129][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(129, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[129][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(129, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[129][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(129, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[129]" value="<?php echo getRemark(129, $saved_remarks); ?>">
</td>
            </tr>	
<tr>
                <td><strong>3.21</strong></td>
                <td><strong>Bushings/bearings are not worn and are well lubricated</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.3
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[130][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(130, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[130][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(130, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[130][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(130, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[130]" value="<?php echo getRemark(130, $saved_remarks); ?>">
</td>
            </tr>
			
			<tr>
                <td><strong>3.22</strong></td>
                <td><strong>Guards are fitted and secured to cover exposed moving parts</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.7.5
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[131][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(131, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[131][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(131, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[131][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(131, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[131]" value="<?php echo getRemark(131, $saved_remarks); ?>">
</td>
            </tr>
			<tr>
                <td><strong>3.23</strong></td>
                <td><strong>Rope wedge anchor is correctly fitted and secure, and is fitted in accordance with the manufacturer’s recommendations</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.5.3 (c)
  </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[132][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(132, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[132][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(132, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[132][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(132, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[132]" value="<?php echo getRemark(132, $saved_remarks); ?>">
</td>
            </tr>
			<tr>
                <td><strong>3.24</strong></td>
                <td><strong>Counterweight(s) are securely fitted and locked in position on the operating arms</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 3.4.1
  </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[133][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(133, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[133][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(133, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[133][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(133, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[133]" value="<?php echo getRemark(133, $saved_remarks); ?>">
</td>
            </tr>
			<tr>
                <td><strong>3.25</strong></td>
                <td><strong>Operating rams are undamaged with no seal or hose leaks
</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 3.4.1
  </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[134][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(134, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[134][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(134, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[134][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(134, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[134]" value="<?php echo getRemark(134, $saved_remarks); ?>">
</td>
            </tr>
			<tr>
                <td><strong>3.26</strong></td>
                <td><strong>Back stops are in place and secure</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 3.4.1
</strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[135][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(135, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[135][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(135, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[135][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(135, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[135]" value="<?php echo getRemark(135, $saved_remarks); ?>">
</td>
            </tr>
			<tr>
                <td><strong>3.27</strong></td>
                <td><strong>Complete counterweight and arm assembly shows no cracks or deformation</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 3.4.1
</strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[136][]" id="checkbox4" value="PASS" class="large-checkbox" <?php echo isChecked(136, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[136][]" id="checkbox5" value="FAIL" class="large-checkbox" <?php echo isChecked(136, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[136][]" id="checkbox6" value="NA" class="large-checkbox" <?php echo isChecked(136, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[136]" value="<?php echo getRemark(136, $saved_remarks); ?>">
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
        <tr>
            <th style="width: 25%;">INSPECTOR’S NAME:</th>
            <td style="width: 25%;">
                <input type="text" name="inspected_by" value="<?php echo htmlspecialchars($row['inspected_by'] ?? ''); ?>" class="form-control" style="font-weight: bold; border: none; background: transparent;">
            </td>
            <th style="width: 25%;">CLIENT’S REP. NAME:</th>
            <td style="width: 25%;" onclick="openModal()">
        <span id="clientNameDisplay">Click to enter</span>
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
            $image_path = 'https://appcims.com/whiteapp/inspector/uploads/' . str_replace(' ', '_', strtolower($inspector_name)) . '/images/signature_image.jpg';
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
