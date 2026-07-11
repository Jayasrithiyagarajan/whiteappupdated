<?php
$_GET['id'] = 6;
ini_set('display_errors', 1);
error_reporting(E_ALL);
try {
    include 'c:/xampp/htdocs/whiteappupdated/operator_assessment/download-certificate.php';
} catch (Exception $e) {
    echo "EXCEPTION: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
    echo $e->getTraceAsString();
} catch (Error $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
