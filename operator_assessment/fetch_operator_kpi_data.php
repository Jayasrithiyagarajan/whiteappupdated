<?php
session_start();
include_once('../file/config.php');

header('Content-Type: application/json');

$logged_in_user = $_SESSION['username'] ?? null;
$userRole       = $_SESSION['role'] ?? null;

if (!$logged_in_user) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// ── Filters ──
$date_from  = isset($_GET['date_from']) ? trim($_GET['date_from']) : '';
$date_to    = isset($_GET['date_to'])   ? trim($_GET['date_to'])   : '';
$inspector  = isset($_GET['inspector']) ? trim($_GET['inspector']) : '';
$client     = isset($_GET['client'])    ? trim($_GET['client'])    : '';
$status     = isset($_GET['status'])    ? trim($_GET['status'])    : '';

// ── Build WHERE clause ──
$where  = " WHERE 1=1 ";
$params = [];
$types  = "";

// Role-based restriction
if ($userRole === 'inspector') {
    $where .= " AND oa.inspector_id = (SELECT user_id FROM new_users WHERE username = ?) ";
    $params[] = $logged_in_user;
    $types .= "s";
}

if ($date_from !== '') {
    $where .= " AND oa.date >= ? ";
    $params[] = $date_from;
    $types .= "s";
}
if ($date_to !== '') {
    $where .= " AND oa.date <= ? ";
    $params[] = $date_to;
    $types .= "s";
}
if ($inspector !== '') {
    $where .= " AND oa.inspector_id = (SELECT user_id FROM new_users WHERE username = ?) ";
    $params[] = $inspector;
    $types .= "s";
}
if ($client !== '') {
    $where .= " AND oa.client_id = ? ";
    $params[] = $client;
    $types .= "s";
}
if ($status !== '') {
    $where .= " AND oa.status = ? ";
    $params[] = $status;
    $types .= "s";
}

$response = [];

// ═══════════════════════════════════════════════
// 1. KPI Summary Cards
// ═══════════════════════════════════════════════
$sql_kpi = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN oa.status = 'COMPLETED' THEN 1 ELSE 0 END) as completed,
    SUM(CASE WHEN oa.status = 'IN_PROGRESS' THEN 1 ELSE 0 END) as in_progress,
    SUM(CASE WHEN oa.status = 'PENDING' THEN 1 ELSE 0 END) as pending,
    SUM(CASE WHEN oa.exam_status = 'PASSED' THEN 1 ELSE 0 END) as exam_passed,
    SUM(CASE WHEN oa.exam_status = 'FAILED' THEN 1 ELSE 0 END) as exam_failed,
    SUM(CASE WHEN oa.signals_status = 'PASSED' THEN 1 ELSE 0 END) as signals_passed,
    SUM(CASE WHEN oa.signals_status = 'FAILED' THEN 1 ELSE 0 END) as signals_failed
FROM operator_assessments oa $where";

$stmt = $conn->prepare($sql_kpi);
if ($types) $stmt->bind_param($types, ...$params);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

$total = (int)($row['total'] ?? 0);
$completed = (int)($row['completed'] ?? 0);
$exam_passed = (int)($row['exam_passed'] ?? 0);
$exam_failed = (int)($row['exam_failed'] ?? 0);
$signals_passed = (int)($row['signals_passed'] ?? 0);
$signals_failed = (int)($row['signals_failed'] ?? 0);

$response['kpi'] = [
    'total'           => $total,
    'completed'       => $completed,
    'in_progress'     => (int)($row['in_progress'] ?? 0),
    'pending'         => (int)($row['pending'] ?? 0),
    'exam_passed'     => $exam_passed,
    'exam_failed'     => $exam_failed,
    'signals_passed'  => $signals_passed,
    'signals_failed'  => $signals_failed,
    'completion_rate' => $total > 0 ? round(($completed / $total) * 100, 1) : 0,
    'exam_pass_rate'  => ($exam_passed + $exam_failed) > 0 ? round(($exam_passed / ($exam_passed + $exam_failed)) * 100, 1) : 0,
    'signals_pass_rate' => ($signals_passed + $signals_failed) > 0 ? round(($signals_passed / ($signals_passed + $signals_failed)) * 100, 1) : 0
];

// ═══════════════════════════════════════════════
// 2. Status Distribution (Donut chart)
// ═══════════════════════════════════════════════
$sql_status = "SELECT oa.status, COUNT(*) as count FROM operator_assessments oa $where GROUP BY oa.status";
$stmt2 = $conn->prepare($sql_status);
if ($types) $stmt2->bind_param($types, ...$params);
$stmt2->execute();
$res2 = $stmt2->get_result();

$status_data = ['PENDING' => 0, 'IN_PROGRESS' => 0, 'COMPLETED' => 0];
while ($r = $res2->fetch_assoc()) {
    if (isset($status_data[$r['status']])) {
        $status_data[$r['status']] = (int)$r['count'];
    }
}
$response['status_distribution'] = [
    'labels' => ['Pending', 'In Progress', 'Completed'],
    'data'   => array_values($status_data)
];

