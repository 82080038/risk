<?php
/**
 * Template Kop Surat untuk Laporan PDF
 * KEPOLISIAN NEGARA REPUBLIK INDONESIA
 * DAERAH SUMATERA UTARA
 * RESOR SAMOSIR
 */

/**
 * Generate kop surat untuk TCPDF
 * 
 * @param TCPDF $pdf Instance TCPDF
 * @param float $x Posisi X (default: 20 = 2cm)
 * @param float $y Posisi Y (default: 15 = 1.5cm)
 * @param string $font Font family (default: 'Arial')
 * @param int $font_size Font size (default: 12)
 * @return float Y position setelah kop surat
 */
function generateKopSuratTCPDF($pdf, $x = 20, $y = 15, $font = 'Arial', $font_size = 12, $tahun = null) {
    // Set font
    $pdf->SetFont($font, 'B', $font_size);
    
    // Baris 1: KEPOLISIAN NEGARA REPUBLIK INDONESIA
    $pdf->SetXY($x, $y);
    $pdf->Cell(0, 6, 'KEPOLISIAN NEGARA REPUBLIK INDONESIA', 0, 1, 'L');
    
    // Baris 2: DAERAH SUMATERA UTARA
    $pdf->SetXY($x, $y + 6);
    $pdf->Cell(0, 6, 'DAERAH SUMATERA UTARA', 0, 1, 'L');
    
    // Baris 3: RESOR SAMOSIR
    $pdf->SetXY($x, $y + 12);
    $pdf->Cell(0, 6, 'RESOR SAMOSIR', 0, 1, 'L');
    
    // Spacing
    $y_current = $y + 20;
    
    // Baris 4: RISK ASSESMENT OBJEK WISATA [TAHUN]
    if ($tahun === null) {
        $tahun = date('Y'); // Default: tahun sekarang
    }
    $pdf->SetXY($x, $y_current);
    $pdf->Cell(0, 6, 'RISK ASSESMENT OBJEK WISATA ' . $tahun, 0, 1, 'L');
    
    // Baris 5: WILKUM POLRES SAMOSIR
    $pdf->SetXY($x, $y_current + 6);
    $pdf->Cell(0, 6, 'WILKUM POLRES SAMOSIR', 0, 1, 'L');
    
    // Garis pemisah (optional)
    $pdf->SetLineWidth(0.5);
    $pdf->Line($x, $y_current + 14, 190, $y_current + 14);
    
    // Return Y position untuk konten berikutnya
    return $y_current + 20; // 3cm dari posisi awal
}

/**
 * Generate kop surat untuk FPDF
 * 
 * @param FPDF $pdf Instance FPDF
 * @param float $x Posisi X (default: 20 = 2cm)
 * @param float $y Posisi Y (default: 15 = 1.5cm)
 * @param string $font Font family (default: 'Arial')
 * @param int $font_size Font size (default: 12)
 * @return float Y position setelah kop surat
 */
function generateKopSuratFPDF($pdf, $x = 20, $y = 15, $font = 'Arial', $font_size = 12, $tahun = null) {
    // Set font
    $pdf->SetFont($font, 'B', $font_size);
    
    // Baris 1: KEPOLISIAN NEGARA REPUBLIK INDONESIA
    $pdf->SetXY($x, $y);
    $pdf->Cell(0, 6, 'KEPOLISIAN NEGARA REPUBLIK INDONESIA', 0, 1, 'L');
    
    // Baris 2: DAERAH SUMATERA UTARA
    $pdf->SetXY($x, $y + 6);
    $pdf->Cell(0, 6, 'DAERAH SUMATERA UTARA', 0, 1, 'L');
    
    // Baris 3: RESOR SAMOSIR
    $pdf->SetXY($x, $y + 12);
    $pdf->Cell(0, 6, 'RESOR SAMOSIR', 0, 1, 'L');
    
    // Spacing
    $y_current = $y + 20;
    
    // Baris 4: RISK ASSESMENT OBJEK WISATA [TAHUN]
    if ($tahun === null) {
        $tahun = date('Y'); // Default: tahun sekarang
    }
    $pdf->SetXY($x, $y_current);
    $pdf->Cell(0, 6, 'RISK ASSESMENT OBJEK WISATA ' . $tahun, 0, 1, 'L');
    
    // Baris 5: WILKUM POLRES SAMOSIR
    $pdf->SetXY($x, $y_current + 6);
    $pdf->Cell(0, 6, 'WILKUM POLRES SAMOSIR', 0, 1, 'L');
    
    // Garis pemisah (optional)
    $pdf->SetLineWidth(0.5);
    $pdf->Line($x, $y_current + 14, 190, $y_current + 14);
    
    // Return Y position untuk konten berikutnya
    return $y_current + 20;
}

