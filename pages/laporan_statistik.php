<?php
/**
 * Laporan Statistik Detail
 * Risk Assessment Objek Wisata
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

$current_user = getCurrentUser();
$conn = getDBConnection();

$filter_aspek = $_GET['aspek'] ?? 'all';
$filter_objek = $_GET['objek'] ?? 'all';
$filter_personil = $_GET['personil'] ?? 'all';
$export = $_GET['export'] ?? '';

// Get all aspek
$aspek_result = $conn->query("SELECT * FROM aspek ORDER BY urutan");
$aspek_list = [];
while ($aspek = $aspek_result->fetch_assoc()) {
    $aspek_list[] = $aspek;
}

// Get all objek wisata
$objek_result = $conn->query("SELECT * FROM objek_wisata ORDER BY nama");
$objek_list = [];
while ($objek = $objek_result->fetch_assoc()) {
    $objek_list[] = $objek;
}

// Get all personil
$personil_result = $conn->query("SELECT DISTINCT u.* FROM users u JOIN penilaian p ON u.id = p.user_id WHERE p.status = 'selesai' ORDER BY u.nama");
$personil_list = [];
while ($personil = $personil_result->fetch_assoc()) {
    $personil_list[] = $personil;
}

// Build query
$where_conditions = ["p.status = 'selesai'"];
$params = [];
$types = '';

if ($filter_aspek !== 'all') {
    $where_conditions[] = "a.id = ?";
    $params[] = $filter_aspek;
    $types .= 'i';
}

if ($filter_objek !== 'all') {
    $where_conditions[] = "p.objek_wisata_id = ?";
    $params[] = $filter_objek;
    $types .= 'i';
}

if ($filter_personil !== 'all') {
    $where_conditions[] = "p.user_id = ?";
    $params[] = $filter_personil;
    $types .= 'i';
}

$where_clause = implode(' AND ', $where_conditions);

// Statistik Per Aspek
$aspek_stats = [];
foreach ($aspek_list as $aspek) {
    $sql = "
        SELECT 
            COUNT(DISTINCT p.id) as total_penilaian,
            AVG(p.skor_final) as avg_skor,
            MIN(p.skor_final) as min_skor,
            MAX(p.skor_final) as max_skor,
            SUM(CASE WHEN p.kategori LIKE '%Emas%' THEN 1 ELSE 0 END) as emas,
            SUM(CASE WHEN p.kategori LIKE '%Perak%' THEN 1 ELSE 0 END) as perak,
            SUM(CASE WHEN p.kategori LIKE '%Perunggu%' THEN 1 ELSE 0 END) as perunggu,
            SUM(CASE WHEN p.kategori LIKE '%Kurang%' THEN 1 ELSE 0 END) as kurang
        FROM penilaian p
        JOIN penilaian_detail pd ON p.id = pd.penilaian_id
        JOIN kriteria k ON pd.kriteria_id = k.id
        JOIN elemen e ON k.elemen_id = e.id
        JOIN aspek a ON e.aspek_id = a.id
        WHERE a.id = ? AND p.status = 'selesai'
    ";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $aspek['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $aspek_stats[$aspek['id']] = $result->fetch_assoc();
    $stmt->close();
}

// Statistik Per Objek Wisata
$objek_stats = [];
foreach ($objek_list as $objek) {
    $sql = "
        SELECT 
            COUNT(*) as total_penilaian,
            AVG(skor_final) as avg_skor,
            MIN(skor_final) as min_skor,
            MAX(skor_final) as max_skor,
            MAX(tanggal_penilaian) as last_penilaian
        FROM penilaian
        WHERE objek_wisata_id = ? AND status = 'selesai'
    ";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $objek['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $objek_stats[$objek['id']] = $result->fetch_assoc();
    $stmt->close();
}

// Statistik Per Personil
$personil_stats = [];
foreach ($personil_list as $personil) {
    $sql = "
        SELECT 
            COUNT(*) as total_penilaian,
            AVG(skor_final) as avg_skor,
            MIN(skor_final) as min_skor,
            MAX(skor_final) as max_skor
        FROM penilaian
        WHERE user_id = ? AND status = 'selesai'
    ";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $personil['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $personil_stats[$personil['id']] = $result->fetch_assoc();
    $stmt->close();
}

// Export to CSV
if ($export === 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="laporan_statistik_' . date('Ymd') . '.csv"');
    echo "\xEF\xBB\xBF";
    
    // Statistik Per Aspek
    echo "STATISTIK PER ASPEK\n";
    echo "Aspek,Total Penilaian,Rata-rata Skor,Min Skor,Max Skor,Emas,Perak,Perunggu,Kurang\n";
    foreach ($aspek_list as $aspek) {
        $stat = $aspek_stats[$aspek['id']];
        echo sprintf('"%s",%d,%.2f,%.2f,%.2f,%d,%d,%d,%d\n',
            $aspek['nama'],
            $stat['total_penilaian'] ?? 0,
            $stat['avg_skor'] ?? 0,
            $stat['min_skor'] ?? 0,
            $stat['max_skor'] ?? 0,
            $stat['emas'] ?? 0,
            $stat['perak'] ?? 0,
            $stat['perunggu'] ?? 0,
            $stat['kurang'] ?? 0
        );
    }
    
    echo "\nSTATISTIK PER OBJEK WISATA\n";
    echo "Objek Wisata,Total Penilaian,Rata-rata Skor,Min Skor,Max Skor,Penilaian Terakhir\n";
    foreach ($objek_list as $objek) {
        $stat = $objek_stats[$objek['id']];
        echo sprintf('"%s",%d,%.2f,%.2f,%.2f,"%s"\n',
            $objek['nama'],
            $stat['total_penilaian'] ?? 0,
            $stat['avg_skor'] ?? 0,
            $stat['min_skor'] ?? 0,
            $stat['max_skor'] ?? 0,
            $stat['last_penilaian'] ?? '-'
        );
    }
    
    echo "\nSTATISTIK PER PERSONIL\n";
    echo "Personil,Total Penilaian,Rata-rata Skor,Min Skor,Max Skor\n";
    foreach ($personil_list as $personil) {
        $stat = $personil_stats[$personil['id']];
        echo sprintf('"%s",%d,%.2f,%.2f,%.2f\n',
            $personil['nama'],
            $stat['total_penilaian'] ?? 0,
            $stat['avg_skor'] ?? 0,
            $stat['min_skor'] ?? 0,
            $stat['max_skor'] ?? 0
        );
    }
    
    $conn->close();
    exit;
}

$conn->close();

$page_title = 'Laporan Statistik';
$show_navbar = true;
include __DIR__ . '/../includes/header.php';
?>

<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0">
                <i class="fas fa-chart-line me-2"></i>Laporan Statistik Detail
            </h2>
            <p class="text-muted">Statistik lengkap penilaian risiko objek wisata</p>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-12 col-md-4">
                    <label class="form-label">Filter Aspek</label>
                    <select name="aspek" class="form-select">
                        <option value="all" <?php echo $filter_aspek === 'all' ? 'selected' : ''; ?>>Semua Aspek</option>
                        <?php foreach ($aspek_list as $aspek): ?>
                        <option value="<?php echo $aspek['id']; ?>" <?php echo $filter_aspek == $aspek['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($aspek['nama']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label">Filter Objek Wisata</label>
                    <select name="objek" class="form-select">
                        <option value="all" <?php echo $filter_objek === 'all' ? 'selected' : ''; ?>>Semua Objek</option>
                        <?php foreach ($objek_list as $objek): ?>
                        <option value="<?php echo $objek['id']; ?>" <?php echo $filter_objek == $objek['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($objek['nama']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label">Filter Personil</label>
                    <select name="personil" class="form-select">
                        <option value="all" <?php echo $filter_personil === 'all' ? 'selected' : ''; ?>>Semua Personil</option>
                        <?php foreach ($personil_list as $personil): ?>
                        <option value="<?php echo $personil['id']; ?>" <?php echo $filter_personil == $personil['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($personil['nama']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-2"></i>Filter
                    </button>
                    <a href="?export=csv&aspek=<?php echo $filter_aspek; ?>&objek=<?php echo $filter_objek; ?>&personil=<?php echo $filter_personil; ?>" class="btn btn-success">
                        <i class="fas fa-file-csv me-2"></i>Export CSV
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Statistik Per Aspek -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Statistik Per Aspek</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Aspek</th>
                            <th>Total Penilaian</th>
                            <th>Rata-rata Skor</th>
                            <th>Min Skor</th>
                            <th>Max Skor</th>
                            <th>Emas</th>
                            <th>Perak</th>
                            <th>Perunggu</th>
                            <th>Kurang</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($aspek_list as $aspek): 
                            $stat = $aspek_stats[$aspek['id']];
                        ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($aspek['nama']); ?></strong></td>
                            <td><?php echo $stat['total_penilaian'] ?? 0; ?></td>
                            <td><?php echo number_format($stat['avg_skor'] ?? 0, 2); ?>%</td>
                            <td><?php echo number_format($stat['min_skor'] ?? 0, 2); ?>%</td>
                            <td><?php echo number_format($stat['max_skor'] ?? 0, 2); ?>%</td>
                            <td><span class="badge bg-warning"><?php echo $stat['emas'] ?? 0; ?></span></td>
                            <td><span class="badge bg-secondary"><?php echo $stat['perak'] ?? 0; ?></span></td>
                            <td><span class="badge bg-info"><?php echo $stat['perunggu'] ?? 0; ?></span></td>
                            <td><span class="badge bg-danger"><?php echo $stat['kurang'] ?? 0; ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Statistik Per Objek Wisata -->
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Statistik Per Objek Wisata</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Objek Wisata</th>
                            <th>Total Penilaian</th>
                            <th>Rata-rata Skor</th>
                            <th>Min Skor</th>
                            <th>Max Skor</th>
                            <th>Penilaian Terakhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($objek_list as $objek): 
                            $stat = $objek_stats[$objek['id']];
                            if ($stat['total_penilaian'] > 0):
                        ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($objek['nama']); ?></strong></td>
                            <td><?php echo $stat['total_penilaian']; ?></td>
                            <td><?php echo number_format($stat['avg_skor'], 2); ?>%</td>
                            <td><?php echo number_format($stat['min_skor'], 2); ?>%</td>
                            <td><?php echo number_format($stat['max_skor'], 2); ?>%</td>
                            <td><?php echo $stat['last_penilaian'] ? date('d/m/Y', strtotime($stat['last_penilaian'])) : '-'; ?></td>
                        </tr>
                        <?php 
                            endif;
                        endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Statistik Per Personil -->
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="fas fa-users me-2"></i>Statistik Per Personil</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Personil</th>
                            <th>Pangkat/NRP</th>
                            <th>Total Penilaian</th>
                            <th>Rata-rata Skor</th>
                            <th>Min Skor</th>
                            <th>Max Skor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($personil_list as $personil): 
                            $stat = $personil_stats[$personil['id']];
                        ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($personil['nama']); ?></strong></td>
                            <td><?php echo htmlspecialchars($personil['pangkat_nrp'] ?? '-'); ?></td>
                            <td><?php echo $stat['total_penilaian'] ?? 0; ?></td>
                            <td><?php echo number_format($stat['avg_skor'] ?? 0, 2); ?>%</td>
                            <td><?php echo number_format($stat['min_skor'] ?? 0, 2); ?>%</td>
                            <td><?php echo number_format($stat['max_skor'] ?? 0, 2); ?>%</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>

