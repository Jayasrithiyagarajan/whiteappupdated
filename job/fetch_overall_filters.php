<?php
session_start();
include_once('../file/config.php');

$role = $_SESSION['role'] ?? '';
$user = $_SESSION['username'] ?? '';

$where = " WHERE 1=1 "; 

if (!in_array($role, ['admin', 'reviewer', 'quality controller', 'document controller'])) {
    if ($role === 'customer') {
        $where .= " AND customer_name = '" . $conn->real_escape_string($user) . "' ";
    } else {
        $where .= " AND inspector_name = '" . $conn->real_escape_string($user) . "' ";
    }
}

$cacheKey = 'overall_filters_' . md5($role . '_' . $user);
if (isset($_SESSION[$cacheKey]) && empty($_GET['refresh'])) {
    echo $_SESSION[$cacheKey];
    exit;
}

$inspectors = [];
$res = $conn->query("SELECT DISTINCT inspector_name FROM project_info $where AND inspector_name IS NOT NULL AND inspector_name != '' ORDER BY inspector_name ASC");
if ($res) {
    while($row = $res->fetch_assoc()){
        $inspectors[] = $row['inspector_name'];
    }
}

$clients = [];
$res2 = $conn->query("SELECT DISTINCT customer_name FROM project_info $where AND customer_name IS NOT NULL AND customer_name != '' ORDER BY customer_name ASC");
if ($res2) {
    while($row = $res2->fetch_assoc()){
        $clients[] = $row['customer_name'];
    }
}

$years = [];
$res3 = $conn->query("SELECT MIN(creation_date) as min_d, MAX(creation_date) as max_d FROM project_info $where");
if ($res3 && $row = $res3->fetch_assoc()) {
    if (!empty($row['min_d']) && !empty($row['max_d'])) {
        $minYear = (int)date('Y', strtotime($row['min_d']));
        $maxYear = (int)date('Y', strtotime($row['max_d']));
        for ($y = $maxYear; $y >= $minYear; $y--) {
            $years[] = (string)$y;
        }
    } else {
        $years[] = date('Y');
    }
}

$output = json_encode([
    'inspectors' => $inspectors,
    'clients' => $clients,
    'years' => $years
]);

$_SESSION[$cacheKey] = $output;
echo $output;
?>
