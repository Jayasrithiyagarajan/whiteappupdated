<?php
include '../file/config.php';
include_once __DIR__ . '/doc_names.php';
ensure_doc_name_columns($conn);
$docNamesPosted = (isset($_POST['doc_names']) && is_array($_POST['doc_names'])) ? $_POST['doc_names'] : [];

header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (empty($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'Your login session has expired. Please log in again.']);
    exit;
}
if (!in_array($_SESSION['role'] ?? '', ['inspector', 'admin'], true)) {
    echo json_encode(['success' => false, 'message' => 'Only inspectors and admins can upload documents.']);
    exit;
}

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
$uploaded_by = $_SESSION['role'] ?? ($_POST['uploaded_by'] ?? 'Unknown');
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
$docNames = array_fill(1, 10, null);
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
        $docNames[$key + 1] = clean_doc_name($docNamesPosted[$key] ?? '');  // Name typed by the inspector
        $uploadedCount++;
    }
}

// Build the INSERT: project_no, 10 x (file, original name, document name), uploaded_by
$columns = [];
$params  = [$project_no];
for ($i = 1; $i <= 10; $i++) {
    $columns[] = "file_$i";
    $columns[] = "original_filename_$i";
    $columns[] = "doc_name_$i";
    $params[]  = $filePaths[$i] ?? null;
    $params[]  = $originalNames[$i] ?? null;
    $params[]  = $docNames[$i] ?? null;
}
$params[] = $uploaded_by;
$columnsStr = implode(', ', $columns);
$placeholdersStr = implode(', ', array_fill(0, 30, '?'));

$sql = "INSERT INTO documents (project_no, $columnsStr, uploaded_by, uploaded_at)
        VALUES (?, $placeholdersStr, ?, NOW())";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
    exit;
}
$stmt->bind_param(str_repeat('s', count($params)), ...$params);

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