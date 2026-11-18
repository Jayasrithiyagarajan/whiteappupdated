<?php
include_once('../file/config.php');

// Get the posted data
$identifier = isset($_POST['identifier']) ? trim($_POST['identifier']) : '';
$type = isset($_POST['type']) ? trim($_POST['type']) : '';

// Initialize response
$response = [
    'valid' => false,
    'message' => ''
];

if (empty($identifier)) {
    $response['message'] = 'Please enter a verification number';
    echo json_encode($response);
    exit;
}

if ($type === 'sticker') {
    // Check sticker status and associated project completion in the database
    $query = "SELECT s.status, s.project_no, p.project_status 
              FROM stickers s 
              LEFT JOIN project_info p ON s.project_no = p.project_no 
              WHERE s.sticker_start_no = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $identifier);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (empty($row['project_no']) || strcasecmp(trim($row['project_status'] ?? ''), 'Completed') !== 0) {
            $response['message'] = 'Project not completed. Contact admin';
        } else {
            $response['valid'] = true;
        }
    } else {
        $response['message'] = 'Sticker number not found.';
    }
} elseif ($type === 'project') {
    // Check project status and equipment type in the database
    $query = "SELECT p.project_status, p.equipment_type 
              FROM project_info p 
              WHERE p.project_no = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $identifier);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['project_status'] !== 'Completed') {
            $response['message'] = 'Project is not yet completed. Only completed projects can be processed.';
        } elseif ($row['equipment_type'] !== 'NDT Equipment') {
            $response['message'] = 'Only NDT Equipment projects can be accessed with project number. Please use sticker number for other equipment types.';
        } else {
            $response['valid'] = true;
        }
    } else {
        $response['message'] = 'Project number not found.';
    }
} else {
    $response['message'] = 'Invalid verification type.';
}

echo json_encode($response);
?>