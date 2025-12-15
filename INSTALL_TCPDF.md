# Instalasi TCPDF untuk PDF Generator

## Cara Instalasi

### Opsi 1: Menggunakan Composer (Direkomendasikan)

1. Pastikan Composer sudah terinstall di sistem Anda
2. Buka terminal/command prompt di folder project
3. Jalankan perintah:
```bash
composer install
```

Atau jika composer.json sudah ada, jalankan:
```bash
composer require tecnickcom/tcpdf
```

### Opsi 2: Download Manual

1. Download TCPDF dari: https://github.com/tecnickcom/TCPDF
2. Extract file ke folder `vendor/tecnickcom/tcpdf/`
3. Pastikan struktur folder:
```
vendor/
  tecnickcom/
    tcpdf/
      tcpdf.php
      (file-file lainnya)
```

### Opsi 3: Menggunakan XAMPP (Windows)

1. Download TCPDF dari: https://github.com/tecnickcom/TCPDF
2. Extract ke folder: `C:\xampp\php\vendor\tecnickcom\tcpdf\`
3. Atau extract ke folder project: `E:\xampp\htdocs\RISK\vendor\tecnickcom\tcpdf\`

## Verifikasi Instalasi

Setelah instalasi, buka file `pages/laporan_generate.php` dan pastikan:
- File `vendor/tecnickcom/tcpdf/tcpdf.php` ada
- Class `TCPDF` dapat di-load

## Catatan

- Jika TCPDF tidak terinstall, aplikasi akan menggunakan fallback HTML output yang dapat dicetak ke PDF melalui browser
- Untuk full functionality, disarankan menggunakan TCPDF

## Troubleshooting

### Error: Class 'TCPDF' not found
- Pastikan path ke TCPDF benar
- Cek file `includes/pdf_generator.php` untuk path yang benar
- Pastikan `vendor/tecnickcom/tcpdf/tcpdf.php` ada

### Error: Cannot create TCPDF object
- Pastikan PHP version >= 7.4
- Cek memory_limit di php.ini (minimal 128M)
- Pastikan extension PHP yang diperlukan sudah aktif (mbstring, gd, etc)

