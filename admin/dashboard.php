<?php
require_once '../includes/auth.php'; // Pastikan auth.php sudah ada
require_once '../config.php';


// Pindahkan session_start() ke atas
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
//     header('Location: /PWDTUBES_WalBayExpress/admin/login.php');
//     exit;
// }

if ($_SESSION['user_role'] !== 'admin') {
    header('Location: /PWDTUBES_WalBayExpress/admin/login.php');
    exit;
}
// Pastikan koneksi database sudah ada
if (!isset($conn)) {
    try {
        $conn = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
    } catch (PDOException $e) {
        die("⚠️ Database connection failed: " . $e->getMessage());
    }
}

// Pastikan timezone sudah diatur
date_default_timezone_set('Asia/Jakarta');

// Hitung jumlah produk
$stmt = $conn->query("SELECT COUNT(*) FROM products");
$total_products = $stmt->fetchColumn();

// Hitung produk low stock
$low_stock_threshold = 10;
$stmt = $conn->prepare("SELECT COUNT(*) FROM products WHERE stock < :threshold");
$stmt->execute([':threshold' => $low_stock_threshold]);
$low_stock_products = $stmt->fetchColumn();

// Hitung jumlah pesanan pending
$stmt = $conn->query("SELECT COUNT(*) FROM orders WHERE status = 'pending'");
$pending_orders = $stmt->fetchColumn();

// Hitung jumlah produk tanpa gambar
$stmt = $conn->query("SELECT COUNT(*) FROM products WHERE image IS NULL OR image = ''");
$no_image_products = $stmt->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - WalBayExpress_CRUD</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <?php include '../includes/admin-sidebar.php'; ?>
        <!-- Main Content -->
        <div class="main-content">
            <!-- Topbar -->
            <?php include '../includes/admin-topbar.php'; ?>

            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <div class="stats-container">
                    <div class="stat-card">
                        <div class="stat-icon" style="background-color: #4e73df;">
                            <img src="/PWDTUBES_WalBayExpress/assets/img/logo/product.png" alt="Products">
                        </div>
                        <div class="stat-info">
                            <p>Total Products</p>
                            <h3><?= $total_products ?></h3>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon" style="background-color: #1cc88a;">
                            <img src="/PWDTUBES_WalBayExpress/assets/img/logo/out-of-stock.png" alt="Stock">
                        </div>
                        <div class="stat-info">
                            <p>Low Stock</p>
                            <h3><?= $low_stock_products ?></h3>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon" style="background-color: #f6c23e;">
                            <img src="/PWDTUBES_WalBayExpress/assets/img/logo/no-image.png" alt="Images">
                        </div>
                        <div class="stat-info">
                            <p>No Image</p>
                            <h3><?= $no_image_products ?></h3>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon" style="background-color: #e74a3b;">
                            <img src="/PWDTUBES_WalBayExpress/assets/img/logo/file (1).png" alt="Pending">
                        </div>
                        <div class="stat-info">
                            <p>Pending Orders</p>
                            <h3><?= $pending_orders ?></h3>
                        </div>
                    </div>
                </div>
                
                <div class="recent-products">
                    <div class="section-header">
                        <h3>Recent Products</h3>
                        <a href="/PWDTUBES_WalBayExpress/admin/products.php" class="view-all">View All</a>
                    </div>
                    <div class="products-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Category</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // JOIN ke tabel categories agar dapat nama kategori
                                $stmt = $conn->query("SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.created_at DESC LIMIT 5");
                                while ($product = $stmt->fetch()):
                                ?>
                                <tr>
                                    <td><?= $product['id'] ?></td>
                                    <td>
                                        <?php if ($product['image']): ?>
                                            <img src="../assets/img/products/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-thumbnail">
                                        <?php else: ?>
                                            <div class="no-image">No Image</div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($product['name']) ?></td>
                                    <td>$<?= number_format($product['price'], 2) ?></td>
                                    <td><?= $product['stock'] ?></td>
                                    <td><?= htmlspecialchars(ucfirst($product['category_name'])) ?></td>
                                    <td>
                                        <a href="produk/edit.php?id=<?= $product['id'] ?>" class="action-btn edit-btn">Edit</a>
                                        <a href="produk/delete.php?id=<?= $product['id'] ?>" class="action-btn delete-btn" onclick="return confirm('Are you sure?')">Delete</a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/admin.js"></script>
</body>
</html>