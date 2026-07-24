<?php
include_once(__DIR__.'/../file/config.php');

$cert_no = trim($_REQUEST['cert'] ?? $_REQUEST['cert_no'] ?? '');
$assessment = null;
$error_message = '';
$searched = !empty($cert_no);
$equipment_list = [];

if ($searched) {
    $sql = "SELECT oa.*, c.customer_name as client_name, c.profile_photo as client_logo,
                   nu.username as inspector_name
            FROM operator_assessments oa
            LEFT JOIN customers c  ON oa.client_id   = c.cus_id
            LEFT JOIN new_users nu ON oa.inspector_id = nu.user_id
            WHERE LOWER(TRIM(oa.assessment_no)) = LOWER(?)
            LIMIT 1";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("s", $cert_no);
        $stmt->execute();
        $assessment = $stmt->get_result()->fetch_assoc();
    }

    if ($assessment) {
        $assessment_id = $assessment['id'];
        
        // Fetch Photo
        $photo_stmt = $conn->prepare("SELECT file_path FROM operator_documents WHERE assessment_id=? AND document_type='PHOTO' LIMIT 1");
        $photo_stmt->bind_param("i", $assessment_id);
        $photo_stmt->execute();
        $photo_row = $photo_stmt->get_result()->fetch_assoc();
        
        $photo_url = '../assets/img/avatar/avatar-1.png';
        if ($photo_row && !empty($photo_row['file_path'])) {
            $fp = $photo_row['file_path'];
            if (strpos($fp, '../') === 0) $fp = substr($fp, 3);
            $photo_url = '../' . $fp;
        }
        $assessment['photo_url'] = $photo_url;

        // Fetch Equipment List
        $eq_stmt = $conn->prepare("SELECT * FROM operator_equipment WHERE assessment_id=?");
        $eq_stmt->bind_param("i", $assessment_id);
        $eq_stmt->execute();
        $eq_res = $eq_stmt->get_result();
        while ($row = $eq_res->fetch_assoc()) {
            $equipment_list[] = $row;
        }
    } else {
        $error_message = "No certificate found matching Certificate Number: " . htmlspecialchars($cert_no);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate Verification - CIMS Global</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #002B5B;
            --accent: #C55A11;
            --success: #16a34a;
            --success-bg: #f0fdf4;
            --danger: #dc2626;
            --danger-bg: #fef2f2;
            --bg-gradient: linear-gradient(135deg, #001e3d 0%, #002B5B 50%, #0f4c81 100%);
            --card-bg: #ffffff;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg-gradient);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px 15px;
            color: #333;
        }
        .header-logo {
            text-align: center;
            margin-bottom: 25px;
            color: #fff;
        }
        .header-logo h1 {
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #ffffff;
            margin-top: 10px;
        }
        .header-logo p {
            font-size: 0.85rem;
            color: #cbd5e1;
            margin-top: 4px;
        }
        .main-container {
            width: 100%;
            max-width: 650px;
            background: var(--card-bg);
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }
        .search-box {
            padding: 30px 25px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }
        .search-box h2 {
            font-size: 1.1rem;
            color: var(--primary);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .search-form {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .input-group {
            flex: 1;
            min-width: 220px;
            position: relative;
        }
        .input-group i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }
        .input-group input {
            width: 100%;
            padding: 12px 14px 12px 42px;
            border: 2px solid #cbd5e1;
            border-radius: 10px;
            font-family: inherit;
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--primary);
            text-transform: uppercase;
            transition: all 0.3s ease;
        }
        .input-group input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 43, 91, 0.15);
        }
        .btn-submit {
            background: var(--primary);
            color: #fff;
            border: none;
            padding: 12px 24px;
            border-radius: 10px;
            font-family: inherit;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .btn-submit:hover {
            background: #001e3d;
            transform: translateY(-1px);
        }
        .result-box {
            padding: 30px 25px;
        }
        .status-badge-container {
            text-align: center;
            margin-bottom: 25px;
        }
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 24px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .status-badge.valid {
            background: var(--success-bg);
            color: var(--success);
            border: 2px solid #bbf7d0;
        }
        .status-badge.invalid {
            background: var(--danger-bg);
            color: var(--danger);
            border: 2px solid #fecaca;
        }
        .operator-card {
            display: flex;
            gap: 20px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 20px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }
        .operator-photo-wrap {
            width: 120px;
            height: 150px;
            border-radius: 10px;
            overflow: hidden;
            border: 3px solid #fff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            background: #e2e8f0;
            flex-shrink: 0;
            margin: 0 auto;
        }
        .operator-photo-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .operator-info {
            flex: 1;
            min-width: 250px;
        }
        .operator-name {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 4px;
            text-transform: uppercase;
        }
        .operator-company {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--accent);
            margin-bottom: 15px;
            text-transform: uppercase;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 12px 15px;
        }
        .info-item {
            display: flex;
            flex-direction: column;
        }
        .info-label {
            font-size: 0.72rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-val {
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--primary);
        }
        .equipment-box {
            background: #f1f5f9;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 25px;
        }
        .equipment-box h4 {
            font-size: 0.85rem;
            color: var(--primary);
            text-transform: uppercase;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .equipment-box ul {
            list-style: none;
            padding-left: 0;
        }
        .equipment-box li {
            font-size: 0.85rem;
            font-weight: 600;
            color: #334155;
            padding: 4px 0;
            border-bottom: 1px dashed #cbd5e1;
        }
        .equipment-box li:last-child {
            border-bottom: none;
        }
        .action-buttons {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
        .btn-action {
            flex: 1;
            min-width: 200px;
            padding: 14px 20px;
            border-radius: 10px;
            text-align: center;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s ease;
        }
        .btn-cert {
            background: var(--success);
            color: #fff;
        }
        .btn-cert:hover {
            background: #15803d;
            box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3);
        }
        .btn-card {
            background: #eab308;
            color: #000;
        }
        .btn-card:hover {
            background: #ca8a04;
            box-shadow: 0 4px 12px rgba(234, 179, 8, 0.3);
        }
        .footer-text {
            text-align: center;
            color: #94a3b8;
            font-size: 0.78rem;
            margin-top: 25px;
        }
        @media (max-width: 480px) {
            .operator-card { flex-direction: column; text-align: center; }
            .info-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <div class="header-logo">
        <i class="fas fa-certificate fa-3x" style="color: #fbbf24;"></i>
        <h1>CIMS Global Verification</h1>
        <p>Crane Inspection & Maintenance Services</p>
    </div>

    <div class="main-container">
        <!-- Search Box -->
        <div class="search-box">
            <h2><i class="fas fa-search"></i> Certificate Verification</h2>
            <form action="verify.php" method="GET" class="search-form">
                <div class="input-group">
                    <i class="fas fa-id-card-clip"></i>
                    <input type="text" name="cert" value="<?= htmlspecialchars($cert_no) ?>" placeholder="Enter Certificate No. (e.g. CIMS-OAT-004)" required>
                </div>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-shield-halved"></i> Verify
                </button>
            </form>
        </div>

        <?php if ($searched): ?>
            <div class="result-box">
                <?php if ($assessment): ?>
                    <!-- Valid Result -->
                    <div class="status-badge-container">
                        <div class="status-badge valid">
                            <i class="fas fa-circle-check fa-lg"></i> Verified Valid Certificate
                        </div>
                    </div>

                    <div class="operator-card">
                        <div class="operator-photo-wrap">
                            <img src="<?= htmlspecialchars($assessment['photo_url']) ?>" alt="Candidate Photo">
                        </div>
                        <div class="operator-info">
                            <div class="operator-name"><?= htmlspecialchars(ucwords(strtolower($assessment['operator_name'] ?? ''))) ?></div>
                            <div class="operator-company"><?= htmlspecialchars($assessment['client_name'] ?? 'N/A') ?></div>
                            
                            <div class="info-grid">
                                <div class="info-item">
                                    <span class="info-label">Certificate No.</span>
                                    <span class="info-val"><?= htmlspecialchars($assessment['assessment_no']) ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Passport / ID</span>
                                    <span class="info-val"><?= htmlspecialchars($assessment['operator_id_passport'] ?? 'N/A') ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Vessel / Location</span>
                                    <span class="info-val">AL-KHOBAR, SAUDI ARABIA</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Training Program</span>
                                    <span class="info-val"><?= htmlspecialchars(ucwords(strtolower($assessment['training_program'] ?? 'Training & Competency Assessment'))) ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Date of Issue</span>
                                    <span class="info-val"><?= !empty($assessment['date_of_assessment']) ? date('d F Y', strtotime($assessment['date_of_assessment'])) : 'N/A' ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Expiry Date</span>
                                    <span class="info-val"><?= !empty($assessment['date_of_expiry']) ? date('d F Y', strtotime($assessment['date_of_expiry'])) : 'N/A' ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($equipment_list)): ?>
                        <div class="equipment-box">
                            <h4><i class="fas fa-truck-monster"></i> Equipment & Competency Assessment</h4>
                            <ul>
                                <?php foreach ($equipment_list as $eq): ?>
                                    <li>
                                        &bull; <?= htmlspecialchars(trim($eq['equipment_type'].' '.$eq['manufacturer'].' '.$eq['model'])) ?>
                                        <?php if (!empty($eq['capacity'])): ?>
                                            (SWL: <?= htmlspecialchars($eq['capacity']) ?>)
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <div class="action-buttons">
                        <a href="generate_certificate.php?id=<?= $assessment['id'] ?>" class="btn-action btn-cert" target="_blank">
                            <i class="fas fa-file-pdf"></i> Download Certificate
                        </a>
                        <a href="../operator_card/view-card.php?id=<?= $assessment['id'] ?>" class="btn-action btn-card" target="_blank">
                            <i class="fas fa-id-card"></i> View Operator Card
                        </a>
                    </div>

                <?php else: ?>
                    <!-- Invalid / Not Found -->
                    <div class="status-badge-container">
                        <div class="status-badge invalid">
                            <i class="fas fa-circle-xmark fa-lg"></i> Invalid Certificate Number
                        </div>
                    </div>
                    <p style="text-align:center; color:#64748b; font-size:0.9rem;">
                        <?= htmlspecialchars($error_message) ?>
                    </p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="footer-text">
        &copy; <?= date('Y') ?> CIMS Global - Crane Inspection & Maintenance Services. All rights reserved.
    </div>

</body>
</html>
