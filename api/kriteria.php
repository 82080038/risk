<?php
/**
 * API Kriteria
 * GET - Ambil data kriteria per aspek/elemen
 */

require_once __DIR__ . '/api_base.php';
requireApiLogin();

$method = $_SERVER['REQUEST_METHOD'];
$conn = getDBConnection();

if ($method !== 'GET') {
    jsonError('Method tidak didukung', 405);
}

try {
    $aspek_id = $_GET['aspek_id'] ?? null;
    $elemen_id = $_GET['elemen_id'] ?? null;
    
    if ($aspek_id) {
        // Get semua kriteria dalam aspek
        $sql = "
            SELECT k.*, e.nama as elemen_nama, e.bobot as elemen_bobot,
                   a.nama as aspek_nama, a.bobot as aspek_bobot
            FROM kriteria k
            JOIN elemen e ON k.elemen_id = e.id
            JOIN aspek a ON e.aspek_id = a.id
            WHERE a.id = ?
            ORDER BY e.urutan, k.urutan
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $aspek_id);
    } elseif ($elemen_id) {
        // Get kriteria dalam elemen
        $sql = "
            SELECT k.*, e.nama as elemen_nama, e.bobot as elemen_bobot,
                   a.nama as aspek_nama, a.bobot as aspek_bobot
            FROM kriteria k
            JOIN elemen e ON k.elemen_id = e.id
            JOIN aspek a ON e.aspek_id = a.id
            WHERE e.id = ?
            ORDER BY k.urutan
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $elemen_id);
    } else {
        // Get semua aspek dengan struktur lengkap
        $sql = "
            SELECT a.*, 
                   GROUP_CONCAT(DISTINCT e.id ORDER BY e.urutan) as elemen_ids
            FROM aspek a
            LEFT JOIN elemen e ON a.id = e.aspek_id
            GROUP BY a.id
            ORDER BY a.urutan
        ";
        $result = $conn->query($sql);
        
        $aspek_list = [];
        while ($aspek = $result->fetch_assoc()) {
            // Get elemen
            $elemen_sql = "
                SELECT e.*,
                       GROUP_CONCAT(DISTINCT k.id ORDER BY k.urutan) as kriteria_ids
                FROM elemen e
                LEFT JOIN kriteria k ON e.id = k.elemen_id
                WHERE e.aspek_id = ?
                GROUP BY e.id
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
        
        jsonSuccess('Data berhasil diambil', $aspek_list);
        exit;
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    
    jsonSuccess('Data berhasil diambil', $data);
    $stmt->close();
    
} catch (Exception $e) {
    jsonError('Error: ' . $e->getMessage(), 500);
} finally {
    $conn->close();
}
?>

