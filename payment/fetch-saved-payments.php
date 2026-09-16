<?php
ini_set('display_errors', 0);
error_reporting(0);
session_start();

include_once('../file/config.php');
if (!$conn) {
    echo json_encode(["draw" => 0, "recordsTotal" => 0, "recordsFiltered" => 0, "data" => [], "error" => "Database connection failed"]);
    exit;
}

$conn->set_charset("utf8mb4");

$user_role = $_SESSION['role'] ?? '';
if ($user_role !== 'admin') {
    echo json_encode(["draw" => 0, "recordsTotal" => 0, "recordsFiltered" => 0, "data" => [], "error" => "Unauthorized access"]);
    exit;
}

// Auto-create table if not exists
$conn->query("CREATE TABLE IF NOT EXISTS payment_list (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_no VARCHAR(100) NOT NULL UNIQUE,
    inspector_name VARCHAR(255) NOT NULL,
    no_of_equipment INT DEFAULT 0,
    total_amount DECIMAL(10,2) DEFAULT 0.00,
    travel_charge DECIMAL(10,2) DEFAULT 0.00,
    broucher_no VARCHAR(100) DEFAULT NULL,
    remarks VARCHAR(50) DEFAULT 'Self',
    other_inspector_name VARCHAR(255) DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)");

$draw   = intval($_POST['draw'] ?? 0);
$start  = intval($_POST['start'] ?? 0);
$length = intval($_POST['length'] ?? 25);
$search = trim($_POST['search']['value'] ?? '');

$filter_year            = trim($_POST['filter_year'] ?? '2026');
$filter_client          = trim($_POST['filter_client'] ?? '');
$filter_inspector       = trim($_POST['filter_inspector'] ?? '');
$filter_other_inspector = trim($_POST['filter_other_inspector'] ?? '');
$filter_remarks         = trim($_POST['filter_remarks'] ?? '');
$filter_equip_id        = trim($_POST['filter_equip_id'] ?? '');
$filter_location        = trim($_POST['filter_location'] ?? '');
$filter_sticker_no      = trim($_POST['filter_sticker_no'] ?? '');
$filter_equip_type      = trim($_POST['filter_equip_type'] ?? '');
$filter_inspection_type = trim($_POST['filter_inspection_type'] ?? '');
$filter_date_from       = trim($_POST['filter_date_from'] ?? '');
$filter_date_to         = trim($_POST['filter_date_to'] ?? '');

$cols = [
    0  => 'pl.id',
    1  => 'pl.project_no',
    2  => 'pl.project_no',
    3  => 'pl.created_at',
    4  => 'pi.equipment_id',
    5  => 'pi.equipment_location',
    6  => 'pi.customer_name',
    7  => 'pl.inspector_name',
    8  => 'pl.total_amount',
    9  => 'pl.travel_charge',
    10 => 'pl.broucher_no',
    11 => 'pl.remarks',
    12 => 'pl.other_inspector_name',
    13 => 'pl.project_no',
    14 => 'pi.equipment_type',
    15 => 'pi.inspection_type',
    16 => 'pl.no_of_equipment'
];

$orderBy  = 'pl.created_at';
$orderDir = 'DESC';

if (isset($_POST['order'][0])) {
    $colIdx = intval($_POST['order'][0]['column']);
    if (isset($cols[$colIdx])) {
        $orderBy = $cols[$colIdx];
    }
    $orderDir = (strtolower($_POST['order'][0]['dir'] ?? '') === 'asc') ? 'ASC' : 'DESC';
}

$whereConditions = [];
$params = [];
$types  = "";

if ($filter_year !== '' && $filter_year !== 'all') {
    $whereConditions[] = "pl.created_at >= ? AND pl.created_at <= ?";
    $params[] = "$filter_year-01-01 00:00:00";
    $params[] = "$filter_year-12-31 23:59:59";
    $types .= "ss";
}

if ($search !== '') {
    $like = "%$search%";
    $whereConditions[] = "(pl.project_no LIKE ? OR pi.customer_name LIKE ? OR pl.inspector_name LIKE ? OR pl.broucher_no LIKE ? OR pl.other_inspector_name LIKE ? OR pi.equipment_id LIKE ? OR pi.equipment_location LIKE ? OR pi.inspection_type LIKE ? OR pi.equipment_type LIKE ? OR EXISTS (SELECT 1 FROM checklist_information ci WHERE ci.project_no = pl.project_no AND ci.sticker_no LIKE ?))";
    for ($i = 0; $i < 10; $i++) {
        $params[] = $like;
        $types .= "s";
    }
}

