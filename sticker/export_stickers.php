<?php
session_start();
// Increase memory limit for large exports
ini_set('memory_limit', '512M');
set_time_limit(300);

include_once('../file/config.php');

$user = $_SESSION['username'];
$role = $_SESSION['role'];

// Filter parameters
$search       = $_GET['search_value'] ?? '';

// Base Query
$where = " WHERE 1=1 "; 
$params = [];
$types = "";

// Role Restriction
if ($role === 'inspector') {
    $where .= " AND s.assign_inspector = ? ";
    $params[] = $user;
    $types .= "s";
}

// Global Search
if (!empty($search)) {
    $searchWildcard = "%{$search}%";
    $where .= " AND (
        s.sticker_start_no LIKE ? OR
        s.project_no LIKE ? OR
        s.assign_inspector LIKE ? OR
        s.sticker_s.status LIKE ? OR
        s.status LIKE ?
    ) ";
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $types .= "sssss";
}

// Query
$sql = "SELECT s.*, pi.equipment_id, ci.crane_serial_no as equipment_serial_no FROM stickers s LEFT JOIN project_info pi ON s.project_no = pi.project_no LEFT JOIN checklist_information ci ON s.project_no = ci.project_no $where ORDER BY s.created_at DESC";

$stmt = $conn->prepare($sql);
if(!empty($params)){
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Headers
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="sticker_list_'.date('Y-m-d_H-i-s').'.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, array('Sticker ID', 'Project ID', 'Equipment No', 'Equipment Serial No', 'Inspect By', 'Created At', 'Inspection Date', 'Expiry Date', 'Sticker Status', 'Status'));

while($row = $result->fetch_assoc()) {
    
    // Logic for Expiry Date
    $next_inspection_due_date = null;
    $rSql = "SELECT next_inspection_due_date FROM reports WHERE sticker_number_issued = ? ORDER BY next_inspection_due_date DESC LIMIT 1";
    $rStmt = $conn->prepare($rSql);
    $rStmt->bind_param("s", $row['sticker_start_no']);
    $rStmt->execute();
    $rStmt->bind_result($next_inspection_due_date);
    $rStmt->fetch();
    $rStmt->close();

    if (!$next_inspection_due_date) {
        $next_inspection_due_date = date("Y-m-d", strtotime($row['inspection_date'] . " +3 months"));
    }
    $expiry_date = $next_inspection_due_date;

    fputcsv($output, array(
        $row['sticker_start_no'],
        $row['project_no'],
        $row['equipment_id'],
        $row['equipment_serial_no'],
        $row['assign_inspector'],
        $row['created_at'],
        $row['inspection_date'],
        $expiry_date,
        $row['sticker_status'],
        $row['status']
    ));
}

fclose($output);
exit();
?>
