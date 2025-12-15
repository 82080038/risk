# TEMPLATE KOP SURAT
## Risk Assessment Objek Wisata - Polres Samosir

---

## 📋 SPESIFIKASI KOP SURAT

### Format Kop Surat:

```
┌─────────────────────────────────────────────────────────────┐
│ KEPOLISIAN NEGARA REPUBLIK INDONESIA                        │
│ DAERAH SUMATERA UTARA                                        │
│ RESOR SAMOSIR                                                │
│                                                             │
│ RISK ASSESMENT OBJEK WISATA [TAHUN]                         │
│ WILKUM POLRES SAMOSIR                                       │
│                                                             │
│ [Logo Polri - jika ada]                                     │
│                                                             │
│ [Judul Laporan Spesifik]                                    │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

---

## 📝 DETAIL KOP SURAT

### Posisi:
- **Lokasi:** Sudut kiri atas
- **Alignment:** Left (rata kiri)
- **Margin Top:** 1.5cm - 2cm
- **Margin Left:** 2cm - 2.5cm

### Format Teks:

**Baris 1:**
- **Teks:** "KEPOLISIAN NEGARA REPUBLIK INDONESIA"
- **Font:** Arial atau Times New Roman
- **Size:** 12pt - 14pt
- **Style:** Bold
- **Alignment:** Left

**Baris 2:**
- **Teks:** "DAERAH SUMATERA UTARA"
- **Font:** Arial atau Times New Roman
- **Size:** 12pt - 14pt
- **Style:** Bold
- **Alignment:** Left

**Baris 3:**
- **Teks:** "RESOR SAMOSIR"
- **Font:** Arial atau Times New Roman
- **Size:** 12pt - 14pt
- **Style:** Bold
- **Alignment:** Left

**Baris 4:** (Setelah spacing)
- **Teks:** "RISK ASSESMENT OBJEK WISATA [TAHUN]"
- **Font:** Arial atau Times New Roman
- **Size:** 12pt - 14pt
- **Style:** Bold
- **Alignment:** Left
- **Note:** [TAHUN] diganti dengan tahun aktual (contoh: 2025)

**Baris 5:**
- **Teks:** "WILKUM POLRES SAMOSIR"
- **Font:** Arial atau Times New Roman
- **Size:** 12pt - 14pt
- **Style:** Bold
- **Alignment:** Left

### Spacing:
- **Line Height:** 1.2 - 1.5
- **Spacing antar baris:** 0.3cm - 0.5cm
- **Spacing setelah kop:** 1cm - 1.5cm

---

## 🎨 CONTOH IMPLEMENTASI HTML/CSS

### HTML Structure:
```html
<div class="kop-surat">
    <div class="kop-line-1">KEPOLISIAN NEGARA REPUBLIK INDONESIA</div>
    <div class="kop-line-2">DAERAH SUMATERA UTARA</div>
    <div class="kop-line-3">RESOR SAMOSIR</div>
</div>
```

### CSS Style:
```css
.kop-surat {
    position: absolute;
    top: 1.5cm;
    left: 2cm;
    text-align: left;
    font-family: Arial, sans-serif;
}

.kop-line-1,
.kop-line-2,
.kop-line-3 {
    font-size: 12pt;
    font-weight: bold;
    line-height: 1.4;
    margin-bottom: 0.2cm;
}

.kop-line-1 {
    margin-top: 0;
}
```

---

## 📄 CONTOH UNTUK PDF (TCPDF/FPDF)

### PHP Code untuk TCPDF:
```php
// Set kop surat
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetXY(20, 15); // X=2cm, Y=1.5cm
$pdf->Cell(0, 6, 'KEPOLISIAN NEGARA REPUBLIK INDONESIA', 0, 1, 'L');
$pdf->SetXY(20, 21);
$pdf->Cell(0, 6, 'DAERAH SUMATERA UTARA', 0, 1, 'L');
$pdf->SetXY(20, 27);
$pdf->Cell(0, 6, 'RESOR SAMOSIR', 0, 1, 'L');

// Spacing
$pdf->SetXY(20, 35);

// RISK ASSESMENT OBJEK WISATA [TAHUN]
$tahun = date('Y'); // atau ambil dari parameter
$pdf->Cell(0, 6, 'RISK ASSESMENT OBJEK WISATA ' . $tahun, 0, 1, 'L');