if ($filter_client !== '' && $filter_client !== 'all') {
    $whereConditions[] = "pi.customer_name = ?";
    $params[] = $filter_client;
    $types .= "s";
}

if ($filter_inspector !== '' && $filter_inspector !== 'all') {
    $whereConditions[] = "pl.inspector_name = ?";
    $params[] = $filter_inspector;
    $types .= "s";
}

if ($filter_other_inspector !== '' && $filter_other_inspector !== 'all') {
    $whereConditions[] = "pl.other_inspector_name = ?";
    $params[] = $filter_other_inspector;
    $types .= "s";
}

if ($filter_remarks !== '' && $filter_remarks !== 'all') {
    $whereConditions[] = "pl.remarks = ?";
    $params[] = $filter_remarks;
    $types .= "s";
}

if ($filter_equip_id !== '') {
    $whereConditions[] = "pi.equipment_id LIKE ?";
    $params[] = "%$filter_equip_id%";
    $types .= "s";
}

if ($filter_location !== '') {
    $whereConditions[] = "pi.equipment_location LIKE ?";
    $params[] = "%$filter_location%";
    $types .= "s";
}

if ($filter_sticker_no !== '') {
    $whereConditions[] = "EXISTS (SELECT 1 FROM checklist_information ci WHERE ci.project_no = pl.project_no AND ci.sticker_no LIKE ?)";
    $params[] = "%$filter_sticker_no%";
    $types .= "s";
}

if ($filter_equip_type !== '' && $filter_equip_type !== 'all') {
    $whereConditions[] = "pi.equipment_type = ?";
    $params[] = $filter_equip_type;
    $types .= "s";
}

if ($filter_inspection_type !== '' && $filter_inspection_type !== 'all') {
    $whereConditions[] = "pi.inspection_type = ?";
    $params[] = $filter_inspection_type;
    $types .= "s";
}

if ($filter_date_from !== '') {
    $whereConditions[] = "DATE(pl.created_at) >= ?";
    $params[] = $filter_date_from;
    $types .= "s";
}

if ($filter_date_to !== '') {
    $whereConditions[] = "DATE(pl.created_at) <= ?";
    $params[] = $filter_date_to;
    $types .= "s";
}

$where = "";
if (count($whereConditions) > 0) {
    $where = "WHERE " . implode(" AND ", $whereConditions);
}

$totalWhereStr = "";
if ($filter_year !== '' && $filter_year !== 'all') {
    $fy_esc = $conn->real_escape_string($filter_year);
    $totalWhereStr = "WHERE created_at >= '$fy_esc-01-01 00:00:00' AND created_at <= '$fy_esc-12-31 23:59:59'";
}

$totalRes = $conn->query("SELECT COUNT(*) AS cnt FROM payment_list $totalWhereStr");
$total = ($totalRes) ? intval($totalRes->fetch_assoc()['cnt']) : 0;

$filtered = 0;
$filteredTotalAmount = 0.00;
$filteredTotalTravel = 0.00;

$sqlFiltered = "SELECT 
                    COUNT(*) AS cnt,
                    IFNULL(SUM(pl.total_amount), 0.00) AS total_amt,
                    IFNULL(SUM(pl.travel_charge), 0.00) AS total_travel
                FROM payment_list pl 
                LEFT JOIN project_info pi ON pl.project_no = pi.project_no 
                $where";

