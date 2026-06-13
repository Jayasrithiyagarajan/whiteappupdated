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

        // Security check: If the user is an inspector, ensure the project belongs to them
        if ($userRole === 'inspector' && isset($_SESSION['username']) && strcasecmp($data['inspector_name'], $_SESSION['username']) !== 0) {
            echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><title>Access Denied</title>';
            echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
            echo '<style>body { background: #f4f7f6; font-family: "Outfit", sans-serif; }</style>';
            echo '</head><body>';
            echo '<script>
                Swal.fire({
                    icon: "error",
                    title: "Access Restricted",
                    text: "You do not have permission to view this project.",
                    confirmButtonColor: "#eb3349",
                    confirmButtonText: "Return to Dashboard",
                    allowOutsideClick: false,
                    background: "#ffffff",
                    customClass: {
                        popup: "rounded-4 shadow-lg"
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "../dashboard/inspector.php";
                    }
                });
            </script></body></html>';
            exit;
        }

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

        $enableAddCertificate = ($checklistStatus === "Created" && $dataStatus === "Generated" && $reviewStatus === "Completed" && ($userRole === 'document controller' || $userRole === 'inspector') && $certificateStatus !== "Certificate Created");

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
    $stmt->bind_param("ssssssssss", $data_id, $data_id, $data_id, $data_id, $data_id, $data_id, $data_id, $data_id, $data_id, $data_id);    $stmt->execute();    $result = $stmt->get_result();

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Details</title>
    <!-- Include your CSS and JS files here -->
    <link rel="stylesheet" href="<?php echo $url; ?>assets/css/style.css">
    <!-- Lightbox CSS (for image preview) -->
<!--<link href="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.css" rel="stylesheet">-->
<!-- Bootstrap (optional but needed for lightbox styles) -->
<!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">-->

<!-- Ekko Lightbox -->
<!--<link href="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.min.css" rel="stylesheet">-->
<link rel="stylesheet" href="../assets/css/premium-nav.css">
    <script src="<?php echo $url; ?>assets/js/jquery.min.js"></script>
    <script src="<?php echo $url; ?>assets/js/bootstrap.bundle.min.js"></script>
    <style>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');

body {
    font-family: 'Outfit', sans-serif;
}

.main-content {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    min-height: calc(100vh - 60px);
    padding: 2rem 0;
}

.invoice-details-header {
    background: rgba(255, 255, 255, 0.85) !important;
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border-radius: 20px;
    box-shadow: 0 10px 40px 0 rgba(31, 38, 135, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.4);
    padding: 1.5rem !important;
    margin-bottom: 2rem !important;
    animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.invoice-pd.c2-bg {
    background: linear-gradient(120deg, #2b4162 0%, #12100e 100%) !important;
    border-radius: 20px;
    box-shadow: 0 15px 50px 0 rgba(0, 0, 0, 0.2);
    margin-bottom: 2rem !important;
    overflow: hidden;
    position: relative;
    border: 1px solid rgba(255, 255, 255, 0.1);
    animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    animation-delay: 0.1s;
    opacity: 0;
}

.bg-white.invoice-pd {
    background: rgba(255, 255, 255, 0.85) !important;
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border-radius: 20px;
    box-shadow: 0 10px 40px 0 rgba(31, 38, 135, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.4);
    padding: 2rem !important;
    animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    animation-delay: 0.2s;
    opacity: 0;
}

.details-list-wrap {
    background: rgba(255, 255, 255, 0.85) !important;
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border-radius: 20px;
    box-shadow: 0 10px 40px 0 rgba(31, 38, 135, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.4);
    margin-top: 2rem;
    padding: 1.5rem !important;
    animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    animation-delay: 0.3s;
    opacity: 0;
}

.invoice.payment-details {
    background: rgba(255, 255, 255, 0.6);
    border-radius: 15px;
    padding: 1.5rem;
    border: 1px solid rgba(255, 255, 255, 0.8);
    box-shadow: 0 5px 15px rgba(0,0,0,0.03);
    height: 100%;
    transition: all 0.3s ease;
}

.invoice.payment-details:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    background: rgba(255, 255, 255, 0.8);
}

.status-btn.completed {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%) !important;
    border: none;
    box-shadow: 0 4px 15px rgba(56,239,125,0.4);
    border-radius: 50px;
    padding: 0.4rem 1rem;
    font-weight: 500;
    transition: all 0.3s;
}

.bg-primary.status-btn {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%) !important;
    box-shadow: 0 4px 15px rgba(0,242,254,0.4);
}

.bg-warning.status-btn {
    background: linear-gradient(135deg, #f6d365 0%, #fda085 100%) !important;
    box-shadow: 0 4px 15px rgba(253,160,133,0.4);
}

.bg-danger.status-btn {
    background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%) !important;
    box-shadow: 0 4px 15px rgba(244,92,67,0.4);
}

.status-btn:hover {
    transform: translateY(-2px);
    filter: brightness(1.1);
}

.btn-primary {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    border: none;
    border-radius: 50px;
    box-shadow: 0 4px 15px rgba(0,242,254,0.4);
    transition: all 0.3s;
    padding: 0.5rem 1.5rem;
    font-weight: 600;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,242,254,0.6);
}

.document-card {
    background: rgba(255, 255, 255, 0.9) !important;
    backdrop-filter: blur(10px);
    border-radius: 15px !important;
    border: 1px solid rgba(255, 255, 255, 0.6) !important;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
}

.document-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.document-card .card-header {
    background-color: rgba(248, 249, 250, 0.5);
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    border-radius: 15px 15px 0 0 !important;
    font-weight: 600;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Base styles imported */
.list-group-item {
    padding: 0.75rem 1.25rem;
    margin-bottom: 5px;
    border-radius: 4px !important;
}
.list-group-item:hover {
    background-color: #f8f9fa;
}
.btn-group .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}
.document-preview {
    height: 120px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f5f5f5;
    border-radius: 5px;
    margin-bottom: 10px;
}
.document-preview img {
    max-height: 100%;
    width: auto;
    object-fit: contain;
}
.card-title {
    font-size: 0.85rem;
    font-weight: 500;
}
.card-footer small {
    font-size: 0.75rem;
}
.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.8rem;
}

/* Align labels and values neatly */
.status-list li {
    display: flex !important;
    align-items: center !important;
    margin-bottom: 12px !important;
}

.status-list .key {
    width: 170px !important;
    min-width: 170px !important;
    flex-shrink: 0 !important;
    color: #8a99ad !important;
    font-weight: 500 !important;
    display: inline-block !important;
}

.invoice-right .status-list .key {
    color: #CAC6FB !important;
}

.status-list span:not(.key) {
    flex-grow: 1 !important;
}
    </style>
