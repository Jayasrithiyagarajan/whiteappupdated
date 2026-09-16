<?php
session_start();
include_once('../file/config.php');

$user_role = $_SESSION['role'] ?? '';
$user_name = $_SESSION['username'] ?? '';

if (!in_array($user_role, ['admin', 'inspector'])) {
    die("Unauthorized access");
}

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=payment_list_' . date('Y-m-d') . '.csv');

$output = fopen('php://output', 'w');

// CSV Headers
fputcsv($output, [
    'S.No', 'Project ID', 'Payment Mode', 'Creation Date', 'Equipment ID', 'Location', 
    'Client Name', 'Inspector Name', 'Sticker No', 'Equipment Type', 'Inspection Type'
]);

$search                 = trim($_GET['search'] ?? '');
$filter_year            = trim($_GET['filter_year'] ?? '2026');
$filter_payment_mode    = trim($_GET['filter_payment_mode'] ?? '');
$filter_client          = trim($_GET['filter_client'] ?? '');
$filter_inspector       = trim($_GET['filter_inspector'] ?? '');
$filter_equip_id        = trim($_GET['filter_equip_id'] ?? '');
$filter_location        = trim($_GET['filter_location'] ?? '');
$filter_sticker_no      = trim($_GET['filter_sticker_no'] ?? '');
$filter_equip_type      = trim($_GET['filter_equip_type'] ?? '');
$filter_inspection_type = trim($_GET['filter_inspection_type'] ?? '');
$filter_date_from       = trim($_GET['filter_date_from'] ?? '');
$filter_date_to         = trim($_GET['filter_date_to'] ?? '');

$whereConditions = [];
$params = [];
$types  = "";

if ($filter_year !== '' && $filter_year !== 'all') {
    $whereConditions[] = "pi.creation_date >= ? AND pi.creation_date <= ?";
    $params[] = "$filter_year-01-01 00:00:00";
    $params[] = "$filter_year-12-31 23:59:59";
    $types .= "ss";
}

if ($user_role === 'inspector') {
    $whereConditions[] = "pi.inspector_name = ?";
    $params[] = $user_name;
    $types .= "s";

    $whereConditions[] = "LOWER(pi.payment_mode) = 'cash'";
    $whereConditions[] = "pl.project_no IS NULL";
}

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

$sql = "SELECT pi.project_no, pi.customer_name, pi.inspector_name, pi.payment_mode, pi.creation_date, pi.equipment_id, pi.equipment_location, pi.equipment_type, pi.inspection_type,
               (SELECT sticker_no FROM checklist_information WHERE project_no = pi.project_no ORDER BY id DESC LIMIT 1) AS sticker_no 
        FROM project_info pi
        LEFT JOIN payment_list pl ON pi.project_no = pl.project_no
        $where 
        ORDER BY pi.creation_date DESC";

$stmt = $conn->prepare($sql);
if ($stmt) {
    if (!empty($types)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    $sn = 1;
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            fputcsv($output, [
                $sn++,
                $row['project_no'],
                $row['payment_mode'] ? $row['payment_mode'] : 'Not Specified',
                date('d M Y', strtotime($row['creation_date'])),
                $row['equipment_id'] ?? '-',
                $row['equipment_location'] ?? '-',
                $row['customer_name'] ?? '-',
                $row['inspector_name'] ?? '-',
                $row['sticker_no'] ?? '-',
                $row['equipment_type'] ?? '-',
                $row['inspection_type'] ?? '-'
            ]);
        }
    }
    $stmt->close();
}

fclose($output);
exit;
