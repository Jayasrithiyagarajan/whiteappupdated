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
$search = $_POST['search']['value'] ?? $_POST['search'] ?? $_GET['search']['value'] ?? $_GET['search'] ?? '';

/* 🔥 FILTER PARAMETERS */
$filterInspector = $_GET['filter_inspector'] ?? $_POST['filter_inspector'] ?? '';
$filterDate      = $_GET['filter_date'] ?? $_POST['filter_date'] ?? '';
$filterDateFrom  = $_GET['filter_date_from'] ?? $_POST['filter_date_from'] ?? '';
$filterDateTo    = $_GET['filter_date_to'] ?? $_POST['filter_date_to'] ?? '';
$filterClient    = $_GET['filter_client'] ?? $_POST['filter_client'] ?? '';
$filterStatus    = $_GET['filter_status'] ?? $_POST['filter_status'] ?? '';
$filterYear      = $_GET['filter_year'] ?? $_POST['filter_year'] ?? '';

/* ---------- WHERE ---------- */
$where = " WHERE 1=1 ";

if ($role === 'inspector') {
    $where .= " AND mc.inspector_name = '".$conn->real_escape_string($username)."'";
}

if (!empty($filterInspector)) {
    $where .= " AND mc.inspector_name='".mysqli_real_escape_string($conn,$filterInspector)."' ";
}

$dateField = "COALESCE(NULLIF(mc.examination_date, '0000-00-00'), NULLIF(DATE(mc.created_at), '0000-00-00'))";

if (!empty($filterDateFrom)) {
    $where .= " AND $dateField >= '".mysqli_real_escape_string($conn, $filterDateFrom)."' ";
}
if (!empty($filterDateTo)) {
    $where .= " AND $dateField <= '".mysqli_real_escape_string($conn, $filterDateTo)."' ";
}
if (empty($filterDateFrom) && empty($filterDateTo) && !empty($filterDate)) {
    $where .= " AND $dateField = '".mysqli_real_escape_string($conn, $filterDate)."' ";
}

if (!empty($filterClient)) {
    $where .= " AND mc.customer_name='".mysqli_real_escape_string($conn,$filterClient)."' ";
}
if (!empty($filterYear)) {
    $where .= " AND YEAR($dateField)='".mysqli_real_escape_string($conn,$filterYear)."' ";
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
        DATE_FORMAT($dateField, '%d-%m-%Y') LIKE '%$s%'
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
$orderColumnIndex = $_POST['order'][0]['column'] ?? 1;
$orderDir = ($_POST['order'][0]['dir'] ?? 'desc') === 'asc' ? 'ASC' : 'DESC';

if ($orderColumnIndex == 0 || $orderColumnIndex == 1) {
    $orderSql = "CAST(SUBSTRING(mc.project_no, 5) AS UNSIGNED) $orderDir";
} else {
    $columns = [
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
    $orderSql = "$orderCol $orderDir";
}

/* ---------- EXPORT MODE ---------- */
$isExport = (isset($_POST['export']) && $_POST['export'] === 'true') || (isset($_GET['export']) && $_GET['export'] === 'true');

/* ---------- DATA ---------- */
$sql = "
SELECT
    mc.*,
    pi.project_status
FROM mobile_crane_loadtest mc
LEFT JOIN project_info pi ON pi.project_no = mc.project_no
$where
ORDER BY $orderSql
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
        $dispDate = !empty($r['examination_date']) && $r['examination_date'] != '0000-00-00'
            ? date('d-m-Y', strtotime($r['examination_date']))
            : (!empty($r['created_at']) && $r['created_at'] != '0000-00-00' ? date('d-m-Y', strtotime($r['created_at'])) : 'N/A');

        fputcsv($out, [
            $r['project_no'],
            $r['certificate_no'],
            $r['equipment_description'],
            $r['equipment_id'],
            $r['inspector_name'],
            $r['employer_address'],
            $r['premises_address'],
            $dispDate,
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

    $dispDate = !empty($r['examination_date']) && $r['examination_date'] != '0000-00-00'
        ? date('d-m-Y', strtotime($r['examination_date']))
        : (!empty($r['created_at']) && $r['created_at'] != '0000-00-00' ? date('d-m-Y', strtotime($r['created_at'])) : 'N/A');

    $actions = "
        <div class='action-icons'>
            <a href='view.php?project_no={$r['project_no']}' class='view-icon' target='_blank' title='View'><i class='fa fa-eye text-primary'></i></a>
            <a href='download.php?project_no={$r['project_no']}' class='download-icon' title='Download'><i class='fa fa-download text-success'></i></a>
    ";

    if (($role === 'document controller' || $role === 'inspector' || $role === 'admin') && $r['project_status'] !== 'Completed') {
        $actions .= "
            <a href='edit_mobile.php?project_no={$r['project_no']}' class='edit-icon' target='_blank' title='Edit'><i class='fa fa-edit text-warning'></i></a>
        ";
    }

    if ($r['project_status'] !== 'Completed') {
        $actions .= "
            <a href='javascript:void(0)' onclick=\"deleteRow('{$r['project_no']}')\" class='delete-icon text-danger' title='Delete'><i class='fa fa-trash'></i></a>";
    }
    $actions .= "
        </div>
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
        "examination_date" => $dispDate,
        "inspection_date" => $dispDate,
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