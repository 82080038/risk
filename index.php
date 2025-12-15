<?php
/**
 * Entry Point Aplikasi
 * Risk Assessment Objek Wisata
 */

// Auto-create database.php from template if not exists (MUST BE FIRST)
$db_file = __DIR__ . '/config/database.php';
$db_template = __DIR__ . '/config/database.php.example';

if (!file_exists($db_file) && file_exists($db_template)) {
    // Copy template to actual file
    if (!is_dir(__DIR__ . '/config')) {
        mkdir(__DIR__ . '/config', 0755, true);
    }
    copy($db_template, $db_file);
}

// Auto-create config.php from template if not exists (for Railway/Render deployment)
$config_file = __DIR__ . '/config/config.php';
$config_template = __DIR__ . '/config/config.php.example';

if (!file_exists($config_file) && file_exists($config_template)) {
    // Read template
    $config_content = file_get_contents($config_template);
    
    // Replace BASE_URL with environment variable or default
    $base_url = getenv('BASE_URL') ?: 'http://localhost/RISK/';
    $config_content = str_replace(
        "define('BASE_URL', getenv('BASE_URL') ?: 'http://localhost/RISK/');",
        "define('BASE_URL', getenv('BASE_URL') ?: 'http://localhost/RISK/');",
        $config_content
    );
    
    // Add APP_ENV support if not exists
    if (strpos($config_content, "define('APP_ENV'") === false) {
        $config_content = str_replace(
            "// Path aplikasi",
            "// Environment (development, production)\ndefine('APP_ENV', getenv('APP_ENV') ?: 'development');\n\n// Path aplikasi",
            $config_content
        );
    }
    
    // Update error reporting based on environment
    if (strpos($config_content, "if (APP_ENV === 'production')") === false) {
        $config_content = str_replace(
            "error_reporting(E_ALL);\nini_set('display_errors', 1);",
            "// Error reporting based on environment\nif (defined('APP_ENV') && APP_ENV === 'production') {\n    error_reporting(0);\n    ini_set('display_errors', 0);\n    ini_set('log_errors', 1);\n    ini_set('error_log', APP_PATH . 'logs/error.log');\n} else {\n    error_reporting(E_ALL);\n    ini_set('display_errors', 1);\n}",
            $config_content
        );
    }
    
    // Write config file
    if (!is_dir(__DIR__ . '/config')) {
        mkdir(__DIR__ . '/config', 0755, true);
    }
    file_put_contents($config_file, $config_content);
}

require_once $config_file;
require_once __DIR__ . '/includes/functions.php';

// Get BASE_URL
$base_url = defined('BASE_URL') ? BASE_URL : (getenv('BASE_URL') ?: '/');

// Ensure BASE_URL ends with /
if (substr($base_url, -1) !== '/') {
    $base_url .= '/';
}

// Redirect ke dashboard jika sudah login, atau ke login jika belum
try {
    if (function_exists('isLoggedIn') && isLoggedIn()) {
        $redirect_url = $base_url . 'pages/dashboard.php';
        header('Location: ' . $redirect_url);
        exit;
    } else {
        // Instead of redirect, include login page directly to avoid redirect issues
        $login_file = __DIR__ . '/pages/login.php';
        if (file_exists($login_file)) {
            // Set BASE_URL for login page
            if (!defined('BASE_URL')) {
                define('BASE_URL', $base_url);
            }
            require_once $login_file;
            exit;
        } else {
            // Fallback: show simple login form
            die('Login page not found. Please check file: ' . $login_file);
        }
    }
} catch (Exception $e) {
    // Jika ada error, tampilkan error untuk debugging
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    die('Error: ' . $e->getMessage() . '<br>File: ' . $e->getFile() . '<br>Line: ' . $e->getLine() . '<br><br><a href="debug.php">Go to Debug Page</a>');
} catch (Error $e) {
    // Handle fatal errors
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    die('Fatal Error: ' . $e->getMessage() . '<br>File: ' . $e->getFile() . '<br>Line: ' . $e->getLine() . '<br><br><a href="debug.php">Go to Debug Page</a>');
}

