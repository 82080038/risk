<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penilaian - <?php echo htmlspecialchars($penilaian['objek_nama']); ?></title>
    <style>
        @page {
            margin: 2cm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.4;
        }
        .kop-surat {
            position: absolute;
            top: 1.5cm;
            left: 2cm;
            font-weight: bold;
            font-size: 12pt;
        }
        .kop-line {
            margin: 0;
            padding: 0;
            line-height: 1.4;
        }
        .garis-pemisah {
            border-top: 1px solid #000;
            margin-top: 0.5cm;
            margin-bottom: 1cm;
        }
        h1 {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            margin-top: 3cm;
            margin-bottom: 1cm;
        }
        .info-box {
            border: 1px solid #000;
            padding: 0.5cm;
            margin: 0.5cm 0;
        }
        .info-row {
            margin: 0.3cm 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0.5cm 0;
            font-size: 9pt;
        }
        table th, table td {
            border: 1px solid #000;
            padding: 0.3cm;
            text-align: left;
        }
        table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .summary-box {
            border: 2px solid #000;
            padding: 0.5cm;
            margin: 1cm 0;
            background-color: #f9f9f9;
        }
        .ttd-section {
            margin-top: 2cm;
            display: flex;
            justify-content: space-between;
        }
        .ttd-box {
            width: 45%;
            text-align: center;
        }
        .ttd-space {
            height: 3cm;
        }
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Kop Surat -->
    <div class="kop-surat">
        <div class="kop-line">KEPOLISIAN NEGARA REPUBLIK INDONESIA</div>
        <div class="kop-line">DAERAH SUMATERA UTARA</div>
        <div class="kop-line">RESOR SAMOSIR</div>
        <div class="kop-line" style="margin-top: 0.3cm;">RISK ASSESMENT OBJEK WISATA <?php echo $tahun; ?></div>
        <div class="kop-line">WILKUM POLRES SAMOSIR</div>
        <div class="garis-pemisah"></div>
    </div>
    
    <!-- Judul -->
    <h1>LAPORAN PENILAIAN RISIKO OBJEK WISATA</h1>
    
    <!-- Info Objek Wisata -->
    <div class="info-box">
        <div class="info-row"><strong>Nama Objek Wisata:</strong> <?php echo htmlspecialchars($penilaian['objek_nama']); ?></div>
        <div class="info-row"><strong>Alamat:</strong> <?php echo htmlspecialchars($penilaian['objek_alamat']); ?></div>
        <div class="info-row"><strong>Tanggal Penilaian:</strong> <?php echo formatTanggalIndonesia($penilaian['tanggal_penilaian']); ?></div>
        <div class="info-row"><strong>Penilai:</strong> <?php echo htmlspecialchars($penilaian['penilai_nama']); ?></div>
        <div class="info-row"><strong>Pangkat/NRP:</strong> <?php echo htmlspecialchars($penilaian['penilai_pangkat_nrp']); ?></div>
    </div>
    
    <!-- Detail Penilaian Per Aspek -->
    <?php foreach ($aspek_list as $aspek): ?>
    <div style="page-break-inside: avoid; margin: 1cm 0;">
        <h2 style="font-size: 12pt; font-weight: bold; background-color: #e0e0e0; padding: 0.3cm;">
            ASPEK <?php echo $aspek['urutan']; ?>: <?php echo htmlspecialchars($aspek['nama']); ?>
            (Bobot: <?php echo ($aspek['bobot'] * 100); ?>%)
        </h2>
        
        <?php foreach ($aspek['elemen'] as $elemen): ?>
        <div style="margin: 0.5cm 0;">
            <h3 style="font-size: 11pt; font-weight: bold;">
                <?php echo htmlspecialchars($elemen['nama']); ?>
                (Bobot Elemen: <?php echo ($elemen['bobot'] * 100); ?>%)
            </h3>
            
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 45%;">Kriteria</th>
                        <th style="width: 10%;">Nilai</th>
                        <th style="width: 20%;">Temuan</th>
                        <th style="width: 20%;">Rekomendasi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($elemen['kriteria'] as $kriteria): ?>
                    <tr>
                        <td><?php echo $kriteria['nomor']; ?></td>
                        <td><?php echo htmlspecialchars($kriteria['deskripsi']); ?></td>
                        <td style="text-align: center;"><?php echo $kriteria['nilai'] !== null ? $kriteria['nilai'] : '-'; ?></td>
                        <td>
                            <?php echo htmlspecialchars($kriteria['temuan'] ?? '-'); ?>
                            <?php if (!empty($kriteria['referensi'])): ?>
                                <br><small style="color: #0066cc;">[<?php echo count($kriteria['referensi']); ?> file referensi]</small>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($kriteria['rekomendasi'] ?? '-'); ?></td>
                    </tr>
                    <?php if (!empty($kriteria['referensi'])): ?>
                    <tr style="background-color: #f9f9f9;">
                        <td colspan="5" style="padding-left: 1cm;">
                            <strong>Referensi Dokumen:</strong>
                            <?php foreach ($kriteria['referensi'] as $ref): ?>
                                <br>- <?php echo htmlspecialchars($ref['nama_file']); ?> (<?php echo formatFileSize($ref['ukuran_file'] ?? 0); ?>)
                            <?php endforeach; ?>
                        </td>
                    </tr>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr style="background-color: #f0f0f0; font-weight: bold;">
                        <td colspan="2" style="text-align: right;">Skor Elemen:</td>
                        <td colspan="3" style="text-align: center;"><?php echo number_format($elemen['skor'], 2); ?>%</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <?php endforeach; ?>
        
        <div style="background-color: #e8f4f8; padding: 0.3cm; margin: 0.5cm 0;">
            <strong>Skor Aspek:</strong> <?php echo number_format($aspek['skor'], 2); ?>% | 
            <strong>Kontribusi ke Skor Final:</strong> <?php echo number_format($aspek['kontribusi'], 2); ?>%
        </div>
    </div>
    <?php endforeach; ?>
    
    <!-- Ringkasan Skor -->
    <div class="summary-box">
        <h2 style="font-size: 12pt; font-weight: bold; margin-top: 0;">RINGKASAN SKOR</h2>
        <table>
            <thead>
                <tr>
                    <th>Aspek</th>
                    <th>Bobot</th>
                    <th>Skor Aspek</th>
                    <th>Kontribusi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total_kontribusi = 0;
                foreach ($aspek_list as $aspek): 
                    $total_kontribusi += $aspek['kontribusi'];
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($aspek['nama']); ?></td>
                    <td style="text-align: center;"><?php echo ($aspek['bobot'] * 100); ?>%</td>
                    <td style="text-align: center;"><?php echo number_format($aspek['skor'], 2); ?>%</td>
                    <td style="text-align: center;"><?php echo number_format($aspek['kontribusi'], 2); ?>%</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr style="background-color: #d0e8f0; font-weight: bold; font-size: 12pt;">
                    <td colspan="3" style="text-align: right;">SKOR FINAL:</td>
                    <td style="text-align: center;"><?php echo number_format($penilaian['skor_final'], 2); ?>%</td>
                </tr>
                <tr style="background-color: #d0e8f0; font-weight: bold;">
                    <td colspan="4" style="text-align: center;">
                        KATEGORI: <?php echo htmlspecialchars($penilaian['kategori'] ?? '-'); ?>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    
    <!-- Tanda Tangan -->
    <div class="ttd-section">
        <div class="ttd-box">
            <p>Mengetahui,</p>
            <p>Kasat Pamobvit</p>
            <p>Polres Samosir</p>
            <div class="ttd-space"></div>
            <p><strong><?php echo htmlspecialchars($kasat['nama'] ?? '-'); ?></strong></p>
            <p><?php echo htmlspecialchars($kasat['pangkat_nrp'] ?? '-'); ?></p>
        </div>
        
        <div class="ttd-box">
            <p>Penilai,</p>
            <div class="ttd-space"></div>
            <p><strong><?php echo htmlspecialchars($penilaian['penilai_nama']); ?></strong></p>
            <p><?php echo htmlspecialchars($penilaian['penilai_pangkat_nrp']); ?></p>
        </div>
    </div>
    
    <div style="margin-top: 1cm; text-align: center; font-size: 9pt;">
        <p>Dibuat pada: <?php echo formatTanggalIndonesia(date('Y-m-d')); ?></p>
    </div>
</body>
</html>

