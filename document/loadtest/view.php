<?php
require_once('../../vendor/autoload.php');
include_once('../../file/config.php'); // include your database connection

// Get the project ID from the query parameter
$project_no = $_GET['project_no'];

// Fetch the data based on the projectNo
$sql = "SELECT * FROM loadtest_certificate WHERE project_no = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $project_no);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    die("No data found for the given project id.");
}


$examResult = strtoupper(trim($row['first_examination']));
$examColor = ($examResult === 'YES') ? 'blue' : (($examResult === 'NO') ? 'red' : 'black');

$installedCorrectly = strtoupper(trim($row['installed_correctly']));
$installedColor = ($installedCorrectly === 'YES') ? 'blue' : (($installedCorrectly === 'NO') ? 'red' : 'black');

$interval6months = strtoupper(trim($row['interval_6_months']));
$interval6Color = ($interval6months === 'YES') ? 'blue' : (($interval6months === 'NO') ? 'red' : 'black');

$interval12months = strtoupper(trim($row['interval_12_months']));
$interval12Color = ($interval12months === 'YES') ? 'blue' : (($interval12months === 'NO') ? 'red' : 'black');

$examinationScheme = strtoupper(trim($row['examination_scheme']));
$examinationschemeColor = ($examinationScheme === 'YES') ? 'blue' : (($examinationScheme === 'NO') ? 'red' : 'black');

// $exceptionalCircumstances
$exceptionalCircumstances = strtoupper(trim($row['exceptional_circumstances']));
$exceptionalcircumstancesColor = ($exceptionalCircumstances === 'YES') ? 'blue' : (($exceptionalCircumstances === 'NO') ? 'red' : 'black');

$defectNew = strtoupper(trim($row['defect']));
$defectNewColor = ($defectNew === 'YES') ? 'blue' : (($defectNew === 'NO') ? 'red' : 'black');

$equipmentFit = strtoupper(trim($row['equipment_fit']));
$equipmentFitColor = ($equipmentFit === 'YES') ? 'blue' : (($equipmentFit === 'NO') ? 'red' : 'black');

// Fetch leea number from inspectors table
$leea_number = '';
$inspector_name = $row['inspector_name'];

$leea_sql = "SELECT leea_number FROM inspectors WHERE inspector_name = ?";
$leea_stmt = $conn->prepare($leea_sql);
$leea_stmt->bind_param('s', $inspector_name);
$leea_stmt->execute();
$leea_result = $leea_stmt->get_result();

if ($leea_result->num_rows > 0) {
    $leea_row = $leea_result->fetch_assoc();
    $leea_number = $leea_row['leea_number'];
}
$leea_stmt->close();

// 1. INSPECTOR SIGNATURE SOLUTION =====================================

$inspector_folder = strtolower(str_replace(' ', '_', $row['inspector_name'])); 

$technicalManagerSignPath = '../uploads/' . $row['technical_manager'] . '.jpg';
$qualityControllerSignPath = '../uploads/qc/' . $row['quality_controller'] . '.jpeg';

//$inspectorSign = base64_encode(file_get_contents($inspectorSignPath));
$technicalManagerSign = base64_encode(file_get_contents($technicalManagerSignPath));
$qualityControllerSign = base64_encode(file_get_contents($qualityControllerSignPath));
// $sealImage = base64_encode(file_get_contents($sealImagePath));

$formattedExamDate = date('d-m-Y', strtotime($row['examination_date']));

$reportDate = date('d-m-Y', strtotime($row['report_date']));
$latestDateExam = date('d-m-Y', strtotime($row['latest_date_exam']));


$stmt->close();
$conn->close();

// Create an instance of the mPDF class with landscape orientation and minimal margins
$mpdf = new \Mpdf\Mpdf([
    'orientation' => 'P',
    'margin_left' => 5,
    'margin_right' => 5,
    'margin_top' => 5,
    'margin_bottom' => 3,
    'margin_header' => 5,
    'margin_footer' => 5
]);

