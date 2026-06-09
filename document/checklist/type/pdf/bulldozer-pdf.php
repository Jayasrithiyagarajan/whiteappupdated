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
            ['number' => '1.2', 'text' => 'Equipment asset ID Number is prominently marked'],
            ['number' => '1.3', 'text' => 'Nameplate, caution, and instruction markings are available on the equipment'],
            ['number' => '1.4', 'text' => 'All controls are marked for identification of function'],
            ['number' => '1.5', 'text' => 'All safety & warning decals are posted'],
            ['number' => '1.6', 'text' => 'Nameplate (Manufacturer, capacity, serial number, and model) of Blade, Ripper, Towing winch are available'],
            ['number' => '1.7', 'text' => 'The machine is operated by Qualified Operator'],
            ['number' => '1.8', 'text' => 'Fire extinguisher is provided and has valid inspection tag'],
        ],
    ],
    [
        'number' => '2',
        'title' => 'VISUAL INSPECTION & FUNCTIONAL TEST',
        'ref' => '',
        'items' => [
            ['number' => '2.1', 'text' => 'Cab Mirrors, Glasses, etc. condition'],
            ['number' => '2.2', 'text' => 'Blade, ripper, towing winch are in good condition'],
            ['number' => '2.3', 'text' => 'No excessive corrosion on frames, anchorages, structures are present'],
            ['number' => '2.4', 'text' => 'Lift cylinders are operating correctly & without hydraulic oil leaks'],
            ['number' => '2.5', 'text' => 'Steering controls are operating correctly'],
            ['number' => '2.6', 'text' => 'ROPS or overhead guard is provided and can withstand the drop test based on the applicable table or rated capacity'],
            ['number' => '2.7', 'text' => 'Track Chain/Link condition is good'],
            ['number' => '2.8', 'text' => 'Track Grouser Plates condition is good'],
            ['number' => '2.9', 'text' => 'Track Sprocket and Rollers condition is good'],
            ['number' => '2.10', 'text' => 'Track Idler condition is good'],
            ['number' => '2.11', 'text' => 'Safety belt is provided'],
            ['number' => '2.12', 'text' => 'All control levers are within reach of operator during the normal operating conditions'],
            ['number' => '2.13', 'text' => 'All hydraulic hoses are free of tears, and no signs of leaks on their hose fittings'],
            ['number' => '2.14', 'text' => 'Hydraulic oil tank level is correct and tank is securely fastened, and no signs of oil leakages'],
            ['number' => '2.15', 'text' => 'Fuel tank is secured & not leaking'],
            ['number' => '2.16', 'text' => 'Steering & transmission oil levels are correct & not leaking'],
            ['number' => '2.17', 'text' => 'Lubrication points are accessible'],
            ['number' => '2.18', 'text' => 'No deterioration of Hydraulic hoses & fittings or leakage of oil'],
            ['number' => '2.19', 'text' => 'Access and Step Ladders'],
            ['number' => '2.20', 'text' => 'No indication of loose, damaged, or missing components including supports and anchorages on under carriage'],
            ['number' => '2.21', 'text' => 'Control & drive mechanisms are properly adjusted and without excessive wear'],
            ['number' => '2.22', 'text' => 'Seat and back cushions are not torn'],
            ['number' => '2.23', 'text' => 'No damage tubing, piping, electrical cables, or hoses, and their fittings'],
            ['number' => '2.24', 'text' => 'Front & Rear Windshields are in good condition & Wiper Motor Assembly is working'],
            ['number' => '2.25', 'text' => 'Limit Switches are properly working'],
        ],
    ],
    [
        'number' => '3',
        'title' => 'ENGINE & ELECTRICAL SYSTEM',
        'ref' => '',
        'items' => [
            ['number' => '3.1', 'text' => 'Engine has no excessive smoke, & engine oil leak'],
            ['number' => '3.2', 'text' => 'Fuel is not leaking'],
            ['number' => '3.3', 'text' => 'Engine has no loss of power'],
            ['number' => '3.4', 'text' => 'Fan, Alternator, & steering belts tension are not loose'],
            ['number' => '3.5', 'text' => 'Instrument Panel Indicator Lights are functioning correctly'],
            ['number' => '3.6', 'text' => 'Strobe light or rotating beacon light is working'],
            ['number' => '3.7', 'text' => 'Head light & working lights are not broken and are functioning correctly'],
            ['number' => '3.8', 'text' => 'Brake lights are working'],
            ['number' => '3.9', 'text' => 'Back-Up (Reverse) Light and alarm are working'],
            ['number' => '3.10', 'text' => 'Horn is working'],
            ['number' => '3.11', 'text' => 'Battery water/electrolyte level is correct'],
            ['number' => '3.12', 'text' => 'Radiator coolant level is correct and no sign of water leakage'],
            ['number' => '3.13', 'text' => 'Housekeeping is satisfactory'],
        ],
    ],
    [
        'number' => '4',
        'title' => 'ATTACHMENTS/IMPLEMENTS',
        'ref' => '',
        'items' => [
            ['number' => '4.1', 'text' => 'Blade condition is good'],
            ['number' => '4.2', 'text' => 'Ripper condition is good'],
            ['number' => '4.3', 'text' => 'Towing winch condition is good'],
            ['number' => '4.4', 'text' => 'Towing hook condition is good'],
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
