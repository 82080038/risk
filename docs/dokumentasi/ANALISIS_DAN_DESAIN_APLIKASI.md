# ANALISIS DAN DESAIN APLIKASI WEB
## Risk Assessment Objek Wisata

---

## 1. ANALISIS KEBUTUHAN SISTEM

### 1.1 Tujuan Aplikasi
Aplikasi web untuk melakukan penilaian risiko (Risk Assessment) pada objek wisata berdasarkan 6 aspek kriteria dengan sistem scoring 0-2.

### 1.2 Fitur Utama
1. **Manajemen Data Objek Wisata**
   - Input data objek wisata (nama, alamat)
   - Daftar objek wisata yang sudah dinilai
   - History penilaian per objek

2. **Sistem Penilaian**
   - Form penilaian untuk 6 aspek:
     - Infrastruktur (Bobot: 0.2)
     - Keamanan (Bobot: 0.2)
     - Keselamatan (Bobot: 0.25)
     - Kesehatan (Bobot: 0.1)
     - Sistem Pengamanan (Bobot: 0.15)
     - Informasi (Bobot: 0.1)
   - Setiap kriteria dinilai: 0, 1, atau 2
   - Input temuan (wajib untuk nilai 0 dan 1)
   - Input rekomendasi (wajib untuk nilai 0 dan 1)
   - Upload referensi dokumen/foto

3. **Perhitungan Skor**
   - Perhitungan otomatis skor per elemen
   - Perhitungan otomatis skor per aspek
   - Perhitungan skor final (total)
   - Kategori penilaian:
     - 86% - 100%: Baik Sekali (Kategori Emas)
     - 71% - 85%: Baik (Kategori Perak)
     - 56% - 70%: Cukup (Kategori Perunggu)
     - < 55%: Kurang (Tindakan Pembinaan)

4. **Laporan dan Dokumentasi**
   - Cetak laporan penilaian
   - Export ke PDF/Excel
   - History penilaian

5. **Manajemen User**
   - Login/Logout
   - Data penilai (Nama, Pangkat/NRP)
   - Role management (jika diperlukan)

### 1.3 Aturan Bisnis
1. **Sistem Penilaian:**
   - Nilai 0: Tidak dapat dipenuhi → WAJIB isi temuan dan rekomendasi
   - Nilai 1: Terdapat kekurangan → WAJIB isi temuan dan rekomendasi
   - Nilai 2: Dapat dipenuhi → Tidak perlu temuan dan rekomendasi

2. **Perhitungan Skor:**
   - Skor Elemen = (Jumlah nilai kriteria / (Jumlah kriteria × 2)) × 100
   - Skor Aspek = Σ(Skor Elemen × Bobot Elemen)
   - Skor Final = Σ(Skor Aspek × Bobot Aspek)

3. **Validasi:**
   - Semua kriteria harus dinilai
   - Temuan dan rekomendasi wajib untuk nilai 0 dan 1
   - Referensi dokumen/foto dapat diupload (opsional)

---

## 2. DESAIN DATABASE

### 2.1 Entity Relationship Diagram (Konseptual)

```
[objek_wisata] 1---N [penilaian]
[penilaian] 1---N [penilaian_detail]
[aspek] 1---N [elemen]
[elemen] 1---N [kriteria]
[kriteria] 1---N [penilaian_detail]
[penilaian] 1---N [referensi_dokumen]
[users] 1---N [penilaian]
```

### 2.2 Struktur Tabel Database

#### Tabel: `objek_wisata`
```sql
CREATE TABLE objek_wisata (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(255) NOT NULL,
    alamat TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_nama (nama)
);
```

#### Tabel: `aspek`
```sql
CREATE TABLE aspek (
    id INT PRIMARY KEY AUTO_INCREMENT,
    kode VARCHAR(10) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    bobot DECIMAL(5,2) NOT NULL,
    urutan INT NOT NULL,
    INDEX idx_urutan (urutan)
);
```

#### Tabel: `elemen`
```sql
CREATE TABLE elemen (
    id INT PRIMARY KEY AUTO_INCREMENT,
    aspek_id INT NOT NULL,
    kode VARCHAR(10) NOT NULL,
    nama VARCHAR(255) NOT NULL,
    bobot DECIMAL(5,2) NOT NULL,
    urutan INT NOT NULL,
    FOREIGN KEY (aspek_id) REFERENCES aspek(id) ON DELETE CASCADE,
    INDEX idx_aspek (aspek_id),
    INDEX idx_urutan (urutan)
);
```

