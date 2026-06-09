<?php
include_once(__DIR__ . '/_bootstrap.php');

$project_no = $row['project_no'] ?? '';
$inspector_signature_path = pdf_signature_path($row['inspected_by'] ?? '');
// $client_signature_path = $project_no !== '' ? pdf_asset('uploads/' . $project_no . '.png') : '';
$client_signature_path = $project_no !== '' 
    ? __DIR__ . '/../../../uploads/' . $project_no . '.png' 
    : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INSPECTION CHECKLIST FOR JIB CRANES & DAVITS</title>
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
    cursor: not-allowed;
    position: relative;
}

.custom-checkbox:checked::after {
    content: '';
    position: absolute;
    top: 3px;
    left: 7px;
    width: 5px;
    height: 10px;
    border: 2px solid blue; 
    border-width: 0 3px 3px 0;
    transform: rotate(45deg);
}

/* Ensure styles are applied when printing */
@media print {
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
    
    .thead-dark th {
        background-color: #c0d6e8 !important;
        border-color: #454d55 !important;
    }
    
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
    
    .keep-together { page-break-inside: avoid; break-inside: avoid; }
}

.section-header {
    background-color: #c0d6e8;
    font-weight: bold;
    text-align: left;
    font-size: 11px;
}

.checkbox-cell {
    text-align: center;
    vertical-align: middle;
}
</style>
</head>
<body>
    <div class="container">
    
    <div class="table-responsive">
    <table class="w-100">
        <tr>
            <td rowspan="4" class="logo-cell">
                <img src="../../logo.png" alt="CIMS Logo" width="100">
            </td>
            <td colspan="3" class="no-border">
                <span class="main-title">CRANE INSPECTION & MAINTENANCE SERVICES</span><br>
                A DIVISION OF AL-KHOBAR GATE INTERNATIONAL TRADING EST.
            </td>
        </tr>
        <tr>
            <td colspan="3" class="">
                <strong>INSPECTION CHECKLIST FOR JIB CRANES & DAVITS</strong>
            </td>
        </tr>
        <tr>
            <td>FRM.0601-1.4</td>
            <td>Revision 00</td>
            <td><b>Issue Date: </b>30/SEP/2020</td>
        </tr>
        <tr>
            <td class="left-align"><b>Prepared By</b><br>Operations Manager</td>
            <td class="left-align"><b>Reviewed & Approved By</b><br>Managing Director</td>
            <td><img src="../../../code.png" width="80px" height="80px" alt="" /></td>
        </tr>
    </table>
    </div>

    <h4>JIB CRANES & DAVITS</h4>
    <h4>ASME B30.10, ASME B30.11</h4>
    
    <div class="table-responsive">
        <table class="table table-bordered">
            <tr>
                <th style="width: 25%; background-color: #c0d6e8 !important;">REPORT NO</th>
                <td style="width: 25%;"><strong><?php echo htmlspecialchars($row['report_no']); ?></strong></td>
                <th style="width: 25%; background-color: #c0d6e8 !important;">INSPECTION DATE</th>
                <td style="width: 25%;"><strong><?php echo htmlspecialchars($row['inspection_date']); ?></strong></td>
            </tr>
            <tr>
                <th style="background-color: #c0d6e8 !important;">CLIENT'S NAME</th>
                <td><strong><?php echo htmlspecialchars($row['client_name']); ?></strong></td>
                <th style="background-color: #c0d6e8 !important;">INSPECTED BY</th>
                <td><strong><?php echo htmlspecialchars($row['inspected_by']); ?></strong></td>
            </tr>
            <tr>
                <th style="background-color: #c0d6e8 !important;">LOCATION</th>
                <td><strong><?php echo htmlspecialchars($row['location']); ?></strong></td>
                <th style="background-color: #c0d6e8 !important;">STICKER NO.</th>
                <td><strong><?php echo htmlspecialchars($row['sticker_no']); ?></strong></td>
            </tr>
            <tr>
                <th style="background-color: #c0d6e8 !important;">EQUIPMENT NO</th>
                <td><strong><?php echo htmlspecialchars($row['equipment_no']); ?></strong></td>
                <th style="background-color: #c0d6e8 !important;">EQUIP.SERIAL NO.:</th>
                <td><strong><?php echo htmlspecialchars($row['crane_serial_no']); ?></strong></td>
            </tr>
            <tr>
                <th style="background-color: #c0d6e8 !important;">EQUIPMENT TYPE</th>
                <td><strong><?php echo htmlspecialchars($row['equipmenttype']); ?></strong></td>
                <th style="background-color: #c0d6e8 !important;">CAPACITY (SWL)</th>
                <td><strong><?php echo htmlspecialchars($row['capacity_swl']); ?></strong></td>
            </tr>
        </table>
    </div>

    <form method="post" action="?">
        <input type="hidden" name="checklist_no" value="<?php echo $row['checklist_id']; ?>" />
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th style="text-align: center; width: 10%;">S.N</th>
                        <th style="text-align: center; width: 50%;">ACCEPTANCE CRITERIA</th>
                        <th style="text-align: center; width: 10%;">REFERENCE</th>                  
                        <th style="text-align: center; width: 8%;">PASS</th>
                        <th style="text-align: center; width: 8%;">FAIL</th>
                        <th style="text-align: center; width: 8%;">NA</th>                    
                        <th style="text-align: center; width: 11%;">REMARKS</th>
                    </tr>
                </thead>
                <tbody>
