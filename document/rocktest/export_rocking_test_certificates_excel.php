<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include_once('../../file/config.php');

if (!isset($_SESSION['username'])) {
    exit('Unauthorized');
}

$role     = $_SESSION['role'] ?? '';
$username = $_SESSION['username'] ?? '';

/* FILTER PARAMETERS */
$filterInspector = $_GET['filter_inspector'] ?? $_POST['filter_inspector'] ?? '';
$filterDate      = $_GET['filter_date'] ?? $_POST['filter_date'] ?? '';
$filterDateFrom  = $_GET['filter_date_from'] ?? $_POST['filter_date_from'] ?? '';
$filterDateTo    = $_GET['filter_date_to'] ?? $_POST['filter_date_to'] ?? '';
$filterClient    = $_GET['filter_client'] ?? $_POST['filter_client'] ?? '';
$filterStatus    = $_GET['filter_status'] ?? $_POST['filter_status'] ?? '';
$filterYear      = $_GET['filter_year'] ?? $_POST['filter_year'] ?? '';
$search          = trim($_GET['search'] ?? $_POST['search'] ?? "");

/* FORCE DOWNLOAD */
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Rocking_Test_Certificates_" . date('Ymd_His') . ".xls");

/* WHERE */
$where = " WHERE 1=1 ";

if ($role === 'inspector') {
    $where .= " AND rt.inspector = '" . mysqli_real_escape_string($conn, $username) . "' ";
}

$dateField = "COALESCE(NULLIF(rt.this_exam_date, '0000-00-00'), NULLIF(rt.inspection_date, '0000-00-00'), NULLIF(DATE(rt.created_at), '0000-00-00'))";

if (!empty($filterDateFrom)) {
    $where .= " AND $dateField >= '" . mysqli_real_escape_string($conn, $filterDateFrom) . "' ";
}
if (!empty($filterDateTo)) {
    $where .= " AND $dateField <= '" . mysqli_real_escape_string($conn, $filterDateTo) . "' ";
}
if (empty($filterDateFrom) && empty($filterDateTo) && !empty($filterDate)) {
    $where .= " AND $dateField = '" . mysqli_real_escape_string($conn, $filterDate) . "' ";
}

if (!empty($filterInspector)) {
    $where .= " AND rt.inspector = '" . mysqli_real_escape_string($conn, $filterInspector) . "' ";
}
if (!empty($filterClient)) {
    $where .= " AND rt.customer_name = '" . mysqli_real_escape_string($conn, $filterClient) . "' ";
}
if (!empty($filterYear)) {
    $where .= " AND YEAR($dateField) = '" . mysqli_real_escape_string($conn, $filterYear) . "' ";
}
if ($filterStatus === 'Active') {
    $where .= " AND (rt.next_exam_date >= CURDATE() OR rt.next_exam_date IS NULL OR rt.next_exam_date = '0000-00-00')";
} elseif ($filterStatus === 'Expired') {
    $where .= " AND rt.next_exam_date < CURDATE() AND rt.next_exam_date != '0000-00-00' AND rt.next_exam_date IS NOT NULL";
}

/* KEYWORD SEARCH */
if ($search !== "") {
    $searchSafe = mysqli_real_escape_string($conn, $search);
    $keywords = explode(" ", $searchSafe);

    foreach ($keywords as $word) {
        $where .= " AND (
            rt.project_no LIKE '%$word%' OR
            rt.certificate_no LIKE '%$word%' OR
            rt.inspected_item_type LIKE '%$word%' OR
            rt.identification_no LIKE '%$word%' OR
            rt.inspector LIKE '%$word%' OR
            rt.customer_name LIKE '%$word%' OR
            rt.location LIKE '%$word%' OR
            DATE_FORMAT($dateField,'%d-%m-%Y') LIKE '%$word%' OR
            DATE_FORMAT($dateField,'%Y-%m-%d') LIKE '%$word%'
        )";
    }
}

/* QUERY */
$query = "
SELECT
    rt.project_no,
    rt.certificate_no,
    rt.inspected_item_type,
    rt.identification_no,
    rt.inspector,
    rt.customer_name,
    rt.location,
    rt.this_exam_date,
    rt.inspection_date,
    rt.created_at,
    rt.next_exam_date,
    pi.project_status
FROM rocking_test_certificate rt
LEFT JOIN project_info pi ON rt.project_no = pi.project_no
$where
ORDER BY CAST(SUBSTRING(rt.project_no, 5) AS UNSIGNED) DESC
";

$result = $conn->query($query);

/* EXCEL TABLE */
echo "
<table border='1'>
<tr style='background:#f2f2f2;font-weight:bold'>
    <th>Project No</th>
    <th>Certificate No</th>
    <th>Item Type</th>
    <th>Identification No</th>
    <th>Inspector</th>
    <th>Client</th>
    <th>Location</th>
    <th>Inspection Date</th>
    <th>Status</th>
</tr>";

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $rawDate = (!empty($row['this_exam_date']) && $row['this_exam_date'] !== '0000-00-00') ? $row['this_exam_date'] : ((!empty($row['inspection_date']) && $row['inspection_date'] !== '0000-00-00') ? $row['inspection_date'] : ($row['created_at'] ?? ''));
        $dispDate = (!empty($rawDate) && $rawDate !== '0000-00-00') ? date('d-m-Y', strtotime($rawDate)) : '-';

        $status = 'Active';
        if (!empty($row['next_exam_date']) && $row['next_exam_date'] != '0000-00-00' && strtotime($row['next_exam_date']) < time()) {
            $status = 'Expired';
        }

        echo "
        <tr>
            <td>".htmlspecialchars($row['project_no'] ?? '')."</td>
            <td>".htmlspecialchars($row['certificate_no'] ?? '')."</td>
            <td>".htmlspecialchars($row['inspected_item_type'] ?? '')."</td>
            <td>".htmlspecialchars($row['identification_no'] ?? '')."</td>
            <td>".htmlspecialchars($row['inspector'] ?? '')."</td>
            <td>".htmlspecialchars($row['customer_name'] ?? '')."</td>
            <td>".htmlspecialchars($row['location'] ?? '')."</td>
            <td>{$dispDate}</td>
            <td>{$status}</td>
        </tr>";
    }
}

echo "</table>";
