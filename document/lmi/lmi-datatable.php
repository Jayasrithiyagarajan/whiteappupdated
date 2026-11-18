<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include_once('../../file/config.php');

if (!isset($_SESSION['username'])) {
    echo json_encode([
        "draw" => 0,
        "recordsTotal" => 0,
        "recordsFiltered" => 0,
        "data" => []
    ]);
    exit;
}

$role     = $_SESSION['role'] ?? '';
$username = $_SESSION['username'] ?? '';

/* FILTER PARAMETERS */
$filterInspector = $_POST['filter_inspector'] ?? $_GET['filter_inspector'] ?? '';
$filterDate      = $_POST['filter_date'] ?? $_GET['filter_date'] ?? '';
$filterDateFrom  = $_POST['filter_date_from'] ?? $_GET['filter_date_from'] ?? '';
$filterDateTo    = $_POST['filter_date_to'] ?? $_GET['filter_date_to'] ?? '';
$filterClient    = $_POST['filter_client'] ?? $_GET['filter_client'] ?? '';
$filterStatus    = $_POST['filter_status'] ?? $_GET['filter_status'] ?? '';
$filterYear      = $_POST['filter_year'] ?? $_GET['filter_year'] ?? '';
$search          = trim($_POST['search']['value'] ?? $_POST['search'] ?? $_GET['search']['value'] ?? $_GET['search'] ?? "");
$isExport        = (isset($_POST['export']) && $_POST['export'] === 'true') || (isset($_GET['export']) && $_GET['export'] === 'true');

$limit  = intval($_POST['length'] ?? 10);
$start  = intval($_POST['start'] ?? 0);
$draw   = intval($_POST['draw'] ?? 1);

$where = "WHERE 1=1";

if ($role === 'inspector') {
    $where .= " AND lmi.inspector = '" . mysqli_real_escape_string($conn, $username) . "'";
}

$dateField = "COALESCE(NULLIF(lmi.inspection_date, '0000-00-00'), NULLIF(DATE(lmi.created_at), '0000-00-00'))";

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
    $where .= " AND lmi.inspector = '" . mysqli_real_escape_string($conn, $filterInspector) . "'";
}
if (!empty($filterClient)) {
    $where .= " AND lmi.customer_name = '" . mysqli_real_escape_string($conn, $filterClient) . "'";
}
if (!empty($filterYear)) {
    $where .= " AND YEAR($dateField) = '" . mysqli_real_escape_string($conn, $filterYear) . "'";
}
if ($filterStatus === 'Active') {
    $where .= " AND (lmi.next_inspection_date >= CURDATE() OR lmi.next_inspection_date IS NULL OR lmi.next_inspection_date = '0000-00-00')";
} elseif ($filterStatus === 'Expired') {
    $where .= " AND (lmi.next_inspection_date < CURDATE() AND lmi.next_inspection_date != '0000-00-00' AND lmi.next_inspection_date IS NOT NULL)";
}

if (!empty($search)) {
    $safe = mysqli_real_escape_string($conn, $search);
    $keywords = explode(" ", $safe);
    foreach ($keywords as $word) {
        $where .= " AND (
            lmi.project_no LIKE '%$word%' OR
            lmi.certificate_no LIKE '%$word%' OR
            lmi.crane_make LIKE '%$word%' OR
            lmi.crane_model LIKE '%$word%' OR
            lmi.crane_id_no LIKE '%$word%' OR
            lmi.customer_name LIKE '%$word%' OR
            lmi.inspector LIKE '%$word%' OR
            lmi.location LIKE '%$word%' OR
            DATE_FORMAT($dateField, '%d-%m-%Y') LIKE '%$word%' OR
            DATE_FORMAT($dateField, '%Y-%m-%d') LIKE '%$word%'
        )";
    }
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

/* COLUMNS & ORDERING */
$columns = [
    0 => 'CAST(SUBSTRING(lmi.project_no, 5) AS UNSIGNED)',
    1 => 'lmi.certificate_no',
    2 => 'lmi.crane_make',
    3 => 'lmi.crane_id_no',
    4 => 'lmi.inspector',
    5 => 'lmi.customer_name',
    6 => 'lmi.location',
    7 => $dateField,
    8 => 'lmi.next_inspection_date'
];

$orderColumnIndex = intval($_POST['order'][0]['column'] ?? 0);
$orderDir         = (($_POST['order'][0]['dir'] ?? 'desc') === 'asc') ? 'ASC' : 'DESC';
$orderSql         = ($columns[$orderColumnIndex] ?? 'CAST(SUBSTRING(lmi.project_no, 5) AS UNSIGNED)') . " " . $orderDir;

/* DATA QUERY */
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
    lmi.inspection_date,
    lmi.created_at,
    lmi.next_inspection_date,
    pi.project_status
