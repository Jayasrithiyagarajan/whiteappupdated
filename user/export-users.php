<?php
include('../file/config.php');

/* -------- Filters -------- */
$search = trim($_GET['search'] ?? '');
$role   = trim($_GET['role'] ?? '');

$where = "WHERE 1";

if ($search !== '') {
    $search = mysqli_real_escape_string($conn, $search);
    $where .= " AND (username LIKE '%$search%' 
                 OR email LIKE '%$search%' 
                 OR mobile LIKE '%$search%')";
}

if ($role !== '') {
    $role = mysqli_real_escape_string($conn, $role);
    $where .= " AND role = '$role'";
}

/* -------- Export Headers -------- */
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="users_list.csv"');

$output = fopen('php://output', 'w');

/* -------- CSV Header -------- */
fputcsv($output, ['User ID', 'Username', 'Email', 'Mobile', 'Role', 'Status']);

/* -------- Data -------- */
$sql = "SELECT user_id, username, email, mobile, role
        FROM new_users
        $where
        ORDER BY user_id DESC";

$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    fputcsv($output, [
        $row['user_id'],
        $row['username'],
        $row['email'],
        $row['mobile'],
        ucfirst($row['role']),
        'Active'
    ]);
}

fclose($output);
exit;
