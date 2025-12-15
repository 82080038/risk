# PANDUAN IMPORT DATA OBJEK WISATA
## Risk Assessment Objek Wisata

---

## 📋 INFORMASI DATA

**Total Objek Wisata:** 69 objek
**Wilayah:** Polres Samosir
**Sumber:** DATA WISATA ,OBVITNAS & OBVITER-1.pdf

### Kategori:
- **Kawasan Ekonomi Khusus:** 6 objek
- **Objek Vital Nasional:** 2 objek
- **Objek Wisata Biasa:** 61 objek

### Jenis Wisata:
- Wisata Alam
- Wisata Budaya
- Wisata Sejarah dan Budaya
- Wisata Belanja
- Wisata Religi
- Agrowisata
- Kombinasi (Alam + Budaya, dll)

---

## 📁 FILE YANG TERSEDIA

1. **`sql/data_objek_wisata.sql`** - File SQL untuk import ke database
2. **`data_objek_wisata.txt`** - Data dalam format teks (readable)
3. **`data_objek_wisata.csv`** - Data dalam format CSV (untuk Excel)

---

## 🗄️ CARA IMPORT KE DATABASE

### Metode 1: Menggunakan phpMyAdmin

1. Buka **phpMyAdmin** di browser
   - URL: `http://localhost/phpmyadmin`

2. Pilih database **`risk_assessment_db`**
   - Jika belum ada, buat dulu dengan menjalankan `sql/database.sql`

3. Klik tab **"SQL"**

4. Klik **"Choose File"** atau **"Pilih File"**

5. Pilih file: **`sql/data_objek_wisata.sql`**

6. Klik **"Go"** atau **"Jalankan"**

7. Tunggu hingga proses selesai

8. Verifikasi data:
   ```sql
   SELECT COUNT(*) as total_objek FROM objek_wisata;
   SELECT * FROM objek_wisata ORDER BY id LIMIT 10;
   ```

### Metode 2: Menggunakan Command Line (MySQL)

```bash
# Masuk ke MySQL
mysql -u root -p

# Pilih database
USE risk_assessment_db;

# Import file SQL
SOURCE E:/xampp/htdocs/RISK/sql/data_objek_wisata.sql;

# Atau langsung dari command line:
mysql -u root -p risk_assessment_db < E:/xampp/htdocs/RISK/sql/data_objek_wisata.sql
```

### Metode 3: Copy-Paste SQL

1. Buka file **`sql/data_objek_wisata.sql`**

2. Copy semua isi file

3. Buka **phpMyAdmin** → Pilih database **`risk_assessment_db`** → Tab **"SQL"**

4. Paste SQL yang sudah dicopy

5. Klik **"Go"** atau **"Jalankan"**

---

## 📊 STRUKTUR DATA

Setiap objek wisata memiliki:
- **id** - Auto increment
- **nama** - Nama objek wisata
- **alamat** - Informasi lengkap (Lokasi, Jenis, Wilayah, Keterangan)
- **created_at** - Timestamp otomatis
- **updated_at** - Timestamp otomatis (update saat diubah)

### Format Alamat:
```
Lokasi: [Kota/Kab] | Jenis: [Jenis Wisata] | Wilayah: [Wilkum] | Keterangan: [KET]
```

**Contoh:**
```
Lokasi: Parbaba/Samosir | Jenis: Wisata Alam | Wilayah: Polres Samosir | Keterangan: Kawasan Ekonomi Khusus
```

---

## ✅ VERIFIKASI SETELAH IMPORT

Jalankan query berikut untuk memverifikasi:

```sql
-- Total objek wisata
SELECT COUNT(*) as total_objek FROM objek_wisata;

-- Daftar 10 objek pertama
SELECT id, nama, LEFT(alamat, 100) as alamat_preview FROM objek_wisata ORDER BY id LIMIT 10;

-- Objek berdasarkan kategori
SELECT 
    CASE 
        WHEN alamat LIKE '%Kawasan Ekonomi Khusus%' THEN 'Kawasan Ekonomi Khusus'
        WHEN alamat LIKE '%Objek Vital nasional%' THEN 'Objek Vital Nasional'
        ELSE 'Objek Wisata Biasa'
    END as kategori,
    COUNT(*) as jumlah
FROM objek_wisata
GROUP BY kategori;

-- Objek berdasarkan jenis wisata
SELECT 
    SUBSTRING_INDEX(SUBSTRING_INDEX(alamat, 'Jenis: ', -1), ' |', 1) as jenis_wisata,
    COUNT(*) as jumlah
FROM objek_wisata
GROUP BY jenis_wisata
ORDER BY jumlah DESC;
```

