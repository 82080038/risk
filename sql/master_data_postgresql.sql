-- =====================================================
-- MASTER DATA - PostgreSQL Version
-- Risk Assessment Objek Wisata
-- Data Aspek, Elemen, dan Kriteria
-- =====================================================

-- =====================================================
-- DATA ASPEK (6 Aspek)
-- =====================================================
INSERT INTO aspek (kode, nama, bobot, urutan) VALUES
('ASP1', 'INFRASTRUKTUR', 0.20, 1),
('ASP2', 'KEAMANAN', 0.20, 2),
('ASP3', 'KESELAMATAN', 0.25, 3),
('ASP4', 'KESEHATAN', 0.10, 4),
('ASP5', 'SISTEM PENGAMANAN', 0.15, 5),
('ASP6', 'INFORMASI', 0.10, 6)
ON CONFLICT (kode) DO NOTHING;

-- =====================================================
-- DATA ELEMEN - ASPEK 1: INFRASTRUKTUR
-- =====================================================
INSERT INTO elemen (aspek_id, kode, nama, bobot, urutan) VALUES
(1, 'E1A', 'KELAIKAN GEDUNG/VENUE', 0.12, 1),
(1, 'E1B', 'KELENGKAPAN HOTEL', 0.08, 2);

-- =====================================================
-- DATA KRITERIA - ELEMEN 1A: KELAIKAN GEDUNG/VENUE
-- =====================================================
INSERT INTO kriteria (elemen_id, nomor, deskripsi, urutan) VALUES
(1, 1, 'Memiliki parameter pembatas dengan lingkungan sekitar', 1),
(1, 2, 'Memiliki pagar pembatas dan Gerbang Masuk', 2),
(1, 3, 'Pos Penjagaan Utama/Depan', 3),
(1, 4, 'Pos Penjagaan Belakang/Samping', 4),
(1, 5, 'Pos bagian penurunan barang/loading dock', 5),
(1, 6, 'Pos Bagian Dalam Objek', 6),
(1, 7, 'Menara Pantau', 7),
(1, 8, 'Ruang Kesehatan', 8),
(1, 9, 'Ruang Monitoring Center/Kontrol', 9),
(1, 10, 'Rambu/Tanda-tanda peringatan', 10),
(1, 11, 'sistem Pencahayaan', 11),
(1, 12, 'Ruang Power Supply', 12),
(1, 13, 'Fasilitas bagi Difabel', 13),
(1, 14, 'Jalur keluar masuk dan evakuasi', 14),
(1, 15, 'Area parkir', 15),
(1, 16, 'Menara pantau area parkir', 16),
(1, 17, 'Jalur kendaraan', 17),
(1, 18, 'Jalur pedestrian/assembling point', 18),
(1, 19, 'Jalur dan fasilitas disabilitas', 19),
(1, 20, 'Toilet/restroom pada ruang publik', 20),
(1, 21, 'tempat penitipan barang', 21),
(1, 22, 'Ruang Media/monitoring', 22),
(1, 23, 'Area Foodcort', 23),
(1, 24, 'Jalur evakusi VIP/VVIP (kendaraan darat, perairan dan udara)', 24);

-- =====================================================
-- DATA KRITERIA - ELEMEN 1B: KELENGKAPAN HOTEL
-- =====================================================
INSERT INTO kriteria (elemen_id, nomor, deskripsi, urutan) VALUES
(2, 1, 'Lampu lapangan/venue', 1),
(2, 2, 'sistem CCTV', 2),
(2, 3, 'Sarana komunikasi (HP, Handy Talky, videotron, intercome, Public address)', 3),
(2, 4, 'Alarm tanda bahaya', 4),
(2, 5, 'Tempat penampungan dan pengolahan limbah air kotor', 5),
(2, 6, 'Fire system (hydrant system dan Apar)', 6),
(2, 7, 'Kendaraan evakuasi medis', 7),
(2, 8, 'Kendaraan Patroli', 8),
(2, 9, 'Genset/Power Supply', 9),
(2, 10, 'Perambuan HSSE (Keamanan dan Keselamatan)', 10);

