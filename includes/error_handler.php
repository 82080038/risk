<?php
/**
 * Custom Error Handler untuk Production
 * Risk Assessment Objek Wisata
 */

// Set custom error handler
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    // Log error ke file
    $log_message = date('Y-m-d H:i:s') . " - Error [$errno]: $errstr in $errfile on line $errline\n";
    error_log($log_message, 3, __DIR__ . '/../logs/error.log');
    
    // Di production, jangan tampilkan error detail
    if (defined('IS_PRODUCTION') && IS_PRODUCTION) {
        // Tampilkan error user-friendly
        if ($errno === E_ERROR || $errno === E_USER_ERROR) {
            http_response_code(500);
            die('Terjadi kesalahan sistem. Silakan hubungi administrator.');
        }
        return true; // Suppress error display
    }
    
    // Di development, tampilkan error normal
    return false;
});

// Set exception handler
set_exception_handler(function($exception) {
    // Log exception
    $log_message = date('Y-m-d H:i:s') . " - Exception: " . $exception->getMessage() . 
                   " in " . $exception->getFile() . " on line " . $exception->getLine() . "\n";
    error_log($log_message, 3, __DIR__ . '/../logs/error.log');
    
    // Di production, tampilkan error user-friendly
    if (defined('IS_PRODUCTION') && IS_PRODUCTION) {
        http_response_code(500);
        die('Terjadi kesalahan sistem. Silakan hubungi administrator.');
    }
    
    // Di development, tampilkan exception detail
    throw $exception;
});

// Handle fatal errors
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== NULL && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        $log_message = date('Y-m-d H:i:s') . " - Fatal Error: " . $error['message'] . 
                       " in " . $error['file'] . " on line " . $error['line'] . "\n";
        error_log($log_message, 3, __DIR__ . '/../logs/error.log');
        
        if (defined('IS_PRODUCTION') && IS_PRODUCTION) {
            http_response_code(500);
            die('Terjadi kesalahan sistem. Silakan hubungi administrator.');
        }
    }
});

?>

