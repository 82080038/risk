# 🚂 Panduan Deploy Aplikasi ke Railway.app

## 📋 Kelebihan Railway.app

- ✅ **Free tier**: $5 credit gratis per bulan (tanpa kartu kredit!)
- ✅ **MySQL support**: Database MySQL tersedia di free tier
- ✅ **Auto-deploy dari GitHub**: Otomatis deploy setiap push
- ✅ **Tidak perlu kartu kredit**: Untuk free tier
- ✅ **Simple setup**: Lebih mudah dari Render

---

## 🚀 Langkah-Langkah Deploy ke Railway

### 1. Login ke Railway

1. **Buka**: https://railway.app
2. **Klik "Login"** → **"Deploy from GitHub repo"**
3. **Authorize Railway** untuk akses GitHub Anda
4. **Pilih repository**: `82080038/risk`

### 2. Buat New Project

1. **Klik "New Project"** (atau "New +")
2. **Pilih "Deploy from GitHub repo"**
3. **Pilih repository**: `82080038/risk`
4. Railway akan otomatis detect aplikasi

### 3. Setup Web Service

Railway akan otomatis:
- Detect Dockerfile (jika ada)
- Atau detect PHP dan setup otomatis

**Jika Railway tidak auto-detect PHP**, kita perlu setup manual:

1. **Klik service** yang baru dibuat
2. **Klik "Settings"** tab
3. **Setup berikut**:
   - **Build Command**: `composer install --no-dev --optimize-autoloader`
   - **Start Command**: `php -S 0.0.0.0:$PORT`
   - **Healthcheck Path**: `/` (opsional)

### 4. Buat MySQL Database

1. **Di project Railway**, klik **"New +"**
2. **Pilih "Database"** → **"Add MySQL"**
3. **Railway akan otomatis**:
   - Buat database MySQL
   - Generate connection string
   - Link ke web service

### 5. Set Environment Variables

1. **Klik Web Service** → **"Variables"** tab
2. **Railway sudah otomatis menambahkan**:
   - `MYSQL_HOST`
   - `MYSQL_PORT`
   - `MYSQL_DATABASE`
   - `MYSQL_USER`
   - `MYSQL_PASSWORD`
   - `MYSQL_URL` (connection string)

3. **Tambahkan variables berikut**:
   ```
   BASE_URL=https://your-app-name.up.railway.app/
   APP_ENV=production
   PHP_VERSION=8.1
   ```

4. **Map database variables ke format aplikasi**:
   ```
   DB_HOST=${{MySQL.MYSQLHOST}}
   DB_PORT=${{MySQL.MYSQLPORT}}
   DB_USER=${{MySQL.MYSQLUSER}}
   DB_PASS=${{MySQL.MYSQLPASSWORD}}
   DB_NAME=${{MySQL.MYSQLDATABASE}}
   DB_TYPE=mysql
   ```

   **Atau lebih mudah**, edit `config/database.php` untuk membaca dari Railway variables.

### 6. Update Konfigurasi untuk Railway

Railway menggunakan format environment variables yang berbeda. Kita perlu update `config/database.php`:

```php
// Cek Railway MySQL variables
if (getenv('MYSQL_HOST')) {
    // Railway MySQL format
    define('DB_HOST', getenv('MYSQL_HOST'));
    define('DB_PORT', getenv('MYSQL_PORT') ?: 3306);
    define('DB_USER', getenv('MYSQL_USER'));
    define('DB_PASS', getenv('MYSQL_PASSWORD'));
    define('DB_NAME', getenv('MYSQL_DATABASE'));
    define('DB_TYPE', 'mysql');
} elseif (getenv('DATABASE_URL')) {
    // ... (kode yang sudah ada untuk Render)
}
```

### 7. Import Database Schema

Setelah database dibuat:

1. **Klik MySQL service** → **"Data"** tab
2. **Klik "Query"** atau gunakan **"Connect"** untuk akses via client
3. **Import SQL files**:
   - Copy isi `sql/database.sql`
   - Paste dan execute
   - Ulangi untuk `sql/master_data.sql` dan `sql/data_personil.sql`

**Atau via Railway CLI**:
```bash
railway connect mysql
mysql -u $MYSQL_USER -p$MYSQL_PASSWORD -h $MYSQL_HOST $MYSQL_DATABASE < sql/database.sql
```

### 8. Deploy

1. **Railway akan otomatis deploy** setelah setup
2. **Tunggu sampai build selesai** (2-5 menit)
3. **Cek logs** jika ada error
4. **Buka URL** yang diberikan Railway (format: `https://your-app-name.up.railway.app`)

### 9. Generate Domain (Opsional)

1. **Klik Web Service** → **"Settings"** tab
2. **Klik "Generate Domain"** untuk mendapatkan custom domain
3. **Atau tambahkan custom domain** Anda sendiri

---

## 🔧 Konfigurasi Khusus untuk Railway

### Update `config/database.php` untuk Railway

Kita perlu update file ini untuk support Railway MySQL variables. Mari saya update sekarang.

### File yang Perlu Diupdate

1. `config/database.php` - Support Railway MySQL variables
2. `config/config.php` - Sudah support environment variables
3. `Dockerfile` - Sudah ada (Railway akan gunakan jika ada)

---

## 📝 Checklist Deploy

- [ ] Login ke Railway dengan GitHub
- [ ] Create New Project dari GitHub repo
- [ ] Web Service sudah dibuat
- [ ] MySQL Database sudah dibuat dan di-link
- [ ] Environment variables sudah di-set
- [ ] `config/database.php` sudah di-update untuk Railway
- [ ] Database schema sudah di-import
- [ ] Deploy berhasil
- [ ] Aplikasi bisa diakses

---

## 🆘 Troubleshooting

### Error: Database Connection Failed
- **Penyebab**: Environment variables belum di-set atau salah
- **Solusi**: 
  - Cek Variables tab di Railway
  - Pastikan `DB_*` variables sudah di-map dari `MYSQL_*`
  - Test koneksi via Railway Connect

### Error: Build Failed
- **Penyebab**: Composer error atau PHP version
- **Solusi**: 
  - Cek build logs di Railway
  - Pastikan `composer.json` benar
  - Set `PHP_VERSION` environment variable

### Error: Port Already in Use
- **Penyebab**: Start command salah
- **Solusi**: 
  - Pastikan start command: `php -S 0.0.0.0:$PORT`
  - Railway akan inject `$PORT` variable

---

## 💰 Pricing Railway

- **Free Tier**: $5 credit gratis per bulan
- **Hobby Plan**: Mulai dari $5/bulan (jika melebihi free credit)
- **Tidak perlu kartu kredit** untuk free tier

---

**Selamat! Aplikasi Anda sekarang online di Railway! 🚂**

