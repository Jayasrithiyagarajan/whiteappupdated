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

// Auto-create payment_list table if it doesn't exist
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

$user_role = $_SESSION['role'] ?? '';
$user_name = $_SESSION['username'] ?? '';

if (!in_array($user_role, ['admin', 'inspector'])) {
    echo json_encode(["draw" => 0, "recordsTotal" => 0, "recordsFiltered" => 0, "data" => [], "error" => "Unauthorized access"]);
    exit;
}

$draw   = intval($_POST['draw'] ?? 0);
$start  = intval($_POST['start'] ?? 0);
$length = intval($_POST['length'] ?? 25);
$search = trim($_POST['search']['value'] ?? '');

// Helper to ensure performance indexes exist
function createPaymentIndexIfNotExists($conn, $table, $indexName, $column) {
    $res = @$conn->query("SHOW INDEX FROM `$table` WHERE Key_name = '$indexName'");
    if ($res && $res->num_rows == 0) {
        @$conn->query("ALTER TABLE `$table` ADD INDEX `$indexName` (`$column`)");
    }
}
createPaymentIndexIfNotExists($conn, 'project_info', 'idx_creation_date', 'creation_date');
createPaymentIndexIfNotExists($conn, 'checklist_information', 'idx_project_no', 'project_no');
createPaymentIndexIfNotExists($conn, 'reports', 'idx_project_no', 'project_no');

// Filter Parameters
$filter_year            = trim($_POST['filter_year'] ?? '2026');
$filter_payment_mode    = trim($_POST['filter_payment_mode'] ?? '');
$filter_client          = trim($_POST['filter_client'] ?? '');
$filter_inspector       = trim($_POST['filter_inspector'] ?? '');
$filter_equip_id        = trim($_POST['filter_equip_id'] ?? '');
$filter_location        = trim($_POST['filter_location'] ?? '');
$filter_sticker_no      = trim($_POST['filter_sticker_no'] ?? '');
$filter_equip_type      = trim($_POST['filter_equip_type'] ?? '');
$filter_inspection_type = trim($_POST['filter_inspection_type'] ?? '');
$filter_date_from       = trim($_POST['filter_date_from'] ?? '');
$filter_date_to         = trim($_POST['filter_date_to'] ?? '');

// DataTables column index mapping
$cols = [
    0  => 'pi.project_no',
    1  => 'pi.project_no',
    2  => 'pi.project_no',
    3  => 'pi.payment_mode',
    4  => 'pi.creation_date',
    5  => 'pi.equipment_id',
    6  => 'pi.equipment_location',
    7  => 'pi.customer_name',
    8  => 'pi.inspector_name',
    9  => 'pi.project_no',
    10 => 'pi.equipment_type',
    11 => 'pi.inspection_type'
];

$orderBy  = 'pi.creation_date';
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

// Year Filter (Default: 2026)
if ($filter_year !== '' && $filter_year !== 'all') {
    $whereConditions[] = "pi.creation_date >= ? AND pi.creation_date <= ?";
    $params[] = "$filter_year-01-01 00:00:00";
    $params[] = "$filter_year-12-31 23:59:59";
    $types .= "ss";
}

// Inspector role restriction
if ($user_role === 'inspector') {
    $whereConditions[] = "pi.inspector_name = ?";
    $params[] = $user_name;
    $types .= "s";

    $whereConditions[] = "LOWER(pi.payment_mode) = 'cash'";
    $whereConditions[] = "pl.project_no IS NULL";
}

// Global Search
if ($search !== '') {
    $like = "%$search%";
    $whereConditions[] = "(pi.project_no LIKE ? OR pi.customer_name LIKE ? OR pi.inspector_name LIKE ? OR pi.payment_mode LIKE ? OR pi.equipment_id LIKE ? OR pi.equipment_location LIKE ? OR pi.inspection_type LIKE ? OR pi.equipment_type LIKE ? OR EXISTS (SELECT 1 FROM checklist_information ci WHERE ci.project_no = pi.project_no AND ci.sticker_no LIKE ?))";
    for ($i = 0; $i < 9; $i++) {
        $params[] = $like;
        $types .= "s";
    }
}

if ($filter_payment_mode !== '' && $filter_payment_mode !== 'all') {
    $whereConditions[] = "pi.payment_mode = ?";
    $params[] = $filter_payment_mode;
    $types .= "s";
}

if ($filter_client !== '' && $filter_client !== 'all') {
    $whereConditions[] = "pi.customer_name = ?";
    $params[] = $filter_client;
    $types .= "s";
}

if ($filter_inspector !== '' && $filter_inspector !== 'all') {
    $whereConditions[] = "pi.inspector_name = ?";
    $params[] = $filter_inspector;
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
    $whereConditions[] = "EXISTS (SELECT 1 FROM checklist_information ci WHERE ci.project_no = pi.project_no AND ci.sticker_no LIKE ?)";
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
    $whereConditions[] = "DATE(pi.creation_date) >= ?";
    $params[] = $filter_date_from;
    $types .= "s";
}

