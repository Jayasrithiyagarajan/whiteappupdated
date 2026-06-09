<?php
require_once('../../vendor/autoload.php'); // Adjust the path as necessary

// Sample data array
$certificateData = [
    'certificate_no' => '240120291',
    'reference_no' => '2029',
    'customer_name' => 'HAMZA S. BALHARITH EST. (HSBE)',
    'location' => '2ND INDUSTRIAL DAMMAM',
    'inspection_date' => '28-JAN-2024',
    'next_inspection_date' => '27-Jul-2024',
    'inspected_item' => 'FORKLIFT TRUCK FORK',
    'swl' => '10 TON',
    'serial_numbers' => '01102B0474',
    'manufacturer_equip_no' => 'HELI / FL-008 (2030-SAA)',
    'standards' => 'ASTM E 709 & BS EN 9934-1:2016',
    'mpi_equip_type' => 'PERMANENT MAGNET',
    'brand' => 'MAGNAFLUX',
    'current' => 'N/A',
    'prod_spacing' => '10-15 CM',
    'contrast_paint' => '8903W/ARDROX',
    'ink' => '800/3/ARDOX',
    'particle_medium' => 'WET',
    'yoke_sn' => '039',
    'calibration_expiry_date' => '11-FEB-2024',
    'model_no' => 'N/A',
    'result' => 'MPI HAD BEEN DONE FOR ABOVE DESCRIPTION AND FOUND: FREE FROM ANY SURFACE CRACKS AT TIME OF INSPECTION MPI ACCEPTED',
    'inspector' => 'SHAROON B. MASIH',
    'level' => 'NDT LEVEL III',
    'signature' => 'SIGNATURE'
];

$html = '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Magnetic Particle Inspection Certificate</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #000;
            position: relative;
        }
        .header, .footer {
            text-align: center;
        }
        .header img, .footer img {
            max-width: 100%;
            height: auto;
        }
        .content {
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        .result-comments {
            margin-top: 20px;
        }
        .signature {
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="../head.jpg" alt="Header Image">
        </div>
        <div class="content">
            <h1>Magnetic Particle Inspection Certificate</h1>
            <table>
                <tr>
                    <th>CERTIFICATE NO.</th>
                    <td>'.$certificateData['certificate_no'].'</td>
                    <th>REFERENCE NO.</th>
                    <td>'.$certificateData['reference_no'].'</td>
                </tr>
                <tr>
                    <th>CUSTOMER NAME</th>
                    <td colspan="3">'.$certificateData['customer_name'].'</td>
                </tr>
                <tr>
                    <th>LOCATION</th>
                    <td colspan="3">'.$certificateData['location'].'</td>
                </tr>
                <tr>
                    <th>INSPECTION DATE</th>
                    <td>'.$certificateData['inspection_date'].'</td>
                    <th>NEXT INSPECTION DATE</th>
                    <td>'.$certificateData['next_inspection_date'].'</td>
                </tr>
                <tr>
                    <th>INSPECTED ITEM</th>
                    <td colspan="3">'.$certificateData['inspected_item'].' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; SWL: '.$certificateData['swl'].'</td>
                </tr>
                <tr>
                    <th>SERIAL NUMBERS</th>
                    <td colspan="3">'.$certificateData['serial_numbers'].'</td>
                </tr>
                <tr>
                    <th>MANUFACTURER / EQUIP. NO.</th>
                    <td colspan="3">'.$certificateData['manufacturer_equip_no'].'</td>
                </tr>
                <tr>
                    <th>STANDARDS</th>
                    <td colspan="3">'.$certificateData['standards'].'</td>
                </tr>
                <tr>
                    <th colspan="4">TESTING TOOLS</th>
                </tr>
                <tr>
                    <th>MPI EQUIP. TYPE</th>
                    <td>'.$certificateData['mpi_equip_type'].'</td>
                    <th>BRAND</th>
                    <td>'.$certificateData['brand'].'</td>
                </tr>
                <tr>
                    <th>CURRENT</th>
                    <td>'.$certificateData['current'].'</td>
                    <th>PROD. SPACING</th>
                    <td>'.$certificateData['prod_spacing'].'</td>
                </tr>
                <tr>
                    <th>CONTRAST PAINT</th>
                    <td>'.$certificateData['contrast_paint'].'</td>
                    <th>INK</th>
                    <td>'.$certificateData['ink'].'</td>
                </tr>
                <tr>
                    <th>PARTICLE MEDIUM</th>
                    <td>'.$certificateData['particle_medium'].'</td>
                    <th>YOKE S/N</th>
                    <td>'.$certificateData['yoke_sn'].'</td>
                </tr>
                <tr>
                    <th>CALIBRATION EXPIRY DATE</th>
                    <td>'.$certificateData['calibration_expiry_date'].'</td>
                    <th>MODEL NO.</th>
                    <td>'.$certificateData['model_no'].'</td>
                </tr>
            </table>
            <div class="result-comments">
                <strong>RESULT</strong><br>
                '.nl2br($certificateData['result']).'
            </div>
            <div class="signature">
                <table>
                    <tr>
                        <th>NDT INSPECTOR</th>
                        <th>'.$certificateData['level'].'</th>
                    </tr>
                    <tr>
                        <td>'.$certificateData['inspector'].'</td>
                        <td>'.$certificateData['signature'].'</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="footer">
            <img src="../foot.jpg" alt="Footer Image">
        </div>
    </div>
</body>
</html>
';

$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($html);
$mpdf->Output('certificate.pdf', \Mpdf\Output\Destination::DOWNLOAD);
?>
