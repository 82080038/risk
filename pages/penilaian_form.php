<?php
// Start session dulu sebelum require functions
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

$current_user = getCurrentUser();
$conn = getDBConnection();

$action = $_GET['action'] ?? 'new';
$penilaian_id = $_GET['id'] ?? null;
$objek_id = $_GET['objek_id'] ?? null;

// Get objek wisata
$objek = null;
if ($objek_id) {
    $stmt = $conn->prepare("SELECT * FROM objek_wisata WHERE id = ?");
    $stmt->bind_param("i", $objek_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $objek = $result->fetch_assoc();
    }
    $stmt->close();
}

// Get all aspek dengan elemen dan kriteria
$aspek_sql = "
    SELECT a.*, 
           GROUP_CONCAT(DISTINCT e.id ORDER BY e.urutan) as elemen_ids
    FROM aspek a
    LEFT JOIN elemen e ON a.id = e.aspek_id
    GROUP BY a.id
    ORDER BY a.urutan
";
$aspek_result = $conn->query($aspek_sql);

$aspek_list = [];
while ($aspek = $aspek_result->fetch_assoc()) {
    // Get elemen
    $elemen_sql = "
        SELECT e.*
        FROM elemen e
        WHERE e.aspek_id = ?
        ORDER BY e.urutan
    ";
    $elemen_stmt = $conn->prepare($elemen_sql);
    $elemen_stmt->bind_param("i", $aspek['id']);
    $elemen_stmt->execute();
    $elemen_result = $elemen_stmt->get_result();
    
    $elemen_list = [];
    while ($elemen = $elemen_result->fetch_assoc()) {
        // Get kriteria
        $kriteria_sql = "SELECT * FROM kriteria WHERE elemen_id = ? ORDER BY urutan";
        $kriteria_stmt = $conn->prepare($kriteria_sql);
        $kriteria_stmt->bind_param("i", $elemen['id']);
        $kriteria_stmt->execute();
        $kriteria_result = $kriteria_stmt->get_result();
        
        $kriteria_list = [];
        while ($kriteria = $kriteria_result->fetch_assoc()) {
            $kriteria_list[] = $kriteria;
        }
        
        $elemen['kriteria'] = $kriteria_list;
        $elemen_list[] = $elemen;
        
        $kriteria_stmt->close();
    }
    
    $aspek['elemen'] = $elemen_list;
    $aspek_list[] = $aspek;
    
    $elemen_stmt->close();
}

// Get existing penilaian jika edit
$penilaian_data = null;
$penilaian_details = [];
if ($penilaian_id) {
    $stmt = $conn->prepare("SELECT * FROM penilaian WHERE id = ?");
    $stmt->bind_param("i", $penilaian_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $penilaian_data = $result->fetch_assoc();
        $objek_id = $penilaian_data['objek_wisata_id'];
        
        // Get objek
        $objek_stmt = $conn->prepare("SELECT * FROM objek_wisata WHERE id = ?");
        $objek_stmt->bind_param("i", $objek_id);
        $objek_stmt->execute();
        $objek_result = $objek_stmt->get_result();
        $objek = $objek_result->fetch_assoc();
        $objek_stmt->close();
        
        // Get details
        $detail_stmt = $conn->prepare("SELECT * FROM penilaian_detail WHERE penilaian_id = ?");
        $detail_stmt->bind_param("i", $penilaian_id);
        $detail_stmt->execute();
        $detail_result = $detail_stmt->get_result();
        
        while ($detail = $detail_result->fetch_assoc()) {
            $penilaian_details[$detail['kriteria_id']] = $detail;
        }
        $detail_stmt->close();
    }
    $stmt->close();
}

$conn->close();

$page_title = $action == 'new' ? 'Penilaian Baru' : 'Edit Penilaian';
$show_navbar = true;
$additional_css = ['https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.css'];
include __DIR__ . '/../includes/header.php';
?>

