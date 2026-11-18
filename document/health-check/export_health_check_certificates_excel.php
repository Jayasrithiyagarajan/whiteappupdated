<?php
session_start();
include_once('../../file/config.php');

$role     = $_SESSION['role'] ?? '';
$username = $_SESSION['username'] ?? '';

$filterInspector = $_REQUEST['filter_inspector'] ?? '';
$filterDateFrom  = $_REQUEST['filter_date_from'] ?? '';
$filterDateTo    = $_REQUEST['filter_date_to'] ?? '';
$filterDate      = $_REQUEST['filter_date'] ?? '';
$filterClient    = $_REQUEST['filter_client'] ?? '';
$filterStatus    = $_REQUEST['filter_status'] ?? '';
$filterYear      = $_REQUEST['filter_year'] ?? '';

$where = "WHERE 1=1 ";
if ($role === 'inspector') {
    $where .= " AND chc.inspector='" . mysqli_real_escape_string($conn, $username) . "' ";
}

if (!empty($filterInspector)) {
    $where .= " AND chc.inspector='" . mysqli_real_escape_string($conn, $filterInspector) . "' ";
}

$dateField = "COALESCE(NULLIF(chc.inspection_date, '0000-00-00'), NULLIF(DATE(chc.created_at), '0000-00-00'))";

if (!empty($filterDateFrom)) {
    $where .= " AND $dateField >= '" . mysqli_real_escape_string($conn, $filterDateFrom) . "' ";
}
if (!empty($filterDateTo)) {
    $where .= " AND $dateField <= '" . mysqli_real_escape_string($conn, $filterDateTo) . "' ";
}
if (empty($filterDateFrom) && empty($filterDateTo) && !empty($filterDate)) {
    $where .= " AND $dateField = '" . mysqli_real_escape_string($conn, $filterDate) . "' ";
}
if (!empty($filterClient)) {
    $where .= " AND chc.customer_name='" . mysqli_real_escape_string($conn, $filterClient) . "' ";
}
if (!empty($filterYear)) {
    $where .= " AND YEAR(chc.created_at)='" . mysqli_real_escape_string($conn, $filterYear) . "' ";
}
if ($filterStatus === 'Active') {
    $where .= " AND (r.next_inspection_due_date >= CURDATE() OR r.next_inspection_due_date IS NULL) ";
} elseif ($filterStatus === 'Expired') {
    $where .= " AND r.next_inspection_due_date < CURDATE() AND r.next_inspection_due_date IS NOT NULL ";
}

$sql = "
SELECT
 chc.project_no AS 'Project No',
 chc.certificate_no AS 'Certificate No',
 chc.vessel_name_location AS 'Inspected Item',
 chc.serial_number AS 'Serial No',
 chc.inspector AS 'Inspector',
 chc.customer_name AS 'Client',
 chc.asset_number AS 'Location',
 IFNULL(DATE_FORMAT(NULLIF(chc.inspection_date, '0000-00-00'),'%d-%m-%Y'), DATE_FORMAT(chc.created_at,'%d-%m-%Y')) AS 'Inspection Date',
 CASE 
    WHEN r.next_inspection_due_date >= CURDATE() OR r.next_inspection_due_date IS NULL THEN 'Active'
    ELSE 'Expired'
 END AS 'Status'
FROM crane_health_check_certificate chc
LEFT JOIN reports r ON r.project_no = chc.project_no AND r.report_no = chc.report_no
LEFT JOIN project_info pi ON chc.project_no = pi.project_no
$where
ORDER BY CAST(chc.project_no AS UNSIGNED) DESC";

$res = $conn->query($sql);

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Health_Check_Certificates.xls");

$first = true;
while ($row = $res->fetch_assoc()) {
    if ($first) {
        echo implode("\t", array_keys($row)) . "\n";
        $first = false;
    }
    echo implode("\t", array_values($row)) . "\n";
}
exit;
