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
while ($eq_row = $eq_result->fetch_assoc()) {
    // Only set designation once based on first equipment if it contains keywords
    if ($designation === "Crane Operator") {
        if (stripos($eq_row['equipment_type'], 'mobile') !== false) {
            $designation = "Mobile Crane Operator";
        } elseif (stripos($eq_row['equipment_type'], 'forklift') !== false) {
            $designation = "Forklift Operator";
        }
    }
    $equipment_list[] = $eq_row;
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 20px;
            font-family: "Segoe UI", Arial, sans-serif;
            background: #f4f7f6;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 80px;
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
            opacity: 0.12;
            z-index: 0;
            pointer-events: none;
        }

        .watermark img {
            width: 180px;
            filter: grayscale(100%) blur(0.3px);
        }

        /* HEADER */
        .id-header {
            background: linear-gradient(90deg, #062b63 0%, #0a3d8a 100%);
            color: #fff;
            padding: 16px 25px;
            display: flex;
            align-items: center;
            gap: 15px;
            position: relative;
            z-index: 2;
            border-bottom: 2px solid #ffcc00; /* Gold accent line */
        }

        /* LOGO BOX */
        .logo-box {
            position: absolute;
            right: 30px;
            top: 25px; /* Adjusted top for removal of box padding */
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
            /* background, padding, and shadow removed as requested */
        }

        .header-logo {
            height: 90px;
        }

        .name-logo {
            height: 120px;
            margin-left: 15px;
            object-fit: contain;
        }

        /* HEADER TITLE */
        .header-title {
            flex: 1;
            text-align: center;
        }

        .company-name {
            font-size: 21px;
            font-weight: 800 !important;
            display: block;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
            letter-spacing: 0.8px;
            line-height: 1.2;
        }

        .division-name {
            font-size: 13px;
            font-weight: 600;
            display: block;
            opacity: 0.9;
            margin-top: 2px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
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
            width: 200px;
            height: 150px;
            object-fit: contain;
            padding: 4px;
            background: #fff;
        }

        /* NAME */
        .operator-name {
            font-size: 24px;
            font-weight: 800;
            margin-bottom: 2px;
        }

        /* ROLE */
        .role {
            font-size: 20px;
            font-weight: 700;
            color: #062b63;
            margin-bottom: 18px;
        }

        /* INFO */
        .info {
            font-size: 20px;
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
            font-size: 20px;
            text-align: center;
        }

        .highlight {
            color: #062b63;
            font-weight: bold !important;
        }

        /* BACK CARD OVERRIDES */
        .back-card .id-header {
            justify-content: space-between;
            font-size: 20px;
            font-weight: 800 !important;
        }

        .header-right {
            font-size: 20px;
            font-weight: 800 !important;
            text-transform: uppercase;
        }

        .equipment-box {
            border: 2px solid #062b63;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.92);
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
            padding: 8px;
        }

        .equipment-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11.5px;
        }

        .equipment-table th {
            background: rgba(240, 244, 248, 0.85);
            color: #062b63;
            text-align: left;
            padding: 6px 10px;
            border: 1px solid #d6dce6;
            text-transform: uppercase;
            font-weight: 700;
            font-size: 10px;
        }

        .equipment-table td {
            padding: 5px 10px;
            border: 1px solid #d6dce6;
            color: #333;
            line-height: 1.2;
        }

        .equipment-table tr:nth-child(even) {
            background: rgba(250, 250, 250, 0.6);
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
            padding: 10px 25px;
            z-index: 2;
            height: 125px; /* Fixed height for consistency */
            display: flex;
            align-items: center;
        }

        .footer-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }

        .footer-img {
            width: 95px;
            height: 95px;
            object-fit: contain;
            background: #ffffff;
            padding: 6px;
            border-radius: 6px;
            flex-shrink: 0;
        }

        .footer-text {
            flex: 1;
            padding: 0 25px;
            font-size: 13px;
            line-height: 1.4;
            text-align: center;
        }

        .footer-text i {
            /* color: #ffcc00; */
            color: #ffffff; /* White color */
            margin-right: 4px;
            font-size: 12.5px;
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
            /* Custom page size = card's exact pixel dimensions in mm (1px ≈ 0.2646mm at 96dpi) */
            @page {
                margin: 0;
                size: 22.7cm 13.7cm;
            }
            html, body {
                margin: 0;
                padding: 0;
                gap: 140px;
                background: white;
                width: 100%;
            }
            .actions { display: none; }

            .id-card {
                width: 100%;
                height: 100vh;
                box-shadow: none;
                border: none;
                border-radius: 0;
                page-break-after: always;
                page-break-inside: avoid;
                margin: 0;
                position: relative;
                overflow: hidden;
            }

            /* Restore absolute footers exactly as designed */
            .id-footer,
            .id-footer-back {
                position: absolute !important;
                bottom: 0 !important;
                width: 100% !important;
            }

            /* ---- Larger, crisper fonts for license-card readability ---- */
            .operator-name        { font-size: 28px !important; }
            .role                 { font-size: 20px !important; }
            .info                 { font-size: 19px !important; }
            .info span            { font-size: 20px !important; }
            .highlight            { font-size: 16px !important; font-weight: bold;}
            .header-title         { font-size: 16px !important; letter-spacing: 0.4px !important; }
            .company-name         { font-size: 19px !important; text-shadow: none !important; }
            .division-name        { font-size: 11px !important; }
            .equipment-title      { font-size: 14px !important; }
            .equipment-item       { font-size: 13px !important; }
            .center-text          { font-size: 13px !important; }
            .footer-text          { font-size: 12px !important; }
            .id-footer            { font-size: 15px !important; font-weight: 700 !important; }
        }
    </style>