$stmt = $conn->prepare($sqlFiltered);
if (!empty($types)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$filteredRes = $stmt->get_result();
if ($filteredRes && $row = $filteredRes->fetch_assoc()) {
    $filtered            = intval($row['cnt']);
    $filteredTotalAmount = floatval($row['total_amt']);
    $filteredTotalTravel = floatval($row['total_travel']);
}
$stmt->close();

$sqlData = "SELECT pl.id, pl.project_no, pl.inspector_name, pl.no_of_equipment, pl.total_amount, pl.travel_charge, 
                   pl.broucher_no, pl.remarks, pl.other_inspector_name, pl.created_at, pi.customer_name, pi.equipment_id, pi.equipment_location, pi.inspection_type, pi.equipment_type,
                   (SELECT sticker_no FROM checklist_information WHERE project_no = pl.project_no ORDER BY id DESC LIMIT 1) AS sticker_no
            FROM payment_list pl
            LEFT JOIN project_info pi ON pl.project_no = pi.project_no
            $where
            ORDER BY $orderBy $orderDir
            LIMIT ?, ?";

$stmt = $conn->prepare($sqlData);
$typesWithLimit = $types . "ii";
$paramsWithLimit = array_merge($params, [$start, $length]);
$stmt->bind_param($typesWithLimit, ...$paramsWithLimit);
$stmt->execute();
$res = $stmt->get_result();

$data = [];
$sn = $start + 1;
while ($r = $res->fetch_assoc()) {
    $projectNoEsc = htmlspecialchars($r['project_no']);
    $noOfEquip = htmlspecialchars($r['no_of_equipment'] ?? '1');
    $totalAmt  = htmlspecialchars($r['total_amount'] ?? '0.00');
    $travelChg = htmlspecialchars($r['travel_charge'] ?? '0.00');
    $broucher  = htmlspecialchars($r['broucher_no'] ?? '');
    $remarks   = htmlspecialchars($r['remarks'] ?? 'Self');
    $otherInsp = htmlspecialchars($r['other_inspector_name'] ?? '');

    $paymentFormBtn = "
        <button type='button' class='btn btn-sm btn-success btn-open-payment-form'
            data-project='{$projectNoEsc}'
            data-equip='{$noOfEquip}'
            data-total='{$totalAmt}'
            data-travel='{$travelChg}'
            data-broucher='{$broucher}'
            data-remarks='{$remarks}'
            data-otherinsp='{$otherInsp}'
            style='padding:4px 12px; font-size:12px; border-radius:6px; font-weight:700;'>
            <i class='icofont-ui-edit'></i> Edit Details
        </button>
    ";

    $actionBtn = "<div style='display:flex; gap:6px; align-items:center;'>";
    $actionBtn .= $paymentFormBtn;
    $actionBtn .= "<a href='../job/job-details.php?id=" . urlencode($r['project_no']) . "' class='btn btn-sm btn-outline-secondary' style='padding:4px 8px; font-size:12px; border-radius:6px;' title='View Job Details'><i class='icofont-eye-open'></i></a>";
    $actionBtn .= "</div>";

    $remarksBadge = ($r['remarks'] === 'Other')
        ? "<span style='background:#fef3c7; color:#b45309; border:1px solid #fde68a; padding:3px 10px; border-radius:12px; font-weight:700; font-size:12px;'>Other</span>"
        : "<span style='background:#f3f4f6; color:#374151; border:1px solid #e5e7eb; padding:3px 10px; border-radius:12px; font-weight:700; font-size:12px;'>Self</span>";

    $otherInspectorDisplay = (!empty($r['other_inspector_name'])) ? htmlspecialchars($r['other_inspector_name']) : '-';

    $data[] = [
        "sn"                   => $sn++,
        "project_no"           => $project_link,
        "actions"              => $actionBtn,
        "created_at"           => htmlspecialchars(date('d M Y, h:i A', strtotime($r['created_at']))),
        "equipment_id"         => htmlspecialchars($r['equipment_id'] ?? '-'),
        "equipment_location"   => htmlspecialchars($r['equipment_location'] ?? '-'),
        "customer_name"        => htmlspecialchars($r['customer_name'] ?? '-'),
        "inspector_name"       => htmlspecialchars($r['inspector_name'] ?? '-'),
        "total_amount"         => "SAR " . number_format($r['total_amount'], 2),
        "travel_charge"        => "SAR " . number_format($r['travel_charge'], 2),
        "broucher_no"          => htmlspecialchars($r['broucher_no'] ?? '-'),
        "remarks"              => $remarksBadge,
        "other_inspector_name" => $otherInspectorDisplay,
        "sticker_no"           => htmlspecialchars($r['sticker_no'] ?? '-'),
        "equipment_type"       => htmlspecialchars($r['equipment_type'] ?? '-'),
        "inspection_type"      => htmlspecialchars($r['inspection_type'] ?? '-'),
        "no_of_equipment"      => htmlspecialchars($r['no_of_equipment'] ?? 0)
    ];
}
$stmt->close();

echo json_encode([
    "draw"            => $draw,
    "recordsTotal"    => $total,
    "recordsFiltered" => $filtered,
    "stats"           => [
        "total_records" => number_format($filtered),
        "total_amount"  => "SAR " . number_format($filteredTotalAmount, 2),
        "total_travel"  => "SAR " . number_format($filteredTotalTravel, 2)
    ],
    "data"            => $data
]);
