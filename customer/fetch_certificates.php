<?php
session_start();
include_once('../file/config.php');

// Check if the user is logged in and is a customer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

if (isset($_POST['project_no'])) {
    $project_no = $_POST['project_no'];
    $customer_username = $_SESSION['username']; // Get customer username from session
    
    // Verify that this project belongs to the logged-in customer using username
    $verify_sql = "SELECT p.project_no 
                   FROM project_info p 
                   WHERE p.project_no = ? AND p.customer_name = ?";
    $stmt = $conn->prepare($verify_sql);
    $stmt->bind_param("ss", $project_no, $customer_username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode([
            'success' => false, 
            'message' => 'Project not found or access denied',
            'debug' => [
                'project_no' => $project_no,
                'customer_username' => $customer_username
            ]
        ]);
        exit();
    }
    
    // Query to fetch all certificates for this project
    $query = "
        SELECT 
            'healthcheck' AS certificate_type,
            hc.certificate_no,
            hc.created_at
        FROM crane_health_check_certificate hc
        WHERE hc.project_no = ?        
    
        UNION
    
        SELECT 
            'loadtestwithload' AS certificate_type,
            lw.certificate_no,
            lw.created_at
        FROM loadtest_certificate lw
        WHERE lw.project_no = ?
    
        UNION
    
        SELECT 
            'mobile' AS certificate_type,
            mc.certificate_no,
            mc.created_at
        FROM mobile_crane_loadtest mc
        WHERE mc.project_no = ?
    
        UNION
    
        SELECT 
            'lifting' AS certificate_type,
            lc.certificate_no,
            lc.created_at
        FROM lifting_gear_certificates lc
        WHERE lc.project_no = ?
    
        UNION
    
        SELECT 
            'mpi' AS certificate_type,
            mp.certificate_no,
            mp.created_at
        FROM mpi_certificates mp
        WHERE mp.project_no = ?
        
        UNION
    
        SELECT 
            'eddycurrent' AS certificate_type,
            ec.certificate_no,
            ec.created_at
        FROM eddy_current_inspection ec
        WHERE ec.project_no = ?
    
        UNION
    
        SELECT 
            'liquidpenetrantinspection' AS certificate_type,
            lpi.certificate_no,
            lpi.created_at
        FROM liquid_penetrant_inspection lpi
        WHERE lpi.project_no = ?
    
        UNION
    
        SELECT 
            'rocktest' AS certificate_type,
            rt.certificate_no,
            rt.created_at
        FROM rocking_test_certificate rt
        WHERE rt.project_no = ?
        
        UNION
    
        SELECT 
            'withloadtest' AS certificate_type,
            lt.certificate_no,
            lt.created_at
        FROM withload lt
        WHERE lt.project_no = ?

        UNION
    
        SELECT 
            'lmi' AS certificate_type,
            lmi.certificate_no,
            lmi.created_at
        FROM lmi_certificates lmi
        WHERE lmi.project_no = ?
    ";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssssssss", $project_no, $project_no, $project_no, $project_no, 
                     $project_no, $project_no, $project_no, $project_no, $project_no, $project_no);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $certificates = [];
    while ($row = $result->fetch_assoc()) {
        $certificates[] = $row;
    }
    
    echo json_encode([
        'success' => true,
        'certificates' => $certificates,
        'debug' => [
            'project_no' => $project_no,
            'customer_username' => $customer_username
        ]
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Project number not provided']);
}
?>