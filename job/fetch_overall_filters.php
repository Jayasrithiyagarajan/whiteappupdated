<?php
include_once('../file/config.php');

// Fetch All unique Inspectors, Clients, Years
// This is used for dropdown population.
// We might want to respect role restrictions here too? Usually yes.
// But mostly this is for 'admin' who sees everything.
// Let's keep it simple: fetch all distinct. Frontend logic or role access will limit visibility if needed, 
// but filters usually show all options unless data is strictly siloed.
// If role is restricted, 'fetch_overall_jobs.php' handles the data restriction.
// Filters can show all, but selecting one not assigned to you will just yield 0 results.

$where = " WHERE 1=1 "; // No specific filter other than not null

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
