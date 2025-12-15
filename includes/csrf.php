<?php
/**
 * CSRF Protection
 * Risk Assessment Objek Wisata
 */

// Generate CSRF token
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Verify CSRF token
function verifyCSRFToken($token) {
    if (!isset($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

// Get CSRF token for forms
function getCSRFTokenField() {
    $token = generateCSRFToken();
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
}

// Verify CSRF token from POST
function verifyCSRFTokenFromPost() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return true; // Only verify POST requests
    }
    
    $token = $_POST['csrf_token'] ?? '';
    if (!verifyCSRFToken($token)) {
        http_response_code(403);
        die('CSRF token verification failed');
    }
    return true;
}

?>

