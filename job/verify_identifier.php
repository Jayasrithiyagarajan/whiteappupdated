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
    // Check sticker status in the database
    $query = "SELECT status FROM stickers WHERE sticker_start_no = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $identifier);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['status'] === 'active') {
            $response['valid'] = true;
        } else {
            $response['message'] = 'Your sticker number is not active. Kindly contact admin.';
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