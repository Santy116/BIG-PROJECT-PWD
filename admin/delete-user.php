<?php
require_once '../includes/auth.php';
require_once '../config.php';

if ($_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$id = intval($_GET['id'] ?? 0);

$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->execute([$id]);

header('Location: users.php?status=deleted');
exit;
?>