<?php
/**
 * Test TCPDF Installation
 * Risk Assessment Objek Wisata
 */

require_once __DIR__ . '/../config/config.php';

echo "<h2>Test TCPDF Installation</h2>";

// Check 1: File exists
$tcpdf_path = __DIR__ . '/../vendor/tecnickcom/tcpdf/tcpdf.php';
echo "<p><strong>1. File Path:</strong> $tcpdf_path</p>";

if (file_exists($tcpdf_path)) {
    echo "<p style='color: green;'>✓ File TCPDF ditemukan</p>";
} else {
    echo "<p style='color: red;'>✗ File TCPDF tidak ditemukan</p>";
    exit;
}

// Check 2: Include TCPDF
try {
    require_once $tcpdf_path;
    echo "<p style='color: green;'>✓ TCPDF berhasil di-include</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error include TCPDF: " . $e->getMessage() . "</p>";
    exit;
}

// Check 3: Class exists
if (class_exists('TCPDF')) {
    echo "<p style='color: green;'>✓ Class TCPDF tersedia</p>";
} else {
    echo "<p style='color: red;'>✗ Class TCPDF tidak ditemukan</p>";
    exit;
}

// Check 4: Create TCPDF instance
try {
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    echo "<p style='color: green;'>✓ TCPDF instance berhasil dibuat</p>";
    
    // Test basic methods
    $pdf->SetCreator('Test');
    $pdf->SetAuthor('Test');
    $pdf->SetTitle('Test PDF');
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->SetMargins(20, 30, 20);
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Write(0, 'Test PDF Generation');
    
    echo "<p style='color: green;'>✓ TCPDF methods berfungsi dengan baik</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error membuat TCPDF instance: " . $e->getMessage() . "</p>";
    exit;
}

// Check 5: Required PHP extensions
$required_extensions = ['mbstring', 'gd', 'zlib'];
echo "<p><strong>5. PHP Extensions:</strong></p><ul>";
foreach ($required_extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "<li style='color: green;'>✓ $ext</li>";
    } else {
        echo "<li style='color: orange;'>⚠ $ext (tidak wajib, tapi disarankan)</li>";
    }
}
echo "</ul>";

// Check 6: Memory limit
$memory_limit = ini_get('memory_limit');
echo "<p><strong>6. Memory Limit:</strong> $memory_limit</p>";
if (intval($memory_limit) >= 128 || $memory_limit === '-1') {
    echo "<p style='color: green;'>✓ Memory limit cukup</p>";
} else {
    echo "<p style='color: orange;'>⚠ Memory limit mungkin kurang (disarankan minimal 128M)</p>";
}

echo "<hr>";
echo "<h3 style='color: green;'>✓ TCPDF berhasil diinstall dan siap digunakan!</h3>";
echo "<p>Anda dapat menggunakan PDF generator di aplikasi sekarang.</p>";

?>

