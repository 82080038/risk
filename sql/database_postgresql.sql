-- =====================================================
-- DATABASE STRUCTURE - PostgreSQL Version
-- Risk Assessment Objek Wisata
-- Untuk Railway PostgreSQL
-- =====================================================

-- =====================================================
-- TABEL: users
-- =====================================================
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    pangkat_nrp VARCHAR(100),
    role VARCHAR(20) DEFAULT 'penilai' CHECK (role IN ('admin', 'penilai')),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_users_username ON users(username);

-- =====================================================
-- TABEL: objek_wisata
-- =====================================================
CREATE TABLE IF NOT EXISTS objek_wisata (
    id SERIAL PRIMARY KEY,
    nama VARCHAR(255) NOT NULL,
    alamat TEXT,
    jenis VARCHAR(100),
    wilayah_hukum VARCHAR(100),
    keterangan VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_objek_nama ON objek_wisata(nama);
CREATE INDEX IF NOT EXISTS idx_objek_jenis ON objek_wisata(jenis);
CREATE INDEX IF NOT EXISTS idx_objek_wilayah ON objek_wisata(wilayah_hukum);

-- =====================================================
-- TABEL: aspek
-- =====================================================
CREATE TABLE IF NOT EXISTS aspek (
    id SERIAL PRIMARY KEY,
    kode VARCHAR(10) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    bobot DECIMAL(5,2) NOT NULL,
    urutan INTEGER NOT NULL
);

CREATE INDEX IF NOT EXISTS idx_aspek_urutan ON aspek(urutan);

-- =====================================================
-- TABEL: elemen
-- =====================================================
CREATE TABLE IF NOT EXISTS elemen (
    id SERIAL PRIMARY KEY,
    aspek_id INTEGER NOT NULL,
    kode VARCHAR(10) NOT NULL,
    nama VARCHAR(255) NOT NULL,
    bobot DECIMAL(5,2) NOT NULL,
    urutan INTEGER NOT NULL,
    FOREIGN KEY (aspek_id) REFERENCES aspek(id) ON DELETE CASCADE
);

CREATE INDEX IF NOT EXISTS idx_elemen_aspek ON elemen(aspek_id);
CREATE INDEX IF NOT EXISTS idx_elemen_urutan ON elemen(urutan);

-- =====================================================
-- TABEL: kriteria
-- =====================================================
CREATE TABLE IF NOT EXISTS kriteria (
    id SERIAL PRIMARY KEY,
    elemen_id INTEGER NOT NULL,
    nomor INTEGER NOT NULL,
    deskripsi TEXT NOT NULL,
    urutan INTEGER NOT NULL,
    FOREIGN KEY (elemen_id) REFERENCES elemen(id) ON DELETE CASCADE
);

CREATE INDEX IF NOT EXISTS idx_kriteria_elemen ON kriteria(elemen_id);
CREATE INDEX IF NOT EXISTS idx_kriteria_urutan ON kriteria(urutan);

-- =====================================================
-- TABEL: penilaian
-- =====================================================
CREATE TABLE IF NOT EXISTS penilaian (
    id SERIAL PRIMARY KEY,
    objek_wisata_id INTEGER NOT NULL,
    user_id INTEGER NOT NULL,
    tanggal_penilaian DATE NOT NULL,
    nama_penilai VARCHAR(100) NOT NULL,
    pangkat_nrp VARCHAR(100),
    skor_final DECIMAL(5,2) DEFAULT 0,
    kategori VARCHAR(50),
    status VARCHAR(20) DEFAULT 'draft' CHECK (status IN ('draft', 'selesai')),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (objek_wisata_id) REFERENCES objek_wisata(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE INDEX IF NOT EXISTS idx_penilaian_objek ON penilaian(objek_wisata_id);
CREATE INDEX IF NOT EXISTS idx_penilaian_user ON penilaian(user_id);
CREATE INDEX IF NOT EXISTS idx_penilaian_tanggal ON penilaian(tanggal_penilaian);
CREATE INDEX IF NOT EXISTS idx_penilaian_status ON penilaian(status);

-- =====================================================
-- TABEL: penilaian_detail
-- =====================================================
CREATE TABLE IF NOT EXISTS penilaian_detail (
    id SERIAL PRIMARY KEY,
    penilaian_id INTEGER NOT NULL,
    kriteria_id INTEGER NOT NULL,
    nilai INTEGER NOT NULL CHECK (nilai IN (0, 1, 2)),
    temuan TEXT,
    rekomendasi TEXT,
    skor_elemen DECIMAL(5,2) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (penilaian_id) REFERENCES penilaian(id) ON DELETE CASCADE,
    FOREIGN KEY (kriteria_id) REFERENCES kriteria(id) ON DELETE CASCADE,
    UNIQUE (penilaian_id, kriteria_id)
);

CREATE INDEX IF NOT EXISTS idx_detail_penilaian ON penilaian_detail(penilaian_id);
CREATE INDEX IF NOT EXISTS idx_detail_kriteria ON penilaian_detail(kriteria_id);

-- =====================================================
-- TABEL: referensi_dokumen
-- =====================================================
CREATE TABLE IF NOT EXISTS referensi_dokumen (
    id SERIAL PRIMARY KEY,
    penilaian_id INTEGER NOT NULL,
    kriteria_id INTEGER,
    nama_file VARCHAR(255) NOT NULL,
    path_file VARCHAR(500) NOT NULL,
    tipe_file VARCHAR(50),
    ukuran_file INTEGER,
    deskripsi TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (penilaian_id) REFERENCES penilaian(id) ON DELETE CASCADE,
    FOREIGN KEY (kriteria_id) REFERENCES kriteria(id) ON DELETE SET NULL
);

CREATE INDEX IF NOT EXISTS idx_ref_penilaian ON referensi_dokumen(penilaian_id);
CREATE INDEX IF NOT EXISTS idx_ref_kriteria ON referensi_dokumen(kriteria_id);

-- =====================================================
-- FUNCTION: Update updated_at timestamp
-- =====================================================
CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ language 'plpgsql';

-- Trigger untuk objek_wisata
CREATE TRIGGER update_objek_wisata_updated_at BEFORE UPDATE ON objek_wisata
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

-- Trigger untuk penilaian
CREATE TRIGGER update_penilaian_updated_at BEFORE UPDATE ON penilaian
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

-- Trigger untuk penilaian_detail
CREATE TRIGGER update_penilaian_detail_updated_at BEFORE UPDATE ON penilaian_detail
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

-- =====================================================
-- INSERT DEFAULT ADMIN USER
-- Password: admin123 (hash dengan bcrypt)
-- =====================================================
INSERT INTO users (username, password, nama, pangkat_nrp, role) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'Admin', 'admin')
ON CONFLICT (username) DO NOTHING;

-- Note: Password di atas adalah hash dari 'admin123'
-- Untuk production, gunakan password_hash() di PHP

