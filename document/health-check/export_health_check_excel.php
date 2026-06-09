<?php
session_start();
include_once('../../file/config.php');

$role     = $_SESSION['role'] ?? '';
$username = $_SESSION['username'] ?? '';

$where = "WHERE 1=1";

if ($role === 'inspector') {
    $where .= " AND inspector = '".$conn->real_escape_string($username)."'";
}

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Health_Check_List.xls");

echo "Project No\tCertificate No\tItem\tSerial No\tInspector\tClient\tLocation\tDate\n";

$sql = "
SELECT project_no, certificate_no, vessel_name_location,
       serial_number, inspector, customer_name,
       asset_number, created_at
FROM crane_health_check_certificate
$where
ORDER BY created_at DESC
";

$res = $conn->query($sql);

while ($r = $res->fetch_assoc()) {
    echo "{$r['project_no']}\t{$r['certificate_no']}\t{$r['vessel_name_location']}\t{$r['serial_number']}\t{$r['inspector']}\t{$r['customer_name']}\t{$r['asset_number']}\t".date('d-m-Y', strtotime($r['created_at']))."\n";
}
