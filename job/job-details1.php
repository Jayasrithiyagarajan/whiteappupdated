<?php
include_once('../file/config.php');
// include '../file/auth.php';
include_once('../inc/function.php');

$userRole = $_SESSION['role'] ?? ''; // Default to empty if not set

// Check if the user is a reviewer
$isReviewer = ($userRole === 'reviewer');

// Check if project_no is set in the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $data_id = $_GET['id'];

    // Query to fetch project, checklist, and report details using JOIN
    $query = "
        SELECT 
            p.project_no, p.creation_date, p.project_status, p.equipment_location, p.checklist_type, p.customer_name, p.customer_mobile, p.customer_email, p.checklist_status, p.report_status, p.certificatestatus, p.review_status, p.inspector_name, p.equipment_type,
            c.checklist_no, c.client_name, c.inspected_by, c.created_at AS checklist_created_at,
            c.checklist_type, c.checklist_id,
            r.report_no, r.sticker_number_issued, r.inspection_status, r.created_at AS report_created_at
        FROM project_info p
        LEFT JOIN checklist_information c ON p.project_no = c.project_no
        LEFT JOIN reports r ON p.project_no = r.project_no
        WHERE p.project_no = ?
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $data_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();

        // Determine checklist and report creation status
        $checklistCreated = isset($data['checklist_no']);
        $reportCreated = isset($data['report_no']);

        // Extract the certificatestatus from the database
        $certificateStatus = $data['certificatestatus'];
        // Retrieve checklist_status and report_status directly from the joined data
        $project_status = $data['project_status'];
        $checklistStatus = $data['checklist_status'];
        $dataStatus = $data['report_status'];
        $reviewStatus = $data['review_status']; // Default to 'Pending' if not set

        // Determine if the "Add Certificate" button should be enabled
        // $enableAddCertificate = ($checklistStatus === "Created" && $dataStatus === "Generated");

        // $enableAddCertificate = ($checklistStatus === "Created" && $dataStatus === "Generated" && $reviewStatus === "Completed" && $userRole === 'document controller');

        $enableAddCertificate = ($checklistStatus === "Created" && $dataStatus === "Generated" && $reviewStatus === "Completed" && $userRole === 'document controller' && $certificateStatus !== "Certificate Created");

    // Fetch review status (assuming you have a field `review_status` in your database)

    }
    else {
        echo "No details found for this project.";
        exit;
    }

    // Query to fetch certificate data
    $query = "
    SELECT 
        'healthcheck' AS certificate_type,
        hc.certificate_no,
        COALESCE(hc.inspector, NULL) AS inspector,
        hc.created_at
    FROM crane_health_check_certificate hc
    WHERE hc.project_no = ?        

    UNION

    SELECT 
        'loadtestwithload' AS certificate_type,
        lw.certificate_no,
        COALESCE(lw.inspector_name, NULL) AS inspector,
        lw.created_at
    FROM loadtest_certificate lw
    WHERE lw.project_no = ?

    UNION

    SELECT 
        'mobile' AS certificate_type,
        mc.certificate_no,
        COALESCE(mc.inspector_name, NULL) AS inspector,
        mc.created_at
    FROM mobile_crane_loadtest mc
    WHERE mc.project_no = ?

    UNION
    
    SELECT 
        'withloadtest' AS certificate_type,
        lt.certificate_no,
        COALESCE(lt.inspector_name, NULL) AS inspector,
        lt.created_at
    FROM withload lt
    WHERE lt.project_no = ?

    UNION

    SELECT 
        'lifting' AS certificate_type,
        lc.certificate_no,
        COALESCE(lc.inspector, NULL) AS inspector,
        lc.created_at
    FROM lifting_gear_certificates lc
    WHERE lc.project_no = ?

    UNION

    SELECT 
        'mpi' AS certificate_type,
        mp.certificate_no,
        COALESCE(mp.inspector, NULL) AS inspector,
        mp.created_at
    FROM mpi_certificates mp
    WHERE mp.project_no = ?
    
    UNION

    SELECT 
        'eddycurrent' AS certificate_type,
        ec.certificate_no,
        COALESCE(ec.inspector, NULL) AS inspector,
        ec.created_at
    FROM eddy_current_inspection ec
    WHERE ec.project_no = ?

    UNION

    SELECT 
        'liquidpenetrantinspection' AS certificate_type,
        lpi.certificate_no,
        COALESCE(lpi.inspector, NULL) AS inspector,
        lpi.created_at
    FROM liquid_penetrant_inspection lpi
    WHERE lpi.project_no = ?

    UNION

    SELECT 
        'rocktest' AS certificate_type,
        rt.certificate_no,
        COALESCE(rt.inspector, NULL) AS inspector,
        rt.created_at
    FROM rocking_test_certificate rt
    WHERE rt.project_no = ?

    UNION

    SELECT 
        'lmi' AS certificate_type,
        lmi.certificate_no,
        COALESCE(lmi.inspector, NULL) AS inspector,
        lmi.created_at
    FROM lmi_certificates lmi
    WHERE lmi.project_no = ?    
