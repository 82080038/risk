# RENCANA PELENGKAPAN APLIKASI
## Berdasarkan Analisis File Acuan

**Tanggal:** <?php echo date('d F Y H:i:s'); ?>

---

## 🎯 PRIORITAS TINGGI (Harus Dilengkapi)

### 1. **UI Upload Referensi Dokumen/Foto** 🔴
**Status:** API sudah ada, UI belum lengkap

**File yang perlu diupdate:**
- `pages/penilaian_form.php` - Tambah UI upload per kriteria
- `assets/js/penilaian_form.js` - Handler upload file
- `pages/penilaian_detail.php` - Tampilkan file yang sudah diupload

**Fitur yang perlu:**
- [ ] Button upload file per kriteria (muncul jika nilai 0 atau 1)
- [ ] Preview file yang sudah diupload
- [ ] Delete file yang sudah diupload
- [ ] Multiple file upload (dokumen + foto)
- [ ] Validasi tipe file (PDF, DOCX, JPG, PNG)
- [ ] Validasi ukuran file (max 5MB per file)

### 2. **PDF Generator Lengkap** 🔴
**Status:** Template sudah ada, perlu TCPDF

**File yang perlu diupdate:**
- `pages/laporan_generate.php` - Install TCPDF
- `includes/kop_surat.php` - Pastikan format sesuai
- Template laporan lengkap

**Fitur yang perlu:**
- [ ] Install TCPDF atau DomPDF
- [ ] Template laporan sesuai format file acuan
- [ ] Kop surat resmi Polri
- [ ] Tampilkan semua detail penilaian
- [ ] Tampilkan referensi dokumen/foto di PDF
- [ ] Tanda tangan digital/scan
- [ ] Nomor surat otomatis

### 3. **Tampilkan Referensi di Detail Penilaian** 🟡
**Status:** Detail page sudah ada, perlu tambah referensi

**File yang perlu diupdate:**
- `pages/penilaian_detail.php` - Tampilkan file referensi

**Fitur yang perlu:**
- [ ] Tampilkan daftar file referensi per kriteria
- [ ] Preview file (jika gambar)
- [ ] Download file
- [ ] Tampilkan di card view (mobile)

---

## 🟡 PRIORITAS SEDANG (Sebaiknya Dilengkapi)

### 4. **Export/Import Data** 🟡
**Fitur yang perlu:**
- [ ] Export data objek wisata ke CSV/Excel
- [ ] Import data objek wisata dari CSV/Excel
- [ ] Export data penilaian ke Excel
- [ ] Export statistik ke Excel
- [ ] Template import sesuai format file acuan

### 5. **Validasi Referensi Dokumen** 🟡
**Fitur yang perlu:**
- [ ] Warning jika nilai 0/1 tanpa referensi dokumen
- [ ] Validasi minimal 1 file untuk nilai 0
- [ ] Validasi semua kriteria harus dinilai sebelum submit

### 6. **Laporan Statistik Detail** 🟡
**Fitur yang perlu:**
- [ ] Laporan statistik per aspek
- [ ] Laporan statistik per objek wisata
- [ ] Laporan statistik per personil
- [ ] Export statistik ke PDF/Excel

---

## 🟢 PRIORITAS RENDAH (Nice to Have)

### 7. **User Management** 🟢
**Fitur yang perlu:**
- [ ] Admin: Manage users (CRUD)
- [ ] Change password
- [ ] Profile management
- [ ] Role management

### 8. **Advanced Features** 🟢
**Fitur yang perlu:**
- [ ] Activity log
- [ ] Notifications
- [ ] Backup database
- [ ] Restore database

---

## 📋 CHECKLIST PELENGKAPAN

### Fase 1: Core Features (Prioritas Tinggi)
- [ ] UI Upload Referensi Dokumen/Foto
- [ ] PDF Generator Lengkap dengan TCPDF
- [ ] Tampilkan Referensi di Detail Penilaian
- [ ] Tampilkan Referensi di PDF

### Fase 2: Supporting Features (Prioritas Sedang)
- [ ] Export/Import Data
- [ ] Validasi Referensi Dokumen
- [ ] Laporan Statistik Detail

### Fase 3: Advanced Features (Prioritas Rendah)
- [ ] User Management
- [ ] Activity Log
- [ ] Notifications

---

## 🚀 LANGKAH IMPLEMENTASI

### Step 1: Update Database
```sql
-- Jalankan: sql/alter_objek_wisata.sql
-- Atau update: sql/database.sql (sudah diupdate)
```

### Step 2: UI Upload Referensi
1. Update `pages/penilaian_form.php`
2. Update `assets/js/penilaian_form.js`
3. Test upload file

### Step 3: PDF Generator
1. Install TCPDF atau DomPDF
2. Update `pages/laporan_generate.php`
3. Test generate PDF

### Step 4: Tampilkan Referensi
1. Update `pages/penilaian_detail.php`
2. Update template PDF
3. Test tampilan

---

**Catatan:** Implementasi dilakukan secara bertahap sesuai prioritas.

