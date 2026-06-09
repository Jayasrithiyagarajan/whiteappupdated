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
    <title>INSPECTION CHECKLIST FOR FIXED CRANES & HOIST </title>
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
            <strong>INSPECTION CHECKLIST FOR FIXED CRANES & HOIST</strong>
        </td>
    </tr>
    <tr>
        <td>FRM.0601-1.2</td>
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
                <td colspan="3" style="text-align: center;"><strong>INSPECTION CHECKLIST FOR FIXED CRANES & HOIST </strong></td>
				</tr>
            <tr>
                <td style="width: 25%; text-align: center;"><strong>FRM.0601-1.11</strong></td>
                <td style="width: 25%; text-align: center;"> <strong>Revision 02</strong></td>
                
                <td style="width: 25%; text-align: center;"> <strong>Issue Date: 30/SEP/2020</strong></td>
            </tr>
			</tbody>
			</table> -->
			
			</div>

        <h4>FIXED CRANES & HOISTS</h4>
        <h4>ASME B30.2-2016, ASME B30.3-2016, ASME B30.4-2015, ASME B30.6-2015, ASME B30.16-2017, ASME B30.17-2015
</h4>
		
        
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
                    <input type="date" name="inspection_date" value="<?php echo htmlspecialchars($row['inspection_date'] ?? ''); ?>" class="form-control" style="font-weight: bold; border: none; background: transparent;">
                </td>
            </tr>
            <tr>
                <th>CLIENT’S NAME</th>
                <td>
                    <input type="text" name="header_client_name" value="<?php echo htmlspecialchars($row['client_name'] ?? ''); ?>" class="form-control" style="font-weight: bold; border: none; background: transparent;">
                </td>
                <th>INSPECTED BY</th>
                <td>
                    <input type="text" name="inspected_by" value="<?php echo htmlspecialchars($row['inspected_by'] ?? ''); ?>" class="form-control" style="font-weight: bold; border: none; background: transparent;">
                </td>
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
                <th>CRANE ASSET NO:</th>
                <td>
                    <input type="text" name="equipment_no" value="<?php echo htmlspecialchars($row['equipment_no'] ?? ''); ?>" class="form-control" style="font-weight: bold; border: none; background: transparent;">
                </td>
                <th>CRANE SERIAL NO.:</th>
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
				<td style="text-align: center;"><strong>ASME B30.2, 
Sec.1.16 
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
				<td style="text-align: center;"><strong> ASME B30.2,
Sec.2.1.5
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
                <td><strong> Rated load is clearly marked on both sides of crane bridge</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.1.1.1</strong></td>
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
                <td><strong>Rated load is clearly marked on hoist or trolley unit </strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.1.1.1
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
                <td><strong>Equipment number is clearly marked for identification purposes</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.1.1
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
                <td><strong>Safe working load is clearly marked on the runway and the lifting machine</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.1.1.1 </strong></td>
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
                <td><strong>Crane manufacturer name, address, serial number and power ratings are clearly marked or tagged </strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, 
Sec.1.1.3 
</strong></td>
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
                <td><strong>Precautionary warnings to operator are clearly marked</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, 
Sec.1.1.5
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
                    <th style="text-align: center;">2</th>
                    <th style="text-align: center;">GENERAL INSPECTION POINTS</th>
					<th style="text-align: center;"> </th>                    
                    <th style="text-align: center;">PASS</th>
                    <th style="text-align: center;">FAIL</th>
                    <th style="text-align: center;">NA</th>
                    <th> </th>
                </tr>
				
 <tr>
                <td><strong>2.1</strong></td>
                <td><strong>Clearance exits between the crane and sides of the building or adjacent crane are maintained throughout all motions</strong></td>
				<td style="text-align: center;"><strong> ASME B30.2, Sec.1.2.1
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
                <td><strong>2.2</strong></td>
                <td><strong>Controls are clearly marked with their functions and modes of operation</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
