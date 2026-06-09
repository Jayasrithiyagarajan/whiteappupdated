<?php
session_start();
include_once('../../file/config.php');

$role     = $_SESSION['role'];
$username = $_SESSION['username'];

$draw   = intval($_POST['draw']);
$start  = intval($_POST['start']);
$length = intval($_POST['length']);
$search = $_POST['search']['value'] ?? '';

/* IMPORTANT: project IDs are stored like CIMS6545, so sort by their numeric part. */
$projectNoSort = "CAST(REGEXP_REPLACE(lc.project_no, '[^0-9]', '') AS UNSIGNED)";
$columns = [
    $projectNoSort,
    'lc.certificate_no',
    'lc.equipment_description',
    'lc.equipment_id',
    'lc.inspector_name',
    'lc.employer_address',
    'lc.premises_address',
    'lc.examination_date',
    'lc.project_no'
];

$where = " WHERE 1 ";
if ($role === 'inspector') {
    $where .= " AND lc.inspector_name='".mysqli_real_escape_string($conn,$username)."' ";
}
if ($search) {
    $search = mysqli_real_escape_string($conn,$search);
    $where .= " AND (
        lc.project_no LIKE '%$search%' OR
        lc.certificate_no LIKE '%$search%' OR
        lc.equipment_description LIKE '%$search%' OR
        lc.inspector_name LIKE '%$search%' OR
        lc.employer_address LIKE '%$search%' OR
        lc.premises_address LIKE '%$search%'
    )";
}


$filterInspector = $_POST['filter_inspector'] ?? '';
$filterDate = $_POST['filter_date'] ?? '';
$filterClient = $_POST['filter_client'] ?? '';
$filterStatus = $_POST['filter_status'] ?? '';
$filterYear = $_POST['filter_year'] ?? '';

if (!empty($filterInspector)) {
    $where .= " AND lc.inspector_name = '" . mysqli_real_escape_string($conn, $filterInspector) . "'";
}
if (!empty($filterDate)) {
    $where .= " AND DATE(lc.examination_date) = '" . mysqli_real_escape_string($conn, $filterDate) . "'";
}
if (!empty($filterClient)) {
    $where .= " AND lc.customer_name = '" . mysqli_real_escape_string($conn, $filterClient) . "'";
}
if (!empty($filterYear)) {
    $where .= " AND YEAR(lc.examination_date) = '" . mysqli_real_escape_string($conn, $filterYear) . "'";
}
if ($filterStatus === 'Active') {
    $where .= " AND (lc.latest_date_exam >= CURDATE() OR lc.latest_date_exam IS NULL OR lc.latest_date_exam = '0000-00-00')";
} elseif ($filterStatus === 'Expired') {
    $where .= " AND lc.latest_date_exam < CURDATE() AND lc.latest_date_exam != '0000-00-00' AND lc.latest_date_exam IS NOT NULL";
}

/* KPI counts */
$kpiSql = "
SELECT
    COUNT(DISTINCT project_no) AS total,
    COUNT(DISTINCT CASE WHEN latest_date_exam >= CURDATE() OR latest_date_exam IS NULL OR latest_date_exam = '0000-00-00' THEN project_no END) AS active,
    COUNT(DISTINCT CASE WHEN latest_date_exam < CURDATE() AND latest_date_exam != '0000-00-00' AND latest_date_exam IS NOT NULL THEN project_no END) AS expired
FROM loadtest_certificate lc
$where
";
$kpiRow = $conn->query($kpiSql)->fetch_assoc();

/* total */
$total = $conn->query("
SELECT COUNT(*) total
FROM loadtest_certificate lc
LEFT JOIN project_info pi ON lc.project_no = pi.project_no
$where")->fetch_assoc()['total'];

/* order */
$idx = $_POST['order'][0]['column'] ?? 0;
$dir = ($_POST['order'][0]['dir'] ?? 'desc') === 'asc' ? 'ASC' : 'DESC';
$orderCol = $columns[$idx] ?? $projectNoSort;

/* data */
$sql = "
SELECT
 lc.project_no,
 lc.certificate_no,
 lc.equipment_description,
 lc.equipment_id,
 lc.inspector_name,
 lc.employer_address,
 lc.premises_address,
 lc.examination_date,
 pi.project_status
FROM loadtest_certificate lc
LEFT JOIN project_info pi ON lc.project_no = pi.project_no
$where
ORDER BY $orderCol $dir
LIMIT $start,$length";

$res = $conn->query($sql);
$data = [];

while($r=$res->fetch_assoc()){
    $initial = strtoupper($r['inspector_name'][0]);

    $data[]=[
        $r['project_no'],
        $r['certificate_no'],
        $r['equipment_description'],
        $r['equipment_id'],
        "<div style='display:flex;gap:8px'>
            <div class='avatar-circle'>$initial</div>
            {$r['inspector_name']}
         </div>",
        $r['employer_address'],
        $r['premises_address'],
        date('d-m-Y',strtotime($r['examination_date'])),
        "<span class='actions'>
            <a href='view.php?project_no={$r['project_no']}' target='_blank'><i class='fa fa-eye'></i></a>
            <a href='download.php?project_no={$r['project_no']}'><i class='fa fa-download'></i></a>
            ".($_SESSION['role']==='document controller' && $r['project_status']!=='Completed'
                ?"<i class='fa fa-edit' onclick=\"redirectToEditLoadTest('{$r['project_no']}')\"></i>":"")."
            <i class='fa fa-trash text-danger' onclick=\"deleteRow('{$r['project_no']}')\"></i>
        </span>"
    ];
}

echo json_encode([
    "draw"=>$draw,
    "recordsTotal"=>$total,
    "recordsFiltered"=>$total,
    "kpi" => [
        "total"   => (int)$kpiRow['total'],
        "active"  => (int)$kpiRow['active'],
        "expired" => (int)$kpiRow['expired']
    ],
    "data"=>$data
]);
