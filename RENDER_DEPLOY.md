# 🚀 Panduan Deploy Aplikasi ke Render

## 📋 Prasyarat

- ✅ Akun GitHub sudah terhubung ke Render
- ✅ Repository aplikasi sudah di GitHub: https://github.com/82080038/risk
- ✅ Aplikasi sudah siap untuk production

---

## 🎯 Langkah-Langkah Deploy ke Render

### 1. Buat Web Service di Render

1. **Login ke Render Dashboard**: https://dashboard.render.com/
2. **Klik "New +"** (kanan atas)
3. **Pilih "Web Service"**
4. **Connect Repository**:
   - Pilih **"Connect GitHub"** (jika belum)
   - Pilih repository: **`82080038/risk`**
   - Klik **"Connect"**

### 2. Konfigurasi Web Service

Isi form dengan detail berikut:

#### Basic Settings
- **Name**: `risk-assessment-objek-wisata` (atau nama lain)
- **Region**: Pilih yang terdekat (misal: `Singapore` untuk Indonesia)
- **Branch**: `main`
- **Root Directory**: (biarkan kosong, atau `./` jika perlu)

#### Build & Deploy
- **Runtime**: `PHP`
- **Build Command**: 
  ```bash
  composer install --no-dev --optimize-autoloader
  ```
- **Start Command**: 
  ```bash
  php -S 0.0.0.0:$PORT -t .
  ```
  Atau jika menggunakan Apache:
  ```bash
  php -S 0.0.0.0:$PORT
  ```

#### Environment Variables
Klik **"Add Environment Variable"** dan tambahkan:

| Key | Value | Keterangan |
|-----|-------|------------|
| `PHP_VERSION` | `8.1` atau `8.2` | Versi PHP yang digunakan |
| `APP_ENV` | `production` | Environment aplikasi |

**JANGAN** tambahkan database credentials di sini (akan dibuat terpisah).

### 3. Buat Database di Render

**PENTING**: Render free tier menyediakan **PostgreSQL**, sedangkan aplikasi ini menggunakan **MySQL**. Ada 2 opsi:

#### Opsi A: Gunakan MySQL External (Direkomendasikan)

1. **Gunakan MySQL dari provider lain**:
   - **PlanetScale** (free tier tersedia): https://planetscale.com
   - **Railway MySQL** (free tier terbatas): https://railway.app
   - **Aiven MySQL** (free trial): https://aiven.io
   
2. **Atau gunakan Render MySQL** (jika tersedia di paid tier)

#### Opsi B: Konversi ke PostgreSQL (Kompleks)

Jika ingin tetap pakai Render free tier, perlu konversi schema MySQL ke PostgreSQL.

**Untuk tutorial ini, kita asumsikan menggunakan MySQL external atau Render MySQL.**

Jika menggunakan MySQL external:
1. **Dapatkan connection string** dari provider MySQL
2. **Set environment variables** di Render:
   - `DB_HOST`: Host MySQL
   - `DB_PORT`: 3306
   - `DB_USER`: Username MySQL
   - `DB_PASS`: Password MySQL
   - `DB_NAME`: Database name
   - `DB_TYPE`: mysql

Jika menggunakan Render PostgreSQL (perlu konversi):
1. **Klik "New +"** → **"PostgreSQL"**
2. **Isi form**:
   - **Name**: `risk-assessment-db`
   - **Database**: `risk_assessment_db`
   - **User**: (otomatis dibuat)
   - **Region**: Pilih sama dengan Web Service
   - **Plan**: **Free**
3. **Klik "Create Database"**
4. **Catat informasi koneksi**:
   - **Internal Database URL**: (format: `postgresql://user:pass@host:port/dbname`)
   - **Host, Port, Database, User, Password**: (akan muncul di dashboard)

### 4. Link Database ke Web Service

1. **Buka Web Service** yang sudah dibuat
2. **Klik "Environment"** tab
3. **Klik "Link Database"**
4. **Pilih database** yang sudah dibuat
5. **Render akan otomatis menambahkan environment variables**:
   - `DATABASE_URL` (internal)
   - `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASSWORD`

### 5. Update Konfigurasi Aplikasi untuk Render

Kita perlu membuat file konfigurasi khusus untuk Render.

#### A. Buat File `render.yaml` (Opsional)

Buat file `render.yaml` di root repository:

