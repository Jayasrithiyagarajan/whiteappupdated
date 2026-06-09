<?php
include_once('../file/config.php');

// Retrieve either stickerNo or projectNo from the query parameters
$stickerNo = isset($_GET['stickerNo']) ? trim($_GET['stickerNo']) : '';
$projectNo = isset($_GET['projectNo']) ? trim($_GET['projectNo']) : '';

// Initialize variables to store the fetched data
$projectId = '';
$checklistNo = '';
$checklistType = '';
$reportNo = '';
$certificates = [];
$equipmentId = '';
$customerName = '';
$inspectorName = '';
$equipmentLocation = '';
$equipmentNo = '';
$inspectionDate = '';
$stickerStatus = '';

// New fields from reports table
$prevStickerNo = '';
$equipmentIdNo = '';
$equipmentSerialNo = '';
$dateOfInspection = '';
$inspection_status = '';
$nextInspectionDueDate = '';

if (!empty($stickerNo)) {
    // Case 1: User came with stickerNo
    // Step 1: Query the stickers table to get project_no
    $query = "SELECT project_no, sticker_status FROM stickers WHERE sticker_start_no = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $stickerNo);
    $stmt->execute();
    $stmt->bind_result($projectId, $stickerStatus);
    $stmt->fetch();
    $stmt->close();
}
elseif (!empty($projectNo)) {
    // Case 2: User came with projectNo
    $projectId = $projectNo;

    // Try to get the stickerNo associated with this project
    $query = "SELECT sticker_start_no, sticker_status FROM stickers WHERE project_no = ? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $projectId);
    $stmt->execute();
    $stmt->bind_result($stickerNo, $stickerStatus);
    $stmt->fetch();
    $stmt->close();
}

if (!empty($projectId)) {
    // Step 2: Query the project_info table to get additional data
    $query = "SELECT checklist_type, equipment_id, customer_name, inspector_name, equipment_location FROM project_info WHERE project_no = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $projectId);
    $stmt->execute();
    $stmt->bind_result($checklistType, $equipmentId, $customerName, $inspectorName, $equipmentLocation);
    $stmt->fetch();
    $stmt->close();

    // Step 3: Query the checklist_information table for additional data
    $query = "SELECT checklist_id, report_no, equipment_no, inspection_date FROM checklist_information WHERE project_no = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $projectId);
    $stmt->execute();
    $stmt->bind_result($checklistNo, $reportNo, $equipmentNo, $inspectionDate);
    $stmt->fetch();
    $stmt->close();

    // Step 4: Query the reports table for additional fields
    $query = "SELECT report_no, prev_sticker_no, equipment_id_no, equipment_serial_no, date_of_inspection, inspection_status, next_inspection_due_date FROM reports WHERE project_no = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $projectId);
    $stmt->execute();
    $stmt->bind_result($reportNo, $prevStickerNo, $equipmentIdNo, $equipmentSerialNo, $dateOfInspection, $inspection_status, $nextInspectionDueDate);
    $stmt->fetch();
    $stmt->close();

    // Step 5: Query the certificates table
    $query = "
        SELECT 
            'health-check' AS certificate_type, hc.certificate_no, hc.created_at
        FROM crane_health_check_certificate hc WHERE hc.project_no = ?
        
        UNION
        
        SELECT 'loadtest' AS certificate_type, lw.certificate_no, lw.created_at
        FROM loadtest_certificate lw WHERE lw.project_no = ?
        
        UNION
        
        SELECT 'mobile' AS certificate_type, mc.certificate_no, mc.created_at
        FROM mobile_crane_loadtest mc WHERE mc.project_no = ?

        UNION
        
        SELECT 'withloadtest' AS certificate_type, lt.certificate_no, lt.created_at
        FROM withload lt WHERE lt.project_no = ?

        UNION

        SELECT 'lifting' AS certificate_type, lc.certificate_no, lc.created_at
        FROM lifting_gear_certificates lc WHERE lc.project_no = ?

        UNION

        SELECT 'mpi' AS certificate_type, mp.certificate_no, mp.created_at
        FROM mpi_certificates mp WHERE mp.project_no = ?

        UNION

        SELECT 'eddycurrent' AS certificate_type, ec.certificate_no, ec.created_at
        FROM eddy_current_inspection ec WHERE ec.project_no = ?

        UNION

        SELECT 'liquid-penetrant-inspection-certificate' AS certificate_type, lpi.certificate_no, lpi.created_at
        FROM liquid_penetrant_inspection lpi WHERE lpi.project_no = ?

        UNION

        SELECT 'rocktest' AS certificate_type, rt.certificate_no, rt.created_at
        FROM rocking_test_certificate rt WHERE rt.project_no = ?

        UNION

        SELECT 'lmi' AS certificate_type, lmi.certificate_no, lmi.created_at
        FROM lmi_certificates lmi WHERE lmi.project_no = ?
    ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssssss", $projectId, $projectId, $projectId, $projectId, $projectId, $projectId, $projectId, $projectId, $projectId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $certificates[] = $row;
        }
    }
}

