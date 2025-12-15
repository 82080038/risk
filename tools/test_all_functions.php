<?php
/**
 * Test All Functions
 * Risk Assessment Objek Wisata
 * Test setiap fungsi aplikasi
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/functions.php';

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test All Functions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .test-result { padding: 1rem; margin: 0.5rem 0; border-radius: 0.5rem; }
        .test-pass { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; }
        .test-fail { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; }
        .test-warning { background: #fff3cd; border: 1px solid #ffeaa7; color: #856404; }
    </style>
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="fas fa-vial me-2"></i>Test All Functions</h4>
        </div>
        <div class="card-body">
            <?php
            $tests = [];
            $total_tests = 0;
            $passed_tests = 0;
            $failed_tests = 0;
            
            // Test 1: Database Connection
            $total_tests++;
            try {
                $conn = getDBConnection();
                $tests[] = ['name' => 'Database Connection', 'status' => 'pass', 'message' => 'Koneksi database berhasil'];
                $passed_tests++;
            } catch (Exception $e) {
                $tests[] = ['name' => 'Database Connection', 'status' => 'fail', 'message' => 'Error: ' . $e->getMessage()];
                $failed_tests++;
            }
            
            // Test 2: Check Tables
            if (isset($conn)) {
                $tables = ['users', 'objek_wisata', 'aspek', 'elemen', 'kriteria', 'penilaian', 'penilaian_detail', 'referensi_dokumen'];
                foreach ($tables as $table) {
                    $total_tests++;
                    $result = $conn->query("SHOW TABLES LIKE '$table'");
                    if ($result && $result->num_rows > 0) {
                        $tests[] = ['name' => "Table: $table", 'status' => 'pass', 'message' => 'Tabel ditemukan'];
                        $passed_tests++;
                    } else {
                        $tests[] = ['name' => "Table: $table", 'status' => 'fail', 'message' => 'Tabel tidak ditemukan'];
                        $failed_tests++;
                    }
                }
            }
            
            // Test 3: Check Data
            if (isset($conn)) {
                $total_tests++;
                $result = $conn->query("SELECT COUNT(*) as total FROM users");
                $count = $result->fetch_assoc()['total'];
                if ($count > 0) {
                    $tests[] = ['name' => 'Data Users', 'status' => 'pass', 'message' => "$count user ditemukan"];
                    $passed_tests++;
                } else {
                    $tests[] = ['name' => 'Data Users', 'status' => 'warning', 'message' => 'Tidak ada data user'];
                    $failed_tests++;
                }
                
                $total_tests++;
                $result = $conn->query("SELECT COUNT(*) as total FROM objek_wisata");
                $count = $result->fetch_assoc()['total'];
                if ($count > 0) {
                    $tests[] = ['name' => 'Data Objek Wisata', 'status' => 'pass', 'message' => "$count objek wisata ditemukan"];
                    $passed_tests++;
                } else {
                    $tests[] = ['name' => 'Data Objek Wisata', 'status' => 'warning', 'message' => 'Tidak ada data objek wisata'];
                    $failed_tests++;
                }
                
                $total_tests++;
                $result = $conn->query("SELECT COUNT(*) as total FROM aspek");
                $count = $result->fetch_assoc()['total'];
                if ($count > 0) {
                    $tests[] = ['name' => 'Data Aspek', 'status' => 'pass', 'message' => "$count aspek ditemukan"];
                    $passed_tests++;
                } else {
                    $tests[] = ['name' => 'Data Aspek', 'status' => 'warning', 'message' => 'Tidak ada data aspek'];
                    $failed_tests++;
                }
            }
            
            // Test 4: Check Files
            $files = [
                'pages/login.php',
                'pages/dashboard.php',
                'pages/objek_wisata.php',
                'pages/penilaian.php',
                'pages/laporan.php',
                'includes/header.php',
                'includes/footer.php',
                'includes/functions.php',
                'config/config.php',
                'config/database.php',
                'assets/js/app.js',
                'assets/js/api.js',
                'assets/js/dynamic.js'
            ];
            
            foreach ($files as $file) {
                $total_tests++;
                if (file_exists($file)) {
                    $tests[] = ['name' => "File: $file", 'status' => 'pass', 'message' => 'File ditemukan'];
                    $passed_tests++;
                } else {
                    $tests[] = ['name' => "File: $file", 'status' => 'fail', 'message' => 'File tidak ditemukan'];
                    $failed_tests++;
                }
            }
            
            // Test 5: Check API Files
            $api_files = [
                'api/api_base.php',
                'api/objek_wisata.php',
                'api/penilaian.php',
                'api/kriteria.php',
                'api/dashboard.php'
            ];
            
            foreach ($api_files as $file) {
                $total_tests++;
                if (file_exists($file)) {
                    $tests[] = ['name' => "API: $file", 'status' => 'pass', 'message' => 'File ditemukan'];
                    $passed_tests++;
                } else {
                    $tests[] = ['name' => "API: $file", 'status' => 'fail', 'message' => 'File tidak ditemukan'];
                    $failed_tests++;
                }
            }
            
            // Test 6: Check Functions
            $functions = [
                'getDBConnection',
                'isLoggedIn',
                'requireLogin',
                'getCurrentUser',
                'sanitize',
                'formatTanggalIndonesia',
                'getKategori',
                'formatAngka'
            ];
            
            foreach ($functions as $func) {
                $total_tests++;
                if (function_exists($func)) {
                    $tests[] = ['name' => "Function: $func()", 'status' => 'pass', 'message' => 'Function tersedia'];
                    $passed_tests++;
                } else {
                    $tests[] = ['name' => "Function: $func()", 'status' => 'fail', 'message' => 'Function tidak ditemukan'];
                    $failed_tests++;
                }
            }
            
            // Display Results
            foreach ($tests as $test) {
                $class = $test['status'] == 'pass' ? 'test-pass' : ($test['status'] == 'warning' ? 'test-warning' : 'test-fail');
                $icon = $test['status'] == 'pass' ? 'fa-check-circle' : ($test['status'] == 'warning' ? 'fa-exclamation-triangle' : 'fa-times-circle');
                echo "<div class='test-result $class'>";
                echo "<i class='fas $icon me-2'></i><strong>{$test['name']}</strong>: {$test['message']}";
                echo "</div>";
            }
            
            // Summary
            echo "<div class='alert alert-info mt-4'>";
            echo "<h5><i class='fas fa-info-circle me-2'></i>Summary</h5>";
            echo "<ul class='mb-0'>";
            echo "<li>Total Tests: <strong>$total_tests</strong></li>";
            echo "<li>Passed: <strong class='text-success'>$passed_tests</strong></li>";
            echo "<li>Failed: <strong class='text-danger'>$failed_tests</strong></li>";
            $percentage = $total_tests > 0 ? round(($passed_tests / $total_tests) * 100, 2) : 0;
            echo "<li>Success Rate: <strong>$percentage%</strong></li>";
            echo "</ul>";
            echo "</div>";
            
            if ($failed_tests == 0) {
                echo "<div class='alert alert-success mt-3'>";
                echo "<h5><i class='fas fa-check-circle me-2'></i>Semua Test Berhasil!</h5>";
                echo "<p class='mb-2'>Aplikasi siap digunakan.</p>";
                echo "<a href='pages/login.php' class='btn btn-primary'><i class='fas fa-sign-in-alt me-2'></i>Login ke Aplikasi</a>";
                echo "</div>";
            } else {
                echo "<div class='alert alert-warning mt-3'>";
                echo "<h5><i class='fas fa-exclamation-triangle me-2'></i>Ada Beberapa Test yang Gagal</h5>";
                echo "<p>Silakan perbaiki error yang ditemukan.</p>";
                echo "</div>";
            }
            
            if (isset($conn)) {
                $conn->close();
            }
            ?>
        </div>
    </div>
</div>
</body>
</html>

