<?php
/**
 * Functions Helper
 * Risk Assessment Objek Wisata
 */

// Auto-create database.php from template if not exists (for Railway/Render deployment)
$db_file = __DIR__ . '/../config/database.php';
$db_template = __DIR__ . '/../config/database.php.example';

if (!file_exists($db_file) && file_exists($db_template)) {
    // Copy template to actual file
    copy($db_template, $db_file);
}

require_once $db_file;

// Auto-create config.php from template if not exists (for Railway/Render deployment)
$config_file = __DIR__ . '/../config/config.php';
$config_template = __DIR__ . '/../config/config.php.example';

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
    file_put_contents($config_file, $config_content);
}

require_once $config_file;
require_once __DIR__ . '/image_helper.php';

/**
 * Cek apakah user sudah login
 */
function isLoggedIn() {
    if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
        return false;
    }
    
    // Regenerate session ID secara berkala untuk security (setiap 30 menit)
    if (!isset($_SESSION['last_regeneration'])) {
        $_SESSION['last_regeneration'] = time();
    } elseif (time() - $_SESSION['last_regeneration'] > 1800) { // 30 menit
        session_regenerate_id(true);
        $_SESSION['last_regeneration'] = time();
    }
    
    return true;
}

/**
 * Redirect jika belum login
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ' . BASE_URL . 'pages/login.php');
        exit;
    }
}

/**
 * Redirect jika sudah login
 */
function redirectIfLoggedIn() {
    if (isLoggedIn()) {
        header('Location: ' . BASE_URL . 'pages/dashboard.php');
        exit;
    }
}

/**
 * Get user data dari session
 */
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    $conn = getDBConnection();
    $user_id = $_SESSION['user_id'];
    
    $stmt = $conn->prepare("SELECT id, username, nama, pangkat_nrp, role FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    $stmt->close();
    $conn->close();
    
    return $user;
}

/**
 * Logout user
 */
function logout() {
    session_unset();
    session_destroy();
    header('Location: ' . BASE_URL . 'pages/login.php');
    exit;
}

/**
 * Format tanggal Indonesia
 */
function formatTanggalIndonesia($date) {
    $bulan = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];
    
    $timestamp = strtotime($date);
    $tanggal = date('d', $timestamp);
    $bulan_nama = $bulan[(int)date('m', $timestamp)];
    $tahun = date('Y', $timestamp);
    
    return $tanggal . ' ' . $bulan_nama . ' ' . $tahun;
}

/**
 * Sanitize input
 */
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

/**
 * Get kategori berdasarkan skor
 * Sesuai file acuan: RISK ASSESMENT OBJEK WISATA 2025.txt
 */
function getKategori($skor) {
    if ($skor >= 86) {
        return ['nama' => 'Baik Sekali (Kategori Emas)', 'kode' => 'emas', 'icon' => '🥇'];
    } elseif ($skor >= 71) {
        return ['nama' => 'Baik (Kategori Perak)', 'kode' => 'perak', 'icon' => '🥈'];
    } elseif ($skor >= 56) {
        return ['nama' => 'Cukup (Kategori Perunggu)', 'kode' => 'perunggu', 'icon' => '🥉'];
    } else {
        return ['nama' => 'Kurang (Tindakan Pembinaan untuk Perbaikan)', 'kode' => 'kurang', 'icon' => '⚠️'];
    }
}

/**
 * Format angka dengan 2 desimal
 */
function formatAngka($angka, $desimal = 2) {
    return number_format($angka, $desimal, ',', '.');
}

/**
 * Format ukuran file
 */
function formatFileSize($bytes, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    
    if ($bytes == 0) {
        return '0 B';
    }
    
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    
    $bytes /= pow(1024, $pow);
    
    return round($bytes, $precision) . ' ' . $units[$pow];
}

/**
 * Get kategori dengan class untuk styling
 */
function getKategoriWithClass($skor) {
    // Sesuai file acuan: RISK ASSESMENT OBJEK WISATA 2025.txt
    // 86% - 100%: Baik Sekali (Kategori Emas)
    // 71% - 85%: Baik (Kategori Perak)
    // 56% - 70%: Cukup (Kategori Perunggu)
    // < 55%: Kurang (Tindakan Pembinaan untuk Perbaikan)
    
    if ($skor >= 86) {
        return [
            'nama' => 'Baik Sekali (Kategori Emas)',
            'kode' => 'emas',
            'icon' => '🥇',
            'class' => 'text-warning',
            'progress_class' => 'bg-warning',
            'badge_text' => 'EMAS',
            'range' => '86% - 100%'
        ];
    } elseif ($skor >= 71) {
        return [
            'nama' => 'Baik (Kategori Perak)',
            'kode' => 'perak',
            'icon' => '🥈',
            'class' => 'text-info',
            'progress_class' => 'bg-info',
            'badge_text' => 'PERAK',
            'range' => '71% - 85%'
        ];
    } elseif ($skor >= 56) {
        return [
            'nama' => 'Cukup (Kategori Perunggu)',
            'kode' => 'perunggu',
            'icon' => '🥉',
            'class' => 'text-primary',
            'progress_class' => 'bg-primary',
            'badge_text' => 'PERUNGGU',
            'range' => '56% - 70%'
        ];
    } else {
        return [
            'nama' => 'Kurang (Tindakan Pembinaan untuk Perbaikan)',
            'kode' => 'kurang',
            'icon' => '⚠️',
            'class' => 'text-danger',
            'progress_class' => 'bg-danger',
            'badge_text' => 'KURANG',
            'range' => '< 55%'
        ];
    }
}

?>

