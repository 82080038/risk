# INSTALL & INTEGRASI PHP EXTENSION: GD
## Risk Assessment Objek Wisata - Polres Samosir

**Tanggal:** <?php echo date('d F Y H:i:s'); ?>

---

## ✅ INSTALASI GD EXTENSION

### Apa itu GD Extension?

GD (Graphics Draw) adalah PHP extension untuk manipulasi gambar. Extension ini diperlukan untuk:
- ✅ TCPDF (PDF generation dengan gambar)
- ✅ Image processing (resize, crop, watermark)
- ✅ Thumbnail generation
- ✅ Image validation

---

## 🔧 CARA MENGINSTALL (XAMPP Windows)

### Langkah 1: Buka php.ini

1. Buka **XAMPP Control Panel**
2. Klik **"Config"** di sebelah Apache
3. Pilih **"PHP (php.ini)"**
4. File `php.ini` akan terbuka di text editor

**Atau buka manual:**
```
C:\xampp\php\php.ini
```

### Langkah 2: Aktifkan Extension GD

1. Cari baris yang berisi:
   ```ini
   ;extension=gd
   ```

2. Hapus tanda `;` di depan `extension=gd`:
   ```ini
   extension=gd
   ```

3. **Pastikan** extension lain yang diperlukan juga aktif:
   ```ini
   extension=gd
   extension=mbstring
   extension=mysqli
   extension=curl
   ```

### Langkah 3: Restart Apache

1. Di **XAMPP Control Panel**
2. Klik **"Stop"** pada Apache
3. Tunggu beberapa detik
4. Klik **"Start"** pada Apache

### Langkah 4: Verifikasi

1. Buka: `http://localhost/RISK/tools/check_php_extensions.php`
2. Pastikan GD Library menunjukkan status **"LOADED"**
3. Check GD Library Details untuk memastikan fitur yang didukung

---

## 🧪 TEST INSTALASI

### Via Browser:
```
http://localhost/RISK/tools/check_php_extensions.php
```

### Via Command Line:
```bash
php -m | findstr gd
```

Jika muncul `gd`, berarti extension sudah aktif.

---

## ✅ VERIFIKASI GD FUNCTIONS

Setelah GD terinstall, pastikan fungsi-fungsi berikut tersedia:

- ✅ `imagecreate()` - Create image
- ✅ `imagejpeg()` - Output JPEG
- ✅ `imagepng()` - Output PNG
- ✅ `imagettftext()` - Text dengan TrueType font
- ✅ `imagesx()` - Get image width
- ✅ `imagesy()` - Get image height
- ✅ `imagecopyresampled()` - Resize image

---

## 📋 GD FEATURES YANG DIPERLUKAN

### Untuk TCPDF:
- ✅ **FreeType Support** - Untuk font rendering
- ✅ **JPEG Support** - Untuk gambar JPEG
- ✅ **PNG Support** - Untuk gambar PNG
- ✅ **GIF Support** - Untuk gambar GIF (opsional)

### Untuk Aplikasi:
- ✅ Image validation
- ✅ Thumbnail generation (jika diperlukan)
- ✅ Image processing (jika diperlukan)

---

## 🔍 CHECK GD INFO

Buka di browser:
```
http://localhost/RISK/tools/check_php_extensions.php
```

Script akan menampilkan:
- ✅ Status extension GD
- ✅ GD Library version
- ✅ Supported image formats
- ✅ FreeType support status

---

## ⚠️ TROUBLESHOOTING

### Error: "Call to undefined function imagecreate()"

**Penyebab:** Extension GD belum aktif

**Solusi:**
1. Pastikan `extension=gd` tidak ada tanda `;` di php.ini
2. Restart Apache
3. Check lagi dengan `check_php_extensions.php`

### Error: "FreeType Support: No"

**Penyebab:** FreeType belum terinstall atau tidak aktif

**Solusi:**
1. Pastikan file `freetype.dll` ada di folder `C:\xampp\php\`
2. Check php.ini untuk konfigurasi FreeType
3. Restart Apache

### Extension tidak aktif setelah restart

**Penyebab:** 
- Path extension salah
- File DLL tidak ditemukan
- Konflik dengan extension lain

**Solusi:**
1. Check error log Apache: `C:\xampp\apache\logs\error.log`
2. Pastikan file `php_gd2.dll` ada di `C:\xampp\php\ext\`
3. Check `extension_dir` di php.ini:
   ```ini
   extension_dir = "ext"
   ```

---

## 📝 INTEGRASI DENGAN APLIKASI

### 1. TCPDF
GD extension **otomatis digunakan** oleh TCPDF untuk:
- Render gambar di PDF
- Font rendering (jika FreeType aktif)
- Image processing

**Tidak perlu konfigurasi tambahan!**

### 2. File Upload Validation
Aplikasi sudah menggunakan GD untuk:
- Validasi file gambar
- Check image dimensions
- Verify image format

**File:**
- `api/upload.php` - File upload handler

### 3. Image Processing (Future)
Jika diperlukan, dapat digunakan untuk:
- Generate thumbnails
- Resize images
- Add watermarks

---

## ✅ CHECKLIST

- [ ] Extension GD aktif di php.ini
- [ ] Apache sudah di-restart
- [ ] GD Library terdeteksi (check via `check_php_extensions.php`)
- [ ] FreeType Support aktif (jika diperlukan)
- [ ] JPEG Support aktif
- [ ] PNG Support aktif
- [ ] TCPDF dapat menggunakan GD

---

## 🚀 QUICK START

1. **Aktifkan Extension:**
   - Buka `C:\xampp\php\php.ini`
   - Hapus `;` dari `;extension=gd`
   - Simpan file

2. **Restart Apache:**
   - XAMPP Control Panel → Stop Apache → Start Apache

3. **Verifikasi:**
   - Buka: `http://localhost/RISK/tools/check_php_extensions.php`
   - Pastikan GD Library: **LOADED**

---

## 📊 STATUS

**GD Extension Installation: ✅ READY**

Setelah GD terinstall:
- ✅ TCPDF dapat menggunakan GD untuk image processing
- ✅ File upload validation lebih baik
- ✅ Aplikasi siap untuk image manipulation (jika diperlukan)

---

**Status:** ✅ **GD EXTENSION INSTALLATION GUIDE READY**

