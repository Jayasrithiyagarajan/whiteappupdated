<?php
session_start();
include_once('../file/config.php');

$user = $_SESSION['username'];
$role = $_SESSION['role'];

// DataTables parameters
$draw   = intval($_POST['draw'] ?? 0);
$start  = intval($_POST['start'] ?? 0);
$length = intval($_POST['length'] ?? 10);
$search = $_POST['search']['value'] ?? '';

// New Filters
$filter_inspector = $_POST['filter_inspector'] ?? '';
$filter_date      = $_POST['filter_date'] ?? '';
$filter_project   = $_POST['filter_project'] ?? '';
$filter_expiry    = $_POST['filter_expiry'] ?? '';
$filter_status    = $_POST['filter_status'] ?? '';

// Columns mapping
$columns = [
    0 => "sticker_start_no",
    1 => "project_no",
    2 => "assign_inspector",
    3 => "created_at",
    4 => "inspection_date",
    5 => "expiry_date",
    6 => "sticker_status",
    7 => "status",
    8 => "sticker_start_no"
];

// Base Query
$where = " WHERE 1=1 ";
$params = [];
$types = "";

// Role Restriction
if ($role === 'inspector') {
    $where .= " AND assign_inspector = ? ";
    $params[] = $user;
    $types .= "s";
}

// Apply Filters
if (!empty($filter_inspector)) {
    $where .= " AND assign_inspector = ? ";
    $params[] = $filter_inspector;
    $types .= "s";
}

if (!empty($filter_date)) {
    $where .= " AND DATE(created_at) = ? ";
    $params[] = $filter_date;
    $types .= "s";
}

if (!empty($filter_project)) {
    $where .= " AND project_no LIKE ? ";
    $params[] = "%$filter_project%";
    $types .= "s";
}

if (!empty($filter_expiry)) {
    $where .= " AND (
        SELECT next_inspection_due_date 
        FROM reports 
        WHERE sticker_number_issued = stickers.sticker_start_no 
        ORDER BY next_inspection_due_date DESC LIMIT 1
    ) = ? ";
    $params[] = $filter_expiry;
    $types .= "s";
}

if (!empty($filter_status)) {
    if ($filter_status === 'Unused') {
        $where .= " AND (project_no IS NULL OR project_no = '') ";
    } elseif ($filter_status === 'Passed') {
        $where .= " AND sticker_status = 'Passed' AND (project_no IS NOT NULL AND project_no != '') ";
    } elseif ($filter_status === 'Failed') {
        $where .= " AND sticker_status = 'Failed' AND (project_no IS NOT NULL AND project_no != '') ";
    }
}

// Global Search
if (!empty($search)) {
    $searchWildcard = "%{$search}%";
    $where .= " AND (
        sticker_start_no LIKE ? OR
        project_no LIKE ? OR
        assign_inspector LIKE ? OR
        sticker_status LIKE ? OR
        status LIKE ? OR
        inspection_date LIKE ? 
    ) ";
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $types .= "ssssss";
}

// Total records
$sqlTotal = "SELECT COUNT(*) as total FROM stickers" . ($role === 'inspector' ? " WHERE assign_inspector = ?" : "");
$stmtTotal = $conn->prepare($sqlTotal);
if ($role === 'inspector') {
    $stmtTotal->bind_param("s", $user);
}
$stmtTotal->execute();
$recordsTotal = $stmtTotal->get_result()->fetch_assoc()['total'] ?? 0;
$stmtTotal->close();

// Filtered records
$sqlFiltered = "SELECT COUNT(*) as total FROM stickers $where";
$stmtFiltered = $conn->prepare($sqlFiltered);
if ($params) $stmtFiltered->bind_param($types, ...$params);
$stmtFiltered->execute();
$recordsFiltered = $stmtFiltered->get_result()->fetch_assoc()['total'] ?? 0;
$stmtFiltered->close();

// Ordering
$orderColumnIndex = $_POST['order'][0]['column'] ?? 3;
$orderColumn = $columns[$orderColumnIndex] ?? "created_at";
$orderDir = $_POST['order'][0]['dir'] ?? 'desc';
$orderBy = " ORDER BY $orderColumn $orderDir ";

// Fetch Data
$sql = "SELECT *, 
        (SELECT next_inspection_due_date FROM reports 
         WHERE sticker_number_issued = stickers.sticker_start_no 
         ORDER BY next_inspection_due_date DESC LIMIT 1) as next_due 
        FROM stickers $where $orderBy LIMIT ?, ?";

$params[] = $start;
$params[] = $length;
$types .= "ii";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

$data = [];

while ($row = $result->fetch_assoc()) {
    $expiry_date = $row['next_due'] ?: date("Y-m-d", strtotime($row['inspection_date'] . " +3 months"));
    
    $current_date = date("Y-m-d");
    
    // ==================== STATUS COLUMN (Active / Expired / Inactive) ====================
    if (empty($row['project_no'])) {
        $status_class = 'oj-pill oj-pill--slate';      // Grey for Inactive
        $status_text = 'Inactive';
    } else if ($current_date > $expiry_date) {
        $status_class = 'oj-pill oj-pill--red';        // Red for Expired
        $status_text = 'Expired';
    } else {
        $status_class = 'oj-pill oj-pill--teal';       // Green/Teal for Active
        $status_text = 'Active';
    }
    
    // ==================== STICKER STATUS COLUMN (Passed / Failed / Pending) ====================
    if ($row['sticker_status'] === "Passed") {
        $sticker_status_class = 'oj-pill oj-pill--teal';   // Green for Passed
        $sticker_status_text = 'Passed';
    } elseif ($row['sticker_status'] === "Failed") {
        $sticker_status_class = 'oj-pill oj-pill--red';    // Red for Failed
        $sticker_status_text = 'Failed';
    } else {
        $sticker_status_class = 'oj-pill oj-pill--slate';  // Grey for Pending
        $sticker_status_text = 'Pending';
    }

    // Action Buttons
    $downloadBtn = '';
    if ($row['sticker_status'] === "Passed") {
        $downloadBtn = "<a href='download-white.php?sticker_start_no={$row['sticker_start_no']}' target='_blank' class='text-primary ml-1' title='Download'><i class='icofont-download'></i></a>";
    } elseif ($row['sticker_status'] === "Failed") {
        $downloadBtn = "<a href='download.php?sticker_start_no={$row['sticker_start_no']}' target='_blank' class='text-primary ml-1' title='Download'><i class='icofont-download'></i></a>";
    }
    
    $actions = "<div class='d-flex align-items-center justify-content-center'>$downloadBtn 
                <a href='delete-sticker.php?id={$row['sticker_start_no']}' onclick='return confirm(\"Delete this sticker?\");' class='text-danger ml-2'><i class='icofont-trash'></i></a></div>";

    $data[] = [
        "#" . $row['sticker_start_no'],
        $row['project_no'] ?: '-',
        $row['assign_inspector'],
        date("d/m/Y", strtotime($row['created_at'])),
        (!empty($row['project_no']) && $row['inspection_date']) ? date("d/m/Y", strtotime($row['inspection_date'])) : '-',
        (!empty($row['project_no']) && $expiry_date) ? date("d/m/Y", strtotime($expiry_date)) : '-',
        "<span class='$sticker_status_class'>$sticker_status_text</span>",
        "<span class='$status_class'>$status_text</span>",
        $actions
    ];
}

echo json_encode([
    "draw" => $draw,
    "recordsTotal" => $recordsTotal,
    "recordsFiltered" => $recordsFiltered,
    "data" => $data
]);
?>