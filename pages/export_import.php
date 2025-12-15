<?php
/**
 * Export/Import Data
 * Risk Assessment Objek Wisata
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

$current_user = getCurrentUser();
$conn = getDBConnection();

$action = $_GET['action'] ?? 'menu';
$error = '';
$success = '';

// Handle export
if ($action === 'export_objek_wisata') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="data_objek_wisata_' . date('Ymd') . '.csv"');
    
    // Output BOM for UTF-8
    echo "\xEF\xBB\xBF";
    
    // Header CSV
    $headers = ['No', 'Nama', 'Alamat', 'Jenis', 'Wilayah Hukum', 'Keterangan', 'Tanggal Dibuat'];
    echo implode(',', array_map(function($h) {
        return '"' . str_replace('"', '""', $h) . '"';
    }, $headers)) . "\n";
    
    // Data
    $result = $conn->query("SELECT * FROM objek_wisata ORDER BY id");
    $no = 1;
    while ($row = $result->fetch_assoc()) {
        $data = [
            $no++,
            $row['nama'],
            $row['alamat'] ?? '',
            $row['jenis'] ?? '',
            $row['wilayah_hukum'] ?? '',
            $row['keterangan'] ?? '',
            $row['created_at']
        ];
        echo implode(',', array_map(function($d) {
            return '"' . str_replace('"', '""', $d) . '"';
        }, $data)) . "\n";
    }
    
    $conn->close();
    exit;
}

// Handle import
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'import_objek_wisata') {
    if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        $error = 'File tidak ditemukan atau terjadi error saat upload';
    } else {
        $file = $_FILES['file'];
        $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        if ($file_extension !== 'csv') {
            $error = 'Format file harus CSV';
        } else {
            $handle = fopen($file['tmp_name'], 'r');
            if ($handle === false) {
                $error = 'Gagal membaca file';
            } else {
                // Skip BOM if exists
                $first_line = fgets($handle);
                if (substr($first_line, 0, 3) === "\xEF\xBB\xBF") {
                    $first_line = substr($first_line, 3);
                }
                
                // Skip header
                $line = 0;
                $imported = 0;
                $skipped = 0;
                
                while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                    $line++;
                    if ($line === 1) continue; // Skip header
                    
                    if (count($data) < 2) continue; // Skip invalid rows
                    
                    $nama = trim($data[1] ?? '');
                    if (empty($nama)) {
                        $skipped++;
                        continue;
                    }
                    
                    $alamat = trim($data[2] ?? '');
                    $jenis = trim($data[3] ?? '');
                    $wilayah_hukum = trim($data[4] ?? '');
                    $keterangan = trim($data[5] ?? '');
                    
                    // Check if exists
                    $check_stmt = $conn->prepare("SELECT id FROM objek_wisata WHERE nama = ?");
                    $check_stmt->bind_param("s", $nama);
                    $check_stmt->execute();
                    $check_result = $check_stmt->get_result();
                    
                    if ($check_result->num_rows > 0) {
                        $skipped++;
                        $check_stmt->close();
                        continue;
                    }
                    $check_stmt->close();
                    
                    // Insert
                    $stmt = $conn->prepare("INSERT INTO objek_wisata (nama, alamat, jenis, wilayah_hukum, keterangan) VALUES (?, ?, ?, ?, ?)");
                    $stmt->bind_param("sssss", $nama, $alamat, $jenis, $wilayah_hukum, $keterangan);
                    
                    if ($stmt->execute()) {
                        $imported++;
                    } else {
                        $skipped++;
                    }
                    $stmt->close();
                }
                
                fclose($handle);
                $success = "Import berhasil! $imported data ditambahkan, $skipped data dilewati.";
            }
        }
    }
}

$page_title = 'Export/Import Data';
$show_navbar = true;
include __DIR__ . '/../includes/header.php';
?>

<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0">
                <i class="fas fa-file-export me-2"></i>Export/Import Data
            </h2>
            <p class="text-muted">Export dan import data objek wisata</p>
        </div>
    </div>
    
    <?php if ($error): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i><?php echo htmlspecialchars($error); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    
    <?php if ($success): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i><?php echo htmlspecialchars($success); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    
    <div class="row g-3">
        <!-- Export Section -->
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-download me-2"></i>Export Data</h5>
                </div>
                <div class="card-body">
                    <p>Export data objek wisata ke format CSV</p>
                    <a href="?action=export_objek_wisata" class="btn btn-primary">
                        <i class="fas fa-file-csv me-2"></i>Export Objek Wisata (CSV)
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Import Section -->
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-upload me-2"></i>Import Data</h5>
                </div>
                <div class="card-body">
                    <p>Import data objek wisata dari file CSV</p>
                    <form method="POST" enctype="multipart/form-data" action="?action=import_objek_wisata">
                        <div class="mb-3">
                            <label for="file" class="form-label">Pilih File CSV</label>
                            <input type="file" class="form-control" id="file" name="file" accept=".csv" required>
                            <small class="text-muted">Format: CSV dengan header: No, Nama, Alamat, Jenis, Wilayah Hukum, Keterangan</small>
                        </div>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-upload me-2"></i>Import Data
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Info Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Petunjuk</h5>
                </div>
                <div class="card-body">
                    <h6>Export:</h6>
                    <ul>
                        <li>Klik tombol "Export Objek Wisata" untuk mengunduh data dalam format CSV</li>
                        <li>File CSV dapat dibuka dengan Excel, Google Sheets, atau text editor</li>
                    </ul>
                    
                    <h6 class="mt-3">Import:</h6>
                    <ul>
                        <li>Format file harus CSV dengan encoding UTF-8</li>
                        <li>Header kolom: No, Nama, Alamat, Jenis, Wilayah Hukum, Keterangan</li>
                        <li>Kolom "Nama" wajib diisi, kolom lain opsional</li>
                        <li>Data dengan nama yang sudah ada akan dilewati (tidak diimport)</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>