```yaml
services:
  - type: web
    name: risk-assessment-app
    env: php
    buildCommand: composer install --no-dev --optimize-autoloader
    startCommand: php -S 0.0.0.0:$PORT
    envVars:
      - key: APP_ENV
        value: production
      - key: PHP_VERSION
        value: 8.1

databases:
  - name: risk-assessment-db
    databaseName: risk_assessment_db
    user: risk_user
```

#### B. Update `config/database.php` untuk Render

Edit `config/database.php` untuk mendukung environment variables dari Render:

```php
<?php
/**
 * Konfigurasi Database
 * Risk Assessment Objek Wisata
 * Support untuk Render.com environment variables
 */

// Cek apakah di Render (ada DATABASE_URL)
if (getenv('DATABASE_URL')) {
    // Parse DATABASE_URL dari Render
    $url = parse_url(getenv('DATABASE_URL'));
    
    define('DB_HOST', $url['host'] ?? 'localhost');
    define('DB_PORT', $url['port'] ?? 5432); // PostgreSQL default port
    define('DB_USER', $url['user'] ?? '');
    define('DB_PASS', $url['pass'] ?? '');
    define('DB_NAME', ltrim($url['path'] ?? '', '/'));
} else {
    // Konfigurasi lokal/default
    define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
    define('DB_PORT', getenv('DB_PORT') ?: 3306); // MySQL default port
    define('DB_USER', getenv('DB_USER') ?: 'root');
    define('DB_PASS', getenv('DB_PASS') ?: '');
    define('DB_NAME', getenv('DB_NAME') ?: 'risk_assessment_db');
}

// Koneksi database
function getDBConnection() {
    // ... (kode yang sudah ada)
}
```

#### C. Update `config/config.php` untuk Render

Edit `config/config.php`:

```php
<?php
/**
 * Konfigurasi Aplikasi
 * Risk Assessment Objek Wisata
 */

// Base URL - ambil dari environment variable atau default
define('BASE_URL', getenv('BASE_URL') ?: 'http://localhost/RISK/');

// Path aplikasi
define('APP_PATH', __DIR__ . '/../');

// Session
session_start();

// Timezone
date_default_timezone_set('Asia/Jakarta');

// Error reporting - production mode
if (getenv('APP_ENV') === 'production') {
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', APP_PATH . 'logs/error.log');
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

// Upload settings
define('UPLOAD_PATH', APP_PATH . 'assets/uploads/');
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx']);

// Pagination
define('ITEMS_PER_PAGE', 10);

// Tahun default untuk laporan
define('DEFAULT_YEAR', date('Y'));
?>
```

### 6. Buat File `.htaccess` untuk Render (Jika Perlu)

Jika Render menggunakan Apache, pastikan `.htaccess` sudah ada. Jika menggunakan PHP built-in server, mungkin tidak perlu.

### 7. Buat File `index.php` di Root (Jika Belum Ada)

Pastikan ada file `index.php` di root yang redirect ke halaman utama:

```php
<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/functions.php';

// Redirect ke dashboard jika sudah login, atau login jika belum
if (isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'pages/dashboard.php');
} else {
    header('Location: ' . BASE_URL . 'pages/login.php');
}
exit;
```

### 8. Setup Database Schema

Setelah database dibuat, kita perlu import schema:

#### Opsi A: Via Render Dashboard (Jika Support)
- Buka database di Render Dashboard
- Cari opsi "Connect" atau "Query"
- Import SQL files

#### Opsi B: Via Script PHP
Buat file `setup_database_render.php`:

```php
<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

$conn = getDBConnection();

// Baca dan execute SQL files
$sql_files = [
    'sql/database.sql',
    'sql/master_data.sql',
    'sql/data_personil.sql'
];

foreach ($sql_files as $file) {
    if (file_exists($file)) {
        $sql = file_get_contents($file);
        // Split by semicolon and execute each statement
        $statements = array_filter(array_map('trim', explode(';', $sql)));
        foreach ($statements as $statement) {
            if (!empty($statement)) {
                $conn->query($statement);
            }
        }
        echo "Executed: $file\n";
    }
}

$conn->close();
echo "Database setup complete!\n";
```

Jalankan via Render Shell atau deploy sementara.

### 9. Deploy ke Render

1. **Klik "Create Web Service"** di Render
2. **Render akan otomatis**:
   - Clone repository dari GitHub
   - Run build command
   - Deploy aplikasi
3. **Tunggu sampai deploy selesai** (biasanya 2-5 menit)
4. **Cek logs** jika ada error

### 10. Set Environment Variables di Render

Setelah deploy, pastikan environment variables sudah benar:

