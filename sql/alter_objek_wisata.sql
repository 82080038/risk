-- =====================================================
-- ALTER TABLE objek_wisata
-- Menambahkan field sesuai spesifikasi file acuan
-- =====================================================

USE risk_assessment_db;

-- Tambah field jenis (jenis wisata)
ALTER TABLE objek_wisata 
ADD COLUMN jenis VARCHAR(100) NULL AFTER alamat;

-- Tambah field wilayah_hukum (wilkum)
ALTER TABLE objek_wisata 
ADD COLUMN wilayah_hukum VARCHAR(100) NULL AFTER jenis;

-- Tambah field keterangan (ket)
ALTER TABLE objek_wisata 
ADD COLUMN keterangan VARCHAR(255) NULL AFTER wilayah_hukum;

-- Update alamat menjadi lokasi (kota_kab)
-- Note: alamat tetap ada, tapi bisa diisi dengan lokasi/kota_kab

-- Tambah index untuk pencarian
ALTER TABLE objek_wisata 
ADD INDEX idx_jenis (jenis),
ADD INDEX idx_wilayah (wilayah_hukum);