#### Tabel: `kriteria`
```sql
CREATE TABLE kriteria (
    id INT PRIMARY KEY AUTO_INCREMENT,
    elemen_id INT NOT NULL,
    nomor INT NOT NULL,
    deskripsi TEXT NOT NULL,
    urutan INT NOT NULL,
    FOREIGN KEY (elemen_id) REFERENCES elemen(id) ON DELETE CASCADE,
    INDEX idx_elemen (elemen_id),
    INDEX idx_urutan (urutan)
);
```

#### Tabel: `users`
```sql
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    pangkat_nrp VARCHAR(100),
    role ENUM('admin', 'penilai') DEFAULT 'penilai',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_username (username)
);
```

#### Tabel: `penilaian`
```sql
CREATE TABLE penilaian (
    id INT PRIMARY KEY AUTO_INCREMENT,
    objek_wisata_id INT NOT NULL,
    user_id INT NOT NULL,
    tanggal_penilaian DATE NOT NULL,
    nama_penilai VARCHAR(100) NOT NULL,
    pangkat_nrp VARCHAR(100),
    skor_final DECIMAL(5,2) DEFAULT 0,
    kategori VARCHAR(50),
    status ENUM('draft', 'selesai') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (objek_wisata_id) REFERENCES objek_wisata(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_objek (objek_wisata_id),
    INDEX idx_user (user_id),
    INDEX idx_tanggal (tanggal_penilaian)
);
```

#### Tabel: `penilaian_detail`
```sql
CREATE TABLE penilaian_detail (
    id INT PRIMARY KEY AUTO_INCREMENT,
    penilaian_id INT NOT NULL,
    kriteria_id INT NOT NULL,
    nilai INT NOT NULL CHECK (nilai IN (0, 1, 2)),
    temuan TEXT,
    rekomendasi TEXT,
    skor_elemen DECIMAL(5,2) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (penilaian_id) REFERENCES penilaian(id) ON DELETE CASCADE,
    FOREIGN KEY (kriteria_id) REFERENCES kriteria(id) ON DELETE CASCADE,
    UNIQUE KEY unique_penilaian_kriteria (penilaian_id, kriteria_id),
    INDEX idx_penilaian (penilaian_id),
    INDEX idx_kriteria (kriteria_id)
);
```

#### Tabel: `referensi_dokumen`
```sql
CREATE TABLE referensi_dokumen (
    id INT PRIMARY KEY AUTO_INCREMENT,
    penilaian_id INT NOT NULL,
    kriteria_id INT,
    nama_file VARCHAR(255) NOT NULL,
    path_file VARCHAR(500) NOT NULL,
    tipe_file VARCHAR(50),
    ukuran_file INT,
    deskripsi TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (penilaian_id) REFERENCES penilaian(id) ON DELETE CASCADE,
    FOREIGN KEY (kriteria_id) REFERENCES kriteria(id) ON DELETE SET NULL,
    INDEX idx_penilaian (penilaian_id),
    INDEX idx_kriteria (kriteria_id)
);
```

### 2.3 Data Master yang Perlu Diinput

#### Data Aspek (6 aspek):
1. INFRASTRUKTUR (Bobot: 0.2)
2. KEAMANAN (Bobot: 0.2)
3. KESELAMATAN (Bobot: 0.25)
4. KESEHATAN (Bobot: 0.1)
5. SISTEM PENGAMANAN (Bobot: 0.15)
6. INFORMASI (Bobot: 0.1)

#### Data Elemen dan Kriteria:
- Perlu diinput berdasarkan struktur dari file Excel
- Setiap aspek memiliki beberapa elemen
- Setiap elemen memiliki beberapa kriteria

---

## 3. DESAIN GUI/UI (MOBILE-FIRST)

### 3.1 Prinsip Desain
1. **Mobile-First Approach**: Desain dimulai dari layar kecil (mobile) kemudian ditingkatkan untuk desktop
2. **Responsive Design**: Menggunakan Bootstrap 5 untuk responsivitas
3. **Touch-Friendly**: Tombol dan input area cukup besar untuk sentuhan
4. **Progressive Disclosure**: Informasi ditampilkan bertahap untuk menghindari overload
5. **Clear Navigation**: Navigasi jelas dan mudah diakses

### 3.2 Struktur Halaman

#### 3.2.1 Layout Umum
```
┌─────────────────────────────────────┐
│         HEADER / NAVBAR             │
├─────────────────────────────────────┤
│                                     │
│         CONTENT AREA                │
│         (Scrollable)                │
│                                     │
├─────────────────────────────────────┤
│         FOOTER (Optional)           │
└─────────────────────────────────────┘
```

#### 3.2.2 Halaman Utama (Dashboard)
- **Mobile:**
  - Card grid 1 kolom
  - Tombol besar untuk aksi utama
  - Statistik ringkas (jika ada)
  
- **Desktop:**
  - Card grid 2-3 kolom
  - Sidebar navigasi (opsional)
  - Statistik lebih detail