</head>
<body>
    <div class="main-content d-flex flex-column flex-md-row">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <!-- Invoice Header -->
                    <div class="invoice-details-header bg-white d-flex flex-column flex-sm-row mb-30 justify-content-between">
                        <div class="d-flex align-items-center mb-3 mb-sm-0">
                            <a href="#" class="mr-2">
                                <img src="<?php echo $url; ?>assets/img/svg/angle-left.svg" alt="" class="svg">
                            </a>
                            <h2 class="regular mr-3 font-30">JOB ID</h2>
                            <h4 class="c4">#<?php echo htmlspecialchars($data['project_no']); ?></h4>
                        </div>
                        <!-- <div class="invoice-header-right d-flex align-items-center justify-content-end">
                            <div class="delete_mail mr-20">
                                <a href="#"><img src="<?php echo $url; ?>assets/img/svg/delete.svg" alt="" class="svg"></a>
                            </div>
                            <div class="edit-invoice-btn pr-1">
                                <a href="invoice-add-new.html" class="btn-circle">
                                    <img src="<?php echo $url; ?>assets/img/svg/writing.svg" alt="" class="svg">
                                </a>
                            </div>
                        </div> -->
                    </div>
                    <!-- End Invoice Header -->

                    <!-- Invoice Top -->
                    <div class="invoice-pd c2-bg" data-bg-img="../../../assets/img/media/invoice-pattern.png">
                        <div class="row">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <!-- Invoice Left -->
                                <div class="invoice-left">
                                    <h3 class="white font-20 mb-3">Customer Details</h3>
                                    <ul class="list-invoice">
                                        <li class="user">
     <?php echo htmlspecialchars($data['customer_name']); ?>
</li>
                                        <li class="location"> 
                                            <?php echo htmlspecialchars($data['equipment_location']); ?>
                                        </li>
                                        <li class="call">
                                            <a href="tel:+01234567891"><?php echo htmlspecialchars($data['customer_mobile']); ?></a>
                                        </li>
                                        <li class="mail"><?php echo htmlspecialchars($data['customer_email']); ?></li>
                                    </ul>
                                </div>
                                <!-- End Invoice Left -->
                            </div>
                            <div class="col-md-4 mb-3 mb-md-0">
                                <!-- Invoice Right -->
                                <div class="invoice-right">
                                    <h3 class="white font-20 mb-3">Project Details</h3>
                                    <ul class="status-list">
                                        
                                        <li><span class="key font-14">Project No:</span>
                                            <span class="white bold font-17"><?php echo htmlspecialchars($data['project_no']); ?></span>
                                        </li>
                                        <li>
                                            <span class="key font-14">Start Date:</span>
                                            <span class="white bold font-17"><?php echo htmlspecialchars(date('d/m/Y', strtotime($data['creation_date']))); ?></span>
                                        </li>
                                        <li><span class="key font-14">Handle Crane:</span>
                                             <span class="white bold font-17">
                                                 <?php

echo ucwords(str_replace(['-', '_'], ' ', htmlspecialchars($data['checklist_type'])));
?>


                                             </span> 
                                        </li>
                                        
                                        <li><span class="key font-14">Equipment Category:</span>
                                            <span class="white bold font-17">
                                                <?php echo htmlspecialchars($data['equipment_type']); ?>
                                                </span>
                                        </li>
                                        <li><span class="key font-14">Inspector:</span>
                                            <span class="white bold font-17">
                                                <?php echo htmlspecialchars($data['inspector_name']); ?>
                                                </span>
                                        </li>
                                        <li><span class="key font-14">Status:</span>
                                            <span class="white status-btn completed">
                                                <?php echo htmlspecialchars($data['project_status']); ?>
                                                </span>
                                        </li>
                                    </ul>
                                </div>
                                <!-- End Invoice Right -->
                            </div>
                            <div class="col-md-4">
    <!-- Invoice Right -->
    <div class="invoice-right">
        <h3 class="white font-20 mb-3">QR Status</h3>
        <img class="img-fluid" src="../document/code.png" alt="QR Code" width="150px" id="qrCode">
    </div>
    <!-- End Invoice Right -->
</div>
                        </div>
                    </div>
                    <!-- End Invoice Top -->

                    <!-- Invoice Wrapper -->
                    <div class="bg-white invoice-pd position-relative">
                        <!-- Button in the top-right corner -->





                        <div class="row">
                            <div class="col-lg-4 col-md-6">
                                <!-- Checklist Details -->
                                <div class="invoice payment-details mt-5 mt-xl-0">
                                    <div class="bold black font-17 mb-3">Checklist Details:</div>
                                    <?php if ($checklistCreated): ?>
                                        <ul class="status-list">
                                            <li><span class="key">Checklist No:</span> <span class="black"><?php echo htmlspecialchars($data['checklist_no']); ?></span></li>
                                            <li><span class="key">Inspector:</span> <span class="black"><?php echo htmlspecialchars($data['inspected_by']); ?></span></li>
                                            <li><span class="key">Client Name:</span> <span class="black"><?php echo htmlspecialchars($data['client_name']); ?></span></li>
                                           <li><span class="key">Created At:</span> 
    <span class="black">
        <?php
    if (!empty($data['checklist_created_at'])) {
        echo date('F d, Y', strtotime($data['checklist_created_at']));
    }
    else {
        echo 'N/A';
    }
?>
    </span>
</li>
                                            <li><span class="key">Review Status:</span> <span class="text-success"><?php echo htmlspecialchars($reviewStatus); ?></span></li>
                                            <a href="../document/checklist/preview.php?project_no=<?php echo htmlspecialchars($data['project_no']); ?>" target="_blank">
                                                <span class="bg-primary text-white status-btn completed"> View Checklist</span>
                                            </a>
                                        </ul>
                                    <?php
else: ?>
                                        <p class="black">Checklist not created.</p>
                                    <?php
endif; ?>
                                </div>
                            </div>

                            

                            <div class="col-lg-4 col-md-6">
                                <!-- Report Details -->
                                <!--<div class="invoice invoice-form">-->
                                <div class="invoice payment-details mt-5 mt-xl-0">
                                    <div class="bold black font-17 mb-3">Report Details:</div>
                                    <?php if ($reportCreated): ?>
                                        <ul class="status-list">
                                            <li><span class="key">Report No:</span> <span class="black"><?php echo htmlspecialchars($data['report_no']); ?></span></li>
                                            <li><span class="key">New Sticker No:</span> <span class="black"><?php echo htmlspecialchars($data['sticker_number_issued']); ?></span></li>
                                            <li><span class="key">Inspection Status:</span> <span class="black"><?php echo htmlspecialchars($data['inspection_status']); ?></span></li>
                                            <li><span class="key">Created At:</span> 
    <span class="black">
        <?php
    if (!empty($data['checklist_created_at'])) {
        echo date('F d, Y', strtotime($data['report_created_at']));
    }
    else {
        echo 'N/A';
    }
?>
    </span>
