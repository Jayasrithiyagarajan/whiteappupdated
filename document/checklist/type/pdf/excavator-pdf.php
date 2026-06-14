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
INSPECTION CHECKLIST FOR EXCAVATOR<br>
FRM.0601-2.1 (rev.01)<br>
HYDRAULIC EXCAVATOR (EARTH MOVING MACHINERY)<br>
DS/EN 474-1:2006+A6:2019
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
    <th>MANUFACTURER</th>
    <td style="text-align:center;"><?= htmlspecialchars($row['manufacturer'] ?? '') ?></td>
</tr>
<tr>
    <th>MODEL NO.</th>
    <td style="text-align:center;"><?= htmlspecialchars($row['model_no'] ?? '') ?></td>
    <th>YEAR MODEL</th>
    <td style="text-align:center;"><?= htmlspecialchars($row['year_model'] ?? '') ?></td>
</tr>
<tr>
    <th>CAPACITY (SWL)</th>
    <td style="text-align:center;"><?= htmlspecialchars($row['capacity_swl'] ?? '') ?></td>
    <th></th>
    <td></td>
</tr>
</table>

<br>

<table>
<thead>
<tr>
    <th width="6%">S.N</th>
    <th width="44%">ACCEPTANCE CRITERIA</th>
    <th width="8%">PASS</th>
    <th width="8%">FAIL</th>
    <th width="8%">NA</th>
    <th width="26%">REMARKS</th>
</tr>
</thead>
<tbody>
<?php
$sections = [
    '1. RATINGS & MARKINGS' => [
        'Documentation is available.',
        'Equipment asset ID Number is prominently marked.',
        'Nameplate, caution, and instruction markings are available on the equipment.',
        'All controls are marked for identification of function.',
        'All safety & warning decals are posted.',
        'Nameplate (Manufacturer, capacity, serial number, and model) of Blade, Ripper, Towing winch are available.',
        'The machine is operated by Qualified Operator.',
        'Fire extinguisher is provided and has valid inspection tag.',
    ],
    '2. VISUAL INSPECTION & FUNCTIONAL TEST' => [
        'Cab Mirrors, Glasses, etc. condition',
        'Bucket is in good condition',
        'No excessive corrosion on frames, anchorages, structures are present.',
        'Lift cylinders are operating correctly & without hydraulic oil leaks.',
        'Dipper cylinders are operating correctly & without hydraulic oil leaks.',
        'Steering controls are operating correctly',
        'ROPS or overhead guard is provided and can withstand the drop test based on the applicable table or rated capacity.',
        'Track Chain/ Link condition is good',
        'Track Grouser Plates condition is good',
        'Track Sprocket and Rollers condition is good',
        'Track Idler condition is good',
        'Safety belt is provided.',
        'All control levers are within reach of operator during the normal operating conditions.',
        'All hydraulic hoses are free of tears, and no signs of leaks on their hose fittings.',
        'Hydraulic oil tank level is correct and tank is securely fastened, and no signs of oil leakages.',
        'Fuel tank is secured & not leaking.',
        'Steering & transmission oil levels are correct & not leaking.',
        'Lubrication points are accessible.',
        'No deterioration of Hydraulic hoses & fittings or leakage of oil.',
        'Access and Step Ladders',
        'No indication of loose, damaged, or missing components including supports and anchorages on under carriage',
        'Control & drive mechanisms are properly adjusted and without excessive wear.',
        'Seat and back cushions are not torn.',
        'No damage tubing, piping, electrical cables, or hoses, and their fittings.',
        'Front & Rear Windshields are in good condition & Wiper Motor Assembly is working.',
        'Limit Switches are properly working.',
    ],
    '3. ENGINE & ELECTRICAL SYSTEM' => [
        'Engine oil level is correct',
        'Engine has no excessive smoke, & engine oil leak.',
        'Fuel is not leaking.',
        'Engine has no loss of power.',
        'Fan, Alternator, & steering belts tension are not loose.',
        'Instrument Panel Indicator Lights are functioning correctly.',
        'Strobe light or rotating beacon light is working.',
        'Head light & working lights are not broken and are functioning correctly.',
        'Back-Up (Reverse) Light and alarm are working.',
        'Horn is working.',
        'Battery water/electrolyte level is correct.',
        'Radiator coolant level is correct and no sign of water leakage.',
        'Housekeeping is satisfactory',
    ],
    '4. ATTACHMENTS/IMPLEMENTS' => [
        'Bucket condition is good',
        'Grappler condition is good',
        'Rock Breaker condition is good',
    ],
];

$index = 0;
foreach ($sections as $sectionTitle => $items) {
    echo "<tr><td colspan='7' class='section'>" . htmlspecialchars($sectionTitle) . "</td></tr>";
    $itemNo = 1;
    foreach ($items as $item) {
        $sn = explode('. ', $sectionTitle, 2)[0] . '.' . $itemNo;
        echo '<tr>';
        echo '<td>' . htmlspecialchars($sn) . '</td>';
        echo '<td>' . htmlspecialchars($item) . '</td>';
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
</div>

</body>
</html>
