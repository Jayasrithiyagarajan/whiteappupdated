<?php 
include_once('../../../../file/config.php'); // include your database connection

// Check if checklist_type and checklist_no parameters are set in the URL
$checklist_type = isset($_GET['checklist_type']) ? $_GET['checklist_type'] : null;
$checklist_no = isset($_GET['checklist_no']) ? $_GET['checklist_no'] : null;

// Debugging lines to verify received parameters
// echo "Checklist Type: " . htmlspecialchars($checklist_type) . "<br>";
// echo "Checklist No: " . htmlspecialchars($checklist_no) . "<br>";

// Validate both parameters
if (empty($checklist_type) || empty($checklist_no)) {
    echo "Both checklist_type and checklist_no are required.";
    exit;
}

// SQL query to fetch data from the 'checklist_information' table based on checklist_type and checklist_no
$query = "SELECT *, project_no FROM checklist_information WHERE checklist_type = ? AND checklist_id = ?;";
$stmt = $conn->prepare($query);
$stmt->bind_param("si", $checklist_type, $checklist_no); // "s" for string, "i" for integer
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $project_no = $row['project_no']; // Fetch project_no
} else {
    echo "No record found for the specified checklist type and checklist ID!";
    $row = []; // Initialize as an empty array if no record found
    exit;
}

// Fetch checklist results
// Initialize variables
$selected_results = [];
$db_remark = '';
$revision_data = []; // Array to store revision data

if ($checklist_no) {
    // Fetch checklist data
    $stmt = $conn->prepare("SELECT result, checklist_remark, client_name, recommendations, 
                           revision_1, type_revision_1, revision_1_date,
                           revision_2, type_revision_2, revision_2_date 
                           FROM checklist_results WHERE checklist_id = ?");
    $stmt->bind_param("i", $checklist_no);
    $stmt->execute();
    $stmt->bind_result(
        $db_result, 
        $db_remark, 
        $client_name, 
        $recommendations,
        $revision_1,
        $type_revision_1,
        $revision_1_date,
        $revision_2,
        $type_revision_2,
        $revision_2_date
    );
    $stmt->fetch();
    $stmt->close();

    // Debugging output
    // echo "Database Result: " . htmlspecialchars($db_result) . "<br>";
    // echo "Database Remark: " . htmlspecialchars($db_remark) . "<br>";
    
     // Store revision data in an array
    $revision_data = [
        'revision_1' => $revision_1,
        'type_revision_1' => $type_revision_1,
        'revision_1_date' => $revision_1_date,
        'revision_2' => $revision_2,
        'type_revision_2' => $type_revision_2,
        'revision_2_date' => $revision_2_date
    ];

    // Convert the result to an array for easy checking
    $selected_results = explode(",", $db_result);
    $chek_remark = explode(",", $db_remark);
} else {
    echo "Checklist ID is required.";
    exit;
}
?>