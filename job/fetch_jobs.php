<?php
error_reporting(0);
ini_set('display_errors', 0);
// Debug log
// file_put_contents('debug.log', date('Y-m-d H:i:s') . " - Called fetch_jobs.php\n", FILE_APPEND);

include '../file/config.php';
@session_start();
header('Content-Type: application/json');

// if (!isset($_SESSION['username'])) file_put_contents('debug.log', "Session missing\n", FILE_APPEND);

// Check if the user is logged in
$logged_in_user = $_SESSION['username'] ?? null;
$user_role = $_SESSION['role'] ?? null;

if (!$logged_in_user) {
    echo json_encode(['error' => 'not logged in']);
    exit;
}

// DataTables parameters
$draw = intval($_POST['draw'] ?? 1);
$start = intval($_POST['start'] ?? 0);
$rowperpage = intval($_POST['length'] ?? 10);
$columnIndex = $_POST['order'][0]['column'] ?? 0;
$columnName = $_POST['columns'][$columnIndex]['data'] ?? 'pi.project_no';
$columnSortOrder = $_POST['order'][0]['dir'] ?? 'desc';
if (!in_array(strtolower($columnSortOrder), ['asc', 'desc'])) {
    $columnSortOrder = 'desc';
}
$searchValue = $_POST['search']['value'] ?? '';

// Custom filters
$filterInspector = $_POST['inspector'] ?? '';
$filterCustomer = $_POST['customer'] ?? '';
$filterStatus = $_POST['status'] ?? '';

// Map DataTable column names to DB column names
$columnMap = [
    'project_no' => 'pi.project_no',
    'creation_date' => 'pi.creation_date',
    'customer_name' => 'pi.customer_name',
    'project_status' => 'pi.project_status',
    'equipment_id' => 'pi.equipment_id',
    'checklist_type' => 'pi.checklist_type',
    'sticker_no' => 'ci.sticker_no',
    'equipment_type' => 'pi.equipment_type',
    'equipment_location' => 'pi.equipment_location',
    'inspector_name' => 'pi.inspector_name',
    'checklist_status' => 'pi.checklist_status',
    'report_status' => 'pi.report_status',
    'review_status' => 'pi.review_status',
    'certificatestatus' => 'pi.certificatestatus'
];

if (isset($columnMap[$columnName])) {
    $orderBy = $columnMap[$columnName];
} else {
    $orderBy = 'pi.project_no'; // Default fallback
}

// Base search filter
$searchQuery = " ";
if ($searchValue != '') {
    $escSearch = $conn->real_escape_string($searchValue);
    $searchQuery = " AND (pi.project_no LIKE '%$escSearch%' OR 
                          pi.customer_name LIKE '%$escSearch%' OR 
                          pi.inspector_name LIKE '%$escSearch%' OR 
                          pi.equipment_id LIKE '%$escSearch%' OR 
                          pi.checklist_type LIKE '%$escSearch%' OR 
                          pi.equipment_type LIKE '%$escSearch%' OR 
                          pi.equipment_location LIKE '%$escSearch%' OR 
                          ci.sticker_no LIKE '%$escSearch%') ";
}

// Custom filters
if ($filterInspector != '') {
    $searchQuery .= " AND pi.inspector_name = '" . $conn->real_escape_string($filterInspector) . "' ";
}
if ($filterCustomer != '') {
    $searchQuery .= " AND pi.customer_name = '" . $conn->real_escape_string($filterCustomer) . "' ";
}
if ($filterStatus != '') {
    $searchQuery .= " AND (pi.project_status = '" . $conn->real_escape_string($filterStatus) . "' OR pi.checklist_status = '" . $conn->real_escape_string($filterStatus) . "') ";
}

// Role-based filtering
if (in_array($user_role, ['admin', 'reviewer', 'quality controller', 'document controller'])) {
    $roleFilter = " 1 ";
} elseif ($user_role === 'customer') {
    $roleFilter = " pi.customer_name = '" . $conn->real_escape_string($logged_in_user) . "' ";
} else {
    $roleFilter = " pi.inspector_name = '" . $conn->real_escape_string($logged_in_user) . "' ";
}

