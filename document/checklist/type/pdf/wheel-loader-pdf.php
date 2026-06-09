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
<html>
<head>
<meta charset="utf-8">
<style>
body {
    font-family: DejaVu Sans, sans-serif;
    font-size: 9.5px;
    line-height: 1.4;
    color: #000;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    border: 1px solid #000;
    padding: 6px 5px;
    vertical-align: middle;
}

thead {
    display: table-header-group;
}

th {
    background-color: #c0d6e8;
    font-weight: bold;
    text-align: center;
}

.section {
    background-color: #c0d6e8;
    font-weight: bold;
    text-align: left;
    padding: 6px;
    font-size: 10px;
}

.center {
    text-align: center;
    font-weight: bold;
}

.tick {
    color: #1a8f2a;
    font-size: 18px;
    font-weight: bold;
    display: inline-block;
    line-height: 1;
}

.signature-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

.signature-table th {
    background-color: #c0d6e8;
    font-size: 10px;
    text-align: center;
    padding: 6px;
}

.signature-table td {
    text-align: center;
    vertical-align: top;
    padding: 6px 4px;
    height: 60px;
}

.signature-name {
    font-size: 9px;
    font-weight: bold;
    margin-bottom: 3px;
}

.signature-box {
    height: 28px;
    margin: 0 auto;
}

.signature-box img {
    max-width: 45px;
    max-height: 18px;
}

.signature-placeholder {
    font-size: 8px;
    color: #777;
    font-style: italic;
}

        .keep-together {
            page-break-inside: avoid;
            break-inside: avoid;
        }
</style>
</head>
<body>

<br>

<h2 style="text-align: center;">NDT CHECKLIST <br>
REFERENCE: ASME B30 STANDARDS</h2>

<br>

<table>
<tr>
    <th width="25%">VESSEL NAME</th>
    <td width="25%" style="text-align:center;"><?= htmlspecialchars($row['vessel_name'] ?? '') ?></td>
    <th width="25%">REPORT NO</th>
    <td width="25%" style="text-align:center;"><?= htmlspecialchars($row['report_no'] ?? '') ?></td>
</tr>
<tr>
    <th>LOCATION</th>
    <td style="text-align:center;"><?= htmlspecialchars($row['location'] ?? '') ?></td>
    <th>INSPECTION DATE</th>
    <td style="text-align:center;"><?= htmlspecialchars($row['inspection_date'] ?? '') ?></td>
</tr>
<tr>
    <th>EQUIPMENT NO</th>
    <td style="text-align:center;"><?= htmlspecialchars($row['equipment_no'] ?? '') ?></td>
    <th>TYPE</th>
    <td style="text-align:center;"><?= htmlspecialchars($row['equipmenttype'] ?? '') ?></td>
</tr>
<tr>
    <th>MANUFACTURER</th>
    <td style="text-align:center;"><?= htmlspecialchars($row['manufacturer'] ?? '') ?></td>
    <th>YEAR MODEL</th>
    <td style="text-align:center;"><?= htmlspecialchars($row['year_model'] ?? '') ?></td>
</tr>
<tr>
    <th>MODEL NO</th>
    <td style="text-align:center;"><?= htmlspecialchars($row['model_no'] ?? '') ?></td>
    <th>CAPACITY (SWL)</th>
    <td style="text-align:center;"><?= htmlspecialchars($row['capacity_swl'] ?? '') ?></td>
</tr>
<tr>
    <th>SERIAL NO</th>
    <td style="text-align:center;"><?= htmlspecialchars($row['crane_serial_no'] ?? '') ?></td>
    <th>CLIENT NAME</th>
    <td style="text-align:center;"><?= htmlspecialchars($row['client_name'] ?? '') ?></td>
</tr>
</table>

<br>

<table>
<thead>
<tr>
    <th width="6%">S.N</th>
    <th width="38%">ACCEPTANCE CRITERIA</th>
    <th width="10%">REFERENCE</th>
    <th width="8%">PASS</th>
    <th width="8%">FAIL</th>
    <th width="8%">NA</th>
    <th width="22%">REMARKS</th>
