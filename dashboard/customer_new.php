<?php 
// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once('../file/config.php');

// Check if the user is logged in - using proper session variable
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}
 
// Check if the user has the 'customer' role - using proper session variable
if ($_SESSION['role'] !== 'customer') {
    header("Location: ../index.php");
    exit();
}

// Fetch customer details from database based on customer_name
$customer_name = $_SESSION['username'];
$sql = "SELECT * FROM customers WHERE customer_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $customer_name);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $customer = $result->fetch_assoc();
    
    // Set variables for display
    $cus_name = $customer['customer_name'];
    $profilePhoto = !empty($customer['profile_photo']) ? $customer['profile_photo'] : $url . 'assets/img/media/profile-pic.jpg';
    $signaturePhoto = !empty($customer['signature_photo']) ? $customer['signature_photo'] : '';
    $email = $customer['email'];
    $mobile = $customer['mobile'];
    $address = $customer['address'];
    $city = $customer['city'];
    $created_at = date('F j, Y', strtotime($customer['created_at']));
    $cus_id = $customer['cus_id'];
} else {
    // Handle case where customer not found
    echo "Customer not found";
    exit();
}

// Fetch notifications for the logged-in customer
$query_notifications = "SELECT project_no, Notification_message, created_at 
                        FROM project_notifications 
                        WHERE customer_name = ? 
                        ORDER BY created_at DESC 
                        LIMIT 5";

$stmt = $conn->prepare($query_notifications);
$stmt->bind_param("s", $customer_name);
$stmt->execute();
$result_notifications = $stmt->get_result();

// Remove notifications for completed projects
$cleanup_query = "DELETE pn FROM project_notifications pn
                  JOIN project_info pi ON pn.project_no = pi.project_no
                  WHERE pi.project_status = 'Completed' AND pn.customer_name = ?";
$stmt_cleanup = $conn->prepare($cleanup_query);
$stmt_cleanup->bind_param("s", $customer_name);
$stmt_cleanup->execute();

include_once('../inc/customer-option.php');
// Manually set the project number for survey link
$manual_project_no = "CIMS217"; // 👈 change this anytime you need
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard - CIMS</title>

    <!-- Internal Styles for Premium Look -->
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

