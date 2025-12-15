<?php
// Start session dulu sebelum require functions
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

$current_user = getCurrentUser();
$conn = getDBConnection();

$page = intval($_GET['page'] ?? 1);
$limit = 10;
$offset = ($page - 1) * $limit;
$search = sanitize($_GET['search'] ?? '');
$status = $_GET['status'] ?? '';

// Build query
$where = "WHERE 1=1";
$params = [];
$types = '';

if (!empty($search)) {
    $where .= " AND (ow.nama LIKE ? OR u.nama LIKE ?)";
    $search_param = "%$search%";
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= 'ss';
}

if (!empty($status)) {
    $where .= " AND p.status = ?";
    $params[] = $status;
    $types .= 's';
}

// Count total
$count_sql = "
    SELECT COUNT(*) as total 
    FROM penilaian p
    JOIN objek_wisata ow ON p.objek_wisata_id = ow.id
    JOIN users u ON p.user_id = u.id
    $where
";

if (!empty($params)) {
    $count_stmt = $conn->prepare($count_sql);
    $count_stmt->bind_param($types, ...$params);
    $count_stmt->execute();
    $total = $count_stmt->get_result()->fetch_assoc()['total'];
    $count_stmt->close();
} else {
    $total = $conn->query($count_sql)->fetch_assoc()['total'];
}

$total_pages = ceil($total / $limit);

// Get data
$sql = "
    SELECT p.*, ow.nama as objek_nama, ow.alamat as objek_alamat,
           u.nama as penilai_nama, u.pangkat_nrp as penilai_pangkat_nrp
    FROM penilaian p
    JOIN objek_wisata ow ON p.objek_wisata_id = ow.id
    JOIN users u ON p.user_id = u.id
    $where
    ORDER BY p.tanggal_penilaian DESC, p.created_at DESC
    LIMIT ? OFFSET ?
";

if (!empty($params)) {
    $types .= 'ii';
    $params[] = $limit;
    $params[] = $offset;
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
} else {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $limit, $offset);
}

$stmt->execute();
$result = $stmt->get_result();
$penilaian_list = [];
while ($row = $result->fetch_assoc()) {
    $penilaian_list[] = $row;
}
$stmt->close();
$conn->close();

$page_title = 'Laporan';
$show_navbar = true;
include __DIR__ . '/../includes/header.php';
?>

