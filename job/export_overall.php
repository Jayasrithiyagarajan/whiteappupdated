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
$statusFilter    = $_GET['status_filter'] ?? '';
$inspectorFilter = $_GET['filter_inspector'] ?? '';
$clientFilter    = $_GET['filter_client'] ?? '';
$dateFrom        = $_GET['filter_date_from'] ?? '';
$dateTo          = $_GET['filter_date_to'] ?? '';
$yearFilter      = $_GET['filter_year'] ?? '';
$expiryFilter    = $_GET['filter_expiry_status'] ?? '';

// Base Query
$sqlBase = "FROM project_info pi LEFT JOIN checklist_information ci ON pi.project_no = ci.project_no";
$where = " WHERE 1=1 "; 
$params = [];
$types = "";

// Role-based filtering
if ($role === 'customer') {
    $where .= " AND pi.customer_name = ? ";
    $params[] = $user;
    $types .= "s";
} elseif (!in_array($role, ['admin','reviewer','quality controller','document controller'])) {
    $where .= " AND pi.inspector_name = ? ";
    $params[] = $user;
    $types .= "s";
}

// --- APPLY FILTERS ---

if (!empty($inspectorFilter)) {
    $where .= " AND pi.inspector_name = ? ";
    $params[] = $inspectorFilter;
    $types .= "s";
}
if (!empty($clientFilter)) {
    $where .= " AND pi.customer_name = ? ";
    $params[] = $clientFilter;
    $types .= "s";
}
if (!empty($dateFrom)) {
    $where .= " AND DATE(pi.creation_date) >= ? ";
    $params[] = $dateFrom;
    $types .= "s";
}
if (!empty($dateTo)) {
    $where .= " AND DATE(pi.creation_date) <= ? ";
    $params[] = $dateTo;
    $types .= "s";
}
if (!empty($yearFilter)) {
    $where .= " AND YEAR(pi.creation_date) = ? ";
    $params[] = $yearFilter;
    $types .= "s";
}
if (!empty($statusFilter)) {
    $where .= " AND pi.project_status = ? ";
    $params[] = $statusFilter;
    $types .= "s";
}

// Active/Expired Logic
if (!empty($expiryFilter)) {
    if ($expiryFilter === 'Expired') {
        $where .= " AND (SELECT MAX(next_inspection_due_date) FROM reports r WHERE r.project_no = pi.project_no) < CURDATE() ";
    } elseif ($expiryFilter === 'Active') {
        $where .= " AND ( (SELECT MAX(next_inspection_due_date) FROM reports r WHERE r.project_no = pi.project_no) >= CURDATE() OR (SELECT MAX(next_inspection_due_date) FROM reports r WHERE r.project_no = pi.project_no) IS NULL ) ";
    }
}


// Global Search
if (!empty($search)) {
    $searchWildcard = "%{$search}%";
    $where .= " AND (
        pi.project_no LIKE ? OR
        pi.customer_name LIKE ? OR
        pi.inspector_name LIKE ? OR
        pi.equipment_id LIKE ? OR
        pi.equipment_location LIKE ? OR
        pi.checklist_type LIKE ? OR
        pi.project_status LIKE ? OR
        ci.sticker_no LIKE ?
    ) ";
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $types .= "ssssssss";
}

// Query
// We need creation date DESC
$sql = "SELECT pi.*, ci.sticker_no, 
       (SELECT MAX(next_inspection_due_date) FROM reports r WHERE r.project_no = pi.project_no) as due_date
       $sqlBase $where ORDER BY pi.creation_date DESC";

$stmt = $conn->prepare($sql);
if(!empty($params)){
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Headers
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="overall_jobs_'.date('Y-m-d_H-i-s').'.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, array('Project No', 'Date', 'Checklist Status', 'Report Status', 'Review Status', 'Certificate Status', 'Customer', 'Project Status', 'Expiry Status', 'Sticker No', 'Checklist Type', 'Equipment Type', 'Equipment ID', 'Location', 'Inspector'));

while($row = $result->fetch_assoc()) {
    
    // Determine expiry string
    $expiryStr = 'Active';
    if ($row['due_date']) {
        if (strtotime($row['due_date']) < time()) {
             $expiryStr = 'Expired';
        }
    }

    fputcsv($output, array(
        $row['project_no'],
        $row['creation_date'],
        $row['checklist_status'],
        $row['report_status'],
        $row['review_status'],
        $row['certificatestatus'],
        $row['customer_name'],
        $row['project_status'],
        $expiryStr,
        $row['sticker_no'],
        $row['checklist_type'],
        $row['equipment_type'],
        $row['equipment_id'],
        $row['equipment_location'],
        $row['inspector_name']
    ));
}

fclose($output);
exit();
?>
