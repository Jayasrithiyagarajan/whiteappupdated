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
<div class="main-content">
    <div class="container-fluid">
        <div class="card bg-transparent pb-3">
            <div class="card-body bg-white">
                <h4 class="pt-3 pb-2">Edit Customer</h4>
                <form action="./update-customer.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="cus_id" value="<?php echo $customer['cus_id']; ?>">

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Customer Name</label>
                                <input type="text" class="theme-input-style" name="customer_name"
                                    value="<?php echo $customer['customer_name']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="theme-input-style" name="email"
                                    value="<?php echo $customer['email']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Company</label>
                                <input type="text" class="theme-input-style" name="company"
                                    value="<?php echo $customer['company']; ?>">
                            </div>
                            <div class="form-group">
                                <label>Rep. Name</label>
                                <input type="text" class="theme-input-style" name="rep_name"
                                    value="<?php echo $customer['rep_name']; ?>">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Mobile</label>
                                <input type="text" class="theme-input-style" name="mobile"
                                    value="<?php echo $customer['mobile']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Address</label>
                                <textarea class="form-control" name="address"><?php echo $customer['address']; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>City</label>
                                <select class="form-control" name="city">
                                    <option value="Kobar" <?php echo $customer['city'] == 'Kobar' ? 'selected' : ''; ?>>
                                        Al Kobar</option>
                                    <option value="Riyadh" <?php echo $customer['city'] == 'Riyadh' ? 'selected' : ''; ?>>
                                        Riyadh</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Profile Photo</label><br>
                                <img src="<?php echo $customer['profile_photo']; ?>" alt="Current Photo" width="100">
                                <input type="file" class="form-control mt-2" name="profile_photo">
                            </div>

                            <div class="form-group">
    <label>Customer Signature</label><br>
    <?php if (!empty($customer['signature_photo'])) : ?>
        <img src="<?php echo $customer['signature_photo']; ?>" alt="Current Signature" width="150">
    <?php endif; ?>
    <input type="file" class="form-control mt-2" name="signature_photo">
</div>

                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary" name="update_customer">Update</button>
                        <a href="customer-list.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
