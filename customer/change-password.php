<?php
include_once('../file/config.php');

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form inputs
    $cus_id = htmlspecialchars($_POST['cus_id']);
    $old_password = htmlspecialchars($_POST['old_password']);
    $new_password = htmlspecialchars($_POST['new_password']);
    $retype_password = htmlspecialchars($_POST['retype_password']);

    // Validate inputs
    if (empty($old_password) || empty($new_password) || empty($retype_password)) {
        echo "All fields are required.";
        exit;
    }

    if ($new_password !== $retype_password) {
        echo "New passwords do not match.";
        exit;
    }

    // Fetch the current password and customer_name from the customers table
    $query = "SELECT password, customer_name FROM customers WHERE cus_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $cus_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $customer = $result->fetch_assoc();

    if (!$customer) {
        echo "Customer not found.";
        exit;
    }

    $customer_name = $customer['customer_name'];

    // Verify the old password
    if (!password_verify($old_password, $customer['password'])) {
        echo "Old password is incorrect.";
        exit;
    }

    // Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update the password in the customers table
    $sql_customers = "UPDATE customers SET password = ? WHERE cus_id = ?";
    $stmt_customers = $conn->prepare($sql_customers);
    $stmt_customers->bind_param("ss", $hashed_password, $cus_id);

    // Update the password in the users table based on customer_name (mapped to username)
    $sql_users = "UPDATE users SET password = ? WHERE username = ? AND role = 'customer'";
    $stmt_users = $conn->prepare($sql_users);
    $stmt_users->bind_param("ss", $hashed_password, $customer_name);

    // Start a transaction to ensure both updates succeed or fail together
    $conn->begin_transaction();

    try {
        // Update customers table
        if (!$stmt_customers->execute()) {
            throw new Exception("Error updating password in customers table.");
        }

        // Update users table
        if (!$stmt_users->execute()) {
            throw new Exception("Error updating password in users table.");
        }

        // Commit the transaction if both updates succeed
        $conn->commit();
        echo "Password updated successfully.";
        header("Location: customer-list.php");
        exit;
    } catch (Exception $e) {
        // Rollback the transaction if any update fails
        $conn->rollback();
        echo $e->getMessage();
    }

    // Close statements
    $stmt_customers->close();
    $stmt_users->close();
}
?>
