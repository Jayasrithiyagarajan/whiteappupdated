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
INSPECTION CHECKLIST FOR FORKLIFT<br>
FRM.0601-1.15 (rev.00)<br>
SAFETY STANDARD FOR LOW & HIGH LIFT TRUCKS<br>
ANSI/ITSDF B56.1 – 2018, ANSI/ITSDF B56.6 – 2016
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
    <th width="48%">ACCEPTANCE CRITERIA</th>
    <th width="8%">PASS</th>
    <th width="8%">FAIL</th>
    <th width="8%">NA</th>
    <th width="22%">REMARKS</th>
</tr>
</thead>
<tbody>
<?php
$sections = [
    '1. RATINGS & MARKINGS' => [
        [
            'item' => 'Documentation is available.', // 1.1 (Index: 0)
        ],
        [
            'item' => 'Equipment asset ID Number is prominently marked.', // 1.2 (Index: 1)
        ],
        [
            'item' => 'Nameplate, caution, and instruction markings are available on the truck.', // 1.3 (Index: 2)
        ],
        [
            'item' => 'SWL/WLL (Capacities) are clearly marked & on a prominent location.', // 1.4 (Index: 3)
        ],
        [
            'item' => 'All controls are marked for identification of function.', // 1.5 (Index: 4)
        ],
        [
            'item' => 'Front end attachments, including fork extensions are marked with identification, capacity, maximum elevation with the load laterally centered.', // 1.6 (Index: 5)
        ],
        [
            'item' => 'The machine is operated by Certified Operator.', // 1.7 (Index: 6)
        ],
        [
            'item' => 'All control levers are within reach of operator during the normal operating conditions.', // 1.8 (Index: 7)
        ],
        [
            'item' => 'Capacity chart is provided & legible.', // 1.9 (Index: 8)
        ],
        [
            'item' => 'All safety & warning decals are posted.', // 1.10 (Index: 9)
        ],
    ],
    '2. VISUAL INSPECTION & FUNCTIONAL TEST' => [
        [
            'item' => 'Carriage, backrest, & mast are not bent or deformed.', // 2.1 (Index: 10)
        ],
        [
            'item' => 'Forks are not deformed or bent.', // 2.2 (Index: 11)
        ],
        [
            'item' => 'Forks arms & levers are not deformed or bent.', // 2.3 (Index: 12)
        ],
        [
            'item' => 'No excessive corrosion on frames, anchorages, structures are present.', // 2.4 (Index: 13)
        ],
        [
            'item' => 'Load chains have no broken links or pins.', // 2.5 (Index: 14)
        ],
        [
            'item' => 'Lift & tilt cylinders are operating correctly & without hydraulic oil leaks.', // 2.6 (Index: 15)
        ],
        [
            'item' => 'Steering & side shift cylinders are operating correctly & without hydraulic oil leaks.', // 2.7 (Index: 16)
        ],
        [
            'item' => 'ROPS or overhead guard is provided and can withstand the drop test based on the applicable table or rated capacity.', // 2.8 (Index: 17)
        ],
        [
            'item' => 'Safety belt is provided.', // 2.9 (Index: 18)
        ],
        [
            'item' => 'All control levers are within reach of operator during the normal operating conditions.', // 2.10 (Index: 19)
        ],
        [
            'item' => 'All hydraulic hoses are free of tears, and no signs of leaks on their hose fittings.', // 2.11 (Index: 20)
        ],
        [
            'item' => 'Hydraulic oil tank level is correct and tank is securely fastened, and no signs of oil leakages.', // 2.12 (Index: 21)
        ],
        [
            'item' => 'Fuel tank is correct, secured, & not leaking.', // 2.13 (Index: 22)
        ],
        [
            'item' => 'Steering & transmission oil levels are correct & not leaking.', // 2.14 (Index: 23)
        ],
        [
            'item' => 'Lubrication points are accessible.', // 2.15 (Index: 24)
        ],
        [
            'item' => 'No deterioration or leakage in air, water or hydraulic is found.', // 2.16 (Index: 25)
        ],
        [
            'item' => 'No indication of loose, damaged, or missing structural components including supports and anchorages.', // 2.17 (Index: 26)
        ],
        [
            'item' => 'Limit Switches are properly working.', // 2.18 (Index: 27)
        ],
        [
            'item' => 'Brake & Clutch system parts & linings have no excessive wear, severe distortion, and damage.', // 2.19 (Index: 28)
        ],
        [
            'item' => 'Seat and back cushion are not torn.', // 2.20 (Index: 29)
        ],
    ],
    '3. INSPECTION CRITERIA' => [
        [
            'item' => 'No deterioration or leakage in air or hydraulic is found.', // 3.1 (Index: 30)
        ],
        [
            'item' => 'Tires have no tears, sidewall cuts & excessive wear.', // 3.2 (Index: 31)
        ],
        [
            'item' => 'Wheel hubs and gear boxes have no leakages.', // 3.3 (Index: 32)
        ],
        [
            'item' => 'Wheel bearings / bushing have no signs of excessive wear.', // 3.4 (Index: 33)
        ],
        [
            'item' => 'No indication of loose, damaged, or missing components including supports and anchorages on under chassis.', // 3.5 (Index: 34)
        ],
        [
            'item' => 'Control & drive mechanisms are properly adjusted and without excessive wear.', // 3.6 (Index: 35)
        ],
        [
            'item' => 'Brake fluid level is correctly topped-up.', // 3.7 (Index: 36)
        ],
        [
            'item' => 'Parking brake is correctly working.', // 3.8 (Index: 37)
        ],
        [
            'item' => 'Steering cylinders, knuckles, kingpins, tie rods, equalizer bar, etc. have no excessive plays and not excessively worn-out.', // 3.9 (Index: 38)
        ],
        [
            'item' => 'No damage tubing, piping, electrical cables, or hoses, and their fittings.', // 3.10 (Index: 39)
        ],
    ],
    '4. ENGINE & ELECTRICAL SYSTEM' => [
        [
            'item' => 'Engine has no excessive smoke, & engine oil leak.', // 4.1 (Index: 40)
        ],
        [
            'item' => 'Fuel is not leaking.', // 4.2 (Index: 41)
        ],
        [
            'item' => 'Engine has no loss of power.', // 4.3 (Index: 42)
        ],
        [
            'item' => 'Fan, Alternator, & steering belts tension are not loose.', // 4.4 (Index: 43)
        ],
        [
            'item' => 'All indicator lights are not broken and are functioning correctly.', // 4.5 (Index: 44)
        ],
        [
            'item' => 'Strobe light or rotating beacon light is provided working.', // 4.6 (Index: 45)
        ],
        [
            'item' => 'Head light & working lights are not broken and are functioning correctly.', // 4.7 (Index: 46)
        ],
        [
            'item' => 'Brake & tail lights, including reverse light are working.', // 4.8 (Index: 47)
        ],
        [
            'item' => 'Back-Up alarm is working.', // 4.9 (Index: 48)
        ],
        [
            'item' => 'Horn is working.', // 4.10 (Index: 49)
        ],
        [
            'item' => 'Radiator coolant level is correct and no sign of water leakage.', // 4.11 (Index: 50)
        ],
        [
            'item' => 'Turn signal lights are provided and working correctly.', // 4.12 (Index: 51)
        ],
        [
            'item' => 'Battery water/electrolyte level is correct.', // 4.13 (Index: 52)
        ],
        [
            'item' => 'Fire extinguisher is provided and has the valid inspection tag.', // 4.14 (Index: 53)
        ],
    ],
];

$index = 0;
foreach ($sections as $sectionTitle => $items) {
    echo "<tr><td colspan='6' class='section'>" . htmlspecialchars($sectionTitle) . "</td></tr>";
    $itemNo = 1;
    foreach ($items as $itemData) {
        $item = $itemData['item'];
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
