<?php
session_start();
include_once('../file/config.php');

header('Content-Type: application/json');

// Only allow admin role
if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$dateFrom = $_GET['date_from'] ?? '';
$dateTo   = $_GET['date_to'] ?? '';
$selectedInspector = $_GET['inspector'] ?? '';

// Build dynamic WHERE clause based on filters
$where = " WHERE 1=1 ";
if (!empty($selectedInspector)) {
    $escInspector = mysqli_real_escape_string($conn, $selectedInspector);
    $where .= " AND inspector_name = '$escInspector' ";
}
if (!empty($dateFrom)) {
    $dateFrom = mysqli_real_escape_string($conn, $dateFrom);
    $where .= " AND DATE(creation_date) >= '$dateFrom' ";
}
if (!empty($dateTo)) {
    $dateTo = mysqli_real_escape_string($conn, $dateTo);
    $where .= " AND DATE(creation_date) <= '$dateTo' ";
}

// 1. Fetch inspectors list for dropdown filter
$inspectorsList = [];
$insRes = $conn->query("SELECT DISTINCT inspector_name FROM inspectors WHERE inspector_name IS NOT NULL AND inspector_name != '' ORDER BY inspector_name ASC");
if ($insRes) {
    while ($r = $insRes->fetch_assoc()) {
        $inspectorsList[] = $r['inspector_name'];
    }
}

// 2. Core Stat Cards Calculations
$totalInspectors = count($inspectorsList);

// Total projects, completed projects, pending projects
$statSql = "SELECT 
    COUNT(*) AS total,
    SUM(project_status = 'Completed') AS completed,
    SUM(project_status != 'Completed') AS pending,
    SUM(checklist_status = 'Pending') AS pending_checklist,
    SUM(report_status = 'Pending') AS pending_report
FROM project_info $where";

$statRes = $conn->query($statSql);
$statRow = $statRes ? $statRes->fetch_assoc() : [];

$totalProjects     = (int)($statRow['total'] ?? 0);
$completedProjects   = (int)($statRow['completed'] ?? 0);
$pendingProjects     = (int)($statRow['pending'] ?? 0);
$pendingChecklists   = (int)($statRow['pending_checklist'] ?? 0);
$pendingReports      = (int)($statRow['pending_report'] ?? 0);

// Fetch sticker counts from stickers table joined with project_info on project_no
$stickerWhere = str_replace("creation_date", "pi.creation_date", str_replace("inspector_name", "pi.inspector_name", $where));
$stickerStatSql = "SELECT 
    SUM(s.sticker_status = 'Passed') AS passed,
    SUM(s.sticker_status = 'Failed') AS failed
FROM stickers s
INNER JOIN project_info pi ON s.project_no = pi.project_no
$stickerWhere";

$stickerStatRes = $conn->query($stickerStatSql);
$stickerStatRow = $stickerStatRes ? $stickerStatRes->fetch_assoc() : [];

$stickerPassed       = (int)($stickerStatRow['passed'] ?? 0);
$stickerFailed       = (int)($stickerStatRow['failed'] ?? 0);

$completionRate = $totalProjects > 0 ? round(($completedProjects / $totalProjects) * 100, 2) : 0;
$stickerBase    = max(1, $stickerPassed + $stickerFailed);
$stickerRate    = round(($stickerPassed / $stickerBase) * 100, 2);

// Date Label
$dateLabel = 'All Time';
if (!empty($dateFrom) && !empty($dateTo)) {
    $dateLabel = date('d M Y', strtotime($dateFrom)) . ' to ' . date('d M Y', strtotime($dateTo));
} elseif (!empty($dateFrom)) {
    $dateLabel = 'From ' . date('d M Y', strtotime($dateFrom));
} elseif (!empty($dateTo)) {
    $dateLabel = 'Until ' . date('d M Y', strtotime($dateTo));
}

// 3. Workload Distribution Chart (Share of Projects per Inspector)
$workloadLabels = [];
$workloadData = [];
$workloadSql = "SELECT inspector_name, COUNT(*) AS cnt 
FROM project_info 
$where AND inspector_name IS NOT NULL AND inspector_name != ''
GROUP BY inspector_name 
ORDER BY cnt DESC";

$workloadRes = $conn->query($workloadSql);
$wlTotal = 0;
$wlRows = [];
if ($workloadRes) {
    while ($row = $workloadRes->fetch_assoc()) {
        $wlRows[] = $row;
        $wlTotal += (int)$row['cnt'];
    }
}
foreach ($wlRows as $row) {
    $pct = $wlTotal > 0 ? round(((int)$row['cnt'] / $wlTotal) * 100) : 0;
    $workloadLabels[] = $row['inspector_name'] . ' (' . $pct . '%)';
    $workloadData[] = (int)$row['cnt'];
}

// 4. Monthly Trend Chart (Total vs Completed)
$monthlyLabels = [];
$monthlyTotal = [];
$monthlyCompleted = [];
$monthlyPending = [];
$monthlySql = "SELECT 
    DATE_FORMAT(creation_date, '%Y-%m') AS ym,
    DATE_FORMAT(creation_date, '%b %Y') AS label,
    COUNT(*) AS total,
    SUM(project_status = 'Completed') AS completed
FROM project_info
$where
GROUP BY ym, label
ORDER BY ym ASC
LIMIT 12";

$monthlyRes = $conn->query($monthlySql);
if ($monthlyRes) {
    while ($row = $monthlyRes->fetch_assoc()) {
        $monthlyLabels[] = $row['label'];
        $monthlyTotal[] = (int)$row['total'];
        $monthlyCompleted[] = (int)$row['completed'];
        $monthlyPending[] = (int)$row['total'] - (int)$row['completed'];
    }
}

