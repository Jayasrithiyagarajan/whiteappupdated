<?php
session_start();
include_once('../file/config.php');

$logged_in_user = $_SESSION['username'] ?? null;
$userRole       = $_SESSION['role'] ?? null;

if (!$logged_in_user) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$where_clause = " 1 = 1 ";
$params = [];
$types = "";

if ($userRole === 'inspector') {
    $where_clause .= " AND inspector_id = (SELECT user_id FROM new_users WHERE username = ?) ";
    $params[] = $logged_in_user;
    $types .= "s";
}

$response = [
    'status_distribution' => [
        'labels' => ['Pending', 'In Progress', 'Completed'],
        'data' => [0, 0, 0]
    ],
    'monthly_trend' => [
        'labels' => [],
        'data' => []
    ]
];

// 1. Status Distribution
$sql_status = "SELECT status, COUNT(*) as count FROM operator_assessments WHERE $where_clause GROUP BY status";
$stmt = $conn->prepare($sql_status);
if ($types) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$status_map = [
    'PENDING' => 0,
    'IN_PROGRESS' => 1,
    'COMPLETED' => 2
];

while ($row = $result->fetch_assoc()) {
    $status = $row['status'];
    if (isset($status_map[$status])) {
        $response['status_distribution']['data'][$status_map[$status]] = (int)$row['count'];
    }
}

// 2. Monthly Trend (Last 6 Months)
for ($i = 5; $i >= 0; $i--) {
    $month = date('M Y', strtotime("-$i months"));
    $response['monthly_trend']['labels'][] = $month;
    $response['monthly_trend']['data'][$month] = 0;
}

$sql_trend = "SELECT DATE_FORMAT(date, '%b %Y') as month_year, COUNT(*) as count 
              FROM operator_assessments 
              WHERE date >= DATE_SUB(CURDATE(), INTERVAL 5 MONTH) 
              AND $where_clause 
              GROUP BY month_year";

$stmt2 = $conn->prepare($sql_trend);
if ($types) {
    $stmt2->bind_param($types, ...$params);
}
$stmt2->execute();
$result2 = $stmt2->get_result();

while ($row = $result2->fetch_assoc()) {
    $month_year = $row['month_year'];
    if (isset($response['monthly_trend']['data'][$month_year])) {
        $response['monthly_trend']['data'][$month_year] = (int)$row['count'];
    }
}

// Re-index monthly trend data
$response['monthly_trend']['data'] = array_values($response['monthly_trend']['data']);

echo json_encode($response);
