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

// Fetch customer details from database based on customer_name
$customer_name = $_SESSION['username']; // Assuming username is stored in session as customer_name
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
    $rep_name = $customer['rep_name'];
    $created_at = date('F j, Y', strtotime($customer['created_at']));
    $cus_id = $customer['cus_id']; // Get OSS ID from the result
} else {
    // Handle case where customer not found
    echo "Customer not found";
    exit();
}

include_once('../inc/customer-option.php');
?>

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

                                <!-- <div>
                                    <h3><?php echo htmlspecialchars($cus_name); ?></h3>
                                    <p class="font-14">Registered Name: <?php echo htmlspecialchars($reg_name); ?></p>
                                </div> -->
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
                                    <p class="font-14">Member since: <?php echo $created_at; ?></p>
                                </div>
                            </div>

                            <!-- Edit Profile Button -->
                            <div class="edit-profile-btn pr-1">
                                <a href="edit-customer.php" class="btn-circle">
                                    <img src="<?php echo $url; ?>assets/img/svg/writing.svg" alt="" class="svg">
                                </a>
                            </div>
                            <!-- End Edit Profile Button -->
                        </div>
                        <!-- End Profile Completion -->

                        <!-- NOW ADD YOUR EDIT FORM CONTENT HERE -->
                        <div class="card mb-30">
                            <div class="card-body p-30">
                                <form action="update-customer.php" method="post" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-xl-6">
                                            <h4 class="mb-3">Personal Information</h4>
                                            
                                            <div class="form-group">
                                                <label>Full Name</label>
                                                <input type="text" class="form-control" name="customer_name" value="<?php echo htmlspecialchars($cus_name); ?>">
                                            </div>
                                            
                                            <div class="form-group">
                                                <label>Rep. Name</label>
                                                <input type="text" class="form-control" name="rep_name" value="<?php echo htmlspecialchars($rep_name); ?>">
                                            </div>
                                            
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($email); ?>">
                                            </div>
                                            
                                            <div class="form-group">
                                                <label>Mobile</label>
                                                <input type="text" class="form-control" name="mobile" value="<?php echo htmlspecialchars($mobile); ?>">
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-6">
                                            <h4 class="mb-3">Address Information</h4>
                                            
                                            <div class="form-group">
                                                <label>Address</label>
                                                <textarea class="form-control" name="address"><?php echo htmlspecialchars($address); ?></textarea>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label>City</label>
                                                <input type="text" class="form-control" name="city" value="<?php echo htmlspecialchars($city); ?>">
                                            </div>

                                            <h4 class="mb-3 mt-4">Password Change</h4>
                    
                    <div class="form-group">
                        <label>Current Password</label>
                        <input type="password" class="form-control" name="old_password" placeholder="Enter current password">
                        <small class="text-muted">Required to change password</small>
                    </div>
                    
                    <div class="form-group">
                        <label>New Password</label>
                        <input type="password" class="form-control" name="new_password" placeholder="Enter new password">
                        <small class="text-muted">Minimum 8 characters</small>
                    </div>
                    
                    <div class="form-group">
                        <label>Confirm New Password</label>
                        <input type="password" class="form-control" name="retype_password" placeholder="Retype new password">
                    </div>
                
                                            
                                            <div class="form-group">
                                                <label>Profile Photo</label>
                                                <input type="file" class="form-control" name="profile_photo">
                                                <?php if(!empty($profilePhoto)): ?>
                                                    <img src="<?php echo $profilePhoto; ?>" width="100" class="mt-2">
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label>Signature Photo</label>
                                                <input type="file" class="form-control" name="signature_photo">
                                                <?php if(!empty($signaturePhoto)): ?>
                                                    <img src="<?php echo $signaturePhoto; ?>" width="100" class="mt-2">
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        
                                        <div class="col-12 text-center mt-4">
                                            <input type="hidden" name="cus_id" value="<?php echo $cus_id; ?>">
                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                            <a href="customer_new.php" class="btn btn-secondary ml-2">Cancel</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Main Content -->

<?php 
include_once('../inc/footer.php');
?>