**Hasil yang diharapkan:**
- Total objek wisata: 69
- Kawasan Ekonomi Khusus: 6
- Objek Vital Nasional: 2
- Objek Wisata Biasa: 61

---

## 🔄 UPDATE DATA (Jika Perlu)

### Menambah Objek Wisata Baru:
```sql
INSERT INTO objek_wisata (nama, alamat) VALUES
('Nama Objek Wisata', 'Lokasi: ... | Jenis: ... | Wilayah: ... | Keterangan: ...');
```

### Mengubah Data Objek Wisata:
```sql
UPDATE objek_wisata 
SET nama = 'Nama Baru', 
    alamat = 'Alamat Baru'
WHERE id = 1;
```

### Menghapus Objek Wisata:
```sql
-- Hapus objek wisata tertentu
DELETE FROM objek_wisata WHERE id = 1;

-- Hapus semua objek wisata (hati-hati!)
DELETE FROM objek_wisata;
```

---

## 📝 CATATAN PENTING

1. **Alamat Field:**
   - Field `alamat` berisi informasi lengkap yang digabung dengan separator `|`
   - Format: `Lokasi: ... | Jenis: ... | Wilayah: ... | Keterangan: ...`
   - Jika perlu memisahkan, bisa dilakukan dengan parsing di aplikasi

2. **Keterangan (KET):**
   - Kawasan Ekonomi Khusus
   - Objek Vital nasional
   - Objek Wisata Biasa

3. **Jenis Wisata:**
   - Wisata Alam
   - Wisata Budaya
   - Wisata Sejarah dan Budaya
   - Wisata Belanja
   - Wisata Religi
   - Agrowisata
   - Kombinasi (Alam + Budaya, dll)

4. **Relasi dengan Tabel Lain:**
   - Tabel `objek_wisata` akan dihubungkan dengan tabel `penilaian`
   - Satu objek wisata bisa memiliki banyak penilaian (history)

---

## 🔍 CONTOH QUERY UNTUK APLIKASI

### Mencari Objek Wisata:
```sql
-- Cari berdasarkan nama
SELECT * FROM objek_wisata 
WHERE nama LIKE '%Pantai%' 
ORDER BY nama;

-- Cari berdasarkan lokasi
SELECT * FROM objek_wisata 
WHERE alamat LIKE '%Samosir%' 
ORDER BY nama;

-- Cari berdasarkan jenis
SELECT * FROM objek_wisata 
WHERE alamat LIKE '%Wisata Alam%' 
ORDER BY nama;
```

### Daftar Objek dengan Jumlah Penilaian:
```sql
SELECT 
    ow.id,
    ow.nama,
    COUNT(p.id) as jumlah_penilaian,
    MAX(p.tanggal_penilaian) as penilaian_terakhir
FROM objek_wisata ow
LEFT JOIN penilaian p ON ow.id = p.objek_wisata_id
GROUP BY ow.id, ow.nama
ORDER BY ow.nama;
```

---

## ❓ TROUBLESHOOTING

### Error: Table doesn't exist
**Solusi:** Jalankan `sql/database.sql` terlebih dahulu untuk membuat struktur database

### Error: Duplicate entry
**Solusi:** Data sudah ada, gunakan `UPDATE` atau `DELETE` dulu data lama, atau gunakan `INSERT IGNORE`

### Error: Unknown database 'risk_assessment_db'
**Solusi:** Buat database terlebih dahulu dengan menjalankan `sql/database.sql`

### Error: Data tidak lengkap
**Solusi:** Periksa file SQL, pastikan semua data lengkap. Bisa juga import ulang dari file CSV

---

## 📈 STATISTIK DATA

Berdasarkan data yang diimport:

- **Total Objek:** 69
- **Kawasan Ekonomi Khusus:** 6 (8.7%)
- **Objek Vital Nasional:** 2 (2.9%)
- **Objek Wisata Biasa:** 61 (88.4%)

**Jenis Wisata Terbanyak:**
- Wisata Alam: ~40+ objek
- Wisata Budaya: ~15+ objek
- Kombinasi: ~10+ objek
- Lainnya: Wisata Belanja, Religi, Agrowisata

---

**File ini dibuat untuk memudahkan import data objek wisata ke database.**

