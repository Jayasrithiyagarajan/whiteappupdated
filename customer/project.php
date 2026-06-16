<?php
session_start();
include_once('../file/config.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}
 
// Check if the user has the 'customer' role
if ($_SESSION['role'] !== 'customer') {
    header("Location: ../index.php");
    exit();
}

// Fetch customer details from database
$customer_name = $_SESSION['username'];
$sql = "SELECT * FROM customers WHERE customer_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $customer_name);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $customer = $result->fetch_assoc();
    $cus_name = $customer['customer_name'];
    $profilePhoto = !empty($customer['profile_photo']) ? $customer['profile_photo'] : $url . 'assets/img/media/profile-pic.jpg';
    $signaturePhoto = !empty($customer['signature_photo']) ? $customer['signature_photo'] : '';
    $email = $customer['email'];
    $mobile = $customer['mobile'];
    $address = $customer['address'];
    $city = $customer['city'];
    $rep_name = $customer['rep_name'];
    $created_at = date('F j, Y', strtotime($customer['created_at']));
    $cus_id = $customer['cus_id'];
} else {
    echo "Customer not found";
    exit();
}

include_once('../inc/customer-option.php');

// Fetch project details
// $sql = "SELECT p.project_no, p.creation_date, p.project_status, 
//               p.equipment_type, p.equipment_location, p.inspector_name,
//               p.checklist_status, p.customer_name 
//         FROM project_info p
//         WHERE p.customer_name = ?";

$sql = "SELECT p.project_no, p.creation_date, p.project_status, 
               p.equipment_type, p.equipment_location, p.inspector_name,
               p.checklist_status, p.customer_name 
        FROM project_info p
        WHERE p.customer_name = ?
        ORDER BY CAST(SUBSTRING(p.project_no, 5) AS UNSIGNED) DESC";


$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $cus_name);
$stmt->execute();
$result = $stmt->get_result();
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

/* Apply modern font to main-content3 container */
.main-content3 {
    font-family: 'Plus Jakarta Sans', sans-serif !important;
    background: #f8fafc;
    padding: 30px 0;
}

/* Card Styling */
.main-content3 .card {
    background: #ffffff;
    border: 1px solid rgba(226, 232, 240, 0.8) !important;
    border-radius: 20px !important;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.03), 0 8px 10px -6px rgba(0, 0, 0, 0.03) !important;
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    margin-bottom: 24px;
}

.main-content3 .card:hover {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
}

/* Cover Image Styling */
.main-content3 .cover-img {
    position: relative;
    border-radius: 20px 20px 0 0;
    overflow: hidden;
    height: 220px;
}
.main-content3 .cover-img img.w-100 {
    height: 100%;
    object-fit: cover;
    filter: brightness(0.95);
}

/* Upload Button Styling */
.main-content3 .upload-button {
    position: absolute;
    bottom: 16px;
    right: 16px;
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.5);
    padding: 8px 16px;
    border-radius: 12px;
    font-size: 13px;
    font-weight: 600;
    color: #1e293b;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}
.main-content3 .upload-button:hover {
    background: #ffffff;
    transform: translateY(-1px);
}
.main-content3 .upload-button input.file-input {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
}

/* User Profile Nav Card */
.main-content3 .user-profile-nav {
    padding: 24px;
    background: #ffffff;
    border-radius: 20px;
}

/* Profile Info */
.main-content3 .profile-info {
    position: relative;
}
.main-content3 .profile-pic {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    border: 3px solid #ffffff;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    overflow: hidden;
    margin-top: 0px !important;
    background: #ffffff;
    z-index: 2;
    transition: transform 0.3s ease;
}
.main-content3 .profile-pic:hover {
    transform: scale(1.05);
}
.main-content3 .profile-pic img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.main-content3 .profile-info h3 {
    font-size: 22px;
    font-weight: 700;
    color: #0f172a;
    margin: 0 !important;
}

