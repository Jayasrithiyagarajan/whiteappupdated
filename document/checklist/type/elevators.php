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
    <title>INSPECTION CHECKLIST FOR ELEVATORS AND ESCALATORS </title>
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link href="style.css" rel="stylesheet">
    <style>

.container{
            max-width: 934px;

        }

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
            <strong>INSPECTION CHECKLIST FOR ELEVATORS AND ESCALATORS</strong>
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
                <td colspan="3" style="text-align: center;"><strong>INSPECTION CHECKLIST FOR ELEVATORS AND ESCALATORS</strong></td>
				</tr>
            <tr>
                <td style="width: 25%; text-align: center;"><strong>FRM.0601-1.2</strong></td>
                <td style="width: 25%; text-align: center;"> <strong>Revision 02</strong></td>
                
                <td style="width: 25%; text-align: center;"> <strong>Issue Date: 30/SEP/2020</strong></td>
            </tr>
			</tbody>
			</table> -->
			
			</div>

        <h4>ELEVATORS AND ESCALATORS</h4>
        <h4>ASME A17.1</h4>
		
        
		 <!--<button class="btn btn-primary no-print" onclick="preparePrint()">Print View</button>-->
         <!-- <?php if (isset($row)): ?> -->
         <div class="table-responsive">


        <table class="table table-bordered">
            <tr>
                <th style="width: 25%;">REPORT NO</th>
                <td style="width: 25%;"><strong><?php echo $row['report_no']; ?></strong></td>
                <th style="width: 25%;">INSPECTION DATE</th>
                <td style="width: 25%;"><strong><?php echo date('F j, Y', strtotime($row['inspection_date'])); ?></strong></td>
            </tr>
            <tr>
                <th>CLIENT’S NAME</th>
                <td><strong><?php echo $row['client_name']; ?></strong></td>
                <th>INSPECTED BY</th>
                <td><strong><?php echo $row['inspected_by']; ?></strong></td>
            </tr>
            <tr>
                <th>LOCATION</th>
                <td><strong><?php echo $row['location']; ?></strong></td>
                <th>STICKER NO.</th>
                <td><strong><?php echo $row['sticker_no']; ?></strong></td>
            </tr>
            <tr>
                <th>EQUIPMENT NO</th>
                <td><strong><?php echo $row['equipment_no']; ?></strong></td>
                <th>EQUIP.SERIAL NO.:</th>
                <td><strong><?php echo $row['crane_serial_no']; ?></strong></td>
            </tr>
            <tr>
                <th>EQUIPMENT TYPE</th>
                <td><strong><?php echo $row['equipmenttype']; ?></strong></td>
                <th>CAPACITY (SWL)</th>
                <td><strong><?php echo $row['capacity_swl']; ?></strong></td>
            </tr>
        </table>

</div>
<!-- <?php endif; ?> -->

        
<form method="post" action="./update_checklist.php" id="checklistForm">
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
                    <th style="text-align: center;"></th>
                    <th style="text-align: center;">GENERAL REQUIREMENTS</th>
                    <th style="text-align: center;"></th>					
                    <th style="text-align: center;" colspan="3"></th>                    
                    <th style="text-align: center;"></th>
                </tr>
				
				<tr>
                    <th style="text-align: center;">1</th>
                    <th style="text-align: center;">HYDRAULIC ELEVATOR</th>
					<th style="text-align: center;"> </th>                    
                    <th style="text-align: center;"></th>
                    <th style="text-align: center;"></th>
                    <th style="text-align: center;"></th>
                    <th> </th>
                </tr>
					<tr>
                    <th style="text-align: center;">1.1</th>
                    <th style="text-align: center;">INSIDE OF CAR</th>
					<th style="text-align: center;"> </th>                    
                    <th style="text-align: center;">PASS</th>
                    <th style="text-align: center;">FAIL</th>
                    <th style="text-align: center;">NA</th>
                    <th> </th>
                </tr>
				</thead>
 
                <tbody>

 <tr>
                <td><strong>1.1.1</strong></td>
                <td><strong> Door reopening device is operating correctly </strong></td>
				<td style="text-align: center;"><strong>ASME A17.1 
Sec. (2.13(3.13),
8.11.2.1.1a, 8.11.3.1.1a)

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
                <td><strong>1.1.2</strong></td>
                <td><strong>  Emergency stop switches are not provided on passenger elevators but are provided on freight elevators, in the car and in or adjacent to each car operating panel </strong></td>
				<td style="text-align: center;"><strong> ASME A17.1 Sec. (3.26.4.2a,
3.26.4.2f, 8.11.3.1.1b)

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
                <td><strong>1.1.3</strong></td>
                <td><strong> All operating control devices are of the enclosed electric type   </strong></td>
				<td style="text-align: center;"><strong>ASME A17.1 
Sec. (2.26.1.1(3.26.1),
  3.26.3, 8.11.3.1.1c)
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
                <td><strong>1.1.4</strong></td>
                <td><strong> Sills are of the correct type and are of sufficient strength and clearance with adjoining car platform or hoist way sill  (min. clearance 13mm)  </strong></td>
				<td style="text-align: center;"><strong> ASME A17.1
Sec. (2.5.1(3.5), 2.11.10.3 (3.11), 2.11.11.1,
2.11.13.1, 2.15.16 (3.15), 8.11.3.1.1d)
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
                <td><strong>1.1.5</strong></td>
                <td><strong>Door reopening device is operating correctly</strong></td>
				<td style="text-align: center;"><strong>    ASME A17.1 Sec. (2.13(3.13),
8.11.2.1.1a,
8.11.3.1.1a)
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
                <td><strong>1.1.6</strong></td>
                <td><strong>Emergency stop switches are not provided on passenger elevators but are provided on freight elevators, in the car and in or adjacent to each car operating panel</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1 Sec. (3.26.4.2a,
3.26.4.2f,
8.11.3.1.1b)
 </strong></td>
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
                <td><strong>1.1.7</strong></td>
                <td><strong>All operating control devices are of the enclosed electric type</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1 
Sec.(2.26.1.1(3.26.1),
3.26.3,
8.11.3.1.1c)
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
                <td><strong>1.1.8</strong></td>
                <td><strong>Sills are of the correct type and are of sufficient strength and clearance with adjoining car platform or hoist way sill  (min. clearance 13mm)</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.5.1(3.5),
2.11.10.3 (3.11),
2.11.11.1,
2.11.13.1, 2.15.16
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
                <td><strong>1.1.9</strong></td>
                <td><strong>Car has minimum of two lamps (min. of 50 lux for passenger and 25 lux for freight elevators) (Passenger elevators shall have auxiliary lighting which automatically turns on if normal power fails)</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1 
Sec. (3.14, 8.11.3.1.1e)
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
                <td><strong>1.1.10</strong></td>
                <td><strong>Car emergency communication signal to authorized and emergency personnel is available and working</strong></td>
				<td style="text-align: center;"><strong> ASME A17.1
Sec. (2.27.1 (3.27),
8.11.3.1.1f)

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
                <td><strong>1.1.11</strong></td>
                <td><strong>Each car door or gate has electric contacts or interlocks (where required) to prevent operation of the driving machine when the door or gate is  open</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.12.7.3 (3.12),
2.13.2.1 (3.13),
2.14.4, 2.14.6
(3.14), 2.26.2
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
                <td><strong>1.1.12</strong></td>
                <td><strong> Force required to prevent door closing  does not exceed 30 ft.lb</strong></td>
				<td style="text-align: center;"><strong> ASME A17.1 
  Sec. (2.13.4.2.3,
8.11.3.1.1h)
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
                <td><strong>1.1.13</strong></td>
                <td><strong>An Identification Plate is provided with the following items are clearly marked: Manufacturer name & address, weight of the empty platform, date of manufacture, number of personnel allowed on the platform, certificate number of compliance to the design, construction and testing.  </strong></td>
				<td style="text-align: center;"><strong>ASME A17.1    
Sec. (2.13.3 (3.13),
8.11.3.1.1i)
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
                <td><strong>1.1.14</strong></td>
                <td><strong>Power opening of doors or gates only occurs when the car is at rest at the landing, or in the landing zone</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.12.5 (3.12),
2.26.1.6, 2.26.9
(2.26.9.3), 3.26.3,
8.11.3.1.1j)
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
                <td><strong>1.1.15</strong></td>
                <td><strong>Car vision panels and glass car doors meet specifications (not more than 0.1 sq. m. and no panel more than 150mm wide, glass to be laminated or safety glass or safety plastic)</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
 Sec. (2.14.2.5, 2.14.5.8
(3.14), 8.11.3.1.1k)
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
                <td><strong>1.1.16</strong></td>
                <td><strong>Car enclosure is in compliance with the required equipment (specification)</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.14 (3.14),
2.29.1 (3.27),
8.3.7, 8.7.2.14,
8.7.3.13,8.11.3.1.1l)
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
                <td><strong>1.1.17</strong></td>
                <td><strong>Ventilation (natural or forced) complies with the various opening and size requirements as well as air change volume per minute (for forced ventilation)</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1 
Sec. (2.14.2.3, 2.14.3.3
(3.14), 8.11.3.1.1n)
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
                <td><strong>1.1.18</strong></td>
                <td><strong>Signs and operating device symbols are installed and legible</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1 (2.26.12, 8.11.3.1.1b)
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
                <td><strong>1.1.19</strong></td>
                <td><strong>Signs and operating device symbols are installed and legible</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.26.12, 8.11.3.1.1b)
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
                <td><strong>1.1.20</strong></td>
                <td><strong>Rated load, platform area and data plate are available and legible</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1 
Sec. (2.16 (3.16),
8.11.3.1.1p)
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
                <td><strong>1.1.21</strong></td>
                <td><strong>Standby power operation (at least one elevator at a time) with rated load in the event of power supply failure (transfer from normal to standby supply is automatic)
