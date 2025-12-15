<?php
/**
 * Entry Point Aplikasi
 * Risk Assessment Objek Wisata
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/functions.php';

// Redirect ke dashboard jika sudah login, atau ke login jika belum
if (isLoggedIn()) {
    header('Location: ' . BASE_URL . 'pages/dashboard.php');
} else {
    header('Location: ' . BASE_URL . 'pages/login.php');
}
exit;

