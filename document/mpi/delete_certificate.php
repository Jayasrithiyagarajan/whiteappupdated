<?php
include_once('../../file/config.php'); // Include database connection

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set JSON header
header('Content-Type: application/json');

// Decode input data
$data = json_decode(file_get_contents('php://input'), true);

// Check for project_no in input
if (isset($data['project_no'])) {
    $project_no = $data['project_no'];

    // Fetch the certificate record
    $query = "SELECT id FROM mpi_certificates WHERE project_no = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $project_no);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $certificate_id = $row['id'];

        // Delete associated images
        $imageQuery = "SELECT image_path FROM mpi_images WHERE certificate_id = ?";
        $imageStmt = $conn->prepare($imageQuery);
        $imageStmt->bind_param('i', $certificate_id);
        $imageStmt->execute();
        $imageResult = $imageStmt->get_result();

        while ($imageRow = $imageResult->fetch_assoc()) {
            $imagePath = $imageRow['image_path'];
            if (file_exists($imagePath)) {
                if (!unlink($imagePath)) {
                    echo json_encode(['success' => false, 'message' => 'Failed to delete image file: ' . $imagePath]);
                    exit;
                }
            }
        }

        // Delete image records
        $deleteImageQuery = "DELETE FROM mpi_images WHERE certificate_id = ?";
        $deleteImageStmt = $conn->prepare($deleteImageQuery);
        $deleteImageStmt->bind_param('i', $certificate_id);
        $deleteImageStmt->execute();

        // Delete the certificate record
        $deleteCertQuery = "DELETE FROM mpi_certificates WHERE project_no = ?";
        $deleteCertStmt = $conn->prepare($deleteCertQuery);
        $deleteCertStmt->bind_param('s', $project_no);
        $deleteCertStmt->execute();

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Certificate not found!']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request!']);
}

$conn->close();
?>
