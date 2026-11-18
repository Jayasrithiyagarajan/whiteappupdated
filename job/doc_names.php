<?php
/**
 * Uploaded-document names.
 * Adds the columns documents.doc_name_1 .. doc_name_10 (one name per uploaded file) the first time it runs.
 * Safe to call on every request: it only checks the schema once per request and does nothing when the columns exist.
 */
function ensure_doc_name_columns(mysqli $conn): bool
{
    static $done = null;
    if ($done !== null) return $done;
    try {
        $have = [];
        $r = $conn->query("SHOW COLUMNS FROM documents LIKE 'doc_name\\_%'");
        while ($r && ($row = $r->fetch_assoc())) { $have[$row['Field']] = true; }
        $add = [];
        for ($i = 1; $i <= 10; $i++) {
            if (!isset($have["doc_name_$i"])) $add[] = "ADD COLUMN doc_name_$i VARCHAR(255) NULL DEFAULT NULL";
        }
        if ($add) $conn->query("ALTER TABLE documents " . implode(', ', $add));
        $done = true;
    } catch (Throwable $e) {
        error_log('doc_names: ' . $e->getMessage());
        $done = false;          // pages keep working without names if the database user may not ALTER
    }
    return $done;
}

/** Clean a name typed by the user (single line, max 255 chars). */
function clean_doc_name($v): ?string
{
    $v = trim(preg_replace('/\s+/u', ' ', (string)$v));
    if ($v === '') return null;
    return mb_substr($v, 0, 255);
}