-- =====================================================
-- DATA ELEMEN - ASPEK 2: KEAMANAN
-- =====================================================
INSERT INTO elemen (aspek_id, kode, nama, bobot, urutan) VALUES
(2, 'E2A', 'PROSEDUR PELAKSANA PENGAMANAN', 0.10, 1),
(2, 'E2B', 'PELAYANAN KEAMANAN', 0.10, 2);

-- =====================================================
-- DATA KRITERIA - ELEMEN 2A: PROSEDUR PELAKSANA PENGAMANAN
-- =====================================================
INSERT INTO kriteria (elemen_id, nomor, deskripsi, urutan) VALUES
(3, 1, 'Terdapat jadwal petugas pengamanan sesuai ketentuan yang berlaku', 1),
(3, 2, 'memiliki struktur organisasi petugas pengamanan', 2),
(3, 3, 'Terdapat Job Discription bagi seluruh komponen pengamanan', 3),
(3, 4, 'pengamanan dilakukan dibagian depan (loby), bagian belakang (loadingdok) dan pintu masuk karyawan', 4),
(3, 5, 'pengamanan dilaksanakan selama 24 jam', 5),
(3, 6, 'roling/pergantian bergilir petugas patroli yang disesuaikan dengan luas area dan potensi ancaman', 6),
(3, 7, 'sistem pelaporan melalui pencatatan mutasi kegiatan dan peristiwa menonjol', 7),
(3, 8, 'prosedur terhadap pemeriksaan kendaraan dan orang yang masuk dan keluar area', 8),
(3, 9, 'Terdapat SOP terhadap manajemen pelaksana pengamanan', 9),
(3, 10, 'Terdapat SOP penanggulangan manajemen crowded dan darurat', 10),
(3, 11, 'Terdapat SOP penanggulangan terorisme dan sabotas', 11),
(3, 12, 'terdapat SOP pengamanan bagi karyawan selain petugas pengamanan', 12);

-- =====================================================
-- DATA KRITERIA - ELEMEN 2B: PELAYANAN KEAMANAN
-- =====================================================
INSERT INTO kriteria (elemen_id, nomor, deskripsi, urutan) VALUES
(4, 1, 'menyediakan peralatan keamanan berupa perlengkapan perorangan', 1),
(4, 2, 'menyediakan peralatan pemeriksaan kendaraan dan badan yang masuk dan keluar area', 2),
(4, 3, 'menyediakan peralatan sarana khusus berupa alat scaning atau Satwa Pelacak yang bersertifikat sesuai standar dan ketentuan yang berlaku', 3),
(4, 4, 'Pelayanan Petugas Keamanan bersertifikat sesuai standar kualifikasinya', 4),
(4, 5, 'Kelayakan dan perawatan berkala terhadap sarana/peralatan pengamanan', 5),
(4, 6, 'menyediakan sarana pelaporan bagi pengunjung/karyawan', 6),
(4, 7, 'Penempatan peralatan petugas keamanan pada objek sesaui dengan kerawanan', 7),
(4, 8, 'terdapat SOP penggunaan peralatan keamanan', 8),
(4, 9, 'Jalur komunikasi terhadap Satuan Kepolsiian terdekat', 9),
(4, 10, 'Terdapat SOP kecepatan merespon dalam pelayanan keamanan', 10);

-- =====================================================
-- DATA ELEMEN - ASPEK 3: KESELAMATAN
-- =====================================================
INSERT INTO elemen (aspek_id, kode, nama, bobot, urutan) VALUES
(3, 'E3A', 'PELAYANAN SISTEM KESELAMATAN', 0.15, 1),
(3, 'E3B', 'PETUGAS KESELAMATAN', 0.10, 2);

