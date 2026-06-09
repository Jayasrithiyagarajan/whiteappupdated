<?php
// include_once '../file/config.php';
// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database configuration
include_once __DIR__ . '/../file/config.php'; // Adjust the path as needed

// $url = 'https://appcims.com/whiteapp/';
$url = 'http://localhost/whiteappupdated/';

// Redirect to login if the user is not authenticated
if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: ../index.php");
    exit();
}

$role = $_SESSION['role'];
$username = $_SESSION['username'];
$user_id = $_SESSION['user_id']; // Add this line
// --- Fetch Profile Photo and User Details ---
$profile_photo = $url . 'assets/img/avatar.png'; // Global default

// 1. Fetch data from new_users table (centralized profile data)
$sql_user = "SELECT profile_photo FROM new_users WHERE id = ?";
if ($stmt_user = $conn->prepare($sql_user)) {
    $stmt_user->bind_param("i", $user_id);
    $stmt_user->execute();
    $res_user = $stmt_user->get_result();
    if ($res_user->num_rows > 0) {
        $user_data = $res_user->fetch_assoc();
        if (!empty($user_data['profile_photo'])) {
            $db_photo = $user_data['profile_photo'];
            if (strpos($db_photo, '../') === 0) {
                $profile_photo = $url . substr($db_photo, 3);
            } elseif (strpos($db_photo, 'http') === 0) {
                $profile_photo = $db_photo;
            } else {
                $profile_photo = $url . ltrim($db_photo, '/');
            }
        }
    }
    $stmt_user->close();
}