// HTML content
$html = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thorough Examination Certificate</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 5px;
            line-height: 1.2;
        }
        .container-fluid {
            max-width: 100%;
            /* margin: auto; */
            padding: 5px;
            /* border: 1px solid #000; */
            /* box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); */
        }
        h2 {
            text-align: center;
            font-size: 12px;
            margin-bottom:0px;
        }
        h2 span {
            text-align: center;
            font-size: 10px;
            
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }
        th, td {
            padding: 4px;
            border: 1px solid #000;
            text-align: left;
            font-size: 10px;
        }
        
        .section-title {
            background-color: #72c5f0;
            font-weight: bold;
        }
        
        .content-table td {
            vertical-align: top;
        }
        @media (max-width: 600px) {
            .header-table, .details-table, .content-table {
                font-size: 12px;
            }
        }
        .no-right-border {
            border-right: none;
        }
        .center-text {
            text-align: center;
        }
     
        .qrcode {
      width: 70px;
      height: 70px;
      float: right;
      margin-top: -54px;
         z-index: 1;
    }
    .leea {
      width: 65px;
      height: 62px;
      float: left;
      margin-top: -50px;
      z-index: 1;
    }
    .text-center{
text-align: center;
margin: 1px;
      }
     
.head{
    width: 1200px;
    height: 80px;
}





.sign {
    height: 90px;
    max-width: 100px;
    object-fit: contain;
    display: inline-block; /* Use inline-block instead of block */
}

    .seal {
      width: 30px;
      height: 30px;
    }

    </style>
</head>
<body>
    <div class="container">
    <img src="../head.jpg" class="head" alt="Header Image" style="height: 120px; ">
        <div class="row1">
        <h2>CERTIFICATE OF THOROUGH EXAMINATION <br>
        <span>This report complies with the Lifting Equipment Engineers Association Technical requirements
        </span>
       </h2>
        <img src="../leea.png" class="leea" alt="Leea">
        <img src="../code.png" class="qrcode" alt="Qr Code">
   
   
   <table class="content-table">
            <tr>
                <td>
            <span>
                Date of Thorough Examination:
            </span>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <strong> {$formattedExamDate} </strong>
            
        </td>


               <td>Date of Report: &nbsp; &nbsp; &nbsp; &nbsp;<strong>{$reportDate}</strong></td>


                <td>
            Report Number: &nbsp; &nbsp; &nbsp; &nbsp; <strong>{$row['report_no']}</strong><br/>
            Sticker Number: &nbsp; &nbsp; &nbsp; &nbsp; <strong>{$row['sticker_no']}</strong>
        </td>

                
            </tr>
    </table>
    
    <table class="content-table">
            <tr>
                <td colspan="3" class="center-text"> Name and Address of employer for whom the thorough examination was made <br/>
                <br/>
                <span style="text-transform: uppercase; font-size: 12px; color: blue; margin-top: 10px;">
                <strong>{$row['employer_address']}</strong>
                </span>
                <div>
                </div>
                </td>
                
                <td colspan="3" class="center-text"> Address of premises at which the examination was made <br/>
                <br/>
                <span style="text-transform: uppercase; font-size: 12px; color: blue; margin-top: 10px;">
                <strong>{$row['premises_address']}</strong>
                </span>
                <div>
                </div>
                </td>
            </tr>
            <tr>
                <td colspan="3" class="center-text"> Description and Identification of the equipment <br/>
                <span style="text-transform: uppercase; font-size: 12px;">
                <strong>{$row['equipment_description']}</strong> </span>
                </td>
                <td class="center-text"> Safe Working Load(s) </td>
                <td class="center-text"> Date of manufacture if known </td>
                <td class="center-text"> Date of last thorough examination </td>
            </tr>
            <tr>
               <td colspan="3" class="no-right-border">
    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border: none;">
        <tr>
            <td style="border: none;">Manufacturer:<strong> {$row['manufacturer']} </strong></td>
            <td align="right" style="border: none;">Certificate No.: <strong>{$row['certificate_no']} </strong></td>
        </tr>
        <tr>
            <td style="border: none;">Model No.: <strong>{$row['model']} </strong></td>
            <td align="right" style="border: none;"> JRN:<strong> {$row['jrn']} </strong></td>
        </tr>
        <tr>
            <td style="border: none;"> Equipment ID No.:<strong> {$row['equipment_id']}</strong></td>
            <td align="right" style="border: none;"> Equipment Serial No.:<strong> {$row['equipment_serial_no']} </strong></td>
        </tr>
        <tr>
            <td colspan="2" style="border: none;"><strong>{$row['common_text_area']}</strong></td>
        </tr>
    </table>
