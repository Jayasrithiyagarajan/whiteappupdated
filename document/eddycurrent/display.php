<?php
/**
 * Eddy Current certificate - create handler.
 *
 * Saves ONE certificate per request (create.php sends them one after another).
 * Why: a single form with N certificates exceeds PHP limits very quickly
 *   - max_input_vars   (default 1000)  -> later certificates lose fields
 *   - max_file_uploads (default 20)    -> later certificates lose images
 *   - post_max_size    (default 8M)    -> whole request silently dropped
 * and a slow save invited a second click, which inserted every certificate twice.
 *
 * Duplicate protection:
 *   - client_token  : a retry of the same entry returns the first result (per session)
 *   - GET_LOCK      : certificate numbers are allocated one at a time
 *   - number check  : an already-used certificate number is never reused
 */
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 0);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once('../../file/config.php');

function out(array $payload, int $code = 200) {
    http_response_code($code);
    echo json_encode($payload);
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    out(['ok' => false, 'message' => 'Invalid request.'], 405);
}

// Body bigger than post_max_size: PHP drops $_POST and $_FILES without any error.
if (empty($_POST) && empty($_FILES) && (int)($_SERVER['CONTENT_LENGTH'] ?? 0) > 0) {
    out(['ok' => false, 'message' => 'The images are too large for the server (post_max_size = '
        . ini_get('post_max_size') . '). Use smaller images, or raise post_max_size and upload_max_filesize in php.ini.']);
}

if (empty($_SESSION['username'])) {
    out(['ok' => false, 'expired' => true, 'message' => 'Your login session has expired. Log in again in another tab, then press Save again - nothing on this page is lost.'], 401);
}
if (!isset($_POST['save_one'])) {
    out(['ok' => false, 'message' => 'Invalid request.'], 400);
}

function field(string $key): string {
    return isset($_POST[$key]) ? trim((string)$_POST[$key]) : '';
}

/* ---------- Duplicate-submit guard (same entry sent twice) ---------- */
$token = preg_replace('/[^A-Za-z0-9_\-]/', '', field('client_token'));
if ($token !== '' && isset($_SESSION['eddy_saved_tokens'][$token])) {
    out(['ok' => true, 'certificate_no' => $_SESSION['eddy_saved_tokens'][$token], 'duplicate' => true]);
}

/* ---------- Required fields ---------- */
$project_no = field('project_no');
if ($project_no === '') {
    out(['ok' => false, 'message' => 'Project ID is missing.']);
}
if (field('jrn') === '') {
    out(['ok' => false, 'message' => 'JRN is required.']);
}

/* ---------- Image helper ---------- */
function uploadErrorMessage(int $code, string $label): string {
    switch ($code) {
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            return "$label is larger than the server allows (upload_max_filesize = " . ini_get('upload_max_filesize') . ").";
        case UPLOAD_ERR_PARTIAL:
            return "$label was only partly uploaded. Please try again.";
        case UPLOAD_ERR_NO_TMP_DIR:
        case UPLOAD_ERR_CANT_WRITE:
            return "The server could not store $label (temporary folder problem).";
        default:
            return "$label could not be uploaded (error $code).";
    }
}

