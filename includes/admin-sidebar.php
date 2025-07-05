<div class="sidebar">
    <div class="sidebar-header">
        <img src="/PWDTUBES_WalBayExpress/assets/img/logo/admin-logo.png" alt="Admin Logo" class="sidebar-logo">
        <h2>WalBayExpress</h2>
        <p>Admin Panel</p>
    </div>
    
    <div class="sidebar-menu">
        <ul>
            <li class="<?= basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : '' ?>">
                <a href="/PWDTUBES_WalBayExpress/admin/dashboard.php"><img src="/PWDTUBES_WalBayExpress/assets/img/logo/dashboard.png" alt="Dashboard">Dashboard</a>
            </li>
            <li class="<?= basename($_SERVER['PHP_SELF']) === 'create.php' ? 'active' : '' ?>">
                <a href="/PWDTUBES_WalBayExpress/admin/produk/create.php"><img src="/PWDTUBES_WalBayExpress/assets/img/logo/add-product.png" alt="Add Product"> Add Product</a>
            </li>
            <li>
                <a href="/PWDTUBES_WalBayExpress/admin/manage-products.php"><img src="/PWDTUBES_WalBayExpress/assets/img/logo/product-management (1).png" alt="Products"> Manage Products</a>
            </li>
            <li>
                <a href="/PWDTUBES_WalBayExpress/admin/orders.php"><img src="/PWDTUBES_WalBayExpress/assets/img/logo/checkout.png" alt="Orders"> Orders</a>
            </li>
            <li class="<?= basename($_SERVER['PHP_SELF']) === 'users.php' ? 'active' : '' ?>">
                <a href="/PWDTUBES_WalBayExpress/admin/users.php"><img src="/PWDTUBES_WalBayExpress/assets/img/logo/user (3).png" alt="Users"> Manage Users</a>
            </li>
            <li>
                <a href="/PWDTUBES_WalBayExpress/index.php"><img src="/PWDTUBES_WalBayExpress/assets/img/logo/hypothesis.png" alt="Check Changes"> Track Changes</a>
            </li>
            <li class="<?= basename($_SERVER['PHP_SELF']) === 'settings.php' ? 'active' : '' ?>">
                <a href="/PWDTUBES_WalBayExpress/admin/settings.php"><img src="/PWDTUBES_WalBayExpress/assets/img/logo/setting.png" alt="Settings"> Settings</a>
            </li><br>
        </ul>
    </div>
    <div class="sidebar-footer">
        <a href="/PWDTUBES_WalBayExpress/admin/logout.php" class="logout-button"><img src="/PWDTUBES_WalBayExpress/assets/img/logo/logout.png" alt="Logout"> Logout</a>
    </div>
</div>
