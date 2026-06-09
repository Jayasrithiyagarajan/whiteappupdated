<?php 
include_once('./view-fetch.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INSPECTION CHECKLIST FOR ELEVATORS AND ESCALATORS </title>
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

        .container{
            max-width: 934px;

        }
/* Custom checkbox styling */
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
    cursor: not-allowed; /* Indicates it's disabled */
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
@media print {
    /* .custom-checkbox {
        border-color: #007bff;
        background-color: #007bff;
    } */

    .custom-checkbox:checked::after {
        border-color: blue;
    }
    
    body * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    
    th {
        background-color: #c0d6e8 !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    
    /* Specifically style the dark headers */
    .thead-dark th {
        background-color: #c0d6e8 !important;
        border-color: #454d55 !important;
    }
    .custom-checkbox:checked::after {
        border-color: blue;
    }
    
    /* For printing */

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
   
   <td><img src="../../../code.png" width="80px" height="80px" alt="" /></td>
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
         
         <div class="table-responsive">
        <table class="table table-bordered">
            <tr>
                <th style="width: 25%; background-color: #c0d6e8 !important;">REPORT NO</th>
                <td style="width: 25%;"><strong><?php echo $row['report_no']; ?></strong></td>
                <th style="width: 25%; background-color: #c0d6e8 !important;">INSPECTION DATE</th>
                <td style="width: 25%;"><strong><?php echo date('F j, Y', strtotime($row['inspection_date'])); ?></strong></td>
            </tr>
            <tr>
                <th style="background-color: #c0d6e8 !important;">CLIENT’S NAME</th>
                <td><strong><?php echo $row['client_name']; ?></strong></td>
                <th style="background-color: #c0d6e8 !important;">INSPECTED BY</th>
                <td><strong><?php echo $row['inspected_by']; ?></strong></td>
            </tr>
            <tr>
                <th style="background-color: #c0d6e8 !important;">LOCATION</th>
                <td><strong><?php echo $row['location']; ?></strong></td>
                <th style="background-color: #c0d6e8 !important;">STICKER NO.</th>
                <td><strong><?php echo $row['sticker_no']; ?></strong></td>
            </tr>
            <tr>
                <th style="background-color: #c0d6e8 !important;">EQUIPMENT NO</th>
                <td><strong><?php echo $row['equipment_no']; ?></strong></td>
                <th style="background-color: #c0d6e8 !important;">EQUIP.SERIAL NO.:</th>
                <td><strong><?php echo $row['crane_serial_no']; ?></strong></td>
            </tr>
            <tr>
                <th style="background-color: #c0d6e8 !important;">EQUIPMENT TYPE</th>
                <td><strong><?php echo $row['equipmenttype']; ?></strong></td>
                <th style="background-color: #c0d6e8 !important;">CAPACITY (SWL)</th>
                <td><strong><?php echo $row['capacity_swl']; ?></strong></td>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[0]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[0]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[0]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[0];?>" disabled>
    </td>
            </tr>
			
			<tr>
                <td><strong>1.1.2</strong></td>
                <td><strong>  Emergency stop switches are not provided on passenger elevators but are provided on freight elevators, in the car and in or adjacent to each car operating panel </strong></td>
				<td style="text-align: center;"><strong> ASME A17.1 Sec. (3.26.4.2a,
3.26.4.2f, 8.11.3.1.1b)

 </strong></td>
 <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[1]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[1]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[1]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[1];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[2]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[2]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[2]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[2];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[3]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[3]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[3]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[3];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[4]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[4]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[4]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[4];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[5]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[5]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[5]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[5];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[6]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[6]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[6]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[6];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[7]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[7]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[7]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[7];?>" disabled>
    </td>
            </tr>  
 <tr>
                <td><strong>1.1.9</strong></td>
                <td><strong>Car has minimum of two lamps (min. of 50 lux for passenger and 25 lux for freight elevators) (Passenger elevators shall have auxiliary lighting which automatically turns on if normal power fails)</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1 
Sec. (3.14, 8.11.3.1.1e)
 </strong></td>
 <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[8]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[8]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[8]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[8];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[9]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[9]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[9]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[9];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[10]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[10]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[10]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[10];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[11]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[11]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[11]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[11];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[12]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[12]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[12]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[12];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[13]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[13]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[13]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[13];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[14]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[14]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[14]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[14];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[15]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[15]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[15]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[15];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[16]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[16]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[16]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[16];?>" disabled>
    </td>
            </tr>
			
			<tr>
                <td><strong>1.1.18</strong></td>
                <td><strong>Signs and operating device symbols are installed and legible</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1 (2.26.12, 8.11.3.1.1b)
 </strong></td>
 <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[17]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[17]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[17]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[17];?>" disabled>
    </td>
            </tr>
			<tr>
                <td><strong>1.1.19</strong></td>
                <td><strong>Signs and operating device symbols are installed and legible</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.26.12, 8.11.3.1.1b)
</strong></td>
<td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[18]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[18]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[18]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[18];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[19]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[19]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[19]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[19];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[20]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[20]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[20]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[20];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[21]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[21]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[21]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[21];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[22]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[22]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[22]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[22];?>" disabled>
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
				</thead>
 
                <tbody>
				<tr>

 <tr>
                <td><strong>1.2.1</strong></td>
                <td><strong> Access to the machine space is in conformance with the type of access, location , and combustibility allowed</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1 
Sec. (3.1,3.7, 8.11.3.1.2a)
 </strong></td>
                <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[23]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[23]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[23]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[23];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[24]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[24]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[24]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[24];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[25]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[25]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[25]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[25];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[26]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[26]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[26]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[26];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[27]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[27]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[27]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[27];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[28]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[28]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[28]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[28];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[29]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[29]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[29]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[29];?>" disabled>
    </td>
            </tr>
			
      <tr>
                <td><strong>1.2.8</strong></td>
                <td><strong>Pipes, wiring and ducts conform to the relevant specification (Pipes - 15psi steam or hot water only; wiring to NFPA70 or CSA-C22.1 standard).</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1 
Sec. (2.8.1, 2.8.2 (3.8), 8.11.3.1.2h)
</strong></td>
                <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[30]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[30]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[30]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[30];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[31]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[31]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[31]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[31];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[32]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[32]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[32]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[32];?>" disabled>
    </td>
            </tr>
			<tr>
                <td><strong>1.2.11</strong></td>
                <td><strong>Electrical disconnecting means (devices) and controls operate correctly</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (3.26, 3.26.3.1 (3.26.3.1.4b), 8.11.3.1.2k)
</strong></td>
                <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[33]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[33]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[33]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[33];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[34]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[34]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[34]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[34];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[35]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[35]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[35]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[35];?>" disabled>
    </td>
            </tr>
			<tr>
                <td><strong>1.2.14</strong></td>
                <td><strong>Code date plate states correct information and is legible</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1 
Sec. (8.7.1.8, 8.9)
</strong></td>
                <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[36]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[36]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[36]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[36];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[37]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[37]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[37]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[37];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[38]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[38]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[38]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[38];?>" disabled>
    </td>
            </tr>
 <tr>
                <td><strong>1.2.17</strong></td>
                <td><strong>Hydraulic control valve(s) are marked with their rated pressure and electrical data</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (3.19, 8.11.3.1.2o)
 </strong></td>
                <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[39]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[39]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[39]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[39];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[40]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[40]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[40]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[40];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[41]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[41]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[41]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[41];?>" disabled>
    </td>
            </tr>
			<tr>
                <td><strong>1.2.20</strong></td>
                <td><strong>Supply line and shutoff line are leak-free, and the shut-off valve is located between pump and jack and outside the hoist way</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1 
  Sec. (3.19, 8.11.3.1.2r)
</strong></td>
                <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[42]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[42]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[42]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[42];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[43]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[43]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[43]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[43];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[44]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[44]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[44]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[44];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[45]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[45]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[45]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[45];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[46]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[46]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[46]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[46];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[47]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[47]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[47]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[47];?>" disabled>
    </td>
            </tr>
			<tr>
                <td><strong>1.3.3</strong></td>
                <td><strong>Car top operating device is provided (for inspection purposes)</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (3.26.2, 8.11.3.1.3c)
  </strong></td>
                <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[48]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[48]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[48]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[48];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[49]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[49]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[49]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[49];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[50]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[50]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[50]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[50];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[51]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[51]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[51]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[51];?>" disabled>
    </td>
            </tr>
			<tr>
                <td><strong>1.3.7</strong></td>
                <td><strong>Anti-creep device controls the car within 25mm of the landing irrespective of hoist way door position</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1  Sec.(3.26.3, 3.26.4,
8.11.3.1.3g)
</strong></td>
                <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[52]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[52]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[52]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[52];?>" disabled>
    </td>
            </tr>
			
      <tr>
                <td><strong>1.3.8</strong></td>
                <td><strong>Top emergency exit is at least 16" square</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1 
Sec. (2.14.1.5 (3.14), 8.11.3.1.3i)
</strong></td>
                <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[53]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[53]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[53]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[53];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[54]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[54]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[54]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[54];?>" disabled>
    </td>
            </tr>
			
			<tr>
                <td><strong>1.3.10</strong></td>
                <td><strong>Hoist way construction complies with appropriate standards and building regulations (where applicable)</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (3.1, 8.11.3.1.3k)
 </strong></td>
                <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[55]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[55]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[55]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[55];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[56]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[56]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[56]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[56];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[57]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[57]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[57]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[57];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[58]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[58]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[58]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[58];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[59]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[59]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[59]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[59];?>" disabled>
    </td>
            </tr>
			<tr>
                <td><strong>1.3.15</strong></td>
                <td><strong>Multiple hoist ways (and the number of elevators in a hoist way) conforms with the appropriate building code</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1 
Sec. (2.1.1.4 (3.1), 8.11.3.1.3p)
</strong></td>
                <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[60]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[60]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[60]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[60];?>" disabled>
    </td>
            </tr>
			
      <tr>
                <td><strong>1.3.16</strong></td>
                <td><strong>Traveling cables and junction boxes conforms to NFPA70 or CSA - C22.1, whichever is applicable</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1 Sec. (2.8.1 (3.8),
8.11.3.1.3q)
</strong></td>
                <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[61]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[61]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[61]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[61];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[62]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[62]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[62]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[62];?>" disabled>
    </td>
            </tr>
			
			<tr>
                <td><strong>1.3.18</strong></td>
                <td><strong>Car frame and stiles are suitable for the purpose and show no defects</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (3.15, 8.8 (3.18.5), 8.11.3.1.3s)
 </strong></td>
                <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[63]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[63]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[63]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[63];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[64]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[64]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[64]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[64];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[65]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[65]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[65]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[65];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[66]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[66]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[66]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[66];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[67]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[67]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[67]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[67];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[68]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[68]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[68]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[68];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[69]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[69]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[69]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[69];?>" disabled>
    </td>
            </tr>
	<tr>
                <td><strong>1.3.25</strong></td>
                <td><strong>Slack rope device (roped-hydraulic elevators installed under A17.1b- 1989 and later editions) does cause the electric power to be removed from the hydraulic machine pump motor and control valves should a rope become slack</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1 
Sec. (3.18.1.2, 3.26.4, 8.11.3.1.3z)
</strong></td>
                <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[70]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[70]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[70]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[70];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[71]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[71]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[71]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[71];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[72]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[72]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[72]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[72];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[73]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[73]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[73]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[73];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[74]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[74]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[74]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[74];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[75]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[75]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[75]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[75];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[76]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[76]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[76]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[76];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[77]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[77]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[77]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[77];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[78]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[78]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[78]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[78];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[79]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[79]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[79]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[79];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[80]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[80]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[80]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[80];?>" disabled>
    </td>
            </tr>  
 <tr>
                <td><strong>1.4.9</strong></td>
                <td><strong>Elevator parking devices are operable</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (8.11.3.1.4i)
 </strong></td>
                <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[81]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[81]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[81]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[81];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[82]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[82]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[82]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[82];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[83]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[83]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[83]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[83];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[84]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[84]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[84]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[84];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[85]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[85]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[85]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[85];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[86]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[86]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[86]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[86];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[87]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[87]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[87]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[87];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[88]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[88]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[88]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[88];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[89]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[89]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[89]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[89];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[90]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[90]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[90]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[90];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[91]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[91]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[91]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[91];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[92]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[92]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[92]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[92];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[93]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[93]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[93]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[93];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[94]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[94]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[94]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[94];?>" disabled>
    </td>
            </tr>
			<tr>
                <td><strong>1.5.12</strong></td>
                <td><strong> Oil supply piping meets design requirements (as per manufacturer) and is leak-proof and secure</strong></td>
				<td style="text-align: center;"><strong>  ASME A17.1
Sec. (2.24, 8.10.3.2.2r, 8.11.3.1.5i)
  </strong></td>
                <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[95]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[95]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[95]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[95];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[96]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[96]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[96]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[96];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[97]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[97]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[97]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[97];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[98]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[98]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[98]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[98];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[99]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[99]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[99]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[99];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[100]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[100]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[100]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[100];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[101]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[101]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[101]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[101];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[102]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[102]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[102]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[102];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[103]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[103]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[103]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[103];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[104]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[104]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[104]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[104];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[105]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[105]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[105]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[105];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[106]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[106]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[106]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[106];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[107]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[107]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[107]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[107];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[108]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[108]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[108]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[108];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[109]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[109]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[109]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[109];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[110]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[110]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[110]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[110];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[111]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[111]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[111]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[111];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[112]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[112]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[112]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[112];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[113]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[113]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[113]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[113];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[114]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[114]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[114]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[114];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[115]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[115]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[115]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[115];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[116]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[116]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[116]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[116];?>" disabled>
    </td>
            </tr>
			
			<tr>
                <td><strong>2.1.18</strong></td>
                <td><strong>Rated load (depending on net platform) is in compliance with  load area (chart) and data plate information</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.16, 8.11.2.1.1p)
 </strong></td>
                <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[117]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[117]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[117]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[117];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[118]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[118]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[118]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[118];?>" disabled>
    </td>
            </tr>
			<tr>
                <td><strong>2.1.20</strong></td>
                <td><strong>Restricted opening of car or hoist way doors (4" max) is possible outside the unlocking zone</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (8.11.2.1.1r)
</strong></td>
                <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[119]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[119]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[119]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[119];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[120]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[120]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[120]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[120];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[121]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[121]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[121]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[121];?>" disabled>
    </td>
            </tr>
			
			<tr>
                <td><strong>2.2.2</strong></td>
                <td><strong>Minimum headroom clearance is either 84" , 53", 42", or 35" depending on type and location of machine room / hoist way</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.7.4, 8.11.2.1.2c)
 </strong></td>
                <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[122]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[122]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[122]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[122];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[123]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[123]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[123]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[123];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[124]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[124]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[124]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[124];?>" disabled>
    </td>
            </tr>
			<tr>
                <td><strong>2.2.5</strong></td>
                <td><strong>Housekeeping is adequate</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (8.6.4.8, 8.6.10.3, 8.11.2.1.2e)
  </strong></td>
                <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[125]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[125]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[125]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[125];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[126]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[126]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[126]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[126];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[127]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[127]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[127]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[127];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[128]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[128]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[128]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[128];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[129]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[129]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[129]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[129];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[130]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[130]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[130]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[130];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[131]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[131]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[131]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[131];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[132]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[132]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[132]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[132];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[133]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[133]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[133]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[133];?>" disabled>
    </td>
            </tr>
			<tr>
                <td><strong>2.2.14</strong></td>
                <td><strong>Code date plate indicates the code and edition in effect at the time of installation (or alteration)</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (8.7.1.8, 8.9)
</strong></td>
                <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[134]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[134]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[134]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[134];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[135]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[135]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[135]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[135];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[136]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[136]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[136]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[136];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[137]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[137]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[137]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[137];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[138]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[138]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[138]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[138];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[139]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[139]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[139]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[139];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[140]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[140]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[140]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[140];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[141]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[141]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[141]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[141];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[142]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[142]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[142]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[142];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[143]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[143]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[143]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[143];?>" disabled>
    </td>
            </tr>
						<tr>
                <td><strong>2.2.24</strong></td>
                <td><strong>AC drives from DC source use a static inverter and other devices as a means of control</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1 Sec.(2.26.2, 2.26.9.6, 8.11.2.1.2v, </strong></td>
                <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[144]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[144]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[144]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[144];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[145]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[145]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[145]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[145];?>" disabled>
    </td>
            </tr>
						<tr>
                <td><strong>2.2.26</strong></td>
                <td><strong>Secondary and deflector sheaves comply as 1.2.25 above</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1 
Sec. (2.24, 8.6.1.6.2, 8.11.2.1.2x)
 </strong></td>
                <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[146]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[146]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[146]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[146];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[147]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[147]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[147]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[147];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[148]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[148]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[148]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[148];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[149]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[149]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[149]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[149];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[150]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[150]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[150]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[150];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[151]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[151]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[151]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[151];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[152]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[152]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[152]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[152];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[153]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[153]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[153]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[153];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[154]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[154]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[154]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[154];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[155]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[155]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[155]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[155];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[156]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[156]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[156]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[156];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[157]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[157]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[157]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[157];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[158]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[158]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[158]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[158];?>" disabled>
    </td>
            </tr>
			
			<tr>
                <td><strong>2.3.10</strong></td>
                <td><strong>Hoistway construction complies with appropriate standards and building regulations (where applicable)</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.1, 8.11.2.1.3p)
 </strong></td>
                <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[159]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[159]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[159]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[159];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[160]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[160]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[160]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[160];?>" disabled>
    </td>
            </tr>
			<tr>
                <td><strong>2.3.12</strong></td>
                <td><strong>Pipes, wiring and ducts conform to the relevant specification (Pipes - 15psi steam or hot water only; wiring to NFPA70 or CSA-C22.1 standard)</strong></td>
				<td style="text-align: center;"><strong> ASME A17.1
Sec. (2.8, 8.11.2.1.3r)
  </strong></td>
                <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[161]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[161]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[161]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[161];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[162]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[162]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[162]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[162];?>" disabled>
    </td>
            </tr>
			<tr>
                <td><strong>2.3.14</strong></td>
                <td><strong>Various hoistway clearances are at least the same all the way around (20mm)</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec.(2.4, 2.5, 8.11.2.1.3t)
</strong></td>
                <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[163]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[163]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[163]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[163];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[164]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[164]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[164]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[164];?>" disabled>
    </td>
            </tr>
			
      <tr>
                <td><strong>2.3.16</strong></td>
                <td><strong>Traveling cables and junction boxes conforms to NFPA70 or CSA -C22.1 whichever is applicable</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.8.1, 8.11.2.1.3v)
