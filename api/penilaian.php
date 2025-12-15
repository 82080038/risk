<?php
/**
 * API Penilaian
 * GET, POST, PUT
 */

require_once __DIR__ . '/api_base.php';
requireApiLogin();

$method = $_SERVER['REQUEST_METHOD'];
$conn = getDBConnection();

try {
    switch ($method) {
        case 'GET':
            $id = $_GET['id'] ?? null;
            $objek_id = $_GET['objek_id'] ?? null;
            
            if ($id) {
                // Get single penilaian dengan detail
                $stmt = $conn->prepare("
                    SELECT p.*, ow.nama as objek_nama, ow.alamat as objek_alamat,
                           u.nama as penilai_nama, u.pangkat_nrp as penilai_pangkat_nrp
                    FROM penilaian p
                    JOIN objek_wisata ow ON p.objek_wisata_id = ow.id
                    JOIN users u ON p.user_id = u.id
                    WHERE p.id = ?
                ");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0) {
                    $penilaian = $result->fetch_assoc();
                    
                    // Get detail penilaian
                    $detail_stmt = $conn->prepare("
                        SELECT pd.*, k.deskripsi as kriteria_deskripsi, 
                               e.nama as elemen_nama, a.nama as aspek_nama
                        FROM penilaian_detail pd
                        JOIN kriteria k ON pd.kriteria_id = k.id
                        JOIN elemen e ON k.elemen_id = e.id
                        JOIN aspek a ON e.aspek_id = a.id
                        WHERE pd.penilaian_id = ?
                        ORDER BY a.urutan, e.urutan, k.urutan
                    ");
                    $detail_stmt->bind_param("i", $id);
                    $detail_stmt->execute();
                    $detail_result = $detail_stmt->get_result();
                    
                    $details = [];
                    while ($row = $detail_result->fetch_assoc()) {
                        $details[] = $row;
                    }
                    
                    $penilaian['details'] = $details;
                    jsonSuccess('Data ditemukan', $penilaian);
                } else {
                    jsonError('Data tidak ditemukan', 404);
                }
                
                $stmt->close();
                $detail_stmt->close();
            } else {
                // Get list
                $page = intval($_GET['page'] ?? 1);
                $limit = intval($_GET['limit'] ?? 10);
                $offset = ($page - 1) * $limit;
                $status = $_GET['status'] ?? '';
                
                $where = "WHERE 1=1";
                if ($objek_id) {
                    $where .= " AND p.objek_wisata_id = " . intval($objek_id);
                }
                if ($status) {
                    $where .= " AND p.status = '" . $conn->real_escape_string($status) . "'";
                }
                
                // Count
                $count_sql = "SELECT COUNT(*) as total FROM penilaian p $where";
                $total = $conn->query($count_sql)->fetch_assoc()['total'];
                
                // Get data
                $sql = "
                    SELECT p.*, ow.nama as objek_nama, u.nama as penilai_nama
                    FROM penilaian p
                    JOIN objek_wisata ow ON p.objek_wisata_id = ow.id
                    JOIN users u ON p.user_id = u.id
                    $where
                    ORDER BY p.tanggal_penilaian DESC, p.created_at DESC
                    LIMIT ? OFFSET ?
                ";
                
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ii", $limit, $offset);
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
                    'limit' => $limit
                ]);
                
                $stmt->close();
            }
            break;
        
        case 'POST':
            // Create new penilaian
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (empty($data['objek_wisata_id'])) {
                jsonError('Objek wisata wajib dipilih');
            }
            
            $objek_id = intval($data['objek_wisata_id']);
            $user_id = $_SESSION['user_id'];
            $tanggal = $data['tanggal_penilaian'] ?? date('Y-m-d');
            $nama_penilai = sanitize($data['nama_penilai'] ?? $_SESSION['nama']);
            $pangkat_nrp = sanitize($data['pangkat_nrp'] ?? '');
            $status = $data['status'] ?? 'draft';
            
            $conn->autocommit(FALSE);
            
            try {
                // Insert penilaian
                $stmt = $conn->prepare("
                    INSERT INTO penilaian (objek_wisata_id, user_id, tanggal_penilaian, 
                                          nama_penilai, pangkat_nrp, status)
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
                $stmt->bind_param("iissss", $objek_id, $user_id, $tanggal, 
                                 $nama_penilai, $pangkat_nrp, $status);
                $stmt->execute();
                $penilaian_id = $conn->insert_id;
                $stmt->close();
                
                // Insert/Update details jika ada
                if (!empty($data['details']) && is_array($data['details'])) {
                    $detail_stmt = $conn->prepare("
                        INSERT INTO penilaian_detail (penilaian_id, kriteria_id, nilai, temuan, rekomendasi)
                        VALUES (?, ?, ?, ?, ?)
                        ON DUPLICATE KEY UPDATE 
                            nilai = VALUES(nilai),
                            temuan = VALUES(temuan),
                            rekomendasi = VALUES(rekomendasi)
                    ");
                    
                    foreach ($data['details'] as $detail) {
                        $kriteria_id = intval($detail['kriteria_id']);
                        $nilai = intval($detail['nilai']);
                        $temuan = sanitize($detail['temuan'] ?? '');
                        $rekomendasi = sanitize($detail['rekomendasi'] ?? '');
                        
                        $detail_stmt->bind_param("iiiss", $penilaian_id, $kriteria_id, 
                                                $nilai, $temuan, $rekomendasi);
                        $detail_stmt->execute();
                    }
                    
                    $detail_stmt->close();
                }
                
                // Update skor final dan kategori
                if (isset($data['skor_final'])) {
                    $skor_final = floatval($data['skor_final']);
                    $kategori = sanitize($data['kategori'] ?? '');
                    
                    $update_stmt = $conn->prepare("
                        UPDATE penilaian 
                        SET skor_final = ?, kategori = ? 
                        WHERE id = ?
                    ");
                    $update_stmt->bind_param("dsi", $skor_final, $kategori, $penilaian_id);
                    $update_stmt->execute();
                    $update_stmt->close();
                }
                
                $conn->commit();
                jsonSuccess('Penilaian berhasil disimpan', ['id' => $penilaian_id]);
                
            } catch (Exception $e) {
                $conn->rollback();
                jsonError('Gagal menyimpan penilaian: ' . $e->getMessage());
            }
            
            $conn->autocommit(TRUE);
            break;
        
        case 'PUT':
            // Update penilaian
            $id = $_GET['id'] ?? null;
            if (!$id) {
                jsonError('ID wajib diisi');
            }
            
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Update header
            if (isset($data['status']) || isset($data['skor_final'])) {
                $updates = [];
                $params = [];
                $types = '';
                
                if (isset($data['status'])) {
                    $updates[] = "status = ?";
                    $params[] = $data['status'];
                    $types .= 's';
                }
                
                if (isset($data['skor_final'])) {
                    $updates[] = "skor_final = ?";
                    $params[] = floatval($data['skor_final']);
                    $types .= 'd';
                    
                    // Update kategori
                    $kategori = getKategori($data['skor_final']);
                    $updates[] = "kategori = ?";
                    $params[] = $kategori['nama'];
                    $types .= 's';
                }
                
                if (!empty($updates)) {
                    $params[] = $id;
                    $types .= 'i';
                    
                    $sql = "UPDATE penilaian SET " . implode(', ', $updates) . " WHERE id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param($types, ...$params);
                    $stmt->execute();
                    $stmt->close();
                }
            }
            
            // Update details
            if (!empty($data['details']) && is_array($data['details'])) {
                foreach ($data['details'] as $detail) {
                    $kriteria_id = intval($detail['kriteria_id']);
                    $nilai = intval($detail['nilai']);
                    $temuan = sanitize($detail['temuan'] ?? '');
                    $rekomendasi = sanitize($detail['rekomendasi'] ?? '');
                    
                    $stmt = $conn->prepare("
                        INSERT INTO penilaian_detail (penilaian_id, kriteria_id, nilai, temuan, rekomendasi)
                        VALUES (?, ?, ?, ?, ?)
                        ON DUPLICATE KEY UPDATE 
                            nilai = VALUES(nilai),
                            temuan = VALUES(temuan),
                            rekomendasi = VALUES(rekomendasi)
                    ");
                    $stmt->bind_param("iiiss", $id, $kriteria_id, $nilai, $temuan, $rekomendasi);
                    $stmt->execute();
                    $stmt->close();
                }
            }
            
            jsonSuccess('Penilaian berhasil diupdate');
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

