# PANDUAN IMPORT DATA PERSONIL
## Risk Assessment Objek Wisata

---

## 📋 INFORMASI DATA

**Total Personil:** 19 orang
- **Admin:** 1 orang (TANGIO HAOJAHAN SITANGGANG, S.H.)
- **Penilai:** 18 orang

---

## 📁 FILE YANG TERSEDIA

1. **`sql/data_personil.sql`** - File SQL untuk import ke database
2. **`data_personil.txt`** - Data dalam format teks (readable)
3. **`data_personil.csv`** - Data dalam format CSV (untuk Excel)

---

## 🗄️ CARA IMPORT KE DATABASE

### Metode 1: Menggunakan phpMyAdmin

1. Buka **phpMyAdmin** di browser
   - URL: `http://localhost/phpmyadmin`

2. Pilih database **`risk_assessment_db`**
   - Jika belum ada, buat dulu dengan menjalankan `sql/database.sql`

3. Klik tab **"SQL"**

4. Klik **"Choose File"** atau **"Pilih File"**

5. Pilih file: **`sql/data_personil.sql`**

6. Klik **"Go"** atau **"Jalankan"**

7. Tunggu hingga proses selesai

8. Verifikasi data:
   ```sql
   SELECT COUNT(*) as total_personil FROM users WHERE role IN ('admin', 'penilai');
   SELECT * FROM users ORDER BY id;
   ```

### Metode 2: Menggunakan Command Line (MySQL)

```bash
# Masuk ke MySQL
mysql -u root -p

# Pilih database
USE risk_assessment_db;

# Import file SQL
SOURCE E:/xampp/htdocs/RISK/sql/data_personil.sql;

# Atau langsung dari command line:
mysql -u root -p risk_assessment_db < E:/xampp/htdocs/RISK/sql/data_personil.sql
```

### Metode 3: Copy-Paste SQL

1. Buka file **`sql/data_personil.sql`**

2. Copy semua isi file

3. Buka **phpMyAdmin** → Pilih database **`risk_assessment_db`** → Tab **"SQL"**

4. Paste SQL yang sudah dicopy

5. Klik **"Go"** atau **"Jalankan"**

---

## 🔐 INFORMASI LOGIN

### Sistem Login:
- **Username:** NRP (Nomor Registrasi Pokok) - hanya angka
- **Password:** NRP (sama dengan username) - hanya angka
- Password sudah di-hash menggunakan bcrypt

### Contoh Login:

**Admin:**
- **Username:** `72100664`
- **Password:** `72100664`
- **Role:** admin
- **Nama:** Tangio Haojahan Sitanggang, S.H.

**Penilai (contoh):**
- **Username:** `69090552`
- **Password:** `69090552`
- **Role:** penilai
- **Nama:** Rahmat Kurniawan

**⚠️ PENTING:** 
- Username dan Password menggunakan **NRP** (hanya angka)
- Password sudah di-hash menggunakan bcrypt
- Disarankan untuk mengubah password setelah login pertama kali

---

## 📊 STRUKTUR DATA

Setiap personil memiliki:
- **id** - Auto increment
- **username** - Dibuat dari nama (lowercase, underscore)
- **password** - Hash bcrypt dari "password123"
- **nama** - Nama lengkap personil
- **pangkat_nrp** - Format: "PANGKAT/NRP" (contoh: "IPTU/72100664")
- **role** - "admin" atau "penilai"
- **created_at** - Timestamp otomatis

---

## ✅ VERIFIKASI SETELAH IMPORT

Jalankan query berikut untuk memverifikasi:

```sql
-- Total personil
SELECT COUNT(*) as total_personil FROM users WHERE role IN ('admin', 'penilai');

-- Daftar semua personil
SELECT id, username, nama, pangkat_nrp, role FROM users ORDER BY id;

-- Cek admin
SELECT * FROM users WHERE role = 'admin';

-- Cek penilai
SELECT COUNT(*) as total_penilai FROM users WHERE role = 'penilai';
```

**Hasil yang diharapkan:**
- Total personil: 19
- Admin: 1
- Penilai: 18

---

## 🔄 UPDATE DATA (Jika Perlu)

### Menambah Personil Baru:
```sql
INSERT INTO users (username, password, nama, pangkat_nrp, role) VALUES
('username_baru', '$2y$10$...', 'NAMA LENGKAP', 'PANGKAT/NRP', 'penilai');
```

### Mengubah Password:
```sql
-- Di aplikasi PHP, gunakan password_hash()
UPDATE users SET password = '$2y$10$...' WHERE username = 'username';
```

### Mengubah Role:
```sql
UPDATE users SET role = 'admin' WHERE username = 'username';
```

---

## 🗑️ HAPUS DATA (Jika Perlu)

```sql
-- Hapus semua personil (kecuali admin default)
DELETE FROM users WHERE role = 'penilai';

-- Hapus semua user (hati-hati!)
DELETE FROM users;
```

---

## 📝 CATATAN

1. **Username** dibuat otomatis dari nama dengan format:
   - Lowercase
   - Spasi diganti underscore
   - Karakter khusus dihapus

2. **Password Hash:**
   - Format: bcrypt
   - Default: `$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi`
   - Ini adalah hash dari: `password123`

3. **Pangkat/NRP:**
   - Format: "PANGKAT/NRP"
   - Contoh: "IPTU/72100664"

4. **Role:**
   - `admin`: Dapat mengelola semua data
   - `penilai`: Hanya dapat melakukan penilaian

---

## ❓ TROUBLESHOOTING

### Error: Table doesn't exist
**Solusi:** Jalankan `sql/database.sql` terlebih dahulu untuk membuat struktur database

### Error: Duplicate entry for key 'username'
**Solusi:** Data sudah ada, gunakan `UPDATE` atau `DELETE` dulu data lama

### Error: Unknown database 'risk_assessment_db'
**Solusi:** Buat database terlebih dahulu dengan menjalankan `sql/database.sql`

---

**File ini dibuat untuk memudahkan import data personil ke database.**

