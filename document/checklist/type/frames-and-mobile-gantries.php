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
    <title>INSPECTION CHECKLIST FOR A-FRAMES AND MOBILE GANTRIES</title>
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
            <strong>INSPECTION CHECKLIST FOR A-FRAMES AND MOBILE GANTRIES</strong>
        </td>
    </tr>
    <tr>
        <td>FRM.0601-1.14	</td>
        <td>Revision 02	</td>
        <td>Issue Date: 30/SEP/2020</td>
    </tr>
    <tr>
        <td class="left-align">Prepared By<br>Operations Manager</td>
        <td class="left-align">Reviewed & Approved By<br>Managing Director</td>
         
   <td><img src="../../code.png" width="80px" height="80px" alt="" /></td>
    </tr>
</table>
			</div>
	 

        <h4>A-FRAMES AND MOBILE GANTRIES</h4>
        <h4>ASME B30.16-2017, ASME B30.17-2015</h4>
		
        
		 <!--<button class="btn btn-primary no-print" onclick="preparePrint()">Print View</button>-->

         <form method="post" action="./update_checklist.php" id="checklistForm">
<div class="table-responsive">
            <table class="table table-bordered">               
                <tr>
                <th style="width: 25%;">REPORT NO</th>
                <td style="width: 25%;"><input type="text" name="report_no" value="<?php echo htmlspecialchars($row['report_no']); ?>" class="form-control"></td>
                <th style="width: 25%;">INSPECTION DATE</th>
                <td style="width: 25%;"><input type="date" name="inspection_date" value="<?php echo htmlspecialchars($row['inspection_date']); ?>" class="form-control"></td>
            </tr>
            <tr>
                <th>CLIENT’S NAME</th>
                <td><input type="text" name="client_name" value="<?php echo htmlspecialchars($row['client_name']); ?>" class="form-control"></td>
                <th>INSPECTED BY</th>
                <td><input type="text" name="inspected_by" value="<?php echo htmlspecialchars($row['inspected_by']); ?>" class="form-control"></td>
            </tr>
            <tr>
                <th>LOCATION</th>
                <td><input type="text" name="location" value="<?php echo htmlspecialchars($row['location']); ?>" class="form-control"></td>
                <th>STICKER NO.</th>
                <td><input type="text" name="sticker_no" value="<?php echo htmlspecialchars($row['sticker_no']); ?>" class="form-control"></td>
            </tr>
            <tr>
                <th>EQUIPMENT NO</th>
                <td><input type="text" name="equipment_no" value="<?php echo htmlspecialchars($row['equipment_no']); ?>" class="form-control"></td>
                <th>EQUIP.SERIAL NO.:</th>
                <td><input type="text" name="crane_serial_no" value="<?php echo htmlspecialchars($row['crane_serial_no']); ?>" class="form-control"></td>
            </tr>
            <tr>
                <th>EQUIPMENT TYPE</th>
                <td><input type="text" name="equipmenttype" value="<?php echo htmlspecialchars($row['equipmenttype']); ?>" class="form-control"></td>
                <th>CAPACITY (SWL)</th>
                <td><input type="text" name="capacity_swl" value="<?php echo htmlspecialchars($row['capacity_swl']); ?>" class="form-control"></td>
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
				<td style="text-align: center;"><strong>ASME B30.17 Sec.2.1.5
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
				<td style="text-align: center;"><strong>ASME B30.17 Sec.2.1..2(g)
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
                <td><strong>Safe working load is clearly marked on the runway and the lifting machine</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.1.1.1</strong></td>
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
                <td><strong>Rated load is clearly marked on hoist or trolley unit</strong></td>
				<td style="text-align: center;"><strong> ASME B30.16, Sec.1.1.1</strong></td>
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
				<td style="text-align: center;"><strong>ASME B30.16 Sec.1.1</strong></td>
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
                <td><strong>Crane manufacturer name, address, serial number and power ratings are clearly marked or tagged</strong></td>
				<td style="text-align: center;"><strong>ASME B30.17 Sec.1.1.1.(b)
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
                <td><strong>Controls are clearly marked with their functions and modes of operation</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.1.1.2</strong></td>
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
                <td><strong>Hoist and swing drives are capable of starts and stops with variable acceleration and deceleration required in normal operation</strong></td>
				<td style="text-align: center;"><strong>ASME B30.7 Sec.1.2.2(f)
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
                <td><strong>Hoist drum specifications are marked (rated load, drum size, rope size, rope speed (ft/min or m/s), rated power)</strong></td>
				<td style="text-align: center;"><strong>ASME B30.7 Sec1.1.3</strong></td>
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
                <td><strong>Hand Chain Hoist: Manufacturer data, serial number and safe working load are clearly displayed on the item</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.1.1.3a</strong></td>
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
                <td><strong>Electric Powered Hoist: Manufacturer data, serial number, safe working load, voltage and  phase are clearly </strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.1.1.3b</strong></td>
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
                <td><strong>1.13</strong></td>
                <td><strong>Air Powered Hoist: Manufacturer data, serial number, model, safe working load and  rated air pressure are </strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.1.1.3c</strong></td>
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
                <td><strong>1.14</strong></td>
                <td><strong>Warning signs/labels are provided on the hoist units and electrical enclosures</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.1.1.4</strong></td>
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
                    <th style="text-align: center;">2</th>
                    <th style="text-align: center;">CRANE RUNWAY AND MONORAIL TRACKS</th>
					<th style="text-align: center;"> </th>                    
                    <th style="text-align: center;">PASS</th>
                    <th style="text-align: center;">FAIL</th>
                    <th style="text-align: center;">NA</th>
                    <th> </th>
                </tr>
				
 <tr>
                <td><strong>2.1</strong></td>
                <td><strong>Structure is vibration free under normal operating condition</strong></td>
				<td style="text-align: center;"><strong>ASME B30.17 Sec.1.3.1</strong></td>
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
                <td><strong>2.2</strong></td>
                <td><strong>Monorail end stops are installed and in good condition</strong></td>
				<td style="text-align: center;"><strong>ASME B30.17 Sec.1.3.1g</strong></td>
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
                <td><strong>2.3</strong></td>
                <td><strong>Jib crane end stops are installed and in good condition</strong></td>
				<td style="text-align: center;"><strong>ASME B30.17 Sec.1.3.1g</strong></td>
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
                <td><strong>2.4</strong></td>
                <td><strong>Tracks are properly installed and aligned</strong></td>
				<td style="text-align: center;"><strong>ASME B30.17 Sec.1.3.1c</strong></td>
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
                <td><strong>2.5</strong></td>
                <td><strong>Crane runways or monorail tracks are fastened and Secured to a supporting structure</strong></td>
				<td style="text-align: center;"><strong>ASME B30.17 Sec.1.3.2</strong></td>
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
                <td><strong>2.6</strong></td>
                <td><strong>All welded members are free of defects and not corroded</strong></td>
				<td style="text-align: center;"><strong>ASME B30.17 Sec.1.3.4</strong></td>
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
                    <th style="text-align: center;">3</th>
                    <th style="text-align: center;">GUARDS FOR MOVING PARTS</th>
					<th style="text-align: center;"> </th>                    
                    <th style="text-align: center;">PASS</th>
                    <th style="text-align: center;">FAIL</th>
                    <th style="text-align: center;">NA</th>
                    <th> </th>
                </tr>
				
 <tr>
                <td><strong>3.1</strong></td>
                <td><strong>Guards protect moving parts such as gears, chains, chain sprockets</strong></td>
				<td style="text-align: center;"><strong>ASME B30.17 Sec.1.7.1</strong></td>
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
                <td><strong>3.2</strong></td>
                <td><strong>Guards protect ropes where liable to come in contact with conductors</strong></td>
				<td style="text-align: center;"><strong>ASME B30.17 Sec.1.7.2</strong></td>
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
                <td><strong>3.3</strong></td>
                <td><strong>Guards are provided to prevent contact between crane bridge or runway conductors and hoisting ropes.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.17 Sec.1.7.2</strong></td>
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
                    <th style="text-align: center;">4</th>
                    <th style="text-align: center;">HOISTING BRAKES</th>
					<th style="text-align: center;"> </th>                    
                    <th style="text-align: center;">PASS</th>
                    <th style="text-align: center;">FAIL</th>
                    <th style="text-align: center;">NA</th>
                    <th> </th>
                </tr>
				
 <tr>
                <td><strong>4.1</strong></td>
                <td><strong>Hand chain operated hoist : Hoist automatically stops and holds lifted load when the actuating force is removed</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.1.2.11a</strong></td>
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
                <td><strong>4.2</strong></td>
                <td><strong>Electric Powered Hoist : Braking system will stop and hold the load hook when controls are released under any load </strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.1.2.11(b1-</strong></td>
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
                <td><strong>4.3</strong></td>
                <td><strong>Air Powered Hoist : Braking system will stop and hold the load hook when controls are released under any load </strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.1.2.11(c1-</strong></td>
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
                <td><strong>4.4</strong></td>
                <td><strong>An electric hoist stops and holds the load block in the event of power failure</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.1.2.11(b1-</strong></td>
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
                <td><strong>4.5</strong></td>
                <td><strong>An air hoist stops and holds the load block in the event of air pressure loose</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.1.2.11(c1-</strong></td>
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
                <td><strong>4.6</strong></td>
                <td><strong>Braking systems has means for adjustment to compensate for wear</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.16.1.2.11(b3)</strong></td>
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
                    <th style="text-align: center;">5</th>
                    <th style="text-align: center;">ELECTRICAL EQUIPMENT</th>
					<th style="text-align: center;"> </th>                    
                    <th style="text-align: center;">PASS</th>
                    <th style="text-align: center;">FAIL</th>
                    <th style="text-align: center;">NA</th>
                    <th> </th>
                </tr>
	<tr>
                <td><strong>5.0</strong></td>
                <td><strong>Control circuit voltage does not  exceed 600v for AC or DC</strong></td>
				<td style="text-align: center;"><strong>ASME B30.17 Sec.1.9.1(b)</strong></td>
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
                <td><strong>5.1</strong></td>
                <td><strong>Controls are clearly marked with their functions and modes of operation</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.1.1.2</strong></td>
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
                <td><strong>5.2</strong></td>
                <td><strong>Pendant control station is constructed to prevent electrical conductors strain</strong></td>
				<td style="text-align: center;"><strong>ASME B30.17 Sec.1.9.1(d)</strong></td>
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
                <td><strong>5.3</strong></td>
                <td><strong>Push button enclosure is grounded</strong></td>
				<td style="text-align: center;"><strong>ASME B30.17 Sec.1.9.1(e)</strong></td>
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
                <td><strong>5.4</strong></td>
                <td><strong>Push button enclosure is marked for identification of function</strong></td>
				<td style="text-align: center;"><strong>ASME B30.17 Sec.1.9.1(e)</strong></td>
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
                <td><strong>5.5</strong></td>
                <td><strong>Parts of electrical equipment are enclosed and are not exposed to inadvertent contact under normal operating </strong></td>
				<td style="text-align: center;"><strong>ASME B30.17 Sec.1.9.2(a)</strong></td>
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
                <td><strong>5.6</strong></td>
                <td><strong>Live parts of electrical equipment are protected from direct exposure to grease and oil and protected from dirt and moisture</strong></td>
				<td style="text-align: center;"><strong>ASME B30.17 Sec.1.9.2(b)</strong></td>
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
                <td><strong>5.7</strong></td>
                <td><strong>Guards on live parts are not deformed or/and in contact</strong></td>
				<td style="text-align: center;"><strong>ASME B30.17 Sec.1.9.2(c)</strong></td>
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
                <td><strong>5.8</strong></td>
                <td><strong>Floor operated cranes controllers return to off position when released</strong></td>
				<td style="text-align: center;"><strong>ASME B30.17 Sec.1.9.3(b1)</strong></td>
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
                <td><strong>5.9</strong></td>
                <td><strong>Pendant push buttons that control motion return to off position when pressure is released</strong></td>
				<td style="text-align: center;"><strong>ASME B30.17 Sec.1.9.3(b2)</strong></td>
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
                    <th style="text-align: center;">6</th>
                    <th style="text-align: center;">LOAD CHAIN, ROPE AND HOOK BLOCK</th>
					<th style="text-align: center;"> </th>                    
                    <th style="text-align: center;">PASS</th>
                    <th style="text-align: center;">FAIL</th>
                    <th style="text-align: center;">NA</th>
                    <th> </th>
                </tr>
	<tr>
                <td><strong>6.0</strong></td>
                <td><strong>Chain passes over all load sprockets without binding</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.1.2.8</strong></td>
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
                <td><strong>6.1</strong></td>
                <td><strong>Hand Operated Chain : Chain length for extension (stretch)  tolerance is no longer than 2.5% of unused chain or as per manufacturer recommendations</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.2.5.2(a)</strong></td>
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
                <td><strong>6.2</strong></td>
                <td><strong>Power Operated Chain : Chain length for extension (stretch)  tolerance is no longer than 1.5% of unused chain or as per manufacturer recommendations</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.2.5.2(a)</strong></td>
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
                <td><strong>6.3</strong></td>
                <td><strong>The chain does not suffer from gouges, nicks, corrosion, weld spatter or distorted links (Judgement to be used as to the suitability or otherwise of using chain with these deficiencies)</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.2.5.2(b)</strong></td>
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
                <td><strong>6.4</strong></td>
                <td><strong>The chain does not bind, jump or gets noisy when hoist is operated</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.2.6.1(b)</strong></td>
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
                <td><strong>6.5</strong></td>
                <td><strong>The chain is not stretched or elongated more than 1/4" (6.3 mm) in 12" (305 mm) with reference to the manufacturer's manual (roller chain)</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.2.6.1(c1)</strong></td>
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
                <td><strong>6.6</strong></td>
                <td><strong>The chain is not twisted more than 15 degree in 5 ft. (1.52 m) sections (roller chain).</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.2.6.1(c2)</strong></td>
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
                <td><strong>6.7</strong></td>
                <td><strong>The roller chain pins, links and rollers move freely and are not corroded, pitted, discolored or damaged.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.2.6.1(d)</strong></td>
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
                <td><strong>6.8</strong></td>
                <td><strong>Fitted sling or chain would be retained slack in the bowl of the hook where latches are provided.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.1.2.9</strong></td>
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
                <td><strong>6.9</strong></td>
                <td><strong>Hand operated hoist : Load block is provided with a guard against load chain jamming in the load block under normal operating conditions</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.1.2.10</strong></td>
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
                <td><strong>6.10</strong></td>
                <td><strong>Electric or Air Powered Hoist: Load block is of the enclosed type and means is provided to guard against rope or load chain jamming in the load block under </strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.1.2.10</strong></td>
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
                <td><strong>6.11</strong></td>
                <td><strong>Rope is free of damages
•	Max of 12 randomly broken wires in 1 lay
•	4 broken wires in 1 strand of 1 lay
•	1 broken wire protruding from the core (2 for rotation resistant ropes)
•	Wear of 1/3 of the original diameter of outside individual wires
Kinking, crushing, birdcaging or other distortion
</strong></td>
				<td style="text-align: center;"><strong>ASME B30.7, Sec.2.4.1(c2)</strong></td>
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
                <td><strong>6.12</strong></td>
                <td><strong>Rope termination is completed at the hoist wedge anchor with a drop forged U- clip</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.1.2.6</strong></td>
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
                <td><strong>6.13</strong></td>
                <td><strong>Pendant push buttons that control motion return to off position when pressure is released</strong></td>
				<td style="text-align: center;"><strong>ASME B30.17 Sec.1.9.3(b2)</strong></td>
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
                    <th style="text-align: center;">7</th>
                    <th style="text-align: center;">ROPE DRUM</th>
					<th style="text-align: center;"> </th>                    
                    <th style="text-align: center;">PASS</th>
                    <th style="text-align: center;">FAIL</th>
                    <th style="text-align: center;">NA</th>
                    <th> </th>
                </tr>
	<tr>
                <td><strong>7.0</strong></td>
                <td><strong>Electric and air powered hoists : Rope drum is grooved and free of surface defects that could cause rope damage (excluding hoists made for special applications)</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.1.2.5</strong></td>
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
                <td><strong>7.1</strong></td>
                <td><strong>Hoist drum specifications are marked (rated load, drum size, rope size, rope speed (ft/min or m/s), rated power)</strong></td>
				<td style="text-align: center;"><strong>ASME B30.7 Sec.1.1.3</strong></td>
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
                <td><strong>7.2</strong></td>
                <td><strong>Hand Chain Hoist: Manufacturer data, serial number and safe working load are clearly displayed on the item</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.1.1.3a</strong></td>
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
                <td><strong>7.3</strong></td>
                <td><strong>Electric Powered Hoist: Manufacturer data, serial number, safe working load, voltage and phase are clearly displayed on the item</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.1.1.3b</strong></td>
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
                <td><strong>7.4</strong></td>
                <td><strong>Air Powered Hoist: Manufacturer data, serial number, model, safe working load and rated air pressure are clearly displayed on the item</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.1.1.3c</strong></td>
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
                <td><strong>7.5</strong></td>
                <td><strong>Warning signs/labels are provided on the hoist units and electrical enclosures</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.1.1.4</strong></td>
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
                <td><strong>7.6</strong></td>
                <td><strong>Hoist drum is adequately lubricated as per the hoist manufacturers manual</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.2.3.</strong></td>
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
                <td><strong>7.7</strong></td>
                <td><strong>Drum has a minimum of two wraps of rope on it</strong></td>
				<td style="text-align: center;"><strong>ASME B30.716 Sec.1.2.6(c)</strong></td>
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
                <td><strong>7.8</strong></td>
                <td><strong>Structure is vibration free under normal operating condition</strong></td>
				<td style="text-align: center;"><strong>ASME B30.17 Sec.1.3.1(b)</strong></td>
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
                <td><strong>7.9</strong></td>
                <td><strong>Monorail end stops are installed and in good condition</strong></td>
				<td style="text-align: center;"><strong>ASME B30.17 Sec.1.4.2, Sec 1.5.3</strong></td>
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
                <td><strong>7.10</strong></td>
                <td><strong>Jib crane end stops are installed and in good condition</strong></td>
				<td style="text-align: center;"><strong>ASME B30.17 Sec.1.4.2, Sec 1.5.3</strong></td>
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
                <td><strong>7.11</strong></td>
                <td><strong>Tracks are properly installed and aligned</strong></td>
				<td style="text-align: center;"><strong>ASME B30.17 Sec.1.3.1  Sec 1.4.1</strong></td>
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
                <td><strong>7.12</strong></td>
                <td><strong>Crane runways or monorail tracks are fastened and Secured to a supporting structure</strong></td>
				<td style="text-align: center;"><strong>ASME B30.17 Sec.1.3.2</strong></td>
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
                <td><strong>7.13</strong></td>
                <td><strong>All welded members are free of defects and not corroded</strong></td>
				<td style="text-align: center;"><strong>ASME B30.17 Sec.1.3.4</strong></td>
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
                <td><strong>7.14</strong></td>
                <td><strong>Guards protect moving parts such as gears, chains, chain sprockets</strong></td>
				<td style="text-align: center;"><strong>ASME B30.17 Sec.1.11.1</strong></td>
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
                <td><strong>7.15</strong></td>
                <td><strong>Guards protect ropes where liable to come in contact with conductors</strong></td>
				<td style="text-align: center;"><strong>ASME B30.17 Sec.1.11.2(a)</strong></td>
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
                <td><strong>7.16</strong></td>
                <td><strong>Guards are provided to prevent contact between crane bridge or runway conductors and hoisting ropes.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.17 Sec.1.11.2(b)</strong></td>
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
                <td><strong>7.17</strong></td>
                <td><strong>Hand chain operated Hoist: Hoist automatically stops and holds lifted load when the actuating force is removed</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.1.2.11a</strong></td>
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
                <td><strong>7.18</strong></td>
                <td><strong>Electric Powered Hoist: Braking system will stop and hold the load hook when controls are released under any load condition</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.1.2.11(b1-b)</strong></td>
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
                <td><strong>7.19</strong></td>
                <td><strong>Air Powered Hoist: Braking system will stop and hold the load hook when controls are released under any load condition</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.1.2.11(c1-a)</strong></td>
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
                <td><strong>7.20</strong></td>
                <td><strong>An electric hoist stops and holds the load block in the event of power failure</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.1.2.11(b1-c)</strong></td>
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
                <td><strong>7.21</strong></td>
                <td><strong>An air hoist stops and holds the load block in the event of air pressure loose</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.1.2.11(c1-b)</strong></td>
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
                <td><strong>7.22</strong></td>
                <td><strong>Braking systems has means for adjustment to compensate for wear</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.1.2.11(b3/c)</strong></td>
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
                <td><strong>7.23</strong></td>
                <td><strong>Control circuit voltage does not exceed 600v for AC or DC</strong></td>
				<td style="text-align: center;"><strong>ASME B30.17 Sec. 1.14.1(b)</strong></td>
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
                <td><strong>7.24</strong></td>
                <td><strong>Push button enclosure is grounded</strong></td>
				<td style="text-align: center;"><strong>ASME B30.17 
Sec. 1.14.1(e)
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
                <td><strong>7.25</strong></td>
                <td><strong>Push button enclosure is marked for identification of function</strong></td>
				<td style="text-align: center;"><strong>ASME B30.17 
Sec. 1.14.1(e)
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
                <td><strong>7.26</strong></td>
                <td><strong>Parts of electrical equipment are enclosed and are not exposed to inadvertent contact under normal operating conditions</strong></td>
				<td style="text-align: center;"><strong>ASME B30.17 
Sec. 1.14.2(a)
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
                <td><strong>7.27</strong></td>
                <td><strong>Live parts of electrical equipment are protected from direct exposure to grease and oil and protected from dirt and moisture</strong></td>
				<td style="text-align: center;"><strong>ASME B30.17 
Sec. 1.14.2(b)
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
                <td><strong>7.28</strong></td>
                <td><strong>Guards on live parts are not deformed or/and in contact</strong></td>
				<td style="text-align: center;"><strong>ASME B30.17 Sec.1.14.2(c)</strong></td>
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
                <td><strong>7.29</strong></td>
                <td><strong>Floor operated cranes controllers return to off position when released</strong></td>
				<td style="text-align: center;"><strong>ASME B30.17 Sec.1.14.3(c1)</strong></td>
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
                <td><strong>7.30</strong></td>
                <td><strong>Pendant push buttons that control motion return to off position when pressure is released</strong></td>
				<td style="text-align: center;"><strong>ASME B30.17 Sec.1.14.3(c)</strong></td>
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
                <td><strong>7.31</strong></td>
                <td><strong>Chain passes over all load sprockets without binding</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.1.2.8</strong></td>
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
                <td><strong>7.32</strong></td>
                <td><strong>Hand Operated Chain: Chain length for extension (stretch) tolerance is no longer than 2.5% of unused chain or as per manufacturer recommendations</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.2.5.2(a)</strong></td>
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
                <td><strong>7.33</strong></td>
                <td><strong>Power Operated Chain: Chain length for extension (stretch) tolerance is no longer than 1.5% of unused chain or as per manufacturer recommendations</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.2.5.2(a)</strong></td>
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
                <td><strong>7.34</strong></td>
                <td><strong>The chain does not suffer from gouges, nicks, corrosion, weld spatter or distorted links (Judgement to be used as to the suitability or otherwise of using chain with these deficiencies)</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.2.5.2(b)</strong></td>
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
                <td><strong>7.35</strong></td>
                <td><strong>The chain does not bind jump or gets noisy when hoist is operated</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.2.6.1(b)</strong></td>
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
                <td><strong>7.36</strong></td>
                <td><strong>The chain is not stretched or elongated more than 1/4" (6.3 mm) in 12" (305 mm) with reference to the manufacturer's manual (roller chain)</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.2.6.1(c1)</strong></td>
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
                <td><strong>7.37</strong></td>
                <td><strong>The chain is not twisted more than 15 degree in 5 ft (1.52 m) sections (roller chain)</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.2.6.1(c2)</strong></td>
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
                <td><strong>7.38</strong></td>
                <td><strong>The roller chain pins, links and rollers move freely and are not corroded, pitted, discolored or damaged</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.2.6.1(d)</strong></td>
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
                <td><strong>7.39</strong></td>
                <td><strong>Fitted sling or chain would be retained slack in the bowl of the hook where latches are provided</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.1.2.9</strong></td>
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
                <td><strong>7.40</strong></td>
                <td><strong>Hand operated hoist: Load block is provided with a guard against load chain jamming in the load block under normal operating conditions</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.1.2.10</strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[94][]" id="checkbox4" value="PASS" class="large-checkbox">
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[94][]" id="checkbox5" value="FAIL" class="large-checkbox">
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[94][]" id="checkbox6" value="NA" class="large-checkbox">
</td>
<td>
    <input type="text" name="checklist_remark[94]">
</td>
            </tr>		
 <tr>
                <td><strong>7.41</strong></td>
                <td><strong>Electric or Air Powered Hoist: Load block is of the enclosed type and means is provided to guard against rope or load chain jamming in the load block under normal operating conditions.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.1.2.10</strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[95][]" id="checkbox4" value="PASS" class="large-checkbox">
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[95][]" id="checkbox5" value="FAIL" class="large-checkbox">
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[95][]" id="checkbox6" value="NA" class="large-checkbox">
</td>
<td>
    <input type="text" name="checklist_remark[95]">
</td>
            </tr>
			
			<tr>
                <td><strong>7.42</strong></td>
                <td><strong>Rope termination is completed at the hoist wedge anchor with a drop forged U- clip</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.2.5.2(a)</strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[96][]" id="checkbox4" value="PASS" class="large-checkbox">
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[96][]" id="checkbox5" value="FAIL" class="large-checkbox">
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[96][]" id="checkbox6" value="NA" class="large-checkbox">
</td>
<td>
    <input type="text" name="checklist_remark[96]">
</td>
            </tr>
			<tr>
                <td><strong>7.43</strong></td>
                <td><strong>A rope thimble is used in the eye when an eye splice is used in a rope termination (in accordance with the manufacturer’s instructions)</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.1.2.6</strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[97][]" id="checkbox4" value="PASS" class="large-checkbox">
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[97][]" id="checkbox5" value="FAIL" class="large-checkbox">
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[97][]" id="checkbox6" value="NA" class="large-checkbox">
</td>
<td>
    <input type="text" name="checklist_remark[97]">
</td>
            </tr>
			<tr>
                <td><strong>7.44</strong></td>
                <td><strong>Electric and air powered hoists: Rope drum is grooved and free of surface defects that could cause rope damage (excluding hoists made for special applications</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.1.2.5</strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[98][]" id="checkbox4" value="PASS" class="large-checkbox">
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[98][]" id="checkbox5" value="FAIL" class="large-checkbox">
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[98][]" id="checkbox6" value="NA" class="large-checkbox">
</td>
<td>
    <input type="text" name="checklist_remark[98]">
</td>
            </tr>
			<tr>
                <td><strong>7.45</strong></td>
                <td><strong>Hoist drum is adequately lubricated as per the hoist manufacturers manual</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.2.3.4</strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[99][]" id="checkbox4" value="PASS" class="large-checkbox">
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[99][]" id="checkbox5" value="FAIL" class="large-checkbox">
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[99][]" id="checkbox6" value="NA" class="large-checkbox">
</td>
<td>
    <input type="text" name="checklist_remark[99]">
</td>
            </tr>
		<tr>
                <td><strong>7.46</strong></td>
                <td><strong>Drum capacity can accommodate the specific rope size and length</strong></td>
				<td style="text-align: center;"><strong>ASME B30.7 Sec.1.2.2(c)</strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[100][]" id="checkbox4" value="PASS" class="large-checkbox">
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[100][]" id="checkbox5" value="FAIL" class="large-checkbox">
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[100][]" id="checkbox6" value="NA" class="large-checkbox">
</td>
<td>
    <input type="text" name="checklist_remark[100]">
</td>
            </tr>	
	<tr>
                <td><strong>7.47</strong></td>
                <td><strong>Drum has a minimum of two wraps of rope on it</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.1.2.6(c)</strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[101][]" id="checkbox4" value="PASS" class="large-checkbox">
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[101][]" id="checkbox5" value="FAIL" class="large-checkbox">
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[101][]" id="checkbox6" value="NA" class="large-checkbox">
</td>
<td>
    <input type="text" name="checklist_remark[101]">
</td>
            </tr>
	<tr>
                <td><strong>7.48</strong></td>
                <td><strong>Each drum end of the rope is anchored by a clamp attached to the drum or by a socket arrangement (approved by the manufacturer)</strong></td>
				<td style="text-align: center;"><strong>ASME B30.7 Sec.1.2.2(c2)</strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[102][]" id="checkbox4" value="PASS" class="large-checkbox">
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[102][]" id="checkbox5" value="FAIL" class="large-checkbox">
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[102][]" id="checkbox6" value="NA" class="large-checkbox">
</td>
<td>
    <input type="text" name="checklist_remark[102]">
</td>
            </tr>
	<tr>
                <td><strong>7.49</strong></td>
                <td><strong>Drum flanges always extend a minimum of 1/2" (13mm) above the top layer of rope at all times</strong></td>
				<td style="text-align: center;"><strong>ASME B30.7 Sec.1.2.2(c3)</strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[103][]" id="checkbox4" value="PASS" class="large-checkbox">
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[103][]" id="checkbox5" value="FAIL" class="large-checkbox">
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[103][]" id="checkbox6" value="NA" class="large-checkbox">
</td>
<td>
    <input type="text" name="checklist_remark[103]">
</td>
            </tr>
<tr>
                    <th style="text-align: center;">8</th>
                    <th style="text-align: center;">HOOKS</th>
					<th style="text-align: center;"> </th>
                    
                    <th style="text-align: center;">PASS</th>
                    <th style="text-align: center;">FAIL</th>
                    <th style="text-align: center;">NA</th>
                    <th> </th>
                </tr>
	<tr>
                <td><strong>8.0</strong></td>
                <td><strong>Labeling and manufacturer data are available and legible</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10 Sec.2.1.1</strong></td>
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
                <td><strong>8.1</strong></td>
                <td><strong>Hook is freely swiveling and lubricated</strong></td>
				<td style="text-align: center;"><strong>ASME B30.16 Sec.1.2.9</strong></td>
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
                <td><strong>8.2</strong></td>
                <td><strong>Hook's weight is clearly marked/printed on the hook</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10 Sec.1.1.1</strong></td>
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
                <td><strong>8.3</strong></td>
                <td><strong>Safe working load is clearly marked on the hook</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10 Sec2.1.1</strong></td>
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
                <td><strong>8.4</strong></td>
                <td><strong>Hook is not bent or twisted Max. bending or twisting not to exceed 10 degrees from plane of unbent hook or as per manufacturer recommendations</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10 Sec1.2.1.3(c1)</strong></td>
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
                <td><strong>8.5</strong></td>
                <td><strong>Hook is not distorted in the throat opening
Max. allowable throat opening is 15% compared to new hook, or as per manufacturer recommendations
</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10 Sec.1.2.1.3(c2)</strong></td>
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
                <td><strong>8.6</strong></td>
                <td><strong>Maximum wear in the hook bowl is not exceeding 10% (compared to new hook) or as per manufacturer recommendations</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10 Sec.1.2.1.3(c3)</strong></td>
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
                <td><strong>8.7</strong></td>
                <td><strong>Maximum wear in the hook bowl is not exceeding 10% (compared to new hook) or as per manufacturer recommendations</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10 Sec.1.2.1.3(c3)</strong></td>
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
                <td><strong>8.8</strong></td>
                <td><strong>Hook is not cracked, gouged or shows nicks</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10 Sec1.2.1.2(c3)</strong></td>
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
                <td><strong>8.9</strong></td>
                <td><strong>Hook can lock (if it is a self-locking hook)</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10 Sec.1.2.1.3(c4)</strong></td>
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
                <td><strong>8.10</strong></td>
                <td><strong>Hook latch is operative</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10 Sec.1.2.1.3(c5)</strong></td>
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
                <td><strong>8.11</strong></td>
                <td><strong>Hook is free to rotate</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10 Sec1.2.1.3(c5)</strong></td>
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
                <textarea style="width: 100%; height: 120px; box-sizing: border-box;" name="recommendations" class="form-control"><?php echo htmlspecialchars($row['recommendations']); ?></textarea>
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
