<?php
/**
 * SIMULASI PENILAIAN OTOMATIS - KATEGORI EMAS
 * Risk Assessment Objek Wisata
 * Script untuk melakukan simulasi penilaian dengan target kategori EMAS (86-100%)
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/functions.php';

// Start session untuk simulasi
session_start();

$conn = getDBConnection();

echo "<!DOCTYPE html>
<html lang='id'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Simulasi Penilaian - Kategori EMAS</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css'>
    <style>
        body { background: #f5f5f5; padding: 20px; }
        .card { margin-bottom: 20px; }
        .score-box { text-align: center; padding: 15px; border-radius: 8px; margin: 10px 0; }
        .score-excellent { background: #fff3cd; border: 3px solid #ffc107; }
        .calculation { background: #f8f9fa; padding: 10px; border-radius: 5px; margin: 5px 0; font-family: monospace; }
        table { font-size: 0.9em; }
        .badge-emas { background-color: #ffc107 !important; color: #000 !important; font-weight: bold; padding: 0.5rem 1rem; }
    </style>
</head>
<body>
<div class='container-fluid'>
    <div class='row'>
        <div class='col-12'>
            <h1 class='mb-4'><i class='fas fa-trophy me-2'></i>Simulasi Penilaian - Target Kategori EMAS 🥇</h1>";

try {
    // 1. Ambil objek wisata pertama
    $objek_result = $conn->query("SELECT * FROM objek_wisata LIMIT 1");
    if ($objek_result->num_rows == 0) {
        throw new Exception("Tidak ada objek wisata. Silakan tambahkan objek wisata terlebih dahulu.");
    }
    $objek = $objek_result->fetch_assoc();
    
    // 2. Ambil user pertama (penilai)
    $user_result = $conn->query("SELECT * FROM users WHERE role = 'penilai' LIMIT 1");
    if ($user_result->num_rows == 0) {
        throw new Exception("Tidak ada user penilai. Silakan tambahkan user terlebih dahulu.");
    }
    $user = $user_result->fetch_assoc();
    
    echo "<div class='card'>
        <div class='card-header bg-warning text-dark'>
            <h5 class='mb-0'><i class='fas fa-info-circle me-2'></i>Data Simulasi - Target EMAS</h5>
        </div>
        <div class='card-body'>
            <div class='row'>
                <div class='col-md-6'>
                    <p><strong>Objek Wisata:</strong> " . htmlspecialchars($objek['nama']) . "</p>
                    <p><strong>Alamat:</strong> " . htmlspecialchars($objek['alamat']) . "</p>
                </div>
                <div class='col-md-6'>
                    <p><strong>Penilai:</strong> " . htmlspecialchars($user['nama']) . "</p>
                    <p><strong>Pangkat/NRP:</strong> " . htmlspecialchars($user['pangkat_nrp']) . "</p>
                </div>
            </div>
            <div class='alert alert-warning mt-3'>
                <i class='fas fa-info-circle me-2'></i>
                <strong>Distribusi Nilai untuk Kategori EMAS:</strong><br>
                - 90% nilai 2 (Dapat dipenuhi)<br>
                - 8% nilai 1 (Terdapat kekurangan)<br>
                - 2% nilai 0 (Tidak dapat dipenuhi)<br>
                <small>Target skor: 86-100% (Kategori EMAS)</small>
            </div>
        </div>
    </div>";
    
    // 3. Buat penilaian baru
    $tanggal = date('Y-m-d');
    $stmt = $conn->prepare("
        INSERT INTO penilaian (objek_wisata_id, user_id, tanggal_penilaian, nama_penilai, pangkat_nrp, status)
        VALUES (?, ?, ?, ?, ?, 'draft')
    ");
    $stmt->bind_param("iisss", $objek['id'], $user['id'], $tanggal, $user['nama'], $user['pangkat_nrp']);
    $stmt->execute();
    $penilaian_id = $conn->insert_id;
    $stmt->close();
    
    echo "<div class='alert alert-success'>
        <i class='fas fa-check-circle me-2'></i>Penilaian baru dibuat dengan ID: <strong>$penilaian_id</strong>
    </div>";
    
    // 4. Ambil semua aspek, elemen, dan kriteria
    $aspek_sql = "SELECT * FROM aspek ORDER BY urutan";
    $aspek_result = $conn->query($aspek_sql);
    
    $aspek_list = [];
    $total_kriteria = 0;
    $total_nilai = 0;
    
    while ($aspek = $aspek_result->fetch_assoc()) {
        $elemen_sql = "SELECT * FROM elemen WHERE aspek_id = ? ORDER BY urutan";
        $elemen_stmt = $conn->prepare($elemen_sql);
        $elemen_stmt->bind_param("i", $aspek['id']);
        $elemen_stmt->execute();
        $elemen_result = $elemen_stmt->get_result();
        
        $elemen_list = [];
        while ($elemen = $elemen_result->fetch_assoc()) {
            $kriteria_sql = "SELECT * FROM kriteria WHERE elemen_id = ? ORDER BY urutan";
            $kriteria_stmt = $conn->prepare($kriteria_sql);
            $kriteria_stmt->bind_param("i", $elemen['id']);
            $kriteria_stmt->execute();
            $kriteria_result = $kriteria_stmt->get_result();
            
            $kriteria_list = [];
            $elemen_total_nilai = 0;
            $elemen_total_kriteria = 0;
            
            while ($kriteria = $kriteria_result->fetch_assoc()) {
                // Simulasi untuk KATEGORI EMAS: 90% nilai 2, 8% nilai 1, 2% nilai 0
                // Target: skor 86-100%
                $rand = rand(1, 100);
                if ($rand <= 2) {
                    // 2% nilai 0
                    $nilai = 0;
                    $temuan = "Temuan simulasi untuk kriteria " . $kriteria['nomor'] . " (Kategori EMAS)";
                    $rekomendasi = "Rekomendasi simulasi untuk kriteria " . $kriteria['nomor'] . " (Kategori EMAS)";
                } elseif ($rand <= 10) {
                    // 8% nilai 1
                    $nilai = 1;
                    $temuan = "Temuan simulasi untuk kriteria " . $kriteria['nomor'] . " (Kategori EMAS)";
                    $rekomendasi = "Rekomendasi simulasi untuk kriteria " . $kriteria['nomor'] . " (Kategori EMAS)";
                } else {
                    // 90% nilai 2
                    $nilai = 2;
                    $temuan = "";
                    $rekomendasi = "";
                }
                
                // Simpan ke database
                $detail_stmt = $conn->prepare("
                    INSERT INTO penilaian_detail (penilaian_id, kriteria_id, nilai, temuan, rekomendasi)
                    VALUES (?, ?, ?, ?, ?)
                ");
                $detail_stmt->bind_param("iiiss", $penilaian_id, $kriteria['id'], $nilai, $temuan, $rekomendasi);
                $detail_stmt->execute();
                $detail_stmt->close();
                
                $kriteria['nilai'] = $nilai;
                $kriteria['temuan'] = $temuan;
                $kriteria['rekomendasi'] = $rekomendasi;
                $kriteria_list[] = $kriteria;
                
                $elemen_total_nilai += $nilai;
                $elemen_total_kriteria++;
                $total_nilai += $nilai;
                $total_kriteria++;
            }
            
            // Hitung skor elemen
            $elemen['skor'] = $elemen_total_kriteria > 0 
                ? ($elemen_total_nilai / ($elemen_total_kriteria * 2)) * 100 
                : 0;
            $elemen['kriteria'] = $kriteria_list;
            $elemen['total_nilai'] = $elemen_total_nilai;
            $elemen['total_kriteria'] = $elemen_total_kriteria;
            $elemen_list[] = $elemen;
        }
        
        // Hitung skor aspek
        $aspek_total_skor = 0;
        $aspek_total_bobot = 0;
        foreach ($elemen_list as $elemen) {
            $elemen_bobot = floatval($elemen['bobot']);
            $aspek_total_skor += $elemen['skor'] * $elemen_bobot;
            $aspek_total_bobot += $elemen_bobot;
        }
        $aspek['skor'] = $aspek_total_bobot > 0 ? ($aspek_total_skor / $aspek_total_bobot) : 0;
        $aspek['bobot'] = floatval($aspek['bobot']);
        $aspek['kontribusi'] = $aspek['skor'] * $aspek['bobot'];
        $aspek['elemen'] = $elemen_list;
        $aspek_list[] = $aspek;
    }
    
    // 5. Hitung skor final
    $skor_final = 0;
    foreach ($aspek_list as $aspek) {
        $skor_final += $aspek['kontribusi'];
    }
    
    // 6. Tentukan kategori
    $kategori = getKategoriWithClass($skor_final);
    
    // 7. Update penilaian dengan skor final dan kategori
    $update_stmt = $conn->prepare("
        UPDATE penilaian 
        SET skor_final = ?, kategori = ?, status = 'selesai'
        WHERE id = ?
    ");
    $update_stmt->bind_param("dsi", $skor_final, $kategori['nama'], $penilaian_id);
    $update_stmt->execute();
    $update_stmt->close();
    
    // 8. Tampilkan hasil
    echo "<div class='card'>
        <div class='card-header bg-warning text-dark'>
            <h5 class='mb-0'><i class='fas fa-trophy me-2'></i>Hasil Simulasi Penilaian - KATEGORI EMAS</h5>
        </div>
        <div class='card-body'>";
    
    // Ringkasan
    echo "<div class='row mb-4'>
        <div class='col-md-3'>
            <div class='score-box score-excellent'>
                <h6>Skor Final</h6>
                <h2 class='text-warning mb-0'>" . number_format($skor_final, 2) . "%</h2>
            </div>
        </div>
        <div class='col-md-3'>
            <div class='score-box score-excellent'>
                <h6>Kategori</h6>
                <h3>
                    <span style='font-size: 2rem; margin-right: 0.5rem;'>" . $kategori['icon'] . "</span>
                    <strong>" . htmlspecialchars($kategori['badge_text'] ?? $kategori['nama']) . "</strong>
                </h3>
                <small class='text-muted'>" . htmlspecialchars($kategori['nama']) . "</small>
            </div>
        </div>
        <div class='col-md-3'>
            <div class='score-box'>
                <h6>Total Kriteria</h6>
                <h3>" . $total_kriteria . "</h3>
            </div>
        </div>
        <div class='col-md-3'>
            <div class='score-box'>
                <h6>Total Nilai</h6>
                <h3>" . $total_nilai . " / " . ($total_kriteria * 2) . "</h3>
            </div>
        </div>
    </div>";
    
    // Detail per aspek
    echo "<h5 class='mb-3'><i class='fas fa-list me-2'></i>Detail Per Aspek</h5>";
    
    foreach ($aspek_list as $aspek) {
        $aspek_kategori = getKategoriWithClass($aspek['skor']);
        echo "<div class='card mb-3'>
            <div class='card-header bg-primary text-white'>
                <h6 class='mb-0'>
                    " . htmlspecialchars($aspek['nama']) . " 
                    <span class='badge bg-light text-dark ms-2'>Bobot: " . ($aspek['bobot'] * 100) . "%</span>
                </h6>
            </div>
            <div class='card-body'>
                <div class='row mb-3'>
                    <div class='col-md-6'>
                        <div class='calculation'>
                            <strong>Skor Aspek:</strong> " . number_format($aspek['skor'], 2) . "%<br>
                            <small>Kontribusi ke Skor Final: " . number_format($aspek['kontribusi'], 2) . "%</small>
                        </div>
                    </div>
                    <div class='col-md-6'>
                        <div class='calculation'>
                            <strong>Kategori:</strong> 
                            <span class='badge " . $aspek_kategori['progress_class'] . " ms-2 px-3 py-2'>
                                <span style='font-size: 1.2rem; margin-right: 0.3rem;'>" . $aspek_kategori['icon'] . "</span>
                                <strong>" . htmlspecialchars($aspek_kategori['badge_text'] ?? $aspek_kategori['nama']) . "</strong>
                            </span>
                        </div>
                    </div>
                </div>";
        
        // Detail elemen
        echo "<h6 class='mb-2'>Elemen:</h6>";
        foreach ($aspek['elemen'] as $elemen) {
            echo "<div class='card mb-2 border-secondary'>
                <div class='card-body p-2'>
                    <div class='d-flex justify-content-between align-items-center'>
                        <div>
                            <strong>" . htmlspecialchars($elemen['nama']) . "</strong>
                            <small class='text-muted d-block'>Bobot: " . ($elemen['bobot'] * 100) . "%</small>
                        </div>
                        <div class='text-end'>
                            <span class='badge bg-primary'>" . number_format($elemen['skor'], 2) . "%</span><br>
                            <small class='text-muted'>Nilai: {$elemen['total_nilai']} / " . ($elemen['total_kriteria'] * 2) . "</small>
                        </div>
                    </div>
                </div>
            </div>";
        }
        
        echo "</div></div>";
    }
    
    // Statistik
    echo "<div class='card mt-4'>
        <div class='card-header bg-info text-white'>
            <h5 class='mb-0'><i class='fas fa-chart-bar me-2'></i>Statistik Penilaian</h5>
        </div>
        <div class='card-body'>
            <div class='row'>
                <div class='col-md-4'>
                    <p><strong>Total Kriteria:</strong> " . $total_kriteria . "</p>
                    <p><strong>Total Nilai:</strong> " . $total_nilai . "</p>
                </div>
                <div class='col-md-4'>
                    <p><strong>Nilai Maksimal:</strong> " . ($total_kriteria * 2) . "</p>
                    <p><strong>Persentase:</strong> " . number_format(($total_nilai / ($total_kriteria * 2)) * 100, 2) . "%</p>
                </div>
                <div class='col-md-4'>
                    <p><strong>Range Kategori EMAS:</strong> 86% - 100%</p>
                    <p><strong>Status:</strong> <span class='badge badge-emas'>" . htmlspecialchars($kategori['badge_text']) . "</span></p>
                </div>
            </div>
        </div>
    </div>";
    
    echo "</div></div>";
    
    // Footer
    echo "<div class='card mt-4'>
        <div class='card-body text-center'>
            <div class='alert alert-info'>
                <i class='fas fa-info-circle me-2'></i>
                <strong>Catatan:</strong> Simulasi ini menggunakan distribusi nilai khusus untuk kategori EMAS: 90% nilai 2 (baik), 8% nilai 1 (kurang), 2% nilai 0 (tidak ada).
            </div>
            <div class='d-flex justify-content-center gap-2 flex-wrap'>
                <a href='" . BASE_URL . "pages/penilaian_detail.php?id=$penilaian_id' class='btn btn-primary'><i class='fas fa-eye me-2'></i>Lihat Detail Penilaian</a>
                <a href='" . BASE_URL . "pages/laporan_generate.php?penilaian_id=$penilaian_id' class='btn btn-success' target='_blank'><i class='fas fa-download me-2'></i>Download PDF</a>
                <a href='" . BASE_URL . "simulasi_penilaian_emas.php' class='btn btn-warning'><i class='fas fa-redo me-2'></i>Simulasi Lagi</a>
            </div>
        </div>
    </div>";

} catch (Exception $e) {
    echo "<div class='alert alert-danger'>
        <i class='fas fa-exclamation-triangle me-2'></i>
        <strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "
    </div>";
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}

echo "</div></div>
</body>
</html>";
?>

