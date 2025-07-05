<?php
require_once '../config.php';
require_once '../includes/auth.php'; // Tambahkan auth check

// Hanya admin yang bisa menambahkan admin
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Data admin baru (ganti sesuai kebutuhan)
$name = 'Admin Baru'; // Nama admin baru
$email = 'flowearth225@gmail.com'; // Email admin baru (pastikan unik)
$password_plain = 'password111'; // Password admin baru

// Hash password
$password_hash = password_hash($password_plain, PASSWORD_DEFAULT);

// Cek apakah email sudah ada di tabel users
$stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
$stmt->execute([':email' => $email]);
if ($stmt->fetchColumn() > 0) {
    exit('Email sudah terdaftar di tabel users!');
}

// Insert ke tabel users sebagai admin
$stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)");
$stmt->execute([
    ':name' => $name,
    ':email' => $email,
    ':password' => $password_hash,
    ':role' => 'admin'
]);

echo "Admin baru berhasil ditambahkan!";
?>