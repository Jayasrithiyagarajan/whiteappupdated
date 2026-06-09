<?php
include_once('../../file/config.php');

// Ensure both project_no and report_no are provided
if (isset($_GET['project_no']) && isset($_GET['report_no'])) {
    $project_no = $_GET['project_no'];
    $report_no = $_GET['report_no'];

    // Query to fetch the specific report
    $query = "SELECT * FROM reports WHERE project_no = ? AND report_no = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $project_no, $report_no);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        //$deficiencies = json_decode($row['deficiencies'], true); // Decode JSON to PHP array
    } else {
        echo "No matching report found!";
        exit;
    }

    $stmt->close();
} else {
    echo "Project ID and Report Number are required!";
    exit;
}




// Fetch client details
$query_client = "SELECT client_name, client_phone FROM checklist_results WHERE project_no = ?";
$stmt_client = $conn->prepare($query_client);

if ($stmt_client) {
    $stmt_client->bind_param("s", $project_no);
    $stmt_client->execute();
    $result_client = $stmt_client->get_result();

    if ($result_client && $result_client->num_rows > 0) {
        $client_row = $result_client->fetch_assoc();
        $client_name = $client_row['client_name'];
        $client_phone = $client_row['client_phone'];
    } else {
        $client_name = "No client found for this project ID";
    }
} else {
    die("Failed to prepare the query: " . $conn->error);
}


// Fetch inspector's LEEA number
$inspector_name = $row['issued_by'];
$leea_number = ""; // Initialize variable

$query_leea = "SELECT leea_number FROM inspectors WHERE inspector_name = ?";
$stmt_leea = $conn->prepare($query_leea);

if ($stmt_leea) {
    $stmt_leea->bind_param("s", $inspector_name);
    $stmt_leea->execute();
    $result_leea = $stmt_leea->get_result();

    if ($result_leea && $result_leea->num_rows > 0) {
        $leea_row = $result_leea->fetch_assoc();
        $leea_number = $leea_row['leea_number'];
    } else {
        $leea_number = "N/A"; // Default if not found
    }
    
    $stmt_leea->close();
} else {
    $leea_number = "Error"; // In case of query error
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inspection Report</title>
    <style>
        .container {
    width: 90%;
    max-width: 1200px;
    margin: 20px auto;
    padding: 20px;
    background: #fff;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
    border-radius: 8px;
}

         body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }
        .inspection-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .inspection-table th, .inspection-table td {
            border: 1px solid black;
            padding: 4px;
            text-align: center;
            font-size: 11px; /* Consistent font size */

        }
        .inspection-table th {
            background-color: #277bbee0;
            color: white;
        }
        .footer {
            margin-top: 10px;
            text-align: center;
            font-size: 9px;
        }
        .manufac{
            background-color: #277bbee0 !important; /* Print background colors */
            color: #fff !important;
            font-weight: bold;
        }

        .signature-cell {        
        justify-content: flex-start; /* Align items to the left */
        height: 50px;
    }

    .signature-cell img {
        margin-right: 10px; /* Add some space between the image and the text */
    }

    .signature-cell span {
        flex-grow: 1; /* Ensure the name stays aligned correctly */
    }

    .checkbox-container {
        display: flex;
        align-items: center; /* Vertically center the items */
        gap: 10px; /* Add space between the checkbox and the text */
    }

    .checkbox-container input[type="checkbox"] {
        margin: 0; /* Remove default margin from checkbox */
        padding: 0; /* Remove default padding from checkbox */
    }

    .checkbox-container span {
        direction: rtl; /* Ensure proper alignment for Arabic text if needed */
    }
        @media print {
            body {
                margin: 0;
                padding: 0;
                transform: scale(0.95); /* Slight scaling for single page */
                transform-origin: top left;
                color-adjust: exact; /* Ensures color fidelity */
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .footer {
        color: black; /* Ensure footer text remains visible */
    }
            .manufac{
            background-color: #277bbee0 !important; /* Print background colors */
            color: #fff !important;
            font-weight: bold;
        }

            #non-printable {
        display: none; /* Hide buttons in print view */
    }
        }
    </style>
