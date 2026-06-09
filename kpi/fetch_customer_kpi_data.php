<?php
session_start();
include_once('../file/config.php');

// ONLY Customer Role Allowed
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'customer') {
    echo json_encode(['error' => 'Unauthorized access. Only customers can view this data.']);
    exit;
}

header('Content-Type: application/json');

$customerName = $_SESSION['username'];
$safeCustomer = mysqli_real_escape_string($conn, $customerName);

// --- Filters ---
$dateFrom = trim($_GET['date_from'] ?? '');
$dateTo   = trim($_GET['date_to'] ?? '');

$whereFilters = "";
if (!empty($dateFrom)) {
    $whereFilters .= " AND DATE(creation_date) >= '" . mysqli_real_escape_string($conn, $dateFrom) . "'";
}
if (!empty($dateTo)) {
    $whereFilters .= " AND DATE(creation_date) <= '" . mysqli_real_escape_string($conn, $dateTo) . "'";
}

// ============================================================
// 1. PROJECT STATS (Summary + Status Distribution + Monthly Trend)
// ============================================================
$projBaseWhere = "WHERE customer_name = '$safeCustomer'";

// --- Summary Stats ---
$projSql = "SELECT 
    COUNT(*) as total_projects,
    SUM(project_status = 'Pending') as pending_projects,
    SUM(project_status = 'In Progress') as in_progress_projects,
    SUM(project_status = 'Completed') as completed_projects
FROM project_info 
$projBaseWhere $whereFilters";

$projRes = $conn->query($projSql);
$projData = $projRes ? $projRes->fetch_assoc() : [];

$totalProjects   = (int)($projData['total_projects'] ?? 0);
$pendingProjects = (int)($projData['pending_projects'] ?? 0);
$inProgProjects  = (int)($projData['in_progress_projects'] ?? 0);
$compProjects    = (int)($projData['completed_projects'] ?? 0);

// Default status mapping if raw counts don't align with 'total' perfectly
if($totalProjects > 0 && ($pendingProjects + $inProgProjects + $compProjects == 0)) {
    // Some logic just in case project_status strings vary slightly
    $fallbackSql = "SELECT project_status, COUNT(*) as cnt FROM project_info $projBaseWhere $whereFilters GROUP BY project_status";
    $fallbackRes = $conn->query($fallbackSql);
    while($r = $fallbackRes->fetch_assoc()) {
        $st = strtolower($r['project_status']);
        if(strpos($st, 'pend') !== false) $pendingProjects += $r['cnt'];
        elseif(strpos($st, 'prog') !== false) $inProgProjects += $r['cnt'];
        elseif(strpos($st, 'comp') !== false) $compProjects += $r['cnt'];
    }
}

// --- Monthly Trend (Projects) ---
$trendSql = "SELECT 
    DATE_FORMAT(creation_date, '%Y-%m') AS ym,
    DATE_FORMAT(creation_date, '%b %Y') AS label,
    COUNT(*) AS total
FROM project_info 
$projBaseWhere $whereFilters
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

// --- Equipment Type Distribution ---
$eqSql = "SELECT equipment_type, COUNT(*) as cnt 
FROM project_info 
$projBaseWhere $whereFilters
AND equipment_type IS NOT NULL AND equipment_type != ''
GROUP BY equipment_type 
ORDER BY cnt DESC LIMIT 7";

$eqRes = $conn->query($eqSql);
$eqLabels = [];
$eqData = [];
if ($eqRes) {
    while($r = $eqRes->fetch_assoc()) {
        $eqLabels[] = $r['equipment_type'];
        $eqData[] = (int)$r['cnt'];
    }
}

// ============================================================
// 2. CHECKLIST STATS
// ============================================================
$chkBaseWhere = "WHERE client_name = '$safeCustomer'";
$chkFilter = str_replace("creation_date", "created_at", $whereFilters);

$chkTotalSql = "SELECT COUNT(*) as cnt FROM checklist_information $chkBaseWhere $chkFilter";
$chkRes = $conn->query($chkTotalSql);
$totalChecklists = $chkRes ? (int)($chkRes->fetch_assoc()['cnt'] ?? 0) : 0;

$chkTypeSql = "SELECT checklist_type, COUNT(*) as cnt 
FROM checklist_information 
$chkBaseWhere $chkFilter 
GROUP BY checklist_type";
$chkTypeRes = $conn->query($chkTypeSql);
$chkTypeLabels = [];
$chkTypeData = [];
if ($chkTypeRes) {
    while($r = $chkTypeRes->fetch_assoc()) {
        // Format names nice
        $lbl = ucwords(str_replace(['_', '-'], ' ', $r['checklist_type']));
        $chkTypeLabels[] = $lbl;
        $chkTypeData[] = (int)$r['cnt'];
    }
}

// ============================================================
// 3. CERTIFICATE STATS
// ============================================================
$certTables = [
    'MPI'             => 'mpi_certificates',
    'Health Check'    => 'crane_health_check_certificate',
    'Lifting Gear'    => 'lifting_gear_certificates',
    'Load Test'       => 'loadtest_certificate',
    'LPI'             => 'liquid_penetrant_inspection',
    'Mobile Crane'    => 'mobile_crane_loadtest',
    'Rocking Test'    => 'rocking_test_certificate',
    'Eddy Current'    => 'eddy_current_inspection',
    'With Load'       => 'withload'
];

$certFilter = str_replace("creation_date", "pi.creation_date", $whereFilters);
$certProjWhere = str_replace("customer_name", "pi.customer_name", $projBaseWhere);

$totalCertificates = 0;
$certLabels = [];
$certData = [];

foreach ($certTables as $label => $table) {
    $q = "SELECT COUNT(*) as cnt FROM $table c LEFT JOIN project_info pi ON c.project_no = pi.project_no $certProjWhere $certFilter";
    $res = $conn->query($q);
    $cnt = $res ? (int)$res->fetch_assoc()['cnt'] : 0;
    
    $totalCertificates += $cnt;
    if ($cnt > 0) {
        $certLabels[] = $label;
        $certData[] = $cnt;
    }
}

// ============================================================
// 4. STICKER STATS
// ============================================================
$stickerSql = "SELECT COUNT(*) as cnt FROM project_info $projBaseWhere $whereFilters AND sticker_status IN ('Yes', 'No')";
$stickerRes = $conn->query($stickerSql);
$totalStickers = $stickerRes ? (int)$stickerRes->fetch_assoc()['cnt'] : 0;


// ============================================================
// Return Payload
// ============================================================
echo json_encode([
    'kpi' => [
        'total_projects'    => $totalProjects,
        'completed_projects'=> $compProjects,
        'pending_projects'  => $pendingProjects,
        'in_progress_projects' => $inProgProjects,
        'total_checklists'  => $totalChecklists,
        'total_certificates'=> $totalCertificates,
        'total_stickers'    => $totalStickers
    ],
    'project_status' => [
        'labels' => ['Pending', 'In Progress', 'Completed'],
        'data'   => [$pendingProjects, $inProgProjects, $compProjects]
    ],
    'monthly_trend' => [
        'labels' => $monthlyLabels,
        'data'   => $monthlyData
    ],
    'equipment_types' => [
        'labels' => $eqLabels,
        'data'   => $eqData
    ],
    'checklist_distribution' => [
        'labels' => $chkTypeLabels,
        'data'   => $chkTypeData
    ],
    'certificate_distribution' => [
        'labels' => $certLabels,
        'data'   => $certData
    ]
]);
