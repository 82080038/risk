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

**⚠️ Catatan Penting**: Railway **TIDAK menyediakan SQL query editor** di dashboard. Railway hanya menyediakan interface untuk **melihat dan mengedit data** (bukan untuk menjalankan SQL queries).

**Untuk import SQL schema, gunakan salah satu metode berikut:**

#### Metode 1: Via Railway CLI (Paling Mudah) ⭐ Recommended

**Langkah 1: Install Railway CLI** (jika belum)

**Windows (PowerShell):**
```powershell
# Install via npm (jika sudah install Node.js)
npm install -g @railway/cli

# Atau download dari: https://railway.app/cli
```

**Atau download langsung:**
- Buka: https://railway.app/cli
- Download untuk Windows
- Extract dan tambahkan ke PATH

**Langkah 2: Login ke Railway**
```bash
railway login
```

**Langkah 3: Connect ke MySQL Database**
```bash
# Masuk ke direktori project
cd E:\xampp\htdocs\RISK

# Link ke project Railway
railway link

# Connect ke MySQL service
railway connect MySQL
```

**Langkah 4: Import SQL Files**
```bash
# Import struktur database
mysql -h $MYSQL_HOST -u $MYSQL_USER -p$MYSQL_PASSWORD $MYSQL_DATABASE < sql/database.sql

# Import master data
mysql -h $MYSQL_HOST -u $MYSQL_USER -p$MYSQL_PASSWORD $MYSQL_DATABASE < sql/master_data.sql

# Import data personil
mysql -h $MYSQL_HOST -u $MYSQL_USER -p$MYSQL_PASSWORD $MYSQL_DATABASE < sql/data_personil.sql
```

**Atau jika Railway CLI sudah set environment variables:**
```bash
railway run mysql -h $MYSQL_HOST -u $MYSQL_USER -p$MYSQL_PASSWORD $MYSQL_DATABASE < sql/database.sql
railway run mysql -h $MYSQL_HOST -u $MYSQL_USER -p$MYSQL_PASSWORD $MYSQL_DATABASE < sql/master_data.sql
railway run mysql -h $MYSQL_HOST -u $MYSQL_USER -p$MYSQL_PASSWORD $MYSQL_DATABASE < sql/data_personil.sql
```

#### Metode 2: Via MySQL Workbench (Alternatif - Mudah untuk Windows)

**Langkah 1: Dapatkan Connection Credentials**

1. **Klik MySQL service** di Railway Dashboard
2. **Klik tab "Variables"**
3. **Copy credentials berikut**:
   - `MYSQL_HOST` (contoh: `containers-us-west-xxx.railway.app`)
   - `MYSQL_PORT` (biasanya `3306`)
   - `MYSQL_USER` (contoh: `root`)
   - `MYSQL_PASSWORD` (password yang diberikan Railway)
   - `MYSQL_DATABASE` (nama database)

**Langkah 2: Install MySQL Client**

**Windows:**
- Download MySQL Workbench: https://dev.mysql.com/downloads/workbench/
- Atau install MySQL Command Line Client (termasuk di XAMPP)

**Langkah 3: Connect ke Database**

**Via MySQL Workbench:**
1. Buka MySQL Workbench
2. Klik **"+"** untuk connection baru
3. Isi:
   - **Connection Name**: Railway MySQL
   - **Hostname**: `MYSQL_HOST` (dari Railway)
   - **Port**: `MYSQL_PORT` (dari Railway)
   - **Username**: `MYSQL_USER` (dari Railway)
   - **Password**: `MYSQL_PASSWORD` (dari Railway)
4. Klik **"Test Connection"** → **"OK"**
5. Double-click connection untuk connect

**Langkah 4: Import SQL Files**

1. **Buka file SQL** di text editor:
   - `sql/database.sql`
   - `sql/master_data.sql`
   - `sql/data_personil.sql`

2. **Copy seluruh isi file** (Ctrl+A → Ctrl+C)

