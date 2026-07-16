<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('pcre.backtrack_limit', '5000000');
ob_start();

require_once(__DIR__.'/../vendor/autoload.php');
include_once(__DIR__.'/../file/config.php');

$certificate = require __DIR__.'/includes/fetch_certificate.php';

ob_start();
require __DIR__.'/templates/operator_certificate.php';
$html = ob_get_clean();

require __DIR__.'/pdf/download.php';
