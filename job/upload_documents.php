<?php
include '../file/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Validate required fields
if (empty($_POST['project_no'])) {
    echo json_encode(['success' => false, 'message' => 'Project number is required']);
    exit;
}

$project_no = $_POST['project_no'];
$uploaded_by = $_POST['uploaded_by'] ?? 'Unknown';
$maxFileSize = 5 * 1024 * 1024; // 5MB
$allowedTypes = [
    'image/jpeg',
    'image/png',
    'application/pdf',
    'image/jpg'
];
$uploadDir = '../uploads/' . $project_no . '/'; // Added ../ to ensure correct path

// Create directory if not exists
if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0777, true)) {
        echo json_encode(['success' => false, 'message' => 'Failed to create upload directory']);
        exit;
    }
}

// Initialize arrays for both stored and original filenames
$filePaths = array_fill(1, 10, null);
$originalNames = array_fill(1, 10, null);
$uploadedCount = 0;

// Process uploaded files
foreach ($_FILES['documents']['tmp_name'] as $key => $tmpName) {
    if ($key >= 10) break; // Limit to 10 files
    
    if ($_FILES['documents']['error'][$key] !== UPLOAD_ERR_OK) {
        continue; // Skip files with upload errors
    }

    $originalName = $_FILES['documents']['name'][$key];
    $fileType = $_FILES['documents']['type'][$key];
    $fileSize = $_FILES['documents']['size'][$key];
    
    // Validate file type
    if (!in_array($fileType, $allowedTypes)) {
        continue;
    }
    
    // Validate file size
    if ($fileSize > $maxFileSize) {
        continue;
    }
    
    // Sanitize file name for storage
    $fileExt = pathinfo($originalName, PATHINFO_EXTENSION);
    $fileName = pathinfo($originalName, PATHINFO_FILENAME);
    
    // Remove special characters but keep basic ones
    $safeName = preg_replace("/[^a-zA-Z0-9\-_]/", "", $fileName) . '_' . bin2hex(random_bytes(4)) . '.' . $fileExt;
    $filePath = $uploadDir . $safeName;
    
    if (move_uploaded_file($tmpName, $filePath)) {
        $filePaths[$key + 1] = $safeName;  // Stored filename
        $originalNames[$key + 1] = $originalName;  // Original filename
        $uploadedCount++;
    }
}

// Prepare SQL columns and values - now including original filenames
$columns = [];
$fileValues = [];
$originalValues = [];
$placeholders = [];

for ($i = 1; $i <= 10; $i++) {
    $columns[] = "file_$i";
    $columns[] = "original_filename_$i";  // Assuming you have these columns
    $placeholders[] = "?";
    $placeholders[] = "?";
    $fileValues[] = $filePaths[$i] ?? null;
    $originalValues[] = $originalNames[$i] ?? null;
}

$columnsStr = implode(', ', $columns);
$placeholdersStr = implode(', ', $placeholders);

// Insert into database
$sql = "INSERT INTO documents (project_no, $columnsStr, uploaded_by, uploaded_at) 
        VALUES (?, $placeholdersStr, ?, NOW())";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
    exit;
}

// Bind parameters (project_no + 10 files + 10 original names + uploaded_by)
$types = 's' . str_repeat('ss', 10) . 's'; // s for string for each value
$params = array_merge([$project_no], array_merge_recursive(array_map(null, $fileValues, $originalValues)), [$uploaded_by]);
$flatParams = [];
array_walk_recursive($params, function($a) use (&$flatParams) { $flatParams[] = $a; });

$stmt->bind_param($types, ...$flatParams);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'message' => 'Files uploaded successfully',
        'uploaded_count' => $uploadedCount
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>