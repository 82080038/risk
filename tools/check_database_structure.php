<?php
/**
 * Check Database Structure
 * Risk Assessment Objek Wisata
 * 
 * Script untuk memeriksa struktur database dan memastikan semua field sudah benar
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/config.php';

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Database Structure</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">
    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-database me-2"></i>Check Database Structure</h4>
            </div>
            <div class="card-body">
                <?php
                $conn = getDBConnection();
                $errors = [];
                $warnings = [];
                $success = [];
                
                // Check 1: Database exists
                echo "<h5 class='mt-3'><i class='fas fa-database me-2'></i>1. Database Connection</h5>";
                if ($conn) {
                    echo "<div class='alert alert-success'><i class='fas fa-check me-2'></i>Database connection: OK</div>";
                    $success[] = "Database connection";
                } else {
                    echo "<div class='alert alert-danger'><i class='fas fa-times me-2'></i>Database connection: FAILED</div>";
                    $errors[] = "Database connection failed";
                    exit;
                }
                
                // Check 2: Table objek_wisata structure
                echo "<h5 class='mt-4'><i class='fas fa-table me-2'></i>2. Table: objek_wisata</h5>";
                $table_check = $conn->query("SHOW COLUMNS FROM objek_wisata");
                if ($table_check) {
                    $columns = [];
                    while ($row = $table_check->fetch_assoc()) {
                        $columns[$row['Field']] = $row;
                    }
                    
                    $required_fields = [
                        'id' => 'INT PRIMARY KEY AUTO_INCREMENT',
                        'nama' => 'VARCHAR(255) NOT NULL',
                        'alamat' => 'TEXT',
                        'jenis' => 'VARCHAR(100)',
                        'wilayah_hukum' => 'VARCHAR(100)',
                        'keterangan' => 'VARCHAR(255)',
                        'created_at' => 'TIMESTAMP',
                        'updated_at' => 'TIMESTAMP'
                    ];
                    
                    echo "<table class='table table-bordered table-sm'>";
                    echo "<thead><tr><th>Field</th><th>Required</th><th>Status</th><th>Type</th></tr></thead>";
                    echo "<tbody>";
                    
                    foreach ($required_fields as $field => $type) {
                        if (isset($columns[$field])) {
                            echo "<tr class='table-success'>";
                            echo "<td><strong>{$field}</strong></td>";
                            echo "<td>Required</td>";
                            echo "<td><span class='badge bg-success'>OK</span></td>";
                            echo "<td><small>{$columns[$field]['Type']}</small></td>";
                            echo "</tr>";
                            $success[] = "Field: $field";
                        } else {
                            echo "<tr class='table-danger'>";
                            echo "<td><strong>{$field}</strong></td>";
                            echo "<td>Required</td>";
                            echo "<td><span class='badge bg-danger'>MISSING</span></td>";
                            echo "<td><small>{$type}</small></td>";
                            echo "</tr>";
                            $errors[] = "Missing field: $field";
                        }
                    }
                    
                    echo "</tbody></table>";
                } else {
                    echo "<div class='alert alert-danger'><i class='fas fa-times me-2'></i>Table objek_wisata tidak ditemukan!</div>";
                    $errors[] = "Table objek_wisata tidak ada";
                }
                
                // Check 3: All required tables
                echo "<h5 class='mt-4'><i class='fas fa-list me-2'></i>3. All Required Tables</h5>";
                $required_tables = [
                    'users',
                    'objek_wisata',
                    'aspek',
                    'elemen',
                    'kriteria',
                    'penilaian',
                    'penilaian_detail',
                    'referensi_dokumen'
                ];
                
                $result = $conn->query("SHOW TABLES");
                $existing_tables = [];
                while ($row = $result->fetch_array()) {
                    $existing_tables[] = $row[0];
                }
                
                echo "<table class='table table-bordered table-sm'>";
                echo "<thead><tr><th>Table</th><th>Status</th></tr></thead>";
                echo "<tbody>";
                
                foreach ($required_tables as $table) {
                    if (in_array($table, $existing_tables)) {
                        echo "<tr class='table-success'>";
                        echo "<td><strong>{$table}</strong></td>";
                        echo "<td><span class='badge bg-success'>EXISTS</span></td>";
                        echo "</tr>";
                        $success[] = "Table: $table";
                    } else {
                        echo "<tr class='table-danger'>";
                        echo "<td><strong>{$table}</strong></td>";
                        echo "<td><span class='badge bg-danger'>MISSING</span></td>";
                        echo "</tr>";
                        $errors[] = "Missing table: $table";
                    }
                }
                
                echo "</tbody></table>";
                
                // Check 4: Indexes
                echo "<h5 class='mt-4'><i class='fas fa-key me-2'></i>4. Indexes on objek_wisata</h5>";
                $index_result = $conn->query("SHOW INDEXES FROM objek_wisata");
                $indexes = [];
                while ($row = $index_result->fetch_assoc()) {
                    $indexes[] = $row['Key_name'];
                }
                
                $required_indexes = ['PRIMARY', 'idx_nama', 'idx_jenis', 'idx_wilayah'];
                echo "<ul>";
                foreach ($required_indexes as $idx) {
                    if (in_array($idx, $indexes)) {
                        echo "<li class='text-success'><i class='fas fa-check me-2'></i>Index: <strong>{$idx}</strong> - OK</li>";
                    } else {
                        echo "<li class='text-warning'><i class='fas fa-exclamation-triangle me-2'></i>Index: <strong>{$idx}</strong> - Missing (optional)</li>";
                        $warnings[] = "Missing index: $idx";
                    }
                }
                echo "</ul>";
                
                // Summary
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
                
                // Action buttons
                if (count($errors) > 0) {
                    echo "<div class='alert alert-danger mt-4'>";
                    echo "<h6><i class='fas fa-exclamation-circle me-2'></i>Errors Found:</h6>";
                    echo "<ul>";
                    foreach ($errors as $error) {
                        echo "<li>{$error}</li>";
                    }
                    echo "</ul>";
                    echo "<p class='mb-0'><strong>Action:</strong> Jalankan script SQL untuk memperbaiki struktur database.</p>";
                    echo "</div>";
                    
                    echo "<div class='mt-3'>";
                    echo "<a href='http://localhost/phpmyadmin/index.php?route=/database/sql&db=risk_assessment_db' target='_blank' class='btn btn-primary me-2'>";
                    echo "<i class='fas fa-external-link-alt me-2'></i>Buka phpMyAdmin SQL</a>";
                    echo "<button onclick='copySQL()' class='btn btn-secondary'>";
                    echo "<i class='fas fa-copy me-2'></i>Copy SQL Script</button>";
                    echo "</div>";
                    
                    // Generate SQL fix
                    $sql_fix = "-- Fix Database Structure\n";
                    $sql_fix .= "USE risk_assessment_db;\n\n";
                    
                    if (!isset($columns['jenis'])) {
                        $sql_fix .= "ALTER TABLE objek_wisata ADD COLUMN jenis VARCHAR(100) NULL AFTER alamat;\n";
                    }
                    if (!isset($columns['wilayah_hukum'])) {
                        $sql_fix .= "ALTER TABLE objek_wisata ADD COLUMN wilayah_hukum VARCHAR(100) NULL AFTER jenis;\n";
                    }
                    if (!isset($columns['keterangan'])) {
                        $sql_fix .= "ALTER TABLE objek_wisata ADD COLUMN keterangan VARCHAR(255) NULL AFTER wilayah_hukum;\n";
                    }
                    
                    // Add indexes if missing
                    if (!in_array('idx_jenis', $indexes)) {
                        $sql_fix .= "ALTER TABLE objek_wisata ADD INDEX idx_jenis (jenis);\n";
                    }
                    if (!in_array('idx_wilayah', $indexes)) {
                        $sql_fix .= "ALTER TABLE objek_wisata ADD INDEX idx_wilayah (wilayah_hukum);\n";
                    }
                    
                    echo "<textarea id='sqlScript' class='form-control mt-3' rows='10' style='display:none;'>" . htmlspecialchars($sql_fix) . "</textarea>";
                    echo "<script>
                    function copySQL() {
                        var textarea = document.getElementById('sqlScript');
                        textarea.style.display = 'block';
                        textarea.select();
                        document.execCommand('copy');
                        textarea.style.display = 'none';
                        alert('SQL Script telah di-copy ke clipboard!');
                    }
                    </script>";
                } else {
                    echo "<div class='alert alert-success mt-4'>";
                    echo "<h6><i class='fas fa-check-circle me-2'></i>Database Structure: OK</h6>";
                    echo "<p class='mb-0'>Semua field dan table yang diperlukan sudah ada.</p>";
                    echo "</div>";
                }
                
                $conn->close();
                ?>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Quick Actions</h5>
            </div>
            <div class="card-body">
                <a href="http://localhost/phpmyadmin/index.php?route=/database/sql&db=risk_assessment_db" target="_blank" class="btn btn-primary me-2">
                    <i class="fas fa-external-link-alt me-2"></i>Buka phpMyAdmin SQL
                </a>
                <a href="http://localhost/RISK/pages/dashboard.php" class="btn btn-success">
                    <i class="fas fa-home me-2"></i>Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
</body>
</html>

