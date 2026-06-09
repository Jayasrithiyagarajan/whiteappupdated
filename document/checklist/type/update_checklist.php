<?php
// Debugging: Show all errors and warnings
// ini_set('display_errors', 1);
// error_reporting(E_ALL);

include_once('../../../file/config.php');

// Prepare the data for insertion
$results = [];
$remarks = [];

// Validate and fetch POST inputs
$client_name = $_POST['client_name'] ?? $_POST['header_client_name'] ?? null;
$client_phone = $_POST['client_phone'] ?? null; // Add this line
$client_signature = $_POST['client_signature'] ?? null;
$checklist_no = $_POST['checklist_no'] ?? null;
$recommendations = htmlspecialchars($_POST['recommendations'] ?? '', ENT_QUOTES, 'UTF-8');

// NEW: Get revision data from POST
$revision_1 = $_POST['revision_1'] ?? null;
$type_revision_1 = $_POST['type_revision_1'] ?? null;
$revision_1_date = $_POST['revision_1_date'] ?? null;
$revision_2 = $_POST['revision_2'] ?? null;
$type_revision_2 = $_POST['type_revision_2'] ?? null;
$revision_2_date = $_POST['revision_2_date'] ?? null;

if (!$client_name || !$checklist_no) {
    die("Client name and checklist number are required.");
}

// Fetch project_no from the checklistinformation table
$projectQuery = $conn->prepare("SELECT project_no FROM checklist_information WHERE checklist_id = ?");
$projectQuery->bind_param("i", $checklist_no);
$projectQuery->execute();
$projectQuery->bind_result($project_no);
$projectQuery->fetch();
$projectQuery->close();

if (empty($project_no)) {
    die("Project ID not found for the given checklist number.");
}

// Decode the Base64 image string and save the signature ONLY if provided
$signature_folder = '../../uploads/';
if (!file_exists($signature_folder)) {
    mkdir($signature_folder, 0777, true);
}

$signature_filename = $project_no . '.png';
$signature_file_path = $signature_folder . $signature_filename;

// Decode Base64 and save as image
if (!empty($client_signature)) {
    $signature_data = explode(',', $client_signature);
    if (count($signature_data) === 2) {
        $decoded_signature = base64_decode($signature_data[1]);
        if (file_put_contents($signature_file_path, $decoded_signature) === false) {
            die("Failed to save signature.");
        }
    } else {
        die("Invalid signature format.");
    }
}

// Check if 'result' is an array before iterating
if (isset($_POST['result']) && is_array($_POST['result'])) {
    foreach ($_POST['result'] as $key => $value) {
        $results[$key] = is_array($value) ? implode(",", $value) : $value;
    }
}

// Check if 'remarks' is an array; if not, make it an array
if (isset($_POST['checklist_remark'])) {
    $remarks = is_array($_POST['checklist_remark']) ? $_POST['checklist_remark'] : [$_POST['checklist_remark']];
}

$checklist_no = $_POST['checklist_no'] ?? null;

if (!$checklist_no) {
    die("Checklist number is required.");
}

