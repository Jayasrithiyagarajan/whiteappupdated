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

$role     = $_SESSION['role'] ?? '';
$username = $_SESSION['username'] ?? '';

$draw   = intval($_POST['draw'] ?? 1);
$start  = intval($_POST['start'] ?? 0);
$length = intval($_POST['length'] ?? 10);
$search = $_POST['search']['value'] ?? '';

/* 🔥 FILTER PARAMETERS */
$filterInspector = $_POST['filter_inspector'] ?? '';
$filterDate = $_POST['filter_date'] ?? '';
$filterClient = $_POST['filter_client'] ?? '';
$filterStatus = $_POST['filter_status'] ?? '';
$filterYear = $_POST['filter_year'] ?? '';

/* ---------- WHERE ---------- */
$where = " WHERE 1=1 ";

if ($role === 'inspector') {
    $where .= " AND lpi.inspector = '".$conn->real_escape_string($username)."'";
}

if (!empty($filterInspector)) {
    $where .= " AND lpi.inspector='".mysqli_real_escape_string($conn,$filterInspector)."' ";
}
if (!empty($filterDate)) {
    $where .= " AND DATE(lpi.inspection_date)='".mysqli_real_escape_string($conn,$filterDate)."' ";
}
if (!empty($filterClient)) {
    $where .= " AND lpi.customer_name='".mysqli_real_escape_string($conn,$filterClient)."' ";
}
if (!empty($filterYear)) {
    $where .= " AND YEAR(lpi.inspection_date)='".mysqli_real_escape_string($conn,$filterYear)."' ";
}

if ($filterStatus === 'Active') {
    $where .= " AND (lpi.next_inspection_date >= CURDATE() OR lpi.next_inspection_date IS NULL OR lpi.next_inspection_date = '0000-00-00')";
} elseif ($filterStatus === 'Expired') {
    $where .= " AND lpi.next_inspection_date < CURDATE() AND lpi.next_inspection_date != '0000-00-00' AND lpi.next_inspection_date IS NOT NULL";
}

if ($search !== '') {
    $s = $conn->real_escape_string($search);
    $where .= " AND (
        lpi.project_no LIKE '%$s%' OR
        lpi.certificate_no LIKE '%$s%' OR
        lpi.description LIKE '%$s%' OR
        lpi.item_checked LIKE '%$s%' OR
        lpi.inspector LIKE '%$s%' OR
        lpi.customer_name LIKE '%$s%' OR
        lpi.location LIKE '%$s%' OR
        DATE(lpi.inspection_date) LIKE '%$s%'
    )";
}

/* ---------- KPI ---------- */
$kpiQuery = "
SELECT
    COUNT(*) total,
    SUM(CASE WHEN next_inspection_date >= CURDATE() OR next_inspection_date IS NULL OR next_inspection_date = '0000-00-00' THEN 1 ELSE 0 END) active,
    SUM(CASE WHEN next_inspection_date < CURDATE() AND next_inspection_date != '0000-00-00' AND next_inspection_date IS NOT NULL THEN 1 ELSE 0 END) expired
FROM liquid_penetrant_inspection lpi
$where";
$kpi = $conn->query($kpiQuery)->fetch_assoc();

/* ---------- DATA COUNT ---------- */
$totalRecordsQuery = "SELECT COUNT(*) cnt FROM liquid_penetrant_inspection lpi";
$totalRecords = $conn->query($totalRecordsQuery)->fetch_assoc()['cnt'];

$filteredRecordsQuery = "SELECT COUNT(*) cnt FROM liquid_penetrant_inspection lpi $where";
$filteredRecords = $conn->query($filteredRecordsQuery)->fetch_assoc()['cnt'];

/* ---------- SORTING ---------- */
$orderColumnIndex = $_POST['order'][0]['column'] ?? 0;
$orderDir = ($_POST['order'][0]['dir'] ?? 'desc') === 'asc' ? 'ASC' : 'DESC';

$columns = [
    0 => 'lpi.project_no',
    1 => 'lpi.certificate_no',
    2 => 'lpi.description',
    3 => 'lpi.item_checked',
    4 => 'lpi.inspector',
    5 => 'lpi.customer_name',
    6 => 'lpi.location',
    7 => 'lpi.inspection_date',
    8 => 'lpi.next_inspection_date'
];
$orderCol = $columns[$orderColumnIndex] ?? 'lpi.project_no';

/* ---------- DATA ---------- */
$sql = "
SELECT
    lpi.*,
    pi.project_status
FROM liquid_penetrant_inspection lpi
LEFT JOIN project_info pi ON pi.project_no = lpi.project_no
$where
ORDER BY $orderCol $orderDir
LIMIT $start,$length
";

$res = $conn->query($sql);
$data = [];

while ($r = $res->fetch_assoc()) {

    $inspectorName = trim($r['inspector'] ?? '');
    $initial = !empty($inspectorName) ? strtoupper(substr($inspectorName, 0, 1)) : 'U';

    $status = 'Active';
    $badgeClass = 'badge-success';
    if (!empty($r['next_inspection_date']) && $r['next_inspection_date'] != '0000-00-00' && strtotime($r['next_inspection_date']) < time()) {
        $status = 'Expired';
        $badgeClass = 'badge-danger';
    }

    /* Action buttons */
    $actions = '
        <div class="action-icons">
            <a href="view.php?project_no='.$r['project_no'].'" class="view-icon" title="View">
                <i class="fa fa-eye"></i>
            </a>
            <a href="download.php?project_no='.$r['project_no'].'" class="download-icon" title="Download">
                <i class="fa fa-download"></i>
            </a>
    ';

    if (($role === 'document controller' || $role === 'inspector' || $role === 'admin') && $r['project_status'] !== 'Completed') {
        $actions .= '
            <a href="edit.php?project_no='.$r['project_no'].'" class="edit-icon" title="Edit" style="color: #b45309; background: #fef3c7;">
                <i class="fa fa-edit"></i>
            </a>
        ';
    }

    if ($r['project_status'] !== 'Completed') {
        $actions .= '
                <a href="javascript:void(0)" class="delete-icon" onclick="deleteRow(\''.$r['project_no'].'\')" title="Delete" style="color: #e11d48; background: #ffe4e6;">
                    <i class="fa fa-trash"></i>
                </a>';
    }
    $actions .= '
        </div>
    ';

    $data[] = [
        "project_no" => $r['project_no'],
        "certificate_no" => $r['certificate_no'],
        "description" => $r['description'],
        "item_checked" => $r['item_checked'],
        "inspector" => '
            <div style="display:flex; gap:8px; align-items:center;">
                <div class="avatar-circle" style="background-color: ' . getInitialColor($r['inspector']) . ';">
                    ' . $initial . '
                </div>
                <span>' . htmlspecialchars($inspectorName) . '</span>
            </div>',
        "customer_name" => $r['customer_name'],
        "location" => $r['location'],
        "inspection_date" => date('d-m-Y',strtotime($r['inspection_date'])),
        "status" => "<span class='status-badge $badgeClass'>$status</span>",
        "actions" => $actions
    ];
}

/* ---------- RESPONSE ---------- */
echo json_encode([
    "draw" => $draw,
    "recordsTotal" => (int)$totalRecords,
    "recordsFiltered" => (int)$filteredRecords,
    "kpi" => [
        "total" => (int)$kpi['total'],
        "active" => (int)$kpi['active'],
        "expired" => (int)$kpi['expired']
    ],
    "data" => $data
]);
?>