";
    $stmt = $conn->prepare($query);    if (!$stmt) {
        die("Error in SQL query: " . $conn->error);    }
    $stmt->bind_param("sssssssss", $data_id, $data_id, $data_id, $data_id, $data_id, $data_id, $data_id, $data_id, $data_id);    $stmt->execute();    $result = $stmt->get_result();

    $certificates = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $certificates[] = $row;
        }
    }

// Example: Display the new data
// foreach ($certificates as $certificate) {
//     echo "Type: " . $certificate['certificate_type'] . "<br>";
//     echo "Certificate No: " . $certificate['certificate_no'] . "<br>";
//     echo "Created At: " . $certificate['created_at'] . "<br>";
//     echo "Inspector: " . $certificate['inspector'] . "<br>";


// }

}
else {
    echo "Invalid Project ID.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Project Details | Crane Management System</title>
    
    <!-- Keep your original CSS includes (unchanged) -->
    <link rel="stylesheet" href="<?php echo $url; ?>assets/css/style.css">
    <script src="<?php echo $url; ?>assets/js/jquery.min.js"></script>
    <script src="<?php echo $url; ?>assets/js/bootstrap.bundle.min.js"></script>
    
    <!-- Font Awesome 6 (will not conflict) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        /* ============================================
           ISOLATED PREMIUM STYLES - Only affects this page
           Uses unique class prefixes to avoid conflicts
           ============================================ */
        
        /* Premium container wrapper - scoped to this page only */
        .premium-project-container {
            font-family: system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
        }
        
        /* Premium card styling - unique class */
        .premium-card {
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 20px 35px -12px rgba(0, 0, 0, 0.08), 0 1px 2px rgba(0, 0, 0, 0.02);
            transition: all 0.2s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .premium-card-header {
            background: transparent;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1.25rem 1.5rem;
            font-weight: 600;
            font-size: 1.1rem;
        }
        
        /* Gradient hero - scoped */
        .premium-gradient-hero {
            background: linear-gradient(135deg, #0b2b40 0%, #123e4f 100%);
            border-radius: 28px;
            position: relative;
            overflow: hidden;
        }
        
        /* Badges - scoped */
        .premium-badge {
            padding: 0.5rem 1rem;
            border-radius: 40px;
            font-weight: 600;
            font-size: 0.75rem;
            letter-spacing: 0.3px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        
        .premium-badge-success {
            background: #e0f2e9;
            color: #1e6f3f;
        }
        
        .premium-badge-warning {
            background: #fff3e0;
            color: #b45f06;
        }
        
        .premium-badge-info {
            background: #e3f2fd;
            color: #0b5e7c;
        }
        
        /* Info list - scoped */
        .premium-info-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .premium-info-list li {
            display: flex;
            align-items: baseline;
            padding: 0.6rem 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.04);
            font-size: 0.9rem;
        }
        
        .premium-info-list li:last-child {
            border-bottom: none;
        }
        
        .premium-info-key {
            width: 130px;
            flex-shrink: 0;
            color: #5f6c7a;
            font-weight: 500;
        }
        
        .premium-info-value {
            font-weight: 600;
            color: #1e2a36;
        }
        
        /* Document card - scoped */
        .premium-doc-card {
            background: #fefefe;
            border-radius: 20px;
            transition: transform 0.2s, box-shadow 0.2s;
            border: 1px solid rgba(0, 0, 0, 0.05);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
        }
        
        .premium-doc-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 28px -12px rgba(0, 0, 0, 0.12);
        }
        
        /* Action icons - scoped */
        .premium-action-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 12px;
            background: #f0f2f8;
            color: #2c5f8a;
            transition: all 0.2s;
            text-decoration: none;
        }
        
        .premium-action-icon:hover {
            background: #e2e6ef;
            color: #0a4b6e;
            text-decoration: none;
        }
        
        /* Buttons - scoped with !important to override only if needed */
        .premium-btn {
            background: linear-gradient(95deg, #1a5d7d 0%, #0f445f 100%);
            border: none;
            padding: 0.6rem 1.4rem;
            border-radius: 40px;
            font-weight: 600;
            color: white !important;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }
        
        .premium-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            background: linear-gradient(95deg, #146f96 0%, #0c4b6b 100%);
            color: white !important;
            text-decoration: none;
        }
        
        .premium-btn-outline {
            border: 1px solid #cddfe7;
            background: white;
            border-radius: 40px;
            font-weight: 500;
            padding: 0.5rem 1.2rem;
            transition: all 0.2s;
            color: #2c5f8a !important;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        
        .premium-btn-outline:hover {
            background: #f5f9fc;
            border-color: #98b7ca;
            text-decoration: none;
            color: #1a5d7d !important;
        }
        
        .premium-btn-sm {
            padding: 0.4rem 1rem;
            font-size: 0.85rem;
        }
        
        /* Section title - scoped */
        .premium-section-title {
            font-weight: 600;
            font-size: 1.2rem;
            margin-bottom: 1.2rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .premium-icon-bg {
            background: #eef2fa;
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            color: #1d6f8f;
        }
        
        /* QR wrapper */
        .premium-qr-wrapper {
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(4px);
            border-radius: 24px;
            padding: 0.8rem;
            display: inline-block;
            cursor: pointer;
            transition: 0.2s;
        }
        
        .premium-qr-wrapper:hover {
            transform: scale(1.02);
        }
        
        /* For white text in hero section */
        .premium-gradient-hero .premium-info-key {
            color: rgba(255,255,255,0.7);
        }
        
        .premium-gradient-hero .premium-info-value {
            color: white;
        }
        
        /* Modal styling - scoped but uses modal classes */
        .premium-modal .modal-content {
            border-radius: 28px;
            border: none;
            box-shadow: 0 30px 40px rgba(0,0,0,0.2);
        }
        
        .premium-modal .modal-header {
            border-bottom: 1px solid #eef2f6;
            padding: 1.2rem 1.5rem;
        }
        
        /* Form controls - scoped to not affect global */
        .premium-form-control {
            border-radius: 14px;
            border: 1px solid #dee2e9;
            padding: 0.6rem 1rem;
            width: 100%;
        }
        
        /* Responsive adjustments */
        @media (max-width: 991px) {
            .premium-info-key {
                width: 110px;
                font-size: 0.85rem;
            }
            .premium-info-value {
                font-size: 0.85rem;
            }
            .premium-card-header {
                padding: 1rem;
            }
            .card-body {
                padding: 1rem !important;
            }
        }
        
        @media (max-width: 768px) {
            .premium-project-container .container-fluid {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }
            .premium-gradient-hero {
                padding: 1.5rem !important;
                border-radius: 20px;
            }
            .premium-section-title {
                font-size: 1.1rem;
            }
            .premium-btn {
                width: 100%;
                justify-content: center;
            }
            .premium-badge {
                font-size: 0.7rem;
                padding: 0.4rem 0.8rem;
            }
        }

        @media (max-width: 576px) {
            .premium-info-list li {
                flex-direction: column;
                align-items: flex-start;
                gap: 2px;
            }
            .premium-info-key {
                width: 100%;
                opacity: 0.8;
                font-weight: 400;
            }
            .premium-info-value {
                width: 100%;
                font-size: 0.95rem;
            }
            .h3 {
                font-size: 1.5rem;
            }
            .premium-qr-wrapper img {
                width: 100px;
            }
        }
        
        /* Preserve original table styles if any - no interference */
        .premium-project-container table,
        .premium-project-container .table {
            background: transparent;
            width: 100%;
            display: block;
            overflow-x: auto;
        }
    </style>
</head>
<body>
<div class="main-content d-flex flex-column flex-md-row">
<!-- Wrap entire premium content in a unique container to isolate styles -->
<!-- <div class="premium-project-container-fluid"> -->
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-3">
            <div class="d-flex align-items-center gap-3">
                <a href="#" class="text-secondary text-decoration-none">
                    <i class="fas fa-arrow-left fa-lg"></i>
                </a>
                <div>
                    <h1 class="h3 fw-bold mb-0">Project Workspace</h1>
                    <p class="text-muted small mt-1 mb-0">Inspection & Certification Details</p>
                </div>
            </div>
            <div>
                <span class="badge bg-white text-dark border px-3 py-2 rounded-pill shadow-sm">
                    <i class="far fa-clock me-1 text-primary"></i> JOB ID: #<?php echo htmlspecialchars($data['project_no']); ?>
                </span>
            </div>
        </div>

        <!-- Hero Section -->
        <div class="premium-gradient-hero text-white p-4 p-md-5 mb-4">
            <div class="row g-4 align-items-center">
                <div class="col-12 col-md-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="fas fa-user-circle fa-2x opacity-75"></i>
                        <h5 class="mb-0 fw-semibold">Customer</h5>
                    </div>
                    <div class="ps-1">
                        <div class="fw-bold fs-5 mb-2"><?php echo htmlspecialchars($data['customer_name']); ?></div>
                        <div class="opacity-75 small mb-1"><i class="fas fa-map-marker-alt me-2"></i> <?php echo htmlspecialchars($data['equipment_location']); ?></div>
                        <div class="opacity-75 small mb-1"><i class="fas fa-phone-alt me-2"></i> <?php echo htmlspecialchars($data['customer_mobile']); ?></div>
                        <div class="opacity-75 small mb-1"><i class="fas fa-envelope me-2"></i> <?php echo htmlspecialchars($data['customer_email']); ?></div>
                    </div>
                </div>
                <div class="col-12 col-md-5 col-lg-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="fas fa-clipboard-list fa-2x opacity-75"></i>
                        <h5 class="mb-0 fw-semibold">Project Details</h5>
                    </div>
                    <ul class="premium-info-list">
                        <li><span class="premium-info-key">Project No:</span><span class="premium-info-value"><?php echo htmlspecialchars($data['project_no']); ?></span></li>
                        <li><span class="premium-info-key">Start Date:</span><span class="premium-info-value"><?php echo date('d/m/Y', strtotime($data['creation_date'])); ?></span></li>
                        <li><span class="premium-info-key">Crane Type:</span><span class="premium-info-value"><?php echo ucwords(str_replace(['-', '_'], ' ', htmlspecialchars($data['checklist_type']))); ?></span></li>
                        <li><span class="premium-info-key">Equipment:</span><span class="premium-info-value"><?php echo htmlspecialchars($data['equipment_type']); ?></span></li>
                        <li><span class="premium-info-key">Inspector:</span><span class="premium-info-value"><?php echo htmlspecialchars($data['inspector_name']); ?></span></li>
                        <li><span class="premium-info-key">Status:</span><span class="badge bg-white text-dark px-3 py-1 rounded-pill fw-bold" style="font-size: 0.7rem;"><?php echo htmlspecialchars($data['project_status']); ?></span></li>
                    </ul>
                </div>
                <div class="col-12 col-md-3 col-lg-4 text-center text-md-end">
                    <div class="premium-qr-wrapper d-inline-block text-center">
                        <img class="img-fluid rounded-3" src="../document/code.png" alt="QR Code" width="120px" id="qrCode" style="cursor:pointer; background: white; padding: 5px;">
                        <div class="small mt-2 opacity-75 fw-medium"><i class="fas fa-qrcode mr-1"></i> Tap to verify</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Cards -->
        <div class="row g-4 mb-4">
            <!-- Checklist Card -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="premium-card h-100">
                    <div class="premium-card-header">
                        <div class="premium-section-title mb-0">
                            <span class="premium-icon-bg"><i class="fas fa-check-double"></i></span> 
                            <span>Checklist</span>
                        </div>
                    </div>
                    <div class="card-body p-4 pt-0">
                        <?php if ($checklistCreated): ?>
                            <ul class="premium-info-list">
                                <li><span class="premium-info-key">Checklist No:</span><span class="premium-info-value"><?php echo htmlspecialchars($data['checklist_no']); ?></span></li>
                                <li><span class="premium-info-key">Inspector:</span><span class="premium-info-value"><?php echo htmlspecialchars($data['inspected_by']); ?></span></li>
                                <li><span class="premium-info-key">Client:</span><span class="premium-info-value"><?php echo htmlspecialchars($data['client_name']); ?></span></li>
                                <li><span class="premium-info-key">Created:</span><span class="premium-info-value"><?php echo !empty($data['checklist_created_at']) ? date('F d, Y', strtotime($data['checklist_created_at'])) : 'N/A'; ?></span></li>
                                <li><span class="premium-info-key">Review:</span><span class="premium-badge premium-badge-info"><i class="fas fa-star-of-life"></i> <?php echo htmlspecialchars($reviewStatus); ?></span></li>
                            </ul>
                            <a href="../document/checklist/preview.php?project_no=<?php echo htmlspecialchars($data['project_no']); ?>" target="_blank" class="premium-btn-outline w-100 text-center mt-2 d-block"><i class="fas fa-eye me-2"></i>View Checklist</a>
                        <?php else: ?>
                            <div class="text-center py-4 text-muted"><i class="fas fa-file-alt fa-2x mb-2 opacity-50"></i><br />Checklist not created.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Report Card -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="premium-card h-100">
                    <div class="premium-card-header">
                        <div class="premium-section-title mb-0">
                            <span class="premium-icon-bg"><i class="fas fa-file-signature"></i></span> 
                            <span>Inspection Report</span>
                        </div>
                    </div>
                    <div class="card-body p-4 pt-0">
                        <?php if ($reportCreated): ?>
                            <ul class="premium-info-list">
                                <li><span class="premium-info-key">Report No:</span><span class="premium-info-value"><?php echo htmlspecialchars($data['report_no']); ?></span></li>
                                <li><span class="premium-info-key">Sticker No:</span><span class="premium-info-value"><?php echo htmlspecialchars($data['sticker_number_issued']); ?></span></li>
                                <li><span class="premium-info-key">Inspection:</span><span class="premium-info-value"><?php echo htmlspecialchars($data['inspection_status']); ?></span></li>
                                <li><span class="premium-info-key">Created:</span><span class="premium-info-value"><?php echo !empty($data['report_created_at']) ? date('F d, Y', strtotime($data['report_created_at'])) : 'N/A'; ?></span></li>
                                <li><span class="premium-info-key">Review:</span><span class="premium-badge premium-badge-info"><?php echo htmlspecialchars($reviewStatus); ?></span></li>
                            </ul>
                            <div class="d-flex gap-2 mt-2 flex-wrap">
                                <a href="../document/report/view.php?project_no=<?php echo $data['project_no']; ?>&report_no=<?php echo $data['report_no']; ?>" target="_blank" class="premium-btn-outline flex-grow-1 text-center"><i class="fas fa-eye"></i> View Report</a>
                                <?php if (strtolower($data['inspection_status']) === 'passed'): ?>
                                    <a href="../sticker/download-white.php?sticker_start_no=<?php echo $data['sticker_number_issued']; ?>" target="_blank" class="premium-btn-outline flex-grow-1 text-center"><i class="fas fa-tag"></i> Sticker</a>
                                <?php elseif (strtolower($data['inspection_status']) === 'failed'): ?>
                                    <a href="../sticker/download.php?sticker_start_no=<?php echo $data['sticker_number_issued']; ?>" target="_blank" class="premium-btn-outline flex-grow-1 text-center"><i class="fas fa-exclamation-triangle"></i> Sticker</a>
                                <?php endif; ?>
                            </div>
                            <?php if ($isReviewer && $reviewStatus !== 'Completed'): ?>
                                <a href="#" class="premium-btn w-100 mt-3 text-center" data-toggle="modal" data-target="#reviewModal"
                                   data-project-no="<?php echo htmlspecialchars($data['project_no']); ?>"
                                   data-checklist-no="<?php echo htmlspecialchars($data['checklist_no'] ?? ''); ?>"
                                   data-checklist-type="<?php echo htmlspecialchars($data['checklist_type'] ?? ''); ?>"
                                   data-report-no="<?php echo htmlspecialchars($data['report_no'] ?? ''); ?>"
                                   data-inspected-by="<?php echo htmlspecialchars($data['inspected_by'] ?? ''); ?>">
                                   <i class="fas fa-clipboard-list me-2"></i>Review Project
                                </a>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="text-center py-4 text-muted"><i class="fas fa-file-pdf fa-2x mb-2 opacity-50"></i><br />Report not created.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Certificate Card -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="premium-card h-100">
                    <div class="premium-card-header">
                        <div class="premium-section-title mb-0">
                            <span class="premium-icon-bg"><i class="fas fa-certificate"></i></span> 
                            <span>Certificates</span>
                        </div>
                    </div>
                    <div class="card-body p-4 pt-0">
                        <?php if (!empty($certificates)): ?>
                            <?php 
                            $pathMap = ['healthcheck'=>'health-check', 'loadtestwithload'=>'loadtest', 'mobile'=>'mobile', 'withloadtest'=>'withloadtest', 'lifting'=>'lifting', 'mpi'=>'mpi', 'eddycurrent'=>'eddycurrent', 'liquidpenetrantinspection'=>'liquid-penetrant-inspection-certificate', 'rocktest'=>'rocktest', 'lmi'=>'lmi'];
                            foreach ($certificates as $certificate): 
                                $path = $pathMap[$certificate['certificate_type']] ?? $certificate['certificate_type'];
                            ?>
                                <div class="mb-3 pb-2 border-bottom">
                                    <div class="fw-semibold"><?php echo htmlspecialchars($certificate['certificate_no']); ?></div>
                                    <div class="small text-muted"><?php echo ucfirst(str_replace(['-', 'with'], [' ', 'with '], $certificate['certificate_type'])); ?> • <?php echo date('M d, Y', strtotime($certificate['created_at'])); ?></div>
                                    <a href="../document/<?php echo htmlspecialchars($path); ?>/view.php?project_no=<?php echo $data['project_no']; ?>" target="_blank" class="premium-btn-outline premium-btn-sm mt-2 d-inline-block"><i class="fas fa-file-alt"></i> View Certificate</a>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center py-4 text-muted"><i class="fas fa-stamp fa-2x mb-2 opacity-50"></i><br />No certificates yet.</div>
                        <?php endif; ?>
                        <?php if ($enableAddCertificate): ?>
                            <button type="button" class="premium-btn w-100 mt-2" data-toggle="modal" data-target="#addCertificateModal">
                                <i class="fas fa-plus-circle me-2"></i>Add Certificate
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Documents Section -->
        <div class="premium-card mb-4">
            <div class="premium-card-header d-flex justify-content-between align-items-center flex-wrap">
                <div class="premium-section-title mb-0"><span class="premium-icon-bg"><i class="fas fa-folder-open"></i></span> Uploaded Documents</div>
                <?php if ($userRole === 'inspector'): ?>
                    <button class="premium-btn premium-btn-sm" data-toggle="modal" data-target="#uploadModal"><i class="fas fa-upload me-1"></i> Upload Documents</button>
                <?php endif; ?>
            </div>
            <div class="card-body p-4">
                <?php
                $project_no = $data['project_no'];
                $queryDocs = "SELECT * FROM documents WHERE project_no = ? ORDER BY uploaded_at DESC";
                $stmtDocs = $conn->prepare($queryDocs);
                $stmtDocs->bind_param("s", $project_no);
                $stmtDocs->execute();
                $resultDocs = $stmtDocs->get_result();
                $documents = $resultDocs->fetch_all(MYSQLI_ASSOC);
                $total_docs = 0;
                foreach ($documents as $doc) {
                    for ($i=1;$i<=10;$i++) if(!empty($doc["file_$i"])) $total_docs++;
                }
                if ($total_docs > 0): ?>
                    <div class="row g-3">
                    <?php foreach ($documents as $doc):
                        for ($i=1;$i<=10;$i++):
                            if(!empty($doc["file_$i"])):
                                $filePath = "../uploads/" . htmlspecialchars($doc['project_no']) . "/" . htmlspecialchars($doc["file_$i"]);
                                $fileName = htmlspecialchars(basename($doc["file_$i"]));
                                $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                                $icon = in_array($ext, ['jpg','jpeg','png','gif','webp']) ? 'fas fa-file-image' : (($ext === 'pdf') ? 'fas fa-file-pdf' : 'fas fa-file-alt');
                    ?>
                                <div class="col-12 col-sm-6 col-lg-4">
                                    <div class="premium-doc-card p-3 h-100">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="fw-semibold text-truncate" style="max-width: 70%;"><i class="<?php echo $icon; ?> me-2"></i><?php echo $fileName; ?></div>
                                            <div class="d-flex gap-2">
                                                <a href="<?php echo $filePath; ?>" target="_blank" download class="premium-action-icon"><i class="fas fa-download"></i></a>
                                                <a href="<?php echo $filePath; ?>" target="_blank" class="premium-action-icon"><i class="fas fa-eye"></i></a>
                                            </div>
                                        </div>
                                        <div class="small text-muted mt-2">
                                            <i class="far fa-user-circle"></i> <?php echo htmlspecialchars($doc['uploaded_by'] ?? 'System'); ?> 
                                            <i class="far fa-calendar-alt ms-2"></i> <?php echo date('M d, Y', strtotime($doc['uploaded_at'])); ?>
                                        </div>
                                    </div>
                                </div>
                    <?php   endif;
                        endfor;
                    endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-light text-center py-5"><i class="fas fa-cloud-upload-alt fa-2x mb-2 opacity-50"></i><br/>No documents uploaded yet.</div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Survey & QC Section -->
        <div class="row mt-2">
            <div class="col-12 text-center">
                <?php if ($userRole === 'inspector'): ?>
                    <?php if ($project_status === 'Completed'): ?>
                        <a href="../document/customer_survey_report.php?project_id=<?php echo htmlspecialchars($data['project_no']); ?>" class="premium-btn px-4 py-2" target="_blank"><i class="fas fa-smile me-2"></i>Customer Satisfaction Survey</a>
                    <?php else: ?>
                        <button class="premium-btn-outline px-4 py-2" disabled><i class="fas fa-lock me-2"></i>Survey after completion</button>
                    <?php endif; ?>
                <?php elseif ($userRole === 'admin'): 
                    $surveyCheck = $conn->prepare("SELECT COUNT(*) as total FROM customer_survey_report WHERE project_id = ?");
                    $surveyCheck->bind_param("s", $data['project_no']);
                    $surveyCheck->execute();
                    $surveyRes = $surveyCheck->get_result()->fetch_assoc();
                    $surveyCompleted = ($surveyRes['total'] > 0);
                ?>
                    <?php if ($surveyCompleted): ?>
                        <span class="premium-badge premium-badge-success px-4 py-2"><i class="fas fa-check-circle me-2"></i>Customer Survey Completed</span>
                    <?php else: ?>
                        <span class="premium-badge premium-badge-warning px-4 py-2"><i class="fas fa-hourglass-half me-2"></i>Customer Survey Not Completed</span>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if ($userRole === 'quality controller' && isset($certificateStatus) && $certificateStatus === "Certificate Created"): ?>
                    <div class="mt-3"><button type="button" class="premium-btn" data-toggle="modal" data-target="#qcReviewModal"><i class="fas fa-microphone-alt me-2"></i>QC Controller Review</button></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<!-- </div> -->
</div>

<!-- Modals (same functionality, just keep original IDs) -->
<div class="modal fade" id="addCertificateModal" tabindex="-1" role="dialog" aria-labelledby="addCertificateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Generate Certificate</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Project ID</label>
                    <input type="text" class="form-control" id="projectNo" value="<?php echo htmlspecialchars($data['project_no']); ?>" readonly>
                </div>
                <div class="form-group">
                    <label>Checklist No</label>
                    <input type="text" class="form-control" id="checklistNo" value="<?php echo htmlspecialchars($data['checklist_no']); ?>" readonly>
                </div>
                <div class="form-group">
                    <label>Report No</label>
                    <input type="text" class="form-control" id="reportNo" value="<?php echo htmlspecialchars($data['report_no']); ?>" readonly>
                </div>
                <div class="form-group">
                    <label>Certificate Type</label>
                    <select class="form-control" id="certificateType" required>
                        <option value="" disabled selected>Select Certificate Type</option>
                        <option value="healthcheck">Offshore Crane Health Check</option>
                        <option value="loadtestwithload">Thorough Examination</option>
                        <option value="mobile">Mobile Crane with Load Test</option>
                        <option value="withloadtest">Load Test</option>
                        <option value="lifting">Below the Hook Lifting Gears</option>
                        <option value="mpi">MPI</option>
                        <option value="eddycurrent">Eddy Current</option>
                        <option value="liquidpenetrantinspection">LPI</option>
                        <option value="rocktest">RT</option>
                        <option value="lmi">LMI</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="createCertificateBtn">Create</button>
            </div>
        </div>
    </div>
</div>

<!-- Review Modal -->
<div class="modal fade" id="reviewModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Technical Review</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="reviewForm">
                    <input type="hidden" name="projectNo" id="reviewProjectNo">
                    <input type="hidden" name="checklistNo" id="reviewChecklistNo">
                    <input type="hidden" name="checklistType" id="reviewChecklistType">
                    <input type="hidden" name="reportNo" id="reviewReportNo">
                    <div class="form-group">
                        <label>Review Status</label>
                        <select class="form-control" name="reviewStatus" id="reviewStatus">
                            <option value="Completed">Completed</option>
                            <option value="Pending">Pending</option>
                            <option value="Corrections Needed">Corrections Needed</option>
                            <option value="Rejected">Rejected</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Comments</label>
                        <textarea class="form-control" name="commentBox" id="commentBox" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button class="btn btn-primary" id="submitReview">Submit Review</button>
            </div>
        </div>
    </div>
</div>

<!-- QC Modal -->
<div class="modal fade" id="qcReviewModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5>QC Controller Review</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="qcReviewForm">
                    <input type="hidden" name="qcProjectNo" value="<?php echo htmlspecialchars($data['project_no']); ?>">
                    <input type="hidden" name="qcChecklistNo" value="<?php echo htmlspecialchars($data['checklist_no'] ?? ''); ?>">
                    <input type="hidden" name="qcChecklistType" value="<?php echo htmlspecialchars($data['checklist_type'] ?? ''); ?>">
                    <input type="hidden" name="qcReportNo" value="<?php echo htmlspecialchars($data['report_no'] ?? ''); ?>">
                    <input type="hidden" name="qcReviewStatus" value="In Review">
                    <input type="hidden" name="qcReviewer" value="<?php echo $_SESSION['username']; ?>">
                    <div class="card mb-3">
                        <div class="card-header bg-light">Checklist Review</div>
                        <div class="card-body">
                            <select class="form-control mb-2" name="checklistReviewStatus" id="checklistReviewStatus">
                                <option value="Approved">Approved</option>
                                <option value="Corrections Needed">Corrections Needed</option>
                            </select>
                            <textarea class="form-control" name="checklistComments" placeholder="Comments"></textarea>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header bg-light">Report Review</div>
                        <div class="card-body">
                            <select class="form-control mb-2" name="reportReviewStatus">
                                <option value="Approved">Approved</option>
                                <option value="Corrections Needed">Corrections Needed</option>
                            </select>
                            <textarea class="form-control" name="reportComments" placeholder="Comments"></textarea>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header bg-light">Certificate Review</div>
                        <div class="card-body">
                            <select class="form-control mb-2" name="certificateReviewStatus">
                                <option value="Approved">Approved</option>
                                <option value="Corrections Needed">Corrections Needed</option>
                            </select>
                            <textarea class="form-control" name="certificateComments" placeholder="Comments"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button class="btn btn-primary" id="submitQcReview">Submit Review</button>
            </div>
        </div>
    </div>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Upload Documents</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="uploadForm" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Select files (max 10, JPG/PNG/PDF)</label>
                        <input type="file" class="form-control" name="documents[]" multiple accept=".jpg,.jpeg,.png,.pdf">
                        <div id="fileList" class="mt-2 small"></div>
                    </div>
                    <input type="hidden" name="project_no" value="<?php echo htmlspecialchars($data['project_no']); ?>">
                    <input type="hidden" name="uploaded_by" value="<?php echo htmlspecialchars($userRole); ?>">
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button class="btn btn-primary" id="uploadBtn">Upload</button>
            </div>
        </div>
    </div>
</div>

<script>
// Keep all your original JavaScript functionality exactly as it was
document.addEventListener("DOMContentLoaded", function(){
    const enableAdd = <?php echo json_encode($enableAddCertificate); ?>;
    if(!enableAdd) { let btn = document.querySelector("button[data-target='#addCertificateModal']"); if(btn) btn.disabled = true; }
    
    const qr = document.getElementById('qrCode');
    if(qr){
        qr.onclick = function(){ if("<?php echo $data['project_status']; ?>" === "Completed") window.location.href="verify.php"; else alert("QR code scanning is invalid"); };
    }
    
    $('#createCertificateBtn').click(function(){
        const projectNo = $('#projectNo').val(), checklistNo = $('#checklistNo').val(), reportNo = $('#reportNo').val(), certType = $('#certificateType').val();
        if(!projectNo||!checklistNo||!reportNo||!certType){ alert('Fill all fields'); return; }
        const fd = new FormData(); 
        fd.append('project_no',projectNo); 
        fd.append('checklist_no',checklistNo); 
        fd.append('report_no',reportNo); 
        fd.append('certificate_type',certType);
        fetch('save_certificate.php',{method:'POST',body:fd}).then(res=>res.json()).then(data=>{
            if(data.success){ alert('Certificate saved!'); if(data.redirect_url) location.href=data.redirect_url; } 
            else alert('Error: '+data.message); 
        }).catch(console.error);
    });
    
    $('#reviewModal').on('show.bs.modal',function(e){ 
        let btn=$(e.relatedTarget); 
        $(this).find('#reviewProjectNo').val(btn.data('project-no')); 
        $(this).find('#reviewChecklistNo').val(btn.data('checklist-no')); 
        $(this).find('#reviewChecklistType').val(btn.data('checklist-type')); 
        $(this).find('#reviewReportNo').val(btn.data('report-no')); 
    });
    
    $('#submitReview').click(function(){ 
        let formData=$('#reviewForm').serialize(); 
        $.ajax({url:'submit_review.php',type:'POST',data:formData,dataType:'json',
            success:function(r){ if(r.success){ alert('Review submitted'); $('#reviewModal').modal('hide'); location.reload(); } else alert('Error: '+r.message); },
            error:function(){ alert('Error occurred'); } 
        }); 
    });
    
    $('#submitQcReview').click(function(){ 
        let formData = new FormData($('#qcReviewForm')[0]); 
        $.ajax({url:'submit_qc_review.php',type:'POST',data:formData,processData:false,contentType:false,dataType:'json',
            success:function(r){ if(r.success){ alert('QC Review submitted'); $('#qcReviewModal').modal('hide'); location.reload(); } else alert('Error: '+r.message); },
            error:function(){ alert('QC submit failed'); } 
        }); 
    });
    
    $('#uploadBtn').click(function(){ 
        let files = $('#documentUpload')[0].files; 
        if(files.length===0){ alert('Select files'); return; } 
        let fd = new FormData($('#uploadForm')[0]); 
        $.ajax({url:'upload_documents.php',type:'POST',data:fd,processData:false,contentType:false,
            success:function(res){ 
                try{ let data=typeof res==='object'?res:JSON.parse(res); 
                    if(data.success){ alert('Upload success'); $('#uploadModal').modal('hide'); location.reload(); } 
                    else alert('Upload error: '+data.message);
                }catch(e){ alert('Upload error'); } 
            },
            error:function(){ alert('Upload failed'); } 
        }); 
    });
});
</script>

<?php include_once('../inc/footer.php'); ?>
</body>
</html>