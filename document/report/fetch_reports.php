<?php
session_start();
include_once('../../file/config.php');

$user = $_SESSION['username'];
$role = $_SESSION['role'];

$draw   = intval($_POST['draw'] ?? 0);
$start  = intval($_POST['start'] ?? 0);
$length = intval($_POST['length'] ?? 10);
$search = $_POST['search']['value'] ?? '';

/* 🔥 FILTER PARAMETERS */
$filterInspector = $_POST['filter_inspector'] ?? '';
$filterDate = $_POST['filter_date'] ?? '';
$filterClient = $_POST['filter_client'] ?? '';
$filterYear = $_POST['filter_year'] ?? '';
$filterExpiry = $_POST['filter_expiry'] ?? '';

/* 🔥 COLUMN MAP — NUMERIC PROJECT SORT */
$columns = [
    "CAST(SUBSTRING(r.project_no, 5) AS UNSIGNED)", // 0 Project No
    "CAST(r.report_no AS UNSIGNED)",                // 1 Report No
    "r.checklist_no",
    "r.date_of_inspection",
    "r.client_company_name",
    "r.equipment_id_no",
    "r.equipment_serial_no",
    "r.sticker_number_issued",
    "r.location",
    "r.issued_by",
    "r.next_inspection_due_date",                   // 10 Expiry Date
    "r.next_inspection_due_date >= CURDATE()",      // 11 Status (Active/Expired)
    "r.project_no"
];

$where = " WHERE 1 ";

/* ROLE FILTER - Applied to ALL queries */
if (trim(strtolower($role)) === 'inspector') {
    $where .= " AND r.issued_by='".mysqli_real_escape_string($conn,$user)."' ";
}

/* TOTAL RECORD COUNT FOR THIS USER/ROLE (Before Search/Filters) */
$totalCountResult = $conn->query("SELECT COUNT(*) as total FROM reports r $where");
$recordsTotal = $totalCountResult->fetch_assoc()['total'];

/* 🔍 APPLY SEARCH/ADDITIONAL FILTERS */
if (!empty($filterInspector)) {
    $where .= " AND r.issued_by='".mysqli_real_escape_string($conn,$filterInspector)."' ";
}
if (!empty($filterDate)) {
    $where .= " AND DATE(r.date_of_inspection)='".mysqli_real_escape_string($conn,$filterDate)."' ";
}
if (!empty($filterClient)) {
    $where .= " AND r.client_company_name='".mysqli_real_escape_string($conn,$filterClient)."' ";
}
if (!empty($filterYear)) {
    $where .= " AND YEAR(r.date_of_inspection)='".mysqli_real_escape_string($conn,$filterYear)."' ";
}
if (!empty($filterExpiry)) {
    $where .= " AND DATE(r.next_inspection_due_date)='".mysqli_real_escape_string($conn,$filterExpiry)."' ";
}

/* 🔍 SEARCH (INCLUDES STICKER NO) */
if ($search !== '') {
    $search = mysqli_real_escape_string($conn,$search);
    $where .= " AND (
    r.project_no LIKE '%$search%' OR
    r.report_no LIKE '%$search%' OR
    r.checklist_no LIKE '%$search%' OR
    r.client_company_name LIKE '%$search%' OR
    r.equipment_id_no LIKE '%$search%' OR
    r.equipment_serial_no LIKE '%$search%' OR
    r.sticker_number_issued LIKE '%$search%' OR
    r.location LIKE '%$search%' OR
    r.issued_by LIKE '%$search%' OR

    /* 🔥 DATE SEARCH FIX */
    DATE_FORMAT(r.date_of_inspection, '%d-%m-%Y') LIKE '%$search%' OR
    DATE_FORMAT(r.date_of_inspection, '%Y-%m-%d') LIKE '%$search%' OR
    DATE_FORMAT(r.date_of_inspection, '%Y-%m') LIKE '%$search%'
)";

}

/* FILTERED RECORD COUNT (After Search/Filters) */
$filteredCountResult = $conn->query("SELECT COUNT(*) as total FROM reports r $where");
$recordsFiltered = $filteredCountResult->fetch_assoc()['total'];

/* ORDER */
$idx = $_POST['order'][0]['column'] ?? 0;
$dir = ($_POST['order'][0]['dir'] ?? 'desc') === 'asc' ? 'ASC' : 'DESC';

/* 🔥 FORCE PROJECT_NO NUMERIC DESC */
$orderCol = $columns[$idx] ?? "CAST(SUBSTRING(r.project_no,5) AS UNSIGNED)";

/* DATA QUERY */
$sql = "
SELECT r.*, p.project_status
FROM reports r
JOIN project_info p ON r.project_no = p.project_no
$where
ORDER BY $orderCol $dir
LIMIT $start, $length
";

$res = $conn->query($sql);

$data = [];
while ($r = $res->fetch_assoc()) {

    $initial = strtoupper(substr($r['issued_by'], 0, 1));

    // Format expiry date
    $expiryDate = !empty($r['next_inspection_due_date']) 
        ? date('d-m-Y', strtotime($r['next_inspection_due_date'])) 
        : 'N/A';

    // Status Calculation
    $statusBadge = '';
    if (!empty($r['next_inspection_due_date'])) {
        if (strtotime($r['next_inspection_due_date']) < time()) {
            $statusBadge = "<span class='badge badge-danger'>Expired</span>";
        } else {
            $statusBadge = "<span class='badge badge-success'>Active</span>";
        }
    } else {
        $statusBadge = "<span class='badge badge-secondary'>N/A</span>";
    }

    $data[] = [
        $r['project_no'],
        $r['report_no'],
        $r['checklist_no'],
        date('d-m-Y', strtotime($r['date_of_inspection'])),
        $r['client_company_name'],
        $r['equipment_id_no'],
        $r['equipment_serial_no'],
        $r['sticker_number_issued'],
        $r['location'],
        "<div style='display:flex;align-items:center;gap:8px'>
            <div class='avatar-circle'>$initial</div>
            {$r['issued_by']}
        </div>",
        $expiryDate,
        $statusBadge,
        "<div style='display:flex;gap:12px;align-items:center'>
    <a href='view.php?project_no={$r['project_no']}&report_no={$r['report_no']}' 
       title='View Report'
       style='color:#4f46e5;font-size:18px'>
        <i class='fas fa-eye'></i>
    </a>

    <a href='edit.php?project_no={$r['project_no']}&report_no={$r['report_no']}' 
       title='Edit Report'
       style='color:#f59e0b;font-size:18px'>
        <i class='fas fa-edit'></i>
    </a>

    <a href='download.php?project_no={$r['project_no']}&report_no={$r['report_no']}' 
       title='Download Report'
       style='color:#059669;font-size:18px'>
        <i class='fas fa-download'></i>
    </a>
</div>"

    ];
}

echo json_encode([
    "draw" => $draw,
    "recordsTotal" => $recordsTotal,
    "recordsFiltered" => $recordsFiltered,
    "data" => $data
]);
