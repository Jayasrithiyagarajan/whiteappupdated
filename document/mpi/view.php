<?php
require_once('../../vendor/autoload.php');
include_once('../../file/config.php');

// Fetch the project_no from the request
$project_no = $_GET['project_no'] ?? '';
if (empty($project_no)) {
    die("Project number is required");
}

// Fetch all certificates for this project
$sql = "SELECT * FROM mpi_certificates WHERE project_no = ? ORDER BY id";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $project_no);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("No certificates found for this project");
}

$mpdf = new \Mpdf\Mpdf([
    'orientation' => 'P',
    'margin_left' => 5,
    'margin_right' => 5,
    'margin_top' => 5,
    'margin_bottom' => 5,
    'margin_header' => 5,
    'margin_footer' => 5,
    'tempDir' => sys_get_temp_dir()
]);

// CSS Styles
$stylesheet = <<<CSS
<style>
/* your same CSS content from earlier */
.page-break {
    page-break-before: always;
}
body {
    font-family: Arial, sans-serif;
    font-size: 12px;
  }
  .certificate-title {
    text-align: center;
    margin: 4px;
  }
  .signature-section {
    display: flex;
    justify-content: space-between;
    margin-top: 10px;
  }
  .signature {
    text-align: center;
  }
  
  body {
          font-family: Arial, sans-serif;
          margin: 0;
          padding: 10px;
          line-height: 1.6;
      }
      .container {
          max-width: 800px;
          margin: auto;
          padding: 20px;
          border: none;
          box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      }
      h1 {
          text-align: center;
          font-size: 14px;
          margin: 20px 0;
      }
      p {
          text-align: center;
          font-size: 12px;
      }
      table {
          width: 100%;
          border-collapse: collapse;
          margin-bottom: 5px;
      }
      th, td {
          padding: 3px;
          border: 1px solid #000;
          text-align: left;
          font-size: 10px;
      }
      .header-table, .details-table {
          border: none;
          margin-bottom: 0;
      }
      .header-table th, .header-table td {
          border: none;
          padding: 5px;
      }
      .section-title {
          background-color: #bfdaef;
          
          font-size: 10px;
      }
      .answer {
          color: red;
          font-weight: bold;
      }
       .header, .footer {
          text-align: center;
      }
      .header img{
          max-width: 100%;
          height: 126px;
      }
     .footer img {
          max-width: 100%;
          height: 86px;
      }

      .sign {
    height: 60px;
    max-width: 100px;
    object-fit: contain;
    display: block;
    margin: 0 auto;
  }
    .seal {
      width: 30px;
      height: 30px;
    }
      .qrcode{
          width:73px;
          height:73px;
          float:right;
          margin-top:0px;
      }
      .leea{
        width: 69px;
        height: 67px;
          float:left;
          margin-top:0px;
      }
      .text-center{
            text-align: center;
            margin: 0px;
      }
      .text-center button{
        
    padding: 18px;
    font-size: 14px;
    font-weight: 800;
    background: rgb(8, 177, 255);
    }

      @media (max-width: 600px) {
          .header-table, .details-table, .content-table {
              font-size: 12px;
          }
      }
</style>
CSS;

$mpdf->WriteHTML($stylesheet);

// Use fetch_all so we can track the loop index
$certificates = $result->fetch_all(MYSQLI_ASSOC);
$total = count($certificates);
$current = 0;

