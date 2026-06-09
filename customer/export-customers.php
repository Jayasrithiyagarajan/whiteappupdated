<?php
include '../file/config.php';
$conn->set_charset("utf8mb4");

$search = trim($_GET['search_value'] ?? '');

/* ----- SEARCH ----- */
$where = "";
$params = [];
$types = "";
if ($search !== '') {
    $where = "WHERE (
        cus_id LIKE ? OR customer_name LIKE ? OR email LIKE ?
        OR mobile LIKE ? OR city LIKE ? OR rep_name LIKE ?
    )";
    $like = "%$search%";
    $params = [$like, $like, $like, $like, $like, $like];
    $types = "ssssss";
}

$sql = "SELECT cus_id, customer_name, email, mobile, city, address, date_of_adding, rep_name, reference_by, notes 
        FROM customers $where 
        ORDER BY cus_id DESC";

$stmt = $conn->prepare($sql);
if ($types) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$filename = "customers_export_" . date('Y-m-d_H-i-s') . ".csv";

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=' . $filename);

$output = fopen('php://output', 'w');

// BOM for Excel
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// Column headers
fputcsv($output, ['Customer ID', 'Customer Name', 'Email', 'Mobile', 'City', 'Address', 'Date of Adding', 'Rep Name', 'Reference By', 'Notes']);

while ($row = $result->fetch_assoc()) {
    fputcsv($output, [
        $row['cus_id'],
        $row['customer_name'],
        $row['email'],
        $row['mobile'],
        $row['city'],
        $row['address'],
        $row['date_of_adding'],
        $row['rep_name'],
        $row['reference_by'],
        $row['notes']
    ]);
}

fclose($output);
exit;
