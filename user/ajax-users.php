<?php
include('../file/config.php');
header('Content-Type: application/json');

$page  = max(1, (int)($_POST['page'] ?? 1));
$limit = (int)($_POST['limit'] ?? 10);
$allowedLimits = [10, 25, 50];
if (!in_array($limit, $allowedLimits)) $limit = 10;

$search = trim($_POST['search'] ?? '');
$role   = trim($_POST['role'] ?? '');

$offset = ($page - 1) * $limit;
$where = "WHERE 1";

if ($search !== '') {
    $s = mysqli_real_escape_string($conn, $search);
    $where .= " AND (username LIKE '%$s%' OR email LIKE '%$s%' OR mobile LIKE '%$s%')";
}

if ($role !== '') {
    $r = mysqli_real_escape_string($conn, $role);
    $where .= " AND role = '$r'";
}

$total = $conn->query("SELECT COUNT(*) total FROM new_users $where")
              ->fetch_assoc()['total'];

/* Role counts (always over all users, no filter) */
$roleCounts = [
    'total'              => 0,
    'admin'              => 0,
    'inspector'          => 0,
    'reviewer'           => 0,
    'quality controller' => 0,
    'document controller'=> 0,
];
$rcRes = $conn->query("SELECT role, COUNT(*) cnt FROM new_users GROUP BY role");
while ($rc = $rcRes->fetch_assoc()) {
    $r = strtolower($rc['role']);
    if (isset($roleCounts[$r])) $roleCounts[$r] = (int)$rc['cnt'];
    $roleCounts['total'] += (int)$rc['cnt'];
}

$sql = "SELECT user_id, username, email, mobile, role
        FROM new_users
        $where
        ORDER BY user_id DESC
        LIMIT $limit OFFSET $offset";

$res = $conn->query($sql);

$rows = '';
$i = $offset + 1;

if ($res && $res->num_rows > 0) {
    while ($u = $res->fetch_assoc()) {
        $rows .= "
<tr>
<td>{$i}</td>
<td>{$u['user_id']}</td>
<td>".htmlspecialchars($u['username'])."</td>
<td>".htmlspecialchars($u['email'])."</td>
<td>".htmlspecialchars($u['mobile'])."</td>
<td>".ucfirst($u['role'])."</td>
<td><span class='badge badge-success'>Active</span></td>
<td>
<button class='btn btn-sm btn-warning changePwdBtn'
 data-id='{$u['user_id']}'
 data-name='".htmlspecialchars($u['username'], ENT_QUOTES)."'>
 Change Password
</button>
</td>
</tr>";
        $i++;
    }
} else {
    $rows = "<tr><td colspan='8' class='text-center'>No users found</td></tr>";
}

$totalPages = ceil($total / $limit);
$pagination = '';

if ($totalPages > 1) {
    if ($page > 1) {
        $pagination .= "<li class='page-item'><a class='page-link' href='#' data-page='".($page - 1)."'>&laquo; Prev</a></li>";
    } else {
        $pagination .= "<li class='page-item disabled'><span class='page-link'>&laquo; Prev</span></li>";
    }

    $startPg = max(1, $page - 2);
    $endPg   = min($totalPages, $page + 2);

    if ($startPg > 1) {
        $pagination .= "<li class='page-item'><a class='page-link' href='#' data-page='1'>1</a></li>";
        if ($startPg > 2) {
            $pagination .= "<li class='page-item disabled'><span class='page-link'>...</span></li>";
        }
    }

    for ($p = $startPg; $p <= $endPg; $p++) {
        $active = ($p == $page) ? 'active' : '';
        $pagination .= "<li class='page-item $active'><a class='page-link' href='#' data-page='$p'>$p</a></li>";
    }

    if ($endPg < $totalPages) {
        if ($endPg < $totalPages - 1) {
            $pagination .= "<li class='page-item disabled'><span class='page-link'>...</span></li>";
        }
        $pagination .= "<li class='page-item'><a class='page-link' href='#' data-page='$totalPages'>$totalPages</a></li>";
    }

    if ($page < $totalPages) {
        $pagination .= "<li class='page-item'><a class='page-link' href='#' data-page='".($page + 1)."'>Next &raquo;</a></li>";
    } else {
        $pagination .= "<li class='page-item disabled'><span class='page-link'>Next &raquo;</span></li>";
    }
}

$start_info = $total > 0 ? $offset + 1 : 0;
$info = "Showing $start_info&ndash;" . min($offset + $limit, $total) . " of $total users";

echo json_encode([
    'rows'       => $rows,
    'pagination' => $pagination,
    'info'       => $info,
    'roleCounts' => $roleCounts,
]);
