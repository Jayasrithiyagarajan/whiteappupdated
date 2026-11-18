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
$search          = $_GET['search'] ?? $_POST['search'] ?? '';

$where = "WHERE 1=1 ";
if ($role === 'inspector') {
    $where .= " AND lc.inspector_name = '" . mysqli_real_escape_string($conn, $username) . "' ";
}

$dateField = "COALESCE(NULLIF(lc.examination_date, '0000-00-00'), NULLIF(DATE(lc.created_at), '0000-00-00'))";

if (!empty($filterInspector)) {
    $where .= " AND lc.inspector_name = '" . mysqli_real_escape_string($conn, $filterInspector) . "' ";
}
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
    $where .= " AND lc.customer_name = '" . mysqli_real_escape_string($conn, $filterClient) . "' ";
}
if (!empty($filterYear)) {
    $where .= " AND YEAR($dateField) = '" . mysqli_real_escape_string($conn, $filterYear) . "' ";
}
if ($filterStatus === 'Active') {
    $where .= " AND (lc.latest_date_exam >= CURDATE() OR lc.latest_date_exam IS NULL OR lc.latest_date_exam = '0000-00-00') ";
} elseif ($filterStatus === 'Expired') {
    $where .= " AND lc.latest_date_exam < CURDATE() AND lc.latest_date_exam != '0000-00-00' AND lc.latest_date_exam IS NOT NULL ";
}

if (!empty($search)) {
    $searchSafe = mysqli_real_escape_string($conn, $search);
    $where .= " AND (
        lc.project_no LIKE '%$searchSafe%' OR
        lc.certificate_no LIKE '%$searchSafe%' OR
        lc.equipment_description LIKE '%$searchSafe%' OR
        lc.equipment_id LIKE '%$searchSafe%' OR
        lc.inspector_name LIKE '%$searchSafe%' OR
        lc.employer_address LIKE '%$searchSafe%' OR
        lc.premises_address LIKE '%$searchSafe%' OR
        DATE_FORMAT($dateField, '%d-%m-%Y') LIKE '%$searchSafe%'
    )";
}

$sql = "
SELECT
 lc.project_no AS 'Project No',
 lc.certificate_no AS 'Certificate No',
 lc.equipment_description AS 'Equipment',
 lc.equipment_id AS 'Equipment ID',
 lc.inspector_name AS 'Inspector',
 lc.employer_address AS 'Client',
 lc.premises_address AS 'Location',
 DATE_FORMAT($dateField, '%d-%m-%Y') AS 'Examination Date',
 CASE
    WHEN lc.latest_date_exam >= CURDATE() OR lc.latest_date_exam IS NULL OR lc.latest_date_exam = '0000-00-00' THEN 'Active'
    ELSE 'Expired'
 END AS 'Status'
FROM loadtest_certificate lc
LEFT JOIN project_info pi ON lc.project_no = pi.project_no
$where
ORDER BY CAST(SUBSTRING(lc.project_no, 5) AS UNSIGNED) DESC";

$res = $conn->query($sql);

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Loadtest_Certificates.xls");

$first = true;
while ($row = $res->fetch_assoc()) {
    if ($first) {
        echo implode("\t", array_keys($row)) . "\n";
        $first = false;
    }
    echo implode("\t", array_values($row)) . "\n";
}
exit;
