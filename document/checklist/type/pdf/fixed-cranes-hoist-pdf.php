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
    font-size: 9px;
    line-height: 1.3;
    color: #000;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    border: 1px solid #000;
    padding: 4px 3px;
    vertical-align: middle;
}

thead {
    display: table-header-group;
}

th {
    background-color: #c0d6e8;
    font-weight: bold;
    text-align: center;
    font-size: 8px;
}

td {
    font-size: 8px;
}

.section {
    background-color: #c0d6e8;
    font-weight: bold;
    text-align: left;
    padding: 4px;
    font-size: 8px;
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
    margin-top: 10px;
}

.signature-table th {
    background-color: #c0d6e8;
    font-size: 8px;
    text-align: center;
    padding: 4px;
}

.signature-table td {
    text-align: center;
    vertical-align: top;
    padding: 4px 3px;
    height: 45px;
}

.signature-name {
    font-size: 7px;
    font-weight: bold;
    margin-bottom: 2px;
}

.signature-box {
    height: 20px;
    margin: 0 auto;
}

.signature-box img {
    max-width: 35px;
    max-height: 15px;
}

.signature-placeholder {
    font-size: 7px;
    color: #777;
    font-style: italic;
}

.title-section {
    text-align: center;
    font-weight: bold;
    font-size: 10px;
    margin: 8px 0;
}

.footer-section {
    page-break-inside: avoid;
}

.info-table td {
    font-size: 8px;
    padding: 3px;
}

        .keep-together {
            page-break-inside: avoid;
            break-inside: avoid;
        }
</style>
</head>
<body>

<div class="title-section">
INSPECTION CHECKLIST FOR FIXED CRANES & HOISTS<br>
FRM.0601-1.2 (rev.02)<br>
ASME B30.2-2016, ASME B30.3-2016, ASME B30.4-2015, ASME B30.6-2015, ASME B30.16-2017, ASME B30.17-2015
</div>

<br>

<table class="info-table">
<tr>
    <th width="20%">REPORT NO</th>
    <td width="20%" style="text-align:center;"><?= htmlspecialchars($row['report_no'] ?? '') ?></td>
    <th width="20%">INSPECTION DATE</th>
    <td width="20%" style="text-align:center;"><?= htmlspecialchars($row['inspection_date'] ?? '') ?></td>
</tr>
<tr>
    <th>CLIENT'S NAME</th>
    <td style="text-align:center;"><?= htmlspecialchars($row['client_name'] ?? '') ?></td>
    <th>INSPECTED BY</th>
    <td style="text-align:center;"><?= htmlspecialchars($row['inspected_by'] ?? '') ?></td>
</tr>
<tr>
    <th>LOCATION</th>
    <td style="text-align:center;"><?= htmlspecialchars($row['location'] ?? '') ?></td>
    <th>STICKER NO.</th>
    <td style="text-align:center;"><?= htmlspecialchars($row['sticker_no'] ?? '') ?></td>
</tr>
<tr>
    <th>CRANE ASSET NO</th>
    <td style="text-align:center;"><?= htmlspecialchars($row['equipment_no'] ?? '') ?></td>
    <th>CRANE SERIAL NO.</th>
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
    <th width="4%">S.N</th>
    <th width="42%">ACCEPTANCE CRITERIA</th>
    <th width="12%">REFERENCE</th>
    <th width="6%">PASS</th>
    <th width="6%">FAIL</th>
    <th width="6%">NA</th>
    <th width="24%">REMARKS</th>
