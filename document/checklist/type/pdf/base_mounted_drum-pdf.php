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
    <th>EQUIP. SERIAL NO</th>
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
    <th width="40%">ACCEPTANCE CRITERIA</th>
    <th width="8%">PASS</th>
    <th width="8%">FAIL</th>
    <th width="8%">NA</th>
    <th width="30%">REMARKS</th>
</tr>
</thead>
<tbody>
<?php
$sections = [
    [
        'number' => '1',
        'title' => 'RATINGS & MARKINGS',
        'ref' => '',
        'items' => [
            ['number' => '1.1', 'text' => 'Documentation is available'],
            ['number' => '1.2', 'text' => 'The winch rating is clearly marked'],
            ['number' => '1.3', 'text' => 'The following clearly marked: (a) drum size, consisting of barrel diameter, barrel length, and flange diameter; (b) rope diameter(s); (c) rope speed in meter per second at rated load on specified layer'],
            ['number' => '1.4', 'text' => 'Line pull ratings for each layer and a specified rope diameter are provided'],
        ],
    ],
    [
        'number' => '2',
        'title' => 'CONSTRUCTION',
        'ref' => '',
        'items' => [
            ['number' => '2.1', 'text' => 'Cooling, power, and operational characteristics are provided to perform'],
            ['number' => '2.2', 'text' => 'The rope is anchored to the drum approved by manufacturer'],
            ['number' => '2.3', 'text' => 'Drum flanges extends a minimum of one-half rope diameter, but not less than 0.5 inches (13mm) above the top layer'],
            ['number' => '2.4', 'text' => 'Diameter of the drum provides first layer rope pitch diameter of not less than 15 times the nominal diameter of the rope'],
            ['number' => '2.5', 'text' => 'Mechanical holding device, other than brake, capable of holding the rated load'],
            ['number' => '2.6', 'text' => 'Each brake is equipped with at least one brake capable of holding not less than 125% of the rated load at the point where the brake is applied'],
            ['number' => '2.7', 'text' => 'Remote-operated winches is equipped with self-setting brake'],
            ['number' => '2.8', 'text' => 'Winch is allowed simultaneous underwind & overwind brake capable of holding 125% of the rated load in those directions'],
            ['number' => '2.9', 'text' => 'Means to control the drum speed when moving the load is provided'],
            ['number' => '2.10', 'text' => 'Adjustments to compensate for wear in the braking system is provided'],
            ['number' => '2.11', 'text' => 'Guard(s) for exposed moving parts is provided'],
            ['number' => '2.12', 'text' => 'Guard(s) is capable of supporting the weight of a 200 lbs. (90 kg)'],
            ['number' => '2.13', 'text' => 'Rope size can withstand the minimum breaking force'],
            ['number' => '2.14', 'text' => 'Rope winch meets manufacturer or qualified person\'s recommendation'],
            ['number' => '2.15', 'text' => 'All controls are marked for identification of function and direction of the drum rotation'],
            ['number' => '2.16', 'text' => 'All winch controls are within reach of operator during the normal operating conditions'],
            ['number' => '2.17', 'text' => 'Electric motor-driven winches shall be provided with a device that will disconnect all motors from the power source in the event of a power failure'],
            ['number' => '2.18', 'text' => 'Remote operated winches shall function so that if the control signal becomes ineffective, winch motion shall stop'],
            ['number' => '2.19', 'text' => 'All prime mover controls shall return to neutral position when released'],
            ['number' => '2.20', 'text' => 'Engine-driven winches are provided with a clutch for disengaging power to winch'],
            ['number' => '2.21', 'text' => 'Lubrication points are accessible'],
        ],
    ],
    [
        'number' => '3',
        'title' => 'INSPECTION CRITERIA',
        'ref' => '',
        'items' => [
            ['number' => '3.1', 'text' => 'Rope is properly spooled on drum'],
            ['number' => '3.2', 'text' => 'Control mechanisms are operating properly'],
            ['number' => '3.3', 'text' => 'Limit switches are properly working'],
            ['number' => '3.4', 'text' => 'No deterioration or leakage in air or hydraulic is found'],
            ['number' => '3.5', 'text' => 'No indication of loose, damaged, or missing structural components including supports and anchorages'],
            ['number' => '3.6', 'text' => 'Electrical apparatus properly functioning, without signs of excessive deterioration, and no dirt accumulation'],
            ['number' => '3.7', 'text' => 'Control & drive mechanisms are properly adjusted and without excessive wear'],
            ['number' => '3.8', 'text' => 'No damage tubing, piping, electrical cables, or hoses, and their fittings'],
            ['number' => '3.9', 'text' => 'Pins, bearings, shafts, gears, rollers, and locking and clamping devices have no excessive wear, cracking, distortion, & corrosion'],
            ['number' => '3.10', 'text' => 'Brake & clutch system parts & linings have no excessive wear, sever distortion, & corrosion'],
            ['number' => '3.11', 'text' => 'Chain & chain drive sprockets have no excessive wear, & excessive chain stretch'],
            ['number' => '3.12', 'text' => 'Winch is operated by a qualified operator'],
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
    echo "<tr><td colspan='6' class='section'>" . htmlspecialchars($headerText) . "</td></tr>";
    
    // Print items under this section
    foreach ($section['items'] as $item) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($item['number']) . '</td>';
        echo '<td>' . htmlspecialchars($item['text']) . '</td>';
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
