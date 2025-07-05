<?php
require_once '../config.php';
require_once '../includes/auth.php';

if ($_SESSION['user_role'] !== 'admin') {
    header('Location: /PWDTUBES_WalBayExpress/admin/login.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $hashed_pw = password_hash($new_password, PASSWORD_DEFAULT);

        try {
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
            $stmt->execute([$hashed_pw, $email]);

            if ($stmt->rowCount() > 0) {
                $success = "Password successfully changed!";
            } else {
                $error = "Email not found.";
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password - Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="admin-container">
    <?php include '../includes/admin-sidebar.php'; ?>
    <div class="main-content">
        <?php include '../includes/admin-topbar.php'; ?>
        <div class="dashboard-content">
            <h2>Change Admin Password</h2>

            <?php if ($error): ?>
                <div style="color: red;"><?= $error ?></div>
            <?php elseif ($success): ?>
                <div style="color: green;"><?= $success ?></div>
            <?php endif; ?>

            <form method="post">
                <div>
                    <label for="email">Admin Email:</label>
                    <input type="email" name="email" required>
                </div>
                <div>
                    <label for="new_password">New Password:</label>
                    <input type="password" name="new_password" required>
                </div>
                <div>
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" name="confirm_password" required>
                </div>
                <button type="submit">Reset Password</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>