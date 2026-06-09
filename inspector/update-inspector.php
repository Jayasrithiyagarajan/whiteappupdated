<?php
// Include database connection
include_once('../inc/db.php');

// Check if form is submitted
if (isset($_POST['update_inspector'])) {
    // Retrieve form data
    $id = $_POST['id'];
    $inspector_name = $_POST['inspector_name'];
    $email = $_POST['email'];
    $handle_crane = isset($_POST['handle_crane']) ? implode(',', $_POST['handle_crane']) : '';
    // $emp_id = $_POST['emp_id'];
    $mobile = $_POST['mobile'];
    $password = $_POST['password'];
    $address = $_POST['address'];
    $city = $_POST['city'];

    // Handle profile photo upload
    if (!empty($_FILES['profile_photo']['name'])) {
        $profile_photo = 'uploads/' . time() . '_' . $_FILES['profile_photo']['name'];
        move_uploaded_file($_FILES['profile_photo']['tmp_name'], $profile_photo);
    } else {
        $profile_photo = $_POST['existing_profile_photo'];
    }

    // Handle signature upload
    if (!empty($_FILES['signature_photo']['name'])) {
        $signature_photo = 'uploads/' . time() . '_' . $_FILES['signature_photo']['name'];
        move_uploaded_file($_FILES['signature_photo']['tmp_name'], $signature_photo);
    } else {
        $signature_photo = $_POST['existing_signature_photo'];
    }

    // Update inspector data in the database
    $sql = "UPDATE inspectors SET 
                inspector_name = ?, 
                email = ?, 
                handle_crane = ?,                 
                mobile = ?, 
                password = ?, 
                address = ?, 
                city = ?, 
                profile_photo = ?, 
                signature_photo = ? 
            WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssi", $inspector_name, $email, $handle_crane, $mobile, $password, $address, $city, $profile_photo, $signature_photo, $id);

    if ($stmt->execute()) {
        // Redirect to list page with success message
        header("Location: all-inspector.php?status=updated");
        exit();
    } else {
        // Redirect with error
        header("Location: edit-inspector.php?id=$id&status=error");
        exit();
    }
} else {
    // If form is not submitted, redirect back
    header("Location: all-inspector.php");
    exit();
}
?>
