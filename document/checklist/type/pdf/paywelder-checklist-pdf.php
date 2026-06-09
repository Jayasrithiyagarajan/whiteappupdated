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

th {
    background-color: #c0d6e8;
    font-weight: bold;
    text-align: center;
}

.notice {
    background: #fff4d6;
    border: 1px solid #c9b26b;
    padding: 10px;
    margin-top: 12px;
    font-size: 10px;
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

<div class="notice">
    Paywelder checklist PDF template is not fully configured yet.
    Add the checklist sections for this type in this file when the form layout is ready.
</div>

<div class="keep-together">

<table class="signature-table">
    <tr>
        <th width="50%">INSPECTOR</th>
        <th width="50%">CLIENT REPRESENTATIVE</th>
    </tr>
    <tr>
        <td>
            <div><?= htmlspecialchars($row['inspected_by'] ?? '') ?></div>
            <?php if ($inspector_signature_path && file_exists($inspector_signature_path)) : ?>
                <img src="<?= htmlspecialchars($inspector_signature_path) ?>" alt="Inspector Signature" width="85" height="48">
            <?php else : ?>
                <div class="signature-placeholder">Signature Not Available</div>
            <?php endif; ?>
        </td>
        <td>
            <div><?= htmlspecialchars($client_name) ?></div>
            <?php if ($client_signature_path && file_exists($client_signature_path)) : ?>
                <img src="<?= htmlspecialchars($client_signature_path) ?>" alt="Client Signature" width="85" height="48">
            <?php else : ?>
                <div class="signature-placeholder">Signature Not Available</div>
            <?php endif; ?>
        </td>
    </tr>
</table>

</div>

</body>
</html>
