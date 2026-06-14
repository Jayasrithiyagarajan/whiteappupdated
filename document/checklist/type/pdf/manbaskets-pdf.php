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

<h2 style="text-align: center; margin-bottom: 2px;">PERSONNEL LIFTING SYSTEMS (MANBASKET)
<br>ASME B30.23 – 2016  

</h2>

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
    '1. RATINGS & MARKINGS' => [
        [
            'item' => 'Documentation is available.',
            'ref' => 'ASME B30.23 Sec 1.2.1(c)(1,2,3)'
        ],
        [
            'item' => 'Platform has an identification number / asset number marked on it.',
            'ref' => 'ASME B30.23 SEC 1.1(7)(-c)'
        ],
        [
            'item' => 'Platform’s SWL is prominently marked Minimum Platform rating is 300 lbs. (136 kg).',
            'ref' => 'ASME B30.23 SEC 1.1.1 (B)(1)'
        ],
        [
            'item' => 'Maximum number of persons allowed on the platform is clearly marked.',
            'ref' => 'ASME B30.23'
        ],
        [
            'item' => 'An Identification Plate is provided with the following items are clearly marked: Manufacturer name & address, weight of the empty platform, date of manufacture, number of personnel allowed on the platform, certificate number of compliance to the design, construction and testing.',
            'ref' => 'ASME B30.23 Sec 1.2(c) Sec 1.1. (7)(-a)(d)(-e)'
        ],
        [
            'item' => 'Rope sling and chain sling suspension systems shall have each leg of the system permanently marked with the rated load of the leg.',
            'ref' => 'ASME B30.23 sec 1.1(10)(-h)'
        ],
        [
            'item' => 'The master link in the system shall be permanently marked with the suspension system\'s rated load and identification as a personnel lifting platform suspension component.',
            'ref' => 'ASME B30.23 sec 1.1(10)(-h)'
        ],
    ],
    '2. INSPECTION POINTS' => [
        [
            'item' => 'Shackles shall be of a bolt type with cotter pin.',
            'ref' => 'ASME B30.23 sec 1.1(10)(-i)'
        ],
        [
            'item' => 'Access system (gate), if provided, shall only open inward.',
            'ref' => 'ASME B30.23 sec 1.1(8)'
        ],
        [
            'item' => 'Rope Sling Suspension System with Mechanically spliced Flemish eyes, if used, have thimbles in all eyes. (Wire rope clips, wedge sockets, or knots shall not be used).',
            'ref' => 'ASME B30.23 sec 1.1(10)(-a)'
        ],
        [
            'item' => 'Chain sling, if used, has a minimum of grade 80 chain.',
            'ref' => 'ASME B30.23 sec 1.1(10)(-c)'
        ],
        [
            'item' => 'All sling suspension systems utilized a master link for attachment to the hoisting equipment’s hook or bolt type shackle with cotter pin.',
            'ref' => 'ASME B30.23 sec 1.1(10)(-d)'
        ],
        [
            'item' => 'Guard rail protection consists of: Top rail (not less than 39” (990 mm & not more than 45” in 1,140 mm),',
            'ref' => 'ASME B30.23 sec 1.1.1(2)'
        ],
        [
            'item' => 'Intermediate rail, toe board has a min. in height of 3.9”(90 mm) from the top of the platform floor.',
            'ref' => 'ASME B30.23 sec 1.1.1(2)'
        ],
        [
            'item' => 'Anchorage points are provided within the platform or on the boom tip.',
            'ref' => 'ASME B30.23 sec 1.1(3)'
        ],
        [
            'item' => 'Hand Railings are provided around the platform, with the exception of any access gate or door. Hand railings shall have the clearance of not less than 1.5” (39 mm) between the railings and any other structure.',
            'ref' => 'ASME B30.23 sec 1.1(4)(-c)'
        ],
        [
            'item' => 'Flooring with a slip-resistant surface and provisions to facilitate the free drainage of fluids and that will stand the forces developed under proof load test. Flooring shall have no opening that will allow a sphere of 0.5” (13 mm) to pass through.',
            'ref' => 'ASME B30.23 sec 1.1(5)'
        ],
        [
            'item' => 'Synthetic webbing sling or natural synthetic fiber rope sling is not used for suspension systems.',
            'ref' => 'ASME B30.23 sec 1.1(10)(-e)'
        ],
        [
            'item' => 'Overhead protection if provided, shall allow for a clear view of the hoist equipment components directly overhead from any position in the platform. Any openings designed in the overhead protection shall not allow a sphere of 0.5” (13 mm) to pass through.',
            'ref' => 'ASME B30.23 sec 1.1(11)'
        ],
    ],
];

$index = 0;
foreach ($sections as $sectionTitle => $items) {
    echo "<tr><td colspan='7' class='section'>" . htmlspecialchars($sectionTitle) . "</td></tr>";
    $itemNo = 1;
    foreach ($items as $itemData) {
        $sn = explode('. ', $sectionTitle, 2)[0] . '.' . $itemNo;
        echo '<tr>';
        echo '<td>' . htmlspecialchars($sn) . '</td>';
        echo '<td>' . htmlspecialchars($itemData['item']) . '</td>';
        echo '<td>' . htmlspecialchars($itemData['ref']) . '</td>';
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