</strong></td>
                <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[165]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[165]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[165]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[165];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[166]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[166]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[166]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[166];?>" disabled>
    </td>
            </tr>
			
			<tr>
                <td><strong>2.3.18</strong></td>
                <td><strong>Car frame and stiles are suitable for the purpose and show no defects</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1 Sec. (2.15, 8.6.2,
8.7.2.15.1, 8.8)
 </strong></td>
                <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[167]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[167]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[167]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[167];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[168]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[168]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[168]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[168];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[169]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[169]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[169]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[169];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[170]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[170]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[170]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[170];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[171]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[171]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[171]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[171];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[172]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[172]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[172]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[172];?>" disabled>
    </td>
            </tr>
						<tr>
                <td><strong>2.3.24</strong></td>
                <td><strong>Top counterweight clearance is not less than the sum of all other relevant clearances such as bottom run by, car buffer stroke, 50% of gravity stopping distance, plus 150mm</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1 Sec. (2.4.9,8.11.2.1.3e)</strong></td>
                <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[173]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[173]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[173]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[173];?>" disabled>
    </td>
            </tr>
						<tr>
                <td><strong>2.3.25</strong></td>
                <td><strong>Traction sheaves comply with requirements as to material (metal) and finished grooves.  Diameter to be not less than 40 times rope diameter (suspension ropes) or not less than 32 times rope diameter (compensating ropes)</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.24)
 </strong></td>
                <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[174]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[174]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[174]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[174];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[175]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[175]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[175]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[175];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[176]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[176]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[176]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[176];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[177]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[177]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[177]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[177];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[178]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[178]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[178]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[178];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[179]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[179]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[179]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[179];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[180]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[180]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[180]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[180];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[181]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[181]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[181]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[181];?>" disabled>
    </td>
            </tr>
			<tr>
                <td><strong>2.4.3</strong></td>
                <td><strong>Car vision panel (if fitted) is 0.1sq.m. (Max) and either wire-glass or laminated, and in the case of glass doors be laminated, safety glass or safety plastic, with not less than 60% of the total visible door panel surface area as glass. </strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec.(2.11.7,8.11.2.1.4c)
  </strong></td>
                <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[182]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[182]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[182]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[182];?>" disabled>
    </td>
            </tr>
			<tr>
                <td><strong>2.4.4</strong></td>
                <td><strong>Hoistway door locking devices are operational (interlocks)</strong></td>
				<td style="text-align: center;"><strong> ASME A17.1
