<?php
require_once '../vendor/autoload.php'; // Include the autoloader from composer
require_once '../file/config.php'; // Include the existing database connection


// Specify the path to the single QR code file
$qrCodeFile = 'output/code.png'; // Replace with your specific QR code file path


// Get the sticker number from the URL (e.g., ?sticker_no=12345)
if (!isset($_GET['sticker_start_no']) || empty($_GET['sticker_start_no'])) {
    die("Sticker No is required in the URL (e.g., ?sticker_start_no=12345)");
}

$stickerNumber = $_GET['sticker_start_no'];

// Fetch sticker details from the database
$query = "SELECT * FROM stickers WHERE sticker_start_no = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $stickerNumber);
$stmt->execute();
$result = $stmt->get_result();
$stickerData = $result->fetch_assoc();

// Check if data exists
if (!$stickerData) {
    die("Sticker details not found for Sticker No: $stickerNumber");
}

// Initialize mPDF with custom page size
$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => [140, 140]]);

// Start buffering the output
ob_start();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Print Sticker</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	
    <style>
		@page { 
            sheet-size: 140mm 140mm; 
            padding: 0;
            margin: 0;
		}

		@page toc { sheet-size: A4; }

        @media print {
			.button2 {
				border: 1px solid #fff;
				background: #fff;
				width: 120px;
				padding: 6px;
				border-radius: 50px;
				text-align: center;
				font-size: 18px;
				font-weight: 600;
				margin: 10px;
			}
			
            body {
                margin: 0;
                padding: 0;
            }

            .qr-code {
                width: 20in;
                height: 8in;
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                page-break-after: always; /* Ensure each QR code is on a new page */
                background: #ff0000;
                padding: 6px 15px;
                page-break-after: avoid;
            }
			
			.num {
				margin-top: 10px;
				padding-bottom: 12px;
				font-weight: bold;
			}
			
			.num1 {
				margin-top: 5px;
				font-weight: bold;
			}							

            img {
                display: block;
                width: 100%;
                height: 100%;
            }
			
			h5 {
				font-size: 15px;
				font-weight: 800;
				color: #000;
			}
			
			span {
				font-weight: bold;
				color: #ffff;
				font-size: 15px;
			}
        }
    </style>
</head>
<body>
<div class="container">
  <div class="row">
	<?php
	// Extract the unique ID from the QR code file
	$fileNameWithoutExtension = pathinfo($qrCodeFile, PATHINFO_FILENAME);

	// Use the filename as the unique ID for now
	$uniqueID = $fileNameWithoutExtension;

	?>
	<div class="qr-code">
		<img src="./header.png" alt="CIMS" style="padding-top:0px;width:100%;height:102px;"/>
		<div style="padding-left:55px;margin-top:-20px;">
			<div style="margin-top:-40px;">
				<img src="<?php echo $qrCodeFile; ?>" alt="CIMS" style="width:160px;height:160px;padding-left:260px">
			</div>
		</div> 

		<div class="col-md-12" style="margin-top:-116px"> 
			<div class="row" style="text-transform: uppercase;font-weight:800;padding-top:20px">
				<div class="button2">REJECTED</div>
				<h5 class="num" style="padding-top:10px">Sticker No: <span><?php echo $stickerData['sticker_start_no']; ?></span></h5>
				<h5 class="num" style="padding-top:10px">Equipment No: <span><?php echo $stickerData['equipment_no']; ?></span> </h5>
				<h5 class="num">Equipment Serial No: <span><?php echo $stickerData['equipment_serial_no']; ?></span> </h5>
				<h5 class="num">Date Inspected: <span><?php echo $stickerData['inspection_date']; ?></span> </h5>
				<h5>
					THIS EQUIPMENT SHALL NOT BE OPERATED ON SAUDI ARAMCO FACILITIES / PROJECTS 
					UNTIL REPAIRS HAVE BEEN COMPLETED & EQUIPMENT RE-INSPECTED (SEE LIFTING EQUIPMENT INSPECTION REPORT)
				</h5>
				<h5>
					لايجوز تشغيل هذه الاله في مرافق / مشاريع ارامكو السعودية الا بعد اصلاحها ومعاينتها للتاكد من صلاحيتها للتشغيل (انظر التقرير الخاص بالرافعة)
				</h5>
				<h5 class="num">Inspected By: <span><?php echo $stickerData['assign_inspector']; ?></span> </h5>
			</div>  
		</div>  
	</div>
  </div>
</div>
</body>
</html>
<?php

// Get the buffered output and close the buffer
$html = ob_get_clean();

// Write HTML to PDF
$mpdf->WriteHTML($html);

// Output PDF to the browser
$mpdf->Output('single_sticker.pdf', \Mpdf\Output\Destination::INLINE);
