<?php
include_once('../file/config.php');

// Ensure id is provided
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Query to fetch the specific operator assessment and client/inspector info
    $query = "SELECT oa.*, c.customer_name as client_name, c.mobile as client_phone, nu.username as inspector_name 
              FROM operator_assessments oa 
              LEFT JOIN customers c ON oa.client_id = c.cus_id 
              LEFT JOIN new_users nu ON oa.inspector_id = nu.user_id
              WHERE oa.id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "No matching assessment found!";
        exit;
    }

    $stmt->close();
} else {
    echo "Assessment ID is required!";
    exit;
}

// Fetch inspector's LEEA number
$inspector_name = $row['inspector_name'];
$leea_number = ""; 

$query_leea = "SELECT leea_number FROM inspectors WHERE inspector_name = ?";
$stmt_leea = $conn->prepare($query_leea);
if ($stmt_leea) {
    $stmt_leea->bind_param("s", $inspector_name);
    $stmt_leea->execute();
    $result_leea = $stmt_leea->get_result();
    if ($result_leea && $result_leea->num_rows > 0) {
        $leea_row = $result_leea->fetch_assoc();
        $leea_number = $leea_row['leea_number'];
    } else {
        $leea_number = "N/A";
    }
    $stmt_leea->close();
} else {
    $leea_number = "Error";
}

// Fetch Photo
$photo_stmt = $conn->prepare("SELECT file_path FROM operator_documents WHERE assessment_id=? AND document_type='PHOTO' LIMIT 1");
$photo_stmt->bind_param("i", $id);
$photo_stmt->execute();
$photo = $photo_stmt->get_result()->fetch_assoc();
$photo_path = '../assets/img/avatar/avatar-1.png';
if ($photo && !empty($photo['file_path'])) {
    $photo_path = $photo['file_path'];
}

// Fetch Equipment
$eq_stmt = $conn->prepare("SELECT * FROM operator_equipment WHERE assessment_id=? ORDER BY equipment_number ASC");
$eq_stmt->bind_param("i", $id);
$eq_stmt->execute();
$equipments = $eq_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Calculate designation
$designation = 'Crane Operator';
foreach ($equipments as $eq) {
    $t = $eq['equipment_type'] ?? '';
    if ($designation === 'Crane Operator') {
        if (stripos($t,'mobile') !== false) $designation = 'Mobile Crane Operator';
        if (stripos($t,'forklift') !== false) $designation = 'Forklift Operator';
    }
}

// Validation & Formatting
$issue_date = date('d-m-Y', strtotime($row['date_of_assessment'] ?? $row['date']));
$expiry_date = !empty($row['date_of_expiry']) ? date('d-m-Y', strtotime($row['date_of_expiry'])) : 'N/A';
$renewal_due = ($expiry_date !== 'N/A') ? 'Before ' . $expiry_date : 'N/A';
$validity = '2 Years';
if (!empty($row['date_of_assessment']) && !empty($row['date_of_expiry'])) {
    $days = abs(strtotime($row['date_of_expiry']) - strtotime($row['date_of_assessment'])) / 86400;
    $yrs  = round($days / 365);
    if ($yrs >= 1) $validity = $yrs.' '.($yrs>1?'Years':'Year');
    else {
        $mos = round($days / 30);
        $validity = $mos.' '.($mos>1?'Months':'Month');
    }
}

$training_program = ucwords(strtolower($row['training_program'] ?? 'Training & Competency Assessment'));
$assessment_standard = 'Company Assessment Criteria & Applicable Safety Standards';

