# 🚀 Quick Setup Railway - Langkah Cepat

## ⚡ Setup dalam 5 Menit

### 1. Tambahkan MySQL Database (2 menit)

1. **Buka Railway Dashboard**: https://railway.app
2. **Pilih Project** Anda (atau buat baru jika belum ada)
3. **Klik "New +"** (di pojok kanan atas)
4. **Pilih "Database"** → **"Add MySQL"**
5. Railway akan otomatis:
   - Membuat MySQL database service
   - Generate connection credentials
   - **Link otomatis ke web service** (jika web service sudah ada)

### 2. Set BASE_URL (1 menit)

1. **Klik Web Service** (bukan database service)
2. **Klik tab "Variables"**
3. **Tambahkan variable baru**:
   ```
   Key: BASE_URL
   Value: https://risk-assessment-app-production-f6a4.up.railway.app/
   ```
   **Catatan**: Ganti URL dengan URL aplikasi Anda (lihat di tab "Settings" → "Domains")

4. **Tambahkan juga**:
   ```
   Key: APP_ENV
   Value: production
   ```

### 3. Import Database Schema (2 menit)

#### Opsi A: Via Railway Web Interface (Mudah)

1. **Klik MySQL Database service** yang baru dibuat
2. **Klik tab "Data"**
3. **Klik "Query"** (akan membuka SQL editor)
4. **Copy-paste SQL berikut** (satu per satu):

**File 1: `sql/database.sql`**
- Copy seluruh isi file `sql/database.sql` dari repository
- Paste di SQL editor
- Klik "Run" atau tekan Ctrl+Enter
- Tunggu sampai selesai

**File 2: `sql/master_data.sql`** (jika ada)
- Copy seluruh isi file `sql/master_data.sql`
- Paste di SQL editor
- Klik "Run"

**File 3: `sql/data_personil.sql`** (jika ada)
- Copy seluruh isi file `sql/data_personil.sql`
- Paste di SQL editor
- Klik "Run"

#### Opsi B: Via Railway CLI (Advanced)

```bash
# Install Railway CLI
npm i -g @railway/cli

# Login
railway login

# Link ke project
railway link

# Connect ke database
railway connect mysql

# Import SQL files
mysql -u $MYSQL_USER -p$MYSQL_PASSWORD -h $MYSQL_HOST $MYSQL_DATABASE < sql/database.sql
mysql -u $MYSQL_USER -p$MYSQL_PASSWORD -h $MYSQL_HOST $MYSQL_DATABASE < sql/master_data.sql
mysql -u $MYSQL_USER -p$MYSQL_PASSWORD -h $MYSQL_HOST $MYSQL_DATABASE < sql/data_personil.sql
```

### 4. Restart Web Service (30 detik)

1. **Klik Web Service**
2. **Klik tab "Deployments"**
3. **Klik tombol "Redeploy"** (atau "Restart")
4. **Tunggu sampai deploy selesai** (2-3 menit)

### 5. Verifikasi (30 detik)

1. **Buka URL aplikasi**: `https://your-app-name.up.railway.app/`
2. **Akses debug page**: `https://your-app-name.up.railway.app/debug.php`
3. **Cek bagian "6. Database Connection Test"** - harusnya ✅

---

## ✅ Checklist Setup

- [ ] MySQL Database service sudah dibuat
- [ ] Database service sudah di-link ke web service (otomatis atau manual)
- [ ] BASE_URL sudah di-set di Web Service → Variables
- [ ] APP_ENV sudah di-set ke "production"
- [ ] Database schema sudah di-import (database.sql, master_data.sql, data_personil.sql)
- [ ] Web service sudah di-restart (Redeploy)
- [ ] Debug page menunjukkan database connection ✅

---

## 🔍 Troubleshooting

### Error: "Database connection failed"

**Solusi:**
1. Cek di Web Service → Variables, pastikan ada:
   - `MYSQL_HOST`
   - `MYSQL_PORT`
   - `MYSQL_USER`
   - `MYSQL_PASSWORD`
   - `MYSQL_DATABASE`

2. Jika tidak ada, database belum di-link. Link manual:
   - Di Web Service → Variables → "Add Variable"
   - Gunakan format: `MYSQL_HOST=${{MySQL.MYSQLHOST}}`
   - Atau copy langsung dari Database service → Variables

### Error: "Table doesn't exist"

**Solusi:**
- Database schema belum di-import
- Import `sql/database.sql` via Database service → Data → Query

### Error: "Access denied"

**Solusi:**
- Cek credentials di Database service → Variables
- Pastikan database sudah di-link ke web service

### Aplikasi masih menampilkan "Database Belum Dikonfigurasi"

**Solusi:**
1. Pastikan semua environment variables sudah di-set
2. Restart web service (Redeploy)
3. Tunggu 2-3 menit setelah restart
4. Clear browser cache dan refresh

---

## 📞 Butuh Bantuan?

Jika masih ada masalah:
1. Cek **Railway Logs**: Web Service → tab "Logs"
2. Cek **Debug Page**: `https://your-app-name.up.railway.app/debug.php`
3. Cek **Database Variables**: Database service → tab "Variables"

---

**Selamat! Aplikasi Anda seharusnya sudah berjalan normal setelah setup selesai.** 🎉

