<?php
include ('../file/config.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Cast to integer for security

    if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
        // Get inspector_id before deletion
        $query = "SELECT inspector_id FROM inspectors WHERE id = $id";
        $result = $conn->query($query);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $inspector_id = $conn->real_escape_string($row['inspector_id']);

            // Delete from inspectors table
            $sql = "DELETE FROM inspectors WHERE id = $id";

            if ($conn->query($sql) === TRUE) {
                // Delete from new_users table using user_id = inspector_id
                $deleteUserSql = "DELETE FROM new_users WHERE user_id = '$inspector_id'";
                $conn->query($deleteUserSql);

                header("Location: all-inspector.php");
                exit();
            } else {
                echo "Error deleting inspector: " . $conn->error;
            }
        } else {
            echo "Inspector not found.";
        }
    } else {
        // Ask for confirmation
        echo "
        <script>
            if (confirm('Are you sure you want to delete this inspector?')) {
                window.location.href = '?id=$id&confirm=yes';
            } else {
                window.location.href = 'all-inspector.php';
            }
        </script>";
    }
} else {
    echo "Invalid request.";
}
$conn->close();
?>