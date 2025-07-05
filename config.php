<?php
// Database settings
define('DB_HOST', 'localhost'); // Host database
define('DB_USER', 'root');      // Username database
define('DB_PASS', '');          // Password database
define('DB_NAME', 'walbayexpress_crud'); // Nama database

// Base URL (adjust if needed)
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://";
define('BASE_URL', $protocol . $_SERVER['HTTP_HOST'] . '/PWDTUBES_WalBayExpress/');

// Set session cookie params (Pindahkan sebelum session_start())
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 86400, // 1 hari
        'path' => '/PWDTUBES_WalBayExpress/',
        'domain' => $_SERVER['HTTP_HOST'],
        'secure' => isset($_SERVER['HTTPS']), // Aktifkan jika HTTPS digunakan
        'httponly' => true, // Mencegah akses JavaScript ke cookie
        'samesite' => 'Lax' // Mencegah pengiriman cookie lintas situs
    ]);
    session_start(); // Mulai session setelah pengaturan cookie
}

// Create PDO connection
try {
    $conn = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    die("⚠️ Database connection failed: " . $e->getMessage());
}

// Timezone settings
date_default_timezone_set('Asia/Jakarta');

// Enable error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>