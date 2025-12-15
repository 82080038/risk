<?php
/**
 * Konfigurasi Database Production
 * Risk Assessment Objek Wisata
 * 
 * Copy file ini ke database.php dan sesuaikan untuk production
 * JANGAN commit file ini dengan credentials real ke repository!
 */

// Konfigurasi database - GANTI dengan credentials production
define('DB_HOST', 'localhost'); // atau IP server database
define('DB_USER', 'your_db_user');
define('DB_PASS', 'your_db_password');
define('DB_NAME', 'risk_assessment_db');

// Koneksi database
function getDBConnection() {
    // Cek apakah database sudah didefinisikan
    if (!defined('DB_HOST') || !defined('DB_USER') || !defined('DB_NAME')) {
        // Di production, jangan tampilkan error detail
        error_log("Error: Konfigurasi database belum lengkap");
        die("Error: Konfigurasi database tidak valid. Silakan hubungi administrator.");
    }
    
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Cek koneksi
    if ($conn->connect_error) {
        // Log error tapi jangan tampilkan detail ke user
        error_log("Database connection error: " . $conn->connect_error);
        die("Error: Tidak dapat terhubung ke database. Silakan hubungi administrator.");
    }
    
    // Set charset UTF-8
    $conn->set_charset("utf8mb4");
    
    return $conn;
}

?>