3. **Di MySQL Workbench**:
   - Klik tab **"Query"** (atau tekan Ctrl+T)
   - Paste SQL (Ctrl+V)
   - Klik **"Execute"** (atau tekan Ctrl+Shift+Enter)
   - Tunggu sampai selesai (akan muncul "Success")

4. **Ulangi untuk setiap file** (database.sql → master_data.sql → data_personil.sql)

**Via Command Line (Windows PowerShell):**
```powershell
# Masuk ke direktori project
cd E:\xampp\htdocs\RISK

# Import SQL files (ganti dengan credentials dari Railway)
mysql -h containers-us-west-xxx.railway.app -P 3306 -u root -p"PASSWORD_DARI_RAILWAY" MYSQL_DATABASE < sql/database.sql
mysql -h containers-us-west-xxx.railway.app -P 3306 -u root -p"PASSWORD_DARI_RAILWAY" MYSQL_DATABASE < sql/master_data.sql
mysql -h containers-us-west-xxx.railway.app -P 3306 -u root -p"PASSWORD_DARI_RAILWAY" MYSQL_DATABASE < sql/data_personil.sql
```

**Catatan**: Ganti `containers-us-west-xxx.railway.app`, `PASSWORD_DARI_RAILWAY`, dan `MYSQL_DATABASE` dengan nilai dari Railway Variables.

#### Metode 3: Via DBeaver / Beekeeper Studio (Alternatif - Cross Platform)

**DBeaver** dan **Beekeeper Studio** adalah aplikasi database management yang mendukung MySQL.

**Langkah 1: Install DBeaver**
- Download: https://dbeaver.io/download/
- Install sesuai OS Anda

**Langkah 2: Connect ke Railway MySQL**
1. Buka DBeaver
2. Klik **"New Database Connection"** → Pilih **"MySQL"**
3. Isi connection details dari Railway Variables:
   - **Host**: `MYSQL_HOST` (dari Railway)
   - **Port**: `MYSQL_PORT` (dari Railway, biasanya 3306)
   - **Database**: `MYSQL_DATABASE` (dari Railway)
   - **Username**: `MYSQL_USER` (dari Railway)
   - **Password**: `MYSQL_PASSWORD` (dari Railway)
4. Klik **"Test Connection"** → **"OK"**

**Langkah 3: Import SQL Files**
1. Klik kanan pada database → **"SQL Editor"** → **"New SQL Script"**
2. Buka file `sql/database.sql` di text editor
3. Copy seluruh isi (Ctrl+A → Ctrl+C)
4. Paste di SQL editor DBeaver (Ctrl+V)
5. Klik **"Execute SQL Script"** (Ctrl+Alt+X)
6. Ulangi untuk `master_data.sql` dan `data_personil.sql`

#### Metode 4: Via Web-Based Tools (Alternatif - Tidak Perlu Install)

**Opsi A: Indiequery** (Web-based MySQL client)
1. Buka: https://indiequery.com
2. Connect menggunakan `DATABASE_PUBLIC_URL` dari Railway
3. Jalankan SQL queries via web interface

**Opsi B: Sequel.sh** (Web-based MySQL client)
1. Buka: https://sequel.sh
2. Connect menggunakan credentials dari Railway
3. Jalankan SQL queries via web interface

1. **Buka**: https://www.phpmyadmin.co/ (atau hosting phpMyAdmin lainnya)
2. **Connect menggunakan credentials** dari Railway
3. **Pilih database** yang sudah dibuat
4. **Klik tab "SQL"**
5. **Copy-paste SQL** dari file `sql/database.sql`, `master_data.sql`, `data_personil.sql`
6. **Klik "Go"** untuk execute

### 3. Restart Web Service (30 detik)

1. **Klik Web Service** di Railway Dashboard
2. **Klik tab "Deployments"**
3. **Klik tombol "Redeploy"** (atau "Restart")
4. **Tunggu sampai deploy selesai** (2-3 menit)
   - Status akan berubah dari "Building" → "Deploying" → "Active"

### 5. Verifikasi Aplikasi (30 detik)

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

