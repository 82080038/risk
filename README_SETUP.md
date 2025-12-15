# PANDUAN SETUP APLIKASI
## Risk Assessment Objek Wisata - Polres Samosir

---

## 📋 PRASYARAT

1. **XAMPP** terinstall dan berjalan
   - Apache: Running
   - MySQL: Running
   - PHP: 7.4 atau lebih tinggi

2. **Browser** modern (Chrome, Firefox, Edge)

---

## 🚀 LANGKAH SETUP

### 1. Setup Database

1. Buka **phpMyAdmin**: `http://localhost/phpmyadmin`

2. Import database:
   - Pilih database atau buat baru: `risk_assessment_db`
   - Tab **"SQL"** → **"Choose File"**
   - Import file: `sql/database.sql`
   - Klik **"Go"**

3. Import data master:
   - Tab **"SQL"** → **"Choose File"**
   - Import file: `sql/master_data.sql`
   - Klik **"Go"**

4. Import data personil:
   - Tab **"SQL"** → **"Choose File"**
   - Import file: `sql/data_personil.sql`
   - Klik **"Go"**

5. Import data objek wisata:
   - Tab **"SQL"** → **"Choose File"**
   - Import file: `sql/data_objek_wisata.sql`
   - Klik **"Go"**

### 2. Konfigurasi Database

Edit file: `config/database.php`

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');        // Sesuaikan jika berbeda
define('DB_PASS', '');            // Sesuaikan jika ada password
define('DB_NAME', 'risk_assessment_db');
```

### 3. Konfigurasi Base URL

Edit file: `config/config.php`

```php
define('BASE_URL', 'http://localhost/RISK/');
```

Sesuaikan jika path berbeda.

### 4. Setup Folder Uploads

Pastikan folder `assets/uploads/` dapat ditulis:

```bash
# Windows: Set permission untuk folder uploads
# Atau buat folder secara manual dengan permission write
```

### 5. Akses Aplikasi

Buka browser dan akses:
```
http://localhost/RISK/
```

Atau langsung ke halaman login:
```
http://localhost/RISK/pages/login.php
```

---

## 🔐 LOGIN DEFAULT

### Admin:
- **Username:** `72100664`
- **Password:** `72100664`
- **Nama:** Tangio Haojahan Sitanggang, S.H.
- **Role:** Admin

### Penilai (contoh):
- **Username:** `69090552`
- **Password:** `69090552`
- **Nama:** Rahmat Kurniawan
- **Role:** Penilai

**Catatan:** Username dan Password menggunakan **NRP** (Nomor Registrasi Pokok)

---

## 📁 STRUKTUR FOLDER

```
RISK/
├── assets/
│   ├── css/          (Custom CSS)
│   ├── js/           (Custom JavaScript)
│   ├── images/       (Logo, icons)
│   └── uploads/      (File referensi - harus writable)
├── config/
│   ├── config.php    (Konfigurasi aplikasi)
│   └── database.php  (Konfigurasi database)
├── includes/
│   ├── header.php    (Header HTML)
│   ├── footer.php    (Footer HTML)
│   ├── navbar.php    (Navigation bar)
│   ├── functions.php (Helper functions)
│   └── kop_surat.php (Template kop surat)
├── pages/
│   ├── login.php     (Halaman login)
│   ├── dashboard.php (Dashboard)
│   └── ...           (Halaman lainnya)
├── api/              (API endpoints)
├── sql/              (File SQL database)
├── index.php         (Entry point)
└── logout.php        (Logout handler)
```

---

## ✅ VERIFIKASI SETUP

### 1. Cek Database
```sql
-- Cek tabel
SHOW TABLES;

-- Cek data personil
SELECT COUNT(*) FROM users;

-- Cek data objek wisata
SELECT COUNT(*) FROM objek_wisata;

-- Cek data aspek
SELECT COUNT(*) FROM aspek;
```

### 2. Cek File Permission
- Folder `assets/uploads/` harus writable
- File `config/database.php` dapat dibaca

### 3. Cek Browser Console
- Buka Developer Tools (F12)
- Cek tab Console untuk error JavaScript
- Cek tab Network untuk error loading resource

### 4. Cek PHP Error
- Buka file `config/config.php`
- Pastikan `error_reporting` dan `display_errors` sesuai kebutuhan

---

## 🐛 TROUBLESHOOTING

### Error: Koneksi database gagal
**Solusi:**
- Pastikan MySQL berjalan di XAMPP
- Cek username dan password di `config/database.php`
- Pastikan database `risk_assessment_db` sudah dibuat

### Error: Page not found (404)
**Solusi:**
- Pastikan Apache berjalan
- Cek Base URL di `config/config.php`
- Pastikan file `.htaccess` ada

### Error: Permission denied (uploads)
**Solusi:**
- Set permission folder `assets/uploads/` menjadi writable
- Windows: Klik kanan folder → Properties → Security → Edit permissions

### Error: Session tidak bekerja
**Solusi:**
- Pastikan `session_start()` dipanggil di `config/config.php`
- Cek folder `tmp` di PHP dapat ditulis

---

## 📝 CATATAN PENTING

1. **Database:** Pastikan semua SQL file sudah diimport
2. **Uploads:** Folder `assets/uploads/` harus writable
3. **Base URL:** Sesuaikan dengan path instalasi
4. **Password:** Default menggunakan NRP, disarankan diubah setelah login pertama

---

## 🔗 LINK PENTING

- **Aplikasi:** http://localhost/RISK/
- **Login:** http://localhost/RISK/pages/login.php
- **Dashboard:** http://localhost/RISK/pages/dashboard.php
- **phpMyAdmin:** http://localhost/phpmyadmin

---

**Aplikasi siap digunakan setelah setup database selesai!**

