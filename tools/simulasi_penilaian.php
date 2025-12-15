<?php
/**
 * SIMULASI PENILAIAN
 * Risk Assessment Objek Wisata
 * Script untuk melakukan simulasi penilaian dan melihat hasil perhitungan
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';

// Start session untuk simulasi
session_start();

$conn = getDBConnection();

echo "<!DOCTYPE html>
<html lang='id'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Simulasi Penilaian</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css'>
    <style>
        body { background: #f5f5f5; padding: 20px; }
        .card { margin-bottom: 20px; }
        .score-box { text-align: center; padding: 15px; border-radius: 8px; margin: 10px 0; }
        .score-excellent { background: #d4edda; border: 2px solid #28a745; }
        .score-good { background: #d1ecf1; border: 2px solid #17a2b8; }
        .score-fair { background: #fff3cd; border: 2px solid #ffc107; }
        .score-poor { background: #f8d7da; border: 2px solid #dc3545; }
        .calculation { background: #f8f9fa; padding: 10px; border-radius: 5px; margin: 5px 0; font-family: monospace; }
        table { font-size: 0.9em; }
    </style>
</head>
<body>
<div class='container-fluid'>
    <div class='row'>
        <div class='col-12'>
            <h1 class='mb-4'><i class='fas fa-calculator me-2'></i>Simulasi Penilaian</h1>";

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
        <div class='card-header bg-primary text-white'>
            <h5 class='mb-0'><i class='fas fa-info-circle me-2'></i>Data Simulasi</h5>
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
                // Simulasi: Berikan nilai random (0, 1, atau 2)
                // Untuk simulasi yang lebih realistis, kita akan memberikan distribusi:
                // 60% nilai 2 (baik), 25% nilai 1 (kurang), 15% nilai 0 (tidak ada)
                $rand = rand(1, 100);
                if ($rand <= 15) {
                    $nilai = 0;
                    $temuan = "Temuan simulasi untuk kriteria " . $kriteria['nomor'];
                    $rekomendasi = "Rekomendasi simulasi untuk kriteria " . $kriteria['nomor'];
                } elseif ($rand <= 40) {
                    $nilai = 1;
                    $temuan = "Temuan simulasi untuk kriteria " . $kriteria['nomor'];
                    $rekomendasi = "Rekomendasi simulasi untuk kriteria " . $kriteria['nomor'];
                } else {
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
            // Skor Elemen = (Jumlah nilai kriteria / (Jumlah kriteria × 2)) × 100
            $elemen['skor'] = $elemen_total_kriteria > 0 
                ? ($elemen_total_nilai / ($elemen_total_kriteria * 2)) * 100 
                : 0;
            $elemen['kriteria'] = $kriteria_list;
            $elemen['total_nilai'] = $elemen_total_nilai;
            $elemen['total_kriteria'] = $elemen_total_kriteria;
            $elemen_list[] = $elemen;
            
            $kriteria_stmt->close();
        }
        
        // Hitung skor aspek
        // Skor Aspek = Σ(Skor Elemen × Bobot Elemen)
        $aspek_skor = 0;
        foreach ($elemen_list as $elemen) {
            $aspek_skor += $elemen['skor'] * $elemen['bobot'];
        }
        $aspek['skor'] = $aspek_skor;
        $aspek['kontribusi'] = $aspek_skor * $aspek['bobot'];
        $aspek['elemen'] = $elemen_list;
        $aspek_list[] = $aspek;
        
        $elemen_stmt->close();
    }
    
    // 5. Hitung skor final
    // Skor Final = Σ(Skor Aspek × Bobot Aspek) = Σ(Kontribusi Aspek)
    $skor_final = 0;
    foreach ($aspek_list as $aspek) {
        $skor_final += $aspek['kontribusi'];
    }
    
    // 6. Tentukan kategori
    $kategori = getKategoriWithClass($skor_final);
    
    // 7. Update penilaian dengan skor final
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
        <div class='card-header bg-success text-white'>
            <h5 class='mb-0'><i class='fas fa-check-circle me-2'></i>Hasil Simulasi Penilaian</h5>
        </div>
        <div class='card-body'>";
    
    // Ringkasan
    $score_class = '';
    if ($skor_final >= 86) $score_class = 'score-excellent';
    elseif ($skor_final >= 71) $score_class = 'score-good';
    elseif ($skor_final >= 56) $score_class = 'score-fair';
    else $score_class = 'score-poor';
    
    echo "<div class='row mb-4'>
        <div class='col-md-4'>
            <div class='score-box $score_class'>
                <h6>Skor Final</h6>
                <h2>" . number_format($skor_final, 2) . "%</h2>
            </div>
        </div>
        <div class='col-md-4'>
            <div class='score-box $score_class'>
                <h6>Kategori</h6>
                <h3>" . $kategori['icon'] . " " . htmlspecialchars($kategori['nama']) . "</h3>
            </div>
        </div>
        <div class='col-md-4'>
            <div class='score-box'>
                <h6>Total Kriteria</h6>
                <h3>$total_kriteria</h3>
                <small>Total Nilai: $total_nilai</small>
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
                            <strong>Kategori:</strong> " . $aspek_kategori['icon'] . " " . htmlspecialchars($aspek_kategori['nama']) . "
                        </div>
                    </div>
                </div>";
        
        // Detail elemen
        echo "<h6 class='mb-2'>Elemen:</h6>";
        foreach ($aspek['elemen'] as $elemen) {
            $elemen_kategori = getKategoriWithClass($elemen['skor']);
            echo "<div class='card mb-2 border-start border-4 border-info'>
                <div class='card-body p-2'>
                    <div class='row'>
                        <div class='col-md-6'>
                            <strong>" . htmlspecialchars($elemen['nama']) . "</strong>
                            <span class='badge bg-secondary ms-2'>Bobot: " . ($elemen['bobot'] * 100) . "%</span>
                        </div>
                        <div class='col-md-3'>
                            <small>Skor: <strong>" . number_format($elemen['skor'], 2) . "%</strong></small>
                        </div>
                        <div class='col-md-3'>
                            <small>Kriteria: " . $elemen['total_kriteria'] . " (Nilai: " . $elemen['total_nilai'] . ")</small>
                        </div>
                    </div>
                    <div class='calculation mt-2'>
                        <small>
                            Perhitungan: ($elemen[total_nilai] / ($elemen[total_kriteria] × 2)) × 100 = " . number_format($elemen['skor'], 2) . "%
                        </small>
                    </div>
                </div>
            </div>";
        }
        
        echo "</div></div>";
    }
    
    // Perhitungan skor final
    echo "<div class='card mt-4'>
        <div class='card-header bg-info text-white'>
            <h5 class='mb-0'><i class='fas fa-calculator me-2'></i>Perhitungan Skor Final</h5>
        </div>
        <div class='card-body'>
            <div class='calculation'>
                <strong>Rumus:</strong> Skor Final = Σ(Skor Aspek × Bobot Aspek)<br><br>";
    
    foreach ($aspek_list as $aspek) {
        echo htmlspecialchars($aspek['nama']) . ": " . number_format($aspek['skor'], 2) . "% × " . ($aspek['bobot'] * 100) . "% = " . number_format($aspek['kontribusi'], 2) . "%<br>";
    }
    
    echo "<br><strong>Total: " . number_format($skor_final, 2) . "%</strong>
            </div>
        </div>
    </div>";
    
    // Statistik
    echo "<div class='card mt-4'>
        <div class='card-header bg-secondary text-white'>
            <h5 class='mb-0'><i class='fas fa-chart-bar me-2'></i>Statistik</h5>
        </div>
        <div class='card-body'>
            <div class='row'>
                <div class='col-md-3'>
                    <p><strong>Total Kriteria:</strong> $total_kriteria</p>
                </div>
                <div class='col-md-3'>
                    <p><strong>Total Nilai:</strong> $total_nilai</p>
                </div>
                <div class='col-md-3'>
                    <p><strong>Nilai Maksimal:</strong> " . ($total_kriteria * 2) . "</p>
                </div>
                <div class='col-md-3'>
                    <p><strong>Persentase:</strong> " . number_format(($total_nilai / ($total_kriteria * 2)) * 100, 2) . "%</p>
                </div>
            </div>
        </div>
    </div>";
    
    echo "</div></div>";
    
    echo "<div class='alert alert-info mt-4'>
        <i class='fas fa-info-circle me-2'></i>
        <strong>Catatan:</strong> Simulasi ini menggunakan distribusi nilai random: 60% nilai 2 (baik), 25% nilai 1 (kurang), 15% nilai 0 (tidak ada).
        <br>Penilaian ID: <strong>$penilaian_id</strong> telah disimpan ke database dengan status 'selesai'.
    </div>";
    
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>
        <i class='fas fa-exclamation-triangle me-2'></i>
        <strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "
    </div>";
}

$conn->close();

echo "</div>
</div>
<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js'></script>
</body>
</html>";
?>