</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.27.2 (3.27),
8.11.2.2.7
(8.11.3.2.3f),
8.11.3.1.1q)

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
                <td><strong>1.1.22</strong></td>
                <td><strong>Restricted opening of car or hoist way doors (4" max) is possible outside the unlocking zone</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1 
Sec. (2.12.5 (3.12),
8.11.3.1.1r)
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
                <td><strong>1.1.23</strong></td>
                <td><strong>Car ride is smooth in acceleration and deceleration throughout its travel</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (3.15, 3.23.1,
8.6.1.6.2 (8.6.5),
8.11.3.1.1s)
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
                    <th style="text-align: center;">1.2</th>
                    <th style="text-align: center;">MACHINE ROOM</th>
					<th style="text-align: center;"> </th>
                    
                    <th style="text-align: center;">PASS</th>
                    <th style="text-align: center;">FAIL</th>
                    <th style="text-align: center;">NA</th>
                    <th> </th>
                </tr>
				
 
                <tbody>
				<tr>

 <tr>
                <td><strong>1.2.1</strong></td>
                <td><strong> Access to the machine space is in conformance with the type of access, location , and combustibility allowed</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1 
Sec. (3.1,3.7, 8.11.3.1.2a)
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
                <td><strong>1.2.2</strong></td>
                <td><strong>  Emergency stop switches are not provided on passenger elevators but are provided on freight elevators, in the car and in or adjacent to each car operating panel Minimum headroom clearance is either 84" , 53", 42", or 35" depending on type and location of machine room / hoist way</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1 
Sec. (2.4.7 (3.7),
8.11.3.1.2b)
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
                <td><strong>1.2.3</strong></td>
                <td><strong>Electric lighting in the machine room is not less than 200 lux at floor level and the control switch is at the lock - jamb side of the access door wherever practicable.</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.7.5.1 (3.7),
8.11.3.1.2c)
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
                <td><strong>1.2.4</strong></td>
                <td><strong> Strength and construction of the floor of the machine room, windows, skylights and fire resistance is in accordance with the relevant building code.</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1 
Sec. (2.7.1.1 (3.7),
2.9.2, 2.9.4 (3.9),
8.11.3.1.2d)
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
                <td><strong>1.2.5</strong></td>
                <td><strong>Housekeeping is adequate.</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1 
Sec. (8.6.1.2, 8.6.4.8
(8.6.5), 8.6.10.3, 8.11.3.1.2e)
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
                <td><strong>1.2.6</strong></td>
                <td><strong>Ventilation (natural or forced) complies with the various opening and size requirements as well as air change volume per minute (for forced ventilation).</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
  Sec. (2.7.5.2 (3.7),
2.8.4, 8.11.3.1.2f)
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
                <td><strong>1.2.7</strong></td>
                <td><strong>Fire extinguisher is available in the machine room (Class ABC).</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1 
Sec. (8.11.3.1.2g,
(8.6.5))
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
                <td><strong>1.2.8</strong></td>
                <td><strong>Pipes, wiring and ducts conform to the relevant specification (Pipes - 15psi steam or hot water only; wiring to NFPA70 or CSA-C22.1 standard).</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1 
Sec. (2.8.1, 2.8.2 (3.8), 8.11.3.1.2h)
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
                <td><strong>1.2.9</strong></td>
                <td><strong>Guarding of exposed auxiliary equipment is in place and secure.</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1 
Sec. (2.10.1 (3.10),
8.11.3.1.2i)
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
                <td><strong>1.2.10</strong></td>
                <td><strong>Verify numbering of elevators (min. 50mm height figures) on driving machine , disconnect switch, mg set, controller, selector, governor and the car crosshead or frame </strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
 Sec. (2.10.4.2, 2.29.1
(3.27), 3.26)
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
                <td><strong>1.2.11</strong></td>
                <td><strong>Electrical disconnecting means (devices) and controls operate correctly</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (3.26, 3.26.3.1 (3.26.3.1.4b), 8.11.3.1.2k)
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
                <td><strong>1.2.12</strong></td>
                <td><strong>Controller wiring, fuses, grounding, etc. conform to NFPA 70 or CSA C22.1</strong></td>
				<td style="text-align: center;"><strong> ASME A17.1
Sec. (2.8.1 (3.8), 3.26,
3,26.5, 8.6.1.6.3,
8.6.5, 8.11.3.1.2l)
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
                <td><strong>1.2.13</strong></td>
                <td><strong>Governor, over speed switch and seal conform to requirements:  namely, an over speed switch on every car and counterweight governor, sealing of the means to regulate the governor rope pull-out force (carrier) once set, to not more than 60% of the pull through </strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.17, 2.18,
3.17.1, 8.6.1.2,
8.7.2.19,
8.11.2.2.2,
8.11.3.2.3,
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
                <td><strong>1.2.14</strong></td>
                <td><strong>Code date plate states correct information and is legible</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1 
Sec. (8.7.1.8, 8.9)
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
                <td><strong>1.2.15</strong></td>
                <td><strong>Hydraulic power unit is operational, undamaged and does not leak</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (3.24, 8.6.5,
8.11.3.1.2m)
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
                <td><strong>1.2.16</strong></td>
                <td><strong>Hydraulic relief valve(s) are fitted between the pump and check valve and are of sufficient capacity to pass the rated capacity of the pump without raising working pressure more than 50% above normal  (valve should be sealed )</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (3.19.1, 3.19.2,
3.19.4.2, 3.28,
8.10.3.2.2m,
8.11.3.2.1)
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
                <td><strong>1.2.17</strong></td>
                <td><strong>Hydraulic control valve(s) are marked with their rated pressure and electrical data</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (3.19, 8.11.3.1.2o)
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
                <td><strong>1.2.18</strong></td>
                <td><strong>Oil tanks are of sufficient capacity to provide reserve liquid, prevent ingress of air and be clearly marked with minimum level.</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1 
Sec. (3.24, 8.6.5.1,
8.6.5.2, 8.6.5.5,
8.6.5.6, 8.7.3.29,
8.11.3.1.2p, 8.11.3.3.2)
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
                <td><strong>1.2.19</strong></td>
                <td><strong>Flexible hydraulic hoses and fitting assemblies are undamaged and leak- free.</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec.
(3.19.3.3,8.11.3.1.2q,8.11.3.2.4)
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
                <td><strong>1.2.20</strong></td>
                <td><strong>Supply line and shutoff line are leak-free, and the shut-off valve is located between pump and jack and outside the hoist way</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1 
  Sec. (3.19, 8.11.3.1.2r)
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
                <td><strong>1.2.21</strong></td>
                <td><strong>Hydraulic cylinders are free from damage and are leak-free </strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (3.18.3, 8.11.3.1.2s,
8.11.3.2.2)
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
                <td><strong>1.2.22</strong></td>
                <td><strong>Pressure switch is fitted if the top of the cylinder is above the top of the storage tank in line between cylinder and valve, the latter activating on loss of positive pressure at the top of the cylinder</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec.(3.26.8,8.11.3.1.2t,
8.11.3.2.5)
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
                <td><strong>1.2.23</strong></td>
                <td><strong>Pressure switch prevents automatic door opening and the operation of the lowering valve(s) (Car doors can be opened when in the unlocking zone using the in-car button)</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (3.26.8,8.11.3.1.2t,
8.11.3.2.5)
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
                    <th style="text-align: center;">1.3</th>
                    <th style="text-align: center;">TOP OF CAR</th>
					<th style="text-align: center;"> </th>
                    
                    <th style="text-align: center;">PASS</th>
                    <th style="text-align: center;">FAIL</th>
                    <th style="text-align: center;">NA</th>
                    <th> </th>
                </tr>
				</thead>
 
                <tbody>
				<tr>

 <tr>
                <td><strong>1.3.1</strong></td>
                <td><strong> Car top stop switch is provided and operational</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (3.26.4, 8.11.3.1.3a)
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
                <td><strong>1.3.2</strong></td>
                <td><strong>  Emergency stop switches areCar top light and outlet is provided and operational</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.14.7 (3.14),
8.11.3.1.3b)
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
                <td><strong>1.3.3</strong></td>
                <td><strong>Car top operating device is provided (for inspection purposes)</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (3.26.2, 8.11.3.1.3c)
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
                <td><strong>1.3.4</strong></td>
                <td><strong> Car top clearance and refuge space dimensions: varies for the former: minimum 43" for the latter</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (3.4, 3.18.4,
8.10.3.2.2s,
8.10.3.2.3d,
8.11.3.1.3d)
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
                <td><strong>1.3.5</strong></td>
                <td><strong>Terminal stopping devices are provided and arranged to slow down and stop the car automatically at or near the top and bottom terminal landings (with up to rated load) and at a speed attained in normal
operation </strong></td>
<td style="text-align: center;"><strong> ASME A17.1
Sec. (3.25.1.1,8.10.2.3.2k,
8.11.2.2.5 (8.11.3.2.3),
8.11.3.1.3e)
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
                <td><strong>1.3.6</strong></td>
                <td><strong>Final terminal stopping devices are electro-mechanically operated and cause power to the driving machine motor to be removed automatically after the car has passed a terminal landing
</strong></td>
<td style="text-align: center;"><strong>ASME A17.1
  Sec. (2.7.5.2 (3.7),
2.8.4, 8.11.3.1.2f)
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
                <td><strong>1.3.7</strong></td>
                <td><strong>Anti-creep device controls the car within 25mm of the landing irrespective of hoist way door position</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1  Sec.(3.26.3, 3.26.4,
8.11.3.1.3g)
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
                <td><strong>1.3.8</strong></td>
                <td><strong>Top emergency exit is at least 16" square</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1 
Sec. (2.14.1.5 (3.14), 8.11.3.1.3i)
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
                <td><strong>1.3.9</strong></td>
                <td><strong>Verify floor level and emergency identification numbering of elevators (min. 50mm height)</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.29.1 (3.27),
2.29.2 (3.1),
8.11.3.1.3j)
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
                <td><strong>1.3.10</strong></td>
                <td><strong>Hoist way construction complies with appropriate standards and building regulations (where applicable)</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (3.1, 8.11.3.1.3k)
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
                <td><strong>1.3.11</strong></td>
                <td><strong>Hoist way smoke control arrangements are satisfactory enough to prevent the accumulation of  smoke and hot gases</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.1.4 (3.1),
8.11.3.1.3l)
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
                <td><strong>1.3.12</strong></td>
                <td><strong>Pipes, wiring and ducts conform to the relevant specification (Pipes - 15psi steam or hot water only; wiring to NFPA70 or CSA-C22.1 standard)</strong></td>
				<td style="text-align: center;"><strong> ASME A17.1
Sec. (2.8(3.8),
8.11.3.1.3m)
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
                <td><strong>1.3.13</strong></td>
                <td><strong>Windows, projections, recesses and setbacks comply with the appropriate building codes and hoist way enclosures generally have flush surfaces on the hoist way side</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.1.5, 2.1.6 (3.1),
