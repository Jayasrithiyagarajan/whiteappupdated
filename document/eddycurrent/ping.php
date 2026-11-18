<?php
/** Keep-alive for long forms. Touching the session resets its idle timer. Returns {alive: bool}. */
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$alive = !empty($_SESSION['username']);
if (!$alive) { http_response_code(401); }
echo json_encode(['alive' => $alive]);
