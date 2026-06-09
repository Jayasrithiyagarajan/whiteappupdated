<?php
include_once('../inc/function.php');
include ('../file/config.php');

$cus_id = $_GET['cusid'] ?? '';
// Fetch customer data based on cus_id
$sql = "SELECT * FROM customers WHERE cus_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $cus_id);
$stmt->execute();
$result = $stmt->get_result();
$customer = $result->fetch_assoc();

if (!$customer) {
    echo "Customer not found!";
    exit;
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
                        <form action="../customer/update-profile.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="cus_id" value="<?php echo $customer['cus_id']; ?>">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Company Name</label>
                                        <input type="text" name="customer_name" class="form-control" value="<?php echo $customer['customer_name']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" name="email" class="form-control" value="<?php echo $customer['email']; ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Mobile</label>
                                        <input type="text" name="mobile" class="form-control" value="<?php echo $customer['mobile']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Upload Profile Photo</label>
                                        <input type="file" name="profile_photo" class="form-control">
                                        <img src="<?php echo $customer['profile_photo']; ?>" class="img-thumbnail mt-2" width="100">
                                    </div>
                                    <div class="form-group">
                                        <label>Upload Signature</label>
                                        <input type="file" name="signature_photo" class="form-control">
                                        <img src="<?php echo $customer['signature_photo']; ?>" class="img-thumbnail mt-2" width="100">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                            <button type="reset" class="btn btn-secondary">Cancel</button>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="c_pass">
    <h4 class="mb-4">Change Password</h4>
    <form action="../customer/change-password.php" method="POST">
        <input type="hidden" name="cus_id" value="<?php echo $customer['cus_id']; ?>">
        <div class="form-group">
            <label>Old Password</label>
            <input type="password" name="old_password" class="form-control">
        </div>
        <div class="form-group">
            <label>New Password</label>
            <input type="password" name="new_password" class="form-control">
        </div>
        <div class="form-group">
            <label>Retype Password</label>
            <input type="password" name="retype_password" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
</div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<?php include_once('../inc/footer.php'); ?>