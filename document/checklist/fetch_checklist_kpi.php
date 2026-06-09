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
if (!in_array($role, ['admin','document controller','quality controller','reviewer'])) {
    $where .= " AND ci.inspected_by='".mysqli_real_escape_string($conn,$user)."' ";
}

/* 🔍 APPLY FILTERS */
if (!empty($filterInspector)) {
    $where .= " AND ci.inspected_by='".mysqli_real_escape_string($conn,$filterInspector)."' ";
}
if (!empty($filterDate)) {
    $where .= " AND DATE(ci.created_at)='".mysqli_real_escape_string($conn,$filterDate)."' ";
}
if (!empty($filterClient)) {
    $where .= " AND ci.client_name='".mysqli_real_escape_string($conn,$filterClient)."' ";
}
if (!empty($filterYear)) {
    $where .= " AND YEAR(ci.created_at)='".mysqli_real_escape_string($conn,$filterYear)."' ";
}
if (!empty($filterExpiry)) {
    $where .= " AND DATE(r.next_inspection_due_date)='".mysqli_real_escape_string($conn,$filterExpiry)."' ";
}

/* CONSOLIDATED KPI QUERY */
$sql = "
    SELECT 
        COUNT(ci.checklist_id) AS total,
        SUM(CASE WHEN pi.project_status = 'Completed' THEN 1 ELSE 0 END) AS completed,
        SUM(CASE WHEN pi.project_status != 'Completed' OR pi.project_status IS NULL THEN 1 ELSE 0 END) AS pending,
        SUM(CASE WHEN (SELECT MAX(next_inspection_due_date) FROM reports WHERE project_no = ci.project_no) >= CURDATE() THEN 1 ELSE 0 END) AS active,
        SUM(CASE WHEN (SELECT MAX(next_inspection_due_date) FROM reports WHERE project_no = ci.project_no) < CURDATE() THEN 1 ELSE 0 END) AS expired
    FROM checklist_information ci
    LEFT JOIN project_info pi ON ci.project_no = pi.project_no
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
