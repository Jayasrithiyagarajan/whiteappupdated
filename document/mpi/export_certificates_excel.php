<?php
session_start();
include_once('../../file/config.php');

if (!isset($_SESSION['username'])) {
    exit('Unauthorized');
}

$role     = $_SESSION['role'];
$username = $_SESSION['username'];

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
header("Content-Disposition: attachment; filename=MPI_Certificates_" . date('Ymd_His') . ".xls");

/* WHERE */
$where = " WHERE 1 ";

if ($role === 'inspector') {
    $where .= " AND mc.inspector='".mysqli_real_escape_string($conn,$username)."' ";
}

$dateField = "COALESCE(NULLIF(mc.inspection_date, '0000-00-00'), NULLIF(DATE(mc.created_at), '0000-00-00'))";

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
    $where .= " AND mc.inspector='".mysqli_real_escape_string($conn,$filterInspector)."' ";
}
if (!empty($filterClient)) {
    $where .= " AND mc.customer_name='".mysqli_real_escape_string($conn,$filterClient)."' ";
}
if (!empty($filterYear)) {
    $where .= " AND YEAR($dateField)='".mysqli_real_escape_string($conn,$filterYear)."' ";
}
if ($filterStatus === 'Active') {
    $where .= " AND (mc.next_inspection_date >= CURDATE() OR mc.next_inspection_date IS NULL OR mc.next_inspection_date = '0000-00-00')";
} elseif ($filterStatus === 'Expired') {
    $where .= " AND mc.next_inspection_date < CURDATE() AND mc.next_inspection_date != '0000-00-00' AND mc.next_inspection_date IS NOT NULL";
}

/* KEYWORD SEARCH */
if ($search !== "") {
    $searchSafe = mysqli_real_escape_string($conn, $search);
    $keywords = explode(" ", $searchSafe);

    foreach ($keywords as $word) {
        $where .= " AND (
            mc.project_no LIKE '%$word%' OR
            mc.certificate_no LIKE '%$word%' OR
            mc.inspected_item LIKE '%$word%' OR
            mc.serial_numbers LIKE '%$word%' OR
            mc.inspector LIKE '%$word%' OR
            mc.customer_name LIKE '%$word%' OR
            mc.location LIKE '%$word%' OR
            DATE_FORMAT($dateField,'%d-%m-%Y') LIKE '%$word%' OR
            DATE_FORMAT($dateField,'%Y-%m-%d') LIKE '%$word%'
        )";
    }
}

/* QUERY */
$query = "
SELECT
    mc.project_no,
    mc.certificate_no,
    mc.inspected_item,
    mc.serial_numbers,
    mc.inspector,
    mc.customer_name,
    mc.location,
    mc.inspection_date,
    mc.created_at,
    mc.next_inspection_date,
    pi.project_status
FROM mpi_certificates mc
LEFT JOIN project_info pi ON mc.project_no=pi.project_no
$where
ORDER BY CAST(SUBSTRING(mc.project_no,5) AS UNSIGNED) DESC
";

$result = $conn->query($query);

/* EXCEL TABLE */
echo "
<table border='1'>
<tr style='background:#f2f2f2;font-weight:bold'>
    <th>Project No</th>
    <th>Certificate No</th>
    <th>Inspected Item</th>
    <th>Serial No</th>
    <th>Inspector</th>
    <th>Client</th>
    <th>Location</th>
    <th>Inspection Date</th>
    <th>Status</th>
</tr>";

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $rawDate = (!empty($row['inspection_date']) && $row['inspection_date'] !== '0000-00-00') ? $row['inspection_date'] : ($row['created_at'] ?? '');
        $dispDate = (!empty($rawDate) && $rawDate !== '0000-00-00') ? date('d-m-Y', strtotime($rawDate)) : '-';

        $status = 'Active';
        if (!empty($row['next_inspection_date']) && $row['next_inspection_date'] != '0000-00-00' && strtotime($row['next_inspection_date']) < time()) {
            $status = 'Expired';
        }

        echo "
        <tr>
            <td>".htmlspecialchars($row['project_no'] ?? '')."</td>
            <td>".htmlspecialchars($row['certificate_no'] ?? '')."</td>
            <td>".htmlspecialchars($row['inspected_item'] ?? '')."</td>
            <td>".htmlspecialchars($row['serial_numbers'] ?? '')."</td>
            <td>".htmlspecialchars($row['inspector'] ?? '')."</td>
            <td>".htmlspecialchars($row['customer_name'] ?? '')."</td>
            <td>".htmlspecialchars($row['location'] ?? '')."</td>
            <td>{$dispDate}</td>
            <td>{$status}</td>
        </tr>";
    }
}

echo "</table>";