</li>
                                            <li><span class="key">Review Status:</span> <span class="text-success"><?php echo htmlspecialchars($reviewStatus); ?></span></li>                                            
                                            <a href="../document/report/view.php?project_no=<?php echo $data['project_no']; ?>&report_no=<?php echo $data['report_no']; ?>" target="_blank">
    <span class="bg-primary text-white status-btn completed"> View Report</span>
</a>

<!--<a href="../sticker/download-white.php?sticker_start_no=<?php echo $data['sticker_number_issued']; ?>" target="_blank">-->
<!--    <span class="bg-warning text-white status-btn ps-2 completed"> View Sticker</span>-->
<!--</a>-->

<?php if (strtolower($data['inspection_status']) === 'passed'): ?>
    <a href="../sticker/download-white.php?sticker_start_no=<?php echo $data['sticker_number_issued']; ?>" target="_blank">
        <span class="bg-warning text-white status-btn ps-2 completed"> View Sticker</span>
    </a>
<?php
    elseif (strtolower($data['inspection_status']) === 'failed'): ?>
    <a href="../sticker/download.php?sticker_start_no=<?php echo $data['sticker_number_issued']; ?>" target="_blank">
        <span class="bg-danger text-white status-btn ps-2 completed"> View Sticker</span>
    </a>
<?php
    endif; ?>



                                            <?php if ($isReviewer && $reviewStatus !== 'Completed'): ?>
    <a href="#" class="btn btn-primary" style="padding: 5px 10px;" data-toggle="modal" data-target="#reviewModal"
       data-project-no="<?php echo htmlspecialchars($data['project_no']); ?>"
       data-checklist-no="<?php echo htmlspecialchars($data['checklist_no'] ?? ''); ?>"
       data-checklist-type="<?php echo htmlspecialchars($data['checklist_type'] ?? ''); ?>"
       data-report-no="<?php echo htmlspecialchars($data['report_no'] ?? ''); ?>"
       data-inspected-by="<?php echo htmlspecialchars($data['inspected_by'] ?? ''); ?>">
        Review
    </a>
<?php
    endif; ?>
                                        </ul>
                                    <?php
else: ?>
                                        <p class="black">Report not created.</p>
                                    <?php
endif; ?>
                                </div>
                            </div>








                            <!-- Certificate Details -->
<div class="col-lg-4 col-md-6">
    <!--<div class="invoice invoice-form">-->
    <div class="invoice payment-details mt-5 mt-xl-0">
        <div class="black bold font-17 mb-3">
            Certificate Details:
            <?php if (count($certificates) > 1): ?>
                <span class="badge badge-primary badge-pill ml-2"><?php echo count($certificates); ?></span>
            <?php endif; ?>
        </div>
        <?php if (!empty($certificates)): ?>
            <ul class="status-list" id="certificatesList">
                <?php
    // Define path mapping for certificate types
    $certificate_paths = [
        'healthcheck' => 'health-check',
        'loadtestwithload' => 'loadtest',
        'mobile' => 'mobile',
        'withloadtest' => 'withloadtest',
        'lifting' => 'lifting',
        'mpi' => 'mpi',
        'eddycurrent' => 'eddycurrent',
        'liquidpenetrantinspection' => 'liquid-penetrant-inspection-certificate',
        'rocktest' => 'rocktest',
        'lmi' => 'lmi'
    ];

    foreach ($certificates as $index => $certificate):
        $path = $certificate_paths[$certificate['certificate_type']] ?? strtolower($certificate['certificate_type']);
        $isFirstCert = ($index === 0);
        $certificateClass = $isFirstCert ? 'cert-item-visible' : 'cert-item-hidden';
?>
                    <li class="cert-item <?php echo $certificateClass; ?>" data-cert-index="<?php echo $index; ?>">
                        <span class="key">Certificate No:</span> 
                        <span class="black"><?php echo htmlspecialchars($certificate['certificate_no']); ?></span>
                    </li>
                    <li class="cert-item <?php echo $certificateClass; ?>" data-cert-index="<?php echo $index; ?>">
                        <span class="key">Created On:</span> 
                        <span class="black"><?php echo date('F d, Y', strtotime($certificate['created_at'])); ?></span>
                    </li>
                    <li class="cert-item <?php echo $certificateClass; ?>" data-cert-index="<?php echo $index; ?>">
                        <span class="key">Type:</span> 
                        <span class="black"><?php echo ucfirst(str_replace(['-', 'with'], [' ', 'with '], $certificate['certificate_type'])); ?></span>
                    </li>
                    <li class="cert-item <?php echo $certificateClass; ?>" data-cert-index="<?php echo $index; ?>">
                        <a href="../document/<?php echo htmlspecialchars($path); ?>/view.php?project_no=<?php echo $data['project_no']; ?>" class="d-inline-block mt-2"  target="_blank">
                            <span class="bg-primary text-white status-btn completed">View Certificate</span>
                        </a>
                    </li>
                    <?php
    endforeach; ?>
            </ul>
            <?php if (count($certificates) > 1): ?>
                <button class="btn btn-sm btn-outline-primary mt-3" id="toggleCertificatesBtn">
                    <i class="fas fa-chevron-down mr-2"></i>Show All
                </button>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($enableAddCertificate): ?>
            <button type="button" class="btn btn-primary w-100 mt-3" data-toggle="modal" data-target="#addCertificateModal" id="addCertificateButton">
                <i class="fas fa-plus-circle mr-2"></i> Add Certificate
            </button>
        <?php endif; ?>
    </div>
</div>

<style>
    #certificatesList .cert-item-hidden {
        display: none !important;
    }
    
    #certificatesList .cert-item-visible {
        display: list-item !important;
    }
    
    #certificatesList.certificates-expanded .cert-item-hidden {
        display: list-item !important;
    }
</style>

<!-- Document Details -->
<!--<div class="mt-5 ml-3 mr-3">-->
<div class="col-lg-4 col-md-6 mt-5">
    <div class="invoice invoice-form">
    <div class="black bold font-17 mb-3">Uploaded Documents:</div>
    <?php
// Fetch uploaded documents from the database
$project_no = $data['project_no'];
$query = "SELECT * FROM documents WHERE project_no = ? ORDER BY uploaded_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $project_no);
$stmt->execute();
$result = $stmt->get_result();
$documents = $result->fetch_all(MYSQLI_ASSOC);

// Count total documents
$total_docs = 0;
foreach ($documents as $doc) {
    for ($i = 1; $i <= 10; $i++) {
        if (!empty($doc["file_$i"])) {
            $total_docs++;
        }
    }
}
?>

    <?php if ($total_docs > 0): ?>
        <div class="uploaded-documents-container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0">Total Documents Uploaded: <span class="badge bg-primary"><?php echo $total_docs; ?></span></h5>
            </div>
            
            <div class="row">
    <?php foreach ($documents as $doc): ?>
        <?php for ($i = 1; $i <= 10; $i++): ?>
            <?php if (!empty($doc["file_$i"])):
                $filePath = "../uploads/" . htmlspecialchars($doc['project_no']) . "/" . htmlspecialchars($doc["file_$i"]);
                $fileName = htmlspecialchars(basename($doc["file_$i"]));
                $isImage = isImageFile($doc["file_$i"]);
