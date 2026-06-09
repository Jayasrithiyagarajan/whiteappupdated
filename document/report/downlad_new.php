<?php
require_once('../../vendor/autoload.php');
include_once('../../file/config.php'); // Database connection file

if (!isset($_GET['project_no'])) {
    die("Project ID not provided!");
}

$project_no = $_GET['project_no'];

// Prepare and execute the main query for report details
$query = "SELECT * FROM reports WHERE project_no = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $project_no);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("No record found for the provided project ID!");
}

$row = $result->fetch_assoc();
$deficiencies = json_decode($row['deficiencies'], true);
$inspection_status = $row['inspection_status'];  // Fetching the inspection status

// Fetch the client name
$query_client = "SELECT client_name FROM checklist_results WHERE project_no = ?";
$stmt_client = $conn->prepare($query_client);
$stmt_client->bind_param("s", $project_no);
$stmt_client->execute();
$result_client = $stmt_client->get_result();

$client_name = $result_client->num_rows > 0 ? $result_client->fetch_assoc()['client_name'] : "No client found for this project ID";

// Start capturing the HTML output
ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Inspection Report</title>
    <style>
        /* Include styles for the PDF */
        body { font-family: Arial, sans-serif; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        h3, h4 { margin: 0; }
        td {
            /* border: 2px solid #eee; */
            border: 1px solid black;
            padding-left: 10px;
            /* padding-bottom: 30px; */
        }

    
        /* .checkbox input[type="checkbox"]:checked~label::after {
            transform: rotate(-45deg) scale(1);
        } */

        .notes {
            background-attachment: local;
            background-image: linear-gradient(to right, white 10px, transparent 10px),
            linear-gradient(to left, white 10px, transparent 10px),
            repeating-linear-gradient(white, white 30px, #ccc 30px, #ccc 31px, white 31px);
            line-height: 31px;
        }


        .card {
            background-color: #242b75;
        }

        .fail {
            color: red;
        }

        .flex-row {
            display: flex;
        }

        .wrapper {
            border-right: 0;
        }


        /* Example: Adjusting Bootstrap columns for print */
@media print {
    .col-md-6, .col-md-5, .col-md-1 {
        float: left;
        width: auto;
    }
}


@media print {
        .row {
            display: flex;
            flex-wrap: nowrap;
        }
        .col-md-1,
        .col-md-6,
        .col-md-5 {
            flex: none;
            max-width: none;
        }
        .col-md-1 {
            width: 10%;
        }
        .col-md-6 {
            width: 60%;
        }
        .col-md-5 {
            width: 30%;
        }
        img {
            max-width: 100%;
            height: auto;
        }
        h3, h4 {
            margin: 0;
            padding: 0;
        }
        p {
            margin: 0;
        }
    }


.table thead th {
    vertical-align: bottom;
    border-bottom: 1px solid black;
}


.table th, .table td {
    padding: 1rem;
    vertical-align: top;
    border-top: 1px solid black; 
}
    </style>
</head>
<body>
<div class="container-fluid " >
        <div class="report">
            <form method="POST">
                <div class="panel_s ">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-1">
                                <img src="../logo.png" alt="CIMS" width="70" height="90">
                            </div>
                            <div class="col-md-6">
                                <h3>Crane Inspection & Maintenance Services</h3>
                                <p>
                                <b>P.O.BOX 74007, AL- Khobar 31952, Saudi Arabia</b><br>
                                    <b>TEL.: 013 814 6861 - 013 814 6862 Ext.109 - Fax: 013 814 6863</b><br>
                                    <b>Email: office@cims.com.sa - info@cims.com.sa</b>  <br>                             </p>
                                    <h3 style="font-size: 18px;margin-top:15px;">Heavy Equipment & Elevating / Lifting Equipment Inspection Report</h3>
                            </div>
                            <div class="col-md-3">
                                <h4 style="font-size: 18px;" class="bold estimate-html-number" >
                                Report No: <?php echo htmlspecialchars($row['report_no']); ?><br>
                               JRN: <?php echo htmlspecialchars($row['jrn']); ?> 
                                   </h4>
                                
                                
                            </div>
                            <div class="col-md-2">
                                <img src="../code.png" alt="CIMS" width="90" height="90">
                            </div>
                        </div>

                        <div class="row">
                            <!-- <div class="col-md-1">
                              
                            </div>
                            <div class="col-md-6">
                                
                                    <h3 style="font-size: 22px;">Heavy Equipment & Elevating / Lifting Equipment Inspection Report</h3>
                            </div>
                            <div class="col-md-5">
                                
                                <h4 style="font-size: 19px;text-align:right">JRN: <?php echo htmlspecialchars($row['jrn']); ?> </h4>
                                
                            </div> -->
                            <!-- <div class="col-md-2">
                                <img src="../code.png" alt="CIMS" width="120" height="120">
                            </div> -->
                        </div>

                     
                        <table style="border:1px solid black; width: 100%; height: 164px;">
                            <tbody>
                                <tr>
                                    <td colspan="2" rowspan="3" style="vertical-align: top;">
                                        <b>Client Company / Name & Address:</b> <?php echo htmlspecialchars($row['client_company_address']); ?></td>
                                    <td><b>Manufacturer : </b> <?php echo htmlspecialchars($row['manufacturer']); ?></td>
                                    <td> <b> Equipment Identification Number:</b> <?php echo htmlspecialchars($row['equipment_id_no']); ?></td>
                                    <td><b>Date of Inspection: </b><?php echo htmlspecialchars($row['date_of_inspection']); ?></td>
                                    
                                </tr>
                                <tr>
                                    <td><b>Model: </b><?php echo htmlspecialchars($row['model']); ?></td>
                                    <td><b> Equipment Serial Number : </b> <?php echo htmlspecialchars($row['equipment_serial_no']); ?></td>
                                    <td><b>Next Inspection Due Date : </b> <?php echo htmlspecialchars($row['next_inspection_due_date']); ?></td>
                                </tr>
                                <tr>
                                    <td  style="vertical-align: top;"> <b> Type:</b></td>
                                    <td style="vertical-align: top;" rowspan="2"> <b>
                                        Location:
                                    </b><?php echo htmlspecialchars($row['location']); ?></td>
                                    <td colspan="2">
                            <b>Inspection Status: </b>
                            <?php 
                                if ($inspection_status === 'Passed') {
                                    echo "Passed";
                                } elseif ($inspection_status === 'Failed') {
                                    echo "Failed";
                                } else {
                                    echo "Status not available";
                                }
                            ?>
                        </td>
                                   


                                    
                                </tr>
                                <tr>
                                    <td> <b>Previous Sticker S.No.:</b><?php echo htmlspecialchars($row['prev_sticker_no']); ?></td>
                                    <td><b>Issued by: </b><?php echo htmlspecialchars($row['issued_by']); ?></td>
                                    <td><b>Capacity: </b><?php echo htmlspecialchars($row['capacity']); ?></td>
                                    <td> <b>Sticker Number Issued:</b><?php echo htmlspecialchars($row['sticker_number_issued']); ?></td>
                                    
                                </tr>
                            </tbody>
                        </table>
                        
                        <!-- <br> -->
                         <p>
                     <b>
                           Above Equipment was visually inspected in accordance with local and international standards. Deficiencies that require corrective actions are listed below. Specific repairs to correct each deficiency should be noted in the right column.
                         </b></p>
                        <br>
                        <div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 65%;">Deficiency</th>
                        <th style="width: 30%;">Corrective Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($deficiencies)): ?>
                        <?php foreach ($deficiencies as $index => $entry): ?>
                            <tr style="height: 50px;"> <!-- Adjust the row height -->
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo htmlspecialchars($entry['deficiency']); ?></td>
                                <td><?php echo htmlspecialchars($entry['corrective_action']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr style="height: 50px;"> <!-- Adjust the row height -->
                            <td colspan="3">No deficiencies recorded.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

                          
                           <div class="col-md-12 estimate-html-note">
                                <p>When re-inspected, a complete copy of this report should be ready for review by the inspector.</p>
                                <div class="col-md-12" style="border:2px solid #d0cece;">
                                    <div class="form-group1">
                                        <div class="form-check2">

                                        <!-- <input type="checkbox" name="terms" id="terms" onchange="activateButton(this)" checked style="zoom:1;"> -->
                               
                                               <h6> <input type="checkbox" style="padding-right: 5px;">I agree to take full responsibility for this inspection
                                                   <span> اواوافق الى تحمل المسؤليه الكامله عن هذا الفحص</span>
                                               </h6> 
                                            
                                          
                                            
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                            <div class="col-md-12">
                            <table class="table table-responsive" style="width:100%";>
                            <tr>
                            <td>Report Receiver's Name & Signature</td>
                            <td>Contact Tel. / Mobile Number</td>
                            <td> Inspector Name & Signature</td>
                            </tr>
                            <tr>
                            <td>
                                <img src="../uploads/<?php echo $project_no; ?>.png" alt="" width="70px" height="90px">
                            <?php echo htmlspecialchars($client_name); ?>

                        </td>
                            <td>
                                <img src="../uploads/signatures/<?php echo $project_no; ?>.png" alt="" width="70px" height="90px">
                                Sathish Kumar</td>
                            <?php
// Include database connection
include_once('../../file/config.php');

// Retrieve the project ID from the request (GET or POST)
if (isset($_GET['project_no'])) {
    $project_no = $_GET['project_no'];

    // Query to fetch the inspector_name and signature_photo using the project_no
    $query = "
        SELECT pi.inspector_name, i.signature_photo 
        FROM project_info pi 
        JOIN inspectors i ON pi.inspector_name = i.inspector_name
        WHERE pi.project_no = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $project_no);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $inspector_name = $row['inspector_name'];
        $signature_photo = $row['signature_photo'];

        // Generate the inspector folder name
        $inspector_folder = preg_replace('/\s+/', '_', strtolower($inspector_name));
        $signature_image_path = "../../inspector/uploads/" . $inspector_folder . "/images/" . $signature_photo;

        // Render the table row
        echo "<td><img src='" . htmlspecialchars($signature_image_path) . "' alt='Signature' width='70px' height='90px'> " . htmlspecialchars($inspector_name) . "</td>";
    } else {
        echo "<td>No inspector found for the provided project ID</td>";
    }

    $stmt->close();
} else {
    echo "<td>Project ID not provided</td>";
}

$conn->close();
?>

                            </tr>

                            </table>
                            </div>
                                </div>

                             
                            </div>
                        </div>
                       
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

<?php
$html = ob_get_clean();

// Create an instance of mPDF
$mpdf = new \Mpdf\Mpdf([
    'orientation' => 'L',
    'margin_left' => 5,
    'margin_right' => 5,
    'margin_top' => 5,
    'margin_bottom' => 5,
    'margin_header' => 5,
    'margin_footer' => 5
]);

// Write HTML content to the PDF
$mpdf->WriteHTML($html);


// Output as a PDF
$mpdf->Output('inspection_report.pdf', 'D'); // Download the PDF


?>
