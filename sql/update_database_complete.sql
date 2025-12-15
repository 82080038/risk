-- =====================================================
-- UPDATE DATABASE STRUCTURE - COMPLETE
-- Risk Assessment Objek Wisata
-- 
-- Script ini untuk update struktur database ke versi lengkap
-- Jalankan di phpMyAdmin: http://localhost/phpmyadmin/index.php?route=/database/sql&db=risk_assessment_db
-- =====================================================

USE risk_assessment_db;

-- =====================================================
-- UPDATE TABEL: objek_wisata
-- =====================================================

-- Tambahkan field jenis jika belum ada
ALTER TABLE objek_wisata 
ADD COLUMN IF NOT EXISTS jenis VARCHAR(100) NULL AFTER alamat;

-- Tambahkan field wilayah_hukum jika belum ada
ALTER TABLE objek_wisata 
ADD COLUMN IF NOT EXISTS wilayah_hukum VARCHAR(100) NULL AFTER jenis;

-- Tambahkan field keterangan jika belum ada
ALTER TABLE objek_wisata 
ADD COLUMN IF NOT EXISTS keterangan VARCHAR(255) NULL AFTER wilayah_hukum;

-- Tambahkan index untuk jenis jika belum ada
CREATE INDEX IF NOT EXISTS idx_jenis ON objek_wisata (jenis);

-- Tambahkan index untuk wilayah_hukum jika belum ada
CREATE INDEX IF NOT EXISTS idx_wilayah ON objek_wisata (wilayah_hukum);

-- =====================================================
-- VERIFIKASI
-- =====================================================

-- Tampilkan struktur tabel objek_wisata
DESCRIBE objek_wisata;

-- Tampilkan semua indexes
SHOW INDEXES FROM objek_wisata;

-- =====================================================
-- CATATAN
-- =====================================================
-- Jika error "IF NOT EXISTS" tidak didukung, gunakan script di bawah:
-- (MySQL versi lama mungkin tidak support IF NOT EXISTS)

