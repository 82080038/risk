# 🐬 Setup MySQL di Railway - Panduan Lengkap

## ✅ MySQL Tersedia di Railway

Railway menyediakan MySQL database service. Aplikasi ini **sudah support MySQL** dan akan otomatis detect jika environment variables MySQL di-set.

---

## 🚀 Langkah Setup MySQL (5 Menit)

### 1. Set MySQL Environment Variables di Railway (1 menit)

1. **Buka Railway Dashboard**: https://railway.app
2. **Klik Web Service** (bukan database service)
3. **Klik tab "Variables"**
4. **Railway biasanya sudah otomatis menambahkan** environment variables MySQL jika database sudah di-link:
   - `MYSQL_HOST`
   - `MYSQL_PORT`
   - `MYSQL_USER`
   - `MYSQL_PASSWORD`
   - `MYSQL_DATABASE`

5. **Jika belum ada, tambahkan manual**:
   - Klik MySQL service → tab "Variables"
   - Copy semua environment variables
   - Paste ke Web Service → Variables

6. **Tambahkan juga**:
   ```
   BASE_URL=https://risk-assessment-app-production-f6a4.up.railway.app/
   APP_ENV=production
   ```
   **Catatan**: Ganti `BASE_URL` dengan URL aplikasi Anda

### 2. Import Database Schema (3 menit)

#### Via Railway Web Interface (Paling Mudah)

1. **Klik MySQL Database service** di Railway Dashboard
2. **Klik tab "Data"**
3. **Klik "Query"** (akan membuka SQL editor)
4. **Copy-paste SQL berikut** (satu per satu, tunggu selesai sebelum lanjut):

**📄 File 1: Struktur Database**
- **Copy** dari: https://github.com/82080038/risk/blob/main/sql/database.sql
- **Atau copy langsung**:
```sql
-- Copy seluruh isi file sql/database.sql
-- Paste di SQL editor → Run
```
- **Klik "Run"** atau tekan **Ctrl+Enter**
- **Tunggu sampai selesai** (akan muncul "Success" atau "Query executed successfully")

**📄 File 2: Master Data (Aspek, Elemen, Kriteria)**
- **Copy** dari: https://github.com/82080038/risk/blob/main/sql/master_data.sql
- **Paste di SQL editor** → **Run**
- **Tunggu sampai selesai**

**📄 File 3: Data Personil**
- **Copy** dari: https://github.com/82080038/risk/blob/main/sql/data_personil.sql
- **Paste di SQL editor** → **Run**
- **Tunggu sampai selesai**

#### Via Railway CLI (Alternatif)

Jika Anda sudah install Railway CLI:

```bash
# Connect ke database
railway connect MySQL

# Import SQL files (satu per satu)
mysql -h $MYSQL_HOST -u $MYSQL_USER -p$MYSQL_PASSWORD $MYSQL_DATABASE < sql/database.sql
mysql -h $MYSQL_HOST -u $MYSQL_USER -p$MYSQL_PASSWORD $MYSQL_DATABASE < sql/master_data.sql
mysql -h $MYSQL_HOST -u $MYSQL_USER -p$MYSQL_PASSWORD $MYSQL_DATABASE < sql/data_personil.sql
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
   - **DB_TYPE**: `mysql`
   - **Connection type**: `MySQLi`

4. **Jika berhasil**, Anda akan melihat halaman login atau dashboard

---

## ✅ Checklist Setup

- [ ] MySQL Database service sudah dibuat dan running
- [ ] MySQL service sudah di-link ke web service (otomatis atau manual)
- [ ] Environment variables MySQL sudah di-set di Web Service → Variables
- [ ] BASE_URL sudah di-set (ganti dengan URL aplikasi Anda)
- [ ] APP_ENV sudah di-set ke "production"
- [ ] Database schema sudah di-import (`database.sql`)
- [ ] Master data sudah di-import (`master_data.sql`)
- [ ] Data personil sudah di-import (`data_personil.sql`)
- [ ] Web service sudah di-restart (Redeploy)
- [ ] Debug page menunjukkan database connection ✅
- [ ] Debug page menunjukkan DB_TYPE: mysql

---

## 🔍 Troubleshooting

### ❌ Error: "MYSQL_HOST not found"

**Solusi:**
- Pastikan MySQL service sudah di-link ke web service
- Cek di Web Service → Variables, pastikan ada:
  - `MYSQL_HOST`
  - `MYSQL_PORT`
  - `MYSQL_USER`
  - `MYSQL_PASSWORD`
  - `MYSQL_DATABASE`
- Jika tidak ada, link manual:
  - Di Web Service → Variables → "Add Variable"
  - Gunakan format: `MYSQL_HOST=${{MySQL.MYSQLHOST}}`
  - Atau copy langsung dari MySQL service → Variables

### ❌ Error: "MySQLi extension tidak tersedia"

**Solusi:**
- Extension `mysqli` sudah ditambahkan di `Dockerfile.railway`
- Pastikan web service sudah di-restart setelah deploy
- Cek di debug page apakah extension terdeteksi

### ❌ Error: "Table doesn't exist"

**Solusi:**
- Database schema belum di-import
- Import `sql/database.sql` via MySQL service → Data → Query
- Pastikan menggunakan file **MySQL** (bukan PostgreSQL)

### ❌ Error: "Access denied"

**Solusi:**
- Cek credentials di MySQL service → Variables
- Pastikan MySQL service sudah di-link ke web service
- Cek apakah database service masih running di Railway

### ❌ Aplikasi masih menampilkan "Database Belum Dikonfigurasi"

**Solusi:**
1. Pastikan semua environment variables MySQL sudah di-set
2. Restart web service (Redeploy)
3. Tunggu 2-3 menit setelah restart
4. Clear browser cache dan refresh
5. Cek di debug page apakah MYSQL_HOST terdeteksi

---

## 📝 Perbedaan MySQL vs PostgreSQL

| Feature | MySQL | PostgreSQL |
|---------|-------|------------|
| **File SQL** | `database.sql` | `database_postgresql.sql` |
| **Environment Variables** | `MYSQL_*` | `DATABASE_URL` atau `PG*` |
| **Extension PHP** | `mysqli` | `pdo_pgsql` |
| **AUTO_INCREMENT** | ✅ | ❌ (gunakan SERIAL) |
| **ENUM** | ✅ | ❌ (gunakan CHECK) |

**Aplikasi sudah support keduanya!** Pilih yang Anda prefer.

---

## 🎯 Pilih Database: MySQL atau PostgreSQL?

### ✅ Gunakan MySQL jika:
- Lebih familiar dengan MySQL
- Ingin menggunakan file SQL yang sudah ada (`database.sql`)
- Tidak perlu fitur advanced PostgreSQL

### ✅ Gunakan PostgreSQL jika:
- Ingin fitur advanced (JSON, array, dll)
- Railway memberikan PostgreSQL secara default
- Tidak masalah dengan syntax yang sedikit berbeda

**Keduanya sama-sama bagus!** Aplikasi sudah support keduanya.

---

## 🎉 Setelah Setup Selesai

Jika semua checklist ✅, aplikasi seharusnya sudah:
- ✅ Connect ke MySQL database
- ✅ Menampilkan halaman login
- ✅ Siap digunakan untuk penilaian risiko objek wisata

**Default login:**
- Username: `admin`
- Password: `admin123`

**Atau gunakan NRP personil** (lihat di `data_personil.sql`)

---

**Selamat! Aplikasi Anda sudah online dengan MySQL!** 🚀

