<?php
// Quick diagnostic script to check query performance
include_once('../../file/config.php');

// Check table sizes
echo "=== TABLE SIZES ===\n";
$tables = ['checklist_information', 'reports', 'project_info'];
foreach ($tables as $table) {
    $res = $conn->query("SELECT COUNT(*) as cnt FROM $table");
    $count = $res->fetch_assoc()['cnt'];
    echo "$table: $count rows\n";
}

echo "\n=== INDEXES ===\n";
foreach ($tables as $table) {
    echo "\n$table:\n";
    $res = $conn->query("SHOW INDEX FROM $table");
    while ($row = $res->fetch_assoc()) {
        echo "  {$row['Key_name']} on {$row['Column_name']}\n";
    }
}

echo "\n=== QUERY EXECUTION TIME TEST ===\n";

// Test the current query
$start = microtime(true);
$sql = "
SELECT ci.*, pi.project_status, r.next_inspection_due_date
FROM checklist_information ci
LEFT JOIN reports r ON ci.project_no = r.project_no
LEFT JOIN project_info pi ON ci.project_no = pi.project_no
ORDER BY ci.created_at DESC
LIMIT 0,10
";
$res = $conn->query($sql);
$time1 = microtime(true) - $start;
echo "Current query (with JOINs): " . number_format($time1, 4) . " seconds\n";

// Test without JOINs
$start = microtime(true);
$sql = "
SELECT ci.*
FROM checklist_information ci
ORDER BY ci.created_at DESC
LIMIT 0,10
";
$res = $conn->query($sql);
$time2 = microtime(true) - $start;
echo "Query without JOINs: " . number_format($time2, 4) . " seconds\n";
echo "Difference: " . number_format(($time1 - $time2) * 1000, 2) . " ms\n";

// Check if project_no has index
echo "\n=== EXPLAIN CURRENT QUERY ===\n";
$sql = "
EXPLAIN SELECT ci.*, pi.project_status, r.next_inspection_due_date
FROM checklist_information ci
LEFT JOIN reports r ON ci.project_no = r.project_no
LEFT JOIN project_info pi ON ci.project_no = pi.project_no
ORDER BY ci.created_at DESC
LIMIT 0,10
";
$res = $conn->query($sql);
while ($row = $res->fetch_assoc()) {
    print_r($row);
}
