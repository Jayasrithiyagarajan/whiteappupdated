<?php
include_once('../inc/function.php');
include_once('../file/config.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user data
$sql = "SELECT * FROM new_users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "User not found!";
    exit;
}

// Set default values if empty
$profile_photo = !empty($user['profile_photo']) ? $user['profile_photo'] : '../assets/img/avatar.png';
$signature_photo = !empty($user['signature']) ? $user['signature'] : '';
$username = htmlspecialchars($user['username']);
$full_name = htmlspecialchars($user['fullname'] ?? $username);
$role = htmlspecialchars($user['role'] ?? 'User');
$email = htmlspecialchars($user['email'] ?? '');
$mobile = htmlspecialchars($user['phone_number'] ?? '');
?>

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --secondary-gradient: linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%);
        --card-shadow: 0 10px 30px rgba(0,0,0,0.08);
        --premium-shadow: 0 15px 35px rgba(0,0,0,0.12);
        --glass-bg: rgba(255, 255, 255, 0.95);
    }

    .main-content {
        background: #f0f2f5;
        min-height: 100vh;
        padding-top: 30px;
        padding-bottom: 50px;
    }

    /* Profile Header & Hero */
    .profile-hero {
        position: relative;
        background: var(--primary-gradient);
        height: 250px;
        border-radius: 20px 20px 0 0;
        margin-bottom: 80px;
        box-shadow: var(--card-shadow);
    }

    .profile-info-card {
        position: absolute;
        bottom: -60px;
        left: 40px;
        right: 40px;
        background: var(--glass-bg);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 25px;
        display: flex;
        align-items: center;
        box-shadow: var(--premium-shadow);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .profile-avatar-wrapper {
        position: relative;
        margin-right: 30px;
    }

    .profile-avatar {
        width: 140px;
        height: 140px;
        border-radius: 20px;
        object-fit: cover;
        border: 5px solid white;
        box-shadow: var(--card-shadow);
        background: white;
    }

    .profile-text-info h2 {
        margin: 0;
        font-weight: 800;
        color: #2d3748;
        font-size: 28px;
    }

    .profile-text-info .badge-role {
        display: inline-block;
        padding: 5px 15px;
        border-radius: 10px;
        background: var(--primary-gradient);
        color: white;
        font-size: 13px;
        font-weight: 600;
        margin-top: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Tabs Styling */
    .profile-tabs-nav {
        background: white;
        border-radius: 15px;
        padding: 10px;
        margin-bottom: 30px;
        box-shadow: var(--card-shadow);
        border: none;
        display: flex;
        gap: 10px;
    }

    .profile-tabs-nav .nav-link {
        border: none;
        border-radius: 10px;
        padding: 12px 25px;
        color: #718096;
        font-weight: 600;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .profile-tabs-nav .nav-link.active {
        background: var(--primary-gradient);
        color: white;
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
    }

    .profile-tabs-nav .nav-link:hover:not(.active) {
        background: #f7fafc;
        color: #4a5568;
    }

    /* Form & Content Cards */
    .content-card {
        background: white;
        border-radius: 20px;
        border: none;
        box-shadow: var(--card-shadow);
        overflow: hidden;
        margin-bottom: 30px;
        transition: transform 0.3s ease;
    }

    .content-card:hover {
        transform: translateY(-5px);
    }

    .card-title-premium {
        padding: 25px 30px;
        border-bottom: 1px solid #edf2f7;
        margin: 0;
        font-size: 20px;
        font-weight: 700;
        color: #2d3748;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .card-body-premium {
        padding: 35px 40px;
    }

    .form-group-premium {
        margin-bottom: 25px;
    }

    .form-group-premium label {
        font-weight: 600;
        color: #4a5568;
        margin-bottom: 10px;
        display: block;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-control-premium {
        border-radius: 12px;
        border: 2px solid #edf2f7;
        padding: 12px 20px;
        height: auto;
        font-size: 15px;
        transition: all 0.3s ease;
        background: #f8fafc;
    }

    .form-control-premium:focus {
        border-color: #667eea;
        background: white;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        outline: none;
    }

    /* File Upload Styling */
    .file-upload-wrapper {
        position: relative;
        width: 100%;
    }

    .file-upload-input {
        position: absolute;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
        z-index: 2;
    }

    .file-upload-design {
        background: #f8fafc;
        border: 2px dashed #cbd5e0;
        border-radius: 12px;
        padding: 30px;
        text-align: center;
        transition: all 0.3s ease;
    }

    .file-upload-design i {
        font-size: 32px;
        color: #a0aec0;
        margin-bottom: 10px;
    }

    .file-upload-design p {
        margin: 0;
        color: #718096;
        font-weight: 500;
    }

    .file-upload-wrapper:hover .file-upload-design {
        border-color: #667eea;
        background: #f0f4ff;
    }

    .preview-img {
        width: 100px;
        height: 100px;
        border-radius: 12px;
        object-fit: cover;
        margin-top: 15px;
        border: 2px solid #edf2f7;
    }

    /* Buttons */
    .btn-premium {
        padding: 14px 30px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 16px;
        transition: all 0.3s ease;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .btn-primary-premium {
        background: var(--primary-gradient);
        color: white;
        box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
    }

    .btn-primary-premium:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5);
        color: white;
    }

    /* Info Items */
    .info-item {
        display: flex;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 30px;
    }

    .info-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        background: #f0f4ff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: #667eea;
        flex-shrink: 0;
    }

    .info-content h6 {
        margin: 0 0 5px 0;
        color: #718096;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .info-content p {
        margin: 0;
        color: #2d3748;
        font-weight: 700;
        font-size: 16px;
    }

    /* Signature Styles */
    .signature-preview {
        max-width: 200px;
        max-height: 100px;
        border-radius: 10px;
        border: 1px solid #edf2f7;
        padding: 5px;
        background: white;
        margin-top: 15px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .profile-hero { margin-bottom: 120px; }
        .profile-info-card { 
            flex-direction: column; 
            text-align: center; 
            bottom: -100px;
            left: 20px;
            right: 20px;
        }
        .profile-avatar-wrapper { margin-right: 0; margin-bottom: 20px; }
        .profile-avatar { width: 120px; height: 120px; }
        .profile-tabs-nav { overflow-x: auto; flex-wrap: nowrap; }
        .profile-tabs-nav .nav-link { white-space: nowrap; }
    }
</style>

<div class="main-content">
    <div class="container">
        <!-- Profile Header -->
        <div class="row">
            <div class="col-12">
                <div class="profile-hero">
                    <div class="profile-info-card">
                        <div class="profile-avatar-wrapper">
                            <img src="<?php echo $profile_photo; ?>" alt="Profile" class="profile-avatar" id="header-avatar-preview">
                        </div>
                        <div class="profile-text-info">
                            <h2><?php echo $full_name; ?></h2>
                            <span class="badge-role"><?php echo $role; ?></span>
                            <div class="mt-2 text-muted">
                                <i class="fa-solid fa-calendar-alt me-2"></i> Joined <?php echo date('F Y', strtotime($user['created_at'] ?? 'now')); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="row">
            <div class="col-12">
                <ul class="nav nav-tabs profile-tabs-nav" id="profileTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="overview-tab" data-toggle="tab" href="#overview" role="tab"><i class="fa-solid fa-id-card"></i> Overview</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="edit-tab" data-toggle="tab" href="#edit" role="tab"><i class="fa-solid fa-user-edit"></i> Edit Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="security-tab" data-toggle="tab" href="#security" role="tab"><i class="fa-solid fa-shield-halved"></i> Security</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Tab Content -->
        <div class="tab-content" id="myTabContent">
            <!-- Overview Tab -->
            <div class="tab-pane fade show active" id="overview" role="tabpanel">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="content-card">
                            <h4 class="card-title-premium"><i class="fa-solid fa-info-circle text-primary"></i> Personal Information</h4>
                            <div class="card-body-premium">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <div class="info-icon"><i class="fa-solid fa-user"></i></div>
                                            <div class="info-content">
                                                <h6>Full Name</h6>
                                                <p><?php echo $full_name; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <div class="info-icon"><i class="fa-solid fa-at"></i></div>
                                            <div class="info-content">
                                                <h6>Username</h6>
                                                <p><?php echo $username; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <div class="info-icon"><i class="fa-solid fa-envelope"></i></div>
                                            <div class="info-content">
                                                <h6>Email Address</h6>
                                                <p><?php echo $email; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <div class="info-icon"><i class="fa-solid fa-phone"></i></div>
                                            <div class="info-content">
                                                <h6>Phone Number</h6>
                                                <p><?php echo $mobile; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="content-card">
                            <h4 class="card-title-premium"><i class="fa-solid fa-signature text-primary"></i> Digital Signature</h4>
                            <div class="card-body-premium text-center">
                                <?php if (!empty($signature_photo)): ?>
                                    <img src="<?php echo $signature_photo; ?>" alt="Signature" class="signature-preview img-fluid">
                                <?php else: ?>
                                    <div class="py-4 text-muted">
                                        <i class="fa-solid fa-pen-nib fa-3x mb-3 opacity-25"></i>
                                        <p>No signature uploaded yet</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Profile Tab -->
            <div class="tab-pane fade" id="edit" role="tabpanel">
                <form id="updateProfileForm" action="user-profile-update.php" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="content-card">
                                <h4 class="card-title-premium"><i class="fa-solid fa-user-gear text-primary"></i> Edit Personal Details</h4>
                                <div class="card-body-premium">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group-premium">
                                                <label>Full Name</label>
                                                <input type="text" name="fullname" class="form-control-premium w-100" value="<?php echo $user['fullname'] ?? ''; ?>" placeholder="Enter your full name">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group-premium">
                                                <label>Username</label>
                                                <input type="text" class="form-control-premium w-100" value="<?php echo $username; ?>" readonly style="background: #edf2f7; cursor: not-allowed;">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group-premium">
                                                <label>Email Address</label>
                                                <input type="email" name="email" class="form-control-premium w-100" value="<?php echo $user['email'] ?? ''; ?>" placeholder="Enter email">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group-premium">
                                                <label>Phone Number</label>
                                                <input type="text" name="phone_number" class="form-control-premium w-100" value="<?php echo $user['phone_number'] ?? ''; ?>" placeholder="Enter phone number">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="content-card">
                                <h4 class="card-title-premium"><i class="fa-solid fa-camera text-primary"></i> Profile Content</h4>
                                <div class="card-body-premium">
                                    <div class="form-group-premium">
                                        <label>Profile Picture</label>
                                        <div class="file-upload-wrapper">
                                            <input type="file" name="profile_photo" class="file-upload-input" onchange="previewImage(this, 'avatar-preview')">
                                            <div class="file-upload-design">
                                                <i class="fa-solid fa-cloud-arrow-up"></i>
                                                <p>Click or Drag Photo</p>
                                            </div>
                                        </div>
                                        <img id="avatar-preview" src="<?php echo $profile_photo; ?>" class="preview-img">
                                    </div>
                                    <hr>
                                    <div class="form-group-premium">
                                        <label>Digital Signature</label>
                                        <div class="file-upload-wrapper">
                                            <input type="file" name="signature_photo" class="file-upload-input" onchange="previewImage(this, 'signature-preview-edit')">
                                            <div class="file-upload-design">
                                                <i class="fa-solid fa-pen-clip"></i>
                                                <p>Upload Signature</p>
                                            </div>
                                        </div>
                                        <img id="signature-preview-edit" src="<?php echo $signature_photo; ?>" class="signature-preview <?php echo empty($signature_photo) ? 'd-none' : ''; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="content-card">
                                <div class="card-body-premium p-4">
                                    <button type="submit" class="btn btn-premium btn-primary-premium px-5">
                                        <i class="fa-solid fa-save"></i> Save Profile Changes
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Security Tab -->
            <div class="tab-pane fade" id="security" role="tabpanel">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="content-card">
                            <h4 class="card-title-premium"><i class="fa-solid fa-key text-primary"></i> Change Password</h4>
                            <div class="card-body-premium">
                                <form id="changePasswordForm">
                                    <div class="form-group-premium">
                                        <label>Current Password</label>
                                        <input type="password" name="current_password" class="form-control-premium w-100" required>
                                    </div>
                                    <div class="form-group-premium">
                                        <label>New Password</label>
                                        <input type="password" id="new_password" name="new_password" class="form-control-premium w-100" required>
                                    </div>
                                    <div class="form-group-premium">
                                        <label>Confirm New Password</label>
                                        <input type="password" name="confirm_password" class="form-control-premium w-100" required>
                                    </div>
                                    <button type="submit" class="btn btn-premium btn-primary-premium w-100">
                                        <i class="fa-solid fa-lock"></i> Update Password
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="content-card">
                            <h4 class="card-title-premium"><i class="fa-solid fa-circle-check text-success"></i> Account Status</h4>
                            <div class="card-body-premium">
                                <div class="alert alert-info border-0 rounded-4 p-4 shadow-sm" style="background-color: #e3f2fd;">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fa-solid fa-shield-check fa-2x text_blue me-3"></i>
                                        <h5 class="mb-0 text_blue">Security Audit</h5>
                                    </div>
                                    <p class="mb-0 text-dark opacity-75">Your account security is currently healthy. Remember to change your password every 90 days for maximum safety.</p>
                                </div>

                                <div class="mt-4">
                                    <h6>Last Login Details</h6>
                                    <?php
                                    $sess_sql = "SELECT * FROM user_sessions WHERE user_id = ? ORDER BY login_time DESC LIMIT 1 OFFSET 1";
                                    $sess_stmt = $conn->prepare($sess_sql);
                                    $sess_stmt->bind_param("i", $user_id);
                                    $sess_stmt->execute();
                                    $last_sess = $sess_stmt->get_result()->fetch_assoc();
                                    ?>
                                    <div class="p-3 bg-light rounded-3 mt-2">
                                        <div class="small text-muted mb-1">Last IP Address: <?php echo $last_sess['ip_address'] ?? 'N/A'; ?></div>
                                        <div class="small text-muted">Last Login: <?php echo isset($last_sess['login_time']) ? date('M d, Y h:i A', strtotime($last_sess['login_time'])) : 'N/A'; ?></div>
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

<script>
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('d-none');
            // If it's the avatar preview in the form, also update the header avatar
            if (previewId === 'avatar-preview') {
                document.getElementById('header-avatar-preview').src = e.target.result;
            }
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// Simple form submission demo/hook
document.getElementById('changePasswordForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    Swal.fire({
        title: 'Updating Password...',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });
    
    // In a real scenario, use fetch() to send data to ajax-change-password.php
    setTimeout(() => {
        Swal.fire({
            icon: 'success',
            title: 'Password Updated!',
            text: 'Your security settings have been updated.',
            timer: 2000,
            showConfirmButton: false
        });
    }, 1500);
});
</script>

<?php include_once('../inc/footer.php'); ?>