</head>
<body>
<div class="container">
<div class="header">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 20%; text-align: left;">
                    <img src="../checklist/logo.png" height="100px" alt="TUV Rheinland Logo">
                </td>
                <td style="width: 60%; text-align: center;">
                    <h1 style="font-size: 18px; margin: 0;">CRANE INSPECTION & MAINTENANCE SERVICES (CIMS)</h1>
                    <h3 style="font-size: 15px; margin-top: 3px;">A DIVISION OF AL KHOBAR GATE INTERNATIONAL TRADING EST</h3>
                    <p style="font-size: 12px; margin: 5px 0;">
                        <b>P.O.BOX 74007, AL- Khobar 31952, Saudi Arabia</b><br>
                        <b>TEL.: 013 814 6861 - 013 814 6862 Ext.110 - Fax: 013 814 6863</b><br>
                        <b>Email: office@cims.com.sa - info@cims.com.sa</b>
                    </p>
                    <h3 style="font-size: 18px; margin-top: 15px;">Heavy Equipment & Elevating / Lifting Equipment Inspection Report</h3>
                </td>
                <td style="width: 20%; text-align: right;">
    <p style="font-size: 12px; margin: 0; text-align: right;">
        <b>Report No:</b> <?php echo htmlspecialchars($row['report_no']); ?><br>
        <b>JRN No:</b> 
    </p>
    <img src="../../document/code.png" height="100px" alt="QR Code">
</td>

            </tr>
        </table>
    </div>


    <table class="inspection-table">
    
    <tbody>
    <tr>
            <td class="manufac"> Client Company / Name & Address:</td>
            <td class="manufac">Manufacturer:</td>
            <td class="manufac">Equipment Identification Number:</td>
            <td class="manufac">Date of Inspection:</td>
            <td class="manufac">Model:</td>
        </tr>
        <tr>
            <td><b> <?php echo htmlspecialchars($row['client_company_name']); ?></b> </td>
            <td><b><?php echo htmlspecialchars($row['manufacturer']); ?></b></td>
            <td><b><?php echo htmlspecialchars($row['equipment_id_no']); ?></b></td>
            <td><b><?php echo htmlspecialchars(date('d-m-Y', strtotime($row['date_of_inspection']))); ?></b></td>

            <td><b><?php echo htmlspecialchars($row['model']); ?></b></td>
        </tr>

        <tr>
        <td class="manufac">Equipment Serial Number:</td>
        <td class="manufac">Next Inspection Due Date:</td>
        <td class="manufac">Type:</td>
        <td class="manufac">Location:</td>
        <td class="manufac">Inspection Status:</td>
        </tr>
        <tr>
            <td><b><?php echo htmlspecialchars($row['equipment_serial_no']); ?></b></td>
            <td><b><?php echo htmlspecialchars(date('d-m-Y', strtotime($row['next_inspection_due_date']))); ?></b></td>
            <td><b><?php echo htmlspecialchars($row['type']); ?></b></td>
            <td><b><?php echo htmlspecialchars($row['location']); ?></b></td>
            <td>
    
        <div style="display: flex; flex-direction: column; margin-left: 20px;">
            <div>
                <label for="pass"><b>Passed</b></label>
                <input type="checkbox" id="pass" name="ins_result_pass" value="pass" 
                    <?php echo (isset($row['inspection_status']) && $row['inspection_status'] == 'Passed') ? 'checked' : ''; ?> disabled>
            </div>
            <div>
                <label for="fail"><b>Failed</b></label>
                <input type="checkbox" id="fail" name="ins_result_fail" value="fail" 
                    <?php echo (isset($row['inspection_status']) && $row['inspection_status'] == 'Failed') ? 'checked' : ''; ?> disabled>
            </div>
        </div>
    
</td>
        </tr>

        <tr>
        <td class="manufac">Previous Sticker S.No.:</td>
        <td class="manufac">Issued by:</td>
        <td class="manufac">Capacity:</td>
        <td colspan="2" class="manufac">Sticker Number Issued:</td>
        </tr>
        <tr>
            <td><b><?php echo htmlspecialchars($row['prev_sticker_no']); ?></b></td>
            <td><b><?php echo htmlspecialchars($row['issued_company']); ?></b></td>
            <td><b><?php echo htmlspecialchars($row['capacity']); ?></b></td>
            <td colspan="2"><b><?php echo htmlspecialchars($row['sticker_number_issued']); ?></b></td>
        </tr>

        
        <!-- <tr>
            <td colspan="4">Juyamah NSL-MSU<br>P.O.BOX 74007, AL- Khobar 31952, Saudi Arabia</td>
        </tr> -->
    </tbody>
</table>
    <p>
    <b>Above Equipment was visually inspected in accordance with local and international standards. Deficiencies that require corrective actions are listed
