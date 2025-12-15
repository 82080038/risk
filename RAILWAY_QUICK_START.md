# 🚂 Quick Start: Deploy ke Railway.app

## ⚡ Langkah Super Cepat (5 Menit)

### 1. Login & Connect GitHub

1. **Buka**: https://railway.app
2. **Klik "Login"** → **"Deploy from GitHub repo"**
3. **Authorize Railway** untuk akses GitHub
4. **Pilih repository**: `82080038/risk`

### 2. Create New Project

1. **Klik "New Project"** (atau "New +")
2. **Pilih "Deploy from GitHub repo"**
3. **Pilih**: `82080038/risk`
4. Railway akan otomatis detect dan mulai deploy

### 3. Tambahkan MySQL Database

1. **Di project Railway**, klik **"New +"**
2. **Pilih "Database"** → **"Add MySQL"**
3. Railway akan otomatis:
   - Buat database MySQL
   - Generate connection string
   - Link ke web service
   - Set environment variables otomatis

### 4. Set Environment Variables

1. **Klik Web Service** → **"Variables"** tab
2. **Railway sudah otomatis menambahkan**:
   - `MYSQL_HOST`, `MYSQL_PORT`, `MYSQL_DATABASE`, `MYSQL_USER`, `MYSQL_PASSWORD`

3. **Tambahkan variables berikut**:
   ```
   BASE_URL=https://your-app-name.up.railway.app/
   APP_ENV=production
   ```

   **Catatan**: Ganti `your-app-name` dengan nama service Anda di Railway.

### 5. Update `config/database.php` untuk Railway

Karena file `config/database.php` tidak ter-commit (di-ignore), Anda perlu update manual:

**Opsi A: Via Railway Web Editor**
1. **Klik Web Service** → **"Source"** tab
2. **Edit file** `config/database.php`
3. **Pastikan kode ini ada di bagian atas** (setelah `<?php`):

```php
// Cek Railway MySQL variables (prioritas pertama)
if (getenv('MYSQL_HOST')) {
    // Railway MySQL format
    define('DB_HOST', getenv('MYSQL_HOST'));
    define('DB_PORT', getenv('MYSQL_PORT') ?: 3306);
    define('DB_USER', getenv('MYSQL_USER'));
    define('DB_PASS', getenv('MYSQL_PASSWORD'));
    define('DB_NAME', getenv('MYSQL_DATABASE'));
    define('DB_TYPE', 'mysql');
} elseif (getenv('DATABASE_URL')) {
    // ... (kode untuk Render)
}
```

**Opsi B: Update via GitHub (Recommended)**
1. File `config/database.php.example` sudah di-update dengan support Railway
2. Copy isi `config/database.php.example` ke `config/database.php` di Railway
3. Atau pull latest dari GitHub

### 6. Import Database

1. **Klik MySQL service** → **"Data"** tab
2. **Klik "Query"** untuk buka SQL console
3. **Import SQL files** satu per satu:
   - Copy isi `sql/database.sql` → Paste → Execute
   - Copy isi `sql/master_data.sql` → Paste → Execute
   - Copy isi `sql/data_personil.sql` → Paste → Execute

**Atau via Railway Connect**:
```bash
# Install Railway CLI (jika belum)
npm i -g @railway/cli

# Login
railway login

# Connect ke database
railway connect mysql

# Import SQL
mysql -u $MYSQL_USER -p$MYSQL_PASSWORD -h $MYSQL_HOST $MYSQL_DATABASE < sql/database.sql
```

### 7. Deploy & Test

1. **Railway akan otomatis deploy** setelah setup
2. **Tunggu build selesai** (2-5 menit)
3. **Buka URL** aplikasi (format: `https://your-app-name.up.railway.app`)
4. **Login** dengan:
   - Username: `admin`
   - Password: `admin123`

---

## ✅ Yang Sudah Disiapkan

- ✅ `Dockerfile` - Untuk PHP 8.1 + Apache
- ✅ `railway.json` - Konfigurasi Railway
- ✅ `config/database.php` - Support Railway MySQL (perlu update manual)
- ✅ `config/config.php` - Support environment variables
- ✅ `RAILWAY_DEPLOY.md` - Panduan lengkap

---

## 🎯 Tips

1. **Railway auto-deploy**: Setiap push ke GitHub akan otomatis deploy
2. **Free tier**: $5 credit gratis per bulan (cukup untuk testing)
3. **MySQL gratis**: Database MySQL tersedia di free tier
4. **Custom domain**: Bisa tambahkan domain sendiri (gratis)

---

## 🆘 Troubleshooting

### Error: Database Connection Failed
- **Solusi**: Pastikan `config/database.php` sudah di-update untuk membaca `MYSQL_*` variables
- Cek Variables tab di Railway, pastikan `MYSQL_*` variables ada

### Error: Build Failed
- **Solusi**: Cek build logs di Railway
- Pastikan `Dockerfile` ada dan benar

### Error: Port Not Found
- **Solusi**: Pastikan start command: `php -S 0.0.0.0:$PORT`
- Railway akan inject `$PORT` variable

---

**Selamat! Aplikasi Anda sekarang online di Railway! 🚂**

