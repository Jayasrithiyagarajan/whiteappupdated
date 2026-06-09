<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);

session_start();
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

/* ================= ORDER (FROM DATATABLES) ================= */
$orderColumnIndex = $_POST['order'][0]['column'] ?? 1;
$orderDir = ($_POST['order'][0]['dir'] ?? 'desc') === 'asc' ? 'ASC' : 'DESC';

/*
Column index mapping
(IMPORTANT: index 1 = hidden numeric sort column)
*/
if ($orderColumnIndex == 0 || $orderColumnIndex == 1) {
    // 🔥 NUMERIC sort: CIMS001 → 1, CIMS50000 → 50000
    $orderSql = "CAST(SUBSTRING(lg.project_no, 5) AS UNSIGNED) $orderDir";
} else {
    $columns = [
        2 => 'certificate_no',
        3 => 'type',
        4 => 'inspector',
        5 => 'customer_name',
        6 => 'address_of_premises',
        7 => 'date_of_this_examination',
        8 => 'next_examination_date'
    ];
    $orderBy = $columns[$orderColumnIndex] ?? 'date_of_this_examination';
    $orderSql = "$orderBy $orderDir";
}

/* ===============================
   Role-based WHERE clause
 ================================ */
$where = " WHERE 1=1 ";

if ($role === 'inspector') {
    $where .= " AND lg.inspector = '" . mysqli_real_escape_string($conn, $username) . "'";
}

if (!empty($filterInspector)) {
    $where .= " AND lg.inspector = '" . mysqli_real_escape_string($conn, $filterInspector) . "'";
}
if (!empty($filterDate)) {
    $where .= " AND DATE(lg.date_of_this_examination) = '" . mysqli_real_escape_string($conn, $filterDate) . "'";
}
if (!empty($filterClient)) {
    $where .= " AND lg.customer_name = '" . mysqli_real_escape_string($conn, $filterClient) . "'";
}
if (!empty($filterYear)) {
    $where .= " AND YEAR(lg.date_of_this_examination) = '" . mysqli_real_escape_string($conn, $filterYear) . "'";
}

if ($filterStatus === 'Active') {
    $where .= " AND (lg.next_examination_date >= CURDATE() OR lg.next_examination_date IS NULL OR lg.next_examination_date = '0000-00-00')";
} elseif ($filterStatus === 'Expired') {
    $where .= " AND lg.next_examination_date < CURDATE() AND lg.next_examination_date != '0000-00-00' AND lg.next_examination_date IS NOT NULL";
}

/* ===============================
   Search (Global)
 ================================ */
if ($search) {
    $safe = mysqli_real_escape_string($conn, $search);
    $where .= " AND (
        lg.project_no LIKE '%$safe%' OR
        lg.certificate_no LIKE '%$safe%' OR
        lg.type LIKE '%$safe%' OR
        lg.inspector LIKE '%$safe%' OR
        lg.customer_name LIKE '%$safe%' OR
        lg.address_of_premises LIKE '%$safe%' OR
        DATE_FORMAT(lg.date_of_this_examination, '%d-%m-%Y') LIKE '%$safe%'
    )";
}

/* ===============================
   KPI COUNTS (FAST & OPTIMIZED)
 ================================ */
$kpiSql = "
SELECT
    COUNT(DISTINCT project_no) AS total,
    COUNT(DISTINCT CASE WHEN next_examination_date >= CURDATE() OR next_examination_date IS NULL OR next_examination_date = '0000-00-00' THEN project_no END) AS active,
    COUNT(DISTINCT CASE WHEN next_examination_date < CURDATE() AND next_examination_date != '0000-00-00' AND next_examination_date IS NOT NULL THEN project_no END) AS expired
FROM lifting_gear_certificates lg
$where
";
$kpiRow = $conn->query($kpiSql)->fetch_assoc();

/* ===============================
   Total Records
 ================================ */
$totalRecordsQuery = "SELECT COUNT(DISTINCT project_no) AS cnt FROM lifting_gear_certificates";
$totalRecords = $conn->query($totalRecordsQuery)->fetch_assoc()['cnt'];

$filteredRecordsQuery = "SELECT COUNT(DISTINCT project_no) AS cnt FROM lifting_gear_certificates lg $where";
$filteredRecords = $conn->query($filteredRecordsQuery)->fetch_assoc()['cnt'];

