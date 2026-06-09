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
        }        .keep-together {
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

    <br>

    <table>
        <tr>
            <th width="25%">REPORT NO</th>
            <td width="25%" style="text-align:center;"><?= htmlspecialchars($row['report_no'] ?? '') ?></td>
            <th width="25%">INSPECTION DATE</th>
            <td width="25%" style="text-align:center; "><?= htmlspecialchars($row['inspection_date'] ?? '') ?></td>
        </tr>
        <tr>
            <th>CLIENT'S NAME</th>
            <td style="text-align:center; "><?= htmlspecialchars($row['client_name'] ?? '') ?></td>
            <th>INSPECTED BY</th>
            <td style="text-align:center; "><?= htmlspecialchars($row['inspected_by'] ?? '') ?></td>
        </tr>
        <tr>
            <th>LOCATION</th>
            <td style="text-align:center; "><?= htmlspecialchars($row['location'] ?? '') ?></td>
            <th>STICKER NO.</th>
            <td style="text-align:center; "><?= htmlspecialchars($row['sticker_no'] ?? '') ?></td>
        </tr>
        <tr>
            <th>EQUIPMENT NO</th>
            <td style="text-align:center; "><?= htmlspecialchars($row['equipment_no'] ?? '') ?></td>
            <th>EQUIP. SERIAL NO.</th>
            <td style="text-align:center; "><?= htmlspecialchars($row['crane_serial_no'] ?? '') ?></td>
        </tr>
        <tr>
            <th>EQUIPMENT TYPE</th>
            <td style="text-align:center; "><?= htmlspecialchars($row['equipmenttype'] ?? '') ?></td>
            <th>CAPACITY (SWL)</th>
            <td style="text-align:center; "><?= htmlspecialchars($row['capacity_swl'] ?? '') ?></td>
        </tr>
    </table>

    <br>

    <table>
        <thead>
            <tr>
                <th width="10%">Item No.</th>
                <th width="52%">ACCEPTANCE CRITERIA</th>
                <th width="9%">PASS</th>
                <th width="9%">FAIL</th>
                <th width="9%">N/A</th>
                <th width="11%">Comments</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sections = [
                'GENERAL REQUIREMENTS' => [
                    '1.01' => 'Equipment documentation is available',
                    '1.02' => 'Equipment has marking/tagging of manufacturer name, address, serial no., year & model number, lifter weight',
                    '1.03' => 'Equipment Asset ID/Number is marked or stenciled prominently',
                    '1.04' => 'Previous inspection reports are checked',
                    '1.05' => 'Manufacturer Test Certificate, COC, and/or OEM Certificate are available',
                    '1.06' => 'Rated capacity of the elevating/lifting equipment is marked or stenciled (Arabic & English)',
                    '1.07' => 'MPI Reports or Certificates for Structure, Main & Auxiliary Hooks, Pad eyes are available',
                    '1.08' => 'Manuals: Operator\'s Manual, or Technical Manual, is available.',
                    '1.09' => 'Preventive Maintenance records are available.',
                    '1.10' => 'Repair records are available.',
                    '1.11' => 'Equipment Registration is available',
                    '1.12' => 'Safety Decals are available and posted.',
                    '1.13' => 'Equipment is operated by a qualified or competent operator.',
                ],
                'STRUCTURAL AND MECHANICAL DEVICES' => [
                    '2.01' => 'Ropes are without damage i.e. broken wires, birdcaging, unstranding, bends, corrosion, etc.',
                    '2.02' => 'There are no loose or missing guards, bolts and fasteners',
                    '2.03' => 'Covers, stops or nameplates are not missing',
                    '2.04' => 'Product safety labels are attached and legible',
                    '2.05' => 'All welds are not showing cracks or corrosion',
                    '2.06' => 'Guards for moving parts are fitted where required',
                    '2.07' => 'Structural members are not deformed, cracked or excessively worn',
                    '2.08' => 'Verify operation of mechanisms (where applicable)',
                    '2.09' => 'Automatic hold-and-release mechanisms are adjusted and do not interfere with operation (where appropriate)',
                    '2.10' => 'Surface of load is not covered in debris (where applicable)',
                    '2.11' => 'Condition and operation of the controls are correct and functioning well',
                    '2.12' => 'Gears, pulleys and sheaves are not cracked or worn',
                    '2.13' => 'Sprockets, bearings, chains or belts are not cracked or worn',
                    '2.14' => 'Linkages and other mechanical parts do not have excessive wear',
                    '2.15' => 'Hoist hooking points and load support clevises or pins do not have excessive wear',
                ],
                'ELECTRICAL DEVICES' => [
                    '3.01' => 'External power supply, electrical equipment and wiring is in place and undamaged (when applicable)',
                    '3.02' => 'Values of cold current and rated voltage (when applicable) are correct',
                    '3.03' => 'Electrical motors, pumps are working',
                    '3.04' => 'Wirings and cables are not frayed',
                    '3.05' => 'Switch limits are not faulty',
                    '3.06' => 'All Lights are working',
                    '3.07' => 'Condition and operation of the indicators and meters are correct and function well (where applicable)',
                ],
                'HYDRAULIC/PNEUMATIC DEVICES' => [
                    '4.01' => 'Hydraulic motors, pumps, rams/cylinders are free from oil leakages.',
                    '4.02' => 'Hydraulic/pneumatic valves, hose fittings are not ruptured nor oil/air leaking',
                    '4.03' => 'Tires are in good condition with air pressure correctly inflated.',
                ],
            ];

            $index = 0;
            foreach ($sections as $sectionTitle => $items) {
                echo "<tr><td colspan='6' class='section'>" . htmlspecialchars($sectionTitle) . "</td></tr>";
                foreach ($items as $itemNo => $item) {
                    echo '<tr>';
                    echo '<td><strong>' . htmlspecialchars($itemNo) . '</strong></td>';
                    echo '<td><strong>' . htmlspecialchars($item) . '</strong></td>';
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
                    <?php if ($inspector_signature_path && file_exists($inspector_signature_path)): ?>
                        <img src="<?= htmlspecialchars($inspector_signature_path) ?>" alt="Inspector Signature"
                            style="max-width: 50px; max-height: 25px;">
                    <?php else: ?>
                        <div class="signature-placeholder">Signature Not Available</div>
                    <?php endif; ?>
                </div>
            </td>
            <td>
                <div class="signature-name"><?= htmlspecialchars($row['client_rep_name'] ?? '') ?></div>
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