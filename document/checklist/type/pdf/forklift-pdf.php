<?php
include_once(__DIR__ . '/_bootstrap.php');

$output = $html;

try {
    $dompdf = new Dompdf();
    $dompdf->loadHtml($output);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("forklift-inspection-" . uniqid() . ".pdf");
} catch (Exception $e) {
    echo "Error generating PDF: " . $e->getMessage();
}
?>
<?php

include_once('./view-fetch.php');

// Define sections array with all 54 items
$sections = [
    'Section 1. RATINGS & MARKINGS' => [
        'Documentation is available.' => 'ANSI B56.1, Sec.5.1',
        'Equipment asset ID Number is prominently marked.' => 'ANSI B56.1, Sec.5.2',
        'Nameplate, caution, and instruction markings are available on the truck.' => 'ANSI B56.1, Sec.5.3',
        'SWL/WLL (Capacities) are clearly marked & on a prominent location.' => 'ANSI B56.1, Sec.5.4',
        'All controls are marked for identification of function.' => 'ANSI B56.1, Sec.5.5',
        'Front end attachments, including fork extensions are marked with identification, capacity, maximum elevation with the load laterally centered.' => 'ANSI B56.1, Sec.5.6',
        'The machine is operated by Certified Operator.' => 'ANSI B56.1, Sec.5.7',
        'All control levers are within reach of operator during the normal operating conditions.' => 'ANSI B56.1, Sec.5.8',
        'Capacity chart is provided & legible.' => 'ANSI B56.1, Sec.5.9',
        'All safety & warning decals are posted.' => 'ANSI B56.1, Sec.5.10',
    ],
    'Section 2. VISUAL INSPECTION & FUNCTIONAL TEST' => [
        'Carriage, backrest, & mast are not bent or deformed.' => 'ANSI B56.1, Sec.6.1.1',
        'Forks are not deformed or bent.' => 'ANSI B56.1, Sec.6.1.2',
        'Forks arms & levers are not deformed or bent.' => 'ANSI B56.1, Sec.6.1.3',
        'No excessive corrosion on frames, anchorages, structures are present.' => 'ANSI B56.1, Sec.6.1.4',
        'Load chains have no broken links or pins.' => 'ANSI B56.1, Sec.6.1.5',
        'Lift & tilt cylinders are operating correctly & without hydraulic oil leaks.' => 'ANSI B56.1, Sec.6.1.6',
        'Steering & side shift cylinders are operating correctly & without hydraulic oil leaks.' => 'ANSI B56.1, Sec.6.1.7',
        'ROPS or overhead guard is provided and can withstand the drop test based on the applicable table or rated capacity.' => 'ANSI B56.1, Sec.6.1.8',
        'Safety belt is provided.' => 'ANSI B56.1, Sec.6.1.9',
        'All control levers are within reach of operator during the normal operating conditions.' => 'ANSI B56.1, Sec.6.1.10',
        'All hydraulic hoses are free of tears, and no signs of leaks on their hose fittings.' => 'ANSI B56.1, Sec.6.1.11',
        'Hydraulic oil tank level is correct and tank is securely fastened, and no signs of oil leakages.' => 'ANSI B56.1, Sec.6.1.12',
        'Fuel tank is correct, secured, & not leaking.' => 'ANSI B56.1, Sec.6.1.13',
        'Steering & transmission oil levels are correct & not leaking.' => 'ANSI B56.1, Sec.6.1.14',
        'Lubrication points are accessible.' => 'ANSI B56.1, Sec.6.1.15',
        'No deterioration or leakage in air, water or hydraulic is found.' => 'ANSI B56.1, Sec.6.1.16',
        'No indication of loose, damaged, or missing structural components including supports and anchorages.' => 'ANSI B56.1, Sec.6.1.17',
        'Limit Switches are properly working.' => 'ANSI B56.1, Sec.6.1.18',
        'Brake & Clutch system parts & linings have no excessive wear, severe distortion, and damage.' => 'ANSI B56.1, Sec.6.1.19',
        'Seat and back cushion are not torn.' => 'ANSI B56.1, Sec.6.1.20',
    ],
    'Section 3. INSPECTION CRITERIA' => [
        'No deterioration or leakage in air or hydraulic is found.' => 'ANSI B56.1, Sec.7.1.1',
        'Tires have no tears, sidewall cuts & excessive wear.' => 'ANSI B56.1, Sec.7.1.2',
        'Wheel hubs and gear boxes have no leakages.' => 'ANSI B56.1, Sec.7.1.3',
        'Wheel bearings / bushing have no signs of excessive wear.' => 'ANSI B56.1, Sec.7.1.4',
        'No indication of loose, damaged, or missing components including supports and anchorages on under chassis.' => 'ANSI B56.1, Sec.7.1.5',
        'Control & drive mechanisms are properly adjusted and without excessive wear.' => 'ANSI B56.1, Sec.7.1.6',
        'Brake fluid level is correctly topped-up.' => 'ANSI B56.1, Sec.7.1.7',
        'Parking brake is correctly working.' => 'ANSI B56.1, Sec.7.1.8',
        'Steering cylinders, knuckles, kingpins, tie rods, equalizer bar, etc. have no excessive plays and not excessively worn-out.' => 'ANSI B56.1, Sec.7.1.9',
        'No damage tubing, piping, electrical cables, or hoses, and their fittings.' => 'ANSI B56.1, Sec.7.1.10',
    ],
    'Section 4. ENGINE & ELECTRICAL SYSTEM' => [
        'Engine has no excessive smoke, & engine oil leak.' => 'ANSI B56.1, Sec.8.1.1',
        'Fuel is not leaking.' => 'ANSI B56.1, Sec.8.1.2',
        'Engine has no loss of power.' => 'ANSI B56.1, Sec.8.1.3',
        'Fan, Alternator, & steering belts tension are not loose.' => 'ANSI B56.1, Sec.8.1.4',
        'All indicator lights are not broken and are functioning correctly.' => 'ANSI B56.1, Sec.8.1.5',
        'Strobe light or rotating beacon light is provided working.' => 'ANSI B56.1, Sec.8.1.6',
        'Head light & working lights are not broken and are functioning correctly.' => 'ANSI B56.1, Sec.8.1.7',
        'Brake & tail lights, including reverse light are working.' => 'ANSI B56.1, Sec.8.1.8',
        'Back-Up alarm is working.' => 'ANSI B56.1, Sec.8.1.9',
        'Horn is working.' => 'ANSI B56.1, Sec.8.1.10',
        'Radiator coolant level is correct and no sign of water leakage.' => 'ANSI B56.1, Sec.8.1.11',
        'Turn signal lights are provided and working correctly.' => 'ANSI B56.1, Sec.8.1.12',
        'Battery water/electrolyte level is correct.' => 'ANSI B56.1, Sec.8.1.13',
        'Fire extinguisher is provided and has the valid inspection tag.' => 'ANSI B56.1, Sec.8.1.14',
    ],
];

