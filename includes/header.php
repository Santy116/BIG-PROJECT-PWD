<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WalBayExpress_CRUD - Gabungan Walmart, eBay, AliExpress</title>
    <link rel="stylesheet" href="/PWDTUBES_WalBayExpress/assets/css/header.css">
    <link rel="stylesheet" href="/PWDTUBES_WalBayExpress/assets/css/main.css">
    <style>
        /* Reset and Base Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Arial', sans-serif;
}

/* Header Styles */
.walmart-header {
    background-color: #0071dc;
    color: white;
    width: 100%;
    position: sticky;
    top: 0;
    z-index: 1000;
}

.top-header {
    display: flex;
    align-items: center;
    padding: 10px 20px;
    max-width: 1400px;
    margin: 0 auto;
}

.header-logo {
    margin-right: 20px;
}

.header-logo .logo {
    height: 40px;
    width: auto;
}

.header-delivery {
    display: flex;
    align-items: center;
    margin-right: 20px;
    cursor: pointer;
    padding: 8px;
    border-radius: 8px;
}

.header-delivery:hover {
    background-color: #004f9a;
}

.delivery-icon img {
    width: 24px;
    height: 24px;
    margin-right: 8px;
}

.delivery-text {
    display: flex;
    flex-direction: column;
}

.deliver-to {
    font-size: 12px;
    line-height: 14px;
}

.location {
    font-size: 14px;
    font-weight: bold;
    line-height: 16px;
}

/* Search Bar Rounded Style */
.header-search {
    flex: 1 1 0%;
    display: flex;
    justify-content: center;
}

.header-search .search-form {
    display: flex;
    align-items: center;
    background: #f8f9fc;
    border-radius: 30px;
    padding: 2px 10px 2px 16px;
    box-shadow: 0 1px 8px rgba(76, 99, 255, 0.06);
    width: 100%;
    max-width: 700px; /* Ubah sesuai kebutuhan, bisa 800px/900px */
    margin: 0 auto;
}

.header-search .search-input {
    border: none;
    outline: none;
    background: transparent;
    padding: 10px 12px;
    border-radius: 30px;
    font-size: 1rem;
    width: 100%;
    min-width: 0;
    color: #333;
}