function saveImage(string $key, string $cert_no, string $suffix, array &$savedFiles): ?string {
    if (!isset($_FILES[$key]) || (int)$_FILES[$key]['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    $label = 'Image ' . substr($suffix, -1);
    if ((int)$_FILES[$key]['error'] !== UPLOAD_ERR_OK) {
        throw new Exception(uploadErrorMessage((int)$_FILES[$key]['error'], $label));
    }

    $ext = strtolower(pathinfo($_FILES[$key]['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true) || @getimagesize($_FILES[$key]['tmp_name']) === false) {
        throw new Exception("$label must be a JPG, PNG, GIF or WEBP image.");
    }

    $dir = __DIR__ . '/uploads/';
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    $filename = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $cert_no) . '_' . $suffix . '.' . $ext;
    if (!move_uploaded_file($_FILES[$key]['tmp_name'], $dir . $filename)) {
        throw new Exception("Failed to store $label.");
    }
    $savedFiles[] = $dir . $filename;
    return $filename;
}

/* ---------- Save (certificate number allocation is serialised) ---------- */
$savedFiles = [];
$lockTaken  = false;
$response   = ['ok' => false, 'message' => 'Unknown error.'];

try {
    $r = $conn->query("SELECT GET_LOCK('eddy_cert_no', 15) AS l");
    $lockTaken = $r && (int)$r->fetch_assoc()['l'] === 1;
    if (!$lockTaken) {
        throw new Exception('Server is busy allocating certificate numbers. Please try again.');
    }

    // Certificate number: keep the one shown on screen unless it is already used.
    $requested = field('certificate_no');
    $year = date('Y');
    if (preg_match('/^CEC-(\d{3,})-(\d{4})-(.+)$/', $requested, $m)) {
        $year = $m[2];
    }

    $exists = $conn->prepare("SELECT 1 FROM eddy_current_inspection WHERE certificate_no = ? LIMIT 1");
    $certNoExists = function (string $no) use ($exists): bool {
        $exists->bind_param('s', $no);
        $exists->execute();
        $exists->store_result();
        $found = $exists->num_rows > 0;
        $exists->free_result();
        return $found;
    };

    $cert_no = $requested;
    if (!preg_match('/^CEC-\d{3,}-\d{4}-.+$/', $cert_no) || $certNoExists($cert_no)) {
        $like = "CEC-%-{$year}-%";
        $stmtMax = $conn->prepare(
            "SELECT COALESCE(MAX(CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(certificate_no, '-', 2), '-', -1) AS UNSIGNED)), 0) AS mx
             FROM eddy_current_inspection WHERE certificate_no LIKE ?"
        );
        $stmtMax->bind_param('s', $like);
        $stmtMax->execute();
        $next = (int)$stmtMax->get_result()->fetch_assoc()['mx'] + 1;
        $stmtMax->close();

        do {
            $cert_no = sprintf('CEC-%03d-%s-%s', $next, $year, $project_no);
            $next++;
        } while ($certNoExists($cert_no));
    }

    $conn->begin_transaction();

    $img_1 = saveImage('image_1', $cert_no, 'image1', $savedFiles);
    $img_2 = saveImage('image_2', $cert_no, 'image2', $savedFiles);
    $img_3 = saveImage('image_3', $cert_no, 'image3', $savedFiles);

    $params = [
        field('inspection_date'), $cert_no, field('report_no'), field('jrn'), $project_no, field('location'), field('next_inspection_date'),
        field('customer_name'), field('customer_email'), field('mobile'), field('inspector'), field('technical_manager'), field('quality_controller'),
        field('inspected_item'), field('type_of_joint'), field('inspection_method'), field('other_inspection_method'), field('calibration_details'),
        field('gain'), field('probe_frequency'), field('device_maker'), field('model'), field('serial_no'), field('cable_type'), field('sensor_type'),
        field('ref_block_type'), field('ref_block_type_mm'), field('material'), $img_1, $img_2, $img_3,
        field('description_of_inspection'), field('inspection_result'), field('reason'), field('other_reason'), date('Y-m-d H:i:s'),
    ];

    $sql = "INSERT INTO eddy_current_inspection (
                inspection_date, certificate_no, report_no, jrn, project_no, location, next_inspection_date,
                customer_name, customer_email, mobile, inspector, technical_manager, quality_controller,
                inspected_item, type_of_joint, inspection_method, other_inspection_method, calibration_details,
                gain, probe_frequency, device_maker, model, serial_no, cable_type, sensor_type,
                ref_block_type, ref_block_type_mm, material, image_1, image_2, image_3,
                description_of_inspection, inspection_result, reason, other_reason, created_at
            ) VALUES (" . implode(', ', array_fill(0, count($params), '?')) . ")";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
    if (!$stmt->execute()) {
        throw new Exception('Database error: ' . $stmt->error);
    }
    $stmt->close();

    $upd = $conn->prepare("UPDATE project_info SET certificatestatus = 'Certificate Created' WHERE project_no = ?");
    $upd->bind_param('s', $project_no);
    $upd->execute();
    $upd->close();

    $conn->commit();

    if ($token !== '') {
        $_SESSION['eddy_saved_tokens'][$token] = $cert_no;
    }
    $response = ['ok' => true, 'certificate_no' => $cert_no, 'images' => [$img_1, $img_2, $img_3]];

} catch (Throwable $e) {
    try { $conn->rollback(); } catch (Throwable $ignore) {}
    foreach ($savedFiles as $file) {
        if (is_file($file)) { @unlink($file); }
    }
    error_log('eddy create: ' . $e->getMessage());
    $response = ['ok' => false, 'message' => $e->getMessage()];

} finally {
    if ($lockTaken) {
        try { $conn->query("SELECT RELEASE_LOCK('eddy_cert_no')"); } catch (Throwable $ignore) {}
    }
}
out($response);
