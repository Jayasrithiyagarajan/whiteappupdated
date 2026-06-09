<?php
ini_set('display_errors', 0);
error_reporting(0);

include '../file/config.php';
$conn->set_charset("utf8mb4");

$draw   = intval($_POST['draw'] ?? 0);
$start  = intval($_POST['start'] ?? 0);
$length = intval($_POST['length'] ?? 25);
$search = trim($_POST['search']['value'] ?? '');

// New Filters
$filter_name = trim($_POST['filter_name'] ?? '');
$filter_city = trim($_POST['filter_city'] ?? '');
$filter_rep  = trim($_POST['filter_rep'] ?? '');
$filter_date = $_POST['filter_date'] ?? '';

// Column mapping for ordering
$cols = [
    1 => 'cus_id',
    2 => 'customer_name',
    3 => 'email',
    4 => 'mobile',
    5 => 'city',
    6 => 'address',
    7 => 'date_of_adding',
    8 => 'rep_name',
    9 => 'reference_by',
    10 => 'notes'
];

$orderBy = 'cus_id';
$orderDir = 'DESC';

if (isset($_POST['order'][0])) {
    $i = intval($_POST['order'][0]['column']);
    if (isset($cols[$i])) {
        $orderBy = $cols[$i];
    }
    $orderDir = $_POST['order'][0]['dir'] === 'asc' ? 'ASC' : 'DESC';
}

/* ----- WHERE CLAUSE & PARAMETERS ----- */
$whereConditions = [];
$params = [];
$types = "";

// Global Search
if ($search !== '') {
    $like = "%$search%";
    $whereConditions[] = "(cus_id LIKE ? OR customer_name LIKE ? OR email LIKE ? 
                         OR mobile LIKE ? OR city LIKE ? OR rep_name LIKE ?)";
    for ($i = 0; $i < 6; $i++) {
        $params[] = $like;
        $types .= "s";
    }
}

// Filter: Customer Name
if ($filter_name !== '') {
    $whereConditions[] = "customer_name LIKE ?";
    $params[] = "%$filter_name%";
    $types .= "s";
}

// Filter: City
if ($filter_city !== '') {
    $whereConditions[] = "city LIKE ?";
    $params[] = "%$filter_city%";
    $types .= "s";
}

// Filter: Representative
if ($filter_rep !== '') {
    $whereConditions[] = "rep_name LIKE ?";
    $params[] = "%$filter_rep%";
    $types .= "s";
}

// Filter: Date Added
if ($filter_date !== '') {
    $whereConditions[] = "DATE(date_of_adding) = ?";
    $params[] = $filter_date;
    $types .= "s";
}

// Combine all conditions
$where = "";
if (count($whereConditions) > 0) {
    $where = "WHERE " . implode(" AND ", $whereConditions);
}

/* ----- TOTAL RECORDS ----- */
$total = $conn->query("SELECT COUNT(*) c FROM customers")->fetch_assoc()['c'];

/* ----- FILTERED RECORDS ----- */
$sqlFiltered = "SELECT COUNT(*) c FROM customers $where";
$stmt = $conn->prepare($sqlFiltered);
if ($types) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$filtered = $stmt->get_result()->fetch_assoc()['c'];
$stmt->close();

/* ----- FETCH DATA ----- */
$sql = "SELECT cus_id, customer_name, email, mobile, city, address, 
               date_of_adding, rep_name, reference_by, notes 
        FROM customers 
        $where 
        ORDER BY $orderBy $orderDir 
        LIMIT ?, ?";

$stmt = $conn->prepare($sql);

// Add pagination parameters
$types .= "ii";
$params[] = $start;
$params[] = $length;

$stmt->bind_param($types, ...$params);
$stmt->execute();
$res = $stmt->get_result();

$data = [];
while ($r = $res->fetch_assoc()) {
    $initial = strtoupper(mb_substr(trim($r['customer_name'] ?? 'C'), 0, 1));
    $initialClass = "initial-" . $initial;

    $actions = "
        <div class='action-icons'>
            <a href='view-customer.php?cusid={$r['cus_id']}' title='View'><i class='fa fa-eye'></i></a>
            <a href='../profile/edit-profile.php?cusid={$r['cus_id']}' title='Edit'><i class='fa fa-edit'></i></a>
            <button onclick='openResetModal(\"{$r['cus_id']}\", \"" . addslashes($r['customer_name']) . "\")' title='Reset Password'><i class='fa fa-key'></i></button>
            <a href='delete_customer.php?cusid={$r['cus_id']}' onclick='return confirm(\"Delete this customer?\")' title='Delete'><i class='fa fa-trash'></i></a>
        </div>
    ";

    $data[] = [
        "checkbox" => "<input type='checkbox'>",
        "cus_id" => "<a href='view-customer.php?cusid={$r['cus_id']}' style='color:#4f46e5; font-weight:600; text-decoration:none;'>{$r['cus_id']}</a>",
        "customer_name" => "
            <div style='display:flex; gap:10px; align-items:center;'>
                <div class='avatar-circle $initialClass'>$initial</div>
                <div>{$r['customer_name']}</div>
            </div>",
        "email" => $r['email'],
        "mobile" => $r['mobile'],
        "city" => $r['city'],
        "address" => $r['address'],
        "date_of_adding" => $r['date_of_adding'] ? date('M d, Y', strtotime($r['date_of_adding'])) : 'N/A',
        "rep_name" => $r['rep_name'],
        "reference_by" => $r['reference_by'],
        "notes" => $r['notes'],
        "actions" => $actions
    ];
}

echo json_encode([
    "draw" => $draw,
    "recordsTotal" => $total,
    "recordsFiltered" => $filtered,
    "data" => $data
]);
?>