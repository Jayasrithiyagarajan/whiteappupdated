<?php
session_start();
include_once('../../file/config.php');

$user = $_SESSION['username'] ?? '';
$role = $_SESSION['role'] ?? '';

$filterInspector = $_GET['filter_inspector'] ?? '';
$filterDate = $_GET['filter_date'] ?? '';
$filterDateFrom = $_GET['filter_date_from'] ?? '';
$filterDateTo = $_GET['filter_date_to'] ?? '';
$filterClient = $_GET['filter_client'] ?? '';
$filterYear = $_GET['filter_year'] ?? '';
$filterExpiry = $_GET['filter_expiry'] ?? '';
$search = $_GET['search_value'] ?? '';

$where = "WHERE 1 ";
if (trim(strtolower($role)) === 'inspector') {
    $where .= " AND r.issued_by='" . mysqli_real_escape_string($conn, $user) . "' ";
}

if (!empty($filterInspector)) {
    $where .= " AND r.issued_by='" . mysqli_real_escape_string($conn, $filterInspector) . "' ";
}
if (!empty($filterDateFrom)) {
    $where .= " AND DATE(r.date_of_inspection) >= '" . mysqli_real_escape_string($conn, $filterDateFrom) . "' ";
}
if (!empty($filterDateTo)) {
    $where .= " AND DATE(r.date_of_inspection) <= '" . mysqli_real_escape_string($conn, $filterDateTo) . "' ";
}
if (empty($filterDateFrom) && empty($filterDateTo) && !empty($filterDate)) {
    $where .= " AND DATE(r.date_of_inspection) = '" . mysqli_real_escape_string($conn, $filterDate) . "' ";
}
if (!empty($filterClient)) {
    $where .= " AND r.client_company_name='" . mysqli_real_escape_string($conn, $filterClient) . "' ";
}
if (!empty($filterYear)) {
    $where .= " AND YEAR(r.date_of_inspection)='" . mysqli_real_escape_string($conn, $filterYear) . "' ";
}
if (!empty($filterExpiry)) {
    $where .= " AND DATE(r.next_inspection_due_date)='" . mysqli_real_escape_string($conn, $filterExpiry) . "' ";
}

if (!empty($search)) {
    $searchEsc = mysqli_real_escape_string($conn, $search);
    $where .= " AND (
        r.project_no LIKE '%$searchEsc%' OR
        r.report_no LIKE '%$searchEsc%' OR
        r.checklist_no LIKE '%$searchEsc%' OR
        r.client_company_name LIKE '%$searchEsc%' OR
        r.equipment_id_no LIKE '%$searchEsc%' OR
        r.type LIKE '%$searchEsc%' OR
        r.equipment_serial_no LIKE '%$searchEsc%' OR
        r.no_of_equipments_inspected LIKE '%$searchEsc%' OR
        r.sticker_number_issued LIKE '%$searchEsc%' OR
        r.location LIKE '%$searchEsc%' OR
        r.issued_by LIKE '%$searchEsc%' OR
        DATE_FORMAT(r.date_of_inspection, '%d-%m-%Y') LIKE '%$searchEsc%' OR
        DATE_FORMAT(r.date_of_inspection, '%Y-%m-%d') LIKE '%$searchEsc%'
    )";
}

$sql = "
SELECT
 r.project_no AS 'Project No',
 r.report_no AS 'Report No',
 r.checklist_no AS 'Checklist No',
 DATE_FORMAT(r.date_of_inspection,'%d-%m-%Y') AS 'Inspection Date',
 r.client_company_name AS 'Company',
 r.equipment_id_no AS 'Equipment ID',
 r.type AS 'Equipment Type',
 r.equipment_serial_no AS 'Serial No',
 r.no_of_equipments_inspected AS 'No of Items Inspected',
 r.sticker_number_issued AS 'Sticker No',
 r.location AS 'Location',
 r.issued_by AS 'Inspector',
 IFNULL(DATE_FORMAT(r.next_inspection_due_date,'%d-%m-%Y'), 'N/A') AS 'Expiry Date',
 CASE 
    WHEN r.next_inspection_due_date >= CURDATE() THEN 'Active'
    WHEN r.next_inspection_due_date < CURDATE() AND r.next_inspection_due_date IS NOT NULL THEN 'Expired'
    ELSE 'N/A'
 END AS 'Status'
FROM reports r
JOIN project_info p ON r.project_no=p.project_no
$where
ORDER BY CAST(r.project_no AS UNSIGNED) DESC";

$res = $conn->query($sql);

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Reports_List.xls");

$first = true;
while ($row = $res->fetch_assoc()) {
    if ($first) {
        echo implode("\t", array_keys($row)) . "\n";
        $first = false;
    }
    echo implode("\t", array_values($row)) . "\n";
}
exit;
