<?php
/**
 * Check Errors and Warnings
 * Risk Assessment Objek Wisata
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Errors - Risk Assessment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="fas fa-bug me-2"></i>Check Errors & Warnings</h4>
        </div>
        <div class="card-body">
            <?php
            $errors = [];
            $warnings = [];
            $success = [];
            
            // Check 1: Config files
            echo "<h5 class='mt-3'><i class='fas fa-cog me-2'></i>1. Check Config Files</h5>";
            if (file_exists('config/config.php')) {
                require_once 'config/config.php';
                $success[] = "config/config.php - OK";
                echo "<div class='alert alert-success'><i class='fas fa-check me-2'></i>config/config.php ditemukan dan dapat di-load</div>";
            } else {
                $errors[] = "config/config.php tidak ditemukan";
                echo "<div class='alert alert-danger'><i class='fas fa-times me-2'></i>config/config.php tidak ditemukan</div>";
            }
            
            if (file_exists('config/database.php')) {
                require_once 'config/database.php';
                $success[] = "config/database.php - OK";
                echo "<div class='alert alert-success'><i class='fas fa-check me-2'></i>config/database.php ditemukan dan dapat di-load</div>";
            } else {
                $errors[] = "config/database.php tidak ditemukan";
                echo "<div class='alert alert-danger'><i class='fas fa-times me-2'></i>config/database.php tidak ditemukan</div>";
            }
            
            // Check 2: Database connection
            echo "<h5 class='mt-4'><i class='fas fa-database me-2'></i>2. Check Database Connection</h5>";
            if (defined('DB_HOST') && defined('DB_USER') && defined('DB_NAME')) {
                try {
                    $conn = getDBConnection();
                    $success[] = "Database connection - OK";
                    echo "<div class='alert alert-success'><i class='fas fa-check me-2'></i>Koneksi database berhasil</div>";
                    
                    // Check tables
                    $tables = ['users', 'objek_wisata', 'aspek', 'elemen', 'kriteria', 'penilaian', 'penilaian_detail'];
                    echo "<div class='table-responsive'><table class='table table-sm table-bordered'>";
                    echo "<thead><tr><th>Tabel</th><th>Status</th><th>Jumlah Data</th></tr></thead><tbody>";
                    foreach ($tables as $table) {
                        $result = $conn->query("SHOW TABLES LIKE '$table'");
                        if ($result && $result->num_rows > 0) {
                            $count_result = $conn->query("SELECT COUNT(*) as total FROM $table");
                            $count = $count_result ? $count_result->fetch_assoc()['total'] : 0;
                            $status = $count > 0 ? "<span class='badge bg-success'>OK</span>" : "<span class='badge bg-warning'>Kosong</span>";
                            echo "<tr><td>$table</td><td>$status</td><td>$count</td></tr>";
                        } else {
                            echo "<tr><td>$table</td><td><span class='badge bg-danger'>Tidak Ada</span></td><td>-</td></tr>";
                            $warnings[] = "Tabel $table tidak ditemukan";
                        }
                    }
                    echo "</tbody></table></div>";
                    $conn->close();
                } catch (Exception $e) {
                    $errors[] = "Database connection error: " . $e->getMessage();
                    echo "<div class='alert alert-danger'><i class='fas fa-times me-2'></i>Error: " . $e->getMessage() . "</div>";
                }
            } else {
                $errors[] = "Database constants tidak didefinisikan";
                echo "<div class='alert alert-danger'><i class='fas fa-times me-2'></i>Database constants tidak didefinisikan</div>";
            }
            
            // Check 3: Include files
            echo "<h5 class='mt-4'><i class='fas fa-file me-2'></i>3. Check Include Files</h5>";
            $include_files = [
                'includes/header.php',
                'includes/footer.php',
                'includes/navbar.php',
                'includes/functions.php',
                'includes/kop_surat.php'
            ];
            
            foreach ($include_files as $file) {
                if (file_exists($file)) {
                    // Try to include and check for syntax errors
                    ob_start();
                    $error_occurred = false;
                    try {
                        include_once $file;
                    } catch (Exception $e) {
                        $error_occurred = true;
                        $errors[] = "$file - Error: " . $e->getMessage();
                        echo "<div class='alert alert-danger'><i class='fas fa-times me-2'></i>$file - Error: " . $e->getMessage() . "</div>";
                    }
                    ob_end_clean();
                    
                    if (!$error_occurred) {
                        $success[] = "$file - OK";
                        echo "<div class='alert alert-success'><i class='fas fa-check me-2'></i>$file ditemukan dan valid</div>";
                    }
                } else {
                    $errors[] = "$file tidak ditemukan";
                    echo "<div class='alert alert-danger'><i class='fas fa-times me-2'></i>$file tidak ditemukan</div>";
                }
            }
            
            // Check 4: Page files
            echo "<h5 class='mt-4'><i class='fas fa-file-alt me-2'></i>4. Check Page Files</h5>";
            $page_files = [
                'pages/login.php',
                'pages/dashboard.php',
                'index.php',
                'logout.php'
            ];
            
            foreach ($page_files as $file) {
                if (file_exists($file)) {
                    $success[] = "$file - OK";
                    echo "<div class='alert alert-success'><i class='fas fa-check me-2'></i>$file ditemukan</div>";
                } else {
                    $errors[] = "$file tidak ditemukan";
                    echo "<div class='alert alert-danger'><i class='fas fa-times me-2'></i>$file tidak ditemukan</div>";
                }
            }
            
            // Check 5: Assets
            echo "<h5 class='mt-4'><i class='fas fa-folder me-2'></i>5. Check Assets</h5>";
            $asset_files = [
                'assets/css/custom.css',
                'assets/js/app.js'
            ];
            
            foreach ($asset_files as $file) {
                if (file_exists($file)) {
                    $success[] = "$file - OK";
                    echo "<div class='alert alert-success'><i class='fas fa-check me-2'></i>$file ditemukan</div>";
                } else {
                    $warnings[] = "$file tidak ditemukan";
                    echo "<div class='alert alert-warning'><i class='fas fa-exclamation-triangle me-2'></i>$file tidak ditemukan (optional)</div>";
                }
            }
            
            // Check 6: Upload folder
            echo "<h5 class='mt-4'><i class='fas fa-upload me-2'></i>6. Check Upload Folder</h5>";
            if (file_exists('assets/uploads/')) {
                if (is_writable('assets/uploads/')) {
                    $success[] = "assets/uploads/ - Writable";
                    echo "<div class='alert alert-success'><i class='fas fa-check me-2'></i>Folder assets/uploads/ dapat ditulis</div>";
                } else {
                    $warnings[] = "assets/uploads/ tidak writable";
                    echo "<div class='alert alert-warning'><i class='fas fa-exclamation-triangle me-2'></i>Folder assets/uploads/ tidak dapat ditulis</div>";
                }
            } else {
                $warnings[] = "assets/uploads/ tidak ditemukan";
                echo "<div class='alert alert-warning'><i class='fas fa-exclamation-triangle me-2'></i>Folder assets/uploads/ tidak ditemukan</div>";
            }
            
            // Summary
            echo "<div class='alert alert-info mt-4'>";
            echo "<h5><i class='fas fa-info-circle me-2'></i>Summary</h5>";
            echo "<ul class='mb-0'>";
            echo "<li><strong class='text-success'>Success:</strong> " . count($success) . " items</li>";
            echo "<li><strong class='text-warning'>Warnings:</strong> " . count($warnings) . " items</li>";
            echo "<li><strong class='text-danger'>Errors:</strong> " . count($errors) . " items</li>";
            echo "</ul>";
            echo "</div>";
            
            if (count($errors) == 0 && count($warnings) == 0) {
                echo "<div class='alert alert-success mt-3'>";
                echo "<h5><i class='fas fa-check-circle me-2'></i>Semua Check Berhasil!</h5>";
                echo "<p class='mb-2'>Aplikasi siap digunakan.</p>";
                echo "<a href='pages/login.php' class='btn btn-primary'><i class='fas fa-sign-in-alt me-2'></i>Login ke Aplikasi</a> ";
                echo "<a href='test_connection.php' class='btn btn-info'><i class='fas fa-database me-2'></i>Test Database</a>";
                echo "</div>";
            } else {
                echo "<div class='alert alert-warning mt-3'>";
                echo "<h5><i class='fas fa-exclamation-triangle me-2'></i>Ada Beberapa Issue</h5>";
                if (count($errors) > 0) {
                    echo "<p><strong>Errors yang perlu diperbaiki:</strong></p><ul>";
                    foreach ($errors as $error) {
                        echo "<li>$error</li>";
                    }
                    echo "</ul>";
                }
                if (count($warnings) > 0) {
                    echo "<p><strong>Warnings (optional):</strong></p><ul>";
                    foreach ($warnings as $warning) {
                        echo "<li>$warning</li>";
                    }
                    echo "</ul>";
                }
                echo "</div>";
            }
            ?>
        </div>
    </div>
</div>
</body>
</html>