/* Navigation Tabs */
.main-content3 .profile-nav-tabs {
    border-bottom: none !important;
    gap: 8px;
    display: flex;
    align-items: center;
}
.main-content3 .profile-nav-tabs li {
    margin: 0;
}
.main-content3 .profile-nav-tabs li .chat {
    background-color: transparent !important;
    width: auto !important;
    height: auto !important;
    display: inline-flex !important;
}
.main-content3 .profile-nav-tabs a {
    display: inline-flex !important;
    align-items: center;
    justify-content: center;
    height: 42px !important;
    line-height: 1.2 !important;
    padding: 0 20px !important;
    border-radius: 12px !important;
    font-size: 14px;
    font-weight: 600;
    color: #64748b !important;
    transition: all 0.2s ease;
    border: none !important;
    background: transparent;
    text-decoration: none;
}
.main-content3 .profile-nav-tabs a:hover {
    color: #4f46e5 !important;
    background: rgba(79, 70, 229, 0.05) !important;
}
.main-content3 .profile-nav-tabs a.active, 
.main-content3 .profile-nav-tabs a.p_nav-link.active {
    color: #ffffff !important;
    background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%) !important;
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25) !important;
}

.main-content3 .p_nav-link:after,
.main-content3 .p_nav-link:before {
    display: none !important;
}

/* Dropdown Menu style */
.main-content3 .dropdown-button .menu-icon span {
    background-color: #64748b !important;
}
.main-content3 .dropdown-menu {
    border-radius: 12px;
    border: 1px solid rgba(0,0,0,0.08);
    box-shadow: 0 10px 20px rgba(0,0,0,0.05);
    padding: 6px;
}
.main-content3 .dropdown-item, .main-content3 .dropdown-menu a {
    display: block;
    padding: 10px 16px;
    font-size: 14px;
    color: #334155;
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.2s;
}
.main-content3 .dropdown-menu a:hover {
    background-color: #f1f5f9;
    color: #4f46e5;
}

/* Profile Completion Card */
.main-content3 .profile-completion {
    background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
    border: 1px solid rgba(79, 70, 229, 0.15);
    border-radius: 20px;
    padding: 24px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.main-content3 .profile-completion h4 {
    color: #1e1b4b;
    font-weight: 700;
    font-size: 18px;
}
.main-content3 .profile-completion p {
    color: #4338ca;
    font-weight: 500;
}
.main-content3 .ProgressBar-circle {
    stroke: #4f46e5 !important;
    stroke-width: 12px;
}
.main-content3 .ProgressBar-background {
    stroke: rgba(79, 70, 229, 0.1) !important;
    stroke-width: 12px;
}
.main-content3 .ProgressBar-percentage {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-weight: 800;
    color: #4f46e5 !important;
    font-size: 24px !important;
}

/* Edit Profile Circle Button */
.main-content3 .btn-circle {
    width: 44px;
    height: 44px;
    background: #ffffff;
    border: 1px solid rgba(79, 70, 229, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    transition: all 0.2s ease;
}
.main-content3 .btn-circle:hover {
    background: #4f46e5;
    border-color: #4f46e5;
    transform: scale(1.05);
}
.main-content3 .btn-circle:hover img.svg {
    filter: brightness(0) invert(1);
}

/* Table Card Header */
.main-content3 .card-body h4.font-20 {
    font-size: 20px;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 8px;
}

/* Search input container */
.main-content3 .table-controls {
    margin-left: 24px;
    margin-top: 10px;
}
.main-content3 #searchInput {
    background: #f8fafc;
    border: 1px solid #cbd5e1;
    border-radius: 12px !important;
    height: 44px;
    font-size: 14px;
    padding-left: 42px !important;
    padding-right: 36px !important;
    color: #1e293b;
    font-weight: 500;
    transition: all 0.2s ease;
}
.main-content3 #searchInput:focus {
    background: #ffffff;
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

/* Tables styling */
.main-content3 .table-responsive {
    padding: 0 24px 24px 24px;
}
.main-content3 #job-table {
    border-collapse: separate !important;
    border-spacing: 0 8px !important;
    width: 100% !important;
}
.main-content3 #job-table thead tr th {
    background: #f8fafc;
    color: #475569;
    font-weight: 700;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border: none !important;
    padding: 14px 16px;
}
.main-content3 #job-table tbody tr {
    background: #ffffff;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    border-radius: 12px;
    transition: all 0.2s ease;
}
.main-content3 #job-table tbody tr:hover {
    background: #f8fafc;
    transform: translateY(-1px);
}
.main-content3 #job-table tbody tr td {
    padding: 16px;
    vertical-align: middle;
    border-top: 1px solid #e2e8f0 !important;
    border-bottom: 1px solid #e2e8f0 !important;
    color: #334155;
    font-size: 13.5px;
}
.main-content3 #job-table tbody tr td:first-child {
    border-left: 1px solid #e2e8f0 !important;
    border-top-left-radius: 12px;
    border-bottom-left-radius: 12px;
    font-weight: 700;
    color: #4f46e5;
}
.main-content3 #job-table tbody tr td:last-child {
    border-right: 1px solid #e2e8f0 !important;
    border-top-right-radius: 12px;
    border-bottom-right-radius: 12px;
}

