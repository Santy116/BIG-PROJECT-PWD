<?php
require_once '../includes/auth.php';
require_once '../config.php';

// Pastikan hanya admin yang bisa akses
if ($_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Ambil semua user dari database
$stmt = $conn->query("SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users - Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <style>
    .dashboard-content {
        padding: 24px;
        background-color: #f8f9fc;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        margin-top: 20px;
    }

    .dashboard-content h2 {
        margin-bottom: 20px;
        color: #333;
        font-size: 24px;
        font-weight: 600;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background-color: #fff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    }

    th, td {
        padding: 12px 16px;
        text-align: left;
        border-bottom: 1px solid #e9ecef;
        font-size: 14px;
        color: #555;
    }

    th {
        background-color: #f1f4fb;
        color: #4e73df;
        font-weight: 600;
        text-transform: uppercase;
    }

    tr:hover {
        background-color: #f8f9fc;
    }

    .action-btn {
        display: inline-block;
        padding: 4px 10px;
        margin: 2px;
        border-radius: 5px;
        font-size: 13px;
        text-decoration: none;
        transition: all 0.2s ease-in-out;
    }

    .edit-btn {
        background-color: #f6c23e;
        color: #222;
    }

    .delete-btn {
        background-color: #e74a3b;
        color: #fff;
    }

    .action-btn:hover {
        opacity: 0.85;
    }

    @media (max-width: 768px) {
        th, td {
            font-size: 13px;
            padding: 10px;
        }
    }

    /* Opsional: Tambahkan tombol tambah user */
    .add-user-btn {
        display: inline-block;
        margin-bottom: 16px;
        padding: 8px 16px;
        font-size: 14px;
        font-weight: 600;
        color: #fff;
        background-color: #4e73df;
        border-radius: 5px;
        text-decoration: none;
        transition: background 0.2s;
    }

    .add-user-btn:hover {
        background-color: #2e59d9;
    }
    </style>
</head>
<body>
<div class="admin-container">
    <?php include '../includes/admin-sidebar.php'; ?>
    <div class="main-content">
        <?php include '../includes/admin-topbar.php'; ?>
        <div class="dashboard-content">
            <h2>Manage Users</h2>
            <a href="add-user.php" class="add-user-btn">+ Add New User</a>

            <table border="1" cellpadding="10" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['name']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td>
                            <?= $user['role'] === 'admin' ? '<span style="color:green;font-weight:bold;">Admin</span>' : 'Client' ?>
                        </td>
                        <td><?= date('d M Y, H:i', strtotime($user['created_at'])) ?></td>
                        <td>
                            <a href="edit-user.php?id=<?= $user['id'] ?>">Edit</a> |
                            <a href="delete-user.php?id=<?= $user['id'] ?>" onclick="return confirm('Yakin hapus?')">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (!$users): ?>
                        <tr><td colspan="6">Tidak ada pengguna ditemukan.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>