-- =====================================================
-- DATA KRITERIA - ELEMEN 3A: PELAYANAN SISTEM KESELAMATAN
-- =====================================================
INSERT INTO kriteria (elemen_id, nomor, deskripsi, urutan) VALUES
(5, 1, 'Kelayakan dan perawatan berkala terhadap sarana permainan/wahana/venue', 1),
(5, 2, 'Penempatan wahana/venue sesuai dengan standar keselamatan', 2),
(5, 3, 'terdapat sarana pendukung terhadap wahana/venue utama sebagai bagian dari sistem keselamatan bagi pengunjung', 3),
(5, 4, 'terapat batas yang aman antara venue/wahana dengan petugas/pengunjung', 4),
(5, 5, 'menyediakan peralatan keselamatan bagi pengunjung sesuai objek dan kapasitas pengunjung', 5),
(5, 6, 'Peralatan/Sarana Prasarana petugas keselamatan sesuai objek', 6),
(5, 7, 'terdapat pos pantau dengan posisi strategis bagi petugas keselamatan', 7),
(5, 8, 'SOP Pembatasan pengunjung sesaui kapsitas wahana/venue', 8),
(5, 9, 'Perawatan berkala dan penyimpanan, serta penempatan peralatan keselamatan sesuai dengan potensi ancaman', 9),
(5, 10, 'dokumen uji kelayakan wahana/venue dari instansi yang berhak', 10),
(5, 11, 'Batas/Rambu peringatan zona berbahaya/terlarang yang mudah terlihat dan mudah dipahami oleh pengunjung/petugas/karyawan/staf', 11),
(5, 12, 'petunjuk arah dan denah lokasi wahana/venue', 12),
(5, 13, 'aksesibilitas bagi penyandang difabel', 13),
(5, 14, 'Sarana peringatan/informasi tentang situasi bahaya/kedaruratan yang mudah diakses/direspon oleh pengunjung', 14),
(5, 15, 'SOP/KONSIGNES bagi staf dan pengujung Objek/Venue bilamana terjadi kedaruratan', 15);

-- =====================================================
-- DATA KRITERIA - ELEMEN 3B: PETUGAS KESELAMATAN
-- =====================================================
INSERT INTO kriteria (elemen_id, nomor, deskripsi, urutan) VALUES
(6, 1, 'Pelayanan Petugas Keselamatan memiliki kualifikasi sesuai dengan jenis/lokasi venue/wahana yang disediakan (Keselamatan air, Keselamatan vertikal/Ketinggian/Kedalaman, Pawang binatang, Teknisi permainan tertentu, dll) yang dibuktikan dengan sertifikat/keterangan telah mengikuti pelatihan', 1),
(6, 2, 'kesesuaian jumlah petugas keselamatan dengan area kawasan', 2),
(6, 3, 'memiliki SOP tentang penyelamatan dan pertolongan pertama', 3),
(6, 4, 'memiliki SOP tentang jaring komando dan permintaan bantuan', 4),
(6, 5, 'Prosedur evakuasi dan tanggap darurat yang jelas dan dapat diakses oleh pengunjung serta staf', 5),
(6, 6, 'Simulasi/latihan penanganan kedaruratan secara terpadu', 6),
(6, 7, 'SOP/KONSIGNES bagi pengunjung Objek/Venue bilamana terjadi kedaruratan', 7),
(6, 8, 'dilakukan patroli dan monitoring SOP/Konsignes bagi staf dan pengunjung untuk mematuhi ketentuan keselamatan dan kedaruratan', 8),
(6, 9, 'pendokumentasian peristiwa kecelakaan dan gangguan keselamatan yang pernah terjadi', 9),
(6, 10, 'dokumen analisa dan evaluasi hasil penanganan kecelakaan dan upaya pencegahannya', 10);

-- =====================================================
-- DATA ELEMEN - ASPEK 4: KESEHATAN
-- =====================================================
INSERT INTO elemen (aspek_id, kode, nama, bobot, urutan) VALUES
(4, 'E4A', 'PROSEDUR PETUGAS MEDIS', 0.04, 1),
(4, 'E4B', 'PELAYANAN KESEHATAN', 0.03, 2),
(4, 'E4C', 'PROSEDUR KESEHATAN', 0.03, 3);

