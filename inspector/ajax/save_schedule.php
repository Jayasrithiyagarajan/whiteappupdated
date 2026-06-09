<?php
include_once('../../file/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $inspector_id = $_POST['inspector_id'];
    $customer_id = $_POST['customer_id'];
    $schedule_type = $_POST['schedule_type'];
    $priority = $_POST['priority'] ?? 'medium';
    $start_datetime = $_POST['start_datetime'];
    $end_datetime = $_POST['end_datetime'];
    $location = $_POST['location'];
    $description = $_POST['description'] ?? '';
    $equipment = $_POST['equipment'] ?? '';
    $id = $_POST['id'] ?? null;

    // Get customer name
    $cus_query = mysqli_query($conn, "SELECT customer_name FROM customers WHERE id = '$customer_id'");
    $cus_data = mysqli_fetch_assoc($cus_query);
    $customer_name = $cus_data['customer_name'] ?? 'Unknown';

    if ($id) {
        $sql = "UPDATE inspector_schedules SET 
                inspector_id = '$inspector_id', 
                customer_id = '$customer_id', 
                customer_name = '$customer_name', 
                schedule_type = '$schedule_type', 
                priority = '$priority', 
                start_datetime = '$start_datetime', 
                end_datetime = '$end_datetime', 
                location = '$location', 
                description = '$description', 
                equipment = '$equipment'
                WHERE id = '$id'";
    } else {
        $sql = "INSERT INTO inspector_schedules 
                (inspector_id, customer_id, customer_name, schedule_type, priority, start_datetime, end_datetime, location, description, equipment) 
                VALUES 
                ('$inspector_id', '$customer_id', '$customer_name', '$schedule_type', '$priority', '$start_datetime', '$end_datetime', '$location', '$description', '$equipment')";
    }

    if (mysqli_query($conn, $sql)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
    }
}
?>
