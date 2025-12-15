# Risk Assessment Objek Wisata
## Aplikasi Penilaian Risiko Objek Wisata - Polres Samosir

---

## 📋 Deskripsi

Aplikasi web untuk melakukan penilaian risiko objek wisata berdasarkan 6 aspek penilaian:
1. **Infrastruktur** (Bobot: 20%)
2. **Keamanan** (Bobot: 20%)
3. **Keselamatan** (Bobot: 25%)
4. **Kesehatan** (Bobot: 10%)
5. **Sistem Pengamanan** (Bobot: 15%)
6. **Informasi** (Bobot: 10%)

---

## 🚀 Quick Start

### Prasyarat
- XAMPP (Apache, MySQL, PHP 7.4+)
- Browser modern (Chrome, Firefox, Edge)

### Instalasi

1. **Setup Database**
   ```bash
   # Import melalui phpMyAdmin:
   # 1. sql/database.sql
   # 2. sql/master_data.sql
   # 3. sql/data_personil.sql (opsional)
   # 4. sql/data_objek_wisata.sql (opsional)
   ```

2. **Konfigurasi**
   - Edit `config/database.php` (sesuaikan DB_HOST, DB_USER, DB_PASS jika perlu)
   - Edit `config/config.php` (sesuaikan BASE_URL jika perlu)

3. **Akses Aplikasi**
   ```
   http://localhost/RISK/
   ```

4. **Login Default**
   - Username: `admin`
   - Password: `admin123`

---

## 📁 Struktur Folder

```
RISK/
├── api/                 # API endpoints
├── assets/              # CSS, JS, images, uploads
├── config/              # Konfigurasi aplikasi
├── docs/                # Dokumentasi dan file acuan
│   ├── dokumentasi/     # Dokumentasi teknis
│   └── file_acuan/      # File referensi (docx, xlsx, pdf)
├── includes/            # File include (header, footer, functions)
├── pages/               # Halaman aplikasi
├── sql/                 # File SQL (database, master data)
├── tools/               # Script tools dan testing
├── index.php            # Entry point
├── logout.php           # Logout handler
└── README.md            # File ini
```

---

## 🎯 Fitur Utama

### ✅ Fitur yang Sudah Diimplementasikan

1. **Authentication & Session**
   - Login/Logout
   - Session management
   - Role-based access (admin, penilai)

2. **Dashboard**
   - Statistik penilaian
   - Charts (Pie & Bar)
   - Quick actions
   - Recent assessments

3. **Objek Wisata (CRUD)**
   - List dengan pagination
   - Create, Read, Update, Delete
   - Search & filter

4. **Penilaian**
   - Form penilaian lengkap (6 aspek, ~150+ kriteria)
   - Auto-save draft
   - Perhitungan skor otomatis
   - Upload file referensi
   - Detail penilaian
   - List penilaian

5. **Laporan**
   - List penilaian dengan filter
   - Download PDF
   - Search & pagination

6. **Mobile-First Design**
   - Responsive untuk semua device
   - Bottom navigation (mobile)
   - Touch-friendly UI

---

## 📊 Sistem Penilaian

### Nilai Kriteria
- **0**: Tidak dapat dipenuhi → WAJIB isi temuan & rekomendasi
- **1**: Terdapat kekurangan → WAJIB isi temuan & rekomendasi
- **2**: Dapat dipenuhi → Tidak perlu temuan & rekomendasi

### Perhitungan Skor

1. **Skor Elemen**
   ```
   Skor Elemen = (Jumlah nilai kriteria / (Jumlah kriteria × 2)) × 100
   ```

2. **Skor Aspek**
   ```
   Skor Aspek = Σ(Skor Elemen × Bobot Elemen)
   ```

3. **Skor Final**
   ```
   Skor Final = Σ(Skor Aspek × Bobot Aspek)
   ```

### Kategori Penilaian
- **86-100%**: Baik Sekali (Kategori Emas) 🥇
- **71-85%**: Baik (Kategori Perak) 🥈
- **56-70%**: Cukup (Kategori Perunggu) 🥉
- **< 55%**: Kurang (Tindakan Pembinaan) ⚠️

