<?php
/**
 * API Base - Production Version
 * Risk Assessment Objek Wisata
 * 
 * Copy ke api_base.php dan sesuaikan untuk production
 */

header('Content-Type: application/json; charset=utf-8');

// CORS headers - RESTRICT untuk production
// Ganti * dengan domain yang diizinkan
$allowed_origins = [
    'https://yourdomain.com',
    'https://www.yourdomain.com'
];

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if (in_array($origin, $allowed_origins)) {
    header('Access-Control-Allow-Origin: ' . $origin);
} else {
    // Jika tidak ada origin atau tidak diizinkan, jangan set header
    // Atau bisa juga set ke domain utama saja
    header('Access-Control-Allow-Origin: https://yourdomain.com');
}

header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Max-Age: 86400'); // 24 hours

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

// Error handler - jangan expose detail error di production
function jsonError($message, $code = 400) {
    // Log error detail
    error_log("API Error [$code]: $message");
    
    // Tampilkan message user-friendly
    $user_message = $message;
    if (defined('IS_PRODUCTION') && IS_PRODUCTION) {
        // Di production, jangan expose detail error
        if ($code >= 500) {
            $user_message = 'Terjadi kesalahan server. Silakan coba lagi nanti.';
        }
    }
    
    jsonResponse(false, $user_message, null, $code);
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

