<?php
/**
 * API Dashboard
 * GET - Statistik untuk dashboard
 */

require_once __DIR__ . '/api_base.php';
requireApiLogin();

$method = $_SERVER['REQUEST_METHOD'];
$conn = getDBConnection();

if ($method !== 'GET') {
    jsonError('Method tidak didukung', 405);
}

try {
    $stats = [];
    
    // Total Objek Wisata
    $result = $conn->query("SELECT COUNT(*) as total FROM objek_wisata");
    $stats['total_objek'] = intval($result->fetch_assoc()['total']);
    
    // Total Penilaian Selesai
    $result = $conn->query("SELECT COUNT(*) as total FROM penilaian WHERE status = 'selesai'");
    $stats['total_penilaian'] = intval($result->fetch_assoc()['total']);
    
    // Objek Sudah Dinilai
    $result = $conn->query("SELECT COUNT(DISTINCT objek_wisata_id) as total FROM penilaian WHERE status = 'selesai'");
    $stats['objek_sudah_dinilai'] = intval($result->fetch_assoc()['total']);
    
    // Objek Belum Dinilai
    $stats['objek_belum_dinilai'] = $stats['total_objek'] - $stats['objek_sudah_dinilai'];
    
    // Personil Aktif
    $result = $conn->query("SELECT COUNT(DISTINCT user_id) as total FROM penilaian WHERE status = 'selesai'");
    $stats['personil_aktif'] = intval($result->fetch_assoc()['total']);
    
    // Rata-rata Skor
    $result = $conn->query("SELECT AVG(skor_final) as avg FROM penilaian WHERE status = 'selesai' AND skor_final > 0");
    $avg_row = $result->fetch_assoc();
    $stats['rata_rata_skor'] = round(floatval($avg_row['avg']), 2);
    
    // Distribusi Kategori
    $result = $conn->query("
        SELECT kategori, COUNT(*) as jumlah 
        FROM penilaian 
        WHERE status = 'selesai' AND kategori IS NOT NULL
        GROUP BY kategori
    ");
    $stats['distribusi_kategori'] = [];
    while ($row = $result->fetch_assoc()) {
        $stats['distribusi_kategori'][] = $row;
    }
    
    jsonSuccess('Statistik berhasil diambil', $stats);
    
} catch (Exception $e) {
    jsonError('Error: ' . $e->getMessage(), 500);
} finally {
    $conn->close();
}
?>

