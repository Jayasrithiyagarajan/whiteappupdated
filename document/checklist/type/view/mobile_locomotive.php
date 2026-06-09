<?php 
include_once('./view-fetch.php');
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INSPECTION CHECKLIST FOR MOBILE AND LOCOMOTIVE CRANES  </title>
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

.keep-together {
    page-break-inside: avoid;
    break-inside: avoid;
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
            <strong>INSPECTION CHECKLIST FOR MOBILE AND LOCOMOTIVE CRANES  </strong>
        </td>
    </tr>
    <tr>
        <td>FRM.0601-1.1</td>
        <td>Revision 04</td>
        <td><b>Issue Date: </b>22/AUG/2021</td>
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
                <td colspan="3" style="text-align: center;"><strong>INSPECTION CHECKLIST FOR MOBILE AND LOCOMOTIVE CRANES  </strong></td>
				</tr>
            <tr>
                <td style="width: 25%; text-align: center;"><strong>FRM.0601-1.1</strong></td>
                <td style="width: 25%; text-align: center;"> <strong>Revision 04</strong></td>                
                <td style="width: 25%; text-align: center;"> <strong>Issue Date: 22/AUG/2021</strong></td>
            </tr>
			</tbody>
			</table> -->
			
			</div>

        <h4>MOBILE & LOCOMOTIVE CRANES


</h4>
        <h4> ASME B30.5-2018</h4>
		
        
		 <!--<button class="btn btn-primary no-print" onclick="preparePrint()">Print View</button>-->

         <div class="table-responsive">
            <table class="table table-bordered">
                
				
				<tr>
                <th style="width: 25%; background-color: #c0d6e8 !important;">REPORT NO</th>
                <td style="width: 25%;"> <?php echo htmlspecialchars($row['report_no']); ?></strong></td>
                <th style="width: 25%; background-color: #c0d6e8 !important;">INSPECTION DATE</th>
                <td style="width: 25%;"> <?php echo htmlspecialchars($row['inspection_date']); ?></strong></td>
            </tr>
            <tr>
                <th style="background-color: #c0d6e8 !important;">CLIENT’S NAME</th>
                <td><?php echo htmlspecialchars($row['client_name']); ?></td>
                <th style="background-color: #c0d6e8 !important;">INSPECTED BY</th>
                <td><?php echo htmlspecialchars($row['inspected_by']); ?></td>
            </tr>
            <tr>
        <th style="background-color: #c0d6e8 !important;">LOCATION</th>
        <td><?php echo htmlspecialchars($row['location']); ?></td>
        <th style="background-color: #c0d6e8 !important;">STICKER NO.</th>
        <td><?php echo htmlspecialchars($row['sticker_no']); ?></td>
    </tr>
    <tr>
        <th style="background-color: #c0d6e8 !important;">CRANE ASSET NO:</th>
        <td><?php echo htmlspecialchars($row['equipment_no']); ?></td>
        <th style="background-color: #c0d6e8 !important;">CRANE SERIAL NO.:</th>
        <td><?php echo htmlspecialchars($row['crane_serial_no']); ?></td>
    </tr>
    <tr>
        <th style="background-color: #c0d6e8 !important;">EQUIPMENT TYPE</th>
        <td><?php echo htmlspecialchars($row['equipmenttype']); ?></td>
        <th style="background-color: #c0d6e8 !important;">CAPACITY (SWL)</th>
        <td><?php echo htmlspecialchars($row['capacity_swl']); ?></td>
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
                    <th style="text-align: center;">REMARKS/ RECOMMENDATIONS</th>
                </tr>
				
				<tr>
                    <th style="text-align: center;">1</th>
                    <th style="text-align: center;">GENERAL REQUIREMENTS</th>
					<th style="text-align: center;"> </th>
                    
                    <th style="text-align: center;">PASS</th>
                    <th style="text-align: center;">FAIL</th>
                    <th style="text-align: center;">N/A</th>
                    <th> </th>
                </tr>
				</thead>
 
                <tbody>

 <tr>
                <td><strong>1.1</strong></td>
                <td><strong>  Equipment Documentation is available</strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.2.1.5) </strong></td>
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
                <td><strong>  Previous Inspection reports are checked  </strong></td>
				<td style="text-align: center;"><strong>ASME B30.5 (5.2.1.5)  </strong></td>
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
                <td><strong>  Durable load chart is provided in an accessible location  </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.1.1.1, 5.1.1.3(b) </strong></td>
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
                <td><strong> Load rating chart is available </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.1.1.3(a), Fig.11)  </strong></td>
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
                <td><strong>  Operator is qualified (for the inspected crane type/model) and licensed </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.1.1.1, 5.1.1.2, 5.1.3) </strong></td>
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
                <td><strong>  A sign is posted warning the operator not to rely solely on any automatic device as a substitute for safe operating practices  </strong></td>
				<td style="text-align: center;"><strong>CIMS QHSE – 06  </strong></td>
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
                <td><strong>  Rated capacity of crane is marked   </strong></td>
				<td style="text-align: center;"><strong> CIMS QHSE – 06 </strong></td>
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
                <td><strong>  Fire extinguisher is in place with minimum rating 10 BC</strong></td>
				<td style="text-align: center;"><strong>ASME B30.5 (5.3.4.10) </strong></td>
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
                <td><strong>1.9</strong></td>
                <td><strong> Markings and direction of all control levers are clearly displayed </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.1.6.1, Fig.12, Fig.13) </strong></td>
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
                <td><strong>1.10</strong></td>
                <td><strong> Crane’s backward stability is in accordance with manufacturers standards (minimum radius and no-load condition) </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 sec.1.2 </strong></td>
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
			
            
			</tbody>
			
			<thead class="thead-dark">
				<tr>
                    <th style="text-align: center;">2</th>
                    <th style="text-align: center;">GENERAL INSPECTION POINTS</th>
					<th style="text-align: center;"> </th>                    
                    <th style="text-align: center;"> </th>
                    <th style="text-align: center;"> </th>
                    <th style="text-align: center;"> </th>
                    <th> </th>
                </tr>
			</thead>
			<tbody>
			
			<tr>
                <td><strong>2.1</strong></td>
                <td><strong>Load – holding check valve of boom is operational </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.1.3.1d)  </strong></td>
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
                <td><strong>2.2</strong></td>
                <td><strong> Two (2) full wraps of rope remain on the drum when hook is in the extreme low position  </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.1.3.2) </strong></td>
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
                <td><strong>2.3</strong></td>
                <td><strong> Swing control is smoothly operating on acceleration and deceleration  </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.1.4.1) </strong></td>
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
                <td><strong>2.4</strong></td>
                <td><strong>  Swing braking operates, as well as positive locking devices (manual and auto) </strong></td>
				<td style="text-align: center;"><strong>ASME B30.5 (5.1.4.2)  </strong></td>
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
                <td><strong>2.5</strong></td>
                <td><strong>  Travel controls are properly operating / functioning </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.1.5.1b) </strong></td>
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
                <td><strong>2.6</strong></td>
                <td><strong> Auxiliary travel controls function correctly for multiple control – station cranes  </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.1.5.1b) </strong></td>
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
                <td><strong>2.7</strong></td>
                <td><strong>Travel brakes and locks for crawler cranes are correctly operating   </strong></td>
				<td style="text-align: center;"><strong>ASME B30.5 (5.1.5.3a)  </strong></td>
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
                <td><strong>2.8</strong></td>
                <td><strong>  Travel brakes and locks for wheel – mounted cranes are correctly operating </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.1.5.3b) </strong></td>
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
                <td><strong>2.9</strong></td>
                <td><strong> Power plant controls of superstructure – mounted power plant working properly  </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.1.6.3a-d) </strong></td>
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
                <td><strong>2.10</strong></td>
                <td><strong>  Rotation – resistant and fiber – core ropes are not used for boom hoist reeving </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.1.7.2c) </strong></td>
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
                <td><strong>2.11</strong></td>
                <td><strong>  Sheave grooves have surface smoothness without any defects </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.1.7.4a) </strong></td>
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
                <td><strong>2.12</strong></td>
                <td><strong> Sheave guards are in place on the lower block  </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.1.7.4c) </strong></td>
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
                <td><strong>2.13</strong></td>
                <td><strong> All sheaves can be lubricated (except permanently lubricated types)  </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.1.7.4d) </strong></td>
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
                <td><strong>2.14</strong></td>
                <td><strong>All cab doors glazing’s are safety glazing material   </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.1.8.1b) </strong></td>
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
                <td><strong>2.15</strong></td>
                <td><strong>  Verify cab doors functionality and condition </strong></td>
				<td style="text-align: center;"><strong>ASME B30.5 (5.1.8.1c)  </strong></td>
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
                <td><strong>2.16</strong></td>
                <td><strong> Seat belt is fitted and in good condition  </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.1.8.1e) </strong></td>
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
                <td><strong>2.17</strong></td>
                <td><strong> Walking platforms and areas of structure are skid - resistance  </strong></td>
				<td style="text-align: center;"><strong>ASME B30.5 (5.1.8.3c)  </strong></td>
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
                <td><strong>2.18</strong></td>
                <td><strong> Guard rails are fitted for crawler cranes  </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.1.8.31) </strong></td>
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
                <td><strong>2.19</strong></td>
                <td><strong>Fixed or telescoping bumper for boom stop is functional (if fitted)   </strong></td>
				<td style="text-align: center;"><strong>ASME B30.5 (5.1.9.1a1)</strong></td>
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
                <td><strong>2.20</strong></td>
                <td><strong>  Shock absorbing bumper for boom stop is functional (if fitted) </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.1.9.1a2) </strong></td>
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
                <td><strong>2.21</strong></td>
                <td><strong>Hydraulic boom elevating cylinder, used as boom stop is functional (if fitted)   </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.1.9.1a3) </strong></td>
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
                <td><strong>2.22</strong></td>
                <td><strong>Boom angle indicator is fitted, operable, and can be read from the operator station   </strong></td>
				<td style="text-align: center;"><strong>ASME B30.5 (5.1.9.1c)  </strong></td>
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
                <td><strong>2.23</strong></td>
                <td><strong> Boom shut – off or hydraulic relief is provided for high angle boom condition  </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.1.9.1d) </strong></td>
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
                <td><strong>2.24</strong></td>
                <td><strong>  Boom length indicator is fitted for telescoping booms and is working </strong></td>
				<td style="text-align: center;"><strong>  ASME B30.5 (5.1.9.1e)</strong></td>
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
                <td><strong>2.25</strong></td>
                <td><strong>Anti - two blocking devices are fitted and working (main and auxiliary)   </strong></td>
				<td style="text-align: center;"><strong>ASME B30.5 (5.1.9.9.1)  </strong></td>
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
                <td><strong>2.26</strong></td>
                <td><strong> Operation and accuracy of the fitted LMI (compare with load chart over a range of angles and radius)  </strong></td>
				<td style="text-align: center;"><strong>ASME B30.5 (5.1.9.9.2)  </strong></td>
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
                <td><strong>2.27</strong></td>
                <td><strong>Outrigger extensions are fully extendable   </strong></td>
				<td style="text-align: center;"><strong>  ASME B30.5 (5.2.1.2)</strong></td>
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
                <td><strong>2.28</strong></td>
                <td><strong> Outriggers and jacks are not leaking oil  </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.2.1.3) </strong></td>
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
                <td><strong>2.29</strong></td>
                <td><strong>Jack is in good operating condition at each outrigger   </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.2.1.3) </strong></td>
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
                <td><strong>2.30</strong></td>
                <td><strong>  Each outrigger jack locking device is functional
