<?php
/**
 * Entry Point Aplikasi
 * Risk Assessment Objek Wisata
 */

// Auto-create config.php from template if not exists (for Railway/Render deployment)
$config_file = __DIR__ . '/config/config.php';
$config_template = __DIR__ . '/config/config.php.example';

if (!file_exists($config_file) && file_exists($config_template)) {
    // Read template
    $config_content = file_get_contents($config_template);
    
    // Replace BASE_URL with environment variable or default
    $base_url = getenv('BASE_URL') ?: 'http://localhost/RISK/';
    $config_content = str_replace(
        "define('BASE_URL', 'http://localhost/RISK/');",
        "define('BASE_URL', getenv('BASE_URL') ?: 'http://localhost/RISK/');",
        $config_content
    );
    
    // Add APP_ENV support
    if (strpos($config_content, "define('APP_ENV'") === false) {
        $config_content = str_replace(
            "// Path aplikasi",
            "// Environment (development, production)\ndefine('APP_ENV', getenv('APP_ENV') ?: 'development');\n\n// Path aplikasi",
            $config_content
        );
    }
    
    // Update error reporting based on environment
    $config_content = str_replace(
        "// Untuk development (current)\nerror_reporting(E_ALL);\nini_set('display_errors', 1);",
        "// Error reporting based on environment\nif (defined('APP_ENV') && APP_ENV === 'production') {\n    error_reporting(0);\n    ini_set('display_errors', 0);\n    ini_set('log_errors', 1);\n    ini_set('error_log', APP_PATH . 'logs/error.log');\n} else {\n    error_reporting(E_ALL);\n    ini_set('display_errors', 1);\n}",
        $config_content
    );
    
    // Write config file
    file_put_contents($config_file, $config_content);
}

require_once $config_file;
require_once __DIR__ . '/includes/functions.php';

// Redirect ke dashboard jika sudah login, atau ke login jika belum
try {
    if (isLoggedIn()) {
        header('Location: ' . BASE_URL . 'pages/dashboard.php');
    } else {
        header('Location: ' . BASE_URL . 'pages/login.php');
    }
    exit;
} catch (Exception $e) {
    // Jika ada error, tampilkan error untuk debugging
    if (defined('APP_ENV') && APP_ENV === 'development') {
        die('Error: ' . $e->getMessage() . '<br>File: ' . $e->getFile() . '<br>Line: ' . $e->getLine());
    } else {
        die('Terjadi kesalahan. Silakan cek logs atau akses debug.php untuk informasi lebih lanjut.');
    }
}

