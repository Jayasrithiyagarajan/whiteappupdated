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
$filterType = $_POST['filter_type'] ?? '';
$filterDate = $_POST['filter_date'] ?? '';
$filterClient = $_POST['filter_client'] ?? '';
$filterYear = $_POST['filter_year'] ?? '';
$filterExpiry = $_POST['filter_expiry'] ?? '';

/* 🔥 COLUMN MAP (NUMERIC SAFE SORTING) */
$columns = [
    "CAST(SUBSTRING(ci.checklist_no,3) AS UNSIGNED)",
    "CAST(SUBSTRING(ci.project_no,5) AS UNSIGNED)",
    "ci.inspected_by",
    "ci.equipment_type",
    "ci.checklist_type",
    "ci.client_name",
    "ci.equipment_no",
    "ci.crane_serial_no",
    "ci.sticker_no",
    "ci.location",
    "ci.created_at",
    "(SELECT MAX(next_inspection_due_date) FROM reports WHERE project_no = ci.project_no) >= CURDATE()", // 11 Status
    "ci.checklist_id"
];

/* BASE WHERE */
$where = " WHERE 1 ";

/* ROLE FILTER */
if (!in_array($role, ['admin','document controller','quality controller','reviewer'])) {
    $where .= " AND ci.inspected_by='".mysqli_real_escape_string($conn,$user)."' ";
}

/* 🔍 APPLY FILTERS */
if (!empty($filterInspector)) {
    $where .= " AND ci.inspected_by='".mysqli_real_escape_string($conn,$filterInspector)."' ";
}
if (!empty($filterType)) {
    $where .= " AND ci.checklist_type='".mysqli_real_escape_string($conn,$filterType)."' ";
}
if (!empty($filterDate)) {
    $where .= " AND DATE(ci.created_at)='".mysqli_real_escape_string($conn,$filterDate)."' ";
}
if (!empty($filterClient)) {
    $where .= " AND ci.client_name='".mysqli_real_escape_string($conn,$filterClient)."' ";
}
if (!empty($filterYear)) {
    $where .= " AND YEAR(ci.created_at)='".mysqli_real_escape_string($conn,$filterYear)."' ";
}
if (!empty($filterExpiry)) {
    $where .= " AND (SELECT MAX(next_inspection_due_date) FROM reports WHERE project_no = ci.project_no) = '".mysqli_real_escape_string($conn,$filterExpiry)."' ";
}

/* 🔍 GLOBAL SEARCH (ALL COLUMNS) */
if ($search !== '') {
    $search = mysqli_real_escape_string($conn,$search);
    $where .= " AND (
        ci.checklist_no       LIKE '%$search%' OR
        ci.project_no         LIKE '%$search%' OR
        ci.inspected_by       LIKE '%$search%' OR
        ci.equipment_type     LIKE '%$search%' OR
        ci.checklist_type     LIKE '%$search%' OR
        ci.client_name        LIKE '%$search%' OR
        ci.equipment_no       LIKE '%$search%' OR
        ci.crane_serial_no    LIKE '%$search%' OR
        ci.sticker_no         LIKE '%$search%' OR
        ci.location           LIKE '%$search%' OR
        DATE_FORMAT(ci.created_at,'%d-%m-%Y') LIKE '%$search%'
    )";
}

/* 🔢 TOTAL RECORDS (WITHOUT SEARCH) - Apply role filter for consistency */
$totalWhere = " WHERE 1 ";
if (!in_array($role, ['admin','document controller','quality controller','reviewer'])) {
    $totalWhere .= " AND ci.inspected_by='".mysqli_real_escape_string($conn,$user)."' ";
}

$totalSql = "
SELECT COUNT(*) total
FROM checklist_information ci
$totalWhere
";

$totalRes = $conn->query($totalSql)->fetch_assoc()['total'];

/* 🔢 FILTERED RECORDS (WITH SEARCH) */
$filteredSql = "
SELECT COUNT(*) total
FROM checklist_information ci
$where
";

$filteredRes = $conn->query($filteredSql)->fetch_assoc()['total'];

/* 🔃 ORDERING */
$idx = $_POST['order'][0]['column'] ?? 10;
$dir = ($_POST['order'][0]['dir'] ?? 'desc') === 'asc' ? 'ASC' : 'DESC';
$orderCol = $columns[$idx] ?? 'ci.created_at';

/* 📄 DATA QUERY - Use correlated subquery for performance */
$sql = "
SELECT ci.*, pi.project_status, 
(SELECT MAX(next_inspection_due_date) FROM reports WHERE project_no = ci.project_no) as next_inspection_due_date
FROM checklist_information ci
LEFT JOIN project_info pi ON ci.project_no = pi.project_no
$where
ORDER BY $orderCol $dir
LIMIT $start,$length
";

$res = $conn->query($sql);
$data = [];

while ($r = $res->fetch_assoc()) {

    $initial = strtoupper(substr($r['inspected_by'],0,1));
    $typeFmt = ucwords(str_replace(['-','_'],' ',$r['checklist_type']));

    /* 🟢 STATUS LOGIC FROM REPORTS TABLE */
    $statusBadge = "<span class='oj-pill oj-pill--slate'>N/A</span>";

    if (!empty($r['next_inspection_due_date'])) {
        if (strtotime($r['next_inspection_due_date']) < time()) {
            $statusBadge = "<span class='oj-pill oj-pill--red'><span class='oj-pill__dot'></span>Expired</span>";
        } else {
            $statusBadge = "<span class='oj-pill oj-pill--teal'><span class='oj-pill__dot'></span>Active</span>";
        }
    }

    $data[] = [
        $r['checklist_no'],
        $r['project_no'],
        "<div style='display:flex;align-items:center;gap:8px'>
            <div class='avatar-circle'>$initial</div>{$r['inspected_by']}
        </div>",
        $r['equipment_type'],
        $typeFmt,
        $r['client_name'],
        $r['equipment_no'],
        $r['crane_serial_no'],
        $r['sticker_no'],
        $r['location'],
        date('d-m-Y',strtotime($r['created_at'])),
        $statusBadge,
        "<span class='actions'>
            <a href='./type/view/{$r['checklist_type']}.php?checklist_type={$r['checklist_type']}&checklist_no={$r['checklist_id']}' target='_blank' title='View'><i class='fa-solid fa-eye'></i></a>
            <a href='./type/download_pdf.php?checklist_type={$r['checklist_type']}&checklist_no={$r['checklist_id']}' target='_blank' class='action-download' title='Download PDF'><i class='fa-solid fa-download'></i></a>
            ".(
                $r['project_status'] !== 'Completed' && $role === 'inspector'
                ? "<a href='./type/{$r['checklist_type']}.php?checklist_type={$r['checklist_type']}&checklist_no={$r['checklist_id']}' title='Edit'><i class='fa-solid fa-pen-to-square'></i></a>"
                : ""
            )."
        </span>"
    ];
}

/* 🔚 RESPONSE */
echo json_encode([
    "draw"            => $draw,
    "recordsTotal"    => $totalRes,
    "recordsFiltered" => $filteredRes,
    "data"            => $data
]);
