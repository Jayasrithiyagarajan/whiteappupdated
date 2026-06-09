<?php 
include_once('./view-fetch.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INSPECTION CHECKLIST FOR SIDE BOOM TRACTORS </title>
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
   
   <td><img src="../../../code.png" width="80px" height="80px" alt="" /></td>
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



         <form method="post" action="?">
        <input type="hidden" name="checklist_no" value="<?php echo $row['checklist_id'] ?>" />  
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
				<td style="text-align: center;"><strong>ASME B30.14 Sec. 2.1.5 </strong></td>
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
                <td><strong>Previous inspection reports are checked </strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.5
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
                <td><strong>1.3</strong></td>
                <td><strong>Operator is certified or qualified for the specific type of equipment.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec.3.1.1(a)(1-3)</strong></td>
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
                <td><strong>1.4</strong></td>
                <td><strong>Load rating chart is applicable to the configured boom and is legible</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.1.3
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
                <td><strong>1.5</strong></td>
                <td><strong>An operating manual is available</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.1.3 (c)
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
                <td><strong>1.6</strong></td>
                <td><strong>A sign is posted warning the operator not to rely solely on any automatic device as a substitute for safe operating practice</strong></td>
				<td style="text-align: center;"><strong>CIMS QHSE 06</strong></td>
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
                <td><strong>1.7</strong></td>
                <td><strong>Rated capacity of crane is marked</strong></td>
				<td style="text-align: center;"><strong>CIMS QHSE 06
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
                <td><strong>1.8</strong></td>
                <td><strong>A fire extinguisher with minimum rating of 10 BC is installed in the cab or at the machinery housing</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 3.4.4 (a)

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
                <td><strong>2.1</strong></td>
                <td><strong>Control levers are all operable from the operator station</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.3.1&2
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
                <td><strong>2.2</strong></td>
                <td><strong>Control pedals are functioning correctly</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.3.1&2
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
                <td><strong>2.3</strong></td>
                <td><strong>All controls are labeled as to their functions and within reach of operator</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.4.1
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
                <td><strong>2.4</strong></td>
                <td><strong>Guards are fitted and secured to cover exposed moving parts</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.7.5
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
                <td><strong>2.5</strong></td>
                <td><strong>Guards are fitted and secured to cover exposed moving parts</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.7.5
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
                <td><strong>2.6</strong></td>
                <td><strong>All moving parts that require lubrication are lubricated and accessible</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.3.4 (b)
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
                <td><strong>2.7</strong></td>
                <td><strong>Boom angle indicator is functioning correctly</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.2 (c2)
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
                <td><strong>2.8</strong></td>
                <td><strong>Boom hoist shut off is functioning correctly</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.2 (c2)
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
                <td><strong>2.9</strong></td>
                <td><strong>Load indicator is functioning correctly</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.2 (c2)
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
                <td><strong>2.10</strong></td>
                <td><strong>Capacity indicator is functioning correctly</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.2 (c2)
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
                <td><strong>2.11</strong></td>
                <td><strong>Ignition system is operating correctly</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.4.3 (a)
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
                <td><strong>2.12</strong></td>
                <td><strong>Emergency shut down is operating satisfactorily</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.4.3 (c)
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
                <td><strong>2.13</strong></td>
                <td><strong>Engine throttle is functioning satisfactorily</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec.1.4.3 (b)
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
                <td><strong>2.14</strong></td>
                <td><strong> Battery is secured and ventilated</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.3 (f)
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
                <td><strong>2.15</strong></td>
                <td><strong>Engine gauges are functioning correctly</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.2 (c7)
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
                <td><strong>2.16</strong></td>
                <td><strong>Exhaust system is not corroded, and is guarded or insulated where necessary to prevent personal contact with hot surfaces</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.7.2
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
                <td><strong>2.17</strong></td>
                <td><strong>Engine oil level is adequate.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.3.1
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
                <td><strong>2.18</strong></td>
                <td><strong>Hydraulic systems are not leaking</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.2 (c3)
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
                <td><strong>2.19</strong></td>
                <td><strong>Oil and fuel tanks do not leak</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.2 (c3)
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
                <td><strong>2.20</strong></td>
                <td><strong>Hydraulic/Pneumatic systems are not leaking (pumps, valves, lines, etc.</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.2 (c3)
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
                <td><strong>2.21</strong></td>
                <td><strong>Hook assembly labeling and manufacturer data is clearly marked</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10
Sec. 1.1.1
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
                <td><strong>2.22</strong></td>
                <td><strong>Hook's weight is clearly marked/printed on the hook</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10
Sec. 1.1.1
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
                <td><strong>2.23</strong></td>
                <td><strong> Safe working load of hook is clearly marked on the item
</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10
Sec. 1.1.1
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
                <td><strong>2.24</strong></td>
                <td><strong>Hook does not show defects such as nicks, cracks and gouges</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10
Sec. 1.2.1.2 (c3)
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
                <td><strong>2.25</strong></td>
                <td><strong>Hook is not bent or twisted
Max. bending or twisting not to exceed 10 degrees from plane of unbent hook or as per manufacturer recommendations
</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10 Sec.10-1.2.1.3(c1)
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
                <td><strong>2.26</strong></td>
                <td><strong>Hook is not distorted in the throat opening
Max. allowable throat opening is 15% compared to new hook, or as per manufacturer recommendations
</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10
Sec. 1.2.1.3 (c2)
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
                <td><strong>2.27</strong></td>
                <td><strong>Maximum wear in the hook bowl is not exceeding 10% (compared to new hook) or as per manufacturer recommendations</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10
Sec. 1.2.1.3 (c3)
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
                <td><strong>2.28</strong></td>
                <td><strong>Hook is not cracked, gouged or shows nicks</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10
Sec. 1.2.1.2 (c3)
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
                <td><strong>2.29</strong></td>
                <td><strong>Hook can lock (if it is a self-locking hook)</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10
Sec. 1.2.1.3 (c4)
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
                <td><strong>2.30</strong></td>
                <td><strong>Hook latch is operative</strong></td>
				<td style="text-align: center;"><strong>ASME B30.10
Sec. 1.2.1.3 (c5)
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
                <td><strong>2.31</strong></td>
                <td><strong>Upper and lower structures are free of defective/corroded welds</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.7.4
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
                <td><strong>2.32</strong></td>
                <td><strong>Boom mounting ears are not cracked</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.3(d)
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
                <td><strong>2.33</strong></td>
                <td><strong> Boom bushings and pins are not worn and are secure</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.3 (d)
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
                <td><strong>2.34</strong></td>
                <td><strong>Machinery deck frame is undamaged</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.3 (a)
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
                <td><strong>2.35</strong></td>
                <td><strong>Boom hoist cylinder mount is secure</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.2.1
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
                <td><strong>2.36</strong></td>
                <td><strong>A-frame frontage is undamaged
</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.7.3 (a)
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
                <td><strong>2.37</strong></td>
                <td><strong>A-frame back legs are undamaged</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.7.3 (a)
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
                <td><strong>2.38</strong></td>
                <td><strong>Float mast is undamaged</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.7.3 (d)
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
                <td><strong>2.39</strong></td>
                <td><strong>Sheave grooves are free from surface defects and lubricated</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.5.4 (a)
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
                <td><strong>2.40</strong></td>
                <td><strong>Rope Carrying sheaves have close-fittings guards or other suitable devices</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.5.4 (b)
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
                <td><strong>2.41</strong></td>
                <td><strong>Lower block sheaves have close-fittings guards</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14 
Sec. 1.5.4(c)
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
                <td><strong>2.42</strong></td>
                <td><strong>Inner bridle frame is undamaged</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
 Sec. 2.1.3(a)
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
                <td><strong>2.43</strong></td>
                <td><strong>Inner bridle frame sheaves are smooth in their grooves and lubricated</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.4 (a)
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
                <td><strong>2.44</strong></td>
                <td><strong>Bearings and bushings are undamaged, secure and lubricated</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.3 (d)
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
                <td><strong>2.45</strong></td>
                <td><strong>Outer bridle frame is undamaged</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.3 (a)
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
                <td><strong>2.46</strong></td>
                <td><strong>Outer bridle frame sheaves are smooth in their grooves and lubricated</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.5.4
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
                <td><strong>2.47</strong></td>
                <td><strong>Boom stops are fitted and undamaged</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14 Sec.1.7.1 (a)
Sec. 2.2.1 (3)
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
                <td><strong>2.48</strong></td>
                <td><strong>Load hoist brake function is satisfactory</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.2.2 (b)
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
                <td><strong>2.49</strong></td>
                <td><strong>Load lifting and lowering operations are satisfactory</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.2.2 (a)
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
                <td><strong>2.50</strong></td>
                <td><strong>Two full wraps, at least, of rope remain on the drum when the hook is in its extreme low working position</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.2.2 (a2)
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
                <td><strong>2.51</strong></td>
                <td><strong>Rope ends are anchored to the drum by clamps or as per manufacturer recommendations</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.2.2 (a2)
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
                <td><strong>2.52</strong></td>
                <td><strong>Drums are provided with a guidance or other means to prevent rope from jumping off the drum</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.2.2 (a2)
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
                <td><strong>2.53</strong></td>
                <td><strong>Power controlled lowering is operational and meets the speed and load criteria as per manufacturers specifications</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.2.2 (c)
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
                <td><strong>2.54</strong></td>
                <td><strong>Positive braking means is available, controllable by the operator, to prevent drum rotation in the lowering direction</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.2.2 (a4)
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
                <td><strong>2.55</strong></td>
                <td><strong>The rope does not have more than 6 randomly distributed broken wires in 1 lay or 3 in 1 strand in 1 lay (for running ropes)</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.4.2 (b1)
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
                <td><strong>2.56</strong></td>
                <td><strong>The ropes does not have more than two broken wires in 1 lay in sections beyond end connections or more than 1 broken wire at an end connection (for standing </strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.4.2 (b6)
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
                <td><strong>2.57</strong></td>
                <td><strong>The rope wear does not exceed 1/3 of the original diameter</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.4.2 (b2)
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
                <td><strong>2.58</strong></td>
                <td><strong>The rope does not have kinking, crushing, bird caging, evidence of heat damage, unstranding, core corrosion, main strand displacement or any other damages</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14 Sec. 2.4.1 (a)
Sec. 2.4.2 (b3)
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
                <td><strong>2.59</strong></td>
                <td><strong>Boom hoist mechanism (raising and lowering) is properly operating</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.2.1
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
                <td><strong>2.60</strong></td>
                <td><strong>Boom hoist brake and clutch are correctly operating</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.2.1 (a)
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
                <td><strong>2.61</strong></td>
                <td><strong>Boom hoist brake and clutch have adjustments to compensate for wear</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.2.1 (b)
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
                <td><strong>2.62</strong></td>
                <td><strong>Locking pawl and ratchet is in good working condition to prevent inadvertent lowering of the boom</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.2.1 (c)
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
                <td><strong>2.63</strong></td>
                <td><strong>Two full wraps, at least, of rope remain on the drum when the hook is in its extreme low working position)</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.2.2 (a2)
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
                <td><strong>2.64</strong></td>
                <td><strong>Rope ends are anchored to the drum by clamps or as per manufacturer recommendations</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.2.2 (a2)
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
                <td><strong>2.65</strong></td>
                <td><strong>Drums are provided with a guidance or other means to prevent rope from jumping off the drum</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.2.2 (a2)
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
                <td><strong>2.66</strong></td>
                <td><strong>The rope does not have more than 6 randomly distributed broken wires in 1 lay or 3 in 1 strand in 1 lay (for running ropes)</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.4.2 (b1)
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
                <td><strong>2.67</strong></td>
                <td><strong>The ropes does not have more than two broken wires in 1 lay in sections beyond end connections or more than 1 broken wire at an end connection (for standing </strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.4.2 (b6)
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
                <td><strong>2.68</strong></td>
                <td><strong>The rope wear does not exceed 1/3 of the original diameter</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.4.2 (b2)
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
                <td><strong>2.69</strong></td>
                <td><strong>The rope does not have kinking, crushing, bird caging, evidence of heat damage, unstranding, core corrosion, main strand displacement or any other damages</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.4.1 (a)
Sec. 2.4.2 (b3)
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
                <td><strong>2.70</strong></td>
                <td><strong>Boom hoist cylinder and associated hydraulic system is operating correctly</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.2 (c3)

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
                <td><strong>2.71</strong></td>
                <td><strong>Boom hoist cylinder seals are not leaking</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.2 (c3)
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
                <td><strong>2.72</strong></td>
                <td><strong>Track shoes are all in place and not loose or broken</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.3 (a, b, d)
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
                <td><strong>2.73</strong></td>
                <td><strong>Track rollers and followers are turning and not loose, worn or seized</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.3 (a, b, d)
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
                <td><strong>2.74</strong></td>
                <td><strong>End tumblers are serviceable</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.3 (a, b, d)
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
                <td><strong>2.75</strong></td>
                <td><strong>Track tension is sufficient for ground conditions</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14 S
Sec. 2.1.3 (g)
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
                <td><strong>2.76</strong></td>
                <td><strong>Drive chain is not stretched excessively</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.3 (g)
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
                <td><strong>2.77</strong></td>
                <td><strong>Drive gearbox operates without excessive noise or leakage</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.3.3 (1)
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
                <td><strong>2.78</strong></td>
                <td><strong>End sprockets are not excessively worn at the teeth</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14 S
Sec. 2.1.3 (g)
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
                <td><strong>2.79</strong></td>
                <td><strong>Frame to axle fastenings are secured and not loose</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.3 (b)
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
                <td><strong>2.80</strong></td>
                <td><strong>Stabilizer struts are undamaged and secure</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.3 (g)
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
                <td><strong>2.81</strong></td>
                <td><strong>Propelling forward is accepted</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.2.2 (a)
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
                <td><strong>2.82</strong></td>
                <td><strong>Propelling backward is accepted</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.2.2 (a)
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
                <td><strong>2.83</strong></td>
                <td><strong>Transmission gearbox functioning well and gears are operational</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.4.3 (d)
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
                <td><strong>2.84</strong></td>
                <td><strong>Steering to the right is operational</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.3 (h)
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
                <td><strong>2.85</strong></td>
                <td><strong>Steering to the left is operational</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.3 (h)
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
                <td><strong>2.86</strong></td>
                <td><strong>Travel locks are operational and remain in engagement</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.3 (h)
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
                <td><strong>2.87</strong></td>
                <td><strong>All safety limit devices are operational</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.3.2
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
                <td><strong>2.88</strong></td>
                <td><strong>Cab glass is made of safety glazing material</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.6.1 (c)
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
                <td><strong>2.89</strong></td>
                <td><strong>Screen wipers are in good operational condition</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.6.1 (d)
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
                <td><strong>2.90</strong></td>
                <td><strong>Road mirrors are fitted and undamaged</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.6.1 (d)
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
                <td><strong>2.91</strong></td>
                <td><strong>Fire extinguisher is available in the cab (minimum rating is 10BC)</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 3.4.4
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
                <td><strong>2.92</strong></td>
                <td><strong>Gauges and instrumentations are in good working condition</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.2 (c7)
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
                <td><strong>2.93</strong></td>
                <td><strong>Brakes are in working condition</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.3 (e)
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
                <td><strong>2.94</strong></td>
                <td><strong>Parking brake is in working condition</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.3 (e)
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
                <td><strong>2.95</strong></td>
                <td><strong>Clutch is in good working condition</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.3.3
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
                <td><strong>2.96</strong></td>
                <td><strong>Gear selector is properly working</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.4.3 (d)
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
                <td><strong>2.97</strong></td>
                <td><strong>Back-up alarm is operable</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.2 (c2)
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
                <td><strong>2.98</strong></td>
                <td><strong>Headlights are working</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.2 (c2)
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
                <td><strong>2.99</strong></td>
                <td><strong>Brake lights are working</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.2 (c2)
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
                <td><strong>3.1</strong></td>
                <td><strong>Turn signals are working</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.2 (c2)
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
                <td><strong>3.2</strong></td>
                <td><strong>Ignition system is operating correctly</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.4.3 (a)
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
                <td><strong>3.3</strong></td>
                <td><strong>Throttle is operating correctly</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.4.3 (b)
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
                <td><strong>3.4</strong></td>
                <td><strong>Battery is secure and ventilated</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.3 (f)
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
                <td><strong>3.5</strong></td>
                <td><strong>Gauges are in working condition
</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.2 (c7)
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
                <td><strong>3.6</strong></td>
                <td><strong>Engine exhaust system is not corroded, and is guarded or insulated where necessary to prevent personal contact with hot surfaces</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.7.2
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
                <td><strong>3.7</strong></td>
                <td><strong>Oil level is sufficient</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.3.1
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
                <td><strong>3.8</strong></td>
                <td><strong>Torque converter is in working condition</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.3.3
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
                <td><strong>3.9</strong></td>
                <td><strong>Main transmission is in working condition</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.3.3
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
                <td><strong>3.10</strong></td>
                <td><strong>Auxiliary transmission is in working condition</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.3.3
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
                <td><strong>3.11</strong></td>
                <td><strong>Tires and rims are undamaged and serviceable</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.3 (I)
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
                <td><strong>3.12</strong></td>
                <td><strong>Outrigger beam sections are not corroded, cracked or damaged</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.7.3 (a)
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
                <td><strong>3.13</strong></td>
                <td><strong>Outrigger pads are securely fitted and undamaged</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.7.3 (d)
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
                <td><strong>3.14</strong></td>
                <td><strong>Pad pins are locked and undamaged</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.7.3
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
                <td><strong>3.15</strong></td>
                <td><strong>Extension cylinders are working correctly and undamaged</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.7.3
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
                <td><strong>3.16</strong></td>
                <td><strong>Vertical cylinders are working correctly and undamaged</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.7.3
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
                <td><strong>3.17</strong></td>
                <td><strong>Boom tip section is undamaged, has no cracks or distortion</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.3 (a)
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
                <td><strong>3.18</strong></td>
                <td><strong>Boom extension cylinder is properly working with no signs of leaks)</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.2 (c3)
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
                <td><strong>3.19</strong></td>
                <td><strong>Guide sheaves are not worn and are well lubricated</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.3 (c)
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
                <td><strong>3.20</strong></td>
                <td><strong>Main sheaves are smooth in their grooves and well lubricated</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.5.4
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
                <td><strong>3.21</strong></td>
                <td><strong>Bushings/bearings are not worn and are well lubricated</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 2.1.3
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
                <td><strong>3.22</strong></td>
                <td><strong>Guards are fitted and secured to cover exposed moving parts</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.7.5
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
                <td><strong>3.23</strong></td>
                <td><strong>Rope wedge anchor is correctly fitted and secure, and is fitted in accordance with the manufacturer’s recommendations</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 1.5.3 (c)
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
                <td><strong>3.24</strong></td>
                <td><strong>Counterweight(s) are securely fitted and locked in position on the operating arms</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 3.4.1
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
                <td><strong>3.25</strong></td>
                <td><strong>Operating rams are undamaged with no seal or hose leaks
</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 3.4.1
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
                <td><strong>3.26</strong></td>
                <td><strong>Back stops are in place and secure</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 3.4.1
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
                <td><strong>3.27</strong></td>
                <td><strong>Complete counterweight and arm assembly shows no cracks or deformation</strong></td>
				<td style="text-align: center;"><strong>ASME B30.14
Sec. 3.4.1
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