#### 3.2.3 Form Penilaian
- **Mobile:**
  - Accordion/Collapse per aspek
  - Satu aspek per view (step-by-step)
  - Tombol navigasi Previous/Next
  - Progress indicator
  - Sticky bottom bar untuk tombol Save/Submit
  
- **Desktop:**
  - Tabs per aspek
  - Sidebar untuk navigasi cepat antar aspek
  - Form lebih lebar, 2 kolom jika memungkinkan

#### 3.2.4 Form Input Kriteria
```
┌─────────────────────────────────────┐
│ [Icon] Deskripsi Kriteria           │
│ Memiliki parameter pembatas...       │
├─────────────────────────────────────┤
│ Nilai: [○] 0  [○] 1  [●] 2         │
├─────────────────────────────────────┤
│ Temuan: (muncul jika nilai 0/1)     │
│ [Textarea]                           │
├─────────────────────────────────────┤
│ Rekomendasi: (muncul jika nilai 0/1)│
│ [Textarea]                           │
├─────────────────────────────────────┤
│ Referensi:                          │
│ [Upload File] [Preview]              │
└─────────────────────────────────────┘
```

### 3.3 Komponen UI yang Dibutuhkan

1. **Form Components:**
   - Radio buttons (untuk nilai 0,1,2) - besar untuk mobile
   - Textarea (untuk temuan dan rekomendasi)
   - File upload (dengan preview)
   - Input text/select (untuk data objek wisata)

2. **Navigation Components:**
   - Bottom navigation bar (mobile)
   - Sidebar navigation (desktop)
   - Breadcrumb
   - Progress bar/stepper

3. **Display Components:**
   - Cards (untuk summary)
   - Tables (untuk daftar, responsive)
   - Accordion/Collapse (untuk grouping)
   - Tabs (untuk desktop)
   - Badges (untuk status/kategori)

4. **Feedback Components:**
   - Toast notifications
   - Modal dialogs
   - Loading spinners
   - Alert messages

