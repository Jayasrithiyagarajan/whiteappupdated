<?php
session_start();
include_once('../file/config.php');

$user = $_SESSION['username'];
$role = $_SESSION['role'];

// DataTables parameters
$draw = intval($_POST['draw'] ?? 0);
$start = intval($_POST['start'] ?? 0);
$length = intval($_POST['length'] ?? 10);
$search = $_POST['search']['value'] ?? '';

// Filter parameters
$inspectorFilter = $_POST['filter_inspector'] ?? '';
$clientFilter = $_POST['filter_client'] ?? '';
$dateFrom = $_POST['filter_date_from'] ?? '';
$dateTo = $_POST['filter_date_to'] ?? '';
$yearFilter = $_POST['filter_year'] ?? '';
$expiryFilter = $_POST['filter_expiry_status'] ?? '';
$statusFilter = $_POST['status_filter'] ?? ''; // Existing job status filter

// Columns mapping
$columns = [
    0 => "pi.project_no",
    1 => "pi.creation_date",
    2 => "pi.checklist_status",
    3 => "pi.report_status",
    4 => "pi.review_status",
    5 => "pi.certificatestatus",
    6 => "pi.customer_name",
    7 => "pi.project_status",
    8 => "pi.project_no", // Action Column placeholder
    9 => "pi.equipment_id",
    10 => "pi.checklist_type",
    11 => "ci.sticker_no",
    12 => "pi.project_no", // Certificate Column placeholder
    13 => "pi.inspection_type",
    14 => "pi.equipment_type",
    15 => "pi.equipment_location",
    16 => "pi.inspector_name",
    17 => "pi.project_no" // Delete/Action Column placeholder
];

// Base Query
// Join with checklist_information for sticker_no
$sqlBase = "FROM project_info pi LEFT JOIN checklist_information ci ON pi.project_no = ci.project_no";
$where = " WHERE 1=1 ";

$params = [];
$types = "";

// Role-based filtering
if ($role === 'customer') {
    $where .= " AND pi.customer_name = ? ";
    $params[] = $user;
    $types .= "s";
}
elseif (!in_array($role, ['admin', 'reviewer', 'quality controller', 'document controller'])) {
    $where .= " AND pi.inspector_name = ? ";
    $params[] = $user;
    $types .= "s";
}

// --- APPLY FILTERS ---

if (!empty($inspectorFilter)) {
    $where .= " AND pi.inspector_name = ? ";
    $params[] = $inspectorFilter;
    $types .= "s";
}
if (!empty($clientFilter)) {
    $where .= " AND pi.customer_name = ? ";
    $params[] = $clientFilter;
    $types .= "s";
}
if (!empty($dateFrom)) {
    $where .= " AND DATE(pi.creation_date) >= ? ";
    $params[] = $dateFrom;
    $types .= "s";
}
if (!empty($dateTo)) {
    $where .= " AND DATE(pi.creation_date) <= ? ";
    $params[] = $dateTo;
    $types .= "s";
}
if (!empty($yearFilter)) {
    $where .= " AND YEAR(pi.creation_date) = ? ";
    $params[] = $yearFilter;
    $types .= "s";
}
if (!empty($statusFilter)) {
    $where .= " AND pi.project_status = ? ";
    $params[] = $statusFilter;
    $types .= "s";
}

// Active/Expired Logic
if (!empty($expiryFilter)) {
    if ($expiryFilter === 'Expired') {
        $where .= " AND (SELECT MAX(next_inspection_due_date) FROM reports r WHERE r.project_no = pi.project_no) < CURDATE() ";
    }
    elseif ($expiryFilter === 'Active') {
        $where .= " AND ( (SELECT MAX(next_inspection_due_date) FROM reports r WHERE r.project_no = pi.project_no) >= CURDATE() OR (SELECT MAX(next_inspection_due_date) FROM reports r WHERE r.project_no = pi.project_no) IS NULL ) ";
    }
}


// Global Search
if (!empty($search)) {
    $searchWildcard = "%{$search}%";
    $where .= " AND (
        pi.project_no LIKE ? OR
        pi.customer_name LIKE ? OR
        pi.inspector_name LIKE ? OR
        pi.equipment_id LIKE ? OR
        pi.equipment_location LIKE ? OR
        pi.checklist_type LIKE ? OR
        pi.project_status LIKE ? OR
        ci.sticker_no LIKE ?
    ) ";
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $params[] = $searchWildcard;
    $types .= "ssssssss";
}

