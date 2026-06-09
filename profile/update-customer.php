<?php
include ('../file/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cus_id = $_POST['cus_id'];
    $customer_name = $_POST['customer_name'];
    $email = $_POST['email'];
    $company = $_POST['company'];
    $rep_name = $_POST['rep_name'];
    $mobile = $_POST['mobile'];
    $address = $_POST['address'];
    $city = $_POST['city'];

    $uploadDir = '../uploads/profile_photos/';
    $signatureDir = '../uploads/signatures/';
    $profilePath = null;
    $signaturePath = null;

    // Ensure directories exist
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
    if (!is_dir($signatureDir)) mkdir($signatureDir, 0777, true);

    // Profile Photo Upload
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] == 0) {
        $profilePath = $uploadDir . $cus_id . '.png'; // Save as cus_id.png
        move_uploaded_file($_FILES['profile_photo']['tmp_name'], $profilePath);
    }

    // Signature Photo Upload
    if (isset($_FILES['signature_photo']) && $_FILES['signature_photo']['error'] == 0) {
        $signaturePath = $signatureDir . $cus_id . '.png'; // Save as cus_id.png
        move_uploaded_file($_FILES['signature_photo']['tmp_name'], $signaturePath);
    }

    // Password logic
    if (!empty($_POST['old_password']) && !empty($_POST['new_password']) && !empty($_POST['retype_password'])) {
        $old_password = $_POST['old_password'];
        $new_password = $_POST['new_password'];
        $retype_password = $_POST['retype_password'];

        // Fetch the existing password hash
        $sql = "SELECT password FROM customers WHERE cus_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $cus_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $customer = $result->fetch_assoc();

        if (!$customer || !password_verify($old_password, $customer['password'])) {
            echo "Old password is incorrect!";
            exit;
        }

        if ($new_password !== $retype_password) {
            echo "New password and retype password do not match!";
            exit;
        }

        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    }

    // Update query logic
    $sql = "UPDATE customers SET customer_name=?, email=?, company=?, rep_name=?, mobile=?, address=?, city=?";
    $params = [$customer_name, $email, $company, $rep_name, $mobile, $address, $city];
    $types = "sssssss";

    if ($profilePath) {
        $sql .= ", profile_photo=?";
        $params[] = $profilePath;
        $types .= "s";
    }

    if ($signaturePath) {
        $sql .= ", signature_photo=?";
        $params[] = $signaturePath;
        $types .= "s";
    }

    if (!empty($hashed_password)) {
        $sql .= ", password=?";
        $params[] = $hashed_password;
        $types .= "s";
    }

    $sql .= " WHERE cus_id=?";
    $params[] = $cus_id;
    $types .= "s";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        echo "Customer updated successfully!";
        header("Location: customer-list.php");
        exit;
    } else {
        echo "Error updating customer: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