// Retrieve the project number from the query parameter
$documents = [];

if (!empty($projectId)) {
    // Query the documents table for available files
    $query = "SELECT file_1, file_2, file_3, file_4, file_5, file_6, file_7, file_8, file_9, file_10 FROM documents WHERE project_no = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $projectId);
    $stmt->execute();
    $stmt->bind_result($file1, $file2, $file3, $file4, $file5, $file6, $file7, $file8, $file9, $file10);
    $stmt->fetch();
    $stmt->close();

    // Store available files in an array
    $files = [$file1, $file2, $file3, $file4, $file5, $file6, $file7, $file8, $file9, $file10];
    foreach ($files as $file) {
        if (!empty($file)) {
            $documents[] = $file;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inspection Card</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f1f1f1;
            font-family: 'Arial', sans-serif;
        }

        .card {
            width: 520px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: white;
            text-align: center;
            border: 2px solid green;
        }

        .card-header {
            background-color: green;
            color: white;
            padding: 15px;
            font-size: 18px;
            font-weight: bold;
            display: flex;
            justify-content: center;
            align-items: center;
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
        }

        .card-body {
            padding: 15px;
        }

        .row {
            display: flex;
            justify-content: space-between;
            padding: 10px 20px;
            border-bottom: 1px solid #ddd;
        }

        .label {
            color: gray;
            font-size: 14px;
            width: 40%;
            text-align: left;
        }

        .value {
            font-weight: bold;
            font-size: 16px;
            width: 60%;
            text-align: right;
        }

        .download-link {
            display: block;
            color: blue;
            text-decoration: underline;
            cursor: pointer;
            text-align: right;
            font-size: 14px;
        }
        
        .text-success{
            color: green;
        }

        .checkmark {
            width: 30px;
            height: 30px;
            margin-right: 10px;
        }

        .checkmark-circle {
            stroke: #fff;
            stroke-width: 2;
            stroke-dasharray: 157;
            stroke-dashoffset: 157;
            animation: drawCircle 0.6s ease-out forwards;
        }

        .checkmark-check {
            stroke-dasharray: 40;
            stroke-dashoffset: 40;
            animation: drawCheck 0.3s ease-out 0.6s forwards;
        }

        @keyframes drawCircle {
            to {
                stroke-dashoffset: 0;
            }
        }

        @keyframes drawCheck {
            to {
                stroke-dashoffset: 0;
            }
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header" style="margin-top:40px">
            <svg class="checkmark" viewBox="0 0 52 52">
                <circle class="checkmark-circle" cx="26" cy="26" r="25" fill="none"/>
                <path class="checkmark-check" fill="none" stroke="white" stroke-width="5" d="M14 27l7 7 15-15"/>
            </svg>
            Verified
        </div>

        <div class="card-body">
            <div class="row"><div class="label">Project ID:</div> <div class="value"><?php echo htmlspecialchars($projectId); ?></div></div>
            
            <?php if (!empty($equipmentId)): ?>
                <div class="row"><div class="label">Equipment ID:</div> <div class="value"><?php echo htmlspecialchars($equipmentId); ?></div></div>
            <?php
endif; ?>
            
            <?php if (!empty($equipmentSerialNo)): ?>
                <div class="row"><div class="label">Equipment Serial No:</div> <div class="value"><?php echo htmlspecialchars($equipmentSerialNo); ?></div></div>
            <?php
endif; ?>
            
            <?php if (!empty($stickerNo)): ?>
                <div class="row"><div class="label">Sticker No:</div> <div class="value"><?php echo htmlspecialchars($stickerNo); ?></div></div>
            <?php
endif; ?>
            
            <?php if (!empty($inspection_status)): ?>
                <div class="row <?php echo($inspection_status === 'Passed') ? 'text-success' : 'text-danger'; ?>">
                    <div class="label">Inspection Status:</div> 
                    <div class="value"><?php echo htmlspecialchars($inspection_status); ?></div>
                </div>
            <?php
endif; ?>
            
            <?php if (!empty($prevStickerNo)): ?>
                <div class="row"><div class="label">Previous Sticker No:</div> <div class="value"><?php echo htmlspecialchars($prevStickerNo); ?></div></div>
            <?php
endif; ?>
            
            <?php if (!empty($checklistType)): ?>
                <div class="row"><div class="label">Checklist Type:</div> <div class="value"><?php echo htmlspecialchars($checklistType); ?></div></div>
            <?php
endif; ?>
            
            <?php if (!empty($customerName)): ?>
                <div class="row"><div class="label">Customer Name:</div> <div class="value"><?php echo htmlspecialchars($customerName); ?></div></div>
            <?php
endif; ?>
            
            <?php if (!empty($inspectorName)): ?>
                <div class="row"><div class="label">Inspector Name:</div> <div class="value"><?php echo htmlspecialchars($inspectorName); ?></div></div>
            <?php
endif; ?>
            
            <?php if (!empty($equipmentLocation)): ?>
                <div class="row"><div class="label">Equipment Location:</div> <div class="value"><?php echo htmlspecialchars($equipmentLocation); ?></div></div>
            <?php
endif; ?>
            
            <?php if (!empty($inspectionDate)): ?>
                <div class="row"><div class="label">Inspection Date:</div> <div class="value"><?php echo htmlspecialchars($inspectionDate); ?></div></div>
            <?php
endif; ?>
            
            <?php if (!empty($nextInspectionDueDate)): ?>
                <div class="row"><div class="label">Next Inspection Date:</div> <div class="value"><?php echo htmlspecialchars($nextInspectionDueDate); ?></div></div>
            <?php
endif; ?>
            

            <?php if (!empty($checklistNo)): ?>
                <div class="row">
                    <div class="label">Checklist:</div>
                    <div class="value">
                    <a href="../document/checklist/preview.php?project_no=<?php echo htmlspecialchars($projectId); ?>" class="download-link">Download Checklist</a>
                    </div>
                </div>
            <?php
endif; ?>

            <?php if (!empty($reportNo)): ?>
                <div class="row">
                    <div class="label">Report:</div>
                    <div class="value">
                        <a href="../document/report/download.php?project_no=<?php echo htmlspecialchars($projectId); ?>&report_no=<?php echo htmlspecialchars($reportNo); ?>" class="download-link">Download Report</a>
                    </div>
                </div>
            <?php
endif; ?>
            
            
            <?php if (!empty($stickerNo)): ?>
                <div class="row">
                    <div class="label">Sticker:</div>
                    <div class="value">
                        <?php if ($stickerStatus === 'Passed'): ?>
                            <a href="../sticker/download-white.php?sticker_start_no=<?php echo htmlspecialchars($stickerNo); ?>" target="_blank" class="download-link">Download Sticker</a>
                        <?php
    else: ?>
                            <a href="../sticker/download.php?sticker_start_no=<?php echo htmlspecialchars($stickerNo); ?>" target="_blank" class="download-link">Download Sticker</a>
                        <?php
    endif; ?>
                    </div>
                </div>
            <?php
endif; ?>

            <?php if (!empty($certificates)): ?>
                <div class="row"><div class="label">Certificates:</div>
                    <div class="value">
                        <?php foreach ($certificates as $certificate): ?>
                            <a href="../document/<?php echo htmlspecialchars($certificate['certificate_type']); ?>/download.php?project_no=<?php echo htmlspecialchars($projectId); ?>" class="download-link">Download Certificate</a><br>
                            <!--<a href="../document/<?php echo htmlspecialchars($certificate['certificate_type']); ?>/download.php?project_no=<?php echo htmlspecialchars($projectId); ?>" class="download-link">Download <?php echo ucfirst(htmlspecialchars($certificate['certificate_type'])); ?> Certificate</a><br>-->
                        <?php
    endforeach; ?>
                    </div>
                </div>
            <?php
endif; ?>

            <?php if (!empty($documents)): ?>
                <div class="row">
                    <div class="label">Additional Documents:</div>
                    <div class="value">
                        <?php foreach ($documents as $index => $document): ?>
                            <a href="../uploads/<?php echo htmlspecialchars($projectId); ?>/<?php echo urlencode($document); ?>" class="download-link">
                                Download Document <?php echo $index + 1; ?>
                            </a>
                            <br>
                        <?php
    endforeach; ?>
                    </div>
                </div>
            <?php
endif; ?>
        </div>
    </div>
</body>
</html>