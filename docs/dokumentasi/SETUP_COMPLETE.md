# ✅ SETUP SELESAI
## Risk Assessment Objek Wisata - Polres Samosir

---

## 🎉 STATUS SETUP

**Semua komponen sudah disiapkan dan siap digunakan!**

---

## 📋 YANG SUDAH DILAKUKAN

### ✅ 1. Database Setup
- ✅ File SQL siap diimport:
  - `sql/database.sql` - Struktur database
  - `sql/master_data.sql` - Data master (aspek, elemen, kriteria)
  - `sql/data_personil.sql` - Data personil (19 orang)
  - `sql/data_objek_wisata.sql` - Data objek wisata (69 objek)

### ✅ 2. Koneksi Database
- ✅ File `config/database.php` - Koneksi database dengan error handling
- ✅ Auto-create database jika belum ada
- ✅ UTF-8 charset support
- ✅ Error handling yang baik

### ✅ 3. Aplikasi GUI
- ✅ Struktur folder lengkap
- ✅ Halaman login (mobile-friendly)
- ✅ Dashboard dengan statistik
- ✅ Navigation bar responsive
- ✅ Custom CSS (mobile-first)
- ✅ Custom JavaScript dengan jQuery

### ✅ 4. Error Handling
- ✅ Session management
- ✅ Error handling di semua file
- ✅ Warning dan error sudah dicek
- ✅ File check script tersedia

---

## 🚀 CARA MENGGUNAKAN

### Langkah 1: Import Database

**Opsi A: Via Browser (Paling Mudah)**
1. Buka: `http://localhost/RISK/setup_database.php`
2. Script akan otomatis import semua file SQL
3. Tunggu hingga selesai
4. Klik "Login ke Aplikasi"

**Opsi B: Via phpMyAdmin**
1. Buka: `http://localhost/phpmyadmin`
2. Import file SQL satu per satu:
   - `sql/database.sql`
   - `sql/master_data.sql`
   - `sql/data_personil.sql`
   - `sql/data_objek_wisata.sql`

### Langkah 2: Test Koneksi

Buka: `http://localhost/RISK/test_connection.php`

Akan menampilkan:
- Status koneksi database
- Jumlah data di setiap tabel
- Contoh user untuk login

### Langkah 3: Check Errors

Buka: `http://localhost/RISK/check_errors.php`

Akan menampilkan:
- Status semua file
- Status koneksi database
- Status tabel dan data
- Warnings dan errors (jika ada)

### Langkah 4: Login ke Aplikasi

Buka: `http://localhost/RISK/` atau `http://localhost/RISK/pages/login.php`

**Login dengan:**
- Username: `72100664`
- Password: `72100664`

---

## 🔐 CREDENTIALS LOGIN

### Admin:
- **Username:** `72100664`
- **Password:** `72100664`
- **Nama:** Tangio Haojahan Sitanggang, S.H.
- **Role:** Admin

### Penilai (contoh):
- **Username:** `69090552`
- **Password:** `69090552`
- **Nama:** Rahmat Kurniawan
- **Role:** Penilai

**Catatan:** Username dan Password = **NRP** (Nomor Registrasi Pokok)

---

## 📊 DATA YANG SUDAH SIAP

### Database:
- ✅ **Tabel:** 8 tabel (users, objek_wisata, aspek, elemen, kriteria, penilaian, penilaian_detail, referensi_dokumen)
- ✅ **Personil:** 19 orang (1 admin, 18 penilai)
- ✅ **Objek Wisata:** 69 objek
- ✅ **Aspek:** 6 aspek penilaian
- ✅ **Kriteria:** ~150+ kriteria

### Aplikasi:
- ✅ Login page
- ✅ Dashboard dengan statistik
- ✅ Navigation responsive
- ✅ Error handling
- ✅ Session management

---

## 🔗 LINK PENTING

| Link | Deskripsi |
|------|-----------|
| `http://localhost/RISK/` | Halaman utama (redirect ke login/dashboard) |
| `http://localhost/RISK/pages/login.php` | Halaman login |
| `http://localhost/RISK/pages/dashboard.php` | Dashboard (setelah login) |
| `http://localhost/RISK/setup_database.php` | **Setup database otomatis** |
| `http://localhost/RISK/test_connection.php` | Test koneksi database |
| `http://localhost/RISK/check_errors.php` | Check errors & warnings |
| `http://localhost/phpmyadmin` | phpMyAdmin |

---

## ✅ CHECKLIST VERIFIKASI

Sebelum menggunakan aplikasi, pastikan:

- [ ] MySQL berjalan di XAMPP
- [ ] Apache berjalan di XAMPP
- [ ] Database sudah diimport (via setup_database.php atau phpMyAdmin)
- [ ] Folder `assets/uploads/` writable
- [ ] Tidak ada error di browser console (F12)
- [ ] Dapat login dengan NRP

---

## 🐛 TROUBLESHOOTING

### Error: Koneksi database gagal
**Solusi:**
1. Pastikan MySQL berjalan
2. Buka `setup_database.php` untuk import database
3. Atau import manual via phpMyAdmin

### Error: Session tidak bekerja
**Solusi:**
- Pastikan `session_start()` dipanggil di `config/config.php`
- Cek folder `tmp` di PHP dapat ditulis

### Error: Page not found (404)
**Solusi:**
- Pastikan Apache berjalan
- Cek Base URL di `config/config.php`
- Pastikan file `.htaccess` ada

### Error: Upload folder tidak writable
**Solusi:**
- Windows: Klik kanan folder `assets/uploads/` → Properties → Security → Edit permissions
- Atau buat folder secara manual

---

## 📝 CATATAN

1. **Database:** Import semua file SQL untuk data lengkap
2. **Login:** Gunakan NRP sebagai username dan password
3. **Uploads:** Folder uploads harus writable untuk upload file referensi
4. **Browser:** Gunakan browser modern (Chrome, Firefox, Edge)

---

## 🎯 LANGKAH SELANJUTNYA

Setelah setup selesai:

1. ✅ Import database (via `setup_database.php`)
2. ✅ Test koneksi (via `test_connection.php`)
3. ✅ Check errors (via `check_errors.php`)
4. ✅ Login ke aplikasi
5. ✅ Mulai menggunakan aplikasi

---

**Aplikasi siap digunakan! 🚀**

