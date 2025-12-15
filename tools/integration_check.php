<?php
/**
 * Integration Check Tool
 * Risk Assessment Objek Wisata
 * 
 * Pemeriksaan menyeluruh integrasi aplikasi
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Integration Check - Risk Assessment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .check-item { padding: 10px; margin: 5px 0; border-left: 4px solid #ddd; }
        .check-item.success { border-left-color: #28a745; background: #d4edda; }
        .check-item.warning { border-left-color: #ffc107; background: #fff3cd; }
        .check-item.error { border-left-color: #dc3545; background: #f8d7da; }
        .check-item.info { border-left-color: #17a2b8; background: #d1ecf1; }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-check-circle me-2"></i>Integration Check - Risk Assessment</h4>
            </div>
            <div class="card-body">
                <?php
                $checks = [];
                $errors = [];
                $warnings = [];
                $success = [];
                
                // ============================================
                // 1. DATABASE CONNECTION
                // ============================================
                echo "<h5 class='mt-3'><i class='fas fa-database me-2'></i>1. Database Connection</h5>";
                try {
                    $conn = getDBConnection();
                    if ($conn) {
                        echo "<div class='check-item success'><i class='fas fa-check me-2'></i>Database connection: OK</div>";
                        $success[] = "Database connection";
                    } else {
                        echo "<div class='check-item error'><i class='fas fa-times me-2'></i>Database connection: FAILED</div>";
                        $errors[] = "Database connection failed";
                    }
                } catch (Exception $e) {
                    echo "<div class='check-item error'><i class='fas fa-times me-2'></i>Database error: " . htmlspecialchars($e->getMessage()) . "</div>";
                    $errors[] = "Database error: " . $e->getMessage();
                    $conn = null;
                }
                
                // ============================================
                // 2. DATABASE STRUCTURE
                // ============================================
                if ($conn) {
                    echo "<h5 class='mt-4'><i class='fas fa-table me-2'></i>2. Database Structure</h5>";
                    
                    $required_tables = ['users', 'objek_wisata', 'aspek', 'elemen', 'kriteria', 'penilaian', 'penilaian_detail', 'referensi_dokumen'];
                    $result = $conn->query("SHOW TABLES");
                    $existing_tables = [];
                    while ($row = $result->fetch_array()) {
                        $existing_tables[] = $row[0];
                    }
                    
                    foreach ($required_tables as $table) {
                        if (in_array($table, $existing_tables)) {
                            echo "<div class='check-item success'><i class='fas fa-check me-2'></i>Table <strong>{$table}</strong>: EXISTS</div>";
                            $success[] = "Table: $table";
                        } else {
                            echo "<div class='check-item error'><i class='fas fa-times me-2'></i>Table <strong>{$table}</strong>: MISSING</div>";
                            $errors[] = "Missing table: $table";
                        }
                    }
                    
                    // Check objek_wisata fields
                    if (in_array('objek_wisata', $existing_tables)) {
                        $fields_result = $conn->query("SHOW COLUMNS FROM objek_wisata");
                        $fields = [];
                        while ($row = $fields_result->fetch_assoc()) {
                            $fields[] = $row['Field'];
                        }
                        
                        $required_fields = ['id', 'nama', 'alamat', 'jenis', 'wilayah_hukum', 'keterangan'];
                        foreach ($required_fields as $field) {
                            if (in_array($field, $fields)) {
                                echo "<div class='check-item success'><i class='fas fa-check me-2'></i>Field <strong>objek_wisata.{$field}</strong>: EXISTS</div>";
                            } else {
                                echo "<div class='check-item warning'><i class='fas fa-exclamation-triangle me-2'></i>Field <strong>objek_wisata.{$field}</strong>: MISSING</div>";
                                $warnings[] = "Missing field: objek_wisata.$field";
                            }
                        }
                    }
                }
                
                // ============================================
                // 3. FILE STRUCTURE
                // ============================================
                echo "<h5 class='mt-4'><i class='fas fa-folder me-2'></i>3. File Structure</h5>";
                
                $required_files = [
                    'config/config.php' => 'Configuration file',
                    'config/database.php' => 'Database configuration',
                    'includes/functions.php' => 'Helper functions',
                    'includes/header.php' => 'Header template',
                    'includes/footer.php' => 'Footer template',
                    'includes/navbar.php' => 'Navigation bar',
                    'index.php' => 'Entry point',
                    'pages/login.php' => 'Login page',
                    'pages/dashboard.php' => 'Dashboard page',
                    'api/api_base.php' => 'API base',
                    'api/objek_wisata.php' => 'API objek wisata',
                    'api/penilaian.php' => 'API penilaian',
                    'assets/js/app.js' => 'Main JavaScript',
                    'assets/js/api.js' => 'API JavaScript',
                    'assets/css/custom.css' => 'Custom CSS',
                ];
                
                foreach ($required_files as $file => $desc) {
                    $filepath = __DIR__ . '/../' . $file;
                    if (file_exists($filepath)) {
                        echo "<div class='check-item success'><i class='fas fa-check me-2'></i><strong>{$file}</strong>: EXISTS</div>";
                        $success[] = "File: $file";
                    } else {
                        echo "<div class='check-item error'><i class='fas fa-times me-2'></i><strong>{$file}</strong>: MISSING</div>";
                        $errors[] = "Missing file: $file";
                    }
                }
                
                // ============================================
                // 4. DEPENDENCIES
                // ============================================
                echo "<h5 class='mt-4'><i class='fas fa-box me-2'></i>4. Dependencies</h5>";
                
                // Check TCPDF
                $tcpdf_path = __DIR__ . '/../vendor/tecnickcom/tcpdf/tcpdf.php';
                if (file_exists($tcpdf_path)) {
                    echo "<div class='check-item success'><i class='fas fa-check me-2'></i>TCPDF: INSTALLED</div>";
                    $success[] = "TCPDF installed";
                } else {
                    echo "<div class='check-item warning'><i class='fas fa-exclamation-triangle me-2'></i>TCPDF: NOT INSTALLED (PDF generation will use fallback)</div>";
                    $warnings[] = "TCPDF not installed";
                }
                
                // Check upload folder
                $upload_path = __DIR__ . '/../assets/uploads/';
                if (file_exists($upload_path) && is_writable($upload_path)) {
                    echo "<div class='check-item success'><i class='fas fa-check me-2'></i>Upload folder: EXISTS & WRITABLE</div>";
                    $success[] = "Upload folder writable";
                } else {
                    if (!file_exists($upload_path)) {
                        @mkdir($upload_path, 0755, true);
                        echo "<div class='check-item info'><i class='fas fa-info-circle me-2'></i>Upload folder: CREATED</div>";
                    } else {
                        echo "<div class='check-item warning'><i class='fas fa-exclamation-triangle me-2'></i>Upload folder: NOT WRITABLE</div>";
                        $warnings[] = "Upload folder not writable";
                    }
                }
                
                // ============================================
                // 5. API ENDPOINTS
                // ============================================
                echo "<h5 class='mt-4'><i class='fas fa-plug me-2'></i>5. API Endpoints</h5>";
                
                $api_files = [
                    'api/api_base.php',
                    'api/objek_wisata.php',
                    'api/penilaian.php',
                    'api/kriteria.php',
                    'api/dashboard.php',
                    'api/upload.php',
                    'api/health_check.php'
                ];
                
                foreach ($api_files as $api_file) {
                    $filepath = __DIR__ . '/../' . $api_file;
                    if (file_exists($filepath)) {
                        // Check if file includes api_base
                        $content = file_get_contents($filepath);
                        if (strpos($content, 'api_base.php') !== false || $api_file === 'api/api_base.php') {
                            echo "<div class='check-item success'><i class='fas fa-check me-2'></i><strong>{$api_file}</strong>: EXISTS & CONFIGURED</div>";
                            $success[] = "API: $api_file";
                        } else {
                            echo "<div class='check-item warning'><i class='fas fa-exclamation-triangle me-2'></i><strong>{$api_file}</strong>: EXISTS but may not use api_base</div>";
                            $warnings[] = "API $api_file may not use api_base";
                        }
                    } else {
                        echo "<div class='check-item error'><i class='fas fa-times me-2'></i><strong>{$api_file}</strong>: MISSING</div>";
                        $errors[] = "Missing API: $api_file";
                    }
                }
                
                // ============================================
                // 6. CONFIGURATION
                // ============================================
                echo "<h5 class='mt-4'><i class='fas fa-cog me-2'></i>6. Configuration</h5>";
                
                // Check BASE_URL
                if (defined('BASE_URL') && !empty(BASE_URL)) {
                    echo "<div class='check-item success'><i class='fas fa-check me-2'></i>BASE_URL: " . BASE_URL . "</div>";
                    $success[] = "BASE_URL defined";
                } else {
                    echo "<div class='check-item error'><i class='fas fa-times me-2'></i>BASE_URL: NOT DEFINED</div>";
                    $errors[] = "BASE_URL not defined";
                }
                
                // Check DB constants
                if (defined('DB_HOST') && defined('DB_USER') && defined('DB_NAME')) {
                    echo "<div class='check-item success'><i class='fas fa-check me-2'></i>Database constants: DEFINED</div>";
                    $success[] = "Database constants defined";
                } else {
                    echo "<div class='check-item error'><i class='fas fa-times me-2'></i>Database constants: NOT DEFINED</div>";
                    $errors[] = "Database constants not defined";
                }
                
                // Check UPLOAD_PATH
                if (defined('UPLOAD_PATH')) {
                    echo "<div class='check-item success'><i class='fas fa-check me-2'></i>UPLOAD_PATH: " . UPLOAD_PATH . "</div>";
                    $success[] = "UPLOAD_PATH defined";
                } else {
                    echo "<div class='check-item warning'><i class='fas fa-exclamation-triangle me-2'></i>UPLOAD_PATH: NOT DEFINED</div>";
                    $warnings[] = "UPLOAD_PATH not defined";
                }
                
                // ============================================
                // 7. SESSION & SECURITY
                // ============================================
                echo "<h5 class='mt-4'><i class='fas fa-shield-alt me-2'></i>7. Session & Security</h5>";
                
                if (session_status() === PHP_SESSION_ACTIVE) {
                    echo "<div class='check-item success'><i class='fas fa-check me-2'></i>Session: ACTIVE</div>";
                    $success[] = "Session active";
                } else {
                    echo "<div class='check-item warning'><i class='fas fa-exclamation-triangle me-2'></i>Session: NOT ACTIVE</div>";
                    $warnings[] = "Session not active";
                }
                
                // Check if functions.php has security functions
                $functions_content = file_get_contents(__DIR__ . '/../includes/functions.php');
                if (strpos($functions_content, 'isLoggedIn') !== false) {
                    echo "<div class='check-item success'><i class='fas fa-check me-2'></i>Security functions: AVAILABLE</div>";
                    $success[] = "Security functions available";
                } else {
                    echo "<div class='check-item error'><i class='fas fa-times me-2'></i>Security functions: MISSING</div>";
                    $errors[] = "Security functions missing";
                }
                
                // ============================================
                // SUMMARY
                // ============================================
                echo "<hr>";
                echo "<h5 class='mt-4'><i class='fas fa-chart-bar me-2'></i>Summary</h5>";
                
                echo "<div class='row'>";
                echo "<div class='col-md-4'>";
                echo "<div class='card bg-success text-white'>";
                echo "<div class='card-body text-center'>";
                echo "<h3>" . count($success) . "</h3>";
                echo "<p class='mb-0'>Success</p>";
                echo "</div></div></div>";
                
                echo "<div class='col-md-4'>";
                echo "<div class='card bg-warning text-white'>";
                echo "<div class='card-body text-center'>";
                echo "<h3>" . count($warnings) . "</h3>";
                echo "<p class='mb-0'>Warnings</p>";
                echo "</div></div></div>";
                
                echo "<div class='col-md-4'>";
                echo "<div class='card bg-danger text-white'>";
                echo "<div class='card-body text-center'>";
                echo "<h3>" . count($errors) . "</h3>";
                echo "<p class='mb-0'>Errors</p>";
                echo "</div></div></div>";
                echo "</div>";
                
                // Overall status
                if (count($errors) == 0 && count($warnings) == 0) {
                    echo "<div class='alert alert-success mt-4'><h6><i class='fas fa-check-circle me-2'></i>Integration Status: PERFECT</h6><p class='mb-0'>Semua komponen terintegrasi dengan baik!</p></div>";
                } elseif (count($errors) == 0) {
                    echo "<div class='alert alert-warning mt-4'><h6><i class='fas fa-exclamation-triangle me-2'></i>Integration Status: GOOD</h6><p class='mb-0'>Aplikasi berfungsi dengan baik, namun ada beberapa warning yang perlu diperhatikan.</p></div>";
                } else {
                    echo "<div class='alert alert-danger mt-4'><h6><i class='fas fa-times-circle me-2'></i>Integration Status: NEEDS FIX</h6><p class='mb-0'>Terdapat " . count($errors) . " error yang perlu diperbaiki sebelum aplikasi dapat berfungsi dengan baik.</p></div>";
                }
                
                if ($conn) {
                    $conn->close();
                }
                ?>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-tools me-2"></i>Quick Actions</h5>
            </div>
            <div class="card-body">
                <a href="<?php echo BASE_URL; ?>pages/dashboard.php" class="btn btn-primary me-2">
                    <i class="fas fa-home me-2"></i>Dashboard
                </a>
                <a href="<?php echo BASE_URL; ?>tools/check_database_structure.php" class="btn btn-success me-2">
                    <i class="fas fa-database me-2"></i>Check Database
                </a>
                <a href="<?php echo BASE_URL; ?>api/health_check.php" class="btn btn-info">
                    <i class="fas fa-heartbeat me-2"></i>Health Check
                </a>
            </div>
        </div>
    </div>
</body>
</html>

