<?php
session_start();
include_once('../../file/config.php');

if (!isset($_SESSION['username'])) {
    echo json_encode(['inspectors' => [], 'clients' => [], 'years' => []]);
    exit;
}

$user = $_SESSION['username'];
$role = $_SESSION['role'];

$where = " WHERE 1 ";

/* ROLE FILTER - Inspectors only see their own data */
if (trim(strtolower($role)) === 'inspector') {
    $where .= " AND r.issued_by='".mysqli_real_escape_string($conn, $user)."' ";
}

/* FETCH UNIQUE INSPECTORS */
$inspectorQuery = "
    SELECT DISTINCT r.issued_by 
    FROM reports r 
    JOIN project_info p ON r.project_no = p.project_no
    $where
    ORDER BY r.issued_by ASC
";
$inspectorResult = $conn->query($inspectorQuery);
$inspectors = [];
while ($row = $inspectorResult->fetch_assoc()) {
    if (!empty($row['issued_by'])) {
        $inspectors[] = $row['issued_by'];
    }
}

/* FETCH UNIQUE CLIENTS */
$clientQuery = "
    SELECT DISTINCT r.client_company_name 
    FROM reports r 
    JOIN project_info p ON r.project_no = p.project_no
    $where
    ORDER BY r.client_company_name ASC
";
$clientResult = $conn->query($clientQuery);
$clients = [];
while ($row = $clientResult->fetch_assoc()) {
    if (!empty($row['client_company_name'])) {
        $clients[] = $row['client_company_name'];
    }
}

/* FETCH UNIQUE YEARS */
$yearQuery = "
    SELECT DISTINCT YEAR(r.date_of_inspection) as year 
    FROM reports r 
    JOIN project_info p ON r.project_no = p.project_no
    $where
    ORDER BY year DESC
";
$yearResult = $conn->query($yearQuery);
$years = [];
while ($row = $yearResult->fetch_assoc()) {
    if (!empty($row['year'])) {
        $years[] = (int)$row['year'];
    }
}

echo json_encode([
    'inspectors' => $inspectors,
    'clients' => $clients,
    'years' => $years
]);
