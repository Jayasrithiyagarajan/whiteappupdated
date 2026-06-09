<?php
session_start();
include '../file/config.php';

$status = $_GET['status'] ?? '';

$where = " WHERE 1=1 ";
if ($status !== '') {
    $where .= " AND project_status='".mysqli_real_escape_string($conn,$status)."'";
}

$sql = "SELECT * FROM project_info $where ORDER BY project_no DESC";
$res = $conn->query($sql);

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=job_list.xls");

echo "Project No\tDate\tCustomer\tStatus\tEquipment\tInspector\n";

while ($r=$res->fetch_assoc()) {
    echo "{$r['project_no']}\t{$r['creation_date']}\t{$r['customer_name']}\t{$r['project_status']}\t{$r['equipment_id']}\t{$r['inspector_name']}\n";
}
