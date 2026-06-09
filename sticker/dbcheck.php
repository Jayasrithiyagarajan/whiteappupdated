<?php
// No session needed
$conn = new mysqli("localhost","root","","3rdparty");
$conn->set_charset("utf8mb4");

// 1. Column info
$r = $conn->query("SHOW COLUMNS FROM stickers");
while($col = $r->fetch_assoc()){
    echo $col['Field'] . " - " . $col['Type'] . "\n";
}

// 2. Grouped values
$r2 = $conn->query("SELECT COALESCE(project_no,'__NULL__') as pno, sticker_status, COUNT(*) cnt FROM stickers GROUP BY project_no, sticker_status LIMIT 30");
echo "GROUPS:\n";
while($row=$r2->fetch_assoc()){
    echo "  project_no=" . var_export($row['pno'],true) . " status=" . var_export($row['sticker_status'],true) . " cnt=" . $row['cnt'] . "\n";
}

// 3. Count null
$r3 = $conn->query("SELECT COUNT(*) cnt FROM stickers WHERE project_no IS NULL");
echo "NULL count: " . $r3->fetch_assoc()['cnt'] . "\n";

// 4. Count empty string
$r4 = $conn->query("SELECT COUNT(*) cnt FROM stickers WHERE project_no = ''");
echo "EMPTY '' count: " . $r4->fetch_assoc()['cnt'] . "\n";

// 5. Count '0'
$r5 = $conn->query("SELECT COUNT(*) cnt FROM stickers WHERE project_no = '0'");
echo "'0' count: " . $r5->fetch_assoc()['cnt'] . "\n";

// 6. Total
$r6 = $conn->query("SELECT COUNT(*) cnt FROM stickers");
echo "TOTAL: " . $r6->fetch_assoc()['cnt'] . "\n";
?>
