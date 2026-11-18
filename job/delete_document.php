<?php
/**
 * Delete ONE uploaded document of a job (called from job-details.php).
 * POST: id (documents.id), slot (1-10), project_no
 * Returns JSON {success, message}. Only inspectors and admins may delete.
 */
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 0);
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include_once '../file/config.php';
include_once __DIR__ . '/doc_names.php';
ensure_doc_name_columns($conn);

function out(array $p, int $code = 200) { http_response_code($code); echo json_encode($p); exit; }

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') out(['success' => false, 'message' => 'Invalid request.'], 405);
if (empty($_SESSION['username'])) out(['success' => false, 'expired' => true, 'message' => 'Your login session has expired. Please log in again.'], 401);
if (!in_array($_SESSION['role'] ?? '', ['inspector', 'admin'], true)) out(['success' => false, 'message' => 'Only inspectors and admins can delete uploaded documents.'], 403);

$id         = (int)($_POST['id'] ?? 0);
$slot       = (int)($_POST['slot'] ?? 0);
$project_no = trim((string)($_POST['project_no'] ?? ''));
if ($id <= 0 || $slot < 1 || $slot > 10 || $project_no === '') out(['success' => false, 'message' => 'Invalid document.']);

try {
    $conn->begin_transaction();

    $stmt = $conn->prepare("SELECT * FROM documents WHERE id = ? AND project_no = ? LIMIT 1 FOR UPDATE");
    $stmt->bind_param('is', $id, $project_no);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if (!$row || empty($row["file_$slot"])) throw new Exception('Document not found (it may already be deleted).');

    $stored = basename($row["file_$slot"]);

    // $slot is an int 1-10, so these column names are safe
    $upd = $conn->prepare("UPDATE documents SET file_$slot = NULL, original_filename_$slot = NULL, doc_name_$slot = NULL WHERE id = ?");
    $upd->bind_param('i', $id);
    if (!$upd->execute()) throw new Exception('Database error: ' . $upd->error);
    $upd->close();

    // remove the row itself when no file is left in it
    $left = 0;
    for ($i = 1; $i <= 10; $i++) { if ($i !== $slot && !empty($row["file_$i"])) $left++; }
    if ($left === 0) {
        $del = $conn->prepare("DELETE FROM documents WHERE id = ?");
        $del->bind_param('i', $id);
        $del->execute();
        $del->close();
    }

    $conn->commit();

    // file is removed only after the database change succeeded
    $path = __DIR__ . '/../uploads/' . $project_no . '/' . $stored;
    if (is_file($path)) { @unlink($path); }

    out(['success' => true, 'message' => 'Document deleted.']);
} catch (Throwable $e) {
    try { $conn->rollback(); } catch (Throwable $ignore) {}
    out(['success' => false, 'message' => $e->getMessage()]);
}