// 2. Fetch role-specific details (IDs and role-specific fallbacks)
if ($role == 'customer') {
   $sql = "SELECT cus_id, profile_photo FROM customers WHERE customer_name = ?";
   if ($stmt = $conn->prepare($sql)) {
       $stmt->bind_param("s", $username);
       $stmt->execute();
       $result = $stmt->get_result();
       if ($result->num_rows > 0) {
           $customer = $result->fetch_assoc();
           $cus_id = $customer['cus_id'];
           // Fallback to customer table photo only if new_users photo is still default
           if ($profile_photo == $url . 'assets/img/avatar.png' && !empty($customer['profile_photo'])) {
               $profile_photo = $customer['profile_photo'];
           }
       }
       $stmt->close();
   }
} elseif ($role == 'inspector') {
   $sql = "SELECT inspector_id, profile_photo FROM inspectors WHERE inspector_name = ?";
   if ($stmt = $conn->prepare($sql)) {
       $stmt->bind_param("s", $username);
       $stmt->execute();
       $result = $stmt->get_result();
       if ($result->num_rows > 0) {
           $inspector = $result->fetch_assoc();
           $inspector_id = $inspector['inspector_id'];
           
           // Fallback to inspector-specific folder logic only if new_users photo is still default
           if ($profile_photo == $url . 'assets/img/avatar.png' && !empty($inspector['profile_photo'])) {
               $inspector_folder = preg_replace('/\s+/', '_', strtolower($username));
               $legacy_photo_url = $url . 'inspector/uploads/' . $inspector_folder . '/images/' . $inspector['profile_photo'];
               $legacy_photo_path = $_SERVER['DOCUMENT_ROOT'] . '/whiteapp1/inspector/uploads/' . $inspector_folder . '/images/' . $inspector['profile_photo'];
               
               if (file_exists($legacy_photo_path)) {
                   $profile_photo = $legacy_photo_url;
               }
           }
       }
       $stmt->close();
   }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header</title>
    <!-- Add your CSS and other meta tags here -->
</head>
<body>
   <!-- Header -->
   <header class="header white-bg fixed-top d-flex align-content-center flex-wrap">
         <!-- Logo -->
         <div class="logo">
            <a href="<?php echo $url; ?>" class="default-logo"><img src="<?php echo $url; ?>assets/img/logo.png" alt=""></a>
            <a href="<?php echo $url; ?>" class="mobile-logo"><img src="<?php echo $url; ?>assets/img/mobile-logo.png" alt=""></a>
         </div>
         <!-- End Logo -->

         <!-- Main Header -->
         <div class="main-header">
            <div class="container-fluid">
               <div class="row justify-content-between">
                  <div class="col-3 col-lg-1 col-xl-4">
                     <!-- Header Left -->
                     <div class="main-header-left h-100 d-flex align-items-center">
                        <!-- Main Header User -->
                        <div class="main-header-user d-none d-lg-block">
                           <a href="#" class="d-flex align-items-center" data-toggle="dropdown">
                              <div class="menu-icon">
                                 <span></span>
                                 <span></span>
                                 <span></span>
                              </div>

                              <div class="user-profile d-xl-flex align-items-center d-none">
                                 <!-- User Avatar -->
                                 <!-- <div class="user-avatar">
    <img src="<?php echo $url; ?>/uploads/profile_photos/<?php echo htmlspecialchars($_SESSION['username']); ?>.png" alt="User Profile Photo">
</div> -->

<div class="user-avatar">
                                    <img src="<?php echo $profile_photo; ?>" alt="User Profile Photo">
                                 </div>
                                 <!-- End User Avatar -->

                                 <!-- User Info -->
                                 <div class="user-info">
                                 <h4 class="user-name">
                                    <?php echo $username; // Display the username from the session or 'Guest' ?>
        
    </h4>
                                    <p class="user-email">
                                    
<?php echo htmlspecialchars($_SESSION['role']); ?>
                                    </p>
                                 </div>
                                 <!-- End User Info -->
                              </div>
                           </a>
                           <div class="dropdown-menu">
    <?php
    // Only show "My Profile" and "Settings" if role is not "customer"
    if ($role !== 'customer') {

        $profilePaths = [
            'admin' => 'profileupdate/admin/admin.php',
            'document controller' => 'profileupdate/documentcontroller/document controller.php',
            'inspector' => 'profileupdate/inspector/inspector.php',
            'quality controller' => 'profileupdate/qualitycontroller/quality controller.php',
            'reviewer' => 'profileupdate/reviewer/reviewer.php',
            // Add more roles if needed
        ];

        // Default fallback if role is not defined
        $defaultPath = 'profile/index.php';

        // Get the appropriate path or use default
        $profilePath = isset($profilePaths[$role]) ? $profilePaths[$role] : $defaultPath;

        // Construct the full URL
        $profileUrl = $url . 'profile/user-profile.php?id=' . $user_id;
    ?>
        <a href="<?php echo htmlspecialchars($profileUrl); ?>">My Profile</a>
        <!-- <a href="<?php echo $url; ?>profile/edit-profile.php">Settings</a> -->
    <?php } ?>
    
    <a href="<?php echo $url; ?>file/logout.php">Log Out</a>
</div>

                        </div>
                        <!-- End Main Header User -->

                        <!-- Main Header Menu -->
                        <div class="main-header-menu d-block d-lg-none">
                           <div class="header-toogle-menu">
                              <!-- <i class="icofont-navigation-menu"></i> -->
                              <img src="<?php echo $url; ?>assets/img/menu.png" alt="">
                           </div>
                        </div>
                        <!-- End Main Header Menu -->
                     </div>
                     <!-- End Header Left -->
                  </div>
                  <div class="col-9 col-lg-11 col-xl-8">
                     <!-- Header Right -->
                     <div class="main-header-right d-flex justify-content-end">
                        <ul class="nav">
                           <li class="ml-0">
                              <!-- Main Header Language -->
                          
                              <!-- End Main Header Language -->
                           </li>
                           <li class="ml-0 d-none d-lg-flex">
                              <!-- Main Header Print -->
                              <!-- <div class="main-header-print">
                                 <a href="#">
                                    <img src="<?php echo $url; ?>assets/img/svg/print-icon.svg" alt="">
                                 </a>
                              </div> -->
                              <!-- End Main Header Print -->
                           </li>
                           <li class="d-none d-lg-flex">
                              <!-- Main Header Time -->
                              <div class="main-header-date-time text-right">
                                 <h3 class="time">
                                    <span id="hours">21</span>
                                    <span id="point">:</span>
                                    <span id="min">06</span>
                                 </h3>
                                 <span class="date"><span id="date">Tue, 12 October 2019</span></span>
                              </div>
                              <!-- End Main Header Time -->
                           </li>
                           <li class="d-none d-lg-flex">
                              <!-- Main Header Button -->
                              <div class="main-header-btn ml-md-1">
                                 <a href="https://cims.com.sa/" class="btn">Website</a>
                              </div>
                              <!-- End Main Header Button -->
                           </li>
                           <!--<li>-->
                              <!-- Main Header Notification -->
                              <!-- End Main Header Notification -->
                           <!--</li>-->
                        </ul>
                     </div>
                     <!-- End Header Right -->
                  </div>
               </div>
            </div>
         </div>
         <!-- End Main Header -->
      </header>
      <!-- End Header -->
</body>
</html>