// 5. Sticker comparison chart data
$stickerLabels = [];
$stickerUsed = [];
$stickerPassedData = [];
$stickerFailedData = [];
$stickerSql = "SELECT 
    pi.inspector_name,
    COUNT(s.id) AS total,
    SUM(s.sticker_status = 'Passed') AS passed,
    SUM(s.sticker_status = 'Failed') AS failed
FROM stickers s
INNER JOIN project_info pi ON s.project_no = pi.project_no
" . str_replace("creation_date", "pi.creation_date", str_replace("inspector_name", "pi.inspector_name", $where)) . " AND pi.inspector_name IS NOT NULL AND pi.inspector_name != ''
GROUP BY pi.inspector_name
ORDER BY total DESC
LIMIT 8";

$stickerRes = $conn->query($stickerSql);
if ($stickerRes) {
    while ($row = $stickerRes->fetch_assoc()) {
        $stickerLabels[] = $row['inspector_name'];
        $stickerUsed[] = (int)$row['passed'] + (int)$row['failed'];
        $stickerPassedData[] = (int)$row['passed'];
        $stickerFailedData[] = (int)$row['failed'];
    }
}

// 6. Backlog Comparison (Pending checklists vs reports)
$backlogLabels = [];
$backlogChecklists = [];
$backlogReports = [];
$backlogSql = "SELECT 
    inspector_name,
    SUM(checklist_status = 'Pending') AS checklists,
    SUM(report_status = 'Pending') AS reports
FROM project_info
$where AND inspector_name IS NOT NULL AND inspector_name != ''
GROUP BY inspector_name
ORDER BY (SUM(checklist_status = 'Pending') + SUM(report_status = 'Pending')) DESC
LIMIT 8";

$backlogRes = $conn->query($backlogSql);
if ($backlogRes) {
    while ($row = $backlogRes->fetch_assoc()) {
        $backlogLabels[] = $row['inspector_name'];
        $backlogChecklists[] = (int)$row['checklists'];
        $backlogReports[] = (int)$row['reports'];
    }
}

// 7. Table Data: Detailed Inspector Performance Ledger
$tableData = [];
$ledgerSql = "SELECT 
    inspector_name,
    COUNT(*) AS total,
    SUM(project_status = 'Completed') AS completed,
    SUM(checklist_status = 'Pending') AS pending_checklist,
    SUM(report_status = 'Pending') AS pending_report
FROM project_info
WHERE inspector_name IS NOT NULL AND inspector_name != ''
" . (!empty($dateFrom) ? " AND DATE(creation_date) >= '$dateFrom' " : "") . "
" . (!empty($dateTo) ? " AND DATE(creation_date) <= '$dateTo' " : "") . "
GROUP BY inspector_name
ORDER BY total DESC";

$ledgerRes = $conn->query($ledgerSql);
if ($ledgerRes) {
    while ($row = $ledgerRes->fetch_assoc()) {
        $t = (int)$row['total'];
        $c = (int)$row['completed'];
        
        // Fetch sticker counts for this specific inspector from stickers table
        $insNameEsc = mysqli_real_escape_string($conn, $row['inspector_name']);
        $stickerLedgerSql = "SELECT 
            SUM(s.sticker_status = 'Passed') AS passed,
            SUM(s.sticker_status = 'Failed') AS failed
        FROM stickers s
        INNER JOIN project_info pi ON s.project_no = pi.project_no
        WHERE pi.inspector_name = '$insNameEsc'
        " . (!empty($dateFrom) ? " AND DATE(pi.creation_date) >= '$dateFrom' " : "") . "
        " . (!empty($dateTo) ? " AND DATE(pi.creation_date) <= '$dateTo' " : "");
        
        $sRes = $conn->query($stickerLedgerSql);
        $sPassed = 0;
        $sFailed = 0;
        if ($sRes && $sRow = $sRes->fetch_assoc()) {
            $sPassed = (int)($sRow['passed'] ?? 0);
            $sFailed = (int)($sRow['failed'] ?? 0);
        }
        
        $cRate = $t > 0 ? round(($c / $t) * 100, 1) : 0;
        $sBase = max(1, $sPassed + $sFailed);
        $sRate = ($sPassed + $sFailed) > 0 ? round(($sPassed / $sBase) * 100, 1) . '%' : 'N/A';

        $tableData[] = [
            'name' => $row['inspector_name'],
            'total' => $t,
            'completed' => $c,
            'pending' => $t - $c,
            'rate' => $cRate,
            'checklists' => (int)$row['pending_checklist'],
            'reports' => (int)$row['pending_report'],
            'sticker_rate' => $sRate
        ];
    }
}

echo json_encode([
    'inspectors_list' => $inspectorsList,
    'kpi' => [
        'total_inspectors' => $totalInspectors,
        'total_projects' => $totalProjects,
        'completed_projects' => $completedProjects,
        'pending_projects' => $pendingProjects,
        'pending_checklists' => $pendingChecklists,
        'pending_reports' => $pendingReports,
        'completion_rate' => $completionRate,
        'sticker_rate' => $stickerRate
    ],
    'summary' => [
        'date_label' => $dateLabel
    ],
    'workload' => [
        'labels' => $workloadLabels,
        'data' => $workloadData
    ],
    'monthly' => [
        'labels' => $monthlyLabels,
        'total' => $monthlyTotal,
        'completed' => $monthlyCompleted,
        'pending' => $monthlyPending
    ],
    'sticker' => [
        'labels' => $stickerLabels,
        'used' => $stickerUsed,
        'passed' => $stickerPassedData,
        'failed' => $stickerFailedData
    ],
    'backlog' => [
        'labels' => $backlogLabels,
        'checklists' => $backlogChecklists,
        'reports' => $backlogReports
    ],
    'table_data' => $tableData
]);
?>
