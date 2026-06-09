<?php
//session_start();
include_once('../file/config.php');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$url = 'http://localhost/whiteappupdated/'; // Fallback URL

// Fetch Main Details
$sql = "SELECT 
            oa.operator_name,
            oa.assessment_no as certificate_no,
            oa.operator_id_passport as id_iqama,
            c.customer_name as company,
            oa.date_of_assessment as issue_date,
            oa.date_of_expiry as expiry_date,
            nu.username as examiner_name,
            oa.operating_location,
            od.file_path as photo_path
        FROM operator_assessments oa
        LEFT JOIN customers c ON oa.client_id = c.cus_id
        LEFT JOIN new_users nu ON oa.inspector_id = nu.user_id
        LEFT JOIN operator_documents od ON oa.id = od.assessment_id AND od.document_type = 'PHOTO'
        WHERE oa.id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$card = $result->fetch_assoc();

if (!$card) {
    die("Card not found. Ensure assessment is completed and photo is uploaded.");
}

// Adjust photo path if needed
$photo_path = $card['photo_path'];
if (strpos($photo_path, '../') === 0) {
    // If path starts with ../, remove it to work with relative path logic
    $photo_path = str_replace('../', '', $photo_path);
}
$card['photo_path'] = $photo_path;


// Fetch Equipment
$eq_sql = "SELECT * FROM operator_equipment WHERE assessment_id = ?";
$eq_stmt = $conn->prepare($eq_sql);
$eq_stmt->bind_param("i", $id);
$eq_stmt->execute();
$eq_result = $eq_stmt->get_result();

$equipment_list = [];
$designation = "Crane Operator"; // Default
if ($eq_row = $eq_result->fetch_assoc()) {
    // Use first equipment to set designation if possible, or just Mobile Crane Operator
    if (stripos($eq_row['equipment_type'], 'mobile') !== false) {
        $designation = "Mobile Crane Operator";
    } elseif (stripos($eq_row['equipment_type'], 'forklift') !== false) {
        $designation = "Forklift Operator";
    }
    
    // Add first one back
    $str = $eq_row['equipment_type'] . " - " . $eq_row['manufacturer'] . " " . $eq_row['model'];
    if ($eq_row['capacity']) $str .= " (" . $eq_row['capacity'] . ")";
    $equipment_list[] = $str;
}

while ($eq_row = $eq_result->fetch_assoc()) {
     $str = $eq_row['equipment_type'] . " - " . $eq_row['manufacturer'] . " " . $eq_row['model'];
    if ($eq_row['capacity']) $str .= " (" . $eq_row['capacity'] . ")";
    $equipment_list[] = $str;
}

