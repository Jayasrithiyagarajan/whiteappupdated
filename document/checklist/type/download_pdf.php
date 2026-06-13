<?php
require_once(__DIR__ . '/../../../vendor/autoload.php');
include_once(__DIR__ . '/../../../file/config.php');

use Mpdf\Mpdf;

$checklist_type = $_GET['checklist_type'] ?? $_GET['type'] ?? '';
$checklist_no = isset($_GET['checklist_no']) ? (int) $_GET['checklist_no'] : 0;

if ($checklist_type === '' || $checklist_no <= 0) {
    die('Invalid PDF request');
}

$viewMap = [
    'arc-welding-machine' => 'pdf/arc-welding-machine-pdf.php',
    'articulating_boom' => 'pdf/articulating_boom-pdf.php',
    'base_mounted_drum' => 'pdf/base_mounted_drum-pdf.php',
    'bulldozer' => 'pdf/bulldozer-pdf.php',
    'elevators' => 'pdf/elevators-pdf.php',
    'excavator' => 'pdf/excavator-pdf.php',
    'fixed-cranes-hoist' => 'pdf/fixed-cranes-hoist-pdf.php',
    'forklift' => 'pdf/forklift-pdf.php',
    'frames-and-mobile-gantries' => 'pdf/frames-and-mobile-gantries-pdf.php',
    'general-purpose' => 'pdf/general-purpose-pdf.php',
    'lifting-beam-spreader-bar' => 'pdf/lifting-beam-spreader-bar-pdf.php',
    'manbaskets' => 'pdf/manbaskets-pdf.php',
    'marine-offshore-cranes' => 'pdf/marine-offshore-cranes-pdf.php',
    'mobile_locomotive' => 'pdf/mobile_locomotive-pdf.php',
    'motor-grade' => 'pdf/motor-grade-pdf.php',
    'ndt' => 'pdf/ndt-pdf.php',
    'powered-platforms' => 'pdf/powered-platforms-pdf.php',
    'side-boom-tractors' => 'pdf/side-boom-tractors-pdf.php',
    'stbd-crane' => 'pdf/stbd-crane-pdf.php',
    'storage-retrieval' => 'pdf/storage-retrieval-pdf.php',
    'tower-cranes' => 'pdf/tower-cranes-pdf.php',
    'paywelder-checklist' => 'pdf/paywelder-checklist-pdf.php',
    'jib-davit' => 'pdf/jib-davit-pdf.php',
    'sticker' => 'pdf/sticker-pdf.php',
    'vehicle_mounted_elevating' => 'pdf/vehicle_mounted_elevating-pdf.php',
    'wheel-loader' => 'pdf/wheel-loader-pdf.php',
];

if (!isset($viewMap[$checklist_type])) {
    $stmt = $conn->prepare('SELECT project_no FROM checklist_information WHERE checklist_id = ? LIMIT 1');
    $stmt->bind_param('i', $checklist_no);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result || $result->num_rows === 0) {
        die('Checklist not found');
    }

    $row = $result->fetch_assoc();
    $stmt->close();

    header('Location: ../download.php?project_no=' . urlencode($row['project_no']));
    exit;
}

$mpdf = new Mpdf([
    'mode' => 'utf-8',
    'format' => 'A4',
    'margin_top' => 70,
    'margin_bottom' => 20,
    'margin_left' => 10,
    'margin_right' => 10,
    'tempDir' => __DIR__ . '/../../../tmp/mpdf',
    'default_font' => 'DejaVuSans',
]);

$logoPath = str_replace('\\', '/', realpath(__DIR__ . '/../logo.png') ?: (__DIR__ . '/../logo.png'));
$codePath = str_replace('\\', '/', realpath(__DIR__ . '/../../../code.png') ?: (__DIR__ . '/../../../code.png'));

$checklistNamesMap = [
    'arc-welding-machine' => 'ARC WELDING MACHINE',
    'articulating_boom' => 'ARTICULATING BOOM CRANES',
    'base_mounted_drum' => 'BASE MOUNTED DRUM HOIST (WINCHES)',
    'bulldozer' => 'BULLDOZER',
    'elevators' => 'ELEVATORS AND ESCALATORS',
    'excavator' => 'HYDRAULIC EXCAVATOR',
    'fixed-cranes-hoist' => 'FIXED CRANES & HOISTS',
    'forklift' => 'FORK LIFT',
    'frames-and-mobile-gantries' => 'A-FRAMES AND MOBILE GANTRIES',
    'jib-davit' => 'JIB CRANES & DAVITS',
    'lifting-beam-spreader-bar' => 'LIFTING BEAMS/SPREADER BARS',
    'manbaskets' => 'MANBASKET',
    'marine-offshore-cranes' => 'MARINE & OFFSHORE CRANES',
    'mobile_locomotive' => 'MOBILE & LOCOMOTIVE CRANES',
    'motor-grade' => 'MOTOR GRADER',
    'powered-platforms' => 'POWERED PLATFORMS / SKY CLIMBERS',
    'side-boom-tractors' => 'SIDE BOOM TRACTORS',
    'stbd-crane' => 'CRANE HEALTH CHECK',
    'storage-retrieval' => 'STORAGE RETRIEVAL',
    'tower-cranes' => 'TOWER CRANES',
    'vehicle_mounted_elevating' => 'VEHICLE MOUNTED ELEVATING',
    'wheel-loader' => 'WHEEL, COMPACT SKID LOADER, & PIPE LOGGER',
    'general-purpose' => 'ALL-PURPOSE EQUIPMENT CHECKLIST',
    'ndt' => 'NDT CHECKLIST',
    'sticker' => 'STICKER CHECKLIST',
    'paywelder-checklist' => 'PAYWELDER CHECKLIST'
];

