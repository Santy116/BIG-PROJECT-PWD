<?php
require_once '../config.php';
if (session_status() === PHP_SESSION_NONE) session_start();

// Redirect jika sudah login
if (isset($_SESSION['user_id'])) {
    $redirect = $_SESSION['user_role'] === 'admin' ? 'dashboard.php' : '../user/akun.php';
    header("Location: $redirect");
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Email and password are required.";
    } else {
        try {
            $stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = :email LIMIT 1");
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user_first_name'] = explode(' ', $user['name'])[0];

                $redirect = $user['role'] === 'admin' ? 'dashboard.php' : '../user/akun.php';
                header("Location: $redirect");
                exit;
            } else {
                $error = "Invalid email or password.";
            }
        } catch (PDOException $e) {
            $error = "System error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - WalBayExpress</title>
</head>
<body>
    <h2>Login</h2>

    <?php if ($error): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">
        <p>
            <label>Email:</label><br>
            <input type="email" name="email" required>
        </p>
        <p>
            <label>Password:</label><br>
            <input type="password" name="password" required>
        </p>
        <p>
            <button type="submit">Login</button>
        </p>
    </form>

    <p><a href="#">Forgot Password?</a></p>
    <p>Don't have an account? <a href="/PWDTUBES_WalBayExpress/user/register.php">Register here</a></p>
</body>
</html>