.header-search .search-button {
    background: linear-gradient(90deg, #3665f3 0%, #4f8cff 100%);
    border: none;
    border-radius: 50%;
    width: 38px;
    height: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-left: 6px;
    cursor: pointer;
    transition: background 0.18s, transform 0.18s;
    box-shadow: 0 2px 8px rgba(54, 101, 243, 0.10);
}

.header-search .search-button:hover {
    background: linear-gradient(90deg, #111 0%, #3665f3 100%);
    transform: scale(1.07);
}


.search-select {
    position: relative;
    width: 120px;
}

.search-select select {
    appearance: none;
    background-color: #f3f3f3;
    border: none;
    border-radius: 4px 0 0 4px;
    padding: 0 30px 0 15px;
    height: 100%;
    width: 100%;
    font-size: 14px;
    cursor: pointer;
    border-right: 1px solid #ddd;
}

.search-button {
    background-color: #05335e !important;
    border: none;
    border-radius: 50% !important;
    width: 36px !important;
    height: 36px !important;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}

.search-button img {
    width: 16px !important;
    height: 16px !important;
    object-fit: contain;
}

.search-button:hover {
    background-color: #0071dc !important;
}

.header-actions {
    display: flex;
    align-items: center;
}

    .shopping-easy-banner {
    background: #f4f8ff;
    border-radius: 18px;
    box-shadow: 0 4px 24px rgba(76, 99, 255, 0.08);
    padding: 40px 24px 32px 24px;
    margin: 36px auto 40px auto;
    max-width: 1200px; /* Lebar maksimum diperbesar */
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 24px;
    text-align: left; /* Pastikan teks rata kiri */
}

.shopping-easy-banner-content {
    flex: 1 1 auto; /* Konten fleksibel */
    text-align: left; /* Pastikan teks rata kiri */
}

.start-now-btn {
    display: inline-block;
    background: #000000 !important;
    color: #fff;
    font-weight: 600;
    font-size: 1.08rem;
    padding: 12px 36px;
    border-radius: 30px;
    text-decoration: none;
    box-shadow: 0 2px 8px rgba(54, 101, 243, 0.10);
    transition: background 0.18s, transform 0.18s;
    border: none;
    margin-left: 50px; /* Jarak lebih jauh ke kanan */
    white-space: nowrap;
    align-self: flex-start; /* Tetap di sisi kanan */
}
.start-now-btn:hover {
    background: #111;
    color: #fff;
    transform: scale(1.05);
}

@media (max-width: 700px) {
    .shopping-easy-banner {
        flex-direction: column;
        align-items: stretch;
        text-align: left;
        padding: 20px 6px 18px 6px;
        max-width: 98vw;
        border-radius: 10px;
        gap: 12px;
    }
    .shopping-easy-banner-content {
        margin-bottom: 10px;
        text-align: left;
    }
    .start-now-btn {
        margin-left: 0;
        align-self: center;
        padding: 10px 20px;
        font-size: 1rem;
    }
}

.scrollable-banner {
    width: 100%;
    max-width: 1200px; /* Lebar maksimum banner */
    margin: 0 auto 32px auto;
    position: relative;
    overflow: hidden;
    border-radius: 18px;
    box-shadow: 0 4px 24px rgba(76, 99, 255, 0.08);
    background: #fff;
}

.banner-container {
    display: flex;
    transition: transform 0.5s cubic-bezier(.4, 0, .2, 1);
    will-change: transform;
}

.banner-slide {
    min-width: 100%;
    box-sizing: border-box;
    text-align: center;
}

.banner-slide img {
    width: 100%;
    height: 350px; /* Tinggi banner diperbesar */
    object-fit: cover; /* Memastikan gambar tidak terdistorsi */
    border-radius: 18px;
}

.banner-dots {
    position: absolute;
    bottom: 18px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 8px;
}

.banner-dots .dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #d3d3d3;
    cursor: pointer;
    transition: background 0.2s;
    display: inline-block;
}

.banner-dots .dot.active {
    background: #4c63ff;
}

@media (max-width: 500px) {
    .banner-slide img {
        height: 200px; /* Tinggi banner untuk layar kecil */
        border-radius: 10px;
    }
    .scrollable-banner {
        border-radius: 10px;
    }
}

/* Hover Effect for Account, Cart, and Wishlist */
.header-account:hover,
.header-cart:hover,
.header-wishlist:hover {
    background-color: rgba(20, 81, 135, 0.81); /* Warna latar belakang saat hover */
    border-radius: 5px; /* Membuat sudut melengkung */
}

/* Hover Effect for Links Inside */
.header-account a:hover,
.header-cart a:hover,
.header-wishlist a:hover {
    text-decoration: none; /* Menghapus garis bawah pada teks */
    color:rgb(255, 255, 255); /* Warna teks saat hover */
}

.header-actions {
    display: flex;
    align-items: center;
}

/* Account Section Styling */
.header-account {
    display: flex;
    align-items: center;
    gap: 8px; /* Jarak antara gambar dan teks */
    margin-right: 20px;
}

.account-link {
    display: flex;
    align-items: center;
    gap: 8px; /* Jarak antara gambar dan teks */
    text-decoration: none;
    color: #fff;
    font-size: 14px;
    font-weight: bold;
}

.account-link img {
    width: 32px; /* Ukuran gambar admin */
    height: 32px;
    border-radius: 0%; /* Membuat gambar berbentuk lingkaran */
    object-fit: cover; /* Memastikan gambar tidak terdistorsi */

}
/* Reset and Base Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Arial', sans-serif;
}

/* Header Styles */
.walmart-header {
    background-color: #0071dc;
    color: white;
    width: 100%;
    position: sticky;
    top: 0;
    z-index: 1000;
}

.top-header {
    display: flex;
    align-items: center;
    padding: 10px 20px;
    max-width: 1400px;
    margin: 0 auto;
}

.header-logo {
    margin-right: 20px;
}

.header-logo .logo {
    height: 40px;
    width: auto;
}
/* Hover Effect for Logo */
.header-logo a {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: background 0.3s, transform 0.3s, opacity 0.3s;
    padding: 6px; /* Agar hover bulat menyelimuti gambar */
}
.header-logo a:hover {
    opacity: 0.93;
    transform: scale(1.13);
    background-color: #004080;
    border-radius: 50%;
    padding: 6px;
}

.header-delivery {
    display: flex;
    align-items: center;
    margin-right: 20px;
    cursor: pointer;
    padding: 8px;
    border-radius: 8px;
}

.header-delivery:hover {
    background-color: #004f9a;
}

.delivery-icon img {
    width: 24px;
    height: 24px;
    margin-right: 8px;
}

.delivery-text {
    display: flex;
    flex-direction: column;
}

.deliver-to {
    font-size: 12px;
    line-height: 14px;
}

.location {
    font-size: 14px;
    font-weight: bold;
    line-height: 16px;
}

/* Search Bar Styles */
.header-search {
    flex: 1 1 0%;
    display: flex;
    justify-content: center;
}

.header-search .search-form {
    display: flex;
    align-items: center;
    background: #f8f9fc;
    border-radius: 30px;
    padding: 2px 10px 2px 16px;
    box-shadow: 0 1px 8px rgba(76, 99, 255, 0.06);
    width: 100%;
    max-width: 700px;
    margin: 0 auto;
}

.header-search .search-input {
    border: none;
    outline: none;
    background: transparent;
    padding: 10px 12px;
    border-radius: 30px;
    font-size: 1rem;
    width: 100%;
    color: #333;
}

.header-search .search-button {
    background: linear-gradient(90deg, #3665f3 0%, #4f8cff 100%);
    border: none;
    border-radius: 50%;
    width: 38px;
    height: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-left: 6px;
    cursor: pointer;
    transition: background 0.18s, transform 0.18s;
    box-shadow: 0 2px 8px rgba(54, 101, 243, 0.10);
}

.header-search .search-button:hover {
    background: linear-gradient(90deg, #111 0%, #3665f3 100%);
    transform: scale(1.07);
}

/* Account Section Styling */
.header-account {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-right: 20px;
}

.account-link {
    display: flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    color: white;
    font-size: 14px;
    font-weight: bold;
}

.account-link img {
    width: 32px;
    height: 32px;
    border-radius: 0%;
    object-fit: cover;
}

.header-cart {
    position: relative; /* Membuat posisi relatif untuk container */
    display: flex;
    align-items: center;
    gap: 8px;
}

.header-cart img {
    width: 32px; /* Ukuran ikon keranjang */
    height: 32px;
    object-fit: contain;
}

.cart-count {
    position: absolute;
    top: -4px;
    right: -4px;
    background-color: rgb(249, 237, 14);
    color: #d2691e;
    font-size: 10px;
    font-weight: bold;
    padding: 1px 4px;
    border-radius: 50%;
    border: 1px solid rgb(242, 157, 38);
    box-shadow: 0 0 0 1px rgb(239, 150, 25);
    min-width: 7px;
    height: 16px;
    line-height: 14px;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Navigation Menu */
.header-nav {
    margin-top: 10px;
    width: 100%;
    background-color: rgb(252, 252, 252);
    color: #0a2540;
}

/* Tambahkan jarak antar teks menu */
.nav-item {
    margin: 0,3px; /* Atur jarak horizontal antar teks menu */
}

.nav-list {
    display: flex;
    justify-content: center;
    gap: 20px;
    padding: 10px 0;
    list-style: none;
    margin: 0;
}

.nav-item a {
    text-decoration: none;
    color: #0a2540;
    font-size: 16px;
    font-weight: normal;
    transition: color 0.3s, text-decoration 0.3s;
    position: relative;
}

.nav-item a:hover {
    color: #0071dc; /* Warna teks saat hover */
    text-decoration: underline; /* Garis bawah saat hover */
}

.nav-item a:hover::after {
    width: 100%; /* Garis bawah penuh saat hover */
}

/* Wishlist Dropdown */
.wishlist-dropdown {
    display: none;
    position: fixed;
    top: 58px; /* Dekat dengan header-wishlist, bisa disesuaikan */
    right: 60px; /* Dekat dengan tombol Reorder My Items, sesuaikan jika perlu */
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(44, 62, 80, 0.13);
    z-index: 2000;
    width: 200px;
    padding: 8px 0;
    min-width: 140px;
}

.wishlist-dropdown ul {
    list-style: none;
    margin: 0;
    padding: 0;
}

.wishlist-dropdown ul li {
    margin: 0;
    padding: 4px 10px;
    display: flex;
    align-items: center;
    gap: 7px;
    color: #333a42;
    font-size: 13px;
    font-weight: 500;
    border-radius: 5px;
    transition: background 0.15s;
    min-height: 32px;
}

.wishlist-dropdown ul li:hover {
    background: #f4f7fa;
}

.wishlist-dropdown ul li a {
    text-decoration: none;
    color: #333a42;
    display: flex;
    align-items: center;
    gap: 7px;
    width: 100%;
    font-size: 13px;
}

.wishlist-dropdown ul li a img {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    object-fit: cover;
    background: #f7f7f7;
    border: 1px solid #e0e0e0;
    margin-right: 4px;
}

.wishlist-dropdown ul li a {
    text-decoration: none; /* Menghilangkan garis bawah */
    color: #333a42; /* Warna teks */
    display: flex;
    align-items: center;
    gap: 7px;
    width: 100%;
    font-size: 13px;
}

.wishlist-dropdown ul li a:hover {
    text-decoration: underline; /* Pastikan tetap tidak ada garis bawah saat hover */
    color:rgb(10, 10, 10); /* Warna teks saat hover */
}
    </style>
    <script>
    function toggleDropdown(event) {
    event.preventDefault(); // Mencegah link default
    const dropdown = document.getElementById('wishlistDropdown');
    dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
}

// Menutup dropdown jika klik di luar elemen
document.addEventListener('click', function (e) {
    const dropdown = document.getElementById('wishlistDropdown');
    const wishlistLink = document.querySelector('.wishlist-link');
    if (!wishlistLink.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.style.display = 'none';
    }
});
    </script>
</head>
<body>
    <header class="walmart-header">
        <div class="top-header">
            <div class="header-logo">
                <a href="/index.php" class="logo-link">
                    <img src="/PWDTUBES_WalBayExpress/assets/img/logo.png" alt="Walmart Logo" class="logo">
                </a>
            </div>
            
            <div class="header-delivery">
                <div class="delivery-icon">
                    <img src="/PWDTUBES_WalBayExpress/assets/img/location-icon.png" alt="Location">
                </div>
                <div class="delivery-text">
                    <span class="deliver-to">Deliver to</span>
                    <span class="location">Indonesia</span>
                </div>
            </div>
            
            <div class="header-search">
                <form action="/PWDTUBES_WalBayExpress/menus/search.php" method="get" class="search-form">
                    <input type="text" name="q" placeholder="Search WalBayExpress..." class="search-input">
                    <button type="submit" class="search-button">
                        <img src="/PWDTUBES_WalBayExpress/assets/img/search-icon.png" alt="Search">
                    </button>
                </form>
            </div>
            
            <div class="header-account">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if ($_SESSION['user_role'] === 'admin'): ?>
                        <a href="/PWDTUBES_WalBayExpress/admin/dashboard.php" class="account-link">
                            <img src="/PWDTUBES_WalBayExpress/assets/img/admin-icon.png" alt="Admin Icon">
                            <?= htmlspecialchars($_SESSION['user_first_name'] ?? 'Admin') ?>
                        </a>
                    <?php elseif ($_SESSION['user_role'] === 'client'): ?>
                        <a href="/PWDTUBES_WalBayExpress/user/akun.php" class="account-link">
                            <img src="/PWDTUBES_WalBayExpress/assets/img/girl (5).png" alt="User Icon">
                            <?= htmlspecialchars($_SESSION['user_first_name'] ?? 'User') ?>
                        </a>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="/PWDTUBES_WalBayExpress/admin/login.php" class="account-link">
                        <img src="/PWDTUBES_WalBayExpress/assets/img/admin-icon.png" alt="Sign In">
                        Sign In
                    </a>
                <?php endif; ?>
            </div>

                <div class="header-wishlist">
                    <a href="#" class="wishlist-link" onclick="toggleDropdown(event)" style="display:flex;align-items:center;text-decoration:none;">
                        <img src="/PWDTUBES_WalBayExpress/assets/img/wishlist-icon.png" alt="Wishlist" style="width:24px; height:24px; object-fit:contain; margin-right:6px;">
                        <div class="wishlist-text" style="display:flex;flex-direction:column;white-space:nowrap;padding:0 6px;border-radius:4px;">
                            <div style="font-size:13px;line-height:20px;color:#fff;">Reorder</div>
                            <div style="font-weight:bold;font-size:15px;line-height:18px;margin-top:-4px;color:#fff;">My Items</div>
                        </div>
                    </a>
                    <div class="wishlist-dropdown" id="wishlistDropdown">
                        <ul>
                            <li>
                                <a href="/PWDTUBES_WalBayExpress/menus/reorder.php">
                                    <img src="/PWDTUBES_WalBayExpress/assets/img/cycle.png" alt="Reorder Photo" style="width:22px;height:22px;border-radius:50%;object-fit:cover;margin-right:8px;">
                                    <i class="fas fa-sync-alt"></i> Reorder
                                </a>
                            </li>
                            <li>
                                <a href="/PWDTUBES_WalBayExpress/menus/wishlist.php">
                                    <img src="/PWDTUBES_WalBayExpress/assets/img/wishlist.png" alt="Wishlist Photo" style="width:22px;height:22px;border-radius:50%;object-fit:cover;margin-right:8px;">
                                    <i class="fas fa-heart"></i> Wishlist
                                </a>
                            </li>
                            <li>
                                <a href="/PWDTUBES_WalBayExpress/user/register.php">
                                    <img src="/PWDTUBES_WalBayExpress/assets/img/register.png" alt="Register Photo" style="width:22px;height:22px;border-radius:50%;object-fit:cover;margin-right:8px;">
                                    <i class="fas fa-user-plus"></i> Register
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="header-cart">
                    <a href="/PWDTUBES_WalBayExpress/menus/keranjang.php" class="cart-link">
                        <img src="/PWDTUBES_WalBayExpress/assets/img/cart-icon.png" alt="Cart">
                        <span class="cart-count">
                            <?php
                            echo isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0;
                            ?>
                        </span>
                    </a>
                </div>
            </div>
        </div>
        
        <nav class="header-nav">
            <ul class="nav-list">
                <li class="nav-item"><a href="/PWDTUBES_WalBayExpress/index.php">Home</a></li>
                <li class="nav-item"><a href="/PWDTUBES_WalBayExpress/menus/produk.php">Products</a></li>
                <li class="nav-item"><a href="/PWDTUBES_WalBayExpress/menus/kategori.php">Categories</a></li>
                <li class="nav-item"><a href="/PWDTUBES_WalBayExpress/menus/promo.php">Promo</a></li>
                <li class="nav-item"><a href="/PWDTUBES_WalBayExpress/menus/deals.php">Deals</a></li>
                <li class="nav-item"><a href="/PWDTUBES_WalBayExpress/menus/electronics.php">Electronics</a></li>
                <li class="nav-item"><a href="/PWDTUBES_WalBayExpress/menus/fashion.php">Fashion</a></li>
                <li class="nav-item"><a href="/PWDTUBES_WalBayExpress/menus/home-garden.php">Home & Garden</a></li>
                <li class="nav-item"><a href="/PWDTUBES_WalBayExpress/menus/sports.php">Sports</a></li>
                <li class="nav-item"><a href="/PWDTUBES_WalBayExpress/menus/toys.php">Toys</a></li>
                <li class="nav-item"><a href="/PWDTUBES_WalBayExpress/menus/grocery.php">Grocery</a></li>
            </ul>
        </nav>
    </header>
    
    <main class="main-content">
</body>
</html>

