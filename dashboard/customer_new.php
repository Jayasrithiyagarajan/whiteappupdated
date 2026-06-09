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
    <title>Your Page Title</title>

    <!-- Internal Styles for Pagination -->
    <style>

.list-group-item {
   border-left: 5px solid #007bff; /* Blue border for notifications */
   transition: background 0.3s;
}

.list-group-item:hover {
   background: #f8f9fa;
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

                                       
    <!--<a href="../document/customer_survey_report.php?project_id=<?php echo urlencode($manual_project_no); ?>">Survey</a>-->
</li>

                                        <!--<a href="../customer/project.php?cusid=<?php echo urlencode($customer['cus_id']); ?>">Survey</a>-->
                                        <!--../document/customer_survey_report.php?project_id=CIMS217-->
                                        
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
                                            <!-- <a href="../profile/edit-profile.php?cusid={$row['cus_id']}">Edit Profile</a> -->
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
                                    <p class="font-14">Member since: <?php echo $created_at; ?></p>
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

                                <div class="row mt-5">
                                    <div class="col-md-3">
                                        <nav>
                                            <div class="nav flex-md-column about-nav-tab">
                                                <a class="active" id="nav-overview-tab" data-toggle="tab" href="#nav-overview">Overview</a>
                                            </div>
                                        </nav>
                                    </div>

                                    <div class="col-md-9">
                                        <div class="tab-content about-tab-content pl-md-5 mt-4 mt-md-0">
                                            <div class="tab-pane fade show active" id="nav-overview" role="tabpanel">
                                                <!-- Overview -->
                                                <div class="overview">
                                                    <h4 class="mb-3">Personal Information</h4>

                                                    <ul class="p_overview-list list-unstyled">
                                                        <li>
                                                            <div class="d-flex">
                                                                <div class="img mr-3">
                                                                    <i class="icofont-id-card"></i>
                                                                </div>
                                                                <div class="content">
                                                                    <strong>Customer ID:</strong> <?php echo htmlspecialchars($customer['cus_id']); ?>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="d-flex">
                                                                <div class="img mr-3">
                                                                    <i class="icofont-mobile-phone"></i>
                                                                </div>
                                                                <div class="content">
                                                                    <strong>Mobile:</strong> 
                                                                    <a href="tel:<?php echo htmlspecialchars($mobile); ?>" class="text_color">
                                                                        <?php echo htmlspecialchars($mobile); ?>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="d-flex">
                                                                <div class="img mr-3">
                                                                    <i class="icofont-globe"></i>
                                                                </div>
                                                                <div class="content">
                                                                    <strong>Email:</strong> 
                                                                    <a href="mailto:<?php echo htmlspecialchars($email); ?>" class="text_color">
                                                                        <?php echo htmlspecialchars($email); ?>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="d-flex">
                                                                <div class="img mr-3">
                                                                    <i class="icofont-location-pin"></i>
                                                                </div>
                                                                <div class="content">
                                                                    <strong>Address:</strong> 
                                                                    <?php echo htmlspecialchars($address); ?>, <?php echo htmlspecialchars($city); ?>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="d-flex">
                                                                <div class="img mr-3">
                                                                    <i class="icofont-calendar"></i>
                                                                </div>
                                                                <div class="content">
                                                                    <strong>Registration Date:</strong> 
                                                                    <?php echo $created_at; ?>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <?php if (!empty($signaturePhoto)): ?>
                                                        <li>
                                                            <div class="d-flex">
                                                                <div class="img mr-3">
                                                                    <i class="icofont-signature"></i>
                                                                </div>
                                                                <div class="content">
                                                                    <strong>Signature:</strong> 
                                                                    <img src="<?php echo $signaturePhoto; ?>" alt="Signature" style="max-height: 50px;">
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <?php endif; ?>
                                                    </ul>
                                                </div>
                                                <!-- End Overview -->
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-xl-6 col-lg-6">
   <div class="card mb-30">
      <div class="card-body">
         <h4 class="mb-3">Notifications</h4>
         <p class="font-14">Project updates and important alerts.</p>
         
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