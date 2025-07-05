<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>
<div class="topbar">
    <div class="topbar-left">
        <button class="sidebar-toggle">
            <img src="/PWDTUBES_WalBayExpress/assets/img/logo/menu (2).png" alt="Menu">
        </button>
        <h2>
            <?php
                if (isset($page_title)) {
                    echo htmlspecialchars($page_title);
                } else {
                    echo ucfirst(basename($_SERVER['PHP_SELF'], '.php'));
                }
            ?>
        </h2>
    </div>
    <div class="topbar-right">
        <div class="admin-profile">
            <img src="/PWDTUBES_WalBayExpress/assets/img/logo/user (4).png" alt="Admin Avatar" class="admin-avatar">
            <span>
                <?= isset($_SESSION['admin_name']) ? htmlspecialchars($_SESSION['admin_name']) : 'Admin'; ?>
            </span>
        </div>
        <div class="notifications">
            <img src="/PWDTUBES_WalBayExpress/assets/img/logo/bell.png" alt="Notifications">
            <span class="notification-badge">3</span>
        </div>
    </div>
</div>