?>
                <div class="col-12 col-md-12 col-lg-12 mb-4">

                    <div class="card document-card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center py-2">
                            <div>
                                <i class="<?php echo getFileIcon($doc["file_$i"]); ?> mr-2"></i>
                                <strong>Document <?php echo $i; ?></strong>
                            </div>
                        </div>
                        <div class="card-body p-3">
                           <div class="d-flex align-items-center justify-content-between mb-2">
    <h6 class="card-title text-truncate mb-0 flex-grow-1" title="<?php echo $fileName; ?>">
        <?php echo $fileName; ?>
    </h6>
    <div class="ms-2 d-flex align-items-center gap-2">
        <a href="<?php echo $filePath; ?>" 
           target="_blank" 
           download 
           class="text-primary me-2">
            <i class="fas fa-download"></i>
        </a>
        <a href="<?php echo $filePath; ?>" target="_blank" class="text-secondary">
    <i class="fas fa-eye"></i>
</a>

    </div>
</div>

                            
                            <!--<?php if ($isImage): ?>-->
                            <!--<div class="document-preview mb-3">-->
                            <!--    <a href="<?php echo $filePath; ?>" data-toggle="lightbox" data-title="<?php echo $fileName; ?>">-->
                            <!--        <img src="<?php echo $filePath; ?>" class="img-fluid rounded" alt="<?php echo $fileName; ?>">-->
                            <!--    </a>-->
                            <!--</div>-->
                            <!--<?php
                endif; ?>-->
                            
<!--                            <div class="d-flex justify-content-start mt-2 gap-2">-->
<!--    <a href="<?php echo $filePath; ?>" -->
<!--       target="_blank" -->
<!--       download -->
<!--       class="text-primary">-->
<!--        <i class="fas fa-download"></i>-->
<!--    </a>-->
    
<!--    <a href="<?php echo $filePath; ?>" -->
<!--       target="_blank" -->
<!--       class="text-secondary"-->
<!--       <?php if ($isImage): ?>data-toggle="lightbox"<?php
                endif; ?>>-->
<!--        <i class="fas fa-eye"></i>-->
<!--    </a>-->
<!--</div>-->

                        </div>
                        <div class="card-footer bg-transparent py-2">
                            <small class="text-muted">
                                <i class="fas fa-user-circle me-1"></i> <?php echo htmlspecialchars($doc['uploaded_by'] ?? 'System'); ?>
                                <i class="fas fa-clock ms-2 me-1"></i> <?php echo date('M d, Y H:i', strtotime($doc['uploaded_at'])); ?>
                            </small>
                        </div>
                    </div>
                </div>
            <?php
            endif; ?>
        <?php
        endfor; ?>
    <?php
    endforeach; ?>
</div>

        </div>
    <?php
else: ?>
        <div class="alert alert-info d-flex align-items-center">
            <i class="fas fa-info-circle me-2"></i>
            No documents uploaded yet.
        </div>
    <?php
endif; ?>
</div>
</div>

    
    <?php if ($userRole === 'inspector'): ?>
        <div class="justify-content-center mt-3">
            <button class="btn btn-primary mt-3" data-toggle="modal" data-target="#uploadModal">
                <i class="fas fa-upload mr-2"></i> Upload Documents
            </button>
        </div>
    <?php
endif; ?>


<?php
// Helper function to get file icon
function getFileIcon($filename)
{
    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    switch ($extension) {
        case 'pdf':
            return 'far fa-file-pdf text-danger';
        case 'jpg':
        case 'jpeg':
        case 'png':
        case 'gif':
            return 'far fa-file-image text-primary';
        case 'doc':
        case 'docx':
            return 'far fa-file-word text-primary';
        case 'xls':
        case 'xlsx':
            return 'far fa-file-excel text-success';
        case 'zip':
        case 'rar':
            return 'far fa-file-archive text-warning';
        default:
            return 'far fa-file-alt text-secondary';
    }
}

// Helper function to check if file is an image
function isImageFile($filename)
{
    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    return in_array($extension, $imageExtensions);
}
?>


<div class="col-12 mt-4">
    <div class="text-center">
        <?php
// Check if current user is an inspector
if ($userRole === 'inspector'):
?>
            <?php if ($project_status === 'Completed'): ?>
                <a href="../document/customer_survey_report.php?project_id=<?php echo htmlspecialchars($data['project_no']); ?>" 
                   class="btn btn-success btn-lg" 
                   target="_blank">
                    <i class="fas fa-clipboard-check mr-2"></i>
                    Customer Satisfaction Survey
                </a>
                <p class="text-muted mt-2">
                    Please provide your valuable feedback about our service
                </p>
            <?php
    else: ?>
                <button class="btn btn-outline-secondary btn-lg" disabled>
                    <i class="fas fa-clipboard-check mr-2"></i>
                    Survey Available After Project Completion
                </button>
            <?php
    endif; ?>

        <?php
// ✅ For Admin: Show whether survey is completed or not
elseif ($userRole === 'admin'):
    // Check if survey record exists for this project
    $surveyCheckQuery = "SELECT COUNT(*) AS total FROM customer_survey_report WHERE project_id = ?";
    $surveyStmt = $conn->prepare($surveyCheckQuery);
    $surveyStmt->bind_param("s", $data['project_no']);
    $surveyStmt->execute();
    $surveyResult = $surveyStmt->get_result();
    $surveyData = $surveyResult->fetch_assoc();
    $surveyCompleted = ($surveyData['total'] > 0);
?>
            <?php if ($surveyCompleted): ?>
                <p class="text-success font-weight-bold" style="font-size: 18px;">
                    ✅ Customer Survey Completed
                </p>
            <?php
    else: ?>
                <p class="text-danger font-weight-bold" style="font-size: 18px;">
                    ❌ Customer Survey Not Completed
                </p>
            <?php
    endif; ?>
        <?php
endif; ?>
    </div>
</div>




