<?php
include_once('../file/config.php'); // Database connection

// Check if the request is POST and contains project_no
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['project_no'])) {
    $project_no = mysqli_real_escape_string($conn, $_POST['project_no']);

    // Check if the project is marked as 'Completed'
    $check_status_query = "SELECT review_status FROM project_info WHERE project_no = '$project_no'";
    $result = mysqli_query($conn, $check_status_query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $review_status = trim(strtolower($row['review_status'])); // Normalize case

        if ($review_status === 'completed') {
            // Delete notifications for this project
            $delete_query = "DELETE FROM project_notifications WHERE project_no = '$project_no'";
            if (mysqli_query($conn, $delete_query)) {
                echo "success";
            } else {
                echo "error: " . mysqli_error($conn); // Debugging output
            }
        } else {
            echo "not_completed"; // The project is not yet completed
        }
    } else {
        echo "error fetching project status";
    }
} else {
    echo "invalid_request";
}
?>
