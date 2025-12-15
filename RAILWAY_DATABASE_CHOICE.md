# 🎯 Pilih Database: MySQL atau PostgreSQL?

Railway menyediakan **kedua database** (MySQL dan PostgreSQL). Aplikasi ini **sudah support keduanya**!

---

## 📊 Perbandingan Cepat

| Aspek | MySQL | PostgreSQL |
|-------|-------|------------|
| **File SQL** | `sql/database.sql` | `sql/database_postgresql.sql` |
| **Environment Variables** | `MYSQL_HOST`, `MYSQL_PORT`, dll | `DATABASE_URL` atau `PGHOST`, dll |
| **Extension PHP** | `mysqli` | `pdo_pgsql` |
| **Syntax** | Standard MySQL | PostgreSQL (SERIAL, CHECK, dll) |
| **Setup** | ✅ Mudah | ✅ Mudah |
| **Support** | ✅ Full support | ✅ Full support |

---

## 🐬 MySQL - Recommended untuk Aplikasi Ini

### ✅ Kelebihan:
- **File SQL sudah siap**: `database.sql`, `master_data.sql`, `data_personil.sql`
- **Syntax familiar**: AUTO_INCREMENT, ENUM, dll
- **Lebih simple**: Tidak perlu konversi syntax
- **Railway support**: MySQL tersedia di Railway

### 📝 Setup:
1. Link MySQL service ke web service
2. Set environment variables: `MYSQL_HOST`, `MYSQL_PORT`, dll
3. Import `sql/database.sql`, `master_data.sql`, `data_personil.sql`
4. Restart web service

**Lihat panduan lengkap**: `RAILWAY_MYSQL_SETUP.md`

---

## 🐘 PostgreSQL - Alternatif

### ✅ Kelebihan:
- **Fitur advanced**: JSON, array, dll
- **Railway default**: Sering diberikan secara default
- **Performance**: Baik untuk query kompleks

### ⚠️ Catatan:
- **File SQL berbeda**: `database_postgresql.sql`, `master_data_postgresql.sql`, dll
- **Syntax berbeda**: SERIAL (bukan AUTO_INCREMENT), CHECK (bukan ENUM)
- **Perlu konversi**: Jika sudah punya data MySQL

### 📝 Setup:
1. Set `DATABASE_URL` di web service
2. Import `sql/database_postgresql.sql`, `master_data_postgresql.sql`, dll
3. Restart web service

**Lihat panduan lengkap**: `RAILWAY_POSTGRESQL_SETUP.md`

---

## 🎯 Rekomendasi

### Untuk Aplikasi Ini: **MySQL** ✅

**Alasan:**
1. ✅ File SQL sudah siap (`database.sql`)
2. ✅ Syntax lebih familiar
3. ✅ Tidak perlu konversi
4. ✅ Aplikasi dirancang untuk MySQL
5. ✅ Railway support MySQL dengan baik

### Kapan Gunakan PostgreSQL?

- Jika Railway hanya memberikan PostgreSQL
- Jika butuh fitur advanced (JSON, array, dll)
- Jika sudah familiar dengan PostgreSQL

---

## 🔄 Cara Ganti Database

Jika sudah setup salah satu dan ingin ganti:

### Dari MySQL ke PostgreSQL:
1. Set `DATABASE_URL` di web service
2. Hapus environment variables MySQL (atau biarkan, aplikasi akan prioritas DATABASE_URL)
3. Import file SQL PostgreSQL
4. Restart web service

### Dari PostgreSQL ke MySQL:
1. Set environment variables MySQL di web service
2. Hapus `DATABASE_URL` (atau biarkan, aplikasi akan prioritas MYSQL_HOST)
3. Import file SQL MySQL
4. Restart web service

---

## ✅ Checklist Pilih Database

**Pilih MySQL jika:**
- [ ] Ingin setup cepat dan mudah
- [ ] File SQL sudah siap (`database.sql`)
- [ ] Lebih familiar dengan MySQL
- [ ] Tidak perlu fitur advanced PostgreSQL

**Pilih PostgreSQL jika:**
- [ ] Railway hanya memberikan PostgreSQL
- [ ] Butuh fitur advanced (JSON, array, dll)
- [ ] Sudah familiar dengan PostgreSQL
- [ ] Tidak masalah dengan syntax berbeda

---

## 📚 Dokumentasi

- **MySQL Setup**: `RAILWAY_MYSQL_SETUP.md`
- **PostgreSQL Setup**: `RAILWAY_POSTGRESQL_SETUP.md`
- **Quick Setup MySQL**: `RAILWAY_QUICK_SETUP_POSTGRESQL.md` (untuk PostgreSQL)

---

**Kesimpulan: Untuk aplikasi ini, MySQL lebih recommended karena file SQL sudah siap dan syntax lebih familiar.** ✅

