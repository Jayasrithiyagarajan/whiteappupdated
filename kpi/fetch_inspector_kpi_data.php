<?php
session_start();
include_once('../file/config.php');

header('Content-Type: application/json');

if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'inspector') {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$inspectorName = mysqli_real_escape_string($conn, $_SESSION['username']);
$dateFrom = $_GET['date_from'] ?? '';
$dateTo   = $_GET['date_to'] ?? '';

$where = " WHERE inspector_name = '$inspectorName' ";

if (!empty($dateFrom)) {
    $dateFrom = mysqli_real_escape_string($conn, $dateFrom);
    $where .= " AND DATE(creation_date) >= '$dateFrom' ";
}

if (!empty($dateTo)) {
    $dateTo = mysqli_real_escape_string($conn, $dateTo);
    $where .= " AND DATE(creation_date) <= '$dateTo' ";
}

$kpiSql = "SELECT
    COUNT(*) AS total,
    SUM(project_status = 'Completed') AS completed,
    SUM(project_status != 'Completed') AS pending,
    SUM(checklist_status = 'Pending') AS pending_checklist,
    SUM(report_status = 'Pending') AS pending_report,
    SUM(sticker_status = 'Yes') AS sticker_passed,
    SUM(sticker_status = 'No') AS sticker_failed
FROM project_info $where";

$kpiRes = $conn->query($kpiSql);
$kpiRow = $kpiRes ? $kpiRes->fetch_assoc() : [];

$total            = (int)($kpiRow['total'] ?? 0);
$completed        = (int)($kpiRow['completed'] ?? 0);
$pending          = (int)($kpiRow['pending'] ?? 0);
$pendingChecklist = (int)($kpiRow['pending_checklist'] ?? 0);
$pendingReport    = (int)($kpiRow['pending_report'] ?? 0);
$stickerPassed    = (int)($kpiRow['sticker_passed'] ?? 0);
$stickerFailed    = (int)($kpiRow['sticker_failed'] ?? 0);

$completionRate = $total > 0 ? round(($completed / $total) * 100, 2) : 0;
$stickerBase    = max(1, $stickerPassed + $stickerFailed);
$stickerRate    = round(($stickerPassed / $stickerBase) * 100, 2);

$customerSql = "SELECT COUNT(DISTINCT customer_name) AS cnt
FROM project_info
$where AND customer_name IS NOT NULL AND customer_name != ''";
$customerRes = $conn->query($customerSql);
$customerCount = $customerRes ? (int)$customerRes->fetch_assoc()['cnt'] : 0;

$equipmentCountSql = "SELECT COUNT(DISTINCT equipment_type) AS cnt
FROM project_info
$where AND equipment_type IS NOT NULL AND equipment_type != ''";
$equipmentCountRes = $conn->query($equipmentCountSql);
$equipmentTypeCount = $equipmentCountRes ? (int)$equipmentCountRes->fetch_assoc()['cnt'] : 0;

$dateLabel = 'All Time';
if (!empty($dateFrom) && !empty($dateTo)) {
    $dateLabel = date('d M Y', strtotime($dateFrom)) . ' to ' . date('d M Y', strtotime($dateTo));
} elseif (!empty($dateFrom)) {
    $dateLabel = 'From ' . date('d M Y', strtotime($dateFrom));
} elseif (!empty($dateTo)) {
    $dateLabel = 'Until ' . date('d M Y', strtotime($dateTo));
}

$monthlySql = "SELECT
    DATE_FORMAT(creation_date, '%Y-%m') AS ym,
    DATE_FORMAT(creation_date, '%b %Y') AS label,
    COUNT(*) AS total,
    SUM(project_status = 'Completed') AS completed,
    SUM(project_status != 'Completed') AS pending
FROM project_info
$where
GROUP BY ym, label
ORDER BY ym ASC
LIMIT 24";

$monthlyRes = $conn->query($monthlySql);
$monthlyLabels = [];
$monthlyTotal = [];
$monthlyCompleted = [];
$monthlyPending = [];
if ($monthlyRes) {
    while ($row = $monthlyRes->fetch_assoc()) {
        $monthlyLabels[] = $row['label'];
        $monthlyTotal[] = (int)$row['total'];
        $monthlyCompleted[] = (int)$row['completed'];
        $monthlyPending[] = (int)$row['pending'];
    }
}

$customerChartSql = "SELECT
    customer_name,
    COUNT(*) AS total,
    SUM(project_status = 'Completed') AS completed
FROM project_info
$where
AND customer_name IS NOT NULL AND customer_name != ''
GROUP BY customer_name
ORDER BY total DESC
LIMIT 8";

$customerChartRes = $conn->query($customerChartSql);
$customerNames = [];
$customerTotals = [];
$customerCompleted = [];
if ($customerChartRes) {
    while ($row = $customerChartRes->fetch_assoc()) {
        $customerNames[] = $row['customer_name'];
        $customerTotals[] = (int)$row['total'];
        $customerCompleted[] = (int)$row['completed'];
    }
}

$equipmentSql = "SELECT equipment_type, COUNT(*) AS cnt
FROM project_info
$where
AND equipment_type IS NOT NULL AND equipment_type != ''
GROUP BY equipment_type
ORDER BY cnt DESC
LIMIT 8";

$equipmentRes = $conn->query($equipmentSql);
$equipmentRows = [];
$equipmentTotal = 0;
if ($equipmentRes) {
    while ($row = $equipmentRes->fetch_assoc()) {
        $equipmentRows[] = $row;
        $equipmentTotal += (int)$row['cnt'];
    }
}

$equipmentLabels = [];
$equipmentCounts = [];
foreach ($equipmentRows as $row) {
    $pct = $equipmentTotal > 0 ? round(((int)$row['cnt'] / $equipmentTotal) * 100) : 0;
    $equipmentLabels[] = $row['equipment_type'] . ' (' . $pct . '%)';
    $equipmentCounts[] = (int)$row['cnt'];
}

$certWhere = " WHERE pi.inspector_name = '$inspectorName' ";
if (!empty($dateFrom)) {
    $certWhere .= " AND DATE(pi.creation_date) >= '$dateFrom' ";
}
if (!empty($dateTo)) {
    $certWhere .= " AND DATE(pi.creation_date) <= '$dateTo' ";
}

$certTables = [
    'MPI' => 'mpi_certificates',
    'Health Check' => 'crane_health_check_certificate',
    'Lifting Gear' => 'lifting_gear_certificates',
    'Load Test' => 'loadtest_certificate',
    'LPI' => 'liquid_penetrant_inspection',
    'Mobile Crane' => 'mobile_crane_loadtest',
    'Rocking Test' => 'rocking_test_certificate',
    'Eddy Current' => 'eddy_current_inspection',
    'With Load' => 'withload'
];

$certLabels = [];
$certCounts = [];
foreach ($certTables as $label => $table) {
    $sql = "SELECT COUNT(*) AS cnt
    FROM $table c
    LEFT JOIN project_info pi ON c.project_no = pi.project_no
    $certWhere";
    $res = $conn->query($sql);
    $certLabels[] = $label;
    $certCounts[] = $res ? (int)$res->fetch_assoc()['cnt'] : 0;
}

$usedStickersSql = "SELECT COUNT(*) AS cnt
FROM project_info
$where AND sticker_status IN ('Yes', 'No')";
$usedStickersRes = $conn->query($usedStickersSql);
$usedStickers = $usedStickersRes ? (int)$usedStickersRes->fetch_assoc()['cnt'] : 0;

$recentSql = "SELECT project_no, customer_name, project_status, creation_date
FROM project_info
$where
ORDER BY creation_date DESC
LIMIT 5";
$recentRes = $conn->query($recentSql);
$recentProjects = [];
if ($recentRes) {
    while ($row = $recentRes->fetch_assoc()) {
        $recentProjects[] = [
            'project_no' => $row['project_no'],
            'customer_name' => $row['customer_name'],
            'project_status' => $row['project_status'],
            'creation_date' => date('d M Y', strtotime($row['creation_date']))
        ];
    }
}

echo json_encode([
    'inspector' => $_SESSION['username'],
    'kpi' => [
        'total' => $total,
        'completed' => $completed,
        'pending' => $pending,
        'pending_checklist' => $pendingChecklist,
        'pending_report' => $pendingReport,
        'completion_rate' => $completionRate,
        'sticker_rate' => $stickerRate,
    ],
    'summary' => [
        'date_label' => $dateLabel,
        'customer_count' => $customerCount,
        'equipment_type_count' => $equipmentTypeCount,
        'recent_total' => $total,
    ],
    'monthly' => [
        'labels' => $monthlyLabels,
        'total' => $monthlyTotal,
        'completed' => $monthlyCompleted,
        'pending' => $monthlyPending,
    ],
    'customers' => [
        'names' => $customerNames,
        'totals' => $customerTotals,
        'completed' => $customerCompleted,
    ],
    'equipment' => [
        'labels' => $equipmentLabels,
        'data' => $equipmentCounts,
    ],
    'certificates' => [
        'labels' => $certLabels,
        'data' => $certCounts,
    ],
    'sticker' => [
        'used_stickers' => $usedStickers,
        'passed' => $stickerPassed,
        'failed' => $stickerFailed,
        'pass_rate' => $stickerRate,
    ],
    'recent_projects' => $recentProjects,
]);
?>