</tr>
</thead>
<tbody>
<?php
$sections = [
    '1. GENERAL REQUIREMENTS' => [
        [
            'item' => 'Equipment documentation is available',
            'reference' => 'ASME B30.2, Sec.1.16', // 1.1 (Index: 0)
        ],
        [
            'item' => 'Previous inspection reports are checked',
            'reference' => 'ASME B30.2, Sec.2.1.5', // 1.2 (Index: 1)
        ],
        [
            'item' => 'Rated load is clearly marked on both sides of crane bridge',
            'reference' => 'ASME B30.2, Sec.1.1.1', // 1.3 (Index: 2)
        ],
        [
            'item' => 'Rated load is clearly marked on hoist or trolley unit',
            'reference' => 'ASME B30.2, Sec.1.1.1', // 1.4 (Index: 3)
        ],
        [
            'item' => 'Equipment number is clearly marked for identification purposes',
            'reference' => 'ASME B30.16 Sec.1.1', // 1.5 (Index: 4)
        ],
        [
            'item' => 'Safe working load is clearly marked on the runway and the lifting machine',
            'reference' => 'ASME B30.16 Sec.1.1.1', // 1.6 (Index: 5)
        ],
        [
            'item' => 'Crane manufacturer name, address, serial number and power ratings are clearly marked or tagged',
            'reference' => 'ASME B30.2, Sec.1.1.3', // 1.7 (Index: 6)
        ],
        [
            'item' => 'Precautionary warnings to operator are clearly marked',
            'reference' => 'ASME B30.2, Sec.1.1.5', // 1.8 (Index: 7)
        ],
    ],
    '2. GENERAL INSPECTION POINTS' => [
        [
            'item' => 'Clearance exits between the crane and sides of the building or adjacent crane are maintained throughout all motions',
            'reference' => 'ASME B30.2, Sec.1.2.1', // 2.1 (Index: 8)
        ],
        [
            'item' => 'Controls are clearly marked with their functions and modes of operation',
            'reference' => 'ASME B30.3 Sec.3-1.18.1', // 2.2 (Index: 9)
        ],
        [
            'item' => 'Controls and protective equipment are within reach of the operator inside the cab',
            'reference' => 'ASME B30.2, Sec.1.5.1a', // 2.3 (Index: 10)
        ],
        [
            'item' => 'The hook block is visible from operator station at all times',
            'reference' => 'ASME B30.2, Sec.1.5.1b', // 2.4 (Index: 11)
        ],
        [
            'item' => 'Cab is attached to the crane to minimize swaying and vibrations',
            'reference' => 'ASME B30.2, Sec.1.5.2a', // 2.5 (Index: 12)
        ],
        [
            'item' => 'Access to the cab or bridge walkway is by a fixed ladder, stairs, or platform',
            'reference' => 'AASME B30.2, Sec.1.5.3', // 2.6 (Index: 13)
        ],
        [
            'item' => 'Controls arrangements and protective equipment inside the cab are within the reach of the operator',
            'reference' => 'ASME B30.2, Sec.1.5.1a', // 2.7 (Index: 14)
        ],
        [
            'item' => 'The clearance from the surface of the platform to the nearest overhead obstruction is 1220 mm (48")',
            'reference' => 'ASME B30.2, Sec.1.7.1a', // 2.8 (Index: 15)
        ],
        [
            'item' => 'The service platform width is at least 457 mm (18") except at the bridge mechanism where it is not less than 380 mm (15")',
            'reference' => 'ASME B30.2, Sec.1.7.1c', // 2.9 (Index: 16)
        ],
        [
            'item' => 'The electrical control cabinet door(s) are opening 90 degree or removable type',
            'reference' => 'ASME B30.2, Sec.1.7.1e', // 2.10 (Index: 17)
        ],
        [
            'item' => 'Service platform walking surface is slip-resistant',
            'reference' => 'ASME B30.2, Sec.1.7.1g', // 2.11 (Index: 18)
        ],
        [
            'item' => 'Service platform is provided with guard railings and toe boards',
            'reference' => 'ASME B30.2, Sec.1.7.1h', // 2.12 (Index: 19)
        ],
        [
            'item' => 'Emergency escape is possible from the cab',
            'reference' => 'ASME B30.2, Sec.1.7.3', // 2.13 (Index: 20)
        ],
        [
            'item' => 'Stairways are non-slip and have a maximum incline angle of 50 degree',
            'reference' => 'ASME B30.2, Sec.1.7.2', // 2.14 (Index: 21)
        ],
        [
            'item' => 'Each hoisting unit is equipped with at least one holding brake',
            'reference' => 'ASME B30.2, Sec.1.12.1a', // 2.15 (Index: 22)
        ],
        [
            'item' => 'The holding brake is applied to the motor shaft or a gear reducer shaft',
            'reference' => 'ASME B30.2, Sec.1.12.1a', // 2.16 (Index: 23)
        ],
        [
            'item' => 'The holding brake torque rating is not less than the percentage of rated load hoisting torque at the point where the brake is applied (based on the crane design)',
            'reference' => 'ASME B30.2, Sec.1.12.1a', // 2.17 (Index: 24)
        ],
        [
            'item' => 'Pendant control cable is properly enclosed, grounded and suspended with a separate support cable',
            'reference' => 'ASME B30.2, Sec.1.13.1a-d', // 2.18 (Index: 25)
        ],
        [
            'item' => 'Pendant control push-button enclosure is marked for identification of functions',
            'reference' => 'ASME B30.2, Sec.1.13.1e', // 2.19 (Index: 26)
        ],
        [
            'item' => 'Electrical equipment is guarded and not exposed to oil, moisture, dirt and inadvertent contact',
            'reference' => 'ASME B30.2, Sec.1.13.2', // 2.20 (Index: 27)
        ],
        [
            'item' => 'Audio warning device(s) are fitted (one or more of the following: Gong, Bell/Siren/Horn, Rotating Beacon and/or strop light)',
            'reference' => 'ASME B30.2, Sec.1.15.3', // 2.21 (Index: 28)
        ],
        [
            'item' => 'Lifting and lowering functional test is satisfactory',
            'reference' => 'ASME B30.2, Sec.2.2(b-1)', // 2.22 (Index: 29)
        ],
        [
            'item' => 'Crane trolley travel functional test is satisfactory',
            'reference' => 'ASME B30.2, Sec.2.2(b-2)', // 2.23 (Index: 30)
        ],
        [
            'item' => 'Crane bridge travel functional test is satisfactory',
            'reference' => 'ASME B30.2, Sec.2.2(b-3)', // 2.24 (Index: 31)
        ],
        [
            'item' => 'Hoist limit device functional test is satisfactory',
            'reference' => 'ASME B30.2, Sec.2.2(b-4)', // 2.25 (Index: 32)
        ],
        [
            'item' => 'Hoist and swing drives are capable of starts and stops with variable acceleration and deceleration required in normal operation',
            'reference' => 'ASME B30.7 Sec.1.2.2(f)', // 2.26 (Index: 33)
        ],
        [
            'item' => 'Hoist drum specifications are marked (rated load, drum size, rope size, rope speed (ft/min or m/s), rated power)',
            'reference' => 'ASME B30.7 Sec.1.1.3', // 2.27 (Index: 34)
        ],
        [
            'item' => 'Hand Chain Hoist: Manufacturer data, serial number and safe working load are clearly displayed on the item',
            'reference' => 'ASME B30.16 Sec.1.1.3a', // 2.28 (Index: 35)
        ],
        [
            'item' => 'Electric Powered Hoist: Manufacturer data, serial number, safe working load, voltage and phase are clearly displayed on the item',
            'reference' => 'ASME B30.16 Sec.1.1.3b', // 2.29 (Index: 36)
        ],
        [
            'item' => 'Air Powered Hoist: Manufacturer data, serial number, model, safe working load and rated air pressure are clearly displayed on the item',
            'reference' => 'ASME B30.16 Sec.1.1.3c', // 2.30 (Index: 37)
        ],
        [
            'item' => 'Warning signs/labels are provided on the hoist units and electrical enclosures',
            'reference' => 'ASME B30.16 Sec.1.1.4', // 2.31 (Index: 38)
        ],
        [
            'item' => 'Crane Travel limit device functional test is satisfactory',
            'reference' => 'ASME B30.2, Sec.2.2(b-4)', // 2.32 (Index: 39)
        ],
        [
            'item' => 'Wire rope end connections do not have corrosion',
            'reference' => 'ASME B30.2, Sec.2.4.2(c,d)', // 2.33 (Index: 40)
        ],
        [
            'item' => 'Ropes are correctly lubricated',
            'reference' => 'ASME B30.2, Sec.2.4.3e', // 2.34 (Index: 41)
        ],
        [
            'item' => 'Wire rope is not corroded',
            'reference' => 'ASME B30.2, Sec.2.4.1(a1-b)', // 2.35 (Index: 42)
        ],
        [
            'item' => 'The rope is adequately lubricated',
            'reference' => 'ASME B30.2, Sec.2.4.3e', // 2.36 (Index: 43)
        ],
        [
            'item' => 'Fire extinguisher is available Sec.10BC minimum rated)',
            'reference' => 'ASME B30.2, Sec.3.4.3', // 2.37 (Index: 44)
        ],
        [
            'item' => 'Structure is vibration free under normal operating condition',
            'reference' => 'ASME B30.17 Sec.1.3.1(b)', // 2.38 (Index: 45)
        ],
        [
            'item' => 'Monorail end stops are installed and in good condition',
            'reference' => 'ASME B30.17 Sec.1.4.2, Sec 1.5.3', // 2.39 (Index: 46)
        ],
        [
            'item' => 'Jib crane end stops are installed and in good condition',
            'reference' => 'ASME B30.17 Sec.1.4.2, Sec 1.5.3', // 2.40 (Index: 47)
        ],
        [
            'item' => 'Tracks are properly installed and aligned',
            'reference' => 'ASME B30.17 Sec.1.3.1 Sec 1.4.1', // 2.41 (Index: 48)
        ],
        [
            'item' => 'Crane runways or monorail tracks are fastened and Secured to a supporting structure',
            'reference' => 'ASME B30.17 Sec.1.3.2', // 2.42 (Index: 49)
        ],
        [
            'item' => 'All welded members are free of defects and not corroded',
            'reference' => 'ASME B30.17 Sec.1.3.4', // 2.43 (Index: 50)
        ],
        [
            'item' => 'Guards protect moving parts such as gears, chains, chain sprockets',
            'reference' => 'ASME B30.17 Sec.1.11.1', // 2.44 (Index: 51)
        ],
        [
            'item' => 'Guards protect ropes where liable to come in contact with conductors',
            'reference' => 'ASME B30.17 Sec.1.11.2(a)', // 2.45 (Index: 52)
        ],
        [
            'item' => 'Guards are provided to prevent contact between crane bridge or runway conductors and hoisting ropes.',
            'reference' => 'ASME B30.17 Sec.1.11.2(b)', // 2.46 (Index: 53)
        ],
        [
            'item' => 'Hand chain operated Hoist: Hoist automatically stops and holds lifted load when the actuating force is removed',
            'reference' => 'ASME B30.16 Sec.1.2.11a', // 2.47 (Index: 54)
        ],
        [
            'item' => 'Electric Powered Hoist: Braking system will stop and hold the load hook when controls are released under any load condition',
            'reference' => 'ASME B30.16 Sec.1.2.11(b1-b)', // 2.48 (Index: 55)
        ],
        [
            'item' => 'Air Powered Hoist: Braking system will stop and hold the load hook when controls are released under any load condition',
            'reference' => 'ASME B30.16 Sec.1.2.11(c1-a)', // 2.49 (Index: 56)
        ],
        [
            'item' => 'An electric hoist stops and holds the load block in the event of power failure',
            'reference' => 'ASME B30.16 Sec.1.2.11(b1-c)', // 2.50 (Index: 57)
        ],
        [
            'item' => 'An air hoist stops and holds the load block in the event of air pressure loose',
            'reference' => 'ASME B30.16 Sec.1.2.11(c1-b)', // 2.51 (Index: 58)
        ],
        [
            'item' => 'Braking systems has means for adjustment to compensate for wear',
            'reference' => 'ASME B30.16 Sec.1.2.11(b3/c)', // 2.52 (Index: 59)
        ],
        [
            'item' => 'Hoist rope is guarded from chafing where required',
            'reference' => 'ASME B30.2, Sec.1.14.6', // 2.53 (Index: 60)
        ],
        [
            'item' => 'Hook(s) can rotate freely',
            'reference' => 'ASME B30.2, Sec.1.14.5', // 2.54 (Index: 61)
        ],
        [
            'item' => 'Rope compensating sheave(s) (equalizer) is free to turn',
            'reference' => 'ASME B30.2, Sec.1.14.4', // 2.55 (Index: 62)
        ],
        [
            'item' => 'Surface condition of rope drum(s) show no defects and are smooth',
            'reference' => 'ASME B30.2, Sec.1.14.2', // 2.56 (Index: 63)
        ],
        [
            'item' => 'All sheave grooves are smooth',
            'reference' => 'ASME B30.2. Sec.1.14.1', // 2.57 (Index: 64)
        ],
        [
            'item' => 'All sheaves are free to turn',
            'reference' => 'ASME B30.2. Sec.1.14.1', // 2.58 (Index: 65)
        ],
        [
            'item' => 'Rope construction is as per manufacturer recommendations',
            'reference' => 'ASME B30.2, Sec.1.14.3a', // 2.59 (Index: 66)
        ],
        [
            'item' => 'Lower hoist limit cut-out (if fitted) is properly working',
            'reference' => 'ASME B30.2, Sec.1.13.5. e', // 2.60 (Index: 67)
        ],
        [
            'item' => 'Stops and bumpers are fitted to each end of the trolley(s)',
            'reference' => 'ASME B30.2, Sec.1.8.1, 3', // 2.61 (Index: 68)
        ],
        [
            'item' => 'Trolley truck rail sweeps are provided in front of the leading wheels on both ends of the trolley end truck',
            'reference' => 'ASME B30.2, Sec.1.9.2a', // 2.62 (Index: 69)
        ],
        [
            'item' => 'Clearance between the top surface of the rail head and the bottom of the sweep does not exceed 3⁄16" (5 mm)',
            'reference' => 'ASME B30.2, Sec.1.9.2b-1', // 2.63 (Index: 70)
        ],
        [
            'item' => 'The sweep extends below the top surface of the rail head, for a distance not less than 50% of the thickness of the rail head, on both sides of the rail head',
            'reference' => 'ASME B30.2, Sec.1.9.2b-2', // 2.64 (Index: 71)
        ],
        [
            'item' => 'Clearance between the side surface of the rail head and the side of the sweep which extends below the top surface of the rail head is equal to crane float plus 3⁄16"',
            'reference' => 'ASME B30.2, Sec.1.9.2b-3', // 2.65 (Index: 72)
        ],
        [
            'item' => 'Trolley(s) brakes are operable',
            'reference' => 'ASME B30.2, Sec.1.12.3', // 2.66 (Index: 73)
        ],
        [
            'item' => 'Trolley brakes comply with crane design requirements',
            'reference' => 'ASME B30.2, Sec.1.12.5', // 2.67 (Index: 74)
        ],
        [
            'item' => 'Trolley travel warnings (e.g. gong, beacon, bell or strop light) are operable',
            'reference' => 'ASME B30.2, Sec.1.15.1a', // 2.68 (Index: 75)
        ],
        [
            'item' => 'Unusual sounds are not present during trolley travel',
            'reference' => 'ASME B30.2, Sec.2.1.2a', // 2.69 (Index: 76)
        ],
        [
            'item' => 'Trolley has no missing or loose parts',
            'reference' => 'ASME B30.2, Sec.2.1.3b2', // 2.70 (Index: 77)
        ],
        [
            'item' => 'Trolley wheels have no sign of excessive wear',
            'reference' => 'ASME B30.2, Sec.2.1.3b4', // 2.71 (Index: 78)
        ],
        [
            'item' => 'Chain drive and sprocket have no wear or stretch',
            'reference' => 'ASME B30.2, Sec.2.1.3b6', // 2.72 (Index: 79)
        ],
        [
            'item' => 'All moving parts are correctly lubricated',
            'reference' => 'ASME B30.2, Sec.2.3.4', // 2.73 (Index: 80)
        ],
        [
            'item' => 'Crane Bridge stops within stipulated 10% distance of rated load speed under frictional forces (if no braking means provided)',
            'reference' => 'ASME B30.2, Sec.1.12.4a', // 2.74 (Index: 81)
        ],
        [
            'item' => 'Bridge brakes comply with crane design requirements',
            'reference' => 'ASME B30.2, Sec.1.12.5', // 2.75 (Index: 82)
        ],
        [
            'item' => 'Trolley truck frame drop is limited to 25mm',
            'reference' => 'ASME B30.2, Sec.1.11', // 2.76 (Index: 83)
        ],
        [
            'item' => 'Bridge is fitted with bumpers at each end',
            'reference' => 'ASME B30.2, Sec.1.8.2', // 2.77 (Index: 84)
        ],
        [
            'item' => 'Bridge rail sweep clearance is 5mm',
            'reference' => 'ASME B30.2, Sec.1.9.1', // 2.78 (Index: 85)
        ],
        [
            'item' => 'Bridge brakes capable of stopping the crane within 10% distance of rated load speed',
            'reference' => 'ASME B30.2, Sec.1.12.4', // 2.79 (Index: 86)
        ],
        [
            'item' => 'Bridge anchorage in place and withstand external forces, like strong winds (for outdoor cranes)',
            'reference' => 'ASME B30.2, Sec.1.3.1b', // 2.80 (Index: 87)
        ],
        [
            'item' => 'Runway columns are securely anchored to foundations',
            'reference' => 'ASME B30.2, Sec.1.3.2a-2', // 2.81 (Index: 88)
        ],
        [
            'item' => 'The runway structure is free from detrimental vibration under normal operating conditions',
            'reference' => 'ASME B30.2, Sec.1.3.2a-3', // 2.82 (Index: 89)
        ],
        [
            'item' => 'Rails are level, straight, joined, and spaced to the crane span within tolerances as per crane design',
            'reference' => 'ASME B30.2, Sec.1.3.2a-4', // 2.83 (Index: 90)
        ],
        [
            'item' => 'Runway stops are provided at the limits of travel of the bridge',
            'reference' => 'ASME B30.2, Sec.1.3.2b-1', // 2.84 (Index: 91)
        ],
        [
            'item' => 'Stops are designed to withstand the forces applied to the bumpers',
            'reference' => 'ASME B30.2, Sec.1.3.2b-3', // 2.85 (Index: 92)
        ],
        [
            'item' => 'Crane is clear from obstruction throughout its travel (between building walls and other cranes)',
            'reference' => 'ASME B30.2, Sec.1.2.19', // 2.86 (Index: 93)
        ],
        [
            'item' => 'All moving parts are correctly lubricated',
            'reference' => 'ASME B30.2, Sec.2.3.4', // 2.87 (Index: 94)
        ],
        [
            'item' => 'All moving parts are guarded where potential hazard would exist otherwise',
            'reference' => 'ASME B30.2, Sec.1.10a', // 2.88 (Index: 95)
        ],
        [
            'item' => 'Travel warnings are operational (gong, bell, siren, horn, beacon, or strop light)',
            'reference' => 'ASME B30.2, Sec.1.15.1a', // 2.89 (Index: 96)
        ],
        [
            'item' => 'Crane structure shows no deformed, cracked or corroded members',
            'reference' => 'ASME B30.2, Sec.2.1.3b1', // 2.90 (Index: 97)
        ],
        [
            'item' => 'All travel limit devices are functioning',
            'reference' => 'ASME B30.2, Sec.1.3b10', // 2.91 (Index: 98)
        ],
        [
            'item' => 'Safety labels are displayed and legible',
            'reference' => 'ASME B30.2, Sec.1.1.5', // 2.92 (Index: 99)
        ],
        [
            'item' => 'Integral outside platform is in place and door opens outward or slides',
            'reference' => 'ASME B30.2, Sec.1.5.2b', // 2.93 (Index: 100)
        ],
        [
            'item' => 'Trapdoor has a clear opening of not less than 610mm',
            'reference' => 'ASME B30.2, Sec.1.5.2e', // 2.94 (Index: 101)
        ],
        [
            'item' => 'Guard railings and toe boards are in good condition',
            'reference' => 'ASME B30.2, Sec.1.5.2f', // 2.95 (Index: 102)
        ],
        [
            'item' => 'All cab glazing’s are safety glazing materials',
            'reference' => 'ASME B30.2, Sec.1.5.2g', // 2.96 (Index: 103)
        ],
        [
            'item' => 'A tool box is in place for basic maintenance made of noncombustible material and is securely fastened in the cab or on the service platform.',
            'reference' => 'ASME 30.2, Sec.1.5.4', // 2.97 (Index: 104)
        ],
        [
            'item' => 'Fire extinguisher rated 10 BC is provided and in placed',
            'reference' => 'ASME B30.2, Sec.1.5.5', // 2.98 (Index: 105)
        ],
        [
            'item' => 'Lighting is adequate inside the cab and operator can clearly observe the controls',
            'reference' => 'ASME B30.2, Sec.1.5.6', // 2.99 (Index: 106)
        ],
    ],
    '3. INSPECTION POINTS' => [
        [
            'item' => 'Means of emergency exit are in place and effective',
            'reference' => 'ASME B30.2, Sec.1.7.3', // 3.0 (Index: 107)
        ],
        [
            'item' => 'Control circuit voltage does not exceed 600 volts (AC or DC)',
            'reference' => 'ASME B30.2, Sec.1.13.1b', // 3.1 (Index: 108)
        ],
        [
            'item' => 'Welded structures and members do not have cracks or corrosion',
            'reference' => 'ASME B30.2, Sec.1.4.1', // 3.2 (Index: 109)
        ],
        [
            'item' => 'Adequate clearances exist between two parallel crane bridges (if there are no intervening walls or structures)',
            'reference' => 'ASME B30.2, Sec.1.2.2', // 3.3 (Index: 110)
        ],
        [
            'item' => 'Minimum working space on service platforms is 1220mm (48")',
            'reference' => 'ASME B3O.2, Sec.1.7.1a', // 3.4 (Index: 111)
        ],
        [
            'item' => 'Minimum passageway on service platform is 457mm (18")',
            'reference' => 'ASME B3O.2, Sec.1.7.1c', // 3.5 (Index: 112)
        ],
        [
            'item' => 'Doors of electrical cabinets to open 90 degrees or be removable',
            'reference' => 'ASME B3O.2, Sec.1.7.1e', // 3.6 (Index: 113)
        ],
        [
            'item' => 'The crane controllers are equipped with spring return master switches',
            'reference' => 'ASME B30.2, Sec.1.13.3', // 3.7 (Index: 114)
        ],
        [
            'item' => 'Control circuit voltage does not exceed 600v for AC or DC',
            'reference' => 'ASME B30.17 Sec. 1.14.1(b)', // 3.8 (Index: 115)
        ],
        [
            'item' => 'Push button enclosure is grounded',
            'reference' => 'ASME B30.17 Sec. 1.14.1(e)', // 3.9 (Index: 116)
        ],
        [
            'item' => 'Push button enclosure is marked for identification of function',
            'reference' => 'ASME B30.17 Sec. 1.14.1(e)', // 3.10 (Index: 117)
        ],
        [
            'item' => 'Parts of electrical equipment are enclosed and are not exposed to inadvertent contact under normal operating conditions',
            'reference' => 'ASME B30.17 Sec. 1.14.2(a)', // 3.11 (Index: 118)
        ],
        [
            'item' => 'Live parts of electrical equipment are protected from direct exposure to grease and oil and protected from dirt and moisture',
            'reference' => 'ASME B30.17 Sec. 1.14.2(b)', // 3.12 (Index: 119)
        ],
        [
            'item' => 'Guards on live parts are not deformed or/and in contact',
            'reference' => 'ASME B30.17 Sec.1.14.2(c)', // 3.13 (Index: 120)
        ],
        [
            'item' => 'Floor operated cranes controllers return to off position when released',
            'reference' => 'ASME B30.17 Sec.1.14.3(c1)', // 3.14 (Index: 121)
        ],
        [
            'item' => 'Pendant push buttons that control motion return to off position when pressure is released',
            'reference' => 'ASME B30.17 Sec.1.14.3(c)', // 3.15 (Index: 122)
        ],
        [
            'item' => 'The resistors are supported and has minimum vibration effects',
            'reference' => 'ASME B30.2, Sec.-1.13.4', // 3.16 (Index: 123)
        ],
        [
            'item' => 'Runway conductors are guarded',
            'reference' => 'ASME B30.2, Sec.1.13.6', // 3.17 (Index: 124)
        ],
        [
            'item' => 'A separate magnet circuit switch of enclosed type is provided (if a lifting magnet is used)',
            'reference' => 'ASME B30.2, Sec.1.13.7a', // 3.18 (Index: 125)
        ],
        [
            'item' => 'Service receptacle in the cab or on the bridge is grounded type and does not exceed 300 volts (if provided)',
            'reference' => 'ASME B30.2, Sec.1.13.8', // 3.19 (Index: 126)
        ],
        [
            'item' => 'The control circuit voltage in pendant push buttons does not exceed 150V for AC or 300V for DC',
            'reference' => 'ASME B30.2, Sec.1.13.1c', // 3.20 (Index: 127)
        ],
        [
            'item' => 'A suspended push-button station is supported so that the electrical conductors are protected from strain (where multiple conductor cable is used)',
            'reference' => 'ASME B30.2, Sec.2-1.13.1d', // 3.21 (Index: 128)
        ],
        [
            'item' => 'Pendant control stations is constructed to prevent electrical shock',
            'reference' => 'ASME B30.2, Sec.1.13.1e', // 3.22 (Index: 129)
        ],
        [
            'item' => 'The push-button enclosure is at ground potential and marked for identification of functions)',
            'reference' => 'ASME B30.2, Sec.1.13.1e', // 3.23 (Index: 130)
        ],
        [
            'item' => 'Chain passes over all load sprockets without binding',
            'reference' => 'ASME B30.16 Sec.1.2.8', // 3.24 (Index: 131)
        ],
        [
            'item' => 'Hand Operated Chain: Chain length for extension (stretch) tolerance is no longer than 2.5% of unused chain or as per manufacturer recommendations',
            'reference' => 'ASME B30.16 Sec.2.5.2(a)', // 3.25 (Index: 132)
        ],
        [
            'item' => 'Power Operated Chain: Chain length for extension (stretch) tolerance is no longer than 1.5% of unused chain or as per manufacturer recommendations',
            'reference' => 'ASME B30.16 Sec.2.5.2(a)', // 3.26 (Index: 133)
        ],
        [
            'item' => 'The chain does not suffer from gouges, nicks, corrosion, weld spatter or distorted links (Judgement to be used as to the suitability or otherwise of using chain with these deficiencies)',
            'reference' => 'ASME B30.16 Sec.2.5.2(b)', // 3.27 (Index: 134)
        ],
        [
            'item' => 'The chain does not bind jump or gets noisy when hoist is operated',
            'reference' => 'ASME B30.16 Sec.2.6.1(b)', // 3.28 (Index: 135)
        ],
        [
            'item' => 'The chain is not stretched or elongated more than 1/4" (6.3 mm) in 12" (305 mm) with reference to the manufacturer\'s manual (roller chain)',
            'reference' => 'ASME B30.16 Sec.2.6.1(c1)', // 3.29 (Index: 136)
        ],
        [
            'item' => 'The chain is not twisted more than 15 degree in 5 ft (1.52 m) sections (roller chain)',
            'reference' => 'AASME B30.16 Sec.2.6.1(c2)', // 3.30 (Index: 137)
        ],
        [
            'item' => 'The roller chain pins, links and rollers move freely and are not corroded, pitted, discolored or damaged',
            'reference' => 'ASME B30.16 Sec.2.6.1(d)', // 3.31 (Index: 138)
        ],
        [
            'item' => 'Fitted sling or chain would be retained slack in the bowl of the hook where latches are provided',
            'reference' => 'ASME B30.16 Sec.1.2.9', // 3.32 (Index: 139)
        ],
        [
            'item' => 'Hand operated hoist: Load block is provided with a guard against load chain jamming in the load block under normal operating conditions',
            'reference' => 'ASME B30.16 Sec.1.2.10', // 3.33 (Index: 140)
        ],
        [
            'item' => 'Electric or Air Powered Hoist: Load block is of the enclosed type and means is provided to guard against rope or load chain jamming in the load block under normal operating conditions.',
            'reference' => 'ASME B30.16 Sec.1.2.10', // 3.34 (Index: 141)
        ],
        [
            'item' => 'Rope is free of damages • Max of 12 randomly broken wires in 1 lay • 4 broken wires in 1 strand of 1 lay • 1 broken wire protruding from the core (2 for rotation resistant ropes) • Wear of 1/3 of the original diameter of outside individual wires Kinking, crushing, bird caging or other distortion',
            'reference' => 'ASME B30.2, Sec.4.2(b)', // 3.35 (Index: 142)
        ],
        [
            'item' => 'Rope termination is completed at the hoist wedge anchor with a drop forged U- clip',
            'reference' => 'ASME B30.16 Sec 1.2.6', // 3.36 (Index: 143)
        ],
        [
            'item' => 'A rope thimble is used in the eye when an eye splice is used in a rope termination (in accordance with the manufacturer’s instructions)',
            'reference' => 'ASME B30.16 Sec.1.2.6', // 3.37 (Index: 144)
        ],
        [
            'item' => 'Electric and air powered hoists: Rope drum is grooved and free of surface defects that could cause rope damage (excluding hoists made for special applications',
            'reference' => 'ASME B30.16 Sec.1.2.5', // 3.38 (Index: 145)
        ],
        [
            'item' => 'Hoist drum is adequately lubricated as per the hoist manufacturers manual',
            'reference' => 'ASME B30.16 Sec.2.3.4', // 3.39 (Index: 146)
        ],
        [
            'item' => 'Drum capacity can accommodate the specific rope size and length',
            'reference' => 'ASME B30.7 Sec.1.2.2(c)', // 3.40 (Index: 147)
        ],
        [
            'item' => 'Drum has a minimum of two wraps of rope on it',
            'reference' => 'ASME B30.16 Sec.1.2.6(c)', // 3.41 (Index: 148)
        ],
        [
            'item' => 'Each drum end of the rope is anchored by a clamp attached to the drum or by a socket arrangement (approved by the manufacturer)',
            'reference' => 'ASME B30.7 Sec.1.2.2(c2)', // 3.42 (Index: 149)
        ],
        [
            'item' => 'Drum flanges always extend a minimum of 1/2" (13mm) above the top layer of rope at all times',
            'reference' => 'ASME B30.7 Sec.1.2.2(c3)', // 3.43 (Index: 150)
        ],
        [
            'item' => 'Labeling and manufacturer data are available and legible',
            'reference' => 'ASME B30.10 Sec.2.1.1', // 3.44 (Index: 151)
        ],
        [
            'item' => 'Hook is freely swiveling and lubricated',
            'reference' => 'ASME B30.16 Sec.1.2.9', // 3.45 (Index: 152)
        ],
        [
            'item' => 'Hook\'s weight is clearly marked/printed on the hook',
            'reference' => 'ASME B30.10 Sec.1.1.1', // 3.46 (Index: 153)
        ],
        [
            'item' => 'Safe working load is clearly marked on the hook',
            'reference' => 'ASME B30.10 Sec2.1.1 (10-2.1.1)', // 3.47 (Index: 154)
        ],
        [
            'item' => 'Hook is not bent or twisted Max. bending or twisting not to exceed 10 degrees from plane of unbent hook or as per manufacturer recommendations',
            'reference' => 'ASME B30.10 Sec1.2.1.3(c1)', // 3.48 (Index: 155)
        ],
        [
            'item' => 'Hook is not distorted in the throat opening Max. allowable throat opening is 15% compared to new hook, or as per manufacturer recommendations',
            'reference' => 'ASME B30.10 Sec.1.2.1.3(c2)', // 3.49 (Index: 156)
        ],
        [
            'item' => 'Maximum wear in the hook bowl is not exceeding 10% (compared to new hook) or as per manufacturer recommendations',
            'reference' => 'ASME B30.10 Sec.1.2.1.3(c3)', // 3.50 (Index: 157)
        ],
        [
            'item' => 'Maximum wear in the hook bowl is not exceeding 10% (compared to new hook) or as per manufacturer recommendations',
            'reference' => 'ASME B30.10 Sec.1.2.1.3(c3)', // 3.51 (Index: 158)
        ],
        [
            'item' => 'Hook is not cracked, gouged or shows nicks',
            'reference' => 'ASME B30.10 Sec1.2.1.2(c3)', // 3.52 (Index: 159)
        ],
        [
            'item' => 'Hook can lock (if it is a self-locking hook)',
            'reference' => 'ASME B30.10 Sec.1.2.1.3(c4)', // 3.53 (Index: 160)
        ],
        [
            'item' => 'Hook latch is operative',
            'reference' => 'ASME B30.10 Sec.1.2.1.3(c5)', // 3.54 (Index: 161)
        ],
        [
            'item' => 'Hook is free to rotate',
            'reference' => 'ASME B30.10 Sec1.2.1.3(c5)', // 3.55 (Index: 162)
        ],
    ],
];
$index = 0;
foreach ($sections as $sectionTitle => $items) {
    echo "<tr><td colspan='7' class='section'>" . htmlspecialchars($sectionTitle) . "</td></tr>";
    $itemNo = (strpos($sectionTitle, '3.') === 0) ? 0 : 1;
    foreach ($items as $itemData) {
        $item = $itemData['item'];
        $reference = $itemData['reference'];
        $sn = explode('. ', $sectionTitle, 2)[0] . '.' . $itemNo;
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
        $itemNo++;
    }
}
?>
</tbody>
</table>

<div class="footer-section">
<div class="keep-together">

<table>
<tr>
    <th style="text-align:left;">REMARKS / RECOMMENDATIONS</th>
</tr>
<tr>
    <td style="height:70px;"><?= htmlspecialchars($recommendations) ?></td>
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
