<?php
include_once('../file/config.php');

// Function to generate a unique project number
function generateProjectNumber($conn) {
    $conn->begin_transaction();

    try {
        $lockQuery = "SELECT last_project_no FROM project_counter FOR UPDATE";
        $lockResult = $conn->query($lockQuery);
        $row = $lockResult->fetch_assoc();
        $lastProjectNo = $row['last_project_no'];

        $newProjectNo = $lastProjectNo + 1;

        $updateQuery = "UPDATE project_counter SET last_project_no = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("i", $newProjectNo);
        $stmt->execute();
        $stmt->close();

        $conn->commit();

        // Dynamically pad zeros based on number length
        $padLength = max(3, strlen((string)$newProjectNo));
        return "CIMS" . str_pad($newProjectNo, $padLength, "0", STR_PAD_LEFT);

    } catch (Exception $e) {
        $conn->rollback();
        return generateFallbackProjectNumber($conn);
    }
}

function generateFallbackProjectNumber($conn) {
    $maxQuery = "SELECT MAX(CAST(SUBSTRING(project_no, 5) AS UNSIGNED)) AS max_no FROM project_info";
    $maxResult = $conn->query($maxQuery);
    $row = $maxResult->fetch_assoc();
    $maxNo = $row['max_no'] ? $row['max_no'] : 0;

    $newProjectNo = $maxNo + 1;

    $checkQuery = "SELECT project_no FROM project_info WHERE project_no = ?";
    $stmt = $conn->prepare($checkQuery);
    $projectNo = "CIMS" . str_pad($newProjectNo, max(3, strlen((string)$newProjectNo)), "0", STR_PAD_LEFT);
    $stmt->bind_param("s", $projectNo);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    if ($result->num_rows > 0) {
        return generateFallbackProjectNumber($conn);
    }

    return $projectNo;
}


// Generate the project number
$formattedProjectNo = generateProjectNumber($conn);

header('Content-Type: application/json');
echo json_encode(['project_no' => $formattedProjectNo]);

$conn->close();
?>