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



<h4 style="text-align: center;">ARC WELDING EQUIPMENT <br>
        BS  EN 60974-4:2007 </h4>

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
    '1. ELECTRODE HOLDER & RETURN CLAMP' => [
        'Missing or Defective Insulation',
        'Defective Connections',
        'Defective, Damaged Switches',
        'Other Damage',
    ],
    '2. MAINS SUPPLY' => [
        'Defective, Damage Cable',
        'Deformed, Faulty Plug',
        'Broken or Thermally Damaged Plug Pins',
        'Ineffective Cable Anchorage',
        'Cables & Couplers unsuitable for the intended use and performance.',
    ],
    '3. WELDING CIRCUIT' => [
        'Defective, Damage Cable',
        'Deformed, Faulty or Thermally Damaged coupler / sockets.',
        'Ineffective Cable Anchorage',
        'Cables & Couplers unsuitable for the intended use and performance.',
    ],
    '4. ENCLOSURE' => [
        'Missing or Damaged',
        'Unauthorized Modifications',
        'Cooling Openings Blocked or Missing Air Filters.',
        'Signs of Overload & Improper Use',
        'Missing or Defective Wheels, Lifting Means, Holder, Etc.',
        'Defective Wire Reel Mounting Means',
        'Conductive Objects Placed in the Enclosure.',
    ],
    '5. CONTROLS & INDICATORS' => [
        'Defective Switches, Meters & Lamps',
        'Defective Pressure Regulator or F.M',
        'Incorrect Fuses Accessible from Outside the enclosure.',
    ],
    '6. GENERAL CONDITION' => [
        'Cooling Liquid Circuit Leaking',
        'Defective Gas Hoses & Connections',
        'Poor Legibility of Markings & Labelling',
        'Data Plate & Markings',
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

<pagebreak />

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

</body>
</html>
