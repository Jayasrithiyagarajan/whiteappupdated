<?php
session_start();
include_once('../../file/config.php');

$role=$_SESSION['role'];
$username=$_SESSION['username'];

$where="WHERE 1 ";
if($role==='inspector'){
    $where.=" AND lgc.inspector='".mysqli_real_escape_string($conn,$username)."' ";
}

$sql="
SELECT
 lgc.project_no AS 'Project No',
 lgc.certificate_no AS 'Certificate No',
 lgc.type AS 'Inspected Item',
 lgc.inspector AS 'Inspector',
 lgc.customer_name AS 'Client',
 lgc.address_of_premises AS 'Location',
 DATE_FORMAT(lgc.date_of_this_examination,'%d-%m-%Y') AS 'Date'
FROM lifting_gear_certificates lgc
LEFT JOIN project_info pi ON lgc.project_no = pi.project_no
$where
ORDER BY CAST(lgc.project_no AS UNSIGNED) DESC
";

$res=$conn->query($sql);

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Lifting_Gear_Certificates.xls");

$first=true;
while($row=$res->fetch_assoc()){
    if($first){
        echo implode("\t",array_keys($row))."\n";
        $first=false;
    }
    echo implode("\t",array_values($row))."\n";
}
exit;
