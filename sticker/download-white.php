<?php
require_once '../vendor/autoload.php'; // Include the autoloader from composer
require_once '../file/config.php'; // Include the existing database connection
// Path to the single QR code file
$qrCodeFile = 'output/code.png'; // Replace 'sample.png' with your desired file

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

// Initialize mpdf
$mpdf = new \Mpdf\Mpdf();

// Start buffering the output
ob_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Print White Sticker</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <style>
        @page { 
            sheet-size: 130mm 130mm; 
            padding: 0;
            margin: 0;
        }

        @page toc { 
            sheet-size: A4; 
        }

        /* Custom styles for the print view */
        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .qr-code {
                width: 20in;
                height: 10in;
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                page-break-after: always;
                background: white;
                padding: 15px;
                page-break-after: avoid;
            }

            .border-box {
                width: 280px;
                padding: 2px;
                background: #eeee;
            }

            .num {
                padding-bottom: 11px;
                font-weight: bold;
                color: #0000;
            }

            img {
                display: block;
                width: 100%;
                height: 100%;
            }

            h5 {
                font-size: 15px;
                font-weight: 800;
            }

            span {
                color: red;
                font-weight: bold;
                font-size: 18px;
            }

            .sar {
                font-weight: bold;
                padding-left: 2px;
            }

            h6 {
                font-size: 13px;
                color: #0000;
                font-weight: bold;
            }

            .new-font{
                font-size: 16px;
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
                <img src="./header.png" alt="CIMS" style="padding-top: 0px; width: 100%; height: 100px;" />
                <div style="padding-left: 55px; margin-top: -20px;">
                    <div style="margin-top: -30px;">
                        <img src="<?php echo $qrCodeFile; ?>" alt="CIMS" style="width: 160px; height: 160px; padding-left: 240px;">
                    </div>
                </div>
                <div class="col-md-12" style="margin-top: -70px"> 
                    <div class="row" style="text-transform: uppercase; font-weight: 800; color: #000;"> 
                        <div class="border-box">
                            <!-- <h5 class="sar">Sticker No: <span><?php echo pathinfo($qrCodeFile, PATHINFO_FILENAME); ?></span></h5> -->
                            <h5 class="sar">Sticker No: <span><?php echo $stickerData['sticker_start_no']; ?></span></h5>
                        </div>
                        <div style="margin-top: 40px;">
                            <h5 class="num">Equipment No : <span class="new-font"><?php echo $stickerData['equipment_no']; ?></span> </h5>
                            <h5 class="num">Equipment Serial No : <span class="new-font"><?php echo $stickerData['equipment_serial_no']; ?></span> </h5>
                            <h5 class="num">
    Date Inspected : 
    <span class="new-font">
        <?php echo date('d/m/Y', strtotime($stickerData['inspection_date'])); ?>
    </span>
</h5>

<h5 class="num">
    Next Inspection Date : 
    <span class="new-font">
        <?php echo date('d/m/Y', strtotime($stickerData['next_inspection_due_date'])); ?>
    </span>
</h5>

                            <h5 class="num">Inspected By : <span class="new-font"><?php echo $stickerData['assign_inspector']; ?></span></h5>
                        </div>
                    </div> 
                    <div>
                        <h6 style="font-size: 13px; color: #0000; font-weight: bold;">PLEASE SEND REQUEST 7 DAYS BEFORE STICKER EXPIRY DATE</h6>	
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
