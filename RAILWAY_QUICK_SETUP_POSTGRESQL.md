# ⚡ Quick Setup PostgreSQL di Railway - 5 Menit

## 🎯 Connection String Anda

```
postgresql://postgres:BgMSlFmoVzbgAwBpzQMisIeMuNNnBpkJ@turntable.proxy.rlwy.net:39090/railway
```

---

## 🚀 Langkah Cepat (5 Menit)

### 1. Set DATABASE_URL di Railway (1 menit)

1. **Buka Railway Dashboard**: https://railway.app
2. **Klik Web Service** (bukan database service)
3. **Klik tab "Variables"**
4. **Klik "New Variable"** atau **"Add Variable"**
5. **Isi**:
   ```
   Key: DATABASE_URL
   Value: postgresql://postgres:BgMSlFmoVzbgAwBpzQMisIeMuNNnBpkJ@turntable.proxy.rlwy.net:39090/railway
   ```
6. **Klik "Add"** atau **"Save"**

7. **Tambahkan juga**:
   ```
   Key: BASE_URL
   Value: https://risk-assessment-app-production-f6a4.up.railway.app/
   
   Key: APP_ENV
   Value: production
   ```
   **Catatan**: Ganti `BASE_URL` dengan URL aplikasi Anda (lihat di Settings → Domains)

### 2. Import Database Schema (3 menit)

#### Via Railway Web Interface (Paling Mudah)

1. **Klik PostgreSQL Database service** di Railway Dashboard
2. **Klik tab "Data"**
3. **Klik "Query"** (akan membuka SQL editor)
4. **Copy-paste SQL berikut** (satu per satu, tunggu selesai sebelum lanjut):

**📄 File 1: Struktur Database**
- **Copy** dari: https://github.com/82080038/risk/blob/main/sql/database_postgresql.sql
- **Atau copy langsung**:
```sql
-- Copy seluruh isi file sql/database_postgresql.sql
-- Paste di SQL editor → Run
```
- **Klik "Run"** atau tekan **Ctrl+Enter**
- **Tunggu sampai selesai** (akan muncul "Success" atau "Query executed successfully")

**📄 File 2: Master Data (Aspek, Elemen, Kriteria)**
- **Copy** dari: https://github.com/82080038/risk/blob/main/sql/master_data_postgresql.sql
- **Paste di SQL editor** → **Run**
- **Tunggu sampai selesai**

**📄 File 3: Data Personil**
- **Copy** dari: https://github.com/82080038/risk/blob/main/sql/data_personil_postgresql.sql
- **Paste di SQL editor** → **Run**
- **Tunggu sampai selesai**

#### Via Railway CLI (Alternatif)

Jika Anda sudah install Railway CLI:

```bash
# Connect ke database
railway connect Postgres

# Import SQL files (satu per satu)
PGPASSWORD=BgMSlFmoVzbgAwBpzQMisIeMuNNnBpkJ psql -h turntable.proxy.rlwy.net -U postgres -p 39090 -d railway < sql/database_postgresql.sql
PGPASSWORD=BgMSlFmoVzbgAwBpzQMisIeMuNNnBpkJ psql -h turntable.proxy.rlwy.net -U postgres -p 39090 -d railway < sql/master_data_postgresql.sql
PGPASSWORD=BgMSlFmoVzbgAwBpzQMisIeMuNNnBpkJ psql -h turntable.proxy.rlwy.net -U postgres -p 39090 -d railway < sql/data_personil_postgresql.sql
```

### 3. Restart Web Service (30 detik)

1. **Klik Web Service** di Railway Dashboard
2. **Klik tab "Deployments"**
3. **Klik tombol "Redeploy"** (atau "Restart")
4. **Tunggu sampai deploy selesai** (2-3 menit)
   - Status akan berubah dari "Building" → "Deploying" → "Active"

### 4. Verifikasi (30 detik)

1. **Buka URL aplikasi**: 
   ```
   https://risk-assessment-app-production-f6a4.up.railway.app/
   ```

2. **Atau akses debug page**:
   ```
   https://risk-assessment-app-production-f6a4.up.railway.app/debug.php
   ```

3. **Cek bagian "6. Database Connection Test"**:
   - Harusnya menunjukkan: ✅ **Database connection successful**
   - **DB_TYPE**: `postgresql`
   - **Connection type**: `PDO (PostgreSQL)`

