<?php
require_once '../config.php';

if (session_status() === PHP_SESSION_NONE) session_start();

// Redirect jika sudah login
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['user_role'] === 'admin') {
        header("Location: dashboard.php");
        exit;
    } elseif ($_SESSION['user_role'] === 'client') {
        header("Location: ../user/akun.php");
        exit;
    }
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
                
                // Ambil nama depan
                $name_parts = explode(' ', $user['name']);
                $_SESSION['user_first_name'] = $name_parts[0];

                // Redirect berdasarkan role
                if ($user['role'] === 'admin') {
                    header("Location: dashboard.php");
                    exit;
                } else {
                    header("Location: ../user/akun.php");
                    exit;
                }
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - WalBayExpress</title>
    <style>
.body {
    background: #f4f6fb;
    font-family: 'Segoe UI', Arial, sans-serif;
    margin: 0;
    padding: 0;
}
.login-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
}
.login-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(54, 101, 243, 0.10);
    padding: 36px 32px 28px 32px;
    width: 100%;
    max-width: 370px;
}
.login-header {
    text-align: center;
    margin-bottom: 24px;
}
.login-header h1 {
    font-size: 2rem;
    color: #3665f3;
    margin-bottom: 6px;
    font-weight: bold;
    letter-spacing: 1px;
}
.login-header p {
    color: #888;
    font-size: 1.1rem;
    margin-bottom: 0;
}
.login-body {
    margin-top: 10px;
}
.form-group {
    margin-bottom: 18px;
}
.form-label {
    display: block;
    margin-bottom: 6px;
    color: #222;
    font-weight: 500;
    font-size: 1rem;
}
.form-control {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 1rem;
    background: #f8fafc;
    color: #222;
    transition: border 0.2s;
}
.form-control:focus {
    border-color: #3665f3;
    outline: none;
    background: #fff;
}
.btn-primary {
    width: 100%;
    background: linear-gradient(90deg, #3665f3 0%, #4f8cff 100%);
    color: #fff;
    border: none;
    border-radius: 24px;
    padding: 12px 0;
    font-size: 1.1rem;
    font-weight: bold;
    cursor: pointer;
    transition: background 0.18s, transform 0.18s;
    box-shadow: 0 2px 8px rgba(54, 101, 243, 0.10);
}
.btn-primary:hover {
    background: linear-gradient(90deg, #111 0%, #3665f3 100%);
    transform: scale(1.03);
}
.alert-danger {
    background: #ffe9e9;
    color: #e74a3b;
    border-radius: 6px;
    padding: 10px 14px;
    margin-bottom: 18px;
    font-size: 0.98rem;
    border: 1px solid #f5c6cb;
}
.text-center {
    text-align: center;
}
.mt-3 {
    margin-top: 18px;
}
.small {
    font-size: 0.97rem;
    color: #3665f3;
    text-decoration: none;
}
.small:hover {
    text-decoration: underline;
    color: #2e59d9;
}
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1>User Login</h1>
                <p>Welcome back! Please login to your account.</p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <div class="login-body">
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn-primary">Login</button>
                </form>
                
                <div class="text-center mt-3">
                    <a href="#" class="small">Forgot Password?</a>
                </div>
                <div class="text-center mt-3">
                    <span>Don't have an account?</span>
                    <a href="/PWDTUBES_WalBayExpress/user/register.php" class="small">Register here</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>