<?php
$sections = [
    'GENERAL REQUIREMENTS' => [
        '1.1' => ['criteria' => 'Documentation is available', 'reference' => 'ASME B30.11'],
        '1.2' => ['criteria' => 'Equipment number is clearly marked for identification purposes.', 'reference' => 'ASME B30.11'],
        '1.3' => ['criteria' => 'Crane is painted safety yellow', 'reference' => 'ASME B30.11'],
        '1.4' => ['criteria' => 'Crane is painted safety yellow & black stripes for offshore.', 'reference' => 'ASME B30.11'],
        '1.5' => ['criteria' => 'Safe Working Load (SWL) is clearly marked on the runway beam', 'reference' => 'ASME B30.11'],
        '1.6' => ['criteria' => 'Pneumatic/electric control valves & switches are in good condition. No leaks are visible.', 'reference' => 'ASME B30.11'],
        '1.7' => ['criteria' => 'Hoist & swing drives are capable of starts & stops with variable acceleration and deceleration required on normal operation', 'reference' => 'ASME B30.11'],
        '1.8' => ['criteria' => 'Hoist drum specifications are marked (rated load, drum size, rope size, rope speed (ft/min. or m/s), rate power).', 'reference' => 'ASME B30.11'],
        '1.9' => ['criteria' => 'Hand chain hoist: manufacturer data, serial number, safe working load are clearly marked/displayed.', 'reference' => 'ASME B30.11'],
        '1.10' => ['criteria' => 'Electric hoist: manufacturer data, serial number, safe working load, voltage and phase are clearly marked/displayed.', 'reference' => 'ASME B30.11'],
        '1.11' => ['criteria' => 'Pneumatic hoist: manufacturer data, serial number, safe working load, rated air pressure are clearly marked/displayed.', 'reference' => 'ASME B30.11'],
        '1.12' => ['criteria' => 'Warning signs/labels are provided on the hoist units and electrical enclosures', 'reference' => 'ASME B30.11'],
        '1.13' => ['criteria' => 'Structure is vibration free under normal condition.', 'reference' => 'ASME B30.11'],
        '1.14' => ['criteria' => 'Jib crane end stop(s) is installed and in good condition.', 'reference' => 'ASME B30.11'],
        '1.15' => ['criteria' => 'Tracks area properly installed and aligned', 'reference' => 'ASME B30.11'],
        '1.16' => ['criteria' => 'Crane runway is fastened and secured to a supporting structure', 'reference' => 'ASME B30.11'],
        '1.17' => ['criteria' => 'All welded members are free of defects and not corroded', 'reference' => 'ASME B30.11'],
        '1.18' => ['criteria' => 'Air powered hoist: Braking system will stop and hold the load hook when controls are released under any load.', 'reference' => 'ASME B30.11'],
        '1.19' => ['criteria' => 'An air hoist stops and holds the load block in the event of air pressure loss.', 'reference' => 'ASME B30.11'],
        '1.20' => ['criteria' => 'Braking system has means for adjustment to compensate for wear.', 'reference' => 'ASME B30.11'],
        '1.21' => ['criteria' => 'Air Powered Hoist: load block is of the enclosed type and means is provided to guard against rope or load chain jamming in the load block', 'reference' => 'ASME B30.11'],
        '1.22' => ['criteria' => 'Rope termination is completed at the hoist wedge anchor with a drop forged U-clip.', 'reference' => 'ASME B30.11'],
        '1.23' => ['criteria' => 'Rope is free of damaged: * Maximum of 12 randomly broken wires in 1 lay. * 4 Broken wires in 1 strand in one lay * 1 Broken wire protruding from the core (2 for rotation resistant ropes) * Wear of 1/3 of the original diameter of outside individual wire. * Kinking, crushing, birdcaging, or other distortion.', 'reference' => 'ASME B30.11'],
        '1.24' => ['criteria' => 'A rope thimble is used in the eye when an eye splice is used in a rope termination (in accordance with the manufacturer\'s instruction.', 'reference' => 'ASME B30.11'],
        '1.25' => ['criteria' => 'Air powered hoists: Rope drum is grooved and free of surface defects that could cause rope damage (excluding hoists made for special applications)', 'reference' => 'ASME B30.11'],
        '1.26' => ['criteria' => 'Hoist drum is adequately lubricated as per the hoist manufacturer\'s manual.', 'reference' => 'ASME B30.11'],
        '1.27' => ['criteria' => 'Drum capacity can accommodate the specific rope size and length', 'reference' => 'ASME B30.11'],
        '1.28' => ['criteria' => 'Drum has a minimum of two wrap on it.', 'reference' => 'ASME B30.11'],
        '1.29' => ['criteria' => 'Each drum end of the rope is anchored by a clamp attached to the drum or by a socket arrangement (approved by the manufacturer)', 'reference' => 'ASME B30.11'],
        '1.30' => ['criteria' => 'Drum flanges always extend a minimum of 1/2" (13 mm) above the top layer of rope at all times.', 'reference' => 'ASME B30.11'],
        '1.31' => ['criteria' => 'Hook is not bent or twisted * maximum bending or twisting not to exceed 10 degrees from plane of unbent hook or as per manufacturer.', 'reference' => 'ASME B30.10 ASME B30.11'],
        '1.32' => ['criteria' => 'Hook is not distorted from the throat opening * Max allowable throat opening is 15% compared to new hook, or as per manufacturer recommendation.', 'reference' => 'ASME B30.10 ASME B30.11'],
        '1.33' => ['criteria' => 'Maximum wear in the hook bowl is not exceeding 10% compared to new hook or as per manufacturer', 'reference' => 'ASME B30.10 ASME B30.11'],
        '1.34' => ['criteria' => 'Hook is not cracked, gouged, or shows nicks', 'reference' => 'ASME B30.10 ASME B30.11'],
        '1.35' => ['criteria' => 'Gangway handrail is free of defects.', 'reference' => 'ASME B30.11'],
        '1.36' => ['criteria' => 'No defects on hook anchor points.', 'reference' => 'ASME B30.10 ASME B30.11'],
        '1.37' => ['criteria' => 'Lower roller & bearings not defective nor corroded.', 'reference' => 'ASME B30.11'],
        '1.38' => ['criteria' => 'Stairs & frames are free from defects and corrosion.', 'reference' => 'ASME B30.11'],
    ],
];