<!-- Survey Button Section -->




                        </div>

                        
                    </div>
                    <!-- End Invoice Wrapper -->

                    <!-- Invoice Details List Wrapper -->
                    <?php if ($project_status === 'Completed' && $checklistCreated && $reportCreated && !empty($certificates)): ?>
                        <div class="bg-white details-list-wrap">
                            <div class="table-responsive">
                                <!-- Invoice List Table -->
                                <table class="invoice-list-table style-two some-center text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Doc ID</th>
                                            <th>Document Type</th>
                                            <th>Date of Creation</th>
                                            <th>Inspector</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white">
                                        <?php if ($checklistCreated): ?>
                                            <tr>
                                                <td class="bold">#<?php echo htmlspecialchars($data['checklist_no']); ?></td>
                                                <td class="bold"><?php echo htmlspecialchars($data['checklist_type']); ?> Checklist</td>
                                                <!-- For checklist row -->
                                                <td><?php echo date('F d, Y', strtotime($data['checklist_created_at'])); ?></td>
                                                <td><?php echo htmlspecialchars($data['inspected_by']); ?></td>
                                                <td>Completed</td>
                                                <td>
                                                    <a href="../document/checklist/type/view/<?php echo htmlspecialchars($data['checklist_type']); ?>.php?checklist_type=<?php echo htmlspecialchars($data['checklist_type']); ?>&checklist_no=<?php echo htmlspecialchars($data['checklist_id']); ?>" class="download-btn mr-3 bg-info">
                                                        <img src="<?php echo $url; ?>assets/img/svg/copy.svg" alt="" class="svg">
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php
    endif; ?>

                                        <?php if ($reportCreated): ?>
                                            <tr>
                                                <td class="bold">#<?php echo htmlspecialchars($data['report_no']); ?></td>
                                                <td>Report</td>
                                                <!-- For report row -->
<td><?php echo date('F d, Y', strtotime($data['report_created_at'])); ?></td>
                                                
                                                <td><?php echo htmlspecialchars($data['inspected_by']); ?></td>
                                                <td>Generated</td>
                                                <td>
                                                    <a href="../document/report/download.php?project_no=<?php echo $data['project_no']; ?>" class="download-btn mr-3">
                                                        <img src="<?php echo $url; ?>assets/img/svg/download.svg" alt="" class="svg">
                                                    </a>
                                                    <a href="../document/report/view.php?project_no=<?php echo $data['project_no']; ?>&report_no=<?php echo $data['report_no']; ?>" class="download-btn mr-3 bg-info">
                                                        <img src="<?php echo $url; ?>assets/img/svg/copy.svg" alt="" class="svg">
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php
    endif; ?>

                                        <?php foreach ($certificates as $certificate): ?>
                                            <tr>
                                                <td class="bold">#<?php echo htmlspecialchars($certificate['certificate_no']); ?></td>
                                                <td><?php echo ucfirst(htmlspecialchars($certificate['certificate_type'])); ?> Certificate</td>
                                                <td><?php echo date('F d, Y', strtotime($certificate['created_at'])); ?></td>
                                                <td><?php echo htmlspecialchars($certificate['inspector'] ?? 'N/A'); ?></td>
                                                <td>Created</td>
                                                <td>
                                                    <?php
        $certificate_types = [
            'healthcheck' => 'health-check',
            'liquidpenetrantinspection' => 'liquid-penetrant-inspection-certificate',
        ];

        foreach ($certificates as $certificate) {
            $certificate_type = isset($certificate_types[$certificate['certificate_type']])
                ? $certificate_types[$certificate['certificate_type']]
                : $certificate['certificate_type'];
?>
                                                        <a href="../document/<?php echo htmlspecialchars($certificate_type); ?>/download.php?project_no=<?php echo $data['project_no']; ?>" class="download-btn mr-3">
                                                            <img src="<?php echo $url; ?>assets/img/svg/download.svg" alt="" class="svg">
                                                        </a>
                                                        <a href="../document/<?php echo htmlspecialchars($certificate_type); ?>/view.php?project_no=<?php echo $data['project_no']; ?>" class="download-btn mr-3 bg-info">
                                                            <img src="<?php echo $url; ?>assets/img/svg/copy.svg" alt="" class="svg">
                                                        </a>
                                                        <?php
        }
?>
                                                </td>
                                            </tr>
                                        <?php
    endforeach; ?>

                                    <!-- Uploaded Documents Rows -->
                    <?php
    // Fetch uploaded documents from the database
    $project_no = $data['project_no'];
    $query = "SELECT * FROM documents WHERE project_no = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $project_no);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($document = $result->fetch_assoc()) {
            // Loop through file columns (file_1 to file_10)
            for ($i = 1; $i <= 10; $i++) {
                $fileColumn = "file_$i";
                if (!empty($document[$fileColumn])) {
                    $fileName = $document[$fileColumn];
                    $uploadedAt = $document['uploaded_at'];
                    $uploadedBy = $document['uploaded_by'];
                    $project_no = $document['project_no'];
?>
                                    <tr>
                                        <td class="bold">#<?php echo htmlspecialchars($document['id']); ?></td>
                                        <td>Uploaded Document (File <?php echo $i; ?>)</td>
                                        <td><?php echo date('F d, Y', strtotime($uploadedAt)); ?></td>
                                        <td><?php echo htmlspecialchars($uploadedBy); ?></td>
                                        <td>Uploaded</td>
                                        <td>
                                            <a href="uploads/<?php echo htmlspecialchars($project_no); ?>/<?php echo htmlspecialchars($fileName); ?>" class="download-btn mr-3" download>
                                                <img src="<?php echo $url; ?>assets/img/svg/download.svg" alt="" class="svg">
                                            </a>
                                        </td>
                                    </tr>
                                    <?php
                }
            }
        }
    }
?>
                                    </tbody>
                                </table>
                                <!-- End Invoice List Table -->
                            </div>
                        </div>
                    <?php
else: ?>
                        <div style="display: block; text-align: center; padding: 20px">
                        <?php if ($userRole === 'quality controller' && $certificateStatus === "Certificate Created"): ?>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#qcReviewModal">
        QC Controller Review
    </button>
<?php
    endif; ?>
                        </div>
                    <?php