if ($filter_date_to !== '') {
    $whereConditions[] = "DATE(pi.creation_date) <= ?";
    $params[] = $filter_date_to;
    $types .= "s";
}

$where = "";
if (count($whereConditions) > 0) {
    $where = "WHERE " . implode(" AND ", $whereConditions);
}

// Escaped user_name for direct SQL inclusion in total count
$user_name_sql = $conn->real_escape_string($user_name);

$totalWhereConds = [];
if ($user_role === 'inspector') {
    $totalWhereConds[] = "pi.inspector_name = '$user_name_sql'";
    $totalWhereConds[] = "LOWER(pi.payment_mode) = 'cash'";
    $totalWhereConds[] = "pl.project_no IS NULL";
}
if ($filter_year !== '' && $filter_year !== 'all') {
    $fy_esc = $conn->real_escape_string($filter_year);
    $totalWhereConds[] = "pi.creation_date >= '$fy_esc-01-01 00:00:00' AND pi.creation_date <= '$fy_esc-12-31 23:59:59'";
}

$totalBaseJoin = ($user_role === 'inspector') ? "LEFT JOIN payment_list pl ON pi.project_no = pl.project_no" : "";
$totalBaseWhereStr = (count($totalWhereConds) > 0) ? "WHERE " . implode(" AND ", $totalWhereConds) : "";

$total = 0;
$totalRes = $conn->query("SELECT COUNT(*) AS cnt FROM project_info pi $totalBaseJoin $totalBaseWhereStr");
if ($totalRes && $row = $totalRes->fetch_assoc()) {
    $total = intval($row['cnt']);
}

$filtered = 0;
$creditCnt = 0;
$cashCnt   = 0;

$sqlFiltered = "SELECT 
                    COUNT(*) AS cnt,
                    SUM(CASE WHEN LOWER(IFNULL(pi.payment_mode, 'credit')) = 'credit' THEN 1 ELSE 0 END) AS credit_cnt,
                    SUM(CASE WHEN LOWER(pi.payment_mode) = 'cash' THEN 1 ELSE 0 END) AS cash_cnt
                FROM project_info pi 
                LEFT JOIN payment_list pl ON pi.project_no = pl.project_no 
                $where";