FROM lmi_certificates lmi
LEFT JOIN project_info pi ON lmi.project_no = pi.project_no
$where
ORDER BY $orderSql
";

if (!$isExport) {
    $sql .= " LIMIT $start, $limit";
}

$dataQ = $conn->query($sql);

if (!$dataQ) {
    if ($isExport) { die('Query Error: ' . $conn->error); }
    header('Content-Type: application/json');
    echo json_encode([
        "draw" => $draw,
        "recordsTotal" => 0,
        "recordsFiltered" => 0,
        "kpi" => [
            "total" => 0,
            "active" => 0,
            "expired" => 0
        ],
        "data" => [],
        "error" => $conn->error
    ]);
    exit;
}

/* EXPORT CSV */
if ($isExport) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="lmi_certificates_' . date('Ymd_His') . '.csv"');
    $out = fopen('php://output', 'w');
    fputcsv($out, ['Project No', 'Certificate No', 'Crane', 'Crane ID', 'Inspector', 'Client', 'Location', 'Inspection Date', 'Status']);
    while ($row = $dataQ->fetch_assoc()) {
        $rawDate = (!empty($row['inspection_date']) && $row['inspection_date'] !== '0000-00-00') ? $row['inspection_date'] : ($row['created_at'] ?? '');
        $dispDate = (!empty($rawDate) && $rawDate !== '0000-00-00') ? date('d-m-Y', strtotime($rawDate)) : '-';

        $status = 'Active';
        if (!empty($row['next_inspection_date']) && $row['next_inspection_date'] != '0000-00-00' && strtotime($row['next_inspection_date']) < time()) {
            $status = 'Expired';
        }

        fputcsv($out, [
            $row['project_no'],
            $row['certificate_no'],
            trim($row['crane_make'] . ' / ' . $row['crane_model'], ' /'),
            $row['crane_id_no'],
            $row['inspector'],
            $row['customer_name'],
            $row['location'],
            $dispDate,
            $status
        ]);
    }
    fclose($out);
    exit;
}

$data = [];

while ($row = $dataQ->fetch_assoc()) {
    $initial = strtoupper(substr($row['inspector'] ?? 'U', 0, 1));
    $initialClass = "initial-" . $initial;

    $status = 'Active';
    $badgeClass = 'badge-success';
    
    if ($row['next_inspection_date'] && $row['next_inspection_date'] != '0000-00-00' && strtotime($row['next_inspection_date']) < time()) {
        $status = 'Expired';
        $badgeClass = 'badge-danger';
    }

    $rawDate = (!empty($row['inspection_date']) && $row['inspection_date'] !== '0000-00-00') ? $row['inspection_date'] : ($row['created_at'] ?? '');
    $dispDate = (!empty($rawDate) && $rawDate !== '0000-00-00') ? date('d-m-Y', strtotime($rawDate)) : '-';

    $actions = "<div class='action-icons'>";
    $actions .= "<a href='view.php?project_no={$row['project_no']}' target='_blank' class='view-icon' title='View'><i class='fa fa-eye'></i></a>";
    $actions .= "<a href='downloadnew.php?project_no={$row['project_no']}' class='download-icon' title='Download'><i class='fa fa-download'></i></a>";
    
    if (($role === 'document controller' || $role === 'inspector' || $role === 'admin') && $row['project_status'] !== 'Completed') {
        $actions .= "<a href='edit-lmi.php?project_no={$row['project_no']}' target='_blank' title='Edit'><i class='fa fa-edit'></i></a>";
    }
    
    if ($row['project_status'] !== 'Completed') {
        $actions .= "<a href='javascript:void(0)' onclick=\"deleteRow('{$row['project_no']}',this)\" class='text-danger' style='margin-left:8px;' title='Delete'><i class='fa fa-trash'></i></a>";
    }
    $actions .= "</div>";

    $data[] = [
        "project_no"      => $row['project_no'],
        "certificate_no"  => $row['certificate_no'],
        "crane"           => trim($row['crane_make'] . ' / ' . $row['crane_model'], ' /'),
        "crane_id_no"     => $row['crane_id_no'],
        "inspector"       => "
            <div style='display:flex;gap:8px;align-items:center'>
                <div class='avatar-circle $initialClass'>$initial</div>
                {$row['inspector']}
            </div>",
        "customer_name"   => $row['customer_name'],
        "location"        => $row['location'],
        "created_at"      => $dispDate,
        "status"          => "<span class='status-badge $badgeClass'>$status</span>",
        "actions"         => $actions
    ];
}

echo json_encode([
    "draw" => $draw,
    "recordsTotal" => intval($totalData),
    "recordsFiltered" => intval($totalFiltered),
    "kpi" => [
        "total"   => intval($kpi['total'] ?? 0),
        "active"  => intval($kpi['active'] ?? 0),
        "expired" => intval($kpi['expired'] ?? 0)
    ],
    "data" => $data
]);
?>