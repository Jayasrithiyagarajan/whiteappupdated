<?php
session_start();
include_once('../file/config.php');

$user_role = $_SESSION['role'] ?? '';
if ($user_role !== 'admin') {
    die("Unauthorized access");
}

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=inspector_saved_payments_' . date('Y-m-d') . '.csv');

$output = fopen('php://output', 'w');

fputcsv($output, [
    'S.No', 'Project ID', 'Inspection Date', 'Equipment ID', 'Location', 
    'Client Name', 'Inspector Name', 'Total Amount', 'Travel Charge', 
    'Voucher No', 'Remarks', 'Other Inspector Name', 'Sticker No', 
    'Equipment Type', 'Inspection Type', 'No of Equipment'
]);

$search                 = trim($_GET['search'] ?? '');
$filter_year            = trim($_GET['filter_year'] ?? '2026');
$filter_client          = trim($_GET['filter_client'] ?? '');
$filter_inspector       = trim($_GET['filter_inspector'] ?? '');
$filter_other_inspector = trim($_GET['filter_other_inspector'] ?? '');
$filter_remarks         = trim($_GET['filter_remarks'] ?? '');
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
    $whereConditions[] = "YEAR(IFNULL((SELECT ci.inspection_date FROM checklist_information ci WHERE ci.project_no = pl.project_no ORDER BY ci.checklist_id DESC LIMIT 1), DATE(pl.created_at))) = ?";
    $params[] = (int)$filter_year;
    $types .= "i";
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
    $whereConditions[] = "IFNULL((SELECT ci.inspection_date FROM checklist_information ci WHERE ci.project_no = pl.project_no ORDER BY ci.checklist_id DESC LIMIT 1), DATE(pl.created_at)) >= ?";
    $params[] = $filter_date_from;
    $types .= "s";
}

if ($filter_date_to !== '') {
    $whereConditions[] = "IFNULL((SELECT ci.inspection_date FROM checklist_information ci WHERE ci.project_no = pl.project_no ORDER BY ci.checklist_id DESC LIMIT 1), DATE(pl.created_at)) <= ?";
    $params[] = $filter_date_to;
    $types .= "s";
}

$where = "";
if (count($whereConditions) > 0) {
    $where = "WHERE " . implode(" AND ", $whereConditions);
}

$sql = "SELECT pl.*, pi.customer_name, pi.equipment_id, pi.equipment_location, pi.equipment_type, pi.inspection_type,
               (SELECT ci.inspection_date FROM checklist_information ci WHERE ci.project_no = pl.project_no ORDER BY ci.checklist_id DESC LIMIT 1) AS inspection_date,
               (SELECT ci.sticker_no FROM checklist_information ci WHERE ci.project_no = pl.project_no ORDER BY ci.checklist_id DESC LIMIT 1) AS sticker_no 
        FROM payment_list pl 
        LEFT JOIN project_info pi ON pl.project_no = pi.project_no 
        $where
        ORDER BY IFNULL((SELECT ci.inspection_date FROM checklist_information ci WHERE ci.project_no = pl.project_no ORDER BY ci.checklist_id DESC LIMIT 1), DATE(pl.created_at)) DESC, pl.created_at DESC";

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
            $inspDate = !empty($row['inspection_date']) ? date('d-m-Y', strtotime($row['inspection_date'])) : (!empty($row['created_at']) ? date('d-m-Y', strtotime($row['created_at'])) : '-');

            fputcsv($output, [
                $sn++,
                $row['project_no'],
                $inspDate,
                $row['equipment_id'] ?? '-',
                $row['equipment_location'] ?? '-',
                $row['customer_name'] ?? '-',
                $row['inspector_name'],
                number_format($row['total_amount'], 2),
                number_format($row['travel_charge'], 2),
                $row['broucher_no'],
                $row['remarks'],
                $row['other_inspector_name'] ?? '-',
                $row['sticker_no'] ?? '-',
                $row['equipment_type'] ?? '-',
                $row['inspection_type'] ?? '-',
                $row['no_of_equipment']
            ]);
        }
    }
    $stmt->close();
}

fclose($output);
exit;
