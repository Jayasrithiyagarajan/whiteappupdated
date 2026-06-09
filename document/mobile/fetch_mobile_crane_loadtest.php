<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) session_start();
include_once('../../file/config.php');

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
    $where .= " AND mc.inspector_name = '".$conn->real_escape_string($username)."'";
}

if (!empty($filterInspector)) {
    $where .= " AND mc.inspector_name='".mysqli_real_escape_string($conn,$filterInspector)."' ";
}
if (!empty($filterDate)) {
    $where .= " AND DATE(mc.examination_date)='".mysqli_real_escape_string($conn,$filterDate)."' ";
}
if (!empty($filterClient)) {
    $where .= " AND mc.customer_name='".mysqli_real_escape_string($conn,$filterClient)."' ";
}
if (!empty($filterYear)) {
    $where .= " AND YEAR(mc.examination_date)='".mysqli_real_escape_string($conn,$filterYear)."' ";
}

if ($filterStatus === 'Active') {
    $where .= " AND (mc.latest_date_exam >= CURDATE() OR mc.latest_date_exam IS NULL OR mc.latest_date_exam = '0000-00-00')";
} elseif ($filterStatus === 'Expired') {
    $where .= " AND mc.latest_date_exam < CURDATE() AND mc.latest_date_exam != '0000-00-00' AND mc.latest_date_exam IS NOT NULL";
}

if ($search !== '') {
    $s = $conn->real_escape_string($search);
    $where .= " AND (
        mc.project_no LIKE '%$s%' OR
        mc.certificate_no LIKE '%$s%' OR
        mc.equipment_description LIKE '%$s%' OR
        mc.equipment_id LIKE '%$s%' OR
        mc.inspector_name LIKE '%$s%' OR
        mc.employer_address LIKE '%$s%' OR
        mc.premises_address LIKE '%$s%' OR
        DATE(mc.examination_date) LIKE '%$s%'
    )";
}

/* ---------- KPI ---------- */
$kpiQuery = "
SELECT
    COUNT(*) total,
    SUM(CASE WHEN latest_date_exam >= CURDATE() OR latest_date_exam IS NULL OR latest_date_exam = '0000-00-00' THEN 1 ELSE 0 END) active,
    SUM(CASE WHEN latest_date_exam < CURDATE() AND latest_date_exam != '0000-00-00' AND latest_date_exam IS NOT NULL THEN 1 ELSE 0 END) expired
FROM mobile_crane_loadtest mc
$where";
$kpi = $conn->query($kpiQuery)->fetch_assoc();

/* ---------- DATA COUNT ---------- */
$totalRecordsQuery = "SELECT COUNT(*) cnt FROM mobile_crane_loadtest mc";
$totalRecords = $conn->query($totalRecordsQuery)->fetch_assoc()['cnt'];

$filteredRecordsQuery = "SELECT COUNT(*) cnt FROM mobile_crane_loadtest mc $where";
$filteredRecords = $conn->query($filteredRecordsQuery)->fetch_assoc()['cnt'];

/* ---------- SORTING ---------- */
$orderColumnIndex = $_POST['order'][0]['column'] ?? 0;
$orderDir = ($_POST['order'][0]['dir'] ?? 'desc') === 'asc' ? 'ASC' : 'DESC';

$columns = [
    0 => 'mc.project_no',
    1 => 'mc.project_no',
    2 => 'mc.certificate_no',
    3 => 'mc.equipment_description',
    4 => 'mc.equipment_id',
    5 => 'mc.inspector_name',
    6 => 'mc.employer_address',
    7 => 'mc.premises_address',
    8 => 'mc.examination_date',
    9 => 'mc.latest_date_exam'
];
$orderCol = $columns[$orderColumnIndex] ?? 'mc.examination_date';

/* ---------- EXPORT MODE ---------- */
$isExport = isset($_POST['export']) && $_POST['export'] === 'true';

/* ---------- DATA ---------- */
$sql = "
SELECT
    mc.*,
    pi.project_status
FROM mobile_crane_loadtest mc
LEFT JOIN project_info pi ON pi.project_no = mc.project_no
$where
ORDER BY $orderCol $orderDir
";

if (!$isExport) {
    $sql .= " LIMIT $start,$length";
}

$res = $conn->query($sql);

if (!$res) {
    if ($isExport) { die('Query Error: '.$conn->error); }
    header('Content-Type: application/json');
    echo json_encode(['draw'=>$draw,'recordsTotal'=>0,'recordsFiltered'=>0,'kpi'=>$kpi,'data'=>[],'error'=>$conn->error]);
    exit;
}

/* ---------- CSV EXPORT ---------- */
if ($isExport) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="mobile_crane_certificates_'.date('Ymd_His').'.csv"');
    $out = fopen('php://output', 'w');
    fputcsv($out, ['Project No','Certificate No','Equipment','Equipment ID','Inspector','Client','Location','Exam Date','Status']);
    while ($r = $res->fetch_assoc()) {
        $isExpired = !empty($r['latest_date_exam']) && $r['latest_date_exam'] != '0000-00-00' && strtotime($r['latest_date_exam']) < time();
        fputcsv($out, [
            $r['project_no'],
            $r['certificate_no'],
            $r['equipment_description'],
            $r['equipment_id'],
            $r['inspector_name'],
            $r['employer_address'],
            $r['premises_address'],
            $r['examination_date'] ? date('d M Y', strtotime($r['examination_date'])) : '',
            $isExpired ? 'Expired' : 'Active'
        ]);
    }
    fclose($out);
    exit;
}

$data = [];

while ($r = $res->fetch_assoc()) {

    $numericProject = (int)preg_replace('/\D/', '', $r['project_no']);
    
    // === Same Avatar Style as Lifting Gear ===
    $initial = strtoupper(substr($r['inspector_name'] ?? 'U', 0, 1));
    $initialClass = "initial-" . $initial;

    $status = 'Active';
    $badgeClass = 'badge-success';
    
    if (!empty($r['latest_date_exam']) && $r['latest_date_exam'] != '0000-00-00' && strtotime($r['latest_date_exam']) < time()) {
        $status = 'Expired';
        $badgeClass = 'badge-danger';
    }

    $actions = "
        <a href='view.php?project_no={$r['project_no']}' target='_blank'><i class='fa fa-eye text-primary'></i></a>
        <a href='download.php?project_no={$r['project_no']}'><i class='fa fa-download text-success'></i></a>
    ";

    if ($role === 'document controller' && $r['project_status'] !== 'Completed') {
        $actions .= "
            <a href='edit_mobile.php?project_no={$r['project_no']}' target='_blank'>
                <i class='fa fa-edit text-warning'></i>
            </a>
        ";
    }

    $actions .= "
        <i class='fa fa-trash text-danger' style='cursor:pointer' onclick=\"deleteRow('{$r['project_no']}')\"></i>
    ";

    $data[] = [
        "project_no" => $r['project_no'],
        "project_no_sort" => $numericProject,
        "certificate_no" => $r['certificate_no'],
        "equipment_description" => $r['equipment_description'],
        "equipment_id" => $r['equipment_id'],
        "inspector" => "
            <div style='display:flex;gap:8px;align-items:center'>
                <div class='avatar-circle $initialClass'>$initial</div>
                {$r['inspector_name']}
            </div>",
        "employer_address" => $r['employer_address'],
        "premises_address" => $r['premises_address'],
        "examination_date" => date('d-m-Y', strtotime($r['examination_date'])),
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