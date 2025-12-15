<?php
/**
 * Complete Application Test
 * Risk Assessment Objek Wisata
 * Test semua fungsi dengan NRP: 82080038
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
    <title>Complete Application Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .test-result { padding: 1rem; margin: 0.5rem 0; border-radius: 0.5rem; }
        .test-pass { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; }
        .test-fail { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; }
        .test-warning { background: #fff3cd; border: 1px solid #ffeaa7; color: #856404; }
        .test-info { background: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460; }
    </style>
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="fas fa-vial me-2"></i>Complete Application Test</h4>
            <small>NRP Test: 82080038</small>
        </div>
        <div class="card-body">
            <?php
            $tests = [];
            $total_tests = 0;
            $passed_tests = 0;
            $failed_tests = 0;
            
            // ============================================
            // TEST 1: Database Connection
            // ============================================
            $total_tests++;
            try {
                $conn = getDBConnection();
                $tests[] = ['name' => 'Database Connection', 'status' => 'pass', 'message' => 'Koneksi database berhasil'];
                $passed_tests++;
            } catch (Exception $e) {
                $tests[] = ['name' => 'Database Connection', 'status' => 'fail', 'message' => 'Error: ' . $e->getMessage()];
                $failed_tests++;
                $conn = null;
            }
            
            // ============================================
            // TEST 2: Check User dengan NRP 82080038
            // ============================================
            if ($conn) {
                $total_tests++;
                $nrp = '82080038';
                $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
                $stmt->bind_param("s", $nrp);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0) {
                    $user = $result->fetch_assoc();
                    $tests[] = ['name' => 'User dengan NRP 82080038', 'status' => 'pass', 
                               'message' => "User ditemukan: {$user['nama']} ({$user['pangkat_nrp']})"];
                    $passed_tests++;
                    
                    // Test password verification
                    $total_tests++;
                    if (password_verify($nrp, $user['password'])) {
                        $tests[] = ['name' => 'Password Verification NRP 82080038', 'status' => 'pass', 
                                   'message' => 'Password verification berhasil'];
                        $passed_tests++;
                    } else {
                        $tests[] = ['name' => 'Password Verification NRP 82080038', 'status' => 'fail', 
                                   'message' => 'Password verification gagal - password tidak match dengan NRP'];
                        $failed_tests++;
                    }
                } else {
                    $tests[] = ['name' => 'User dengan NRP 82080038', 'status' => 'warning', 
                               'message' => 'User dengan NRP 82080038 tidak ditemukan di database'];
                    $failed_tests++;
                }
                $stmt->close();
            }
            
            // ============================================
            // TEST 3: Check Tables
            // ============================================
            if ($conn) {
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
            
            // ============================================
            // TEST 4: Check Data
            // ============================================
            if ($conn) {
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
                
                $total_tests++;
                $result = $conn->query("SELECT COUNT(*) as total FROM kriteria");
                $count = $result->fetch_assoc()['total'];
                if ($count > 0) {
                    $tests[] = ['name' => 'Data Kriteria', 'status' => 'pass', 'message' => "$count kriteria ditemukan"];
                    $passed_tests++;
                } else {
                    $tests[] = ['name' => 'Data Kriteria', 'status' => 'warning', 'message' => 'Tidak ada data kriteria'];
                    $failed_tests++;
                }
            }
            
            // ============================================
            // TEST 5: Check Files
            // ============================================
            $files = [
                'pages/login.php',
                'pages/dashboard.php',
                'pages/objek_wisata.php',
                'pages/penilaian_form.php',
                'pages/penilaian_list.php',
                'pages/laporan.php',
                'includes/header.php',
                'includes/footer.php',
                'includes/functions.php',
                'config/config.php',
                'config/database.php',
                'assets/js/app.js',
                'assets/js/api.js',
                'assets/js/dynamic.js',
                'assets/js/penilaian_form.js'
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
            
            // ============================================
            // TEST 6: Check API Files
            // ============================================
            $api_files = [
                'api/api_base.php',
                'api/objek_wisata.php',
                'api/penilaian.php',
                'api/kriteria.php',
                'api/dashboard.php',
                'api/upload.php'
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
            
            // ============================================
            // TEST 7: Check Functions
            // ============================================
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
            
            // ============================================
            // TEST 8: Test API Endpoints (if possible)
            // ============================================
            $total_tests++;
            $tests[] = ['name' => 'API Endpoints', 'status' => 'info', 
                       'message' => 'API endpoints tersedia. Test manual diperlukan untuk verifikasi lengkap.'];
            
            // ============================================
            // Display Results
            // ============================================
            foreach ($tests as $test) {
                $class = $test['status'] == 'pass' ? 'test-pass' : 
                        ($test['status'] == 'warning' ? 'test-warning' : 
                        ($test['status'] == 'info' ? 'test-info' : 'test-fail'));
                $icon = $test['status'] == 'pass' ? 'fa-check-circle' : 
                       ($test['status'] == 'warning' ? 'fa-exclamation-triangle' : 
                       ($test['status'] == 'info' ? 'fa-info-circle' : 'fa-times-circle'));
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
                echo "<p class='mb-2'>Aplikasi siap digunakan dengan NRP: <strong>82080038</strong></p>";
                echo "<a href='pages/login.php' class='btn btn-primary me-2'><i class='fas fa-sign-in-alt me-2'></i>Login ke Aplikasi</a>";
                echo "<a href='pages/dashboard.php' class='btn btn-success'><i class='fas fa-home me-2'></i>Dashboard</a>";
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

