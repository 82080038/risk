<?php
/**
 * API Upload File
 * Risk Assessment Objek Wisata
 * Upload referensi dokumen/foto
 */

require_once __DIR__ . '/api_base.php';
require_once __DIR__ . '/../includes/image_helper.php';
requireApiLogin();

header('Content-Type: application/json; charset=utf-8');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // Get uploaded files
    $penilaian_id = intval($_GET['penilaian_id'] ?? 0);
    $kriteria_id = intval($_GET['kriteria_id'] ?? 0);
    
    if ($penilaian_id <= 0) {
        jsonError('Penilaian ID wajib diisi', 400);
    }
    
    $conn = getDBConnection();
    $where = "WHERE penilaian_id = ?";
    $params = [$penilaian_id];
    $types = 'i';
    
    if ($kriteria_id > 0) {
        $where .= " AND kriteria_id = ?";
        $params[] = $kriteria_id;
        $types .= 'i';
    }
    
    $sql = "SELECT * FROM referensi_dokumen $where ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $files = [];
    while ($row = $result->fetch_assoc()) {
        $files[] = [
            'id' => $row['id'],
            'kriteria_id' => $row['kriteria_id'],
            'nama_file' => $row['nama_file'],
            'path' => $row['path_file'],
            'tipe_file' => $row['tipe_file'],
            'ukuran_file' => $row['ukuran_file'],
            'deskripsi' => $row['deskripsi'],
            'created_at' => $row['created_at'],
            'original_name' => $row['nama_file'] // Fallback, bisa ditambahkan field nama_asli jika diperlukan
        ];
    }
    
    $stmt->close();
    $conn->close();
    
    jsonSuccess('Data berhasil diambil', $files);
    exit;
}

if ($method === 'DELETE') {
    // Delete file
    $file_id = intval($_GET['file_id'] ?? 0);
    
    if ($file_id <= 0) {
        jsonError('File ID wajib diisi', 400);
    }
    
    $conn = getDBConnection();
    
    // Get file info
    $stmt = $conn->prepare("SELECT * FROM referensi_dokumen WHERE id = ?");
    $stmt->bind_param("i", $file_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        $stmt->close();
        $conn->close();
        jsonError('File tidak ditemukan', 404);
    }
    
    $file = $result->fetch_assoc();
    $stmt->close();
    
    // Delete from database
    $delete_stmt = $conn->prepare("DELETE FROM referensi_dokumen WHERE id = ?");
    $delete_stmt->bind_param("i", $file_id);
    
    if ($delete_stmt->execute()) {
        // Delete physical file
        $file_path = __DIR__ . '/../' . $file['path_file'];
        if (file_exists($file_path)) {
            @unlink($file_path);
        }
        
        $delete_stmt->close();
        $conn->close();
        jsonSuccess('File berhasil dihapus');
    } else {
        $delete_stmt->close();
        $conn->close();
        jsonError('Gagal menghapus file dari database', 500);
    }
    
    exit;
}

if ($method !== 'POST') {
    jsonError('Method tidak didukung', 405);
}

try {
    // Check if file was uploaded
    if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        jsonError('Tidak ada file yang diupload atau terjadi error', 400);
    }
    
    $file = $_FILES['file'];
    $penilaian_id = intval($_POST['penilaian_id'] ?? 0);
    $kriteria_id = intval($_POST['kriteria_id'] ?? 0);
    
    if ($penilaian_id <= 0) {
        jsonError('Penilaian ID wajib diisi', 400);
    }
    
    // Validate file
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'];
    $max_size = 5 * 1024 * 1024; // 5MB
    
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $file_size = $file['size'];
    
    if (!in_array($file_extension, $allowed_extensions)) {
        jsonError('Format file tidak diizinkan. Format yang diizinkan: ' . implode(', ', $allowed_extensions), 400);
    }
    
    if ($file_size > $max_size) {
        jsonError('Ukuran file terlalu besar. Maksimal: 5MB', 400);
    }
    
    // Validate image file using GD (if image)
    if (in_array($file_extension, ['jpg', 'jpeg', 'png'])) {
        if (isGDAvailable()) {
            // Validate image using GD
            $tmp_path = $file['tmp_name'];
            $image_info = validateImageFile($tmp_path);
            
            if ($image_info === false) {
                jsonError('File gambar tidak valid atau corrupt', 400);
            }
            
            // Check image dimensions (optional - bisa ditambahkan limit)
            // if ($image_info['width'] > 5000 || $image_info['height'] > 5000) {
            //     jsonError('Dimensi gambar terlalu besar', 400);
            // }
        } else {
            // Fallback: basic validation without GD
            $image_size = @getimagesize($file['tmp_name']);
            if ($image_size === false) {
                jsonError('File gambar tidak valid', 400);
            }
        }
    }
    
    // Generate unique filename
    $filename = uniqid() . '_' . time() . '.' . $file_extension;
    $upload_dir = __DIR__ . '/../assets/uploads/';
    
    // Create upload directory if not exists
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    $upload_path = $upload_dir . $filename;
    
    // Move uploaded file
    if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
        jsonError('Gagal menyimpan file', 500);
    }
    
    // Save to database
    $conn = getDBConnection();
    $stmt = $conn->prepare("
        INSERT INTO referensi_dokumen (penilaian_id, kriteria_id, nama_file, path_file, tipe_file, ukuran_file, deskripsi)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    
    $path = 'assets/uploads/' . $filename;
    $deskripsi = sanitize($_POST['deskripsi'] ?? '');
    $stmt->bind_param("iisssis", 
        $penilaian_id,
        $kriteria_id,
        $filename,
        $path,
        $file['type'],
        $file_size,
        $deskripsi
    );
    
    if ($stmt->execute()) {
        $file_id = $conn->insert_id;
        jsonSuccess('File berhasil diupload', [
            'id' => $file_id,
            'filename' => $filename,
            'original_name' => $file['name'],
            'path' => $path,
            'size' => $file_size,
            'type' => $file['type']
        ]);
    } else {
        // Delete uploaded file if database insert fails
        @unlink($upload_path);
        jsonError('Gagal menyimpan data file: ' . $stmt->error, 500);
    }
    
    $stmt->close();
    $conn->close();
    
} catch (Exception $e) {
    jsonError('Error: ' . $e->getMessage(), 500);
}

?>