foreach ($certificates as $row) {
    $current++;

    // Get LEEA number
    $leea_number = "N/A";
    $leea_stmt = $conn->prepare("SELECT leea_number FROM inspectors WHERE inspector_name = ?");
    $leea_stmt->bind_param("s", $row['inspector']);
    $leea_stmt->execute();
    $leea_result = $leea_stmt->get_result();
    if ($leea_result->num_rows > 0) {
        $leea_row = $leea_result->fetch_assoc();
        $leea_number = $leea_row['leea_number'];
    }

    // Process images
    
   $imageHtml = '';
$fixedWidth = '200px'; // Desired fixed width
$fixedHeight = '250px'; // Desired fixed height

$images = json_decode($row['images'] ?? '[]', true) ?: [];

foreach ($images as $image) {
    $imagePath = '../../uploads/mpi_certificates/' . $image;
    if (file_exists($imagePath)) {
        $imageHtml .= "<td style='text-align: center; vertical-align: middle;'>
            <img src='$imagePath' alt='Inspection Image' 
            style='width: $fixedWidth; height: $fixedHeight; object-fit: contain; margin: auto; display: block;'>
        </td>";
    }
}


    // Signature paths
    $inspector_name = strtolower(str_replace(' ', '_', $row['inspector']));
    $inspector_signature = "../../inspector/uploads/{$inspector_name}/images/signature_image.jpg";
    $technicalBasePath = "../uploads/{$row['technical_manager']}";
if (file_exists("{$technicalBasePath}.png")) {
    $technical_signature = "{$technicalBasePath}.png";
} elseif (file_exists("{$technicalBasePath}.jpg")) {
    $technical_signature = "{$technicalBasePath}.jpg";
} elseif (file_exists("{$technicalBasePath}.jpeg")) {
    $technical_signature = "{$technicalBasePath}.jpeg";
} else {
    $technical_signature = "../default-signature.jpg";
}
    $qc_signature = "../uploads/qc/{$row['quality_controller']}.jpeg";

    if (!file_exists($inspector_signature)) $inspector_signature = "../default-signature.jpg";
    if (!file_exists($technical_signature)) $technical_signature = "../default-signature.jpg";
    if (!file_exists($qc_signature)) $qc_signature = "../default-signature.jpg";

    $inspectionDate = date('d-m-Y', strtotime($row['inspection_date']));
    $nextInspectionDate = date('d-m-Y', strtotime($row['next_inspection_date']));
    $calibrationExpiryDate = date('d-m-Y', strtotime($row['calibration_expiry_date']));

    $html = <<<HTML
    <div class="page">
        <div class="header">
            <img src="../head.jpg" alt="Header">
        </div>
        <img src="../leea.png" class="leea" alt="LEEA Logo">
        <img src="../code.png" class="qrcode" alt="QR Code">
        <h3 class="certificate-title">MAGNETIC PARTICLE INSPECTION CERTIFICATE</h3>

        <!-- Certificate Details -->
        <div class="table-responsive">
            <table class="content-table">
                <tbody>
                    <tr>
                        <td class="section-title" style="width: 25%;">CERTIFICATE NO.</td>
                        <td style="width: 25%;"><strong>{$row['certificate_no']}</strong></td>
                        <td class="section-title" style="width: 25%;">REPORT NO.</td>
                        <td  style="width: 25%;"><strong>{$row['report_no']}</strong></td>
                    </tr>
                    <tr>
                        <td class="section-title">CUSTOMER NAME</td>
                        <td colspan="3" style="text-transform: uppercase;"><strong>{$row['customer_name']}</strong></td>
                    </tr>
                    <tr>
                        <td class="section-title">LOCATION</td>
                        <td colspan="3" style="text-transform: uppercase;"><strong>{$row['location']}</strong></td>
                    </tr>
                    <tr>
                        <td class="section-title">INSPECTION DATE</td>
                        <td><strong>{$inspectionDate}</strong></td>
                        <td class="section-title">NEXT INSPECTION DATE</td>
                        <td><strong>{$nextInspectionDate}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Equipment Details -->
        <div class="table-responsive">
        <table class="content-table">
            <tbody>
                
                <tr>
                    <td  class="section-title" style="width: 25%;">INSPECTED ITEM</td>
                    <td style="text-transform: uppercase; width: 25%;"><strong>{$row['inspected_item']}</strong></td>
                    <td class="section-title" style="text-transform: uppercase; width: 25%;"> SWL </td>
                    <td style="text-transform: uppercase; width: 25%;"><strong>{$row['swl']}</strong></td>
                </tr>
                <tr>
                    <td  class="section-title">SERIAL NUMBERS</td>
                    <td colspan="3" style="text-transform: uppercase;"><strong>{$row['serial_numbers']}</strong></td>
                </tr>
                <tr>
                    <td  class="section-title">MANUFACTURER / EQUIP. NO.</td>
                    <td colspan="3" style="text-transform: uppercase;"><strong>{$row['manufacturer']}</strong></td>
                </tr>
                <tr>
                    <td  class="section-title">STANDARDS</td>
                    <td colspan="3" style="text-transform: uppercase;"><strong>{$row['standards']}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>

   
    <div class="table-responsive">
        <table class="content-table">
            <tbody>
			<tr>
                    <td class="section-title" style="text-align: center" colspan="4">TESTING TOOLS</td>
                    
                </tr>
                <tr>
                    <td class="section-title" style="width: 25%;">MPI EQUIP. TYPE</td>
                    <td style="text-transform: uppercase; width: 25%;"><strong>{$row['mpi_equip_type']}</strong></td>
                    <td class="section-title" style="width: 25%;">BRAND</td>
                    <td style="text-transform: uppercase; width: 25%;"><strong>{$row['brand']}</strong></td>
                </tr>
                <tr>
                    <td class="section-title">CURRENT</td>
                    <td style="text-transform: uppercase;"><strong>{$row['current']}</strong></td>
                    <td class="section-title">PROD. SPACING</td>
                    <td style="text-transform: uppercase;"><strong>{$row['prod_spacing']}</strong></td>
                </tr>
                <tr>
                    <td class="section-title">CONTRAST PAINT</td>
                    <td style="text-transform: uppercase;"><strong>{$row['contrast_paint']}</strong></td>
                    <td class="section-title">INK</td>
                    <td style="text-transform: uppercase;"><strong>{$row['ink']}</strong></td>
                </tr>
                <tr>
                    <td class="section-title" >PARTICLE MEDIUM</td>
                    <td style="text-transform: uppercase;"><strong>{$row['particle_medium']}</strong></td>
                    <td class="section-title">YOKE S/N</td>
                    <td style="text-transform: uppercase;"><strong>{$row['yoke_sn']}</strong></td>
                </tr>
                <tr>
                    <td class="section-title">CALIBRATION EXPIRY DATE</td>
                    <td style="text-transform: uppercase;"><strong>{$calibrationExpiryDate}</strong></td>
                    <td class="section-title">MODEL NO.</td>
                    <td style="text-transform: uppercase;"><strong>{$row['model_no']}</strong></td>
                </tr>
				
            </tbody>
        </table>
    </div>

        <!-- Images -->
        <table><tr>{$imageHtml}</tr></table>

        <!-- Results -->
        <table>
            <tr>
                <td class="section-title">RESULT</td>
                <td class="section-title">COMMENTS/ACTION</td>
            </tr>
            <tr>
                <td><strong>MPI HAD BEEN DONE FOR ABOVE DESCRIPTION AND FOUND:<br>{$row['result']}</strong></td>
                <td><strong>{$row['comments']}</strong></td>
            </tr>
        </table>

        <!-- Signatures -->
        <table class="keep-together">
            <tr>
                <th colspan="2" class="section-title text-center">NDT INSPECTOR</th>
                <th class="section-title text-center">QUALITY REVIEWED</th>
                <th class="section-title text-center">NDT LEVEL 3</th>
                <th class="section-title text-center">SEAL</th>
            </tr>
            <tr>
                <td colspan="2" class="text-center"><strong>{$row['inspector']}</strong></td>
                <td rowspan="2" class="text-center"><img src="../qcpass.png" class="sign" alt="QC Pass"></td>
                <td class="text-center"><strong>ASNT NDT LEVEL 3</strong></td>
                <td rowspan="2" class="text-center"><img src="../seal.jpeg" class="sign" alt="Seal"></td>
            </tr>
            <tr>
                <td colspan="2" class="text-center">
                    <img src="{$inspector_signature}" class="sign" alt="Inspector Signature">
                    <div>LEEA No: {$leea_number}</div>
                </td>
                <td class="text-center">
                    <img src="{$technical_signature}" class="sign" alt="Technical Manager Signature">
                </td>
            </tr>
        </table>

        <p class="text-center" style="color: red;"><strong>FRM.0702 (rev.02)</strong></p>

        <div class="footer">
            <img src="../foot.jpg" alt="Footer">
        </div>
    </div>
HTML;

    // Add page break only if not the last one
    if ($current < $total) {
        $html .= '<div class="page-break"></div>';
    }

    $mpdf->WriteHTML($html);
}

$conn->close();

// Output the PDF directly to the browser for viewing
$mpdf->Output('MPI_Certificate_' . $project_no . '.pdf', 'I');