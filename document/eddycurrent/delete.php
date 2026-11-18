<?php
header('Content-Type: application/json; charset=utf-8');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once('../../file/config.php');

if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'Please log in again.']);
    exit;
}

// The register sends the certificate number (the old code received project_no and deleted nothing).
$certificate_no = isset($_POST['certificate_no']) ? trim($_POST['certificate_no']) : '';
if ($certificate_no === '') {
    echo json_encode(['success' => false, 'message' => 'Certificate number missing.']);
    exit;
}

try {
    $project_no = null;
    $stmtP = $conn->prepare("SELECT project_no FROM eddy_current_inspection WHERE certificate_no = ? LIMIT 1");
    $stmtP->bind_param("s", $certificate_no);
    $stmtP->execute();
    $stmtP->bind_result($project_no);
    $stmtP->fetch();
    $stmtP->close();

    // LIMIT 1: if a certificate was accidentally saved twice (identical number), each click removes one copy.
    $stmt = $conn->prepare("DELETE FROM eddy_current_inspection WHERE certificate_no = ? LIMIT 1");
    $stmt->bind_param("s", $certificate_no);
    $stmt->execute();
    $deleted = $stmt->affected_rows;
    $stmt->close();

    if ($deleted < 1) {
        echo json_encode(['success' => false, 'message' => 'Certificate not found.']);
        exit;
    }

    // Only mark the project Pending when it has no certificates left.
    if ($project_no) {
        $stmtC = $conn->prepare("SELECT COUNT(*) FROM eddy_current_inspection WHERE project_no = ?");
        $stmtC->bind_param("s", $project_no);
        $stmtC->execute();
        $stmtC->bind_result($left);
        $stmtC->fetch();
        $stmtC->close();

        if ((int)$left === 0) {
            $upd = $conn->prepare("UPDATE project_info SET certificatestatus = 'Pending' WHERE project_no = ?");
            $upd->bind_param("s", $project_no);
            $upd->execute();
            $upd->close();
        }
    }

    echo json_encode(['success' => true]);
} catch (Throwable $e) {
    error_log('eddy delete: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error deleting record: ' . $e->getMessage()]);
}
