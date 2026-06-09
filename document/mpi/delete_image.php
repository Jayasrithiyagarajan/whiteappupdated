<?php
include_once('../../file/config.php');  

if (isset($_GET['image_id'])) {
    $image_id = $_GET['image_id'];

    // Fetch image path
    $query = "SELECT image_path FROM mpi_images WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $image_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $imagePath = $row['image_path'];

        // Delete image file
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        // Delete image record from database
        $deleteQuery = "DELETE FROM mpi_images WHERE id = ?";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->bind_param('i', $image_id);
        $deleteStmt->execute();

        echo "Image deleted successfully!";
    } else {
        echo "Image not found!";
    }
} else {
    echo "Invalid request!";
}
?>
