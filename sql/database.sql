-- =====================================================
-- DATABASE STRUCTURE
-- Risk Assessment Objek Wisata
-- =====================================================

-- Buat database
CREATE DATABASE IF NOT EXISTS risk_assessment_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE risk_assessment_db;

-- =====================================================
-- TABEL: users
-- =====================================================
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    pangkat_nrp VARCHAR(100),
    role ENUM('admin', 'penilai') DEFAULT 'penilai',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABEL: objek_wisata
-- =====================================================
CREATE TABLE objek_wisata (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(255) NOT NULL,
    alamat TEXT,
    jenis VARCHAR(100) NULL,
    wilayah_hukum VARCHAR(100) NULL,
    keterangan VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_nama (nama),
    INDEX idx_jenis (jenis),
    INDEX idx_wilayah (wilayah_hukum)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABEL: aspek
-- =====================================================
CREATE TABLE aspek (
    id INT PRIMARY KEY AUTO_INCREMENT,
    kode VARCHAR(10) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    bobot DECIMAL(5,2) NOT NULL,
    urutan INT NOT NULL,
    INDEX idx_urutan (urutan)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABEL: elemen
-- =====================================================
CREATE TABLE elemen (
    id INT PRIMARY KEY AUTO_INCREMENT,
    aspek_id INT NOT NULL,
    kode VARCHAR(10) NOT NULL,
    nama VARCHAR(255) NOT NULL,
    bobot DECIMAL(5,2) NOT NULL,
    urutan INT NOT NULL,
    FOREIGN KEY (aspek_id) REFERENCES aspek(id) ON DELETE CASCADE,
    INDEX idx_aspek (aspek_id),
    INDEX idx_urutan (urutan)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABEL: kriteria
-- =====================================================
CREATE TABLE kriteria (
    id INT PRIMARY KEY AUTO_INCREMENT,
    elemen_id INT NOT NULL,
    nomor INT NOT NULL,
    deskripsi TEXT NOT NULL,
    urutan INT NOT NULL,
    FOREIGN KEY (elemen_id) REFERENCES elemen(id) ON DELETE CASCADE,
    INDEX idx_elemen (elemen_id),
    INDEX idx_urutan (urutan)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABEL: penilaian
-- =====================================================
CREATE TABLE penilaian (
    id INT PRIMARY KEY AUTO_INCREMENT,
    objek_wisata_id INT NOT NULL,
    user_id INT NOT NULL,
    tanggal_penilaian DATE NOT NULL,
    nama_penilai VARCHAR(100) NOT NULL,
    pangkat_nrp VARCHAR(100),
    skor_final DECIMAL(5,2) DEFAULT 0,
    kategori VARCHAR(50),
    status ENUM('draft', 'selesai') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (objek_wisata_id) REFERENCES objek_wisata(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_objek (objek_wisata_id),
    INDEX idx_user (user_id),
    INDEX idx_tanggal (tanggal_penilaian),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABEL: penilaian_detail
-- =====================================================
CREATE TABLE penilaian_detail (
    id INT PRIMARY KEY AUTO_INCREMENT,
    penilaian_id INT NOT NULL,
    kriteria_id INT NOT NULL,
    nilai INT NOT NULL CHECK (nilai IN (0, 1, 2)),
    temuan TEXT,
    rekomendasi TEXT,
    skor_elemen DECIMAL(5,2) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (penilaian_id) REFERENCES penilaian(id) ON DELETE CASCADE,
    FOREIGN KEY (kriteria_id) REFERENCES kriteria(id) ON DELETE CASCADE,
    UNIQUE KEY unique_penilaian_kriteria (penilaian_id, kriteria_id),
    INDEX idx_penilaian (penilaian_id),
    INDEX idx_kriteria (kriteria_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABEL: referensi_dokumen
-- =====================================================
CREATE TABLE referensi_dokumen (
    id INT PRIMARY KEY AUTO_INCREMENT,
    penilaian_id INT NOT NULL,
    kriteria_id INT,
    nama_file VARCHAR(255) NOT NULL,
    path_file VARCHAR(500) NOT NULL,
    tipe_file VARCHAR(50),
    ukuran_file INT,
    deskripsi TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (penilaian_id) REFERENCES penilaian(id) ON DELETE CASCADE,
    FOREIGN KEY (kriteria_id) REFERENCES kriteria(id) ON DELETE SET NULL,
    INDEX idx_penilaian (penilaian_id),
    INDEX idx_kriteria (kriteria_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- INSERT DEFAULT ADMIN USER
-- Password: admin123 (harus di-hash dengan password_hash())
-- =====================================================
INSERT INTO users (username, password, nama, pangkat_nrp, role) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'Admin', 'admin');

-- Note: Password di atas adalah hash dari 'admin123'
-- Untuk production, gunakan password_hash() di PHP