</td>

                <td class="center-text" style="text-align: center;  text-transform: uppercase; vertical-align: middle;">
                
                <strong>{$row['safe_working_load']}</strong></td>
                <td class="center-text" style="text-align: center; text-transform: uppercase; vertical-align: middle;">
                <strong>{$row['manufacture_date']}</strong></td>
                <td class="center-text" style="text-align: center; text-transform: uppercase; vertical-align: middle;">
                <strong>{$row['last_exam_date']}</strong> </td>
            </tr>
			
			<tr>
			   
				<th class="section-title center-text" style="text-align: center;" colspan="6" > WAS THE EXAMINATION CARRIED OUT </th>
            
			</tr>
            <tr>
                
<td colspan="2" rowspan="2" class="no-right-border"> 
    Is this the first examination after installation or assembly at a new site or location? 
</td>
<td class="center-text" rowspan="2" style="text-transform: uppercase; vertical-align: middle; color: {$examColor};">
    <strong>{$examResult}</strong>
</td>

                <td colspan="2">Within an interval of 6 months? </td>
                <td class="center-text" style="text-transform: uppercase; color: {$interval6Color};">
                <strong>{$interval6months}</strong></td>
            </tr>
            <tr>
                <td colspan="2"> Within an interval of 12 months? </td>
                <td class="center-text" style="text-transform: uppercase; color: {$interval12Color};">
                <strong>{$interval12months}</strong></td>
            </tr>
            <tr>
			<td colspan="2" rowspan="2" class="no-right-border"> If the answer to the above question is YES has the equipment been installed correctly? </td>
                <td class="center-text" rowspan="2" style="text-transform: uppercase; vertical-align: middle;  color: {$installedColor};">
                <strong>{$installedCorrectly}</strong>
                </td>
                <td colspan="2"> In accordance with an examination scheme? </td>
                <td class="center-text" style="text-transform: uppercase; color: {$examinationschemeColor};">
                <strong>{$examinationScheme}</strong></td>
                
            </tr>
			<tr>
			<td colspan="2"> After the occurrence of exceptional circumstances? </td>
                <td class="center-text" style="text-transform: uppercase; color: {$exceptionalcircumstancesColor};">
                <strong>{$exceptionalCircumstances}</strong></td>
			</tr>
            <tr>
                <td colspan="6" class="center-text">
                     Identification of any part found to have a defect which is or could become a danger to persons and a description of the defect: (If none state NONE)
                     <br/>
                    <strong>{$row['identification_any_part']}</strong>
                </td>
            </tr>
            <tr>
                <td colspan="5"> Is the above a defect which is of immediate danger to persons </td>
                <td class="center-text" style="text-transform: uppercase; color: {$defectNewColor};">
                <strong>{$defectNew}</strong></td>
            </tr>
			
			<tr>
			<td colspan="5"> Is the above a defect which is not yet but could become a danger to persons: (If YES state the date by when) </td>
                <td class="center-text">{$row['defect_future']} by:<br/>
                <br/>
                <strong>{$row['date_defect']} 
                </strong></td>
			</tr>
            <tr>
                <td colspan="6" class="center-text">
                    Particulars of any repair renewal or alteration required to remedy the defect identified above: <br/>
                    <span style="text-transform: uppercase;">
                    <strong>{$row['repair_details']}</strong>
                    </span>
                </td>
            </tr>
            <tr>
                <td colspan="6" class="center-text">
                    Particulars of any tests carried out as part of the examination: (If none state NONE)<br/>
                    <span style="text-transform: uppercase;">
                    <strong>{$row['test_particulars']}</strong>
                    </span>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                     IS THIS EQUIPMENT FIT FOR PURPOSE? </td>
                    <td  colspan="2" class="center-text" style="text-transform: uppercase; color: {$equipmentFitColor};">
                    <strong>{$equipmentFit}</strong>
                </td>
            </tr>
            <tr>
				<td colspan="6" class="center-text">
                     Latest date by which next thorough examination must be carried out:<br/>
                   <span style="color: red;"> <strong>{$latestDateExam} </strong></span>
                </td>
            </tr>
        </table>
        