$checklistName = isset($checklistNamesMap[$checklist_type]) ? $checklistNamesMap[$checklist_type] : strtoupper(str_replace(['-', '_'], ' ', $checklist_type)) . ' CHECKLIST';

$frmMap = [
    'arc-welding-machine' => 'FRM.0601-1.16',
    'articulating_boom' => 'FRM.0601-1.16',
    'base_mounted_drum' => 'FRM.0601-1.16',
    'bulldozer' => 'FRM.0601-2.2',
    'elevators' => 'FRM.0601-1.2',
    'excavator' => 'FRM.0601-2.1',
    'fixed-cranes-hoist' => 'FRM.0601-1.11',
    'forklift' => 'FRM.0601-1.15',
    'frames-and-mobile-gantries' => 'FRM.0601-1.14',
    'general-purpose' => 'FRM.0601-1.10',
    'jib-davit' => 'FRM.0601-1.4',
    'lifting-beam-spreader-bar' => 'FRM.0601-1.7',
    'manbaskets' => 'FRM.0601-1.10',
    'marine-offshore-cranes' => 'FRM.0601-1.4',
    'mobile_locomotive' => 'FRM.0601-1.1',
    'motor-grade' => 'FRM.0601-2.3',
    'ndt' => 'FRM.0601-1.10',
    'powered-platforms' => 'FRM.0601-1.8',
    'side-boom-tractors' => 'FRM.0601-1.11',
    'stbd-crane' => 'FRM.0601-1.0',
    'sticker' => 'FRM.0601-1.10',
    'storage-retrieval' => 'FRM.0601-1.5',
    'tower-cranes' => 'FRM.0601-1.13',
    'vehicle_mounted_elevating' => 'FRM.0601-1.9',
    'wheel-loader' => 'FRM.0601-1.10',
    'paywelder-checklist' => 'FRM.0601-1.10'
];
$frmValue = isset($frmMap[$checklist_type]) ? $frmMap[$checklist_type] : 'FRM.0601-1.10';


$header = '
<table width="100%" style="border-collapse:collapse;">
<tr>
    <td rowspan="4" width="20%" align="center">
        <img src="' . htmlspecialchars($logoPath, ENT_QUOTES) . '" width="90">
    </td>
    <td colspan="3" align="center" style="font-size:14px;font-weight:bold;">
        CRANE INSPECTION & MAINTENANCE SERVICES<br>
        A DIVISION OF AL-KHOBAR GATE INTERNATIONAL TRADING EST
    </td>
</tr>
<tr>
    <td colspan="3" align="center" style="font-size:13px;font-weight:bold;">
        INSPECTION CHECKLIST FOR
        ' . htmlspecialchars($checklistName, ENT_QUOTES) . '
    </td>
</tr>
<tr>
    <td align="center">' . htmlspecialchars($frmValue, ENT_QUOTES) . '</td>
    <td align="center">Revision 02</td>
    <td align="center">Issue Date: 30/SEP/2020</td>
</tr>
<tr>
    <td style="font-size:11px;">
        <b>Prepared By</b><br>Operations Manager
    </td>
    <td style="font-size:11px;">
        <b>Reviewed & Approved By</b><br>Managing Director
    </td>
    <td align="center">
        <img src="../../code.png" width="65">
    </td>
</tr>
</table>';

$mpdf->SetHTMLHeader($header);

$_GET['checklist_type'] = $checklist_type;
$_GET['checklist_no'] = $checklist_no;

ob_start();
include __DIR__ . '/' . $viewMap[$checklist_type];
$html = ob_get_clean();

// Remove UTF-8 BOM if present
$html = preg_replace('/^\x{EF}\x{BB}\x{BF}/', '', $html);

$mpdf->WriteHTML($html);

$filename = ucfirst(str_replace('-', ' ', $checklist_type)) . '_Checklist_' . $checklist_no . '.pdf';
$mpdf->Output($filename, 'D');
exit;