$url2 = "http://" . $_SERVER['HTTP_HOST'] . "/whiteappupdated/"; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operator Assessment Report</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        .container { width: 90%; max-width: 1200px; margin: 20px auto; padding: 20px; background: #fff; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2); border-radius: 8px; }
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 0; padding: 0; background: #f7fbff; }
        .inspection-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .inspection-table th, .inspection-table td { border: 1px solid black; padding: 4px; text-align: center; font-size: 11px; }
        .inspection-table th { background-color: #277bbee0; color: white; }
        .manufac { background-color: #277bbee0 !important; color: #fff !important; font-weight: bold; }
        .signature-cell { justify-content: flex-start; height: 50px; }
        .signature-cell img { margin-right: 10px; }
        .checkbox-container { display: flex; align-items: center; gap: 10px; }
        .checkbox-container input[type="checkbox"] { margin: 0; padding: 0; }
        @media print {
            body { margin: 0; padding: 0; background: white; transform: scale(0.95); transform-origin: top left; color-adjust: exact; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .container { width: 100%; max-width: 100%; box-shadow: none; border: none; margin: 0; padding: 0; }
            .manufac { background-color: #277bbee0 !important; color: #fff !important; font-weight: bold; }
            #non-printable { display: none !important; }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 20%; text-align: left;">
                    <img src="../document/checklist/logo.png" height="80px" alt="TUV Rheinland Logo" onerror="this.src='../assets/img/logo.png'">
                </td>
                <td style="width: 60%; text-align: center;">
                    <h1 style="font-size: 18px; margin: 0;">CRANE INSPECTION & MAINTENANCE SERVICES (CIMS)</h1>
                    <h3 style="font-size: 15px; margin-top: 3px;">A DIVISION OF AL KHOBAR GATE INTERNATIONAL TRADING EST</h3>
                    <p style="font-size: 12px; margin: 5px 0;">
                        <b>P.O.BOX 74007, AL- Khobar 31952, Saudi Arabia</b><br>
                        <b>TEL.: 013 814 6861 - 013 814 6862 Ext.110 - Fax: 013 814 6863</b>
                    </p>
                    <h3 style="font-size: 18px; margin-top: 15px;">Operator Assessment Report</h3>
                </td>
                <td style="width: 20%; text-align: right;">
                    <p style="font-size: 12px; margin: 0; text-align: right;">
                        <b>Assmnt No:</b> <?php echo htmlspecialchars($row['assessment_no']); ?><br>
                        <b>Op ID: <?php echo htmlspecialchars($row['operator_id_passport']); ?></b> 
                    </p>
                    <img src="../document/code.png" height="80px" alt="QR Code" onerror="this.style.display='none'">
                </td>
            </tr>
        </table>
    </div>

    <table class="inspection-table">
        <tbody>
            <tr>
                <td rowspan="4" style="width: 15%; padding: 5px;">
                    <img src="<?php echo htmlspecialchars($photo_path); ?>" style="max-width: 100%; max-height: 120px; display: block; margin: 0 auto;" alt="Operator Photo">
                </td>
                <td class="manufac" style="width: 21%;">Client Company:</td>
                <td class="manufac" style="width: 21%;">Operator Name:</td>
                <td class="manufac" style="width: 21%;">ID / Passport Number:</td>
                <td class="manufac" style="width: 21%;">License Number:</td>
            </tr>
            <tr>
                <td><b><?php echo htmlspecialchars($row['client_name'] ?? 'N/A'); ?></b></td>
                <td><b><?php echo htmlspecialchars($row['operator_name']); ?></b></td>
                <td><b><?php echo htmlspecialchars($row['operator_id_passport']); ?></b></td>
                <td><b><?php echo htmlspecialchars($row['license_number'] ?? 'N/A'); ?></b></td>
            </tr>
            <tr>
                <td class="manufac">Designation:</td>
                <td class="manufac">Location:</td>
                <td class="manufac">Issue Date:</td>
                <td class="manufac">Expiry Date:</td>
            </tr>
            <tr>
                <td><b><?php echo htmlspecialchars($designation); ?></b></td>
                <td><b><?php echo htmlspecialchars($row['location']); ?></b></td>
                <td><b><?php echo htmlspecialchars($issue_date); ?></b></td>
                <td><b><?php echo htmlspecialchars($expiry_date); ?></b></td>
            </tr>

            <tr>
                <td class="manufac">Training Program:</td>
                <td colspan="2"><b><?php echo htmlspecialchars($training_program); ?></b></td>
                <td class="manufac">Assessment Standard:</td>
                <td><b><?php echo htmlspecialchars($assessment_standard); ?></b></td>
            </tr>

            <tr>
                <td class="manufac">Validity:</td>
                <td><b><?php echo htmlspecialchars($validity); ?></b></td>
                <td class="manufac">Renewal Due:</td>
                <td colspan="2"><b><?php echo htmlspecialchars($renewal_due); ?></b></td>
            </tr>

            <tr>
                <td class="manufac">Exam Score:</td>
                <td><b><?php echo htmlspecialchars($row['exam_score'] ?? 'N/A'); ?> / 100</b></td>
                <td class="manufac">Signals Score:</td>
                <td><b><?php echo htmlspecialchars($row['signals_score'] ?? 'N/A'); ?> / 100</b></td>
                <td>
                    <div style="display: flex; flex-direction: column; margin-left: 20px;">
                        <div>
                            <label for="pass"><b>Passed</b></label>
                            <input type="checkbox" id="pass" name="ins_result_pass" value="pass" 
                                <?php echo (strtoupper($row['status']) == 'COMPLETED') ? 'checked' : ''; ?> disabled>
                        </div>
                        <div>
                            <label for="fail"><b>Failed / Pending</b></label>
                            <input type="checkbox" id="fail" name="ins_result_fail" value="fail" 
                                <?php echo (strtoupper($row['status']) != 'COMPLETED') ? 'checked' : ''; ?> disabled>
                        </div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>

    <p style="margin-top: 15px;"><b>Equipment Authorized to Operate:</b></p>
    <table class="inspection-table" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th style="padding: 8px">Type</th>
                <th style="padding: 8px">Manufacturer</th>
                <th style="padding: 8px">Model</th>
                <th style="padding: 8px">Capacity</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($equipments)): ?>
                <tr><td colspan="4">No equipment assigned.</td></tr>
            <?php else: ?>
                <?php foreach($equipments as $eq): ?>
                <tr>
                    <td><?php echo htmlspecialchars($eq['equipment_type'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($eq['manufacturer'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($eq['model'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($eq['capacity'] ?? ''); ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <p style="margin-top: 15px;">
        <b>Above Operator was evaluated in accordance with local and international standards. General remarks and overall status are listed below.</b>
    </p>

    <table class="inspection-table" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th style="width: 50%; padding: 10px">EXAM STATUS</th>
                <th style="width: 50%; padding: 10px">HAND SIGNALS STATUS</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="padding: 10px; height: 60px;">
                    <strong><?php echo htmlspecialchars($row['exam_status']); ?></strong>
                </td>
                <td style="padding: 10px; height: 60px;">
                    <strong><?php echo htmlspecialchars($row['signals_status']); ?></strong>
                </td>
            </tr>
        </tbody>
    </table>
    
    <p>A complete copy of this report should be ready for review by the inspector when required.</p>
    
    <div class="col-md-12" style="border:2px solid #d0cece; padding: 10px; margin-bottom: 20px;">
        <div class="form-group1">
            <div class="form-check2">
                <h6 class="checkbox-container" style="margin: 0;">
                    <input type="checkbox" checked disabled>
                    <span style="font-size: 14px;">اواوافق الى تحمل المسؤليه الكامله عن هذا الفحص
                        <span style="font-size: 14px;"> I agree to take full responsibility for this inspection</span>
                    </span>
                </h6>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <table class="inspection-table" style="width: 100%;">
                <thead>
                    <tr>
                        <th colspan="2">Report Receiver's Name and sign:</th>
                        <th>Phone Number:</th>
                        <th colspan="2">Inspector Name and Sign:</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td rowspan="2" class="signature-cell">
                            <strong><?php echo htmlspecialchars(ucwords($row['client_name'] ?? 'N/A')); ?></strong>
                        </td>
                        <td rowspan="2" class="signature-cell">
                            <!-- Placeholder for client signature -->
                        </td>                          
                        <td rowspan="2">
                            <strong><?php echo htmlspecialchars($row['client_phone'] ?? 'N/A'); ?></strong>
                        </td>
                        <td class="signature-cell">
                            <strong><?php echo htmlspecialchars($row['inspector_name']); ?></strong>
                        </td>
                        <td rowspan="2">
                            <?php if(!empty($row['inspector_name'])): ?>
                                <img src="<?php echo $url2 . 'inspector/uploads/' . strtolower(str_replace(' ', '_', htmlspecialchars($row['inspector_name']))) . '/images/signature_image.jpg'; ?>" height="60px" onerror="this.style.display='none'">
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>LEEA NO: <?php echo htmlspecialchars($leea_number); ?></strong>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="text-center" id="non-printable" style="margin-top: 30px; display: flex; justify-content: center; gap: 10px; padding-bottom: 40px;">
        <a href="assessment-list.php">
            <button type="button" style="padding: 10px 20px; background-color: #64748b; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: bold; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <i class="fas fa-arrow-left"></i> Back to Assessment List
            </button>
        </a>
        <button type="button" onclick="window.print()" style="padding: 10px 20px; background-color: #3b82f6; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: bold; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <i class="fas fa-print"></i> Print Report
        </button>
    </div>

</div>
</body>
</html>
