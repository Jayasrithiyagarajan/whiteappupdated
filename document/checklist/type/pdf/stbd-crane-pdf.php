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

<h2 style="text-align: center;">CRANE HEALTH CHECK INSPECTION CHECKLIST FOR OFFSHORE PEDESTAL CRANES & FLOATING CRANES</h2>

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
    <td style="text-align:center;"><?= htmlspecialchars($row['type'] ?? '') ?></td>
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
    <th>CAPACITY</th>
    <td style="text-align:center;"><?= htmlspecialchars($row['capacity'] ?? '') ?></td>
</tr>
<tr>
    <th>SERIAL NO</th>
    <td style="text-align:center;"><?= htmlspecialchars($row['serial_no'] ?? '') ?></td>
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
    '1. REQUIRED DOCUMENTS' => [
        'Owner\'s Manual',
        'Crane Log Book',
        'Preventive Maintenance Records',
        'Crane Maintenance and Repair Records',
        'Slew/Swing Gear and Pinion Clearances Report',
        'Operator\'s Daily Pre-Operational Inspection Checklists',
        'Previous Inspection Reports are available & deficiencies were already rectified',
    ],
    '2. CERTIFICATES' => [
        'Crane Class Certificates',
        'Main Load Hoist Rope',
        'No. 1 Auxiliary Load Hoist Rope',
        'No. 2 Auxiliary Hoist Rope',
        'Boom Hoist Rope',
        'Pendant Rope',
        'Crane Load Test Certificates',
        'Crane Structure Welds',
        'Main Hook Blocks',
        'Auxiliary Hook Blocks',
        'Operator Certificate for the type/model of crane',
        'LMI/RCL/SLI/AML Calibration Certificates',
        'Boom Rocking Test Certificates',
    ],
    '3. MARKING AND SAFETY DECALS' => [
        'Crane asset number/identification is stenciled prominently',
        'Crane\'s SWL is prominently stenciled/marked',
        'Hook Blocks\' SWL and weights are stenciled on the items',
        'WARNING SIGN: Operator Should Not Rely Solely on Any Automatic Device as a Substitute for Safe Operating Practice, is posted inside the cabin\'s wall or control panel',
        'CRANE\'S DATA PLATE (Crane Manufacturer Name, Model, Serial Number, and Year of Manufacture) is available and posted or stamped on the crane structure',
        'Warning Decal stating: "Warning! Switch Limit must be tested before the start of Lifting Operation and NO Personnel is allowed to By-pass the Crane Limit at any time"',
        'Hand signal decal is posted on the pedestal or mast and cabin',
        'Load rating charts and range diagrams are posted on the wall inside the cabin',
        'Labels of the directional control levers are marked legibly',
    ],
    '4. VISUAL INSPECTION & FUNCTIONAL TEST' => [
        'BOOM STRUCTURE: There have no signs of excessive wear in the boom pivot shafts, boom cylinder anchor bushings & shafts, & boom telescopic wear surfaces & strips/pads',
        'Main Boom',
        'Lattice Boom: Chords, Lacings, Splices, and bridle have no bent, corroded, deformed, damaged, and dents',
        'Knuckle Boom',
        'The boom assembly has no signs of corrosion, distortion, deformation, cracks, & wear',
        'HYDRAULIC CYLINDERS are properly working and no signs of leakages; There is no noticeable boom dropping',
        'Boom Lift',
        'Boom Telescopic',
        'Boom Articulating',
        'The HOLDING VALVES of boom lifting, telescoping, and articulating/ knuckling are in good working condition and have no signs of boom dropping',
        'HOISTING OPERATION: Properly working including their brakes',
        'Boom Hoist',
        'Main Load Hoist',
        'Auxiliary Load Hoist',
        'No leakages are visible on the hydraulic hoses, fittings, valves, & manifolds',
        'Nothing was deformed on the tubing, fittings, & other related components',
        'Boom angle indicator is provided and working properly',
        'BOOM BACK STOPS: Fixed bumper, Shock absorbing bumper, or Hydraulic bumper, is provided and in good condition',
        'WINCH DRUM\'S LOCK (PAWLS) is in good condition & properly functioning (as applicable)',
        'Boom Hoist Drum',
        'Main Hoist Drum',
        'Auxiliary Hoist Drum',
        'Automatic Boom Back Stops: Maximum boom angle',
        'Automatic stop limit for minimum boom angle',
        'Minimum Boom Length & Maximum Boom Length',
        'Boom cradle is provided and can secure the boom at rest',
        'Sheaves are free from deformation, dent, bent, or damage and their bearings sufficiently lubricated',
        'Aviation or pilot light is provided and is working',
    ],
    '5. CRANE STRUCTURE AND SWING COMPONENTS' => [
        'Base Structure/Pedestal/mast has no signs of loose bolts and fasteners',
        'Base Structure/Pedestal/mast\'s welds and joints are free from corrosion and cracks',
        'Pins, bearings, shafts, gears, and locking devices are free from distortion, cracks and corrosion',
        'Swing brakes operate and can restrict further movement of the rotating structure',
        'Swing positive locking device is provided and can lock the structure from further movement',
        'Swing brake is adjustable to compensate its wear',
        'All swing moving parts are sufficiently lubricated',
        'Platforms and walkways are skid resistant',
        'Access ladders, & guard rails are free from rust, damage, & corrosion',
    ],
    '6. MACHINERY POWER, ELECTRICAL COMPONENTS & HYDRAULIC COMPONENTS' => [
        'Work areas, companion ways, access ladders, are equipped with anti-slip surface materials',
        'Electrical wirings and related equipment are free of damages',
        'Manholes and hatches\' covers are provided to protect personnel from accidental fall',
        'Electrical and hydraulic motors & pumps are in good working condition',
        'Hydraulic hoses, fittings, tubes, and manifold joints have no evidence of leakages and not damage',
        'Hydraulic/pneumatic cylinders, pumps and motors have no leaks and working properly',
        'Engine power driven motors and pumps are working properly and have no signs of leaks',
        'Machinery compartment is free from spills and obstruction',
        'Fire extinguisher is provided in the compartment with minimum rating of 10BC',
        'An emergency lowering system, if provided, shall be checked for proper function',
    ],
    '7. CABIN' => [
        'Portable fire extinguisher is provided',
        'Toolbox with basic tools are available',
        'An Emergency stop button shall be available and working effectively',
        'Wipers are installed and working properly',
        'An audible warning device is provided for any errors in the system',
        'Cabin has a good housekeeping',
        'Cabin door is open outward or sliding backward',
        'Windshield is of Safety glazing glass',
        'Operator seat condition (torn seat or back cushions)',
        'Cabin A/C is provided and is working',
        'The Installed anemometer is working',
        'The installed view camera is working',
        'All applicable indicators like but not limited to; oil or water temperature gauge, hydraulic oil pressure gauge, etc. are working correctly',
    ],
    '8. HOIST ROPES' => [
        'Remaining rope on drum is at least two full wraps when the boom hoist or load hoist is at its lowest angle or maximum pay-out respectively',
        'Boom Hoist',
        'Main Load Hoist',
        'Auxiliary Load Hoist',
        'Wire rope shall be free from bird caging, corrosion, crushing, kinking, un-stranding, core protrusion, main strand displacement, evidence of heat damage, or any other damage',
        'Boom Hoist',
        'Main Load Hoist',
        'Auxiliary Load Hoist',
        'The rope does not have more than two broken wires in 1 lay in sections beyond end connections or more than 1 broken wire at an end connection (for standing ropes)',
        'Boom Hoist',
        'Main Load Hoist',
        'Auxiliary Load Hoist',
        'The rope does not have more than 6 randomly distributed broken wires in 1 lay or 3 in 1 strand is 1 lay (for running ropes)',
        'Boom Hoist',
        'Main Load Hoist',
        'Auxiliary Load Hoist',
        'The rope dead end is correctly terminated as per the applicable standard',
        'Boom Hoist',
        'Main Load Hoist',
        'Auxiliary Load Hoist',
        'The wire rope is correctly and securely anchored on the drum',
        'Boom Hoist',
        'Main Load Hoist',
        'Auxiliary Load Hoist',
        'The wire rope is correctly & adequately lubricated',
        'Boom Hoist',
        'Main Load Hoist',
        'Auxiliary Load Hoist',
        'Drum Hoist brakes: Boom & Load Hoist (Main & auxiliary) are operational',
        'Boom Hoist',
        'Main Load Hoist',
        'Auxiliary Load Hoist',
        'Anti-two-blocking system (A2B) is working correctly',
    ],
    '9. HOOKS' => [
        'Labeling and manufacturer data are available & legible',
        'Hook is not bent or twisted. Maximum bending or twisting is not to exceed 10 degrees from plane of unbent hook',
        'Hook has no crack, nick, gouge, or excessive wear',
        'Hook is not distorted in the throat opening. Maximum allowable throat opening is 15% compared to new hook or as per manufacturer recommendation',
        'Hook latch is operative',
        'Hook is rotating freely',
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
        echo '<td></td>';
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