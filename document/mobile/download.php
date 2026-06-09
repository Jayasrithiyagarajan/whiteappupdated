<?php
require_once('../../vendor/autoload.php');
include_once('../../file/config.php'); // include your database connection

// Get the project ID from the query parameter
$project_no = $_GET['project_no'];

// Fetch the data based on the projectNo
$sql = "SELECT * FROM mobile_crane_loadtest WHERE project_no = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $project_no);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    die("No data found for the given report no.");
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

$basePath = '../uploads/' . $row['technical_manager'];
$extensions = ['png', 'jpg', 'jpeg'];  // Add more if needed

$technicalManagerSignPath = null;

foreach ($extensions as $ext) {
    if (file_exists("{$basePath}.{$ext}")) {
        $technicalManagerSignPath = "{$basePath}.{$ext}";
        break;
    }
}

// Optional fallback if no image found
if (!$technicalManagerSignPath) {
    $technicalManagerSignPath = '../uploads/default-signature.png'; // or leave null
}

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


// use Mpdf\Mpdf;

$title = "MOBILE CRANE WITH LOAD TEST CERTIFICATE";
// Load Bootstrap CSS
// $bootstrapCSS = file_get_contents('../../assets/css/bootstrap.css');
$mpdf = new \Mpdf\Mpdf([
    'orientation' => 'P',
    'margin_left' => 5,
    'margin_right' => 5,
    'margin_top' => 5,
    'margin_bottom' => 5,
    'margin_header' => 5,
    'margin_footer' => 5
]);

// Set watermark image before writing content
// $mpdf->SetWatermarkImage('../logo.png', 0.2, 'F', [75, 70]); // 'F' for full page
// $mpdf->showWatermarkImage = true;

$mpdf->SetWatermarkImage('../logo.png', 0.2, '', [75, 70]);
$mpdf->showWatermarkImage = true;


// Your HTML content
$html = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mobile Crane with Load Test Certificate</title>
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
	<style>
.image-container {
  display: flex;
  justify-content: center; /* centers horizontally */
  align-items: center;     /* centers vertically */
  height: 100vh;           /* full screen height */
  width: 100vw;            /* full screen width */
}



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
margin: 3px;
      }
     
.head{
    width: 1200px;
    height: 80px;
}







.sign {
    height: 60px;
    max-width: 100px;
    object-fit: contain;
    display: block;
    margin: 0 auto;
  }

.signnew {
  max-width: 200px; /* optional: adjust as needed */
  height: auto;
}

    .seal {
      width: 30px;
      height: 30px;
    }
    
    
   @media print {
  .seal-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh; /* full printable area */
    width: 100vw;
    page-break-inside: avoid;
  }

  .seal-image {
    max-width: 200px;
    height: auto;
  }
}

	</style>
</head>
<body>
    <div class="container">
    <img src="../head.jpg" class="head" alt="Header Image" style="">
        <div class="row1">
        <h2>CERTIFICATE OF THOROUGH EXAMINATION / LOAD TEST<br>
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
                     Identification of any part found to have a defect which is or could become a danger to persons and a description of the defect:<br/>(If none state NONE)
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
	<strong>LEEA No: {$leea_number}</strong>
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

                <!--certificate2-->

                <div class="container mt-5">
                
		<img src="../head.jpg" class="head" alt="Header Image" style="">
    <h2>CERTIFICATE OF THOROUGH EXAMINATION/ LOAD TEST <br>
        <span>This report complies with the Lifting Equipment Engineers Association Technical requirements
        </span>
       </h2>
        <img src="../leea.png" class="leea" alt="Leea">
        <img src="../code.png" class="qrcode" alt="Qr Code">
        
        <table class="content-table" style="margin-top: 15px;">
            <tr>
                <td>Sticker No.: <strong> &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; {$row['sticker_no']} </strong></td>
                <td>Report No.: <strong> &nbsp; &nbsp; &nbsp; &nbsp;   &nbsp;{$row['report_no']}  </strong></td>
                <td>Certificate No.: <strong> &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; {$row['certificate_no']}</strong></td>
				
				
                
            </tr>
        </table>

        <table class="content-table">
            <tr class="section-title">
                <td colspan="5" style="text-align: center"><strong>B. LOAD TEST</strong></td>
            </tr>
            <tr>
                <td style="text-align: center"> Boom Length (m) </td>
                <td style="text-align: center"> Radius (m) </td>
                <td style="text-align: center"> Boom Angle (°) </td>
				<td style="text-align: center"> SWL/Test Weight
				</td>
				<td style="text-align: center"> Comments </td>              
            </tr>
            <tr>
                <td style="text-align: center"><strong>{$row['boom_length']}</strong></td>
                <td style="text-align: center"><strong>{$row['radius']}</strong></td>
                <td style="text-align: center"><strong>{$row['boom_angle']}</strong></td>
				<td style="text-align: center"><strong>{$row['swl_test_weight']}</strong></td>
				<td style="text-align: center; text-transform: uppercase;"><strong>{$row['comments']}</strong></td>           
				
            </tr>
			<tr>
                <td style="text-align: center"><strong></strong></td>
                <td style="text-align: center"><strong></strong></td>
                <td style="text-align: center"><strong></strong></td>
				<td style="text-align: center"><strong></strong></td>
				<td style="text-align: center"><strong></strong></td>           
            </tr>
        </table>
