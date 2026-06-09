<?php
$conn = new mysqli("localhost","root","","3rdparty");
$conn->set_charset("utf8mb4");

function show_cols($conn, $table) {
    echo "TABLE: $table\n";
    $r = $conn->query("SHOW COLUMNS FROM $table");
    if($r) {
        while($col = $r->fetch_assoc()){
            echo "  " . $col['Field'] . " - " . $col['Type'] . "\n";
        }
    } else {
        echo "  Error: " . $conn->error . "\n";
    }
    echo "\n";
}

show_cols($conn, 'stickers');
show_cols($conn, 'reports');
?>