endif; ?>
                    <!-- End Invoice Details List Wrapper -->

                    

                </div>
            </div>
        </div>
    </div>
    <!-- End Main Content -->
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.min.js"></script>
<script>
        document.addEventListener("DOMContentLoaded", function () {
    const addCertificateButton = document.querySelector("button[data-target='#addCertificateModal']");
    if (addCertificateButton) {
        if (<?php echo json_encode($enableAddCertificate); ?>) {
            addCertificateButton.removeAttribute("disabled");
        } else {
            addCertificateButton.setAttribute("disabled", "true");
        }
    }

    // Disable review button if review is already completed
    const reviewButton = document.getElementById('reviewButton');
    if (reviewButton && <?php echo json_encode($reviewStatus === 'Completed'); ?>) {
        reviewButton.style.pointerEvents = 'none';
        reviewButton.style.opacity = '0.5';
    }

    // Add Certificate handling
    const createCertBtn = document.getElementById('createCertificateBtn');
    if (createCertBtn) {
        createCertBtn.addEventListener('click', function () {
            const projectNo = document.getElementById('addCertProjectNo').value.trim();
            const checklistNo = document.getElementById('addCertChecklistNo').value.trim();
            const reportNo = document.getElementById('addCertReportNo').value.trim();
            const certificateType = document.getElementById('addCertType').value.trim();

            if (!projectNo || !checklistNo || !reportNo || !certificateType) {
                alert('Please fill in all the required fields.');
                return;
            }

            const formData = new FormData();
            formData.append('project_no', projectNo);
            formData.append('checklist_no', checklistNo);
            formData.append('report_no', reportNo);
            formData.append('certificate_type', certificateType);

            fetch('save_certificate.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Certificate saved successfully!');
                    if (data.redirect_url) {
                        window.location.href = data.redirect_url; // Open the correct page
                    }
                } else {
                    alert('Error saving certificate: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    }
});     

</script>


<script>
document.addEventListener("DOMContentLoaded", function() {
    // Review Modal handling
    $('#reviewModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var modal = $(this);
        
        // Extract all data attributes
        var projectNo = button.data('project-no');
        var checklistNo = button.data('checklist-no');
        var checklistType = button.data('checklist-type');
        var reportNo = button.data('report-no');
        var inspectedBy = button.data('inspected-by');

        // Populate the modal fields
        modal.find('#projectNo').val(projectNo);
        modal.find('#checklistNo').val(checklistNo);
        modal.find('#checklistType').val(checklistType);
        modal.find('#reportNo').val(reportNo);
        
        // Clear previous comments and reset status to Pending
        modal.find('#reviewStatus').val('Completed');
        modal.find('#commentBox').val('');
        
        // Debugging - log the values to console
        console.log('Modal data:', {
            projectNo: projectNo,
            checklistNo: checklistNo,
            checklistType: checklistType,
            reportNo: reportNo,
            inspectedBy: inspectedBy
        });
    });

    // Dynamic validation for comments
    $('#reviewStatus').change(function() {
        if ($(this).val() === 'Corrections Needed' || $(this).val() === 'Rejected') {
            $('#commentBox').prop('required', true);
        } else {
            $('#commentBox').prop('required', false);
        }
    });

    // Submit review handler
    $('#submitReview').click(function() {
        var form = $('#reviewForm');
        var formData = form.serialize();
        var reviewStatus = $('#reviewStatus').val();
        var commentBox = $('#commentBox').val();

        // Validate comments for corrections/rejection
        if ((reviewStatus === 'Corrections Needed' || reviewStatus === 'Rejected') && !commentBox.trim()) {
            alert('Please provide comments when requesting corrections or rejecting.');
            return;
        }

        $.ajax({
            url: 'submit_review.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('Review submitted successfully!');
                    $('#reviewModal').modal('hide');
                    location.reload(); // Refresh the page to show changes
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                alert('An error occurred: ' + error);
                console.error(xhr.responseText);
            }
        });
    });

    // Certificate Toggle Handler
    $(document).on('click', '#toggleCertificatesBtn', function() {
        var certificatesList = $('#certificatesList');
        var isExpanded = certificatesList.hasClass('certificates-expanded');
        
        console.log('Toggle clicked. Current expanded state:', isExpanded);
        
        if (isExpanded) {
            // Collapse certificates
            certificatesList.removeClass('certificates-expanded');
            var hiddenCount = certificatesList.find('.cert-item-hidden').length;
            $(this).html('<i class="fas fa-chevron-down mr-2"></i>Show All');
        } else {
            // Expand certificates
            certificatesList.addClass('certificates-expanded');
            $(this).html('<i class="fas fa-chevron-up mr-2"></i>Show Less');
        }
    });
});
</script>

<!-- <script>
document.addEventListener("DOMContentLoaded", function () {
    $('#reviewModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var projectNo = button.data('project-no'); // Extract info from data-* attributes
        var checklistNo = button.data('checklist-no');
        var checklistType = button.data('checklist-type');
        var reportNo = button.data('report-no');

        
        var modal = $(this);
        modal.find('#projectNo').val(projectNo);
        modal.find('#checklistNo').val(checklistNo);
        modal.find('#checklistType').val(checklistType);
        modal.find('#reportNo').val(reportNo);
    });

    document.getElementById('submitReview').addEventListener('click', function () {
        const formData = new FormData(document.getElementById('reviewForm'));

        fetch('submit_review.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Review submitted successfully!');
                $('#reviewModal').modal('hide');

                
                const reviewButton = document.getElementById('reviewButton');
                if (reviewButton) {
                    reviewButton.style.pointerEvents = 'none';
                    reviewButton.style.opacity = '0.5';
                }

                
                const addCertificateButton = document.querySelector("button[data-target='#addCertificateModal']");
                if (addCertificateButton && <?php echo json_encode($userRole === 'document controller'); ?>) {
                    addCertificateButton.removeAttribute("disabled");
                }
            } else {
                alert('Error submitting review: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
});
</script> -->




<script>
document.addEventListener("DOMContentLoaded", function() {
    // Submit QC Review handler
    $('#submitQcReview').click(function() {
        const formData = new FormData(document.getElementById('qcReviewForm'));
        
        // Get all review statuses
        const checklistStatus = $('#checklistReviewStatus').val();
        const checklistComments = $('#checklistComments').val();
        const reportStatus = $('#reportReviewStatus').val();
        const reportComments = $('#reportComments').val();
        const certificateStatus = $('#certificateReviewStatus').val();
        const certificateComments = $('#certificateComments').val();
        
        // Validate if comments are provided when corrections are needed
        if ((checklistStatus === 'Corrections Needed' && !checklistComments.trim())) {
            alert('Please provide comments for checklist corrections');
            return;
        }
        
        if ((reportStatus === 'Corrections Needed' && !reportComments.trim())) {
            alert('Please provide comments for report corrections');
            return;
        }
        
        if ((certificateStatus === 'Corrections Needed' && !certificateComments.trim())) {
            alert('Please provide comments for certificate corrections');
            return;
        }
        
        // Determine overall review status
        const overallStatus = (checklistStatus === 'Corrections Needed' || 
                             reportStatus === 'Corrections Needed' || 
                             certificateStatus === 'Corrections Needed') 
                             ? 'Corrections Needed' : 'Completed';
        
        // Add the overall review status to the form data
        formData.set('qcReviewStatus', overallStatus);
        
        // Submit via AJAX
        $.ajax({
            url: 'submit_qc_review.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('QC Review submitted successfully!');
                    $('#qcReviewModal').modal('hide');
                    
                    // Send notifications if needed
                    if (response.notifications) {
                        if (response.notifications.inspector) {
                            // Notification sent to inspector
                            console.log('Inspector notification sent');
                        }
                        if (response.notifications.document_controller) {
                            // Notification sent to document controller
                            console.log('Document controller notification sent');
                        }
                    }
                    
                    // Refresh the page or update the UI as needed
                    if (response.reviewStatus === 'Completed') {
                        // If review is completed, you might want to do something special
                        console.log('Project review completed');
                    }
                    location.reload(); // Refresh the page
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                alert('An error occurred: ' + error);
                console.error(xhr.responseText);
            }
        });
    });
});
</script>
    
<script>
    $(document).ready(function() {
        // Get the modal
        var modal = document.getElementById("qrPopup");

        // Get the QR code image
        var qrCode = document.getElementById("qrCode");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // Get the project status from PHP
        var projectStatus = "<?php echo htmlspecialchars($data['project_status']); ?>";

        // When the user clicks on the QR code, redirect to verify.php if project status is "Completed"
        qrCode.onclick = function() {
            if (projectStatus === "Completed") {
                window.location.href = "verify.php";
            } else {
                alert("QR code scanning is invalid");
            }
        }

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        // Handle the submit button click
        $("#submitStickerNo").click(function() {
            var stickerNo = $("#stickerNo").val();
            if (stickerNo) {
                // Redirect to the form page with the sticker number as a query parameter
                window.location.href = "form.php?stickerNo=" + stickerNo;
            } else {
                alert("Please enter a sticker number.");
            }
        });
    });
</script>


<script>
$(document).ready(function() {
    // Update file input label and show selected files with preview
    $('#documentUpload').on('change', function() {
        var files = $(this)[0].files;
        var fileList = $('#fileList');
        fileList.empty();
        
        if (files.length === 0) {
            $('.custom-file-label').text('Choose files');
            return;
        }
        
        if (files.length > 10) {
            alert('You can upload a maximum of 10 files at once.');
            $(this).val('');
            $('.custom-file-label').text('Choose files');
            return;
        }
        
        // Create preview container
        var previewContainer = $('<div class="file-preview-container mt-3"></div>');
        fileList.append(previewContainer);
        
        // Check each file
        var validFilesCount = 0;
        var maxSize = 5 * 1024 * 1024; // 5MB
        
        for (var i = 0; i < files.length; i++) {
            var file = files[i];
            var filePreview = $('<div class="file-preview mb-2 p-2 border rounded"></div>');
            var isValid = true;
            var errorMessage = '';
            
            // File info
            var fileInfo = $('<div class="file-info"></div>');
            fileInfo.append('<strong>' + (i+1) + '. ' + file.name + '</strong>');
            fileInfo.append('<span class="file-size ml-2">(' + formatFileSize(file.size) + ')</span>');
            
            // Check file type
            var allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
            if (!allowedTypes.includes(file.type) && 
                !file.name.match(/\.(jpg|jpeg|png|pdf)$/i)) {
                errorMessage = 'Invalid file type';
                isValid = false;
            }
            
            // Check file size
            if (file.size > maxSize) {
                errorMessage = 'File too large (max 5MB)';
                isValid = false;
            }
            
            // Add status icon
            var statusIcon = $('<span class="status-icon ml-2"></span>');
            if (isValid) {
                statusIcon.html('<i class="fas fa-check-circle text-success"></i>');
                validFilesCount++;
            } else {
                statusIcon.html('<i class="fas fa-times-circle text-danger"></i> ' + errorMessage);
            }
            
            fileInfo.append(statusIcon);
            filePreview.append(fileInfo);
            
            // Add thumbnail preview for images
            if (file.type.match('image.*') && isValid) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var imgPreview = $('<div class="image-preview mt-2 text-center"><img src="' + e.target.result + 
                                     '" class="img-thumbnail" style="max-height:100px;"></div>');
                    filePreview.append(imgPreview);
                }
                reader.readAsDataURL(file);
            } else if (file.type === 'application/pdf' && isValid) {
                filePreview.append('<div class="pdf-preview mt-2 text-center"><i class="far fa-file-pdf text-danger fa-3x"></i></div>');
            }
            
            previewContainer.append(filePreview);
        }
        
        // Update label
        if (validFilesCount === 0) {
            $('.custom-file-label').text('Choose files');
        } else if (validFilesCount === 1) {
            $('.custom-file-label').text('1 file selected');
        } else {
            $('.custom-file-label').text(validFilesCount + ' files selected');
        }
        
        // Show validation summary
        if (validFilesCount < files.length) {
            fileList.append('<div class="alert alert-warning mt-2">Some files are invalid and won\'t be uploaded</div>');
        }
    });
    
    // Format file size
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    // Upload button click handler
    $("#uploadBtn").click(function() {
        var files = $('#documentUpload')[0].files;
        if (files.length === 0) {
            alert('Please select at least one file to upload');
            return;
        }
        
        // Create loading state
        var btn = $(this);
        var originalText = btn.html();
        btn.html('<i class="fas fa-spinner fa-spin"></i> Uploading...').prop('disabled', true);
        
        // Show progress container
        var progressContainer = $('<div class="upload-progress-container mt-3"></div>');
        $('#fileList').append(progressContainer);
        
        var formData = new FormData($("#uploadForm")[0]);
        
        // Add debug info
        console.log("FormData contents:");
        for (var pair of formData.entries()) {
            console.log(pair[0] + ': ', pair[1]);
        }
        
        $.ajax({
            url: "upload_documents.php",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = Math.round((evt.loaded / evt.total) * 100);
                        progressContainer.html('<div class="progress">' +
                            '<div class="progress-bar progress-bar-striped progress-bar-animated" ' +
                            'style="width:' + percentComplete + '%">' + percentComplete + '%</div></div>' +
                            '<div class="upload-status small mt-2">Uploading files...</div>');
                    }
                }, false);
                return xhr;
            },
            success: function(response) {
                console.log("Server response:", response); // Debug response
                
                try {
                    var data = typeof response === 'object' ? response : JSON.parse(response);
                    
                    if (data.success) {
                        progressContainer.html('<div class="alert alert-success">' + 
                            data.message + ' ' + data.uploaded_count + ' files uploaded successfully.</div>');
                        
                        // Refresh page after delay
                        setTimeout(function() {
                            $('#uploadModal').modal('hide');
                            location.reload();
                        }, 1500);
                    } else {
                        progressContainer.html('<div class="alert alert-danger">Error: ' + 
                            (data.message || 'Unknown error occurred') + '</div>');
                        btn.html(originalText).prop('disabled', false);
                    }
                } catch (e) {
                    console.error("Error parsing response:", e);
                    progressContainer.html('<div class="alert alert-danger">Error parsing server response: ' + 
                        response + '</div>');
                    btn.html(originalText).prop('disabled', false);
                }
            },
            error: function(xhr, status, error) {
                console.error("Upload error:", status, error);
                progressContainer.html('<div class="alert alert-danger">Upload failed: ' + 
                    error + ' (Status: ' + xhr.status + ')</div>');
                btn.html(originalText).prop('disabled', false);
            }
        });
    });
    
    // Reset form when modal is closed
    $('#uploadModal').on('hidden.bs.modal', function() {
        $('#uploadForm')[0].reset();
        $('.custom-file-label').text('Choose files');
        $('#fileList').empty();
        $("#uploadBtn").prop('disabled', false).html('Upload');
    });
});
</script>