2.11.10 (3.11),
8.11.3.1.3n)
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
                <td><strong>1.3.14</strong></td>
                <td><strong>Various hoist way clearances are at least the same all the way around (20mm )</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.5(3.5), 2.11 
(3.11), 8.11.3.1.3o)
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
                <td><strong>1.3.15</strong></td>
                <td><strong>Multiple hoist ways (and the number of elevators in a hoist way) conforms with the appropriate building code</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1 
Sec. (2.1.1.4 (3.1), 8.11.3.1.3p)
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
                <td><strong>1.3.16</strong></td>
                <td><strong>Traveling cables and junction boxes conforms to NFPA70 or CSA - C22.1, whichever is applicable</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1 Sec. (2.8.1 (3.8),
8.11.3.1.3q)
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
                <td><strong>1.3.17</strong></td>
                <td><strong>Door and gate equipment operation are satisfactory and in accordance with manufacturers recommendations</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.11 (3.11), 2.12
(3.12), 2.26.1.6
(3.26.3), 8.11.3.1.3r)
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
                <td><strong>1.3.18</strong></td>
                <td><strong>Car frame and stiles are suitable for the purpose and show no defects</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (3.15, 8.8 (3.18.5), 8.11.3.1.3s)
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
                <td><strong>1.3.19</strong></td>
                <td><strong>Guide rails fastening and equipment are suitable for the purpose, show no defects, and the guide rails are correctly lubricated (where required) (manufacturer specification )</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.23 (3.23.2),
3.15, 3.23, 3.38,
8.11.3.1.3t)
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
                <td><strong>1.3.20</strong></td>
                <td><strong>Governor rope condition and that it is fitted with a tag</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1 
Sec. (2.18.5, 3.17.1,
8.6.4.2, 8.7.2.19,
8.11.2.1.3,
8.11.3.1.3w)
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
                <td><strong>1.3.21</strong></td>
                <td><strong>Condition of governor releasing carrier and that it is set to require a tension in the governor rope to pull the rope from the carrier of not more than 60% of the pull-through tension developed by the governor. The means to regulate this force shall be mechanical and shall be sealed. </strong></td>
				<td style="text-align: center;"><strong>ASME A17.1 
Sec. (2.17.15, 3.17.1,
8.11.3.1.3y,
8.11.3.4)
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
                <td><strong>1.3.22</strong></td>
                <td><strong>Wire rope fastening and hitch plate are secured using bolts or rivets.</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1 
Sec. (2.9.3.3, 2.15.13,
2.20, 3.18.1.2,
8.6.3, 8.11.3.1.3x)
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
                <td><strong>1.3.23</strong></td>
                <td><strong>Specification and suitability of the suspension rope and its fastenings is acceptable (in the case of a new rope the sheave material shall be assessed as suitable or not )</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1 
Sec. (2.20, 8.2.7, 8.6.2.5, 8.7.2.21, 8.7.3.25,
8.11.2.1.3cc,
8.11.3.1.3y)
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
                <td><strong>1.3.24</strong></td>
                <td><strong>PrSpeed test in both directions is in accordance with manufacturers specifications</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1 
Sec. (2.17.16, 3.4,
8.10.3.2.3cc,
8.11.3.1.3h)
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
                <td><strong>1.3.25</strong></td>
                <td><strong>Slack rope device (roped-hydraulic elevators installed under A17.1b- 1989 and later editions) does cause the electric power to be removed from the hydraulic machine pump motor and control valves should a rope become slack</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1 
Sec. (3.18.1.2, 3.26.4, 8.11.3.1.3z)
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
                <td><strong>1.3.26</strong></td>
                <td><strong>Travelling sheave (roped-hydraulic elevators installed under A17.1b-1989 and later editions) is attached using suitable fastenings (the loading being the resultant of the maximum tensions in the ropes leading from the sheave with the elevator at rest and with rated load in the car)</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.20, 2.24.2,
2.24.3, 2.24,5.
3.18.1.2, 3.23.2,
8.7.3.25,8.11.3.1.3aa)
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
                <td><strong>1.3.27</strong></td>
                <td><strong>Counterweight, counterweight buffers and safeties are in compliance with design requirements</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1 Sec.
(3.4.6, 3.17.2,
3.22.2, 8.2.3)
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
                    <th style="text-align: center;">1.4</th>
                    <th style="text-align: center;">OUTSIDE HOIST WAY</th>
					<th style="text-align: center;"> </th>
                    
                    <th style="text-align: center;">PASS</th>
                    <th style="text-align: center;">FAIL</th>
                    <th style="text-align: center;">NA</th>
                    <th> </th>
                </tr>
				</thead>
 
                <tbody>
				<tr>

 <tr>
                <td><strong>1.4.1</strong></td>
                <td><strong> Car platform guard plates comply with material specification (steel ) and thickness (not less than 1.5 mm)</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (3.15, 8.11.3.1.4a)
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
                <td><strong>1.4.2</strong></td>
                <td><strong> Hoist way doors operate correctly</strong></td>
				<td style="text-align: center;"><strong>       ASME A17.1
Sec. (2.11 (3.11),
2.12.2.2, 2.12.3.2
(3.12), 3.26.4,
8.10.3.2.3r,
8.11.3.1.4b)
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
                <td><strong>1.4.3</strong></td>
                <td><strong>Car vision panel (if fitted) is 0.1sq.m. (Max) and either wire-glass or laminated, and in the case of glass doors be laminated, safety glass or safety plastic, with not less than 60% of the total visible door panel surface area as glass.</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.11.7 (3.11),
8.11.3.1.4c)
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
                <td><strong>1.4.4</strong></td>
                <td><strong> Hoist way door locking devices are operational (interlocks)</strong></td>
				<td style="text-align: center;"><strong>  ASME A17.1
Sec.(2.12 (3.12),
8.11.3.1.4d)
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
                <td><strong>1.4.5</strong></td>
                <td><strong>Access to hoist way (at top or bottom landing) is by use of an access switch adjacent to the entrance </strong></td>
<td style="text-align: center;"><strong> ASME A17.1
Sec. (2.12.6, 2.12.7
(3.12), 8.11.3.1.4e)
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
                <td><strong>1.4.6</strong></td>
                <td><strong>Hoist way doors are power closing</strong></td>
<td style="text-align: center;"><strong>ASME A17.1
  Sec. (2.13.3, 2.13.6
 (3.13), 8.11.3.1.4f)
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
                <td><strong>1.4.7</strong></td>
                <td><strong>Sequence of operation of hoist way doors is correct</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.13.3.4 (3.13),
2.13.6, 8.11.3.1.4g)
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
                <td><strong>1.4.8</strong></td>
                <td><strong>Verify hoist way enclosure fire resistance (or non-fire resistance, depending on building code) (other general requirements such as floor strength and location depend on the code - check specification)</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec.(2.1.1, 2.1.4, 2.1.5
(3.1), 8.11.3.1.4h)
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
                <td><strong>1.4.9</strong></td>
                <td><strong>Elevator parking devices are operable</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (8.11.3.1.4i)
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
                <td><strong>1.4.10</strong></td>
                <td><strong>Emergency doors in blind hoist way are on every third floor, not more than 11m from sill to sill with a clear opening of 700mm x 2030mm (at least) , and doors are self-closing and self-locking and marked "Danger Elevator Hoist way" in 50mm letters  (an open or unlocked door removes power from the elevator motor)
</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.11.1.1,2.11.1.2)
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
                <td><strong>1.4.11</strong></td>
                <td><strong>Standby power selection switch is marked "Elevator Emergency Power" and key operated under a locked cover</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.16.8 (3.16),
2.27.2, 2.27.8
(3.27), 8.11.2.2.7,
8.11.3.1.4k,
8.11.3.2.3)
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
                    <th style="text-align: center;">1.5</th>
                    <th style="text-align: center;">ELEVATOR PIT</th>
					<th style="text-align: center;"> </th>
                    
                    <th style="text-align: center;">PASS</th>
                    <th style="text-align: center;">FAIL</th>
                    <th style="text-align: center;">NA</th>
                    <th> </th>
                </tr>
				</thead>
 
                <tbody>
				<tr>

 <tr>
                <td><strong>1.5.1</strong></td>
                <td><strong> Pit access, lighting and stop switch meet design requirements</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.8 (3.8), 3.6, 3.26.4, 8.6.4.7,
8.11.3.1.5a)
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
                <td><strong>1.5.2</strong></td>
                <td><strong> Verify bottom clearance as 600mm ;  run by clearance as 75mm (min.) 150mm (max., speed dependent) ;   and minimum refuge space as 600 x1200 x600 mm or 450 x 900 x 1070 mm.</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec.(3.4, 3.18.3.3, 8.10.3.2.5c,
8.11.3.1.5b)
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
                <td><strong>1.5.3</strong></td>
                <td><strong>Normal terminal stopping devices operate correctly to slow down and operate the car correctly at or near top and bottom terminal landings (up to rated load and speed)</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (3.25.1,8.11.2.2.5
(8.11.3.2.3), 8.11.3.1.5e)
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
                <td><strong>1.5.4</strong></td>
                <td><strong> Travel cables are undamaged and serviceable</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.8.2 (3.8),
8.11.3.1.5f)
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
                <td><strong>1.5.5</strong></td>
                <td><strong>Governor-rope tension device is working satisfactorily </strong></td>
<td style="text-align: center;"><strong> AASME A17.1
Sec. (2.18.7, 3.17.1,
8.6.1.6.2, 8.11.3.1.5k)
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
                <td><strong>1.5.6</strong></td>
                <td><strong>Car frame and platform meet requirements as per manufacturers specification</strong></td>
<td style="text-align: center;"><strong>ASME A17.1
Sec. (3.15, 2.18.2.3,
3.28, 8.11.3.1.5g)
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
                <td><strong>1.5.7</strong></td>
                <td><strong>Car safeties guarding members are in place and secure - including roped- hydraulic elevators installed under A17.1b-1989 and later editions (where applicable)</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.17, 3.17.1,
