# 🌐 Platform Hosting Gratis untuk Aplikasi Risk Assessment

## 📋 Daftar Platform Hosting Gratis

### ✅ Rekomendasi Terbaik

#### 1. **InfinityFree** ⭐ (Paling Direkomendasikan)
- **URL**: https://www.infinityfree.net
- **Fitur**:
  - ✅ PHP 8.3 (terbaru!)
  - ✅ MySQL 8.0 / MariaDB 11.4 (400 MB)
  - ✅ Storage: 5 GB
  - ✅ Bandwidth: Unlimited
  - ✅ Subdomain gratis: `.epizy.com` atau `.rf.gd`
  - ✅ Custom domain support
  - ✅ cPanel access
  - ✅ FTP access
  - ✅ SSL/HTTPS gratis
  - ✅ No ads (jika menggunakan custom domain)
  - ⚠️ **Auto-deploy dari GitHub**: Tidak langsung, tapi bisa via GitHub Actions + FTP
- **Keterbatasan**:
  - ⚠️ Inactive account akan dihapus setelah 30 hari tidak aktif
  - ⚠️ CPU limit (untuk aplikasi ringan seperti ini masih cukup)
  - ⚠️ Tidak support Git/SSH langsung (harus via FTP)
- **Cara Setup**:
  1. Daftar di https://www.infinityfree.net
  2. Buat hosting account
  3. Upload file via FTP atau File Manager
  4. Buat database MySQL via cPanel
  5. Import SQL files
  6. Update `config/database.php` dan `config/config.php`
- **Auto-Deploy dari GitHub**: 
  - Lihat panduan lengkap di **[INFINITYFREE_GITHUB_DEPLOY.md](INFINITYFREE_GITHUB_DEPLOY.md)**
  - Menggunakan GitHub Actions + FTP untuk auto-deploy otomatis

#### 2. **000webhost** ⭐
- **URL**: https://www.000webhost.com
- **Fitur**:
  - ✅ PHP 7.4 - 8.1
  - ✅ MySQL Database (300 MB)
  - ✅ Storage: 3 GB
  - ✅ Bandwidth: Unlimited
  - ✅ Subdomain gratis: `.000webhostapp.com`
  - ✅ Custom domain support
  - ✅ cPanel access
  - ✅ FTP access
  - ✅ SSL/HTTPS gratis
- **Keterbatasan**:
  - ⚠️ Account akan dihapus jika tidak login selama 30 hari
  - ⚠️ CPU limit
- **Cara Setup**: Sama seperti InfinityFree

#### 3. **Freehostia** ⭐
- **URL**: https://www.freehostia.com
- **Fitur**:
  - ✅ PHP 7.4+
  - ✅ MySQL Database (250 MB)
  - ✅ Storage: 250 MB
  - ✅ Bandwidth: 6 GB/bulan
  - ✅ Subdomain gratis: `.freehostia.com`
  - ✅ Custom domain support
  - ✅ cPanel access
  - ✅ FTP access
  - ✅ SSL/HTTPS gratis
- **Keterbatasan**:
  - ⚠️ Storage lebih kecil (250 MB)
  - ⚠️ Bandwidth terbatas (6 GB/bulan)
- **Cara Setup**: Sama seperti InfinityFree

---

### 🚀 Platform Modern (Dengan Docker/Container)

#### 4. **Render** ⭐⭐⭐
- **URL**: https://render.com
- **Fitur**:
  - ✅ Free tier untuk web service
  - ✅ PostgreSQL gratis (bisa konversi MySQL ke PostgreSQL)
  - ✅ Auto-deploy dari GitHub
  - ✅ SSL/HTTPS otomatis
  - ✅ Custom domain support
  - ✅ Subdomain gratis: `.onrender.com`
- **Keterbatasan**:
  - ⚠️ Service sleep setelah 15 menit tidak aktif (free tier)
  - ⚠️ Perlu konfigurasi khusus untuk PHP
  - ⚠️ Database PostgreSQL (bukan MySQL, perlu konversi)
- **Cara Setup**:
  1. Push aplikasi ke GitHub
  2. Connect GitHub ke Render
  3. Setup build command dan start command
  4. Setup database PostgreSQL
  5. Konversi schema MySQL ke PostgreSQL

#### 5. **Railway** ⭐⭐
- **URL**: https://railway.app
- **Fitur**:
  - ✅ Free tier: $5 credit/bulan
  - ✅ Auto-deploy dari GitHub
  - ✅ MySQL/PostgreSQL support
  - ✅ SSL/HTTPS otomatis
  - ✅ Custom domain support
