# PRODUCTION DEPLOYMENT GUIDE
## Risk Assessment Objek Wisata - Polres Samosir

**Versi:** 1.0  
**Tanggal:** <?php echo date('d F Y'); ?>

---

## 📋 CHECKLIST SEBELUM DEPLOYMENT

### 1. **Konfigurasi Server** ✅
- [ ] PHP >= 7.4 terinstall
- [ ] MySQL/MariaDB terinstall
- [ ] Apache/Nginx dengan mod_rewrite enabled
- [ ] SSL Certificate terinstall (untuk HTTPS)
- [ ] Folder permissions sudah benar (755 untuk folder, 644 untuk file)

### 2. **Konfigurasi Database** ✅
- [ ] Buat database baru di server production
- [ ] Import `sql/database.sql`
- [ ] Import `sql/master_data.sql`
- [ ] Update `config/database.php` dengan credentials production
- [ ] Test koneksi database

### 3. **Konfigurasi Aplikasi** ✅
- [ ] Update `config/config.php` dengan BASE_URL production
- [ ] Disable error reporting (set ke 0)
- [ ] Enable error logging
- [ ] Update CORS policy di `api/api_base.php`
- [ ] Setup `.htaccess` untuk production

### 4. **Security** ✅
- [ ] Enable HTTPS (SSL Certificate)
- [ ] Update BASE_URL ke HTTPS
- [ ] Restrict CORS policy
- [ ] Setup session security
- [ ] Protect sensitive files (.htaccess)
- [ ] Change default admin password

### 5. **File Upload** ✅
- [ ] Buat folder `assets/uploads/` dengan permission 755
- [ ] Test upload file
- [ ] Setup backup untuk folder uploads

### 6. **Error Logging** ✅
- [ ] Buat folder `logs/` dengan permission 755
- [ ] Test error logging
- [ ] Setup log rotation

### 7. **Backup** ✅
- [ ] Setup backup database (cron job)
- [ ] Setup backup file uploads
- [ ] Test restore dari backup

---

## 🚀 LANGKAH DEPLOYMENT

### Step 1: Upload Files
```bash
# Upload semua file ke server (kecuali file development)
# Jangan upload:
# - .git folder
# - node_modules
# - vendor (jika belum install composer)
# - logs/* (jika ada)
# - *.md (dokumentasi, opsional)
```

### Step 2: Install Dependencies
```bash
# Install TCPDF via Composer (jika menggunakan Composer)
cd /path/to/RISK
composer install

# Atau download TCPDF manual ke vendor/tecnickcom/tcpdf/
```

### Step 3: Konfigurasi Database
```bash
# 1. Buat database
mysql -u root -p
CREATE DATABASE risk_assessment_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# 2. Import SQL
mysql -u root -p risk_assessment_db < sql/database.sql
mysql -u root -p risk_assessment_db < sql/master_data.sql

# 3. Update config/database.php dengan credentials production
```

### Step 4: Konfigurasi Aplikasi
```bash
# 1. Copy config production
cp config/config.production.php config/config.php
# Edit config.php dan sesuaikan BASE_URL

cp config/database.production.php config/database.php
# Edit database.php dan sesuaikan credentials

# 2. Copy .htaccess production
cp .htaccess.production .htaccess
# Edit .htaccess dan sesuaikan RewriteBase

# 3. Copy API base production
cp api/api_base.production.php api/api_base.php
# Edit api_base.php dan sesuaikan CORS policy

# 4. Include error handler
# Tambahkan di config/config.php:
# require_once __DIR__ . '/../includes/error_handler.php';
```

### Step 5: Setup Folders
```bash
# Buat folder yang diperlukan
mkdir -p assets/uploads
mkdir -p logs
chmod 755 assets/uploads
chmod 755 logs
chmod 644 assets/uploads/.htaccess  # jika ada
```

### Step 6: Test Aplikasi
```bash
# 1. Test koneksi database
# Buka: https://yourdomain.com/RISK/pages/login.php

# 2. Test login
# Login dengan credentials default

# 3. Test semua fitur utama
# - Dashboard
# - CRUD Objek Wisata
# - Form Penilaian
# - Upload File
# - Generate PDF
# - Export/Import
```

---

## 🔒 SECURITY HARDENING

### 1. **File Permissions**
```bash
# Set permission yang benar
find . -type d -exec chmod 755 {} \;
find . -type f -exec chmod 644 {} \;
chmod 755 assets/uploads
chmod 755 logs
```

### 2. **Protect Sensitive Files**
- Pastikan `.htaccess` sudah protect file sensitif
- Jangan commit credentials ke repository
- Gunakan environment variables jika memungkinkan

### 3. **HTTPS**
- Install SSL Certificate
- Redirect HTTP ke HTTPS
- Update BASE_URL ke HTTPS

### 4. **Session Security**
- Enable `session.cookie_httponly`
- Enable `session.cookie_secure` (untuk HTTPS)
- Enable `session.use_strict_mode`

---

## 📊 MONITORING

### 1. **Error Logging**
- Check `logs/error.log` secara berkala
- Setup log rotation
- Monitor error patterns

### 2. **Database Monitoring**
- Monitor database size
- Monitor query performance
- Setup slow query log

### 3. **Server Monitoring**
- Monitor disk space
- Monitor memory usage
- Monitor CPU usage

---

## 🔄 BACKUP

### 1. **Database Backup**
```bash
# Backup database (cron job)
0 2 * * * mysqldump -u db_user -pdb_password risk_assessment_db > /backup/db_$(date +\%Y\%m\%d).sql
```

### 2. **File Backup**
```bash
# Backup uploads (cron job)
0 3 * * * tar -czf /backup/uploads_$(date +\%Y\%m\%d).tar.gz assets/uploads/
```

### 3. **Full Backup**
```bash
# Backup seluruh aplikasi (weekly)
0 4 * * 0 tar -czf /backup/full_$(date +\%Y\%m\%d).tar.gz /path/to/RISK/
```

---

## 🐛 TROUBLESHOOTING

### Error: Database Connection Failed
- Check credentials di `config/database.php`
- Check MySQL service running
- Check firewall rules

### Error: Permission Denied
- Check folder permissions (755)
- Check file permissions (644)
- Check ownership (www-data atau apache)

### Error: 500 Internal Server Error
- Check `logs/error.log`
- Check PHP error log
- Check Apache/Nginx error log
- Check `.htaccess` syntax

### Error: File Upload Failed
- Check `assets/uploads/` permission (755)
- Check `upload_max_filesize` di php.ini
- Check `post_max_size` di php.ini

---

## ✅ POST-DEPLOYMENT CHECKLIST

- [ ] Aplikasi dapat diakses via HTTPS
- [ ] Login berfungsi
- [ ] Semua fitur utama berfungsi
- [ ] Upload file berfungsi
- [ ] Generate PDF berfungsi
- [ ] Export/Import berfungsi
- [ ] Error logging berfungsi
- [ ] Backup berfungsi
- [ ] Monitoring setup

---

## 📞 SUPPORT

Jika ada masalah saat deployment, check:
1. Error log: `logs/error.log`
2. PHP error log: `php_error.log`
3. Apache/Nginx error log
4. Database error log

---

**Catatan:** Pastikan semua konfigurasi production sudah dilakukan sebelum aplikasi digunakan secara real online.

