<?php
session_start();
include_once('../../file/config.php');

if (!function_exists('getInitialColor')) {
    function getInitialColor($name) {
        $colors = [
            '#4f46e5', '#7c3aed', '#db2777', '#ea580c', '#c026d3',
            '#0891b2', '#15803d', '#b45309', '#be123c', '#4338ca'
        ];
        $hash = crc32($name ?? 'A');
        return $colors[$hash % count($colors)];
    }
}

if (!isset($_SESSION['username'])) {
    echo json_encode([
        "draw"=>0,
        "recordsTotal"=>0,
        "recordsFiltered"=>0,
        "data"=>[]
    ]);
    exit;
}

$role     = $_SESSION['role'];
$username = $_SESSION['username'];

$draw   = intval($_POST['draw'] ?? 0);
$start  = intval($_POST['start'] ?? 0);
$length = intval($_POST['length'] ?? 10);
$search = trim($_POST['search']['value'] ?? $_POST['search'] ?? $_GET['search']['value'] ?? $_GET['search'] ?? "");

/* 🔥 FILTER PARAMETERS */
$filterInspector = $_GET['filter_inspector'] ?? $_POST['filter_inspector'] ?? '';
$filterDate      = $_GET['filter_date'] ?? $_POST['filter_date'] ?? '';
$filterDateFrom  = $_GET['filter_date_from'] ?? $_POST['filter_date_from'] ?? '';
$filterDateTo    = $_GET['filter_date_to'] ?? $_POST['filter_date_to'] ?? '';
$filterClient    = $_GET['filter_client'] ?? $_POST['filter_client'] ?? '';
$filterStatus    = $_GET['filter_status'] ?? $_POST['filter_status'] ?? '';
$filterYear      = $_GET['filter_year'] ?? $_POST['filter_year'] ?? '';

/* COLUMN MAP */
$columns = [
    0 => "CAST(SUBSTRING(mc.project_no,5) AS UNSIGNED)", // numeric CIMS sort
    1 => "mc.certificate_no",
    2 => "mc.inspected_item",
    3 => "mc.serial_numbers",
    4 => "mc.inspector",
    5 => "mc.customer_name",
    6 => "mc.location",
    7 => "mc.inspection_date",
    8 => "mc.next_inspection_date", // For status
    9 => "mc.project_no"
];

/* WHERE */
$where = " WHERE 1 ";

if ($role === 'inspector') {
    $where .= " AND mc.inspector='".mysqli_real_escape_string($conn,$username)."' ";
}

$dateField = "COALESCE(NULLIF(mc.inspection_date, '0000-00-00'), NULLIF(DATE(mc.created_at), '0000-00-00'))";

if (!empty($filterDateFrom)) {
    $where .= " AND $dateField >= '" . mysqli_real_escape_string($conn, $filterDateFrom) . "' ";
}
if (!empty($filterDateTo)) {
    $where .= " AND $dateField <= '" . mysqli_real_escape_string($conn, $filterDateTo) . "' ";
}
if (empty($filterDateFrom) && empty($filterDateTo) && !empty($filterDate)) {
    $where .= " AND $dateField = '" . mysqli_real_escape_string($conn, $filterDate) . "' ";
}

if (!empty($filterInspector)) {
    $where .= " AND mc.inspector='".mysqli_real_escape_string($conn,$filterInspector)."' ";
}
if (!empty($filterClient)) {
    $where .= " AND mc.customer_name='".mysqli_real_escape_string($conn,$filterClient)."' ";
}
if (!empty($filterYear)) {
    $where .= " AND YEAR($dateField)='".mysqli_real_escape_string($conn,$filterYear)."' ";
}
if ($filterStatus === 'Active') {
    $where .= " AND (mc.next_inspection_date >= CURDATE() OR mc.next_inspection_date IS NULL OR mc.next_inspection_date = '0000-00-00')";
} elseif ($filterStatus === 'Expired') {
    $where .= " AND mc.next_inspection_date < CURDATE() AND mc.next_inspection_date != '0000-00-00' AND mc.next_inspection_date IS NOT NULL";
}


/* KEYWORD SEARCH (AND logic) */
if ($search !== "") {
    $searchSafe = mysqli_real_escape_string($conn, $search);
    $keywords = explode(" ", $searchSafe);

    foreach ($keywords as $word) {
        $where .= " AND (
            mc.project_no LIKE '%$word%' OR
            mc.certificate_no LIKE '%$word%' OR
            mc.inspected_item LIKE '%$word%' OR
            mc.serial_numbers LIKE '%$word%' OR
            mc.inspector LIKE '%$word%' OR
            mc.customer_name LIKE '%$word%' OR
            mc.location LIKE '%$word%' OR
            DATE_FORMAT($dateField,'%d-%m-%Y') LIKE '%$word%' OR
            DATE_FORMAT($dateField,'%Y-%m-%d') LIKE '%$word%'
        )";
    }
}


/* TOTAL */
$totalQuery = "
SELECT COUNT(*) total
FROM mpi_certificates mc";
$totalRecords = $conn->query($totalQuery)->fetch_assoc()['total'];

/* FILTERED */
$filteredQuery = "
SELECT COUNT(*) total
FROM mpi_certificates mc
$where";
$filteredRecords = $conn->query($filteredQuery)->fetch_assoc()['total'];

