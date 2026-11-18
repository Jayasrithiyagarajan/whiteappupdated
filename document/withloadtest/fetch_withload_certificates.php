<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);

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
$search = $_POST['search']['value'] ?? $_POST['search'] ?? $_GET['search']['value'] ?? $_GET['search'] ?? '';

/* ── FILTER PARAMETERS ── */
$filterInspector = $_GET['filter_inspector'] ?? $_POST['filter_inspector'] ?? '';
$filterDate      = $_GET['filter_date'] ?? $_POST['filter_date'] ?? '';
$filterDateFrom  = $_GET['filter_date_from'] ?? $_POST['filter_date_from'] ?? '';
$filterDateTo    = $_GET['filter_date_to'] ?? $_POST['filter_date_to'] ?? '';
$filterClient    = $_GET['filter_client'] ?? $_POST['filter_client'] ?? '';
$filterStatus    = $_GET['filter_status'] ?? $_POST['filter_status'] ?? '';
$filterYear      = $_GET['filter_year'] ?? $_POST['filter_year'] ?? '';

/* ── SORTING ── */
$orderColumnIndex = intval($_POST['order'][0]['column'] ?? 1);
$orderDir = ($_POST['order'][0]['dir'] ?? 'desc') === 'asc' ? 'ASC' : 'DESC';

if ($orderColumnIndex <= 1) {
    $orderSql = "CAST(SUBSTRING(w.project_no, 5) AS UNSIGNED) $orderDir";
} else {
    $colMap = [
        2 => 'w.certificate_no',
        3 => 'w.equipment_description',
        4 => 'w.inspector_name',
        5 => 'w.customer_name',
        6 => 'w.premises_address',
        7 => 'w.examination_date',
    ];
    $orderBy = $colMap[$orderColumnIndex] ?? 'w.examination_date';
    $orderSql = "$orderBy $orderDir";
}

/* ── WHERE CLAUSE ── */
$where = " WHERE 1=1 ";

if ($role === 'inspector') {
    $where .= " AND w.inspector_name = '" . mysqli_real_escape_string($conn, $username) . "'";
}

$dateField = "COALESCE(NULLIF(w.examination_date, '0000-00-00'), NULLIF(DATE(w.created_at), '0000-00-00'))";

if (!empty($filterDateFrom)) {
    $where .= " AND $dateField >= '" . mysqli_real_escape_string($conn, $filterDateFrom) . "'";
}
if (!empty($filterDateTo)) {
    $where .= " AND $dateField <= '" . mysqli_real_escape_string($conn, $filterDateTo) . "'";
}
if (empty($filterDateFrom) && empty($filterDateTo) && !empty($filterDate)) {
    $where .= " AND $dateField = '" . mysqli_real_escape_string($conn, $filterDate) . "'";
}

if (!empty($filterInspector)) {
    $where .= " AND w.inspector_name = '" . mysqli_real_escape_string($conn, $filterInspector) . "'";
}
if (!empty($filterClient)) {
    $where .= " AND w.customer_name LIKE '%" . mysqli_real_escape_string($conn, $filterClient) . "%'";
}
if (!empty($filterStatus)) {
    if ($filterStatus === 'active') {
        $where .= " AND (w.latest_date_exam >= CURDATE() OR w.latest_date_exam IS NULL OR w.latest_date_exam = '0000-00-00')";
    } elseif ($filterStatus === 'expired') {
        $where .= " AND (w.latest_date_exam < CURDATE() AND w.latest_date_exam != '0000-00-00' AND w.latest_date_exam IS NOT NULL)";
    }
}
if (!empty($filterYear)) {
    $where .= " AND YEAR($dateField) = '" . intval($filterYear) . "'";
}
if (!empty($search)) {
    $s = mysqli_real_escape_string($conn, $search);
    $where .= " AND (
        w.project_no LIKE '%$s%' OR
        w.certificate_no LIKE '%$s%' OR
        w.equipment_description LIKE '%$s%' OR
        w.inspector_name LIKE '%$s%' OR
        w.customer_name LIKE '%$s%' OR
        w.premises_address LIKE '%$s%' OR
        DATE_FORMAT($dateField, '%d-%m-%Y') LIKE '%$s%'
    )";
}

/* ── KPI COUNTS ── */
$kpiSql = "
SELECT
    COUNT(*) AS total,
    COUNT(CASE WHEN (w.latest_date_exam >= CURDATE() OR w.latest_date_exam IS NULL OR w.latest_date_exam = '0000-00-00') THEN 1 END) AS active,
    COUNT(CASE WHEN (w.latest_date_exam < CURDATE() AND w.latest_date_exam != '0000-00-00' AND w.latest_date_exam IS NOT NULL) THEN 1 END) AS expired
FROM withload w
$where
";
$kpiRes = $conn->query($kpiSql);
$kpi = $kpiRes ? $kpiRes->fetch_assoc() : ['total'=>0,'active'=>0,'expired'=>0];

/* ── TOTAL RECORDS (unfiltered) ── */
$totalRes = $conn->query("SELECT COUNT(*) as cnt FROM withload w WHERE 1=1" . ($role === 'inspector' ? " AND w.inspector_name = '" . mysqli_real_escape_string($conn, $username) . "'" : ""));
$totalRecords = $totalRes ? intval($totalRes->fetch_assoc()['cnt']) : 0;

/* ── FILTERED COUNT ── */
$filteredRes = $conn->query("SELECT COUNT(*) as cnt FROM withload w $where");
$filteredRecords = $filteredRes ? intval($filteredRes->fetch_assoc()['cnt']) : 0;

/* ── EXPORT MODE ── */
$isExport = (isset($_POST['export']) && $_POST['export'] === 'true') || (isset($_GET['export']) && $_GET['export'] === 'true');