// ═══════════════════════════════════════════════
// 3. Exam vs Signals Results (Grouped bar)
// ═══════════════════════════════════════════════
$response['exam_vs_signals'] = [
    'categories' => ['Written Exam', 'Hand Signals'],
    'passed'     => [$exam_passed, $signals_passed],
    'failed'     => [$exam_failed, $signals_failed]
];

// ═══════════════════════════════════════════════
// 4. Monthly Assessment Trend (Line chart, last 12 months)
// ═══════════════════════════════════════════════
$monthly_labels = [];
$monthly_data   = [];
for ($i = 11; $i >= 0; $i--) {
    $m = date('M Y', strtotime("-$i months"));
    $monthly_labels[] = $m;
    $monthly_data[$m] = 0;
}

$sql_monthly = "SELECT DATE_FORMAT(oa.date, '%b %Y') as month_year, COUNT(*) as count 
                FROM operator_assessments oa 
                $where AND oa.date >= DATE_SUB(CURDATE(), INTERVAL 11 MONTH) 
                GROUP BY month_year";
$stmt3 = $conn->prepare($sql_monthly);
if ($types) $stmt3->bind_param($types, ...$params);
$stmt3->execute();
$res3 = $stmt3->get_result();
while ($r = $res3->fetch_assoc()) {
    if (isset($monthly_data[$r['month_year']])) {
        $monthly_data[$r['month_year']] = (int)$r['count'];
    }
}
$response['monthly_trend'] = [
    'labels' => $monthly_labels,
    'data'   => array_values($monthly_data)
];

// ═══════════════════════════════════════════════
// 5. Top Clients by Volume (Horizontal bar)
// ═══════════════════════════════════════════════
$sql_clients = "SELECT c.customer_name as name, COUNT(*) as count 
                FROM operator_assessments oa 
                LEFT JOIN customers c ON oa.client_id = c.cus_id 
                $where 
                GROUP BY oa.client_id, c.customer_name 
                ORDER BY count DESC LIMIT 10";
$stmt4 = $conn->prepare($sql_clients);
if ($types) $stmt4->bind_param($types, ...$params);
$stmt4->execute();
$res4 = $stmt4->get_result();

$client_labels = [];
$client_counts = [];
while ($r = $res4->fetch_assoc()) {
    $client_labels[] = $r['name'] ?? 'Unknown';
    $client_counts[] = (int)$r['count'];
}
$response['top_clients'] = [
    'labels' => $client_labels,
    'data'   => $client_counts
];

// ═══════════════════════════════════════════════
// 6. Top Inspectors (Bar chart)
// ═══════════════════════════════════════════════
$sql_insp = "SELECT u.username as name, COUNT(*) as count 
             FROM operator_assessments oa 
             LEFT JOIN new_users u ON oa.inspector_id = u.user_id 
             $where 
             GROUP BY oa.inspector_id, u.username 
             ORDER BY count DESC LIMIT 8";
$stmt5 = $conn->prepare($sql_insp);
if ($types) $stmt5->bind_param($types, ...$params);
$stmt5->execute();
$res5 = $stmt5->get_result();

$insp_labels = [];
$insp_counts = [];
while ($r = $res5->fetch_assoc()) {
    $insp_labels[] = $r['name'] ?? 'Unknown';
    $insp_counts[] = (int)$r['count'];
}
$response['top_inspectors'] = [
    'labels' => $insp_labels,
    'data'   => $insp_counts
];

// ═══════════════════════════════════════════════
// 7. Location Distribution (Pie chart: Onshore vs Offshore)
// ═══════════════════════════════════════════════
$sql_loc = "SELECT oa.operating_location, COUNT(*) as count 
            FROM operator_assessments oa $where 
            GROUP BY oa.operating_location";
$stmt6 = $conn->prepare($sql_loc);
if ($types) $stmt6->bind_param($types, ...$params);
$stmt6->execute();
$res6 = $stmt6->get_result();

$loc_data = ['ONSHORE' => 0, 'OFFSHORE' => 0];
while ($r = $res6->fetch_assoc()) {
    if (isset($loc_data[$r['operating_location']])) {
        $loc_data[$r['operating_location']] = (int)$r['count'];
    }
}
$response['location_distribution'] = [
    'labels' => ['Onshore', 'Offshore'],
    'data'   => array_values($loc_data)
];

// ═══════════════════════════════════════════════
// 8. Pass Rate Trend (Dual line: Exam & Signals monthly pass %)
// ═══════════════════════════════════════════════
$pr_labels = [];
$pr_exam   = [];
$pr_signal = [];
for ($i = 11; $i >= 0; $i--) {
    $m = date('M Y', strtotime("-$i months"));
    $pr_labels[] = $m;
    $pr_exam[$m]   = ['passed' => 0, 'total' => 0];
    $pr_signal[$m] = ['passed' => 0, 'total' => 0];
}

