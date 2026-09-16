<?php
header('Content-Type: application/json');
session_start();
include_once('../file/config.php');

$user_role = $_SESSION['role'] ?? '';
$logged_in_user = $_SESSION['username'] ?? '';

if (!in_array($user_role, ['admin', 'inspector'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

// Auto-create table if not exists
$createTableSql = "CREATE TABLE IF NOT EXISTS payment_list (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_no VARCHAR(100) NOT NULL UNIQUE,
    inspector_name VARCHAR(255) NOT NULL,
    no_of_equipment INT DEFAULT 0,
    total_amount DECIMAL(10,2) DEFAULT 0.00,
    travel_charge DECIMAL(10,2) DEFAULT 0.00,
    broucher_no VARCHAR(100) DEFAULT NULL,
    remarks VARCHAR(50) DEFAULT 'Self',
    other_inspector_name VARCHAR(255) DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";
$conn->query($createTableSql);

$project_no          = trim($_POST['project_no'] ?? '');
$no_of_equipment     = intval($_POST['no_of_equipment'] ?? 0);
$total_amount        = floatval($_POST['total_amount'] ?? 0);
$travel_charge       = floatval($_POST['travel_charge'] ?? 0);
$broucher_no         = trim($_POST['broucher_no'] ?? '');
$remarks             = trim($_POST['remarks'] ?? 'Self');
$other_inspector_name= trim($_POST['other_inspector_name'] ?? '');

if (empty($project_no)) {
    echo json_encode(['status' => 'error', 'message' => 'Project ID is required']);
    exit;
}

if ($remarks === 'Other' && empty($other_inspector_name)) {
    echo json_encode(['status' => 'error', 'message' => 'Please select the inspector name for Other remarks']);
    exit;
}

if ($remarks === 'Self') {
    $other_inspector_name = null;
}

// Check project_info for inspector_name if not passed or empty
$inspector_name = $logged_in_user;
$projStmt = $conn->prepare("SELECT inspector_name FROM project_info WHERE project_no = ?");
$projStmt->bind_param("s", $project_no);
$projStmt->execute();
$projRes = $projStmt->get_result();
if ($row = $projRes->fetch_assoc()) {
    if (!empty($row['inspector_name'])) {
        $inspector_name = $row['inspector_name'];
    }
}
$projStmt->close();

$sql = "INSERT INTO payment_list 
        (project_no, inspector_name, no_of_equipment, total_amount, travel_charge, broucher_no, remarks, other_inspector_name) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE 
        no_of_equipment = VALUES(no_of_equipment),
        total_amount = VALUES(total_amount),
        travel_charge = VALUES(travel_charge),
        broucher_no = VALUES(broucher_no),
        remarks = VALUES(remarks),
        other_inspector_name = VALUES(other_inspector_name),
        inspector_name = VALUES(inspector_name)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssiddsss", $project_no, $inspector_name, $no_of_equipment, $total_amount, $travel_charge, $broucher_no, $remarks, $other_inspector_name);

if ($stmt->execute()) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Payment details saved successfully into Payment List table for project ' . htmlspecialchars($project_no)
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $stmt->error
    ]);
}
$stmt->close();
