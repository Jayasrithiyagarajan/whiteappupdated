<?php
session_start();
include '../file/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_assessment'])) {
    $assessment_no = $_POST['assessment_no'];
    $date = $_POST['date'];
    $operator_name = mysqli_real_escape_string($conn, $_POST['operator_name']);
    $operator_id_passport = mysqli_real_escape_string($conn, $_POST['operator_id_passport']);
    $client_id = $_POST['client_id'];
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $operating_location = $_POST['operating_location'];
    $training_program = mysqli_real_escape_string($conn, $_POST['training_program']);
    $no_of_equipment = (int)$_POST['no_of_equipment'];
    $inspector_id = $_POST['inspector_id'];
    $created_by = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    // Insert into operator_assessments table
    $sql = "INSERT INTO operator_assessments 
            (assessment_no, date, operator_name, operator_id_passport, client_id, location, 
             operating_location, training_program, no_of_equipment, inspector_id, status, created_by) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'PENDING', ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssi", 
        $assessment_no, 
        $date, 
        $operator_name, 
        $operator_id_passport, 
        $client_id, 
        $location, 
        $operating_location, 
        $training_program,
        $no_of_equipment, 
        $inspector_id, 
        $created_by
    );

    if ($stmt->execute()) {
        $_SESSION['success_msg'] = "Operator Assessment created successfully! Assessment No: " . $assessment_no;
        header("Location: assessment-list.php");
        exit();
    } else {
        $_SESSION['error_msg'] = "Error creating assessment: " . $conn->error;
        header("Location: create-assessment.php");
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: create-assessment.php");
    exit();
}
?>