8.2.6, 8.11.3.1.5j)
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
                <td><strong>1.5.8</strong></td>
                <td><strong>Plunger and cylinder comply with design requirements  (Plunger shall not strike the safety bulkhead of the cylinder when the car is resting on its fully compressed buffer </strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (3.18, 8.6.5.1,
8.6.5.2, 8.6.5.5,
8.6.5.6, 8.11.3.1.5c)
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
                <td><strong>1.5.9</strong></td>
                <td><strong>Plunger stops are provided to prevent the plunger from travelling beyond the limits of the cylinder in the up direction at maximum speed and full load pressure.</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (3.18, 8.6.5.1,
8.6.5.2, 8.6.5.5,
8.6.5.6, 8.11.3.1.5c)
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
                <td><strong>1.5.10</strong></td>
                <td><strong> Car buffers are in place where required and undamaged</strong></td>
				<td style="text-align: center;"><strong>  ASME A17.1
Sec. (3.22.1, 3.26.4,
8.2.3.2, 8.6.4.4,
8.11.3.1.5d)
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
                <td><strong>1.5.11</strong></td>
                <td><strong> Guiding members are in position, securely bracketed, and meet design requirements</strong></td>
				<td style="text-align: center;"><strong>  ASME A17.1
Sec. (3.23, 3.28,
8.6.4.3, 8.11.3.1.5h)
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
                <td><strong>1.5.12</strong></td>
                <td><strong> Oil supply piping meets design requirements (as per manufacturer) and is leak-proof and secure</strong></td>
				<td style="text-align: center;"><strong>  ASME A17.1
Sec. (2.24, 8.10.3.2.2r, 8.11.3.1.5i)
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
                    <th style="text-align: center;">1.6</th>
                    <th style="text-align: center;">FIREFIGHTER’S SERVICE</th>
					<th style="text-align: center;"> </th>
                    
                    <th style="text-align: center;">PASS</th>
                    <th style="text-align: center;">FAIL</th>
                    <th style="text-align: center;">NA</th>
                    <th> </th>
                </tr>
				</thead>
 
                <tbody>
				<tr>

 <tr>
                <td><strong>1.6.1</strong></td>
                <td><strong> Verify / check operation of elevators under fire and other emergency conditions (A17.1b- 1973 through A17.1b- 1980)</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.13.3.4, 2.13.5,
8.6.10.1, 8.11.2.1.4l,
8.11.2.2.6)
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
                <td><strong>1.6.2</strong></td>
                <td><strong> Verify / check operation of elevators under fire and other emergency conditions (A17.1- 1981 through A17.1b- 1983)</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.13.3.4, 2.13.5,
8.6.10.1, 8.11.2.1.4l,
8.11.2.2.6)
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
                <td><strong>1.6.3</strong></td>
                <td><strong>Verify / check operation of elevators under fire and other emergency conditions (A17.1- 1984 through A17.1a- 1988 and A17.3)</strong></td>
				<td style="text-align: center;"><strong>    ASME A17.1
Sec. (2.13.3.4, 2.13.5,
8.6.10.1, 8.11.2.1.4l,
8.11.2.2.6)
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
    <input type="text" name="checklist_remark[99]" value="<?php echo getRemark(99, 'NA', $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>1.6.4</strong></td>
                <td><strong> Verify / check operation of elevators under fire and other emergency conditions (A17.1b- 1989 and later edition)</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.13.3.4, 2.13.5,
8.6.10.1, 8.11.2.1.4l,
8.11.2.2.6)
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
                    <th style="text-align: center;">2</th>
                    <th style="text-align: center;">ELECTRIC ELEVATOR</th>
					<th style="text-align: center;"> </th>
                    
                    <th style="text-align: center;"></th>
                    <th style="text-align: center;"></th>
                    <th style="text-align: center;"></th>
                    <th> </th>
                </tr>
					<tr>
                    <th style="text-align: center;">2.1</th>
                    <th style="text-align: center;">INSIDE OF CAR</th>
					<th style="text-align: center;"> </th>
                    
                    <th style="text-align: center;">PASS</th>
                    <th style="text-align: center;">FAIL</th>
                    <th style="text-align: center;">NA</th>
                    <th> </th>
                </tr>
				</thead>
 
                <tbody>
				<tr>
 <tr>
                <td><strong>2.1.1</strong></td>
                <td><strong> Door reopening device is operating correctly </strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (8.11.2.1.1a)

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
                <td><strong>2.1.2</strong></td>
                <td><strong>  Emergency stop switches are not provided on passenger elevators but are provided on freight elevators, in the car and in or adjacent to each car operating panel </strong></td>
				<td style="text-align: center;"><strong> ASME A17.1
Sec. (2.26.2.5,
2.26.2.21, 8.11.2.1.1b)
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
                <td><strong>2.1.3</strong></td>
                <td><strong> All operating control devices are of the enclosed electric type   </strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.26.1.1,
2.26.1.6, 8.11.2.1.1c)
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
                <td><strong>2.1.4</strong></td>
                <td><strong> Sills are of the correct type and are of sufficient strength and clearance with adjoining car platform or hoist way sill  (min. clearance 13mm)  </strong></td>
				<td style="text-align: center;"><strong> ASME A17.1
Sec.(2.5.1.4 ,2.11.10.3,
2.11.11.1, 2.11.13.1,
2.15.16, 8.11.2.1.1d)
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
                <td><strong>2.1.5</strong></td>
                <td><strong>Car has minimum of two lamps (min. of 50 lux for passenger and 25 lux for freight elevators)    (Passenger elevators shall have auxiliary lighting which automatically turns on if normal power fails )</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec.(2.14.7, 8.11.2.1.1e)
ASME A17.3
Sec. (3.4.5, 3.4.6)
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
                <td><strong>2.1.6</strong></td>
                <td><strong>Passenger elevators are equipped with auxiliary lighting which automatically turns on if normal power fails</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec.(2.14.7,
8.11.2.1.1e)
ASME A17.3
Sec. (3.4.5, 3.4.6)
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
                <td><strong>2.1.7</strong></td>
                <td><strong>Car emergency communication signal to authorized and emergency personnel is available and working</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.27.1,
8.11.2.1.1f)
ASME A17.3
Sec. (3.11.1)
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
                <td><strong>2.1.8</strong></td>
                <td><strong>Car door or gate has electric contacts or interlocks (where required) to prevent operation of the driving machine when the door or gate is open</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.13.2.1, 2.14.4,
2.14.5, 2.14.6,
2.26.2.15, 8.11.2.1.1g
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
                <td><strong>2.1.9</strong></td>
                <td><strong>The force necessary to prevent door closing  does not exceed 30ft.lb</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.13.4.2.3,
8.11.2.1.1h,
8.11.2.2.8)
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
                <td><strong>2.1.10</strong></td>
                <td><strong>Power closing of doors or gates (vertically sliding) is preceded by a warning bell at least 5 seconds prior to door or gate movement and continues until substantial closure (Closure using a switch or button in the car omits the 5 second time interval)</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.13.3,
8.11.2.1.1i)
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
                <td><strong>2.1.11</strong></td>
                <td><strong>Power opening of doors or gates only occurs when the car is at rest at the landing , or in the landing zone</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1.
Sec. (2.26.1.6, 2.26.9,
2.26.9.3, 8.11.2.1.1j,
8.11.2.3.7, 8.11.2.3.8,
8.11.2.3.9)
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
                <td><strong>2.1.12</strong></td>
                <td><strong> Car vision panel (if fitted) is 0.1sq.m. (Max) and either wire-glass or laminated, and in the case of glass doors be laminated, safety glass or safety plastic, with not less than 60% of the total visible door panel surface area as glass.</strong></td>
				<td style="text-align: center;"><strong> ASME A17.1
(2.14.2.5, 2.14.5.8,
8.11.2.1.1k)
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
                <td><strong>2.1.13</strong></td>
                <td><strong>Laminated glass vision panel is a safety glass or safety plastic, with not less than 60% of the total visible door panel surface area as glass</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.14.2.5,
2.14.5.8, 8.11.2.1.1k)
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
                <td><strong>2.1.14</strong></td>
                <td><strong>Car enclosure is in compliance with the required equipment (specification)</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.14, 2.16.2.2,
2.16.4, 2.16.5, 2.29.1, 8.3.7, 8.7.2.14,
8.11.2.1.1m)
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
                <td><strong>2.1.15</strong></td>
                <td><strong>Verify the emergency exit (and cover ) is provided in the top of the car (except cars in partially enclosed hoist ways)</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.14.1.5,
2.14.1.10, 8.11.2.1.1m)
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
                <td><strong>2.1.16</strong></td>
                <td><strong>Ventilation (natural or forced ) complies with the various opening and size requirements as well as air change volume per minute (for forced ventilation)</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.14.2.3, 2.14.3.3,
8.11.2.1.1n)
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
                <td><strong>2.1.17</strong></td>
                <td><strong>Signs and operating device symbols are installed and legible</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.16.12,
8.11.2.1.1o)
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
                <td><strong>2.1.18</strong></td>
                <td><strong>Rated load (depending on net platform) is in compliance with  load area (chart) and data plate information</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.16, 8.11.2.1.1p)
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
                <td><strong>2.1.19</strong></td>
                <td><strong>Standby power is operable (at least one elevator at a time ) with rated load in the event of power supply failure  (transfer from normal to standby supply is automatic)</strong></td>
				<td style="text-align: center;"><strong>AASME A17.1
Sec. (2.16.18, 2.26.10,
2.27.2, 8.11.2.1.1q,
8.11.2.2.7, 8.11.2.3.5)
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
                <td><strong>2.1.20</strong></td>
                <td><strong>Restricted opening of car or hoist way doors (4" max) is possible outside the unlocking zone</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (8.11.2.1.1r)
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
                <td><strong>2.1.21</strong></td>
                <td><strong>Car ride is smooth in acceleration and deceleration throughout its travel</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.15.2, 2.23,
8.11.2.1.1s)
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
                    <th style="text-align: center;">2.2</th>
                    <th style="text-align: center;">MACHINE ROOM</th>
					<th style="text-align: center;"> </th>
                    
                    <th style="text-align: center;">PASS</th>
                    <th style="text-align: center;">FAIL</th>
                    <th style="text-align: center;">NA</th>
                    <th> </th>
                </tr>
				</thead>
 
                <tbody>
				<tr>
 <tr>
                <td><strong>2.2.1</strong></td>
                <td><strong> The access to the machine space is in conformance with the type of access, location , and combustibility allowed </strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.7.1.1, 2.7.3.1,