// Update checklist_information ONLY if the fields are present in the form submission
if (isset($_POST['report_no'])) {
    
    // Fetch existing data so we don't overwrite missing fields with empty strings
    $existing = [];
    $fetchQuery = $conn->prepare("SELECT report_no, inspection_date, client_name, inspected_by, location, sticker_no, equipment_no, crane_serial_no, equipmenttype, capacity_swl, manufacturer, model_no, year_model FROM checklist_information WHERE checklist_id = ?");
    if ($fetchQuery) {
        $fetchQuery->bind_param("i", $checklist_no);
        $fetchQuery->execute();
        $result = $fetchQuery->get_result();
        if ($row = $result->fetch_assoc()) {
            $existing = $row;
        }
        $fetchQuery->close();
    }

    $report_no = $_POST['report_no'] ?? $existing['report_no'] ?? '';
    $inspection_date = $_POST['inspection_date'] ?? $existing['inspection_date'] ?? '';
    
    // For client name, prioritize header_client_name, then client_name, then existing
    $client_name_info = $_POST['header_client_name'] ?? $_POST['client_name'] ?? $existing['client_name'] ?? '';
    
    $inspected_by = $_POST['inspected_by'] ?? $existing['inspected_by'] ?? '';
    $location = $_POST['location'] ?? $existing['location'] ?? '';
    $sticker_no = $_POST['sticker_no'] ?? $existing['sticker_no'] ?? '';
    $equipment_no = $_POST['equipment_no'] ?? $existing['equipment_no'] ?? '';
    $crane_serial_no = $_POST['crane_serial_no'] ?? $existing['crane_serial_no'] ?? '';
    $equipmenttype = $_POST['equipmenttype'] ?? $existing['equipmenttype'] ?? '';
    $capacity_swl = $_POST['capacity_swl'] ?? $existing['capacity_swl'] ?? '';
    $manufacturer = $_POST['manufacturer'] ?? $existing['manufacturer'] ?? '';
    $model_no = $_POST['model_no'] ?? $existing['model_no'] ?? '';
    $year_model = $_POST['year_model'] ?? $existing['year_model'] ?? '';

    $infoQuery = $conn->prepare("UPDATE checklist_information SET report_no=?, inspection_date=?, client_name=?, inspected_by=?, location=?, sticker_no=?, equipment_no=?, crane_serial_no=?, equipmenttype=?, capacity_swl=?, manufacturer=?, model_no=?, year_model=? WHERE checklist_id=?");
    if ($infoQuery) {
        $infoQuery->bind_param("sssssssssssssi", $report_no, $inspection_date, $client_name_info, $inspected_by, $location, $sticker_no, $equipment_no, $crane_serial_no, $equipmenttype, $capacity_swl, $manufacturer, $model_no, $year_model, $checklist_no);
        $infoQuery->execute();
        $infoQuery->close();
    }

    // Also update the `reports` table if a report exists for this project
    // Fetch existing report data to prevent overwriting with empty strings
    $existingReport = [];
    $reportFetchQuery = $conn->prepare("SELECT * FROM reports WHERE project_no = ?");
    if ($reportFetchQuery) {
        $reportFetchQuery->bind_param("s", $project_no);
        $reportFetchQuery->execute();
        $result = $reportFetchQuery->get_result();
        if ($row = $result->fetch_assoc()) {
            $existingReport = $row;
        }
        $reportFetchQuery->close();
    }

    $r_report_no = !empty($_POST['report_no']) ? $_POST['report_no'] : ($existingReport['report_no'] ?? '');
    $r_date_of_inspection = !empty($_POST['inspection_date']) ? $_POST['inspection_date'] : ($existingReport['date_of_inspection'] ?? '');
    $r_client_company_name = !empty($_POST['header_client_name']) ? $_POST['header_client_name'] : (!empty($_POST['client_name']) ? $_POST['client_name'] : ($existingReport['client_company_name'] ?? ''));
    $r_issued_by = !empty($_POST['inspected_by']) ? $_POST['inspected_by'] : ($existingReport['issued_by'] ?? '');
    $r_location = !empty($_POST['location']) ? $_POST['location'] : ($existingReport['location'] ?? '');
    $r_sticker_number_issued = !empty($_POST['sticker_no']) ? $_POST['sticker_no'] : ($existingReport['sticker_number_issued'] ?? '');
    $r_equipment_id_no = !empty($_POST['equipment_no']) ? $_POST['equipment_no'] : ($existingReport['equipment_id_no'] ?? '');
    $r_equipment_serial_no = !empty($_POST['crane_serial_no']) ? $_POST['crane_serial_no'] : ($existingReport['equipment_serial_no'] ?? '');
    $r_type = !empty($_POST['equipmenttype']) ? $_POST['equipmenttype'] : ($existingReport['type'] ?? '');
    $r_capacity = !empty($_POST['capacity_swl']) ? $_POST['capacity_swl'] : ($existingReport['capacity'] ?? '');
    $r_manufacturer = !empty($_POST['manufacturer']) ? $_POST['manufacturer'] : ($existingReport['manufacturer'] ?? '');
    $r_model = !empty($_POST['model_no']) ? $_POST['model_no'] : ($existingReport['model'] ?? '');

    $reportUpdateQuery = $conn->prepare("UPDATE reports SET report_no=?, date_of_inspection=?, client_company_name=?, issued_by=?, location=?, sticker_number_issued=?, equipment_id_no=?, equipment_serial_no=?, type=?, capacity=?, manufacturer=?, model=? WHERE project_no=?");
    if ($reportUpdateQuery) {
        $reportUpdateQuery->bind_param("sssssssssssss", $r_report_no, $r_date_of_inspection, $r_client_company_name, $r_issued_by, $r_location, $r_sticker_number_issued, $r_equipment_id_no, $r_equipment_serial_no, $r_type, $r_capacity, $r_manufacturer, $r_model, $project_no);
        $reportUpdateQuery->execute();
        $reportUpdateQuery->close();
    }
}

// Concatenate all results and remarks into single strings
$combined_results = implode(",", $results);
$combined_remarks = implode(",", $remarks);

// Check if the checklist ID already exists
$checkQuery = $conn->prepare("SELECT 1 FROM checklist_results WHERE checklist_id = ?");
$checkQuery->bind_param("i", $checklist_no);
$checkQuery->execute();
$checkQuery->store_result();

if ($checkQuery->num_rows > 0) {
    // Update if a record exists - add client_phone to the query
    $stmt = $conn->prepare("UPDATE checklist_results SET result = ?, checklist_remark = ?, client_name = ?, client_phone = ?, client_signature = ?, recommendations = ?, project_no = ?, revision_1 = ?, type_revision_1 = ?, revision_1_date = ?, revision_2 = ?, type_revision_2 = ?, revision_2_date = ? WHERE checklist_id = ?");
} else {
    // Insert a new record if none exists - add client_phone to the query
    $stmt = $conn->prepare("INSERT INTO checklist_results (result, checklist_remark, client_name, client_phone, client_signature, recommendations, project_no, revision_1, type_revision_1, revision_1_date, revision_2, type_revision_2, revision_2_date, checklist_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
}

$checkQuery->close();

if ($stmt === false) {
    die("MySQL Error: " . $conn->error);
}

// Bind parameters and execute the query - add $client_phone to the bind_param
$stmt->bind_param("sssssssssssssi", $combined_results, $combined_remarks, $client_name, $client_phone, $signature_filename, $recommendations, $project_no, $revision_1, $type_revision_1, $revision_1_date, $revision_2, $type_revision_2, $revision_2_date, $checklist_no);

if (!$stmt->execute()) {
    die("Execution failed: " . $stmt->error);
}

//echo "Data inserted or updated successfully.";
$stmt->close();
$conn->close();

// Redirect to the desired page
header("Location: ../index.php");
exit();
?>