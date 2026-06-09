<?php
include_once('../file/config.php');

// Ensure unique values from project_info where equipment_type is NDT
$where = " WHERE equipment_type = 'NDT Equipment' ";

$inspectors = [];
$res = $conn->query("SELECT DISTINCT inspector_name FROM project_info $where AND inspector_name IS NOT NULL AND inspector_name != '' ORDER BY inspector_name ASC");
while($row = $res->fetch_assoc()){
    $inspectors[] = $row['inspector_name'];
}

$clients = [];
$res2 = $conn->query("SELECT DISTINCT customer_name FROM project_info $where AND customer_name IS NOT NULL AND customer_name != '' ORDER BY customer_name ASC");
while($row = $res2->fetch_assoc()){
    $clients[] = $row['customer_name'];
}

$years = [];
$res3 = $conn->query("SELECT DISTINCT YEAR(creation_date) as yr FROM project_info $where AND creation_date IS NOT NULL ORDER BY yr DESC");
while($row = $res3->fetch_assoc()){
    $years[] = $row['yr'];
}

echo json_encode([
    'inspectors' => $inspectors,
    'clients' => $clients,
    'years' => $years
]);
?>