Sec.3-1.18.1
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
                <td><strong>2.3</strong></td>
                <td><strong>Controls and protective equipment are within reach of the operator inside the cab</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, 
Sec.1.5.1a
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
                <td><strong>2.4</strong></td>
                <td><strong> The hook block is visible from operator station at all times</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, 
Sec.1.5.1b
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
                <td><strong>2.5</strong></td>
                <td><strong>Cab is attached to the crane to minimize swaying and vibrations</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2,
 Sec.1.5.2a
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
                <td><strong>2.6</strong></td>
                <td><strong>Access to the cab or bridge walkway is by a fixed ladder, stairs, or platform</strong></td>
				<td style="text-align: center;"><strong>AASME B30.2,
 Sec.1.5.3
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
                <td><strong>2.7</strong></td>
                <td><strong>Controls arrangements and protective equipment inside the cab are within the reach of the operator</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, 
Sec.1.5.1a
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
                <td><strong>2.8</strong></td>
                <td><strong>The clearance from the surface of the platform to the nearest overhead obstruction is 1220 mm (48")</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.1.7.1a
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
                <td><strong>2.9</strong></td>
                <td><strong>The service platform width is at least 457 mm (18") except at the bridge mechanism where it is not less than 380 mm (15")</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, 
Sec.1.7.1c
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
                <td><strong>2.10</strong></td>
                <td><strong>The electrical control cabinet door(s) are opening 90 degree or removable type</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2,
Sec.1.7.1e
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
                <td><strong>2.11</strong></td>
                <td><strong>Service platform walking surface is slip-resistant</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2,
Sec.1.7.1g
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
                <td><strong>2.12</strong></td>
                <td><strong>Service platform is provided with guard railings and toe boards</strong></td>
				<td style="text-align: center;"><strong> ASME B30.2,
Sec.1.7.1h
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
                <td><strong>2.13</strong></td>
                <td><strong>Emergency escape is possible from the cab</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.1.7.3
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
                <td><strong>2.14</strong></td>
                <td><strong> Stairways are non-slip and have a maximum incline angle of 50 degree </strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.1.7.2
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
                <td><strong>2.15</strong></td>
                <td><strong>Each hoisting unit is equipped with at least one holding brake</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.1.12.1a
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
                <td><strong>2.16</strong></td>
                <td><strong>The holding brake is applied to the motor shaft or a gear reducer shaft</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.1.12.1a
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
                <td><strong>2.17</strong></td>
                <td><strong>The holding brake torque rating is not less than the percentage of rated load hoisting torque at the point where the brake is applied (based on the crane design) </strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.1.12.1a
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
                <td><strong>2.18</strong></td>
                <td><strong>Pendant control cable is properly enclosed, grounded and suspended with a separate support cable </strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.1.13.1a-d
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
                <td><strong>2.19</strong></td>
                <td><strong>Pendant control push-button enclosure is marked for identification of functions</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.1.13.1e
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
                <td><strong>2.20</strong></td>
                <td><strong>Electrical equipment is guarded and not exposed to oil, moisture, dirt and inadvertent contact</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.1.13.2
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
                <td><strong>2.21</strong></td>
                <td><strong>Audio warning device(s) are fitted (one or more of the following: Gong, Bell/Siren/Horn, Rotating Beacon and/or strop light) </strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.1.15.3
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
                <td><strong>2.22</strong></td>
                <td><strong>Lifting and lowering functional test is satisfactory </strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.2.2(b-1)
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
                <td><strong>2.23</strong></td>
                <td><strong> Crane trolley travel functional test is satisfactory
</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.2.2(b-2)
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
                <td><strong>2.24</strong></td>
                <td><strong> Crane bridge travel functional test is satisfactory</strong></td>
				<td style="text-align: center;"><strong> ASME B30.2, Sec.2.2(b-3)
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
                <td><strong>2.25</strong></td>
                <td><strong>Hoist limit device functional test is satisfactory
</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.2.2(b-4)
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
                <td><strong>2.26</strong></td>
                <td><strong>Hoist and swing drives are capable of starts and stops with variable acceleration and deceleration required in normal operation</strong></td>
				<td style="text-align: center;"><strong>ASME B30.7 
Sec.1.2.2(f)
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
                <td><strong>2.27</strong></td>
                <td><strong>Hoist drum specifications are marked (rated load, drum size, rope size, rope speed (ft/min or m/s), rated power)</strong></td>
				<td style="text-align: center;"><strong>ASME B30.7 
Sec.1.1.3
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
                <td><strong>2.28</strong></td>
                <td><strong>Hand Chain Hoist: Manufacturer data, serial number and safe working load are clearly displayed on the item</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.1.1.3a
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
                <td><strong>2.29</strong></td>
                <td><strong>Electric Powered Hoist: Manufacturer data, serial number, safe working load, voltage and phase are clearly displayed on the item</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.1.1.3b
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
                <td><strong>2.30</strong></td>
                <td><strong>Air Powered Hoist: Manufacturer data, serial number, model, safe working load and rated air pressure are clearly displayed on the item</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.1.1.3c
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
                <td><strong>2.31</strong></td>
                <td><strong>Warning signs/labels are provided on the hoist units and electrical enclosures </strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.1.1.4
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
                <td><strong>2.32</strong></td>
                <td><strong>Crane Travel limit device functional test is satisfactory</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.2.2(b-4)
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
                <td><strong>2.33</strong></td>
                <td><strong> Wire rope end connections do not have corrosion</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.2.4.2(c,d)
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
                <td><strong>2.34</strong></td>
                <td><strong>Ropes are correctly lubricated</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.2.4.3e
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
                <td><strong>2.35</strong></td>
                <td><strong>Wire rope is not corroded</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.2.4.1(a1-b)
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
                <td><strong>2.36</strong></td>
                <td><strong>The rope is adequately lubricated
</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.2.4.3e
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
                <td><strong>2.37</strong></td>
                <td><strong>Fire extinguisher is available Sec.10BC minimum rated) </strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.3.4.3
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
                <td><strong>2.38</strong></td>
                <td><strong>Structure is vibration free under normal operating condition</strong></td>
				<td style="text-align: center;"><strong>ASME B30.17 Sec.1.3.1(b)
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
                <td><strong>2.39</strong></td>
                <td><strong>Monorail end stops are installed and in good condition</strong></td>
				<td style="text-align: center;"><strong>ASME B30.17 Sec.1.4.2, Sec 1.5.3
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
                <td><strong>2.40</strong></td>
                <td><strong>Jib crane end stops are installed and in good condition</strong></td>
				<td style="text-align: center;"><strong>ASME B30.17 Sec.1.4.2, Sec 1.5.3
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
                <td><strong>2.41</strong></td>
                <td><strong>Tracks are properly installed and aligned</strong></td>
				<td style="text-align: center;"><strong>ASME B30.17 Sec.1.3.1  Sec 1.4.1
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
                <td><strong>2.42</strong></td>
                <td><strong>Crane runways or monorail tracks are fastened and Secured to a supporting structure</strong></td>
				<td style="text-align: center;"><strong>ASME B30.17 Sec.1.3.2
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
                <td><strong>2.43</strong></td>
                <td><strong>All welded members are free of defects and not corroded</strong></td>
				<td style="text-align: center;"><strong>ASME B30.17 Sec.1.3.4
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
                <td><strong>2.44</strong></td>
                <td><strong>Guards protect moving parts such as gears, chains, chain sprockets</strong></td>
				<td style="text-align: center;"><strong>ASME B30.17 Sec.1.11.1
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
                <td><strong>2.45</strong></td>
                <td><strong>Guards protect ropes where liable to come in contact with conductors</strong></td>
				<td style="text-align: center;"><strong>ASME B30.17 Sec.1.11.2(a)
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
                <td><strong>2.46</strong></td>
                <td><strong>Guards are provided to prevent contact between crane bridge or runway conductors and hoisting ropes.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.17 Sec.1.11.2(b)
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
                <td><strong>2.47</strong></td>
                <td><strong>Hand chain operated Hoist: Hoist automatically stops and holds lifted load when the actuating force is removed</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.1.2.11a
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
                <td><strong>2.48</strong></td>
                <td><strong>Electric Powered Hoist: Braking system will stop and hold the load hook when controls are released under any load condition</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.1.2.11(b1-b)
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
                <td><strong>2.49</strong></td>
                <td><strong>Air Powered Hoist: Braking system will stop and hold the load hook when controls are released under any load condition</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.1.2.11(c1-a)
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
                <td><strong>2.50</strong></td>
                <td><strong>An electric hoist stops and holds the load block in the event of power failure</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.1.2.11(b1-c)
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
                <td><strong>2.51</strong></td>
                <td><strong> An air hoist stops and holds the load block in the event of air pressure loose</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.1.2.11(c1-b)
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
                <td><strong>2.52</strong></td>
                <td><strong>Braking systems has means for adjustment to compensate for wear</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.1.2.11(b3/c)
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
                <td><strong>2.53</strong></td>
                <td><strong> Hoist rope is guarded from chafing where required</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.1.14.6
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
                <td><strong>2.54</strong></td>
                <td><strong> Hook(s) can rotate freely</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.1.14.5
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
                <td><strong>2.55</strong></td>
                <td><strong>Rope compensating sheave(s) (equalizer) is free to turn</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.1.14.4
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
                <td><strong>2.56</strong></td>
                <td><strong>Surface condition of rope drum(s) show no defects and are smooth</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.1.14.2
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
                <td><strong>2.57</strong></td>
                <td><strong>All sheave grooves are smooth</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2. Sec.1.14.1
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
                <td><strong>2.58</strong></td>
                <td><strong>All sheaves are free to turn</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2. Sec.1.14.1
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
                <td><strong>2.59</strong></td>
                <td><strong>Rope construction is as per manufacturer recommendations</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.1.14.3a
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
                <td><strong>2.60</strong></td>
                <td><strong>Lower hoist limit cut-out (if fitted) is properly working</strong></td>
				<td style="text-align: center;"><strong> ASME B30.2, Sec.1.13.5. e
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
                <td><strong>2.61</strong></td>
                <td><strong>Stops and bumpers are fitted to each end of the trolley(s)</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.1.8.1, 3
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
                <td><strong>2.62</strong></td>
                <td><strong>Trolley truck rail sweeps are provided in front of the leading wheels on both ends of the trolley end truck</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.1.9.2a
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
                <td><strong>2.63</strong></td>
                <td><strong> Clearance between the top surface of the rail head and the bottom of the sweep does not exceed 3⁄16" (5 mm)</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.1.9.2b-1
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
                <td><strong>2.64</strong></td>
                <td><strong>The sweep extends below the top surface of the rail head, for a distance not less than 50% of the thickness of the rail head, on both sides of the rail head</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.1.9.2b-2
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
                <td><strong>2.65</strong></td>
                <td><strong>Clearance between the side surface of the rail head and the side of the sweep which extends below the top surface of the rail head is equal to crane float plus 3⁄16" </strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.1.9.2b-3
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
                <td><strong>2.66</strong></td>
                <td><strong>Trolley(s) brakes are operable</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.1.12.3
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
                <td><strong>2.67</strong></td>
                <td><strong>Trolley brakes comply with crane design requirements </strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.1.12.5
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
                <td><strong>2.68</strong></td>
                <td><strong>Trolley travel warnings (e.g. gong, beacon, bell or strop light) are operable</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.1.15.1a
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
                <td><strong>2.69</strong></td>
                <td><strong>Unusual sounds are not present during trolley travel</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.2.1.2a
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
                <td><strong>2.70</strong></td>
                <td><strong>Trolley has no missing or loose parts</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.2.1.3b2
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
                <td><strong>2.71</strong></td>
                <td><strong>Trolley wheels have no sign of excessive wear</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.2.1.3b4
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
                <td><strong>2.72</strong></td>
                <td><strong>Chain drive and sprocket have no wear or stretch </strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.2.1.3b6
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
                <td><strong>2.73</strong></td>
                <td><strong> All moving parts are correctly lubricated</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.2.3.4
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
                <td><strong>2.74</strong></td>
                <td><strong> Crane Bridge stops within stipulated 10% distance of rated load speed under frictional forces (if no braking means provided) </strong></td>
				<td style="text-align: center;"><strong> ASME B30.2, Sec.1.12.4a
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
                <td><strong>2.75</strong></td>
                <td><strong>Bridge brakes comply with crane design requirements</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.1.12.5
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
                <td><strong>2.76</strong></td>
                <td><strong>Trolley truck frame drop is limited to 25mm</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.1.11
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
                <td><strong>2.77</strong></td>
                <td><strong>Bridge is fitted with bumpers at each end</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.1.8.2
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
                <td><strong>2.78</strong></td>
                <td><strong>Bridge rail sweep clearance is 5mm</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.1.9.1
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
                <td><strong>2.79</strong></td>
                <td><strong>Bridge brakes capable of stopping the crane within 10% distance of rated load speed</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.1.12.4
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
                <td><strong>2.80</strong></td>
                <td><strong>Bridge anchorage in place and withstand external forces, like strong winds (for outdoor cranes)</strong></td>
				<td style="text-align: center;"><strong> ASME B30.2, Sec.1.3.1b
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
                <td><strong>2.81</strong></td>
                <td><strong>Runway columns are securely anchored to foundations</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.1.3.2a-2
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
                <td><strong>2.82</strong></td>
                <td><strong>The runway structure is free from detrimental vibration under normal operating conditions</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.1.3.2a-3
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
                <td><strong>2.83</strong></td>
                <td><strong> Rails are level, straight, joined, and spaced to the crane span within tolerances as per crane design</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.1.3.2a-4
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
                <td><strong>2.84</strong></td>
                <td><strong> Runway stops are provided at the limits of travel of the bridge</strong></td>
				<td style="text-align: center;"><strong> ASME B30.2, Sec.1.3.2b-1
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
                <td><strong>2.85</strong></td>
                <td><strong>Stops are designed to withstand the forces applied to the bumpers</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.1.3.2b-3
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
                <td><strong>2.86</strong></td>
                <td><strong>Crane is clear from obstruction throughout its travel (between building walls and other cranes)</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.1.2.19
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
                <td><strong>2.87</strong></td>
                <td><strong>All moving parts are correctly lubricated</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.2.3.4
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
                <td><strong>2.88</strong></td>
                <td><strong>All moving parts are guarded where potential hazard would exist otherwise</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.1.10a
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
                <td><strong>2.89</strong></td>
                <td><strong>Travel warnings are operational (gong, bell, siren, horn, beacon, or strop light)</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.1.15.1a
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
                <td><strong>2.90</strong></td>
                <td><strong>Crane structure shows no deformed, cracked or corroded members</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.2.1.3b1
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
                <td><strong>2.91</strong></td>
                <td><strong>All travel limit devices are functioning</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.1.3b10
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
                <td><strong>2.92</strong></td>
                <td><strong>Safety labels are displayed and legible </strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.1.1.5
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
                <td><strong>2.93</strong></td>
                <td><strong>Integral outside platform is in place and door opens outward or slides</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.1.5.2b
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
                <td><strong>2.94</strong></td>
                <td><strong>Trapdoor has a clear opening of not less than 610mm</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.1.5.2e
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
                <td><strong>2.95</strong></td>
                <td><strong>Guard railings and toe boards are in good condition</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.1.5.2f
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
                <td><strong>2.96</strong></td>
                <td><strong>All cab glazing’s are safety glazing materials</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.1.5.2g
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
                <td><strong>2.97</strong></td>
                <td><strong>A tool box is in place for basic maintenance made of noncombustible material and is securely fastened in the cab or on the service platform. </strong></td>
				<td style="text-align: center;"><strong>ASME 30.2,