/* Action view buttons */
.main-content3 .btn-sm {
    border-radius: 10px !important;
    font-weight: 600 !important;
    font-size: 12px !important;
    padding: 8px 16px !important;
    transition: all 0.2s ease;
    border: none !important;
}
.main-content3 .btn-primary {
    background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%) !important;
    color: #ffffff !important;
    box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.1), 0 2px 4px -1px rgba(79, 70, 229, 0.06);
}
.main-content3 .btn-primary:hover {
    background: linear-gradient(135deg, #4338ca 0%, #2e2882 100%) !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2) !important;
}
.main-content3 .btn-info {
    background: #0f172a !important;
    color: #ffffff !important;
    box-shadow: 0 4px 6px -1px rgba(15, 23, 42, 0.1);
}
.main-content3 .btn-info:hover {
    background: #1e293b !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.2) !important;
}

/* Status badging */
.main-content3 .bg-success-light {
    background-color: #dcfce7 !important;
    color: #15803d !important;
    padding: 6px 12px !important;
    border-radius: 8px !important;
    font-size: 12px !important;
    font-weight: 700 !important;
    display: inline-block;
    border: 1px solid rgba(21, 128, 61, 0.1) !important;
}
.main-content3 .bg-danger-light {
    background-color: #fee2e2 !important;
    color: #b91c1c !important;
    padding: 6px 12px !important;
    border-radius: 8px !important;
    font-size: 12px !important;
    font-weight: 700 !important;
    display: inline-block;
    border: 1px solid rgba(185, 28, 28, 0.1) !important;
}

/* DataTables pagination styling overrides */
.main-content3 .bottom {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 20px;
    padding-top: 15px;
    border-top: 1px solid #f1f5f9;
}
.main-content3 .dataTables_info {
    font-size: 13px;
    color: #64748b;
    font-weight: 500;
}
.main-content3 .dataTables_paginate {
    display: flex;
    gap: 4px;
}
.main-content3 .dataTables_paginate .paginate_button {
    padding: 8px 16px !important;
    border-radius: 10px !important;
    background: #ffffff !important;
    border: 1px solid #cbd5e1 !important;
    color: #64748b !important;
    font-size: 13px !important;
    font-weight: 600 !important;
    cursor: pointer;
    transition: all 0.2s ease;
}
.main-content3 .dataTables_paginate .paginate_button:hover {
    background: #f1f5f9 !important;
    color: #0f172a !important;
}
.main-content3 .dataTables_paginate .paginate_button.current {
    background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%) !important;
    color: #ffffff !important;
    border-color: #4f46e5 !important;
    box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.15);
}
.main-content3 .dataTables_paginate .paginate_button.disabled {
    background: #f8fafc !important;
    color: #cbd5e1 !important;
    border-color: #e2e8f0 !important;
    cursor: not-allowed;
}

