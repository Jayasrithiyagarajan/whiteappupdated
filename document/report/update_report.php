<?php
include_once('../../inc/function.php');
include_once('../../file/config.php');
session_start(); // if not already handled in function.php

// Check session or login status
if (!isset($_SESSION['user_id'])) {
    die("Session expired. Please log in again.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $report_no = mysqli_real_escape_string($conn, $_POST['report_no']);
    $model = mysqli_real_escape_string($conn, $_POST['model']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $prev_sticker_no = mysqli_real_escape_string($conn, $_POST['prev_sticker_no']);
    $issued_company = mysqli_real_escape_string($conn, $_POST['issued_company']);
    $next_inspection_due_date = mysqli_real_escape_string($conn, $_POST['next_inspection_due_date']);
    $inspection_status = mysqli_real_escape_string($conn, $_POST['inspection_status']);
    $deficiency = mysqli_real_escape_string($conn, $_POST['deficiency']);
    $corrective_action = mysqli_real_escape_string($conn, $_POST['corrective_action']);

    $update_query = "
        UPDATE reports SET 
            model = '$model',
            type = '$type',
            prev_sticker_no = '$prev_sticker_no',
            issued_company = '$issued_company',
            next_inspection_due_date = '$next_inspection_due_date',
            inspection_status = '$inspection_status',
            deficiency = '$deficiency',
            corrective_action = '$corrective_action'
        WHERE report_no = '$report_no'
    ";

    if (mysqli_query($conn, $update_query)) {
        header("Location: index.php"); // optionally redirect
    } else {
        echo "Error updating report: " . mysqli_error($conn);
    }
}
?>