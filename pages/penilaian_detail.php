<?php
/**
 * Detail Penilaian Page
 * Risk Assessment Objek Wisata
 * View-only mode untuk melihat detail penilaian lengkap
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

$current_user = getCurrentUser();
$conn = getDBConnection();

$penilaian_id = $_GET['id'] ?? null;

if (!$penilaian_id) {
    header('Location: ' . BASE_URL . 'pages/penilaian_list.php');
    exit;
}

// Get penilaian data
$stmt = $conn->prepare("
    SELECT p.*, ow.nama as objek_nama, ow.alamat as objek_alamat,
           u.nama as penilai_nama, u.pangkat_nrp as penilai_pangkat_nrp
    FROM penilaian p
    JOIN objek_wisata ow ON p.objek_wisata_id = ow.id
    JOIN users u ON p.user_id = u.id
    WHERE p.id = ?
");
$stmt->bind_param("i", $penilaian_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header('Location: ' . BASE_URL . 'pages/penilaian_list.php');
    exit;
}

$penilaian = $result->fetch_assoc();
$stmt->close();

// Get all aspek dengan elemen dan kriteria
$aspek_sql = "
    SELECT a.*
    FROM aspek a
    ORDER BY a.urutan
";
$aspek_result = $conn->query($aspek_sql);

$aspek_list = [];
while ($aspek = $aspek_result->fetch_assoc()) {
    // Get elemen
    $elemen_sql = "SELECT * FROM elemen WHERE aspek_id = ? ORDER BY urutan";
    $elemen_stmt = $conn->prepare($elemen_sql);
    $elemen_stmt->bind_param("i", $aspek['id']);
    $elemen_stmt->execute();
    $elemen_result = $elemen_stmt->get_result();
    
    $elemen_list = [];
    while ($elemen = $elemen_result->fetch_assoc()) {
        // Get kriteria dengan nilai
        $kriteria_sql = "
            SELECT k.*, pd.nilai, pd.temuan, pd.rekomendasi
            FROM kriteria k
            LEFT JOIN penilaian_detail pd ON k.id = pd.kriteria_id AND pd.penilaian_id = ?
            WHERE k.elemen_id = ?
            ORDER BY k.urutan
        ";
        $kriteria_stmt = $conn->prepare($kriteria_sql);
        $kriteria_stmt->bind_param("ii", $penilaian_id, $elemen['id']);
        $kriteria_stmt->execute();
        $kriteria_result = $kriteria_stmt->get_result();
        
        $kriteria_list = [];
        $total_nilai = 0;
        $total_kriteria = 0;
        
        while ($kriteria = $kriteria_result->fetch_assoc()) {
            if ($kriteria['nilai'] !== null) {
                $total_nilai += intval($kriteria['nilai']);
                $total_kriteria++;
            }
            
            // Get referensi dokumen untuk kriteria ini
            $ref_stmt = $conn->prepare("SELECT * FROM referensi_dokumen WHERE penilaian_id = ? AND kriteria_id = ? ORDER BY created_at DESC");
            $ref_stmt->bind_param("ii", $penilaian_id, $kriteria['id']);
            $ref_stmt->execute();
            $ref_result = $ref_stmt->get_result();
            $kriteria['referensi'] = [];
            while ($ref = $ref_result->fetch_assoc()) {
                $kriteria['referensi'][] = $ref;
            }
            $ref_stmt->close();
            
            $kriteria_list[] = $kriteria;
        }
        
        // Calculate skor elemen
        $elemen['skor'] = $total_kriteria > 0 ? ($total_nilai / ($total_kriteria * 2)) * 100 : 0;
        $elemen['kriteria'] = $kriteria_list;
        $elemen_list[] = $elemen;
        
        $kriteria_stmt->close();
    }
    
    // Calculate skor aspek
    $total_skor = 0;
    foreach ($elemen_list as $elemen) {
        $total_skor += $elemen['skor'] * $elemen['bobot'];
    }
    $aspek['skor'] = $total_skor;
    $aspek['kontribusi'] = $total_skor * $aspek['bobot'];
    
    $aspek['elemen'] = $elemen_list;
    $aspek_list[] = $aspek;
    
    $elemen_stmt->close();
}

// Get referensi dokumen
$ref_stmt = $conn->prepare("
    SELECT rd.*, k.deskripsi as kriteria_deskripsi
    FROM referensi_dokumen rd
    LEFT JOIN kriteria k ON rd.kriteria_id = k.id
    WHERE rd.penilaian_id = ?
    ORDER BY rd.created_at DESC
");
$ref_stmt->bind_param("i", $penilaian_id);
$ref_stmt->execute();
$ref_result = $ref_stmt->get_result();

$referensi_list = [];
while ($ref = $ref_result->fetch_assoc()) {
    $referensi_list[] = $ref;
}
$ref_stmt->close();

$conn->close();

$page_title = 'Detail Penilaian';
$show_navbar = true;
include __DIR__ . '/../includes/header.php';
?>

<div class="container-fluid mt-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-0">
                        <i class="fas fa-eye me-2"></i>Detail Penilaian
                    </h2>
                    <p class="text-muted">Detail lengkap hasil penilaian risiko objek wisata</p>
                </div>
                <div>
                    <?php if ($penilaian['status'] == 'selesai'): ?>
                    <a href="<?php echo BASE_URL; ?>pages/laporan_generate.php?penilaian_id=<?php echo $penilaian_id; ?>" 
                       class="btn btn-success">
                        <i class="fas fa-file-pdf me-2"></i>Download PDF
                    </a>
                    <?php endif; ?>
                    <a href="<?php echo BASE_URL; ?>pages/penilaian_list.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Info Objek Wisata -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Informasi Objek Wisata</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <div class="mb-3">
                        <strong class="d-block mb-1">Nama Objek Wisata:</strong>
                        <span><?php echo htmlspecialchars($penilaian['objek_nama']); ?></span>
                    </div>
                    <div>
                        <strong class="d-block mb-1">Alamat:</strong>
                        <span><?php echo htmlspecialchars($penilaian['objek_alamat']); ?></span>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="mb-3">
                        <strong class="d-block mb-1">Tanggal Penilaian:</strong>
                        <span><?php echo formatTanggalIndonesia($penilaian['tanggal_penilaian']); ?></span>
                    </div>
                    <div class="mb-3">
                        <strong class="d-block mb-1">Penilai:</strong>
                        <div>
                            <?php echo htmlspecialchars($penilaian['penilai_nama']); ?><br>
                            <small class="text-muted"><?php echo htmlspecialchars($penilaian['penilai_pangkat_nrp']); ?></small>
                        </div>
                    </div>
                    <div>
                        <strong class="d-block mb-1">Status:</strong>
                        <?php if ($penilaian['status'] == 'selesai'): ?>
                            <span class="badge bg-success">Selesai</span>
                        <?php else: ?>
                            <span class="badge bg-warning">Draft</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Summary Card -->
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">
                <i class="fas fa-calculator me-2"></i>Ringkasan Skor
            </h5>
        </div>
        <div class="card-body">
            <div class="row text-center g-3">
                <div class="col-6 col-md-3">
                    <h6 class="small mb-2">Skor Final</h6>
                    <h2 class="text-primary mb-0"><?php echo number_format($penilaian['skor_final'], 2); ?>%</h2>
                </div>
                <div class="col-6 col-md-3">
                    <h6 class="small mb-2">Kategori</h6>
                    <?php 
                        $kategori = getKategoriWithClass($penilaian['skor_final']);
                    ?>
                    <div class="mb-2">
                        <span class="badge <?php echo $kategori['progress_class']; ?> fs-5 px-4 py-3 shadow-sm" style="font-size: 1.1rem !important;">
                            <span style="font-size: 1.5rem; margin-right: 0.5rem;"><?php echo $kategori['icon']; ?></span>
                            <strong style="font-size: 1.2rem;"><?php echo htmlspecialchars($kategori['badge_text'] ?? $kategori['nama']); ?></strong>
                        </span>
                    </div>
                    <small class="text-muted d-block mt-2"><?php echo htmlspecialchars($kategori['nama']); ?></small>
                </div>
                <div class="col-12 col-md-6">
                    <h6 class="small mb-2">Progress</h6>
                    <div class="progress" style="height: 2rem;">
                        <div class="progress-bar <?php 
                            $kategori = getKategoriWithClass($penilaian['skor_final']);
                            echo $kategori['progress_class'] ?? 'bg-warning';
                        ?>" 
                             role="progressbar" 
                             style="width: <?php echo $penilaian['skor_final']; ?>%">
                            <?php echo number_format($penilaian['skor_final'], 2); ?>%
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Detail Per Aspek -->
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>Detail Penilaian Per Aspek</h5>
        </div>
        <div class="card-body">
            <!-- Aspek Tabs -->
            <ul class="nav nav-tabs mb-4" id="aspek-tabs" role="tablist">
                <?php foreach ($aspek_list as $index => $aspek): ?>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?php echo $index == 0 ? 'active' : ''; ?>" 
                            id="aspek-<?php echo $aspek['id']; ?>-tab" 
                            data-bs-toggle="tab" 
                            data-bs-target="#aspek-<?php echo $aspek['id']; ?>" 
                            type="button" role="tab">
                        <?php echo htmlspecialchars($aspek['nama']); ?>
                        <span class="badge bg-secondary ms-2"><?php echo number_format($aspek['skor'], 2); ?>%</span>
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
                    
                    <?php 
                        $aspek_kategori = getKategoriWithClass($aspek['skor']);
                    ?>
                    <div class="alert alert-info">
                        <div class="row g-2">
                            <div class="col-12 col-md-4">
                                <strong>Skor Aspek:</strong> 
                                <span class="badge bg-primary fs-6"><?php echo number_format($aspek['skor'], 2); ?>%</span>
                            </div>
                            <div class="col-12 col-md-4">
                                <strong>Kategori:</strong> 
                                <span class="badge <?php echo $aspek_kategori['progress_class']; ?> fs-6 px-2 py-1">
                                    <span style="font-size: 1.1rem; margin-right: 0.3rem;"><?php echo $aspek_kategori['icon']; ?></span>
                                    <strong><?php echo htmlspecialchars($aspek_kategori['badge_text'] ?? $aspek_kategori['nama']); ?></strong>
                                </span>
                            </div>
                            <div class="col-12 col-md-4 text-md-end">
                                <strong>Kontribusi:</strong> 
                                <span class="badge bg-success fs-6"><?php echo number_format($aspek['kontribusi'], 2); ?>%</span>
                            </div>
                        </div>
                    </div>
                    
                    <?php foreach ($aspek['elemen'] as $elemen): ?>
                    <div class="card mb-3 border-secondary">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="fas fa-list me-2"></i>
                                <?php echo htmlspecialchars($elemen['nama']); ?>
                                <small class="ms-2">(Bobot: <?php echo ($elemen['bobot'] * 100); ?>%)</small>
                                <span class="badge bg-info float-end">Skor: <?php echo number_format($elemen['skor'], 2); ?>%</span>
                            </h6>
                        </div>
                        <div class="card-body">
                            <!-- Desktop Table -->
                            <div class="table-responsive d-none d-md-block">
                                <table class="table table-bordered table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 5%;">No</th>
                                            <th style="width: 45%;">Kriteria</th>
                                            <th style="width: 10%;">Nilai</th>
                                            <th style="width: 20%;">Temuan</th>
                                            <th style="width: 20%;">Rekomendasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($elemen['kriteria'] as $kriteria): ?>
                                        <tr>
                                            <td><?php echo $kriteria['nomor']; ?></td>
                                            <td><small><?php echo htmlspecialchars($kriteria['deskripsi']); ?></small></td>
                                            <td class="text-center">
                                                <?php if ($kriteria['nilai'] !== null): ?>
                                                    <span class="badge <?php 
                                                        if ($kriteria['nilai'] == 2) echo 'bg-success';
                                                        elseif ($kriteria['nilai'] == 1) echo 'bg-warning';
                                                        else echo 'bg-danger';
                                                    ?>">
                                                        <?php echo $kriteria['nilai']; ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($kriteria['temuan']): ?>
                                                    <small><?php echo htmlspecialchars($kriteria['temuan']); ?></small>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($kriteria['rekomendasi']): ?>
                                                    <small><?php echo htmlspecialchars($kriteria['rekomendasi']); ?></small>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr class="table-info">
                                            <td colspan="2" class="text-end"><strong>Skor Elemen:</strong></td>
                                            <td colspan="3" class="text-center">
                                                <strong><?php echo number_format($elemen['skor'], 2); ?>%</strong>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            
                            <!-- Mobile Card View -->
                            <div class="d-md-none">
                                <?php foreach ($elemen['kriteria'] as $kriteria): ?>
                                <div class="card mb-3 border-start border-4 <?php 
                                    if ($kriteria['nilai'] == 2) echo 'border-success';
                                    elseif ($kriteria['nilai'] == 1) echo 'border-warning';
                                    elseif ($kriteria['nilai'] == 0) echo 'border-danger';
                                    else echo 'border-secondary';
                                ?>">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div class="flex-grow-1">
                                                <span class="badge bg-primary me-2"><?php echo $kriteria['nomor']; ?></span>
                                                <strong class="small"><?php echo htmlspecialchars($kriteria['deskripsi']); ?></strong>
                                            </div>
                                            <?php if ($kriteria['nilai'] !== null): ?>
                                            <span class="badge <?php 
                                                if ($kriteria['nilai'] == 2) echo 'bg-success';
                                                elseif ($kriteria['nilai'] == 1) echo 'bg-warning';
                                                else echo 'bg-danger';
                                            ?> fs-6">
                                                <?php echo $kriteria['nilai']; ?>
                                            </span>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <?php if ($kriteria['temuan'] || $kriteria['rekomendasi']): ?>
                                        <div class="mt-3">
                                            <?php if ($kriteria['temuan']): ?>
                                            <div class="mb-2">
                                                <small class="text-muted fw-bold d-block mb-1">Temuan:</small>
                                                <p class="mb-0 small"><?php echo htmlspecialchars($kriteria['temuan']); ?></p>
                                            </div>
                                            <?php endif; ?>
                                            
                                            <?php if ($kriteria['rekomendasi']): ?>
                                            <div>
                                                <small class="text-muted fw-bold d-block mb-1">Rekomendasi:</small>
                                                <p class="mb-0 small"><?php echo htmlspecialchars($kriteria['rekomendasi']); ?></p>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($kriteria['referensi'])): ?>
                                        <div class="mt-3 pt-2 border-top">
                                            <small class="text-info fw-bold d-block mb-2">
                                                <i class="fas fa-paperclip me-1"></i>Referensi Dokumen (<?php echo count($kriteria['referensi']); ?>)
                                            </small>
                                            <div class="d-flex flex-wrap gap-2">
                                                <?php foreach ($kriteria['referensi'] as $ref): ?>
                                                <a href="<?php echo BASE_URL . $ref['path_file']; ?>" 
                                                   target="_blank" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-file me-1"></i>
                                                    <?php echo htmlspecialchars($ref['nama_file']); ?>
                                                </a>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                                
                                <div class="alert alert-info mb-0">
                                    <strong>Skor Elemen: <?php echo number_format($elemen['skor'], 2); ?>%</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    
    <!-- Referensi Dokumen -->
    <?php if (count($referensi_list) > 0): ?>
    <div class="card mb-4">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0"><i class="fas fa-file-upload me-2"></i>Referensi Dokumen</h5>
        </div>
        <div class="card-body">
            <!-- Desktop Table -->
            <div class="table-responsive d-none d-md-block">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kriteria</th>
                            <th>Nama File</th>
                            <th>Ukuran</th>
                            <th>Tanggal Upload</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($referensi_list as $index => $ref): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td>
                                <?php if ($ref['kriteria_deskripsi']): ?>
                                    <small><?php echo htmlspecialchars(substr($ref['kriteria_deskripsi'], 0, 50)); ?>...</small>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($ref['nama_file']); ?></td>
                            <td><?php echo formatFileSize($ref['ukuran_file'] ?? 0); ?></td>
                            <td><?php echo formatTanggalIndonesia($ref['created_at']); ?></td>
                            <td>
                                <a href="<?php echo BASE_URL . $ref['path_file']; ?>" 
                                   target="_blank" 
                                   class="btn btn-sm btn-primary">
                                    <i class="fas fa-download"></i> Download
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Mobile Card View -->
            <div class="d-md-none">
                <?php foreach ($referensi_list as $index => $ref): ?>
                <div class="card mb-3 border-start border-4 border-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="flex-grow-1">
                                <span class="badge bg-warning text-dark me-2"><?php echo $index + 1; ?></span>
                                <strong class="small"><?php echo htmlspecialchars($ref['nama_file']); ?></strong>
                            </div>
                        </div>
                        
                        <?php if ($ref['kriteria_deskripsi']): ?>
                        <div class="mb-2">
                            <small class="text-muted d-block mb-1">Kriteria:</small>
                            <small><?php echo htmlspecialchars(substr($ref['kriteria_deskripsi'], 0, 80)); ?>...</small>
                        </div>
                        <?php endif; ?>
                        
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <small class="text-muted d-block">Ukuran</small>
                                <strong class="small"><?php echo formatFileSize($ref['ukuran_file'] ?? 0); ?></strong>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Tanggal</small>
                                <strong class="small"><?php echo formatTanggalIndonesia($ref['created_at']); ?></strong>
                            </div>
                        </div>
                        
                        <a href="<?php echo BASE_URL . $ref['path_file']; ?>" 
                           target="_blank" 
                           class="btn btn-primary w-100">
                            <i class="fas fa-download me-2"></i>Download File
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Action Buttons -->
    <div class="card">
        <div class="card-body">
            <!-- Mobile Buttons -->
            <div class="d-md-none d-grid gap-2">
                <?php if ($penilaian['status'] == 'selesai'): ?>
                <a href="<?php echo BASE_URL; ?>pages/laporan_generate.php?penilaian_id=<?php echo $penilaian_id; ?>" 
                   class="btn btn-success btn-lg">
                    <i class="fas fa-file-pdf me-2"></i>Download PDF
                </a>
                <?php endif; ?>
                <div class="btn-group" role="group">
                    <button onclick="window.print()" class="btn btn-info">
                        <i class="fas fa-print me-2"></i>Print
                    </button>
                    <?php if ($penilaian['status'] == 'draft'): ?>
                    <a href="<?php echo BASE_URL; ?>pages/penilaian_form.php?id=<?php echo $penilaian_id; ?>" 
                       class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Edit
                    </a>
                    <?php endif; ?>
                </div>
                <a href="<?php echo BASE_URL; ?>pages/penilaian_list.php" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
            
            <!-- Desktop Buttons -->
            <div class="d-none d-md-flex justify-content-between flex-wrap gap-2">
                <div>
                    <?php if ($penilaian['status'] == 'draft'): ?>
                    <a href="<?php echo BASE_URL; ?>pages/penilaian_form.php?id=<?php echo $penilaian_id; ?>" 
                       class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Edit Penilaian
                    </a>
                    <?php endif; ?>
                </div>
                <div>
                    <button onclick="window.print()" class="btn btn-info">
                        <i class="fas fa-print me-2"></i>Print
                    </button>
                    <?php if ($penilaian['status'] == 'selesai'): ?>
                    <a href="<?php echo BASE_URL; ?>pages/laporan_generate.php?penilaian_id=<?php echo $penilaian_id; ?>" 
                       class="btn btn-success">
                        <i class="fas fa-file-pdf me-2"></i>Download PDF
                    </a>
                    <?php endif; ?>
                    <a href="<?php echo BASE_URL; ?>pages/penilaian_list.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .no-print, .btn, .nav-tabs, .card-header {
        display: none !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
    }
}
</style>

<?php include __DIR__ . '/../includes/footer.php'; ?>

