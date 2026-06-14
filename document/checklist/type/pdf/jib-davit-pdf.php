<?php
include_once(__DIR__ . '/_bootstrap.php');

$project_no = $row['project_no'] ?? '';
$inspector_signature_path = pdf_signature_path($row['inspected_by'] ?? '');
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

.title-section {
    text-align: center;
    font-weight: bold;
    font-size: 11px;
    margin: 10px 0;
}

.footer-section {
    page-break-inside: avoid;
}

.keep-together {
    page-break-inside: avoid;
    break-inside: avoid;
}
</style>
</head>
<body>

<div class="title-section">
JIB CRANES & DAVITS<br>
ASME B30.10,ASME B30.11
</div>

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
    'GENERAL REQUIREMENTS' => [
        [
            'num' => '1.1',
            'item' => 'Documentation is available',
            'reference' => 'ASME B30.11',
        ],
        [
            'num' => '1.2',
            'item' => 'Equipment number is clearly marked for identification purposes.',
            'reference' => 'ASME B30.11',
        ],
        [
            'num' => '1.3',
            'item' => 'Crane is painted safety yellow',
            'reference' => 'ASME B30.11',
        ],
        [
            'num' => '1.4',
            'item' => 'Crane is painted safety yellow & black stripes for offshore.',
            'reference' => 'ASME B30.11',
        ],
        [
            'num' => '1.5',
            'item' => 'Safe Working Load (SWL) is clearly marked on the runway beam',
            'reference' => 'ASME B30.11',
        ],
        [
            'num' => '1.6',
            'item' => 'Pneumatic/electric control valves & switches are in good condition. No leaks are visible.',
            'reference' => 'ASME B30.11',
        ],
        [
            'num' => '1.7',
            'item' => 'Hoist & swing drives are capable of starts & stops with variable acceleration and deceleration required on normal operation',
            'reference' => 'ASME B30.11',
        ],
        [
            'num' => '1.8',
            'item' => 'Hoist drum specifications are marked (rated load, drum size, rope size, rope speed (ft/min. or m/s), rate power).',
            'reference' => 'ASME B30.11',
        ],
        [
            'num' => '1.9',
            'item' => 'Hand chain hoist: manufacturer data, serial number, safe working load are clearly marked/displayed.',
            'reference' => 'ASME B30.11',
        ],
        [
            'num' => '1.10',
            'item' => 'Electric hoist: manufacturer data, serial number, safe working load, voltage and phase are clearly marked/displayed.',
            'reference' => 'ASME B30.11',
        ],
        [
            'num' => '1.11',
            'item' => 'Pneumatic hoist: manufacturer data, serial number, safe working load, rated air pressure are clearly marked/displayed.',
            'reference' => 'ASME B30.11',
        ],
        [
            'num' => '1.12',
            'item' => 'Warning signs/labels are provided on the hoist units and electrical enclosures',
            'reference' => 'ASME B30.11',
        ],
        [
            'num' => '1.13',
            'item' => 'Structure is vibration free under normal condition.',
            'reference' => 'ASME B30.11',
        ],
        [
            'num' => '1.14',
            'item' => 'Jib crane end stop(s) is installed and in good condition.',
            'reference' => 'ASME B30.11',
        ],
        [
            'num' => '1.15',
            'item' => 'Tracks area properly installed and aligned',
            'reference' => 'ASME B30.11',
        ],
        [
            'num' => '1.16',
            'item' => 'Crane runway is fastened and secured to a supporting structure',
            'reference' => 'ASME B30.11',
        ],
        [
            'num' => '1.17',
            'item' => 'All welded members are free of defects and not corroded',
            'reference' => 'ASME B30.11',
        ],
        [
            'num' => '1.18',
            'item' => 'Air powered hoist: Braking system will stop and hold the load hook when controls are released under any load.',
            'reference' => 'ASME B30.11',
        ],
        [
            'num' => '1.19',
            'item' => 'An air hoist stops and holds the load block in the event of air pressure loss.',
            'reference' => 'ASME B30.11',
        ],
        [
            'num' => '1.20',
            'item' => 'Braking system has means for adjustment to compensate for wear.',
            'reference' => 'ASME B30.11',
        ],
        [
            'num' => '1.21',
            'item' => 'Air Powered Hoist: load block is of the enclosed type and means is provided to guard against rope or load chain jamming in the load block',
            'reference' => 'ASME B30.11',
        ],
        [
            'num' => '1.22',
            'item' => 'Rope termination is completed at the hoist wedge anchor with a drop forged U-clip.',
            'reference' => 'ASME B30.11',
        ],
        [
            'num' => '1.23',
            'item' => 'Rope is free of damaged: * Maximum of 12 randomly broken wires in 1 lay. * 4 Broken wires in 1 strand in one lay * 1 Broken wire protruding from the core (2 for rotation resistant ropes) * Wear of 1/3 of the original diameter of outside individual wire. * Kinking, crushing, birdcaging, or other distortion.',
            'reference' => 'ASME B30.11',
        ],
        [
            'num' => '1.24',
            'item' => 'A rope thimble is used in the eye when an eye splice is used in a rope termination (in accordance with the manufacturer\'s instruction.',
            'reference' => 'ASME B30.11',
        ],
        [
            'num' => '1.25',
            'item' => 'Air powered hoists: Rope drum is grooved and free of surface defects that could cause rope damage (excluding hoists made for special applications)',
            'reference' => 'ASME B30.11',
        ],
        [
            'num' => '1.26',
            'item' => 'Hoist drum is adequately lubricated as per the hoist manufacturer\'s manual.',
            'reference' => 'ASME B30.11',
        ],
        [
            'num' => '1.27',
            'item' => 'Drum capacity can accommodate the specific rope size and length',
            'reference' => 'ASME B30.11',
        ],
        [
            'num' => '1.28',
            'item' => 'Drum has a minimum of two wrap on it.',
            'reference' => 'ASME B30.11',
        ],
        [
            'num' => '1.29',
            'item' => 'Each drum end of the rope is anchored by a clamp attached to the drum or by a socket arrangement (approved by the manufacturer)',
            'reference' => 'ASME B30.11',
        ],
        [
            'num' => '1.30',
            'item' => 'Drum flanges always extend a minimum of 1/2\" (13 mm) above the top layer of rope at all times.',
            'reference' => 'ASME B30.11',
        ],
        [
            'num' => '1.31',
            'item' => 'Hook is not bent or twisted * maximum bending or twisting not to exceed 10 degrees from plane of unbent hook or as per manufacturer.',
            'reference' => 'ASME B30.10 ASME B30.11',
        ],
        [
            'num' => '1.32',
            'item' => 'Hook is not distorted from the throat opening * Max allowable throat opening is 15% compared to new hook, or as per manufacturer recommendation.',
            'reference' => 'ASME B30.10 ASME B30.11',
        ],
        [
            'num' => '1.33',
            'item' => 'Maximum wear in the hook bowl is not exceeding 10% compared to new hook or as per manufacturer',
            'reference' => 'ASME B30.10 ASME B30.11',
        ],
        [
            'num' => '1.34',
            'item' => 'Hook is not cracked, gouged, or shows nicks',
            'reference' => 'ASME B30.10 ASME B30.11',
        ],
        [
            'num' => '1.35',
            'item' => 'Gangway handrail is free of defects.',
            'reference' => 'ASME B30.11',
        ],
        [
            'num' => '1.36',
            'item' => 'No defects on hook anchor points.',
            'reference' => 'ASME B30.10 ASME B30.11',
        ],
        [
            'num' => '1.37',
            'item' => 'Lower roller & bearings not defective nor corroded.',
            'reference' => 'ASME B30.11',
        ],
        [
            'num' => '1.38',
            'item' => 'Stairs & frames are free from defects and corrosion.',
            'reference' => 'ASME B30.11',
        ],
    ],
];

$index = 0;
foreach ($sections as $sectionTitle => $items) {
    echo "<tr><td colspan='7' class='section'>" . htmlspecialchars($sectionTitle) . "</td></tr>";
    foreach ($items as $itemData) {
        $sn = $itemData['num'];
        $item = $itemData['item'];
        $reference = $itemData['reference'];
        
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
    }
}
?>
</tbody>
</table>

<br>

<div class="footer-section">
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
</div>

</body>
</html>
