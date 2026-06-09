<?php
session_start();
include_once('../../file/config.php');

$role=$_SESSION['role'];
$username=$_SESSION['username'];

$where="WHERE 1 ";
if($role==='inspector'){
    $where.=" AND chc.inspector='".mysqli_real_escape_string($conn,$username)."' ";
}

$sql="
SELECT
 chc.project_no AS 'Project No',
 chc.certificate_no AS 'Certificate No',
 chc.vessel_name_location AS 'Inspected Item',
 chc.serial_number AS 'Serial No',
 chc.inspector AS 'Inspector',
 chc.customer_name AS 'Client',
 chc.asset_number AS 'Location',
 DATE_FORMAT(chc.created_at,'%d-%m-%Y') AS 'Created Date'
FROM crane_health_check_certificate chc
LEFT JOIN project_info pi ON chc.project_no = pi.project_no
$where
ORDER BY CAST(chc.project_no AS UNSIGNED) DESC";

$res=$conn->query($sql);

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Health_Check_Certificates.xls");

$first=true;
while($row=$res->fetch_assoc()){
    if($first){
        echo implode("\t",array_keys($row))."\n";
        $first=false;
    }
    echo implode("\t",array_values($row))."\n";
}
exit;