Sec. (2.12, 8.11.2.1.4d)
  </strong></td>
                <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[183]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[183]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[183]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[183];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[184]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[184]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[184]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[184];?>" disabled>
    </td>
            </tr>
			<tr>
                <td><strong>2.4.6</strong></td>
                <td><strong>Hoistway doors are power closing</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.13, 8.11.2.1.4f)
 </strong></td>
                <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[185]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[185]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[185]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[185];?>" disabled>
    </td>
            </tr>
			<tr>
                <td><strong>2.4.7</strong></td>
                <td><strong>Sequence of operation of hoistway doors is correct</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.13.3.4, 2.13.6, 8.11.2.1.4g)
</strong></td>
                <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[186]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[186]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[186]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[186];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[187]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[187]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[187]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[187];?>" disabled>
    </td>
            </tr>  
 <tr>
                <td><strong>2.4.9</strong></td>
                <td><strong>Elevator parking devices are operable</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (8.11.2.1.4i)
 </strong></td>
                <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[188]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[188]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[188]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[188];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[189]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[189]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[189]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[189];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[190]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[190]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[190]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[190];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[191]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[191]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[191]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[191];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[192]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[192]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[192]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[192];?>" disabled>
    </td>
            </tr>
			<tr>
                <td><strong>2.4.14</strong></td>
                <td><strong>Hoistway doors are power closing</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.13, 8.11.2.1.4f)
