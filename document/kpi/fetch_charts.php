<?php
session_start();
include_once('../../file/config.php');

if (strtolower($_SESSION['role'] ?? '') !== 'admin') {
    http_response_code(403);
    echo json_encode([
        'customer_chart' => ['labels' => [], 'values' => []],
        'status_chart' => ['labels' => ['Done', 'Pending'], 'values' => [0, 0]],
        'inspection_type_chart' => ['labels' => [], 'values' => []],
        'certificate_reports_chart' => ['labels' => [], 'values' => []]
    ]);
    exit;
}

header('Content-Type: application/json');

$filterInspector = $_POST['filter_inspector'] ?? '';
$filterClient    = $_POST['filter_client'] ?? '';
$filterDateFrom  = $_POST['filter_date_from'] ?? '';
$filterDateTo    = $_POST['filter_date_to'] ?? '';
$filterEquipment = $_POST['filter_equipment'] ?? '';
$filterLocation  = $_POST['filter_location'] ?? '';

$where = " WHERE 1=1 ";

if ($filterInspector !== '') {
    $where .= " AND inspector_name LIKE '%" . mysqli_real_escape_string($conn, $filterInspector) . "%' ";
}
if ($filterClient !== '') {
    $where .= " AND customer_name LIKE '%" . mysqli_real_escape_string($conn, $filterClient) . "%' ";
}
if ($filterDateFrom !== '') {
    $where .= " AND DATE(creation_date) >= '" . mysqli_real_escape_string($conn, $filterDateFrom) . "' ";
}
if ($filterDateTo !== '') {
    $where .= " AND DATE(creation_date) <= '" . mysqli_real_escape_string($conn, $filterDateTo) . "' ";
}
if ($filterEquipment !== '') {
    $where .= " AND equipment_id LIKE '%" . mysqli_real_escape_string($conn, $filterEquipment) . "%' ";
}
if ($filterLocation !== '') {
    $where .= " AND equipment_location LIKE '%" . mysqli_real_escape_string($conn, $filterLocation) . "%' ";
}

$doneCase = "
CASE
    WHEN certificatestatus = 'Certificate Created' THEN 1
    WHEN project_status = 'Completed' THEN 1
    WHEN sticker_status = 'Yes' THEN 1
    ELSE 0
END
";

$statusSql = "SELECT
    SUM($doneCase) AS done_count,
    COUNT(*) - SUM($doneCase) AS pending_count
FROM project_info $where";
$statusRes = $conn->query($statusSql);
$statusRow = $statusRes ? $statusRes->fetch_assoc() : [];

$customerSql = "SELECT
    customer_name,
    SUM($doneCase) AS done_count
FROM project_info
$where
AND customer_name IS NOT NULL
AND customer_name != ''
GROUP BY customer_name
HAVING done_count > 0
ORDER BY done_count DESC, customer_name ASC
LIMIT 10";
$customerRes = $conn->query($customerSql);
$customerLabels = [];
$customerValues = [];
if ($customerRes) {
    while ($row = $customerRes->fetch_assoc()) {
        $customerLabels[] = $row['customer_name'];
        $customerValues[] = (int)$row['done_count'];
    }
}

$typeSql = "SELECT
    inspection_type,
    SUM($doneCase) AS done_count
FROM project_info
$where
AND inspection_type IS NOT NULL
AND inspection_type != ''
GROUP BY inspection_type
HAVING done_count > 0
ORDER BY done_count DESC, inspection_type ASC
LIMIT 8";
$typeRes = $conn->query($typeSql);
$typeLabels = [];
$typeValues = [];
if ($typeRes) {
    while ($row = $typeRes->fetch_assoc()) {
        $typeLabels[] = $row['inspection_type'];
        $typeValues[] = (int)$row['done_count'];
    }
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

$certWhere = str_replace(
    ['creation_date', 'inspector_name', 'customer_name', 'equipment_id', 'equipment_location'],
    ['pi.creation_date', 'pi.inspector_name', 'pi.customer_name', 'pi.equipment_id', 'pi.equipment_location'],
    $where
);

$reportLabels = [];
$reportValues = [];

foreach ($certTables as $label => $table) {
    $sql = "SELECT COUNT(*) AS cnt
    FROM $table c
    LEFT JOIN project_info pi ON c.project_no = pi.project_no
    $certWhere";
    $res = $conn->query($sql);
    $row = $res ? $res->fetch_assoc() : ['cnt' => 0];
    $reportLabels[] = $label;
    $reportValues[] = (int)($row['cnt'] ?? 0);
}

echo json_encode([
    'customer_chart' => [
        'labels' => $customerLabels,
        'values' => $customerValues
    ],
    'status_chart' => [
        'labels' => ['Done', 'Pending'],
        'values' => [
            (int)($statusRow['done_count'] ?? 0),
            (int)($statusRow['pending_count'] ?? 0)
        ]
    ],
    'inspection_type_chart' => [
        'labels' => $typeLabels,
        'values' => $typeValues
    ],
    'certificate_reports_chart' => [
        'labels' => $reportLabels,
        'values' => $reportValues
    ]
]);
?>
