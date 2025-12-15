<?php
/**
 * Debug Script untuk Railway
 * Hapus file ini setelah aplikasi berjalan normal
 */

// Enable error display
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

echo "<h1>Debug Information</h1>";

// 1. Check PHP version
echo "<h2>1. PHP Version</h2>";
echo "PHP Version: " . phpversion() . "<br>";

// 2. Check file existence
echo "<h2>2. File Existence</h2>";
$files = [
    'config/config.php',
    'config/config.php.example',
    'config/database.php',
    'config/database.php.example',
    'index.php',
    'pages/login.php',
    'includes/functions.php'
];

foreach ($files as $file) {
    $exists = file_exists(__DIR__ . '/' . $file);
    echo "$file: " . ($exists ? "✅ EXISTS" : "❌ NOT FOUND") . "<br>";
}

// 3. Check environment variables
echo "<h2>3. Environment Variables</h2>";
$env_vars = [
    'BASE_URL',
    'APP_ENV',
    'MYSQL_HOST',
    'MYSQL_PORT',
    'MYSQL_DATABASE',
    'MYSQL_USER',
    'MYSQL_PASSWORD'
];

foreach ($env_vars as $var) {
    $value = getenv($var);
    echo "$var: " . ($value ? $value : "❌ NOT SET") . "<br>";
}

// 4. Try to load config
echo "<h2>4. Config Loading</h2>";
try {
    $config_file = __DIR__ . '/config/config.php';
    if (file_exists($config_file)) {
        require_once $config_file;
        echo "✅ Config loaded<br>";
        echo "BASE_URL: " . (defined('BASE_URL') ? BASE_URL : "❌ NOT DEFINED") . "<br>";
        echo "APP_ENV: " . (defined('APP_ENV') ? APP_ENV : "❌ NOT DEFINED") . "<br>";
    } else {
        echo "❌ config.php not found, trying to create...<br>";
        // Try to create from template
        $template = __DIR__ . '/config/config.php.example';
        if (file_exists($template)) {
            $content = file_get_contents($template);
            file_put_contents($config_file, $content);
            echo "✅ Created config.php from template<br>";
        }
    }
} catch (Exception $e) {
    echo "❌ Error loading config: " . $e->getMessage() . "<br>";
}

// 5. Try to load database config
echo "<h2>5. Database Config</h2>";
try {
    $db_file = __DIR__ . '/config/database.php';
    if (file_exists($db_file)) {
        require_once $db_file;
        echo "✅ Database config loaded<br>";
        if (defined('DB_HOST')) {
            echo "DB_HOST: " . DB_HOST . "<br>";
            echo "DB_NAME: " . DB_NAME . "<br>";
            echo "DB_USER: " . DB_USER . "<br>";
        }
    } else {
        echo "❌ database.php not found, trying to create...<br>";
        $template = __DIR__ . '/config/database.php.example';
        if (file_exists($template)) {
            copy($template, $db_file);
            echo "✅ Created database.php from template<br>";
        }
    }
} catch (Exception $e) {
    echo "❌ Error loading database config: " . $e->getMessage() . "<br>";
}

// 6. Test database connection
echo "<h2>6. Database Connection Test</h2>";
try {
    if (file_exists(__DIR__ . '/config/database.php')) {
        require_once __DIR__ . '/config/database.php';
        if (function_exists('getDBConnection')) {
            $conn = getDBConnection();
            if ($conn) {
                echo "✅ Database connection successful<br>";
                $conn->close();
            } else {
                echo "❌ Database connection failed<br>";
            }
        } else {
            echo "❌ getDBConnection() function not found<br>";
        }
    } else {
        echo "❌ database.php not found<br>";
    }
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "<br>";
}

// 7. Check directory permissions
echo "<h2>7. Directory Permissions</h2>";
$dirs = [
    'config',
    'assets/uploads',
    'logs'
];

foreach ($dirs as $dir) {
    $path = __DIR__ . '/' . $dir;
    if (is_dir($path)) {
        $writable = is_writable($path);
        echo "$dir: " . ($writable ? "✅ WRITABLE" : "❌ NOT WRITABLE") . "<br>";
    } else {
        echo "$dir: ❌ NOT EXISTS<br>";
    }
}

// 8. Test redirect
echo "<h2>8. Redirect Test</h2>";
$base_url = defined('BASE_URL') ? BASE_URL : getenv('BASE_URL') ?: 'http://localhost/RISK/';
echo "BASE_URL: $base_url<br>";
echo "Login URL: " . $base_url . "pages/login.php<br>";
echo "<a href='" . $base_url . "pages/login.php'>Try Login Page</a><br>";

echo "<hr>";
echo "<p><strong>Jika semua sudah ✅, coba akses:</strong></p>";
echo "<a href='index.php'>index.php</a><br>";
echo "<a href='pages/login.php'>pages/login.php</a><br>";

?>