/* Modal styling overrides */
#certificateModal .modal-content {
    border-radius: 20px !important;
    border: none !important;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
}
#certificateModal .modal-header {
    border-bottom: 1px solid #e2e8f0 !important;
    padding: 24px !important;
}
#certificateModal .modal-title {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-weight: 800;
    color: #0f172a;
    font-size: 18px;
}
#certificateModal .modal-header .close {
    font-size: 24px;
    color: #64748b;
    outline: none;
}
#certificateModal .modal-body {
    padding: 24px !important;
}
#certificateModal .table {
    margin-bottom: 0;
}
#certificateModal .table thead th {
    background: #f8fafc;
    color: #475569;
    font-weight: 700;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 2px solid #e2e8f0 !important;
    border-top: none;
    padding: 12px;
}
#certificateModal .table tbody td {
    padding: 14px 12px;
    color: #334155;
    font-size: 13.5px;
    border-bottom: 1px solid #f1f5f9;
}
#certificateModal .modal-footer {
    border-top: 1px solid #e2e8f0 !important;
    padding: 16px 24px !important;
}
#certificateModal .modal-footer .btn-secondary {
    background: #f1f5f9 !important;
    color: #475569 !important;
    border-radius: 10px !important;
    font-weight: 600;
    padding: 10px 20px !important;
    border: none !important;
}
#certificateModal .modal-footer .btn-secondary:hover {
    background: #e2e8f0 !important;
    color: #0f172a !important;
}
</style>

