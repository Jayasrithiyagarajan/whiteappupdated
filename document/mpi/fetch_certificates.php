<?php
session_start();
include_once('../../file/config.php');

function getInitialColor($name) {
    $colors = [
        '#4f46e5', '#7c3aed', '#db2777', '#ea580c', '#c026d3',
        '#0891b2', '#15803d', '#b45309', '#be123c', '#4338ca'
    ];
    $hash = crc32($name ?? 'A');
    return $colors[$hash % count($colors)];
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
$search = trim($_POST['search']['value'] ?? "");

/* 🔥 FILTER PARAMETERS */
$filterInspector = $_POST['filter_inspector'] ?? '';
$filterDate = $_POST['filter_date'] ?? '';
$filterClient = $_POST['filter_client'] ?? '';
$filterStatus = $_POST['filter_status'] ?? '';
$filterYear = $_POST['filter_year'] ?? '';

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

if (!empty($filterInspector)) {
    $where .= " AND mc.inspector='".mysqli_real_escape_string($conn,$filterInspector)."' ";
}
if (!empty($filterDate)) {
    $where .= " AND DATE(mc.inspection_date)='".mysqli_real_escape_string($conn,$filterDate)."' ";
}
if (!empty($filterClient)) {
    $where .= " AND mc.customer_name='".mysqli_real_escape_string($conn,$filterClient)."' ";
}
if (!empty($filterYear)) {
    $where .= " AND YEAR(mc.inspection_date)='".mysqli_real_escape_string($conn,$filterYear)."' ";
}
if ($filterStatus === 'Active') {
    $where .= " AND (mc.next_inspection_date >= CURDATE() OR mc.next_inspection_date IS NULL OR mc.next_inspection_date = '0000-00-00')";
} elseif ($filterStatus === 'Expired') {
    $where .= " AND mc.next_inspection_date < CURDATE() AND mc.next_inspection_date != '0000-00-00' AND mc.next_inspection_date IS NOT NULL";
}


/* KEYWORD SEARCH (AND logic) */
if ($search !== "") {
    $search = mysqli_real_escape_string($conn, $search);
    $keywords = explode(" ", $search);

    foreach ($keywords as $word) {
        $where .= " AND (
            mc.project_no LIKE '%$word%' OR
            mc.certificate_no LIKE '%$word%' OR
            mc.inspected_item LIKE '%$word%' OR
            mc.serial_numbers LIKE '%$word%' OR
            mc.inspector LIKE '%$word%' OR
            mc.customer_name LIKE '%$word%' OR
            mc.location LIKE '%$word%' OR
            DATE_FORMAT(mc.inspection_date,'%d-%m-%Y') LIKE '%$word%' OR
            DATE_FORMAT(mc.inspection_date,'%Y-%m-%d') LIKE '%$word%'
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

/* DATA */
$dataQuery = "
SELECT
    mc.*,
    pi.project_status
FROM mpi_certificates mc
LEFT JOIN project_info pi ON mc.project_no=pi.project_no
$where
ORDER BY $orderCol $orderDir
LIMIT $start,$length";

$result = $conn->query($dataQuery);

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

    $actions .= '
            <a href="javascript:void(0)" class="delete-icon" onclick="deleteCertificate(\''.$row['project_no'].'\')" title="Delete" style="color: #e11d48; background: #ffe4e6;">
                <i class="fa fa-trash"></i>
            </a>
        </div>
    ';

    $data[] = [
        $row['project_no'],
        $row['certificate_no'],
        $row['inspected_item'],
        $row['serial_numbers'],
        $inspector,
        $row['customer_name'],
        $row['location'],
        date('d-m-Y', strtotime($row['inspection_date'])),
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
