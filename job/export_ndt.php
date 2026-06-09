<?php
session_start();
include_once('../file/config.php');

$user = $_SESSION['username'];
$role = $_SESSION['role'];

// Filter parameters
$search       = $_GET['search_value'] ?? '';
$inspectorFilter = $_GET['filter_inspector'] ?? '';
$clientFilter    = $_GET['filter_client'] ?? '';
$dateFrom        = $_GET['filter_date_from'] ?? '';
$dateTo          = $_GET['filter_date_to'] ?? '';
$yearFilter      = $_GET['filter_year'] ?? '';
$expiryFilter    = $_GET['filter_expiry_status'] ?? '';

// Base Condition
$where = " WHERE p.equipment_type = 'NDT Equipment' ";
$params = [];
$types = "";

// Role-based filtering
if ($role === 'customer') {
    $where .= " AND p.customer_name = ? ";
    $params[] = $user;
    $types .= "s";
} elseif (!in_array($role, ['admin','reviewer','quality controller','document controller'])) {
    $where .= " AND p.inspector_name = ? ";
    $params[] = $user;
    $types .= "s";
}

// --- APPLY FILTERS ---

// 1. Inspector
if (!empty($inspectorFilter)) {
    $where .= " AND p.inspector_name = ? ";
    $params[] = $inspectorFilter;
    $types .= "s";
}

// 2. Client
if (!empty($clientFilter)) {
    $where .= " AND p.customer_name = ? ";
    $params[] = $clientFilter;
    $types .= "s";
}

// 3. Date Range
if (!empty($dateFrom)) {
    $where .= " AND DATE(p.creation_date) >= ? ";
    $params[] = $dateFrom;
    $types .= "s";
}
if (!empty($dateTo)) {
    $where .= " AND DATE(p.creation_date) <= ? ";
    $params[] = $dateTo;
    $types .= "s";
}

// 4. Year
if (!empty($yearFilter)) {
    $where .= " AND YEAR(p.creation_date) = ? ";
    $params[] = $yearFilter;
    $types .= "s";
}

// 5. Active / Expired Filter
if (!empty($expiryFilter)) {
    if ($expiryFilter === 'Expired') {
        $where .= " AND (SELECT MAX(next_inspection_due_date) FROM reports r WHERE r.project_no = p.project_no) < CURDATE() ";
    } elseif ($expiryFilter === 'Active') {
        $where .= " AND ( (SELECT MAX(next_inspection_due_date) FROM reports r WHERE r.project_no = p.project_no) >= CURDATE() OR (SELECT MAX(next_inspection_due_date) FROM reports r WHERE r.project_no = p.project_no) IS NULL ) ";
    }
}

// Global Search
if (!empty($search)) {
    $searchWildcard = "%{$search}%";
    $where .= " AND (
        p.project_no LIKE ? OR
        p.customer_name LIKE ? OR
        p.inspector_name LIKE ? OR
        p.equipment_id LIKE ? OR
        p.equipment_location LIKE ? OR
        p.checklist_type LIKE ? OR
        p.project_status LIKE ? OR
        DATE_FORMAT(p.creation_date, '%d-%m-%Y') LIKE ?
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
$sql = "SELECT p.*, 
       (SELECT MAX(next_inspection_due_date) FROM reports r WHERE r.project_no = p.project_no) as due_date
       FROM project_info p 
       $where 
       ORDER BY p.creation_date DESC";

$stmt = $conn->prepare($sql);
if(!empty($params)){
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Headers
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="ndt_jobs_'.date('Y-m-d_H-i-s').'.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, array('Project No', 'Date', 'Checklist Status', 'Report Status', 'Review Status', 'Certificate Status', 'Customer', 'Project Status', 'Expiry Status', 'Checklist Type', 'Equipment Type', 'Equipment ID', 'Location', 'Inspector'));

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
        date('d-m-Y', strtotime($row['creation_date'])),
        $row['checklist_status'],
        $row['report_status'],
        $row['review_status'],
        $row['certificatestatus'],
        $row['customer_name'],
        $row['project_status'],
        $expiryStr,
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
