<?php
/**
 * Script untuk generate password hash dari NRP
 * Username dan Password menggunakan NRP
 * 
 * Cara penggunaan:
 * 1. Import data personil ke database terlebih dahulu
 * 2. Akses file ini via browser: http://localhost/RISK/generate_password_hash.php
 *    atau jalankan via CLI: php generate_password_hash.php
 */

// Konfigurasi database
$host = 'localhost';
$username_db = 'root';
$password_db = '';
$database = 'risk_assessment_db';

// Koneksi database
$conn = new mysqli($host, $username_db, $password_db, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

echo "<h2>Generate Password Hash dari NRP</h2>";
echo "<p>Memproses user dengan role admin dan penilai...</p>";
echo "<hr>";

// Query untuk ambil semua user
$sql = "SELECT id, username, nama FROM users WHERE role IN ('admin', 'penilai') ORDER BY id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<p>Ditemukan <strong>" . $result->num_rows . "</strong> user</p>";
    echo "<table border='1' cellpadding='5' cellspacing='0'>";
    echo "<tr><th>ID</th><th>Username (NRP)</th><th>Nama</th><th>Status</th></tr>";
    
    $update_count = 0;
    $error_count = 0;
    
    while ($row = $result->fetch_assoc()) {
        $user_id = $row['id'];
        $username = $row['username']; // Username = NRP
        $nama = $row['nama'];
        
        // Generate password hash dari NRP (username)
        // Password = NRP (sama dengan username)
        $password_hash = password_hash($username, PASSWORD_BCRYPT);
        
        // Update password
        $update_sql = "UPDATE users SET password = ? WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("si", $password_hash, $user_id);
        
        if ($stmt->execute()) {
            echo "<tr><td>{$user_id}</td><td>{$username}</td><td>{$nama}</td><td style='color:green;'>✓ Berhasil</td></tr>";
            $update_count++;
        } else {
            echo "<tr><td>{$user_id}</td><td>{$username}</td><td>{$nama}</td><td style='color:red;'>✗ Error: " . $stmt->error . "</td></tr>";
            $error_count++;
        }
        
        $stmt->close();
    }
    
    echo "</table>";
    echo "<hr>";
    echo "<h3>Hasil:</h3>";
    echo "<p><strong style='color:green;'>Berhasil:</strong> {$update_count} user</p>";
    if ($error_count > 0) {
        echo "<p><strong style='color:red;'>Error:</strong> {$error_count} user</p>";
    }
    echo "<hr>";
    echo "<p><strong>Catatan:</strong></p>";
    echo "<ul>";
    echo "<li>Username = NRP</li>";
    echo "<li>Password = NRP (sama dengan username)</li>";
    echo "<li>Password sudah di-hash menggunakan bcrypt</li>";
    echo "</ul>";
} else {
    echo "<p>Tidak ada user yang ditemukan. Pastikan data personil sudah diimport ke database.</p>";
}

$conn->close();
?>