/* ── MAIN DATA QUERY ── */
$sql = "
SELECT
    w.project_no,
    w.certificate_no,
    w.equipment_description,
    w.equipment_id,
    w.inspector_name,
    w.customer_name,
    w.premises_address,
    w.examination_date,
    w.created_at,
    w.latest_date_exam,
    pi.project_status
FROM withload w
LEFT JOIN project_info pi ON w.project_no = pi.project_no
$where
ORDER BY $orderSql
";

if (!$isExport) {
    $sql .= " LIMIT $start, $length";
}

$res = $conn->query($sql);

if (!$res) {
    if ($isExport) { die("Query Error: " . $conn->error); }
    header('Content-Type: application/json');
    echo json_encode([
        "draw" => $draw, "recordsTotal" => 0, "recordsFiltered" => 0,
        "kpi" => $kpi, "data" => [], "error" => $conn->error
    ]);
    exit;
}

/* ── EXPORT CSV ── */
if ($isExport) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="withload_certificates_' . date('Ymd_His') . '.csv"');
    $out = fopen('php://output', 'w');
    fputcsv($out, ['Project No', 'Certificate No', 'Equipment Description', 'Equipment ID', 'Inspector', 'Client', 'Location', 'Exam Date', 'Next Exam Date', 'Status']);
    while ($row = $res->fetch_assoc()) {
        $nextDate = $row['latest_date_exam'];
        if (!empty($nextDate) && $nextDate !== '0000-00-00') {
            $status = (strtotime($nextDate) >= strtotime('today')) ? 'Active' : 'Expired';
        } else {
            $status = 'Active';
        }
        $dispDate = !empty($row['examination_date']) && $row['examination_date'] != '0000-00-00'
            ? date('d-m-Y', strtotime($row['examination_date']))
            : (!empty($row['created_at']) && $row['created_at'] != '0000-00-00' ? date('d-m-Y', strtotime($row['created_at'])) : 'N/A');

        fputcsv($out, [
            $row['project_no'],
            $row['certificate_no'],
            strip_tags($row['equipment_description']),
            $row['equipment_id'],
            $row['inspector_name'],
            $row['customer_name'],
            $row['premises_address'],
            $dispDate,
            $nextDate && $nextDate !== '0000-00-00' ? date('d-m-Y', strtotime($nextDate)) : 'N/A',
            $status
        ]);
    }
    fclose($out);
    exit;
}

/* ── BUILD ROWS ── */
$data = [];
while ($row = $res->fetch_assoc()) {
    $inspectorName = trim($row['inspector_name'] ?? '');
    $initial = !empty($inspectorName) ? strtoupper(substr($inspectorName, 0, 1)) : 'U';

    $nextDate = $row['latest_date_exam'];
    if (!empty($nextDate) && $nextDate !== '0000-00-00') {
        $isActive = strtotime($nextDate) >= strtotime('today');
    } else {
        $isActive = true;
    }
    
    $statusBadge = $isActive
        ? "<span class='status-badge badge-success'>Active</span>"
        : "<span class='status-badge badge-danger'>Expired</span>";

    $dispDate = !empty($row['examination_date']) && $row['examination_date'] != '0000-00-00'
        ? date('d-m-Y', strtotime($row['examination_date']))
        : (!empty($row['created_at']) && $row['created_at'] != '0000-00-00' ? date('d-m-Y', strtotime($row['created_at'])) : 'N/A');
    $projectNoSort = intval(preg_replace('/[^0-9]/', '', $row['project_no']));

    $inspectorHtml = '
        <div style="display:flex; gap:8px; align-items:center;">
            <div class="avatar-circle" style="background-color: ' . getInitialColor($row['inspector_name']) . ';">
                ' . $initial . '
            </div>
            <span>' . htmlspecialchars($inspectorName) . '</span>
        </div>';

    $actions = '
        <div class="action-icons">
            <a href="view.php?project_no='.$row['project_no'].'" class="view-icon" title="View" target="_blank">
                <i class="fa fa-eye"></i>
            </a>
            <a href="download.php?project_no='.$row['project_no'].'" class="download-icon" title="Download">
                <i class="fa fa-download"></i>
            </a>
    ';

    if (($role === 'document controller' || $role === 'inspector' || $role === 'admin') && $row['project_status'] !== 'Completed') {
        $actions .= '
            <a href="edit_loadtest.php?project_no='.$row['project_no'].'" class="edit-icon" title="Edit" style="color: #b45309; background: #fef3c7;">
                <i class="fa fa-edit"></i>
            </a>
        ';
    }
    
    if ($row['project_status'] !== 'Completed') {
        $actions .= '
                <a href="javascript:void(0)" class="delete-icon" onclick="deleteRow(\''.$row['project_no'].'\')" title="Delete" style="color: #e11d48; background: #ffe4e6;">
                    <i class="fa fa-trash"></i>
                </a>';
    }
    $actions .= '
        </div>
    ';

    $data[] = [
        'project_no'      => $row['project_no'],
        'project_no_sort' => $projectNoSort,
        'certificate_no'  => $row['certificate_no'],
        'item_name'       => $row['equipment_description'],
        'equipment_id'    => $row['equipment_id'],
        'inspector'       => $inspectorHtml,
        'customer_name'   => $row['customer_name'],
        'location'        => $row['premises_address'],
        'exam_date'       => $dispDate,
        'inspection_date' => $dispDate,
        'status'          => $statusBadge,
        'actions'         => $actions,
    ];
}

header('Content-Type: application/json');
echo json_encode([
    'draw'            => $draw,
    'recordsTotal'    => $totalRecords,
    'recordsFiltered' => $filteredRecords,
    'kpi'             => $kpi,
    'data'            => $data,
]);