<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0">
                <i class="fas fa-file-pdf me-2"></i>Laporan
            </h2>
            <p class="text-muted">Generate dan download laporan penilaian</p>
        </div>
    </div>
    
    <!-- Filter & Search -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Cari</label>
                    <input type="text" class="form-control" name="search" 
                           value="<?php echo htmlspecialchars($search); ?>" 
                           placeholder="Cari objek wisata atau penilai...">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status">
                        <option value="">Semua Status</option>
                        <option value="draft" <?php echo $status == 'draft' ? 'selected' : ''; ?>>Draft</option>
                        <option value="selesai" <?php echo $status == 'selesai' ? 'selected' : ''; ?>>Selesai</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-2"></i>Cari
                    </button>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <a href="<?php echo BASE_URL; ?>pages/laporan.php" class="btn btn-secondary w-100">
                        <i class="fas fa-redo me-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- List Penilaian -->
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-list me-2"></i>Daftar Penilaian
                <span class="badge bg-light text-dark ms-2"><?php echo $total; ?> penilaian</span>
            </h5>
        </div>
        <div class="card-body p-0">
            <?php if (count($penilaian_list) > 0): ?>
                <!-- Desktop Table -->
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Objek Wisata</th>
                                <th>Penilai</th>
                                <th>Tanggal</th>
                                <th>Skor</th>
                                <th>Kategori</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($penilaian_list as $index => $penilaian): 
                                $kategori = getKategoriWithClass($penilaian['skor_final'] ?? 0);
                            ?>
                            <tr>
                                <td><?php echo $offset + $index + 1; ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($penilaian['objek_nama']); ?></strong><br>
                                    <small class="text-muted"><?php echo htmlspecialchars($penilaian['objek_alamat']); ?></small>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($penilaian['penilai_nama']); ?><br>
                                    <small class="text-muted"><?php echo htmlspecialchars($penilaian['penilai_pangkat_nrp']); ?></small>
                                </td>
                                <td><?php echo formatTanggalIndonesia($penilaian['tanggal_penilaian']); ?></td>
                                <td>
                                    <strong><?php echo number_format($penilaian['skor_final'] ?? 0, 2); ?>%</strong>
                                </td>
                                <td>
                                    <span class="badge <?php echo $kategori['progress_class']; ?> fs-6 px-3 py-2 shadow-sm">
                                        <span style="font-size: 1.2rem; margin-right: 0.3rem;"><?php echo $kategori['icon']; ?></span>
                                        <strong><?php echo htmlspecialchars($kategori['badge_text'] ?? $kategori['nama']); ?></strong>
                                    </span>
                                </td>
                                <td>
                                    <?php echo $penilaian['status'] == 'selesai' 
                                        ? '<span class="badge bg-success">Selesai</span>' 
                                        : '<span class="badge bg-warning">Draft</span>'; ?>
                                </td>
                                <td>
                                    <a href="<?php echo BASE_URL; ?>pages/penilaian_detail.php?id=<?php echo $penilaian['id']; ?>" 
                                       class="btn btn-sm btn-info" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <?php if ($penilaian['status'] == 'selesai'): ?>
                                    <a href="<?php echo BASE_URL; ?>pages/laporan_generate.php?penilaian_id=<?php echo $penilaian['id']; ?>" 
                                       class="btn btn-sm btn-primary" title="Download PDF" target="_blank">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Mobile Cards -->
                <div class="d-md-none">
                    <?php foreach ($penilaian_list as $index => $penilaian): 
                        $kategori = getKategoriWithClass($penilaian['skor_final'] ?? 0);
                    ?>
                    <div class="card m-3 border-start border-4 border-primary">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="mb-1"><?php echo htmlspecialchars($penilaian['objek_nama']); ?></h6>
                                    <small class="text-muted"><?php echo htmlspecialchars($penilaian['objek_alamat']); ?></small>
                                </div>
                                <?php echo $penilaian['status'] == 'selesai' 
                                    ? '<span class="badge bg-success">Selesai</span>' 
                                    : '<span class="badge bg-warning">Draft</span>'; ?>
                            </div>
                            <hr>
                            <div class="row g-2 mb-2">
                                <div class="col-6">
                                    <small class="text-muted d-block">Penilai</small>
                                    <strong><?php echo htmlspecialchars($penilaian['penilai_nama']); ?></strong>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Tanggal</small>
                                    <strong><?php echo formatTanggalIndonesia($penilaian['tanggal_penilaian']); ?></strong>
                                </div>
                            </div>
                            <div class="row g-2 mb-2">
                                <div class="col-6">
                                    <small class="text-muted d-block">Skor</small>
                                    <strong><?php echo number_format($penilaian['skor_final'] ?? 0, 2); ?>%</strong>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Kategori</small>
                                    <span class="badge <?php echo $kategori['progress_class']; ?> fs-6 px-3 py-2 shadow-sm">
                                        <span style="font-size: 1.2rem; margin-right: 0.3rem;"><?php echo $kategori['icon']; ?></span>
                                        <strong><?php echo htmlspecialchars($kategori['badge_text'] ?? $kategori['nama']); ?></strong>
                                    </span>
                                </div>
                            </div>
                            <div class="d-grid gap-2 mt-3">
                                <a href="<?php echo BASE_URL; ?>pages/penilaian_detail.php?id=<?php echo $penilaian['id']; ?>" 
                                   class="btn btn-sm btn-info">
                                    <i class="fas fa-eye me-2"></i>Lihat Detail
                                </a>
                                <?php if ($penilaian['status'] == 'selesai'): ?>
                                <a href="<?php echo BASE_URL; ?>pages/laporan_generate.php?penilaian_id=<?php echo $penilaian['id']; ?>" 
                                   class="btn btn-sm btn-primary" target="_blank">
                                    <i class="fas fa-download me-2"></i>Download PDF
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                <div class="card-footer">
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center mb-0">
                            <?php if ($page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status); ?>">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>
                            <?php endif; ?>
                            
                            <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                            <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status); ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                            <?php endfor; ?>
                            
                            <?php if ($page < $total_pages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status); ?>">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="text-center p-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Tidak ada data penilaian</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>

