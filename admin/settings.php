<?php
require_once '../includes/auth.php';
require_once '../config.php';

// Pastikan hanya admin yang bisa akses
if ($_SESSION['user_role'] !== 'admin') {
    header('Location: /PWDTUBES_WalBayExpress/admin/login.php');
    exit;
}

// Ambil data dari database
$stmt = $conn->query("SELECT * FROM settings WHERE id = 1");
$setting = $stmt->fetch(PDO::FETCH_ASSOC);

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $site_name = $_POST['site_name'];
    $site_description = $_POST['site_description'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];

    if ($setting) {
        // Update jika sudah ada
        $stmt = $conn->prepare("UPDATE settings SET site_name = ?, site_description = ?, email = ?, address = ?, phone = ? WHERE id = 1");
    } else {
        // Insert jika belum ada
        $stmt = $conn->prepare("INSERT INTO settings (site_name, site_description, email, address, phone) VALUES (?, ?, ?, ?, ?)");
    }

    $stmt->execute([$site_name, $site_description, $email, $address, $phone]);

    // Redirect dengan status sukses
    header('Location: settings.php?status=success');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Settings - Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="admin-container">
    <?php include '../includes/admin-sidebar.php'; ?>
    <div class="main-content">
        <?php include '../includes/admin-topbar.php'; ?>
        <div class="dashboard-content">
            <h2>Website Settings</h2>

            <!-- Alert Sukses -->
            <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
                <div class="alert success">Settings updated successfully!</div>
            <?php endif; ?>

            <!-- Form Settings -->
            <form method="post" action="settings.php">
                <div class="form-group">
                    <label for="site_name">Site Name</label>
                    <input type="text" name="site_name" id="site_name" value="<?= htmlspecialchars($setting['site_name'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label for="site_description">Site Description</label>
                    <textarea name="site_description" id="site_description"><?= htmlspecialchars($setting['site_description'] ?? '') ?></textarea>
                </div>

                <div class="form-group">
                    <label for="email">Contact Email</label>
                    <input type="email" name="email" id="email" value="<?= htmlspecialchars($setting['email'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea name="address" id="address"><?= htmlspecialchars($setting['address'] ?? '') ?></textarea>
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" name="phone" id="phone" value="<?= htmlspecialchars($setting['phone'] ?? '') ?>">
                </div>

                <button type="submit" class="btn btn-primary">Save Settings</button>
            </form>

            <hr style="margin-top: 40px;">

            <!-- Ganti Password -->
            <h3>Change Password</h3>
            <form method="post" action="change-password.php">
                <div class="form-group">
                    <label for="old_password">Old Password</label>
                    <input type="password" name="old_password" required>
                </div>
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" name="new_password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" name="confirm_password" required>
                </div>
                <button type="submit" class="btn btn-warning">Change Password</button>
            </form>

            <hr style="margin-top: 40px;">

            <!-- Backup Database -->
            <h3>Backup & Restore</h3>
            <a href="backup.php" class="btn btn-secondary">Download Database Backup</a>
        </div>
    </div>
</div>

<style>
.admin-container {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f8f9fc;
}

.dashboard-content {
    padding: 24px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    margin-top: 20px;
}

.dashboard-content h2 {
    margin-bottom: 24px;
    color: #333;
    font-size: 24px;
    font-weight: 600;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 6px;
    color: #555;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #d1d3e2;
    border-radius: 6px;
    font-size: 14px;
    resize: vertical;
    transition: border-color 0.2s ease-in-out;
}

.form-group input:focus,
.form-group textarea:focus {
    border-color: #4e73df;
    outline: none;
    box-shadow: 0 0 0 2px rgba(78, 115, 223, 0.25);
}

.btn {
    display: inline-block;
    padding: 10px 18px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    text-align: center;
    text-decoration: none;
    transition: background-color 0.2s ease;
}

.btn-primary {
    background-color: #4e73df;
    color: white;
}

.btn-primary:hover {
    background-color: #3a5fcf;
}

.btn-warning {
    background-color: #f6c23e;
    color: #222;
}

.btn-warning:hover {
    background-color: #e5b73b;
}

.btn-secondary {
    background-color: #858796;
    color: white;
}

.btn-secondary:hover {
    background-color: #737584;
}

.alert {
    padding: 12px 18px;
    border-radius: 6px;
    font-size: 14px;
    margin-bottom: 20px;
}

.alert.success {
    background-color: #d4edda;
    color: #155724;
    border-left: 4px solid #28a745;
}

hr {
    border: 0;
    border-top: 1px solid #e3e6f0;
    margin: 40px 0;
}

h3 {
    margin-top: 0;
    font-size: 18px;
    color: #444;
    margin-bottom: 16px;

}

</style>

<script src="../assets/js/admin.js"></script>
</body>
</html>