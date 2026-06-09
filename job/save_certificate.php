<?php
include_once('../file/config.php'); // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_no = $_POST['project_no'] ?? '';
    $checklist_no = $_POST['checklist_no'] ?? '';
    $report_no = $_POST['report_no'] ?? '';
    $certificate_type = $_POST['certificate_type'] ?? '';

    if (empty($project_no) || empty($checklist_no) || empty($report_no) || empty($certificate_type)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        exit;
    }

    // Insert data into document_controller table
    $stmt = $conn->prepare("INSERT INTO document_controller (project_no, checklist_no, report_no, certificate_type, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssss", $project_no, $checklist_no, $report_no, $certificate_type);

    if ($stmt->execute()) {
        // Define the correct URL to redirect to
        $certificateLinks = [
            'healthcheck' => "../document/health-check/create.php",
            'lifting' => "../document/lifting/create.php",
            'loadtestwithload' => "../document/loadtest/create.php",
            'mobile' => "../document/mobile/create.php",
            'withloadtest' => "../document/withloadtest/create.php",
            'mpi' => "../document/mpi/create.php",
            'eddycurrent' => "../document/eddycurrent/create.php",
            'liquidpenetrantinspection' => "../document/liquid-penetrant-inspection-certificate/create.php",
            'rocktest' => "../document/rocktest/create.php",
            'lmi' => "../document/lmi/create.php",
        ];

        $redirectUrl = isset($certificateLinks[$certificate_type]) 
            ? "{$certificateLinks[$certificate_type]}?project_no=$project_no&checklist_no=$checklist_no&report_no=$report_no"
            : null;

        echo json_encode(['success' => true, 'redirect_url' => $redirectUrl]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