// Total number of records without filtering
$totalRecordsQuery = "SELECT count(*) as allcount FROM project_info pi WHERE $roleFilter";
$resTotal = $conn->query($totalRecordsQuery);
if (!$resTotal) {
    echo json_encode(['error' => 'Query Error: ' . $conn->error]);
    exit;
}
$totalRecords = $resTotal->fetch_assoc()['allcount'];

// Total number of records with filtering
$totalRecordwithFilterQuery = "SELECT count(*) as allcount FROM project_info pi LEFT JOIN checklist_information ci ON pi.project_no = ci.project_no WHERE $roleFilter $searchQuery";
$resFilter = $conn->query($totalRecordwithFilterQuery);
if (!$resFilter) {
    echo json_encode(['error' => 'Query Error: ' . $conn->error]);
    exit;
}
$totalRecordwithFilter = $resFilter->fetch_assoc()['allcount'];

// Fetch records
$empQuery = "SELECT pi.*, ci.sticker_no 
             FROM project_info pi 
             LEFT JOIN checklist_information ci ON pi.project_no = ci.project_no 
             WHERE $roleFilter $searchQuery 
             ORDER BY $orderBy $columnSortOrder 
             LIMIT $start, $rowperpage";
$empRecords = $conn->query($empQuery);
if (!$empRecords) {
    echo json_encode(['error' => 'Query Error: ' . $conn->error]);
    exit;
}

$data = [];

// Certificate Map (fetching certificates for the current batch of projects)
$projectIds = [];
$rows = [];
while ($row = $empRecords->fetch_assoc()) {
    $projectIds[] = $row['project_no'];
    $rows[] = $row;
}

$projectCertificates = [];
if (!empty($projectIds)) {
    $ids = implode(',', $projectIds);
    $certificateQuery = "
        SELECT project_no, certificate_type FROM (
            SELECT project_no, 'Healthcheck' AS certificate_type FROM crane_health_check_certificate WHERE project_no IN ($ids)
            UNION ALL
            SELECT project_no, 'Loadtestwithload' FROM loadtest_certificate WHERE project_no IN ($ids)
            UNION ALL
            SELECT project_no, 'Mobile' FROM mobile_crane_loadtest WHERE project_no IN ($ids)
            UNION ALL
            SELECT project_no, 'WithLoadTest' FROM withload WHERE project_no IN ($ids)
            UNION ALL
            SELECT project_no, 'Lifting' FROM lifting_gear_certificates WHERE project_no IN ($ids)
            UNION ALL
            SELECT project_no, 'MPI' FROM mpi_certificates WHERE project_no IN ($ids)
            UNION ALL
            SELECT project_no, 'EddyCurrent' FROM eddy_current_inspection WHERE project_no IN ($ids)
            UNION ALL
            SELECT project_no, 'LiquidPenetrantInspection' FROM liquid_penetrant_inspection WHERE project_no IN ($ids)
            UNION ALL
            SELECT project_no, 'RockTest' FROM rocking_test_certificate WHERE project_no IN ($ids)
            UNION ALL
            SELECT project_no, 'LMI' FROM lmi_certificates WHERE project_no IN ($ids)
        ) AS cert_types
    ";
    $certRes = $conn->query($certificateQuery);
    if ($certRes) {
        while ($c = $certRes->fetch_assoc()) {
            $projectCertificates[$c['project_no']][] = $c['certificate_type'];
        }
    }
}

