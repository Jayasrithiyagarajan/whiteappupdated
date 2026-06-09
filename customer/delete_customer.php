<?php
include_once('../file/config.php'); // Include the database connection file

// Check if `cusid` is provided
if (isset($_GET['cusid'])) {
    $cus_id = $_GET['cusid'];

    // Validate that `cusid` is numeric
    // if (!is_numeric($cus_id)) {
    //     echo "Invalid request.";
    //     exit();
    // }

    // Check if confirmation is provided
    if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
        // Start transaction
        $conn->begin_transaction();

        try {
            // First delete from customers table
            $stmt1 = $conn->prepare("DELETE FROM customers WHERE cus_id = ?");
            if (!$stmt1) {
                throw new Exception("Failed to prepare customers delete statement: " . $conn->error);
            }
            $stmt1->bind_param("i", $cus_id);
            if (!$stmt1->execute()) {
                throw new Exception("Failed to delete from customers: " . $stmt1->error);
            }
            $stmt1->close();

            // Then delete from new_users table
            $stmt2 = $conn->prepare("DELETE FROM new_users WHERE user_id = ?");
            if (!$stmt2) {
                throw new Exception("Failed to prepare new_users delete statement: " . $conn->error);
            }
            $stmt2->bind_param("i", $cus_id);
            if (!$stmt2->execute()) {
                throw new Exception("Failed to delete from new_users: " . $stmt2->error);
            }
            $stmt2->close();

            // Commit the transaction if both deletions succeeded
            $conn->commit();
            
            // Redirect to index.php after successful deletion
            header("Location: customer-list.php");
            exit();
        } catch (Exception $e) {
            // Roll back the transaction if any error occurred
            $conn->rollback();
            echo "Error: " . $e->getMessage();
        }
    } else {
        // Ask for confirmation
        echo "
        <script>
            if (confirm('Are you sure you want to delete this customer? This will also remove their user account.')) {
                window.location.href = '?cusid=$cus_id&confirm=yes';
            } else {
                window.location.href = 'customer-list.php'; // Redirect to index if canceled
            }
        </script>";
    }
} else {
    echo "Invalid request.";
}

// Close the database connection
$conn->close();
?>