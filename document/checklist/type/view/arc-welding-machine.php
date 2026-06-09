<?php 
include_once('./view-fetch.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VISUAL INSPECTION CHECKLIST FOR ARC WELDING EQUIPMENT  </title>
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
            <strong>INSPECTION CHECKLIST FOR ARC WELDING EQUIPMENT</strong>
        </td>
    </tr>
    <tr>
        <td>FRM.0601-1.16</td>
        <td>Revision 01</td>
        <td>Issue Date: 28/JAN/2021</td>
    </tr>
    <tr>
        <td class="left-align">Prepared By<br>Operations Manager</td>
        <td class="left-align">Reviewed & Approved By<br>Managing Director</td>
         
   <td><img src="../../../code.png" width="80px" height="80px" alt="" /></td>
    </tr>
</table>
			</div>

        <h4>ARC WELDING EQUIPMENT </h4>
        <h4>BS  EN 60974-4:2007 </h4>
		
        
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
            <!-- <tr>
        <th>EQUIPMENT TYPE</th>
        <td><strong> <?php echo htmlspecialchars($row['equipmenttype']); ?></strong></td>
        <th>CAPACITY (SWL)</th>
        <td><strong> <?php echo htmlspecialchars($row['capacity_swl']); ?></strong></td>
    </tr> -->
            
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
                    <th style="text-align: center;">ELECTRODE HOLDER & RETURN CLAMP</th>
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
                <td><strong> Missing or Defective Insulation  </strong></td>
				<td style="text-align: center;"><strong> </strong></td>
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
                <td><strong>Defective Connections </strong></td>
				<td style="text-align: center;"><strong> 
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
                <td><strong>Defective, Damaged Switches </strong></td>
				<td style="text-align: center;"><strong></strong></td>
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
                <td><strong>Other Damage  </strong></td>
				<td style="text-align: center;"><strong>
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
                    <th style="text-align: center;">2</th>
                    <th style="text-align: center;">MAINS SUPPLY</th>
					<th style="text-align: center;"> </th>
                    
                    <th style="text-align: center;">PASS</th>
                    <th style="text-align: center;">FAIL</th>
                    <th style="text-align: center;">NA</th>
                    <th> </th>
                </tr>
				
				
 <tr>
                <td><strong>2.1</strong></td>
                <td><strong>Defective, Damage Cable  </strong></td>
				<td style="text-align: center;"><strong>
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
                <td><strong>2.2</strong></td>
                <td><strong>Deformed, Faulty Plug </strong></td>
				<td style="text-align: center;"><strong>
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
                <td><strong>2.3</strong></td>
                <td><strong>Broken or Thermally Damaged Plug Pins </strong></td>
				<td style="text-align: center;"><strong></strong></td>
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
                <td><strong>2.4</strong></td>
                <td><strong>Ineffective Cable Anchorage </strong></td>
				<td style="text-align: center;"><strong>
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
                <td><strong>2.5</strong></td>
                <td><strong>Cables  &  Couplers  unsuitable  for  the intended use and performance. </strong></td>
				<td style="text-align: center;"><strong>
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
                    <th style="text-align: center;">3</th>
                    <th style="text-align: center;">WEDLING CIRCUIT</th>
					<th style="text-align: center;"> </th>
                    
                    <th style="text-align: center;">PASS</th>
                    <th style="text-align: center;">FAIL</th>
                    <th style="text-align: center;">NA</th>
                    <th> </th>
                </tr>
				
				
 <tr>
                <td><strong>3.1</strong></td>
                <td><strong>Defective, Damage Cable  </strong></td>
				<td style="text-align: center;"><strong>
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
                <td><strong>3.2</strong></td>
                <td><strong>Deformed,Faulty or Thermally Damaged coupler / sockets.  </strong></td>
				<td style="text-align: center;"><strong> 
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
                <td><strong>3.3</strong></td>
                <td><strong>Ineffective Cable Anchorage </strong></td>
				<td style="text-align: center;"><strong></strong></td>
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
                <td><strong>3.4</strong></td>
                <td><strong>Cables  &  Couplers  unsuitable  for  the intended use and performance. </strong></td>
				<td style="text-align: center;"><strong>
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
                    <th style="text-align: center;">4</th>
                    <th style="text-align: center;">ENCLOSURE</th>
					<th style="text-align: center;"> </th>
                    <th style="text-align: center;">PASS</th>
                    <th style="text-align: center;">FAIL</th>
                    <th style="text-align: center;">NA</th>
                    <th> </th>
                </tr>
				

 <tr>
                <td><strong>4.1</strong></td>
                <td><strong> Missing or Damaged   </strong></td>
				<td style="text-align: center;"><strong>
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
                <td><strong>4.2</strong></td>
                <td><strong>Unauthorized Modifications </strong></td>
				<td style="text-align: center;"><strong> 
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
                <td><strong>4.3</strong></td>
                <td><strong>Cooling  Openings  Blocked  or  Missing Air Filters. </strong></td>
				<td style="text-align: center;"><strong></strong></td>
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
                <td><strong>4.4</strong></td>
                <td><strong>Signs of  Overload & Improper Use </strong></td>
				<td style="text-align: center;"><strong>
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
                <td><strong>4.5</strong></td>
                <td><strong>Missing  or  Defective  Wheels,  Lifting Means, Holder, Etc.</strong></td>
				<td style="text-align: center;"><strong></strong></td>
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
                <td><strong>4.6</strong></td>
                <td><strong>Missing or Defective Wheels, Lifting Means,Holder,Etc.</strong></td>
				<td style="text-align: center;"><strong></strong></td>
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
                <td><strong>4.7</strong></td>
                <td><strong>Defective Wire  Reel Mounting Means</strong></td>
				<td style="text-align: center;"><strong>
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
                <td><strong>4.8</strong></td>
                <td><strong>Conductive Objects Placed in the Enclosure.</strong></td>
				<td style="text-align: center;"><strong>
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
                    <th style="text-align: center;">5</th>
                    <th style="text-align: center;">CONTROLS & INDICATORS</th>
					<th style="text-align: center;"> </th>
                    <th style="text-align: center;">PASS</th>
                    <th style="text-align: center;">FAIL</th>
                    <th style="text-align: center;">NA</th>
                    <th> </th>
                </tr>
				

 <tr>
                <td><strong>5.1</strong></td>
                <td><strong> Defective Switches, Meters & Lamps</strong></td>
				<td style="text-align: center;"><strong>  </strong></td>
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
                <td><strong>5.2</strong></td>
                <td><strong>Defective Pressure Regulator or F.M </strong></td>
				<td style="text-align: center;"><strong>  </strong></td>
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
                <td><strong>5.3</strong></td>
                <td><strong>Incorrect Fuses Accessible from Outside the enclosure.</strong></td>
				<td style="text-align: center;"><strong></strong></td>
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
                    <th style="text-align: center;">6</th>
                    <th style="text-align: center;">GENERAL CONDITION</th>
					<th style="text-align: center;"> </th>                    
                    <th style="text-align: center;">PASS</th>
                    <th style="text-align: center;">FAIL</th>
                    <th style="text-align: center;">NA</th>
                    <th> </th>
                </tr>
				

 <tr>
                <td><strong>6.1</strong></td>
                <td><strong> Cooling Liquid Circuit Leaking </strong></td>
				<td style="text-align: center;"><strong>
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
                <td><strong>6.2</strong></td>
                <td><strong>Defective Gas Hoses & Connections </strong></td>
				<td style="text-align: center;"><strong> 
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
                <td><strong>6.3</strong></td>
                <td><strong>Poor Legibility of  Markings & Labelling </strong></td>
				<td style="text-align: center;"><strong></strong></td>
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
                <td><strong>6.4</strong></td>
                <td><strong>Data  Plate & Markings </strong></td>
				<td style="text-align: center;"><strong>
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
