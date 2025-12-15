<?php
/**
 * Backup Database Script
 * Risk Assessment Objek Wisata
 * 
 * Jalankan via cron job atau manual
 * Usage: php backup_database.php
 */

require_once __DIR__ . '/../config/database.php';

$backup_dir = __DIR__ . '/../backups/';
if (!is_dir($backup_dir)) {
    mkdir($backup_dir, 0755, true);
}

$filename = 'backup_' . DB_NAME . '_' . date('Ymd_His') . '.sql';
$filepath = $backup_dir . $filename;

// Command untuk backup
$command = sprintf(
    'mysqldump -h %s -u %s -p%s %s > %s',
    escapeshellarg(DB_HOST),
    escapeshellarg(DB_USER),
    escapeshellarg(DB_PASS),
    escapeshellarg(DB_NAME),
    escapeshellarg($filepath)
);

// Execute backup
exec($command, $output, $return_var);

if ($return_var === 0) {
    echo "Backup berhasil: $filename\n";
    
    // Compress backup (optional)
    if (function_exists('gzencode')) {
        $compressed = gzencode(file_get_contents($filepath));
        file_put_contents($filepath . '.gz', $compressed);
        unlink($filepath);
        echo "Backup dikompres: $filename.gz\n";
    }
    
    // Hapus backup lama (lebih dari 30 hari)
    $files = glob($backup_dir . 'backup_*.sql*');
    foreach ($files as $file) {
        if (filemtime($file) < time() - (30 * 24 * 60 * 60)) {
            unlink($file);
            echo "Backup lama dihapus: " . basename($file) . "\n";
        }
    }
} else {
    echo "Backup gagal!\n";
    exit(1);
}

?>

