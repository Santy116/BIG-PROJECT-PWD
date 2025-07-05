<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

session_unset();
session_destroy();

// Hapus cookie session
setcookie(session_name(), '', time() - 42000, '/');

header('Location: /PWDTUBES_WalBayExpress/index.php');
exit;
?>