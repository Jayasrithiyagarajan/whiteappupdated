<?php
session_start();
include_once('../../file/config.php');

if (strtolower($_SESSION['role'] ?? '') !== 'admin') {
    http_response_code(403);
    echo json_encode([
        'inspectors' => [],
        'clients' => []
    ]);
    exit;
}

// Fetch unique inspectors
$inspectors = [];
$res = $conn->query("SELECT DISTINCT inspector_name FROM project_info WHERE inspector_name IS NOT NULL AND inspector_name != '' ORDER BY inspector_name ASC");
while($row = $res->fetch_assoc()){
    $inspectors[] = $row['inspector_name'];
}

// Fetch unique clients
$clients = [];
$res2 = $conn->query("SELECT DISTINCT customer_name FROM project_info WHERE customer_name IS NOT NULL AND customer_name != '' ORDER BY customer_name ASC");
while($row = $res2->fetch_assoc()){
    $clients[] = $row['customer_name'];
}

echo json_encode([
    'inspectors' => $inspectors,
    'clients' => $clients
]);
?>
