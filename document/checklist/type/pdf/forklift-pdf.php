<?php
include_once(__DIR__ . '/_bootstrap.php');

$project_no = $row['project_no'] ?? '';
$inspector_signature_path = pdf_signature_path($row['inspected_by'] ?? '');
$client_signature_path = $project_no !== '' 
    ? __DIR__ . '/../../../uploads/' . $project_no . '.png' 
    : '';

$logoPath = str_replace('\\', '/', realpath(__DIR__ . '/../../logo.png') ?: (__DIR__ . '/../../logo.png'));
$codePath = str_replace('\\', '/', realpath(__DIR__ . '/../../../code.png') ?: (__DIR__ . '/../../../code.png'));
$stylePath = str_replace('\\', '/', realpath(__DIR__ . '/../style.css') ?: (__DIR__ . '/../style.css'));

$cwd = getcwd();
chdir(__DIR__ . '/../view');
ob_start();
include 'forklift.php';
$html = ob_get_clean();
chdir($cwd);

// Remove scripts and remote Bootstrap links for PDF generation.
$html = preg_replace('#<script[^>]*>.*?</script>#is', '', $html);
$html = preg_replace('#<link[^>]+href=["\ ]?https?://[^"\ ]+["\ ]?[^>]*>#i', '', $html);

$html = str_replace(
    [
        '../../logo.png',
        '../../../code.png',
        '../style.css',
        '../../../uploads/' . $project_no . '.png',
    ],
    [
        htmlspecialchars($logoPath, ENT_QUOTES),
        htmlspecialchars($codePath, ENT_QUOTES),
        htmlspecialchars($stylePath, ENT_QUOTES),
        htmlspecialchars($client_signature_path, ENT_QUOTES),
    ],
    $html
);

if ($inspector_signature_path && file_exists($inspector_signature_path)) {
    $html = preg_replace(
        '#src=["\ ][^"\ ]*signature_image\.jpg["\ ]#i',
        'src="' . htmlspecialchars($inspector_signature_path, ENT_QUOTES) . '"',
        $html
    );
}

echo $html;
?>
