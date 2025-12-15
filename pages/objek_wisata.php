<?php
// Start session dulu sebelum require functions
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

$current_user = getCurrentUser();
$conn = getDBConnection();

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;
$error = '';
$success = '';

// Handle actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? 'create';
    
    if ($action == 'create') {
        $nama = sanitize($_POST['nama'] ?? '');
        $alamat = sanitize($_POST['alamat'] ?? '');
        $jenis = sanitize($_POST['jenis'] ?? '');
        $wilayah_hukum = sanitize($_POST['wilayah_hukum'] ?? '');
        $keterangan = sanitize($_POST['keterangan'] ?? '');
        
        if (empty($nama)) {
            $error = 'Nama objek wisata wajib diisi!';
        } else {
            $stmt = $conn->prepare("INSERT INTO objek_wisata (nama, alamat, jenis, wilayah_hukum, keterangan) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $nama, $alamat, $jenis, $wilayah_hukum, $keterangan);
            
            if ($stmt->execute()) {
                $success = 'Objek wisata berhasil ditambahkan!';
                $action = 'list';
            } else {
                $error = 'Gagal menambahkan objek wisata: ' . $stmt->error;
            }
            $stmt->close();
        }
    } elseif ($action == 'update') {
        $id = intval($_POST['id'] ?? 0);
        $nama = sanitize($_POST['nama'] ?? '');
        $alamat = sanitize($_POST['alamat'] ?? '');
        $jenis = sanitize($_POST['jenis'] ?? '');
        $wilayah_hukum = sanitize($_POST['wilayah_hukum'] ?? '');
        $keterangan = sanitize($_POST['keterangan'] ?? '');
        
        if (empty($nama)) {
            $error = 'Nama objek wisata wajib diisi!';
        } else {
            $stmt = $conn->prepare("UPDATE objek_wisata SET nama = ?, alamat = ?, jenis = ?, wilayah_hukum = ?, keterangan = ? WHERE id = ?");
            $stmt->bind_param("sssssi", $nama, $alamat, $jenis, $wilayah_hukum, $keterangan, $id);
            
            if ($stmt->execute()) {
                $success = 'Objek wisata berhasil diupdate!';
                $action = 'list';
            } else {
                $error = 'Gagal mengupdate objek wisata: ' . $stmt->error;
            }
            $stmt->close();
        }
    } elseif ($action == 'delete') {
        $id = intval($_POST['id'] ?? 0);
        
        $stmt = $conn->prepare("DELETE FROM objek_wisata WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $success = 'Objek wisata berhasil dihapus!';
        } else {
            $error = 'Gagal menghapus objek wisata: ' . $stmt->error;
        }
        $stmt->close();
    }
}

// Get data untuk form edit
$objek = null;
if ($action == 'edit' && $id) {
    $stmt = $conn->prepare("SELECT * FROM objek_wisata WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $objek = $result->fetch_assoc();
    } else {
        $error = 'Objek wisata tidak ditemukan!';
        $action = 'list';
    }
    $stmt->close();
}

// Get list data
$page = intval($_GET['page'] ?? 1);
$limit = 10;
$offset = ($page - 1) * $limit;
$search = sanitize($_GET['search'] ?? '');

$where = '';
$search_param = '';
if (!empty($search)) {
    $where = "WHERE nama LIKE ? OR alamat LIKE ? OR jenis LIKE ? OR wilayah_hukum LIKE ? OR keterangan LIKE ?";
    $search_param = "%$search%";
}

// Count total
$count_sql = "SELECT COUNT(*) as total FROM objek_wisata $where";
if (!empty($search)) {
    $count_stmt = $conn->prepare($count_sql);
    $count_stmt->bind_param("sssss", $search_param, $search_param, $search_param, $search_param, $search_param);
    $count_stmt->execute();
    $total = $count_stmt->get_result()->fetch_assoc()['total'];
    $count_stmt->close();
} else {
    $total = $conn->query($count_sql)->fetch_assoc()['total'];
}

$total_pages = ceil($total / $limit);