</strong></td>
                <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[193]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[193]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[193]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[193];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[194]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[194]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[194]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[194];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[195]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[195]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[195]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[195];?>" disabled>
    </td>
            </tr>
			<tr>
                <td><strong>2.5.3</strong></td>
                <td><strong>Final and emergency terminal stopping devices operate correctly and remove power from driving machine</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.25.3,8.10.2.2.5c, 8.11.2.1.5d)
  </strong></td>
                <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[196]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[196]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[196]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[196];?>" disabled>
    </td>
            </tr>
			<tr>
                <td><strong>2.5.4</strong></td>
                <td><strong>Normal terminal stopping devices operate correctly to slow down and operate the car correctly at or near top and bottom terminal landings (up to rated load and speed)</strong></td>
				<td style="text-align: center;"><strong> ASME A17.1
Sec. (2.25, 8.11.2.2.5)
  </strong></td>
                <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[197]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[197]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[197]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[197];?>" disabled>
    </td>
            </tr>
			<tr>
                <td><strong>2.5.5</strong></td>
                <td><strong>Travel cables are undamaged and serviceable</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.8.1.2)
  </strong></td>
                <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[198]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[198]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[198]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[198];?>" disabled>
    </td>
            </tr>
			<tr>
                <td><strong>2.5.6</strong></td>
                <td><strong>Governor rope tension devices are operating correctly</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.18.7, 8.6.1.6.2, 8.11.2.1.5g)
 </strong></td>
                <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[199]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[199]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[199]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[199];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[200]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[200]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[200]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[200];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[201]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[201]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[201]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[201];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[202]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[202]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[202]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[202];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[203]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[203]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[203]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[203];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[204]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[204]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[204]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[204];?>" disabled>
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
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[205]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[205]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[205]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[205];?>" disabled>
    </td>
            </tr>
			<tr>
                <td><strong>2.6.3</strong></td>
                <td><strong>Verify / check operation of elevators under fire and other emergency conditions (A17.1- 1984 through A17.1a- 1988 and A17.3)</strong></td>
				<td style="text-align: center;"><strong>ASME A17.1
Sec. (2.13.3.4, 2.13.5, 8.6.10.1, 8.11.2.1.4l, 8.11.2.2.6)
  </strong></td>
                <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[206]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[206]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[206]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[206];?>" disabled>
    </td>
            </tr>
			<tr>
                <td><strong>2.6.4</strong></td>
                <td><strong>Verify Firefighters' service (A17.1b- 1989 and later edition)</strong></td>
				<td style="text-align: center;"><strong> ASME A17.1
Sec. (2.13.3.4, 2.13.5, 8.6.10.1, 8.11.2.1.4l, 
8.11.2.2.6)
  </strong></td>
                <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[207]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[207]=="FAIL"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox3" value="NA" 
        <?php echo $selected_results[207]=="NA"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td>
        <input type="text" name="remarks[0]" value="<?php echo $chek_remark[207];?>" disabled>
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
                    
                <?php echo htmlspecialchars($recommendations); ?> 
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