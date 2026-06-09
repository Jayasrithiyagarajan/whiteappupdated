<?php
session_start();
$_SESSION['username'] = 'admin'; // bypass auth check
// Mock $_GET for "All Time"
$_GET['date_from'] = '';
$_GET['date_to'] = '';
$_GET['inspector'] = '';

// Capture output
ob_start();
include 'fetch_admin_kpi_data.php';
$output = ob_get_clean();

$data = json_decode($output, true);

echo "--- VERIFICATION: DEFAULT (ALL TIME) ---\n";
if (isset($data['certificates'])) {
    foreach ($data['certificates']['labels'] as $i => $label) {
        echo $label . ": " . $data['certificates']['data'][$i] . "\n";
    }
} else {
    echo "FAILED: No certificate data found.\n";
    echo "Output: " . $output . "\n";
}
?>