// Get data
$sql = "SELECT * FROM objek_wisata $where ORDER BY id DESC LIMIT ? OFFSET ?";
if (!empty($search)) {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssii", $search_param, $search_param, $search_param, $search_param, $search_param, $limit, $offset);
} else {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $limit, $offset);
}
$stmt->execute();
$result = $stmt->get_result();

$objek_list = [];
while ($row = $result->fetch_assoc()) {
    $objek_list[] = $row;
}
$stmt->close();
$conn->close();

$page_title = 'Objek Wisata';
$show_navbar = true;
include __DIR__ . '/../includes/header.php';
?>

<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-0">
                        <i class="fas fa-map-marker-alt me-2"></i>Objek Wisata
                    </h2>
                    <p class="text-muted">Manajemen data objek wisata</p>
                </div>
                <?php if ($action == 'list'): ?>
                <button class="btn btn-primary" onclick="showForm()">
                    <i class="fas fa-plus me-2"></i>Tambah Objek Wisata
                </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if ($action == 'list'): ?>
        <!-- Search Form -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="">
                    <div class="row g-2">
                        <div class="col-12 col-md-10">
                            <input type="text" class="form-control" name="search" 
                                   placeholder="Cari objek wisata..." value="<?php echo htmlspecialchars($search); ?>">
                        </div>
                        <div class="col-12 col-md-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search me-2 d-md-inline d-none"></i>
                                <span class="d-md-none">Cari</span>
                                <span class="d-none d-md-inline">Cari</span>
                            </button>
                        </div>
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
                                <th>Nama Objek Wisata</th>
                                <th>Lokasi</th>
                                <th>Jenis</th>
                                <th>Wilayah Hukum</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="objek-table-body">
                            <?php if (count($objek_list) > 0): ?>
                                <?php foreach ($objek_list as $index => $obj): ?>
                                <tr>
                                    <td><?php echo $offset + $index + 1; ?></td>
                                    <td><strong><?php echo htmlspecialchars($obj['nama']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($obj['alamat'] ?? '-'); ?></td>
                                    <td><?php echo htmlspecialchars($obj['jenis'] ?? '-'); ?></td>
                                    <td><?php echo htmlspecialchars($obj['wilayah_hukum'] ?? '-'); ?></td>
                                    <td>
                                        <?php if (!empty($obj['keterangan'])): ?>
                                            <span class="badge <?php 
                                                if ($obj['keterangan'] == 'Kawasan Ekonomi Khusus') echo 'bg-warning';
                                                elseif ($obj['keterangan'] == 'Objek Vital nasional') echo 'bg-danger';
                                                else echo 'bg-info';
                                            ?>"><?php echo htmlspecialchars($obj['keterangan']); ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="?action=edit&id=<?php echo $obj['id']; ?>" class="btn btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="btn btn-danger btn-delete" data-id="<?php echo $obj['id']; ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada data</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Card View - Mobile -->
        <div class="d-md-none">
            <?php if (count($objek_list) > 0): ?>
                <?php foreach ($objek_list as $index => $obj): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="flex-grow-1">
                                <h5 class="card-title mb-1"><?php echo htmlspecialchars($obj['nama']); ?></h5>
                                <p class="text-muted small mb-1">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    <?php echo htmlspecialchars($obj['alamat'] ?? '-'); ?>
                                </p>
                                <?php if (!empty($obj['jenis'])): ?>
                                <p class="text-muted small mb-1">
                                    <i class="fas fa-tag me-1"></i>
                                    <?php echo htmlspecialchars($obj['jenis']); ?>
                                </p>
                                <?php endif; ?>
                                <?php if (!empty($obj['wilayah_hukum'])): ?>
                                <p class="text-muted small mb-1">
                                    <i class="fas fa-building me-1"></i>
                                    <?php echo htmlspecialchars($obj['wilayah_hukum']); ?>
                                </p>
                                <?php endif; ?>
                                <?php if (!empty($obj['keterangan'])): ?>
                                <p class="mb-0">
                                    <span class="badge <?php 
                                        if ($obj['keterangan'] == 'Kawasan Ekonomi Khusus') echo 'bg-warning';
                                        elseif ($obj['keterangan'] == 'Objek Vital nasional') echo 'bg-danger';
                                        else echo 'bg-info';
                                    ?>"><?php echo htmlspecialchars($obj['keterangan']); ?></span>
                                </p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="d-grid gap-2 mt-3">
                            <div class="btn-group" role="group">
                                <a href="?action=edit&id=<?php echo $obj['id']; ?>" class="btn btn-primary">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </a>
                                <button class="btn btn-danger btn-delete" data-id="<?php echo $obj['id']; ?>">
                                    <i class="fas fa-trash me-1"></i>Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-map-marker-alt fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Tidak ada data objek wisata</p>
                        <?php if ($current_user && $current_user['role'] == 'admin'): ?>
                        <a href="?action=create" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Tambah Objek Wisata
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                <nav>
                    <ul class="pagination justify-content-center">
                        <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>">Previous</a>
                        </li>
                        <?php endif; ?>
                        
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
                        </li>
                        <?php endfor; ?>
                        
                        <?php if ($page < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>">Next</a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </nav>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Form Modal (hidden by default) -->
        <div class="card mt-4" id="form-card" style="display: none;">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-plus me-2"></i>Tambah Objek Wisata</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="" id="objek-form">
                    <input type="hidden" name="action" value="create">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Objek Wisata <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Lokasi/Kota/Kabupaten</label>
                        <input type="text" class="form-control" id="alamat" name="alamat" placeholder="Contoh: Parbaba/Samosir">
                    </div>
                    <div class="mb-3">
                        <label for="jenis" class="form-label">Jenis Wisata</label>
                        <select class="form-select" id="jenis" name="jenis">
                            <option value="">Pilih Jenis Wisata</option>
                            <option value="Wisata Alam">Wisata Alam</option>
                            <option value="Wisata Budaya">Wisata Budaya</option>
                            <option value="Wisata Sejarah dan Budaya">Wisata Sejarah dan Budaya</option>
                            <option value="Wisata Belanja">Wisata Belanja</option>
                            <option value="Wisata Religi">Wisata Religi</option>
                            <option value="Agrowisata">Agrowisata</option>
                            <option value="Wisata Alam dan Wisata Religi">Wisata Alam dan Wisata Religi</option>
                            <option value="Wisata budaya dan Wisata Religi">Wisata budaya dan Wisata Religi</option>
                            <option value="Wisata Budaya dan wisata religi">Wisata Budaya dan wisata religi</option>
                            <option value="Wisata alam dan Budaya">Wisata alam dan Budaya</option>
                        </select>
                        <small class="form-text text-muted">Atau ketik jenis wisata lainnya</small>
                    </div>
                    <div class="mb-3">
                        <label for="wilayah_hukum" class="form-label">Wilayah Hukum</label>
                        <input type="text" class="form-control" id="wilayah_hukum" name="wilayah_hukum" 
                               value="Polres Samosir" placeholder="Contoh: Polres Samosir">
                    </div>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <select class="form-select" id="keterangan" name="keterangan">
                            <option value="">Pilih Keterangan</option>
                            <option value="Kawasan Ekonomi Khusus">Kawasan Ekonomi Khusus</option>
                            <option value="Objek Wisata Biasa">Objek Wisata Biasa</option>
                            <option value="Objek Vital nasional">Objek Vital nasional</option>
                        </select>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" onclick="hideForm()">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
        
    <?php elseif ($action == 'edit'): ?>
        <!-- Edit Form -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Objek Wisata</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id" value="<?php echo $objek['id']; ?>">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Objek Wisata <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama" name="nama" 
                               value="<?php echo htmlspecialchars($objek['nama'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Lokasi/Kota/Kabupaten</label>
                        <input type="text" class="form-control" id="alamat" name="alamat" 
                               value="<?php echo htmlspecialchars($objek['alamat'] ?? ''); ?>" placeholder="Contoh: Parbaba/Samosir">
                    </div>
                    <div class="mb-3">
                        <label for="jenis" class="form-label">Jenis Wisata</label>
                        <select class="form-select" id="jenis" name="jenis">
                            <option value="">Pilih Jenis Wisata</option>
                            <option value="Wisata Alam" <?php echo (isset($objek['jenis']) && $objek['jenis'] == 'Wisata Alam') ? 'selected' : ''; ?>>Wisata Alam</option>
                            <option value="Wisata Budaya" <?php echo (isset($objek['jenis']) && $objek['jenis'] == 'Wisata Budaya') ? 'selected' : ''; ?>>Wisata Budaya</option>
                            <option value="Wisata Sejarah dan Budaya" <?php echo (isset($objek['jenis']) && $objek['jenis'] == 'Wisata Sejarah dan Budaya') ? 'selected' : ''; ?>>Wisata Sejarah dan Budaya</option>
                            <option value="Wisata Belanja" <?php echo (isset($objek['jenis']) && $objek['jenis'] == 'Wisata Belanja') ? 'selected' : ''; ?>>Wisata Belanja</option>
                            <option value="Wisata Religi" <?php echo (isset($objek['jenis']) && $objek['jenis'] == 'Wisata Religi') ? 'selected' : ''; ?>>Wisata Religi</option>
                            <option value="Agrowisata" <?php echo (isset($objek['jenis']) && $objek['jenis'] == 'Agrowisata') ? 'selected' : ''; ?>>Agrowisata</option>
                            <option value="Wisata Alam dan Wisata Religi" <?php echo (isset($objek['jenis']) && $objek['jenis'] == 'Wisata Alam dan Wisata Religi') ? 'selected' : ''; ?>>Wisata Alam dan Wisata Religi</option>
                            <option value="Wisata budaya dan Wisata Religi" <?php echo (isset($objek['jenis']) && $objek['jenis'] == 'Wisata budaya dan Wisata Religi') ? 'selected' : ''; ?>>Wisata budaya dan Wisata Religi</option>
                            <option value="Wisata Budaya dan wisata religi" <?php echo (isset($objek['jenis']) && $objek['jenis'] == 'Wisata Budaya dan wisata religi') ? 'selected' : ''; ?>>Wisata Budaya dan wisata religi</option>
                            <option value="Wisata alam dan Budaya" <?php echo (isset($objek['jenis']) && $objek['jenis'] == 'Wisata alam dan Budaya') ? 'selected' : ''; ?>>Wisata alam dan Budaya</option>
                        </select>
                        <small class="form-text text-muted">Atau ketik jenis wisata lainnya</small>
                    </div>
                    <div class="mb-3">
                        <label for="wilayah_hukum" class="form-label">Wilayah Hukum</label>
                        <input type="text" class="form-control" id="wilayah_hukum" name="wilayah_hukum" 
                               value="<?php echo htmlspecialchars($objek['wilayah_hukum'] ?? 'Polres Samosir'); ?>" placeholder="Contoh: Polres Samosir">
                    </div>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <select class="form-select" id="keterangan" name="keterangan">
                            <option value="">Pilih Keterangan</option>
                            <option value="Kawasan Ekonomi Khusus" <?php echo (isset($objek['keterangan']) && $objek['keterangan'] == 'Kawasan Ekonomi Khusus') ? 'selected' : ''; ?>>Kawasan Ekonomi Khusus</option>
                            <option value="Objek Wisata Biasa" <?php echo (isset($objek['keterangan']) && $objek['keterangan'] == 'Objek Wisata Biasa') ? 'selected' : ''; ?>>Objek Wisata Biasa</option>
                            <option value="Objek Vital nasional" <?php echo (isset($objek['keterangan']) && $objek['keterangan'] == 'Objek Vital nasional') ? 'selected' : ''; ?>>Objek Vital nasional</option>
                        </select>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <a href="?" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus objek wisata ini?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form method="POST" action="" id="delete-form" style="display: inline;">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" id="delete-id">
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function showForm() {
    $('#form-card').slideDown();
    $('#nama').focus();
}

function hideForm() {
    $('#form-card').slideUp();
    $('#objek-form')[0].reset();
}

// Delete confirmation
$(document).ready(function() {
    $('.btn-delete').on('click', function() {
        const id = $(this).data('id');
        $('#delete-id').val(id);
        $('#deleteModal').modal('show');
    });
});
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>