- **Keterbatasan**:
  - ⚠️ Free credit terbatas ($5/bulan)
  - ⚠️ Perlu konfigurasi untuk PHP
- **Cara Setup**: Mirip dengan Render

---

### 📊 Perbandingan Platform

| Platform | PHP | MySQL | Storage | Bandwidth | SSL | Custom Domain | Auto-Deploy |
|----------|-----|-------|---------|-----------|-----|---------------|-------------|
| **InfinityFree** | ✅ | ✅ | 5 GB | Unlimited | ✅ | ✅ | ❌ |
| **000webhost** | ✅ | ✅ | 3 GB | Unlimited | ✅ | ✅ | ❌ |
| **Freehostia** | ✅ | ✅ | 250 MB | 6 GB/bulan | ✅ | ✅ | ❌ |
| **Render** | ⚠️ | ❌ (PostgreSQL) | - | - | ✅ | ✅ | ✅ |
| **Railway** | ⚠️ | ✅ | - | - | ✅ | ✅ | ✅ |

**Legenda**:
- ✅ = Fully supported
- ⚠️ = Perlu konfigurasi khusus
- ❌ = Tidak supported

---

## 🎯 Rekomendasi untuk Aplikasi Ini

### ⭐ Pilihan Terbaik: InfinityFree (100% GRATIS, TIDAK Perlu Kartu Kredit)

**Alasan**:
- ✅ **100% GRATIS** - Tidak perlu kartu kredit sama sekali
- ✅ Setup sangat mudah (seperti shared hosting biasa)
- ✅ Support PHP 8.3 dan MySQL langsung
- ✅ Storage 5 GB dan Database 400 MB (cukup untuk aplikasi ini)
- ✅ cPanel untuk manajemen file dan database
- ✅ SSL/HTTPS gratis
- ✅ Custom domain support

**Keterbatasan**:
- ⚠️ Harus login minimal 1x per bulan (untuk keep account active)
- ⚠️ CPU limit (tapi cukup untuk aplikasi ringan seperti ini)

### Alternatif: 000webhost atau Freehostia
- Juga 100% gratis tanpa kartu kredit
- Tapi storage/database lebih kecil

### ⚠️ Platform Berbayar (Setelah Free Tier)
- **Railway**: $5 credit gratis/bulan, lalu bayar
- **Render**: Free tier terbatas, perlu kartu kredit

**Lihat juga**: [HOSTING_GRATIS_TANPA_KARTU.md](HOSTING_GRATIS_TANPA_KARTU.md) untuk daftar lengkap platform gratis tanpa kartu kredit.

---

## 📝 Panduan Setup di InfinityFree (Rekomendasi)

### Langkah 1: Daftar Account
1. Buka https://www.infinityfree.net
2. Klik **"Sign Up"**
3. Isi form registrasi
4. Verifikasi email

### Langkah 2: Buat Hosting Account
1. Login ke InfinityFree
2. Klik **"Create Account"** atau **"Add Website"**
3. Pilih **"Free Hosting"**
4. Isi:
   - **Domain**: Pilih subdomain (misal: `risk-assessment.epizy.com`) atau masukkan custom domain
   - **Username**: Pilih username untuk cPanel
   - **Password**: Buat password untuk cPanel
5. Klik **"Create Account"**

### Langkah 3: Setup Database
1. Login ke **cPanel** (link ada di dashboard InfinityFree)
2. Cari **"MySQL Databases"**
3. Buat database baru:
   - **Database Name**: `risk_assessment_db` (atau sesuai kebutuhan)
   - Klik **"Create Database"**
4. Buat user database:
   - **Username**: Buat username
   - **Password**: Buat password kuat
   - Klik **"Create User"**
5. **Add User to Database**:
   - Pilih user dan database
   - Centang **"ALL PRIVILEGES"**
   - Klik **"Make Changes"**
6. **Catat informasi**:
   - Database Host: `sqlXXX.epizy.com` (atau `localhost`)
   - Database Name: `epiz_XXXXXX_risk_assessment_db`
   - Database User: `epiz_XXXXXX_username`
   - Database Password: (password yang dibuat)

### Langkah 4: Upload File
**Opsi A: Via File Manager (cPanel)**
1. Login ke cPanel
2. Buka **"File Manager"**
3. Masuk ke folder `public_html` atau `htdocs`
4. Upload semua file aplikasi (bisa zip dulu, lalu extract di server)

