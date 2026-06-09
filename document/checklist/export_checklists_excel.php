<?php
session_start();
include_once('../../file/config.php');

$user=$_SESSION['username'];
$role=$_SESSION['role'];

$where="WHERE 1 ";
if (!in_array($role,['admin','document controller','quality controller','reviewer'])) {
    $where.=" AND inspected_by='".mysqli_real_escape_string($conn,$user)."' ";
}

$sql="
SELECT
 checklist_no AS 'Checklist No',
 project_no AS 'Project No',
 inspected_by AS 'Inspector',
 equipment_type AS 'Equipment',
 checklist_type AS 'Checklist Type',
 client_name AS 'Company',
 equipment_no AS 'Equipment No',
 crane_serial_no AS 'Serial No',
 sticker_no AS 'Sticker No',
 location AS 'Location',
 DATE_FORMAT(created_at,'%d-%m-%Y') AS 'Created Date'
FROM checklist_information
$where
ORDER BY created_at DESC";

$res=$conn->query($sql);

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Checklist_List.xls");

$first=true;
while($row=$res->fetch_assoc()){
    if($first){
        echo implode("\t",array_keys($row))."\n";
        $first=false;
    }
    echo implode("\t",array_values($row))."\n";
}
exit;
