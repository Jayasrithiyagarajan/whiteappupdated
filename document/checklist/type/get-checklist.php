<?php 
include_once('../../../file/config.php'); // Include your database connection

// Fetch checklist_type and checklist_no from GET parameters
$checklist_type = isset($_GET['checklist_type']) ? $_GET['checklist_type'] : '';
$checklist_no = isset($_GET['checklist_no']) ? $_GET['checklist_no'] : '';

// Debug lines to check input parameters (optional for production)
if (!empty($checklist_type) && !empty($checklist_no)) {
    echo "Checklist Type: " . htmlspecialchars($checklist_type) . "<br>";
    echo "Checklist No: " . htmlspecialchars($checklist_no) . "<br>";
}

// Initialize variables
$row = [];

// Check if both checklist_type and checklist_no are provided
if (!empty($checklist_type) && !empty($checklist_no)) {
    // SQL query to fetch data based on checklist_type and checklist_no using prepared statements
    // SQL query to fetch data from checklist_information and checklist_results
    $query = "SELECT ci.*, cr.result, cr.checklist_remark, cr.recommendations, cr.client_name AS client_rep_name, cr.client_phone, cr.client_signature 
              FROM checklist_information ci 
              LEFT JOIN checklist_results cr ON ci.checklist_id = cr.checklist_id 
              WHERE ci.checklist_type = ? AND ci.checklist_id = ?";
    
    // Prepare the SQL query
    $stmt = $conn->prepare($query);
    
    if ($stmt) {
        // Bind the parameters to prevent SQL injection
        $stmt->bind_param("si", $checklist_type, $checklist_no); // 's' for string, 'i' for integer
        
        // Execute the query
        $stmt->execute();
        
        // Get the result
        $result = $stmt->get_result();

        // Check if data is retrieved
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc(); // Fetch the result as an associative array
        } else {
            echo "No record found!";
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error preparing the SQL statement: " . $conn->error;
    }
} else {
    echo "Checklist Type or Checklist No is missing!";
}

// Don't close the connection here; leave it open for further use
// $conn->close(); 

// Use global to make $row available to the including script
global $row;

if (!empty($checklist_no)) {
    ?>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const selector = 'input[name="report_no"], input[name="header_client_name"], input[name="inspected_by"]';
        document.querySelectorAll(selector).forEach(function(el) {
            el.setAttribute("readonly", "readonly");
            el.style.backgroundColor = "#e9ecef";
            el.style.cursor = "not-allowed";
            el.addEventListener("keydown", function(e) {
                e.preventDefault();
            });
        });
    });
    </script>
    <?php
}
?>
