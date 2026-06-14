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


<h4 style="text-align: center;">ARTICULATING BOOM CRANES <br/>
        ASME B30.22-2016</h4>

        



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
    <th>CRANE ASSET NO</th>
    <td style="text-align:center;"><?= htmlspecialchars($row['equipment_no'] ?? '') ?></td>
    <th>CRANE SERIAL NO</th>
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
    [
        'number' => '1',
        'title' => 'GENERAL REQUIREMENTS',
        'ref' => '',
        'items' => [
            ['number' => '1.1', 'text' => 'Equipment Documentation is available', 'ref' => 'CIMS QHSE 06'],
            ['number' => '1.2', 'text' => 'Previous Inspection reports are checked', 'ref' => 'CIMS QHSE 06'],
            ['number' => '1.3', 'text' => 'Warning signs, cautions and safety labels are provided and in place', 'ref' => 'ASME B30.22 Sec. 22-2.1.4n'],
            ['number' => '1.4', 'text' => 'Crane manufacturer data label, name, address and serial number are marked or tagged', 'ref' => 'ASME B30.22 Sec. 22-2.1.4n'],
            ['number' => '1.5', 'text' => 'Rated capacity of the crane is marked', 'ref' => 'ASME B30.22 Sec. 22-1.1.3a'],
            ['number' => '1.6', 'text' => 'A sign is posted warning the operator not to rely solely on any automatic device as a substitute for safe operating practice', 'ref' => 'CIMS QHSE 06'],
            ['number' => '1.7', 'text' => 'Load rating chart/range diagram are provided (contains all safe operating conditions as per manufacturer)', 'ref' => 'ASME B30.22 Sec. 22-1.1.3a'],
        ],
    ],
    [
        'number' => '1.8',
        'title' => 'OPERATIONAL AIDS:',
        'ref' => 'ASME B30.22 sec 1.8.2',
        'items' => [
            ['number' => '1.8.1', 'text' => 'Two-block damage prevention system or anti-two block device.', 'ref' => 'ASME B30.22 sec 1.8.2.1'],
            ['number' => '1.8.2', 'text' => 'Overload protection system or rated capacity limiters.', 'ref' => 'ASME B30.22 sec 1.8.2.2'],
            ['number' => '1.8.3', 'text' => 'Crane Level Indicator.', 'ref' => 'ASME B30.22 sec 1.8.2.3'],
            ['number' => '1.8.4', 'text' => 'Load indicator, rated capacity indicator, minimum wrap limiter.', 'ref' => 'ASME B30.22 sec 3.2.2 (b)(2)'],
        ],
    ],
    [
        'number' => '2',
        'title' => 'INSPECTION POINTS:',
        'ref' => '',
        'items' => [
            ['number' => '2.1', 'text' => 'Boom cylinder is properly working', 'ref' => 'ASME B30.22 Sec 22-1.2.1(a)'],
            ['number' => '2.2', 'text' => 'Boom cylinder holding valve is in good working condition', 'ref' => 'ASME B30.22 Sec 22-1.2.1(b)'],
            ['number' => '2.3', 'text' => 'Boom cylinder hoses are not leaking', 'ref' => 'ASME B30.22 Sec 22-2.1.4(i)(1)'],
            ['number' => '2.4', 'text' => 'Boom cylinder hoses are not deformed', 'ref' => 'ASME B30.22 Sec 22-2.1.4(i)(2)'],
            ['number' => '2.5', 'text' => 'Boom has no signs of wear, cracks or distorted parts', 'ref' => 'ASME B30.22 Sec 22-2.1.4(d)'],
            ['number' => '2.6', 'text' => 'Boom telescope cylinder is working properly', 'ref' => 'ASME B30.22 Sec. 22-1.2.2(a)'],
            ['number' => '2.7', 'text' => 'Boom telescope cylinder holding valve is in good working condition', 'ref' => 'ASME B30.22 Sec. 22-1.2.2(c)'],
            ['number' => '2.8', 'text' => 'Boom telescope cylinder hoses are not leaking', 'ref' => 'ASME B30.22 Sec. 22-2.1.4(i)(1)'],
            ['number' => '2.9', 'text' => 'Boom telescope cylinder hoses are not deformed', 'ref' => 'ASME B30.22 Sec. 22-2.1.4(i)(2)'],
            ['number' => '2.10', 'text' => 'Boom telescope has no signs of wear, cracks or distorted parts', 'ref' => 'ASME B30.22 Sec. 22-2.1.4(d)'],
            ['number' => '2.11', 'text' => 'Hoist brake is functioning well', 'ref' => 'ASME B30.22 Sec. 22-1.2.3(b)(1)'],
            ['number' => '2.12', 'text' => 'Hoist rope size is as per manufacturer specification', 'ref' => 'ASME B30.22 Sec. 22-1.2.3(b)(2)'],
            ['number' => '2.13', 'text' => 'Minimum of two full rope wraps remains in the drum when the hook at its extreme low position', 'ref' => 'ASME B30.22 Sec. 22-1.2.3(b)(2)(a)'],
            ['number' => '2.14', 'text' => 'Rope end is anchored to the drum as per crane or winch manufacturer', 'ref' => 'ASME B30.22 Sec. 22-1.2.3(b)(2)(b)'],
            ['number' => '2.15', 'text' => 'Rope eye splices are made as per manufacturer recommendations', 'ref' => 'ASME B30.22 Sec. 22-1.5.3(a)'],
            ['number' => '2.16', 'text' => 'Wire rope clips used in conjunction with wedge sockets are attached to the unloaded dead end of the rope only', 'ref' => 'ASME B30.22 Sec. 22-1.5.3(d)'],
            ['number' => '2.17', 'text' => 'Swing mechanism starts and stops with controlled accelerations and deceleration', 'ref' => 'ASME B30.22 Sec. 22-1.3.1'],
            ['number' => '2.18', 'text' => 'Swing brake and locking devices are in good working condition', 'ref' => 'ASME B30.22 Sec. 22-1.3.2(a)'],
            ['number' => '2.19', 'text' => 'A positive locking device or boom support is provided to prevent the boom from rotating when in stowed position for transit', 'ref' => 'ASME B30.22 Sec. 22-1.3.2(b)'],
            ['number' => '2.20', 'text' => 'Sheave grooves are free of surface defects', 'ref' => 'ASME B30.22 Sec. 22-1.5.4(a)'],
            ['number' => '2.21', 'text' => 'Sheave guards are provided and in good condition', 'ref' => 'ASME B30.22 Sec. 22-1.5.4(b)'],
            ['number' => '2.22', 'text' => 'Sheave bearings are provided with lubrication points (except for self-lubricating bearings)', 'ref' => 'ASME B30.22 Sec. 22-1.5.4(d)'],
            ['number' => '2.23', 'text' => 'Load hoisting sheaves have pitch diameters of not less than 18 times the nominal diameter of the rope used', 'ref' => 'ASME B30.22 Sec. 22-1.5.5(a)'],
            ['number' => '2.24', 'text' => 'Lower load block sheaves have pitch diameters of not less than 16 times the nominal diameter of the rope used', 'ref' => 'ASME B30.22 Sec. 22-1.5.5(b)'],
            ['number' => '2.25', 'text' => 'Boom extension system sheaves have a pitch diameter of not less than 15 times the nominal diameter of the rope', 'ref' => 'ASME B30.22 Sec. 22-1.5.5(c)'],
            ['number' => '2.26', 'text' => 'Labeling and manufacturer data are available and legible', 'ref' => 'ASME B30.10 (10-2.1.1)'],
            ['number' => '2.27', 'text' => 'Hook weight is marked', 'ref' => 'ASME B30.10 Sec. 10-1.1.1'],
            ['number' => '2.28', 'text' => 'Safe working load is clearly marked on the hook', 'ref' => 'ASME B30.10 (10-2.1.1)'],
            ['number' => '2.29', 'text' => 'Hook is not bent or twisted', 'ref' => 'ASME B30.10 (10-1.2.1.3c1)'],
            ['number' => '2.30', 'text' => 'Hook is not distorted in the throat opening', 'ref' => 'ASME B30.10 (1.2.1.3c2)'],
            ['number' => '2.31', 'text' => 'Maximum wear in the hook bowl is not exceeding 10%', 'ref' => 'ASME B30.10 (10-1.2.1.3c3)'],
            ['number' => '2.32', 'text' => 'Hook is not cracked, gouged or shows nicks', 'ref' => 'ASME B30.10 (10-1.2.1.2c3)'],
            ['number' => '2.33', 'text' => 'Hook can lock (if it is a self-locking hook)', 'ref' => 'ASME B30.10 (10-1.2.1.3c4)'],
            ['number' => '2.34', 'text' => 'Hook latch is operative', 'ref' => 'ASME B30.10 (10-1.2.1.3c5)'],
            ['number' => '2.35', 'text' => 'All controls are labeled and identified', 'ref' => 'ASME B30.22 Sec. 22-1.5.6.1(a)'],
            ['number' => '2.36', 'text' => 'All controls are functioning properly', 'ref' => 'ASME B30.22 Sec. 22-2.1.3(a)'],
            ['number' => '2.37', 'text' => 'All control levers return to neutral position when force is removed', 'ref' => 'ASME B30.22 Sec. 22.1.5.6.1(b)'],
            ['number' => '2.38', 'text' => 'Stabilizers extension cylinder holding valve is not passing', 'ref' => 'ASME B30.22 Sec. 22-1.8.4(c)'],
            ['number' => '2.39', 'text' => 'Stabilizer cylinder hoses are not leaking', 'ref' => 'ASME B30.22 Sec. 22-2.1.4(i)(1)'],
            ['number' => '2.40', 'text' => 'Stabilizer cylinder hoses are not deformed', 'ref' => 'ASME B30.22 Sec. 22-2.1.4(i)(2)'],
            ['number' => '2.41', 'text' => 'Stabilizers do not have worn, cracked or distorted parts', 'ref' => 'ASME B30.22 Sec. 22-2.1.4(d)'],
            ['number' => '2.42', 'text' => 'Rope is free of corrosion, kinking, crushing, birdcaging, unstranding, main strand displacement or core protrusion', 'ref' => 'ASME B30.22 Sec. 22-2.4.2(a)(1a)'],
            ['number' => '2.43', 'text' => 'Rope and connections are free of corrosion, bends, cracks and wear', 'ref' => 'ASME B30.22 Sec. 22-2.4.2(b-2d)'],
            ['number' => '2.44', 'text' => 'Rope has no broken or cut strands (In running ropes)', 'ref' => 'ASME B30.22 Sec. 22-2.4.3(b 1-7)'],
            ['number' => '2.45', 'text' => 'In standing ropes: more than two broken wires in one lay', 'ref' => 'ASME B30.22 Sec. 22-2.4.3(b 1-7)'],
            ['number' => '2.46', 'text' => 'Rope has no signs of reduction in diameter', 'ref' => 'ASME B30.22 Sec. 22-2.4.2(b-2b)'],
        ],
    ],
    [
        'number' => '3',
        'title' => 'GENERAL INSPECTION POINTS',
        'ref' => '',
        'items' => [
            ['number' => '3.1', 'text' => 'Hydraulic/pneumatic motors have no loose bolts or fasteners', 'ref' => 'ASME B30.22 Sec. 22-2.1.4(j-1)'],
            ['number' => '3.2', 'text' => 'Hydraulic/pneumatic motors have no leaks', 'ref' => 'ASME B30.22 Sec. 22-2.1.4(j2,3)'],
            ['number' => '3.3', 'text' => 'Hydraulic/pneumatic motors are free of unusual noises and vibrations', 'ref' => 'ASME B30.22 Sec. 22-2.1.4(j4)'],
            ['number' => '3.4', 'text' => 'Hydraulic/pneumatic motors are not overheating', 'ref' => 'ASME B30.22 Sec. 22-2.1.4(j6)'],
            ['number' => '3.5', 'text' => 'Hydraulic/pneumatic motors do not loose pressure', 'ref' => 'ASME B30.22 Sec. 22-2.1.4(j7)'],
            ['number' => '3.6', 'text' => 'Hydraulic/pneumatic valves are not leaking', 'ref' => 'ASME B30.22 Sec. 22-2.1.4(k3)'],
            ['number' => '3.7', 'text' => 'Hydraulic/pneumatic valve housings are not cracked', 'ref' => 'ASME B30.22 Sec. 22-2.1.4(k1)'],
            ['number' => '3.8', 'text' => 'Hydraulic/pneumatic relieve valves are maintaining the set pressure', 'ref' => 'ASME B30.22 Sec. 22-2.1.4(k5)'],
            ['number' => '3.9', 'text' => 'Hydraulic/pneumatic cylinder are not leaking at seals', 'ref' => 'ASME B30.22 Sec. 22-2.1.4(l2)'],
            ['number' => '3.10', 'text' => 'Hydraulic/pneumatic cylinder are not leaking at welded joints', 'ref' => 'ASME B30.22 Sec. 22-2.1.4(l3)'],
            ['number' => '3.11', 'text' => 'Hydraulic/pneumatic cylinder case is free of damage', 'ref' => 'ASME B30.22 Sec. 22-2.1.4(l5)'],
            ['number' => '3.12', 'text' => 'Hydraulic/pneumatic cylinders have no loose or deformed rod eyes or connecting joints', 'ref' => 'ASME B30.22 Sec. 22-2.1.4(l6)'],
        ],
    ],
];

