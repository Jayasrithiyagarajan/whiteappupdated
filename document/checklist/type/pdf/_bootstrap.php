<?php
include_once(__DIR__ . '/../../../../file/config.php');

$checklist_type = $_GET['checklist_type'] ?? '';
$checklist_no = isset($_GET['checklist_no']) ? (int) $_GET['checklist_no'] : 0;

if ($checklist_type === '' || $checklist_no <= 0) {
    die('Invalid checklist request');
}

$stmt = $conn->prepare(
    'SELECT *
     FROM checklist_information
     WHERE checklist_type = ? AND checklist_id = ?'
);
$stmt->bind_param('si', $checklist_type, $checklist_no);
$stmt->execute();
$result = $stmt->get_result();

if (!$result || $result->num_rows === 0) {
    die('Checklist not found');
}

$row = $result->fetch_assoc();
$stmt->close();

$stmt = $conn->prepare(
    'SELECT result, checklist_remark, recommendations, client_name
     FROM checklist_results
     WHERE checklist_id = ?'
);
$stmt->bind_param('i', $checklist_no);
$stmt->execute();
$result = $stmt->get_result();

$db_result = '';
$db_remark = '';
$recommendations = '';
$client_name = $row['client_name'] ?? '';

if ($result && $result->num_rows > 0) {
    $results_row = $result->fetch_assoc();
    $db_result = $results_row['result'] ?? '';
    $db_remark = $results_row['checklist_remark'] ?? '';
    $recommendations = $results_row['recommendations'] ?? '';
    if (!empty($results_row['client_name'])) {
        $client_name = $results_row['client_name'];
    }
}
$stmt->close();

$selected_results = $db_result !== '' ? explode(',', $db_result) : [];
$chek_remark = $db_remark !== '' ? explode(',', $db_remark) : [];

function pdf_mark_result($index, $value, $selected_results)
{
    if (isset($selected_results[$index]) && trim($selected_results[$index]) === $value) {
        return '<span class="tick">✓</span>';
    }

    return '';
}

function pdf_asset($relativePath)
{
    $relativePath = ltrim($relativePath, '/');
    $absolute = realpath(__DIR__ . '/../../../../' . $relativePath);
    $path = $absolute ?: (__DIR__ . '/../../../../' . $relativePath);

    return str_replace('\\', '/', $path);
}

function pdf_signature_path($inspectorName)
{
    $folder = preg_replace('/\s+/', '_', strtolower($inspectorName));
    return pdf_asset('inspector/uploads/' . $folder . '/images/signature_image.jpg');
}
