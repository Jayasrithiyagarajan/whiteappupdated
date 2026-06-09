<?php
ob_start();
include_once('../../inc/function.php'); 
include('../../file/config.php');
// session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

// Verify the user ID matches the requested profile
$requested_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($_SESSION['user_id'] !== $requested_id) {
    
    header("Location: ../../../../index.php");
    exit();
}

// Fetch data from both tables
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

// Get data from new_users table
$stmt = $conn->prepare("SELECT * FROM new_users WHERE id = ?");
$stmt->bind_param("i", $requested_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $user_data = array_merge($user_data, $result->fetch_assoc());
}

// If no data found in either table
if (empty($user_data)) {
    header("Location: ../index.php");
    exit();
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
                        <form action="update-profile.php" method="POST">
                            <input type="hidden" name="id" value="<?php echo $user_data['id']; ?>">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-14 bold mb-2">User ID</label>
                                        <input type="text" class="theme-input-style" name="user_id" 
                                        value="<?php echo htmlspecialchars($user_data['user_id'] ?? ''); ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label class="font-14 bold mb-2">User Name</label>
                                        <input type="text" class="theme-input-style" name="username" 
                                        value="<?php echo htmlspecialchars($user_data['username'] ?? $user_data['username'] ?? ''); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="font-14 bold mb-2">Email</label>
                                        <input type="email" class="theme-input-style" name="email" 
                                        value="<?php echo htmlspecialchars($user_data['email'] ?? ''); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="font-14 bold mb-2">Employee ID</label>
                                        <input type="text" class="theme-input-style" name="emp_id" 
                                        value="<?php echo htmlspecialchars($user_data['emp_id'] ?? ''); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-14 bold mb-2">Mobile</label>
                                        <input type="text" class="theme-input-style" name="mobile" 
                                        value="<?php echo htmlspecialchars($user_data['mobile'] ?? ''); ?>" required>
                                    </div>
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
                                </div>
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