<!-- Main Content -->
<div class="main-content3">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card mx-2 mx-lg-4 mx-xl-5">
                    <!-- User Profile Image -->
                    <div class="user-profile-img">
                        <div class="cover-img">
                            <img src="<?php echo $url; ?>assets/img/media/cover.jpg" class="w-100" alt="">
                            <div class="upload-button">
                                <img src="<?php echo $url; ?>assets/img/svg/gallery.svg" alt="" class="svg mr-2">
                                <span>Upload Photo</span>
                                <input class="file-input" type="file" id="fileUpload3" accept="image/*">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mx-2 mx-lg-4 mx-xl-5">
                    <div class="card mt-1">
                        <!-- User Profile Nav -->
                        <div class="user-profile-nav d-flex justify-content-xl-between align-items-xl-center flex-column flex-xl-row">
                            <div class="profile-info d-flex align-items-center">
                                <div class="profile-pic mr-3">
                                    <img src="<?php echo $profilePhoto . '?v=' . time(); ?>" alt="Profile Picture">
                                </div>
                                <div>
                                    <h3><?php echo htmlspecialchars($cus_name); ?></h3>
                                    <p class="font-14">Customer</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mt-3 mt-xl-0">
                                <ul class="nav profile-nav-tabs">
                                    <li>
                                        <a class="active pr-0 pl-2 pl-xl-30">
                                            <span class="chat">
                                                <img src="<?php echo $url; ?>assets/img/svg/chat-icon.svg" alt="" class="svg">
                                            </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="p_nav-link has-before active" href="../dashboard/customer_new.php">About</a>
                                    </li>
                                    <li>
                                        <a class="active p_nav-link has-before" href="../customer/project.php?cusid=<?php echo urlencode($customer['cus_id']); ?>">Projects</a>
                                    </li>
                                    <li>
                                        <a href="../dashboard/customer_kpi_dashboard.php">KPI Analytics</a>
                                    </li>
                                <div class="px-3">
                                    <div class="dropdown-button">
                                        <a href="#" class="d-flex align-items-center" data-toggle="dropdown">
                                            <div class="menu-icon style--two w-auto justify-content-center mr-0">
                                                <span></span>
                                                <span></span>
                                                <span></span>
                                            </div>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a href="">User Dashboard</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-30">
                        <!-- Profile Completion -->
                        <div class="profile-completion d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="progress_22 mr-3">
                                    <div class="ProgressBar-wrap2 position-relative">
                                        <div class="ProgressBar ProgressBar_22" data-progress="86">
                                            <svg class="ProgressBar-contentCircle" viewBox="0 0 200 200">
                                                <circle transform="rotate(-90, 100, 100)" class="ProgressBar-background" cx="100" cy="100" r="85" />
                                                <circle transform="rotate(-90, 100, 100)" class="ProgressBar-circle" cx="100" cy="100" r="85" />
                                            </svg>
                                            <span class="ProgressBar-percentage ProgressBar-percentage--count"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="">
                                    <h4 class="c2 mb-1">Profile Completion</h4>
                                    <p class="font-14">Member since: <?php echo $created_at; ?></p>
                                </div>
                            </div>
                            <div class="edit-profile-btn pr-1">
                                <a href="edit-customer.php" class="btn-circle">
                                    <img src="<?php echo $url; ?>assets/img/svg/writing.svg" alt="" class="svg">
                                </a>
                            </div>
                        </div>

                        <!-- Card -->
                        <div class="card mb-30">
                            <div class="card-body">
                                <div class="d-sm-flex justify-content-between align-items-center">
                                    <h4 class="font-20">Job List</h4>
                                </div>
                            </div>
                            
                            <div class="table-controls mb-3 position-relative" style="max-width: 300px;">
                                <input type="text" id="searchInput" class="form-control rounded-3 pl-5" placeholder="Search Projects..."/>
                                <span class="position-absolute" style="top: 50%; left: 12px; transform: translateY(-50%); pointer-events: none;">
                                    <i class="fa fa-search text-muted"></i>
                                </span>
                                <span id="clearSearch" class="position-absolute" style="top: 50%; right: 12px; transform: translateY(-50%); cursor: pointer;">
                                    <i class="fa fa-times text-muted"></i>
                                </span>
                            </div>

                            <div class="table-responsive">
                                <table id="job-table" class="order-list-table style--three table-centered text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Project ID</th>
                                            <th>Start Date</th>
                                            <th>Equipment ID</th>
                                            <th>Equipment Type</th>
                                            <th>Equipment Serial No</th>
                                            <th>Inspector</th>
                                            <th>Inspection Date</th>
                                            <th>Sticker No</th>
                                            <th>Expiry Date</th>
                                            <th>Checklist</th>
                                            <th>Report</th>
                                            <th>Certificate</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($result->num_rows > 0): ?>
                                            <?php while ($row = $result->fetch_assoc()): ?>
                                                <?php
                                                $project_no = $row['project_no'];
                                                $details_query = "
                                                    SELECT 
                                                        p.project_no, p.project_status, p.checklist_status, p.report_status, p.equipment_id, p.equipment_type, p.certificatestatus,
                                                        c.checklist_no, c.checklist_type, c.checklist_id, c.inspection_date, c.sticker_no, c.equipmenttype,
                                                        r.report_no, r.equipment_serial_no, r.next_inspection_due_date,
                                                        (
                                                            SELECT COUNT(*) 
                                                            FROM (
                                                                SELECT certificate_no FROM crane_health_check_certificate WHERE project_no = ?
                                                                UNION ALL
                                                                SELECT certificate_no FROM loadtest_certificate WHERE project_no = ?
                                                                UNION ALL
                                                                SELECT certificate_no FROM mobile_crane_loadtest WHERE project_no = ?
                                                                UNION ALL
                                                                SELECT certificate_no FROM withload WHERE project_no = ?
                                                                UNION ALL
                                                                SELECT certificate_no FROM lifting_gear_certificates WHERE project_no = ?
                                                                UNION ALL
                                                                SELECT certificate_no FROM mpi_certificates WHERE project_no = ?
                                                                UNION ALL
                                                                SELECT certificate_no FROM eddy_current_inspection WHERE project_no = ?
                                                                UNION ALL
                                                                SELECT certificate_no FROM liquid_penetrant_inspection WHERE project_no = ?
                                                                UNION ALL
                                                                SELECT certificate_no FROM rocking_test_certificate WHERE project_no = ?
                                                            ) AS certificates
                                                        ) AS certificate_count
                                                    FROM project_info p
                                                    LEFT JOIN checklist_information c ON p.project_no = c.project_no
                                                    LEFT JOIN reports r ON p.project_no = r.project_no
                                                    WHERE p.project_no = ?";
                                                
                                                $stmt_details = $conn->prepare($details_query);

