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
    font-size: 9px;
    line-height: 1.3;
    color: #000;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    border: 1px solid #000;
    padding: 4px 3px;
    vertical-align: middle;
}

thead {
    display: table-header-group;
}

th {
    background-color: #c0d6e8;
    font-weight: bold;
    text-align: center;
    font-size: 8px;
}

td {
    font-size: 8px;
}

.section {
    background-color: #c0d6e8;
    font-weight: bold;
    text-align: left;
    padding: 4px;
    font-size: 8px;
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
    margin-top: 10px;
}

.signature-table th {
    background-color: #c0d6e8;
    font-size: 8px;
    text-align: center;
    padding: 4px;
}

.signature-table td {
    text-align: center;
    vertical-align: top;
    padding: 4px 3px;
    height: 45px;
}

.signature-name {
    font-size: 7px;
    font-weight: bold;
    margin-bottom: 2px;
}

.signature-box {
    height: 20px;
    margin: 0 auto;
}

.signature-box img {
    max-width: 35px;
    max-height: 15px;
}

.signature-placeholder {
    font-size: 7px;
    color: #777;
    font-style: italic;
}

.title-section {
    text-align: center;
    font-weight: bold;
    font-size: 10px;
    margin: 8px 0;
}

.footer-section {
    page-break-inside: avoid;
}

.info-table td {
    font-size: 8px;
    padding: 3px;
}

        .keep-together {
            page-break-inside: avoid;
            break-inside: avoid;
        }
</style>
</head>
<body>

<div class="title-section">
INSPECTION CHECKLIST FOR FIXED CRANES & HOISTS<br>
FRM.0601-1.2 (rev.02)<br>
ASME B30.2-2016, ASME B30.3-2016, ASME B30.4-2015, ASME B30.6-2015, ASME B30.16-2017, ASME B30.17-2015
</div>

<br>

<table class="info-table">
<tr>
    <th width="20%">REPORT NO</th>
    <td width="20%" style="text-align:center;"><?= htmlspecialchars($row['report_no'] ?? '') ?></td>
    <th width="20%">INSPECTION DATE</th>
    <td width="20%" style="text-align:center;"><?= htmlspecialchars($row['inspection_date'] ?? '') ?></td>
</tr>
<tr>
    <th>CLIENT'S NAME</th>
    <td style="text-align:center;"><?= htmlspecialchars($row['client_name'] ?? '') ?></td>
    <th>INSPECTED BY</th>
    <td style="text-align:center;"><?= htmlspecialchars($row['inspected_by'] ?? '') ?></td>
</tr>
<tr>
    <th>LOCATION</th>
    <td style="text-align:center;"><?= htmlspecialchars($row['location'] ?? '') ?></td>
    <th>STICKER NO.</th>
    <td style="text-align:center;"><?= htmlspecialchars($row['sticker_no'] ?? '') ?></td>
</tr>
<tr>
    <th>CRANE ASSET NO</th>
    <td style="text-align:center;"><?= htmlspecialchars($row['equipment_no'] ?? '') ?></td>
    <th>CRANE SERIAL NO.</th>
    <td style="text-align:center;"><?= htmlspecialchars($row['crane_serial_no'] ?? '') ?></td>
</tr>
<tr>
    <th>EQUIPMENT TYPE</th>
    <td style="text-align:center;"><?= htmlspecialchars($row['equipmenttype'] ?? '') ?></td>
    <th>CAPACITY (SWL)</th>
    <td style="text-align:center;"><?= htmlspecialchars($row['capacity_swl'] ?? '') ?></td>
</tr>
</table>

<br>

<table>
<thead>
<tr>
    <th width="4%">S.N</th>
    <th width="42%">ACCEPTANCE CRITERIA</th>
    <th width="12%">REFERENCE</th>
    <th width="6%">PASS</th>
    <th width="6%">FAIL</th>
    <th width="6%">NA</th>
    <th width="24%">REMARKS</th>
