<?php
session_start();
include_once('../../file/config.php');

if (strtolower($_SESSION['role'] ?? '') !== 'admin') {
    http_response_code(403);
    exit('Access denied');
}

$user = $_SESSION['username'];
$role = $_SESSION['role'];

// Filter parameters
$filterInspector = $_GET['filter_inspector'] ?? '';
$filterClient    = $_GET['filter_client'] ?? '';
$filterDateFrom  = $_GET['filter_date_from'] ?? '';
$filterDateTo    = $_GET['filter_date_to'] ?? '';
$filterEquipment = $_GET['filter_equipment'] ?? '';
$filterLocation  = $_GET['filter_location'] ?? '';

$where = " WHERE 1=1 ";

if (trim(strtolower($role)) === 'inspector') {
     $where .= " AND inspector_name='".mysqli_real_escape_string($conn,$user)."' ";
}

// Apply Filters
if (!empty($filterInspector)) {
    $where .= " AND inspector_name LIKE '%".mysqli_real_escape_string($conn, $filterInspector)."%' ";
}
if (!empty($filterClient)) {
    $where .= " AND customer_name LIKE '%".mysqli_real_escape_string($conn, $filterClient)."%' ";
}
if (!empty($filterDateFrom)) {
    $where .= " AND DATE(creation_date) >= '".mysqli_real_escape_string($conn, $filterDateFrom)."' ";
}
if (!empty($filterDateTo)) {
    $where .= " AND DATE(creation_date) <= '".mysqli_real_escape_string($conn, $filterDateTo)."' ";
}
if (!empty($filterEquipment)) {
    $where .= " AND equipment_id LIKE '%".mysqli_real_escape_string($conn, $filterEquipment)."%' ";
}
if (!empty($filterLocation)) {
    $where .= " AND equipment_location LIKE '%".mysqli_real_escape_string($conn, $filterLocation)."%' ";
}

// Fetch Data
$sql = "SELECT * FROM project_info $where ORDER BY project_no DESC";
$result = $conn->query($sql);

// Set headers to force download as CSV
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="kpi_report_'.date('Y-m-d_H-i-s').'.csv"');

// Create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// Output the column headings
fputcsv($output, array('Project No', 'Creation Date', 'Customer Name', 'Location', 'Equipment ID', 'Equipment Type', 'Inspector', 'Sticker Status', 'Inspection Type'));

// Fetch the data and loop over the rows
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $formattedDate = date('d-m-Y', strtotime($row['creation_date']));
        $stickerStatus = ($row['sticker_status'] == 'Yes') ? 'Yes' : 'No';

        fputcsv($output, array(
            $row['project_no'],
            $formattedDate,
            $row['customer_name'],
            $row['equipment_location'],
            $row['equipment_id'],
            $row['equipment_type'],
            $row['inspector_name'],
            $stickerStatus,
            $row['inspection_type']
        ));
    }
}

fclose($output);
exit();
?>
