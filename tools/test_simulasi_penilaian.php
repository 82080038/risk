<?php
/**
 * TEST SIMULASI PENILAIAN (Simple Version)
 * Menampilkan hasil simulasi penilaian secara sederhana
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/functions.php';

$conn = getDBConnection();

echo "=== SIMULASI PENILAIAN ===\n\n";

try {
    // Ambil objek wisata pertama
    $objek = $conn->query("SELECT * FROM objek_wisata LIMIT 1")->fetch_assoc();
    if (!$objek) {
        throw new Exception("Tidak ada objek wisata");
    }
    
    // Ambil user penilai pertama
    $user = $conn->query("SELECT * FROM users WHERE role = 'penilai' LIMIT 1")->fetch_assoc();
    if (!$user) {
        throw new Exception("Tidak ada user penilai");
    }
    
    echo "Objek Wisata: " . $objek['nama'] . "\n";
    echo "Penilai: " . $user['nama'] . "\n";
    echo "Tanggal: " . date('Y-m-d') . "\n\n";
    
    // Buat penilaian
    $stmt = $conn->prepare("
        INSERT INTO penilaian (objek_wisata_id, user_id, tanggal_penilaian, nama_penilai, pangkat_nrp, status)
        VALUES (?, ?, ?, ?, ?, 'draft')
    ");
    $tanggal = date('Y-m-d');
    $stmt->bind_param("iisss", $objek['id'], $user['id'], $tanggal, $user['nama'], $user['pangkat_nrp']);
    $stmt->execute();
    $penilaian_id = $conn->insert_id;
    $stmt->close();
    
    echo "Penilaian ID: $penilaian_id\n\n";
    
    // Ambil semua aspek
    $aspek_result = $conn->query("SELECT * FROM aspek ORDER BY urutan");
    $aspek_list = [];
    $total_kriteria = 0;
    $total_nilai = 0;
    
    while ($aspek = $aspek_result->fetch_assoc()) {
        $elemen_result = $conn->query("SELECT * FROM elemen WHERE aspek_id = {$aspek['id']} ORDER BY urutan");
        $elemen_list = [];
        
        while ($elemen = $elemen_result->fetch_assoc()) {
            $kriteria_result = $conn->query("SELECT * FROM kriteria WHERE elemen_id = {$elemen['id']} ORDER BY urutan");
            $kriteria_list = [];
            $elemen_total_nilai = 0;
            $elemen_total_kriteria = 0;
            
            while ($kriteria = $kriteria_result->fetch_assoc()) {
                // Simulasi nilai
                $rand = rand(1, 100);
                if ($rand <= 15) {
                    $nilai = 0;
                    $temuan = "Temuan simulasi";
                    $rekomendasi = "Rekomendasi simulasi";
                } elseif ($rand <= 40) {
                    $nilai = 1;
                    $temuan = "Temuan simulasi";
                    $rekomendasi = "Rekomendasi simulasi";
                } else {
                    $nilai = 2;
                    $temuan = "";
                    $rekomendasi = "";
                }
                
                // Simpan
                $detail_stmt = $conn->prepare("
                    INSERT INTO penilaian_detail (penilaian_id, kriteria_id, nilai, temuan, rekomendasi)
                    VALUES (?, ?, ?, ?, ?)
                ");
                $detail_stmt->bind_param("iiiss", $penilaian_id, $kriteria['id'], $nilai, $temuan, $rekomendasi);
                $detail_stmt->execute();
                $detail_stmt->close();
                
                $elemen_total_nilai += $nilai;
                $elemen_total_kriteria++;
                $total_nilai += $nilai;
                $total_kriteria++;
            }
            
            // Hitung skor elemen
            $elemen['skor'] = $elemen_total_kriteria > 0 
                ? ($elemen_total_nilai / ($elemen_total_kriteria * 2)) * 100 
                : 0;
            $elemen['total_nilai'] = $elemen_total_nilai;
            $elemen['total_kriteria'] = $elemen_total_kriteria;
            $elemen_list[] = $elemen;
        }
        
        // Hitung skor aspek
        $aspek_skor = 0;
        foreach ($elemen_list as $elemen) {
            $aspek_skor += $elemen['skor'] * $elemen['bobot'];
        }
        $aspek['skor'] = $aspek_skor;
        $aspek['kontribusi'] = $aspek_skor * $aspek['bobot'];
        $aspek['elemen'] = $elemen_list;
        $aspek_list[] = $aspek;
    }
    
    // Hitung skor final
    $skor_final = 0;
    foreach ($aspek_list as $aspek) {
        $skor_final += $aspek['kontribusi'];
    }
    
    $kategori = getKategoriWithClass($skor_final);
    
    // Update penilaian
    $update_stmt = $conn->prepare("UPDATE penilaian SET skor_final = ?, kategori = ?, status = 'selesai' WHERE id = ?");
    $update_stmt->bind_param("dsi", $skor_final, $kategori['nama'], $penilaian_id);
    $update_stmt->execute();
    $update_stmt->close();
    
    // Tampilkan hasil
    echo "=== HASIL PENILAIAN ===\n\n";
    echo "Skor Final: " . number_format($skor_final, 2) . "%\n";
    echo "Kategori: " . $kategori['icon'] . " " . $kategori['nama'] . "\n";
    echo "Total Kriteria: $total_kriteria\n";
    echo "Total Nilai: $total_nilai\n";
    echo "Nilai Maksimal: " . ($total_kriteria * 2) . "\n\n";
    
    echo "=== DETAIL PER ASPEK ===\n\n";
    foreach ($aspek_list as $aspek) {
        echo "Aspek: " . $aspek['nama'] . " (Bobot: " . ($aspek['bobot'] * 100) . "%)\n";
        echo "  Skor Aspek: " . number_format($aspek['skor'], 2) . "%\n";
        echo "  Kontribusi: " . number_format($aspek['kontribusi'], 2) . "%\n";
        
        foreach ($aspek['elemen'] as $elemen) {
            echo "  - Elemen: " . $elemen['nama'] . " (Bobot: " . ($elemen['bobot'] * 100) . "%)\n";
            echo "    Skor: " . number_format($elemen['skor'], 2) . "%\n";
            echo "    Kriteria: " . $elemen['total_kriteria'] . " (Nilai: " . $elemen['total_nilai'] . ")\n";
            echo "    Perhitungan: ({$elemen['total_nilai']} / ({$elemen['total_kriteria']} × 2)) × 100 = " . number_format($elemen['skor'], 2) . "%\n";
        }
        echo "\n";
    }
    
    echo "=== PERHITUNGAN SKOR FINAL ===\n";
    foreach ($aspek_list as $aspek) {
        echo $aspek['nama'] . ": " . number_format($aspek['skor'], 2) . "% × " . ($aspek['bobot'] * 100) . "% = " . number_format($aspek['kontribusi'], 2) . "%\n";
    }
    echo "Total: " . number_format($skor_final, 2) . "%\n\n";
    
    echo "Penilaian ID $penilaian_id telah disimpan dengan status 'selesai'.\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

$conn->close();
?>

