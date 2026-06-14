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

<h2 style="text-align: center; margin-bottom: 2px;">CRANE HEALTH CHECK INSPECTION CHECKLIST FOR OFFSHORE PEDESTAL CRANES & FLOATING CRANES</h2>
<h4 style="text-align: center; margin-top: 2px; margin-bottom: 2px; font-weight: bold;">PEDESTAL CRANES, FLOATING CRANES & FLOATING DERRICKS, ARTICULATING BOOM CRANES</h4>
<h4 style="text-align: center; margin-top: 2px; margin-bottom: 10px; font-weight: bold;">ASME B30.4-2015, ASME B30.8-2015, ASME B30.22-2016, API SPEC 2C-2012, API RP 2D-2014</h4>

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
    <th>EQUIPMENT TYPE</th>
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
    <th>EQUIP.SERIAL NO.</th>
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
    
    <th width="8%">PASS</th>
    <th width="8%">FAIL</th>
    <th width="8%">NA</th>
    <th width="32%">REMARKS</th>
</tr>
</thead>
<tbody>
<?php
$sections = [
    '1. REQUIRED DOCUMENTS' => [
        '1.1' => 'Owner’s Manual or Technical Manual.',
        '1.2' => 'Crane Log Book Records.',
        '1.3' => 'Preventive Maintenance Schedule or Planned Maintenance as per Manufacturer’s recommendation records.',
        '1.4' => 'Crane Maintenance and Repair Records.',
        '1.5' => 'Slew/Swing Gear and Pinion Clearances Report.',
        '1.6' => 'Operator’s Daily Pre-Operational Inspection Checklists.',
        '1.7' => 'Previous Inspection Reports are available & deficiencies were already rectified.'
    ],
    '2. CERTIFICATES' => [
        '2.1' => 'Crane Class Certificates.',
        '2.2' => ['subheader' => 'ROPE Manufacturer’s Test Certificates'],
        '2.2.1' => 'Main Load Hoist Rope',
        '2.2.2' => 'No. 1 Auxiliary Load Hoist Rope',
        '2.2.3' => 'No. 2 Auxiliary Hoist Rope',
        '2.2.4' => 'Boom Hoist Rope',
        '2.2.5' => 'Pendant Rope',
        '2.3' => 'Crane Load Test Certificates.',
        '2.4' => ['subheader' => 'NDT/MPI Certificates:'],
        '2.4.1' => 'Crane Structure Welds',
        '2.4.2' => 'Main Hook Blocks',
        '2.4.3' => 'Auxiliary Hook Blocks',
        '2.5' => 'Operator Certificate for the type/model of crane.',
        '2.6' => 'LMI/RCL/SLI/AML Calibration Certificates.',
        '2.7' => 'Boom Rocking Test Certificates.'
    ],
    '3. MARKING AND SAFETY DECALS' => [
        '3.1' => 'Crane asset number/identification is stenciled prominently.',
        '3.2' => 'Crane’s SWL is prominently stenciled/marked.',
        '3.3' => ['subheader' => 'Hook Blocks’ SWL and weights are stenciled on the items.'],
        '3.3.1' => 'Main Hook Block',
        '3.3.2' => 'Auxiliary Hook Block',
        '3.4' => 'WARNING SIGN: Operator Should Not Rely Solely on Any Automatic Device as a Substitute for Safe Operating Practice, is posted inside the cabin’s wall or control panel.',
        '3.5' => 'CRANE’S DATA PLATE (Crane Manufacturer Name, Model, Serial Number, and Year of Manufacture) is available and posted or stamped on the crane structure.',
        '3.6' => 'Warning Decal stating: “Warning! Switch Limit must be tested before the start of Lifting Operation and NO Personnel is allowed to By-pass the Crane Limit at any time.',
        '3.7' => 'Hand signal decal is posted on the pedestal or mast and cabin.',
        '3.8' => 'Load rating charts and range diagrams are posted on the wall inside the cabin.',
        '3.9' => 'Labels of the directional control levers are marked legibly.'
    ],
    '4. VISUAL INSPECTION & FUNCTIONAL TEST' => [
        '4.1' => ['subheader' => 'BOOM STRUCTURE: There have no signs of excessive wear in the boom pivot shafts, boom cylinder anchor bushings & shafts, & boom telescopic wear surfaces & strips/pads.'],
        '4.1.1' => 'Main Boom',
        '4.1.2' => 'Lattice Boom: Chords, Lacings, Splices, and bridle have no bent, corroded, deformed, damaged, and dents.',
        '4.1.3' => 'Knuckle Boom',
        '4.2' => 'The boom assembly has no signs of corrosion, distortion, deformation, cracks, & wear.',
        '4.3' => ['subheader' => 'HYDRAULIC CYLINDERS are properly working and no signs of leakages; There is no noticeable boom dropping:'],
        '4.3.1' => 'Boom Lift',
        '4.3.2' => 'Boom Telescopic',
        '4.3.3' => 'Boom Articulating',
        '4.4' => 'The HOLDING VALVES of boom lifting, telescoping, and articulating/ knuckling are in good working condition and have no signs of boom dropping.',
        '4.5' => ['subheader' => 'HOISTING OPERATION: Properly working including their brakes.'],
        '4.5.1' => 'Boom Hoist',
        '4.5.2' => 'Main Load Hoist',
        '4.5.3' => 'Auxiliary Load Hoist',
        '4.6' => 'No leakages are visible on the hydraulic hoses, fittings, valves, & manifolds.',
        '4.7' => 'Nothing was deformed on the tubing, fittings, & other related components.',
        '4.8' => 'Boom angle indicator is provided and working properly.',
        '4.9' => 'BOOM BACK STOPS: Fixed bumper, Shock absorbing bumper, or Hydraulic bumper, is provided and in good condition.',
        '4.10' => ['subheader' => 'WINCH DRUM’S LOCK (PAWLS) is in good condition & properly functioning (as applicable):'],
        '4.10.1' => 'Boom Hoist Drum',
        '4.10.2' => 'Main Hoist Drum',
        '4.10.3' => 'Auxiliary Hoist Drum',
        '4.11' => 'Automatic Boom Back Stops: Maximum boom angle',
        '4.12' => 'Automatic stop limit for minimum boom angle',
        '4.13' => 'Minimum Boom Length & Maximum Boom Length',
        '4.14' => 'Boom cradle is provided and can secure the boom at rest.',
        '4.15' => 'Sheaves are free from deformation, dent, bent, or damage and their bearings sufficiently lubricated.',
        '4.16' => 'Aviation or pilot light is provided and is working.'
    ],
    '5. CRANE STRUCTURE AND SWING COMPONENTS' => [
        '5.1' => 'Base Structure/Pedestal/mast has no signs of loose bolts and fasteners.',
        '5.2' => 'Base Structure/Pedestal/mast’s welds and joints are free from corrosion and cracks.',
        '5.3' => 'Pins, bearings, shafts, gears, and locking devices are free from distortion, cracks and corrosion.',
        '5.4' => 'Swing brakes operate and can restrict further movement of the rotating structure.',
        '5.5' => 'Swing positive locking device is provided and can lock the structure from further movement.',
        '5.6' => 'Swing brake is adjustable to compensate its wear.',
        '5.7' => 'All swing moving parts are sufficiently lubricated.',
        '5.8' => 'Platforms and walkways are skid resistant.',
        '5.9' => 'Access ladders, & guard rails are free from rust, damage, & corrosion'
    ],
    '6. MACHINERY POWER, ELECTRICAL COMPONENTS & HYDRAULIC COMPONENTS' => [
        '6.1' => 'Work areas, companion ways, access ladders, are equipped with anti-slip surface materials.',
        '6.2' => 'Electrical wirings and related equipment are free of damages.',
        '6.3' => 'Manholes and hatches’ covers are provided to protect personnel from accidental fall.',
        '6.4' => 'Electrical and hydraulic motors & pumps are in good working condition.',
        '6.5' => 'Hydraulic hoses, fittings, tubes, and manifold joints have no evidence of leakages and not damage.',
        '6.6' => 'Hydraulic/pneumatic cylinders, pumps and motors have no leaks and working properly.',
        '6.7' => 'Engine power driven motors and pumps are working properly and have no signs of leaks.',
        '6.8' => 'Machinery compartment is free from spills and obstruction.',
        '6.9' => 'Fire extinguisher is provided in the compartment with minimum rating of 10BC.',
        '6.10' => 'An emergency lowering system, if provided, shall be checked for proper function.'
    ],
    '7. CABIN' => [
        '7.1' => 'Portable fire extinguisher is provided.',
        '7.2' => 'Toolbox with basic tools are available.',
        '7.3' => 'An Emergency stop button shall be available and working effectively.',
        '7.4' => 'Wipers are installed and working properly.',
        '7.5' => 'An audible warning device is provided for any errors in the system.',
        '7.6' => 'Cabin has a good housekeeping.',
        '7.7' => 'Cabin door is open outward or sliding backward.',
        '7.8' => 'Windshield is of Safety glazing glass.',
        '7.9' => 'Operator seat condition (torn seat or back cushions).',
        '7.10' => 'Cabin A/C is provided and is working.',
        '7.11' => 'The Installed anemometer is working.',
        '7.12' => 'The installed view camera is working',
        '7.13' => 'All applicable indicators like but not limited to; oil or water temperature gauge, hydraulic oil pressure gauge, etc. are working correctly.'
    ],
    '8. HOIST ROPES' => [
        '8.1' => ['subheader' => 'Remaining rope on drum is at least two full wraps when the boom hoist or load hoist is at its lowest angle or maximum pay-out respectively.'],
        '8.1.1' => 'Boom Hoist',
        '8.1.2' => 'Main Load Hoist',
        '8.1.3' => 'Auxiliary Load Hoist',
        '8.2' => ['subheader' => 'Wire rope shall be free from bird caging, corrosion, crushing, kinking, un-stranding, core protrusion, main strand displacement, evidence of heat damage, or any other damage.'],
        '8.2.1' => 'Boom Hoist',
        '8.2.2' => 'Main Load Hoist',
        '8.2.3' => 'Auxiliary Load Hoist',
        '8.3' => ['subheader' => 'The rope does not have more than two broken wires in 1 lay in sections beyond end connections or more than 1 broken wire at an end connection (for standing ropes).'],
        '8.3.1' => 'Boom Hoist',
        '8.3.2' => 'Main Load Hoist',
        '8.3.3' => 'Auxiliary Load Hoist',
        '8.4' => ['subheader' => 'The rope does not have more than 6 randomly distributed broken wires in 1 lay or 3 in 1 strand is 1 lay (for running ropes).'],
        '8.4.1' => 'Boom Hoist',
        '8.4.2' => 'Main Load Hoist',
        '8.4.3' => 'Auxiliary Load Hoist',
        '8.5' => ['subheader' => 'The rope dead end is correctly terminated as per the applicable standard.'],
        '8.5.1' => 'Boom Hoist',
        '8.5.2' => 'Main Load Hoist',
        '8.5.3' => 'Auxiliary Load Hoist',
        '8.6' => ['subheader' => 'The rope (Boom & Load hoist) is correctly and securely anchored on the drum.'],
        '8.6.1' => 'Boom Hoist',
        '8.6.2' => 'Main Load Hoist',
        '8.6.3' => 'Auxiliary Load Hoist',
        '8.7' => ['subheader' => 'The wire rope is correctly & adequately lubricated.'],
        '8.7.1' => 'Boom Hoist',
        '8.7.2' => 'Main Load Hoist',
        '8.7.3' => 'Auxiliary Load Hoist',
        '8.8' => ['subheader' => 'Drum Hoist brakes: Boom & Load Hoist (Main & auxiliary) are operational.'],
        '8.8.1' => 'Boom Hoist',
        '8.8.2' => 'Main Load Hoist',
        '8.8.3' => 'Auxiliary Load Hoist',
        '8.9' => 'Anti-two-blocking system (A2B) is working correctly.'
    ],
    '9. HOOKS' => [
        '9.1' => 'Labeling and manufacturer data are available & legible.',
        '9.2' => 'Hook is not bent or twisted. Maximum bending or twisting is not to exceed 10 degrees from plane of unbent hook.',
        '9.3' => 'Hook has no crack, nick, gouge, or excessive wear.',
        '9.4' => 'Hook is not distorted in the throat opening. Maximum allowable throat opening is 15% compared to new hook or as per manufacturer recommendation.',
        '9.5' => 'Hook latch is operative.',
        '9.6' => 'Hook is rotating freely.'
    ]
];

