<?php
// Include database configuration
include_once('../file/config.php');

// Check if 'customer_id' is passed
if (isset($_GET['customer_id']) && !empty($_GET['customer_id'])) {
    // Sanitize and validate the input
    $customer_id = intval(trim($_GET['customer_id']));

    // Check database connection
    if (!$conn) {
        die(json_encode([
            "success" => false,
            "message" => "Database connection failed: " . $conn->connect_error
        ]));
    }

    // Prepare the query to fetch customer details
    $query = "SELECT email, mobile FROM customers WHERE id = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("i", $customer_id); // Bind customer ID as integer
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $customer = $result->fetch_assoc();

            // Return customer details as JSON
            echo json_encode([
                "success" => true,
                "email" => $customer['email'],
                "mobile" => $customer['mobile']
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Customer not found. Please select a valid customer."
            ]);
        }
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Query failed: " . $conn->error
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Invalid customer ID provided."
    ]);
}
?>
