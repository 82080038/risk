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

$params[] = $limit;
$params[] = $offset;
$types .= 'ii';

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

$penilaian_list = [];
while ($row = $result->fetch_assoc()) {
    $penilaian_list[] = $row;
}
$stmt->close();
$conn->close();

$page_title = 'Daftar Penilaian';
$show_navbar = true;
include __DIR__ . '/../includes/header.php';
?>

<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-0">
                        <i class="fas fa-clipboard-list me-2"></i>Daftar Penilaian
                    </h2>
                    <p class="text-muted">Daftar semua penilaian yang telah dilakukan</p>
                </div>
                <a href="<?php echo BASE_URL; ?>pages/penilaian_form.php?action=new" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Penilaian Baru
                </a>
            </div>
        </div>
    </div>
    
    <!-- Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="" class="row g-2">
                <div class="col-12 col-md-6">
                    <input type="text" class="form-control" name="search" 
                           placeholder="Cari objek wisata atau penilai..." 
                           value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <div class="col-12 col-md-3">
                    <select class="form-select" name="status">
                        <option value="">Semua Status</option>
                        <option value="draft" <?php echo $status == 'draft' ? 'selected' : ''; ?>>Draft</option>
                        <option value="selesai" <?php echo $status == 'selesai' ? 'selected' : ''; ?>>Selesai</option>
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-2 d-md-inline d-none"></i>
                        <span>Cari</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Table - Desktop -->
    <div class="card d-none d-md-block">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
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
                    <tbody id="penilaian-table-body">
                        <?php if (count($penilaian_list) > 0): ?>
                            <?php foreach ($penilaian_list as $index => $p): 
                                $kategoriBadge = getKategori($p['skor_final']);
                                $statusBadge = $p['status'] == 'selesai' 
                                    ? '<span class="badge bg-success">Selesai</span>'
                                    : '<span class="badge bg-warning">Draft</span>';
                            ?>
                            <tr>
                                <td><?php echo $offset + $index + 1; ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($p['objek_nama']); ?></strong><br>
                                    <small class="text-muted"><?php echo htmlspecialchars(substr($p['objek_alamat'], 0, 50)); ?>...</small>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($p['penilai_nama']); ?><br>
                                    <small class="text-muted"><?php echo htmlspecialchars($p['penilai_pangkat_nrp']); ?></small>
                                </td>
                                <td><?php echo formatTanggalIndonesia($p['tanggal_penilaian']); ?></td>
                                <td>
                                    <span class="badge bg-primary fs-6"><?php echo formatAngka($p['skor_final']); ?>%</span>
                                </td>
                                <td>
                                    <?php 
                                        $kategori = getKategoriWithClass($p['skor_final'] ?? 0);
                                    ?>
                                    <span class="badge <?php echo $kategori['progress_class']; ?> fs-6 px-3 py-2 shadow-sm">
                                        <span style="font-size: 1.2rem; margin-right: 0.3rem;"><?php echo $kategori['icon']; ?></span>
                                        <strong><?php echo htmlspecialchars($kategori['badge_text'] ?? $kategori['nama']); ?></strong>
                                    </span>
                                </td>
                                <td><?php echo $statusBadge; ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?php echo BASE_URL; ?>pages/penilaian_form.php?id=<?php echo $p['id']; ?>" 
                                           class="btn btn-info" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?php echo BASE_URL; ?>pages/penilaian_detail.php?id=<?php echo $p['id']; ?>" 
                                           class="btn btn-primary" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <?php if ($p['status'] == 'selesai'): ?>
                                        <a href="<?php echo BASE_URL; ?>pages/laporan_generate.php?penilaian_id=<?php echo $p['id']; ?>" 
                                           class="btn btn-success" title="Download PDF">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center">
                                    <p class="text-muted">Tidak ada data penilaian</p>
                                    <a href="<?php echo BASE_URL; ?>pages/penilaian_form.php?action=new" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>Buat Penilaian Baru
                                    </a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Card View - Mobile -->
    <div class="d-md-none">
        <?php if (count($penilaian_list) > 0): ?>
            <?php foreach ($penilaian_list as $index => $p): 
                $kategoriBadge = getKategori($p['skor_final']);
                $statusBadge = $p['status'] == 'selesai' 
                    ? '<span class="badge bg-success">Selesai</span>'
                    : '<span class="badge bg-warning">Draft</span>';
            ?>
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-1"><?php echo htmlspecialchars($p['objek_nama']); ?></h5>
                            <p class="text-muted small mb-2"><?php echo htmlspecialchars(substr($p['objek_alamat'], 0, 60)); ?>...</p>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-primary fs-6"><?php echo formatAngka($p['skor_final']); ?>%</span>
                        </div>
                    </div>
                    
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <small class="text-muted d-block">Penilai</small>
                            <strong class="small"><?php echo htmlspecialchars($p['penilai_nama']); ?></strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Tanggal</small>
                            <strong class="small"><?php echo formatTanggalIndonesia($p['tanggal_penilaian']); ?></strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Kategori</small>
                            <?php 
                                $kategori = getKategoriWithClass($p['skor_final'] ?? 0);
                            ?>
                            <span class="badge <?php echo $kategori['progress_class']; ?> fs-6 px-3 py-2">
                                <span class="icon-kategori"><?php echo $kategori['icon']; ?></span>
                                <strong><?php echo htmlspecialchars($kategori['badge_text'] ?? $kategori['nama']); ?></strong>
                            </span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Status</small>
                            <?php echo $statusBadge; ?>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <div class="btn-group" role="group">
                            <a href="<?php echo BASE_URL; ?>pages/penilaian_detail.php?id=<?php echo $p['id']; ?>" 
                               class="btn btn-primary">
                                <i class="fas fa-eye me-1"></i>Detail
                            </a>
                            <?php if ($p['status'] == 'draft'): ?>
                            <a href="<?php echo BASE_URL; ?>pages/penilaian_form.php?id=<?php echo $p['id']; ?>" 
                               class="btn btn-info">
                                <i class="fas fa-edit me-1"></i>Edit
                            </a>
                            <?php endif; ?>
                        </div>
                        <?php if ($p['status'] == 'selesai'): ?>
                        <a href="<?php echo BASE_URL; ?>pages/laporan_generate.php?penilaian_id=<?php echo $p['id']; ?>" 
                           class="btn btn-success">
                            <i class="fas fa-file-pdf me-2"></i>Download PDF
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Tidak ada data penilaian</p>
                    <a href="<?php echo BASE_URL; ?>pages/penilaian_form.php?action=new" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Buat Penilaian Baru
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status); ?>">Previous</a>
                    </li>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status); ?>"><?php echo $i; ?></a>
                    </li>
                    <?php endfor; ?>
                    
                    <?php if ($page < $total_pages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status); ?>">Next</a>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>

