<?php
require_once '../../config.php';

// Pastikan koneksi database tersedia
if (!isset($conn)) {
    die("Database connection not established.");
}

// Ambil parameter dari URL
$id = intval($_GET['id'] ?? 0);
$action = $_GET['action'] ?? '';
$redirect = $_GET['redirect'] ?? '../manage-products.php';

// Daftar redirect yang diizinkan
$allowedRedirects = ['../manage-products.php'];
if (!in_array($redirect, $allowedRedirects)) {
    $redirect = '../manage-products.php';
}

// Hanya izinkan action 'activate' atau 'deactivate'
if (!$id || !in_array($action, ['activate', 'deactivate'])) {
    header("Location: $redirect");
    exit;
}

// Cek apakah produk ditemukan
$stmtCheck = $conn->prepare("SELECT COUNT(*) FROM products WHERE id = ?");
$stmtCheck->execute([$id]);
if ($stmtCheck->fetchColumn() == 0) {
    header("Location: $redirect");
    exit;
}

// Update status produk
$active = ($action === 'activate') ? 1 : 0;

try {
    $stmt = $conn->prepare("UPDATE products SET active = ? WHERE id = ?");
    $stmt->execute([$active, $id]);

    // Redirect sukses
    header("Location: $redirect");
} catch (PDOException $e) {
    error_log("Toggle error: " . $e->getMessage());
    header("Location: $redirect?error=update_failed");
}

exit;