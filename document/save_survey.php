<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database configuration
include_once('../file/config.php');

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request method.");
}

// Check database connection
if (!isset($conn) || !$conn) {
    die("Database connection failed.");
}

// Get and sanitize form data
$project_id = isset($_POST['project_id']) ? $conn->real_escape_string($_POST['project_id']) : '';
$client_name = isset($_POST['client_name']) ? $conn->real_escape_string($_POST['client_name']) : '';
$survey_date = isset($_POST['survey_date']) ? $conn->real_escape_string($_POST['survey_date']) : '';
$contact_person = isset($_POST['contact_person']) ? $conn->real_escape_string($_POST['contact_person']) : '';
$email = isset($_POST['email']) ? $conn->real_escape_string($_POST['email']) : '';
$years_business = isset($_POST['years_business']) ? $conn->real_escape_string($_POST['years_business']) : '';
$telephone = isset($_POST['telephone']) ? $conn->real_escape_string($_POST['telephone']) : '';
$status = isset($_POST['status']) ? $conn->real_escape_string($_POST['status']) : '';

// Survey questions
$qualification_card = isset($_POST['qualification_card']) ? $conn->real_escape_string($_POST['qualification_card']) : '';
$qualification_remarks = isset($_POST['qualification_remarks']) ? $conn->real_escape_string($_POST['qualification_remarks']) : '';

$response_time = isset($_POST['response_time']) ? $conn->real_escape_string($_POST['response_time']) : '';
$response_remarks = isset($_POST['response_remarks']) ? $conn->real_escape_string($_POST['response_remarks']) : '';

$ppe = isset($_POST['ppe']) ? $conn->real_escape_string($_POST['ppe']) : '';
$ppe_remarks = isset($_POST['ppe_remarks']) ? $conn->real_escape_string($_POST['ppe_remarks']) : '';

$aramco_standards = isset($_POST['aramco_standards']) ? $conn->real_escape_string($_POST['aramco_standards']) : '';
$aramco_remarks = isset($_POST['aramco_remarks']) ? $conn->real_escape_string($_POST['aramco_remarks']) : '';

// NEW: Added overall_satisfaction field
$overall_satisfaction = isset($_POST['overall_satisfaction']) ? $conn->real_escape_string($_POST['overall_satisfaction']) : '';
$overall_satisfaction_remarks = isset($_POST['overall_satisfaction_remarks']) ? $conn->real_escape_string($_POST['overall_satisfaction_remarks']) : '';

$comments = isset($_POST['comments']) ? $conn->real_escape_string($_POST['comments']) : '';
$evaluated_by = isset($_POST['evaluated_by']) ? $conn->real_escape_string($_POST['evaluated_by']) : '';

// Handle signature file path (no file upload needed)
$signature_filename = '';
if (isset($_POST['signature_file']) && !empty($_POST['signature_file'])) {
    $signature_path = $conn->real_escape_string($_POST['signature_file']);
    // Store just the filename or the full path as per your requirement
    $signature_filename = basename($signature_path);
} else {
    // Check if signature exists in the default location
    $default_signature_path = "uploads/{$project_id}.png";
    if (file_exists($default_signature_path)) {
        $signature_filename = "{$project_id}.png";
    }
}

// Validate required fields
$required_fields = [
    'project_id' => 'Project ID',
    'client_name' => 'Client Name',
    'survey_date' => 'Survey Date',
    'status' => 'Client Status',
    'qualification_card' => 'Question 1 (Inspector attention to safety)',
    'response_time' => 'Question 2 (Inspector thorough and effective)',
    'ppe' => 'Question 3 (Inspector arrive on time)',
    'aramco_standards' => 'Question 4 (Inspector professionalism)',
    'overall_satisfaction' => 'Question 5 (Overall satisfaction)',
    'evaluated_by' => 'Evaluated By'
];

$missing_fields = [];
foreach ($required_fields as $field => $field_name) {
    if (empty($$field)) {
        $missing_fields[] = $field_name;
    }
}

if (!empty($missing_fields)) {
    die("Error: The following fields are required: " . implode(', ', $missing_fields));
}

// Check if survey already exists for this project
$check_stmt = $conn->prepare("SELECT id FROM customer_survey_report WHERE project_id = ?");
$check_stmt->bind_param("s", $project_id);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows > 0) {
    die("Error: A survey has already been submitted for this project.");
}

// Insert survey data into database
$sql = "INSERT INTO customer_survey_report (
    project_id, client_name, survey_date, contact_person, email, 
    years_business, telephone, status, qualification_card, 
    qualification_remarks, response_time, response_remarks, 
    ppe, ppe_remarks, aramco_standards, aramco_remarks, 
    overall_satisfaction, overall_satisfaction_remarks,
    comments, evaluated_by, signature_filename, created_at
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param(
    "sssssssssssssssssssss", 
    $project_id, 
    $client_name, 
    $survey_date, 
    $contact_person, 
    $email,
    $years_business, 
    $telephone, 
    $status, 
    $qualification_card,
    $qualification_remarks, 
    $response_time, 
    $response_remarks,
    $ppe, 
    $ppe_remarks, 
    $aramco_standards, 
    $aramco_remarks,
    $overall_satisfaction,
    $overall_satisfaction_remarks,
    $comments, 
    $evaluated_by, 
    $signature_filename
);

if ($stmt->execute()) {
    // Success - redirect to success page or back to project
    header("Location: survey_success.php?project_id=" . $project_id);
    exit();
} else {
    die("Error saving survey: " . $stmt->error);
}

$stmt->close();
$conn->close();
?>