$index = 0;
foreach ($sections as $sectionTitle => $items) {
    // Output section header
    echo '<tr><td colspan="7" class="section-header">' . htmlspecialchars($sectionTitle) . '</td></tr>';
    
    foreach ($items as $itemNo => $itemData) {
        echo '<tr>';
        echo '<td style="text-align: center;"><strong>' . htmlspecialchars($itemNo) . '</strong></td>';
        echo '<td><strong>' . htmlspecialchars($itemData['criteria']) . '</strong></td>';
        echo '<td style="text-align: center;"><strong>' . htmlspecialchars($itemData['reference']) . '</strong></td>';
        
        // Result checkboxes (PASS, FAIL, NA)
        echo '<td class="checkbox-cell">';
        echo '<input type="checkbox" name="checked_arr[0][]" value="PASS" ' . ($selected_results[$index] == "PASS" ? 'checked' : '') . ' disabled class="custom-checkbox">';
        echo '</td>';
        
        echo '<td class="checkbox-cell">';
        echo '<input type="checkbox" name="checked_arr[0][]" value="FAIL" ' . ($selected_results[$index] == "FAIL" ? 'checked' : '') . ' disabled class="custom-checkbox">';
        echo '</td>';
        
        echo '<td class="checkbox-cell">';
        echo '<input type="checkbox" name="checked_arr[0][]" value="NA" ' . ($selected_results[$index] == "NA" ? 'checked' : '') . ' disabled class="custom-checkbox">';
        echo '</td>';
        
        // Remarks field
        echo '<td><input type="text" name="remarks[0]" value="' . htmlspecialchars($chek_remark[$index] ?? '') . '" disabled style="width: 100%;"></td>';
        
        echo '</tr>';
        $index++;
    }
}
?>
                </tbody>
            </table>
        </div>

        <div class="keep-together">
        <div class="table-responsive">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th colspan="3" style="text-align: center;">REMARKS / RECOMMENDATIONS:</th>
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
                    <th style="width: 25%;">INSPECTOR'S NAME:</th>
                    <td style="width: 25%;">
                        <strong><?php echo htmlspecialchars($row['inspected_by']); ?></strong>
                    </td>
                    <th style="width: 25%;">CLIENT'S REP. NAME:</th>
                    <td style="width: 25%;">
                        <?php echo htmlspecialchars($client_name); ?>            
                    </td>
                </tr>
                <tr>
                    <th>SIGNATURE &amp; DATE:</th>
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
    $image_url = $url2 . '/inspector/uploads/' . preg_replace('/\s+/', '_', strtolower($inspector_name)) . '/images/signature_image.jpg';
    echo "<img src=\"$image_url\" alt=\"Inspector Signature\" style=\"max-width: 50px; max-height: 25px;\">";
} else {
    echo "Inspector not found.";
}
?>
                    </td>
                    <th>SIGNATURE &amp; DATE:</th>
                    <td><img src="../../../uploads/<?php echo htmlspecialchars($project_no); ?>.png" height="50px" width="100px" alt="Client Signature" style="max-width: 60px; max-height: 25px;"></td>
                </tr>
            </table>
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
        document.querySelectorAll('#data-table thead tr th').forEach((th, index) => {
            if (index % 4 === 0) {
                th.textContent = 'Print Header Set ' + (Math.floor(index / 4) + 1);
            } else {
                th.textContent = 'Print Column ' + index;
            }
        });
        window.print();
    }
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>