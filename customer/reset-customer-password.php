<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include '../file/config.php';
$conn->set_charset("utf8mb4");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cus_id = $_POST['cus_id'] ?? '';
    // We can also use customer_name if needed, but cus_id is safer for unique identification in 'customers' table.
    // However, 'users' table uses 'username' and 'new_users' uses 'user_id' (which is cus_id).
    // We should fetch the customer_name from the DB using cus_id to be sure.
    
    $new_password_plain = $_POST['new_password'] ?? '';

    if (empty($cus_id) || empty($new_password_plain)) {
        echo json_encode(['status' => 'error', 'message' => 'Missing ID or Password']);
        exit;
    }

    // 1. Get customer details to ensure existence and get username
    $stmt = $conn->prepare("SELECT customer_name FROM customers WHERE cus_id = ?");
    $stmt->bind_param("s", $cus_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows === 0) {
        echo json_encode(['status' => 'error', 'message' => 'Customer not found']);
        exit;
    }
    $row = $res->fetch_assoc();
    $customer_name = $row['customer_name'];
    $stmt->close();

    // 2. Hash Password
    $new_password_hash = password_hash($new_password_plain, PASSWORD_DEFAULT);

    // 3. Update Tables
    $conn->begin_transaction();

    try {
        // Update customers table
        $stmt1 = $conn->prepare("UPDATE customers SET password = ? WHERE cus_id = ?");
        $stmt1->bind_param("ss", $new_password_hash, $cus_id);
        $stmt1->execute();
        $stmt1->close();

        // Update new_users table (user_id = cus_id)
        $stmt2 = $conn->prepare("UPDATE new_users SET password = ? WHERE user_id = ?");
        $stmt2->bind_param("ss", $new_password_hash, $cus_id);
        $stmt2->execute();
        $stmt2->close();

        // Update users table (username = customer_name, and role='customer')
        // Ideally we should have a better link, but based on add-customer.php, this is how it links.
        $stmt3 = $conn->prepare("UPDATE users SET password = ? WHERE username = ? AND role = 'customer'");
        $stmt3->bind_param("ss", $new_password_hash, $customer_name);
        $stmt3->execute();
        $stmt3->close();

        $conn->commit();
        echo json_encode(['status' => 'success', 'message' => 'Password updated successfully for all associated records.']);

    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => 'Database update failed: ' . $e->getMessage()]);
    }

    $conn->close();

} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
