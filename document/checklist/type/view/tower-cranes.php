<?php 
include_once('./view-fetch.php');
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INSPECTION CHECKLIST FOR TOWER CRANES</title>
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
            <strong>INSPECTION CHECKLIST FOR TOWER CRANES</strong>
        </td>
    </tr>
    <tr>
        <td>FRM.0601-1.13</td>
        <td>Revision 02</td>
        <td>Issue Date: 30/SEP/2020</td>
    </tr>
    <tr>
        <td class="left-align">Prepared By<br>Operations Manager</td>
        <td class="left-align">Reviewed & Approved By<br>Managing Director</td>
         
   <td><img src="../../../code.png" width="80px" height="80px" alt="" /></td>
    </tr>
</table>
			</div>

        <h4>TOWER CRANES</h4>
        <h4>ASME B30.3-2016</h4>
		
        
		 <!--<button class="btn btn-primary no-print" onclick="preparePrint()">Print View</button>-->

         <div class="table-responsive">
            <table class="table table-bordered">
                
				
				<tr>
                <th style="width: 25%; background-color: #c0d6e8 !important;">REPORT NO</th>
                <td style="width: 25%;"><strong> <?php echo htmlspecialchars($row['report_no']); ?></strong></td>
                <th style="width: 25%; background-color: #c0d6e8 !important;">INSPECTION DATE</th>
                <td style="width: 25%;"><strong> <?php echo htmlspecialchars($row['inspection_date']); ?></strong></td>
            </tr>
            <tr>
                <th style="background-color: #c0d6e8 !important;">CLIENT’S NAME</th>
                <td><strong><?php echo htmlspecialchars($row['client_name']); ?></strong></td>
                <th style="background-color: #c0d6e8 !important;">INSPECTED BY</th>
                <td><strong> <?php echo htmlspecialchars($row['inspected_by']); ?></strong></td>
            </tr>
            <tr>
                <th style="background-color: #c0d6e8 !important;">LOCATION</th>
                <td><strong> <?php echo htmlspecialchars($row['location']); ?></strong></td>
                <th style="background-color: #c0d6e8 !important;">STICKER NO.</th>
                <td><strong> <?php echo htmlspecialchars($row['sticker_no']); ?></strong></td>
            </tr>
            <tr>
                <th style="background-color: #c0d6e8 !important;">EQUIPMENT NO</th>
                <td><strong> <?php echo htmlspecialchars($row['equipment_no']); ?></strong></td>
                <th style="background-color: #c0d6e8 !important;">EQUIP.SERIAL NO.:</th>
                <td><strong> <?php echo htmlspecialchars($row['crane_serial_no']); ?></strong></td>
            </tr>
            <tr>
                <th style="background-color: #c0d6e8 !important;">EQUIPMENT TYPE</th>
                <td><strong> <?php echo htmlspecialchars($row['equipmenttype']); ?></strong></td>
                <th style="background-color: #c0d6e8 !important;">CAPACITY (SWL)</th>
                <td><strong> <?php echo htmlspecialchars($row['capacity_swl']); ?></strong></td>
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
                    <th style="text-align: center;">1</th>
                    <th style="text-align: center;">MARKINGS, DOCUMENTS</th>
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
                <td><strong>Documentation is available such as but not limited to; operator’s manual, manufacturer’s informal literature, etc.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3 sec.1.9(a)
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
                <td><strong>1.2</strong></td>
                <td><strong>An installation preparation instruction is provided. </strong></td>
				<td style="text-align: center;"><strong>ASME B30.3 sec.1.9.1(a)
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
                <td><strong>1.3</strong></td>
                <td><strong>Structure or anchor has the information data plate bearing the Manufacturer Name, Type/Model Number, Serial Number, & Year of Manufacture.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3 sec 1.5(h)(2)</strong></td>
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
                <td><strong>1.4</strong></td>
                <td><strong>Structure has an identification number / asset number marked on it.</strong></td>
				<td style="text-align: center;"><strong>CIMS QHSE 06</strong></td>
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
                <td><strong>1.5</strong></td>
                <td><strong>Crane’s SWL (Rated Load) is prominently marked on the structure.</strong></td>
				<td style="text-align: center;"><strong>CIMS QHSE 06</strong></td>
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
                <td><strong>1.6</strong></td>
                <td><strong>Load Rating chart of the crane is provided.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3 sec 1.9.2</strong></td>
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
                <td><strong>1.7</strong></td>
                <td><strong>General erection & dismantling requirements are met (Drawings & Calculations).</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3 
