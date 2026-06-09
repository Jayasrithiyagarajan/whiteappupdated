<?php
/**
 * Database Index Creation Script
 * This script adds the missing index on reports.project_no to improve pagination performance
 */

include_once('../../file/config.php');

echo "<!DOCTYPE html>
<html>
<head>
    <title>Add Database Index</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        .success { background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .error { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .info { background: #d1ecf1; color: #0c5460; padding: 15px; border-radius: 5px; margin: 10px 0; }
        pre { background: #f4f4f4; padding: 10px; border-radius: 5px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>Database Index Creation</h1>
";

// Check if index already exists
$checkSql = "SELECT COUNT(*) as count 
             FROM information_schema.statistics 
             WHERE table_schema = '3rdparty' 
               AND table_name = 'reports' 
               AND index_name = 'idx_project_no'";

$result = $conn->query($checkSql);
$row = $result->fetch_assoc();

if ($row['count'] > 0) {
    echo "<div class='info'><strong>Index Already Exists!</strong><br>The index 'idx_project_no' on reports.project_no already exists. No action needed.</div>";
} else {
    echo "<div class='info'><strong>Creating Index...</strong><br>Adding index 'idx_project_no' on reports.project_no</div>";
    
    // Create the index
    $createSql = "CREATE INDEX idx_project_no ON reports(project_no)";
    
    if ($conn->query($createSql) === TRUE) {
        echo "<div class='success'>
                <strong>✓ SUCCESS!</strong><br>
                Index created successfully on reports.project_no<br><br>
                <strong>Expected Performance Improvement:</strong><br>
                • Pagination should now be 10-100x faster<br>
                • Page transitions should be nearly instant (&lt; 100ms)<br><br>
                <strong>Next Step:</strong> Go to the checklist page and test pagination speed.
              </div>";
    } else {
        echo "<div class='error'>
                <strong>✗ ERROR!</strong><br>
                Failed to create index: " . $conn->error . "
              </div>";
    }
}

// Show current indexes on reports table
echo "<h2>Current Indexes on 'reports' Table</h2>";
$indexSql = "SHOW INDEX FROM reports";
$result = $conn->query($indexSql);

echo "<pre>";
echo str_pad("Key Name", 25) . str_pad("Column", 20) . str_pad("Type", 15) . "\n";
echo str_repeat("-", 60) . "\n";

while ($row = $result->fetch_assoc()) {
    echo str_pad($row['Key_name'], 25) . 
         str_pad($row['Column_name'], 20) . 
         str_pad($row['Non_unique'] == 0 ? 'UNIQUE' : 'INDEX', 15) . "\n";
}
echo "</pre>";

$conn->close();

echo "
    <div class='info'>
        <strong>What This Does:</strong><br>
        This index allows MySQL to quickly find matching records when joining the 'reports' table 
        with 'checklist_information' on the project_no column, instead of scanning all 6,484 rows.
    </div>
</body>
</html>";
?>
