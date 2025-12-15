# DATABASE CHECK & UPDATE GUIDE
## Risk Assessment Objek Wisata

---

## ✅ CHECK DATABASE STRUCTURE

### Cara 1: Via Web Interface (Recommended)
Buka di browser:
```
http://localhost/RISK/tools/check_database_structure.php
```

Script ini akan:
- ✅ Check koneksi database
- ✅ Check semua field di tabel `objek_wisata`
- ✅ Check semua table yang diperlukan
- ✅ Check indexes
- ✅ Menampilkan summary (success/warnings/errors)
- ✅ Generate SQL fix jika ada error

### Cara 2: Via phpMyAdmin
Buka:
```
http://localhost/phpmyadmin/index.php?route=/database/sql&db=risk_assessment_db
```

---

## 🔧 UPDATE DATABASE STRUCTURE

### Script SQL yang Tersedia:

#### 1. **sql/update_database_safe.sql** (RECOMMENDED)
- ✅ Aman untuk dijalankan berulang kali
- ✅ Check field/index sebelum menambahkan
- ✅ Tidak akan error jika sudah ada
- ✅ Menampilkan struktur setelah update

**Cara pakai:**
1. Buka phpMyAdmin: `http://localhost/phpmyadmin/index.php?route=/database/sql&db=risk_assessment_db`
2. Copy-paste isi file `sql/update_database_safe.sql`
3. Klik "Go" atau tekan Ctrl+Enter

#### 2. **sql/alter_objek_wisata.sql** (Simple)
- Versi sederhana tanpa check
- Akan error jika field sudah ada
- Gunakan jika yakin field belum ada

**Cara pakai:**
1. Buka phpMyAdmin
2. Copy-paste isi file `sql/alter_objek_wisata.sql`
3. Klik "Go"

---

## 📋 FIELD YANG HARUS ADA

### Tabel: `objek_wisata`

| Field | Type | Required | Status |
|-------|------|----------|--------|
| `id` | INT PRIMARY KEY | ✅ | Auto |
| `nama` | VARCHAR(255) | ✅ | Auto |
| `alamat` | TEXT | ✅ | Auto |
| `jenis` | VARCHAR(100) | ⚠️ | **Perlu ditambahkan** |
| `wilayah_hukum` | VARCHAR(100) | ⚠️ | **Perlu ditambahkan** |
| `keterangan` | VARCHAR(255) | ⚠️ | **Perlu ditambahkan** |
| `created_at` | TIMESTAMP | ✅ | Auto |
| `updated_at` | TIMESTAMP | ✅ | Auto |

### Indexes yang Harus Ada:
- ✅ `PRIMARY` (id)
- ✅ `idx_nama` (nama)
- ⚠️ `idx_jenis` (jenis) - **Perlu ditambahkan**
- ⚠️ `idx_wilayah` (wilayah_hukum) - **Perlu ditambahkan**

---

## 🧪 VERIFIKASI

### Setelah Update, Check:

1. **Via phpMyAdmin:**
   ```sql
   DESCRIBE objek_wisata;
   SHOW INDEXES FROM objek_wisata;
   ```

2. **Via Web Interface:**
   ```
   http://localhost/RISK/tools/check_database_structure.php
   ```

3. **Via Aplikasi:**
   - Buka halaman `Objek Wisata`
   - Coba tambah/edit objek wisata
   - Pastikan field `jenis`, `wilayah_hukum`, `keterangan` muncul

---

## 📝 SQL QUERY UNTUK CHECK

### Check Field:
```sql
SELECT 
    COLUMN_NAME as 'Field',
    COLUMN_TYPE as 'Type',
    IS_NULLABLE as 'Null',
    COLUMN_KEY as 'Key'
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_SCHEMA = 'risk_assessment_db'
  AND TABLE_NAME = 'objek_wisata'
ORDER BY ORDINAL_POSITION;
```

### Check Indexes:
```sql
SHOW INDEXES FROM objek_wisata;
```

### Check Table Exists:
```sql
SHOW TABLES LIKE 'objek_wisata';
```

---

## ⚠️ TROUBLESHOOTING

### Error: "Duplicate column name"
- **Penyebab:** Field sudah ada
- **Solusi:** Gunakan `sql/update_database_safe.sql` yang check dulu

### Error: "Table doesn't exist"
- **Penyebab:** Tabel belum dibuat
- **Solusi:** Jalankan `sql/database.sql` dulu

### Error: "Access denied"
- **Penyebab:** User database tidak punya permission
- **Solusi:** Check user database di `config/database.php`

---

## ✅ CHECKLIST

- [ ] Database `risk_assessment_db` ada
- [ ] Tabel `objek_wisata` ada
- [ ] Field `jenis` ada
- [ ] Field `wilayah_hukum` ada
- [ ] Field `keterangan` ada
- [ ] Index `idx_jenis` ada
- [ ] Index `idx_wilayah` ada
- [ ] Aplikasi bisa akses database
- [ ] Form objek wisata menampilkan field baru

---

## 🚀 QUICK START

1. **Check struktur:**
   ```
   http://localhost/RISK/tools/check_database_structure.php
   ```

2. **Jika ada error, update:**
   - Buka phpMyAdmin
   - Jalankan `sql/update_database_safe.sql`

3. **Verifikasi:**
   - Check lagi via web interface
   - Test via aplikasi

---

**Status:** ✅ **Database structure check & update tools ready**

