<?php
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once('../../inc/function.php'); 
include('../../file/config.php');
// session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$requested_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($_SESSION['user_id'] !== $requested_id) {
    header("Location: ../index.php");
    exit();
}

// Fetch data with proper handle_crane handling
$user_data = [];
$role = $_SESSION['role'];

// Get data from users table
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $requested_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $user_data = array_merge($user_data, $result->fetch_assoc());
}

// Get data from new_users table with proper handle_crane handling
$stmt = $conn->prepare("SELECT *, IFNULL(handle_crane, 'a:0:{}') AS handle_crane FROM new_users WHERE id = ?");
$stmt->bind_param("i", $requested_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $user_data = array_merge($user_data, $result->fetch_assoc());
}

// Also get data from inspectors table if exists
$stmt = $conn->prepare("SELECT * FROM inspectors WHERE inspector_id = ?");
$inspector_id = $user_data['inspector_id'] ?? $user_data['user_id'] ?? '';
$stmt->bind_param("s", $inspector_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $user_data = array_merge($user_data, $result->fetch_assoc());
}

if (empty($user_data)) {
    header("Location: ../index.php");
    exit();
}

function get_encoded_url($base, $id) {
    $url = $base . "?id=" . $id;
    return str_replace(' ', '%20', $url);
}
?>

<!-- Main Content -->
<div class="main-content d-flex flex-column flex-md-row">
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="card p-3">
            <nav class="aside-body">
                <h5>Account Settings</h5>
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#general">General</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#c_pass">Change Password</a></li>
                </ul>
            </nav>
            </div>
        </div>
        
        <!-- Content Section -->
        <div class="col-md-9">
            <div class="card p-4">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="general">
                        <h4 class="mb-4">Account Settings</h4>
                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                        <?php endif; ?>
                        <?php if (isset($_SESSION['success'])): ?>
                            <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
                        <?php endif; ?>
                        <?php if (isset($_SESSION['warning'])): ?>
                            <div class="alert alert-warning"><?php echo $_SESSION['warning']; unset($_SESSION['warning']); ?></div>
                        <?php endif; ?>
                        
                        <form action="update-profile.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?php echo $user_data['id']; ?>">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-14 bold mb-2">Inspector ID</label>
                                        <input type="text" class="theme-input-style" name="inspector_id" 
                                        value="<?php echo htmlspecialchars($user_data['inspector_id'] ?? $user_data['user_id'] ?? ''); ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label class="font-14 bold mb-2">Inspector Name</label>
                                        <input type="text" class="theme-input-style" name="inspector_name" 
                                        value="<?php echo htmlspecialchars($user_data['inspector_name'] ?? $user_data['username'] ?? ''); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="font-14 bold mb-2">Email</label>
                                        <input type="email" class="theme-input-style" name="email" 
                                        value="<?php echo htmlspecialchars($user_data['email'] ?? ''); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="font-14 bold mb-2">Mobile</label>
                                        <input type="text" class="theme-input-style" name="mobile" 
                                        pattern="[0-9]{10,15}" title="10-15 digit mobile number"
                                        value="<?php echo htmlspecialchars($user_data['mobile'] ?? ''); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="font-14 bold mb-2">Employee ID</label>
                                        <input type="text" class="theme-input-style" name="emp_id" 
                                        value="<?php echo htmlspecialchars($user_data['emp_id'] ?? ''); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-14 bold mb-2">Address</label>
                                        <textarea class="form-control" name="address" rows="3" required><?php echo htmlspecialchars($user_data['address'] ?? ''); ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="font-14 bold mb-2">City</label>
                                        <select class="form-control" name="city" required>
                                            <option value="Kobar" <?php echo (($user_data['city'] ?? '')) == 'Kobar' ? 'selected' : ''; ?>>Al Kobar</option>
                                            <option value="Riyadh" <?php echo (($user_data['city'] ?? '')) == 'Riyadh' ? 'selected' : ''; ?>>Riyadh</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="font-14 bold mb-2">Role</label>
                                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($role); ?>" readonly>
                                    </div>

                                    <!-- Profile Photo Section -->
                                    <!-- Replace the profile photo section with: -->