1. **Buka Web Service** → **"Environment"** tab
2. **Tambahkan/Edit variables**:

| Key | Value | Contoh |
|-----|-------|--------|
| `BASE_URL` | URL aplikasi di Render | `https://risk-assessment.onrender.com/` |
| `APP_ENV` | `production` | `production` |
| `PHP_VERSION` | `8.1` atau `8.2` | `8.1` |

**Database variables** sudah otomatis ditambahkan saat link database.

### 11. Setup Database Schema

Setelah deploy pertama kali:

1. **Buka Web Service** → **"Shell"** tab
2. **Jalankan command**:
   ```bash
   php setup_database_render.php
   ```
   Atau import SQL files via database dashboard.

### 12. Set Permissions (Jika Perlu)

Via Render Shell:
```bash
chmod -R 755 assets/uploads/
mkdir -p logs
chmod -R 755 logs/
```

---

## 🔧 Konfigurasi Khusus untuk Render

### 1. Update `composer.json` (Jika Perlu)

Pastikan `composer.json` sudah benar:

```json
{
    "require": {
        "php": ">=7.4",
        "tecnickcom/tcpdf": "^6.6"
    }
}
```

### 2. Buat File `.renderignore` (Opsional)

Untuk exclude file yang tidak perlu di-deploy:

```
.git/
.github/
.gitignore
README.md
*.md
docs/
tools/
.env
.env.*
config/database.php
config/config.php
!config/database.php.example
!config/config.php.example
```

### 3. Update `.gitignore`

Pastikan file sensitif tidak ter-commit:

```
config/database.php
config/config.php
.env
.env.*
logs/
assets/uploads/*
!assets/uploads/.gitkeep
```

---

## 🗄️ Database: MySQL vs PostgreSQL

### Opsi 1: Gunakan PostgreSQL (Free Tier)

Render free tier menyediakan PostgreSQL. Perlu konversi schema:

1. **Install converter tool** atau
2. **Manual convert** SQL syntax MySQL ke PostgreSQL
3. **Update `config/database.php`** untuk PostgreSQL

### Opsi 2: Gunakan MySQL (Paid)

Jika perlu MySQL, upgrade ke paid plan atau gunakan external MySQL service.

### Opsi 3: Gunakan SQLite (Sederhana)

Untuk testing, bisa gunakan SQLite (tidak perlu setup database terpisah).

---

## 📝 Checklist Deploy

- [ ] Repository sudah di GitHub
- [ ] Web Service sudah dibuat di Render
- [ ] Database sudah dibuat di Render
- [ ] Database sudah di-link ke Web Service
- [ ] Environment variables sudah di-set
- [ ] `config/database.php` sudah di-update untuk Render
- [ ] `config/config.php` sudah di-update untuk Render
- [ ] Build command sudah benar
- [ ] Start command sudah benar
- [ ] Database schema sudah di-import
- [ ] Permissions sudah di-set
- [ ] Aplikasi sudah bisa diakses

---

## 🆘 Troubleshooting

### Error: Database Connection Failed
- **Penyebab**: Database belum di-link atau credentials salah
- **Solusi**: 
  - Pastikan database sudah di-link ke Web Service
  - Cek environment variables `DATABASE_URL` atau `DB_*`
  - Test koneksi via Render Shell

### Error: Build Failed
- **Penyebab**: Composer error atau PHP version mismatch
- **Solusi**: 
  - Cek `composer.json`
  - Set `PHP_VERSION` environment variable
  - Cek build logs

### Error: 500 Internal Server Error
- **Penyebab**: PHP error atau permission issue
- **Solusi**: 
  - Cek logs di Render Dashboard
  - Enable error reporting sementara untuk debug
  - Cek file permissions

### Error: File Upload Failed
- **Penyebab**: Folder tidak writable
- **Solusi**: 
  - Set permission via Shell: `chmod -R 755 assets/uploads/`
  - Pastikan folder ada dan writable

---

## 🎯 Setelah Deploy Berhasil

1. **Test aplikasi** di URL Render (misal: `https://risk-assessment.onrender.com`)
2. **Login** dengan kredensial default
3. **Test semua fitur**:
   - Dashboard
   - CRUD Objek Wisata
   - Form Penilaian
   - Laporan PDF
   - Export/Import

---

## 📞 Support

Jika mengalami masalah:
1. Cek logs di Render Dashboard
2. Cek environment variables
3. Test via Render Shell
4. Hubungi Render support

---

**Selamat! Aplikasi Anda sekarang online di Render! 🎉**

