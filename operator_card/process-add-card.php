<?php
include_once('../file/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $operator_name = $_POST['operator_name'];
    $certificate_no = $_POST['certificate_no'];
    $id_iqama = $_POST['id_iqama'];
    $company = $_POST['company'];
    $issue_date = $_POST['issue_date'];
    $expiry_date = $_POST['expiry_date'];
    $examiner_name = $_POST['examiner_name'];
    $operating_location = $_POST['operating_location'];
    $operator_designation = $_POST['operator_designation'];
    $equipment_details = json_encode($_POST['equipment_details']);

    // Photo upload handling
    $target_dir = "../uploads/operator_photos/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $photo_name = time() . '_' . basename($_FILES["operator_photo"]["name"]);
    $target_file = $target_dir . $photo_name;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["operator_photo"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["operator_photo"]["tmp_name"], $target_file)) {
            $photo_path = "uploads/operator_photos/" . $photo_name;

            $sql = "INSERT INTO operator_cards (operator_name, certificate_no, id_iqama, company, issue_date, expiry_date, examiner_name, operating_location, operator_designation, equipment_details, photo_path) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssssssss", $operator_name, $certificate_no, $id_iqama, $company, $issue_date, $expiry_date, $examiner_name, $operating_location, $operator_designation, $equipment_details, $photo_path);

            if ($stmt->execute()) {
                $last_id = $conn->insert_id;
                header("Location: view-card.php?id=" . $last_id);
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>
