<?php
/**
 * API Objek Wisata
 * GET, POST, PUT, DELETE
 */

require_once __DIR__ . '/api_base.php';
requireApiLogin();

$method = $_SERVER['REQUEST_METHOD'];
$conn = getDBConnection();

try {
    switch ($method) {
        case 'GET':
            // Get all atau by ID
            $id = $_GET['id'] ?? null;
            
            if ($id) {
                // Get single
                $stmt = $conn->prepare("SELECT * FROM objek_wisata WHERE id = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0) {
                    jsonSuccess('Data ditemukan', $result->fetch_assoc());
                } else {
                    jsonError('Data tidak ditemukan', 404);
                }
                $stmt->close();
            } else {
                // Get all dengan pagination
                $page = intval($_GET['page'] ?? 1);
                $limit = intval($_GET['limit'] ?? 10);
                $offset = ($page - 1) * $limit;
                $search = $_GET['search'] ?? '';
                
                $where = '';
                $params = [];
                $types = '';
                
                if (!empty($search)) {
                    $where = "WHERE nama LIKE ? OR alamat LIKE ? OR jenis LIKE ? OR wilayah_hukum LIKE ? OR keterangan LIKE ?";
                    $search_param = "%$search%";
                    $params = [$search_param, $search_param, $search_param, $search_param, $search_param];
                    $types = 'sssss';
                }
                
                // Count total
                $count_sql = "SELECT COUNT(*) as total FROM objek_wisata $where";
                if (!empty($params)) {
                    $count_stmt = $conn->prepare($count_sql);
                    $count_stmt->bind_param($types, ...$params);
                    $count_stmt->execute();
                    $total = $count_stmt->get_result()->fetch_assoc()['total'];
                    $count_stmt->close();
                } else {
                    $total = $conn->query($count_sql)->fetch_assoc()['total'];
                }
                
                // Get data
                $sql = "SELECT * FROM objek_wisata $where ORDER BY id DESC LIMIT ? OFFSET ?";
                if (!empty($params)) {
                    $stmt = $conn->prepare($sql);
                    $params[] = $limit;
                    $params[] = $offset;
                    $types .= 'ii';
                    $stmt->bind_param($types, ...$params);
                } else {
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ii", $limit, $offset);
                }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        
        jsonSuccess('Data berhasil diambil', [
            'data' => $data,
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'total_pages' => ceil($total / $limit)
        ]);
        
        $stmt->close();
    }
    break;
    
    case 'POST':
        // Create new
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (empty($data['nama'])) {
            jsonError('Nama objek wisata wajib diisi');
        }
        
        $nama = sanitize($data['nama']);
        $alamat = sanitize($data['alamat'] ?? '');
        $jenis = sanitize($data['jenis'] ?? '');
        $wilayah_hukum = sanitize($data['wilayah_hukum'] ?? '');
        $keterangan = sanitize($data['keterangan'] ?? '');
        
        $stmt = $conn->prepare("INSERT INTO objek_wisata (nama, alamat, jenis, wilayah_hukum, keterangan) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $nama, $alamat, $jenis, $wilayah_hukum, $keterangan);
        
        if ($stmt->execute()) {
            $id = $conn->insert_id;
            jsonSuccess('Objek wisata berhasil ditambahkan', ['id' => $id]);
        } else {
            jsonError('Gagal menambahkan objek wisata: ' . $stmt->error);
        }
        
        $stmt->close();
        break;
    
    case 'PUT':
        // Update
        $id = $_GET['id'] ?? null;
        if (!$id) {
            jsonError('ID wajib diisi');
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (empty($data['nama'])) {
            jsonError('Nama objek wisata wajib diisi');
        }
        
        $nama = sanitize($data['nama']);
        $alamat = sanitize($data['alamat'] ?? '');
        $jenis = sanitize($data['jenis'] ?? '');
        $wilayah_hukum = sanitize($data['wilayah_hukum'] ?? '');
        $keterangan = sanitize($data['keterangan'] ?? '');
        
        $stmt = $conn->prepare("UPDATE objek_wisata SET nama = ?, alamat = ?, jenis = ?, wilayah_hukum = ?, keterangan = ? WHERE id = ?");
        $stmt->bind_param("sssssi", $nama, $alamat, $jenis, $wilayah_hukum, $keterangan, $id);
        
        if ($stmt->execute()) {
            jsonSuccess('Objek wisata berhasil diupdate');
        } else {
            jsonError('Gagal mengupdate objek wisata: ' . $stmt->error);
        }
        
        $stmt->close();
        break;
    
    case 'DELETE':
        // Delete
        $id = $_GET['id'] ?? null;
        if (!$id) {
            jsonError('ID wajib diisi');
        }
        
        $stmt = $conn->prepare("DELETE FROM objek_wisata WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            jsonSuccess('Objek wisata berhasil dihapus');
        } else {
            jsonError('Gagal menghapus objek wisata: ' . $stmt->error);
        }
        
        $stmt->close();
        break;
    
    default:
        jsonError('Method tidak didukung', 405);
}
} catch (Exception $e) {
    jsonError('Error: ' . $e->getMessage(), 500);
} finally {
    $conn->close();
}
?>

