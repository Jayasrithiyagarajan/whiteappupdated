<?php
session_start();
include_once('../file/config.php');

$user = $_SESSION['username'];
$role = $_SESSION['role'];

$inspectorFilter = $_POST['filter_inspector'] ?? '';
$clientFilter    = $_POST['filter_client'] ?? '';
$dateFrom        = $_POST['filter_date_from'] ?? '';
$dateTo          = $_POST['filter_date_to'] ?? '';
$yearFilter      = $_POST['filter_year'] ?? '';
$statusFilter    = $_POST['status_filter'] ?? '';
$expiryFilter    = $_POST['filter_expiry_status'] ?? '';
$certificateFilter = $_POST['filter_certificate'] ?? '';
$certTypeFilter = $_POST['filter_cert_type'] ?? '';

// Base Query
$where = " WHERE project_status = 'Completed' AND YEAR(creation_date) = YEAR(CURDATE()) ";
$params = [];
$types = "";

// Role-based filtering
if ($role === 'customer') {
    $where .= " AND customer_name = ? ";
    $params[] = $user;
    $types .= "s";
} elseif (!in_array($role, ['admin','reviewer','quality controller','document controller'])) {
    $where .= " AND inspector_name = ? ";
    $params[] = $user;
    $types .= "s";
}

// --- APPLY FILTERS ---
if (!empty($inspectorFilter)) {
    $where .= " AND inspector_name = ? ";
    $params[] = $inspectorFilter;
    $types .= "s";
}
if (!empty($clientFilter)) {
    $where .= " AND customer_name = ? ";
    $params[] = $clientFilter;
    $types .= "s";
}
if (!empty($dateFrom)) {
    $where .= " AND DATE(creation_date) >= ? ";
    $params[] = $dateFrom;
    $types .= "s";
}
if (!empty($dateTo)) {
    $where .= " AND DATE(creation_date) <= ? ";
    $params[] = $dateTo;
    $types .= "s";
}
if (!empty($yearFilter)) {
    $where .= " AND YEAR(creation_date) = ? ";
    $params[] = $yearFilter;
    $types .= "s";
}
if (!empty($statusFilter)) {
    $where .= " AND project_status = ? ";
    $params[] = $statusFilter;
    $types .= "s";
}

// Active/Expired Logic
if (!empty($expiryFilter)) {
    if ($expiryFilter === 'Expired') {
        $where .= " AND (SELECT MAX(next_inspection_due_date) FROM reports r WHERE r.project_no = p.project_no) < CURDATE() ";
    } elseif ($expiryFilter === 'Active') {
        $where .= " AND ( (SELECT MAX(next_inspection_due_date) FROM reports r WHERE r.project_no = p.project_no) >= CURDATE() OR (SELECT MAX(next_inspection_due_date) FROM reports r WHERE r.project_no = p.project_no) IS NULL ) ";
    }
}

// Certificate Logic
if (!empty($certificateFilter)) {
    if ($certificateFilter === 'Pending') {
        $where .= " AND p.checklist_status = 'Created' AND p.report_status = 'Generated' AND p.review_status = 'Completed' AND p.certificatestatus = 'Pending' ";
    } elseif ($certificateFilter === 'Completed') {
        $where .= " AND p.certificatestatus = 'Completed' ";
    }
}

// Certificate Type Logic
if (!empty($certTypeFilter)) {
    $certTableMap = [
        'Healthcheck' => 'crane_health_check_certificate',
        'Loadtestwithload' => 'loadtest_certificate',
        'Mobile' => 'mobile_crane_loadtest',
        'WithLoadTest' => 'withload',
        'Lifting' => 'lifting_gear_certificates',
        'MPI' => 'mpi_certificates',
        'EddyCurrent' => 'eddy_current_inspection',
        'LiquidPenetrantInspection' => 'liquid_penetrant_inspection',
        'RockTest' => 'rocking_test_certificate',
        'LMI' => 'lmi_certificates'
    ];

    if ($certTypeFilter === 'none') {
        $where .= " AND NOT EXISTS (
            SELECT 1 FROM crane_health_check_certificate WHERE project_no = p.project_no
            UNION ALL SELECT 1 FROM loadtest_certificate WHERE project_no = p.project_no
            UNION ALL SELECT 1 FROM mobile_crane_loadtest WHERE project_no = p.project_no
            UNION ALL SELECT 1 FROM withload WHERE project_no = p.project_no
            UNION ALL SELECT 1 FROM lifting_gear_certificates WHERE project_no = p.project_no
            UNION ALL SELECT 1 FROM mpi_certificates WHERE project_no = p.project_no
            UNION ALL SELECT 1 FROM eddy_current_inspection WHERE project_no = p.project_no
            UNION ALL SELECT 1 FROM liquid_penetrant_inspection WHERE project_no = p.project_no
            UNION ALL SELECT 1 FROM rocking_test_certificate WHERE project_no = p.project_no
            UNION ALL SELECT 1 FROM lmi_certificates WHERE project_no = p.project_no
        ) ";
    } elseif (isset($certTableMap[$certTypeFilter])) {
        $tbl = $certTableMap[$certTypeFilter];
        $where .= " AND EXISTS (SELECT 1 FROM {$tbl} cert WHERE cert.project_no = p.project_no) ";
    }
}

// 1. Total
$sqlTotal = "SELECT COUNT(*) as cnt FROM project_info p $where";
$stmt = $conn->prepare($sqlTotal);
if($params) $stmt->bind_param($types, ...$params);
$stmt->execute();
$total = $stmt->get_result()->fetch_assoc()['cnt'] ?? 0;
$stmt->close();

// 2. Active
$sqlActive = "SELECT COUNT(*) as cnt FROM project_info p $where 
              AND ( (SELECT MAX(next_inspection_due_date) FROM reports r WHERE r.project_no = p.project_no) >= CURDATE() 
              OR (SELECT MAX(next_inspection_due_date) FROM reports r WHERE r.project_no = p.project_no) IS NULL )";
$stmt = $conn->prepare($sqlActive);
if($params) $stmt->bind_param($types, ...$params);
$stmt->execute();
$active = $stmt->get_result()->fetch_assoc()['cnt'] ?? 0;
$stmt->close();

// 3. Expired
$sqlExpired = "SELECT COUNT(*) as cnt FROM project_info p $where 
               AND (SELECT MAX(next_inspection_due_date) FROM reports r WHERE r.project_no = p.project_no) < CURDATE()";
$stmt = $conn->prepare($sqlExpired);
if($params) $stmt->bind_param($types, ...$params);
$stmt->execute();
$expired = $stmt->get_result()->fetch_assoc()['cnt'] ?? 0;
$stmt->close();

echo json_encode([
    'total' => $total,
    'active' => $active,
    'expired' => $expired
]);
?>