$stmt_details->bind_param(
    "ssssssssss", // ✅ 10 "s"
    $project_no,  // crane_health_check_certificate
    $project_no,  // loadtest_certificate
    $project_no,  // mobile_crane_loadtest
    $project_no,  // withload
    $project_no,  // lifting_gear_certificates
    $project_no,  // mpi_certificates
    $project_no,  // eddy_current_inspection
    $project_no,  // liquid_penetrant_inspection
    $project_no,  // rocking_test_certificate
    $project_no   // final WHERE p.project_no = ?
);

$stmt_details->execute();

                                                $details_result = $stmt_details->get_result();
                                                
                                                if ($details_result->num_rows > 0):
                                                    $details = $details_result->fetch_assoc();
                                                ?>
                                                <tr>
                                                    <td data-order="<?php echo intval(preg_replace('/\D/', '', $row["project_no"])); ?>">
    <?php echo "#" . htmlspecialchars($row["project_no"]); ?>
</td>

                                                    
                                                    
                                                    <td><?php echo date("d M Y", strtotime($row["creation_date"])); ?></td>
                                                    <td>
                                                        <?php if (!empty($details['equipment_id'])) {
                                                            echo htmlspecialchars($details['equipment_id']);
                                                        } else {
                                                            echo '<span class="text-muted">N/A</span>';
                                                        } ?>
                                                    </td>
                                                    <td>
                                                        <?php if (!empty($details['equipmenttype'])) {
                                                            echo htmlspecialchars($details['equipmenttype']);
                                                        } else {
                                                            echo '<span class="text-muted">N/A</span>';
                                                        } ?>
                                                    </td>
                                                    <td>
                                                        <?php if (!empty($details['equipment_serial_no'])) {
                                                            echo htmlspecialchars($details['equipment_serial_no']);
                                                        } else {
                                                            echo '<span class="text-muted">N/A</span>';
                                                        } ?>
                                                    </td>
                                                    
                                                    <td><?php echo htmlspecialchars($row['inspector_name'] ?: 'N/A'); ?></td>
                                                    
                                                    <td>
                                                        <?php if (!empty($details['inspection_date'])) {
                                                            echo htmlspecialchars($details['inspection_date']);
                                                        } else {
                                                            echo '<span class="text-muted">N/A</span>';
                                                        } ?>
                                                    </td>
                                                    
                                                     <td>
                                                        <?php if (!empty($details['sticker_no'])) {
                                                            echo htmlspecialchars($details['sticker_no']);
                                                        } else {
                                                            echo '<span class="text-muted">N/A</span>';
                                                        } ?>
                                                    </td>
                                                    
                                                    <td>
                                                        <?php if (!empty($details['next_inspection_due_date'])) {
                                                            echo htmlspecialchars($details['next_inspection_due_date']);
                                                        } else {
                                                            echo '<span class="text-muted">N/A</span>';
                                                        } ?>
                                                    </td>
                                                    
                                                    
                                                    
                                                    
                                                    
                                                    
                                                    
                                                    <td>
                                                        <?php if ($row["project_status"] === 'Completed' && $details['checklist_no']) { ?>
                                                            <a href="../document/checklist/type/view/<?php echo htmlspecialchars($details['checklist_type']); ?>.php?checklist_type=<?php echo htmlspecialchars($details['checklist_type']); ?>&checklist_no=<?php echo htmlspecialchars($details['checklist_id']); ?>" 
                                                               class="btn btn-sm btn-primary" target="_blank">
                                                                View Checklist
                                                            </a>
                                                        <?php } else { ?>
                                                            <span class="text-muted">
                                                                <?php echo $row["project_status"] === 'Completed' ? 'Not Created' : 'Not Available'; ?>
                                                            </span>
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($row["project_status"] === 'Completed' && $details['report_no']) { ?>
                                                            <a href="../document/report/view.php?project_no=<?php echo $project_no; ?>&report_no=<?php echo $details['report_no']; ?>"  
                                                               class="btn btn-sm btn-primary" target="_blank">
                                                                View Report
                                                            </a>
                                                        <?php } else { ?>
                                                            <span class="text-muted">
                                                                <?php echo $row["project_status"] === 'Completed' ? 'Not Generated' : 'Not Available'; ?>
                                                            </span>
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($row["project_status"] === 'Completed' && $details['certificate_count'] > 0) { ?>
                                                            <button class="btn btn-sm btn-info view-certificates" 
                                                                    data-project-no="<?php echo $project_no; ?>">
                                                                View Certificates (<?php echo $details['certificate_count']; ?>)
                                                            </button>
                                                        <?php } else { ?>
                                                            <span class="text-muted">
                                                                <?php echo $row["project_status"] === 'Completed' ? 'Not Created' : 'Not Available'; ?>
                                                            </span>
                                                        <?php } ?>
                                                    </td>
                                                    <td class="status-btn">
                                                        <a href="#" class="btn s_alert 
                                                            <?php echo strtolower($row["project_status"]) === 'completed' 
                                                                ? 'bg-success-light text-success' 
                                                                : 'bg-danger-light text-danger'; ?> 
                                                            mr-3 mr-sm-4 mb-10">
                                                            <?php echo htmlspecialchars($row["project_status"]); ?>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php endif; ?>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <tr><td colspan="10" class="text-center">No projects found.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Certificate Modal -->
                        <div class="modal fade" id="certificateModal" tabindex="-1" role="dialog" aria-labelledby="certificateModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="certificateModalLabel">Certificates for Project: <span id="modalProjectNo"></span></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Certificate Type</th>
                                                        <th>Certificate No</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="certificateTableBody">
                                                    <!-- Certificate data will be loaded here -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once('../inc/footer.php'); ?>

<!-- Required Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<script>
// $(document).ready(function() {
    // Initialize DataTable with proper configuration
    $(document).ready(function() {
    // Initialize DataTable
    const dataTable = $('#job-table').DataTable({
    pageLength: 10,
    lengthChange: false,
    ordering: true,
    order: [],
    columnDefs: [
        { orderable: false, targets: [9, 10, 11, 12] } // column indexes start from 0
    ],
    language: {
        search: "",
        paginate: {
            next: 'Next',
            previous: 'Previous'
        }
    },
    dom: 'rt<"bottom"ip><"clear">'
});


    // Your custom search input logic
    $('#searchInput').on('keyup', function() {
        dataTable.search(this.value).draw();
    });

    $('#clearSearch').on('click', function() {
        $('#searchInput').val('');
        dataTable.search('').draw();
        $('#searchInput').focus();
    });

    // Certificate modal handling with proper event delegation
    $(document).on('click', '.view-certificates', function(e) {
        e.preventDefault();
        const projectNo = $(this).data('project-no');
        $('#modalProjectNo').text(projectNo);
        
        // Show loading state
        $('#certificateTableBody').html('<tr><td colspan="3" class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading certificates...</td></tr>');
        $('#certificateModal').modal('show');

        $.ajax({
            url: 'fetch_certificates.php',
            type: 'POST',
            data: { project_no: projectNo },
            dataType: 'json',
            success: function(response) {
                if (response.success && response.certificates.length > 0) {
                    let html = '';
                    $.each(response.certificates, function(index, certificate) {
                        const urls = getCertificateUrls(projectNo, certificate.certificate_type);
                        
                        html += `
                            <tr>
                                <td>${urls.displayName}</td>
                                <td>${certificate.certificate_no}</td>
                                <td>
                                    <a href="${urls.viewUrl}" class="text-info mr-3" target="_blank" title="View">
                                        <i class="fas fa-eye fa-lg"></i>
                                    </a>
                                    <a href="${urls.downloadUrl}" class="text-primary" title="Download">
                                        <i class="fas fa-download fa-lg"></i>
                                    </a>
                                </td>
                            </tr>`;
                    });
                    $('#certificateTableBody').html(html);
                } else {
                    $('#certificateTableBody').html(`
                        <tr>
                            <td colspan="3" class="text-center text-muted">
                                ${response.message || 'No certificates found for this project'}
                            </td>
                        </tr>`);
                }
            },
            error: function(xhr, status, error) {
                $('#certificateTableBody').html(`
                    <tr>
                        <td colspan="3" class="text-center text-danger">
                            Error loading certificates: ${error}
                        </td>
                    </tr>`);
                console.error('AJAX Error:', error);
            }
        });
    });

    // Helper function to determine URLs based on certificate type
    function getCertificateUrls(projectNo, certificateType) {
        const base = {
            viewUrl: '#',
            downloadUrl: '#',
            displayName: certificateType
        };
        
        const types = {
            'healthcheck': {
                viewUrl: `../document/health-check/view.php?project_no=${projectNo}`,
                downloadUrl: `../document/health-check/download.php?project_no=${projectNo}`,
                displayName: 'Health Check'
            },
            'loadtestwithload': {
                viewUrl: `../document/loadtest/view.php?project_no=${projectNo}`,
                downloadUrl: `../document/loadtest/download.php?project_no=${projectNo}`,
                displayName: 'Load Test With Load'
            },
            'mobile': {
                viewUrl: `../document/mobile/view.php?project_no=${projectNo}`,
                downloadUrl: `../document/mobile/download.php?project_no=${projectNo}`,
                displayName: 'Mobile Crane'
            },
            'withloadtest': {
                viewUrl: `../document/withloadtest/view.php?project_no=${projectNo}`,
                downloadUrl: `../document/withloadtest/download.php?project_no=${projectNo}`,
                displayName: 'Load Test'
            },
            'lifting': {
                viewUrl: `../document/lifting/view.php?project_no=${projectNo}`,
                downloadUrl: `../document/lifting/download.php?project_no=${projectNo}`,
                displayName: 'Lifting Gear'
            },
            'mpi': {
                viewUrl: `../document/mpi/view.php?project_no=${projectNo}`,
                downloadUrl: `../document/mpi/download.php?project_no=${projectNo}`,
                displayName: 'MPI'
            },
            'eddycurrent': {
                viewUrl: `../document/eddycurrent/view.php?project_no=${projectNo}`,
                downloadUrl: `../document/eddycurrent/download.php?project_no=${projectNo}`,
                displayName: 'Eddy Current'
            },
            'liquidpenetrantinspection': {
                viewUrl: `../document/liquid-penetrant-inspection-certificate/view.php?project_no=${projectNo}`,
                downloadUrl: `../document/liquid-penetrant-inspection-certificate/download.php?project_no=${projectNo}`,
                displayName: 'Liquid Penetrant Inspection'
            },
            'rocktest': {
                viewUrl: `../document/rocktest/view.php?project_no=${projectNo}`,
                downloadUrl: `../document/rocktest/download.php?project_no=${projectNo}`,
                displayName: 'Rocktest'
            },
            'lmi': {
                viewUrl: `../document/lmi/view.php?project_no=${projectNo}`,
                downloadUrl: `../document/lmi/download.php?project_no=${projectNo}`,
                displayName: 'LMI'
            }
        };
        
        return types[certificateType] || base;
    }
});
</script>