<!-- JavaScript for Lightbox functionality -->

<!--<script>-->
<!--$(document).on('click', '[data-toggle="lightbox"]', function(event) {-->
<!--    event.preventDefault();-->
<!--    $(this).ekkoLightbox({-->
<!--        alwaysShowClose: true,-->
<!--        showArrows: true,-->
<!--        wrapping: false-->
<!--    });-->
<!--});-->
<!--</script>-->


<!--<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>-->
<!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>-->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.min.js"></script>-->

<!--<script>-->
<!--    $(document).on('click', '[data-toggle="lightbox"]', function(event) {-->
<!--        event.preventDefault();-->
<!--        $(this).ekkoLightbox();-->
<!--    });-->
<!--</script>-->

    

<!-- Modals relocated to bottom for better compatibility with backdrop-filters -->

<!-- Add Certificate Modal -->
<div class="modal fade" id="addCertificateModal" tabindex="-1" role="dialog" aria-labelledby="addCertificateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCertificateModalLabel">Add Certificate</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="addCertProjectNo">Project ID</label>
                    <input type="text" class="form-control" id="addCertProjectNo" value="<?php echo htmlspecialchars($data['project_no']); ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="addCertChecklistNo">Checklist No</label>
                    <input type="text" class="form-control" id="addCertChecklistNo" value="<?php echo htmlspecialchars($data['checklist_no']); ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="addCertReportNo">Report No</label>
                    <input type="text" class="form-control" id="addCertReportNo" value="<?php echo htmlspecialchars($data['report_no']); ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="addCertType">Certificate Type</label>
                    <select class="form-control" id="addCertType" required>
                        <option value="" disabled selected>Select Certificate Type</option>
                        <option value="healthcheck">Offshore Crane Health Check</option>
                        <option value="loadtestwithload">Thorough Examination </option>
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
<div class="modal fade" id="reviewModal" tabindex="-1" role="dialog" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reviewModalLabel">Review Checklist</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="reviewForm">
                    <div class="form-group">
                        <label for="projectNo">Project No</label>
                        <input type="text" class="form-control" id="projectNo" name="projectNo" readonly>
                    </div>
                    <div class="form-group">
                        <label for="checklistNo">Checklist No</label>
                        <input type="text" class="form-control" id="checklistNo" name="checklistNo" readonly>
                    </div>
                    <div class="form-group">
                        <label for="checklistType">Checklist Type</label>
                        <input type="text" class="form-control" id="checklistType" name="checklistType" readonly>
                    </div>
                    <div class="form-group">
                        <label for="reportNo">Report No</label>
                        <input type="text" class="form-control" id="reportNo" name="reportNo" readonly>
                    </div>
                    <div class="form-group">
                        <label for="reviewStatus">Review Status</label>
                        <select class="form-control" id="reviewStatus" name="reviewStatus" required>
                            <option value="Completed" selected>Completed</option>
                            <option value="Pending">Pending</option>
                            <option value="Corrections Needed">Corrections Needed</option>
                            <option value="Rejected">Rejected</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="commentBox">Comments</label>
                        <textarea class="form-control" id="commentBox" name="commentBox" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="submitReview">Submit</button>
            </div>
        </div>
    </div>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Documents</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="uploadForm" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="documentUpload">Select Documents (Max 10, JPG/PNG/PDF)</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="documentUpload" name="documents[]" multiple accept=".jpg,.jpeg,.png,.pdf" required>
                            <label class="custom-file-label" for="documentUpload" data-browse="Browse">Choose files</label>
                        </div>
                        <small class="form-text text-muted">
                            Hold Ctrl (or Cmd on Mac) to select multiple files, or tap and select multiple files on mobile
                        </small>
                        <div id="fileList" class="mt-2 small"></div>
                    </div>
                    <input type="hidden" name="project_no" value="<?php echo htmlspecialchars($data['project_no']); ?>">
                    <input type="hidden" name="uploaded_by" value="<?php echo htmlspecialchars($userRole); ?>">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="uploadBtn">Upload</button>
            </div>
        </div>
    </div>
