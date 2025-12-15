# 🐘 Setup PostgreSQL di Railway - Panduan Lengkap

## ✅ Connection String yang Diberikan

Railway memberikan connection string PostgreSQL:
```
postgresql://postgres:BgMSlFmoVzbgAwBpzQMisIeMuNNnBpkJ@turntable.proxy.rlwy.net:39090/railway
```

**Informasi dari connection string:**
- **Host**: `turntable.proxy.rlwy.net`
- **Port**: `39090`
- **User**: `postgres`
- **Password**: `BgMSlFmoVzbgAwBpzQMisIeMuNNnBpkJ`
- **Database**: `railway`

---

## 🚀 Langkah Setup (5 Menit)

### 1. Set DATABASE_URL di Railway (1 menit)

1. **Buka Railway Dashboard**: https://railway.app
2. **Klik Web Service** (bukan database service)
3. **Klik tab "Variables"**
4. **Tambahkan variable baru**:
   ```
   Key: DATABASE_URL
   Value: postgresql://postgres:BgMSlFmoVzbgAwBpzQMisIeMuNNnBpkJ@turntable.proxy.rlwy.net:39090/railway
   ```

5. **Tambahkan juga**:
   ```
   Key: BASE_URL
   Value: https://risk-assessment-app-production-f6a4.up.railway.app/
   
   Key: APP_ENV
   Value: production
   ```

**Catatan**: Ganti `BASE_URL` dengan URL aplikasi Anda (lihat di Settings → Domains)

### 2. Import Database Schema (3 menit)

#### Via Railway Web Interface (Mudah)

1. **Klik PostgreSQL Database service** di Railway Dashboard
2. **Klik tab "Data"**
3. **Klik "Query"** (akan membuka SQL editor)
4. **Copy-paste SQL berikut** (satu per satu):

**File 1: `sql/database_postgresql.sql`**
- Copy seluruh isi file dari repository: https://github.com/82080038/risk/blob/main/sql/database_postgresql.sql
- Paste di SQL editor
- Klik "Run" atau tekan Ctrl+Enter
- Tunggu sampai selesai

**File 2: `sql/master_data_postgresql.sql`**
- Copy seluruh isi file dari repository: https://github.com/82080038/risk/blob/main/sql/master_data_postgresql.sql
- Paste di SQL editor
- Klik "Run"

**File 3: `sql/data_personil_postgresql.sql`**
- Copy seluruh isi file dari repository: https://github.com/82080038/risk/blob/main/sql/data_personil_postgresql.sql
- Paste di SQL editor
- Klik "Run"

#### Via Railway CLI (Advanced)

```bash
# Install Railway CLI
npm i -g @railway/cli

# Login
railway login

# Link ke project
railway link

# Connect ke database
railway connect postgres

# Import SQL files
psql $DATABASE_URL < sql/database_postgresql.sql
psql $DATABASE_URL < sql/master_data_postgresql.sql
psql $DATABASE_URL < sql/data_personil_postgresql.sql
```

### 3. Restart Web Service (30 detik)

1. **Klik Web Service**
2. **Klik tab "Deployments"**
3. **Klik tombol "Redeploy"** (atau "Restart")
4. **Tunggu sampai deploy selesai** (2-3 menit)

### 4. Verifikasi (30 detik)

1. **Buka URL aplikasi**: `https://your-app-name.up.railway.app/`
2. **Akses debug page**: `https://your-app-name.up.railway.app/debug.php`
3. **Cek bagian "6. Database Connection Test"** - harusnya ✅
4. **Cek "DB_TYPE"** - harusnya `postgresql`

---

## ✅ Checklist Setup

- [ ] DATABASE_URL sudah di-set di Web Service → Variables
- [ ] BASE_URL sudah di-set
- [ ] APP_ENV sudah di-set ke "production"
- [ ] Database schema sudah di-import (database_postgresql.sql)
- [ ] Master data sudah di-import (master_data_postgresql.sql)
- [ ] Data personil sudah di-import (data_personil_postgresql.sql)
- [ ] Web service sudah di-restart (Redeploy)
- [ ] Debug page menunjukkan database connection ✅
- [ ] Debug page menunjukkan DB_TYPE: postgresql

---

## 🔍 Troubleshooting

### Error: "PDO PostgreSQL extension tidak tersedia"

**Solusi:**
- Extension `pdo_pgsql` sudah ditambahkan di `Dockerfile.railway`
- Pastikan web service sudah di-restart setelah deploy
- Cek di debug page apakah extension terdeteksi

### Error: "Table doesn't exist"

**Solusi:**
- Database schema belum di-import
- Import `sql/database_postgresql.sql` via Database service → Data → Query

### Error: "Access denied" atau "Connection refused"

**Solusi:**
- Cek DATABASE_URL sudah benar di Web Service → Variables
- Pastikan connection string lengkap dan tidak ada spasi
- Cek apakah database service masih running di Railway

### Aplikasi masih menampilkan "Database Belum Dikonfigurasi"

**Solusi:**
1. Pastikan DATABASE_URL sudah di-set dengan benar
2. Restart web service (Redeploy)
3. Tunggu 2-3 menit setelah restart
4. Clear browser cache dan refresh

### Error: "syntax error at or near"

**Solusi:**
- Pastikan menggunakan file SQL untuk PostgreSQL (bukan MySQL)
- File yang benar:
  - `sql/database_postgresql.sql` (bukan `database.sql`)
  - `sql/master_data_postgresql.sql` (bukan `master_data.sql`)
  - `sql/data_personil_postgresql.sql` (bukan `data_personil.sql`)

---

## 📝 Perbedaan MySQL vs PostgreSQL

| Feature | MySQL | PostgreSQL |
|---------|-------|------------|
| AUTO_INCREMENT | ✅ | ❌ (gunakan SERIAL) |
| ENUM | ✅ | ❌ (gunakan CHECK constraint) |
| ENGINE=InnoDB | ✅ | ❌ (tidak perlu) |
| USE database | ✅ | ❌ (tidak perlu) |
| ON CONFLICT | ❌ | ✅ (PostgreSQL specific) |

File SQL PostgreSQL sudah disesuaikan dengan syntax PostgreSQL.

---

## 📞 Butuh Bantuan?

Jika masih ada masalah:
1. Cek **Railway Logs**: Web Service → tab "Logs"
2. Cek **Debug Page**: `https://your-app-name.up.railway.app/debug.php`
3. Cek **Database Variables**: Database service → tab "Variables"

---

**Selamat! Aplikasi Anda seharusnya sudah berjalan normal dengan PostgreSQL setelah setup selesai.** 🎉

