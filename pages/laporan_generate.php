<?php
/**
 * Generate PDF Laporan
 * Risk Assessment Objek Wisata
 * Menggunakan TCPDF atau DomPDF
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

$penilaian_id = $_GET['penilaian_id'] ?? null;
$tahun = $_GET['tahun'] ?? date('Y');

if (!$penilaian_id) {
    die('Penilaian ID tidak ditemukan');
}

$conn = getDBConnection();

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
    die('Data penilaian tidak ditemukan');
}

$penilaian = $result->fetch_assoc();
$stmt->close();

// Get all aspek dengan detail
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

// Get Kasat Pamobvit (ambil admin pertama)
$kasat_stmt = $conn->prepare("
    SELECT * FROM users 
    WHERE role = 'admin'
    LIMIT 1
");

if ($kasat_stmt) {
    $kasat_stmt->execute();
    $kasat_result = $kasat_stmt->get_result();
    $kasat = $kasat_result ? $kasat_result->fetch_assoc() : null;
    $kasat_stmt->close();
} else {
    $kasat = null;
}

$conn->close();

// Generate HTML untuk PDF
ob_start();
include __DIR__ . '/../includes/laporan_template.php';
$html_content = ob_get_clean();

// Check if TCPDF is available, otherwise use simple HTML output
if (class_exists('TCPDF')) {
    require_once __DIR__ . '/../includes/pdf_generator.php';
    generatePDF($html_content, $penilaian, $tahun);
} else {
    // Fallback: Output HTML (can be printed to PDF via browser)
    header('Content-Type: text/html; charset=utf-8');
    echo $html_content;
}

?>