// Total records
$totalSql = "SELECT COUNT(*) as total FROM project_info pi";

// Note: Total without filter usually respects Role.
$totalWhere = " WHERE 1=1 ";
$totalParams = [];
$totalTypes = "";

if ($role === 'customer') {
    $totalWhere .= " AND pi.customer_name = ? ";
    $totalParams[] = $user;
    $totalTypes .= "s";
}
elseif (!in_array($role, ['admin', 'reviewer', 'quality controller', 'document controller'])) {
    $totalWhere .= " AND pi.inspector_name = ? ";
    $totalParams[] = $user;
    $totalTypes .= "s";
}

$stmtTotal = $conn->prepare($totalSql . $totalWhere);
if ($totalParams)
    $stmtTotal->bind_param($totalTypes, ...$totalParams);
$stmtTotal->execute();
$recordsTotal = $stmtTotal->get_result()->fetch_assoc()['total'] ?? 0;
$stmtTotal->close();


// Filtered records
$filteredSql = "SELECT COUNT(*) as total $sqlBase $where";
$stmtFiltered = $conn->prepare($filteredSql);
if ($params)
    $stmtFiltered->bind_param($types, ...$params);
$stmtFiltered->execute();
$recordsFiltered = $stmtFiltered->get_result()->fetch_assoc()['total'] ?? 0;
$stmtFiltered->close();


// Ordering
$orderColumnIndex = $_POST['order'][0]['column'] ?? 0;
$orderColumn = $columns[$orderColumnIndex] ?? "pi.creation_date";
$orderDir = $_POST['order'][0]['dir'] ?? 'desc';
$orderBy = " ORDER BY $orderColumn $orderDir ";

// Fetch Data
// Join reports just to get max date for display? Or run subquery?
// We need checklist_status, report_status logic which is already in project_info.
// We also need certificate types.
$sql = "SELECT pi.*, ci.sticker_no, pi.inspection_type,
       (SELECT MAX(next_inspection_due_date) FROM reports r WHERE r.project_no = pi.project_no) as due_date
       $sqlBase $where $orderBy LIMIT ?, ?";

$params[] = $start;
$params[] = $length;
$types .= "ii";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
$projectIds = [];
$projects = [];

while ($row = $result->fetch_assoc()) {
    $projects[] = $row;
    $projectIds[] = $row['project_no'];
}

// Fetch Certificates for these projects ONLY
$projectCertificates = [];
if (!empty($projectIds)) {
    $idsStr = implode(',', array_map('intval', $projectIds));
    $certSql = "
        SELECT project_no, 'Healthcheck' as type FROM crane_health_check_certificate WHERE project_no IN ($idsStr)
        UNION ALL SELECT project_no, 'Loadtestwithload' FROM loadtest_certificate WHERE project_no IN ($idsStr)
        UNION ALL SELECT project_no, 'Mobile' FROM mobile_crane_loadtest WHERE project_no IN ($idsStr)
        UNION ALL SELECT project_no, 'WithLoadTest' FROM withload WHERE project_no IN ($idsStr)
        UNION ALL SELECT project_no, 'Lifting' FROM lifting_gear_certificates WHERE project_no IN ($idsStr)
        UNION ALL SELECT project_no, 'MPI' FROM mpi_certificates WHERE project_no IN ($idsStr)
        UNION ALL SELECT project_no, 'EddyCurrent' FROM eddy_current_inspection WHERE project_no IN ($idsStr)
        UNION ALL SELECT project_no, 'LiquidPenetrantInspection' FROM liquid_penetrant_inspection WHERE project_no IN ($idsStr)
        UNION ALL SELECT project_no, 'RockTest' FROM rocking_test_certificate WHERE project_no IN ($idsStr)
        UNION ALL SELECT project_no, 'LMI' FROM lmi_certificates WHERE project_no IN ($idsStr)
    ";
    $certRes = $conn->query($certSql);
    while ($cRow = $certRes->fetch_assoc()) {
        $projectCertificates[$cRow['project_no']][] = $cRow['type'];
    }
}

