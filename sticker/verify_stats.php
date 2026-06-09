<?php
$conn = new mysqli("localhost","root","","3rdparty");
$conn->set_charset("utf8mb4");

$today = date('Y-m-d');

function getCount($conn, $query) {
    $res = $conn->query($query);
    if (!$res) return "Error: " . $conn->error;
    $row = $res->fetch_assoc();
    return $row['cnt'] ?? 0;
}

// TOTAL
$totalWhite = getCount($conn, "SELECT COUNT(*) as cnt FROM stickers WHERE sticker_status = 'Passed'");
$totalRed = getCount($conn, "SELECT COUNT(*) as cnt FROM stickers WHERE sticker_status = 'Failed'");

// ACTIVE (Has project_no AND expiry_date >= today)
$activeWhite = getCount($conn, "SELECT COUNT(*) as cnt FROM stickers WHERE (project_no IS NOT NULL AND project_no != '') AND sticker_status = 'Passed' AND expiry_date >= '$today'");
$activeRed = getCount($conn, "SELECT COUNT(*) as cnt FROM stickers WHERE (project_no IS NOT NULL AND project_no != '') AND sticker_status = 'Failed' AND expiry_date >= '$today'");

// IN HAND (No project_no)
$inHandWhite = getCount($conn, "SELECT COUNT(*) as cnt FROM stickers WHERE (project_no IS NULL OR project_no = '') AND sticker_status = 'Passed'");
$inHandRed = getCount($conn, "SELECT COUNT(*) as cnt FROM stickers WHERE (project_no IS NULL OR project_no = '') AND sticker_status = 'Failed'");

echo "RESULTS:\n";
echo "Total White: $totalWhite\n";
echo "Total Red: $totalRed\n";
echo "Active White: $activeWhite\n";
echo "Active Red: $activeRed\n";
echo "In Hand White: $inHandWhite\n";
echo "In Hand Red: $inHandRed\n";
?>
