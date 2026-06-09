<?php
session_start();
include_once('../file/config.php');

// ONLY Admin Role Allowed for now
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['error' => 'Unauthorized access. Only admins can view this data.']);
    exit;
}

header('Content-Type: application/json');

// --- Filters ---
$dateFrom = trim($_GET['date_from'] ?? '');
$dateTo   = trim($_GET['date_to'] ?? '');

$whereFilters = "WHERE 1=1";
if (!empty($dateFrom)) {
    $whereFilters .= " AND DATE(survey_date) >= '" . mysqli_real_escape_string($conn, $dateFrom) . "'";
}
if (!empty($dateTo)) {
    $whereFilters .= " AND DATE(survey_date) <= '" . mysqli_real_escape_string($conn, $dateTo) . "'";
}

// Helper to count distinct values for a given column (for pie charts)
function getDistribution($conn, $column, $whereFilters) {
    if (!in_array($column, ['response_time', 'ppe', 'aramco_standards', 'overall_satisfaction', 'status', 'qualification_card'])) {
        return ['labels' => [], 'data' => []]; // Prevent arbitrary column injection
    }
    
    $sql = "SELECT `$column` as category, COUNT(*) as cnt 
            FROM customer_survey_report 
            $whereFilters AND `$column` IS NOT NULL AND `$column` != ''
            GROUP BY `$column` 
            ORDER BY cnt DESC";
            
    $res = $conn->query($sql);
    $labels = [];
    $data = [];
    
    if ($res) {
        while($r = $res->fetch_assoc()) {
            $cat = trim($r['category']);
            // Capitalize first letter logic
            if ($cat !== '') {
                $labels[] = ucwords(strtolower($cat));
                $data[] = (int)$r['cnt'];
            }
        }
    }
    return ['labels' => $labels, 'data' => $data];
}

// ============================================================
// 1. KPI SUMMARY STATS
// ============================================================
$summarySql = "SELECT 
    COUNT(*) as total_surveys,
    SUM(status = 'new') as new_clients,
    SUM(status = 'existing') as existing_clients
FROM customer_survey_report 
$whereFilters";

$sumRes = $conn->query($summarySql);
$sumData = $sumRes ? $sumRes->fetch_assoc() : [];

$total     = (int)($sumData['total_surveys'] ?? 0);
$newCli    = (int)($sumData['new_clients'] ?? 0);
$existCli  = (int)($sumData['existing_clients'] ?? 0);


// ============================================================
// 2. PIE CHART DISTRIBUTIONS
// ============================================================
$rt_dist   = getDistribution($conn, 'response_time', $whereFilters);
$ppe_dist  = getDistribution($conn, 'ppe', $whereFilters);
$aramco_dist = getDistribution($conn, 'aramco_standards', $whereFilters);
$sat_dist  = getDistribution($conn, 'overall_satisfaction', $whereFilters);
$qual_dist = getDistribution($conn, 'qualification_card', $whereFilters);
$status_dist = getDistribution($conn, 'status', $whereFilters);


// ============================================================
// 3. MONTHLY TREND (Line Chart)
// ============================================================
$trendSql = "SELECT 
    DATE_FORMAT(survey_date, '%Y-%m') AS ym,
    DATE_FORMAT(survey_date, '%b %Y') AS label,
    COUNT(*) AS total
FROM customer_survey_report 
$whereFilters
GROUP BY ym, label 
ORDER BY ym ASC LIMIT 12";

$trendRes = $conn->query($trendSql);
$monthlyLabels = [];
$monthlyData = [];
if ($trendRes) {
    while($r = $trendRes->fetch_assoc()) {
        $monthlyLabels[] = $r['label'];
        $monthlyData[] = (int)$r['total'];
    }
}

// ============================================================
// Return Payload
// ============================================================
echo json_encode([
    'kpi' => [
        'total'    => $total,
        'new'      => $newCli,
        'existing' => $existCli
    ],
    'distributions' => [
        'response_time' => $rt_dist,
        'ppe' => $ppe_dist,
        'aramco' => $aramco_dist,
        'satisfaction' => $sat_dist,
        'qualification' => $qual_dist,
        'status' => $status_dist
    ],
    'trend' => [
        'labels' => $monthlyLabels,
        'data'   => $monthlyData
    ]
]);