// WILKUM POLRES SAMOSIR
$pdf->SetXY(20, 41);
$pdf->Cell(0, 6, 'WILKUM POLRES SAMOSIR', 0, 1, 'L');

// Garis pemisah (optional)
$pdf->SetLineWidth(0.5);
$pdf->Line(20, 49, 190, 49);
```

### PHP Code untuk FPDF:
```php
// Set kop surat
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetXY(20, 15);
$pdf->Cell(0, 6, 'KEPOLISIAN NEGARA REPUBLIK INDONESIA', 0, 1, 'L');
$pdf->SetXY(20, 21);
$pdf->Cell(0, 6, 'DAERAH SUMATERA UTARA', 0, 1, 'L');
$pdf->SetXY(20, 27);
$pdf->Cell(0, 6, 'RESOR SAMOSIR', 0, 1, 'L');
```

---

## 📋 CONTOH UNTUK DOMPDF (HTML to PDF)

### HTML Template:
```html
<!DOCTYPE html>
<html>
<head>
    <style>
        @page {
            margin: 2cm 2cm 2cm 2cm;
        }
        
        .kop-surat {
            position: absolute;
            top: 1.5cm;
            left: 2cm;
            font-family: Arial, sans-serif;
        }
        
        .kop-line {
            font-size: 12pt;
            font-weight: bold;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        
        .kop-line-spacing {
            margin-top: 0.3cm;
        }
        
        .garis-pemisah {
            border-top: 1px solid #000;
            margin-top: 0.5cm;
            margin-bottom: 1cm;
        }
    </style>
</head>
<body>
    <div class="kop-surat">
        <div class="kop-line">KEPOLISIAN NEGARA REPUBLIK INDONESIA</div>
        <div class="kop-line">DAERAH SUMATERA UTARA</div>
        <div class="kop-line">RESOR SAMOSIR</div>
        <div class="kop-line kop-line-spacing">RISK ASSESMENT OBJEK WISATA 2025</div>
        <div class="kop-line">WILKUM POLRES SAMOSIR</div>
        <div class="garis-pemisah"></div>
    </div>
    
    <!-- Konten laporan di bawah ini -->
    <div style="margin-top: 4cm;">
        <!-- Isi laporan -->
    </div>
</body>
</html>
```

---

## 🎯 PENGGUNAAN DI SEMUA LAPORAN

### Laporan yang Menggunakan Kop Surat:

1. ✅ **Laporan Penilaian Lengkap**
2. ✅ **Laporan Ringkasan/Executive Summary**
3. ✅ **Laporan Detail Per Aspek**
4. ✅ **Laporan History/Tracking**
5. ✅ **Laporan Data Personil Lengkap**
6. ✅ **Laporan Personil yang Telah Menilai**
7. ✅ **Laporan Histori Input Laporan**
8. ✅ **Laporan Data Objek Wisata**
9. ✅ **Laporan Cetak/Print**

### Posisi di Halaman:
- **Halaman Pertama:** Kop surat di bagian atas
- **Halaman Berikutnya:** Kop surat tetap ada (header) atau hanya nomor halaman

---

## 📐 DIMENSI DAN SPACING

### Ukuran Halaman:
- **Format:** A4
- **Width:** 21cm
- **Height:** 29.7cm

### Margin:
- **Top:** 2cm (untuk kop surat)
- **Left:** 2cm
- **Right:** 2cm
- **Bottom:** 2cm

### Posisi Kop Surat:
- **X (Left):** 2cm dari kiri
- **Y (Top):** 1.5cm - 2cm dari atas
- **Width:** Sesuai lebar teks
- **Height:** ~1.5cm (3 baris)

### Spacing Setelah Kop:
- **Margin Bottom:** 1cm - 1.5cm
- **Garis Pemisah:** Optional (0.5cm setelah kop)
- **Konten Mulai:** ~3cm dari atas halaman

---

## 🔤 FONT DAN TYPography

### Rekomendasi Font:
1. **Arial** (Sans-serif) - Modern, mudah dibaca
2. **Times New Roman** (Serif) - Formal, standar dokumen resmi
3. **Calibri** (Sans-serif) - Modern, professional

### Font Size:
- **Kop Surat:** 12pt - 14pt
- **Judul Laporan:** 14pt - 16pt (Bold)
- **Sub Judul:** 12pt - 13pt (Bold)
- **Isi:** 11pt - 12pt (Regular)

### Font Weight:
- **Kop Surat:** Bold
- **Judul:** Bold
- **Sub Judul:** Bold atau Semi-bold
- **Isi:** Regular

---

## 🎨 VARIASI DESAIN

### Desain 1: Sederhana (Tanpa Logo)
```
KEPOLISIAN NEGARA REPUBLIK INDONESIA
DAERAH SUMATERA UTARA
RESOR SAMOSIR
─────────────────────────────────────
```

### Desain 2: Dengan Logo (Jika Tersedia)
```
[LOGO]  KEPOLISIAN NEGARA REPUBLIK INDONESIA
        DAERAH SUMATERA UTARA
        RESOR SAMOSIR
─────────────────────────────────────
```

### Desain 3: Dengan Garis Pemisah Tebal
```
KEPOLISIAN NEGARA REPUBLIK INDONESIA
DAERAH SUMATERA UTARA
RESOR SAMOSIR
═════════════════════════════════════
```

---

## 📝 CONTOH IMPLEMENTASI LENGKAP

### File: `includes/kop_surat.php`
```php
<?php
function generateKopSurat($pdf) {
    // Set font
    $pdf->SetFont('Arial', 'B', 12);
    
    // Set position (sudut kiri atas)
    $pdf->SetXY(20, 15); // X=2cm, Y=1.5cm
    
    // Baris 1
    $pdf->Cell(0, 6, 'KEPOLISIAN NEGARA REPUBLIK INDONESIA', 0, 1, 'L');
    
    // Baris 2
    $pdf->SetXY(20, 21);
    $pdf->Cell(0, 6, 'DAERAH SUMATERA UTARA', 0, 1, 'L');
    
    // Baris 3
    $pdf->SetXY(20, 27);
    $pdf->Cell(0, 6, 'RESOR SAMOSIR', 0, 1, 'L');
    
    // Garis pemisah (optional)
    $pdf->SetLineWidth(0.5);
    $pdf->Line(20, 35, 190, 35);
    
    // Return Y position untuk konten berikutnya
    return 40; // 3.5cm dari atas
}
?>
```

### Penggunaan:
```php
<?php
require_once 'includes/kop_surat.php';

// Generate PDF
$pdf = new TCPDF();
$pdf->AddPage();

// Tambahkan kop surat (dengan tahun)
$tahun = date('Y'); // atau ambil dari database/parameter
$y_position = generateKopSuratTCPDF($pdf, 20, 15, 'Arial', 12, $tahun);

// Lanjutkan dengan konten
$pdf->SetXY(20, $y_position);
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 6, 'LAPORAN PENILAIAN RISIKO OBJEK WISATA', 0, 1, 'C');
// ... konten lainnya
?>
```

---

## ✅ CHECKLIST IMPLEMENTASI

- [ ] Kop surat di sudut kiri atas
- [ ] Teks: "KEPOLISIAN NEGARA REPUBLIK INDONESIA"
- [ ] Teks: "DAERAH SUMATERA UTARA"
- [ ] Teks: "RESOR SAMOSIR"
- [ ] Font: Arial atau Times New Roman, Bold, 12pt
- [ ] Alignment: Left (rata kiri)
- [ ] Margin: 2cm dari kiri, 1.5-2cm dari atas
- [ ] Spacing: Line height 1.4, spacing antar baris 0.2-0.3cm
- [ ] Garis pemisah (optional): 0.5cm setelah kop
- [ ] Konsisten di semua halaman pertama
- [ ] Header di halaman berikutnya (opsional)

---

## 📌 CATATAN PENTING

1. **Konsistensi:** Kop surat harus sama di semua laporan
2. **Posisi:** Selalu di sudut kiri atas
3. **Format:** Teks harus sesuai (huruf besar, tidak ada typo)
4. **Spacing:** Berikan jarak yang cukup setelah kop sebelum konten
5. **Header:** Untuk halaman berikutnya, bisa menggunakan kop yang lebih kecil atau hanya nomor halaman

---

**Template ini akan digunakan untuk semua laporan PDF yang dihasilkan aplikasi.**

