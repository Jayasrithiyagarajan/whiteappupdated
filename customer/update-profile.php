<?php
include_once('../file/config.php');

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form inputs
    $cus_id = htmlspecialchars($_POST['cus_id']);    
    $customer_name = htmlspecialchars($_POST['customer_name']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $mobile = htmlspecialchars($_POST['mobile']);
    // $company = htmlspecialchars($_POST['company']);

    $uploadDir = '../uploads/profile_photos/';
    $signatureDir = '../uploads/signatures/';
    $profilePath = null;
    $signaturePath = null;

    // Ensure directories exist
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
    if (!is_dir($signatureDir)) mkdir($signatureDir, 0777, true);

    // Get existing paths from the database
    $query = "SELECT profile_photo, signature_photo FROM customers WHERE cus_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $cus_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $customer = $result->fetch_assoc();

    if (!$customer) {
        echo "Customer not found.";
        exit;
    }

    $profilePath = $customer['profile_photo'];
    $signaturePath = $customer['signature_photo'];

    // Profile Photo Upload
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] == 0) {
        $profilePath = $uploadDir . $cus_id . '.png'; // Save as cus_id.png
        if (!move_uploaded_file($_FILES['profile_photo']['tmp_name'], $profilePath)) {
            echo "Failed to upload profile photo.";
            exit;
        }
    }

    // Signature Photo Upload
    if (isset($_FILES['signature_photo']) && $_FILES['signature_photo']['error'] == 0) {
        $signaturePath = $signatureDir . $cus_id . '.png'; // Save as cus_id.png
        if (!move_uploaded_file($_FILES['signature_photo']['tmp_name'], $signaturePath)) {
            echo "Failed to upload signature photo.";
            exit;
        }
    }

    // Update customer details in the database
    $sql = "UPDATE customers SET 
                customer_name = ?, 
                email = ?, 
                mobile = ?,                 
                profile_photo = ?, 
                signature_photo = ? 
            WHERE cus_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $customer_name, $email, $mobile, $profilePath, $signaturePath, $cus_id);
    if ($stmt->execute()) {
        echo "Profile updated successfully.";
        // Redirect to customer list page
        header("Location: customer-list.php");
        exit;
    } else {
        echo "Error updating profile.";
    }
}
?>
