<?php
/**
 * Eddy Current certificate - update handler (ONE certificate per request, called by edit.php).
 * Returns JSON: {ok: true} or {ok: false, message: "..."}.
 */
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 0);
error_reporting(E_ALL);
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include_once('../../file/config.php');

function out(array $payload, int $code = 200) {
    http_response_code($code);
    echo json_encode($payload);
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    out(['ok' => false, 'message' => 'Invalid request.']);
}
if (empty($_POST) && empty($_FILES) && (int)($_SERVER['CONTENT_LENGTH'] ?? 0) > 0) {
    out(['ok' => false, 'message' => 'The images are too large for the server (post_max_size = '
        . ini_get('post_max_size') . '). Use smaller images, or raise post_max_size and upload_max_filesize in php.ini.']);
}
if (empty($_SESSION['username'])) {
    out(['ok' => false, 'expired' => true, 'message' => 'Your login session has expired. Log in again in another tab, then press Save again - nothing on this page is lost.'], 401);
}
if (!isset($_POST['update_one'])) {
    out(['ok' => false, 'message' => 'Invalid request.']);
}

function post_val($key) {
    return isset($_POST[$key]) ? trim((string)$_POST[$key]) : '';
}

$certificate_no = post_val('certificate_no');
$project_no     = post_val('project_no');
if ($certificate_no === '' || $project_no === '') {
    out(['ok' => false, 'message' => 'Certificate number or project number is missing.']);
}
if (post_val('jrn') === '') {
    out(['ok' => false, 'message' => 'JRN is required.']);
}

// Existing images of this certificate
$stmtOld = $conn->prepare("SELECT image_1, image_2, image_3 FROM eddy_current_inspection WHERE certificate_no = ? AND project_no = ? LIMIT 1");
$stmtOld->bind_param("ss", $certificate_no, $project_no);
$stmtOld->execute();
$stmtOld->bind_result($existing_1, $existing_2, $existing_3);
$found = $stmtOld->fetch();
$stmtOld->close();
if (!$found) {
    out(['ok' => false, 'message' => 'Certificate ' . $certificate_no . ' was not found in project ' . $project_no . '.']);
}

/**
 * Stores a newly chosen image and returns the file name to save. Keeps the existing image when nothing
 * new was chosen; the old file is only removed AFTER the new one is safely stored.
 */
function handleImageUpload($fileInput, $existingFile, $certificate_no, array &$newFiles) {
    $uploadDir = __DIR__ . '/uploads/';
    $n = substr($fileInput, -1);

    if (!isset($_FILES[$fileInput]) || $_FILES[$fileInput]['error'] === UPLOAD_ERR_NO_FILE) {
        return $existingFile;
    }
    if ($_FILES[$fileInput]['error'] !== UPLOAD_ERR_OK) {
        throw new Exception("Image $n could not be uploaded (error " . $_FILES[$fileInput]['error']
            . "). The file may be larger than the server limit of " . ini_get('upload_max_filesize') . ".");
    }

    $ext = strtolower(pathinfo($_FILES[$fileInput]['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true) || @getimagesize($_FILES[$fileInput]['tmp_name']) === false) {
        throw new Exception("Image $n must be a JPG, PNG, GIF or WEBP image.");
    }
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $safe_cert_no = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $certificate_no);
    $newFilename  = $safe_cert_no . '_image' . $n . '.' . $ext;

    if (!move_uploaded_file($_FILES[$fileInput]['tmp_name'], $uploadDir . $newFilename)) {
        throw new Exception("Failed to store image $n.");
    }
    $newFiles[] = [$uploadDir, $newFilename, $existingFile];
    return $newFilename;
}

$newFiles = [];
try {
    $image_1 = handleImageUpload('image_1', $existing_1, $certificate_no, $newFiles);
    $image_2 = handleImageUpload('image_2', $existing_2, $certificate_no, $newFiles);
    $image_3 = handleImageUpload('image_3', $existing_3, $certificate_no, $newFiles);

    $params = [
        post_val('inspection_date'), post_val('report_no'), post_val('jrn'),
        post_val('location'), post_val('next_inspection_date'),
        post_val('customer_name'), post_val('customer_email'), post_val('mobile'),
        post_val('inspector'), post_val('technical_manager'), post_val('quality_controller'), post_val('inspected_item'),
        post_val('type_of_joint'), post_val('inspection_method'), post_val('other_inspection_method'),
        post_val('calibration_details'), post_val('gain'), post_val('probe_frequency'),
        post_val('device_maker'), post_val('model'), post_val('serial_no'), post_val('cable_type'),
        post_val('sensor_type'), post_val('ref_block_type'), post_val('ref_block_type_mm'), post_val('material'),
        $image_1, $image_2, $image_3,
        post_val('description_of_inspection'), post_val('inspection_result'), post_val('reason'), post_val('other_reason'),
        $certificate_no, $project_no,
    ];

    $sql = "UPDATE eddy_current_inspection SET
                inspection_date = ?, report_no = ?, jrn = ?,
                location = ?, next_inspection_date = ?,
                customer_name = ?, customer_email = ?, mobile = ?,
                inspector = ?, technical_manager = ?, quality_controller = ?, inspected_item = ?,
                type_of_joint = ?, inspection_method = ?, other_inspection_method = ?,
                calibration_details = ?, gain = ?, probe_frequency = ?,
                device_maker = ?, model = ?, serial_no = ?, cable_type = ?,
                sensor_type = ?, ref_block_type = ?, ref_block_type_mm = ?, material = ?,
                image_1 = ?, image_2 = ?, image_3 = ?,
                description_of_inspection = ?, inspection_result = ?, reason = ?, other_reason = ?
            WHERE certificate_no = ? AND project_no = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
    if (!$stmt->execute()) {
        throw new Exception('Database error: ' . $stmt->error);
    }
    $stmt->close();

    // Database updated - now remove the files that were replaced
    foreach ($newFiles as [$dir, $name, $old]) {
        if ($old && $old !== $name && is_file($dir . $old)) {
            @unlink($dir . $old);
        }
    }
    out(['ok' => true, 'images' => [$image_1, $image_2, $image_3]]);

} catch (Throwable $e) {
    foreach ($newFiles as [$dir, $name, $old]) {
        if ($old !== $name && is_file($dir . $name)) {
            @unlink($dir . $name);
        }
    }
    error_log('eddy update: ' . $e->getMessage());
    out(['ok' => false, 'message' => $e->getMessage()]);
}
