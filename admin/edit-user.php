<?php
require_once '../includes/auth.php';
require_once '../config.php';

if ($_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$id = intval($_GET['id'] ?? 0);

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
    die("User tidak ditemukan.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_name = $_POST['name'];
    $new_email = $_POST['email'];
    $new_role = $_POST['role'];

    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?");
    $stmt->execute([$new_name, $new_email, $new_role, $id]);

    header('Location: users.php?status=updated');
    exit;
}
?>

<!-- Form Edit -->
<form method="post">
    <label>Nama: <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>"></label><br>
    <label>Email: <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>"></label><br>
    <label>Role:
        <select name="role">
            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
            <option value="client" <?= $user['role'] === 'client' ? 'selected' : '' ?>>Client</option>
        </select>
    </label><br>
    <button type="submit">Update</button>
</form>