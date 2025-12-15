<?php
/**
 * PHP Info
 * Risk Assessment Objek Wisata
 * 
 * Menampilkan informasi PHP dan extensions
 */

// Only allow in development
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../config/config.php';
}

// Check if in development mode
$is_dev = (error_reporting() == E_ALL && ini_get('display_errors'));

if (!$is_dev) {
    die('PHP Info hanya dapat diakses di development mode.');
}

phpinfo();
?>