(if fitted)
 </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.1.9.3) </strong></td>
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
                <td><strong>2.31</strong></td>
                <td><strong> No leakage from each jack seal when the crane is jacked up  </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.2.1.3) </strong></td>
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
                <td><strong>2.32</strong></td>
                <td><strong> Outrigger jack bolts are fully secure  </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.2.1.3) </strong></td>
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
                <td><strong>2.33</strong></td>
                <td><strong> All road wheel nuts are fully secure  </strong></td>
				<td style="text-align: center;"><strong>ASME B30.5 (5.2.1.3)  </strong></td>
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
                <td><strong>2.34</strong></td>
                <td><strong>  All tires are serviceable and without damage or exist wear </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.2.1.3) </strong></td>
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
                <td><strong>2.35</strong></td>
                <td><strong> Full tank supports and straps are secure  </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.2.1.3) </strong></td>
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
                <td><strong>2.36</strong></td>
                <td><strong>Hydraulic oil tank supports and straps are secure   </strong></td>
				<td style="text-align: center;"><strong>ASME B30.5 (5.2.1.3)  </strong></td>
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
                <td><strong>2.37</strong></td>
                <td><strong> Mud wing bolts are secure and none missing  </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.2.1.3) </strong></td>
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
                <td><strong>2.38</strong></td>
                <td><strong> Underside of chassis shows no obvious faults  </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.2.1.3) </strong></td>
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
                <td><strong>2.39</strong></td>
                <td><strong> Outrigger boxes are not cracked  </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.2.1.3) </strong></td>
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
                <td><strong>2.40</strong></td>
                <td><strong>  Front and rear steering cylinder anchor points and pins are secure </strong></td>
				<td style="text-align: center;"><strong>ASME B30.5 (5.2.1.3)  </strong></td>
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
                <td><strong>2.41</strong></td>
                <td><strong> Truck rod end joints are not worn and are secure  </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.2.1.3) </strong></td>
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
                <td><strong>2.42</strong></td>
                <td><strong>  Axle bolts and nuts are fully secure </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.2.1.3) </strong></td>
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
                <td><strong>2.43</strong></td>
                <td><strong> Wheel hub oil seals are not leaking  </strong></td>
				<td style="text-align: center;"><strong>ASME B30.5 (5.2.1.3)  </strong></td>
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
                <td><strong>2.44</strong></td>
                <td><strong>Brake linings are not oil contaminated   </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.2.1.3) </strong></td>
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
                <td><strong>2.45</strong></td>
                <td><strong> Brake hoses have not deteriorated, e.g. cracks, splits, leaks, etc.  </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.2.1.3) </strong></td>
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
                <td><strong>2.46</strong></td>
                <td><strong>  All chassis cross members are not cracked or corroded </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.2.1.3) </strong></td>
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
                <td><strong>2.47</strong></td>
                <td><strong> Transmission mounting bolts and bushings are secure  </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.2.1.3) </strong></td>
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
                <td><strong>2.48</strong></td>
                <td><strong>  All drive shaft bolts and universal joints are not worn </strong></td>
				<td style="text-align: center;"><strong>ASME B30.5 (5.2.1.3)  </strong></td>
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
                <td><strong>2.49</strong></td>
                <td><strong> Ring gear bolts are fully secure  </strong></td>
				<td style="text-align: center;"><strong>  ASME B30.5 (5.2.1.3)</strong></td>
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
                <td><strong>2.50</strong></td>
                <td><strong> Rear axle lock - out cylinder lock - up the axle correctly  </strong></td>
				<td style="text-align: center;"><strong>ASME B30.5 (5.2.1.3)  </strong></td>
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
                <td><strong>2.51</strong></td>
                <td><strong>  All hydraulic hoses under chassis have not deteriorated (cracks, splits, leaks) </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.2.1.3) </strong></td>
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
                <td><strong>2.52</strong></td>
                <td><strong>  Engine mountings have not gone soft with oil contamination </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.2.1.3) </strong></td>
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
                <td><strong>2.53</strong></td>
                <td><strong> Engine shows no major oil leakage  </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.2.1.3) </strong></td>
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
                <td><strong>2.54</strong></td>
                <td><strong>  Radiator does not leak water </strong></td>
				<td style="text-align: center;"><strong>ASME B30.5 (5.2.1.3)  </strong></td>
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
                <td><strong>2.55</strong></td>
                <td><strong> •	Rope of free of damages<br/>
                    •	1 broken wire protruding from the core<br/>
                    •	Wear 1/3 of the original diameter of outside individual wires<br/>
                    •	Kinking, Crushing, Bird Caging, or other distortion
                    (For running ropes:<br/>
                    •	Max of 6 randomly broken wires in 1 lay<br/>
                    •	3 broken wires in 1 strand of 1 lay)
                      </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.2.4.3) </strong></td>
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
                <td><strong>2.56</strong></td>
                <td><strong> (For rotation resistance ropes:<br/>
•	2 randomly distributed broken wires in six rope diameters<br/>
•	4 randomly distributed broken wires in 30 rope diameters<br/>
•	Evidence of any heat damage from any case<br/>
•	Reductions from nominal diameter)


                      </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.2.4.3) </strong></td>
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
                <td><strong>2.57</strong></td>
                <td><strong> Auxiliary hoist rope (if fitted) is free of damage and
                    Wear (Replace if the broken wire and/or wear criteria for the given rope type is exceeded for running ropes, as in 2.55
                      </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.2.4.3) </strong></td>
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
                <td><strong>2.58</strong></td>
                <td><strong>  Main hoist drum mounting bolts are secure </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.2.4.3) </strong></td>
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
                <td><strong>2.59</strong></td>
                <td><strong> Auxiliary hoist drum mounting bolts are secure  </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.2.1.3) </strong></td>
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
                <td><strong>2.60</strong></td>
                <td><strong> Drum rotation indicator(s) are provided.  </strong></td>
				<td style="text-align: center;"><strong>ASME B30.5 (5.1.3.2(a)(5)  </strong></td>
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
                <td><strong>2.61</strong></td>
                <td><strong> Rear telescope side and top wear pads are serviceable  </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.2.1.3) </strong></td>
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
                <td><strong>2.62</strong></td>
                <td><strong>  Bottom and side wear pads of telescope sections are serviceable </strong></td>
				<td style="text-align: center;"><strong>  ASME B30.5 (5.2.1.3)</strong></td>
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
                <td><strong>2.63</strong></td>
                <td><strong> Boom heel pin bushes are not worn (rock boom up and down to check this)  </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.2.1.3) </strong></td>
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
                <td><strong>2.64</strong></td>
                <td><strong>  Boom – over rear alarm operates </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.2.1.6) </strong></td>
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
                <td><strong>2.65</strong></td>
                <td><strong>  Double plate at top of lower boom is not distorted with side weld splitting for AT and RT Cranes (Plate requires renewal if this is the case) </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.2.1.3) </strong></td>
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
                <td><strong>2.66</strong></td>
                <td><strong> Center swivel coupling pipes and hoses do not leak  </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.2.1.3) </strong></td>
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
                <td><strong>2.67</strong></td>
                <td><strong> Center swivel coupling bolts are all secure  </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.2.1.3) </strong></td>
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
                <td><strong>2.68</strong></td>
                <td><strong>Boom lift cylinder anchor bolts and bushings are secure   </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.2.1.3) </strong></td>
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
                <td><strong>2.69</strong></td>
                <td><strong> Swing gearbox mounting bolts are secure  </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.2.1.3) </strong></td>
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
                <td><strong>2.70</strong></td>
                <td><strong>  Swing gearbox bottom oil seal and drive gear are in a serviceable condition (Should be well lubricated) </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.2.1.3) </strong></td>
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
                <td><strong>2.71</strong></td>
                <td><strong>  Horn is working  </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.1.9.11) </strong></td>
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
                <td><strong>2.72</strong></td>
                <td><strong> Crane level indicator is fitted and working  </strong></td>
				<td style="text-align: center;"><strong>ASME B30.5 (5.1.9.11)  </strong></td>
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
                <td><strong>2.73</strong></td>
                <td><strong> Engine gauges and associated indicators are working  </strong></td>
				<td style="text-align: center;"><strong>  ASME B30.5 (5.2.1.3)</strong></td>
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
                <td><strong>2.74</strong></td>
                <td><strong>  Starter safety switch for automatic transmission is functional </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.1.6.3) </strong></td>
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
                <td><strong>2.75</strong></td>
                <td><strong>The warning sign stating power line required clearances is visible to the operator  </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.1.9.12g) </strong></td>
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
                <td><strong>2.76</strong></td>
                <td><strong>  Swing away fly jib lock pins are secure (with fly jib stowed) </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.2.1.3) </strong></td>
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
                <td><strong>2.77</strong></td>
                <td><strong>  The power pin fly jib is operable and undamaged </strong></td>
				<td style="text-align: center;"><strong> QHSE - 06 </strong></td>
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
                <td><strong>2.78</strong></td>
                <td><strong>  Cat head sheaves (jib) turn freely and are undamaged </strong></td>
				<td style="text-align: center;"><strong>ASME B30.5 (5.1.7.4)  </strong></td>
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
                <td><strong>2.79</strong></td>
                <td><strong>  Load block sheaves turn freely and are undamaged </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.1.7.4) </strong></td>
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
                <td><strong>2.80</strong></td>
                <td><strong> Hook assemblies are equipped with safety latches  </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.1.7.6) </strong></td>
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
                <td><strong>2.81</strong></td>
                <td><strong> Hook assembly labeling and manufacturer data is clearly marked  </strong></td>
				<td style="text-align: center;"><strong>ASME B30.10 (10.1.1.1)  </strong></td>
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
                <td><strong>2.82</strong></td>
                <td><strong> Hook’s weight is clearly marked/printed on the hook  </strong></td>
				<td style="text-align: center;"><strong>ASME B30.10 (10.1.1.1)  </strong></td>
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
                <td><strong>2.83</strong></td>
                <td><strong>  Safe working load of hook is clearly marked on the item </strong></td>
				<td style="text-align: center;"><strong> ASME B30.10 (10.1.1.1) </strong></td>
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
                <td><strong>2.84</strong></td>
                <td><strong> Hook does not show defects such as nicks, cracks and gouges  </strong></td>
				<td style="text-align: center;"><strong> ASME B30.10 (10.1.2.2c3) </strong></td>
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
                <td><strong>2.85</strong></td>
                <td><strong>  Hook is not bent or twisted<br/>
                    •	Max. bending or twisting not to exceed 10 degrees from plane of unbent hook or as per manufacturer recommendations
                     </strong></td>
				<td style="text-align: center;"><strong> ASME B30.10 (10.1.2.1.3c1) </strong></td>
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
                <td><strong>2.86</strong></td>
                <td><strong> Hook is not distorted in the throat opening<br/>
                    •	Max. allowable throat opening is 15% compared to new hook, or as per manufacturer recommendations
                      </strong></td>
				<td style="text-align: center;"><strong> ASME B30.10 (10.1.2.1.3c2) </strong></td>
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
                <td><strong>2.87</strong></td>
                <td><strong> Maximum wear in the hook bowl is not exceeding 10% (compared to new hook) or as per manufacturer recommendations  </strong></td>
				<td style="text-align: center;"><strong> ASME B30.10 (10.1.2.1.3c3) </strong></td>
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
                <td><strong>2.88</strong></td>
                <td><strong>   Hook is not cracked, gouged or shows nicks</strong></td>
				<td style="text-align: center;"><strong>  ASME B30.10 (10.1.2.1.2c3)</strong></td>
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
                <td><strong>2.89</strong></td>
                <td><strong> Hook can lock (if it is a self-locking hook)  </strong></td>
				<td style="text-align: center;"><strong> ASME B30.10 (10.1.2.1.3c4) </strong></td>
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
                <td><strong>2.90</strong></td>
                <td><strong>  Hook latch is operative </strong></td>
				<td style="text-align: center;"><strong> ASME B30.10 (10.1.2.1.3c5) </strong></td>
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
                <td><strong>2.91</strong></td>
                <td><strong> Rope guards and locked in position with pins (hook block)  </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.1.7.4) </strong></td>
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
                <td><strong>2.92</strong></td>
                <td><strong>  Dead end rope anchor is fitted with a lock pin (hook block) </strong></td>
				<td style="text-align: center;"><strong>ASME B30.5 (5.1.7.3)  </strong></td>
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
                <td><strong>2.93</strong></td>
                <td><strong> Dead end rope anchor is correctly made with a suitable wedge socket and loop- back termination  </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.1.7.3) </strong></td>
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
                <td><strong>2.94</strong></td>
                <td><strong>  Reverse travel alarm is operational </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.2.1.3) </strong></td>
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
                <td><strong>2.95</strong></td>
                <td><strong>  Turning signals and indicators are working correctly </strong></td>
				<td style="text-align: center;"><strong>ASME B30.5 (5.2.1.3)  </strong></td>
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
                <td><strong>2.96</strong></td>
                <td><strong> Road lighting, including spotlights, are in good condition and operable  </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.2.1.3) </strong></td>
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
                <td><strong>2.97</strong></td>
                <td><strong> Brakes operate on all wheels  </strong></td>
				<td style="text-align: center;"><strong>ASME B30.5 (5.1.5.3)  </strong></td>
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
                <td><strong>2.98</strong></td>
                <td><strong>   Track pads are undamaged</strong></td>
				<td style="text-align: center;"><strong>ASME B30.5 (5.2.1.3)  </strong></td>
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
                <td><strong>2.99</strong></td>
                <td><strong>  Drive chains are correctly tensioned </strong></td>
				<td style="text-align: center;"><strong>ASME B30.5 (5.2.1.3)  </strong></td>
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
                <td><strong>2.99</strong></td>
                <td><strong>  Drive sockets and tumblers are serviceable </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.2.1.3) </strong></td>
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
                <td><strong>3.00</strong></td>
                <td><strong>  Top and bottom swing rollers and tracks are in good overall condition (Should be well lubricated) </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.2.1.3) </strong></td>
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
                <td><strong>3.1</strong></td>
                <td><strong> Track idlers are serviceable  </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.2.1.3) </strong></td>
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
                <td><strong>3.2</strong></td>
                <td><strong>  Boom back  stops are functioning </strong></td>
				<td style="text-align: center;"><strong>ASME B30.5 (5.1.9.1)  </strong></td>
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
                <td><strong>3.3</strong></td>
                <td><strong>  Back A-Frame is in good condition </strong></td>
				<td style="text-align: center;"><strong>  ASME B30.5 (5.2.1.3)</strong></td>
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
                <td><strong>3.4</strong></td>
                <td><strong> Boom cut-out (at maximum angle) is operable  </strong></td>
				<td style="text-align: center;"><strong>ASME B30.5 (5.1.9.1  </strong></td>
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
                <td><strong>3.5</strong></td>
                <td><strong> Power low lowering is operational  </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.2.1.3) </strong></td>
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
                <td><strong>3.6</strong></td>
                <td><strong> Boom hoist safety pawl is engaging  </strong></td>
				<td style="text-align: center;"><strong>ASME B30.5 (5.1.3.2)  </strong></td>
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
                <td><strong>3.7</strong></td>
                <td><strong> Boom chords and lacings are undamaged (bent)  </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.2.1.3) </strong></td>
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
                <td><strong>3.8</strong></td>
                <td><strong> Bridal sheaves are turning and free from surface defects  </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.1.7.4) </strong></td>
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
                <td><strong>3.9</strong></td>
                <td><strong> Pendant ropes are showing no broken wires (see 2.0.55)  </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.2.4.2) </strong></td>
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
                <td><strong>3.10</strong></td>
                <td><strong>  Operation of steering brakes is satisfactory </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.1.5.3) </strong></td>
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
                <td><strong>3.11</strong></td>
                <td><strong> Main machinery guarding is in place  </strong></td>
				<td style="text-align: center;"><strong>ASME B30.5 (5.1.9.6)  </strong></td>
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
                <td><strong>3.12</strong></td>
                <td><strong> Main clutch is fully operational  </strong></td>
				<td style="text-align: center;"><strong>ASME B30.5 (5.2.1.3)  </strong></td>
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
                <td><strong>3.13</strong></td>
                <td><strong> Main boom and hoist drum brakes are fully functional  </strong></td>
				<td style="text-align: center;"><strong>  ASME B30.5 (5.2.1.3)</strong></td>
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
                <td><strong>3.14</strong></td>
                <td><strong>  LMI is fitted, operational and accurate for bool length, rated load, angle, radius, etc. (for 3 ton cranes or more) </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (1.9.10.2) </strong></td>
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
                <td><strong>3.15</strong></td>
                <td><strong> Wind Speed Device (Anemometer) working correctly.  </strong></td>
				<td style="text-align: center;"><strong> ASME B30.5 (5.3.2.1.6) </strong></td>
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
			</tbody>
        </table>
</div>
        

<div class="keep-together">

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
</div>
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