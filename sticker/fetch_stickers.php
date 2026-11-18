<?php
session_start();
include_once('../file/config.php');

$user = $_SESSION['username'] ?? '';
$role = $_SESSION['role'] ?? '';

// DataTables parameters
$draw   = intval($_POST['draw'] ?? 0);
$start  = intval($_POST['start'] ?? 0);
$length = intval($_POST['length'] ?? 10);
$search = $_POST['search']['value'] ?? '';

// Filters
$filter_inspector = $_POST['filter_inspector'] ?? '';
$filter_date      = $_POST['filter_date'] ?? '';
$filter_project   = $_POST['filter_project'] ?? '';
$filter_expiry    = $_POST['filter_expiry'] ?? '';
$filter_status    = $_POST['filter_status'] ?? '';

// Columns mapping
$columns = [
    0 => "s.sticker_start_no",
    1 => "s.project_no",
    2 => "equipment_id",
    3 => "equipment_serial_no",
    4 => "s.assign_inspector",
    5 => "s.created_at",
    6 => "s.inspection_date",
    7 => "s.expiry_date",
    8 => "s.sticker_status",
    9 => "s.status",
    10 => "s.sticker_start_no"
];

// Base Query
$where = " WHERE 1=1 ";
$params = [];
$types = "";

// Role Restriction
if ($role === 'inspector') {
    $where .= " AND s.assign_inspector = ? ";
    $params[] = $user;
    $types .= "s";
}

// Apply Filters
if (!empty($filter_inspector)) {
    $where .= " AND s.assign_inspector = ? ";
    $params[] = $filter_inspector;
    $types .= "s";
}

if (!empty($filter_date)) {
    $where .= " AND DATE(s.created_at) = ? ";
    $params[] = $filter_date;
    $types .= "s";
}

if (!empty($filter_project)) {
    $where .= " AND s.project_no LIKE ? ";
    $params[] = "%$filter_project%";
    $types .= "s";
}

if (!empty($filter_expiry)) {
    $where .= " AND (
        COALESCE(
            r.next_inspection_due_date,
            (SELECT next_inspection_due_date FROM reports WHERE sticker_number_issued = s.sticker_start_no ORDER BY next_inspection_due_date DESC LIMIT 1),
            DATE_ADD(s.inspection_date, INTERVAL 3 MONTH)
        ) = ?
    ) ";
    $params[] = $filter_expiry;
    $types .= "s";
}

if (!empty($filter_status)) {
    if ($filter_status === 'Unused') {
        $where .= " AND (s.project_no IS NULL OR s.project_no = '') ";
    } elseif ($filter_status === 'Passed') {
        $where .= " AND s.sticker_status = 'Passed' AND (s.project_no IS NOT NULL AND s.project_no != '') ";
    } elseif ($filter_status === 'Failed') {
        $where .= " AND s.sticker_status = 'Failed' AND (s.project_no IS NOT NULL AND s.project_no != '') ";
    }
}

// Global Search
if (!empty($search)) {
    $searchWildcard = "%{$search}%";
    $where .= " AND (
        s.sticker_start_no LIKE ? OR
        s.project_no LIKE ? OR
        s.assign_inspector LIKE ? OR
        s.sticker_status LIKE ? OR
        s.status LIKE ? OR
        s.inspection_date LIKE ? OR
        s.equipment_no LIKE ? OR
        pi.equipment_id LIKE ? OR
        ci.equipment_no LIKE ? OR
        r.equipment_id_no LIKE ? OR
        s.equipment_serial_no LIKE ? OR
        ci.crane_serial_no LIKE ? OR
        r.equipment_serial_no LIKE ?
    ) ";
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $types .= "sssssssssssss";
}

// Total records
$sqlTotal = "SELECT COUNT(*) as total FROM stickers s" . ($role === 'inspector' ? " WHERE s.assign_inspector = ?" : "");
$stmtTotal = $conn->prepare($sqlTotal);
if ($role === 'inspector') {
    $stmtTotal->bind_param("s", $user);
}
$stmtTotal->execute();
$recordsTotal = $stmtTotal->get_result()->fetch_assoc()['total'] ?? 0;
$stmtTotal->close();

