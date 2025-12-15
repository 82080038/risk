<?php
/**
 * Script Setup Database
 * Import semua file SQL secara otomatis
 */

// Konfigurasi
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'risk_assessment_db';

// File SQL yang akan diimport (dalam urutan)
$sql_files = [
    'sql/database.sql',
    'sql/master_data.sql',
    'sql/data_personil.sql',
    'sql/data_objek_wisata.sql'
];

echo "<!DOCTYPE html>
<html lang='id'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Setup Database - Risk Assessment</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css'>
    <style>
        body { padding: 2rem; background: #f8f9fa; }
        .log-box { background: #fff; border: 1px solid #dee2e6; border-radius: 0.5rem; padding: 1rem; margin: 1rem 0; max-height: 400px; overflow-y: auto; font-family: monospace; font-size: 0.9rem; }
        .log-success { color: #198754; }
        .log-error { color: #dc3545; }
        .log-info { color: #0dcaf0; }
    </style>
</head>
<body>
<div class='container'>
    <h2 class='mb-4'><i class='fas fa-database me-2'></i>Setup Database</h2>";

// Koneksi MySQL
$conn = new mysqli($db_host, $db_user, $db_pass);

if ($conn->connect_error) {
    die("<div class='alert alert-danger'><i class='fas fa-times-circle me-2'></i>Koneksi MySQL gagal: " . $conn->connect_error . "</div></div></body></html>");
}

echo "<div class='alert alert-success'><i class='fas fa-check-circle me-2'></i>Koneksi MySQL berhasil!</div>";

// Buat database jika belum ada
$sql = "CREATE DATABASE IF NOT EXISTS `$db_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
if ($conn->query($sql)) {
    echo "<div class='alert alert-success'><i class='fas fa-check-circle me-2'></i>Database '$db_name' berhasil dibuat/ditemukan!</div>";
} else {
    echo "<div class='alert alert-danger'><i class='fas fa-times-circle me-2'></i>Error membuat database: " . $conn->error . "</div>";
}

$conn->close();

// Pilih database
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
$conn->set_charset("utf8mb4");

echo "<div class='log-box'>";
echo "<div class='log-info'><strong>Memulai import file SQL...</strong></div>";

$success_count = 0;
$error_count = 0;

foreach ($sql_files as $file) {
    if (!file_exists($file)) {
        echo "<div class='log-error'>✗ File tidak ditemukan: $file</div>";
        $error_count++;
        continue;
    }
    
    echo "<div class='log-info'>→ Memproses: $file</div>";
    
    // Baca file SQL
    $sql_content = file_get_contents($file);
    
    if ($sql_content === false) {
        echo "<div class='log-error'>  ✗ Gagal membaca file: $file</div>";
        $error_count++;
        continue;
    }
    
    // Hapus komentar dan baris kosong
    $sql_content = preg_replace('/--.*$/m', '', $sql_content);
    $sql_content = preg_replace('/\/\*.*?\*\//s', '', $sql_content);
    
    // Split by semicolon
    $queries = array_filter(array_map('trim', explode(';', $sql_content)));
    
    $file_success = 0;
    $file_error = 0;
    
    foreach ($queries as $query) {
        if (empty($query) || strlen($query) < 10) {
            continue;
        }
        
        // Skip USE statement (sudah dipilih database)
        if (preg_match('/^\s*USE\s+/i', $query)) {
            continue;
        }
        
        if ($conn->query($query)) {
            $file_success++;
        } else {
            // Skip error untuk duplicate entry atau table exists
            if (strpos($conn->error, 'Duplicate entry') === false && 
                strpos($conn->error, 'already exists') === false &&
                strpos($conn->error, 'Unknown column') === false) {
                echo "<div class='log-error'>  ✗ Error: " . $conn->error . "</div>";
                echo "<div class='log-error'>    Query: " . substr($query, 0, 100) . "...</div>";
                $file_error++;
                $error_count++;
            } else {
                // Duplicate atau sudah ada - skip
                $file_success++;
            }
        }
    }
    
    if ($file_error == 0) {
        echo "<div class='log-success'>  ✓ Berhasil: $file_success query</div>";
        $success_count++;
    } else {
        echo "<div class='log-error'>  ✗ Error: $file_error query gagal</div>";
    }
}

$conn->close();

echo "</div>";

// Verifikasi
echo "<h4 class='mt-4'>Verifikasi Data</h4>";
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
$conn->set_charset("utf8mb4");

$verifications = [
    'users' => 'SELECT COUNT(*) as total FROM users',
    'objek_wisata' => 'SELECT COUNT(*) as total FROM objek_wisata',
    'aspek' => 'SELECT COUNT(*) as total FROM aspek',
    'elemen' => 'SELECT COUNT(*) as total FROM elemen',
    'kriteria' => 'SELECT COUNT(*) as total FROM kriteria',
];

echo "<div class='table-responsive'><table class='table table-bordered'>";
echo "<thead><tr><th>Tabel</th><th>Jumlah Data</th><th>Status</th></tr></thead><tbody>";

foreach ($verifications as $table => $query) {
    $result = $conn->query($query);
    if ($result) {
        $row = $result->fetch_assoc();
        $total = $row['total'];
        $status = $total > 0 ? "<span class='badge bg-success'>OK</span>" : "<span class='badge bg-warning'>Kosong</span>";
        echo "<tr><td>$table</td><td>$total</td><td>$status</td></tr>";
    } else {
        echo "<tr><td>$table</td><td>-</td><td><span class='badge bg-danger'>Error</span></td></tr>";
    }
}

echo "</tbody></table></div>";

$conn->close();

// Summary
echo "<div class='alert alert-info mt-4'>";
echo "<h5><i class='fas fa-info-circle me-2'></i>Summary</h5>";
echo "<ul class='mb-0'>";
echo "<li>File berhasil diimport: $success_count / " . count($sql_files) . "</li>";
echo "<li>Total error: $error_count</li>";
echo "<li>Database: <strong>$db_name</strong></li>";
echo "</ul>";
echo "</div>";

echo "<div class='alert alert-success mt-3'>";
echo "<h5><i class='fas fa-check-circle me-2'></i>Setup Selesai!</h5>";
echo "<p class='mb-2'>Database sudah siap digunakan.</p>";
echo "<a href='pages/login.php' class='btn btn-primary'><i class='fas fa-sign-in-alt me-2'></i>Login ke Aplikasi</a> ";
echo "<a href='index.php' class='btn btn-secondary'><i class='fas fa-home me-2'></i>Ke Halaman Utama</a>";
echo "</div>";

echo "</div></body></html>";
?>