$sql_pr = "SELECT DATE_FORMAT(oa.date, '%b %Y') as month_year,
                  SUM(CASE WHEN oa.exam_status = 'PASSED' THEN 1 ELSE 0 END) as exam_pass,
                  SUM(CASE WHEN oa.exam_status IN ('PASSED','FAILED') THEN 1 ELSE 0 END) as exam_total,
                  SUM(CASE WHEN oa.signals_status = 'PASSED' THEN 1 ELSE 0 END) as sig_pass,
                  SUM(CASE WHEN oa.signals_status IN ('PASSED','FAILED') THEN 1 ELSE 0 END) as sig_total
           FROM operator_assessments oa 
           $where AND oa.date >= DATE_SUB(CURDATE(), INTERVAL 11 MONTH)
           GROUP BY month_year";
$stmt7 = $conn->prepare($sql_pr);
if ($types) $stmt7->bind_param($types, ...$params);
$stmt7->execute();
$res7 = $stmt7->get_result();
while ($r = $res7->fetch_assoc()) {
    $my = $r['month_year'];
    if (isset($pr_exam[$my])) {
        $pr_exam[$my]   = ['passed' => (int)$r['exam_pass'], 'total' => (int)$r['exam_total']];
        $pr_signal[$my] = ['passed' => (int)$r['sig_pass'],  'total' => (int)$r['sig_total']];
    }
}

$exam_rates   = [];
$signal_rates = [];
foreach ($pr_labels as $lbl) {
    $exam_rates[]   = $pr_exam[$lbl]['total'] > 0 ? round(($pr_exam[$lbl]['passed'] / $pr_exam[$lbl]['total']) * 100, 1) : 0;
    $signal_rates[] = $pr_signal[$lbl]['total'] > 0 ? round(($pr_signal[$lbl]['passed'] / $pr_signal[$lbl]['total']) * 100, 1) : 0;
}
$response['pass_rate_trend'] = [
    'labels'       => $pr_labels,
    'exam_rates'   => $exam_rates,
    'signal_rates' => $signal_rates
];

// ═══════════════════════════════════════════════
// 9. Client Pass/Fail Breakdown (Stacked bar)
// ═══════════════════════════════════════════════
$sql_cpf = "SELECT c.customer_name as name,
                   SUM(CASE WHEN oa.exam_status = 'PASSED' AND oa.signals_status = 'PASSED' THEN 1 ELSE 0 END) as both_passed,
                   SUM(CASE WHEN oa.exam_status = 'FAILED' OR oa.signals_status = 'FAILED' THEN 1 ELSE 0 END) as any_failed,
                   COUNT(*) as total
            FROM operator_assessments oa 
            LEFT JOIN customers c ON oa.client_id = c.cus_id 
            $where 
            GROUP BY oa.client_id, c.customer_name 
            ORDER BY total DESC LIMIT 8";
$stmt8 = $conn->prepare($sql_cpf);
if ($types) $stmt8->bind_param($types, ...$params);
$stmt8->execute();
$res8 = $stmt8->get_result();

$cpf_labels = [];
$cpf_passed = [];
$cpf_failed = [];
while ($r = $res8->fetch_assoc()) {
    $cpf_labels[] = $r['name'] ?? 'Unknown';
    $cpf_passed[] = (int)$r['both_passed'];
    $cpf_failed[] = (int)$r['any_failed'];
}
$response['client_pass_fail'] = [
    'labels' => $cpf_labels,
    'passed' => $cpf_passed,
    'failed' => $cpf_failed
];

// ═══════════════════════════════════════════════
// 10. Filter dropdown values
// ═══════════════════════════════════════════════
// Inspectors list
$insp_list = [];
$sql_il = "SELECT DISTINCT u.username FROM operator_assessments oa LEFT JOIN new_users u ON oa.inspector_id = u.user_id WHERE u.username IS NOT NULL ORDER BY u.username";
$res_il = $conn->query($sql_il);
while ($r = $res_il->fetch_assoc()) {
    $insp_list[] = $r['username'];
}
$response['inspectors_list'] = $insp_list;

// Clients list
$client_list = [];
$sql_cl = "SELECT DISTINCT c.cus_id, c.customer_name FROM operator_assessments oa LEFT JOIN customers c ON oa.client_id = c.cus_id WHERE c.customer_name IS NOT NULL ORDER BY c.customer_name";
$res_cl = $conn->query($sql_cl);
while ($r = $res_cl->fetch_assoc()) {
    $client_list[] = ['id' => $r['cus_id'], 'name' => $r['customer_name']];
}
$response['clients_list'] = $client_list;

// Filter summary
$response['filter_summary'] = [
    'date_range' => ($date_from && $date_to) ? "$date_from to $date_to" : (($date_from) ? "From $date_from" : (($date_to) ? "Until $date_to" : "All Time")),
    'inspector'  => $inspector ?: 'All Inspectors',
    'client'     => $client ?: 'All Clients',
    'status'     => $status ?: 'All Statuses',
    'total'      => $total
];

echo json_encode($response);