$card['operator_designation'] = $designation;
$equipment = $equipment_list;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Operator ID Card - <?= htmlspecialchars($card['operator_name']); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 20px;
            font-family: "Segoe UI", Arial, sans-serif;
            background: #f4f7f6;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 40px;
        }

        /* CARD */
        .id-card {
            width: 860px;
            height: 520px;
            background: #fff;
            margin: 0;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,.25);
            position: relative;
        }

        /* WATERMARK */
        .watermark {
            position: absolute;
            inset: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0.045;
            z-index: 0;
            pointer-events: none;
        }

        .watermark img {
            width: 420px;
            filter: grayscale(100%) blur(0.3px);
        }

        /* HEADER */
        .id-header {
            background: #062b63;
            color: #fff;
            padding: 14px 25px;
            display: flex;
            align-items: center;
            gap: 15px;
            position: relative;
            z-index: 2;
        }

        /* LOGO BOX */
        .logo-box {
            background: #ffffff;
            padding: 6px 12px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 3px 8px rgba(0,0,0,0.35);
        }

        .header-logo {
            height: 65px;
        }

        /* HEADER TITLE */
        .header-title {
            font-size: 17px;
            font-weight: 700;
            letter-spacing: 0.6px;
            text-align: center;
            flex: 1;
        }

        /* BODY */
        .id-body {
            padding: 28px;
            position: relative;
            z-index: 2;
        }

        /* PHOTO */
        .photo img {
            width: 180px;
            height: 230px;
            object-fit: cover;
            border-radius: 6px;
            border: 2px solid #062b63;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }

        /* SECOND IMAGE (BELOW PHOTO) */
        .sub-photo img {
            width: 180px;
            height: 140px;
            object-fit: contain;
            padding: 6px;
            background: #fff;
        }

        /* NAME */
        .operator-name {
            font-size: 24px;
            font-weight: 800;
            margin-bottom: 4px;
        }

        /* ROLE */
        .role {
            font-size: 17px;
            font-weight: 700;
            color: #062b63;
            margin-bottom: 18px;
        }

        /* INFO */
        .info {
            font-size: 18px;
        }

        .info span {
            font-weight: 600;
        }

        /* FOOTER */
        .id-footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            background: #062b63;
            color: #fff;
            padding: 12px 25px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: 600;
            z-index: 2;
            font-size: 15px;
            text-align: center;
        }

        .highlight {
            color: #062b63;
            font-weight: 700;
        }

        /* BACK CARD OVERRIDES */
        .back-card .id-header {
            justify-content: space-between;
        }

        .header-right {
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .equipment-box {
            border: 2px solid #062b63;
            border-radius: 12px;
            background: #ffffff;
            margin-bottom: 15px;
            overflow: hidden;
        }

        .equipment-title {
            background: #062b63;
            color: #ffffff;
            font-size: 13.5px;
            font-weight: 700;
            padding: 8px 15px;
            text-align: center;
            letter-spacing: 0.6px;
            text-transform: uppercase;
            border-radius: 10px 10px 0 0;
        }

        .equipment-list {
            padding: 10px 15px;
        }

        .equipment-item {
            font-size: 13px;
            line-height: 1.4;
            padding: 6px 12px;
            color: #000;
            border: 1px solid #d6dce6;
            border-radius: 8px;
            margin-bottom: 6px;
        }

        .equipment-item:last-child {
            margin-bottom: 0;
        }

        .center-text {
            font-size: 12.5px;
            line-height: 1.4;
            text-align: center;
            color: #222;
            padding: 0 40px;
            margin-bottom: 25px;
            position: relative;
            z-index: 2;
        }

        .id-footer-back {
            position: absolute;
            bottom: 0;
            width: 100%;
            background: #062b63;
            color: #ffffff;
            padding: 14px 22px;
            z-index: 2;
        }

        .footer-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 100%;
        }

        .footer-img {
            width: 110px;
            height: 110px;
            object-fit: contain;
            background: #ffffff;
            padding: 6px;
            border-radius: 6px;
        }

        .footer-text {
            flex: 1;
            padding: 0 40px;
            font-size: 13px;
            line-height: 1.5;
            text-align: center;
        }

        .footer-text strong {
            font-size: 13.5px;
            letter-spacing: 0.3px;
        }

        .actions {
            margin-top: 30px;
            display: flex;
            gap: 15px;
        }

        .btn-action {
            padding: 10px 20px;
            background: #4f46e5;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            transition: opacity 0.3s;
            border: none;
        }

        .btn-action:hover { opacity: 0.9; color: white; }

        @media print {
            body { background: white; padding: 0; gap: 0; }
            .actions { display: none; }
            .id-card {
                box-shadow: none;
                border: 1px solid #eee;
                page-break-after: always;
                margin: 0 auto 40px;
            }
        }
    </style>