/* ===============================
   Main Data Query
 ================================ */
$isExport = isset($_POST['export']) && $_POST['export'] === 'true';

$sql = "
SELECT
    lg.project_no,
    MIN(lg.certificate_no) as certificate_no,
    MIN(lg.type) as type,
    MIN(lg.inspector) as inspector,
    MIN(lg.customer_name) as customer_name,
    MIN(lg.address_of_premises) as address_of_premises,
    MIN(lg.date_of_this_examination) as date_of_this_examination,
    MIN(lg.next_examination_date) as next_examination_date
FROM lifting_gear_certificates lg
$where
GROUP BY lg.project_no
ORDER BY $orderSql
";

if (!$isExport) {
    $sql .= " LIMIT $start, $length";
}

$res = $conn->query($sql);

if (!$res) {
    if ($isExport) {
         die("Query Error: " . $conn->error);
    }
    header('Content-Type: application/json');
    echo json_encode([
        "draw"            => $draw,
        "recordsTotal"    => 0,
        "recordsFiltered" => 0,
        "kpi" => ["total"=>0,"active"=>0,"expired"=>0],
        "data" => [],
        "error" => $conn->error
    ]);
    exit;
}

if ($isExport) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=Lifting_Gear_Certificates_' . date('Ymd_His') . '.csv');
    $output = fopen('php://output', 'w');
    
    fputcsv($output, ['Project No', 'Certificate No', 'Item', 'Inspector', 'Client', 'Location', 'Exam Date', 'Status']);

    while ($r = $res->fetch_assoc()) {
        $status = 'Active';
        if (!empty($r['next_examination_date']) && $r['next_examination_date'] != '0000-00-00' && strtotime($r['next_examination_date']) < time()) {
            $status = 'Expired';
        }

        fputcsv($output, [
            $r['project_no'],
            $r['certificate_no'],
            $r['type'],
            strip_tags($r['inspector']),
            $r['customer_name'],
            $r['address_of_premises'],
            date('d-m-Y', strtotime($r['date_of_this_examination'])),
            $status
        ]);
    }
    fclose($output);
    exit;
}

$data = [];

while ($r = $res->fetch_assoc()) {
    $numeric = (int)substr($r['project_no'], 4);
    $initial = strtoupper(substr($r['inspector'] ?? 'U', 0, 1));
$initialClass = "initial-" . $initial;
    
    $status = 'Active';
    $badgeClass = 'badge-success';   // ← Changed here
    
    if (!empty($r['next_examination_date']) && $r['next_examination_date'] != '0000-00-00' && strtotime($r['next_examination_date']) < time()) {
        $status = 'Expired';
        $badgeClass = 'badge-danger';   // ← Changed here
    }

    $actions = "
        <div class='action-icons'>
            <a href='view.php?project_no={$r['project_no']}' class='view-icon' target='_blank' title='View'><i class='fa fa-eye'></i></a>
            <a href='download.php?project_no={$r['project_no']}' class='download-icon' title='Download'><i class='fa fa-download'></i></a>
            <a href='#' onclick='deleteRow(\"{$r['project_no']}\")' class='text-danger' style='margin-left:8px;' title='Delete'><i class='fa fa-trash'></i></a>
        </div>
    ";

    $data[] = [
        "project_no"        => $r['project_no'],
        "project_no_sort"   => $numeric,
        "certificate_no"    => $r['certificate_no'],
        "item_name"         => $r['type'],
        "inspector" => "
    <div style='display:flex;gap:8px;align-items:center'>
        <div class='avatar-circle $initialClass'>$initial</div>
        {$r['inspector']}
    </div>",
        "customer_name"     => $r['customer_name'],
        "location"          => $r['address_of_premises'],
        "exam_date"         => date('d-m-Y', strtotime($r['date_of_this_examination'])),
        "status"            => "<span class='status-badge $badgeClass'>$status</span>",   // ← Updated
        "actions"           => $actions
    ];
}

header('Content-Type: application/json');
echo json_encode([
    "draw"            => $draw,
    "recordsTotal"    => (int)$totalRecords,
    "recordsFiltered" => (int)$filteredRecords,
    "kpi" => [
        "total"   => (int)$kpiRow['total'],
        "active"  => (int)$kpiRow['active'],
        "expired" => (int)$kpiRow['expired']
    ],
    "data" => $data
]);
?>