</tr>
</thead>
<tbody>
<?php
$sections = [
    '1. GENERAL REQUIREMENTS' => [
        'Equipment documentation is available' => 'ASME B30.2, Sec.1.16',
        'Previous inspection reports are checked' => 'ASME B30.2, Sec.2.1.5',
        'Rated load is clearly marked on both sides of crane bridge' => 'ASME B30.2, Sec.1.1.1',
        'Rated load is clearly marked on hoist or trolley unit' => 'ASME B30.2, Sec.1.1.1',
        'Equipment number is clearly marked for identification purposes' => 'ASME B30.16 Sec.1.1',
        'Safe working load is clearly marked on the runway and the lifting machine' => 'ASME B30.16 Sec.1.1.1',
        'Crane manufacturer name, address, serial number and power ratings are clearly marked or tagged' => 'ASME B30.2, Sec.1.1.3',
        'Precautionary warnings to operator are clearly marked' => 'ASME B30.2, Sec.1.1.5',
    ],
    '2. GENERAL INSPECTION POINTS' => [
        'Clearance exits between the crane and sides of the building or adjacent crane are maintained throughout all motions' => 'ASME B30.2, Sec.1.2.1',
        'Controls are clearly marked with their functions and modes of operation' => 'ASME B30.3 Sec.3-1.18.1',
        'Controls and protective equipment are within reach of the operator inside the cab' => 'ASME B30.2, Sec.1.5.1a',
        'The hook block is visible from operator station at all times' => 'ASME B30.2, Sec.1.5.1b',
        'Cab is attached to the crane to minimize swaying and vibrations' => 'ASME B30.2, Sec.1.5.2a',
        'Access to the cab or bridge walkway is by a fixed ladder, stairs, or platform' => 'ASME B30.2, Sec.1.5.3',
        'Controls arrangements and protective equipment inside the cab are within the reach of the operator' => 'ASME B30.2, Sec.1.5.1a',
        'The clearance from the surface of the platform to the nearest overhead obstruction is 1220 mm (48")' => 'ASME B30.2, Sec.1.7.1a',
        'The service platform width is at least 457 mm (18") except at the bridge mechanism where it is not less than 380 mm (15")' => 'ASME B30.2, Sec.1.7.1c',
        'The electrical control cabinet door(s) are opening 90 degree or removable type' => 'ASME B30.2, Sec.1.7.1e',
        'Service platform walking surface is slip-resistant' => 'ASME B30.2, Sec.1.7.1g',
        'Service platform is provided with guard railings and toe boards' => 'ASME B30.2, Sec.1.7.1h',
        'Emergency escape is possible from the cab' => 'ASME B30.2, Sec.1.7.3',
        'Stairways are non-slip and have a maximum incline angle of 50 degree' => 'ASME B30.2, Sec.1.7.2',
        'Each hoisting unit is equipped with at least one holding brake' => 'ASME B30.2, Sec.1.12.1a',
        'The holding brake is applied to the motor shaft or a gear reducer shaft' => 'ASME B30.2, Sec.1.12.1a',
        'The holding brake torque rating is not less than the percentage of rated load hoisting torque at the point where the brake is applied' => 'ASME B30.2, Sec.1.12.1a',
        'Pendant control cable is properly enclosed, grounded and suspended with a separate support cable' => 'ASME B30.2, Sec.1.13.1a-d',
        'Pendant control push-button enclosure is marked for identification of functions' => 'ASME B30.2, Sec.1.13.1e',
        'Electrical equipment is guarded and not exposed to oil, moisture, dirt and inadvertent contact' => 'ASME B30.2, Sec.1.13.2',
        'Audio warning device(s) are fitted (one or more of the following: Gong, Bell/Siren/Horn, Rotating Beacon and/or strop light)' => 'ASME B30.2, Sec.1.15.3',
        'Lifting and lowering functional test is satisfactory' => 'ASME B30.2, Sec.2.2(b-1)',
        'Crane trolley travel functional test is satisfactory' => 'ASME B30.2, Sec.2.2(b-2)',
        'Crane bridge travel functional test is satisfactory' => 'ASME B30.2, Sec.2.2(b-3)',
        'Hoist limit device functional test is satisfactory' => 'ASME B30.2, Sec.2.2(b-4)',
        'Hoist and swing drives are capable of starts and stops with variable acceleration and deceleration required in normal operation' => 'ASME B30.7 Sec.1.2.2(f)',
        'Hoist drum specifications are marked (rated load, drum size, rope size, rope speed, rated power)' => 'ASME B30.7 Sec.1.1.3',
        'Hand Chain Hoist: Manufacturer data, serial number and safe working load are clearly displayed on the item' => 'ASME B30.16 Sec.1.1.3a',
        'Electric Powered Hoist: Manufacturer data, serial number, safe working load, voltage and phase are clearly displayed on the item' => 'ASME B30.16 Sec.1.1.3b',
        'Air Powered Hoist: Manufacturer data, serial number, model, safe working load and rated air pressure are clearly displayed on the item' => 'ASME B30.16 Sec.1.1.3c',
        'Warning signs/labels are provided on the hoist units and electrical enclosures' => 'ASME B30.16 Sec.1.1.4',
        'Crane Travel limit device functional test is satisfactory' => 'ASME B30.2, Sec.2.2(b-4)',
        'Wire rope end connections do not have corrosion' => 'ASME B30.2, Sec.2.4.2(c,d)',
        'Ropes are correctly lubricated' => 'ASME B30.2, Sec.2.4.3e',
        'Wire rope is not corroded' => 'ASME B30.2, Sec.2.4.1(a1-b)',
        'The rope is adequately lubricated' => 'ASME B30.2, Sec.2.4.3e',
        'Fire extinguisher is available (SEC 10BC minimum rated)' => 'ASME B30.2, Sec.3.4.3',
        'Structure is vibration free under normal operating condition' => 'ASME B30.17 Sec.1.3.1(b)',
        'Monorail end stops are installed and in good condition' => 'ASME B30.17 Sec.1.4.2, Sec 1.5.3',
        'Jib crane end stops are installed and in good condition' => 'ASME B30.17 Sec.1.4.2, Sec 1.5.3',
        'Tracks are properly installed and aligned' => 'ASME B30.17 Sec.1.3.1 Sec 1.4.1',
        'Crane runways or monorail tracks are fastened and Secured to a supporting structure' => 'ASME B30.17 Sec.1.3.2',
        'All welded members are free of defects and not corroded' => 'ASME B30.17 Sec.1.3.4',
        'Guards protect moving parts such as gears, chains, chain sprockets' => 'ASME B30.17 Sec.1.11.1',
        'Guards protect ropes where liable to come in contact with conductors' => 'ASME B30.17 Sec.1.11.2(a)',
        'Guards are provided to prevent contact between crane bridge or runway conductors and hoisting ropes' => 'ASME B30.17 Sec.1.11.2(b)',
        'Hand chain operated Hoist: Hoist automatically stops and holds lifted load when the actuating force is removed' => 'ASME B30.16 Sec.1.2.11a',
        'Electric Powered Hoist: Braking system will stop and hold the load hook when controls are released under any load condition' => 'ASME B30.16 Sec.1.2.11(b1-b)',
        'Air Powered Hoist: Braking system will stop and hold the load hook when controls are released under any load condition' => 'ASME B30.16 Sec.1.2.11(c1-a)',
        'An electric hoist stops and holds the load block in the event of power failure' => 'ASME B30.16 Sec.1.2.11(b1-c)',
        'An air hoist stops and holds the load block in the event of air pressure loose' => 'ASME B30.16 Sec.1.2.11(c1-b)',
        'Braking systems has means for adjustment to compensate for wear' => 'ASME B30.16 Sec.1.2.11(b3/c)',
        'Hoist rope is guarded from chafing where required' => 'ASME B30.2, Sec.1.14.6',
        'Hook(s) can rotate freely' => 'ASME B30.2, Sec.1.14.5',
        'Rope compensating sheave(s) (equalizer) is free to turn' => 'ASME B30.2, Sec.1.14.4',
        'Surface condition of rope drum(s) show no defects and are smooth' => 'ASME B30.2, Sec.1.14.2',
        'All sheave grooves are smooth' => 'ASME B30.2, Sec.1.14.1',
        'All sheaves are free to turn' => 'ASME B30.2, Sec.1.14.1',
        'Rope construction is as per manufacturer recommendations' => 'ASME B30.2, Sec.1.14.3a',
        'Lower hoist limit cut-out (if fitted) is properly working' => 'ASME B30.2, Sec.1.13.5e',
        'Stops and bumpers are fitted to each end of the trolley(s)' => 'ASME B30.2, Sec.1.8.1, 3',
        'Trolley truck rail sweeps are provided in front of the leading wheels on both ends of the trolley end truck' => 'ASME B30.2, Sec.1.9.2a',
        'Clearance between the top surface of the rail head and the bottom of the sweep does not exceed 3/16" (5 mm)' => 'ASME B30.2, Sec.1.9.2b-1',
        'The sweep extends below the top surface of the rail head, for a distance not less than 50% of the thickness of the rail head, on both sides' => 'ASME B30.2, Sec.1.9.2b-2',
        'Hoisting and Lowering Speeds are as per design specifications' => 'ASME B30.2, Sec.1.12',
        'Trolley Travel Speeds are as per design specifications' => 'ASME B30.2, Sec.1.10',
        'Bridge Travel Speed are as per design specifications' => 'ASME B30.2, Sec.1.10',
        'Hoist brakes comply with crane design requirements' => 'ASME B30.2, Sec.1.12.5',
        'Trolley brakes comply with crane design requirement' => 'ASME B30.2, Sec.1.12.5',
        'Trolley stops within stipulated 10% distance of rated load speed under frictional forces' => 'ASME B30.2, Sec.1.12.4b',
        'Crane Bridge stops within stipulated 10% distance of rated load speed under frictional forces' => 'ASME B30.2, Sec.1.12.4a',
        'Bridge brakes comply with crane design requirements' => 'ASME B30.2, Sec.1.12.5',
        'Trolley truck frame drop is limited to 25mm' => 'ASME B30.2, Sec.1.11',
        'Bridge is fitted with bumpers at each end' => 'ASME B30.2, Sec.1.8.2',
        'Bridge rail sweep clearance is 5mm' => 'ASME B30.2, Sec.1.9.1',
        'All moving parts are guarded where potential hazard would exist otherwise' => 'ASME B30.2, Sec.1.10a',
        'Travel warnings are operational (gong, bell, siren, horn, beacon, or strop light)' => 'ASME B30.2, Sec.1.15.1a',
        'Crane structure shows no deformed, cracked or corroded members' => 'ASME B30.2, Sec.2.1.3b1',
        'All travel limit devices are functioning' => 'ASME B30.2, Sec.1.3b10',
        'Safety labels are displayed and legible' => 'ASME B30.2, Sec.1.1.5',
        'Integral outside platform is in place and door opens outward or slides' => 'ASME B30.2, Sec.1.5.2b',
        'Trapdoor has a clear opening of not less than 610mm' => 'ASME B30.2, Sec.1.5.2e',
        'Guard railings and toe boards are in good condition' => 'ASME B30.2, Sec.1.5.2f',
        'All cab glazing\'s are safety glazing materials' => 'ASME B30.2, Sec.1.5.2g',
        'A tool box is in place for basic maintenance made of noncombustible material and is securely fastened in the cab' => 'ASME B30.2, Sec.1.5.2h',
        'Crane is free of excessive noise during normal operation' => 'ASME B30.2, Sec.1.3.1a',
        'Equipment has been maintenance as per manufacturer\'s recommendations' => 'ASME B30.2, Sec.3.1.1',
    ],
    '3. FINAL INSPECTION' => [
        'Load test has been performed at 110% of rated load, which has been successfully completed' => 'ASME B30.2, Sec.3.2.1',
        'Welded structures and members do not have cracks or corrosion' => 'ASME B30.2, Sec.1.4.1',
        'Adequate clearances exist between two parallel crane bridges (if there are no intervening walls or structures)' => 'ASME B30.2, Sec.1.2.2',
        'Minimum working space on service platforms is 1220mm (48")' => 'ASME B30.2, Sec.1.7.1a',
        'Minimum passageway on service platform is 457mm (18")' => 'ASME B30.2, Sec.1.7.1c',
    ],
];

$index = 0;
foreach ($sections as $sectionTitle => $items) {
    echo "<tr><td colspan='7' class='section'>" . htmlspecialchars($sectionTitle) . "</td></tr>";
    $itemNo = 1;
    foreach ($items as $item => $reference) {
        $sn = explode('. ', $sectionTitle, 2)[0] . '.' . $itemNo;
        echo '<tr>';
        echo '<td>' . htmlspecialchars($sn) . '</td>';
        echo '<td>' . htmlspecialchars($item) . '</td>';
        echo '<td style="font-size:7px;">' . htmlspecialchars($reference) . '</td>';
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

<div class="footer-section">
<div class="keep-together">

<table>
<tr>
    <th style="text-align:left;">REMARKS / RECOMMENDATIONS</th>
</tr>
<tr>
    <td style="height:70px;"><?= htmlspecialchars($recommendations) ?></td>
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