---

## 🛠️ Tools & Utilities

File di folder `tools/`:
- `setup_database.php` - Setup database otomatis
- `check_errors.php` - Check errors & warnings
- `simulasi_penilaian.php` - Simulasi penilaian (HTML)
- `test_simulasi_penilaian.php` - Simulasi penilaian (CLI)
- `test_*.php` - File testing
- `extract_*.py` - Script ekstraksi data

---

## 📚 Dokumentasi

Dokumentasi lengkap tersedia di folder `docs/dokumentasi/`:
- `ANALISIS_DAN_DESAIN_APLIKASI.md` - Analisis dan desain sistem
- `FUNGSI_LENGKAP_APLIKASI.md` - Daftar fitur lengkap
- `STATUS_APLIKASI.md` - Status implementasi
- `SPESIFIKASI_LAPORAN_LENGKAP.md` - Spesifikasi laporan
- `TEST_APLIKASI_LENGKAP.md` - Test plan
- `OPTIMASI_MOBILE_COMPLETE.md` - Dokumentasi optimasi mobile
- Dan lainnya...

---

## 🔒 Security

- ✅ Password hashing (bcrypt)
- ✅ Prepared statements (SQL injection prevention)
- ✅ Input sanitization
- ✅ CSRF protection
- ✅ Session management
- ✅ File upload validation

---

## 📱 Mobile Support

Aplikasi dioptimalkan untuk mobile-first:
- ✅ Responsive design (Bootstrap 5)
- ✅ Touch-friendly UI
- ✅ Bottom navigation (mobile)
- ✅ Card-based layout (mobile)
- ✅ Optimized font sizes & spacing

---

## 🗄️ Database

### Tabel Utama
- `users` - Data personil
- `objek_wisata` - Data objek wisata
- `aspek` - 6 aspek penilaian
- `elemen` - Elemen dalam aspek
- `kriteria` - ~150+ kriteria penilaian
- `penilaian` - Header penilaian
- `penilaian_detail` - Detail nilai per kriteria
- `referensi_dokumen` - File upload referensi

---

## 🐛 Troubleshooting

### Database Connection Error
- Pastikan MySQL berjalan di XAMPP
- Cek konfigurasi di `config/database.php`

### Session Error
- Pastikan folder `tmp` writable
- Cek `session.save_path` di php.ini

### Upload Error
- Pastikan folder `assets/uploads/` writable
- Cek `upload_max_filesize` di php.ini

---

## 📞 Support

Untuk pertanyaan atau masalah, silakan hubungi tim pengembang.

---

## 🌐 Deployment Online

Aplikasi ini **BISA** dijalankan secara online dengan memenuhi persyaratan berikut:

### ✅ Persyaratan Server Online
- PHP 7.4+ (disarankan PHP 8.0+)
- MySQL/MariaDB 5.7+
- Apache 2.4+ atau Nginx 1.18+
- PHP Extensions: mysqli, gd, mbstring, zip, fileinfo
- Storage minimal 100MB
- Memory minimal 128MB PHP memory limit

### 📤 Upload ke GitHub
```bash
git init
git add .
git commit -m "Initial commit"
git remote add origin https://github.com/82080038/risk-assessment-objek-wisata.git
git push -u origin main
```

### 📖 Panduan Deployment Lengkap
Lihat file **[DEPLOYMENT.md](DEPLOYMENT.md)** untuk panduan lengkap:
- Setup di Shared Hosting (cPanel)
- Setup di VPS/Cloud Server
- Konfigurasi database online
- Konfigurasi aplikasi online
- Security checklist
- Troubleshooting

---

## 📄 License & Copyright

Aplikasi ini dibuat untuk Polres Samosir.

**Diciptakan oleh:** AIPDA PATRI SIHALOHO, S.H., CPM  
**Phone:** 081265511982  
**© 2024**

---

**Versi:** 1.0  
**Update Terakhir:** 2024

