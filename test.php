<?php
/**
 * Simple Test Page
 * Untuk cek apakah PHP berjalan
 */

echo "<!DOCTYPE html>";
echo "<html><head><title>Test Page</title></head><body>";
echo "<h1>PHP is Working!</h1>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>Current Time: " . date('Y-m-d H:i:s') . "</p>";
echo "<p>Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
echo "<p>Script Name: " . $_SERVER['SCRIPT_NAME'] . "</p>";
echo "<p>Request URI: " . $_SERVER['REQUEST_URI'] . "</p>";

echo "<h2>Environment Variables</h2>";
echo "<pre>";
echo "BASE_URL: " . (getenv('BASE_URL') ?: 'NOT SET') . "\n";
echo "APP_ENV: " . (getenv('APP_ENV') ?: 'NOT SET') . "\n";
echo "MYSQL_HOST: " . (getenv('MYSQL_HOST') ?: 'NOT SET') . "\n";
echo "</pre>";

echo "<h2>File Check</h2>";
$files = ['index.php', 'config/config.php', 'config/config.php.example', 'pages/login.php'];
foreach ($files as $file) {
    $exists = file_exists(__DIR__ . '/' . $file);
    echo "<p>$file: " . ($exists ? "✅ EXISTS" : "❌ NOT FOUND") . "</p>";
}

echo "<h2>Links</h2>";
echo "<p><a href='index.php'>Go to index.php</a></p>";
echo "<p><a href='debug.php'>Go to debug.php</a></p>";
echo "<p><a href='pages/login.php'>Go to login.php directly</a></p>";

echo "</body></html>";
?>

