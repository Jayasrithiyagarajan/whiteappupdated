<?php
include_once('../file/config.php');

if (isset($_GET['inspector_name'])) {
    $inspectorName = $_GET['inspector_name'];

    $query = "SELECT inspector_name, profile_photo, signature_photo FROM inspectors WHERE inspector_name = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $inspectorName);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $inspector = $result->fetch_assoc();

         // Build full paths to the image and signature
         $uploadsFolder = "{$url2}uploads/";
         $profile_photo = $uploadsFolder . $inspector['profile_photo'];
         $signature_photo = $uploadsFolder . $inspector['signature_photo'];
        echo json_encode([
            'success' => true,
            'inspector_name' => $inspector['inspector_name'],
            'profile_photo' => $profile_photo,
            'signature_photo' => $signature_photo
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Inspector details not found.'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Inspector name is required.'
    ]);
}
?>
