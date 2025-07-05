<?php
require_once '../includes/auth.php';
require_once '../config.php';

if ($_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    // Cek email duplikat
    $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetchColumn() > 0) {
        $error = "Email sudah terdaftar.";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $password, $role]);
        $success = "User berhasil ditambahkan!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add User - Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <style>
        .form-group {
            margin-bottom: 16px;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 6px;
        }
        input[type="text"], input[type="email"], select {
            width: 100%;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            background-color: #4e73df;
            color: white;
            padding: 10px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #2e59d9;
        }
        .alert {
            padding: 10px;
            margin-bottom: 16px;
            border-radius: 5px;
            font-size: 14px;
        }
        .alert.success { background-color: #d4edda; color: #155724; }
        .alert.error { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
<div class="admin-container">
    <?php include '../includes/admin-sidebar.php'; ?>
    <div class="main-content">
        <?php include '../includes/admin-topbar.php'; ?>
        <div class="dashboard-content">
            <h2>Add New User</h2>

            <?php if ($error): ?>
                <div class="alert error"><?= $error ?></div>
            <?php elseif ($success): ?>
                <div class="alert success"><?= $success ?></div>
            <?php endif; ?>

            <form method="post">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="role">Role:</label>
                    <select name="role">
                        <option value="client">Client</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <button type="submit">Save User</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>