Sec.1.5.4
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
                <td><strong>2.98</strong></td>
                <td><strong>Fire extinguisher rated 10 BC is provided and in placed</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.1.5.5
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
                <td><strong>2.99</strong></td>
                <td><strong>Lighting is adequate inside the cab and operator can clearly observe the controls</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.1.5.6
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
                <td><strong>Means of emergency exit are in place and effective</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.1.7.3
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
                <td><strong>3.1</strong></td>
                <td><strong> Control circuit voltage does not exceed 600 volts (AC or DC) </strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.1.13.1b
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
                <td><strong>3.2</strong></td>
                <td><strong>Welded structures and members do not have cracks or corrosion </strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.1.4.1
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
                <td><strong>3.3</strong></td>
                <td><strong> Adequate clearances exist between two parallel crane bridges (if there are no intervening walls or structures)</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.1.2.2
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
                <td><strong>3.4</strong></td>
                <td><strong> Minimum working space on service platforms is 1220mm (48")</strong></td>
				<td style="text-align: center;"><strong>ASME B3O.2, Sec.1.7.1a
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
                <td><strong>3.5</strong></td>
                <td><strong>Minimum passageway on service platform is 457mm (18")
</strong></td>
				<td style="text-align: center;"><strong>ASME B3O.2, Sec.1.7.1c
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
                <td><strong>3.6</strong></td>
                <td><strong>Doors of electrical cabinets to open 90 degrees or be removable</strong></td>
				<td style="text-align: center;"><strong>ASME B3O.2, Sec.1.7.1e
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
                <td><strong>3.7</strong></td>
                <td><strong>The crane controllers are equipped with spring return master switches</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.1.13.3
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
                <td><strong>3.8</strong></td>
                <td><strong>Control circuit voltage does not exceed 600v for AC or DC</strong></td>
				<td style="text-align: center;"><strong>ASME B30.17 
Sec. 1.14.1(b)
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
                <td><strong>3.9</strong></td>
                <td><strong>Push button enclosure is grounded</strong></td>
				<td style="text-align: center;"><strong>ASME B30.17 
Sec. 1.14.1(e)
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
                <td><strong>3.10</strong></td>
                <td><strong>Push button enclosure is marked for identification of function</strong></td>
				<td style="text-align: center;"><strong>ASME B30.17 
Sec. 1.14.1(e)
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
                <td><strong>3.11</strong></td>
                <td><strong>Parts of electrical equipment are enclosed and are not exposed to inadvertent contact under normal operating conditions</strong></td>
				<td style="text-align: center;"><strong>ASME B30.17 
Sec. 1.14.2(a)
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
                <td><strong>3.12</strong></td>
                <td><strong>Live parts of electrical equipment are protected from direct exposure to grease and oil and protected from dirt and moisture</strong></td>
				<td style="text-align: center;"><strong>ASME B30.17 
Sec. 1.14.2(b)
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
                <td><strong>3.13</strong></td>
                <td><strong>Guards on live parts are not deformed or/and in contact</strong></td>
				<td style="text-align: center;"><strong>ASME B30.17 Sec.1.14.2(c)
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
                <td><strong>3.14</strong></td>
                <td><strong>Floor operated cranes controllers return to off position when released </strong></td>
				<td style="text-align: center;"><strong>ASME B30.17 Sec.1.14.3(c1)
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
                <td><strong>3.15</strong></td>
                <td><strong>Pendant push buttons that control motion return to off position when pressure is released</strong></td>
				<td style="text-align: center;"><strong>ASME B30.17 Sec.1.14.3(c)
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
                <td><strong>3.16</strong></td>
                <td><strong>The resistors are supported and has minimum vibration effects</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2,
 Sec.-1.13.4
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
                <td><strong>3.17</strong></td>
                <td><strong>Runway conductors are guarded</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.1.13.6
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
                <td><strong>3.18</strong></td>
                <td><strong>A separate magnet circuit switch of enclosed type is provided (if a lifting magnet is used)</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.1.13.7a
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
                <td><strong>3.19</strong></td>
                <td><strong>Service receptacle in the cab or on the bridge is grounded type and does not exceed 300 volts (if provided)</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.1.13.8
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
                <td><strong>3.20</strong></td>
                <td><strong>The control circuit voltage in pendant push buttons does not exceed 150V for AC or 300V for DC</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.1.13.1c
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
                <td><strong>3.21</strong></td>
                <td><strong>A suspended push-button station is supported so that the electrical conductors are protected from strain (where multiple conductor cable is used)</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.2-1.13.1d
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
                <td><strong>3.22</strong></td>
                <td><strong>Pendant control stations is constructed to prevent electrical shock</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.1.13.1e
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
                <td><strong>3.23</strong></td>
                <td><strong>The push-button enclosure is at ground potential and marked for identification of functions)</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.1.13.1e
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
                <td><strong>3.24</strong></td>
                <td><strong>Chain passes over all load sprockets without binding</strong></td>
				<td style="text-align: center;"><strong> ASME B30.16 Sec.1.2.8
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
                <td><strong>3.25</strong></td>
                <td><strong>Hand Operated Chain: Chain length for extension (stretch) tolerance is no longer than 2.5% of unused chain or as per manufacturer recommendations
</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.2.5.2(a)
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
                <td><strong>3.26</strong></td>
                <td><strong>Power Operated Chain: Chain length for extension (stretch) tolerance is no longer than 1.5% of unused chain or as per manufacturer recommendations</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.2.5.2(a)
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
                <td><strong>3.27</strong></td>
                <td><strong>The chain does not suffer from gouges, nicks, corrosion, weld spatter or distorted links (Judgement to be used as to the suitability or otherwise of using chain with these deficiencies)</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.2.5.2(b)
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
                <td><strong>3.28</strong></td>
                <td><strong>The chain does not bind jump or gets noisy when hoist is operated</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.2.6.1(b)
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
                <td><strong>3.29</strong></td>
                <td><strong>The chain is not stretched or elongated more than 1/4" (6.3 mm) in 12" (305 mm) with reference to the manufacturer's manual (roller chain)</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.2.6.1(c1)
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
                <td><strong>3.30</strong></td>
                <td><strong>The chain is not twisted more than 15 degree in 5 ft (1.52 m) sections (roller chain)</strong></td>
				<td style="text-align: center;"><strong>AASME B30.16 Sec.2.6.1(c2)
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
                <td><strong>3.31</strong></td>
                <td><strong>The roller chain pins, links and rollers move freely and are not corroded, pitted, discolored or damaged </strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.2.6.1(d)
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
                <td><strong>3.32</strong></td>
                <td><strong>Fitted sling or chain would be retained slack in the bowl of the hook where latches are provided</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.1.2.9
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
                <td><strong>3.33</strong></td>
                <td><strong> Hand operated hoist: Load block is provided with a guard against load chain jamming in the load block under normal operating conditions</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.1.2.10
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
                <td><strong>3.34</strong></td>
                <td><strong>Electric or Air Powered Hoist: Load block is of the enclosed type and means is provided to guard against rope or load chain jamming in the load block under normal operating conditions. </strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.1.2.10
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
                <td><strong>3.35</strong></td>
                <td><strong>Rope is free of damages
•	Max of 12 randomly broken wires in 1 lay
•	4 broken wires in 1 strand of 1 lay
•	1 broken wire protruding from the core (2 for rotation resistant ropes)
•	Wear of 1/3 of the original diameter of outside individual wires
Kinking, crushing, bird caging or other distortion

</strong></td>
				<td style="text-align: center;"><strong>ASME B30.2, Sec.4.2(b)
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
                <td><strong>3.36</strong></td>
                <td><strong>Rope termination is completed at the hoist wedge anchor with a drop forged U- clip
</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec 1.2.6
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
                <td><strong>3.37</strong></td>
                <td><strong>A rope thimble is used in the eye when an eye splice is used in a rope termination (in accordance with the manufacturer’s instructions)</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.1.2.6
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
                <td><strong>3.38</strong></td>
                <td><strong>Electric and air powered hoists: Rope drum is grooved and free of surface defects that could cause rope damage (excluding hoists made for special applications</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.1.2.5
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
                <td><strong>3.39</strong></td>
                <td><strong>Hoist drum is adequately lubricated as per the hoist manufacturers manual</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.2.3.4
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
                <td><strong>3.40</strong></td>
                <td><strong>Drum capacity can accommodate the specific rope size and length</strong></td>
				<td style="text-align: center;"><strong>ASME B30.7 Sec.1.2.2(c)
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
                <td><strong>3.41</strong></td>
                <td><strong>Drum has a minimum of two wraps of rope on it</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.1.2.6(c)
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
                <td><strong>3.42</strong></td>
                <td><strong>Each drum end of the rope is anchored by a clamp attached to the drum or by a socket arrangement (approved by the manufacturer)</strong></td>
				<td style="text-align: center;"><strong>ASME B30.7 Sec.1.2.2(c2)
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
                <td><strong>3.43</strong></td>
                <td><strong>Drum flanges always extend a minimum of 1/2" (13mm) above the top layer of rope at all times</strong></td>
				<td style="text-align: center;"><strong>ASME B30.7 Sec.1.2.2(c3)
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
                <td><strong>3.44</strong></td>
                <td><strong>Labeling and manufacturer data are available and legible</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10 Sec.2.1.1
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
                <td><strong>3.45</strong></td>
                <td><strong>Hook is freely swiveling and lubricated</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.1.2.9
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
                <td><strong>3.46</strong></td>
                <td><strong>Hook's weight is clearly marked/printed on the hook</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10 Sec.1.1.1
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
                <td><strong>3.47</strong></td>
                <td><strong>Safe working load is clearly marked on the hook</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10 Sec2.1.1
(10-2.1.1)
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
                <td><strong>3.48</strong></td>
                <td><strong>Hook is not bent or twisted Max. bending or twisting not to exceed 10 degrees from plane of unbent hook or as per manufacturer recommendations
</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10 Sec1.2.1.3(c1)
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
                <td><strong>3.49</strong></td>
                <td><strong>Hook is not distorted in the throat opening
Max. allowable throat opening is 15% compared to new hook, or as per manufacturer recommendations
</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10 Sec.1.2.1.3(c2)
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
                <td><strong>3.50</strong></td>
                <td><strong>Maximum wear in the hook bowl is not exceeding 10% (compared to new hook) or as per manufacturer recommendations</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10 Sec.1.2.1.3(c3)
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
                <td><strong>3.51</strong></td>
                <td><strong>Maximum wear in the hook bowl is not exceeding 10% (compared to new hook) or as per manufacturer recommendations</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10 Sec.1.2.1.3(c3)
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
                <td><strong>3.52</strong></td>
                <td><strong>Hook is not cracked, gouged or shows nicks </strong></td>
				<td style="text-align: center;"><strong>ASME B30.10 Sec1.2.1.2(c3)
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
                <td><strong>3.53</strong></td>
                <td><strong>Hook can lock (if it is a self-locking hook)</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10 Sec.1.2.1.3(c4)
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
                <td><strong>3.54</strong></td>
                <td><strong> Hook latch is operative</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10 Sec.1.2.1.3(c5)
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
                <td><strong>3.55</strong></td>
                <td><strong>Hook is free to rotate</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10 Sec1.2.1.3(c5)
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