$index = 0;
foreach ($sections as $section) {
    // Print section header
    $headerText = $section['number'] . ' ' . $section['title'];
    if ($section['ref']) {
        $headerText .= ' - ' . $section['ref'];
    }
    echo "<tr><td colspan='7' class='section'>" . htmlspecialchars($headerText) . "</td></tr>";
    
    // Print items under this section
    foreach ($section['items'] as $item) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($item['number']) . '</td>';
        echo '<td>' . htmlspecialchars($item['text']) . '</td>';
        echo '<td>' . htmlspecialchars($item['ref']) . '</td>';
        echo '<td class="center">' . pdf_mark_result($index, 'PASS', $selected_results) . '</td>';
        echo '<td class="center">' . pdf_mark_result($index, 'FAIL', $selected_results) . '</td>';
        echo '<td class="center">' . pdf_mark_result($index, 'NA', $selected_results) . '</td>';
        echo '<td>' . htmlspecialchars($chek_remark[$index] ?? '') . '</td>';
        echo '</tr>';
        $index++;
    }
}
?>
</tbody>
</table>

<br>

<pagebreak />
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
                    <img src="<?= htmlspecialchars($inspector_signature_path) ?>" alt="Inspector Signature" style="max-width: 50px; max-height: 25px;">
                <?php else : ?>
                    <div class="signature-placeholder">Signature Not Available</div>
                <?php endif; ?>
            </div>
        </td>
        <td>
            <div class="signature-name"><?= htmlspecialchars($client_name) ?></div>
            <div class="signature-box">
                <?php if ($client_signature_path && file_exists($client_signature_path)) : ?>
                    <img src="<?= htmlspecialchars($client_signature_path) ?>" alt="Client Signature" style="max-width: 50px; max-height: 25px;">
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
