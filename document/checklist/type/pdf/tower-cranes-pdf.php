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
    padding: 5px 4px;
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
    padding: 5px;
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
    margin-top: 15px;
}

.signature-table th {
    background-color: #c0d6e8;
    font-size: 8px;
    text-align: center;
    padding: 5px;
}

.signature-table td {
    text-align: center;
    vertical-align: top;
    padding: 5px 4px;
    height: 50px;
}

.signature-name {
    font-size: 8px;
    font-weight: bold;
    margin-bottom: 2px;
}

.signature-box {
    height: 25px;
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
    font-size: 10px;
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

<h4 style="text-align: center;">
TOWER CRANES<br>
ASME B30.3-2016
</h4>

<br>

<table>
<tr>
    <th width="20%">REPORT NO</th>
    <td width="20%" style="text-align:center;"><?= htmlspecialchars($row['report_no'] ?? '') ?></td>
    <th width="20%">DATE</th>
    <td width="20%" style="text-align:center;"><?= htmlspecialchars($row['inspection_date'] ?? '') ?></td>
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
    <th width="42%">ACCEPTANCE CRITERIA</th>
    <th width="12%">REFERENCE</th>
    <th width="8%">PASS</th>
    <th width="8%">FAIL</th>
    <th width="8%">NA</th>
    <th width="16%">REMARKS</th>
</tr>
</thead>
<tbody>
<?php
$sections = [
    '1. MARKINGS, DOCUMENTS' => [
        [
            'num' => '1.1',
            'item' => 'Documentation is available such as but not limited to; operator’s manual, manufacturer’s informal literature, etc.',
            'reference' => 'ASME B30.3 sec.1.9(a)', // Index: 0
        ],
        [
            'num' => '1.2',
            'item' => 'An installation preparation instruction is provided.',
            'reference' => 'ASME B30.3 sec.1.9.1(a)', // Index: 1
        ],
        [
            'num' => '1.3',
            'item' => 'Structure or anchor has the information data plate bearing the Manufacturer Name, Type/Model Number, Serial Number, & Year of Manufacture.',
            'reference' => 'ASME B30.3 sec 1.5(h)(2)', // Index: 2
        ],
        [
            'num' => '1.4',
            'item' => 'Structure has an identification number / asset number marked on it.',
            'reference' => 'CIMS QHSE 06', // Index: 3
        ],
        [
            'num' => '1.5',
            'item' => 'Crane’s SWL (Rated Load) is prominently marked on the structure.',
            'reference' => 'CIMS QHSE 06', // Index: 4
        ],
        [
            'num' => '1.6',
            'item' => 'Load Rating chart of the crane is provided.',
            'reference' => 'ASME B30.3 sec 1.9.2', // Index: 5
        ],
        [
            'num' => '1.7',
            'item' => 'General erection & dismantling requirements are met (Drawings & Calculations).',
            'reference' => 'ASME B30.3 sec 1.2-4', // Index: 6
        ],
        [
            'num' => '1.8',
            'item' => 'The crane is operated by the qualified, competent, or certified operator.',
            'reference' => 'ASME B30.3 sec 3.1.1(a-1,2,3,4(b)', // Index: 7
        ],
    ],
    '2. INSPECTION & TESTING' => [
        [
            'num' => '2.1',
            'item' => 'Structures such as but not limited to, tower masts, knee braces, cross beams, climbing ladders, climbing cross sections have no signs of cracks, corrosions, bends, deformations.',
            'reference' => 'ASME B30.3 sec 1.6.1', // Index: 8
        ],
        [
            'num' => '2.2',
            'item' => 'Tie-in braces and pins are secured.',
            'reference' => 'ASME B30.3 sec 1.6.1', // Index: 9
        ],
        [
            'num' => '2.3',
            'item' => 'Climbing pawls and wedges are secured.',
            'reference' => 'ASME B30.3 sec 1.6.2', // Index: 10
        ],
        [
            'num' => '2.4',
            'item' => 'Tower’s anchor bolts at base are properly mounted and secured.',
            'reference' => 'ASME B30.3 sec 1.3', // Index: 11
        ],
        [
            'num' => '2.5',
            'item' => 'Expendable base and knee-braced base are installed properly.',
            'reference' => 'ASME B30.3 sec 1.5', // Index: 12
        ],
        [
            'num' => '2.6',
            'item' => 'Load & luffing/jib boom hoist drives are provided with a clutch or power disengaging device unless directly coupled to an electric or hydraulic power motor source.',
            'reference' => 'ASME B30.3 Sec 1.7', // Index: 13
        ],
        [
            'num' => '2.7',
            'item' => 'All functions are checked and working correctly, i.e., but not limited to, luffing/jib booms’ hoisting & lowering, structure’s slewing, load block’s lowering & hoisting, trolley traversing.',
            'reference' => 'ASME B30.3 Sec 1.7(3.a-f)', // Index: 14
        ],
        [
            'num' => '2.8',
            'item' => 'Motion limiting devices and brakes of load hoist, luffing/jib boom hoist are checked.',
            'reference' => 'ASME B30.3 Sec 1.7(4.a-c)', // Index: 15
        ],
        [
            'num' => '2.9',
            'item' => 'All controls, drives, and braking means devices are checked which include; load block hoisting & lowering; luffing boom hoisting and lowering; swinging of the upper structure; brake and clutch functioning; limit, locking, and safety device functioning; and load-limiting devices for proper operation.',
            'reference' => 'ASME B30.3 Sec 1.7(4.a-c)', // Index: 16
        ],
        [
            'num' => '2.10',
            'item' => 'Over-speed protection is provided for hoist and luffing boom mechanisms.',
            'reference' => 'ASME B30.3 Sec 1.10(c)', // Index: 17
        ],
        [
            'num' => '2.11',
            'item' => 'Luffing/jib boom and load hoist free-fall lowering is not provided. Ensure that they shall be done only under power control.',
            'reference' => 'ASME B30.3 Sec 1.10(c)', // Index: 18
        ],
        [
            'num' => '2.12',
            'item' => 'Luffing/jib boom hoist powered by hydraulic is not dropping.',
            'reference' => 'ASME B30.3 sec 1.10(3)', // Index: 19
        ],
        [
            'num' => '2.13',
            'item' => 'Luffing boom back stop switch is provided for the maximum boom angle.',
            'reference' => 'ASME B30.3 sec 1.10(4)', // Index: 20
        ],
        [
            'num' => '2.14',
            'item' => 'The luffing/jib hoist rope is securely anchored on the drum as per the manufacturer recommendation.',
            'reference' => 'ASME B30.3 sec 1.10.2 (a)', // Index: 21
        ],
        [
            'num' => '2.15',
            'item' => 'The diameter of the drum is sufficient to provide a first layer rope pitch diameter of not less than 18 times the nominal diameter of the rope used.',
            'reference' => 'ASME B30.3 sec 1.10(c)', // Index: 22
        ],
        [
            'num' => '2.16',
            'item' => 'The remaining rope on load hoist drum shall not be less than three full wraps when the hook is in its extreme lowest position.',
            'reference' => 'ASME B30.3 sec 1.10(d)', // Index: 23
        ],
        [
            'num' => '2.17',
            'item' => 'The remaining rope on luffing/jib boom hoist shall not be less than three full wraps when the luffing/jib boom is at its maximum permissible radius.',
            'reference' => 'ASME B30.3 sec 1.10(e)', // Index: 24
        ],
        [
            'num' => '2.18',
            'item' => 'Load hoist drum and luffing boom hoist drums are provided with a positive holding device, such as ratchets and pawls, unless directly coupled to electric or hydraulic drives.',
            'reference' => 'ASME B30.3 sec 1.10(f)', // Index: 25
        ],
        [
            'num' => '2.19',
            'item' => 'Positive holding devices are controlled only from the operator’s station; hold the drums from rotating in the lowering direction, and capable of holding the rated load indefinitely, or luffing boom and rated load indefinitely, as applicable without further attention from the operator.',
            'reference' => 'ASME B30.3 sec 1.10(g)', // Index: 26
        ],
        [
            'num' => '2.20',
            'item' => 'Luffing boom hoist rope and load hoist rope shall be equipped with at least one braking means that is capable of providing minimum of 125 % of the full load hoisting torque at the point of where the braking is applied.',
            'reference' => 'ASME B30.3 sec 1.10.3(a)', // Index: 27
        ],
        [
            'num' => '2.21',
            'item' => 'A secondary emergency brake is provided on the luffing boom hoist drum for use in the event of a main drive failure.',
            'reference' => 'ASME B30.3 sec 1.10.3(a)', // Index: 28
        ],
        [
            'num' => '2.22',
            'item' => 'Load hoist and luffing boom hoist mechanisms are equipped with braking means capable of providing controlled lowering speeds.',
            'reference' => 'ASME B30.3 sec 1.10.3(b)', // Index: 29
        ],
        [
            'num' => '2.23',
            'item' => 'An automatic means is provided for controlling the load hoist or the luffing boom hoist to stop and hold the load in the event of loss of brake actuating power.',
            'reference' => 'ASME B30.3 sec 1.10.3(c)', // Index: 30
        ],
        [
            'num' => '2.24',
            'item' => 'If foot pedal is provided, it is holding the brakes in the applied position without further attention from the operator.',
            'reference' => 'ASME B30.3 sec 1.10.3(c)', // Index: 31
        ],
        [
            'num' => '2.25',
            'item' => 'Sheave bearings are provided with a means for lubrication, except for those that are permanently lubricated.',
            'reference' => 'ASME B30.3 sec 1.10.4(c)', // Index: 32
        ],
        [
            'num' => '2.26',
            'item' => 'The pitch diameter of the load block sheaves are not less than 18 times the nominal diameter of the rope used.',
            'reference' => 'ASME B30.3 sec 1.10.4(d)', // Index: 33
        ],
        [
            'num' => '2.27',
            'item' => 'The pitch diameter of luffing boom hoist sheaves are not less than 15 times the nominal diameter of the rope used.',
            'reference' => 'ASME B30.3 sec 1.10.4(d)', // Index: 34
        ],
        [
            'num' => '2.28',
            'item' => 'The load block sheaves are equipped with close fitting guard to prevent ropes from becoming fouled when the block is lying on the ground.',
            'reference' => 'ASME B30.3 sec 1.10.4(e)', // Index: 35
        ],
        [
            'num' => '2.29',
            'item' => 'Rope end socketing is as per the manufacturer.',
            'reference' => 'ASME B30.3 sec 1.10.5(g)', // Index: 36
        ],
        [
            'num' => '2.30',
            'item' => 'Rotation-resistant rope is not used for luffing boom hoist.',
            'reference' => 'ASME B30.3 sec 1.10.5(h)', // Index: 37
        ],
        [
            'num' => '2.31',
            'item' => 'Design factor for luffing boom hoist rope is not less than 3.5',
            'reference' => 'ASME B30.3 sec 1.10.5(c)', // Index: 38
        ],
        [
            'num' => '2.32',
            'item' => 'Design factor for load hoist rope is not less than 5.',
            'reference' => 'ASME B30.3 sec 1.10.5(b)', // Index: 39
        ],
        [
            'num' => '2.33',
            'item' => 'Load hook is equipped with safety latches and working properly.',
            'reference' => 'ASME B30.3 sec 1.11(a), ASME B30.10 sec 1 & 5 (i)', // Index: 40
        ],
        [
            'num' => '2.34',
            'item' => 'No pitting or corrosion is visible.',
            'reference' => 'ASME B30.10 sec 1 & 5(c)', // Index: 41
        ],
        [
            'num' => '2.35',
            'item' => 'No signs of cracks, nicks, or gouges are visible.',
            'reference' => 'ASME B30.10 sec 1 & 5(d)', // Index: 42
        ],
        [
            'num' => '2.36',
            'item' => 'Load hook is marked with its SWL and weight.',
            'reference' => 'ASME B30.10 sec 1 & 5(a)', // Index: 43
        ],
        [
            'num' => '2.37',
            'item' => 'The wear on the hook does not exceed 10% from the original.',
            'reference' => 'ASME B30.10 sec 1 & 5(e)', // Index: 44
        ],
        [
            'num' => '2.38',
            'item' => 'There is no deformation that is visibly apparent bend or twist from the plane of the unbent hook.',
            'reference' => 'ASME B30.10 sec 1 & 5(f)', // Index: 45
        ],
        [
            'num' => '2.39',
            'item' => 'No any distortion causing an increase in the throat opening of 5% that exceeded ¼ in. (6mm) or as recommended by the manufacturer.',
            'reference' => 'ASME B30.10 sec 1 & 5(g)', // Index: 46
        ],
        [
            'num' => '2.40',
            'item' => 'Self-locking hook is able to lock.',
            'reference' => 'ASME B30.10 sec 1 & 5(h)', // Index: 47
        ],
        [
            'num' => '2.41',
            'item' => 'No damaged, missing, or malfunctioning hook attachment.',
            'reference' => 'ASME B30.10 sec 1 & 5(j)', // Index: 48
        ],
        [
            'num' => '2.42',
            'item' => 'No thread wear or corrosion is evident.',
            'reference' => 'ASME B30.10 sec 1 & 5(k)', // Index: 49
        ],
        [
            'num' => '2.43',
            'item' => 'No evidence of heat exposure or unauthorized welding.',
            'reference' => 'ASME B30.10 sec 1 & 5(l)', // Index: 50
        ],
        [
            'num' => '2.44',
            'item' => 'No evidence of unauthorized alteration such as drilling, machining, grinding or other modifications.',
            'reference' => 'ASME B30.10 sec 1 & 5(m)', // Index: 51
        ],
        [
            'num' => '2.45',
            'item' => 'Swing mechanism is capable of smooth starts and stops and of providing variable degrees of acceleration and deceleration.',
            'reference' => 'ASME B30.3 sec 1.12.1(a)', // Index: 52
        ],
        [
            'num' => '2.46',
            'item' => 'Crane is equipped with means to rotate freely when it is out of service in order to weathervane.',
            'reference' => 'ASME B30.3 sec 1.1.1(b)', // Index: 53
        ],
        [
            'num' => '2.47',
            'item' => 'Braking means with holding power in both directions is provided.',
            'reference' => 'ASME B30.3 sec 1.12.2(a)', // Index: 54
        ],
        [
            'num' => '2.48',
            'item' => 'Brakes apply automatically when electrical power or actuating force is lost.',
            'reference' => 'ASME B30.3 sec 1.12.2(b)', // Index: 55
        ],
        [
            'num' => '2.49',
            'item' => 'Travel drives are capable of smooth starts and stops, and providing variable degrees of acceleration and deceleration.',
            'reference' => 'ASME B30.3 sec 1.13.1(a)', // Index: 56
        ],
        [
            'num' => '2.50',
            'item' => 'Cable spooling is provided.',
            'reference' => 'ASME B30.3 sec 1.13.1(b)', // Index: 57
        ],
        [
            'num' => '2.51',
            'item' => 'Audible signal automatically sounds continuously whenever the crane travels.',
            'reference' => 'ASME B30.3 sec 1.13.1(c)', // Index: 58
        ],
        [
            'num' => '2.52',
            'item' => 'Crane bogies are fitted with sweeps at each end of the bogie and extending below the top of the rail.',
            'reference' => 'ASME B30.3 sec 1.13.2(a)', // Index: 59
        ],
        [
            'num' => '2.53',
            'item' => 'Bogie wheels are guarded.',
            'reference' => 'ASME B30.3 sec 1.13.2(b)', // Index: 60
        ],
        [
            'num' => '2.54',
            'item' => 'Means are provided to limit the drop of bogie frames to a distance that will not cause the crane to overturn in case of wheel or axle breakage.',
            'reference' => 'ASME B30.3 sec 1.13.2(c)', // Index: 61
        ],
        [
            'num' => '2.55',
            'item' => 'Braking means are provided to hold the crane In position when not travelling and to lock the wheels against rotation.',
            'reference' => 'ASME B30.3 sec 1.13.3(a)', // Index: 62
        ],
        [
            'num' => '2.56',
            'item' => 'Brakes automatically engaged on loss of electrical power or actuating force to the brake.',
            'reference' => 'ASME B30.3 sec 1.13.3(b)', // Index: 63
        ],
        [
            'num' => '2.57',
            'item' => 'Guides are provided to hold the ladders in position for engagement of the climbing dogs.',
            'reference' => 'ASME B30.3 sec 1.14.(a)', // Index: 64
        ],
        [
            'num' => '2.58',
            'item' => 'Hydraulic cylinders used to support the crane during climbing are equipped with check valves.',
            'reference' => 'ASME B30.3 sec 1.14.(b)1', // Index: 65
        ],
        [
            'num' => '2.59',
            'item' => 'Hydraulic system is provided with pressure gauges and over pressure relief valves.',
            'reference' => 'ASME B30.3 sec 1.14.(b)3', // Index: 66
        ],
        [
            'num' => '2.60',
            'item' => 'Positive means to hold the raised portion of the crane in position at the completion of an intermediate climbing step.',
            'reference' => 'ASME B30.3 sec 1.14.(c)', // Index: 67
        ],
        [
            'num' => '2.61',
            'item' => 'Pressurized hydraulic cylinders are not used to support the crane when in service.',
            'reference' => 'ASME B30.3 sec 1.14.(c)', // Index: 68
        ],
        [
            'num' => '2.62',
            'item' => 'Wedges when used shall be provided with means to hold them in place and prevent them from becoming dislodged.',
            'reference' => 'ASME B30.3 sec 1.14.(d)', // Index: 69
        ],
        [
            'num' => '2.63',
            'item' => 'Ropes have a minimum breaking force not less than 3.5 times the load applied to the rope.',
            'reference' => 'ASME B30.3 sec 1.14.(e)', // Index: 70
        ],
        [
            'num' => '2.64',
            'item' => 'Trolley is capable of smooth starts and stops and providing variable degrees of acceleration and deceleration when traversing the jib during operations.',
            'reference' => 'ASME B30.3 sec 1.15.(a)', // Index: 71
        ],
        [
            'num' => '2.65',
            'item' => 'Trolley stops or buffers are provided on both ends of the jib.',
            'reference' => 'ASME B30.3 sec 1.15.(b)', // Index: 72
        ],
        [
            'num' => '2.66',
            'item' => 'The body or frame of the trolley is fitted with means to retrain the trolley from becoming detached from its guide rails.',
            'reference' => 'ASME B30.3 sec 1.15.(c)', // Index: 73
        ],
        [
            'num' => '2.67',
            'item' => 'Braking means is provided and capable of stopping in both directions.',
            'reference' => 'ASME B30.3 sec 1.15.(d)', // Index: 74
        ],
        [
            'num' => '2.68',
            'item' => 'A brake is holding the trolley without further action when power or pressure is lost.',
            'reference' => 'ASME B30.3 sec 1.15.(d)', // Index: 75
        ],
        [
            'num' => '2.69',
            'item' => 'Trolley is equipped with an automatic braking device in case of the rope breakage.',
            'reference' => 'ASME B30.3 sec 1.15.(e)', // Index: 76
        ],
    ],
    '3. OPERATOR AIDS' => [
        [
            'num' => '3.1',
            'item' => 'Indicating device shall be provided to display the load on the hook.',
            'reference' => 'ASME B30.3 sec 1.17.(a)1', // Index: 77
        ],
        [
            'num' => '3.2',
            'item' => 'Indicating device shall be provided to display the luffing boom angle, hook radius, or trolley operating radius, as appropriate.',
            'reference' => 'ASME B30.3 sec 1.17.(a)2', // Index: 78
        ],
        [
            'num' => '3.3',
            'item' => 'Indicating device shall be provided to display the ambient wind velocity',
            'reference' => 'ASME B30.3 sec 1.17.(a)3', // Index: 79
        ],
        [
            'num' => '3.4',
            'item' => 'Limiting device shall be provided to decelerate the trolley travel at both ends of the jib prior to final limit activation.',
            'reference' => 'ASME B30.3 sec 1.17.(b)1', // Index: 80
        ],
        [
            'num' => '3.5',
            'item' => 'Limiting device shall be provided to decelerate the luffing boom travel at minimum and maximum radius prior to final limit activation.',
            'reference' => 'ASME B30.3 sec 1.17.(b)2', // Index: 81
        ],
        [
            'num' => '3.6',
            'item' => 'Limiting device shall limit trolley travel at both ends of the jib.',
            'reference' => 'ASME B30.3 sec 1.17.(b)3', // Index: 82
        ],
        [
            'num' => '3.7',
            'item' => 'Limiting device shall stop the luffing boom travel at minimum and maximum radius of luffing boom.',
            'reference' => 'ASME B30.3 sec 1.17.(b)4', // Index: 83
        ],
        [
            'num' => '3.8',
            'item' => 'Limiting device shall decelerate the load block travel prior to final limit activation.',
            'reference' => 'ASME B30.3 sec 1.17.(b)5', // Index: 84
        ],
        [
            'num' => '3.9',
            'item' => 'Limiting device shall stop load block upward motion before two blocking occurs.',
            'reference' => 'ASME B30.3 sec 1.17.(b)6', // Index: 85
        ],
        [
            'num' => '3.10',
            'item' => 'Limiting device shall stop load block downward motion to prevent from spooling off the drum.',
            'reference' => 'ASME B30.3 sec 1.17.(b)7', // Index: 86
        ],
        [
            'num' => '3.11',
            'item' => 'Limiting device shall limit the crane travel at both ends of the running tracks.',
            'reference' => 'ASME B30.3 sec 1.17.(b)8', // Index: 87
        ],
        [
            'num' => '3.12',
            'item' => 'Limiting device shall limit the load lifted.',
            'reference' => 'ASME B30.3 sec 1.17.(b)9', // Index: 88
        ],
        [
            'num' => '3.13',
            'item' => 'Limiting device shall limit operating radius in accordance with crane’s rated capacity, i.e. load moment.',
            'reference' => 'ASME B30.3 sec 1.17.(b)10', // Index: 89
        ],
        [
            'num' => '3.14',
            'item' => 'Limiting device shall limit pressures in hydraulic or pneumatic circuits.',
            'reference' => 'ASME B30.3 sec 1.17.(b)11', // Index: 90
        ],
        [
            'num' => '3.15',
            'item' => 'Motion limiting devices, should be provided with means to permit the operator to override them under controlled conditions.',
            'reference' => 'ASME B30.3 sec 1.17.(c)', // Index: 91
        ],
    ],
    '4. PENDANTS, STAY ROPES, AND GUYS, COUNTERWEIGHTS, COUNTER JIBS' => [
        [
            'num' => '4.1',
            'item' => 'Fiber core ropes with swayed fittings and rotation-resistant ropes shall not be used for pendants, guy ropes and stay ropes.',
            'reference' => 'ASME B30.3 sec 1.18.(a)', // Index: 92
        ],
        [
            'num' => '4.2',
            'item' => 'Rotation-resistant ropes shall be used for luffing boom.',
            'reference' => 'ASME B30.3 sec 1.18.(a)', // Index: 93
        ],
        [
            'num' => '4.3',
            'item' => 'Wire rope clips are drop-forged steel of the single (U-bolt) or double saddle type clip.',
            'reference' => 'ASME B30.3 sec 1.19.(d)', // Index: 94
        ],
        [
            'num' => '4.4',
            'item' => 'Means to prevent the shifting or dislodgement of superstructure and counterjib’s counter weight during crane operation is provided.',
            'reference' => 'ASME B30.3 sec 1.20.(a)', // Index: 95
        ],
        [
            'num' => '4.5',
            'item' => 'Counterweights and ballast blocks are individually marked with their actual weights and visible when they are in installed position.',
            'reference' => 'ASME B30.3 sec 1.20.(b)', // Index: 96
        ],
        [
            'num' => '4.6',
            'item' => 'Only steel-framed concrete or solid steel counterweights suspended from the superstructure are used.',
            'reference' => 'ASME B30.3 sec 1.20.(c)', // Index: 97
        ],
        [
            'num' => '4.7',
            'item' => 'Movable counterweights, if provided, are moving automatically.',
            'reference' => 'ASME B30.3 sec 1.20.(d)', // Index: 98
        ],
        [
            'num' => '4.8',
            'item' => 'Means to prevent uncontrolled movement in the event of rope failure for counterweights controlled by ropes is provided.',
            'reference' => 'ASME B30.3 sec 1.20.(d)1', // Index: 99
        ],
        [
            'num' => '4.9',
            'item' => 'Controls are within the reach of the operator.',
            'reference' => 'ASME B30.3 sec 1.21.1(a)', // Index: 100
        ],
        [
            'num' => '4.10',
            'item' => 'All controls are labeled of their mode of functions.',
            'reference' => 'ASME B30.3 sec 1.21.1(b)', // Index: 101
        ],
        [
            'num' => '4.11',
            'item' => 'Hoisting, trolleying, luffing, slewing, and travel motions are stopping when control actuation pressure is released.',
            'reference' => 'ASME B30.3 sec 1.21.1(c)', // Index: 102
        ],
        [
            'num' => '4.12',
            'item' => 'An interlock that prevents the re-actuation, except from the neutral position, of controls is provided.',
            'reference' => 'ASME B30.3 sec 1.21.1(c)', // Index: 103
        ],
        [
            'num' => '4.13',
            'item' => 'The crane stops when signal is lost for remote operated cranes.',
            'reference' => 'ASME B30.3 sec 1.21.1(d)', // Index: 104
        ],
        [
            'num' => '4.14',
            'item' => 'The device that will disconnect all motors from the line on failure of power and will not permit any motor to be restarted until the operational control is brought to the neutral position and a manual reset is activated is provided for electric motor powered cranes.',
            'reference' => 'ASME B30.3 sec 1.21.1(e)', // Index: 105
        ],
        [
            'num' => '4.15',
            'item' => 'An electric motor powered crane is provided with means for operator to interrupt the main power circuit from the operating position.',
            'reference' => 'ASME B30.3 sec 1.21.1(f)', // Index: 106
        ],
        [
            'num' => '4.16',
            'item' => 'A remote control station is provided with emergency stop button.',
            'reference' => 'ASME B30.3 sec 1.21.1(g)', // Index: 107
        ],
        [
            'num' => '4.17',
            'item' => 'Simultaneous activation of controls is not possible when more than one operator’s station (remote control) is provided.',
            'reference' => 'ASME B30.3 sec 1.21.1(h)', // Index: 108
        ],
        [
            'num' => '4.18',
            'item' => 'Cranes powered by hydraulic motors shall stop the main power supply system when hydraulic pressure is lost.',
            'reference' => 'ASME B30.3 sec 1.21.1(i)', // Index: 109
        ],
        [
            'num' => '4.19',
            'item' => 'Controls for the main power supply system shall be within the reach of the operator, and will include the following: controlling the speed of the engine, means to control in stopping the engine, means for shifting the transmission’s gear selection.',
            'reference' => 'ASME B30.3 sec 1.21.2(a)1,2,3,4', // Index: 110
        ],
        [
            'num' => '4.20',
            'item' => 'Cabs should be provided for the operator’s station.',
            'reference' => 'ASME B30.3 sec 1.23.1(a)', // Index: 111
        ],
        [
            'num' => '4.21',
            'item' => 'Cab doors are opening outward or sliding.',
            'reference' => 'ASME B30.3 sec 1.23.1(d)', // Index: 112
        ],
        [
            'num' => '4.22',
            'item' => 'An adjustable operator seat is provided.',
            'reference' => 'ASME B30.3 sec 1.23.1(b)', // Index: 113
        ],
        [
            'num' => '4.23',
            'item' => 'Windshield is of safety glazing glass.',
            'reference' => 'ASME B30.3 sec 1.23.1(e)', // Index: 114
        ],
        [
            'num' => '4.24',
            'item' => 'The operator cab shall be on the operating portion of the crane.',
            'reference' => 'ASME B30.3 sec 1.23.1(g)', // Index: 115
        ],
        [
            'num' => '4.25',
            'item' => 'An access ladder to the cab is provided.',
            'reference' => 'ASME B30.3 sec 1.23.2(a)', // Index: 116
        ],
        [
            'num' => '4.26',
            'item' => 'Outside platforms have walking surfaces of a skid resistant type.',
            'reference' => 'ASME B30.3 sec 1.23.2(b)', // Index: 117
        ],
        [
            'num' => '4.27',
            'item' => 'Tool box is available for storage of small tools.',
            'reference' => 'ASME B30.3 sec 1.23.3', // Index: 118
        ],
        [
            'num' => '4.28',
            'item' => 'Fire extinguisher with a basic minimum classification of 10-BC is provided in the cab or at the machinery housing..',
            'reference' => 'ASME B30.3 sec 1.23.4', // Index: 119
        ],
        [
            'num' => '4.29',
            'item' => 'Footwalks and ladders: 18in. or more in width and a slip resistant surface and with handrails or a platform attached to the trolley having a slip resistant surface and handrails.ded',
            'reference' => 'ASME B30.3 sec 1.24.1', // Index: 120
        ],
        [
            'num' => '4.30',
            'item' => 'Guards are installed for exposed moving parts such as gears, drive chains, sprockets, and other rotating parts.',
            'reference' => 'ASME B30.3 sec 1.24.2(a)', // Index: 121
        ],
        [
            'num' => '4.31',
            'item' => 'Each guard shall be capable of supporting the weight of a 300-lb (136 kg) person without permanent distortion.',
            'reference' => 'ASME B30.3 sec 1.24.2(b)', // Index: 122
        ],
        [
            'num' => '4.32',
            'item' => 'Lubrication points should be accessible without the necessity of removing guards or other parts with tools unless equipped with centralized lubrication.',
            'reference' => 'ASME B30.3 sec 1.24.3', // Index: 123
        ],
        [
            'num' => '4.33',
            'item' => 'Engine exhaust gas is to be piped and discharged away from the operator’s cabin.',
            'reference' => 'ASME B30.3 sec 1.24.4', // Index: 124
        ],
        [
            'num' => '4.34',
            'item' => 'Dry friction clutches are protected against rain and other liquids, such as oil and lubricants.',
            'reference' => 'ASME B30.3 sec 1.24.6(a)', // Index: 125
        ],
        [
            'num' => '4.35',
            'item' => 'Clutches are configured to permit adjustments where necessary to compensate wear.',
            'reference' => 'ASME B30.3 sec 1.24.6(b)', // Index: 126
        ],
        [
            'num' => '4.36',
            'item' => 'An anemometer is installed.(Wind Velocity Device)',
            'reference' => 'ASME B30.3 sec 1.24.7', // Index: 127
        ],
        [
            'num' => '4.37',
            'item' => 'Fuel tank filler pipes are located or protected to prevent spillage or overflow.',
            'reference' => 'ASME B30.3 sec 1.24.8', // Index: 128
        ],
        [
            'num' => '4.38',
            'item' => 'Relief valves are provided in hydraulic and pneumatic circuits carrying fluids pressurized by a power driven pump.',
            'reference' => 'ASME B30.3 sec 1.24.9(a)', // Index: 129
        ],
        [
            'num' => '4.39',
            'item' => 'Means to prevent unauthorized adjustment or tampering is provided.',
            'reference' => 'ASME B30.3 sec 1.24.9(b)', // Index: 130
        ],
        [
            'num' => '4.40',
            'item' => 'Means for checking the manufacturer’s specified pressure settings in each circuit is provided.',
            'reference' => 'ASME B30.3 sec 1.24.9(c)', // Index: 131
        ],
        [
            'num' => '4.41',
            'item' => 'Ropes have no loss of rope diameter in a short rope length or unevenness of outer strands.',
            'reference' => 'ASME B30.3 sec 2.4.1.2(a)', // Index: 132
        ],
        [
            'num' => '4.42',
            'item' => 'Rope has broken or cut strands.',
            'reference' => 'ASME B30.3 sec 2.4.1.2(b)(c)', // Index: 133
        ],
        [
            'num' => '4.43',
            'item' => 'In running ropes, 12 randomly distributed broken wires in one lay, or four broken wires in one strand in one lay.',
            'reference' => 'ASME B30.3 sec 2.4.3(b)1 g', // Index: 134
        ],
        [
            'num' => '4.44',
            'item' => 'In rotation-resistant ropes, two randomly distributed broken wires in six rope diameters, or four randomly distributed broken wires in 30 rope diameters.',
            'reference' => 'ASME B30.3 sec 2.4.3(b)2', // Index: 135
        ],
        [
            'num' => '4.45',
            'item' => 'One outer wire broken at the contact point with the core of the rope indicated by an externally protruding wire or loop of loose wires.',
            'reference' => 'ASME B30.3 sec 2.4.3(b)3', // Index: 136
        ],
        [
            'num' => '4.46',
            'item' => 'Wear of one-third the original diameter of outside individual wires.',
            'reference' => 'ASME B30.3 sec 2.4.3(b)4', // Index: 137
        ],
        [
            'num' => '4.47',
            'item' => 'Kinking, crushing, birdcaging, or any other damage resulting to distortion of the rope structure.',
            'reference' => 'ASME B30.3 sec 2.4.3(b)5', // Index: 138
        ],
        [
            'num' => '4.48',
            'item' => 'Evidence of heat damage from any cause.',
            'reference' => 'ASME B30.3 sec 2.4.3(b)6', // Index: 139
        ],
        [
            'num' => '4.49',
            'item' => 'Reduction from nominal diameter greater than 5%.',
            'reference' => 'ASME B30.3 sec 2.4.3(b)7', // Index: 140
        ],
        [
            'num' => '4.50',
            'item' => 'More than two broken wires adjacent to the socketed end connection, the rope shall be re-socketed or replaced.',
            'reference' => 'ASME B30.3 sec 2.4.3(b)8', // Index: 141
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
