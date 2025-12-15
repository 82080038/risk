<?php
/**
 * Check PHP Extensions
 * Risk Assessment Objek Wisata
 * 
 * Script untuk memeriksa PHP extensions yang diperlukan
 */

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check PHP Extensions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .extension-item { padding: 10px; margin: 5px 0; border-left: 4px solid #ddd; }
        .extension-item.success { border-left-color: #28a745; background: #d4edda; }
        .extension-item.warning { border-left-color: #ffc107; background: #fff3cd; }
        .extension-item.error { border-left-color: #dc3545; background: #f8d7da; }
        .extension-item.info { border-left-color: #17a2b8; background: #d1ecf1; }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-check-circle me-2"></i>Check PHP Extensions</h4>
            </div>
            <div class="card-body">
                <?php
                $extensions = [];
                $errors = [];
                $warnings = [];
                $success = [];
                
                // PHP Version
                echo "<h5 class='mt-3'><i class='fas fa-info-circle me-2'></i>PHP Information</h5>";
                echo "<div class='extension-item info'>";
                echo "<i class='fas fa-check me-2'></i><strong>PHP Version:</strong> " . PHP_VERSION;
                echo "</div>";
                
                // Required Extensions
                echo "<h5 class='mt-4'><i class='fas fa-plug me-2'></i>Required Extensions</h5>";
                
                $required_extensions = [
                    'gd' => [
                        'name' => 'GD Library',
                        'description' => 'Image processing untuk TCPDF dan manipulasi gambar',
                        'required' => true,
                        'functions' => ['imagecreate', 'imagejpeg', 'imagepng', 'imagettftext']
                    ],
                    'mbstring' => [
                        'name' => 'Multibyte String',
                        'description' => 'String handling untuk UTF-8 (diperlukan TCPDF)',
                        'required' => true,
                        'functions' => ['mb_strlen', 'mb_substr', 'mb_convert_encoding']
                    ],
                    'zlib' => [
                        'name' => 'Zlib',
                        'description' => 'Compression untuk PDF (opsional tapi disarankan)',
                        'required' => false,
                        'functions' => ['gzcompress', 'gzuncompress']
                    ],
                    'curl' => [
                        'name' => 'cURL',
                        'description' => 'HTTP client (opsional)',
                        'required' => false,
                        'functions' => ['curl_init', 'curl_exec']
                    ],
                    'mysqli' => [
                        'name' => 'MySQLi',
                        'description' => 'Database connection (WAJIB)',
                        'required' => true,
                        'functions' => ['mysqli_connect', 'mysqli_query']
                    ],
                    'json' => [
                        'name' => 'JSON',
                        'description' => 'JSON encoding/decoding (WAJIB)',
                        'required' => true,
                        'functions' => ['json_encode', 'json_decode']
                    ],
                    'session' => [
                        'name' => 'Session',
                        'description' => 'Session management (WAJIB)',
                        'required' => true,
                        'functions' => ['session_start', 'session_regenerate_id']
                    ]
                ];
                
                foreach ($required_extensions as $ext => $info) {
                    $loaded = extension_loaded($ext);
                    $functions_ok = true;
                    $missing_functions = [];
                    
                    // Check functions
                    if (isset($info['functions'])) {
                        foreach ($info['functions'] as $func) {
                            if (!function_exists($func)) {
                                $functions_ok = false;
                                $missing_functions[] = $func;
                            }
                        }
                    }
                    
                    if ($loaded && $functions_ok) {
                        echo "<div class='extension-item success'>";
                        echo "<i class='fas fa-check me-2'></i><strong>{$info['name']}</strong> ({$ext}): ";
                        echo "<span class='badge bg-success'>LOADED</span>";
                        echo "<br><small class='text-muted'>{$info['description']}</small>";
                        echo "</div>";
                        $success[] = $info['name'];
                    } elseif ($loaded && !$functions_ok) {
                        echo "<div class='extension-item warning'>";
                        echo "<i class='fas fa-exclamation-triangle me-2'></i><strong>{$info['name']}</strong> ({$ext}): ";
                        echo "<span class='badge bg-warning'>LOADED BUT FUNCTIONS MISSING</span>";
                        echo "<br><small class='text-muted'>{$info['description']}</small>";
                        echo "<br><small class='text-danger'>Missing functions: " . implode(', ', $missing_functions) . "</small>";
                        echo "</div>";
                        $warnings[] = $info['name'] . " - functions missing";
                    } else {
                        $class = $info['required'] ? 'error' : 'warning';
                        $icon = $info['required'] ? 'times' : 'exclamation-triangle';
                        echo "<div class='extension-item {$class}'>";
                        echo "<i class='fas fa-{$icon} me-2'></i><strong>{$info['name']}</strong> ({$ext}): ";
                        echo "<span class='badge bg-" . ($info['required'] ? 'danger' : 'warning') . "'>NOT LOADED</span>";
                        echo "<br><small class='text-muted'>{$info['description']}</small>";
                        if ($info['required']) {
                            echo "<br><small class='text-danger'><strong>WAJIB DIAKTIFKAN!</strong></small>";
                        }
                        echo "</div>";
                        if ($info['required']) {
                            $errors[] = $info['name'] . " - REQUIRED";
                        } else {
                            $warnings[] = $info['name'] . " - recommended";
                        }
                    }
                }
                
                // GD Library Details
                if (extension_loaded('gd')) {
                    echo "<h5 class='mt-4'><i class='fas fa-image me-2'></i>GD Library Details</h5>";
                    $gd_info = gd_info();
                    echo "<div class='table-responsive'>";
                    echo "<table class='table table-bordered table-sm'>";
                    echo "<thead><tr><th>Feature</th><th>Status</th></tr></thead>";
                    echo "<tbody>";
                    
                    $gd_features = [
                        'GD Version' => $gd_info['GD Version'] ?? 'Unknown',
                        'FreeType Support' => isset($gd_info['FreeType Support']) && $gd_info['FreeType Support'] ? 'Yes' : 'No',
                        'JPEG Support' => isset($gd_info['JPEG Support']) && $gd_info['JPEG Support'] ? 'Yes' : 'No',
                        'PNG Support' => isset($gd_info['PNG Support']) && $gd_info['PNG Support'] ? 'Yes' : 'No',
                        'GIF Support' => isset($gd_info['GIF Support']) && $gd_info['GIF Support'] ? 'Yes' : 'No',
                        'WebP Support' => isset($gd_info['WebP Support']) && $gd_info['WebP Support'] ? 'Yes' : 'No'
                    ];
                    
                    foreach ($gd_features as $feature => $status) {
                        $status_class = ($status === 'Yes' || strpos($status, '2.') === 0) ? 'success' : 'warning';
                        echo "<tr>";
                        echo "<td><strong>{$feature}</strong></td>";
                        echo "<td><span class='badge bg-{$status_class}'>{$status}</span></td>";
                        echo "</tr>";
                    }
                    
                    echo "</tbody></table>";
                    echo "</div>";
                }
                
                // Summary
                echo "<hr>";
                echo "<h5 class='mt-4'><i class='fas fa-chart-bar me-2'></i>Summary</h5>";
                
                echo "<div class='row'>";
                echo "<div class='col-md-4'>";
                echo "<div class='card bg-success text-white'>";
                echo "<div class='card-body text-center'>";
                echo "<h3>" . count($success) . "</h3>";
                echo "<p class='mb-0'>Loaded</p>";
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
                    echo "<div class='alert alert-success mt-4'><h6><i class='fas fa-check-circle me-2'></i>All Extensions: OK</h6><p class='mb-0'>Semua extension yang diperlukan sudah terinstall dan aktif!</p></div>";
                } elseif (count($errors) == 0) {
                    echo "<div class='alert alert-warning mt-4'><h6><i class='fas fa-exclamation-triangle me-2'></i>Extensions: GOOD</h6><p class='mb-0'>Extension wajib sudah terinstall, namun ada beberapa extension opsional yang belum aktif.</p></div>";
                } else {
                    echo "<div class='alert alert-danger mt-4'><h6><i class='fas fa-times-circle me-2'></i>Extensions: NEEDS FIX</h6><p class='mb-0'>Terdapat " . count($errors) . " extension wajib yang belum terinstall. Silakan aktifkan di php.ini.</p></div>";
                }
                
                // Instructions
                if (count($errors) > 0 || !extension_loaded('gd')) {
                    echo "<div class='alert alert-info mt-4'>";
                    echo "<h6><i class='fas fa-info-circle me-2'></i>Cara Mengaktifkan Extension</h6>";
                    echo "<ol>";
                    echo "<li>Buka file <strong>php.ini</strong> (biasanya di <code>C:\\xampp\\php\\php.ini</code>)</li>";
                    echo "<li>Cari baris yang berisi <code>;extension=gd</code></li>";
                    echo "<li>Hapus tanda <code>;</code> di depan <code>extension=gd</code> menjadi <code>extension=gd</code></li>";
                    echo "<li>Simpan file php.ini</li>";
                    echo "<li>Restart Apache di XAMPP Control Panel</li>";
                    echo "<li>Refresh halaman ini untuk verifikasi</li>";
                    echo "</ol>";
                    echo "<p class='mb-0'><strong>Catatan:</strong> Setelah mengaktifkan extension, pastikan untuk restart Apache!</p>";
                    echo "</div>";
                }
                
                // PHP Info Link
                echo "<div class='mt-4'>";
                echo "<a href='phpinfo.php' target='_blank' class='btn btn-info me-2'>";
                echo "<i class='fas fa-info-circle me-2'></i>View phpinfo()</a>";
                echo "<a href='" . BASE_URL . "pages/dashboard.php' class='btn btn-primary'>";
                echo "<i class='fas fa-home me-2'></i>Dashboard</a>";
                echo "</div>";
                ?>
            </div>
        </div>
    </div>
</body>
</html>

