<?php
session_start();
include_once('../../file/config.php');

$role     = $_SESSION['role'] ?? '';
$username = $_SESSION['username'] ?? '';

$filterInspector = $_GET['filter_inspector'] ?? $_POST['filter_inspector'] ?? '';
$filterDate      = $_GET['filter_date'] ?? $_POST['filter_date'] ?? '';
$filterDateFrom  = $_GET['filter_date_from'] ?? $_POST['filter_date_from'] ?? '';
$filterDateTo    = $_GET['filter_date_to'] ?? $_POST['filter_date_to'] ?? '';
$filterClient    = $_GET['filter_client'] ?? $_POST['filter_client'] ?? '';
$filterStatus    = $_GET['filter_status'] ?? $_POST['filter_status'] ?? '';
$filterYear      = $_GET['filter_year'] ?? $_POST['filter_year'] ?? '';

$where = "WHERE 1=1 ";
if ($role === 'inspector') {
    $where .= " AND lgc.inspector = '" . mysqli_real_escape_string($conn, $username) . "' ";
}

if (!empty($filterInspector)) {
    $where .= " AND lgc.inspector = '" . mysqli_real_escape_string($conn, $filterInspector) . "' ";
}

$dateField = "COALESCE(NULLIF(lgc.date_of_this_examination, '0000-00-00'), NULLIF(DATE(lgc.created_at), '0000-00-00'))";

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
    $where .= " AND lgc.customer_name = '" . mysqli_real_escape_string($conn, $filterClient) . "' ";
}
if (!empty($filterYear)) {
    $where .= " AND YEAR($dateField) = '" . mysqli_real_escape_string($conn, $filterYear) . "' ";
}

if ($filterStatus === 'Active') {
    $where .= " AND (lgc.next_examination_date >= CURDATE() OR lgc.next_examination_date IS NULL OR lgc.next_examination_date = '0000-00-00') ";
} elseif ($filterStatus === 'Expired') {
    $where .= " AND lgc.next_examination_date < CURDATE() AND lgc.next_examination_date != '0000-00-00' AND lgc.next_examination_date IS NOT NULL ";
}

$sql = "
SELECT
 lgc.project_no AS 'Project No',
 lgc.certificate_no AS 'Certificate No',
 lgc.type AS 'Inspected Item',
 lgc.inspector AS 'Inspector',
 lgc.customer_name AS 'Client',
 lgc.address_of_premises AS 'Location',
 DATE_FORMAT($dateField, '%d-%m-%Y') AS 'Inspection Date',
 CASE
    WHEN lgc.next_examination_date >= CURDATE() OR lgc.next_examination_date IS NULL OR lgc.next_examination_date = '0000-00-00' THEN 'Active'
    ELSE 'Expired'
 END AS 'Status'
FROM lifting_gear_certificates lgc
LEFT JOIN project_info pi ON lgc.project_no = pi.project_no
$where
ORDER BY CAST(SUBSTRING(lgc.project_no, 5) AS UNSIGNED) DESC
";

$res = $conn->query($sql);

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Lifting_Gear_Certificates.xls");

$first = true;
while ($row = $res->fetch_assoc()) {
    if ($first) {
        echo implode("\t", array_keys($row)) . "\n";
        $first = false;
    }
    echo implode("\t", array_values($row)) . "\n";
}
exit;