$stmt = $conn->prepare($sqlFiltered);
if ($stmt) {
    if (!empty($types)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $filteredRes = $stmt->get_result();
    if ($filteredRes && $row = $filteredRes->fetch_assoc()) {
        $filtered  = intval($row['cnt']);
        $creditCnt = intval($row['credit_cnt'] ?? 0);
        $cashCnt   = intval($row['cash_cnt'] ?? 0);
    }
    $stmt->close();
}

$sqlData = "SELECT pi.project_no, pi.customer_name, pi.inspector_name, pi.payment_mode, pi.creation_date, pi.project_status, pi.equipment_type, pi.equipment_id, pi.equipment_location, pi.inspection_type,
                   (SELECT sticker_no FROM checklist_information WHERE project_no = pi.project_no ORDER BY id DESC LIMIT 1) AS sticker_no,
                   pl.id AS payment_list_id, pl.no_of_equipment, pl.total_amount, pl.travel_charge, pl.broucher_no, pl.remarks, pl.other_inspector_name,
                   (SELECT no_of_equipments_inspected FROM reports WHERE project_no = pi.project_no ORDER BY id DESC LIMIT 1) AS report_equip_count
            FROM project_info pi
            LEFT JOIN payment_list pl ON pi.project_no = pl.project_no
            $where
            ORDER BY $orderBy $orderDir
            LIMIT ?, ?";

$data = [];
$stmt = $conn->prepare($sqlData);
if ($stmt) {
    $typesWithLimit = $types . "ii";
    $paramsWithLimit = array_merge($params, [$start, $length]);
    $stmt->bind_param($typesWithLimit, ...$paramsWithLimit);
    $stmt->execute();
    $res = $stmt->get_result();

    $sn = $start + 1;
    while ($r = $res->fetch_assoc()) {
        $pm = !empty($r['payment_mode']) ? $r['payment_mode'] : 'Credit';
        $projectNoEsc = htmlspecialchars($r['project_no']);
        
        if ($user_role === 'admin') {
            $selectedCredit = (strtolower($pm) === 'credit') ? 'selected' : '';
            $selectedCash   = (strtolower($pm) === 'cash') ? 'selected' : '';
            $payment_mode_display = "
                <select class='form-control form-control-sm quick-payment-select' data-project='{$projectNoEsc}' style='font-weight:600; font-size:12px; padding:3px 8px; border-radius:6px; border:1px solid #cbd5e1; background:#ffffff; color:#1e293b; width:auto; cursor:pointer;'>
                    <option value='Credit' {$selectedCredit}>Credit</option>
                    <option value='Cash' {$selectedCash}>Cash</option>
                </select>
            ";
        } else {
            $badgeStyle = (strtolower($pm) === 'cash') 
                ? 'background-color:#ecfdf5; color:#047857; border:1px solid #a7f3d0;' 
                : 'background-color:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe;';
            $payment_mode_display = "<span style='{$badgeStyle} padding:4px 12px; border-radius:12px; font-weight:700; font-size:12px;'>" . htmlspecialchars($pm) . "</span>";
        }

        $hasSavedPayment = !empty($r['payment_list_id']);
        $reportEquipCount = !empty($r['report_equip_count']) ? $r['report_equip_count'] : '';

        if (!empty($r['no_of_equipment']) && intval($r['no_of_equipment']) > 0) {
            $noOfEquip = htmlspecialchars($r['no_of_equipment']);
        } elseif (!empty($reportEquipCount)) {
            $noOfEquip = htmlspecialchars($reportEquipCount);
        } else {
            $noOfEquip = '1';
        }
        $totalAmt  = htmlspecialchars($r['total_amount'] ?? '0.00');
        $travelChg = htmlspecialchars($r['travel_charge'] ?? '0.00');
        $broucher  = htmlspecialchars($r['broucher_no'] ?? '');
        $remarks   = htmlspecialchars($r['remarks'] ?? 'Self');
        $otherInsp = htmlspecialchars($r['other_inspector_name'] ?? '');

        $paymentFormBtn = "
            <button type='button' class='btn btn-sm " . ($hasSavedPayment ? "btn-success" : "btn-primary") . " btn-open-payment-form'
                data-project='{$projectNoEsc}'
                data-equip='{$noOfEquip}'
                data-total='{$totalAmt}'
                data-travel='{$travelChg}'
                data-broucher='{$broucher}'
                data-remarks='{$remarks}'
                data-otherinsp='{$otherInsp}'
                style='padding:4px 12px; font-size:12px; border-radius:6px; font-weight:700;'>
                <i class='icofont-ui-edit'></i> " . ($hasSavedPayment ? "Saved (Edit)" : "Fill Details") . "
            </button>
        ";

        $actions = "<div style='display:flex; gap:6px; align-items:center;'>";
        $actions .= $paymentFormBtn;
        if ($user_role === 'admin') {
            $actions .= "
                <button class='btn btn-sm btn-outline-secondary btn-edit-mode' data-project='{$projectNoEsc}' data-mode='" . htmlspecialchars($pm) . "' title='Edit Payment Mode' style='padding:4px 8px; font-size:12px; border-radius:6px;'>
                    <i class='fa fa-edit'></i>
                </button>
            ";
        }
        $actions .= "</div>";

        $savedBadge = $hasSavedPayment 
            ? "<span class='badge-details-saved' title='Payment details saved in payment_list' style='background-color:#dcfce7; color:#15803d; border:1px solid #86efac; padding:2px 8px; border-radius:10px; font-weight:700; font-size:11px; margin-left:6px; display:inline-flex; align-items:center; gap:3px;'><i class='icofont-check-circled'></i> Details Filled</span>" 
            : "";

        $project_link = "<a href='../job/job-details.php?id=" . urlencode($r['project_no']) . "' style='color:#2563eb; font-weight:700; text-decoration:none;'>" . $projectNoEsc . "</a>" . $savedBadge;

        $data[] = [
            "sn"                 => $sn++,
            "project_no"         => $project_link,
            "equipment_id"       => htmlspecialchars($r['equipment_id'] ?? '-'),
            "sticker_no"         => htmlspecialchars($r['sticker_no'] ?? '-'),
            "customer_name"      => htmlspecialchars($r['customer_name'] ?? '-'),
            "inspector_name"     => htmlspecialchars($r['inspector_name'] ?? '-'),
            "equipment_location" => htmlspecialchars($r['equipment_location'] ?? '-'),
            "equipment_type"     => htmlspecialchars($r['equipment_type'] ?? '-'),
            "inspection_type"    => htmlspecialchars($r['inspection_type'] ?? '-'),
            "payment_mode"       => $payment_mode_display,
            "creation_date"      => htmlspecialchars(date('d M Y', strtotime($r['creation_date']))),
            "actions"            => $actions,
            "is_saved"           => $hasSavedPayment
        ];
    }
    $stmt->close();
}

echo json_encode([
    "draw"            => $draw,
    "recordsTotal"    => $total,
    "recordsFiltered" => $filtered,
    "stats"           => [
        "total"  => number_format($filtered),
        "credit" => number_format($creditCnt),
        "cash"   => number_format($cashCnt)
    ],
    "data"            => $data
]);