<div class="container-fluid mt-4">
    <!-- Progress Bar -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="mb-0">Progress Penilaian</h5>
                <span id="progress-percent" class="badge bg-primary">0%</span>
            </div>
            <div class="progress" style="height: 25px;">
                <div class="progress-bar progress-bar-striped progress-bar-animated" 
                     role="progressbar" id="progress-bar" style="width: 0%">0%</div>
            </div>
            <small class="text-muted">
                <i class="fas fa-info-circle me-1"></i>
                <span id="progress-text">Pilih objek wisata untuk memulai penilaian</span>
            </small>
        </div>
    </div>
    
    <!-- Objek Wisata Info -->
    <?php if ($objek): ?>
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Objek Wisata</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Nama:</strong> <?php echo htmlspecialchars($objek['nama']); ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Alamat:</strong> <?php echo htmlspecialchars($objek['alamat']); ?></p>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
    <!-- Pilih Objek Wisata -->
    <div class="card mb-4">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Pilih Objek Wisata</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="">
                <input type="hidden" name="action" value="new">
                <div class="mb-3">
                    <label for="objek_id" class="form-label">Pilih Objek Wisata <span class="text-danger">*</span></label>
                    <select class="form-select" id="objek_id" name="objek_id" required>
                        <option value="">-- Pilih Objek Wisata --</option>
                        <?php
                        $conn_temp = getDBConnection();
                        $result = $conn_temp->query("SELECT * FROM objek_wisata ORDER BY nama");
                        while ($row = $result->fetch_assoc()) {
                            $selected = ($objek_id == $row['id']) ? 'selected' : '';
                            echo "<option value='{$row['id']}' $selected>" . htmlspecialchars($row['nama']) . "</option>";
                        }
                        $conn_temp->close();
                        ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-check me-2"></i>Lanjutkan
                </button>
                <a href="<?php echo BASE_URL; ?>pages/dashboard.php" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i>Batal
                </a>
            </form>
        </div>
    </div>
    <?php endif; ?>
    
    <?php if ($objek): ?>
    <!-- Form Penilaian -->
    <form id="penilaian-form" method="POST" data-penilaian-id="<?php echo $penilaian_id ? $penilaian_id : ''; ?>">
        <input type="hidden" name="objek_wisata_id" value="<?php echo $objek['id']; ?>">
        <input type="hidden" name="tanggal_penilaian" value="<?php echo date('Y-m-d'); ?>">
        <input type="hidden" name="nama_penilai" value="<?php echo htmlspecialchars($current_user['nama']); ?>">
        <input type="hidden" name="pangkat_nrp" value="<?php echo htmlspecialchars($current_user['pangkat_nrp']); ?>">
        
        <!-- Aspek Tabs -->
        <ul class="nav nav-tabs mb-4" id="aspek-tabs" role="tablist">
            <?php foreach ($aspek_list as $index => $aspek): ?>
            <li class="nav-item" role="presentation">
                <button class="nav-link <?php echo $index == 0 ? 'active' : ''; ?>" 
                        id="aspek-<?php echo $aspek['id']; ?>-tab" 
                        data-bs-toggle="tab" 
                        data-bs-target="#aspek-<?php echo $aspek['id']; ?>" 
                        type="button" role="tab">
                    <i class="fas fa-check-circle d-none" id="check-<?php echo $aspek['id']; ?>"></i>
                    <?php echo htmlspecialchars($aspek['nama']); ?>
                    <span class="badge bg-secondary ms-2" id="badge-<?php echo $aspek['id']; ?>">0</span>
                </button>
            </li>
            <?php endforeach; ?>
        </ul>
        
        <!-- Tab Content -->
        <div class="tab-content" id="aspek-tab-content">
            <?php foreach ($aspek_list as $index => $aspek): ?>
            <div class="tab-pane fade <?php echo $index == 0 ? 'show active' : ''; ?>" 
                 id="aspek-<?php echo $aspek['id']; ?>" 
                 role="tabpanel">
                
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-clipboard-list me-2"></i>
                            <?php echo htmlspecialchars($aspek['nama']); ?>
                            <small class="ms-2">(Bobot: <?php echo ($aspek['bobot'] * 100); ?>%)</small>
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php foreach ($aspek['elemen'] as $elemen): ?>
                        <div class="card mb-3 border-secondary">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">
                                    <i class="fas fa-list me-2"></i>
                                    <?php echo htmlspecialchars($elemen['nama']); ?>
                                    <small class="ms-2">(Bobot Elemen: <?php echo ($elemen['bobot'] * 100); ?>%)</small>
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm penilaian-table">
                                        <thead class="table-light d-none d-md-table-header-group">
                                            <tr>
                                                <th style="width: 5%;">No</th>
                                                <th style="width: 50%;">Kriteria</th>
                                                <th style="width: 15%;">Nilai</th>
                                                <th style="width: 15%;">Temuan</th>
                                                <th style="width: 15%;">Rekomendasi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($elemen['kriteria'] as $kriteria): 
                                                $detail = $penilaian_details[$kriteria['id']] ?? null;
                                                $nilai = $detail['nilai'] ?? '';
                                                $temuan = $detail['temuan'] ?? '';
                                                $rekomendasi = $detail['rekomendasi'] ?? '';
                                            ?>
                                            <tr data-kriteria-id="<?php echo $kriteria['id']; ?>" 
                                                data-elemen-id="<?php echo $elemen['id']; ?>"
                                                data-aspek-id="<?php echo $aspek['id']; ?>"
                                                class="penilaian-row">
                                                <td data-label="No" class="text-center fw-bold"><?php echo $kriteria['nomor']; ?></td>
                                                <td data-label="Kriteria">
                                                    <div class="fw-semibold mb-2"><?php echo htmlspecialchars($kriteria['deskripsi']); ?></div>
                                                    <div class="d-md-none">
                                                        <label class="form-label small fw-bold">Nilai:</label>
                                                        <select class="form-select nilai-select" 
                                                                name="nilai[<?php echo $kriteria['id']; ?>]"
                                                                data-kriteria-id="<?php echo $kriteria['id']; ?>"
                                                                required>
                                                            <option value="">Pilih Nilai</option>
                                                            <option value="0" <?php echo $nilai === '0' ? 'selected' : ''; ?>>0 - Tidak dapat dipenuhi</option>
                                                            <option value="1" <?php echo $nilai === '1' ? 'selected' : ''; ?>>1 - Terdapat kekurangan</option>
                                                            <option value="2" <?php echo $nilai === '2' ? 'selected' : ''; ?>>2 - Dapat dipenuhi</option>
                                                        </select>
                                                    </div>
                                                </td>
                                                <td data-label="Nilai" class="d-none d-md-table-cell">
                                                    <select class="form-select form-select-sm nilai-select" 
                                                            name="nilai[<?php echo $kriteria['id']; ?>]"
                                                            data-kriteria-id="<?php echo $kriteria['id']; ?>"
                                                            required>
                                                        <option value="">Pilih</option>
                                                        <option value="0" <?php echo $nilai === '0' ? 'selected' : ''; ?>>0 - Tidak dapat dipenuhi</option>
                                                        <option value="1" <?php echo $nilai === '1' ? 'selected' : ''; ?>>1 - Terdapat kekurangan</option>
                                                        <option value="2" <?php echo $nilai === '2' ? 'selected' : ''; ?>>2 - Dapat dipenuhi</option>
                                                    </select>
                                                </td>
                                                <td data-label="Temuan">
                                                    <div class="d-md-none mb-2">
                                                        <label class="form-label small fw-bold">Temuan:</label>
                                                    </div>
                                                    <textarea class="form-control temuan-input" 
                                                              name="temuan[<?php echo $kriteria['id']; ?>]"
                                                              rows="3"
                                                              data-kriteria-id="<?php echo $kriteria['id']; ?>"
                                                              placeholder="Tulis temuan..."><?php echo htmlspecialchars($temuan); ?></textarea>
                                                </td>
                                                <td data-label="Rekomendasi">
                                                    <div class="d-md-none mb-2">
                                                        <label class="form-label small fw-bold">Rekomendasi:</label>
                                                    </div>
                                                    <textarea class="form-control rekomendasi-input" 
                                                              name="rekomendasi[<?php echo $kriteria['id']; ?>]"
                                                              rows="3"
                                                              data-kriteria-id="<?php echo $kriteria['id']; ?>"
                                                              placeholder="Tulis rekomendasi..."><?php echo htmlspecialchars($rekomendasi); ?></textarea>
                                                </td>
                                            </tr>
                                            <!-- Upload File Referensi -->
                                            <tr class="upload-row" data-kriteria-id="<?php echo $kriteria['id']; ?>" style="display: none;">
                                                <td colspan="5" class="bg-light">
                                                    <div class="upload-section p-3">
                                                        <label class="form-label small fw-bold">
                                                            <i class="fas fa-file-upload me-1"></i>Referensi Dokumen (Opsional)
                                                        </label>
                                                        <div class="input-group mb-2">
                                                            <input type="file" 
                                                                   class="form-control form-control-sm file-upload-input" 
                                                                   id="file-<?php echo $kriteria['id']; ?>"
                                                                   data-kriteria-id="<?php echo $kriteria['id']; ?>"
                                                                   accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                                                            <button type="button" 
                                                                    class="btn btn-sm btn-primary btn-upload-file"
                                                                    data-kriteria-id="<?php echo $kriteria['id']; ?>">
                                                                <i class="fas fa-upload me-1"></i>Upload
                                                            </button>
                                                        </div>
                                                        <small class="text-muted d-block mb-2">
                                                            Format: JPG, PNG, PDF, DOC, DOCX | Maks: 5MB
                                                        </small>
                                                        <div class="uploaded-files" id="uploaded-files-<?php echo $kriteria['id']; ?>">
                                                            <!-- Uploaded files akan ditampilkan di sini -->
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr class="table-info">
                                                <td colspan="2" class="text-end"><strong>Skor Elemen:</strong></td>
                                                <td colspan="3">
                                                    <strong id="skor-elemen-<?php echo $elemen['id']; ?>">0.00%</strong>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        
                        <div class="alert alert-info">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Skor Aspek:</strong> 
                                    <span id="skor-aspek-<?php echo $aspek['id']; ?>" class="badge bg-primary fs-6">0.00%</span>
                                </div>
                                <div class="col-md-6 text-end">
                                    <strong>Kontribusi ke Skor Final:</strong> 
                                    <span id="kontribusi-aspek-<?php echo $aspek['id']; ?>" class="badge bg-success fs-6">0.00%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Summary Card -->
        <div class="card mt-4 sticky-bottom">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="fas fa-calculator me-2"></i>Ringkasan Skor
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center">
                        <h6>Skor Final</h6>
                        <h2 id="skor-final" class="text-primary">0.00%</h2>
                    </div>
                    <div class="col-md-3 text-center">
                        <h6>Kategori</h6>
                        <h3 id="kategori-final" class="text-warning">-</h3>
                    </div>
                    <div class="col-md-6">
                        <div class="progress" style="height: 30px;">
                            <div class="progress-bar" id="progress-final" role="progressbar" style="width: 0%">0%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Action Buttons - Sticky on Mobile -->
        <div class="card mt-4 sticky-bottom-actions d-md-none">
            <div class="card-body p-2">
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-success btn-lg" id="btn-submit-mobile">
                        <i class="fas fa-check me-2"></i>Selesai & Submit
                    </button>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-secondary" id="btn-save-draft-mobile">
                            <i class="fas fa-save me-2"></i>Simpan
                        </button>
                        <button type="button" class="btn btn-info" id="btn-auto-save-status-mobile">
                            <i class="fas fa-info-circle me-2"></i><span id="auto-save-status-mobile">OFF</span>
                        </button>
                    </div>
                    <a href="<?php echo BASE_URL; ?>pages/dashboard.php" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>Batal
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Action Buttons - Desktop -->
        <div class="card mt-4 d-none d-md-block">
            <div class="card-body">
                <div class="d-flex justify-content-between flex-wrap gap-2">
                    <div>
                        <button type="button" class="btn btn-secondary" id="btn-save-draft">
                            <i class="fas fa-save me-2"></i>Simpan Draft
                        </button>
                        <button type="button" class="btn btn-info" id="btn-auto-save-status">
                            <i class="fas fa-info-circle me-2"></i>Auto-save: <span id="auto-save-status">OFF</span>
                        </button>
                    </div>
                    <div>
                        <a href="<?php echo BASE_URL; ?>pages/dashboard.php" class="btn btn-warning">
                            <i class="fas fa-times me-2"></i>Batal
                        </a>
                        <button type="button" class="btn btn-success" id="btn-submit">
                            <i class="fas fa-check me-2"></i>Selesai & Submit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <?php endif; ?>
</div>

<!-- Loading Overlay -->
<div id="loading-overlay" class="d-none" style="position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:9999;display:flex;align-items:center;justify-content:center;">
    <div class="spinner-border text-light" role="status" style="width:3rem;height:3rem;">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<?php 
$additional_js = ['assets/js/penilaian_form.js'];
include __DIR__ . '/../includes/footer.php'; 
?>

