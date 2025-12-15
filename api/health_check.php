<?php
/**
 * Health Check Endpoint
 * Risk Assessment Objek Wisata
 * 
 * Endpoint untuk monitoring kesehatan aplikasi
 * Usage: GET /api/health_check.php
 */

header('Content-Type: application/json; charset=utf-8');

$health = [
    'status' => 'ok',
    'timestamp' => date('Y-m-d H:i:s'),
    'checks' => []
];

// Check 1: Database Connection
try {
    require_once __DIR__ . '/../config/database.php';
    $conn = getDBConnection();
    if ($conn) {
        $health['checks']['database'] = 'ok';
        $conn->close();
    } else {
        $health['checks']['database'] = 'error';
        $health['status'] = 'error';
    }
} catch (Exception $e) {
    $health['checks']['database'] = 'error: ' . $e->getMessage();
    $health['status'] = 'error';
}

// Check 2: Upload Directory
$uploads_dir = __DIR__ . '/../assets/uploads/';
if (is_dir($uploads_dir) && is_writable($uploads_dir)) {
    $health['checks']['uploads_dir'] = 'ok';
} else {
    $health['checks']['uploads_dir'] = 'error';
    $health['status'] = 'error';
}

// Check 3: Logs Directory
$logs_dir = __DIR__ . '/../logs/';
if (is_dir($logs_dir) && is_writable($logs_dir)) {
    $health['checks']['logs_dir'] = 'ok';
} else {
    $health['checks']['logs_dir'] = 'warning';
}

// Check 4: PHP Version
$php_version = phpversion();
$health['checks']['php_version'] = $php_version;
if (version_compare($php_version, '7.4.0', '>=')) {
    $health['checks']['php_version_status'] = 'ok';
} else {
    $health['checks']['php_version_status'] = 'warning';
    $health['status'] = 'warning';
}

// Check 5: Required Extensions
$required_extensions = ['mysqli', 'json', 'mbstring', 'gd'];
$missing_extensions = [];
foreach ($required_extensions as $ext) {
    if (!extension_loaded($ext)) {
        $missing_extensions[] = $ext;
    }
}
if (empty($missing_extensions)) {
    $health['checks']['extensions'] = 'ok';
} else {
    $health['checks']['extensions'] = 'error: missing ' . implode(', ', $missing_extensions);
    $health['status'] = 'error';
}

// Set HTTP status code
if ($health['status'] === 'ok') {
    http_response_code(200);
} elseif ($health['status'] === 'warning') {
    http_response_code(200); // Still 200, but with warning
} else {
    http_response_code(503); // Service Unavailable
}

echo json_encode($health, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

?>

