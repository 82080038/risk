# STRUKTUR APLIKASI
## Risk Assessment Objek Wisata - Polres Samosir

**Update:** <?php echo date('d F Y'); ?>

---

## 📁 Struktur Folder Lengkap

```
RISK/
│
├── 📄 index.php                    # Entry point aplikasi
├── 📄 logout.php                   # Logout handler
├── 📄 README.md                    # Dokumentasi utama
├── 📄 README_SETUP.md              # Panduan setup
├── 📄 .gitignore                   # Git ignore file
│
├── 📁 api/                         # API Endpoints
│   ├── api_base.php                # Base API (authentication, JSON response)
│   ├── dashboard.php               # API dashboard (statistik)
│   ├── kriteria.php                # API kriteria
│   ├── objek_wisata.php            # API CRUD objek wisata
│   ├── penilaian.php               # API CRUD penilaian
│   └── upload.php                  # API upload file referensi
│
├── 📁 assets/                      # Static Assets
│   ├── 📁 css/
│   │   └── custom.css              # Custom CSS (mobile-first)
│   ├── 📁 js/
│   │   ├── api.js                  # API helper functions
│   │   ├── app.js                  # Main app JavaScript
│   │   ├── dashboard.js            # Dashboard logic
│   │   ├── dashboard_charts.js     # Chart.js integration
│   │   ├── dynamic.js              # Dynamic rendering
│   │   └── penilaian_form.js       # Form penilaian logic
│   ├── 📁 images/                  # Images (logo, icons)
│   └── 📁 uploads/                 # Uploaded files (referensi dokumen)
│       └── .gitkeep                # Keep folder in git
│
├── 📁 config/                      # Konfigurasi
│   ├── config.php                  # Konfigurasi aplikasi (BASE_URL, session, dll)
│   └── database.php                # Konfigurasi database
│
├── 📁 docs/                        # Dokumentasi & File Acuan
│   ├── 📁 dokumentasi/            # Dokumentasi Teknis
│   │   ├── ANALISIS_DAN_DESAIN_APLIKASI.md
│   │   ├── CARA_SIMULASI_PENILAIAN.md
│   │   ├── DESAIN_GUI_UI.md
│   │   ├── FUNGSI_LENGKAP_APLIKASI.md
│   │   ├── JQUERY_DYNAMIC_SETUP.md
│   │   ├── OPTIMASI_MOBILE_COMPLETE.md
│   │   ├── PANDUAN_IMPORT_OBJEK_WISATA.md
│   │   ├── PANDUAN_IMPORT_PERSONIL.md
│   │   ├── PENILAIAN_OBJEKTIF_APLIKASI.md
│   │   ├── RINGKASAN_ANALISIS.md
│   │   ├── RINGKASAN_FINAL_TODOS.md
│   │   ├── RINGKASAN_OPTIMASI_MOBILE.md
│   │   ├── SETUP_COMPLETE.md
│   │   ├── SPESIFIKASI_LAPORAN_LENGKAP.md
│   │   ├── STATUS_APLIKASI.md
│   │   ├── TEMPLATE_KOP_SURAT.md
│   │   └── TEST_APLIKASI_LENGKAP.md
│   │
│   ├── 📁 file_acuan/             # File Referensi
│   │   ├── *.docx                 # Dokumen kriteria, petunjuk, spesifikasi
│   │   ├── *.xlsx                 # Data Excel (objek wisata, personil, risk assessment)
│   │   ├── *.pdf                  # Dokumen PDF
│   │   ├── *.txt                  # Ekstrak teks dari dokumen
│   │   ├── *.csv                  # Data CSV
│   │   └── *.jpg                  # Gambar referensi
│   │
│   └── STRUKTUR_APLIKASI.md       # File ini
│
├── 📁 includes/                    # Include Files
│   ├── csrf.php                   # CSRF protection
│   ├── footer.php                 # Footer HTML
│   ├── functions.php              # Helper functions
│   ├── header.php                 # Header HTML
│   ├── kop_surat.php             # Template kop surat untuk PDF
│   ├── laporan_template.php      # Template laporan PDF
│   ├── navbar.php                 # Navigation bar
│   └── pdf_generator.php          # PDF generator (TCPDF)
│
├── 📁 pages/                      # Halaman Aplikasi
│   ├── dashboard.php              # Dashboard utama
│   ├── login.php                  # Halaman login
│   ├── laporan.php                # Halaman laporan (list)
│   ├── laporan_generate.php       # Generate PDF laporan
│   ├── objek_wisata.php           # CRUD objek wisata
│   ├── penilaian.php              # Router penilaian
│   ├── penilaian_detail.php       # Detail penilaian
│   ├── penilaian_form.php         # Form penilaian
│   └── penilaian_list.php         # List penilaian
│
├── 📁 sql/                        # File SQL Database
│   ├── database.sql               # Struktur database (8 tabel)
│   ├── master_data.sql            # Data master (aspek, elemen, kriteria)
│   ├── data_personil.sql          # Data personil (opsional)
│   └── data_objek_wisata.sql     # Data objek wisata (opsional)
│
└── 📁 tools/                      # Tools & Utilities
    ├── check_errors.php           # Check errors & warnings
    ├── extract_data_wisata.py    # Script ekstraksi data wisata
    ├── extract_documents.py      # Script ekstraksi dokumen
    ├── extract_personil.py        # Script ekstraksi personil
    ├── extract_personil_excel.py  # Script ekstraksi personil dari Excel
    ├── generate_password_hash.php # Generate password hash
    ├── generate_password_hashes.py # Generate password hashes (batch)
    ├── setup_database.php         # Setup database otomatis
    ├── simulasi_penilaian.php     # Simulasi penilaian (HTML)
    ├── test_all_functions.php     # Test semua fungsi
    ├── test_application_complete.php # Test aplikasi lengkap
    ├── test_connection.php        # Test koneksi database
    └── test_simulasi_penilaian.php # Simulasi penilaian (CLI)
```

