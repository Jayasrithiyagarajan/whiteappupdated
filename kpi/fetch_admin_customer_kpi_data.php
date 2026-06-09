<?php
session_start();
include_once('../file/config.php');

// ONLY Admin Role Allowed
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['error' => 'Unauthorized access. Only admins can view this data.']);
    exit;
}

header('Content-Type: application/json');

// --- Filters ---
$dateFrom = trim($_GET['date_from'] ?? '');
$dateTo   = trim($_GET['date_to'] ?? '');
$customer = trim($_GET['customer'] ?? ''); // Optional: Filter down to a specific customer

$whereFilters = "";
if (!empty($dateFrom)) {
    $whereFilters .= " AND DATE(creation_date) >= '" . mysqli_real_escape_string($conn, $dateFrom) . "'";
}
if (!empty($dateTo)) {
    $whereFilters .= " AND DATE(creation_date) <= '" . mysqli_real_escape_string($conn, $dateTo) . "'";
}

$projBaseWhere = "WHERE customer_name IS NOT NULL AND customer_name != ''";
if (!empty($customer)) {
    $projBaseWhere .= " AND customer_name = '" . mysqli_real_escape_string($conn, $customer) . "'";
}

// ============================================================
// 1. PROJECT STATS (Summary + Status Distribution + Monthly Trend)
// ============================================================

// --- Summary Stats ---
$projSql = "SELECT 
    COUNT(DISTINCT customer_name) as total_customers,
    COUNT(*) as total_projects,
    SUM(project_status = 'Pending') as pending_projects,
    SUM(project_status = 'In Progress') as in_progress_projects,
    SUM(project_status = 'Completed') as completed_projects
FROM project_info 
$projBaseWhere $whereFilters";

$projRes = $conn->query($projSql);
$projData = $projRes ? $projRes->fetch_assoc() : [];

$totalCustomers  = (int)($projData['total_customers'] ?? 0);
$totalProjects   = (int)($projData['total_projects'] ?? 0);
$pendingProjects = (int)($projData['pending_projects'] ?? 0);
$inProgProjects  = (int)($projData['in_progress_projects'] ?? 0);
$compProjects    = (int)($projData['completed_projects'] ?? 0);

// Default status mapping if raw exact counts fail
if($totalProjects > 0 && ($pendingProjects + $inProgProjects + $compProjects == 0)) {
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

// --- Top 10 Customers (By Project Volume) ---
$topCustomerSql = "SELECT customer_name, COUNT(*) as cnt 
FROM project_info 
$projBaseWhere $whereFilters
GROUP BY customer_name 
ORDER BY cnt DESC LIMIT 10";

$topCustRes = $conn->query($topCustomerSql);
$topCustLabels = [];
$topCustData = [];
if ($topCustRes) {
    while($r = $topCustRes->fetch_assoc()) {
        $topCustLabels[] = $r['customer_name'];
        $topCustData[] = (int)$r['cnt'];
    }
}

// ============================================================
// 2. CHECKLIST STATS
// ============================================================
$chkBaseWhere = "WHERE client_name IS NOT NULL AND client_name != ''";
if (!empty($customer)) {
    $chkBaseWhere .= " AND client_name = '" . mysqli_real_escape_string($conn, $customer) . "'";
}
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
foreach ($certTables as $label => $table) {
    $q = "SELECT COUNT(*) as cnt FROM $table c LEFT JOIN project_info pi ON c.project_no = pi.project_no $certProjWhere $certFilter";
    $res = $conn->query($q);
    $totalCertificates += $res ? (int)$res->fetch_assoc()['cnt'] : 0;
}


// ============================================================
// 4. CUSTOMER BREAKDOWN (Data Table)
// ============================================================
// We want to list every customer and how many projects, completed projects, and checklists they have.
$tableCustomers = [];
$drillDownSql = "SELECT 
    pi.customer_name, 
    COUNT(pi.project_no) as total_projects,
    SUM(pi.project_status = 'Completed' OR pi.project_status = 'completed') as completed,
    SUM(pi.project_status = 'Pending' OR pi.project_status = 'pending') as pending
FROM project_info pi
$projBaseWhere $whereFilters
GROUP BY pi.customer_name 
ORDER BY total_projects DESC";

$drillRes = $conn->query($drillDownSql);
if ($drillRes) {
    while($r = $drillRes->fetch_assoc()) {
        // Also get checklist count for this customer
        $cName = mysqli_real_escape_string($conn, $r['customer_name']);
        $cChkQ = "SELECT COUNT(*) as chks FROM checklist_information WHERE client_name = '$cName' $chkFilter";
        $cChkRes = $conn->query($cChkQ);
        $chkCount = $cChkRes ? (int)$cChkRes->fetch_assoc()['chks'] : 0;

        $tableCustomers[] = [
            'name'      => $r['customer_name'],
            'projects'  => (int)$r['total_projects'],
            'completed' => (int)$r['completed'],
            'pending'   => (int)$r['pending'],
            'checklists'=> $chkCount
        ];
    }
}

// List of all valid customer names for the dropdown
$allCustSql = "SELECT DISTINCT customer_name FROM project_info WHERE customer_name IS NOT NULL AND customer_name != '' ORDER BY customer_name ASC";
$allCustRes = $conn->query($allCustSql);
$allCustomerNames = [];
if ($allCustRes) {
    while($r = $allCustRes->fetch_assoc()) {
        $allCustomerNames[] = $r['customer_name'];
    }
}

// ============================================================
// Return Payload
// ============================================================
echo json_encode([
    'kpi' => [
        'total_customers'   => $totalCustomers,
        'total_projects'    => $totalProjects,
        'completed_projects'=> $compProjects,
        'pending_projects'  => $pendingProjects,
        'in_progress_projects' => $inProgProjects,
        'total_checklists'  => $totalChecklists,
        'total_certificates'=> $totalCertificates
    ],
    'project_status' => [
        'labels' => ['Pending', 'In Progress', 'Completed'],
        'data'   => [$pendingProjects, $inProgProjects, $compProjects]
    ],
    'monthly_trend' => [
        'labels' => $monthlyLabels,
        'data'   => $monthlyData
    ],
    'top_customers' => [
        'labels' => $topCustLabels,
        'data'   => $topCustData
    ],
    'checklist_distribution' => [
        'labels' => $chkTypeLabels,
        'data'   => $chkTypeData
    ],
    'table_data' => $tableCustomers,
    'customers_list' => $allCustomerNames
]);
