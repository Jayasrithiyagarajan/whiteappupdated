<?php
require_once('../../vendor/autoload.php');
include_once('../../file/config.php');  // Include your database connection file


// Fetch the record based on report_no
$project_no = $_GET['project_no']; // Assuming report_no is passed via URL

$query = "SELECT * FROM reports WHERE project_no = '$project_no'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result); // Fetch record into $row array
} else {
    die("No record found!");
}


// Start capturing output
ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Inspection Report</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 0;
        }

        @page {
            size: A4;
            margin: 15mm;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        th, td {
            border: 1px solid black;
            padding: 5px;
            text-align: left;
            word-wrap: break-word;
        }

        .header, .footer {
            text-align: center;
            font-size: 12px;
            font-weight: bold;
        }

        h3, h4 {
            margin: 0;
        }

        .content-section {
            margin-bottom: 10px;
        }

        /* Layout Adjustments for Compactness */
        .row {
            display: flex;
            justify-content: space-between;
        }

        .col-md-1 {
            width: 10%;
        }

        .col-md-6 {
            width: 60%;
        }

        .col-md-3 {
            width: 30%;
        }

        .logo {
            max-width: 80px;
            max-height: 100px;
        }

        .checkbox {
            display: inline-block;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .signature-section {
            margin-top: 15px;
        }

        /* Compact Table */
        .table th, .table td {
            padding: 4px;
        }

        /* Align Content on One Page */
        .wrapper {
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <img src="../logo.png" class="logo" alt="CIMS">
        <h3>Crane Inspection & Maintenance Services</h3>
        <p><b>P.O.BOX 74007, AL- Khobar 31952, Saudi Arabia</b><br>
           <b>TEL.: 013 814 6861 - 013 814 6862 Ext.109 - Fax: 013 814 6863</b><br>
           <b>Email: office@cims.com.sa - info@cims.com.sa</b>
        </p>
    </div>
    <div class="content-section">
        <div class="row">
            <div class="col-md-6">
                <h4>Report No: <?php echo htmlspecialchars($row['report_no']); ?></h4>
            </div>
            <div class="col-md-6" style="text-align: right;">
                <h4>JRN: <?php echo htmlspecialchars($row['jrn']); ?></h4>
            </div>
        </div>
        <table>
            <tbody>
                <tr>
                    <td colspan="2"><b>Client Company / Address:</b> <?php echo htmlspecialchars($row['client_company_address']); ?></td>
                    <td><b>Manufacturer:</b> <?php echo htmlspecialchars($row['manufacturer']); ?></td>
                </tr>
                <tr>
                    <td><b>Model:</b> <?php echo htmlspecialchars($row['model']); ?></td>
                    <td><b>Equipment ID No:</b> <?php echo htmlspecialchars($row['equipment_id_no']); ?></td>
                    <td><b>Date of Inspection:</b> <?php echo htmlspecialchars($row['date_of_inspection']); ?></td>
                </tr>
                <tr>
                    <td><b>Location:</b> <?php echo htmlspecialchars($row['location']); ?></td>
                    <td><b>Next Inspection Due:</b> <?php echo htmlspecialchars($row['next_inspection_due_date']); ?></td>
                    <td><b>Issued By:</b> <?php echo htmlspecialchars($row['issued_by']); ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="content-section">
        <h4>Deficiencies and Corrective Actions</h4>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Deficiencies</th>
                    <th>Corrective Action Taken</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $deficiencies = explode("\n", trim($row['deficiency'] ?? ''));
            $corrective_actions = explode("\n", trim($row['corrective_action'] ?? ''));

            foreach ($deficiencies as $index => $deficiency) {
                echo "<tr>
                    <td>" . ($index + 1) . "</td>
                    <td>" . htmlspecialchars(trim($deficiency)) . "</td>
                    <td>" . htmlspecialchars(trim($corrective_actions[$index] ?? 'N/A')) . "</td>
                </tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
    <div class="signature-section">
        <p>Receiver Name: ____________________</p>
        <p>Inspector Name: ____________________</p>
    </div>
</div>
</body>
</html>

<?php
$html = ob_get_clean();

// Create mPDF instance
// $mpdf = new \Mpdf\Mpdf(['format' => 'A4', 'margin_left' => 10, 'margin_right' => 10, 'margin_top' => 10, 'margin_bottom' => 10]);

$mpdf = new \Mpdf\Mpdf([
    'format' => 'A4',
    'orientation' => 'P', // 'P' for portrait, 'L' for landscape
    'margin_left' => 10,
    'margin_right' => 10,
    'margin_top' => 10,
    'margin_bottom' => 10,
]);

// Write HTML content to the PDF
$mpdf->WriteHTML($html);


// Output the PDF
$mpdf->Output('inspection_report.pdf', 'D'); // Forces download
?>