<p style="font-size: 10px; text-align: center; color: red;"><strong><i>Note: 	SWL Test weight is calculated by the following formula and includes the mass of lifting hook and slings, with test load rated lifting capacity of X 100% and outriggers are fully extended.</i></strong></p>
        <table class="content-table">
            <tr class="section-title">
                <td colspan="4" style="text-align: center"><strong>C. RESULT OF INSPECTION</strong></td>
            </tr>
            <tr class="section-title" >
                <td style="text-align: center; width: 25%;"><strong>OPERATION</strong></td>
				<td style="text-align: center; width: 25%;"><strong>COMMENTS</strong></td>
				<td style="text-align: center; width: 25%;"><strong>SAFETY DEVICES</strong></td>
				<td style="text-align: center; width: 25%;"><strong>COMMENTS</strong></td>              
            </tr>
            <tr>
                <td>Boom Lifting</td>
                <td style="text-align: center"><strong>{$row['boom_lifting']}</strong></td>
                <td> Auto Moment Limiter</td>
				<td style="text-align: center"><strong>{$row['auto_moment_limiter']}</strong></td>              
            </tr>
            <tr>
                <td> M. Winch Hoist</td>
                <td style="text-align: center"><strong>{$row['m_winch_hoist']}</strong></td>
                <td> Swing & Winch Brake </td>
				<td style="text-align: center"><strong>{$row['swing_winch_brake']}</strong></td>              
            </tr>
            <tr>
                <td> Aux. Winch Hoist </td>
                <td style="text-align: center"><strong>{$row['aux_winch_hoist']}</strong></td>
                <td> Winch Drum Lock (Pawl) </td>
				<td style="text-align: center"><strong>{$row['winch_drum_lock']}</strong></td>              
            </tr>
            <tr>
                <td> Boom Extending </td>
                <td style="text-align: center"><strong>{$row['boom_extending']}</strong></td>
                <td> Leveling Device </td>
				<td style="text-align: center"><strong>{$row['leveling_device']}</strong></td>              
            </tr>
            <tr>
                <td> Outriggers </td>
                <td style="text-align: center"><strong>{$row['outriggers']}</strong></td>
                <td> Hook Block Assembly </td>
				<td style="text-align: center"><strong>{$row['hook_block_assembly']}</strong></td>              
            </tr>
            <tr>
                <td> Swings / Slew </td>
                <td style="text-align: center"><strong>{$row['swings_slew']}</strong></td>
                <td> Boom Angle Indicator </td>
				<td style="text-align: center"><strong>{$row['boom_angle_indicator']}</strong></td>              
            </tr>
            <tr>
                <td> Hydraulic System </td>
                <td style="text-align: center"><strong>{$row['hydraulic_system']}</strong></td>
                <td> Wind Speed Indicator (Anemometer) </td>
				<td style="text-align: center"><strong>{$row['wind_speed_indicator']}</strong></td>              
            </tr>
        </table>

        <p style="font-size: 10px; text-align: center; color: red;"><strong>We hereby certify that the above Crane has been duly inspected and load tested as per the Manufacturer’s Recommendation or based on ASME B30.5 and conducted by a competent person and witnessed by certified inspector.</strong></p>
        
        

  
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
	<strong>LEEA No: {$leea_number}</strong>
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
	<img src="data:image/png;base64,<?= $technicalManagerSign ?>" class="sign">
	</td>
	</tr>
	
	
    </tbody>
  </table>
</div>      



        
<div class="space"></div>
        <p style="font-size: 10px; margin-top: 180px; text-align: center;  color: red;"><strong>This certificate contained herein is the good-faith opinion of CIMS – AGITE as to the Visual Condition of the crane inspected. This Certificate is in no way represents any guarantee expressed or implied as to the classification fitness for use of merchantability of the crane and in no event shall CIMS – AGITE be held liable for any damage as result to its use.</strong></p>
		
		<p style="font-size: 11px;text-align: center;">
		
		<strong><i>OVERSEAS FULL MEMBER OF LIFTING EQUIPMENT ENGINEERS ASSOCIATION (LEEA, UNITED KINGDOM) 662</i></strong> 
		</p>

        <div class="footer">
            <img src="../foot.jpg" alt="Footer Image">
        </div>

        


    </div>
    </body>
</html>
HTML;

// $mpdf->SetWatermarkImage('../logo.png', 0.3, '', [70, 100]);
// $mpdf->showWatermarkImage = true;

// $mpdf = new \Mpdf\Mpdf(['orientation' => 'P']); // 'L' for landscape
// $mpdf->WriteHTML($html);
// $mpdf->Output('mobile-crane.pdf', 'D'); // 'D' to force download, use 'I' to inline view



// Write the HTML content to the PDF
$mpdf->WriteHTML($html);

// Add watermark image
// $mpdf->SetWatermarkImage('../logo.png', 0.2, '', [75, 70]);
// $mpdf->showWatermarkImage = true;
// Output the PDF to the browser for download
// $mpdf->Output('mobile_crane_with_load_test.pdf', 'D');

$filename = "Crane_with_load_test_" . $project_no . ".pdf";
$mpdf->Output($filename, 'D'); // Force download

?>