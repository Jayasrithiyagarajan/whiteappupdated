<?php
include_once(__DIR__ . '/_bootstrap.php');

$selected_results = explode(',', $db_result);
$chek_remark = explode(',', $db_remark);

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

<table>
<tr>
    <th width="25%">REPORT NO</th>
    <td width="25%" style="text-align:center;"><?= htmlspecialchars($row['report_no'] ?? '') ?></td>
    <th width="25%">DATE</th>
    <td width="25%" style="text-align:center;"><?= htmlspecialchars($row['inspection_date'] ?? '') ?></td>
</tr>
<tr>
    <th>CLIENT</th>
    <td style="text-align:center;"><?= htmlspecialchars($row['client_name'] ?? '') ?></td>
    <th>INSPECTOR</th>
    <td style="text-align:center;"><?= htmlspecialchars($row['inspected_by'] ?? '') ?></td>
</tr>
<tr>
    <th>LOCATION</th>
    <td style="text-align:center;"><?= htmlspecialchars($row['location'] ?? '') ?></td>
    <th>STICKER NO</th>
    <td style="text-align:center;"><?= htmlspecialchars($row['sticker_no'] ?? '') ?></td>
</tr>
<tr>
    <th>EQUIPMENT NO</th>
    <td style="text-align:center;"><?= htmlspecialchars($row['equipment_no'] ?? '') ?></td>
    <th>EQUIP. SERIAL NO.</th>
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
    <th width="6%">S.N</th>
    <th width="38%">ACCEPTANCE CRITERIA</th>
    <th width="10%">REF</th>
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
        '1.1' => ['Equipment documentation is available', 'ASME B30.14 Sec. 2.1.5'],
        '1.2' => ['Previous inspection reports are checked', 'ASME B30.14 Sec. 2.1.5'],
        '1.3' => ['Operator is certified or qualified for the specific type of equipment.', 'ASME B30.14 Sec.3.1.1(a)(1-3)'],
        '1.4' => ['Load rating chart is applicable to the configured boom and is legible', 'ASME B30.14 Sec. 1.1.3'],
        '1.5' => ['An operating manual is available', 'ASME B30.14 Sec. 1.1.3 (c)'],
        '1.6' => ['A sign is posted warning the operator not to rely solely on any automatic device as a substitute for safe operating practice', 'CIMS QHSE 06'],
        '1.7' => ['Rated capacity of crane is marked', 'CIMS QHSE 06'],
        '1.8' => ['A fire extinguisher with minimum rating of 10 BC is installed in the cab or at the machinery housing', 'ASME B30.14 Sec. 3.4.4 (a)'],
    ],
    '2. GENERAL INSPECTION POINTS' => [
        '2.0' => ['Electrical apparatus is working correctly without excessive dirt, deterioration or moisture accumulation', 'ASME B30.2, Sec.1.2.1'],
        '2.1' => ['Control levers are all operable from the operator station', 'ASME B30.14 Sec. 1.3.1&2'],
        '2.2' => ['Control pedals are functioning correctly', 'ASME B30.14 Sec. 1.3.1&2'],
        '2.3' => ['All controls are labeled as to their functions and within reach of operator', 'ASME B30.14 Sec. 1.4.1'],
        '2.4' => ['Guards are fitted and secured to cover exposed moving parts', 'ASME B30.14 Sec. 1.7.5'],
        '2.5' => ['Guards are fitted and secured to cover exposed moving parts', 'ASME B30.14 Sec. 1.7.5'],
        '2.6' => ['All moving parts that require lubrication are lubricated and accessible', 'ASME B30.14 Sec. 2.3.4 (b)'],
        '2.7' => ['Boom angle indicator is functioning correctly', 'ASME B30.14 Sec. 2.1.2 (c2)'],
        '2.8' => ['Boom hoist shut off is functioning correctly', 'ASME B30.14 Sec. 2.1.2 (c2)'],
        '2.9' => ['Load indicator is functioning correctly', 'ASME B30.14 Sec. 2.1.2 (c2)'],
        '2.10' => ['Capacity indicator is functioning correctly', 'ASME B30.14 Sec. 2.1.2 (c2)'],
        '2.11' => ['Ignition system is operating correctly', 'ASME B30.14 Sec. 1.4.3 (a)'],
        '2.12' => ['Emergency shut down is operating satisfactorily', 'ASME B30.14 Sec. 1.4.3 (c)'],
        '2.13' => ['Engine throttle is functioning satisfactorily', 'ASME B30.14 Sec. 1.4.3 (b)'],
        '2.14' => ['Battery is secured and ventilated', 'ASME B30.14 Sec. 2.1.3 (f)'],
        '2.15' => ['Engine gauges are functioning correctly', 'ASME B30.14 Sec. 2.1.2 (c7)'],
        '2.16' => ['Exhaust system is not corroded, and is guarded or insulated where necessary to prevent personal contact with hot surfaces', 'ASME B30.14 Sec. 1.7.2'],
        '2.17' => ['Engine oil level is adequate.', 'ASME B30.14 Sec. 2.3.1'],
        '2.18' => ['Hydraulic systems are not leaking', 'ASME B30.14 Sec. 2.1.2 (c3)'],
        '2.19' => ['Oil and fuel tanks do not leak', 'ASME B30.14 Sec. 2.1.2 (c3)'],
        '2.20' => ['Hydraulic/Pneumatic systems are not leaking (pumps, valves, lines, etc.', 'ASME B30.14 Sec. 2.1.2 (c3)'],
        '2.21' => ['Hook assembly labeling and manufacturer data is clearly marked', 'ASME B30.10 Sec. 1.1.1'],
        '2.22' => ['Hook\'s weight is clearly marked/printed on the hook', 'ASME B30.10 Sec. 1.1.1'],
        '2.23' => ['Safe working load of hook is clearly marked on the item', 'ASME B30.10 Sec. 1.1.1'],
        '2.24' => ['Hook does not show defects such as nicks, cracks and gouges', 'ASME B30.10 Sec. 1.2.1.2 (c3)'],
        '2.25' => ['Hook is not bent or twisted Max. bending or twisting not to exceed 10 degrees from plane of unbent hook or as per manufacturer recommendations', 'ASME B30.10 Sec.10-1.2.1.3(c1)'],
        '2.26' => ['Hook is not distorted in the throat opening Max. allowable throat opening is 15% compared to new hook, or as per manufacturer recommendations', 'ASME B30.10 Sec. 1.2.1.3 (c2)'],
        '2.27' => ['Maximum wear in the hook bowl is not exceeding 10% (compared to new hook) or as per manufacturer recommendations', 'ASME B30.10 Sec. 1.2.1.3 (c3)'],
        '2.28' => ['Hook is not cracked, gouged or shows nicks', 'ASME B30.10 Sec. 1.2.1.2 (c3)'],
        '2.29' => ['Hook can lock (if it is a self-locking hook)', 'ASME B30.10 Sec. 1.2.1.3 (c4)'],
        '2.30' => ['Hook latch is operative', 'ASME B30.10 Sec. 1.2.1.3 (c5)'],
        '2.31' => ['Upper and lower structures are free of defective/corroded welds', 'ASME B30.14 Sec. 1.7.4'],
        '2.32' => ['Boom mounting ears are not cracked', 'ASME B30.14 Sec. 2.1.3(d)'],
        '2.33' => ['Boom bushings and pins are not worn and are secure', 'ASME B30.14 Sec. 2.1.3 (d)'],
        '2.34' => ['Machinery deck frame is undamaged', 'ASME B30.14 Sec. 2.1.3 (a)'],
        '2.35' => ['Boom hoist cylinder mount is secure', 'ASME B30.14 Sec. 1.2.1'],
        '2.36' => ['A-frame frontage is undamaged', 'ASME B30.14 Sec. 1.7.3 (a)'],
        '2.37' => ['A-frame back legs are undamaged', 'ASME B30.14 Sec. 1.7.3 (a)'],
        '2.38' => ['Float mast is undamaged', 'ASME B30.14 Sec. 1.7.3 (d)'],
        '2.39' => ['Sheave grooves are free from surface defects and lubricated', 'ASME B30.14 Sec. 1.5.4 (a)'],
        '2.40' => ['Rope Carrying sheaves have close-fittings guards or other suitable devices', 'ASME B30.14 Sec. 1.5.4 (b)'],
        '2.41' => ['Lower block sheaves have close-fittings guards', 'ASME B30.14 Sec. 1.5.4(c)'],
        '2.42' => ['Inner bridle frame is undamaged', 'ASME B30.14 Sec. 2.1.3(a)'],
        '2.43' => ['Inner bridle frame sheaves are smooth in their grooves and lubricated', 'ASME B30.14 Sec. 2.1.4 (a)'],
        '2.44' => ['Bearings and bushings are undamaged, secure and lubricated', 'ASME B30.14 Sec. 2.1.3 (d)'],
        '2.45' => ['Outer bridle frame is undamaged', 'ASME B30.14 Sec. 2.1.3 (a)'],
        '2.46' => ['Outer bridle frame sheaves are smooth in their grooves and lubricated', 'ASME B30.14 Sec. 1.5.4'],
        '2.47' => ['Boom stops are fitted and undamaged', 'ASME B30.14 Sec.1.7.1 (a) Sec. 2.2.1 (3)'],
        '2.48' => ['Load hoist brake function is satisfactory', 'ASME B30.14 Sec. 1.2.2 (b)'],
        '2.49' => ['Load lifting and lowering operations are satisfactory', 'ASME B30.14 Sec. 1.2.2 (a)'],
        '2.50' => ['Two full wraps, at least, of rope remain on the drum when the hook is in its extreme low working position', 'ASME B30.14 Sec. 1.2.2 (a2)'],
        '2.51' => ['Rope ends are anchored to the drum by clamps or as per manufacturer recommendations', 'ASME B30.14 Sec. 1.2.2 (a2)'],
        '2.52' => ['Drums are provided with a guidance or other means to prevent rope from jumping off the drum', 'ASME B30.14 Sec. 1.2.2 (a2)'],
        '2.53' => ['Power controlled lowering is operational and meets the speed and load criteria as per manufacturers specifications', 'ASME B30.14 Sec. 1.2.2 (c)'],
        '2.54' => ['Positive braking means is available, controllable by the operator, to prevent drum rotation in the lowering direction', 'ASME B30.14 Sec. 1.2.2 (a4)'],
        '2.55' => ['The rope does not have more than 6 randomly distributed broken wires in 1 lay or 3 in 1 strand in 1 lay (for running ropes)', 'ASME B30.14 Sec. 2.4.2 (b1)'],
        '2.56' => ['The ropes does not have more than two broken wires in 1 lay in sections beyond end connections or more than 1 broken wire at an end connection (for standing', 'ASME B30.14 Sec. 2.4.2 (b6)'],
        '2.57' => ['The rope wear does not exceed 1/3 of the original diameter', 'ASME B30.14 Sec. 2.4.2 (b2)'],
        '2.58' => ['The rope does not have kinking, crushing, bird caging, evidence of heat damage, unstranding, core corrosion, main strand displacement or any other damages', 'ASME B30.14 Sec. 2.4.1 (a) Sec. 2.4.2 (b3)'],
        '2.59' => ['Boom hoist mechanism (raising and lowering) is properly operating', 'ASME B30.14 Sec. 1.2.1'],
        '2.60' => ['Boom hoist brake and clutch are correctly operating', 'ASME B30.14 Sec. 1.2.1 (a)'],
        '2.61' => ['Boom hoist brake and clutch have adjustments to compensate for wear', 'ASME B30.14 Sec. 1.2.1 (b)'],
        '2.62' => ['Locking pawl and ratchet is in good working condition to prevent inadvertent lowering of the boom', 'ASME B30.14 Sec. 1.2.1 (c)'],
        '2.63' => ['Two full wraps, at least, of rope remain on the drum when the hook is in its extreme low working position)', 'ASME B30.14 Sec. 1.2.2 (a2)'],
        '2.64' => ['Rope ends are anchored to the drum by clamps or as per manufacturer recommendations', 'ASME B30.14 Sec. 1.2.2 (a2)'],
        '2.65' => ['Drums are provided with a guidance or other means to prevent rope from jumping off the drum', 'ASME B30.14 Sec. 1.2.2 (a2)'],
        '2.66' => ['The rope does not have more than 6 randomly distributed broken wires in 1 lay or 3 in 1 strand in 1 lay (for running ropes)', 'ASME B30.14 Sec. 2.4.2 (b1)'],
        '2.67' => ['The ropes does not have more than two broken wires in 1 lay in sections beyond end connections or more than 1 broken wire at an end connection (for standing', 'ASME B30.14 Sec. 2.4.2 (b6)'],
        '2.68' => ['The rope wear does not exceed 1/3 of the original diameter', 'ASME B30.14 Sec. 2.4.2 (b2)'],
        '2.69' => ['The rope does not have kinking, crushing, bird caging, evidence of heat damage, unstranding, core corrosion, main strand displacement or any other damages', 'ASME B30.14 Sec. 2.4.1 (a) Sec. 2.4.2 (b3)'],
        '2.70' => ['Boom hoist cylinder and associated hydraulic system is operating correctly', 'ASME B30.14 Sec. 2.1.2 (c3)'],
        '2.71' => ['Boom hoist cylinder seals are not leaking', 'ASME B30.14 Sec. 2.1.2 (c3)'],
        '2.72' => ['Track shoes are all in place and not loose or broken', 'ASME B30.14 Sec. 2.1.3 (a, b, d)'],
        '2.73' => ['Track rollers and followers are turning and not loose, worn or seized', 'ASME B30.14 Sec. 2.1.3 (a, b, d)'],
        '2.74' => ['End tumblers are serviceable', 'ASME B30.14 Sec. 2.1.3 (a, b, d)'],
        '2.75' => ['Track tension is sufficient for ground conditions', 'ASME B30.14 Sec. 2.1.3 (g)'],
        '2.76' => ['Drive chain is not stretched excessively', 'ASME B30.14 Sec. 2.1.3 (g)'],
        '2.77' => ['Drive gearbox operates without excessive noise or leakage', 'ASME B30.14 Sec. 2.3.3 (1)'],
        '2.78' => ['End sprockets are not excessively worn at the teeth', 'ASME B30.14 S Sec. 2.1.3 (g)'],
        '2.79' => ['Frame to axle fastenings are secured and not loose', 'ASME B30.14 Sec. 2.1.3 (b)'],
        '2.80' => ['Stabilizer struts are undamaged and secure', 'ASME B30.14 Sec. 2.1.3 (g)'],
        '2.81' => ['Propelling forward is accepted', 'ASME B30.14 Sec. 2.2.2 (a)'],
        '2.82' => ['Propelling backward is accepted', 'ASME B30.14 Sec. 2.2.2 (a)'],
        '2.83' => ['Transmission gearbox functioning well and gears are operational', 'ASME B30.14 Sec. 1.4.3 (d)'],
        '2.84' => ['Steering to the right is operational', 'ASME B30.14 Sec. 2.1.3 (h)'],
        '2.85' => ['Steering to the left is operational', 'ASME B30.14 Sec. 2.1.3 (h)'],
        '2.86' => ['Travel locks are operational and remain in engagement', 'ASME B30.14 Sec. 2.1.3 (h)'],
        '2.87' => ['All safety limit devices are operational', 'ASME B30.14 Sec. 1.3.2'],
        '2.88' => ['Cab glass is made of safety glazing material', 'ASME B30.14 Sec. 1.6.1 (c)'],
        '2.89' => ['Screen wipers are in good operational condition', 'ASME B30.14 Sec. 1.6.1 (d)'],
        '2.90' => ['Road mirrors are fitted and undamaged', 'ASME B30.14 Sec. 1.6.1 (d)'],
        '2.91' => ['Fire extinguisher is available in the cab (minimum rating is 10BC)', 'ASME B30.14 Sec. 3.4.4'],
        '2.92' => ['Gauges and instrumentations are in good working condition', 'ASME B30.14 Sec. 2.1.2 (c7)'],
        '2.93' => ['Brakes are in working condition', 'ASME B30.14 Sec. 2.1.3 (e)'],
        '2.94' => ['Parking brake is in working condition', 'ASME B30.14 Sec. 2.1.3 (e)'],
        '2.95' => ['Clutch is in good working condition', 'ASME B30.14 Sec. 2.3.3'],
        '2.96' => ['Gear selector is properly working', 'ASME B30.14 Sec. 1.4.3 (d)'],
        '2.97' => ['Back-up alarm is operable', 'ASME B30.14 Sec. 2.1.2 (c2)'],
        '2.98' => ['Headlights are working', 'ASME B30.14 Sec. 2.1.2 (c2)'],
        '2.99' => ['Brake lights are working', 'ASME B30.14 Sec. 2.1.2 (c2)'],
    ],
    '3. INSPECTION POINTS' => [
        '3.0' => ['Back-up lights are working', 'ASME B30.14 Sec. 2.1.2 (c2)'],
        '3.1' => ['Turn signals are working', 'ASME B30.14 Sec. 2.1.2 (c2)'],
        '3.2' => ['Ignition system is operating correctly', 'ASME B30.14 Sec. 1.4.3 (a)'],
        '3.3' => ['Throttle is operating correctly', 'ASME B30.14 Sec. 1.4.3 (b)'],
        '3.4' => ['Battery is secure and ventilated', 'ASME B30.14 Sec. 2.1.3 (f)'],
        '3.5' => ['Gauges are in working condition', 'ASME B30.14 Sec. 2.1.2 (c7)'],
        '3.6' => ['Engine exhaust system is not corroded, and is guarded or insulated where necessary to prevent personal contact with hot surfaces', 'ASME B30.14 Sec. 1.7.2'],
        '3.7' => ['Oil level is sufficient', 'ASME B30.14 Sec. 2.3.1'],
        '3.8' => ['Torque converter is in working condition', 'ASME B30.14 Sec. 2.3.3'],
        '3.9' => ['Main transmission is in working condition', 'ASME B30.14 Sec. 2.3.3'],
        '3.10' => ['Auxiliary transmission is in working condition', 'ASME B30.14 Sec. 2.3.3'],
        '3.11' => ['Tires and rims are undamaged and serviceable', 'ASME B30.14 Sec. 2.1.3 (I)'],
        '3.12' => ['Outrigger beam sections are not corroded, cracked or damaged', 'ASME B30.14 Sec. 1.7.3 (a)'],
        '3.13' => ['Outrigger pads are securely fitted and undamaged', 'ASME B30.14 Sec. 1.7.3 (d)'],
        '3.14' => ['Pad pins are locked and undamaged', 'ASME B30.14 Sec. 1.7.3'],
        '3.15' => ['Extension cylinders are working correctly and undamaged', 'ASME B30.14 Sec. 1.7.3'],
        '3.16' => ['Vertical cylinders are working correctly and undamaged', 'ASME B30.14 Sec. 1.7.3'],
        '3.17' => ['Boom tip section is undamaged, has no cracks or distortion', 'ASME B30.14 Sec. 2.1.3 (a)'],
        '3.18' => ['Boom extension cylinder is properly working with no signs of leaks)', 'ASME B30.14 Sec. 2.1.2 (c3)'],
        '3.19' => ['Guide sheaves are not worn and are well lubricated', 'ASME B30.14 Sec. 2.1.3 (c)'],
        '3.20' => ['Main sheaves are smooth in their grooves and well lubricated', 'ASME B30.14 Sec. 1.5.4'],
        '3.21' => ['Bushings/bearings are not worn and are well lubricated', 'ASME B30.14 Sec. 2.1.3'],
        '3.22' => ['Guards are fitted and secured to cover exposed moving parts', 'ASME B30.14 Sec. 1.7.5'],
        '3.23' => ['Rope wedge anchor is correctly fitted and secure, and is fitted in accordance with the manufacturer’s recommendations', 'ASME B30.14 Sec. 1.5.3 (c)'],
        '3.24' => ['Counterweight(s) are securely fitted and locked in position on the operating arms', 'ASME B30.14 Sec. 3.4.1'],
        '3.25' => ['Operating rams are undamaged with no seal or hose leaks', 'ASME B30.14 Sec. 3.4.1'],
        '3.26' => ['Back stops are in place and secure', 'ASME B30.14 Sec. 3.4.1'],
        '3.27' => ['Complete counterweight and arm assembly shows no cracks or deformation', 'ASME B30.14 Sec. 3.4.1'],
    ],
];

