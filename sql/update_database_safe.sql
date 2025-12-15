-- =====================================================
-- UPDATE DATABASE STRUCTURE - SAFE VERSION
-- Risk Assessment Objek Wisata
-- 
-- Script ini aman untuk dijalankan berulang kali
-- Tidak akan error jika field sudah ada
-- =====================================================

USE risk_assessment_db;

-- =====================================================
-- UPDATE TABEL: objek_wisata
-- =====================================================

-- Check dan tambahkan field jenis
SET @exist := (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = 'risk_assessment_db' 
    AND TABLE_NAME = 'objek_wisata' 
    AND COLUMN_NAME = 'jenis');
SET @sqlstmt := IF(@exist = 0, 
    'ALTER TABLE objek_wisata ADD COLUMN jenis VARCHAR(100) NULL AFTER alamat', 
    'SELECT "Field jenis sudah ada" AS message');
PREPARE stmt FROM @sqlstmt;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Check dan tambahkan field wilayah_hukum
SET @exist := (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = 'risk_assessment_db' 
    AND TABLE_NAME = 'objek_wisata' 
    AND COLUMN_NAME = 'wilayah_hukum');
SET @sqlstmt := IF(@exist = 0, 
    'ALTER TABLE objek_wisata ADD COLUMN wilayah_hukum VARCHAR(100) NULL AFTER jenis', 
    'SELECT "Field wilayah_hukum sudah ada" AS message');
PREPARE stmt FROM @sqlstmt;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Check dan tambahkan field keterangan
SET @exist := (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = 'risk_assessment_db' 
    AND TABLE_NAME = 'objek_wisata' 
    AND COLUMN_NAME = 'keterangan');
SET @sqlstmt := IF(@exist = 0, 
    'ALTER TABLE objek_wisata ADD COLUMN keterangan VARCHAR(255) NULL AFTER wilayah_hukum', 
    'SELECT "Field keterangan sudah ada" AS message');
PREPARE stmt FROM @sqlstmt;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Check dan tambahkan index idx_jenis
SET @exist := (SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS 
    WHERE TABLE_SCHEMA = 'risk_assessment_db' 
    AND TABLE_NAME = 'objek_wisata' 
    AND INDEX_NAME = 'idx_jenis');
SET @sqlstmt := IF(@exist = 0, 
    'ALTER TABLE objek_wisata ADD INDEX idx_jenis (jenis)', 
    'SELECT "Index idx_jenis sudah ada" AS message');
PREPARE stmt FROM @sqlstmt;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Check dan tambahkan index idx_wilayah
SET @exist := (SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS 
    WHERE TABLE_SCHEMA = 'risk_assessment_db' 
    AND TABLE_NAME = 'objek_wisata' 
    AND INDEX_NAME = 'idx_wilayah');
SET @sqlstmt := IF(@exist = 0, 
    'ALTER TABLE objek_wisata ADD INDEX idx_wilayah (wilayah_hukum)', 
    'SELECT "Index idx_wilayah sudah ada" AS message');
PREPARE stmt FROM @sqlstmt;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- =====================================================
-- VERIFIKASI STRUKTUR
-- =====================================================

-- Tampilkan struktur tabel objek_wisata
SELECT 
    COLUMN_NAME as 'Field',
    COLUMN_TYPE as 'Type',
    IS_NULLABLE as 'Null',
    COLUMN_KEY as 'Key',
    COLUMN_DEFAULT as 'Default',
    EXTRA as 'Extra'
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_SCHEMA = 'risk_assessment_db'
  AND TABLE_NAME = 'objek_wisata'
ORDER BY ORDINAL_POSITION;

-- Tampilkan semua indexes
SHOW INDEXES FROM objek_wisata;

-- =====================================================
-- HASIL
-- =====================================================
-- Script ini akan:
-- 1. Check apakah field sudah ada sebelum menambahkan
-- 2. Check apakah index sudah ada sebelum menambahkan
-- 3. Menampilkan struktur tabel setelah update
-- 4. Menampilkan semua indexes
-- 
-- Script aman untuk dijalankan berulang kali
-- =====================================================