// Filtered records
$sqlFiltered = "SELECT COUNT(*) as total 
                FROM stickers s 
                LEFT JOIN project_info pi ON s.project_no = pi.project_no 
                LEFT JOIN checklist_information ci ON s.project_no = ci.project_no 
                LEFT JOIN reports r ON s.sticker_start_no = r.sticker_number_issued 
                $where";
$stmtFiltered = $conn->prepare($sqlFiltered);
if ($params) $stmtFiltered->bind_param($types, ...$params);
$stmtFiltered->execute();
$recordsFiltered = $stmtFiltered->get_result()->fetch_assoc()['total'] ?? 0;
$stmtFiltered->close();

// Ordering
$orderColumnIndex = $_POST['order'][0]['column'] ?? 0;
$orderColumn = $columns[$orderColumnIndex] ?? "s.sticker_start_no";
$orderDir = (isset($_POST['order'][0]['dir']) && strtolower($_POST['order'][0]['dir']) === 'asc') ? 'ASC' : 'DESC';
$orderBy = " ORDER BY $orderColumn $orderDir ";

// Fetch Data
$sql = "SELECT 
            s.*,
            COALESCE(NULLIF(s.equipment_no, ''), NULLIF(pi.equipment_id, ''), NULLIF(ci.equipment_no, ''), NULLIF(r.equipment_id_no, '')) AS equipment_id,
            COALESCE(NULLIF(s.equipment_serial_no, ''), NULLIF(ci.crane_serial_no, ''), NULLIF(r.equipment_serial_no, '')) AS equipment_serial_no,
            COALESCE(
                r.next_inspection_due_date,
                (SELECT next_inspection_due_date FROM reports WHERE sticker_number_issued = s.sticker_start_no ORDER BY next_inspection_due_date DESC LIMIT 1)
            ) as next_due 
        FROM stickers s 
        LEFT JOIN project_info pi ON s.project_no = pi.project_no 
        LEFT JOIN checklist_information ci ON s.project_no = ci.project_no 
        LEFT JOIN reports r ON s.sticker_start_no = r.sticker_number_issued 
        $where $orderBy LIMIT ?, ?";

$params[] = $start;
$params[] = $length;
$types .= "ii";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

$data = [];

while ($row = $result->fetch_assoc()) {
    $expiry_date = $row['next_due'] ?: (!empty($row['inspection_date']) ? date("Y-m-d", strtotime($row['inspection_date'] . " +3 months")) : null);
    
    $current_date = date("Y-m-d");
    
    // ==================== STATUS COLUMN (Active / Expired / Inactive) ====================
    if (empty($row['project_no'])) {
        $status_class = 'oj-pill oj-pill--slate';      // Grey for Inactive
        $status_text = 'Inactive';
    } else if ($expiry_date && $current_date > $expiry_date) {
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
    
    $deleteBtn = '';
    if (empty($row['project_no'])) {
        $deleteBtn = "<a href='delete-sticker.php?id={$row['sticker_start_no']}' onclick='return confirm(\"Delete this sticker?\");' class='text-danger ml-2'><i class='icofont-trash'></i></a>";
    } else {
        $deleteBtn = "<span class='text-muted ml-2' title='Cannot delete assigned sticker' style='cursor: not-allowed;'><i class='icofont-trash'></i></span>";
    }
    
    $actions = "<div class='d-flex align-items-center justify-content-center'>$downloadBtn $deleteBtn</div>";

    $data[] = [
        "#" . $row['sticker_start_no'],
        $row['project_no'] ?: '-',
        $row['equipment_id'] ?: '-',
        $row['equipment_serial_no'] ?: '-',
        $row['assign_inspector'],
        date("d/m/Y", strtotime($row['created_at'])),
        (!empty($row['project_no']) && !empty($row['inspection_date'])) ? date("d/m/Y", strtotime($row['inspection_date'])) : '-',
        (!empty($row['project_no']) && !empty($expiry_date)) ? date("d/m/Y", strtotime($expiry_date)) : '-',
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
