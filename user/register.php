<?php
require_once '../config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $address = trim($_POST['address']);

    // Validasi input
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password) || empty($address)) {
        $error = 'All fields are required.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long.';
    } else {
        try {
            // Cek apakah email sudah terdaftar
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
            $stmt->execute([':email' => $email]);
            if ($stmt->fetch()) {
                $error = 'Email already registered.';
            } else {
                // Hash password
                $password_hash = password_hash($password, PASSWORD_DEFAULT);

                // Masukkan data user baru ke database
                $stmt = $conn->prepare("INSERT INTO users (name, email, password, address, role) 
                                        VALUES (:name, :email, :password, :address, 'client')");
                $stmt->execute([
                    ':name' => $name,
                    ':email' => $email,
                    ':password' => $password_hash,
                    ':address' => $address
                ]);

                // Mulai session jika belum
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                // Set session untuk user
                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_name'] = $name;
                $_SESSION['user_email'] = $email;
                $_SESSION['user_role'] = 'client';

                $success = 'Registrasi berhasil! Mengarahkan ke akun Anda...';
                header("Location: /PWDTUBES_WalBayExpress/user/akun.php");
                exit;
            }
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - WalBayExpress</title>
    <style>
    :root {
    --primary: #4e73df;
    --primary-dark: #2e59d9;
    --danger: #e74a3b;
    --light-danger: #f8d7da;
    --success: #1cc88a;
    --gray: #858796;
    --light: #f8f9fc;
    --dark: #5a5c69;
}

body {
    font-family: 'Nunito', sans-serif;
    background-color: var(--light);
    min-height: 100vh;
    padding: 20px 0;
}

.container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 90vh;
}

.auth-container {
    width: 100%;
    max-width: 420px;
    margin: 0 auto;
}

.auth-form {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    padding: 2.5rem 2rem 2rem 2rem;
}

.auth-form h1 {
    font-size: 1.5rem;
    color: var(--primary);
    text-align: center;
    margin-bottom: 1.5rem;
    font-weight: 700;
}

.alert {
    padding: 0.75rem 1rem;
    margin-bottom: 1.5rem;
    border-radius: 4px;
    font-size: 0.95rem;
}

.alert.error, .alert-danger {
    background-color: var(--light-danger);
    color: var(--danger);
    border: 1px solid rgba(231, 74, 59, 0.2);
}

.alert.success {
    background-color: #d4edda;
    color: var(--success);
    border: 1px solid #c3e6cb;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label,
.form-label {
    display: block;
    margin-bottom: 0.5rem;
    color: var(--dark);
    font-weight: 600;
    font-size: 0.95rem;
}

.form-group input,
.form-control {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid #d1d3e2;
    border-radius: 4px;
    font-size: 1rem;
    transition: all 0.3s;
    background: #f8f9fc;
}

.form-group input:focus,
.form-control:focus {
    border-color: var(--primary);
    outline: none;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.15);
}

.form-row {
    display: flex;
    gap: 16px;
}

.form-row .form-group {
    flex: 1 1 0;
    margin-bottom: 0;
}

.form-options {
    margin-bottom: 1.2rem;
    font-size: 0.97rem;
    color: var(--gray);
}

.form-options input[type="checkbox"] {
    accent-color: var(--primary);
    margin-right: 6px;
}

.form-agreement {
    font-size: 0.93rem;
    color: var(--gray);
    margin-bottom: 1.5rem;
}

.form-agreement a {
    color: var(--primary);
    text-decoration: underline;
}

.auth-btn, .btn, .btn-primary {
    display: inline-block;
    font-weight: 600;
    text-align: center;
    border: 1px solid transparent;
    padding: 0.75rem 1.5rem;
    font-size: 1rem;
    line-height: 1.5;
    border-radius: 4px;
    transition: all 0.15s;
    cursor: pointer;
    width: 100%;
    background-color: var(--primary);
    color: #fff;
}

.auth-btn:hover, .btn-primary:hover {
    background-color: var(--primary-dark);
}

.auth-footer {
    text-align: center;
    margin-top: 1.5rem;
    font-size: 0.97rem;
    color: var(--gray);
}

.auth-footer a {
    color: var(--primary);
    text-decoration: underline;
}

.auth-banner {
    display: none; /* Sembunyikan banner di mode simple, atau atur sesuai kebutuhan */
}

@media (max-width: 600px) {
    .auth-form {
        padding: 1.2rem 0.7rem 1.2rem 0.7rem;
    }
    .container {
        padding: 0 4px;
    }
}
</style>
</head>
<body>
    <div class="container">
        <div class="auth-container">
            <div class="auth-form">
                <h1>Create New Account</h1>
                
                <?php if ($error): ?>
                    <div class="alert error"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert success"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>
                
                <form method="post">
                    <div class="form-group">
                        <label for="name">Full Name *</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="address">Address *</label>
                        <textarea id="address" name="address" rows="3" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password *</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password *</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                    
                    <button type="submit" class="auth-btn">Create Account</button>
                </form>
                
                <div class="auth-footer">
                    Already have an account? <a href="/PWDTUBES_WalBayExpress/admin/login.php">Sign in</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>