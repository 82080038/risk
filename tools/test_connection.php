<?php
/**
 * Test Koneksi Database
 */

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/config.php';

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Koneksi Database</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="fas fa-database me-2"></i>Test Koneksi Database</h4>
        </div>
        <div class="card-body">
            <?php
            try {
                // Test koneksi
                $conn = getDBConnection();
                echo "<div class='alert alert-success'>";
                echo "<i class='fas fa-check-circle me-2'></i><strong>Koneksi Database Berhasil!</strong>";
                echo "</div>";
                
                // Test query
                echo "<h5 class='mt-4'>Test Query:</h5>";
                echo "<div class='table-responsive'><table class='table table-bordered table-sm'>";
                echo "<thead><tr><th>Test</th><th>Hasil</th><th>Status</th></tr></thead><tbody>";
                
                // Test 1: Cek tabel users
                $result = $conn->query("SELECT COUNT(*) as total FROM users");
                if ($result) {
                    $row = $result->fetch_assoc();
                    $status = $row['total'] > 0 ? "<span class='badge bg-success'>OK</span>" : "<span class='badge bg-warning'>Kosong</span>";
                    echo "<tr><td>Tabel users</td><td>{$row['total']} data</td><td>$status</td></tr>";
                } else {
                    echo "<tr><td>Tabel users</td><td>Error</td><td><span class='badge bg-danger'>Gagal</span></td></tr>";
                }
                
                // Test 2: Cek tabel objek_wisata
                $result = $conn->query("SELECT COUNT(*) as total FROM objek_wisata");
                if ($result) {
                    $row = $result->fetch_assoc();
                    $status = $row['total'] > 0 ? "<span class='badge bg-success'>OK</span>" : "<span class='badge bg-warning'>Kosong</span>";
                    echo "<tr><td>Tabel objek_wisata</td><td>{$row['total']} data</td><td>$status</td></tr>";
                } else {
                    echo "<tr><td>Tabel objek_wisata</td><td>Error</td><td><span class='badge bg-danger'>Gagal</td></tr>";
                }
                
                // Test 3: Cek tabel aspek
                $result = $conn->query("SELECT COUNT(*) as total FROM aspek");
                if ($result) {
                    $row = $result->fetch_assoc();
                    $status = $row['total'] > 0 ? "<span class='badge bg-success'>OK</span>" : "<span class='badge bg-warning'>Kosong</span>";
                    echo "<tr><td>Tabel aspek</td><td>{$row['total']} data</td><td>$status</td></tr>";
                } else {
                    echo "<tr><td>Tabel aspek</td><td>Error</td><td><span class='badge bg-danger'>Gagal</span></td></tr>";
                }
                
                // Test 4: Cek tabel kriteria
                $result = $conn->query("SELECT COUNT(*) as total FROM kriteria");
                if ($result) {
                    $row = $result->fetch_assoc();
                    $status = $row['total'] > 0 ? "<span class='badge bg-success'>OK</span>" : "<span class='badge bg-warning'>Kosong</span>";
                    echo "<tr><td>Tabel kriteria</td><td>{$row['total']} data</td><td>$status</td></tr>";
                } else {
                    echo "<tr><td>Tabel kriteria</td><td>Error</td><td><span class='badge bg-danger'>Gagal</span></td></tr>";
                }
                
                // Test 5: Cek user untuk login
                $result = $conn->query("SELECT username, nama, role FROM users LIMIT 3");
                if ($result && $result->num_rows > 0) {
                    echo "<tr><td colspan='3'><strong>Contoh User untuk Login:</strong></td></tr>";
                    while ($user = $result->fetch_assoc()) {
                        echo "<tr><td colspan='3'>";
                        echo "Username: <strong>{$user['username']}</strong> | ";
                        echo "Nama: {$user['nama']} | ";
                        echo "Role: <span class='badge bg-info'>{$user['role']}</span>";
                        echo "</td></tr>";
                    }
                }
                
                echo "</tbody></table></div>";
                
                // Info database
                echo "<div class='alert alert-info mt-4'>";
                echo "<h5><i class='fas fa-info-circle me-2'></i>Informasi Database</h5>";
                echo "<ul class='mb-0'>";
                echo "<li>Host: " . DB_HOST . "</li>";
                echo "<li>Database: " . DB_NAME . "</li>";
                echo "<li>User: " . DB_USER . "</li>";
                echo "<li>Charset: UTF-8</li>";
                echo "</ul>";
                echo "</div>";
                
                $conn->close();
                
                echo "<div class='alert alert-success mt-3'>";
                echo "<h5><i class='fas fa-check-circle me-2'></i>Semua Test Berhasil!</h5>";
                echo "<p class='mb-2'>Database siap digunakan.</p>";
                echo "<a href='pages/login.php' class='btn btn-primary'><i class='fas fa-sign-in-alt me-2'></i>Login ke Aplikasi</a> ";
                echo "<a href='index.php' class='btn btn-secondary'><i class='fas fa-home me-2'></i>Ke Halaman Utama</a>";
                echo "</div>";
                
            } catch (Exception $e) {
                echo "<div class='alert alert-danger'>";
                echo "<i class='fas fa-times-circle me-2'></i><strong>Error:</strong> " . $e->getMessage();
                echo "</div>";
                
                echo "<div class='alert alert-warning mt-3'>";
                echo "<h5><i class='fas fa-exclamation-triangle me-2'></i>Troubleshooting</h5>";
                echo "<ol>";
                echo "<li>Pastikan MySQL berjalan di XAMPP</li>";
                echo "<li>Import database menggunakan: <a href='setup_database.php' target='_blank'>setup_database.php</a></li>";
                echo "<li>Cek konfigurasi di <code>config/database.php</code></li>";
                echo "</ol>";
                echo "</div>";
            }
            ?>
        </div>
    </div>
</div>
</body>
</html>