sec 1.2-4
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
                <td><strong>1.8</strong></td>
                <td><strong>The crane is operated by the qualified, competent, or certified operator.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 3.1.1(a-1,2,3,4(b)</strong></td>
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
                    <th style="text-align: center;">2</th>
                    <th style="text-align: center;">INSPECTION & TESTING</th>
					<th style="text-align: center;"> </th>
                    
                    <th style="text-align: center;">PASS</th>
                    <th style="text-align: center;">FAIL</th>
                    <th style="text-align: center;">NA</th>
                    <th> </th>
                </tr>
				
 <tr>
                <td><strong>2.1</strong></td>
                <td><strong>Structures such as but not limited to, tower masts, knee braces, cross beams, climbing ladders, climbing cross sections have no signs of cracks, corrosions, bends, deformations.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3 sec 1.6.1</strong></td>
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
                <td><strong>2.2</strong></td>
                <td><strong>Tie-in braces and pins are secured.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3 
sec 1.6.1</strong></td>
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
                <td><strong>2.3</strong></td>
                <td><strong>Climbing pawls and wedges are secured.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3 
sec 1.6.2
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
                <td><strong>2.4</strong></td>
                <td><strong>Tower’s anchor bolts at base are properly mounted and secured.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3 
sec 1.3
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
                <td><strong>2.5</strong></td>
                <td><strong>Expendable base and knee-braced base are installed properly.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3 
sec 1.5
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
                <td><strong>2.6</strong></td>
                <td><strong>Load & luffing/jib boom hoist drives are provided with a clutch or power disengaging device unless directly coupled to an electric or hydraulic power motor source.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
Sec 1.7
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
                <td><strong>2.7</strong></td>
                <td><strong>All functions are checked and working correctly, i.e., but not limited to, luffing/jib booms’ hoisting & lowering, structure’s slewing, load block’s lowering & hoisting, trolley traversing.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
Sec 1.7(3.a-f)
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
                <td><strong>2.8</strong></td>
                <td><strong>Motion limiting devices and brakes of load hoist, luffing/jib boom hoist are checked.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
Sec 1.7(4.a-c)
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
                <td><strong>2.9</strong></td>
                <td><strong>All controls, drives, and braking means devices are checked which include; load block hoisting & lowering; luffing boom hoisting and lowering; swinging of the upper structure; brake and clutch functioning; limit, locking, and safety device functioning; and load-limiting devices for proper operation.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
Sec 1.7(4.a-c)
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
                <td><strong>2.10</strong></td>
                <td><strong>Over-speed protection is provided for hoist and luffing boom mechanisms.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
Sec 1.10(c)
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
                <td><strong>2.11</strong></td>
                <td><strong>Luffing/jib boom and load hoist free-fall lowering is not provided. Ensure that they shall be done only under power control.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
Sec 1.10(c)
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
                <td><strong>2.12</strong></td>
                <td><strong>Luffing/jib boom hoist powered by hydraulic is not dropping.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.10(3)
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
                <td><strong>2.13</strong></td>
                <td><strong>Luffing boom back stop switch is provided for the maximum boom angle.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
 sec 1.10(4)
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
                <td><strong>2.14</strong></td>
                <td><strong>The luffing/jib hoist rope is securely anchored on the drum as per the manufacturer recommendation. </strong></td>
				<td style="text-align: center;"><strong>ASME B30.3 
sec 1.10.2 (a)
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
                <td><strong>2.15</strong></td>
                <td><strong>The diameter of the drum is sufficient to provide a first layer rope pitch diameter of not less than 18 times the nominal diameter of the rope used.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
 sec 1.10(c)
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
                <td><strong>2.16</strong></td>
                <td><strong>The remaining rope on load hoist drum shall not be less than three full wraps when the hook is in its extreme lowest position.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.10(d)
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
                <td><strong>2.17</strong></td>
                <td><strong>The remaining rope on luffing/jib boom hoist shall not be less than three full wraps when the luffing/jib boom is at its maximum permissible radius.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.10(e)
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
                <td><strong>2.18</strong></td>
                <td><strong>Load hoist drum and luffing boom hoist drums are provided with a positive holding device, such as ratchets and pawls, unless directly coupled to electric or hydraulic drives.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.10(f)</strong></td>
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
                <td><strong>2.19</strong></td>
                <td><strong>Positive holding devices are controlled only from the operator’s station; hold the drums from rotating in the lowering direction, and capable of holding the rated load indefinitely, or luffing boom and rated load indefinitely, as applicable without further attention from the operator.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.10(g)</strong></td>
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
                <td><strong>2.20</strong></td>
                <td><strong>Luffing boom hoist rope and load hoist rope shall be equipped with at least one braking means that is capable of providing minimum of 125 % of the full load hoisting torque at the point of where the braking is applied.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3 sec 1.10.3(a)</strong></td>
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
                <td><strong>2.21</strong></td>
                <td><strong>A secondary emergency brake is provided on the luffing boom hoist drum for use in the event of a main drive failure.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3 sec 1.10.3(a)</strong></td>
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
                <td><strong>2.22</strong></td>
                <td><strong>Load hoist and luffing boom hoist mechanisms are equipped with braking means capable of providing controlled lowering speeds. </strong></td>
				<td style="text-align: center;"><strong>ASME B30.3 sec 1.10.3(b)</strong></td>
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
                <td><strong>2.23</strong></td>
                <td><strong>An automatic means is provided for controlling the load hoist or the luffing boom hoist to stop and hold the load in the event of loss of brake actuating power.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3 sec 1.10.3(c)</strong></td>
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
                <td><strong>2.24</strong></td>
                <td><strong>If foot pedal is provided, it is holding the brakes in the applied position without further attention from the operator.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3 sec 1.10.3(c)</strong></td>
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
                <td><strong>2.25</strong></td>
                <td><strong>Sheave bearings are provided with a means for lubrication, except for those that are permanently lubricated.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3 sec 1.10.4(c)</strong></td>
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
                <td><strong>2.26</strong></td>
                <td><strong>The pitch diameter of the load block sheaves are not less than 18 times the nominal diameter of the rope used.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3 sec 1.10.4(d)</strong></td>
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
                <td><strong>2.27</strong></td>
                <td><strong>The pitch diameter of luffing boom hoist sheaves are not less than 15 times the nominal diameter of the rope used.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3 sec 1.10.4(d)</strong></td>
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
                <td><strong>2.28</strong></td>
                <td><strong>The load block sheaves are equipped with close fitting guard to prevent ropes from becoming fouled when the block is lying on the ground. </strong></td>
				<td style="text-align: center;"><strong>ASME B30.3 sec 1.10.4(e)</strong></td>
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
                <td><strong>2.29</strong></td>
                <td><strong>Rope end socketing is as per the manufacturer.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3 sec 1.10.5(g)</strong></td>
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
                <td><strong>2.30</strong></td>
                <td><strong>Rotation-resistant rope is not used for luffing boom hoist.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3 sec 1.10.5(h)</strong></td>
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
                <td><strong>2.31</strong></td>
                <td><strong>Design factor for luffing boom hoist rope is not less than 3.5</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3 sec 1.10.5(c)</strong></td>
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
                <td><strong>2.32</strong></td>
                <td><strong>Design factor for load hoist rope is not less than 5.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3 sec 1.10.5(b)</strong></td>
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
                <td><strong>2.33</strong></td>
                <td><strong>Load hook is equipped with safety latches and working properly.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3 sec 1.11(a), ASME B30.10 sec 1 & 5 (i) </strong></td>
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
                <td><strong>2.34</strong></td>
                <td><strong>No pitting or corrosion is visible.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10 sec 1 & 5(c)</strong></td>
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
                <td><strong>2.35</strong></td>
                <td><strong>No signs of cracks, nicks, or gouges are visible.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10 sec 1 & 5(d)</strong></td>
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
                <td><strong>2.36</strong></td>
                <td><strong>Load hook is marked with its SWL and weight.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10 sec 1 & 5(a)</strong></td>
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
                <td><strong>2.37</strong></td>
                <td><strong>The wear on the hook does not exceed 10% from the original.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10 sec 1 & 5(e)</strong></td>
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
                <td><strong>2.38</strong></td>
                <td><strong>There is no deformation that is visibly apparent bend or twist from the plane of the unbent hook.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10 sec 1 & 5(f)</strong></td>
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
                <td><strong>2.39</strong></td>
                <td><strong>No any distortion causing an increase in the throat opening of 5% that exceeded ¼ in. (6mm) or as recommended by the manufacturer.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10 sec 1 & 5(g)</strong></td>
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
                <td><strong>2.40</strong></td>
                <td><strong>Self-locking hook is able to lock.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10 sec 1 & 5(h)</strong></td>
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
                <td><strong>2.41</strong></td>
                <td><strong>No damaged, missing, or malfunctioning hook attachment.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10 sec 1 & 5(j)</strong></td>
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
                <td><strong>2.42</strong></td>
                <td><strong>No thread wear or corrosion is evident.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10 sec 1 & 5(k)</strong></td>
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
                <td><strong>2.43</strong></td>
                <td><strong>No evidence of heat exposure or unauthorized welding.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10 sec 1 & 5(l)</strong></td>
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
                <td><strong>2.44</strong></td>
                <td><strong>No evidence of unauthorized alteration such as drilling, machining, grinding or other modifications.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10 sec 1 & 5(m)</strong></td>
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
                <td><strong>2.45</strong></td>
                <td><strong>Swing mechanism is capable of smooth starts and stops and of providing variable degrees of acceleration and deceleration.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3 
sec 1.12.1(a)
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
                <td><strong>2.46</strong></td>
                <td><strong>Crane is equipped with means to rotate freely when it is out of service in order to weathervane.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3 
sec 1.1.1(b)
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
                <td><strong>2.47</strong></td>
                <td><strong>Braking means with holding power in both directions is provided. </strong></td>
				<td style="text-align: center;"><strong>ASME B30.3 
sec 1.12.2(a)
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
                <td><strong>2.48</strong></td>
                <td><strong>Brakes apply automatically when electrical power or actuating force is lost.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3 
sec 1.12.2(b)
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
                <td><strong>2.49</strong></td>
                <td><strong>Travel drives are capable of smooth starts and stops, and providing variable degrees of acceleration and deceleration.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
 sec 1.13.1(a)
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
                <td><strong>2.50</strong></td>
                <td><strong>Cable spooling is provided.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.13.1(b)
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
                <td><strong>2.51</strong></td>
                <td><strong>Audible signal automatically sounds continuously whenever the crane travels.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.13.1(c)
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
                <td><strong>2.52</strong></td>
                <td><strong>Crane bogies are fitted with sweeps at each end of the bogie and extending below the top of the rail.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.13.2(a)
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
                <td><strong>2.53</strong></td>
                <td><strong>Bogie wheels are guarded.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.13.2(b)
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
                <td><strong>2.54</strong></td>
                <td><strong>Means are provided to limit the drop of bogie frames to a distance that will not cause the crane to overturn in case of wheel or axle breakage.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.13.2(c)
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
                <td><strong>2.55</strong></td>
                <td><strong>Braking means are provided to hold the crane In position when not travelling and to lock the wheels against rotation.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.13.3(a)
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
                <td><strong>2.56</strong></td>
                <td><strong>Brakes automatically engaged on loss of electrical power or actuating force to the brake. </strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.13.3(b)
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
                <td><strong>2.57</strong></td>
                <td><strong>Guides are provided to hold the ladders in position for engagement of the climbing dogs.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.14.(a)
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
                <td><strong>2.58</strong></td>
                <td><strong>Hydraulic cylinders used to support the crane during climbing are equipped with check valves.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.14.(b)1
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
                <td><strong>2.59</strong></td>
                <td><strong>Hydraulic system is provided with pressure gauges and over pressure relief valves.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.14.(b)3
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
                <td><strong>2.60</strong></td>
                <td><strong>Positive means to hold the raised portion of the crane in position at the completion of an intermediate climbing step. </strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.14.(c)
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
                <td><strong>2.61</strong></td>
                <td><strong>Pressurized hydraulic cylinders are not used to support the crane when in service.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.14.(c)
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
                <td><strong>2.62</strong></td>
                <td><strong>Wedges when used shall be provided with means to hold them in place and prevent them from becoming dislodged.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.14.(d)
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
                <td><strong>2.63</strong></td>
                <td><strong>Ropes have a minimum breaking force not less than 3.5 times the load applied to the rope.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.14.(e)
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
                <td><strong>2.64</strong></td>
                <td><strong>Trolley is capable of smooth starts and stops and providing variable degrees of acceleration and deceleration when traversing the jib during operations.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.15.(a)
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
                <td><strong>2.65</strong></td>
                <td><strong>Trolley stops or buffers are provided on both ends of the jib.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.15.(b)
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
                <td><strong>2.66</strong></td>
                <td><strong>The body or frame of the trolley is fitted with means to retrain the trolley from becoming detached from its guide rails.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.15.(c)
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
                <td><strong>2.67</strong></td>
                <td><strong>Braking means is provided and capable of stopping in both directions.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.15.(d)
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
                <td><strong>2.68</strong></td>
                <td><strong>A brake is holding the trolley without further action when power or pressure is lost.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.15.(d)
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
                <td><strong>2.69</strong></td>
                <td><strong>Trolley is equipped with an automatic braking device in case of the rope breakage.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.15.(e)
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
                    <th style="text-align: center;">3</th>
                    <th style="text-align: center;">OPERATOR AIDS</th>
					<th style="text-align: center;"> </th>
                    
                    <th style="text-align: center;">PASS</th>
                    <th style="text-align: center;">FAIL</th>
                    <th style="text-align: center;">NA</th>
                    <th> </th>
                </tr>
				
 <tr>
                <td><strong>3.1</strong></td>
                <td><strong>Indicating device shall be provided to display the load on the hook.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.17.(a)1
</strong></td>
                 <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox1" value="PASS" 
        <?php echo $selected_results[77]=="PASS"?'checked':''; ?> disabled class="custom-checkbox"> 
    </td>
    <td class="checkbox-cell">
        <input type="checkbox" name="checked_arr[0][]" id="checkbox2" value="FAIL" 
        <?php echo $selected_results[77]=="FAIL"?'check6ed':''; ?> disabled class="custom-checkbox">  
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
                <td><strong>3.2</strong></td>
                <td><strong>Indicating device shall be provided to display the luffing boom angle, hook radius, or trolley operating radius, as appropriate.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.17.(a)2
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
                <td><strong>3.3</strong></td>
                <td><strong>Indicating device shall be provided to display the ambient wind velocity</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.17.(a)3
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
                <td><strong>3.4</strong></td>
                <td><strong>Limiting device shall be provided to decelerate the trolley travel at both ends of the jib prior to final limit activation.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.17.(b)1
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
                <td><strong>3.5</strong></td>
                <td><strong>Limiting device shall be provided to decelerate the luffing boom travel at minimum and maximum radius prior to final limit activation.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.17.(b)2
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
                <td><strong>3.6</strong></td>
                <td><strong>Limiting device shall limit trolley travel at both ends of the jib.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.17.(b)3
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
                <td><strong>3.7</strong></td>
                <td><strong>Limiting device shall stop the luffing boom travel at minimum and maximum radius of luffing boom.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.17.(b)4
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
                <td><strong>3.8</strong></td>
                <td><strong>Limiting device shall decelerate the load block travel prior to final limit activation.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.17.(b)5
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
                <td><strong>3.9</strong></td>
                <td><strong>Limiting device shall stop load block upward motion before two blocking occurs.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.17.(b)6
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
                <td><strong>3.10</strong></td>
                <td><strong>Limiting device shall stop load block downward motion to prevent from spooling off the drum.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.17.(b)7
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
                <td><strong>3.11</strong></td>
                <td><strong>Limiting device shall limit the crane travel at both ends of the running tracks.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.17.(b)8
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
                <td><strong>3.12</strong></td>
                <td><strong>Limiting device shall limit the load lifted.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.17.(b)9
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
                <td><strong>3.13</strong></td>
                <td><strong>Limiting device shall limit operating radius in accordance with crane’s rated capacity, i.e. load moment.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.17.(b)10
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
                <td><strong>3.14</strong></td>
                <td><strong>Limiting device shall limit pressures in hydraulic or pneumatic circuits.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.17.(b)11
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
                <td><strong>3.15</strong></td>
                <td><strong>Motion limiting devices, should be provided with means to permit the operator to override them under controlled conditions.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.17.(c)
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
                    <th style="text-align: center;">4</th>
                    <th style="text-align: center;">PENDANTS, STAY ROPES, AND GUYS, COUNTERWEIGHTS, COUNTER JIBS</th>
					<th style="text-align: center;"> </th>
                    
                    <th style="text-align: center;">PASS</th>
                    <th style="text-align: center;">FAIL</th>
                    <th style="text-align: center;">NA</th>
                    <th> </th>
                </tr>
	 <tr>
                <td><strong>4.1</strong></td>
                <td><strong>Fiber core ropes with swayed fittings and rotation-resistant ropes shall not be used for pendants, guy ropes and stay ropes.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.18.(a)
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
                <td><strong>4.2</strong></td>
                <td><strong>Rotation-resistant ropes shall be used for luffing boom.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.18.(a)
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
                <td><strong>4.3</strong></td>
                <td><strong>Wire rope clips are drop-forged steel of the single (U-bolt) or double saddle type clip.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.19.(d)
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
                <td><strong>4.4</strong></td>
                <td><strong>Means to prevent the shifting or dislodgement of superstructure and counterjib’s counter weight during crane operation is provided.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.20.(a)
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
                <td><strong>4.5</strong></td>
                <td><strong>Counterweights and ballast blocks are individually marked with their actual weights and visible when they are in installed position.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.20.(b)
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
                <td><strong>4.6</strong></td>
                <td><strong>Only steel-framed concrete or solid steel counterweights suspended from the superstructure are used.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.20.(c)
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
                <td><strong>4.7</strong></td>
                <td><strong>Movable counterweights, if provided, are moving automatically.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.20.(d)
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
                <td><strong>4.8</strong></td>
                <td><strong>Means to prevent uncontrolled movement in the event of rope failure for counterweights controlled by ropes is provided.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.20.(d)1
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
                <td><strong>4.9</strong></td>
                <td><strong>Controls are within the reach of the operator.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.21.1(a)
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
                <td><strong>4.10</strong></td>
                <td><strong>All controls are labeled of their mode of functions.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.21.1(b)
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
                <td><strong>4.11</strong></td>
                <td><strong>Hoisting, trolleying, luffing, slewing, and travel motions are stopping when control actuation pressure is released.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.21.1(c)
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
                <td><strong>4.12</strong></td>
                <td><strong>An interlock that prevents the re-actuation, except from the neutral position, of controls is provided.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.21.1(c)
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
                <td><strong>4.13</strong></td>
                <td><strong>The crane stops when signal is lost for remote operated cranes. </strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.21.1(d)
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
                <td><strong>4.14</strong></td>
                <td><strong>The device that will disconnect all motors from the line on failure of power and will not permit any motor to be restarted until the operational control is brought to the neutral position and a manual reset is activated is provided for electric motor powered cranes.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.21.1(e)
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
                <td><strong>4.15</strong></td>
                <td><strong>An electric motor powered crane is provided with means for operator to interrupt the main power circuit from the operating position.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.21.1(f)
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
                <td><strong>4.16</strong></td>
                <td><strong>A remote control station is provided with emergency stop button.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.21.1(g)
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
                <td><strong>4.17</strong></td>
                <td><strong>Simultaneous activation of controls is not possible when more than one operator’s station (remote control) is provided.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.21.1(h)
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
                <td><strong>4.18</strong></td>
                <td><strong>Cranes powered by hydraulic motors shall stop the main power supply system when hydraulic pressure is lost.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.21.1(i)
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
                <td><strong>4.19</strong></td>
                <td><strong>Controls for the main power supply system shall be within the reach of the operator, and will include the following: controlling the speed of the engine, means to control in stopping the engine, means for shifting the transmission’s gear selection.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.21.2(a)1,2,3,4
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
                <td><strong>4.20</strong></td>
                <td><strong>Cabs should be provided for the operator’s station.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.23.1(a)
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
                <td><strong>4.21</strong></td>
                <td><strong>Cab doors are opening outward or sliding.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.23.1(d)
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
                <td><strong>4.22</strong></td>
                <td><strong>An adjustable operator seat is provided.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.23.1(b)
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
                <td><strong>4.23</strong></td>
                <td><strong>Windshield is of safety glazing glass.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.23.1(e)
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
                <td><strong>4.24</strong></td>
                <td><strong>The operator cab shall be on the operating portion of the crane.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.23.1(g)
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
                <td><strong>4.25</strong></td>
                <td><strong>An access ladder to the cab is provided.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.23.2(a)
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
                <td><strong>4.26</strong></td>
                <td><strong>Outside platforms have walking surfaces of a skid resistant type.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.23.2(b)
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
                <td><strong>4.27</strong></td>
                <td><strong>Tool box is available for storage of small tools.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.23.3 
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
                <td><strong>4.28</strong></td>
                <td><strong>Fire extinguisher with a basic minimum classification of 10-BC is provided in the cab or at the machinery housing..</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.23.4
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
                <td><strong>4.29</strong></td>
                <td><strong>Footwalks and ladders: 18in. or more in width and a slip resistant surface and with handrails or a platform attached to the trolley having a slip resistant surface and handrails.ded</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.24.1
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
                <td><strong>4.30</strong></td>
                <td><strong>Guards are installed for exposed moving parts such as gears, drive chains, sprockets, and other rotating parts.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.24.2(a)</strong></td>
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
                <td><strong>4.31</strong></td>
                <td><strong>Each guard shall be capable of supporting the weight of a 300-lb (136 kg) person without permanent distortion.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.24.2(b)
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
                <td><strong>4.32</strong></td>
                <td><strong>Lubrication points should be accessible without the necessity of removing guards or other parts with tools unless equipped with centralized lubrication.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.24.3
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
                <td><strong>4.33</strong></td>
                <td><strong>Engine exhaust gas is to be piped and discharged away from the operator’s cabin.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.24.4
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
                <td><strong>4.34</strong></td>
                <td><strong>Dry friction clutches are protected against rain and other liquids, such as oil and lubricants.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.24.6(a)
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
                <td><strong>4.35</strong></td>
                <td><strong>Clutches are configured to permit adjustments where necessary to compensate wear.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.24.6(b)
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
                <td><strong>4.36</strong></td>
                <td><strong>An anemometer is installed.(Wind Velocity Device)</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.24.7
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
                <td><strong>4.37</strong></td>
                <td><strong>Fuel tank filler pipes are located or protected to prevent spillage or overflow.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.24.8
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
                <td><strong>4.38</strong></td>
                <td><strong>Relief valves are provided in hydraulic and pneumatic circuits carrying fluids pressurized by a power driven pump.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.24.9(a)
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
                <td><strong>4.39</strong></td>
                <td><strong>Means to prevent unauthorized adjustment or tampering is provided.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.24.9(b)
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
                <td><strong>4.40</strong></td>
                <td><strong>Means for checking the manufacturer’s specified pressure settings in each circuit is provided.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 1.24.9(c)
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
                <td><strong>4.41</strong></td>
                <td><strong>Ropes have no loss of rope diameter in a short rope length or unevenness of outer strands.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 2.4.1.2(a)
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
                <td><strong>4.42</strong></td>
                <td><strong>Rope has broken or cut strands.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 2.4.1.2(b)(c)
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
                <td><strong>4.43</strong></td>
                <td><strong>In running ropes, 12 randomly distributed broken wires in one lay, or four broken wires in one strand in one lay.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 2.4.3(b)1
g</strong></td>
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
                <td><strong>4.44</strong></td>
                <td><strong>In rotation-resistant ropes, two randomly distributed broken wires in six rope diameters, or four randomly distributed broken wires in 30 rope diameters.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 2.4.3(b)2
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
                <td><strong>4.45</strong></td>
                <td><strong>One outer wire broken at the contact point with the core of the rope indicated by an externally protruding wire or loop of loose wires.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 2.4.3(b)3
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
                <td><strong>4.46</strong></td>
                <td><strong>Wear of one-third the original diameter of outside individual wires.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 2.4.3(b)4
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
                <td><strong>4.47</strong></td>
                <td><strong>Kinking, crushing, birdcaging, or any other damage resulting to distortion of the rope structure.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 2.4.3(b)5
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
                <td><strong>4.48</strong></td>
                <td><strong>Evidence of heat damage from any cause.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 2.4.3(b)6
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
                <td><strong>4.49</strong></td>
                <td><strong>Reduction from nominal diameter greater than 5%.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 2.4.3(b)7
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
                <td><strong>4.50</strong></td>
                <td><strong>More than two broken wires adjacent to the socketed end connection, the rope shall be re-socketed or replaced.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.3
sec 2.4.3(b)8
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
  <a href="../../index.php" class="mr-4 btn btn-primary">Back</a>
 <button type="submit" onclick="window.print()" class="btn btn-primary">Print</button>
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