foreach ($rows as $row) {
    // Certificate badges
    $certHtml = '';
    $certTypes = $projectCertificates[$row['project_no']] ?? [];
    if (!empty($certTypes)) {
        foreach (array_unique($certTypes) as $type) {
            $label = '';
            switch (strtolower($type)) {
                case 'loadtestwithload': $label = 'Crane Without Load Test'; break;
                case 'mobile': $label = 'Crane With Load Test'; break;
                default: $label = ucfirst($type);
            }
            $certHtml .= '<span class="badge badge-success mr-1">' . htmlspecialchars($label) . '</span> ';
        }
    } else {
        $certHtml = '<span class="badge badge-secondary">N/A</span>';
    }

    // Progress Column Logic
    $progressHtml = '';
    if ($user_role === 'inspector') {
        if ($row['checklist_status'] === 'Pending') {
            $progressHtml .= '<a href="../document/checklist/add-checklist.php?project_no=' . $row['project_no'] . '" class="text-primary"><i class="icofont-checked color-primary"></i> Create Checklist</a><br>';
        } else {
            $progressHtml .= '<span class="text-success"><i class="icofont-check color-success"></i> Checklist Created</span><br>';
        }

        if ($row['checklist_status'] === 'Created') {
            if ($row['report_status'] === 'Pending') {
                $progressHtml .= '<a href="../document/report/create.php?project_no=' . $row['project_no'] . '" class="text-primary"><i class="icofont-edit color-primary"></i> Create Report</a>';
            } elseif ($row['report_status'] === 'Generated') {
                $progressHtml .= '<span class="text-success"><i class="icofont-check color-success"></i> Report Created</span>';
            } else {
                $progressHtml .= '<span class="text-muted"><i class="icofont-lock"></i> Report Locked</span>';
            }
        } else {
            $progressHtml .= '<span class="text-muted"><i class="icofont-lock"></i> Checklist Pending</span>';
        }
    } else {
        $progressHtml = '<span class="text-muted"><i class="icofont-lock"></i> Access Restricted</span>';
    }

    // Actions
    $actionHtml = '<a href="job-details.php?id=' . $row['project_no'] . '"><button type="button" class="btn btn-sm" style="padding: 6px 9px; font-size: 11px;">Details <i class="icofont-arrow-right"></i></button></a>';
    
    $deleteHtml = '';
    if ($user_role === 'admin') {
        $deleteHtml = '<button type="button" class="text-danger" onclick="deleteProject(\'' . $row['project_no'] . '\')" style="padding: 6px 9px; font-size: 14px; display: inline-block; margin-top: 5px;"><i class="icofont-trash"></i></button>';
    }

    $data[] = array(
        "project_no" => "#" . str_pad($row["project_no"], 5, "0", STR_PAD_LEFT),
        "creation_date" => date("d M Y", strtotime($row["creation_date"])),
        "progress" => $progressHtml,
        "checklist_status" => $row['checklist_status'],
        "report_status" => $row['report_status'],
        "review_status" => $row['review_status'],
        "certificatestatus" => ucfirst($row['certificatestatus']),
        "customer_name" => $row['customer_name'],
        "project_status" => '<a href="#" class="btn s_alert ' . (($row["project_status"] === "Completed") ? 'bg-success-light text-success' : 'bg-danger-light text-danger') . ' mb-10" style="padding: 6px 9px; font-size: 11px;">' . (($row["project_status"] === "Completed") ? 'Completed' : 'Pending') . '</a>',
        "action" => $actionHtml,
        "equipment_id" => $row['equipment_id'],
        "checklist_type" => ucwords(str_replace(['-', '_'], ' ', $row["checklist_type"])),
        "sticker_no" => $row['sticker_no'] ?? 'N/A',
        "certificate_type" => $certHtml,
        "equipment_type" => $row['equipment_type'],
        "equipment_location" => ucfirst($row['equipment_location']),
        "inspector_name" => $row['inspector_name'],
        "delete_action" => $deleteHtml
    );
}

// Response
$response = array(
    "draw" => intval($draw),
    "iTotalRecords" => $totalRecords,
    "iTotalDisplayRecords" => $totalRecordwithFilter,
    "aaData" => $data
);

$json = json_encode($response);
if ($json === false) {
    echo json_encode(['error' => 'JSON encoding error: ' . json_last_error_msg()]);
} else {
    echo $json;
}
exit;
?>