/* ORDER */
$orderIndex = intval($_POST['order'][0]['column'] ?? 0);
$orderDir   = (($_POST['order'][0]['dir'] ?? 'desc') === 'asc') ? 'ASC' : 'DESC';
$orderCol   = $columns[$orderIndex] ?? $columns[0];

/* EXPORT MODE */
$isExport = (isset($_POST['export']) && $_POST['export'] === 'true') || (isset($_GET['export']) && $_GET['export'] === 'true');

/* DATA */
$dataQuery = "
SELECT
    mc.*,
    pi.project_status
FROM mpi_certificates mc
LEFT JOIN project_info pi ON mc.project_no=pi.project_no
$where
ORDER BY $orderCol $orderDir";

if (!$isExport) {
    $dataQuery .= " LIMIT $start,$length";
}

$result = $conn->query($dataQuery);

if ($isExport) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="mpi_certificates_' . date('Ymd_His') . '.csv"');
    $out = fopen('php://output', 'w');
    fputcsv($out, ['Project No', 'Certificate No', 'Inspected Item', 'Serial No', 'Inspector', 'Client', 'Location', 'Inspection Date', 'Status']);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $rawDate = (!empty($row['inspection_date']) && $row['inspection_date'] !== '0000-00-00') ? $row['inspection_date'] : ($row['created_at'] ?? '');
            $dispDate = (!empty($rawDate) && $rawDate !== '0000-00-00') ? date('d-m-Y', strtotime($rawDate)) : '-';

            $status = 'Active';
            if (!empty($row['next_inspection_date']) && $row['next_inspection_date'] != '0000-00-00' && strtotime($row['next_inspection_date']) < time()) {
                $status = 'Expired';
            }

            fputcsv($out, [
                $row['project_no'],
                $row['certificate_no'],
                $row['inspected_item'],
                $row['serial_numbers'],
                $row['inspector'],
                $row['customer_name'],
                $row['location'],
                $dispDate,
                $status
            ]);
        }
    }
    fclose($out);
    exit;
}

$data = [];

while ($row = $result->fetch_assoc()) {

    $inspectorName = trim($row['inspector'] ?? '');
    $initial = !empty($inspectorName) ? strtoupper(substr($inspectorName, 0, 1)) : 'U';

    $status = 'Active';
    $badgeClass = 'badge-success';
    if (!empty($row['next_inspection_date']) && $row['next_inspection_date'] != '0000-00-00' && strtotime($row['next_inspection_date']) < time()) {
        $status = 'Expired';
        $badgeClass = 'badge-danger';
    }

    $inspector = '
        <div style="display:flex; gap:8px; align-items:center;">
            <div class="avatar-circle" style="background-color: ' . getInitialColor($row['inspector']) . ';">
                ' . $initial . '
            </div>
            <span>' . htmlspecialchars($inspectorName) . '</span>
        </div>';

    $actions = '
        <div class="action-icons">
            <a href="view.php?project_no='.$row['project_no'].'" class="view-icon" title="View" target="_blank">
                <i class="fa fa-eye"></i>
            </a>
            <a href="download.php?project_no='.$row['project_no'].'" class="download-icon" title="Download" target="_blank">
                <i class="fa fa-download"></i>
            </a>
    ';

    if (($role === 'document controller' || $role === 'inspector' || $role === 'admin') && $row['project_status'] !== 'Completed') {
        $actions .= '
            <a href="edit.php?project_no='.$row['project_no'].'" class="edit-icon" title="Edit" target="_blank" style="color: #b45309; background: #fef3c7;">
                <i class="fa fa-edit"></i>
            </a>
        ';
    }

    if ($row['project_status'] !== 'Completed') {
        $actions .= '
            <a href="javascript:void(0)" class="delete-icon" onclick="deleteCertificate(\''.$row['project_no'].'\')" title="Delete" style="color: #e11d48; background: #ffe4e6;">
                <i class="fa fa-trash"></i>
            </a>';
    }
    $actions .= '
        </div>
    ';

    $rawDate = (!empty($row['inspection_date']) && $row['inspection_date'] !== '0000-00-00') ? $row['inspection_date'] : ($row['created_at'] ?? '');
    $dispDate = (!empty($rawDate) && $rawDate !== '0000-00-00') ? date('d-m-Y', strtotime($rawDate)) : '-';

    $data[] = [
        $row['project_no'],
        $row['certificate_no'],
        $row['inspected_item'],
        $row['serial_numbers'],
        $inspector,
        $row['customer_name'],
        $row['location'],
        $dispDate,
        "<span class='status-badge $badgeClass'>$status</span>",
        $actions
    ];
}

/* KPI */
$kpiQuery = "
SELECT
    COUNT(*) total,
    SUM(CASE WHEN next_inspection_date >= CURDATE() OR next_inspection_date IS NULL OR next_inspection_date = '0000-00-00' THEN 1 ELSE 0 END) active,
    SUM(CASE WHEN next_inspection_date < CURDATE() AND next_inspection_date != '0000-00-00' AND next_inspection_date IS NOT NULL THEN 1 ELSE 0 END) expired
FROM mpi_certificates mc
$where";

$kpi = $conn->query($kpiQuery)->fetch_assoc();

/* RESPONSE */
echo json_encode([
    "draw"=>$draw,
    "recordsTotal"=>(int)$totalRecords,
    "recordsFiltered"=>(int)$filteredRecords,
    "data"=>$data,
    "kpi"=>[
        "total" => (int)$kpi['total'],
        "active" => (int)$kpi['active'],
        "expired" => (int)$kpi['expired']
    ]
]);
?>
