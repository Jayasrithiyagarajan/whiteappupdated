<?php
include 'file/config.php';

$queries = [
    "ALTER TABLE customers ADD COLUMN date_of_adding DATE AFTER info_correct",
    "ALTER TABLE customers ADD COLUMN reference_by VARCHAR(255) AFTER date_of_adding",
    "ALTER TABLE customers ADD COLUMN notes TEXT AFTER reference_by",
    "ALTER TABLE new_users ADD COLUMN date_of_adding DATE AFTER profile_photo",
    "ALTER TABLE new_users ADD COLUMN reference_by VARCHAR(255) AFTER date_of_adding",
    "ALTER TABLE new_users ADD COLUMN notes TEXT AFTER reference_by"
];

foreach ($queries as $query) {
    if ($conn->query($query)) {
        echo "Success: $query\n";
    } else {
        echo "Error: " . $conn->error . " (Query: $query)\n";
    }
}
?>
