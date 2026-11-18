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
    $where .= " AND w.inspector_name = '" . mysqli_real_escape_string($conn, $username) . "' ";
}

$dateField = "COALESCE(NULLIF(w.examination_date, '0000-00-00'), NULLIF(DATE(w.created_at), '0000-00-00'))";

if (!empty($filterInspector)) {
    $where .= " AND w.inspector_name = '" . mysqli_real_escape_string($conn, $filterInspector) . "' ";
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
    $where .= " AND w.customer_name LIKE '%" . mysqli_real_escape_string($conn, $filterClient) . "%' ";
}
if (!empty($filterStatus)) {
    if ($filterStatus === 'active') {
        $where .= " AND (w.latest_date_exam >= CURDATE() OR w.latest_date_exam IS NULL OR w.latest_date_exam = '0000-00-00') ";
    } elseif ($filterStatus === 'expired') {
        $where .= " AND (w.latest_date_exam < CURDATE() AND w.latest_date_exam != '0000-00-00' AND w.latest_date_exam IS NOT NULL) ";
    }
}
if (!empty($filterYear)) {
    $where .= " AND YEAR($dateField) = '" . intval($filterYear) . "' ";
}

if (!empty($search)) {
    $searchSafe = mysqli_real_escape_string($conn, $search);
    $where .= " AND (
        w.project_no LIKE '%$searchSafe%' OR
        w.certificate_no LIKE '%$searchSafe%' OR
        w.equipment_description LIKE '%$searchSafe%' OR
        w.inspector_name LIKE '%$searchSafe%' OR
        w.customer_name LIKE '%$searchSafe%' OR
        w.premises_address LIKE '%$searchSafe%' OR
        DATE_FORMAT($dateField, '%d-%m-%Y') LIKE '%$searchSafe%'
    )";
}

$sql = "
SELECT
 w.project_no AS 'Project No',
 w.certificate_no AS 'Certificate No',
 w.equipment_description AS 'Equipment',
 w.equipment_id AS 'Equipment ID',
 w.inspector_name AS 'Inspector',
 w.customer_name AS 'Client',
 w.premises_address AS 'Location',
 DATE_FORMAT($dateField, '%d-%m-%Y') AS 'Examination Date',
 CASE
    WHEN w.latest_date_exam >= CURDATE() OR w.latest_date_exam IS NULL OR w.latest_date_exam = '0000-00-00' THEN 'Active'
    ELSE 'Expired'
 END AS 'Status'
FROM withload w
LEFT JOIN project_info pi ON w.project_no = pi.project_no
$where
ORDER BY CAST(SUBSTRING(w.project_no, 5) AS UNSIGNED) DESC";

$res = $conn->query($sql);

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=With_Load_Test_Certificates.xls");

$first = true;
while ($row = $res->fetch_assoc()) {
    if ($first) {
        echo implode("\t", array_keys($row)) . "\n";
        $first = false;
    }
    echo implode("\t", array_values($row)) . "\n";
}
exit;
