<?php
// session_start(); // Public page, session not strictly required but we can keep standard
include_once('../file/config.php');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$url = isset($url2) ? $url2 : 'http://localhost/whiteappupdated/'; // Fallback URL

// Fetch Main Details
$sql = "SELECT 
            oa.id as assessment_id,
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
    die("Verification details not found. The assessment might be incomplete or the card ID is invalid.");
}

if (!empty($card['certificate_no'])) {
    header("Location: ../certificate/verify.php?cert=" . urlencode($card['certificate_no']));
    exit;
}

// Adjust photo path if needed
$photo_path = $card['photo_path'];
if (strpos($photo_path, '../') === 0) {
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
    <title>Verified Operator Details - <?= htmlspecialchars($card['operator_name']); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        :root {
            --primary: #062b63;
            --primary-light: #0a3d8a;
            --secondary: #ffcc00;
            --accent: #22c55e; /* Green for verified */
            --bg-gradient: linear-gradient(135deg, #f0f4f8 0%, #d9e2ec 100%);
            --card-bg: rgba(255, 255, 255, 0.95);
            --text-main: #102a43;
            --text-muted: #627d98;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: var(--bg-gradient);
            color: var(--text-main);
            min-height: 100vh;
            padding: 30px 15px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .verified-container {
            max-width: 800px;
            width: 100%;
            background: var(--card-bg);
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0, 43, 99, 0.12);
            overflow: hidden;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.8);
        }

        .verification-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: #fff;
            padding: 30px;
            text-align: center;
            position: relative;
            border-bottom: 4px solid var(--secondary);
        }

        .verified-badge {
            background: rgba(34, 197, 94, 0.15);
            color: var(--accent);
            border: 2px solid var(--accent);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 18px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 15px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.4); }
            70% { transform: scale(1.02); box-shadow: 0 0 0 10px rgba(34, 197, 94, 0); }
            100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
        }

        .operator-profile-wrapper {
            padding: 40px 30px;
        }

        .photo-column {
            text-align: center;
            margin-bottom: 30px;
        }

        .operator-photo {
            width: 160px;
            height: 200px;
            object-fit: cover;
            border-radius: 16px;
            border: 3px solid var(--primary);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        @media (max-width: 768px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
        }

        .info-item {
            background: rgba(240, 244, 248, 0.5);
            padding: 15px 20px;
            border-radius: 12px;
            border: 1px solid rgba(217, 226, 236, 0.6);
        }

        .info-label {
            font-size: 12px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .info-value {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-main);
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary);
            margin: 30px 0 15px;
            border-bottom: 2px solid rgba(6, 43, 99, 0.1);
            padding-bottom: 8px;
        }

        .equipment-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #d9e2ec;
        }

        .equipment-table th {
            background: var(--primary);
            color: #fff;
            padding: 12px 15px;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .equipment-table td {
            padding: 12px 15px;
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }

        @media (max-width: 576px) {
            .action-buttons {
                flex-direction: column;
            }
        }

        .btn-download {
            background: linear-gradient(135deg, var(--accent) 0%, #16a34a 100%);
            color: #fff;
            font-weight: 700;
            padding: 14px 28px;
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 15px rgba(34, 197, 94, 0.3);
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-download:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(34, 197, 94, 0.4);
            color: #fff;
        }

        .btn-preview {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: #fff;
            font-weight: 700;
            padding: 14px 28px;
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 15px rgba(6, 43, 99, 0.2);
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-preview:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(6, 43, 99, 0.3);
            color: #fff;
        }

        .footer-credit {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: var(--text-muted);
        }
    </style>
</head>
<body>

<div class="verified-container">
    <div class="verification-header">
        <img src="../assets/img/logo-1.png" alt="CIMS Logo" style="height: 60px; margin-bottom: 10px;">
        <h4 class="mb-0" style="font-weight: 800; letter-spacing: 0.5px;">CRANE INSPECTION & MAINTENANCE SERVICES</h4>
        <span class="verified-badge">
            <i class="fa-solid fa-circle-check"></i> Verified Operator Profile
        </span>
    </div>

    <div class="operator-profile-wrapper">
        <div class="row align-items-center">
            <div class="col-md-3 photo-column">
                <?php if (!empty($card['photo_path'])): ?>
                    <img src="../<?= htmlspecialchars($card['photo_path']); ?>" alt="Operator Photo" class="operator-photo">
                <?php else: ?>
                    <img src="../assets/img/avatar/avatar-1.png" alt="Default Avatar" class="operator-photo">
                <?php endif; ?>
            </div>
            
            <div class="col-md-9">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Operator Name</div>
                        <div class="info-value"><?= htmlspecialchars($card['operator_name']); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Designation</div>
                        <div class="info-value"><?= htmlspecialchars($card['operator_designation']); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Certificate Number</div>
                        <div class="info-value"><?= htmlspecialchars($card['certificate_no']); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">ID / IQAMA</div>
                        <div class="info-value"><?= htmlspecialchars($card['id_iqama']); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Company / Client</div>
                        <div class="info-value"><?= htmlspecialchars($card['company']); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Operating Location</div>
                        <div class="info-value"><?= htmlspecialchars($card['operating_location']); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Issue Date</div>
                        <div class="info-value"><?= date('d M Y', strtotime($card['issue_date'])); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Expiry Date</div>
                        <div class="info-value"><?= date('d M Y', strtotime($card['expiry_date'])); ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-title">Equipment Details</div>
        <?php if (!empty($equipment)): ?>
            <div class="table-responsive">
                <table class="equipment-table">
                    <thead>
                        <tr>
                            <th>Equipment Type</th>
                            <th>Manufacturer</th>
                            <th>Model</th>
                            <th>Capacity (SWL)</th>
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
            </div>
        <?php else: ?>
            <div class="alert alert-light text-center border">No equipment details specified.</div>
        <?php endif; ?>

        <div class="action-buttons">
            <a href="../operator_assessment/download-certificate.php?id=<?= $id; ?>" class="btn-download" target="_blank">
                <i class="fa-solid fa-file-pdf"></i> Download Certificate
            </a>
            <a href="view-card.php?id=<?= $id; ?>" class="btn-preview" target="_blank">
                <i class="fa-solid fa-id-card"></i> View Operator Card
            </a>
        </div>
        
        <div class="footer-credit">
            Powered by Crane Inspection & Maintenance Services © <?= date('Y'); ?>
        </div>
    </div>
</div>

</body>
</html>