$html = '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INSPECTION CHECKLIST FOR FORKLIFT - PDF</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }
        
        @media print {
            body * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
        
        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 9px;
            line-height: 1.4;
        }
        
        .container {
            width: 100%;
            padding: 10px;
            margin: 0 auto;
        }
        
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        
        .header-table td {
            border: 1px solid #000;
            padding: 6px;
            font-size: 9px;
        }
        
        .logo-cell {
            width: 80px;
            text-align: center;
        }
        
        .main-title {
            font-weight: bold;
            font-size: 11px;
        }
        
        .subtitle {
            font-weight: bold;
            font-size: 10px;
        }
        
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        
        .info-table th {
            background-color: #c0d6e8 !important;
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
            font-weight: bold;
            font-size: 8px;
        }
        
        .info-table td {
            border: 1px solid #000;
            padding: 5px;
            font-size: 8px;
        }
        
        .checklist-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        
        .checklist-table thead th {
            background-color: #c0d6e8 !important;
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
            font-weight: bold;
            font-size: 8px;
        }
        
        .checklist-table tbody td {
            border: 1px solid #000;
            padding: 4px;
            font-size: 8px;
            vertical-align: top;
        }
        
        .sn-col {
            width: 4%;
            text-align: center;
        }
        
        .criteria-col {
            width: 42%;
            text-align: left;
        }
        
        .reference-col {
            width: 12%;
            text-align: left;
            font-size: 7px;
        }
        
        .pass-col, .fail-col, .na-col {
            width: 6%;
            text-align: center;
        }
        
        .remarks-col {
            width: 24%;
            text-align: left;
        }
        
        .section-title {
            background-color: #c0d6e8 !important;
            font-weight: bold;
            text-align: center;
        }
        
        .checkbox-mark {
            text-align: center;
            font-weight: bold;
            color: #000;
        }
        
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        .signature-table th {
            background-color: #c0d6e8 !important;
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            font-size: 8px;
        }
        
        .signature-table td {
            border: 1px solid #000;
            padding: 8px;
            font-size: 8px;
            height: 45px;
        }
        
        .keep-together {
            page-break-inside: avoid;
            break-inside: avoid;
        }
        .footer-section {
            page-break-inside: avoid;
        }
        
        .form-header {
            margin-bottom: 15px;
        }
        
        .form-header h3 {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .form-header h4 {
            font-size: 9px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        h4 {
            text-align: center;
            font-size: 10px;
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header Section -->
        <table class="header-table">
            <tr>
                <td class="logo-cell" rowspan="4">
                    <img src="' . pdf_asset('logo.png') . '" alt="CIMS Logo" style="max-width: 80px; max-height: 80px;">
                </td>
                <td colspan="3">
                    <span class="main-title">CRANE INSPECTION & MAINTENANCE SERVICES</span><br>
                    <span style="font-size: 8px;">A DIVISION OF AL-KHOBAR GATE INTERNATIONAL TRADING EST.</span>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <span class="subtitle">INSPECTION CHECKLIST FOR FORKLIFT</span>
                </td>
            </tr>
            <tr>
                <td style="width: 33%; font-weight: bold; font-size: 8px;">FRM.0601-1.15</td>
                <td style="width: 33%; font-weight: bold; font-size: 8px;">Revision 00</td>
                <td style="width: 34%; font-weight: bold; font-size: 8px;">Issue Date: 01/JAN/2020</td>
            </tr>
            <tr>
                <td colspan="3" style="font-size: 7px;">
                    <strong>Standards:</strong> ANSI/ITSDF B56.1 – 2018, ANSI/ITSDF B56.6 – 2016
                </td>
            </tr>
        </table>
        
        <!-- Equipment Information Table -->
        <table class="info-table">
            <tr>
                <th style="width: 25%;">REPORT NO</th>
                <td style="width: 25%; font-weight: bold;">' . htmlspecialchars($row['report_no']) . '</td>
                <th style="width: 25%;">INSPECTION DATE</th>
                <td style="width: 25%; font-weight: bold;">' . htmlspecialchars($row['inspection_date']) . '</td>
            </tr>
            <tr>
                <th>CLIENT\'S NAME</th>
                <td style="font-weight: bold;">' . htmlspecialchars($row['client_name']) . '</td>
                <th>INSPECTED BY</th>
                <td style="font-weight: bold;">' . htmlspecialchars($row['inspected_by']) . '</td>
            </tr>
            <tr>
                <th>LOCATION</th>
                <td style="font-weight: bold;">' . htmlspecialchars($row['location']) . '</td>
                <th>STICKER NO.</th>
                <td style="font-weight: bold;">' . htmlspecialchars($row['sticker_no']) . '</td>
            </tr>
            <tr>
                <th>EQUIPMENT NO</th>
                <td style="font-weight: bold;">' . htmlspecialchars($row['equipment_no']) . '</td>
                <th>EQUIP. SERIAL NO.</th>
                <td style="font-weight: bold;">' . htmlspecialchars($row['crane_serial_no']) . '</td>
            </tr>
            <tr>
                <th>EQUIPMENT TYPE</th>
                <td style="font-weight: bold;">' . htmlspecialchars($row['equipmenttype']) . '</td>
                <th>CAPACITY (SWL)</th>
                <td style="font-weight: bold;">' . htmlspecialchars($row['capacity_swl']) . '</td>
            </tr>
        </table>
        
        <!-- Checklist Table -->
        <table class="checklist-table">
            <thead>
                <tr>
                    <th class="sn-col">S.N</th>
                    <th class="criteria-col">ACCEPTANCE CRITERIA</th>
                    <th class="reference-col">REFERENCE</th>
                    <th class="pass-col">PASS</th>
                    <th class="fail-col">FAIL</th>
                    <th class="na-col">N/A</th>
                    <th class="remarks-col">REMARKS</th>
                </tr>
            </thead>
            <tbody>';

$itemIndex = 0;
$sectionNumber = 1;

foreach ($sections as $sectionTitle => $items) {
    // Section header row
    $html .= '<tr>
                <td colspan="7" class="section-title" style="font-size: 8px; padding: 5px;">' . htmlspecialchars($sectionTitle) . '</td>
            </tr>';
    
    $itemNumber = 1;
    foreach ($items as $criteria => $reference) {
        $itemLabel = $sectionNumber . '.' . $itemNumber;
        
        $html .= '<tr>
                    <td class="sn-col"><strong>' . $itemLabel . '</strong></td>
                    <td class="criteria-col"><strong>' . htmlspecialchars($criteria) . '</strong></td>
                    <td class="reference-col"><small>' . htmlspecialchars($reference) . '</small></td>
                    <td class="pass-col">' . pdf_mark_result($itemIndex, 'PASS', $selected_results) . '</td>
                    <td class="fail-col">' . pdf_mark_result($itemIndex, 'FAIL', $selected_results) . '</td>
                    <td class="na-col">' . pdf_mark_result($itemIndex, 'NA', $selected_results) . '</td>
                    <td class="remarks-col"><small>' . htmlspecialchars($chek_remark[$itemIndex] ?? '') . '</small></td>
                </tr>';
        
        $itemIndex++;
        $itemNumber++;
    }
    
    $sectionNumber++;
}

$html .= '
            </tbody>
        </table>
        
        <!-- Footer Section with page-break protection -->
        <div class="footer-section keep-together">
            <!-- Remarks Table -->
            <table class="signature-table" style="margin-bottom: 0;">
                <tr>
                    <th colspan="4">REMARKS / RECOMMENDATIONS</th>
                </tr>
                <tr>
                    <td colspan="4" style="height: 80px; vertical-align: top;">' . htmlspecialchars($recommendations ?? '') . '</td>
                </tr>
            </table>
            <!-- Signature Table -->
            <table class="signature-table">
                <tr>
                    <th style="width: 25%;">INSPECTOR\'S NAME:</th>
                    <td style="width: 25%; font-weight: bold;">' . htmlspecialchars($row['inspected_by']) . '</td>
                    <th style="width: 25%;">CLIENT\'S REP. NAME:</th>
                    <td style="width: 25%; font-weight: bold;">' . htmlspecialchars($client_name) . '</td>
                </tr>
                <tr>
                    <th>SIGNATURE & DATE:</th>
                    <td>' . pdf_signature_path($row['inspected_by']) . '</td>
                    <th>SIGNATURE & DATE:</th>
                    <td><img style="max-width: 60px; max-height: 25px;" src="' . pdf_asset('../../../../uploads/' . htmlspecialchars($project_no) . '.png') . '" alt="Client Signature" style="max-width: 60px; max-height: 25px;"></td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>';

?>