**Opsi B: Via FTP**
1. Download FTP client (FileZilla, WinSCP, dll)
2. Login dengan:
   - **Host**: `ftp.epizy.com` (atau sesuai info dari InfinityFree)
   - **Username**: (username cPanel)
   - **Password**: (password cPanel)
   - **Port**: 21
3. Upload semua file ke folder `public_html`

### Langkah 5: Import Database
1. Login ke cPanel
2. Buka **"phpMyAdmin"**
3. Pilih database yang sudah dibuat
4. Klik **"Import"**
5. Upload file SQL:
   - `sql/database.sql` (struktur)
   - `sql/master_data.sql` (data master)
   - `sql/data_personil.sql` (data personil)
6. Klik **"Go"**

### Langkah 6: Konfigurasi Aplikasi
1. Via File Manager atau FTP, edit file:
   - `config/database.php`
2. Update dengan kredensial database dari InfinityFree:
   ```php
   define('DB_HOST', 'sqlXXX.epizy.com'); // atau localhost
   define('DB_USER', 'epiz_XXXXXX_username');
   define('DB_PASS', 'password_database');
   define('DB_NAME', 'epiz_XXXXXX_risk_assessment_db');
   ```
3. Edit `config/config.php`:
   ```php
   define('BASE_URL', 'https://risk-assessment.epizy.com/');
   ```

### Langkah 7: Set Permissions
1. Via File Manager, set permissions:
   - Folder `assets/uploads/`: **755** atau **777**
   - File `config/database.php`: **644**

### Langkah 8: Test Aplikasi
1. Buka: `https://risk-assessment.epizy.com/`
2. Test login dengan kredensial default
3. Pastikan semua fitur berfungsi

---

## 🔧 Konfigurasi Khusus untuk Hosting Gratis

### 1. Update .htaccess untuk Subfolder (Jika Perlu)
Jika aplikasi tidak di root, tambahkan di `.htaccess`:
```apache
RewriteBase /subfolder/
```

### 2. Update BASE_URL
Pastikan `BASE_URL` di `config/config.php` sesuai dengan domain hosting:
```php
define('BASE_URL', 'https://yourdomain.epizy.com/');
```

### 3. Set Error Reporting untuk Production
Edit `config/config.php`:
```php
// Untuk production
error_reporting(0);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', APP_PATH . 'logs/error.log');
```

### 4. Setup Folder Logs
Buat folder `logs/` dan set permission **755** atau **777**

---

## ⚠️ Catatan Penting untuk Hosting Gratis

### 1. Backup Rutin
- Hosting gratis bisa dihapus jika tidak aktif
- Lakukan backup database dan file secara rutin
- Gunakan script `tools/backup_database.php` dan `tools/backup_uploads.php`

### 2. Keep Account Active
- Login ke hosting minimal 1x per bulan
- Atau setup cron job untuk keep-alive (jika memungkinkan)

### 3. Resource Limits
- CPU dan memory terbatas
- Untuk aplikasi ringan seperti ini masih cukup
- Jika traffic tinggi, pertimbangkan upgrade ke paid hosting

### 4. Database Size
- Monitor ukuran database
- Hapus data lama jika perlu
- Export data penting secara berkala

---

## 🆚 InfinityFree vs 000webhost

| Aspek | InfinityFree | 000webhost |
|-------|--------------|------------|
| Storage | 5 GB | 3 GB |
| Database | 400 MB | 300 MB |
| Bandwidth | Unlimited | Unlimited |
| SSL | ✅ Gratis | ✅ Gratis |
| Custom Domain | ✅ | ✅ |
| Inactive Policy | 30 hari | 30 hari |
| **Rekomendasi** | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐ |

**Kesimpulan**: InfinityFree lebih direkomendasikan karena storage lebih besar.

---

## 📞 Support

Jika mengalami masalah saat setup:
1. Cek dokumentasi hosting provider
2. Hubungi support hosting (jika tersedia)
3. Cek `DEPLOYMENT.md` untuk troubleshooting umum

---

## ✅ Checklist Setup Hosting Gratis

- [ ] Account hosting sudah dibuat
- [ ] Database MySQL sudah dibuat
- [ ] File aplikasi sudah di-upload
- [ ] SQL files sudah di-import
- [ ] `config/database.php` sudah dikonfigurasi
- [ ] `config/config.php` sudah dikonfigurasi (BASE_URL)
- [ ] Folder `assets/uploads/` sudah set permission (755/777)
- [ ] Aplikasi sudah ditest dan berfungsi
- [ ] SSL/HTTPS sudah aktif
- [ ] Backup sudah disiapkan

---

**Selamat! Aplikasi Anda sekarang online secara gratis! 🎉**

