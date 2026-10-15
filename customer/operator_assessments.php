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

// Fetch Operator Assessments
$sql = "SELECT 
            oa.id,
            oa.assessment_no,
            oa.date,
            oa.operator_name,
            oa.operator_id_passport,
            oa.location,
            oa.operating_location,
            oa.no_of_equipment,
            nu.username AS inspector_name,
            oa.status,
            oa.exam_status,
            oa.exam_score,
            oa.signals_status,
            oa.signals_score,
            oa.created_at,
            oa.date_of_expiry
        FROM operator_assessments oa
        LEFT JOIN new_users nu ON oa.inspector_id = nu.user_id
        WHERE oa.client_id = ?
        ORDER BY oa.id DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $cus_id);
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
.main-content3 .bg-warning-light {
    background-color: #fef3c7 !important;
    color: #b45309 !important;
    padding: 6px 12px !important;
    border-radius: 8px !important;
    font-size: 12px !important;
    font-weight: 700 !important;
    display: inline-block;
    border: 1px solid rgba(180, 83, 9, 0.1) !important;
}
.main-content3 .bg-info-light {
    background-color: #e0f2fe !important;
    color: #0369a1 !important;
    padding: 6px 12px !important;
    border-radius: 8px !important;
    font-size: 12px !important;
    font-weight: 700 !important;
    display: inline-block;
    border: 1px solid rgba(3, 105, 161, 0.1) !important;
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
                                        <a class="p_nav-link has-before active" href="../customer/project.php?cusid=<?php echo urlencode($customer['cus_id']); ?>">Projects</a>
                                    </li>
                                    <li>
                                        <a class="active p_nav-link has-before" href="../customer/operator_assessments.php?cusid=<?php echo urlencode($customer['cus_id']); ?>">Operator assessments</a>
                                    </li>
                                    <li>
                                        <a href="../dashboard/customer_kpi_dashboard.php">KPI Analytics</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="mt-30">
                        <!-- Profile Completion -->
                        <div class="profile-completion d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="">
                                    <h4 class="c2 mb-1">Operator Assessments</h4>
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
                            <div class="card-body pb-0">
                                <div class="d-sm-flex justify-content-between align-items-center">
                                    <h4 class="font-20">Assessments List</h4>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap align-items-center mb-3">
                                <div class="table-controls position-relative mr-3 mb-2 mb-sm-0" style="width: 300px;">
                                    <input type="text" id="searchInput" class="form-control rounded-3 pl-5" placeholder="Search Assessments..."/>
                                    <span class="position-absolute" style="top: 50%; left: 12px; transform: translateY(-50%); pointer-events: none;">
                                        <i class="fa fa-search text-muted"></i>
                                    </span>
                                    <span id="clearSearch" class="position-absolute" style="top: 50%; right: 12px; transform: translateY(-50%); cursor: pointer;">
                                        <i class="fa fa-times text-muted"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table id="job-table" class="order-list-table style--three table-centered text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Assessment No</th>
                                            <th>Date</th>
                                            <th>Operator Name</th>
                                            <th>ID / Passport</th>
                                            <th>Location</th>
                                            <th>Inspector</th>
                                            <th>Exam Status</th>
                                            <th>Signals Status</th>
                                            <th>Status</th>
                                            <th>Expiry Date</th>
                                            <th>Certificate</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($result->num_rows > 0): ?>
                                            <?php while ($row = $result->fetch_assoc()): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($row["assessment_no"]); ?></td>
                                                    <td><?php echo date("d M Y", strtotime($row["date"])); ?></td>
                                                    <td><?php echo htmlspecialchars($row["operator_name"]); ?></td>
                                                    <td><?php echo htmlspecialchars($row["operator_id_passport"]); ?></td>
                                                    <td><?php echo htmlspecialchars($row["location"]); ?></td>
                                                    <td><?php echo htmlspecialchars($row["inspector_name"]); ?></td>
                                                    <td>
                                                        <span class="<?php echo strtolower($row['exam_status']) === 'completed' || strtolower($row['exam_status']) === 'pass' ? 'bg-success-light text-success' : 'bg-warning-light text-warning'; ?>">
                                                            <?php echo htmlspecialchars($row["exam_status"]); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="<?php echo strtolower($row['signals_status']) === 'completed' || strtolower($row['signals_status']) === 'pass' ? 'bg-success-light text-success' : 'bg-info-light text-info'; ?>">
                                                            <?php echo htmlspecialchars($row["signals_status"]); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="<?php echo strtolower($row['status']) === 'completed' ? 'bg-success-light text-success' : 'bg-danger-light text-danger'; ?>">
                                                            <?php echo htmlspecialchars($row["status"]); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <?php 
                                                            if(!empty($row["date_of_expiry"])) {
                                                                echo date("d M Y", strtotime($row["date_of_expiry"]));
                                                            } else {
                                                                echo '<span class="text-muted">N/A</span>';
                                                            }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php if (strtoupper($row["status"]) === 'COMPLETED') { ?>
                                                            <a href="../certificate/generate_certificate.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-sm btn-primary" target="_blank" title="Download Certificate">
                                                                Download
                                                            </a>
                                                        <?php } else { ?>
                                                            <span class="text-muted">Not Available</span>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <tr><td colspan="11" class="text-center">No assessments found.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
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
    $(document).ready(function() {
        // Initialize DataTable
        const dataTable = $('#job-table').DataTable({
            pageLength: 10,
            lengthChange: false,
            ordering: true,
            order: [],
            columnDefs: [
                { orderable: false, targets: [10] } // disable ordering on actions column
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
    });
</script>
