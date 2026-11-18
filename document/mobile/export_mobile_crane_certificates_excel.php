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
    $where .= " AND mc.inspector_name = '" . mysqli_real_escape_string($conn, $username) . "' ";
}

$dateField = "COALESCE(NULLIF(mc.examination_date, '0000-00-00'), NULLIF(DATE(mc.created_at), '0000-00-00'))";

if (!empty($filterInspector)) {
    $where .= " AND mc.inspector_name = '" . mysqli_real_escape_string($conn, $filterInspector) . "' ";
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
    $where .= " AND mc.customer_name = '" . mysqli_real_escape_string($conn, $filterClient) . "' ";
}
if (!empty($filterYear)) {
    $where .= " AND YEAR($dateField) = '" . mysqli_real_escape_string($conn, $filterYear) . "' ";
}
if ($filterStatus === 'Active') {
    $where .= " AND (mc.latest_date_exam >= CURDATE() OR mc.latest_date_exam IS NULL OR mc.latest_date_exam = '0000-00-00') ";
} elseif ($filterStatus === 'Expired') {
    $where .= " AND mc.latest_date_exam < CURDATE() AND mc.latest_date_exam != '0000-00-00' AND mc.latest_date_exam IS NOT NULL ";
}

if (!empty($search)) {
    $searchSafe = mysqli_real_escape_string($conn, $search);
    $where .= " AND (
        mc.project_no LIKE '%$searchSafe%' OR
        mc.certificate_no LIKE '%$searchSafe%' OR
        mc.equipment_description LIKE '%$searchSafe%' OR
        mc.equipment_id LIKE '%$searchSafe%' OR
        mc.inspector_name LIKE '%$searchSafe%' OR
        mc.employer_address LIKE '%$searchSafe%' OR
        mc.premises_address LIKE '%$searchSafe%' OR
        DATE_FORMAT($dateField, '%d-%m-%Y') LIKE '%$searchSafe%'
    )";
}

$sql = "
SELECT
 mc.project_no AS 'Project No',
 mc.certificate_no AS 'Certificate No',
 mc.equipment_description AS 'Equipment',
 mc.equipment_id AS 'Equipment ID',
 mc.inspector_name AS 'Inspector',
 mc.customer_name AS 'Client',
 mc.premises_address AS 'Location',
 DATE_FORMAT($dateField, '%d-%m-%Y') AS 'Examination Date',
 CASE
    WHEN mc.latest_date_exam >= CURDATE() OR mc.latest_date_exam IS NULL OR mc.latest_date_exam = '0000-00-00' THEN 'Active'
    ELSE 'Expired'
 END AS 'Status'
FROM mobile_crane_loadtest mc
LEFT JOIN project_info pi ON mc.project_no = pi.project_no
$where
ORDER BY CAST(SUBSTRING(mc.project_no, 5) AS UNSIGNED) DESC";

$res = $conn->query($sql);

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Mobile_Crane_Certificates.xls");

$first = true;
while ($row = $res->fetch_assoc()) {
    if ($first) {
        echo implode("\t", array_keys($row)) . "\n";
        $first = false;
    }
    echo implode("\t", array_values($row)) . "\n";
}
exit;