<div class="form-group">
    <label class="font-14 bold mb-2">Profile Photo</label>
    
    <?php 
    $inspector_name = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $user_data['inspector_name'] ?? $user_data['username'] ?? ''));
    $profile_url = '/whiteapp/inspector/uploads/' . $inspector_name . '/images/profile_image.jpg';
    $profile_path = $_SERVER['DOCUMENT_ROOT'] . $profile_url;
    ?>
    
    <?php if (file_exists($profile_path)): ?>
        <div class="mb-3">
            <img src="<?= htmlspecialchars($profile_url) ?>?t=<?= time() ?>" 
                 alt="Profile Photo" 
                 class="img-thumbnail" 
                 style="max-width: 150px;">
            <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" name="remove_photo" id="remove_photo" value="1">
                <label class="form-check-label" for="remove_photo">
                    Remove current photo
                </label>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">
            No profile photo found
        </div>
    <?php endif; ?>
    
    <input type="file" class="form-control" name="profile_photo" accept="image/jpeg,image/png">
    <small class="form-text text-muted">Will be saved as profile_image.jpg in your directory</small>
</div>

<!-- Replace the signature photo section with: -->
<div class="form-group">
    <label class="font-14 bold mb-2">Signature Photo</label>
    
    <?php 
    $signature_url = '/whiteapp/inspector/uploads/' . $inspector_name . '/images/signature_image.jpg';
    $signature_path = $_SERVER['DOCUMENT_ROOT'] . $signature_url;
    ?>
    
    <?php if (file_exists($signature_path)): ?>
        <div class="mb-3">
            <img src="<?= htmlspecialchars($signature_url) ?>?t=<?= time() ?>" 
                 alt="Signature Photo" 
                 class="img-thumbnail" 
                 style="max-width: 150px;">
            <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" name="remove_signature" id="remove_signature" value="1">
                <label class="form-check-label" for="remove_signature">
                    Remove current signature
                </label>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">
            No signature photo found
        </div>
    <?php endif; ?>
    
    <input type="file" class="form-control" name="signature_photo" accept="image/jpeg,image/png">
    <small class="form-text text-muted">Will be saved as signature_image.jpg in your directory</small>
</div>
                                </div>
                            </div>
                            <div class="form-group pt-1">
                                <label class="custom-checkbox position-relative mr-2">
                                    <input type="checkbox" name="info_correct" required>
                                    <span class="checkmark"></span>
                                </label>
                                <label>Confirm whether the provided details are correct</label>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                            <button type="reset" class="btn btn-secondary">Cancel</button>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="c_pass">
                        <h4 class="mb-4">Change Password</h4>
                        <form action="change-password.php" method="POST">
                            <input type="hidden" name="id" value="<?php echo $user_data['id']; ?>">
                            <div class="form-group">
                                <label class="font-14 bold mb-2">Old Password</label>
                                <input type="password" class="theme-input-style" name="old_password" required>
                            </div>
                            <div class="form-group">
                                <label class="font-14 bold mb-2">New Password</label>
                                <input type="password" class="theme-input-style" name="new_password" required>
                            </div>
                            <div class="form-group">
                                <label class="font-14 bold mb-2">Confirm Password</label>
                                <input type="password" class="theme-input-style" name="confirm_password" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const forms = document.querySelectorAll("form");
    
    forms.forEach(form => {
        const submitButton = form.querySelector("button[type='submit']");
        
        form.addEventListener("submit", function () {
            submitButton.disabled = true;
            submitButton.innerText = submitButton.innerText.includes("Password") 
                ? "Updating Password..." 
                : "Saving Changes...";
        });
    });
});
</script>

<?php
include_once('../../inc/footer.php'); 
ob_end_flush();
?>