</div>

<!-- QC Controller Review Modal -->
<div class="modal fade" id="qcReviewModal" tabindex="-1" role="dialog" aria-labelledby="qcReviewModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="qcReviewModalLabel">QC Controller Review</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="qcReviewForm">
                    <div class="form-group">
                        <label for="qcProjectNo">Project No</label>
                        <input type="text" class="form-control" id="qcProjectNo" name="qcProjectNo" value="<?php echo htmlspecialchars($data['project_no']); ?>" readonly>
                    </div>

                    <!-- Hidden fields for additional data -->
                    <input type="hidden" id="qcChecklistNo" name="qcChecklistNo" value="<?php echo htmlspecialchars($data['checklist_no'] ?? ''); ?>">
                    <input type="hidden" id="qcChecklistType" name="qcChecklistType" value="<?php echo htmlspecialchars($data['checklist_type'] ?? ''); ?>">
                    <input type="hidden" id="qcReportNo" name="qcReportNo" value="<?php echo htmlspecialchars($data['report_no'] ?? ''); ?>">
                    <input type="hidden" id="qcCertificateType" name="qcCertificateType" value="<?php echo htmlspecialchars($data['certificate_type'] ?? ''); ?>">
                    <input type="hidden" id="qcReviewStatus" name="qcReviewStatus" value="In Review">
                    
                    <!-- Review Sections -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Checklist Review</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="checklistReviewStatus">Status</label>
                                <select class="form-control" id="checklistReviewStatus" name="checklistReviewStatus" required>
                                    <option value="Approved">Approved</option>
                                    <option value="Corrections Needed">Corrections Needed</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="checklistComments">Comments</label>
                                <textarea class="form-control" id="checklistComments" name="checklistComments" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Report Review</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="reportReviewStatus">Status</label>
                                <select class="form-control" id="reportReviewStatus" name="reportReviewStatus" required>
                                    <option value="Approved">Approved</option>
                                    <option value="Corrections Needed">Corrections Needed</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="reportComments">Comments</label>
                                <textarea class="form-control" id="reportComments" name="reportComments" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Certificate Review</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="certificateReviewStatus">Status</label>
                                <select class="form-control" id="certificateReviewStatus" name="certificateReviewStatus" required>
                                    <option value="Approved">Approved</option>
                                    <option value="Corrections Needed">Corrections Needed</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="certificateComments">Comments</label>
                                <textarea class="form-control" id="certificateComments" name="certificateComments" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <input type="hidden" name="qcReviewer" value="<?php echo $_SESSION['username']; ?>">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="submitQcReview">Submit Review</button>
            </div>
        </div>
    </div>
</div>

<?php
include_once('../inc/footer.php');
?>
</body>
</html>