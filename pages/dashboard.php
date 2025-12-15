<?php
// Start session dulu sebelum require functions
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

$current_user = getCurrentUser();
$conn = getDBConnection();

// Statistik
$stats = [];

// Total Objek Wisata
$result = $conn->query("SELECT COUNT(*) as total FROM objek_wisata");
$stats['total_objek'] = $result->fetch_assoc()['total'];

// Total Penilaian
$result = $conn->query("SELECT COUNT(*) as total FROM penilaian WHERE status = 'selesai'");
$stats['total_penilaian'] = $result->fetch_assoc()['total'];

// Objek Sudah Dinilai
$result = $conn->query("SELECT COUNT(DISTINCT objek_wisata_id) as total FROM penilaian WHERE status = 'selesai'");
$stats['objek_sudah_dinilai'] = $result->fetch_assoc()['total'];

// Objek Belum Dinilai
$stats['objek_belum_dinilai'] = $stats['total_objek'] - $stats['objek_sudah_dinilai'];

// Personil Aktif
$result = $conn->query("SELECT COUNT(DISTINCT user_id) as total FROM penilaian WHERE status = 'selesai'");
$stats['personil_aktif'] = $result->fetch_assoc()['total'];

// Penilaian Terbaru
$result = $conn->query("
    SELECT p.*, ow.nama as objek_nama, u.nama as penilai_nama 
    FROM penilaian p
    JOIN objek_wisata ow ON p.objek_wisata_id = ow.id
    JOIN users u ON p.user_id = u.id
    WHERE p.status = 'selesai'
    ORDER BY p.tanggal_penilaian DESC, p.created_at DESC
    LIMIT 5
");
$penilaian_terbaru = [];
while ($row = $result->fetch_assoc()) {
    $penilaian_terbaru[] = $row;
}

// Objek Belum Dinilai (Top 5)
$result = $conn->query("
    SELECT ow.*, 
           (SELECT COUNT(*) FROM penilaian WHERE objek_wisata_id = ow.id AND status = 'selesai') as jumlah_penilaian
    FROM objek_wisata ow
    WHERE (SELECT COUNT(*) FROM penilaian WHERE objek_wisata_id = ow.id AND status = 'selesai') = 0
    ORDER BY ow.created_at DESC
    LIMIT 5
");
$objek_belum_dinilai = [];
while ($row = $result->fetch_assoc()) {
    $objek_belum_dinilai[] = $row;
}

// Close connection
if (isset($conn)) {
    $conn->close();
}

$page_title = 'Dashboard';
$show_navbar = true;
include __DIR__ . '/../includes/header.php';
?>

<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0">
                <i class="fas fa-home me-2"></i>Dashboard
            </h2>
            <p class="text-muted">Selamat datang, <?php echo htmlspecialchars($current_user['nama']); ?>!</p>
        </div>
    </div>
    
    <!-- Charts Row -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Distribusi Kategori</h5>
                </div>
                <div class="card-body">
                    <canvas id="kategoriChart" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Skor Per Aspek</h5>
                </div>
                <div class="card-body">
                    <canvas id="aspekChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Statistik Cards -->
    <div class="row g-2 g-md-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card text-white bg-primary stat-card">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-subtitle mb-2 small">Total Objek</h6>
                            <h2 class="card-title mb-0 stat-value" id="stat-total-objek"><?php echo $stats['total_objek']; ?></h2>
                        </div>
                        <i class="fas fa-map-marker-alt fa-2x opacity-50 d-none d-md-block"></i>
                        <i class="fas fa-map-marker-alt fa-lg opacity-50 d-md-none"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-6 col-md-3">
            <div class="card text-white bg-success stat-card">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-subtitle mb-2 small">Sudah Dinilai</h6>
                            <h2 class="card-title mb-0 stat-value" id="stat-sudah-dinilai"><?php echo $stats['objek_sudah_dinilai']; ?></h2>
                        </div>
                        <i class="fas fa-check-circle fa-2x opacity-50 d-none d-md-block"></i>
                        <i class="fas fa-check-circle fa-lg opacity-50 d-md-none"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-6 col-md-3">
            <div class="card text-white bg-warning stat-card">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-subtitle mb-2 small">Belum Dinilai</h6>
                            <h2 class="card-title mb-0 stat-value" id="stat-belum-dinilai"><?php echo $stats['objek_belum_dinilai']; ?></h2>
                        </div>
                        <i class="fas fa-exclamation-circle fa-2x opacity-50 d-none d-md-block"></i>
                        <i class="fas fa-exclamation-circle fa-lg opacity-50 d-md-none"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-6 col-md-3">
            <div class="card text-white bg-info stat-card">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-subtitle mb-2 small">Total Penilaian</h6>
                            <h2 class="card-title mb-0 stat-value" id="stat-total-penilaian"><?php echo $stats['total_penilaian']; ?></h2>
                        </div>
                        <i class="fas fa-clipboard-check fa-2x opacity-50 d-none d-md-block"></i>
                        <i class="fas fa-clipboard-check fa-lg opacity-50 d-md-none"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="row g-2 mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-bolt me-2"></i>Aksi Cepat
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-6 col-md-3">
                            <a href="<?php echo BASE_URL; ?>pages/penilaian_form.php?action=new" class="btn btn-primary w-100 btn-lg">
                                <i class="fas fa-plus me-2"></i><span class="d-none d-sm-inline">Penilaian Baru</span><span class="d-sm-none">Baru</span>
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="<?php echo BASE_URL; ?>pages/objek_wisata.php" class="btn btn-success w-100 btn-lg">
                                <i class="fas fa-map-marker-alt me-2"></i><span class="d-none d-sm-inline">Objek Wisata</span><span class="d-sm-none">Objek</span>
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="<?php echo BASE_URL; ?>pages/laporan.php" class="btn btn-info w-100 btn-lg">
                                <i class="fas fa-file-pdf me-2"></i><span class="d-none d-sm-inline">Laporan</span><span class="d-sm-none">Laporan</span>
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="<?php echo BASE_URL; ?>pages/penilaian_list.php" class="btn btn-warning w-100 btn-lg">
                                <i class="fas fa-list me-2"></i><span class="d-none d-sm-inline">Daftar</span><span class="d-sm-none">Daftar</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Penilaian Terbaru -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <i class="fas fa-clock me-2"></i>Penilaian Terbaru
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush" id="penilaian-terbaru-list">
                    <?php if (count($penilaian_terbaru) > 0): ?>
                            <?php foreach ($penilaian_terbaru as $p): ?>
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1"><?php echo htmlspecialchars($p['objek_nama']); ?></h6>
                                            <small class="text-muted">
                                                <i class="fas fa-user me-1"></i><?php echo htmlspecialchars($p['penilai_nama']); ?><br>
                                                <i class="fas fa-calendar me-1"></i><?php echo formatTanggalIndonesia($p['tanggal_penilaian']); ?>
                                            </small>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-primary"><?php echo formatAngka($p['skor_final']); ?>%</span><br>
                                            <?php 
                                                $kategori = getKategoriWithClass($p['skor_final'] ?? 0);
                                            ?>
                                            <span class="badge <?php echo $kategori['progress_class']; ?> fs-6 px-2 py-1 shadow-sm">
                                                <span style="font-size: 1.1rem; margin-right: 0.3rem;"><?php echo $kategori['icon']; ?></span>
                                                <strong><?php echo htmlspecialchars($kategori['badge_text'] ?? $kategori['nama']); ?></strong>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center mb-0">Belum ada penilaian</p>
                    <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Objek Belum Dinilai -->
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <i class="fas fa-exclamation-triangle me-2"></i>Objek Belum Dinilai
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush" id="objek-belum-dinilai-list">
                    <?php if (count($objek_belum_dinilai) > 0): ?>
                            <?php foreach ($objek_belum_dinilai as $objek): ?>
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1"><?php echo htmlspecialchars($objek['nama']); ?></h6>
                                            <small class="text-muted">
                                                <?php echo htmlspecialchars(substr($objek['alamat'], 0, 50)); ?>...
                                            </small>
                                        </div>
                                        <div>
                                            <a href="<?php echo BASE_URL; ?>pages/penilaian_form.php?action=new&objek_id=<?php echo $objek['id']; ?>" 
                                               class="btn btn-sm btn-primary">
                                                <i class="fas fa-plus me-1"></i>Nilai
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-success text-center mb-0">
                            <i class="fas fa-check-circle me-2"></i>Semua objek sudah dinilai!
                        </p>
                    <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
// Load dashboard-specific JS and Chart.js
$additional_js = [
    'https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js',
    'assets/js/dashboard.js',
    'assets/js/dashboard_charts.js'
];
include __DIR__ . '/../includes/footer.php'; 
?>