/**
 * Generate kop surat dalam format HTML (untuk DomPDF)
 * 
 * @return string HTML kop surat
 */
function generateKopSuratHTML($tahun = null) {
    if ($tahun === null) {
        $tahun = date('Y'); // Default: tahun sekarang
    }
    
    $html = '
    <div style="position: absolute; top: 1.5cm; left: 2cm; font-family: Arial, sans-serif;">
        <div style="font-size: 12pt; font-weight: bold; line-height: 1.4; margin: 0; padding: 0;">
            KEPOLISIAN NEGARA REPUBLIK INDONESIA
        </div>
        <div style="font-size: 12pt; font-weight: bold; line-height: 1.4; margin: 0; padding: 0;">
            DAERAH SUMATERA UTARA
        </div>
        <div style="font-size: 12pt; font-weight: bold; line-height: 1.4; margin: 0; padding: 0;">
            RESOR SAMOSIR
        </div>
        <div style="font-size: 12pt; font-weight: bold; line-height: 1.4; margin: 0.3cm 0 0 0; padding: 0;">
            RISK ASSESMENT OBJEK WISATA ' . $tahun . '
        </div>
        <div style="font-size: 12pt; font-weight: bold; line-height: 1.4; margin: 0; padding: 0;">
            WILKUM POLRES SAMOSIR
        </div>
        <div style="border-top: 1px solid #000; margin-top: 0.5cm; margin-bottom: 1cm; width: 100%;"></div>
    </div>
    ';
    
    return $html;
}

/**
 * Generate kop surat dengan logo (jika tersedia)
 * 
 * @param TCPDF $pdf Instance TCPDF
 * @param string $logo_path Path ke file logo (optional)
 * @param float $x Posisi X
 * @param float $y Posisi Y
 * @return float Y position setelah kop surat
 */
function generateKopSuratWithLogo($pdf, $logo_path = '', $x = 20, $y = 15, $tahun = null) {
    $logo_width = 0;
    $logo_height = 0;
    $logo_x = $x;
    $logo_y = $y;
    
    // Jika ada logo, tampilkan di kiri
    if ($logo_path && file_exists($logo_path)) {
        $logo_width = 20; // 2cm
        $logo_height = 20; // 2cm
        $pdf->Image($logo_path, $logo_x, $logo_y, $logo_width, $logo_height);
        $text_x = $x + $logo_width + 5; // Spacing setelah logo
    } else {
        $text_x = $x;
    }
    
    // Set font
    $pdf->SetFont('Arial', 'B', 12);
    
    // Baris 1: KEPOLISIAN NEGARA REPUBLIK INDONESIA
    $pdf->SetXY($text_x, $y);
    $pdf->Cell(0, 6, 'KEPOLISIAN NEGARA REPUBLIK INDONESIA', 0, 1, 'L');
    
    // Baris 2: DAERAH SUMATERA UTARA
    $pdf->SetXY($text_x, $y + 6);
    $pdf->Cell(0, 6, 'DAERAH SUMATERA UTARA', 0, 1, 'L');
    
    // Baris 3: RESOR SAMOSIR
    $pdf->SetXY($text_x, $y + 12);
    $pdf->Cell(0, 6, 'RESOR SAMOSIR', 0, 1, 'L');
    
    // Spacing
    $y_current = $y + 20;
    
    // Baris 4: RISK ASSESMENT OBJEK WISATA [TAHUN]
    if ($tahun === null) {
        $tahun = date('Y'); // Default: tahun sekarang
    }
    $pdf->SetXY($text_x, $y_current);
    $pdf->Cell(0, 6, 'RISK ASSESMENT OBJEK WISATA ' . $tahun, 0, 1, 'L');
    
    // Baris 5: WILKUM POLRES SAMOSIR
    $pdf->SetXY($text_x, $y_current + 6);
    $pdf->Cell(0, 6, 'WILKUM POLRES SAMOSIR', 0, 1, 'L');
    
    // Garis pemisah
    $pdf->SetLineWidth(0.5);
    $pdf->Line($x, $y_current + 14, 190, $y_current + 14);
    
    // Return Y position untuk konten berikutnya
    return $y_current + 20;
}

?>

