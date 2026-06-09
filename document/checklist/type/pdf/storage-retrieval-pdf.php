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

<h2 style="text-align: center;">STORAGE RETRIEVAL <br>
ASME B30.13-2017</h2>

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
        ['Equipment documentation is available', 'ASME B30.13 sec.2.1.5'],
        ['Previous inspection reports are checked', 'ASME B30.13 sec.2.1.5'],
        ['Rated load is clearly marked and visible to the operator', 'CIMS-QHSE-06 (13.1.1.1)'],
        ['Warning and cautionary labels are affixed at aisle entrance points or access positions and are durable and legible', 'ASME B30.13 sec. 1.1.2'],
        ['Clearances and tolerances within the system are as determined by the manufacturer or user (specifications)', 'ASME B30.13 sec.1.2'],
        ['A fire extinguisher with minimum 10BC rating is available (in the cab)', 'ASME B30.13 sec..1.4.3'],
    ],
    '2. INSPECTION POINTS' => [
        ['Welded members and joints are free of defects, cracks and corrosion', 'ASME B30.13
Sec. 1.3.3'],
        ['Structures and supports of S/R machine are not cracked , corroded or deformed', 'ASME B30.13 
sec .2.1.3(a)'],
        ['Structures and supports of S/R machine are free of unusual vibrations', 'ASME B30.13 
sec. 1.3.2.1(a2)'],
        ['S/R machine rails are straight, leveled and properly joined', 'ASME B30.13 
Sec.1.3.2.1(a3)'],
        ['Stops are provided at the limits of travel of the S/R machine and aisle transfer car', 'ASME B30.13 Sec.1.3.2.1(b1),1.7.1'],
        ['Structure and S/R machine shows no loose bolts or rivets.', 'ASME B30.13 sec..2.1.3b'],
        ['All devices/controls required for operation are within convenient reach of operator', 'ASME B30.13 sec.1.4.1(a)'],
        ['The cab interior is free of knobs, edges or corners', 'ASME B30.13 sec.1.4.2(a)'],
        ['The cab door, if fitted, opens inward or slides and is self-closing with a positive latch', 'ASME B30.13 sec.1.4.2(c)'],
        ['Emergency exits to the floor are available for all positions of a carriage mounted cab', 'ASME B30.13 sec.1.4.2(d)'],
        ['All cab glazing is safety glazing material', 'ASME B30.13 sec.1.4.2(f)'],
        ['Cab lighting to be adequate (either natural or artificial) to enable the operator observe the controls', 'ASME B30.13 sec.1.4.4'],
        ['All ladders and platforms are secure and not corroded or damaged', 'ASME B30.13 sec.1.6.2'],
        ['Ladder access opening to platforms is 24"x 27" with hinged cover', 'ASME B30.13 sec.1.6.2,1.2.3'],
        ['Platforms have non-slip walking surfaces', 'ASME B30.13 sec.1.6.2(b)'],
        ['Bumpers provide required stop of an S/R machine or aisle transfer car travelling at rated load and speed from causing structural damage to the equipment', 'ASME B30.13 sec.1.7.2'],
        ['Runway interlocks are provided to prevent travel between the aisle and aisle transfer car unless the tracks are aligned', 'ASME B30.13 sec.1.7.3(a)'],
        ['Sweeps are fitted in front of the runway wheels', 'ASME B30.13 sec.1.7.4'],
        ['Guards for hoisting ropes or chains are fitted where appropriate to prevent chafing', 'ASME B30.13 sec.1.7.5'],
        ['Guards are fitted over moving parts such as gears, sprockets ,chains and ropes where these constitute a hazard', 'ASME B30.13 sec.1.7.5'],
        ['Holding brake exists (at least one) for each independent hoisting unit of the S/R machine (125% full load hoisting torque for non-mechanical brake and 100% for a mechanical one - holding brake shall be applied automatically when power to the brake is removed)', 'ASME B30.13 sec.1.8.1(a),
Sec.1.8.2(a)'],
        ['', ''],
        ['Holding brake is applied automatically when power to the brake is removed', 'ASME B30.13 sec.1.8.2(c)'],
        ['Control braking is capable of maintaining controlled travel or lowering speeds', 'ASME B30.13 sec.1.8.3'],
        ['Wearing surfaces of brake wheels, disks and drums are free of defects that could interfere with their operation', 'ASME B30.13 sec.1.8.4(d)'],
        ['The electrical cables outside of control enclosures are fully protected and insulated (S/R machine or transfer car)', 'ASME B30.13 sec.1.9.1(c2)'],
        ['Traveling cables are suspended at the carriage and S/R machine frame end as to reduce the strain on the individual conductors', 'ASME B30.13 sec.1.9.1(e)'],
        ['Supporting fillers are used for unsuspended travelling cable lengths exceeding 100ft (30m)', 'ASME B30.13 sec.1.9.1(e)'],
        ['The entire S/R machine is electrically grounded', 'ASME B30.13 sec.1.9.1(g)'],
        ['Any pendant control station is electrically grounded', 'ASME B30.13 sec.1.9.1(i)'],
        ['Live parts of electrical equipment are protected from direct exposure to grease, oil, dirt and moisture', 'ASME B30.13 sec.1.9.2(b)'],
        ['Any guards fitted over live parts are not deformed', 'ASME B30.13 sec.1.9.2(c)'],
        ['Power disconnect between the power supply and the aisle contact conductor or travelling cable is provided (motor circuit switch or breaker)', 'ASME B30.13 sec.1.9.3(a)'],
        ['Operation of limit sensors, which shut down any drive whose motion passes the extremity of designed travel, is satisfactory', 'ASME B30.13 sec.1.9.4(a)'],
        ['Operation of limit sensors where used to reduce speed prior to the machine reaching the extreme travel limit is satisfactory', 'ASME B30.13 sec.1.9.4(b)'],
        ['Hoist motion over speed device operate independently from all other power, drive and electrical systems (carriage mounted cab only)', 'ASME B30.13
sec.1.10.8(b)'],
        ['Hoist motion over speed device causes controlled descent of no more than 200% of the rated lowering speed and stops the carriage when lowering rated speed exceeds 200% (carriage mounted cab only)', 'ASME B30.13 
sec.1.10.8(c)'],
        ['Hoist motion over speed device operates when lowering rate speed exceeds 100 ft./min (0.5 m/s) or 150% of the rated lowering speed, whichever is greater (carriage mounted cab only)', 'ASME B30.13
sec.1.10.8(d)'],
        ['Over speed switch operation to stop descent of the carriage', 'ASME B30.13 sec.1.10.8(e)'],
        ['Actual over speed figure at which the device is set to operate is clearly marked on the device in letters at least 6mm high', 'ASME B30.13 sec.1.10.8(f)'],
        ['Over speed device is sealed to prevent readjustment of the trip speed', 'ASME B30.13 sec.1.10.8(g)'],
        ['Control voltages do not exceed 150V AC or 300V DC', 'ASME B30.13 sec.1.9.5'],
        ['Controls at operator\'s cab are within reach of the operator (for arms and legs)', 'ASME B30.13 sec.1.9.6'],
        ['Sequence of operation for the controls is verified (automatic control operating sequence)', 'ASME B30.13 sec.1.9.6'],
        ['Audio and visual warning devices are operable', 'ASME B30.13
sec.1.9.7
sec.2.1.2(b4)'],
        ['Emergency stop switch(es) are in good working condition', 'ASME B30.13 sec.2.1.3(g)'],
        ['Electrical overload or power failure sensors are fitted', 'ASME B30.13 sec.1.9.8a4-b3'],
        ['Emergency stop actuator(s) in the aisle(s) are operable', 'ASME B30.13 sec.1.9.8(e)'],
        ['Correct sequence of operation under automatic and remote control of S/R machine and aisle transfer car is verified (In auto mode all motion is discontinued if the sequence is interrupted, or the last command is permissible if power is available. In remote mode if the signal is interrupted the machine stops)', 'ASME B30.13 sec.1.9.9'],
        ['Sheave grooves are smooth with no surface defects', 'ASME B30.13 sec.1.10.1(a)(1)'],
        ['Close fitting rope guides or guards are fitted where required to prevent momentary unloading of the rope', 'ASME B30.13 sec.1.10.1(b)'],
        ['Sheaves have means of lubrication or are permanently lubricated', 'ASME B30.13 sec.1.10.1(d)'],
        ['Sheave pitch diameter is not less than 20 times the rope diameter', 'ASME B30.13 sec.1.10.1(e)'],
        ['Rope drums are free from surface defects that could cause rope damage', 'ASME B30.13 sec.1.10.2'],
        ['Rope end socket assemblies are undamaged and are to the manufacturer\'s specification (where fitted)', 'ASME B30.13 sec.1.10.3(b)'],
        ['Two wraps of rope remains on the drum (as a minimum) when the carriage is in the extreme low position', 'ASME B30.13 sec.1.10.3(c1)'],
        ['Rope is correctly clamped to the drum (or with a socket arrangement) as per the rope or S/R machine manufacturers recommendations', 'ASME B30.13 sec.1.10.3(c2)'],
        ['Rope is free of damages
•	Max of 12 randomly broken wires in 1 lay
•	4 broken wires in 1 strand of 1 lay
•	1 broken wire protruding from the core (2 for rotation resistant ropes)
•	Wear of 1/3 of the original diameter of outside individual wires
Kinking, crushing, bird caging or other distortion', 'ASME B30.13 sec.2.4.1a1(c)
sec.2.4.2(b2)'],
        ['Sprocketed wheels and chain spockets are free from surface defects', 'ASME B30.13 sec.1.10.4(a)
sec.2.1.3(c)'],
        ['Sprockets, pocket wheels or running chains are adequately lubricated.', 'ASME B30.13 sec.1.10.4(c)'],
        ['All lines, tanks, valves, pumps, motors and other parts of fluid systems are not leaking', 'ASME B30.13 sec.2.1.2(a3/b3)'],
        ['Bearings, shafts, gears and rollers are not worn, cracked or distorted', 'ASME B30.13 sec.2.1.3(d)'],
        ['Rope equalizer pulley is free to turn and undamaged (if fitted)', 'ASME B30.13 sec.1.10.6'],
        ['Carriage free fall stops are in place (can be activated mechanically by simulating a slack rope or chain condition)', 'ASME B30.13 sec.1.10.7(a)'],
        ['Lifting and lowering function of the cab and carriage is satisfactory', 'ASME B30.13 sec.2.2.1(a1)'],
        ['Horizontal travel function of the machine is satisfactory', 'ASME B30.13 sec.2.2.1(a2)'],
        ['Shuttle function of the machine is satisfactory', 'ASME B30.13 sec.2.2.1(a3)'],
        ['All moving parts of the S/R machine or aisle transfer car for which lubrication is specified, including rope and chain are lubricated', 'ASME B30.13 sec.2.3.4(a)'],
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