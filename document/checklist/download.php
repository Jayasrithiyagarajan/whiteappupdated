<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
ini_set('display_errors', 0);

require_once('../../vendor/autoload.php');
include_once('../../file/config.php');

if (!isset($_GET['project_no'])) {
    die("Project ID is required!");
}

$project_no = $_GET['project_no'];

// Fetch project info to get checklist_type
$query_project = "SELECT checklist_type, inspector_name FROM project_info WHERE project_no = ?";
$stmt_project = $conn->prepare($query_project);
$stmt_project->bind_param("s", $project_no);
$stmt_project->execute();
$result_project = $stmt_project->get_result();

if ($result_project->num_rows === 0) {
    die("Project not found!");
}

$project_data = $result_project->fetch_assoc();
$checklist_type = $project_data['checklist_type'];
$inspector_name = $project_data['inspector_name'];

// Fetch checklist information
$query_checklist = "SELECT checklist_id FROM checklist_information WHERE project_no = ?";
$stmt_checklist = $conn->prepare($query_checklist);
$stmt_checklist->bind_param("s", $project_no);
$stmt_checklist->execute();
$result_checklist = $stmt_checklist->get_result();

if ($result_checklist->num_rows === 0) {
    die("Checklist not found!");
}

$checklist_info = $result_checklist->fetch_assoc();
$checklist_no = $checklist_info['checklist_id'];

// Path to the specific checklist view file
$checklist_view_path = "type/view/" . $checklist_type . ".php";

if (!file_exists($checklist_view_path)) {
    die("Checklist template not found for type: " . htmlspecialchars($checklist_type));
}

// Set up variables that the included view file expects
$_GET['checklist_type'] = $checklist_type;
$_GET['checklist_no'] = $checklist_no;

// Start capturing output
ob_start();

// We need to be careful with paths inside the included file.
// Most checklist view files include './view-fetch.php'.
// We are in document/checklist/, and they are in document/checklist/type/view/.
// So we should chdir to that directory first.
$current_dir = getcwd();
chdir('type/view');
include($checklist_type . ".php");
chdir($current_dir);

$html = ob_get_clean();

// --- HTML Cleaning for Mpdf Compatibility ---
// Remove extra <html>, <head>, <body> tags if they exist (common in nested includes)
$html = preg_replace('/<!DOCTYPE.*?>/is', '', $html);
$html = preg_replace('/<html.*?>/is', '', $html);
$html = preg_replace('/<\/html>/is', '', $html);
$html = preg_replace('/<head>.*?<\/head>/is', '', $html);
$html = preg_replace('/<body.*?>/is', '', $html);
$html = preg_replace('/<\/body>/is', '', $html);

// Remove Bootstrap links as Mpdf doesn't support them well and they cause external request lag/errors
$html = preg_replace('/<link.*?>/is', '', $html);

// Remove scripts
$html = preg_replace('/<script.*?>.*?<\/script>/is', '', $html);


// --- PDF Optimization ---

// 1. Fix Image Paths for Mpdf (Absolute filesystem paths work best)
$html = preg_replace_callback('/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/i', function ($matches) use ($url2) {
    $src = $matches[1];

    // Resolve absolute URLs to local paths if they point to our server
    if (strpos($src, 'http') === 0) {
        if (strpos($src, $url2) === 0) {
            $relative_path = substr($src, strlen($url2));
            $local_path = realpath(__DIR__ . '/../../' . $relative_path);
            if ($local_path)
                return str_replace($src, $local_path, $matches[0]);
        }
        return $matches[0];
    }

    if (strpos($src, 'data:') === 0)
        return $matches[0];

    // Resolve relative paths
    $resolved_path = "";
    if ($src === "../../logo.png") {
        $resolved_path = realpath(__DIR__ . "/../logo.png");
    }
    elseif (strpos($src, "../../../") === 0) {
        // From document/checklist/type/view/ to root-relative
        $path_chunk = substr($src, 9); // e.g., 'code.png' or 'uploads/...'
        // If it's code.png, it's in document/
        if ($path_chunk == "code.png") {
            $resolved_path = realpath(__DIR__ . "/../code.png");
        }
        else {
            // Otherwise assume it's relative to whiteapp1/
            $resolved_path = realpath(__DIR__ . "/../../" . $path_chunk);
        }
    }
    elseif (strpos($src, "../../") === 0) {
        $resolved_path = realpath(__DIR__ . "/../" . substr($src, 6));
    }
    else {
        $resolved_path = realpath(__DIR__ . "/type/view/" . $src);
    }

    if ($resolved_path) {
        return str_replace($src, $resolved_path, $matches[0]);
    }
    return $matches[0];
}, $html);

// 2. Replace Checkboxes with Symbols (Prevents "checked='checked'" text issue)
$html = preg_replace_callback('/<input[^>]+type=["\']checkbox["\'][^>]*>/i', function ($matches) {
    $isChecked = (stripos($matches[0], 'checked') !== false);
    // Use Unicode symbols that Mpdf understands with DejaVu Sans
    if ($isChecked) {
        return '<span style="font-family: DejaVuSans, sans-serif; font-size: 14pt;">&#9746;</span>'; // Checked box [X]
    }
    else {
        return '<span style="font-family: DejaVuSans, sans-serif; font-size: 14pt;">&#9744;</span>'; // Empty box [ ]
    }
}, $html);

// Initialize Mpdf
$mpdf = new \Mpdf\Mpdf([
    'mode' => 'utf-8',
    'format' => 'A4',
    'orientation' => 'P',
    'margin_left' => 10,
    'margin_right' => 10,
    'margin_top' => 10,
    'margin_bottom' => 10,
    'default_font' => 'DejaVuSans'
]);

// Write HTML to PDF with error suppression
try {
    @$mpdf->WriteHTML($html);
}
catch (\Exception $e) {
    // If it fails, try writing a simpler version
    $mpdf->WriteHTML("<h1>Error generating detailed PDF</h1><p>The content was too complex for Mpdf.</p><p>" . $e->getMessage() . "</p>");
}


// Cleanup filename
$safe_checklist_type = preg_replace('/[^a-zA-Z0-9]/', '_', $checklist_type);
$filename = "Checklist_" . $safe_checklist_type . "_" . $project_no . ".pdf";

// Output as download
$mpdf->Output($filename, 'D');
exit;
?>