-- =====================================================
-- DATA KRITERIA - ELEMEN 4A: PROSEDUR PETUGAS MEDIS
-- =====================================================
INSERT INTO kriteria (elemen_id, nomor, deskripsi, urutan) VALUES
(7, 1, 'Terdapat SOP manajemen petugas medis terhadap penanganan Staf dan Pengunjung', 1),
(7, 2, 'Terdapat SOP manajemen petugas medis terhadap penanganan Staf dan Pengunjung saat terjadi kontijensi/kedaruratan', 2),
(7, 3, 'Terdapat SOP manajemen petugas medis dalam penanganan VIP/VVIP', 3),
(7, 4, 'Terdapat prosedur disinfectan dan fogging pada benda-benda dan tempat-tempat yang berpotensi menjadi sarang penyakit', 4),
(7, 5, 'pelatihan pertolongan pertama terhadap gangguan kesehatan oleh petugas kesehatan dan staf', 5),
(7, 6, 'Rencana tindakan pertama hingga evakuasi dan RS rujukan terhadap staf dan pengujung', 6);

-- =====================================================
-- DATA KRITERIA - ELEMEN 4B: PELAYANAN KESEHATAN
-- =====================================================
INSERT INTO kriteria (elemen_id, nomor, deskripsi, urutan) VALUES
(8, 1, 'Kelayakan dan perawatan berkala terhadap sarana kesehatan yang disiapkan pengelola objek', 1),
(8, 2, 'Pelayanan Petugas Kesehatan sesuai standar/bersertifikat sesuai kualifikasinya', 2),
(8, 3, 'menyediakan peralatan kesehatan bagi staf dan pengunjung', 3),
(8, 4, 'terdapat Peralatan petugas kesehatan yang sesuai dengan potensi gangguan kesehatan pada pengunjung', 4);

-- =====================================================
-- DATA KRITERIA - ELEMEN 4C: PROSEDUR KESEHATAN
-- =====================================================
INSERT INTO kriteria (elemen_id, nomor, deskripsi, urutan) VALUES
(9, 1, 'Miliki saluran pembuangan/sanitari sesuai dengan stadar kesehatan', 1),
(9, 2, 'Memiliki Sistem pembuangan Sampah dengan benar dan area pembuangan yang dijaga kebersihannya untuk mencegah timbulnya penyakit', 2),
(9, 3, 'Terdapat pengolahan limbah dapur dan pengolahan air kotor', 3),
(9, 4, 'pengecekan kualitas air bersih secara berkala', 4),
(9, 5, 'prosedur pemisahan sampah organik, non organik dan sampah medis sesuai standar kesehatan', 5);

-- =====================================================
-- DATA ELEMEN - ASPEK 5: SISTEM PENGAMANAN
-- =====================================================
INSERT INTO elemen (aspek_id, kode, nama, bobot, urutan) VALUES
(5, 'E5A', 'ANALISA RISIKO KEAMANAN', 0.05, 1),
(5, 'E5B', 'PELAKSANAAN KEGIATAN PAM', 0.07, 2),
(5, 'E5C', 'PENGENDALIAN AKSES', 0.03, 3);

-- =====================================================
-- DATA KRITERIA - ELEMEN 5A: ANALISA RISIKO KEAMANAN
-- =====================================================
INSERT INTO kriteria (elemen_id, nomor, deskripsi, urutan) VALUES
(10, 1, 'Terdapat analisa data potensi gangguan', 1),
(10, 2, 'Terdapat analisa risiko', 2),
(10, 3, 'Terdapat penilaian risiko', 3),
(10, 4, 'Adanya pengendalian risiko melalui Rencana Pengamanan yang tersutruktur', 4);

