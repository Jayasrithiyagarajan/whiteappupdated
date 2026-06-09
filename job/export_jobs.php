<?php
include '../file/config.php';
session_start();

$logged_in_user = $_SESSION['username'] ?? null;
$user_role = $_SESSION['role'] ?? null;

if (!$logged_in_user) {
    die("Unauthorized");
}

$format = $_GET['format'] ?? 'excel';
$filterStatus = $_GET['status'] ?? '';
$filterInspector = $_GET['inspector'] ?? '';
$filterCustomer = $_GET['customer'] ?? '';
$searchValue = $_GET['search'] ?? '';

// Role-based filtering
if (in_array($user_role, ['admin', 'reviewer', 'quality controller', 'document controller'])) {
    $roleFilter = " 1 ";
} elseif ($user_role === 'customer') {
    $roleFilter = " pi.customer_name = '" . $conn->real_escape_string($logged_in_user) . "' ";
} else {
    $roleFilter = " pi.inspector_name = '" . $conn->real_escape_string($logged_in_user) . "' ";
}

// Base search filter
$searchQuery = " ";
if ($searchValue != '') {
    $searchQuery = " AND (pi.project_no LIKE '%$searchValue%' OR 
                          pi.customer_name LIKE '%$searchValue%' OR 
                          pi.inspector_name LIKE '%$searchValue%' OR 
                          pi.equipment_id LIKE '%$searchValue%' OR 
                          pi.checklist_type LIKE '%$searchValue%' OR 
                          pi.equipment_type LIKE '%$searchValue%' OR 
                          pi.equipment_location LIKE '%$searchValue%') ";
}

if ($filterInspector != '') {
    $searchQuery .= " AND pi.inspector_name = '" . $conn->real_escape_string($filterInspector) . "' ";
}
if ($filterCustomer != '') {
    $searchQuery .= " AND pi.customer_name = '" . $conn->real_escape_string($filterCustomer) . "' ";
}
if ($filterStatus != '') {
    $searchQuery .= " AND (pi.project_status = '" . $conn->real_escape_string($filterStatus) . "' OR pi.checklist_status = '" . $conn->real_escape_string($filterStatus) . "') ";
}

$sql = "SELECT pi.*, ci.sticker_no 
        FROM project_info pi 
        LEFT JOIN checklist_information ci ON pi.project_no = ci.project_no 
        WHERE $roleFilter $searchQuery 
        ORDER BY pi.creation_date DESC";

$result = $conn->query($sql);

if ($format === 'excel' || $format === 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=job-list-' . date('Ymd-His') . '.csv');
    
    $output = fopen('php://output', 'w');
    
    // Header row
    fputcsv($output, [
        'Project ID', 'Date', 'Checklist Status', 'Report Status', 'Review Status', 
        'Certificate Status', 'Customer', 'Project Status', 'Equip ID', 
        'Checklist Type', 'Sticker No', 'Equip Type', 'Location', 'Inspector'
    ]);
    
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [
            "#" . str_pad($row["project_no"], 5, "0", STR_PAD_LEFT),
            $row['creation_date'],
            $row['checklist_status'],
            $row['report_status'],
            $row['review_status'],
            $row['certificatestatus'],
            $row['customer_name'],
            $row['project_status'],
            $row['equipment_id'],
            $row['checklist_type'],
            $row['sticker_no'] ?? 'N/A',
            $row['equipment_type'],
            $row['equipment_location'],
            $row['inspector_name']
        ]);
    }
    fclose($output);
    exit;
} else if ($format === 'pdf') {
    // Simple HTML output for PDF print if no library is available
    // In practice, we would use TCPDF or Dompdf here.
    // Since I cannot install new libraries easily, I will provide a simple printable HTML view.
    echo "<html><head><title>Job List Export</title><style>table{width:100%; border-collapse:collapse;} th,td{border:1px solid #ccc; padding:8px; text-align:left;}</style></head><body>";
    echo "<h1>Job List</h1>";
    echo "<table><thead><tr>";
    echo "<th>Project ID</th><th>Date</th><th>Customer</th><th>Status</th><th>Inspector</th>";
    echo "</tr></thead><tbody>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>#" . str_pad($row["project_no"], 5, "0", STR_PAD_LEFT) . "</td>";
        echo "<td>" . $row['creation_date'] . "</td>";
        echo "<td>" . $row['customer_name'] . "</td>";
        echo "<td>" . $row['project_status'] . "</td>";
        echo "<td>" . $row['inspector_name'] . "</td>";
        echo "</tr>";
    }
    echo "</tbody></table>";
    echo "<script>window.print();</script>";
    echo "</body></html>";
    exit;
}
?>
