<?php
header('Content-Type: application/json');
ob_clean(); // clears any previous output
error_reporting(E_ALL);
ini_set('display_errors', 1);

//session_start();
include_once('../inc/function.php');
include '../file/config.php';

$draw = intval($_GET['draw'] ?? 1);
$start = intval($_GET['start'] ?? 0);
$length = intval($_GET['length'] ?? 10);

$logged_in_user = $_SESSION['username'] ?? null;
$user_role = $_SESSION['role'] ?? null;

if (!$logged_in_user) {
    echo json_encode([
        "draw" => $draw,
        "recordsTotal" => 0,
        "recordsFiltered" => 0,
        "data" => []
    ]);
    exit;
}

$columns = "project_no, creation_date, checklist_status, report_status, review_status, certificatestatus,
            customer_name, project_status, checklist_type, equipment_type, equipment_id, equipment_location,
            inspector_name";

$baseQuery = "SELECT $columns FROM project_info";
$countQuery = "SELECT COUNT(*) FROM project_info";

$params = [];
$where = "";
$order = " ORDER BY creation_date DESC LIMIT ? OFFSET ?";

if (in_array($user_role, ['admin', 'reviewer', 'quality controller', 'document controller'])) {
    $sql = "$baseQuery $order";
    $countSql = $countQuery;
    $params = [$length, $start];
    $types = "ii";
} elseif ($user_role === 'customer') {
    $where = " WHERE customer_name = ?";
    $sql = "$baseQuery $where $order";
    $countSql = "$countQuery $where";
    $params = [$logged_in_user, $length, $start];
    $types = "sii";
} else {
    $where = " WHERE inspector_name = ?";
    $sql = "$baseQuery $where $order";
    $countSql = "$countQuery $where";
    $params = [$logged_in_user, $length, $start];
    $types = "sii";
}

// Prepare and execute data query
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

// Count total records
if ($where) {
    $countStmt = $conn->prepare($countSql);
    $countStmt->bind_param("s", $params[0]);
    $countStmt->execute();
    $countResult = $countStmt->get_result();
    $total = $countResult->fetch_row()[0];
} else {
    $total = $conn->query($countSql)->fetch_row()[0];
}

$data = [];

while ($row = $result->fetch_assoc()) {
    $progress = ($user_role === 'inspector')
        ? (($row['checklist_status'] === 'Pending') 
            ? "<a href='../document/checklist/add-checklist.php?project_no={$row['project_no']}'>Create Checklist</a>" 
            : "<span>Checklist Created</span>")
        : "<span>Access Restricted</span>";

    $statusBtn = ($row["project_status"] === "Completed")
        ? "<span style='color:green;'>Completed</span>"
        : "<span style='color:red;'>Pending</span>";

    $deleteBtn = ($user_role === 'admin')
        ? "<button onclick=\"alert('Delete project {$row['project_no']}')\">Delete</button>"
        : "";

    $data[] = [
        "project_no" => "#" . str_pad($row["project_no"], 5, "0", STR_PAD_LEFT),
        "creation_date" => date("d M Y", strtotime($row["creation_date"])),
        "progress" => $progress,
        "checklist_status" => $row["checklist_status"],
        "report_status" => $row["report_status"],
        "review_status" => $row["review_status"],
        "certificatestatus" => ucfirst($row["certificatestatus"]),
        "customer_name" => $row["customer_name"],
        "project_status" => $statusBtn,
        "action" => "<a href='job-details.php?id={$row['project_no']}'>View</a>",
        "equipment_id" => $row["equipment_id"],
        "checklist_type" => ucwords(str_replace(['-', '_'], ' ', $row["checklist_type"])),
        "equipment_type" => $row["equipment_type"],
        "equipment_location" => ucfirst($row["equipment_location"]),
        "inspector_name" => $row["inspector_name"],
        "delete_action" => $deleteBtn
    ];
}

echo json_encode([
    "draw" => $draw,
    "recordsTotal" => $total,
    "recordsFiltered" => $total,
    "data" => $data
]);