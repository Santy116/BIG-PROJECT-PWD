<?php
require_once '../includes/auth.php';
require_once '../config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SESSION['user_role'] !== 'client') {
    header('Location: /PWDTUBES_WalBayExpress/admin/login.php');
    exit;
}

// // PERBAIKAN: Validasi role client
// if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'client') {
//     header('Location: /PWDTUBES_WalBayExpress/admin/login.php');
//     exit;
// }


// Ambil data user dari database
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute([':id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) {
    // Jika user tidak ditemukan, logout
    session_unset();
    session_destroy();
    header('Location: /PWDTUBES_WalBayExpress/admin/login.php');
    exit;
}

// Inisialisasi variabel untuk pesan error dan sukses
$error = '';
$success = '';

// Proses update profil
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validasi input
    if (empty($name) || empty($email)) {
        $error = 'Name and email are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        // Cek apakah password akan diubah
        $password_update = '';
        if (!empty($new_password)) {
            if (empty($current_password)) {
                $error = 'Please enter your current password.';
            } elseif (!password_verify($current_password, $user['password'])) {
                $error = 'Current password is incorrect.';
            } elseif ($new_password !== $confirm_password) {
                $error = 'New passwords do not match.';
            } else {
                $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
                $password_update = ", password = :password";
            }
        }

        if (!$error) {
            try {
                // Update data user di database
                $sql = "UPDATE users SET name = :name, email = :email, address = :address $password_update WHERE id = :id";
                $stmt = $conn->prepare($sql);

                $params = [
                    ':name' => $name,
                    ':email' => $email,
                    ':address' => $address,
                    ':id' => $user_id
                ];

                if (!empty($password_update)) {
                    $params[':password'] = $password_hash;
                }

                $stmt->execute($params);

                $success = 'Profile updated successfully!';

                // Refresh data user
                $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
                $stmt->execute([':id' => $user_id]);
                $user = $stmt->fetch();

                // Update session
                $_SESSION['user_name'] = $user['name'];
            } catch (PDOException $e) {
                $error = 'Database error: ' . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account - WalBayExpress</title>
    <link rel="stylesheet" href="/PWDTUBES_WalBayExpress/assets/css/menu.css">
</head>
<body>
    <div class="container">
        <h1>My Account</h1>

        <div class="account-container">
            <div class="account-sidebar">
                <div class="account-info">
                    <div class="account-avatar">
                        <img src="/PWDTUBES_WalBayExpress/assets/img/tentang/santy.jpg" alt="User Avatar">
                    </div>
                    <h2><?= htmlspecialchars($user['name']) ?></h2>
                    <p><?= htmlspecialchars($user['email']) ?></p>
                </div>

                <nav class="account-nav">
                    <a href="akun.php" class="active">Profile</a>
                    <a href="orders.php">My Orders</a>
                    <a href="wishlist.php">Wishlist</a>
                    <a href="addresses.php">Addresses</a>
                    <a href="logout.php">Logout</a>
                </nav>
            </div>

            <div class="account-content">
                <h2>Profile Information</h2>

                <?php if ($error): ?>
                    <div class="alert error"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert success"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>

                <form method="post">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Full Name *</label>
                            <input type="text" id="name" name="name" required value="<?= htmlspecialchars($user['name']) ?>">
                        </div>

                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" id="email" name="email" required value="<?= htmlspecialchars($user['email']) ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea id="address" name="address" rows="3"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                    </div>

                    <h3>Change Password</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="current_password">Current Password</label>
                            <input type="password" id="current_password" name="current_password">
                        </div>

                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <input type="password" id="new_password" name="new_password">
                        </div>

                        <div class="form-group">
                            <label for="confirm_password">Confirm New Password</label>
                            <input type="password" id="confirm_password" name="confirm_password">
                        </div>
                    </div>

                    <button type="submit" class="save-btn">Save Changes</button>
                </form><br>

                <div class="account-actions">
                    <a href="/PWDTUBES_WalBayExpress/index.php" class="btn btn-secondary">Back to Home</a>
                    <a href="/PWDTUBES_WalBayExpress/user/logout.php" class="btn btn-danger">Logout</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>