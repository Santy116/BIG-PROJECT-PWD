<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect jika belum login
if (!isset($_SESSION['user_id'])) {
    header('Location: /PWDTUBES_WalBayExpress/admin/login.php');
    exit;
}

// Validasi akses berdasarkan role
$current_page = $_SERVER['PHP_SELF'];

// Admin tidak boleh mengakses halaman user
if ($_SESSION['user_role'] === 'admin') {
    if (strpos($current_page, '/user/') !== false) {
        header('Location: /PWDTUBES_WalBayExpress/admin/dashboard.php');
        exit;
    }
} 
// Client tidak boleh mengakses halaman admin
elseif ($_SESSION['user_role'] === 'client') {
    if (strpos($current_page, '/admin/') !== false) {
        if (basename($current_page) !== 'logout.php') {
            header('Location: /PWDTUBES_WalBayExpress/user/akun.php');
            exit;
        }
    }
    
    // Blokir akses ke folder admin/produk
    if (strpos($current_page, '/admin/produk/') !== false) {
        header('Location: /PWDTUBES_WalBayExpress/user/akun.php');
        exit;
    }
}