2.7.3.2, 2.7.3.3,
2.7.3.4, 8.11.2.1.2a)
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
                <td><strong>2.2.2</strong></td>
                <td><strong>Minimum headroom clearance is either 84" , 53", 42", or 35" depending on type and location of machine room / hoist way</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.7.4, 8.11.2.1.2c)
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
                <td><strong>2.2.3</strong></td>
                <td><strong> Electric lighting in the machine room is not less than 200 lux at floor level and the control switch is at the lock - jamb side of the access door </strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.7.5.1,
8.11.2.1.2c)
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
                <td><strong>2.2.4</strong></td>
                <td><strong> The strength and construction of the floor of the machine room, windows, skylights and fire resistance is in accordance with the relevant building code(s)</strong></td>
				<td style="text-align: center;"><strong> ASME A17.1
Sec.(2.1.3.3, 2.1.3.4,
2.1.5, 2.7.1.1,
2.7.2.1, 2.7.8,
8.11.2.1.2d)
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
                <td><strong>2.2.5</strong></td>
                <td><strong>Housekeeping is adequate</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (8.6.4.8, 8.6.10.3, 8.11.2.1.2e)
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
                <td><strong>2.2.6</strong></td>
                <td><strong>Ventilation (natural or forced ) complies with the elevator equipment manufacturers requirements for ambient air temperature and humidity(as posted in machine room )</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.7.5.2, 2.8.4,
8.11.2.1.2g)
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
                <td><strong>2.2.7</strong></td>
                <td><strong>Fire extinguisher is available in the machine room (Class ABC)</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (8.6.1.6.5,
8.11.2.1.2g)
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
                <td><strong>2.2.8</strong></td>
                <td><strong>Pipes, wiring and ducts conform to the relevant specification (Pipes - 15psi steam or hot water only; wiring to NFPA70 or CSA-C22.1 standard)</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1 
Sec. (2.8.1, 2.8.2,
8.11.2.1.2h)
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
                <td><strong>2.2.9</strong></td>
                <td><strong>Guarding of exposed auxiliary equipment is in place and secure</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.10.1,
8.11.2.1.2i)
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
                <td><strong>2.2.10</strong></td>
                <td><strong>Verify numbering of elevators (min. 50mm height figures) on driving machine , disconnect switch, mg set, controller, selector, governor and the car crosshead or frame</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.26.4, 2.29.1,
8.11.2.1.2j)
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
                <td><strong>2.2.11</strong></td>
                <td><strong>Electrical disconnecting means (devices) and controls are working properly</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.26.4,
8.11.2.1.2k)
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
                <td><strong>2.2.12</strong></td>
                <td><strong>Controller wiring, fuses, grounding, etc. conform to NFPA 70 or CSA C22.1</strong></td>
				<td style="text-align: center;"><strong> ASME A17.1
Sec. (2.8.1, 2.26.4,
8.6.1.6.3,
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
                <td><strong>2.2.13</strong></td>
                <td><strong>Governor, over speed switch and seal conform to requirements:  namely, an over speed switch on every car and counterweight governor, sealing of the means to regulate the governor rope pull-out force (carrier) once set, to not more than 60% of the pull through tension developed by the governor</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.17.15, 2.18,
2.26.2, 2.26.2.10,
8.6.1.6.2,
8.10.2.1.2cc-1,
8.11.2.1.2bb, 8.11.2.3.1, 8.11.2.3.2)
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
                <td><strong>2.2.14</strong></td>
                <td><strong>Code date plate indicates the code and edition in effect at the time of installation (or alteration)</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (8.7.1.8, 8.9)
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
                <td><strong>2.2.15</strong></td>
                <td><strong>Static control is available with  each type of hoist motor/ DC source/ inverter arrangement</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.26.2, 2.26.9.5, 8.10.2.2.2m,
8.11.2.1.2m)
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
                <td><strong>2.2.16</strong></td>
                <td><strong>Overhead beams and fastenings are secure and suitable for the duty (beams shall be or re-inforced concrete)</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.9.1, 2.9.2,
2.9.3, 8.11.2.1.2n)
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
                <td><strong>2.2.17</strong></td>
                <td><strong>Drive machine brake (on its own) holds the car at rest with the rated load, and when empty</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.16.2.2, 2.16.8,
2.24.8.3, 2.26.8,
8.11.2.1.2o,
8.11.2.3.4)
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
                <td><strong>2.2.18</strong></td>
                <td><strong>Drive machine brake stops a decelerating empty car in the upward direction from governor overspeed setting, not to exceed 9.8 m/sec/sec</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.16.2.2, 2.16.8,
2.24.8.3, 2.26.8,
8.11.2.1.2o,
8.11.2.3.4)
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
                <td><strong>2.2.19</strong></td>
                <td><strong>Gears, bearings and flexible couplings are lubricated (where required) as per manufacturers recommendations as to grade and type</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (8.6.1.6.2,
8.11.2.1.2q)
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
                <td><strong>2.2.20</strong></td>
                <td><strong>Winding drum machine slack cable device is operational when the rope is slack</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.20.10, 2.24.10,
2.26.2, 8.6.4.10,
8.11.2.1.2r,8.11.2.2.4)
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
                <td><strong>2.2.21</strong></td>
                <td><strong>Belt or chain drive machines include three belts or chains (or more) operating together in parallel as a set</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1.
Sec. (2.24.9,
8.11.2.1.2s)
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
                <td><strong>2.2.22</strong></td>
                <td><strong>Motor generator cannot supply sufficient current to the driving machine motor to move the car when the motor control switches are in the off position</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.26.9.7,
8.10.2.2.2t,
8.11.2.1.2t)
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
                <td><strong>2.2.23</strong></td>
                <td><strong>Absorption of regenerated power is available to prevent elevator from reaching governor trip speed or in excess of 125% rated speed</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec.(2.26.10,
8.10.2.2.2u,
8.11.2.1.2u)
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
                <td><strong>2.2.24</strong></td>
                <td><strong>AC drives from DC source use a static inverter and other devices as a means of control</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1 Sec.(2.26.2, 2.26.9.6, 8.11.2.1.2v, </strong></td>
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
                <td><strong>2.2.25</strong></td>
                <td><strong>Traction sheaves comply with requirements as to material (metal) and finished grooves. Diameter to be not less than 40 times rope diameter (suspension ropes), or not less than 32 times rope diameter (compensating ropes )</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.16.8, 2.20,
2.24.2, 8.6.1.6,
8.6.4.1, 8.7.2.21,
8.11.2.1.2w)
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
                <td><strong>2.2.26</strong></td>
                <td><strong>Secondary and deflector sheaves comply as 1.2.25 above</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1 
Sec. (2.24, 8.6.1.6.2, 8.11.2.1.2x)
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
                <td><strong>2.2.27</strong></td>
                <td><strong>Rope fastenings comply with type and material requirements</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1 
Sec. (2.9.3.3, 2.20,
8.11.2.1.2y)
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
                <td><strong>2.2.28</strong></td>
                <td><strong>Terminal stopping devices are provided and arranged to slow down and stop the car automatically at or near the top and bottom terminal landings (with up to rated load) and at a speed attained in normal operation</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1 
Sec. (2.25,8.11.2.1.2Z,
8.11.2.3.6)
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
                <td><strong>2.2.29</strong></td>
                <td><strong>Car and counterweight safeties comply with number and type requirements, namely;  one or more type A, B or C attached to the car frame,  and one below the frame</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec.(2.27, table
2.17.3, 8.2.6,
8.7.2.18, 8.10.2.2,
8.11.2.1.2cc,
8.11.2.2.2, 8.11.2.3.1)
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
                    <th style="text-align: center;">2.3</th>
                    <th style="text-align: center;">TOP OF CAR</th>
					<th style="text-align: center;"> </th>
                    
                    <th style="text-align: center;">PASS</th>
                    <th style="text-align: center;">FAIL</th>
                    <th style="text-align: center;">NA</th>
                    <th> </th>
                </tr>
				</thead>
 
                <tbody>
				<tr>
 <tr>
                <td><strong>2.3.1</strong></td>
                <td><strong>Top of car stop switch is provided and operational</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.26.2.8,
8.11.2.1.3a)
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
                <td><strong>2.3.2</strong></td>
                <td><strong>Car of top light and outlet are provided and operational</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.14.7,
8.11.2.1.3b)
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
                <td><strong>2.3.3</strong></td>
                <td><strong>Top of car operating device is provided (for inspection purposes) </strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.14.1.7,
2.26.1.4, 8.11.2.1.3c)
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
                <td><strong>2.3.4</strong></td>
                <td><strong>Top of car clearance and refuge space dimensions vary for the former, minimum 43" for the latter</strong></td>
				<td style="text-align: center;"><strong> ASME A17.1
Sec. (2.4, 8.2.4,
8.6.4.11)
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
                <td><strong>2.3.5</strong></td>
                <td><strong>Terminal stopping devices are provided and arranged to slow down and stop the car automatically at or near the top and bottom terminal landings (with up to rated load) and at a speed attained in normal operation</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec.(2.16.4, 2.25.2,
2.26.2, 8.10.2.2.2z,
8.10.2.3.2k,
8.11.2.1.3g,
8.11.2.2.5)
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
                <td><strong>2.3.6</strong></td>
                <td><strong>Final terminal stopping devices meet the general requirement that they be mechanically operated and cause power to the driving machine motor and brake to be removed automatically after the car has passed a terminal landing</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.25.3,
8.10.2.3.2k, 8.11.2.1.3h,
8.11.2.2.5)
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
                <td><strong>2.3.7</strong></td>
                <td><strong>Car leveling and anticreep devices operate satisfactorily within the given landing zone (3" above and below)</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.26.1.6,
8.11.2.1.3j)
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
                <td><strong>2.3.8</strong></td>
                <td><strong>Top emergency exit is at least 16" square</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.14.1.5,
8.11.2.1.3l)
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
                <td><strong>2.3.9</strong></td>
                <td><strong>Verify floor level and emergency identification numbering of elevators (min. 50mm height)</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.29.1, 2.29.2,
8.11.2.1.3o)
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
                <td><strong>2.3.10</strong></td>
                <td><strong>Hoistway construction complies with appropriate standards and building regulations (where applicable)</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.1, 8.11.2.1.3p)
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
                <td><strong>2.3.11</strong></td>
                <td><strong>Hoistway smoke control arrangements are satisfactory enough to prevent the accumulation of smoke and hot gases</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.1.4,
