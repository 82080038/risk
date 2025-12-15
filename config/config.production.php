<?php
/**
 * Konfigurasi Production
 * Risk Assessment Objek Wisata
 * 
 * Copy file ini ke config.php dan sesuaikan untuk production
 */

// Base URL - SESUAIKAN dengan domain production
define('BASE_URL', 'https://yourdomain.com/RISK/');

// Path aplikasi
define('APP_PATH', __DIR__ . '/../');

// Session
session_start();

// Session Security untuk Production
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1); // Hanya untuk HTTPS
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_samesite', 'Strict');

// Timezone
date_default_timezone_set('Asia/Jakarta');

// Error reporting - DISABLE di production
error_reporting(0);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', APP_PATH . 'logs/error.log');

// Upload settings
define('UPLOAD_PATH', APP_PATH . 'assets/uploads/');
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx']);

// Pagination
define('ITEMS_PER_PAGE', 10);

// Tahun default untuk laporan
define('DEFAULT_YEAR', date('Y'));

// Production flags
define('IS_PRODUCTION', true);
define('ENABLE_DEBUG', false);

?>