/* Survey Form inside nav */
.main-content3 .profile-nav-tabs form {
    display: flex !important;
    align-items: center;
    gap: 6px;
    margin-left: 10px;
}
.main-content3 .profile-nav-tabs form input[type="text"] {
    padding: 8px 14px;
    border-radius: 10px;
    border: 1px solid #cbd5e1;
    font-size: 13px;
    outline: none;
    transition: all 0.2s ease;
    width: 140px;
    background: #f8fafc;
}
.main-content3 .profile-nav-tabs form input[type="text"]:focus {
    border-color: #4f46e5;
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}
.main-content3 .profile-nav-tabs form button[type="submit"] {
    padding: 8px 16px;
    border-radius: 10px;
    background: #0f172a;
    color: #ffffff;
    font-size: 13px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
}
.main-content3 .profile-nav-tabs form button[type="submit"]:hover {
    background: #1e293b;
    transform: translateY(-1px);
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

/* Personal Info / About details section */
.main-content3 .about-myself h4 {
    font-size: 20px;
    font-weight: 700;
    color: #0f172a;
}
.main-content3 .about-myself p {
    color: #64748b;
    font-size: 14px;
}

/* Overview Info List */
.main-content3 .overview h4 {
    font-size: 18px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 20px;
}
.main-content3 .p_overview-list {
    display: grid;
    grid-template-columns: 1fr;
    gap: 16px;
}
@media (min-width: 768px) {
    .main-content3 .p_overview-list {
        grid-template-columns: 1fr 1fr;
    }
}
.main-content3 .p_overview-list li {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    padding: 16px;
    border-radius: 14px;
    transition: all 0.2s;
}
.main-content3 .p_overview-list li:hover {
    background: #ffffff;
    border-color: #cbd5e1;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.02);
}
.main-content3 .p_overview-list li .d-flex {
    align-items: center;
}
.main-content3 .p_overview-list li .img {
    background: rgba(79, 70, 229, 0.1);
    color: #4f46e5;
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}
.main-content3 .p_overview-list li .content {
    font-size: 14px;
    color: #334155;
    margin-left: 12px;
}
.main-content3 .p_overview-list li .content strong {
    color: #0f172a;
    display: block;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 2px;
}
.main-content3 .p_overview-list li .content a.text_color {
    color: #4f46e5;
    font-weight: 600;
    text-decoration: none;
}

/* Notifications Card styling */
.main-content3 .card.mb-30 {
    border: 1px solid rgba(226, 232, 240, 0.8) !important;
    border-radius: 20px !important;
    background: #ffffff;
}
.main-content3 .card.mb-30 h4 {
    font-size: 18px;
    font-weight: 700;
    color: #0f172a;
}
.main-content3 .list-group-item {
    border: 1px solid #e2e8f0 !important;
    border-left: 4px solid #4f46e5 !important;
    border-radius: 12px !important;
    margin-bottom: 10px;
    padding: 16px;
    background: #f8fafc;
    transition: all 0.2s ease;
}
.main-content3 .list-group-item:hover {
    background: #ffffff !important;
    transform: translateX(4px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.03);
}
.main-content3 .list-group-item h6 {
    font-size: 14px;
    font-weight: 700;
    color: #0f172a;
}
.main-content3 .list-group-item p {
    font-size: 13px;
    color: #475569;
}
.main-content3 .list-group-item small {
    font-size: 11px;
    color: #94a3b8;
    display: block;
    margin-top: 6px;
}
    </style>
</head>
<body>
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

                            <!-- Upload Photo -->
                            <div class="upload-button">
                                <img src="<?php echo $url; ?>assets/img/svg/gallery.svg" alt="" class="svg mr-2">
                                <span>Upload Photo</span>
                                <input class="file-input" type="file" id="fileUpload3" accept="image/*">
                            </div>
                            <!-- End Upload Photo -->
                        </div>
                    </div>
                    <!-- End User Profile Image -->
                </div>
                <div class="mx-2 mx-lg-4 mx-xl-5">
                    <div class="card mt-1">
                        <!-- User Profile Nav -->
                        <div class="user-profile-nav d-flex justify-content-xl-between align-items-xl-center flex-column flex-xl-row">
                            <!-- Profile Info -->
                            <div class="profile-info d-flex align-items-center">
                                <div class="profile-pic mr-3">
                                    <img src="<?php echo $profilePhoto . '?v=' . time(); ?>" alt="Profile Picture">
                                </div>

                                <div>
                                    <h3><?php echo htmlspecialchars($cus_name); ?></h3>
                                </div>
                            </div>
                            <!-- End Profile Info -->

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
                                        <a href="../customer/project.php?cusid=<?php echo urlencode($customer['cus_id']); ?>">Projects</a>
                                    </li>
                                    <li>
                                        <a href="../dashboard/customer_kpi_dashboard.php">KPI Analytics</a>
                                    </li>
                                    <li>
                                        <form method="get" action="../document/customer_survey_report.php" style="display:inline;">
                                            <input type="text" name="project_id" placeholder="Enter Project ID" required>
                                            <button type="submit">Open Survey</button>
                                        </form>
                                    </li>
                                </ul>

                                <div class="px-3">
                                    <!-- Dropdown Button -->
                                    <div class="dropdown-button">
                                        <a href="#" class="d-flex align-items-center" data-toggle="dropdown">
                                            <div class="menu-icon style--two w-auto justify-content-center mr-0">
                                                <span></span>
                                                <span></span>
                                                <span></span>
                                            </div>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a href="../profile/edit-profile.php?cusid=<?php echo urlencode($customer['cus_id']); ?>">Edit Profile</a>
                                            <a href="">User Dashboard</a>
                                        </div>
                                    </div>
                                    <!-- End Dropdown Button -->
                                </div>
                            </div>
                        </div>
                        <!-- End User Profile Nav -->
                    </div>

                    <div class="mt-30">
                        <!-- Profile Completion -->
                        <div class="profile-completion d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <!-- Progress -->
                                <div class="progress_22 mr-3">
                                    <div class="ProgressBar-wrap2 position-relative">
                                        <div class="ProgressBar ProgressBar_22" data-progress="86">
                                            <svg class="ProgressBar-contentCircle" viewBox="0 0 200 200">
                                                <!-- on rotation circle -->
                                                <circle transform="rotate(-90, 100, 100)" class="ProgressBar-background" cx="100" cy="100" r="85" />
                                                <circle transform="rotate(-90, 100, 100)" class="ProgressBar-circle" cx="100" cy="100" r="85" />
                                            </svg>
                                            <span class="ProgressBar-percentage ProgressBar-percentage--count"></span>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Progress -->

                                <div class="">
                                    <h4 class="c2 mb-1">Profile Completion</h4>
                                    <p class="font-14 mb-0">Member since: <?php echo $created_at; ?></p>
                                </div>
                            </div>

                            <!-- Edit Profile Button -->
                            <div class="edit-profile-btn pr-1">
                                <a href="../customer/edit-customer.php" class="btn-circle">
                                    <img src="<?php echo $url; ?>assets/img/svg/writing.svg" alt="" class="svg">
                                </a>
                            </div>
                            <!-- End Edit Profile Button -->
                        </div>
                        <!-- End Profile Completion -->

                        <!-- Card -->
                        <div class="card">
                            <div class="p-30">
                                <div class="about-myself mt-2 pb-2">
                                    <h4 class="mb-3">About Myself</h4>
                                    <p>Here are my complete profile details:</p>
                                </div>

                                <div class="row mt-4">
                                    <!-- Left Column: Personal Information -->
                                    <div class="col-lg-7 col-xl-8">
                                        <div class="overview">
                                            <h4 class="mb-3">Personal Information</h4>

                                            <ul class="p_overview-list list-unstyled">
                                                <li>
                                                    <div class="d-flex">
                                                        <div class="img">
                                                            <i class="icofont-id-card"></i>
                                                        </div>
                                                        <div class="content">
                                                            <strong>Customer ID</strong> <?php echo htmlspecialchars($customer['cus_id']); ?>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="d-flex">
                                                        <div class="img">
                                                            <i class="icofont-mobile-phone"></i>
                                                        </div>
                                                        <div class="content">
                                                            <strong>Mobile</strong> 
                                                            <a href="tel:<?php echo htmlspecialchars($mobile); ?>" class="text_color">
                                                                <?php echo htmlspecialchars($mobile); ?>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="d-flex">
                                                        <div class="img">
                                                            <i class="icofont-globe"></i>
                                                        </div>
                                                        <div class="content">
                                                            <strong>Email</strong> 
                                                            <a href="mailto:<?php echo htmlspecialchars($email); ?>" class="text_color">
                                                                <?php echo htmlspecialchars($email); ?>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="d-flex">
                                                        <div class="img">
                                                            <i class="icofont-location-pin"></i>
                                                        </div>
                                                        <div class="content">
                                                            <strong>Address</strong> 
                                                            <?php echo htmlspecialchars($address); ?>, <?php echo htmlspecialchars($city); ?>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="d-flex">
                                                        <div class="img">
                                                            <i class="icofont-calendar"></i>
                                                        </div>
                                                        <div class="content">
                                                            <strong>Registration Date</strong> 
                                                            <?php echo $created_at; ?>
                                                        </div>
                                                    </div>
                                                </li>
                                                <?php if (!empty($signaturePhoto)): ?>
                                                <li>
                                                    <div class="d-flex">
                                                        <div class="img">
                                                            <i class="icofont-signature"></i>
                                                        </div>
                                                        <div class="content">
                                                            <strong>Signature</strong> 
                                                            <img src="<?php echo $signaturePhoto; ?>" alt="Signature" style="max-height: 50px;">
                                                        </div>
                                                    </div>
                                                </li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    </div>

                                    <!-- Right Column: Notifications -->
                                    <div class="col-lg-5 col-xl-4 mt-4 mt-lg-0">
                                        <div class="card mb-30" style="border: none !important; box-shadow: none !important; background: transparent !important; margin-bottom: 0 !important;">
                                            <div class="card-body p-0">
                                                <h4 class="mb-3">Notifications</h4>
                                                <p class="font-14 text-muted mb-3">Project updates and important alerts.</p>
                                                
                                                <ul class="list-group">
                                                    <?php while ($row = $result_notifications->fetch_assoc()): ?>
                                                       <li class="list-group-item d-flex justify-content-between align-items-start">
                                                          <div>
                                                             <h6 class="mb-1"><?php echo htmlspecialchars($row['project_no']); ?></h6>
                                                             <p class="mb-0"><?php echo htmlspecialchars($row['Notification_message']); ?></p>
                                                             <small class="text-muted"><?php echo date("d M Y, H:i A", strtotime($row['created_at'])); ?></small>
                                                          </div>
                                                       </li>
                                                    <?php endwhile; ?>
                                                    
                                                    <?php if ($result_notifications->num_rows == 0): ?>
                                                       <li class="list-group-item text-center">No new notifications.</li>
                                                    <?php endif; ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- End Card -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Main Content -->
</body>
</html>
<?php 
include_once('../inc/footer.php');
?>