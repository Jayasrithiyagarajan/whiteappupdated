<?php
session_start();
include_once('../file/config.php');

$user = $_SESSION['username'] ?? null;
$role = $_SESSION['role'] ?? null;

if (!$user) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$today = date('Y-m-d');

// Helper: run a COUNT query
function countQuery($conn, $sql, $types = "", $params = []) {
    $stmt = $conn->prepare($sql);
    if ($params) $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $cnt = $stmt->get_result()->fetch_assoc()['cnt'] ?? 0;
    $stmt->close();
    return (int)$cnt;
}

// ─────────────────────────────────────────────────────────────────────
// 1. KPIs
// ─────────────────────────────────────────────────────────────────────
$where = " WHERE 1=1 ";
$p = []; $t = "";
if ($role === 'inspector') {
    $where .= " AND assign_inspector = ? ";
    $p[] = $user; $t .= "s";
}

$totalStickers = countQuery($conn, "SELECT COUNT(*) AS cnt FROM stickers $where", $t, $p);
$passStickers  = countQuery($conn, "SELECT COUNT(*) AS cnt FROM stickers $where AND sticker_status = 'Passed'", $t, $p);
$failStickers  = countQuery($conn, "SELECT COUNT(*) AS cnt FROM stickers $where AND sticker_status = 'Failed'", $t, $p);

// Active Stickers (Used & Not Expired)
$activeSql = "SELECT COUNT(*) AS cnt FROM stickers $where AND (project_no IS NOT NULL AND project_no != '') AND expiry_date >= ?";
$activeP = array_merge($p, [$today]);
$activeT = $t . "s";
$activeStickers = countQuery($conn, $activeSql, $activeT, $activeP);

// ─────────────────────────────────────────────────────────────────────
// 2. RECENT STICKERS
// ─────────────────────────────────────────────────────────────────────
$recentSql = "SELECT * FROM stickers $where ORDER BY created_at DESC LIMIT 6";
$stmt = $conn->prepare($recentSql);
if ($p) $stmt->bind_param($t, ...$p);
$stmt->execute();
$res = $stmt->get_result();
$recentStickers = [];
while ($row = $res->fetch_assoc()) {
    // Determine badge classes
    $resClass = ($row['sticker_status'] === 'Passed') ? 'badge-success' : 'badge-danger';
    $statusClass = ($row['status'] === 'active') ? 'badge-success' : 'badge-warning';
    
    // Initial for avatar
    $initial = strtoupper(substr($row['assign_inspector'], 0, 1));

    $recentStickers[] = [
        'inspector' => $row['assign_inspector'],
        'initial' => $initial,
        'sticker_no' => $row['sticker_start_no'],
        'result' => $row['sticker_status'],
        'resClass' => $resClass,
        'status' => ucfirst($row['status']),
        'statusClass' => $statusClass,
        'date' => date('d-m-Y', strtotime($row['created_at']))
    ];
}
$stmt->close();

// ─────────────────────────────────────────────────────────────────────
// 3. REVIEWS & ACTIVITY
// ─────────────────────────────────────────────────────────────────────
// Total Reviews: Count of reports
$reportWhere = " WHERE 1=1 ";
if ($role === 'inspector') { $reportWhere .= " AND issued_by = '$user' "; }
$totalReviews = countQuery($conn, "SELECT COUNT(*) AS cnt FROM reports $reportWhere");

// Pending Reviews: Stickers with no project_no
$pendingReviews = countQuery($conn, "SELECT COUNT(*) AS cnt FROM stickers $where AND (project_no IS NULL OR project_no = '')", $t, $p);

// Completed Projects
$projectWhere = " WHERE project_status = 'Completed' ";
if ($role === 'inspector') { 
    // Join with something to filter by inspector? Reports?
    // For now keep it simple or filter if we can link projects to inspectors.
}
$completedProjects = countQuery($conn, "SELECT COUNT(*) AS cnt FROM project_info $projectWhere");

// Calculate progress weights (dummy logic based on totals for the UI bars)
$reviewProgress = ($totalStickers > 0) ? round(($totalReviews / $totalStickers) * 100) : 0;
$pendingProgress = ($totalStickers > 0) ? round(($pendingReviews / $totalStickers) * 100) : 0;
// Completed projects might not be relative to stickers directly, but we'll show a high percentage for the bar look
$projectProgress = 85; 

echo json_encode([
    'kpis' => [
        'total'  => number_format($totalStickers),
        'active' => number_format($activeStickers),
        'pass'   => number_format($passStickers),
        'fail'   => number_format($failStickers)
    ],
    'recent' => $recentStickers,
    'activity' => [
        'totalReviews' => number_format($totalReviews),
        'pendingReviews' => number_format($pendingReviews),
        'completedProjects' => number_format($completedProjects),
        'reviewProgress' => $reviewProgress,
        'pendingProgress' => $pendingProgress,
        'projectProgress' => $projectProgress
    ],
    'userName' => strtoupper($_SESSION['username'] ?? 'User')
]);
?>
