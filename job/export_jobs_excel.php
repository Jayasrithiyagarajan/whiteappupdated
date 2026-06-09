<?php
session_start();
include '../file/config.php';

$user=$_SESSION['username'];
$role=$_SESSION['role'];

$where="WHERE 1 ";

if ($role==='customer') {
    $where.=" AND pi.customer_name='".mysqli_real_escape_string($conn,$user)."'";
}
elseif (!in_array($role,['admin','reviewer','quality controller','document controller'])) {
    $where.=" AND pi.inspector_name='".mysqli_real_escape_string($conn,$user)."'";
}

$sql="
SELECT
 pi.project_no AS 'Project No',
 DATE_FORMAT(pi.creation_date,'%d-%m-%Y') AS 'Date',
 pi.customer_name AS 'Customer',
 pi.project_status AS 'Status',
 pi.equipment_id AS 'Equipment ID',
 ci.sticker_no AS 'Sticker No',
 pi.inspector_name AS 'Inspector'
FROM project_info pi
LEFT JOIN checklist_information ci ON pi.project_no=ci.project_no
$where
ORDER BY CAST(SUBSTRING(pi.project_no,5) AS UNSIGNED) DESC";

$res=$conn->query($sql);

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Job_List.xls");

$first=true;
while($row=$res->fetch_assoc()){
    if($first){
        echo implode("\t",array_keys($row))."\n";
        $first=false;
    }
    echo implode("\t",array_values($row))."\n";
}
exit;