---

## 📋 Deskripsi Folder

### `/api/` - API Endpoints
Semua endpoint API untuk komunikasi AJAX. Menggunakan JSON response format.

### `/assets/` - Static Assets
- `css/` - Stylesheet custom (mobile-first design)
- `js/` - JavaScript files (jQuery, Chart.js integration)
- `images/` - Logo, icons, images
- `uploads/` - File upload referensi dokumen (writable)

### `/config/` - Konfigurasi
File konfigurasi aplikasi dan database.

### `/docs/` - Dokumentasi
- `dokumentasi/` - Dokumentasi teknis lengkap
- `file_acuan/` - File referensi (docx, xlsx, pdf, dll)

### `/includes/` - Include Files
File-file yang di-include di berbagai halaman (header, footer, functions, dll).

### `/pages/` - Halaman Aplikasi
Semua halaman aplikasi web.

### `/sql/` - File SQL
File SQL untuk setup database dan data master.

### `/tools/` - Tools & Utilities
Script-script untuk testing, setup, dan utilities.

---

## 🔍 File Penting

### Entry Point
- `index.php` - Redirect ke dashboard atau login

### Core Files
- `config/config.php` - Konfigurasi aplikasi
- `config/database.php` - Konfigurasi database
- `includes/functions.php` - Helper functions

### Main Pages
- `pages/login.php` - Authentication
- `pages/dashboard.php` - Dashboard utama
- `pages/penilaian_form.php` - Form penilaian
- `pages/penilaian_detail.php` - Detail penilaian
- `pages/laporan.php` - Halaman laporan

### API
- `api/api_base.php` - Base API
- `api/penilaian.php` - API penilaian
- `api/upload.php` - API upload

---

## ✅ Checklist Verifikasi

- [x] Struktur folder rapi dan terorganisir
- [x] File acuan dipindah ke `docs/file_acuan/`
- [x] Dokumentasi dipindah ke `docs/dokumentasi/`
- [x] Tools dipindah ke `tools/`
- [x] File yang tidak dibutuhkan sudah dihapus atau dipindah
- [x] README.md utama dibuat
- [x] .gitignore dibuat
- [x] Semua file penting ada dan valid

---

**Struktur aplikasi sudah rapi dan terorganisir!** ✅

