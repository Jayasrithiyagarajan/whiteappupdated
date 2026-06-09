<?php
include_once('../../file/config.php');

$project_no = $_GET['project_no'] ?? die("Project number required");

// Get the highest base certificate number
$query = "SELECT certificate_no FROM lifting_gear_certificates 
          WHERE project_no = ? AND certificate_no REGEXP '^CLC-[0-9]+-[0-9]{4}$'
          ORDER BY certificate_no DESC LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $project_no);
$stmt->execute();
$result = $stmt->get_result();

$year = date('Y');
if ($result->num_rows > 0) {
    $lastCert = $result->fetch_assoc()['certificate_no'];
    $parts = explode('-', $lastCert);
    $nextNum = (int)$parts[1] + 1;
} else {
    $nextNum = 1;
}

header('Content-Type: text/plain');
echo sprintf("CLC-%03d-%s", $nextNum, $year);
?>