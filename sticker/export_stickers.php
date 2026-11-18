<?php
session_start();
// Increase memory limit for large exports
ini_set('memory_limit', '512M');
set_time_limit(300);

include_once('../file/config.php');

$user = $_SESSION['username'] ?? '';
$role = $_SESSION['role'] ?? '';

// Filter parameters
$search           = $_GET['search_value'] ?? '';
$filter_inspector = $_GET['filter_inspector'] ?? '';
$filter_date      = $_GET['filter_date'] ?? '';
$filter_project   = $_GET['filter_project'] ?? '';
$filter_expiry    = $_GET['filter_expiry'] ?? '';
$filter_status    = $_GET['filter_status'] ?? '';

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

// Apply Filters
if (!empty($filter_inspector)) {
    $where .= " AND s.assign_inspector = ? ";
    $params[] = $filter_inspector;
    $types .= "s";
}

if (!empty($filter_date)) {
    $where .= " AND DATE(s.created_at) = ? ";
    $params[] = $filter_date;
    $types .= "s";
}

if (!empty($filter_project)) {
    $where .= " AND s.project_no LIKE ? ";
    $params[] = "%$filter_project%";
    $types .= "s";
}

if (!empty($filter_expiry)) {
    $where .= " AND (
        COALESCE(
            r.next_inspection_due_date,
            (SELECT next_inspection_due_date FROM reports WHERE sticker_number_issued = s.sticker_start_no ORDER BY next_inspection_due_date DESC LIMIT 1),
            DATE_ADD(s.inspection_date, INTERVAL 3 MONTH)
        ) = ?
    ) ";
    $params[] = $filter_expiry;
    $types .= "s";
}

if (!empty($filter_status)) {
    if ($filter_status === 'Unused') {
        $where .= " AND (s.project_no IS NULL OR s.project_no = '') ";
    } elseif ($filter_status === 'Passed') {
        $where .= " AND s.sticker_status = 'Passed' AND (s.project_no IS NOT NULL AND s.project_no != '') ";
    } elseif ($filter_status === 'Failed') {
        $where .= " AND s.sticker_status = 'Failed' AND (s.project_no IS NOT NULL AND s.project_no != '') ";
    }
}

// Global Search
if (!empty($search)) {
    $searchWildcard = "%{$search}%";
    $where .= " AND (
        s.sticker_start_no LIKE ? OR
        s.project_no LIKE ? OR
        s.assign_inspector LIKE ? OR
        s.sticker_status LIKE ? OR
        s.status LIKE ? OR
        s.inspection_date LIKE ? OR
        s.equipment_no LIKE ? OR
        pi.equipment_id LIKE ? OR
        ci.equipment_no LIKE ? OR
        r.equipment_id_no LIKE ? OR
        s.equipment_serial_no LIKE ? OR
        ci.crane_serial_no LIKE ? OR
        r.equipment_serial_no LIKE ?
    ) ";
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $types .= "sssssssssssss";
}

// Query
$sql = "SELECT 
            s.*, 
            COALESCE(NULLIF(s.equipment_no, ''), NULLIF(pi.equipment_id, ''), NULLIF(ci.equipment_no, ''), NULLIF(r.equipment_id_no, '')) AS equipment_id,
            COALESCE(NULLIF(s.equipment_serial_no, ''), NULLIF(ci.crane_serial_no, ''), NULLIF(r.equipment_serial_no, '')) AS equipment_serial_no,
            COALESCE(
                r.next_inspection_due_date,
                (SELECT next_inspection_due_date FROM reports WHERE sticker_number_issued = s.sticker_start_no ORDER BY next_inspection_due_date DESC LIMIT 1)
            ) as next_due
        FROM stickers s 
        LEFT JOIN project_info pi ON s.project_no = pi.project_no 
        LEFT JOIN checklist_information ci ON s.project_no = ci.project_no 
        LEFT JOIN reports r ON s.sticker_start_no = r.sticker_number_issued
        $where 
        ORDER BY s.created_at DESC";

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
fputcsv($output, array('Sticker ID', 'Project ID', 'Equipment Id', 'Equipment Serial No', 'Inspect By', 'Created At', 'Inspection Date', 'Expiry Date', 'Sticker Status', 'Status'));

while($row = $result->fetch_assoc()) {
    $expiry_date = $row['next_due'] ?: (!empty($row['inspection_date']) ? date("Y-m-d", strtotime($row['inspection_date'] . " +3 months")) : '');

    fputcsv($output, array(
        $row['sticker_start_no'],
        $row['project_no'] ?: '-',
        $row['equipment_id'] ?: '-',
        $row['equipment_serial_no'] ?: '-',
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