</head>
<body>

    <!-- FRONT SIDE -->
    <div class="id-card">
        <!-- WATERMARK -->
        <div class="watermark">
            <img src="../assets/img/logo-1.png" alt="Watermark Logo">
        </div>

        <!-- HEADER -->
        <div class="id-header">
            <div class="header-title">
                <span class="company-name">CRANE INSPECTION AND MAINTENANCE SERVICES (CIMS)</span>
                <span class="division-name">A DIVISION OF AL KHOBAR GATE INTERNATIONAL TRADING EST</span>
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
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="operator-name">
                                <?= htmlspecialchars($card['operator_name']); ?>
                            </div>
                            <div class="role" style="font-weight: bold; margin-bottom: 12px;">
                                CERTIFICATE NO: <?= htmlspecialchars($card['certificate_no']); ?>
                            </div>
                        </div>
                        <img src="../assets/img/logo-1.png" alt="Logo" class="name-logo">
                    </div>

                    <div class="row info mb-3">
                        <div class="col-6">
                            <span>ID / IQAMA:</span><br>
                            <span class="highlight"><?= htmlspecialchars($card['id_iqama']); ?></span>
                        </div>
                        <div class="col-6">
                            <span>COMPANY:</span><br>
                            <span class="highlight" style="text-transform: uppercase;"><?= htmlspecialchars($card['company']); ?></span>
                        </div>
                    </div>

                    <div class="row info mb-3">
                        <div class="col-6">
                            <span>ISSUE DATE:</span><br>
                            <span class="highlight"><?= date('d M Y', strtotime($card['issue_date'])); ?></span>
                        </div>
                        <div class="col-6">
                            <span>EXPIRY DATE:</span><br>
                            <span class="highlight"><?= date('d M Y', strtotime($card['expiry_date'])); ?></span>
                        </div>
                    </div>

                    <div class="row info mb-3">
                        <div class="col-6">
                            <span>EXAMINER:</span><br>
                            <span class="highlight" style="text-transform: uppercase;"><?= htmlspecialchars($card['examiner_name']); ?></span>
                        </div>
                        <div class="col-6">
                            <span>OPERATING LOCATION:</span><br>
                            <span class="highlight"><?= htmlspecialchars($card['operating_location']); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FOOTER -->
        <div class="id-footer">
            <div class="text-center" style="text-transform: uppercase; font-weight: bold;"><?= htmlspecialchars($card['operator_designation']); ?></div>
        </div>
    </div>

    <!-- BACK SIDE -->
    <div class="id-card back-card">
        <!-- WATERMARK -->
        <div class="watermark">
            <img src="../assets/img/logo-1.png" alt="CIMS Watermark">
        </div>

        <!-- HEADER -->
        <div class="id-header" style="font-weight: bold;">
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
                        <table class="equipment-table">
                            <thead>
                                <tr>
                                    <th>Equipment Type</th>
                                    <th>Manufacturer</th>
                                    <th>Model</th>
                                    <th>Capacity</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($equipment as $item): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($item['equipment_type']); ?></td>
                                        <td><?= htmlspecialchars($item['manufacturer']); ?></td>
                                        <td><?= htmlspecialchars($item['model']); ?></td>
                                        <td><?= htmlspecialchars($item['capacity']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="text-center p-3 text-muted">No equipment details provided.</div>
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
                <!-- <div class="footer-text">
                    <strong style="color: #fff; font-size: 14.5px;">CRANE INSPECTION AND MAINTENANCE SERVICES</strong><br>
                    A Division of AL-KHOBAR GATE INTERNATIONAL TRADING EST.<br>
                    <i class="fa-solid fa-location-dot"></i> P.O. Box 74007, Bldg. #7036, Al Andalus Street, Rakah, Al Khobar – 31952, KSA.<br>
                    <i class="fa-solid fa-phone"></i> (013) 814 6861 / 6862, 847 8822 | <i class="fa-solid fa-fax"></i> (013) 814 6863<br>
                    <i class="fa-solid fa-globe"></i> www.cims.com.sa
                </div> -->

                <div class="footer-text">
    <strong style="color: #fff; font-size: 14.5px;">
        CRANE INSPECTION AND MAINTENANCE SERVICES
    </strong><br>

    A Division of AL-KHOBAR GATE INTERNATIONAL TRADING EST.<br>

    <i class="fa-solid fa-location-dot"></i>
    P.O. Box 74007, Bldg. #7036, Al Andalus Street, Rakah, Al Khobar – 31952, KSA.<br>

    <i class="fa-solid fa-phone"></i>
    (013) 814 6861 / 6862, 847 8822 |
    <i class="fa-solid fa-fax"></i>
    (013) 814 6863<br>

    <i class="fa-solid fa-envelope"></i>
    imad@cims.com.sa | info@cims.com.sa<br>

    
</div>
                <!-- Adjust QR to link to this card -->
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?= $url . 'operator_card/scan-details.php?id=' . $id; ?>" alt="QR Code" class="footer-img">
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