// Format Output
foreach ($projects as $row) {
    // ID
    $pNo = '<strong>#' . str_pad($row['project_no'], 5, '0', STR_PAD_LEFT) . '</strong>';
    $date = date('d M Y', strtotime($row['creation_date']));

    // Progress / Actions Logic based on role
    // Replicating frontend logic from original file
    $progressHtml = "";
    if ($role === 'inspector') {
        if ($row['checklist_status'] === 'Pending') {
            $progressHtml = "<a href='../document/checklist/add-checklist.php?project_no={$row['project_no']}' class='text-primary'><i class='icofont-checked color-primary'></i> Create Checklist</a>";
        }
        else {
            $progressHtml = "<span class='text-success'><i class='icofont-check color-success'></i> Checklist Created</span><br>";
            if ($row['checklist_status'] === 'Created') {
                if ($row['report_status'] === 'Pending') {
                    $progressHtml .= "<a href='../document/report/create.php?project_no={$row['project_no']}' class='text-primary'><i class='icofont-edit color-primary'></i> Create Report</a>";
                }
                elseif ($row['report_status'] === 'Generated') {
                    $progressHtml .= "<span class='text-success'><i class='icofont-check color-success'></i> Report Created</span>";
                }
                else {
                    $progressHtml .= "<span class='text-muted'><i class='icofont-lock'></i> Report Locked</span>";
                }
            }
            else {
                $progressHtml .= "<span class='text-muted'><i class='icofont-lock'></i> Checklist Pending</span>";
            }
        }
    }
    else {
        $progressHtml = "<span class='text-muted'><i class='icofont-lock'></i> Access Restricted</span>";
    }

    // Status Badge
    $statusClass = ($row['project_status'] === 'Completed') ? 'bg-success-light text-success' : 'bg-danger-light text-danger';
    $statusBtn = "<span class='btn s_alert $statusClass mb-10' style='padding: 6px 9px; font-size: 11px;'>{$row['project_status']}</span>";

    // Expiry Badge
    $expiryBadge = '';
    if ($row['due_date']) {
        if (strtotime($row['due_date']) < time()) {
            $expiryBadge = "<br><span class='badge badge-danger' style='font-size:10px'>Expired</span>";
        }
        else {
            $expiryBadge = "<br><span class='badge badge-success' style='font-size:10px'>Active</span>";
        }
    }

    // Certificates
    $certHtml = "";
    $types = $projectCertificates[$row['project_no']] ?? [];
    if (!empty($types)) {
        foreach (array_unique($types) as $t) {
            // Label mapping logic
            $label = ucfirst($t);
            if (strtolower($t) == 'loadtestwithload')
                $label = 'Crane Without Load Test';
            if (strtolower($t) == 'mobile')
                $label = 'Crane With Load Test';
            $certHtml .= "<span class='badge badge-success mr-1'>$label</span>";
        }
    }
    else {
        $certHtml = "<span class='badge badge-secondary'>N/A</span>";
    }

    // Action Details
    $detailsBtn = "<a href='job-details.php?id={$row['project_no']}'><button type='button' class='btn btn-sm' style='padding: 6px 9px; font-size: 11px;'>Details <i class='icofont-arrow-right'></i></button></a>";

    // Delete Button (Admin)
    $deleteBtn = "";
    if ($role === 'admin') {
        $deleteBtn = "<button type='button' class='text-danger' onclick='deleteProject({$row['project_no']})' style='padding: 6px 9px; font-size: 14px; border:none; background:none;'><i class='icofont-trash'></i></button>";
    }

    // row data
    $data[] = [
        $pNo,
        $date,
        $progressHtml,
        $row['checklist_status'],
        $row['report_status'],
        $row['review_status'],
        ucfirst($row['certificatestatus']),
        $row['customer_name'],
        $statusBtn . $expiryBadge,
        $detailsBtn,
        $row['equipment_id'],
        ucwords(str_replace(['-', '_'], ' ', $row['checklist_type'])),
        $row['sticker_no'] ?? 'N/A',
        $certHtml,
        ucwords(str_replace(['-', '_'], ' ', $row['inspection_type'] ?? 'N/A')),
        $row['equipment_type'],
        ucfirst($row['equipment_location']),
        $row['inspector_name'],
        $deleteBtn
    ];
}

echo json_encode([
    "draw" => $draw,
    "recordsTotal" => $recordsTotal,
    "recordsFiltered" => $recordsFiltered,
    "data" => $data
]);
?>
