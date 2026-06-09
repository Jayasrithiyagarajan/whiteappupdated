<?php
session_start();
require './config.php'; // Include your database connection script

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if user exists
    $stmt = $conn->prepare("SELECT id, password, role FROM new_users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $hashed_password, $role); // Fetch role
        $stmt->fetch();

        // Verify password
        if (password_verify($password, $hashed_password)) {
            // Create a unique session ID
            $session_id = session_create_id();

            // Save session information in the database, including the role
            $stmt = $conn->prepare("INSERT INTO user_sessions (session_id, user_id, user_agent, ip_address, login_time, role, username) VALUES (?, ?, ?, ?, NOW(), ?, ?)");
            $stmt->bind_param("sissss", $session_id, $user_id, $_SERVER['HTTP_USER_AGENT'], $_SERVER['REMOTE_ADDR'], $role, $username);
            $stmt->execute();
            
            // Get the auto-incremented ID of the newly inserted session (if you have an auto-increment id column)
            $session_db_id = $conn->insert_id;

            // Set session variables
            $_SESSION['session_id'] = $session_id;
            // $_SESSION['id'] = $uid;
            $_SESSION['user_id'] = $user_id;
            $_SESSION['role'] = $role; // Store role in session
            $_SESSION['username'] = $username;
            $_SESSION['session_db_id'] = $session_db_id; // Store the database ID of the session if needed

            // Redirect based on role
            switch ($role) {
                case 'admin':
                    header("Location: ../dashboard/index.php");
                    break;
                case 'customer':
                    header("Location: ../dashboard/customer_new.php");
                    break;
                case 'inspector':
                    header("Location: ../dashboard/inspector.php");
                    break;
                case 'document controller':
                    header("Location: ../dashboard/document controller.php");
                    break;
                case 'reviewer':
                    header("Location: ../dashboard/reviewer.php");
                    break;    
                case 'quality controller':
                    header("Location: ../dashboard/quality controller.php");
                    break;
                case 'certified':
                    header("Location: ../dashboard/certified.php");
                    break;
                default:
                    // Default redirect if role is not recognized
                    header("Location: ../index.php");
                    break;
            }
            exit();
        } else {
            echo "Invalid username or password.";
        }
    } else {
        echo "Invalid username or password.";
    }
}
?>