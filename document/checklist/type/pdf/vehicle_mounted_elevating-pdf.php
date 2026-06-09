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

<h2 style="text-align: center;">VEHICLE-MOUNTED ELEVATING & ROTATING AERIAL DEVICES <br>
ANSI/SAIA A92.2-2015</h2>

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
    '1. MARKINGS, CONSTRUCTION, & INSPECTION' => [
        ['Documentation is available such as but not limited to; manufacturer test certificate, operator’s manual, etc.', 'ANSI/SAIA A92.2
 Sec. 4.11.1(3),sec. 8.11, 
Sec7.4, sec 6.4, sec 6.5.3'],
        ['Equipment has an identification number / asset number marked on it.', 'ANSI/SAIA A92.2 
Sec. 4.11.1 (1)'],
        ['Previous inspection reports are available and checked.', 'ANSI/SAIA A92.2 
Sec. 9.35'],
        ['Equipment has the information data plate bearing the Manufacturer Name, Type/Model Number, Serial Number, & Year of manufacture.', 'ANSI/SAIA A92.2 
sec. 6.2.2.1(1), sec 6.5'],
        ['IDENTIFICATION MARKINGS (Placard) are posted: 1. Make, 2. model, 3.Insulating or non-insulating, 4.qualification of voltage date of test, 5. Serial number, 6. year of manufacture, 7. rated load capacity, 8. Rated platform height, 9. Aerial device system pressure or aerial device control system voltage or both, 10. Number of platforms, 11. Category of insulating aerial device (if applicable), 12. Ambient temperature range for which the aerial device is designed, 13. Name & location of manufacturer, 14. Installer, 15. Unit equipped with material handling attachment or not.', 'ANSI/SAIA A92.2
sec. 6.5.2.(1-15)'],
        ['Operator is qualified, trained, & and authorized to operate the machine.', 'ANSI/SAIA A92.2, sec 10.2'],
        ['Rated platform height from the ground to the bottom of the platform is 1 meter or 40 inches.', 'ANSI/SAIA A92.2
sec. 6.2.2.3'],
        ['Platform’s SWL (Rated Load) is prominently marked on each side of the boom.', 'ANSI/SAIA A92.2 
sec. 6.2.2.2'],
        ['Platform’s reach is measured horizontally from the center line of the pedestal (rotation) to the outer edge (rail) of the platform.', 'ANSI/SAIA A92.2 
sec. 6.2.2.4'],
        ['Maximum number of persons allowed on the platform is marked.', 'ANSI/SAIA A92.2, sec 4.9.4.2'],
        ['Mobile unit (MEWP) is stable on a slope not greater than 5°', 'ANSI/SAIA A92.2, sec 4.5.2'],
        ['Slope indicator is provided and visible to the operator during set-up.', 'ANSI/SAIA A92.2, sec 4.5.4'],
        ['Manually operated stabilizer is designed to prevent unintentional movement.', 'ANSI/SAIA A92.2, sec 4.5.7'],
        ['Parking brake interlock is provided for mobile unit.', 'ANSI/SAIA A92.2, sec 4.5.8'],
        ['Control levers are labeled of each directional function.', 'ANSI/SAIA A92.2 sec 4.3.1-7, sec 6.5.3'],
        ['Control levers return to neutral position when released.', 'ANSI/SAIA A92.2 sec 4.3.1'],
        ['Lower control is provided with a means to override the upper control system when operated at ground.', 'ANSI/SAIA A92.2 sec 4.3.3'],
        ['Power rating is provided and marked (DC and/or AC)', 'ANSI/SAIA A92.2 , sec 6.2.2.6,
sec. 6.5.2(3)(4)(9)(11)'],
        ['Electrical Hazard decals are marked.', 'ANSI/SAIA A92.2, sec 6.5.4 (1)'],
        ['Decals stating clearances to power lines are posted.', 'ANSI/SAIA A92.2, sec 6.5.4 (2)'],
        ['Information decal related to the use and load rating of the equipment is posted.', 'ANSI/SAIA A92.2, sec 6.5.4 (4)'],
        ['Information decal related to the use of aerial device for mobile operation is posted.', 'ANSI/SAIA A92.2, sec 6.5.4 (7)'],
        ['Notice decal that the aerial device shall not be operated with missing covers or guards except as required for the maintenance or testing of it is posted.', 'ANSI/SAIA A92.2, sec 6.5.4 (8)'],
        ['Emergency stop is properly identified and is working effectively.', 'ANSI/SAIA A92.2, sec. 4.3.5, sec 8.2.3((8)(c)'],
        ['Aerial ladder is provided with a securing device when in travelling position.', 'ANSI/SAIA A92.2, sec 4.4.1'],
        ['Boom is provided with a securing device to remain in cradled position when in transport.', 'ANSI/SAIA A92.2, sec 4.4.2'],
        ['Platform can withstand vibration and shock loading during travel.', 'ANSI/SAIA A92.2, sec 4.4.3'],
        ['Guardrail system (with the exemption of Bucket & Basket) shall have top rail with 42” (1067 mm) high, plus or minus 3” (76mm) above the platform surface.', 'ANSI/SAIA A92.2, sec 4.9.1(1)'],
        ['Guardrail system (with the exemption of Bucket & Basket) shall at least include one rail approximately midway between the top rail and the platform surface.', 'ANSI/SAIA A92.2, sec 4.9.1(2)'],
        ['Platform with folding type floors and steps or rungs maybe used without rails & kickplates.', 'ANSI/SAIA A92.2, sec 4.9.3'],
        ['Anchorages for lanyard are provided.', 'ANSI/SAIA A92.2, sec 4.9.4.1'],
        ['Notice decal that fiberglass or plastic covers are not insulating is posted.', 'ANSI/SAIA A92.2, sec 6.5.4 (9)'],
        ['Inspection Sticker is posted prominently on the structure.', 'ANSI/SAIA A92.2,sec 8.3.1'],
        ['Steps/ladders: Distance between the ground or lower platform surface to the top surface of the first step should not exceed 27 inches where possible.', 'ANSI/SAIA A92.2, sec 7.6.1'],
        ['Distance between the top surface of steps or rungs should not exceed 16 inches where possible.', 'ANSI/SAIA A92.2, sec 7.6.1'],
        ['Each step or rung should have a minimum width of 6 inches for placement of one foot or 12 inches for placement of two feet and minimum rung diameter of one inch.', 'ANSI/SAIA A92.2, sec 7.6.1'],
        ['Access opening passage should have a minimum width of 18 inches and minimum opening height of 30 inches.', 'ANSI/SAIA A92.2, sec 7.6.2'],
    ],
    '2. MECHANICAL TESTS & VISUAL INSPECTION' => [
        ['Boom Elevating and lowering mechanisms are operable and no evidence of dropping.', 'ANSI/SAIA A92.2, sec 6.6.1(1)'],
        ['Boom extension/retraction mechanism is operable.', 'ANSI/SAIA A92.2, sec 6.6.1(2)'],
        ['Rotating mechanism is functioning correctly and smoothly.', 'ANSI/SAIA A92.2, sec 6.6.1(3)'],
        ['Aerial device is stable.', 'ANSI/SAIA A92.2, sec 6.6.1(4)'],
        ['All safety devices have been checked and are properly functioning.', 'ANSI/SAIA A92.2, sec 6.6.1(5)'],
        ['No damage or deformation is evident on either the lower or upper structure.', 'ANSI/SAIA A92.2, sec 6.6.2'],
        ['No visible hydraulic oil leak from any component , such as but not limited to; hydraulic motors, hydraulic pumps, hydraulic rams, hydraulic valves, hydraulic tank, etc.', 'ANSI/SAIA A92.2, sec 6.6.2'],
        ['No loose connections were found from both the upper and lower structure.', 'ANSI/SAIA A92.2, sec 6.6.2'],
        ['The vehicle’s electrical system were properly functioning, i.e. but not limited to headlights, turn signal lights, beacon lights/warning lights, brake lights, reverse lights and back-up alarms, etc.', 'ANSI/SAIA A92.2, 
                    sec. 8.2.4(11)'],
        ['Both service & parking brakes are operable.', 'ANSI/SAIA A92.2, sec 10.5'],
        ['All locking pins shall be secured against unintentional disengagement and loss.', 'ANSI/SAIA A92.2, sec 7.5.1'],
        ['Interlocks are properly operating.', 'ANSI/SAIA A92.2, sec 7.5.1'],
        ['Visual and audible safety devices are properly operating.', 'ANSI/SAIA A92.2, sec 8.2.3(3)'],
        ['Fiberglass and insulating components have no visible damage and contamination', 'ANSI/SAIA A92.2, sec 8.2.3(4)'],
        ['Hydraulic and pneumatic systems have no observable deterioration and excessive leakages.', 'ANSI/SAIA A92.2, sec 8.2.3(6)'],
        ['Electrical systems related to the aerial device have no signs of excessive deterioration & malfunctions, dirt and moisture accumulation.', 'ANSI/SAIA A92.2, sec 8.2.3(7)'],
        ['Stabilizers/outriggers are check for proper operation and no dropping is evident.', 'ANSI/SAIA A92.2, sec 4.5.5
                Sec. 4.5.7'],
        ['Safety harness anchorage is fitted in the platform.', 'ANSI/SAIA A92.2, sec 4.9.4.4'],
        ['Spirit level is fitted and is operational.', 'SAIA/SIA 92.5'],
        ['All moving parts are lubricated.', 'ANSI/SIA 92.2, sec. 6.6.1'],
        ['Upper station does not include drive and steering controls.', 'ANSI/SIA 92.2, sec. 6.6.1'],
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