-- =====================================================
-- DATA KRITERIA - ELEMEN 5B: PELAKSANAAN KEGIATAN PAM
-- =====================================================
INSERT INTO kriteria (elemen_id, nomor, deskripsi, urutan) VALUES
(11, 1, 'memiliki kebijakan keamanan yang jelas yang mencakup prosedur untuk menangani insiden keamanan, kehilangan barang, dan tindakan darurat', 1),
(11, 2, 'Seluruh staf mengetahui prosedur untuk melaporkan insiden keamanan atau kejadian mencurigakan kepada manajemen atau pihak berwenang', 2),
(11, 3, 'Melaksanakan sistem pengamanan melalui: adanya pola pengamanan (Pam) yang terdiri dari bentuk dan sifat Pam terbuka dan tertutup', 3),
(11, 4, 'Melaksanakan sistem pengamanan melalui: terdapat sarana dan area Pam, meliputi manusia dan fasilitas Pam', 4),
(11, 5, 'Melaksanakan sistem pengamanan melalui: terselenggaranya Komando dan pengendalian dalam dinamika Ops Sispam', 5),
(11, 6, 'Konfigurasi pengamanan: tersedianya komponen pengamanan (Pam Internal) yang terstruktur', 6),
(11, 7, 'Konfigurasi pengamanan: terdapat penetapan dan pembinaan area pengamanan (area bebas, area terbatas, area terlarang)', 7),
(11, 8, 'Konfigurasi pengamanan: adanya konsep umum Pam serta ketersediaan personel Pam dan Sarpas yang cukup memadai', 8),
(11, 9, 'Standar pelaksanaan pengamanan: memiliki kopetensi sesuai dengan objek pengamanan dan resiko tugas', 9),
(11, 10, 'Standar pelaksanaan pengamanan: memiliki komitmen dalam tugas', 10),
(11, 11, 'Standar pelaksanaan pengamanan: Memiliki sikap humanis beretika dan tegas', 11),
(11, 12, 'Standar pelaksanaan pengamanan: Sertifikasi sesuai bidang tugas/keahlian', 12),
(11, 13, 'Monitoring dan evaluasi: Breefing/arahan dari atasan/pimpinan', 13),
(11, 14, 'Monitoring dan evaluasi: melaksanakan konsolidasi', 14),
(11, 15, 'Monitoring dan evaluasi: membuat laporan tertulis dan evaluasi', 15),
(11, 16, 'Terdapat catatan peristiwa gangguan keamanan yang terdomkumentasi secara tertib dan lengkap', 16);

-- =====================================================
-- DATA KRITERIA - ELEMEN 5C: PENGENDALIAN AKSES
-- =====================================================
INSERT INTO kriteria (elemen_id, nomor, deskripsi, urutan) VALUES
(12, 1, 'Dilengkapi penggunaan kartu kunci elektronik yang dapat diaktifkan atau dinonaktifkan sesuai dengan status tamu dan area yang diizinkan', 1),
(12, 2, 'Terdapat sistem yang membatasi akses ke area tertentu hanya untuk staf yang berwenang, seperti ruang mesin, gudang, atau area layanan', 2),
(12, 3, 'monitoring dan pengawasan terhadap akses terbatas melalui penggunaan CCTV dan sistem alarm', 3),
(12, 4, 'terdapat SOP untuk mengendalikan dan melakukan tindakan pertama terhadap pelanggaran/situasi darurat sebagai akibat dari kesalahan penggunaan akses', 4);

-- =====================================================
-- DATA ELEMEN - ASPEK 6: INFORMASI
-- =====================================================
INSERT INTO elemen (aspek_id, kode, nama, bobot, urutan) VALUES
(6, 'E6A', 'INFORMASI', 0.10, 1);

-- =====================================================
-- DATA KRITERIA - ELEMEN 6A: INFORMASI
-- =====================================================
INSERT INTO kriteria (elemen_id, nomor, deskripsi, urutan) VALUES
(13, 1, 'Terdapat sarana dan sumber informasi yang mudah diakses oleh staf dan pengunjung', 1),
(13, 2, 'Terdapat sistem informasi yang bersifat segera/mendesak beurupa alarm Peringatan/larangan/tanda bahaya', 2),
(13, 3, 'Terdapat nomor telpon pengaduan/pelaporan kedaruratan', 3),
(13, 4, 'terdapat sarana penyampaian informasi segera yang bersumber dari staf/pengunjung', 4),
(13, 5, 'tersedia sarana pengaduan dari pengunjung', 5),
(13, 6, 'Terdapat media informasi secara visual bagi staf dan pengunjung (video tron, media sosial dan rambu petunjuk dan papan pengumuman)', 6),
(13, 7, 'terdapat SOP terhadap penyampaian informasi yang meliputi keakuratan, ketepatan waktu dan keotentikan sumber informasi', 7),
(13, 8, 'terdapat sarana komunikasi atar staf dalam penyampaian informasi yang bersifat internal', 8),
(13, 9, 'dilakukan pembaruan informasi sesuai perkembangan situasi dan kondisi serta kebutuhan', 9),
(13, 10, 'perawatan dan pemeliharaan terhadap sarana informasi secara berkala dan sesaui standar', 10);