</head>
<body>

    <!-- FRONT SIDE -->
    <div class="id-card">
        <!-- WATERMARK -->
        <div class="watermark">
            <img src="../assets/img/logo.png" alt="Watermark Logo">
        </div>

        <!-- HEADER -->
        <div class="id-header">
            <div class="logo-box">
                <img src="../assets/img/logo.png" alt="Logo" class="header-logo">
            </div>
            <div class="header-title">
                CRANE INSPECTION &amp; MAINTENANCE SERVICES (CIMS)<br>
                A DIVISION OF AL KHOBAR GATE INTERNATIONAL TRADING EST
            </div>
        </div>

        <!-- BODY -->
        <div class="id-body">
            <div class="row align-items-start">
                <!-- PHOTO COLUMN -->
                <div class="col-4 text-center">
                    <div class="photo">
                        <!-- Adjusted to handle relative path cleanly -->
                        <img src="../<?= htmlspecialchars($card['photo_path']); ?>" alt="Operator Photo">
                    </div>
                    <div class="sub-photo">
                        <img src="../document/new.jpeg" alt="LEEA Logo">
                    </div>
                </div>

                <!-- DETAILS -->
                <div class="col-8">
                    <div class="operator-name">
                        <?= htmlspecialchars($card['operator_name']); ?>
                    </div>
                    <div class="role">
                        Certificate No: <?= htmlspecialchars($card['certificate_no']); ?>
                    </div>

                    <div class="row info mb-3">
                        <div class="col-6">
                            <span>ID / Iqama:</span><br>
                            <span class="highlight"><?= htmlspecialchars($card['id_iqama']); ?></span>
                        </div>
                        <div class="col-6">
                            <span>Company:</span><br>
                            <span class="highlight"><?= htmlspecialchars($card['company']); ?></span>
                        </div>
                    </div>

                    <div class="row info mb-3">
                        <div class="col-6">
                            <span>Issue Date:</span><br>
                            <span class="highlight"><?= date('d M Y', strtotime($card['issue_date'])); ?></span>
                        </div>
                        <div class="col-6">
                            <span>Expiry Date:</span><br>
                            <span class="highlight"><?= date('d M Y', strtotime($card['expiry_date'])); ?></span>
                        </div>
                    </div>

                    <div class="row info mb-3">
                        <div class="col-6">
                            <span>Examiner:</span><br>
                            <span class="highlight" style="text-transform: uppercase;"><?= htmlspecialchars($card['examiner_name']); ?></span>
                        </div>
                        <div class="col-6">
                            <span>Operating location:</span><br>
                            <span class="highlight"><?= htmlspecialchars($card['operating_location']); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FOOTER -->
        <div class="id-footer">
            <div class="text-center"><?= htmlspecialchars($card['operator_designation']); ?></div>
        </div>
    </div>

    <!-- BACK SIDE -->
    <div class="id-card back-card">
        <!-- WATERMARK -->
        <div class="watermark">
            <img src="../assets/img/logo.png" alt="CIMS Watermark">
        </div>

        <!-- HEADER -->
        <div class="id-header">
            <div>Cert. No. <?= htmlspecialchars($card['certificate_no']); ?></div>
            <div class="header-right">
                <?= htmlspecialchars($card['operator_designation']); ?>
            </div>
        </div>

        <!-- BODY -->
        <div class="id-body">
            <div class="equipment-box">
                <div class="equipment-title">Equipment Details</div>
                <div class="equipment-list">
                    <?php if (!empty($equipment)): ?>
                        <?php foreach ($equipment as $item): ?>
                            <div class="equipment-item"><?= htmlspecialchars($item); ?></div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="equipment-item">No equipment details provided.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- NOTE -->
        <div class="center-text" style="margin-top: -25px;">
            This certification card issued hereto remains a property of
            <strong>Crane Inspection &amp; Maintenance Services (CIMS)</strong>.
            If either lost or expired, please notify or return it respectively
            to the address written here below.
        </div>

        <!-- FOOTER -->
        <div class="id-footer-back">
            <div class="footer-content">
                <img src="../document/leea.png" alt="LEEA Logo" class="footer-img">
                <div class="footer-text">
                    <strong>CRANE INSPECTION AND MAINTENANCE SERVICES</strong><br>
                    A Division of AL-KHOBAR GATE INTERNATIONAL TRADING EST.<br>
                    P.O. Box 74007, Bldg. #7036, Al Andalus Street, Rakah,<br>
                    Al Khobar – 31952, KSA.<br>
                    Tel: (013) 814 6861 / 6862, 847 8822 |
                    Fax: (013) 814 6863
                </div>
                <!-- Adjust QR to link to this card -->
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?= $url . 'operator_card/view-card.php?id=' . $id; ?>" alt="QR Code" class="footer-img">
            </div>
        </div>
    </div>

    <div class="actions">
        <button onclick="window.print()" class="btn-action">Print Card</button>
        <!-- Removed Add Another / View All since this is integrated into assessment -->
        <a href="../operator_assessment/view-assessment.php?id=<?= $id; ?>" class="btn-action" style="background: #333;">Back to Assessment</a>
    </div>

</body>
</html>