4. **Jika berhasil**, Anda akan melihat halaman login atau dashboard

---

## ✅ Checklist Setup

- [ ] DATABASE_URL sudah di-set di Web Service → Variables
- [ ] BASE_URL sudah di-set (ganti dengan URL aplikasi Anda)
- [ ] APP_ENV sudah di-set ke "production"
- [ ] Database schema sudah di-import (`database_postgresql.sql`)
- [ ] Master data sudah di-import (`master_data_postgresql.sql`)
- [ ] Data personil sudah di-import (`data_personil_postgresql.sql`)
- [ ] Web service sudah di-restart (Redeploy)
- [ ] Debug page menunjukkan database connection ✅
- [ ] Debug page menunjukkan DB_TYPE: postgresql

---

## 🔍 Troubleshooting

### ❌ Error: "DATABASE_URL not found"

**Solusi:**
- Pastikan DATABASE_URL sudah di-set di **Web Service** → Variables (bukan di Database service)
- Pastikan tidak ada spasi di awal/akhir value
- Restart web service setelah set variable

### ❌ Error: "PDO PostgreSQL extension tidak tersedia"

**Solusi:**
- Extension `pdo_pgsql` sudah ditambahkan di `Dockerfile.railway`
- Pastikan web service sudah di-restart setelah deploy
- Cek di debug page apakah extension terdeteksi

### ❌ Error: "Table doesn't exist"

**Solusi:**
- Database schema belum di-import
- Import `sql/database_postgresql.sql` via Database service → Data → Query
- Pastikan menggunakan file **PostgreSQL** (bukan MySQL)

### ❌ Error: "syntax error at or near"

**Solusi:**
- Pastikan menggunakan file SQL untuk **PostgreSQL** (bukan MySQL)
- File yang benar:
  - ✅ `sql/database_postgresql.sql` (bukan `database.sql`)
  - ✅ `sql/master_data_postgresql.sql` (bukan `master_data.sql`)
  - ✅ `sql/data_personil_postgresql.sql` (bukan `data_personil.sql`)

### ❌ Error: "Connection refused" atau "Access denied"

**Solusi:**
- Cek DATABASE_URL sudah benar di Web Service → Variables
- Pastikan connection string lengkap dan tidak ada spasi
- Cek apakah database service masih running di Railway
- Coba copy connection string langsung dari Railway Dashboard

### ❌ Aplikasi masih menampilkan "Database Belum Dikonfigurasi"

**Solusi:**
1. Pastikan DATABASE_URL sudah di-set dengan benar
2. Restart web service (Redeploy)
3. Tunggu 2-3 menit setelah restart
4. Clear browser cache dan refresh
5. Cek di debug page apakah DATABASE_URL terdeteksi

---

## 📝 Catatan Penting

1. **Gunakan file SQL PostgreSQL** (bukan MySQL):
   - ✅ `database_postgresql.sql`
   - ✅ `master_data_postgresql.sql`
   - ✅ `data_personil_postgresql.sql`

2. **Aplikasi sudah support PostgreSQL**:
   - File `config/database.php.example` sudah bisa parse `DATABASE_URL`
   - Extension `pdo_pgsql` sudah ada di `Dockerfile.railway`

3. **Connection string bisa berubah**:
   - Railway mungkin mengubah host/port/password
   - Selalu gunakan connection string terbaru dari Railway Dashboard
   - Update DATABASE_URL jika connection string berubah

4. **Import SQL harus berurutan**:
   - File 1: `database_postgresql.sql` (struktur tabel)
   - File 2: `master_data_postgresql.sql` (data master)
   - File 3: `data_personil_postgresql.sql` (data personil)

---

## 🎉 Setelah Setup Selesai

Jika semua checklist ✅, aplikasi seharusnya sudah:
- ✅ Connect ke PostgreSQL database
- ✅ Menampilkan halaman login
- ✅ Siap digunakan untuk penilaian risiko objek wisata

**Default login:**
- Username: `admin`
- Password: `admin123`

**Atau gunakan NRP personil** (lihat di `data_personil_postgresql.sql`)

---

**Selamat! Aplikasi Anda sudah online dengan PostgreSQL!** 🚀

