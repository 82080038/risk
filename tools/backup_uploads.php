<?php
/**
 * Backup Uploads Script
 * Risk Assessment Objek Wisata
 * 
 * Jalankan via cron job atau manual
 * Usage: php backup_uploads.php
 */

$uploads_dir = __DIR__ . '/../assets/uploads/';
$backup_dir = __DIR__ . '/../backups/uploads/';

if (!is_dir($backup_dir)) {
    mkdir($backup_dir, 0755, true);
}

$filename = 'uploads_backup_' . date('Ymd_His') . '.tar.gz';
$filepath = $backup_dir . $filename;

// Create tar.gz archive
$command = sprintf(
    'tar -czf %s -C %s .',
    escapeshellarg($filepath),
    escapeshellarg($uploads_dir)
);

exec($command, $output, $return_var);

if ($return_var === 0) {
    echo "Backup uploads berhasil: $filename\n";
    
    // Hapus backup lama (lebih dari 30 hari)
    $files = glob($backup_dir . 'uploads_backup_*.tar.gz');
    foreach ($files as $file) {
        if (filemtime($file) < time() - (30 * 24 * 60 * 60)) {
            unlink($file);
            echo "Backup lama dihapus: " . basename($file) . "\n";
        }
    }
} else {
    echo "Backup uploads gagal!\n";
    exit(1);
}

?>

