<?php
session_start();
include_once('../../file/config.php');

if (!isset($_SESSION['username'])) {
    header('Content-Type: application/json');
    echo json_encode(['inspectors' => [], 'clients' => [], 'years' => [], 'types' => []]);
    exit;
}

header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

$user = $_SESSION['username'];
$role = $_SESSION['role'] ?? '';

/* ── Role-based WHERE ─────────────────────────────────────── */
$where  = " WHERE 1=1 ";
$params = [];
$types  = "";

if (!in_array($role, ['admin', 'document controller', 'quality controller', 'reviewer'])) {
    $where .= " AND ci.inspected_by = ? ";
    $params[] = $user;
    $types  .= "s";
}

/* ── Single query to gather all distinct filter values ───── */
$sql = "
    SELECT ci.inspected_by, ci.client_name, ci.checklist_type, YEAR(ci.created_at) AS yr
    FROM checklist_information ci
    $where
";

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    echo json_encode(['inspectors' => [], 'clients' => [], 'years' => [], 'types' => []]);
    exit;
}

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$inspectors = [];
$clients    = [];
$years      = [];
$checkTypes = [];

while ($row = $result->fetch_assoc()) {
    if (!empty($row['inspected_by']))   $inspectors[$row['inspected_by']] = true;
    if (!empty($row['client_name']))    $clients[$row['client_name']]     = true;
    if (!empty($row['checklist_type'])) $checkTypes[$row['checklist_type']] = true;
    if (!empty($row['yr']))             $years[(int)$row['yr']]           = true;
}
$stmt->close();

$inspectors = array_keys($inspectors);
sort($inspectors);

$clients = array_keys($clients);
sort($clients);

$checkTypes = array_keys($checkTypes);
sort($checkTypes);

$years = array_keys($years);
rsort($years);

echo json_encode([
    'inspectors' => $inspectors,
    'clients'    => $clients,
    'years'      => $years,
    'types'      => $checkTypes
]);
?>