### 3.4 Skema Warna (Saran)
- **Primary**: Biru (#0d6efd) - Bootstrap default
- **Success**: Hijau (#198754) - untuk nilai 2
- **Warning**: Kuning (#ffc107) - untuk nilai 1
- **Danger**: Merah (#dc3545) - untuk nilai 0
- **Info**: Biru muda (#0dcaf0) - untuk informasi
- **Background**: Putih/Light gray
- **Text**: Dark gray/Black

### 3.5 Typography
- **Font Family**: System fonts (Arial, Helvetica, sans-serif)
- **Heading**: Bold, ukuran responsif
- **Body**: 14-16px untuk mobile, 16px untuk desktop
- **Line Height**: 1.5-1.6 untuk readability

---

## 4. ARSITEKTUR APLIKASI

### 4.1 Teknologi Stack
- **Frontend:**
  - HTML5
  - CSS3 (Bootstrap 5)
  - JavaScript (jQuery 3.x)
  - Font Awesome (untuk icons)

- **Backend:**
  - PHP 7.4+ (Native PHP, tidak menggunakan framework)
  - MySQL/MariaDB

- **Server:**
  - Apache (XAMPP)
  - PHPMyAdmin (untuk manajemen database)

### 4.2 Struktur Folder
```
RISK/
├── assets/
│   ├── css/
│   │   ├── bootstrap.min.css
│   │   ├── custom.css
│   │   └── mobile.css
│   ├── js/
│   │   ├── jquery.min.js
│   │   ├── bootstrap.bundle.min.js
│   │   ├── app.js
│   │   └── penilaian.js
│   ├── images/
│   │   └── (logo, icons, dll)
│   └── uploads/
│       └── (file referensi dokumen)
├── config/
│   ├── database.php
│   └── config.php
├── includes/
│   ├── header.php
│   ├── footer.php
│   ├── navbar.php
│   └── functions.php
├── api/
│   ├── objek_wisata.php
│   ├── penilaian.php
│   ├── kriteria.php
│   └── upload.php
├── pages/
│   ├── login.php
│   ├── dashboard.php
│   ├── objek_wisata.php
│   ├── penilaian.php
│   ├── laporan.php
│   └── profile.php
├── sql/
│   ├── database.sql
│   └── master_data.sql
├── index.php
└── logout.php
```

### 4.3 Alur Aplikasi

#### 4.3.1 Alur Login
```
User → Login Page → Validasi → Dashboard
```

#### 4.3.2 Alur Penilaian
```
Dashboard → Pilih Objek Wisata → Form Penilaian → 
Input Per Aspek → Validasi → Simpan Draft/Submit → 
Perhitungan Skor → Laporan
```

#### 4.3.3 Alur API (AJAX)
```
Frontend (jQuery) → API Endpoint (PHP) → Database → 
Response (JSON) → Update UI
```

---

## 5. FITUR TEKNIS

### 5.1 Validasi Form
- **Client-side:** JavaScript/jQuery
- **Server-side:** PHP
- **Validasi:**
  - Semua kriteria harus dinilai
  - Temuan wajib jika nilai 0 atau 1
  - Rekomendasi wajib jika nilai 0 atau 1
  - Format file upload (jpg, png, pdf, doc, docx)
  - Ukuran file maksimal (misal: 5MB)

### 5.2 Perhitungan Skor
```javascript
// Pseudocode
function hitungSkorElemen(elemenId) {
    let totalNilai = 0;
    let jumlahKriteria = 0;
    
    // Ambil semua nilai kriteria dalam elemen
    kriteria.forEach(k => {
        totalNilai += k.nilai;
        jumlahKriteria++;
    });
    
    // Skor = (total nilai / (jumlah kriteria × 2)) × 100
    return (totalNilai / (jumlahKriteria * 2)) * 100;
}

function hitungSkorAspek(aspekId) {
    let totalSkor = 0;
    
    // Ambil semua elemen dalam aspek
    elemen.forEach(e => {
        let skorElemen = hitungSkorElemen(e.id);
        totalSkor += skorElemen * e.bobot;
    });
    
    return totalSkor;
}

function hitungSkorFinal() {
    let skorFinal = 0;
    
    // Ambil semua aspek
    aspek.forEach(a => {
        let skorAspek = hitungSkorAspek(a.id);
        skorFinal += skorAspek * a.bobot;
    });
    
    return skorFinal;
}

function tentukanKategori(skorFinal) {
    if (skorFinal >= 86) return "Baik Sekali (Kategori Emas)";
    if (skorFinal >= 71) return "Baik (Kategori Perak)";
    if (skorFinal >= 56) return "Cukup (Kategori Perunggu)";
    return "Kurang (Tindakan Pembinaan)";
}
```

### 5.3 Upload File
- **Lokasi:** `assets/uploads/[penilaian_id]/[kriteria_id]/`
- **Naming:** `[timestamp]_[original_filename]`
- **Validasi:** Tipe file, ukuran file
- **Security:** Sanitasi nama file, cek ekstensi

### 5.4 Session Management
- PHP Session untuk autentikasi
- Session timeout (misal: 2 jam)
- CSRF protection untuk form

---

## 6. RESPONSIVE BREAKPOINTS (Bootstrap 5)

- **xs:** < 576px (Mobile portrait)
- **sm:** ≥ 576px (Mobile landscape)
- **md:** ≥ 768px (Tablet)
- **lg:** ≥ 992px (Desktop)
- **xl:** ≥ 1200px (Large desktop)
- **xxl:** ≥ 1400px (Extra large desktop)

### 6.1 Strategi Mobile-First
1. **Base styles:** Untuk mobile (xs)
2. **Media queries:** Tambahkan untuk layar lebih besar
3. **Grid system:** Gunakan Bootstrap grid
4. **Navigation:** Hamburger menu untuk mobile, sidebar untuk desktop
5. **Forms:** Full width untuk mobile, 2 kolom untuk desktop

---

## 7. KESIMPULAN DAN REKOMENDASI

### 7.1 Prioritas Pengembangan
1. **Phase 1:** Setup database dan struktur folder
2. **Phase 2:** Halaman login dan dashboard
3. **Phase 3:** Manajemen objek wisata
4. **Phase 4:** Form penilaian (satu aspek dulu)
5. **Phase 5:** Perhitungan skor dan laporan
6. **Phase 6:** Upload file dan referensi
7. **Phase 7:** Testing dan optimasi

### 7.2 Best Practices
1. **Security:**
   - Prepared statements untuk SQL
   - Input validation dan sanitization
   - File upload security
   - Password hashing (bcrypt)

2. **Performance:**
   - Lazy loading untuk gambar
   - Minify CSS/JS
   - Database indexing
   - Caching jika diperlukan

3. **UX:**
   - Loading indicators
   - Auto-save draft
   - Confirmation dialogs
   - Error messages yang jelas

4. **Code Quality:**
   - Kode terstruktur dan terorganisir
   - Comments untuk fungsi kompleks
   - Consistent naming convention
   - Separation of concerns

---

## 8. CATATAN TAMBAHAN

- File dokumen asli tetap digunakan sebagai referensi
- Struktur database dapat disesuaikan jika diperlukan
- Desain UI dapat dikustomisasi sesuai kebutuhan
- Fitur tambahan dapat ditambahkan di kemudian hari

---

**Dokumen ini akan terus diperbarui seiring dengan perkembangan pengembangan aplikasi.**

