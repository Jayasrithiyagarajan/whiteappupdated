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

        th,
        td {
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

        .main-title {
            font-size: 14px;
            font-weight: bold;
        }

        .w-100 {
            width: 100%;
        }

        .logo-cell {
            text-align: center;
            vertical-align: top;
        }

        .no-border {
            border: none;
        }

        .left-align {
            text-align: left;
        }

        .keep-together {
            page-break-inside: avoid;
            break-inside: avoid;
        }

        @media print {
            body * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            th {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>

<h4 style="text-align: center;">
    POWERED PLATFORM / SKY CLIMBERS

<br>
ASME A120.1-2014 </h4>

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
    <th width="6%">S.N</th>
    <th width="48%">ACCEPTANCE CRITERIA</th>
    <th width="16%">REFERENCE</th>
    <th width="10%">PASS</th>
    <th width="10%">FAIL</th>
    <th width="10%">NA</th>
    <th width="22%">REMARKS</th>
</tr>
</thead>
<tbody>
<?php
$sections = [
    '1. GENERAL REQUIREMENTS' => [
        ['1.1', 'Equipment Documentation is available', 'ASME A 120.1 Sec. 3.1.5'],
        ['1.2', 'Previous Inspection reports are checked', 'ASME A 120.1 Sec. 3.1.5'],
        ['1.3', 'Warning notice, cautions or restrictions for safe operation and maintenance are clearly displayed and legible (including wind speed)', 'ASME A 120.1 Sec. 3.1.5'],
        ['1.4', 'Two qualified/trained operators are operating the machine (one at each side)', 'ASME A120.1 Sec. 4.2.1'],
        ['1.5', 'A sign is posted warning the operator not to rely solely on any automatic device as a substitute for safe operating practice', 'CIMS QHSE 06'],
        ['1.6', 'Manufacturer name, address, serial number and model are clearly marked or tagged', 'ASME A 120.1 Sec. 3.1.5'],
        ['1.7', 'Safe working load and number of persons are clearly marked on the work platform', 'ASME A 120.1 Sec. 3.1.5'],
        ['1.8', 'Self-Weight of the platform is clearly marked', 'ASME A120.1 Sec. 3.7.2'],
        ['1.9', 'Each suspension jib is marked up with a unique ID number, safe working load and any fitted counterbalance load', 'ASME A120.1 Sec. 3.4'],
        ['1.10', 'Suspension brackets are marked up with unique ID number and safe working load', 'ASME A120.1 Sec. 3.4'],
        ['1.11', 'All shackles are fully secured and fitted with lock pins where required (or wire-locked)', 'ASME A120.1 Sec. 3.4'],
        ['1.12', 'All hooks are fully secured', 'ASME A120.1 Sec. 3.4'],
        ['1.13', 'Suspension and safety ropes are marked with a unique number and safe working load', 'ASME A120.1 Sec. 3.10'],
        ['1.14', 'Hoist motors and safety rope inertia devices are marked with safe working loads and serial numbers', 'ASME A120.1 Sec. 3.6.1 to 3.6.8, 3.6'],
    ],
    '2. PLATFORM CONDITION' => [
        ['2.1', 'Platform surfaces are slip-resistant materials', 'ASME A120.1 Sec. 3.7.5.2'],
        ['2.2', 'Platform dimensions are not less than 24" (610mm) wide working area and a minimum of 12" (305mm) wide passage way', 'ASME A120.1 Sec. 3.7.5.2'],
        ['2.3', 'Platform guardrails are provided and secure', 'ASME A120.1 Sec. 3.7.5'],
        ['2.4', 'Platform guardrails are 42" height (+/- 3")', 'ASME A120.1 Sec. 3.7.5'],
        ['2.5', 'Welded members/joints are free of defects and corrosion', 'ASME A120.1 Sec. 3.1.1, 3.1.4'],
        ['2.6', 'Maximum operating height is within 130 ft (40m) for jib installation', 'ASME A120.1 Sec. 3.4'],
        ['2.7', 'Each platform occupant has a harness or safety belt (with anchorage for a tail line or a lanyard)', 'ASME A120.1 Sec. 3.7.5.10'],
        ['2.8', 'A tail line or a lanyard has means to be secured to a trolley line or a separate lifeline', 'ASME A120.1 Sec. 3.7.5.10 (a)'],
        ['2.9', 'Each hoist machine/motor is capable of raising and lowering 125% of hoist rated load', 'ASME A120.1 Sec. 3.6.1'],
        ['2.10', 'Electric motors are protected with a current overload device', 'ASME A120.1 Sec. 3.6.1 (a)'],
        ['2.11', 'Speed reducers are of positive/gear reduction type (friction type is not allowed)', 'ASME A120.1 Sec.3.6.2, 3.6.3'],
        ['2.12', 'Speed reducers are directly connected to the elevating mechanism of the hoisting machine', 'ASME A120.1 Sec.3.6.2 (a)'],
        ['2.13', 'All moving parts are guarded', 'ASME A120.1 Sec.3.6.5'],
        ['2.14', 'Gear reduction speed reducers are in compliance with the standards of the American Gear Manufacturers Association', 'ASME A120.1 Sec. 3.6.3'],
        ['2.15', 'Minimum of two independent hoist brakes are provided', 'ASME A120.1 Sec. 3.6.8'],
        ['2.16', 'Hoist brakes automatically engage whenever power is interrupted (Primary brakes)', 'ASME A120.1 Sec. 3.6.8(a1)'],
        ['2.17', 'Primary brakes hold 125% of the hoist\'s rated load', 'ASME A120.1 Sec. 3.6.8 (a2)'],
        ['2.18', 'Primary brakes are directly connected to the hoist\'s drive train (belts, shear pins, clutches, roller chains, or friction devices are prohibited)', 'ASME A120.1 Sec. 3.6.8 (a3)'],
        ['2.19', 'Automatic secondary brakes hold 125% of the hoist\'s rated load within a vertical distance of 24" (610mm)', 'ASME A120.1 Sec. 3.6.8 (b1)'],
        ['2.20', 'Secondary brakes arc directly on the suspension wire rope (or on the drum or drum extension on a winding drum type hoist)', 'ASME A120.1 Sec. 3.6.8 (b2)'],
        ['2.21', 'The secondary brake are not used to stop the hoist except under overspeed or abnormal conditions', 'ASME A120.1 Sec. 3.6.8 (b3)'],
        ['2.22', 'Lubrication of the hoisting machine and moving parts is adequate', 'ASME A120.1 Sec.3.6.4'],
        ['2.23', 'Lubrication points are accessible', 'ASME A120.1 Sec.3.6.4'],
        ['2.24', 'Fillets are provided as points of change in the diameter of hoisting machinery shafts and sheave shafts to prevent excessive stress concentration in the shafts', 'ASME A120.1 Sec. 3.6.6'],
        ['2.25', 'Fitted keys, splines, bolts, or machine screws are used in all connections subject to torque', 'ASME A120.1 Sec. 3.6.6'],
        ['2.26', 'All threaded fasteners have an ant loosening device', 'ASME A120.1 Sec. 3.6.6'],
        ['2.27', 'Suitable runways, ladders, stairs, or platforms are provided for safe access to and egress from all manned scaffold platforms', 'ASME A120.1 Sec. 3.7.5.9'],
        ['2.28', 'A two-way radio or a two-way telephone system is provided for every manned platform (if applicable)', 'ASME A120.1 Sec. 3.7.5.6'],
        ['2.29', 'Wire rope classification is as per manufacturer specifications (check equipment manual)', 'ASME A120.1 Sec.3.10.1'],
        ['2.30', 'Suspension rope diameter is not less than 5/16" (8mm)', 'ASME A120.1 Sec.3.10.1.5'],
        ['2.31', 'Rope cores are one of the following: fiber, independent wire rope, or wire strand, or coated electrical conductors (check manufacturer\'s recommendations)', 'ASME A120.1 Sec.3.10.1.1'],
        ['2.32', 'Wire rope is adequately lubricated', 'ASME A120.1 Sec.3.10.1.3'],
        ['2.33', 'Wire rope is free of kinks, crushes and bird caging', 'ASME A120.1 Sec.4.1.4 (c)'],
        ['2.34', 'Wire rope is not heat damaged', 'ASME A120.1 Sec.4.1.4 (d)'],
        ['2.35', 'Wire rope is free of rust, corrosion or pitting’s', 'ASME A120.1 Sec.4.1.4 (e)'],
        ['2.36', 'Wire rope does not have four randomly distributed broken wires in three lays', 'ASME A120.1 Sec.4.1.4 (a)'],
        ['2.37', 'Wire rope does not have two broken wires in one strand in three lays', 'ASME A120.1 Sec.4.1.4 (a)'],
        ['2.38', 'Wire rope does not have more than two broken wires in the vicinity of end attachments', 'ASME A120.1 Sec.4.1.4 (e)'],
        ['2.39', 'Wire rope does not show evidence of core failure (a lengthening of rope lay and a reduction in rope diameter suggests core failure)', 'ASME A120.1 Sec.4.1.4 (f)'],
        ['2.40', 'Wire rope does not have more than one valley break (broken wire)', 'ASME A120.1 Sec.4.1.4 (g)'],
        ['2.41', 'Suspension wire rope fasteners are one of the following: (1) Individual tapered babbitted sockets (2) Zinced fastenings for wire rope 1⁄2" (13mm) diameter and larger (3) Swaged fittings (U-Type wire rope clips are not allowed)', 'ASME A120.1 Sec.3.10.5'],
        ['2.42', 'Platform is fitted with face rollers are its lower inner edge (to protect the surface of the supporting structure)', 'ASME A120.1 Sec.3.7.4.1'],
        ['2.43', 'Upper anchorage brackets and jibs are fully secured to permanent and certified points on the structure', 'ASME A120.1 Sec.3.4'],
        ['2.44', 'Each counterbalanced jib is tied to a suitable certified point on the structure', 'ASME A120.1 Sec. 3.4 (b)'],
        ['2.45', 'All exposed noncurrent-carrying metal parts are grounded', 'ASME A120.1 Sec.3.11.1'],
        ['2.46', 'Electrical wirings and components are free of damages', 'ASME A120.1 Sec.3.11.2.1'],
        ['2.47', 'The independent power supply to the equipment is provided with a disconnect switch', 'ASME A120.1 Sec.3.11.2.2'],
        ['2.48', 'All electrical controls are housed in weatherproof enclosures', 'ASME A120.1 Sec.3.11.2.7 (a)'],
        ['2.49', 'Control systems are provided with electrical overloads protection devices', 'ASME A120.1 Sec.3.11.2.7 (b)'],
        ['2.50', 'Traveling cables/conductors are insulated', 'ASME A120.1 Sec.3.11.2.6'],
        ['2.51', 'A fire extinguisher with BC rating is provided and securely attached on the platform', 'ASME A120.1 Sec.3.7.5.8'],
    ],
];

$index = 0;
foreach ($sections as $sectionTitle => $items) {
    echo "<tr><td colspan='7' class='section'>" . htmlspecialchars($sectionTitle) . "</td></tr>";
    foreach ($items as $item) {
        list($sn, $criteria, $reference) = $item;
        echo '<tr>';
        echo '<td><strong>' . htmlspecialchars($sn) . '</strong></td>';
        echo '<td><strong>' . htmlspecialchars($criteria) . '</strong></td>';
        echo '<td><strong>' . htmlspecialchars($reference) . '</strong></td>';
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

<br>

<table>
<tr>
    <th style="text-align:left;">REMARKS / RECOMMENDATIONS</th>
</tr>
<tr>
    <td style="height:120px;"><?= htmlspecialchars($recommendations) ?></td>
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
                    <?php if ($inspector_signature_path && file_exists($inspector_signature_path)): ?>
                        <img src="<?= htmlspecialchars($inspector_signature_path) ?>" alt="Inspector Signature"
                            style="max-width: 50px; max-height: 25px;">
                    <?php else: ?>
                        <div class="signature-placeholder">Signature Not Available</div>
                    <?php endif; ?>
                </div>
        </td>
        <td>
            <div class="signature-name"><?= htmlspecialchars($client_name) ?></div>
            <div class="signature-box">
                    <?php if ($client_signature_path && file_exists($client_signature_path)): ?>
                        <img src="<?= htmlspecialchars($client_signature_path) ?>" alt="Client Signature" style="max-width: 50px; max-height: 25px;">
                    <?php else: ?>
                        <div class="signature-placeholder">Signature Not Available</div>
                    <?php endif; ?>
            </div>
        </td>
    </tr>
</table>

</div>

</body>
</html>
