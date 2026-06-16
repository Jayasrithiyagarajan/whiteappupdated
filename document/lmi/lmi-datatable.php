<?php
include_once('../../file/config.php');
session_start();

$role     = $_SESSION['role'];
$username = $_SESSION['username'];

/* 🔥 FILTER PARAMETERS */
$filterInspector = $_POST['filter_inspector'] ?? '';
$filterDate = $_POST['filter_date'] ?? '';
$filterClient = $_POST['filter_client'] ?? '';
$filterStatus = $_POST['filter_status'] ?? '';
$filterYear = $_POST['filter_year'] ?? '';

$columns = [
    0 => 'lmi.project_no',
    1 => 'lmi.project_no',
    2 => 'lmi.certificate_no',
    3 => 'lmi.crane_make',
    4 => 'lmi.crane_id_no',
    5 => 'lmi.inspector',
    6 => 'lmi.customer_name',
    7 => 'lmi.location',
    8 => 'lmi.created_at',
    9 => 'lmi.next_inspection_date'
];

$limit  = intval($_POST['length'] ?? 10);
$start  = intval($_POST['start'] ?? 0);
$orderColumnIndex = $_POST['order'][0]['column'] ?? 8;
$orderDir         = ($_POST['order'][0]['dir'] ?? 'desc') === 'asc' ? 'ASC' : 'DESC';
$search = $_POST['search']['value'] ?? '';

/* 🔥 NUMERIC SORT FOR PROJECT NO */
if ($orderColumnIndex == 1) {
    $orderSql = "CAST(SUBSTRING(lmi.project_no, 5) AS UNSIGNED) $orderDir";
} else {
    $orderBy = $columns[$orderColumnIndex] ?? 'lmi.created_at';
    $orderSql = "$orderBy $orderDir";
}

$where = "WHERE 1=1";

if ($role === 'inspector') {
    $where .= " AND lmi.inspector = '" . $conn->real_escape_string($username) . "'";
}

if (!empty($filterInspector)) {
    $where .= " AND lmi.inspector = '".$conn->real_escape_string($filterInspector)."'";
}
if (!empty($filterDate)) {
    $where .= " AND DATE(lmi.inspection_date) = '".$conn->real_escape_string($filterDate)."'";
}
if (!empty($filterClient)) {
    $where .= " AND lmi.customer_name = '".$conn->real_escape_string($filterClient)."'";
}
if (!empty($filterYear)) {
    $where .= " AND YEAR(lmi.created_at) = '".$conn->real_escape_string($filterYear)."'";
}
if ($filterStatus === 'Active') {
    $where .= " AND (lmi.next_inspection_date >= CURDATE() OR lmi.next_inspection_date IS NULL OR lmi.next_inspection_date = '0000-00-00')";
} elseif ($filterStatus === 'Expired') {
    $where .= " AND (lmi.next_inspection_date < CURDATE() AND lmi.next_inspection_date != '0000-00-00' AND lmi.next_inspection_date IS NOT NULL)";
}

if (!empty($search)) {
    $s = $conn->real_escape_string($search);
    $where .= " AND (
        lmi.project_no LIKE '%$s%' OR
        lmi.certificate_no LIKE '%$s%' OR
        lmi.customer_name LIKE '%$s%' OR
        lmi.inspector LIKE '%$s%' OR
        lmi.location LIKE '%$s%'
    )";
}

/* TOTAL RECORDS */
$totalData = $conn->query("SELECT COUNT(*) c FROM lmi_certificates")->fetch_assoc()['c'];

/* FILTERED */
$totalFiltered = $conn->query("SELECT COUNT(*) c FROM lmi_certificates lmi $where")->fetch_assoc()['c'];

/* KPI */
$kpi = $conn->query("
    SELECT
        COUNT(*) total,
        SUM(CASE WHEN next_inspection_date >= CURDATE() OR next_inspection_date IS NULL OR next_inspection_date = '0000-00-00' THEN 1 ELSE 0 END) active,
        SUM(CASE WHEN next_inspection_date < CURDATE() AND next_inspection_date != '0000-00-00' AND next_inspection_date IS NOT NULL THEN 1 ELSE 0 END) expired
    FROM lmi_certificates lmi
    $where
")->fetch_assoc();

/* DATA */
$sql = "
SELECT 
    lmi.project_no,
    lmi.certificate_no,
    lmi.crane_make,
    lmi.crane_model,
    lmi.crane_id_no,
    lmi.customer_name,
    lmi.location,
    lmi.inspector,
    lmi.created_at,
    lmi.next_inspection_date,
    pi.project_status
FROM lmi_certificates lmi
LEFT JOIN project_info pi ON lmi.project_no = pi.project_no
$where
ORDER BY $orderSql
LIMIT $start, $limit
";

$dataQ = $conn->query($sql);
$data = [];

while ($row = $dataQ->fetch_assoc()) {
    // === Same Avatar Style as Lifting Gear ===
    $initial = strtoupper(substr($row['inspector'] ?? 'U', 0, 1));
    $initialClass = "initial-" . $initial;

    $status = 'Active';
    $badgeClass = 'badge-success';
    
    if ($row['next_inspection_date'] && $row['next_inspection_date'] != '0000-00-00' && strtotime($row['next_inspection_date']) < time()) {
        $status = 'Expired';
        $badgeClass = 'badge-danger';
    }

    $actions = "<div class='action-icons'>";
    $actions .= "<a href='view.php?project_no={$row['project_no']}' target='_blank' class='view-icon' title='View'><i class='fa fa-eye'></i></a>";
    $actions .= "<a href='downloadnew.php?project_no={$row['project_no']}' class='download-icon' title='Download'><i class='fa fa-download'></i></a>";
    
    if (($role === 'document controller' || $role === 'inspector' || $role === 'admin') && $row['project_status'] !== 'Completed') {
        $actions .= "<a href='edit-lmi.php?project_no={$row['project_no']}' target='_blank' title='Edit'><i class='fa fa-edit'></i></a>";
    }
    
    $actions .= "<a href='javascript:void(0)' onclick=\"deleteRow('{$row['project_no']}',this)\" class='text-danger' style='margin-left:8px;' title='Delete'><i class='fa fa-trash'></i></a>";
    $actions .= "</div>";

    $data[] = [
        "project_no"      => $row['project_no'],
        "certificate_no"  => $row['certificate_no'],
        "crane"           => $row['crane_make'].' / '.$row['crane_model'],
        "crane_id_no"     => $row['crane_id_no'],
        "inspector"       => "
            <div style='display:flex;gap:8px;align-items:center'>
                <div class='avatar-circle $initialClass'>$initial</div>
                {$row['inspector']}
            </div>",
        "customer_name"   => $row['customer_name'],
        "location"        => $row['location'],
        "created_at"      => date('d-m-Y', strtotime($row['created_at'])),
        "status"          => "<span class='status-badge $badgeClass'>$status</span>",
        "actions"         => $actions
    ];
}

echo json_encode([
    "draw" => intval($_POST['draw']),
    "recordsTotal" => intval($totalData),
    "recordsFiltered" => intval($totalFiltered),
    "kpi" => [
        "total" => intval($kpi['total']),
        "active" => intval($kpi['active']),
        "expired" => intval($kpi['expired'])
    ],
    "data" => $data
]);
?>