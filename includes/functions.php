<?php
/**
 * Functions Helper
 * Risk Assessment Objek Wisata
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/config.php';
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

