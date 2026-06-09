<?php
session_start();
include_once('../../file/config.php');

if (!isset($_SESSION['username'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

$user = $_SESSION['username'];
$role = $_SESSION['role'] ?? '';

$filterInspector = trim($_GET['filter_inspector'] ?? '');
$filterClient    = trim($_GET['filter_client'] ?? '');
$filterType      = trim($_GET['filter_type'] ?? '');
$filterDateFrom  = trim($_GET['filter_date_from'] ?? '');
$filterDateTo    = trim($_GET['filter_date_to'] ?? '');
$filterYear      = trim($_GET['filter_year'] ?? '');
$filterExpiry    = trim($_GET['filter_expiry'] ?? '');

/* ── Build WHERE clause ─────────────────────────────────────── */
$where  = " WHERE 1=1 ";
$params = [];
$types  = "";

if (!in_array($role, ['admin', 'document controller', 'quality controller', 'reviewer'])) {
    $where .= " AND ci.inspected_by = ? ";
    $params[] = $user;
    $types  .= "s";
}
if ($filterInspector !== '') {
    $where .= " AND ci.inspected_by = ? ";
    $params[] = $filterInspector;
    $types  .= "s";
}
if ($filterClient !== '') {
    $where .= " AND ci.client_name = ? ";
    $params[] = $filterClient;
    $types  .= "s";
}
if ($filterType !== '') {
    $where .= " AND ci.checklist_type = ? ";
    $params[] = $filterType;
    $types  .= "s";
}
if ($filterDateFrom !== '') {
    $where .= " AND ci.created_at >= ? ";
    $params[] = $filterDateFrom . ' 00:00:00';
    $types  .= "s";
}
if ($filterDateTo !== '') {
    $where .= " AND ci.created_at <= ? ";
    $params[] = $filterDateTo . ' 23:59:59';
    $types  .= "s";
}
if ($filterYear !== '') {
    $where .= " AND ci.created_at >= ? AND ci.created_at <= ? ";
    $params[] = $filterYear . '-01-01 00:00:00';
    $params[] = $filterYear . '-12-31 23:59:59';
    $types  .= "ss";
}
if ($filterExpiry !== '') {
    $where .= " AND r.next_due_date = ? ";
    $params[] = $filterExpiry;
    $types  .= "s";
}

/* ── SINGLE optimised query ─────────────────────────────────── */
$sql = "
SELECT
    ci.inspected_by,
    ci.client_name,
    ci.checklist_type,
    ci.created_at,
    COALESCE(pi.project_status, '') AS project_status,
    r.next_due_date
FROM checklist_information ci
LEFT JOIN project_info pi ON ci.project_no = pi.project_no
LEFT JOIN (
    SELECT project_no, MAX(next_inspection_due_date) AS next_due_date
    FROM reports
    GROUP BY project_no
) r ON ci.project_no = r.project_no
$where
";

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    echo json_encode(['error' => 'Query prepare failed', 'detail' => $conn->error]);
    exit;
}

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    echo json_encode(['error' => 'Query execution failed', 'detail' => $conn->error]);
    exit;
}

/* ── Process rows once ─────────────────────────────────────── */
$today = date('Y-m-d');

$total     = 0;
$completed = 0;
$pending   = 0;
$active    = 0;
$expired   = 0;

$clientSet    = [];
$inspectorSet = [];
$typeSet      = [];

$monthlyData   = [];
$inspectorData = [];
$clientData    = [];
$typeData      = [];

while ($row = $result->fetch_assoc()) {
    $total++;

    // Status
    if ($row['project_status'] === 'Completed') {
        $completed++;
    } else {
        $pending++;
    }

    // Active / Expired
    if (!empty($row['next_due_date'])) {
        if ($row['next_due_date'] >= $today) {
            $active++;
        } else {
            $expired++;
        }
    }

    // Distinct counts
    if ($row['client_name'] !== '' && $row['client_name'] !== null) {
        $clientSet[$row['client_name']] = true;
    }
    if ($row['inspected_by'] !== '' && $row['inspected_by'] !== null) {
        $inspectorSet[$row['inspected_by']] = true;
    }
    if ($row['checklist_type'] !== '' && $row['checklist_type'] !== null) {
        $typeSet[$row['checklist_type']] = true;
    }

    // Monthly chart
    if (!empty($row['created_at'])) {
        $ts = strtotime($row['created_at']);
        $ym    = date('Y-m', $ts);
        $label = date('M Y', $ts);
        if (!isset($monthlyData[$ym])) {
            $monthlyData[$ym] = ['label' => $label, 'count' => 0];
        }
        $monthlyData[$ym]['count']++;
    }

    // Inspector chart
    if ($row['inspected_by'] !== '' && $row['inspected_by'] !== null) {
        $inspectorData[$row['inspected_by']] = ($inspectorData[$row['inspected_by']] ?? 0) + 1;
    }

    // Client chart
    if ($row['client_name'] !== '' && $row['client_name'] !== null) {
        $clientData[$row['client_name']] = ($clientData[$row['client_name']] ?? 0) + 1;
    }

    // Type chart
    if ($row['checklist_type'] !== '' && $row['checklist_type'] !== null) {
        $typeData[$row['checklist_type']] = ($typeData[$row['checklist_type']] ?? 0) + 1;
    }
}
$stmt->close();

/* ── Build chart arrays ─────────────────────────────────────── */

// Monthly – sort by key (YYYY-MM), take last 12
ksort($monthlyData);
$monthlyData = array_slice($monthlyData, -12, 12, true);
$monthlyLabels = array_column($monthlyData, 'label');
$monthlyValues = array_column($monthlyData, 'count');

// Inspector – sort descending, top 8
arsort($inspectorData);
$inspectorData = array_slice($inspectorData, 0, 8, true);
$inspectorLabels = array_keys($inspectorData);
$inspectorValues = array_values($inspectorData);

// Client – sort descending, top 8
arsort($clientData);
$clientData = array_slice($clientData, 0, 8, true);
$clientLabels = array_keys($clientData);
$clientValues = array_values($clientData);

// Type – sort descending, top 10
arsort($typeData);
$typeData = array_slice($typeData, 0, 10, true);
$typeLabels = array_keys($typeData);
$typeValues = array_values($typeData);

/* ── JSON response ──────────────────────────────────────────── */
echo json_encode([
    'summary' => [
        'total'           => $total,
        'completed'       => $completed,
        'pending'         => $pending,
        'active'          => $active,
        'expired'         => $expired,
        'clients'         => count($clientSet),
        'inspectors'      => count($inspectorSet),
        'checklist_types' => count($typeSet)
    ],
    'status_chart' => [
        'labels' => ['Completed', 'Pending', 'Active', 'Expired'],
        'values' => [$completed, $pending, $active, $expired]
    ],
    'inspector_chart' => [
        'labels' => $inspectorLabels,
        'values' => $inspectorValues
    ],
    'client_chart' => [
        'labels' => $clientLabels,
        'values' => $clientValues
    ],
    'type_chart' => [
        'labels' => $typeLabels,
        'values' => $typeValues
    ],
    'monthly_chart' => [
        'labels' => $monthlyLabels,
        'values' => $monthlyValues
    ]
]);
?>
