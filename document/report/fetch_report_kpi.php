<?php
session_start();
include_once('../../file/config.php');

if (!isset($_SESSION['username'])) {
    echo json_encode(['total'=>0,'completed'=>0,'pending'=>0,'active'=>0,'expired'=>0]);
    exit;
}

$user = $_SESSION['username'];
$role = $_SESSION['role'];

/* 🔥 FILTER PARAMETERS */
$filterInspector = $_GET['filter_inspector'] ?? '';
$filterDate = $_GET['filter_date'] ?? '';
$filterClient = $_GET['filter_client'] ?? '';
$filterYear = $_GET['filter_year'] ?? '';
$filterExpiry = $_GET['filter_expiry'] ?? '';

$where = " WHERE 1 ";

/* ROLE FILTER */
if (trim(strtolower($role)) === 'inspector') {
    $where .= " AND r.issued_by='".mysqli_real_escape_string($conn,$user)."' ";
}

/* 🔍 APPLY FILTERS */
if (!empty($filterInspector)) {
    $where .= " AND r.issued_by='".mysqli_real_escape_string($conn,$filterInspector)."' ";
}
if (!empty($filterDate)) {
    $where .= " AND DATE(r.date_of_inspection)='".mysqli_real_escape_string($conn,$filterDate)."' ";
}
if (!empty($filterClient)) {
    $where .= " AND r.client_company_name='".mysqli_real_escape_string($conn,$filterClient)."' ";
}
if (!empty($filterYear)) {
    $where .= " AND YEAR(r.date_of_inspection)='".mysqli_real_escape_string($conn,$filterYear)."' ";
}
if (!empty($filterExpiry)) {
    $where .= " AND DATE(r.next_inspection_due_date)='".mysqli_real_escape_string($conn,$filterExpiry)."' ";
}

/* CONSOLIDATED KPI QUERY */
$sql = "
    SELECT 
        COUNT(*) AS total,
        SUM(CASE WHEN p.report_status = 'Generated' THEN 1 ELSE 0 END) AS completed,
        SUM(CASE WHEN p.report_status = 'Pending' THEN 1 ELSE 0 END) AS pending,
        SUM(CASE WHEN r.next_inspection_due_date >= CURDATE() THEN 1 ELSE 0 END) AS active,
        SUM(CASE WHEN r.next_inspection_due_date < CURDATE() AND r.next_inspection_due_date IS NOT NULL THEN 1 ELSE 0 END) AS expired
    FROM reports r
    JOIN project_info p ON r.project_no = p.project_no
    $where
";

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

echo json_encode([
    'total'     => (int)$row['total'],
    'completed' => (int)$row['completed'],
    'pending'   => (int)$row['pending'],
    'active'    => (int)$row['active'],
    'expired'   => (int)$row['expired']
]);
