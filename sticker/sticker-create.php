<?php
include_once('../file/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture and sanitize form data
    $sticker_start_no = $_POST['sticker_start_no']; // Ensure it's treated as a number
    $assign_inspector = htmlspecialchars($_POST['assign_inspector']);
    $no_of_stickers = (int) $_POST['no_of_stickers'];

    // Variable for single sticker count
    $single_sticker_count = 1;

    // Initialize a counter for successfully created stickers
    $created_count = 0;

    // Start a loop to create separate entries for each sticker
    for ($i = 0; $i < $no_of_stickers; $i++) {
        $current_sticker_no = $sticker_start_no + $i; // Ensure unique sticker numbers

        // Check if the sticker number already exists
        $check_sql = "SELECT 1 FROM stickers WHERE sticker_start_no = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("i", $current_sticker_no);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            // If the sticker number exists, skip this number
            echo "Sticker number $current_sticker_no already exists. Skipping.<br>";
            $check_stmt->close();
            continue;
        }
        $check_stmt->close();

        // Prepare an SQL insert statement
        $sql = "INSERT INTO stickers (sticker_start_no, assign_inspector, no_of_stickers) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Preparation failed: " . $conn->error);
        }

        // Bind parameters
        $stmt->bind_param("isi", $current_sticker_no, $assign_inspector, $single_sticker_count);

        // Execute and check if the data was saved
        if ($stmt->execute()) {
            $created_count++; // Increment the counter if successful
        } else {
            echo "Error inserting sticker number $current_sticker_no: " . $stmt->error . "<br>";
        }

        // Close the insert statement
        $stmt->close();
    }

    // Close the connection
    $conn->close();

    // Redirect to index.php after completion
    header("Location: sticker-list.php");
    exit(); // Ensure script execution stops after redirection
}
?>