$index = 0;
foreach ($sections as $sectionTitle => $items) {
    echo "<tr><td colspan='7' class='section'>" . htmlspecialchars($sectionTitle) . "</td></tr>";
    foreach ($items as $sn => $item) {
        $criteria = $item[0];
        $ref = $item[1];
        $result = $selected_results[$index] ?? '';
        $remark = $chek_remark[$index] ?? '';
        echo '<tr>';
        echo '<td>' . htmlspecialchars($sn) . '</td>';
        echo '<td>' . htmlspecialchars($criteria) . '</td>';
        echo '<td>' . htmlspecialchars($ref) . '</td>';
        echo '<td class="center">' . ($result == 'PASS' ? '✓' : '') . '</td>';
        echo '<td class="center">' . ($result == 'FAIL' ? '✓' : '') . '</td>';
        echo '<td class="center">' . ($result == 'NA' ? '✓' : '') . '</td>';
        echo '<td>' . htmlspecialchars($remark) . '</td>';
        echo '</tr>';
        $index++;
    }
}
?>
</tbody>
</table>

<br>

<div class="keep-together">

<table class="signature-table">
<thead>
<tr>
    <th colspan="2">REMARKS / RECOMMENDATIONS</th>
</tr>
</thead>
<tbody>
<tr>
    <td colspan="2" style="height: 80px; vertical-align: top;"><?= htmlspecialchars($recommendations ?? '') ?></td>
</tr>
</tbody>
</table>

<table class="signature-table">
<thead>
<tr>
    <th>INSPECTOR’S NAME</th>
    <th>CLIENT’S REP. NAME</th>
</tr>
</thead>
<tbody>
<tr>
    <td style="text-align: center; vertical-align: top;">
        <div class="signature-name"><?= htmlspecialchars($row['inspected_by'] ?? '') ?></div>
        <div class="signature-box">
            <?php if ($inspector_signature_path): ?>
                <img src="<?= $inspector_signature_path ?>" alt="Inspector Signature" style="max-width: 60px; max-height: 25px;">
            <?php else: ?>
                <div class="signature-placeholder">Signature</div>
            <?php endif; ?>
        </div>
    </td>
    <td style="text-align: center; vertical-align: top;">
        <div class="signature-name"><?= htmlspecialchars($client_name ?? '') ?></div>
        <div class="signature-box">
            <?php if ($client_signature_path): ?>
                <img src="<?= $client_signature_path ?>" alt="Client Signature" style="max-width: 60px; max-height: 25px;">
            <?php else: ?>
                <div class="signature-placeholder">Signature</div>
            <?php endif; ?>
        </div>
    </td>
</tr>
</tbody>
</table>

</div>

</body>
</html>