<div class="table-responsive keep-together">
  <table class="content-table">
    <thead>
      <tr>
        <th class="section-title text-center" colspan="2" style="text-align: center;">INSPECTED BY</th>
        <th class="section-title text-center" style="text-align: center;">QUALITY REVIEWED</th>
        <th class="section-title text-center" style="text-align: center;">APPROVED BY</th>
        <th class="section-title text-center" style="text-align: center;">SEAL</th>
      </tr>
    </thead>
    <tbody>
    
	<tr>
	<td style="text-align: center;">
	<strong>{$row['inspector_name']} </strong>
	</td>	
	
	<td style="text-align: center;">
	<strong>Leea No: {$leea_number}</strong>
	</td>	
	
	
	<td style="text-align: center; vertical-align: middle;" rowspan="2">
    <img src="../qcpass.png" class="sign" style="display: block; margin: 0 auto;">
</td>

	<td style="text-align: center;">
	<strong>
	Technical Manager
	</strong>
	</td>
	<td rowspan="2"  style="text-align: center;">
	<img src="../seal.jpeg" class="sign" alt="Default Signature">
	</td>
	</tr>
	
	<tr>
	<td colspan="2"  style="text-align: center;">
	<img src="../../inspector/uploads/{$inspector_folder}/images/signature_image.jpg" class="sign">
	</td>
	
	<td  style="text-align: center;">
	<img src="data:image/jpg;base64,<?= $technicalManagerSign ?>" class="sign">
	</td>
	</tr>
	
	
    </tbody>
  </table>
</div>            

<div class="table-responsive keep-together">
  <table class="content-table">
    <tbody>
<tr>
                <td colspan="6" class="center-text">
                     Name and address of employer of persons making and authenticating this report: <br/>
                    <span><strong><i>  CIMS, P.O. BOX 74007, AL ANDALUS STREET, AL RAKAH, AL KHOBAR 31952 </i></strong></span>
                </td>
            </tr>
            </tbody>
  </table>
</div>            
            
            
        
        <p style="text-align: center; font-size: 10px;" >
        <strong> <i>OVERSEAS FULL MEMBER OF LIFTING EQUIPMENT ENGINEERS ASSOCIATION (LEEA UNITED KINGDOM) 662</i></strong>
        <br/>
        <span style="text-align: center; color: red; font-size: 10px;">
FRM. 0608 (rev.02) 08/05/2023        
        </span>
        </p>
        
        
        <div class="footer">
                <img src="../foot.jpg" alt="Footer Image" style="width: 100%;">
        </div>
    </div>
    </div>
</body>
</html>

HTML;

// Write the HTML content to the PDF
$mpdf->WriteHTML($html);

// Add watermark image
$mpdf->SetWatermarkImage('../logo.png', 0.2, '', [75, 70]);
$mpdf->showWatermarkImage = true;
// Output the PDF to the browser for inline viewing
// $mpdf->Output('loadtest.pdf', 'I');

// Create the filename
$filename = "Crane_without_loadtest_certificate_" . $project_no . ".pdf";

// Output the PDF to the browser for inline viewing with the desired filename
$mpdf->Output($filename, 'I');
?>