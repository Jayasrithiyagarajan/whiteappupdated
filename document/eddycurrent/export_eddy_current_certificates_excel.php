<?php
session_start();
include_once('../../file/config.php');

$role=$_SESSION['role'];
$username=$_SESSION['username'];

$where="WHERE 1 ";
if($role==='inspector'){
    $where.=" AND ec.inspector='".mysqli_real_escape_string($conn,$username)."' ";
}

$sql="
SELECT
 ec.project_no AS 'Project No',
 ec.certificate_no AS 'Certificate No',
 ec.inspected_item AS 'Inspected Item',
 ec.serial_no AS 'Serial No',
 ec.inspector AS 'Inspector',
 ec.customer_name AS 'Client',
 ec.location AS 'Location',
 DATE_FORMAT(ec.inspection_date,'%d-%m-%Y') AS 'Inspection Date'
FROM eddy_current_inspection ec
LEFT JOIN project_info pi ON ec.project_no=pi.project_no
$where
ORDER BY CAST(ec.project_no AS UNSIGNED) DESC";

$res=$conn->query($sql);

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Eddy_Current_Certificates.xls");

$first=true;
while($row=$res->fetch_assoc()){
    if($first){
        echo implode("\t",array_keys($row))."\n";
        $first=false;
    }
    echo implode("\t",array_values($row))."\n";
}
exit;
