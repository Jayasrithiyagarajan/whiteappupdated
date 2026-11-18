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
header("Content-Disposition: attachment; filename=LMI_Certificates_" . date('Ymd_His') . ".xls");

/* WHERE */
$where = "WHERE 1=1";

if ($role === 'inspector') {
    $where .= " AND lmi.inspector = '" . mysqli_real_escape_string($conn, $username) . "' ";
}

$dateField = "COALESCE(NULLIF(lmi.inspection_date, '0000-00-00'), NULLIF(DATE(lmi.created_at), '0000-00-00'))";

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
    $where .= " AND lmi.inspector = '" . mysqli_real_escape_string($conn, $filterInspector) . "' ";
}
if (!empty($filterClient)) {
    $where .= " AND lmi.customer_name = '" . mysqli_real_escape_string($conn, $filterClient) . "' ";
}
if (!empty($filterYear)) {
    $where .= " AND YEAR($dateField) = '" . mysqli_real_escape_string($conn, $filterYear) . "' ";
}
if ($filterStatus === 'Active') {
    $where .= " AND (lmi.next_inspection_date >= CURDATE() OR lmi.next_inspection_date IS NULL OR lmi.next_inspection_date = '0000-00-00')";
} elseif ($filterStatus === 'Expired') {
    $where .= " AND (lmi.next_inspection_date < CURDATE() AND lmi.next_inspection_date != '0000-00-00' AND lmi.next_inspection_date IS NOT NULL)";
}

/* KEYWORD SEARCH */
if ($search !== "") {
    $searchSafe = mysqli_real_escape_string($conn, $search);
    $keywords = explode(" ", $searchSafe);

    foreach ($keywords as $word) {
        $where .= " AND (
            lmi.project_no LIKE '%$word%' OR
            lmi.certificate_no LIKE '%$word%' OR
            lmi.crane_make LIKE '%$word%' OR
            lmi.crane_model LIKE '%$word%' OR
            lmi.crane_id_no LIKE '%$word%' OR
            lmi.customer_name LIKE '%$word%' OR
            lmi.inspector LIKE '%$word%' OR
            lmi.location LIKE '%$word%' OR
            DATE_FORMAT($dateField, '%d-%m-%Y') LIKE '%$word%' OR
            DATE_FORMAT($dateField, '%Y-%m-%d') LIKE '%$word%'
        )";
    }
}

/* QUERY */
$query = "
SELECT 
    lmi.project_no,
    lmi.certificate_no,
    lmi.crane_make,
    lmi.crane_model,
    lmi.crane_id_no,
    lmi.customer_name,
    lmi.location,
    lmi.inspector,
    lmi.inspection_date,
    lmi.created_at,
    lmi.next_inspection_date,
    pi.project_status
FROM lmi_certificates lmi
LEFT JOIN project_info pi ON lmi.project_no = pi.project_no
$where
ORDER BY CAST(SUBSTRING(lmi.project_no, 5) AS UNSIGNED) DESC
";

$result = $conn->query($query);

/* EXCEL TABLE */
echo "
<table border='1'>
<tr style='background:#f2f2f2;font-weight:bold'>
    <th>Project No</th>
    <th>Certificate No</th>
    <th>Crane</th>
    <th>Crane ID</th>
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

        $crane = trim(($row['crane_make'] ?? '') . ' / ' . ($row['crane_model'] ?? ''), ' /');

        echo "
        <tr>
            <td>".htmlspecialchars($row['project_no'] ?? '')."</td>
            <td>".htmlspecialchars($row['certificate_no'] ?? '')."</td>
            <td>".htmlspecialchars($crane)."</td>
            <td>".htmlspecialchars($row['crane_id_no'] ?? '')."</td>
            <td>".htmlspecialchars($row['inspector'] ?? '')."</td>
            <td>".htmlspecialchars($row['customer_name'] ?? '')."</td>
            <td>".htmlspecialchars($row['location'] ?? '')."</td>
            <td>{$dispDate}</td>
            <td>{$status}</td>
        </tr>";
    }
}

echo "</table>";