below. Specific repairs to correct each deficiency should be noted in the right column.</b>
    </p>

    <!-- <h3>Deficiencies</h3> -->
    <table class="inspection-table" style="width: 100%; border-collapse: collapse;">
    <thead>
        <tr>
            <!-- <th style="padding: 15px; line-height: 1.5;">#</th> -->
            <th style="width: 65%; padding: 15px">DEFICIENCIES</th>
            <th style="width: 30%; padding: 15px">CORRECTIVE ACTION TAKEN</th>
        </tr>
    </thead>
    <tbody>
    
        <tr>
            <!-- <td style="padding: 15px; line-height: 1.5;">1</td> -->
            <td style="padding: 5px; height: 100px; text-align: left !important;">
            
            <strong><?php echo htmlspecialchars($row['deficiency']); ?></strong>
</td>
<td style="padding: 15px; height: 100px; text-align: left !important;">
    <strong><?php echo htmlspecialchars($row['corrective_action']); ?></strong>

            
            </td>
        </tr>
        
        
                    <tr>
                   <!--      <td colspan="3">No deficiencies recorded for this project.</td>
                    </tr>
        
        <!-- <tr>
            <td style="padding: 15px; line-height: 1.5;">2</td>
            <td style="padding: 15px; line-height: 1.5;">Wear on lifting cable</td>
            <td style="padding: 15px; line-height: 1.5;">Replace cable</td>
        </tr> -->
    </tbody>
</table>
<p>When re-inspected, a complete copy of this report should be ready for review by the inspector.</p>
<div class="col-md-12" style="border:2px solid #d0cece;">
                                    <div class="form-group1">
                                        <div class="form-check2">

                                        <!-- <input type="checkbox" name="terms" id="terms" onchange="activateButton(this)" checked style="zoom:1;"> -->
                               
                                        <h6 class="checkbox-container">
    <input type="checkbox" checked>
    <span style="font-size: 14px;">اواوافق الى تحمل المسؤليه الكامله عن هذا الفحص
        <span style="font-size: 14px;"> I agree to take full responsibility for this inspection</span>
    </span>
</h6>
                                            
                                          
                                            
                                        </div>
                                    </div>
                                </div>

    
    
    
    
    <div class="row">
        <div class="col-md-12">
        <table class="inspection-table" style="width: 100%;">
    <thead>
        <tr>
            <th colspan="2">Report Receiver's Name and sign:</th>
            <th>Phone Number:</th>
            <th colspan="2">Inspector Name and Sign: <b> </th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td rowspan="2" class="signature-cell">
                <strong><?php echo htmlspecialchars(ucwords($client_name)); ?></strong>

            </td>
            <td rowspan="2" class="signature-cell">
                <img src="../uploads/<?php echo $project_no; ?>.png" height="60px;">
            </td>                          
            <td rowspan="2">
                <strong><?php echo htmlspecialchars($client_phone); ?></strong>
            </td>
            
            <td class="signature-cell">
            <strong><?php echo htmlspecialchars($row['issued_by']); ?></strong>
                
            </td>
            <td rowspan="2">
                    <img src="<?php echo $url2 . 'inspector/uploads/' . strtolower(str_replace(' ', '_', htmlspecialchars($row['issued_by']))) . '/images/signature_image.jpg'; ?>" height="60px">



            </td>
        </tr>
        <tr>
            <td>
                <strong>LEEA NO: <?php echo htmlspecialchars($leea_number); ?></strong>
            </td>
        </tr>
    </tbody>
</table>
        </div>
    </div>
    <!-- <div class="footer">
        <p>Generated by TUV Rheinland Arabia LLC</p>
    </div>    -->
    
    <!-- <div id="non-printable">
    <button type="button" class="btn btn-primary" id="downloadBtn">Save as PDF</button>
    <button type="button" class="btn btn-danger" onclick="window.print()">Print</button>
</div> -->

<div class="text-center" style="margin-top: 10px;">
<!-- <a href="./download.php?project_no=<?php echo $row['project_no']; ?>&report_no=<?php echo $row['report_no']; ?>"> -->
    <a href="download.php?project_no=<?php echo $row['project_no']; ?>&report_no=<?php echo $row['report_no']; ?>">
        <button>Download</button>
    </a>
</div>

</div>

    <!-- <script>
        document.getElementById('downloadBtn').addEventListener('click', function () {
            window.location.href = 'download.php';
        });
    </script> -->
</body>
</html>