</tr>
</thead>
<tbody>
<?php
$sections = [
    '1. GENERAL REQUIREMENTS' => [
        ['Documentation is available.', '>'],
        ['Equipment asset ID Number is prominently marked.', '>'],
        ['Nameplate, caution, and instruction markings are available on the truck.', '>'],
        ['SWL/WLL (Capacities) are clearly marked & on a prominent location.', '>'],
        ['All controls are marked for identification of function.', '>'],
        ['Bucket Identification, capacity, serial number, and model.', '>'],
        ['The machine is operated by Certified Operator.', '>'],
        ['Capacity chart is provided & legible.', '>'],
        ['All safety & warning decals are posted.', '>'],
    ],
    '2. INSPECTION POINTS' => [
        ['Carriage & Backrest are not deformed.', '>'],
        ['Bucket structure.', '>'],
        ['No excessive corrosion on frames, anchorages, structures are present.', '>'],
        ['Lift & tilt cylinders are operating correctly & without hydraulic oil leaks.', '>'],
        ['Steering cylinders are operating correctly & without hydraulic oil leaks.', '>'],
        ['ROPS or overhead guard is provided and can withstand the drop test based on the applicable table or rated capacity.', '>'],
        ['Safety belt is provided.', '>'],
        ['All control levers are within reach of operator during the normal operating conditions.', '>'],
        ['All hydraulic hoses are free of tears, and no signs of leaks on their hose fittings.', '>'],
        ['Hydraulic oil tank level is correct and tank is securely fastened, and no signs of oil leakages.', '>'],
        ['Fuel tank is secured & not leaking.', '>'],
        ['Steering & transmission oil levels are correct & not leaking.', '>'],
        ['Lubrication points are accessible.', '>'],
        ['No deterioration of Air & Water Hose and or leakage of Air and Water.', '>'],
        ['No indication of loose, damaged, or missing structural components including supports and anchorages.', '>'],
        ['Limit Switches are properly working.', '>'],
        ['Brake & Clutch system parts & linings have no excessive wear, severe distortion, and damage.', '>'],
        ['Seat and back cushion are not torn.', '>'],
        ['Tires have no tears, sidewall cuts & excessive wear.', '>'],
        ['Wheel hubs and gear boxes have no leakages.', '>'],
        ['Wheel bearings / bushing have no signs of excessive wear.', '>'],
        ['No indication of loose, damaged, or missing components including supports and anchorages on under chassis.', '>'],
        ['Control & drive mechanisms are properly adjusted and without excessive wear.', '>'],
        ['Brake fluid level is correctly topped-up.', '>'],
        ['Parking brake is correctly working.', '>'],
        ['Steering cylinders, knuckles, kingpins, tie rods, equalizer bar, etc. have no excessive plays and not excessively worn-out.', '>'],
        ['No damage tubing, piping, electrical cables, or hoses, and their fittings.', '>'],
        ['Front & Rear Windshields are in good condition& Wiper Motor Assembly are working.', '>'],
    ],
    '3. ADDITIONAL INSPECTION' => [
        ['Engine has no excessive smoke, & engine oil leak.', '>'],
        ['Fuel is not leaking.', '>'],
        ['Engine has no loss of power.', '>'],
        ['Fan, Alternator, & steering belts tension are not loose.', '>'],
        ['Instrument Panel Indicator Lights are functioning correctly.', '>'],
        ['Strobe light or rotating beacon light is working.', '>'],
        ['Head light & working lights are not broken and are functioning correctly.', '>'],
        ['Brake & tail lights are working.', '>'],
        ['Back-Up (Reverse) Light and alarm are working.', '>'],
        ['Horn is working.', '>'],
    ],
    '4. SECTION' => [
        ['Bucket Leveling Proximity Switch.', '>'],
        ['Bucket Arm Tilt-up Proximity Switch.', '>'],
        ['Access and Step Ladders', '>'],
        ['Guard Rails', '>'],
        ['Fire Extinguisher', '>'],
        ['Housekeeping', '>'],
    ],
];

$index = 0;
foreach ($sections as $sectionTitle => $items) {
    echo "<tr><td colspan='7' class='section'>" . htmlspecialchars($sectionTitle) . "</td></tr>";
    $itemNo = 1;
    foreach ($items as $itemData) {
        $item = $itemData[0];
        $ref = $itemData[1];
        $sn = explode('. ', $sectionTitle, 2)[0] . '.' . $itemNo;
        echo '<tr>';
        echo '<td>' . htmlspecialchars($sn) . '</td>';
        echo '<td>' . htmlspecialchars($item) . '</td>';
        echo '<td style="text-align:center; font-size: 8px;">' . htmlspecialchars($ref) . '</td>';
        echo '<td class="center">' . pdf_mark_result($index, 'PASS', $selected_results) . '</td>';
        echo '<td class="center">' . pdf_mark_result($index, 'FAIL', $selected_results) . '</td>';
        echo '<td class="center">' . pdf_mark_result($index, 'NA', $selected_results) . '</td>';
        echo '<td>' . htmlspecialchars($chek_remark[$index] ?? '') . '</td>';
        echo '</tr>';
        $index++;
        $itemNo++;
    }
}
?>
</tbody>
</table>

<div class="keep-together">

<table>
<tr>
    <th style="text-align:left;">REMARKS / RECOMMENDATIONS</th>
</tr>
<tr>
    <td style="height:90px;"><?= htmlspecialchars($recommendations) ?></td>
</tr>
</table>





<br>

<table class="signature-table">
    <tr>
        <th width="50%">INSPECTOR</th>
        <th width="50%">CLIENT REPRESENTATIVE</th>
    </tr>
    <tr>
        <td>
            <div class="signature-name"><?= htmlspecialchars($row['inspected_by'] ?? '') ?></div>
            <div class="signature-box">
                <?php if ($inspector_signature_path && file_exists($inspector_signature_path)) : ?>
                    <img src="<?= htmlspecialchars($inspector_signature_path) ?>" alt="Inspector Signature" style="max-width: 60px; max-height: 25px;">
                <?php else : ?>
                    <div class="signature-placeholder">Signature Not Available</div>
                <?php endif; ?>
            </div>
        </td>
        <td>
            <div class="signature-name"><?= htmlspecialchars($client_name) ?></div>
            <div class="signature-box">
                <?php if ($client_signature_path && file_exists($client_signature_path)) : ?>
                    <img src="<?= htmlspecialchars($client_signature_path) ?>" alt="Client Signature" style="max-width: 60px; max-height: 25px;">
                <?php else : ?>
                    <div class="signature-placeholder">Signature Not Available</div>
                <?php endif; ?>
            </div>
        </td>
    </tr>
</table>

</div>

</body>
</html>