$index = 0;
foreach ($sections as $sectionTitle => $items) {
    echo "<tr><td colspan='7' class='section'>" . htmlspecialchars($sectionTitle) . "</td></tr>";
    foreach ($items as $itemSn => $itemData) {
        if (is_array($itemData) && isset($itemData['subheader'])) {
            // This is a subheader row, no checkboxes
            echo "<tr>";
            echo "<td style='font-weight:bold; text-align:center;'>" . htmlspecialchars($itemSn) . "</td>";
            echo "<td colspan='6' style='font-weight:bold; text-align:left; background-color:#eef4fb;'>" . htmlspecialchars($itemData['subheader']) . "</td>";
            echo "</tr>";
        } else {
            // This is a standard question row
            echo '<tr>';
            echo '<td>' . htmlspecialchars($itemSn) . '</td>';
            echo '<td>' . htmlspecialchars($itemData) . '</td>';            
            echo '<td class="center">' . pdf_mark_result($index, 'PASS', $selected_results) . '</td>';
            echo '<td class="center">' . pdf_mark_result($index, 'FAIL', $selected_results) . '</td>';
            echo '<td class="center">' . pdf_mark_result($index, 'NA', $selected_results) . '</td>';
            echo '<td>' . htmlspecialchars($chek_remark[$index] ?? '') . '</td>';
            echo '</tr>';
            $index++;
        }
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

<table>
    <tbody>
        <tr>
            <th style="text-align: center;">REVISION NO.</th>
            <th style="text-align: center;">TYPE OF REVISION</th>
            <th style="text-align: center;">DATE</th>
        </tr>
        <tr>
            <td>00</td>
            <td>New Checklist</td>
            <td>Feb. 09, 2020</td>
        </tr>
        <tr>
            <td>01</td>
            <td>Addition of Inspection Criteria's and Line Items</td>
            <td>Oct. 08, 2020</td>
        </tr>
    </tbody>
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