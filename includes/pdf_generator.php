<?php
/**
 * PDF Generator menggunakan TCPDF
 * Risk Assessment Objek Wisata
 */

// Check if TCPDF is available
if (!class_exists('TCPDF')) {
    // Try to include TCPDF
    $tcpdf_path = __DIR__ . '/../vendor/tecnickcom/tcpdf/tcpdf.php';
    if (file_exists($tcpdf_path)) {
        // Include autoconfig first (defines constants)
        $autoconfig_path = __DIR__ . '/../vendor/tecnickcom/tcpdf/tcpdf_autoconfig.php';
        if (file_exists($autoconfig_path)) {
            require_once $autoconfig_path;
        }
        require_once $tcpdf_path;
    } else {
        // Fallback: Use simple HTML output
        function generatePDF($html_content, $penilaian, $tahun) {
            header('Content-Type: text/html; charset=utf-8');
            echo $html_content;
        }
        return;
    }
}

// Define TCPDF constants if not already defined (fallback)
if (!defined('PDF_PAGE_ORIENTATION')) {
    define('PDF_PAGE_ORIENTATION', 'P'); // P = Portrait, L = Landscape
}
if (!defined('PDF_UNIT')) {
    define('PDF_UNIT', 'mm');
}
if (!defined('PDF_PAGE_FORMAT')) {
    define('PDF_PAGE_FORMAT', 'A4');
}

function generatePDF($html_content, $penilaian, $tahun) {
    // Create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
    // Set document information
    $pdf->SetCreator('Risk Assessment Objek Wisata');
    $pdf->SetAuthor('Polres Samosir');
    $pdf->SetTitle('Laporan Penilaian - ' . $penilaian['objek_nama']);
    $pdf->SetSubject('Laporan Penilaian Risiko Objek Wisata');
    
    // Remove default header/footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    
    // Set margins
    $pdf->SetMargins(20, 30, 20);
    $pdf->SetAutoPageBreak(TRUE, 25);
    
    // Add a page
    $pdf->AddPage();
    
    // Set font
    $pdf->SetFont('helvetica', '', 10);
    
    // Include kop surat
    require_once __DIR__ . '/kop_surat.php';
    $y_position = generateKopSuratTCPDF($pdf, 20, 15, 'helvetica', 12, $tahun);
    
    // Set Y position after kop
    $pdf->SetY($y_position);
    
    // Write HTML content
    // Remove kop surat from HTML since we already added it
    $html_content = preg_replace('/<div class="kop-surat">.*?<\/div>/s', '', $html_content);
    
    $pdf->writeHTML($html_content, true, false, true, false, '');
    
    // Output PDF
    $filename = 'Laporan_Penilaian_' . preg_replace('/[^a-zA-Z0-9]/', '_', $penilaian['objek_nama']) . '_' . date('Ymd') . '.pdf';
    $pdf->Output($filename, 'D'); // D = Download, I = Inline
    exit;
}

?>

