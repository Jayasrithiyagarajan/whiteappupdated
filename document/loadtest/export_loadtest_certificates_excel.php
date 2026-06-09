<?php
session_start();
include_once('../../file/config.php');

$role=$_SESSION['role'];
$username=$_SESSION['username'];

$where="WHERE 1 ";
if($role==='inspector'){
    $where.=" AND lc.inspector_name='".mysqli_real_escape_string($conn,$username)."' ";
}

$sql="
SELECT
 lc.project_no AS 'Project No',
 lc.certificate_no AS 'Certificate No',
 lc.equipment_description AS 'Equipment',
 lc.equipment_id AS 'Equipment ID',
 lc.inspector_name AS 'Inspector',
 lc.employer_address AS 'Client',
 lc.premises_address AS 'Location',
 DATE_FORMAT(lc.examination_date,'%d-%m-%Y') AS 'Examination Date'
FROM loadtest_certificate lc
LEFT JOIN project_info pi ON lc.project_no = pi.project_no
$where
ORDER BY CAST(lc.project_no AS UNSIGNED) DESC";

$res=$conn->query($sql);

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Loadtest_Certificates.xls");

$first=true;
while($row=$res->fetch_assoc()){
    if($first){
        echo implode("\t",array_keys($row))."\n";
        $first=false;
    }
    echo implode("\t",array_values($row))."\n";
}
exit;