8.11.2.1.3q)
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
                <td><strong>2.3.12</strong></td>
                <td><strong>Pipes, wiring and ducts conform to the relevant specification (Pipes - 15psi steam or hot water only; wiring to NFPA70 or CSA-C22.1 standard)</strong></td>
				<td style="text-align: center;"><strong> ASME A17.1
Sec. (2.8, 8.11.2.1.3r)
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
                <td><strong>2.3.13</strong></td>
                <td><strong>Windows, projections, recesses and setbacks comply with the appropriate building codes and hoistway enclosures generally have flush surfaces on the hoistway side</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.1.5, 2.1.6,
2.11.10, 8.11.2.1.3s)
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
                <td><strong>2.3.14</strong></td>
                <td><strong>Various hoistway clearances are at least the same all the way around (20mm)</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec.(2.4, 2.5, 8.11.2.1.3t)
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
                <td><strong>2.3.15</strong></td>
                <td><strong>Multiple hoistways (and the number of elevators in a hoistway ) conforms with the appropriate building code</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.1.1.4,
8.11.2.1.3u)
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
                <td><strong>2.3.16</strong></td>
                <td><strong>Traveling cables and junction boxes conforms to NFPA70 or CSA -C22.1 whichever is applicable</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.8.1, 8.11.2.1.3v)
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
 <tr>
                <td><strong>2.3.17</strong></td>
                <td><strong>Door and gate equipment operation is satisfactory and in accordance with manufacturers recommendations</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec.(2.11, 2.12,
2.26.1.6, 8.11.2.1.3w)
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[167][]" id="checkbox167_1" value="PASS" class="large-checkbox" <?php echo isChecked(167, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[167][]" id="checkbox167_2" value="FAIL" class="large-checkbox" <?php echo isChecked(167, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[167][]" id="checkbox167_3" value="NA" class="large-checkbox" <?php echo isChecked(167, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[167]" value="<?php echo getRemark(167, $saved_remarks); ?>" class="form-control">
</td>
            </tr>

			<tr>
                <td><strong>2.3.18</strong></td>
                <td><strong>Car frame and stiles are suitable for the purpose and show no defects</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1 Sec. (2.15, 8.6.2,
8.7.2.15.1, 8.8)
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[168][]" id="checkbox168_1" value="PASS" class="large-checkbox" <?php echo isChecked(168, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[168][]" id="checkbox168_2" value="FAIL" class="large-checkbox" <?php echo isChecked(168, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[168][]" id="checkbox168_3" value="NA" class="large-checkbox" <?php echo isChecked(168, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[168]" value="<?php echo getRemark(168, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>2.3.19</strong></td>
                <td><strong>Guide rails fastening and equipment are suitable for the purpose, show no defects, and the guide rails are correctly lubricated (where required) as per the manufacturer specification</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec.(2.17.16, 8.6.4.3,
8.11.2.1.3y)
</strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[169][]" id="checkbox169_1" value="PASS" class="large-checkbox" <?php echo isChecked(169, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[169][]" id="checkbox169_2" value="FAIL" class="large-checkbox" <?php echo isChecked(169, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[169][]" id="checkbox169_3" value="NA" class="large-checkbox" <?php echo isChecked(169, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[169]" value="<?php echo getRemark(169, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>2.3.20</strong></td>
                <td><strong>The governor rope is in good condition and fitted with a tag describing all relevant rope data </strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.18.5, 8.6.4.2,
8.7.2.19, 8.11.2.1.3z)
</strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[170][]" id="checkbox170_1" value="PASS" class="large-checkbox" <?php echo isChecked(170, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[170][]" id="checkbox170_2" value="FAIL" class="large-checkbox" <?php echo isChecked(170, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[170][]" id="checkbox170_3" value="NA" class="large-checkbox" <?php echo isChecked(170, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[170]" value="<?php echo getRemark(170, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>2.3.21</strong></td>
                <td><strong>The governor releasing carrier is in good condition and set to require a tension in the governor rope to pull the rope from the carrier of not more than 60% of the  </strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.17.15,
8.11.2.1.3aa)
  </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[171][]" id="checkbox171_1" value="PASS" class="large-checkbox" <?php echo isChecked(171, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[171][]" id="checkbox171_2" value="FAIL" class="large-checkbox" <?php echo isChecked(171, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[171][]" id="checkbox171_3" value="NA" class="large-checkbox" <?php echo isChecked(171, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[171]" value="<?php echo getRemark(171, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
		<tr>
                <td><strong>2.3.22</strong></td>
                <td><strong>Rope fastening and hitch plate  are secured using bolts or rivets</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.9.3.3, 2.15.13,
2.20, 8.6.3, 8.6.4.10,
8.11.2.1.3bb)
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[172][]" id="checkbox172_1" value="PASS" class="large-checkbox" <?php echo isChecked(172, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[172][]" id="checkbox172_2" value="FAIL" class="large-checkbox" <?php echo isChecked(172, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[172][]" id="checkbox172_3" value="NA" class="large-checkbox" <?php echo isChecked(172, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[172]" value="<?php echo getRemark(172, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
						<tr>
                <td><strong>2.3.23</strong></td>
                <td><strong>Suspension rope is satisfactory and complies with the specifications as marked on the rope data tag</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.18.7, 2.20,
8.6.2.5, 8.7.2.21,
8.11.2.1.3cc)
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[173][]" id="checkbox173_1" value="PASS" class="large-checkbox" <?php echo isChecked(173, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[173][]" id="checkbox173_2" value="FAIL" class="large-checkbox" <?php echo isChecked(173, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[173][]" id="checkbox173_3" value="NA" class="large-checkbox" <?php echo isChecked(173, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[173]" value="<?php echo getRemark(173, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
						<tr>
                <td><strong>2.3.24</strong></td>
                <td><strong>Top counterweight clearance is not less than the sum of all other relevant clearances such as bottom run by, car buffer stroke, 50% of gravity stopping distance, plus 150mm</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1 Sec. (2.4.9,8.11.2.1.3e)</strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[174][]" id="checkbox174_1" value="PASS" class="large-checkbox" <?php echo isChecked(174, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[174][]" id="checkbox174_2" value="FAIL" class="large-checkbox" <?php echo isChecked(174, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[174][]" id="checkbox174_3" value="NA" class="large-checkbox" <?php echo isChecked(174, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[174]" value="<?php echo getRemark(174, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
						<tr>
                <td><strong>2.3.25</strong></td>
                <td><strong>Traction sheaves comply with requirements as to material (metal) and finished grooves.  Diameter to be not less than 40 times rope diameter (suspension ropes) or not less than 32 times rope diameter (compensating ropes)</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.24)
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[175][]" id="checkbox175_1" value="PASS" class="large-checkbox" <?php echo isChecked(175, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[175][]" id="checkbox175_2" value="FAIL" class="large-checkbox" <?php echo isChecked(175, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[175][]" id="checkbox175_3" value="NA" class="large-checkbox" <?php echo isChecked(175, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[175]" value="<?php echo getRemark(175, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
						<tr>
                <td><strong>2.3.26</strong></td>
                <td><strong>Broken rope, chain or tape switch are working</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.25.2.3.2,
2.26.2.6, 8.11.2.1.3i,
8.11.2.2.9)
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[176][]" id="checkbox176_1" value="PASS" class="large-checkbox" <?php echo isChecked(176, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[176][]" id="checkbox176_2" value="FAIL" class="large-checkbox" <?php echo isChecked(176, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[176][]" id="checkbox176_3" value="NA" class="large-checkbox" <?php echo isChecked(176, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[176]" value="<?php echo getRemark(176, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
						<tr>
                <td><strong>2.3.27</strong></td>
                <td><strong>Crosshead data plate states: the complete car weight, rated load and speed, wire rope data, name or trade mark of manufacturer, rail lubrication instructions</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.16.3, 2.20.2,
8.7.2.21, 8.11.2.1.3k)
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[177][]" id="checkbox177_1" value="PASS" class="large-checkbox" <?php echo isChecked(177, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[177][]" id="checkbox177_2" value="FAIL" class="large-checkbox" <?php echo isChecked(177, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[177][]" id="checkbox177_3" value="NA" class="large-checkbox" <?php echo isChecked(177, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[177]" value="<?php echo getRemark(177, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
						<tr>
                <td><strong>2.3.28</strong></td>
                <td><strong>Counterweight and counterweight buffers are in compliance with design requirements</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.21, 2.22,
8.11.2.1.3M)
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[178][]" id="checkbox178_1" value="PASS" class="large-checkbox" <?php echo isChecked(178, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[178][]" id="checkbox178_2" value="FAIL" class="large-checkbox" <?php echo isChecked(178, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[178][]" id="checkbox178_3" value="NA" class="large-checkbox" <?php echo isChecked(178, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[178]" value="<?php echo getRemark(178, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
						<tr>
                <td><strong>2.3.29</strong></td>
                <td><strong>Counterweight safeties are fitted and working  in accessible areas below the car or hoistway</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.6,2.17, 8.2.3,
8.10.2.2, 8.11.2.1.3n,
8.11.2.3.1)
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[179][]" id="checkbox179_1" value="PASS" class="large-checkbox" <?php echo isChecked(179, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[179][]" id="checkbox179_2" value="FAIL" class="large-checkbox" <?php echo isChecked(179, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[179][]" id="checkbox179_3" value="NA" class="large-checkbox" <?php echo isChecked(179, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[179]" value="<?php echo getRemark(179, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
									<tr>
                <td><strong>2.3.30</strong></td>
                <td><strong>Compensating ropes and chains are in place to tie the counterweight and car together</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.21.4, 8.10.2.2.3w-3,
8.11.2.1.3dd)
 </strong></td>
                 <td class="checkbox-cell">
    <input type="checkbox" name="result[180][]" id="checkbox180_1" value="PASS" class="large-checkbox" <?php echo isChecked(180, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[180][]" id="checkbox180_2" value="FAIL" class="large-checkbox" <?php echo isChecked(180, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[180][]" id="checkbox180_3" value="NA" class="large-checkbox" <?php echo isChecked(180, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[180]" value="<?php echo getRemark(180, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
											<tr>
                    <th style="text-align: center;">2.4</th>
                    <th style="text-align: center;">OUTSIDE HOISTWAY</th>
					<th style="text-align: center;"> </th>
                    
                    <th style="text-align: center;">PASS</th>
                    <th style="text-align: center;">FAIL</th>
                    <th style="text-align: center;">NA</th>
                    <th> </th>
                </tr>
				</thead>
 
                <tbody>
				<tr>
 <tr>
                <td><strong>2.4.1</strong></td>
                <td><strong>Car platform guard plates comply with material specification (steel ) and thickness ( not less than 1.5 mm )</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.15.9,
8.11.2.1.4a)
 </strong></td>
                  <td class="checkbox-cell">
    <input type="checkbox" name="result[181][]" id="checkbox181_1" value="PASS" class="large-checkbox" <?php echo isChecked(181, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[181][]" id="checkbox181_2" value="FAIL" class="large-checkbox" <?php echo isChecked(181, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[181][]" id="checkbox181_3" value="NA" class="large-checkbox" <?php echo isChecked(181, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[181]" value="<?php echo getRemark(181, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			
			<tr>
                <td><strong>2.4.2</strong></td>
                <td><strong>Hoistway doors operate correctly</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.12.7, 2.26.2,
8.11.2.1.4b)
 </strong></td>
                  <td class="checkbox-cell">
    <input type="checkbox" name="result[182][]" id="checkbox182_1" value="PASS" class="large-checkbox" <?php echo isChecked(182, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[182][]" id="checkbox182_2" value="FAIL" class="large-checkbox" <?php echo isChecked(182, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[182][]" id="checkbox182_3" value="NA" class="large-checkbox" <?php echo isChecked(182, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[182]" value="<?php echo getRemark(182, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>2.4.3</strong></td>
                <td><strong>Car vision panel (if fitted) is 0.1sq.m. (Max) and either wire-glass or laminated, and in the case of glass doors be laminated, safety glass or safety plastic, with not less than 60% of the total visible door panel surface area as glass. </strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec.(2.11.7,8.11.2.1.4c)
  </strong></td>
                  <td class="checkbox-cell">
    <input type="checkbox" name="result[183][]" id="checkbox183_1" value="PASS" class="large-checkbox" <?php echo isChecked(183, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[183][]" id="checkbox183_2" value="FAIL" class="large-checkbox" <?php echo isChecked(183, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[183][]" id="checkbox183_3" value="NA" class="large-checkbox" <?php echo isChecked(183, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[183]" value="<?php echo getRemark(183, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>2.4.4</strong></td>
                <td><strong>Hoistway door locking devices are operational (interlocks)</strong></td>
				<td style="text-align: center;"><strong> ASME A17.1
Sec. (2.12, 8.11.2.1.4d)
  </strong></td>
                  <td class="checkbox-cell">
    <input type="checkbox" name="result[184][]" id="checkbox184_1" value="PASS" class="large-checkbox" <?php echo isChecked(184, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[184][]" id="checkbox184_2" value="FAIL" class="large-checkbox" <?php echo isChecked(184, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[184][]" id="checkbox184_3" value="NA" class="large-checkbox" <?php echo isChecked(184, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[184]" value="<?php echo getRemark(184, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>2.4.5</strong></td>
                <td><strong>Access to hoistway (at top or bottom landing) is by use of an access switch adjacent to the entrance.</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.12.6, 2.12.7,
8.11.2.1.4e)
  </strong></td>
                  <td class="checkbox-cell">
    <input type="checkbox" name="result[185][]" id="checkbox185_1" value="PASS" class="large-checkbox" <?php echo isChecked(185, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[185][]" id="checkbox185_2" value="FAIL" class="large-checkbox" <?php echo isChecked(185, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[185][]" id="checkbox185_3" value="NA" class="large-checkbox" <?php echo isChecked(185, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[185]" value="<?php echo getRemark(185, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>2.4.6</strong></td>
                <td><strong>Hoistway doors are power closing</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.13, 8.11.2.1.4f)
 </strong></td>
                  <td class="checkbox-cell">
    <input type="checkbox" name="result[186][]" id="checkbox186_1" value="PASS" class="large-checkbox" <?php echo isChecked(186, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[186][]" id="checkbox186_2" value="FAIL" class="large-checkbox" <?php echo isChecked(186, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[186][]" id="checkbox186_3" value="NA" class="large-checkbox" <?php echo isChecked(186, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[186]" value="<?php echo getRemark(186, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>2.4.7</strong></td>
                <td><strong>Sequence of operation of hoistway doors is correct</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.13.3.4, 2.13.6, 8.11.2.1.4g)
</strong></td>
                  <td class="checkbox-cell">
    <input type="checkbox" name="result[187][]" id="checkbox187_1" value="PASS" class="large-checkbox" <?php echo isChecked(187, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[187][]" id="checkbox187_2" value="FAIL" class="large-checkbox" <?php echo isChecked(187, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[187][]" id="checkbox187_3" value="NA" class="large-checkbox" <?php echo isChecked(187, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[187]" value="<?php echo getRemark(187, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			
      <tr>
                <td><strong>2.4.8</strong></td>
                <td><strong>Hoistway enclosure is fire resistance (or non-fire resistance, depending on building code)</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.1.1, 2.1.4,
2.1.5, 8.11.2.1.4h)
</strong></td>
                  <td class="checkbox-cell">
    <input type="checkbox" name="result[188][]" id="checkbox188_1" value="PASS" class="large-checkbox" <?php echo isChecked(188, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[188][]" id="checkbox188_2" value="FAIL" class="large-checkbox" <?php echo isChecked(188, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[188][]" id="checkbox188_3" value="NA" class="large-checkbox" <?php echo isChecked(188, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[188]" value="<?php echo getRemark(188, $saved_remarks); ?>" class="form-control">
</td>
            </tr>  
 <tr>
                <td><strong>2.4.9</strong></td>
                <td><strong>Elevator parking devices are operable</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (8.11.2.1.4i)
 </strong></td>
                  <td class="checkbox-cell">
    <input type="checkbox" name="result[189][]" id="checkbox189_1" value="PASS" class="large-checkbox" <?php echo isChecked(189, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[189][]" id="checkbox189_2" value="FAIL" class="large-checkbox" <?php echo isChecked(189, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[189][]" id="checkbox189_3" value="NA" class="large-checkbox" <?php echo isChecked(189, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[189]" value="<?php echo getRemark(189, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			
			<tr>
                <td><strong>2.4.10</strong></td>
                <td><strong>Emergency doors in blind hoistway are on every third floor, not more than 11m from sill to sill with a clear opening of 700mm x 2030mm (at least)</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.11.1.1,
2.11.1.2, 8.11.2.1.4j)
 </strong></td>
                  <td class="checkbox-cell">
    <input type="checkbox" name="result[190][]" id="checkbox190_1" value="PASS" class="large-checkbox" <?php echo isChecked(190, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[190][]" id="checkbox190_2" value="FAIL" class="large-checkbox" <?php echo isChecked(190, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[190][]" id="checkbox190_3" value="NA" class="large-checkbox" <?php echo isChecked(190, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[190]" value="<?php echo getRemark(190, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>2.4.11</strong></td>
                <td><strong>Doors are self-closing and self-locking and marked "Danger Elevator Hoistway" in 50mm letters (an open or unlocked door removes power from the elevator motor)</strong></td>
				<td style="text-align: center;"><strong>     ASME A17.1
Sec. (2.11.1.1,
2.11.1.2, 8.11.2.1.4j)
</strong></td>
                  <td class="checkbox-cell">
    <input type="checkbox" name="result[191][]" id="checkbox191_1" value="PASS" class="large-checkbox" <?php echo isChecked(191, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[191][]" id="checkbox191_2" value="FAIL" class="large-checkbox" <?php echo isChecked(191, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[191][]" id="checkbox191_3" value="NA" class="large-checkbox" <?php echo isChecked(191, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[191]" value="<?php echo getRemark(191, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>2.4.12</strong></td>
                <td><strong>Access to hoistway (at top or bottom landing) is by use of an access switch adjacent to the entrance.</strong></td>
				<td style="text-align: center;"><strong> ASME A17.1
Sec. (2.12.6, 2.12.7,
8.11.2.1.4e)
  </strong></td>
                  <td class="checkbox-cell">
    <input type="checkbox" name="result[192][]" id="checkbox192_1" value="PASS" class="large-checkbox" <?php echo isChecked(192, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[192][]" id="checkbox192_2" value="FAIL" class="large-checkbox" <?php echo isChecked(192, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[192][]" id="checkbox192_3" value="NA" class="large-checkbox" <?php echo isChecked(192, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[192]" value="<?php echo getRemark(192, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>2.4.13</strong></td>
                <td><strong>Access to hoistway (at top or bottom landing) is by use of an access switch adjacent to the entrance.</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.12.6, 2.12.7,
8.11.2.1.4e)
  </strong></td>
                  <td class="checkbox-cell">
    <input type="checkbox" name="result[193][]" id="checkbox193_1" value="PASS" class="large-checkbox" <?php echo isChecked(193, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[193][]" id="checkbox193_2" value="FAIL" class="large-checkbox" <?php echo isChecked(193, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[193][]" id="checkbox193_3" value="NA" class="large-checkbox" <?php echo isChecked(193, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[193]" value="<?php echo getRemark(193, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>2.4.14</strong></td>
                <td><strong>Hoistway doors are power closing</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.13, 8.11.2.1.4f)
</strong></td>
                  <td class="checkbox-cell">
    <input type="checkbox" name="result[194][]" id="checkbox194_1" value="PASS" class="large-checkbox" <?php echo isChecked(194, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[194][]" id="checkbox194_2" value="FAIL" class="large-checkbox" <?php echo isChecked(194, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[194][]" id="checkbox194_3" value="NA" class="large-checkbox" <?php echo isChecked(194, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[194]" value="<?php echo getRemark(194, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                    <th style="text-align: center;">2.5</th>
                    <th style="text-align: center;">ELEVATOR PIT</th>
					<th style="text-align: center;"> </th>
                    
                    <th style="text-align: center;">PASS</th>
                    <th style="text-align: center;">FAIL</th>
                    <th style="text-align: center;">NA</th>
                    <th> </th>
                </tr>
				</thead>
 
                <tbody>
				<tr>
 <tr>
                <td><strong>2.5.1</strong></td>
                <td><strong>The pit access, lighting and stop switch meet design requirements</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.3.2, 2.8,
2.26.2.7, 8.6.4.7,
8.11.2.1.5a)
 </strong></td>
                  <td class="checkbox-cell">
    <input type="checkbox" name="result[195][]" id="checkbox195_1" value="PASS" class="large-checkbox" <?php echo isChecked(195, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[195][]" id="checkbox195_2" value="FAIL" class="large-checkbox" <?php echo isChecked(195, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[195][]" id="checkbox195_3" value="NA" class="large-checkbox" <?php echo isChecked(195, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[195]" value="<?php echo getRemark(195, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			
			<tr>
                <td><strong>2.5.2</strong></td>
                <td><strong>Bottom clearance, runby and minimum refuge space dimensions meet design requirements (min. 600mm) (min. 150mm for bottom runby)</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.3.2, 2.4.1,
2.4.2, 2.22.4.8,
8.6.4.11, 8.11.2.1.5b)
 </strong></td>
                  <td class="checkbox-cell">
    <input type="checkbox" name="result[196][]" id="checkbox196_1" value="PASS" class="large-checkbox" <?php echo isChecked(196, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[196][]" id="checkbox196_2" value="FAIL" class="large-checkbox" <?php echo isChecked(196, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[196][]" id="checkbox196_3" value="NA" class="large-checkbox" <?php echo isChecked(196, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[196]" value="<?php echo getRemark(196, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>2.5.3</strong></td>
                <td><strong>Final and emergency terminal stopping devices operate correctly and remove power from driving machine</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.25.3,8.10.2.2.5c, 8.11.2.1.5d)
  </strong></td>
                  <td class="checkbox-cell">
    <input type="checkbox" name="result[197][]" id="checkbox197_1" value="PASS" class="large-checkbox" <?php echo isChecked(197, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[197][]" id="checkbox197_2" value="FAIL" class="large-checkbox" <?php echo isChecked(197, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[197][]" id="checkbox197_3" value="NA" class="large-checkbox" <?php echo isChecked(197, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[197]" value="<?php echo getRemark(197, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>2.5.4</strong></td>
                <td><strong>Normal terminal stopping devices operate correctly to slow down and operate the car correctly at or near top and bottom terminal landings (up to rated load and speed)</strong></td>
				<td style="text-align: center;"><strong> ASME A17.1
Sec. (2.25, 8.11.2.2.5)
  </strong></td>
                  <td class="checkbox-cell">
    <input type="checkbox" name="result[198][]" id="checkbox198_1" value="PASS" class="large-checkbox" <?php echo isChecked(198, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[198][]" id="checkbox198_2" value="FAIL" class="large-checkbox" <?php echo isChecked(198, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[198][]" id="checkbox198_3" value="NA" class="large-checkbox" <?php echo isChecked(198, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[198]" value="<?php echo getRemark(198, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>2.5.5</strong></td>
                <td><strong>Travel cables are undamaged and serviceable</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.8.1.2)
  </strong></td>
                  <td class="checkbox-cell">
    <input type="checkbox" name="result[199][]" id="checkbox199_1" value="PASS" class="large-checkbox" <?php echo isChecked(199, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[199][]" id="checkbox199_2" value="FAIL" class="large-checkbox" <?php echo isChecked(199, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[199][]" id="checkbox199_3" value="NA" class="large-checkbox" <?php echo isChecked(199, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[199]" value="<?php echo getRemark(199, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>2.5.6</strong></td>
                <td><strong>Governor rope tension devices are operating correctly</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.18.7, 8.6.1.6.2, 8.11.2.1.5g)
 </strong></td>
                  <td class="checkbox-cell">
    <input type="checkbox" name="result[200][]" id="checkbox200_1" value="PASS" class="large-checkbox" <?php echo isChecked(200, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[200][]" id="checkbox200_2" value="FAIL" class="large-checkbox" <?php echo isChecked(200, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[200][]" id="checkbox200_3" value="NA" class="large-checkbox" <?php echo isChecked(200, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[200]" value="<?php echo getRemark(200, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>2.5.7</strong></td>
                <td><strong>Car frame and platform conforms to correct material and fixings requirements as permitted in relevant specifications</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.15.6, 2.15.8,
2.16.2.2, 8.11.2.1.5h)
</strong></td>
                  <td class="checkbox-cell">
    <input type="checkbox" name="result[201][]" id="checkbox201_1" value="PASS" class="large-checkbox" <?php echo isChecked(201, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[201][]" id="checkbox201_2" value="FAIL" class="large-checkbox" <?php echo isChecked(201, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[201][]" id="checkbox201_3" value="NA" class="large-checkbox" <?php echo isChecked(201, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[201]" value="<?php echo getRemark(201, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			
      <tr>
                <td><strong>2.5.8</strong></td>
                <td><strong>Car safeties guiding members- including roped-hydraulic elevators installed under A17.1b-1989 and later editions - are lubricated and clean</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.15, 2.17.11,
8.6.4.5, 8.7.2.15.1,
8.11.2.1.5j, 8.11.2.3.1)
</strong></td>
                  <td class="checkbox-cell">
    <input type="checkbox" name="result[202][]" id="checkbox202_1" value="PASS" class="large-checkbox" <?php echo isChecked(202, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[202][]" id="checkbox202_2" value="FAIL" class="large-checkbox" <?php echo isChecked(202, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[202][]" id="checkbox202_3" value="NA" class="large-checkbox" <?php echo isChecked(202, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[202]" value="<?php echo getRemark(202, $saved_remarks); ?>" class="form-control">
</td>
            </tr>  
 <tr>
                <td><strong>2.5.9</strong></td>
                <td><strong>Buffers and terminal speed devices are operating correctly</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.6,2.22,
2.26.2.22, 8.2.3, 8.6.1.6.3, 8.10.2.2.5c,
8.11.2.3.6)
 </strong></td>
                  <td class="checkbox-cell">
    <input type="checkbox" name="result[203][]" id="checkbox203_1" value="PASS" class="large-checkbox" <?php echo isChecked(203, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[203][]" id="checkbox203_2" value="FAIL" class="large-checkbox" <?php echo isChecked(203, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[203][]" id="checkbox203_3" value="NA" class="large-checkbox" <?php echo isChecked(203, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[203]" value="<?php echo getRemark(203, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			
			<tr>
                <td><strong>2.5.10</strong></td>
                <td><strong>Compensating ropes and chains are in place to tie the counterweight and car together</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.17.17, 2.21.4,
2.26.2.3, 8.11.2.1.5h)
 </strong></td>
                  <td class="checkbox-cell">
    <input type="checkbox" name="result[204][]" id="checkbox204_1" value="PASS" class="large-checkbox" <?php echo isChecked(204, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[204][]" id="checkbox204_2" value="FAIL" class="large-checkbox" <?php echo isChecked(204, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[204][]" id="checkbox204_3" value="NA" class="large-checkbox" <?php echo isChecked(204, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[204]" value="<?php echo getRemark(204, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                    <th style="text-align: center;">2.6</th>
                    <th style="text-align: center;">FIREFIGHTER’S SERVICE</th>
					<th style="text-align: center;"> </th>
                    
                    <th style="text-align: center;">PASS</th>
                    <th style="text-align: center;">FAIL</th>
                    <th style="text-align: center;">NA</th>
                    <th> </th>
                </tr>
				</thead>
 
                <tbody>
				<tr>
 <tr>
                <td><strong>2.6.1</strong></td>
                <td><strong>Verify / check operation of elevators under fire and other emergency conditions (A17.1b- 1973 through A17.1b- 1980)</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.13.3.4, 2.13.5,
8.6.10.1, 8.11.2.1.4l,
8.11.2.2.6)
 </strong></td>
                  <td class="checkbox-cell">
    <input type="checkbox" name="result[205][]" id="checkbox205_1" value="PASS" class="large-checkbox" <?php echo isChecked(205, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[205][]" id="checkbox205_2" value="FAIL" class="large-checkbox" <?php echo isChecked(205, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[205][]" id="checkbox205_3" value="NA" class="large-checkbox" <?php echo isChecked(205, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[205]" value="<?php echo getRemark(205, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			
			<tr>
                <td><strong>2.6.2</strong></td>
                <td><strong>Verify / check operation of elevators under fire and other emergency conditions (A17.1- 1981 through A17.1b- 1983)</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.13.3.4, 2.13.5,
8.6.10.1, 8.11.2.1.4l,
8.11.2.2.6)
 </strong></td>
                  <td class="checkbox-cell">
    <input type="checkbox" name="result[206][]" id="checkbox206_1" value="PASS" class="large-checkbox" <?php echo isChecked(206, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[206][]" id="checkbox206_2" value="FAIL" class="large-checkbox" <?php echo isChecked(206, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[206][]" id="checkbox206_3" value="NA" class="large-checkbox" <?php echo isChecked(206, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[206]" value="<?php echo getRemark(206, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>2.6.3</strong></td>
                <td><strong>Verify / check operation of elevators under fire and other emergency conditions (A17.1- 1984 through A17.1a- 1988 and A17.3)</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.13.3.4, 2.13.5,
8.6.10.1, 8.11.2.1.4l,
8.11.2.2.6)
  </strong></td>
                  <td class="checkbox-cell">
    <input type="checkbox" name="result[207][]" id="checkbox207_1" value="PASS" class="large-checkbox" <?php echo isChecked(207, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[207][]" id="checkbox207_2" value="FAIL" class="large-checkbox" <?php echo isChecked(207, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[207][]" id="checkbox207_3" value="NA" class="large-checkbox" <?php echo isChecked(207, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[207]" value="<?php echo getRemark(207, $saved_remarks); ?>" class="form-control">
</td>
            </tr>
			<tr>
                <td><strong>2.6.4</strong></td>
                <td><strong>Verify Firefighters' service (A17.1b- 1989 and later edition)</strong></td>
				<td style="text-align: center;"><strong> ASME A17.1
Sec. (2.13.3.4, 2.13.5,
8.6.10.1, 8.11.2.1.4l,
8.11.2.2.6)
  </strong></td>
                  <td class="checkbox-cell">
    <input type="checkbox" name="result[208][]" id="checkbox208_1" value="PASS" class="large-checkbox" <?php echo isChecked(208, 'PASS', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[208][]" id="checkbox208_2" value="FAIL" class="large-checkbox" <?php echo isChecked(208, 'FAIL', $saved_results); ?>>
</td>
<td class="checkbox-cell">
    <input type="checkbox" name="result[208][]" id="checkbox208_3" value="NA" class="large-checkbox" <?php echo isChecked(208, 'NA', $saved_results); ?>>
</td>
<td>
    <input type="text" name="checklist_remark[208]" value="<?php echo getRemark(208, $saved_remarks); ?>" class="form-control">
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


<!-- 
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
