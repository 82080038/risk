# 🚀 Panduan Deployment Aplikasi Risk Assessment Objek Wisata

## 📋 Daftar Isi
1. [Persyaratan Server](#persyaratan-server)
2. [Persiapan Upload ke GitHub](#persiapan-upload-ke-github)
3. [Instalasi di Server Online](#instalasi-di-server-online)
4. [Konfigurasi Database Online](#konfigurasi-database-online)
5. [Konfigurasi Aplikasi Online](#konfigurasi-aplikasi-online)
6. [Troubleshooting](#troubleshooting)

---

## 📦 Persyaratan Server

### Minimum Requirements
- **PHP**: 7.4 atau lebih tinggi (disarankan PHP 8.0+)
- **MySQL/MariaDB**: 5.7+ atau MariaDB 10.3+
- **Web Server**: Apache 2.4+ atau Nginx 1.18+
- **PHP Extensions**:
  - `mysqli` (untuk koneksi database)
  - `gd` (untuk validasi gambar)
  - `mbstring` (untuk encoding UTF-8)
  - `zip` (untuk backup/restore)
  - `fileinfo` (untuk validasi file upload)
- **Storage**: Minimal 100MB (untuk upload file)
- **Memory**: Minimal 128MB PHP memory limit

### Recommended Requirements
- **PHP**: 8.1 atau lebih tinggi
- **MySQL/MariaDB**: 8.0+ atau MariaDB 10.6+
- **Memory**: 256MB+ PHP memory limit
- **Storage**: 500MB+ (untuk file upload dan backup)

---

## 📤 Persiapan Upload ke GitHub

### 1. Inisialisasi Git Repository

```bash
# Masuk ke folder aplikasi
cd E:\xampp\htdocs\RISK

# Inisialisasi git repository
git init

# Tambahkan semua file
git add .

# Commit pertama
git commit -m "Initial commit: Risk Assessment Objek Wisata v1.0"
```

### 2. Buat Repository di GitHub

1. Login ke GitHub: https://github.com/82080038
2. Klik **"New repository"** atau **"+"** → **"New repository"**
3. Isi:
   - **Repository name**: `risk-assessment-objek-wisata` (atau nama lain)
   - **Description**: "Aplikasi Penilaian Risiko Objek Wisata - Polres Samosir"
   - **Visibility**: Public atau Private (sesuai kebutuhan)
   - **JANGAN** centang "Initialize with README" (karena sudah ada)
4. Klik **"Create repository"**

### 3. Push ke GitHub

```bash
# Tambahkan remote repository
git remote add origin https://github.com/82080038/risk-assessment-objek-wisata.git

# Atau jika menggunakan SSH:
# git remote add origin git@github.com:82080038/risk-assessment-objek-wisata.git

# Push ke GitHub
git branch -M main
git push -u origin main
```

**Catatan**: Jika diminta autentikasi:
- Gunakan **Personal Access Token** (bukan password)
- Buat token di: GitHub → Settings → Developer settings → Personal access tokens → Tokens (classic)
- Scope: `repo` (full control)

---

## 🌐 Instalasi di Server Online

### Opsi 1: Shared Hosting (cPanel, dll)

#### Langkah 1: Upload File
1. Download repository dari GitHub sebagai ZIP
2. Extract file
3. Upload semua file ke folder `public_html` atau `www` via FTP/cPanel File Manager
4. Pastikan struktur folder tetap sama

#### Langkah 2: Setup Konfigurasi
1. Copy `config/database.php.example` menjadi `config/database.php`
2. Edit `config/database.php` dengan kredensial database dari hosting
3. Copy `config/config.php.example` menjadi `config/config.php`
4. Edit `config/config.php` dan ubah `BASE_URL` menjadi domain Anda:
   ```php
   define('BASE_URL', 'https://yourdomain.com/');
   ```

#### Langkah 3: Setup Database
1. Buat database via cPanel → MySQL Databases
2. Import file SQL:
   - `sql/database.sql` (struktur database)
   - `sql/master_data.sql` (data master)
   - `sql/data_personil.sql` (data personil default)
3. Atau jalankan via phpMyAdmin → Import

#### Langkah 4: Set Permissions
```bash
# Set folder uploads writable
chmod 755 assets/uploads/
chmod 644 config/database.php
chmod 644 config/config.php
```

### Opsi 2: VPS/Cloud Server (Ubuntu/Debian)

#### Langkah 1: Install Dependencies
```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install Apache, PHP, MySQL
sudo apt install apache2 mysql-server php php-mysql php-gd php-mbstring php-zip php-xml -y

# Enable Apache modules
sudo a2enmod rewrite
sudo systemctl restart apache2
```

#### Langkah 2: Clone Repository
```bash
cd /var/www/html
sudo git clone https://github.com/82080038/risk-assessment-objek-wisata.git risk
cd risk
```

#### Langkah 3: Setup Database
```bash
# Login MySQL
sudo mysql -u root -p

# Buat database dan user
CREATE DATABASE risk_assessment_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'risk_user'@'localhost' IDENTIFIED BY 'strong_password_here';
GRANT ALL PRIVILEGES ON risk_assessment_db.* TO 'risk_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# Import database
mysql -u risk_user -p risk_assessment_db < sql/database.sql
mysql -u risk_user -p risk_assessment_db < sql/master_data.sql
mysql -u risk_user -p risk_assessment_db < sql/data_personil.sql
```

#### Langkah 4: Konfigurasi
```bash
# Copy config template
cp config/database.php.example config/database.php
cp config/config.php.example config/config.php

# Edit config
nano config/database.php
# Isi dengan: DB_USER='risk_user', DB_PASS='strong_password_here'

nano config/config.php
# Ubah BASE_URL menjadi: https://yourdomain.com/
```

#### Langkah 5: Set Permissions
```bash
# Set ownership
sudo chown -R www-data:www-data /var/www/html/risk

# Set permissions
sudo chmod -R 755 /var/www/html/risk
sudo chmod -R 777 /var/www/html/risk/assets/uploads
```

#### Langkah 6: Setup Apache Virtual Host
```bash
sudo nano /etc/apache2/sites-available/risk.conf
```

Isi dengan:
```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    ServerAlias www.yourdomain.com
    DocumentRoot /var/www/html/risk
    
    <Directory /var/www/html/risk>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/risk_error.log
    CustomLog ${APACHE_LOG_DIR}/risk_access.log combined
</VirtualHost>
```

Enable site:
```bash
sudo a2ensite risk.conf
sudo systemctl reload apache2
```

---

## 🗄️ Konfigurasi Database Online

### 1. Buat Database
- **Nama Database**: `risk_assessment_db` (atau sesuai kebutuhan)
- **Charset**: `utf8mb4`
- **Collation**: `utf8mb4_unicode_ci`

### 2. Import SQL Files
Urutan import:
1. `sql/database.sql` - Struktur database
2. `sql/master_data.sql` - Data master (aspek, elemen, kriteria)
3. `sql/data_personil.sql` - Data personil default
4. `sql/data_objek_wisata.sql` - Data objek wisata (opsional)

### 3. Update Kredensial
Edit `config/database.php`:
```php
define('DB_HOST', 'localhost'); // atau IP database server
define('DB_USER', 'username_database');
define('DB_PASS', 'password_database');
define('DB_NAME', 'risk_assessment_db');
```

---

## ⚙️ Konfigurasi Aplikasi Online

### 1. Update BASE_URL
Edit `config/config.php`:
```php
// Untuk production
define('BASE_URL', 'https://yourdomain.com/');

// Atau dengan subfolder
define('BASE_URL', 'https://yourdomain.com/risk/');
```

### 2. Enable Error Logging (Production)
Edit `config/config.php`:
```php
// Disable error display
error_reporting(0);
ini_set('display_errors', 0);

// Enable error logging
ini_set('log_errors', 1);
ini_set('error_log', APP_PATH . 'logs/error.log');
```

### 3. Setup Folder Logs
```bash
mkdir logs
chmod 755 logs
```

### 4. Install TCPDF (jika belum)
```bash
# Via Composer
composer install

# Atau manual: download dan extract ke vendor/tecnickcom/tcpdf/
```

### 5. Setup SSL/HTTPS (Recommended)
- Install SSL certificate (Let's Encrypt gratis)
- Update `BASE_URL` menjadi `https://`
- Redirect HTTP ke HTTPS via `.htaccess`

---

## 🔒 Security Checklist untuk Production

- [ ] Ubah password default admin
- [ ] Enable HTTPS/SSL
- [ ] Set `display_errors = 0` di `config.php`
- [ ] Set proper file permissions (755 untuk folder, 644 untuk file)
- [ ] Backup database secara berkala
- [ ] Update PHP ke versi terbaru
- [ ] Enable firewall (jika VPS)
- [ ] Setup `.htaccess` untuk security headers
- [ ] Disable directory listing
- [ ] Setup rate limiting untuk API

---

## 🐛 Troubleshooting

### Error: Database Connection Failed
- **Penyebab**: Kredensial database salah atau database tidak ada
- **Solusi**: 
  - Cek `config/database.php`
  - Pastikan database sudah dibuat
  - Test koneksi via phpMyAdmin

### Error: Permission Denied
- **Penyebab**: Folder tidak writable
- **Solusi**: 
  ```bash
  chmod -R 755 assets/uploads/
  chmod -R 755 logs/
  ```

### Error: TCPDF Not Found
- **Penyebab**: Library TCPDF belum terinstall
- **Solusi**: 
  ```bash
  composer install
  # Atau download manual dari: https://github.com/tecnickcom/TCPDF
  ```

### Error: Session Cannot Start
- **Penyebab**: Folder session tidak writable
- **Solusi**: 
  ```bash
  # Cek session.save_path di php.ini
  # Pastikan folder writable
  chmod 777 /tmp  # atau folder session yang ditentukan
  ```

### Error: File Upload Failed
- **Penyebab**: 
  - Folder uploads tidak writable
  - `upload_max_filesize` terlalu kecil
  - `post_max_size` terlalu kecil
- **Solusi**: 
  ```bash
  chmod 777 assets/uploads/
  # Edit php.ini:
  upload_max_filesize = 10M
  post_max_size = 10M
  ```

### Error: 404 Not Found
- **Penyebab**: 
  - `.htaccess` tidak aktif
  - `mod_rewrite` tidak enabled
  - BASE_URL salah
- **Solusi**: 
  ```bash
  # Enable mod_rewrite
  sudo a2enmod rewrite
  sudo systemctl restart apache2
  
  # Cek .htaccess ada dan readable
  ```

---

## 📞 Support

Jika mengalami masalah deployment, silakan:
1. Cek log error di `logs/error.log`
2. Cek dokumentasi di `docs/`
3. Hubungi developer: AIPDA PATRI SIHALOHO, S.H., CPM - 081265511982

---

## ✅ Checklist Deployment

- [ ] Repository sudah di-upload ke GitHub
- [ ] File sudah di-upload ke server
- [ ] Database sudah dibuat dan di-import
- [ ] `config/database.php` sudah dikonfigurasi
- [ ] `config/config.php` sudah dikonfigurasi (BASE_URL)
- [ ] Folder `assets/uploads/` writable
- [ ] TCPDF sudah terinstall
- [ ] SSL/HTTPS sudah setup (recommended)
- [ ] Error logging sudah diaktifkan
- [ ] Password default sudah diubah
- [ ] Aplikasi sudah ditest di production

---

**Selamat! Aplikasi Anda siap digunakan secara online! 🎉**

