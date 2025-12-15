# 🚀 Quick Start: Deploy ke Render (Langkah Cepat)

## ⚡ Langkah Super Cepat

### 1. Di Render Dashboard

1. **Klik "New +"** → **"Web Service"**
2. **Connect GitHub** → Pilih repository: `82080038/risk`
3. **Isi form**:
   - **Name**: `risk-assessment`
   - **Runtime**: `PHP`
   - **Build Command**: `composer install --no-dev --optimize-autoloader`
   - **Start Command**: `php -S 0.0.0.0:$PORT`
4. **Klik "Create Web Service"**

### 2. Setup Database

**Opsi Terbaik: Gunakan MySQL External**

1. **Daftar di PlanetScale** (gratis): https://planetscale.com
2. **Buat database** baru
3. **Dapatkan connection string**
4. **Di Render Dashboard** → Web Service → **Environment** tab
5. **Tambahkan environment variables**:
   ```
   DB_HOST=your-mysql-host.planetscale.com
   DB_PORT=3306
   DB_USER=your-username
   DB_PASS=your-password
   DB_NAME=risk_assessment_db
   DB_TYPE=mysql
   BASE_URL=https://risk-assessment.onrender.com/
   APP_ENV=production
   ```

### 3. Import Database

1. **Buka PlanetScale Dashboard** → Database → **Console**
2. **Import SQL files**:
   - Copy isi `sql/database.sql`
   - Paste dan execute di console
   - Ulangi untuk `sql/master_data.sql` dan `sql/data_personil.sql`

### 4. Deploy

1. **Render akan otomatis deploy** setelah Anda create Web Service
2. **Tunggu sampai selesai** (2-5 menit)
3. **Buka URL** yang diberikan Render (misal: `https://risk-assessment.onrender.com`)

### 5. Test

1. **Buka aplikasi** di URL Render
2. **Login** dengan kredensial default:
   - Username: `admin`
   - Password: `admin123`
3. **Test semua fitur**

---

## 🎯 Yang Sudah Disiapkan

✅ `config/database.php` - Support environment variables  
✅ `config/config.php` - Support production mode  
✅ `render.yaml` - Konfigurasi Render  
✅ `index.php` - Entry point sudah ada  

---

## 📝 Catatan Penting

- **MySQL vs PostgreSQL**: Aplikasi ini pakai MySQL, jadi gunakan MySQL external atau konversi ke PostgreSQL
- **BASE_URL**: Pastikan di-set di Render Environment Variables
- **File Upload**: Folder `assets/uploads/` perlu writable (Render akan handle otomatis)

---

**Selamat! Aplikasi Anda sekarang online di Render! 🎉**

