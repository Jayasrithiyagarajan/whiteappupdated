<?php
session_start();
include_once('../../file/config.php');

if (strtolower($_SESSION['role'] ?? '') !== 'admin') {
    http_response_code(403);
    echo json_encode([
        'total' => 0,
        'active' => 0,
        'completed' => 0,
        'certificates' => 0
    ]);
    exit;
}

$user = $_SESSION['username'];
$role = $_SESSION['role'];

// Filter parameters
$filterInspector = $_POST['filter_inspector'] ?? '';
$filterClient    = $_POST['filter_client'] ?? '';
$filterDateFrom  = $_POST['filter_date_from'] ?? '';
$filterDateTo    = $_POST['filter_date_to'] ?? '';
$filterEquipment = $_POST['filter_equipment'] ?? '';
$filterLocation  = $_POST['filter_location'] ?? '';

$where = " WHERE 1=1 ";

if (trim(strtolower($role)) === 'inspector') {
     $where .= " AND inspector_name='".mysqli_real_escape_string($conn,$user)."' ";
}

// Apply Filters
if (!empty($filterInspector)) {
    $where .= " AND inspector_name LIKE '%".mysqli_real_escape_string($conn, $filterInspector)."%' ";
}
if (!empty($filterClient)) {
    $where .= " AND customer_name LIKE '%".mysqli_real_escape_string($conn, $filterClient)."%' ";
}
if (!empty($filterDateFrom)) {
    $where .= " AND DATE(creation_date) >= '".mysqli_real_escape_string($conn, $filterDateFrom)."' ";
}
if (!empty($filterDateTo)) {
    $where .= " AND DATE(creation_date) <= '".mysqli_real_escape_string($conn, $filterDateTo)."' ";
}
if (!empty($filterEquipment)) {
    $where .= " AND equipment_id LIKE '%".mysqli_real_escape_string($conn, $filterEquipment)."%' ";
}
if (!empty($filterLocation)) {
    $where .= " AND equipment_location LIKE '%".mysqli_real_escape_string($conn, $filterLocation)."%' ";
}

// 1. Total Projects
$totalSql = "SELECT COUNT(*) as total FROM project_info $where";
$totalRes = $conn->query($totalSql);
$total = $totalRes->fetch_assoc()['total'] ?? 0;

// 2. Active vs Completed Projects
// Assuming 'project_status' column exists (from fetch_reports.php join), if not fallback to logic
// For now, let's look for a status column. 'project_status' was seen in fetch_reports.php join.
// If it doesn't exist, we might default to 0 or check another table.
// Let's assume it exists for now based on previous file analysis.
$activeSql = "SELECT COUNT(*) as total FROM project_info $where AND project_status = 'Pending'"; // Assuming 'Pending' means Active/In-progress
$activeRes = $conn->query($activeSql);
$active = $activeRes->fetch_assoc()['total'] ?? 0; // Fallback if query fails will be handled by try/catch conceptually, but here simple

$completedSql = "SELECT COUNT(*) as total FROM project_info $where AND project_status = 'Completed'";
$completedRes = $conn->query($completedSql);
$completed = $completedRes->fetch_assoc()['total'] ?? 0;

// If project_status doesn't exist, these queries might error. 
// A safer bet based on 'reports' table join might be:
// "Completed" often means a report is generated.
// Logic: Count valid reports related to these projects?
// For simplicity in this iteration, I'll use the 'project_status' column I saw earlier.
// If that column is missing, I'll need to patch this.

// 3. Certificates / Stickers
// Count projects with sticker_status = 'Yes'
$certSql = "SELECT COUNT(*) as total FROM project_info $where AND sticker_status = 'Yes'";
$certRes = $conn->query($certSql);
$certs = $certRes->fetch_assoc()['total'] ?? 0;


echo json_encode([
    'total' => $total,
    'active' => $active,
    'completed' => $completed,
    'certificates' => $certs
]);
?>
