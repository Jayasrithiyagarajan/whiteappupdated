<?php
// Enable all error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load PHPMailer
require '../vendor/autoload.php';

// Verify includes
$functionFile = '../inc/function.php';
$configFile = '../file/config.php';

if (!file_exists($functionFile)) {
    die("Error: functions.php not found at $functionFile");
}

if (!file_exists($configFile)) {
    die("Error: config.php not found at $configFile");
}

include_once($functionFile);
include_once($configFile);

// Verify database connection
if (!isset($conn) || !($conn instanceof mysqli)) {
    die("Database connection not properly initialized");
}

// Fetch all users from the database
$users = [];
$query = "SELECT id, username, email, mobile FROM new_users";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

if ($result === false) {
    die("Database error: " . $conn->error);
}

while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

// Initialize variables for selected user
$selectedEmail = '';
$selectedMobile = '';
$selectedUserId = '';
$selectedUserName = '';
$successMessage = '';
$errorMessage = '';

// Handle form submission or selection change
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedUserId = $_POST['user_id'] ?? '';
    
    // Find the selected user
    foreach ($users as $user) {
        if ($user['id'] == $selectedUserId) {
            $selectedEmail = $user['email'];
            $selectedMobile = $user['mobile'];
            $selectedUserName = $user['username'];
            break;
        }
    }
    
    // If the form was submitted (not just user selection change)
    if (isset($_POST['message'])) {
        // Get form data
        $email = $_POST['email'] ?? $selectedEmail;
        $mobile = $_POST['mobile'] ?? $selectedMobile;
        $message = $_POST['message'] ?? '';
        $userName = $selectedUserName;
        
        // Validate data
        if (empty($email) || empty($message)) {
            $errorMessage = "Email and Message are required fields.";
        } else {
            try {
                $mail = new PHPMailer\PHPMailer\PHPMailer(true);
                
                // Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'your@gmail.com'; // Your Gmail
                $mail->Password = 'your-app-password'; // App password if 2FA enabled
                $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
                
                // Recipients
                $mail->setFrom($email, $userName);
                $mail->addAddress('recipient@example.com'); // Where emails should go
                
                // Content
                $mail->isHTML(false);
                $mail->Subject = "New Contact Form Submission from $userName";
                $mail->Body = "You have received a new contact form submission:\n\n" .
                             "Name: $userName\n" .
                             "Email: $email\n" .
                             "Phone: $mobile\n" .
                             "Message:\n$message\n";
                
                $mail->send();
                $successMessage = "Thank you for your message. We'll get back to you soon!";
                // Clear form fields if needed
                $selectedUserId = '';
                $selectedEmail = '';
                $selectedMobile = '';
            } catch (Exception $e) {
                $errorMessage = "There was a problem sending your message. Error: " . $mail->ErrorInfo;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <style>
        /* body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            line-height: 1.6;
            color: #333;
        } */
        /* h1 {
            font-size: 24px;
            margin-bottom: 20px;
            font-weight: normal;
        } */
        .contact-info {
            margin-bottom: 30px;
        }
        .contact-method {
            margin-bottom: 15px;
        }
        .address {
            margin-top: 25px;
            position: relative;
            padding-left: 25px;
        }
        .address:before {
            content: "✔️";
            position: absolute;
            left: 0;
        }
        hr {
            border: none;
            border-top: 1px solid #ddd;
            margin: 25px 0;
        }
        .form-section {
            margin-bottom: 25px;
        }
        .form-section h2 {
            font-size: 16px;
            margin-bottom: 8px;
            font-weight: normal;
        }
        .form-field {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
            width: 100%;
            box-sizing: border-box;
            font-size: 14px;
            border-radius: 3px;
        }
        select.form-field {
            height: 42px;
            background-color: white;
        }
        .submit-btn {
            background-color: #000;
            color: #fff;
            border: none;
            padding: 12px 25px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 3px;
            width: 100%;
        }
        .form-field:focus {
            outline: none;
            border-color: #666;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }
        .alert-success {
            color: #3c763d;
            background-color: #dff0d8;
            border-color: #d6e9c6;
        }
        .alert-danger {
            color: #a94442;
            background-color: #f2dede;
            border-color: #ebccd1;
        }
    </style>
</head>
<body>
<div class="main-content d-flex flex-column flex-md-row">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <!-- Invoice Header -->
                    <div class="invoice-details-header bg-white mb-30 justify-content-between">

                    <h2 class="mb-30 mt-10">
                        CONTACT FORM
                    </h2>
                        


                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" id="contactForm">
        <div class="form-section">
            <h2>Name</h2>            
            <select class="form-field" name="user_id" id="user_id" onchange="fetchUserDetails()" required>
                <option value="">Select a user</option>
                <?php foreach ($users as $user): ?>
                    <option value="<?= htmlspecialchars($user['id']) ?>" 
                        <?= ($user['id'] == $selectedUserId) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($user['username']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-section">
            <h2>Email</h2>
            <input type="email" class="form-field" name="email" placeholder="example@email.com" 
                   value="<?= htmlspecialchars($selectedEmail) ?>" required>
        </div>
        
        <div class="form-section">
            <h2>Phone (optional)</h2>
            <input type="tel" class="form-field" name="mobile" placeholder="xxx-xxx-xxxx" 
                   value="<?= htmlspecialchars($selectedMobile) ?>">
        </div>
        
        <div class="form-section">
            <h2>Message</h2>
            <textarea class="form-field" name="message" rows="4" placeholder="Type your message ..." required></textarea>
        </div>
        
        <button type="submit" class="submit-btn">Submit</button>
    </form>
                    </div>
                    </div>
                    </div>
                    </div>
                    </div>


    <script>
        function fetchUserDetails() {
            const userId = document.getElementById('user_id').value;
            if (!userId) return;
            document.getElementById('contactForm').submit();
        }
    </script>
</body>
</html>


<?php include_once('../inc/footer.php'); ?>