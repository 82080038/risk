# 🌐 Cara Mengakses Aplikasi di Railway

## 📍 Menemukan URL Aplikasi

### Metode 1: Dari Railway Dashboard (Paling Mudah)

1. **Login ke Railway**: https://railway.app
2. **Klik Project** Anda (atau buka project yang sudah dibuat)
3. **Klik Web Service** (service yang menjalankan aplikasi)
4. **Lihat di bagian atas** atau **"Settings"** tab:
   - **URL akan terlihat** di bagian header service
   - Format: `https://your-app-name.up.railway.app`
   - Atau: `https://[random-name].railway.app`

5. **Klik URL tersebut** atau **copy-paste ke browser**

### Metode 2: Generate Custom Domain

1. **Klik Web Service** → **"Settings"** tab
2. **Scroll ke bagian "Domains"**
3. **Klik "Generate Domain"** untuk mendapatkan domain otomatis
4. **Atau tambahkan custom domain** Anda sendiri (jika punya)

---

## 🔗 Format URL Railway

Railway memberikan URL dengan format:
```
https://[service-name]-[random].up.railway.app
```

Contoh:
- `https://risk-assessment-production.up.railway.app`
- `https://web-production-abc123.up.railway.app`

---

## ✅ Checklist Sebelum Mengakses

Pastikan hal-hal berikut sudah selesai:

### 1. ✅ Build & Deploy Berhasil
- **Cek di Railway Dashboard**:
  - Klik Web Service → **"Deployments"** tab
  - Status harus **"SUCCESS"** (hijau)
  - Jika masih **"BUILDING"** atau **"FAILED"**, tunggu atau perbaiki error

### 2. ✅ Database Sudah Di-import
- **Cek MySQL Service**:
  - Klik MySQL service → **"Data"** tab
  - Pastikan tabel sudah ada (users, objek_wisata, dll)
  - Jika belum, import SQL files:
    - `sql/database.sql`
    - `sql/master_data.sql`
    - `sql/data_personil.sql`

### 3. ✅ Environment Variables Sudah Di-set
- **Klik Web Service** → **"Variables"** tab
- **Pastikan variables berikut ada**:
  ```
  MYSQL_HOST (otomatis dari Railway)
  MYSQL_PORT (otomatis dari Railway)
  MYSQL_DATABASE (otomatis dari Railway)
  MYSQL_USER (otomatis dari Railway)
  MYSQL_PASSWORD (otomatis dari Railway)
  BASE_URL=https://your-app-name.up.railway.app/
  APP_ENV=production
  ```

### 4. ✅ File `config/database.php` Sudah Benar
- **Pastikan file ini membaca Railway variables**
- File `config/database.php.example` sudah support Railway
- Jika belum, copy isi `config/database.php.example` ke `config/database.php`

---

## 🚀 Cara Mengakses Aplikasi

### Langkah 1: Buka URL di Browser

1. **Copy URL** dari Railway Dashboard
2. **Paste di browser** (Chrome, Firefox, Safari, dll)
3. **Tekan Enter**

### Langkah 2: Login ke Aplikasi

**Default Login (setelah import `data_personil.sql`)**:
- **Username**: `admin`
- **Password**: `admin123`

**Atau cek di database**:
- Buka MySQL service → **"Data"** tab → **"Query"**
- Jalankan: `SELECT username, role FROM users;`

---

## 🔍 Troubleshooting

### Error: "This site can't be reached" atau "Connection refused"

**Penyebab**:
- Service belum fully deployed
- Build masih berjalan atau gagal

**Solusi**:
1. Cek **Deployments** tab di Railway
2. Tunggu sampai status **"SUCCESS"**
3. Jika **FAILED**, cek logs untuk error

### Error: "Database Connection Failed"

**Penyebab**:
- Environment variables belum di-set
- `config/database.php` belum di-update

**Solusi**:
1. Cek **Variables** tab, pastikan `MYSQL_*` variables ada
2. Pastikan `config/database.php` membaca dari Railway variables
3. Test koneksi via Railway Connect

### Error: "404 Not Found" atau "Page not found"

**Penyebab**:
- `BASE_URL` belum di-set dengan benar
- Routing tidak bekerja

**Solusi**:
1. Set `BASE_URL` di Variables tab:
   ```
   BASE_URL=https://your-actual-url.up.railway.app/
   ```
2. Pastikan ada file `index.php` di root
3. Cek `.htaccess` (jika menggunakan Apache)

### Error: "500 Internal Server Error"

**Penyebab**:
- PHP error atau database connection error

**Solusi**:
1. **Cek Logs** di Railway:
   - Klik Web Service → **"Logs"** tab
   - Lihat error message
2. **Cek database connection**:
   - Pastikan MySQL service running
   - Pastikan environment variables benar
3. **Cek file permissions**:
   - Pastikan `assets/uploads/` dan `logs/` writable

---

## 📱 Mengakses dari Mobile

Aplikasi sudah dioptimalkan untuk mobile! Cukup:

1. **Buka URL** di browser mobile (Chrome, Safari, dll)
2. **Aplikasi akan otomatis responsive**
3. **Gunakan bottom navigation** untuk navigasi mudah

---

## 🔐 Keamanan

### Setelah Online:

1. **Ganti password default**:
   - Login sebagai admin
   - Buka **Pengaturan** → **Profile**
   - Ganti password

2. **Set HTTPS** (Railway sudah otomatis HTTPS):
   - Railway memberikan SSL certificate otomatis
   - URL sudah menggunakan `https://`

3. **Backup database**:
   - Railway menyediakan backup otomatis
   - Atau export manual via MySQL service

---

## 📞 Bantuan

Jika masih ada masalah:

1. **Cek Logs** di Railway Dashboard
2. **Cek dokumentasi**: `RAILWAY_DEPLOY.md`
3. **Cek troubleshooting**: `RAILWAY_TROUBLESHOOTING.md`

---

**Selamat! Aplikasi Anda sekarang online dan bisa diakses dari mana saja! 🎉**

