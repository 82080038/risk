<?php
/**
 * API Base
 * Risk Assessment Objek Wisata
 */

header('Content-Type: application/json; charset=utf-8');

// CORS headers (untuk development)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';

// Response helper
function jsonResponse($success, $message, $data = null, $code = 200) {
    http_response_code($code);
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

// Error handler
function jsonError($message, $code = 400) {
    jsonResponse(false, $message, null, $code);
}

// Success handler
function jsonSuccess($message, $data = null) {
    jsonResponse(true, $message, $data);
}

// Check login untuk API yang memerlukan autentikasi
function requireApiLogin() {
    if (!isLoggedIn()) {
        jsonError('Unauthorized. Silakan login terlebih dahulu.', 401);
    }
}

?>

