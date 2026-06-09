<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include '../file/config.php';
$conn->set_charset("utf8mb4");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inspector_id = $_POST['inspector_id'] ?? '';
    $new_password_plain = $_POST['new_password'] ?? '';

    if (empty($inspector_id) || empty($new_password_plain)) {
        echo json_encode(['status' => 'error', 'message' => 'Missing Inspector ID or Password']);
        exit;
    }

    // 1. Get inspector details to ensure existence and get inspector_name
    $stmt = $conn->prepare("SELECT inspector_name FROM inspectors WHERE inspector_id = ?");
    $stmt->bind_param("s", $inspector_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows === 0) {
        echo json_encode(['status' => 'error', 'message' => 'Inspector not found']);
        exit;
    }
    $row = $res->fetch_assoc();
    $inspector_name = $row['inspector_name'];
    $stmt->close();

    // 2. Hash Password
    $new_password_hash = password_hash($new_password_plain, PASSWORD_DEFAULT);

    // 3. Update Tables
    $conn->begin_transaction();

    try {
        // Update inspectors table
        $stmt1 = $conn->prepare("UPDATE inspectors SET password = ? WHERE inspector_id = ?");
        $stmt1->bind_param("ss", $new_password_hash, $inspector_id);
        $stmt1->execute();
        $stmt1->close();

        // Update new_users table (user_id = inspector_id)
        $stmt2 = $conn->prepare("UPDATE new_users SET password = ? WHERE user_id = ?");
        $stmt2->bind_param("ss", $new_password_hash, $inspector_id);
        $stmt2->execute();
        $stmt2->close();

        // Update users table (username = inspector_name, and role='inspector')
        $stmt3 = $conn->prepare("UPDATE users SET password = ? WHERE username = ? AND role = 'inspector'");
        $stmt3->bind_param("ss", $new_password_hash, $inspector_name);
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
