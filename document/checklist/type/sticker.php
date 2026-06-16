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
    <title>INSPECTION CHECKLIST FOR STICKER </title>
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
            <strong>INSPECTION CHECKLIST FOR STICKER</strong>
        </td>
    </tr>
    <tr>
        <td>FRM.0601-1.10</td>
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
                <td colspan="3" style="text-align: center;"><strong>INSPECTION CHECKLIST FOR PERSONNEL LIFTING SYSTEMS  </strong></td>
				</tr>
            <tr>
                <td style="width: 25%; text-align: center;"><strong>FRM.0601-1.10</strong></td>
                <td style="width: 25%; text-align: center;"> <strong>Revision 02</strong></td>
                
                <td style="width: 25%; text-align: center;"> <strong>Issue Date: 30/SEP/2020</strong></td>
            </tr>
			</tbody>
			</table> -->
			
			</div>

        <h4>STICKER CHECKLIST</h4>
        <h4>REFERENCE: ASME B30 STANDARDS     </h4>
		
        
		 <!--<button class="btn btn-primary no-print" onclick="preparePrint()">Print View</button>-->

<form method="post" action="./update_checklist.php" id="checklistForm">
    <input type="hidden" name="checklist_no" value="<?php echo $row['checklist_id'] ?>" />
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
                <th>EQUIPMENT NO</th>
                <td>
                    <input type="text" name="equipment_no" value="<?php echo htmlspecialchars($row['equipment_no'] ?? ''); ?>" class="form-control" style="font-weight: bold; border: none; background: transparent;">
                </td>
                <th>EQUIP. SERIAL NO.</th>
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
        

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="thead-dark">
                <tr>
                    <th style="text-align: center;">Item No.	</th>
                    <th style="text-align: center;">ACCEPTANCE CRITERIA</th>                                        
                    <th style="text-align: center;">PASS</th>
                    <th style="text-align: center;">FAIL</th>
                    <th style="text-align: center;">N/A</th>                    
                    <th style="text-align: center;">COMMENTS</th>
                </tr>				
				<tr>
                    <th style="text-align: center;">1.0	</th>
                    <th style="text-align: center;">GENERAL REQUIREMENTS </th>					
                    <th style="text-align: center;"> </th>					
                    <th style="text-align: center;"> </th>					
                    <th style="text-align: center;"> </th>					
                    <th style="text-align: center;"> </th>					
                    
                </tr>
				</thead> 
                <tbody>
                    <tr>
<td><strong> 1.01</strong></td>
<td><strong>	For STICKER Checklist</strong></td>
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
    <input type="text" name="checklist_remark[1]" value="<?php echo getRemark(1, $saved_remarks); ?>">
</td>
</tr>
			
			
        
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
                <strong>
                <?php echo htmlspecialchars($row['inspected_by']); ?>
                </strong>
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