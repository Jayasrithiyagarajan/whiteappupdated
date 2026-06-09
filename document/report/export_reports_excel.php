<?php
session_start();
include_once('../../file/config.php');

$user=$_SESSION['username'];
$role=$_SESSION['role'];

$where="WHERE 1 ";
if($role==='inspector'){
    $where.=" AND r.issued_by='".mysqli_real_escape_string($conn,$user)."' ";
}

$sql="
SELECT
 r.project_no AS 'Project No',
 r.report_no AS 'Report No',
 r.checklist_no AS 'Checklist No',
 DATE_FORMAT(r.date_of_inspection,'%d-%m-%Y') AS 'Inspection Date',
 r.client_company_name AS 'Company',
 r.equipment_id_no AS 'Equipment ID',
 r.equipment_serial_no AS 'Serial No',
 r.sticker_number_issued AS 'Sticker No',
 r.location AS 'Location',
 r.issued_by AS 'Inspector'
FROM reports r
JOIN project_info p ON r.project_no=p.project_no
$where
ORDER BY CAST(r.project_no AS UNSIGNED) DESC";

$res=$conn->query($sql);

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Reports_List.xls");

$first=true;
while($row=$res->fetch_assoc()){
    if($first){
        echo implode("\t",array_keys($row))."\n";
        $first=false;
    }
    echo implode("\t",array_values($row))."\n";
}
exit;
