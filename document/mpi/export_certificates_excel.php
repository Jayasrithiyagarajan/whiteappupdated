<?php
session_start();
include_once('../../file/config.php');

if (!isset($_SESSION['username'])) {
    exit('Unauthorized');
}

$role     = $_SESSION['role'];
$username = $_SESSION['username'];
$search   = trim($_GET['search'] ?? "");

/* FORCE DOWNLOAD */
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=MPI_Certificates.xls");

/* WHERE */
$where = " WHERE 1 ";

if ($role === 'inspector') {
    $where .= " AND mc.inspector='".mysqli_real_escape_string($conn,$username)."' ";
}

/* SEARCH (same logic as DataTable) */
if ($search !== "") {
    $search = mysqli_real_escape_string($conn, $search);
    $keywords = explode(" ", $search);

    foreach ($keywords as $word) {
        $where .= " AND (
            mc.project_no LIKE '%$word%' OR
            mc.certificate_no LIKE '%$word%' OR
            mc.inspected_item LIKE '%$word%' OR
            mc.serial_numbers LIKE '%$word%' OR
            mc.inspector LIKE '%$word%' OR
            mc.customer_name LIKE '%$word%' OR
            mc.location LIKE '%$word%' OR
            DATE_FORMAT(mc.inspection_date,'%d-%m-%Y') LIKE '%$word%' OR
            DATE_FORMAT(mc.inspection_date,'%Y-%m-%d') LIKE '%$word%' OR
            DATE_FORMAT(mc.inspection_date,'%Y') LIKE '%$word%' OR
            DATE_FORMAT(mc.inspection_date,'%M') LIKE '%$word%' OR
            DATE_FORMAT(mc.inspection_date,'%b') LIKE '%$word%'
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

while ($row = $result->fetch_assoc()) {
    echo "
    <tr>
        <td>{$row['project_no']}</td>
        <td>{$row['certificate_no']}</td>
        <td>{$row['inspected_item']}</td>
        <td>{$row['serial_numbers']}</td>
        <td>{$row['inspector']}</td>
        <td>{$row['customer_name']}</td>
        <td>{$row['location']}</td>
        <td>".date('d-m-Y', strtotime($row['inspection_date']))."</td>
        <td>{$row['project_status']}</td>
    </tr>";
}

echo "</table>";
