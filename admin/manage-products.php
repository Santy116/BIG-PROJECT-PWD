<?php
require_once '../config.php';
if (isset($_GET['error']) && $_GET['error'] === 'update_failed') {
    echo "<div class='alert error'>Gagal mengubah status produk.</div>";
}

// Statistik produk
$total_products = $conn->query("SELECT COUNT(*) FROM products")->fetchColumn();
$low_stock_products = $conn->query("SELECT COUNT(*) FROM products WHERE stock <= 5")->fetchColumn();
$no_image_products = $conn->query("SELECT COUNT(*) FROM products WHERE image IS NULL OR image = ''")->fetchColumn();
$pending_orders = 12;

// Ambil semua kategori
$categories = $conn->query("SELECT * FROM categories ORDER BY name")->fetchAll();

// Ambil parameter filter & search
$selected_category = isset($_GET['category']) ? intval($_GET['category']) : 0;
$search = trim($_GET['search'] ?? '');
$sort = $_GET['sort'] ?? 'newest';

// Query produk dinamis
$where = [];
$params = [];
if ($selected_category) {
    $where[] = "p.category_id = ?";
    $params[] = $selected_category;
}
if ($search) {
    $where[] = "(p.name LIKE ? OR p.description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}
$where_sql = $where ? 'WHERE ' . implode(' AND ', $where) : '';
switch ($sort) {
    case 'price_asc':
        $order_sql = 'ORDER BY p.price ASC';
        break;
    case 'price_desc':
        $order_sql = 'ORDER BY p.price DESC';
        break;
    case 'stock_asc':
        $order_sql = 'ORDER BY p.stock ASC';
        break;
    case 'stock_desc':
        $order_sql = 'ORDER BY p.stock DESC';
        break;
    case 'oldest':
        $order_sql = 'ORDER BY p.created_at ASC';
        break;
    default:
        $order_sql = 'ORDER BY p.created_at DESC';
        break;
}
$sql = "SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id $where_sql $order_sql";
$stmt = $conn->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

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
    <title>Manage Products - Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <style>
    /* SCOPED: hanya untuk konten manage-products */
    .manage-products-content {
        margin-top: 32px;
        padding-bottom: 24px;
    }
    @media (max-width: 900px) {
        .manage-products-content { margin-top: 16px; }
    }
    .manage-products-content .stats-container {
        display: flex;
        gap: 20px;
        margin-bottom: 30px;
        flex-wrap: wrap;
    }
    .manage-products-content .stat-card {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        display: flex;
        align-items: center;
        padding: 18px 24px;
        min-width: 220px;
        flex: 1 1 200px;
    }
    .manage-products-content .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 18px;
    }
    .manage-products-content .stat-icon img {
        width: 32px;
        height: 32px;
    }
    .manage-products-content .stat-info p {
        margin: 0;
        color: #888;
        font-size: 14px;
    }
    .manage-products-content .stat-info h3 {
        margin: 2px 0 0 0;
        font-size: 22px;
        color: #444;
    }
    .manage-products-content .filter-bar {
        margin-bottom: 18px;
        background: #f8f9fc;
        padding: 12px 18px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .manage-products-content .filter-bar form {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    .manage-products-content .filter-bar select,
    .manage-products-content .filter-bar input[type="text"] {
        padding: 6px 10px;
        border: 1px solid #d1d3e2;
        border-radius: 5px;
        font-size: 14px;
    }
    .manage-products-content .filter-bar button {
        background: #4e73df;
        color: #fff;
        border: none;
        padding: 7px 16px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        transition: background 0.2s;
    }
    .manage-products-content .filter-bar button:hover {
        background: #2e59d9;
    }
    .manage-products-content .products-table table {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    }
    .manage-products-content .products-table th, 
    .manage-products-content .products-table td {
        padding: 10px 12px;
        text-align: left;
        border-bottom: 1px solid #f1f1f1;
        font-size: 14px;
    }
    .manage-products-content .products-table th {
        background: #f8f9fc;
        color: #4e73df;
        font-weight: 600;
    }
    .manage-products-content .products-table tr:last-child td {
        border-bottom: none;                                 
    }
    .manage-products-content .product-thumbnail {
        width: 48px;
        height: 48px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid #eee;
        background: #fafafa;
    }
    .manage-products-content .no-image {
        width: 48px;
        height: 48px;
        background: #f6c23e;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        font-size: 12px;
        font-weight: bold;
    }
    .manage-products-content .badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        color: #fff;
    }
    .manage-products-content .badge-diskon { background: #1cc88a; }
    .manage-products-content .badge-active { background: #4e73df; }
    .manage-products-content .badge-inactive { background: #e74a3b; }
    .manage-products-content .action-btn {
        display: inline-block;
        padding: 4px 10px;
        margin: 1px 2px;
        border-radius: 5px;
        font-size: 12px;
        text-decoration: none;
        color: #fff;
        transition: background 0.2s;
    }
    .manage-products-content .view-btn { background: #36b9cc; }
    .manage-products-content .edit-btn { background: #f6c23e; color: #222; }
    .manage-products-content .delete-btn { background: #e74a3b; }
    .manage-products-content .toggle-btn { background: #858796; }
    .manage-products-content .action-btn:hover { opacity: 0.85; }
    @media (max-width: 900px) {
        .manage-products-content .stats-container { flex-direction: column; gap: 12px; }
        .manage-products-content .stat-card { min-width: 0; }
        .manage-products-content .products-table table, 
        .manage-products-content .products-table th, 
        .manage-products-content .products-table td { font-size: 13px; }
    }
    </style>
</head>
<body>
<div class="admin-container">
    <?php include '../includes/admin-sidebar.php'; ?>
    <div class="main-content">
        <?php include '../includes/admin-topbar.php'; ?>
        <div class="dashboard-content manage-products-content">
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
                <<div class="stat-card">
    <div class="stat-icon" style="background-color: #e74a3b;">
        <img src="/PWDTUBES_WalBayExpress/assets/img/logo/file (1).png" alt="Pending">
    </div>
    <div class="stat-info">
        <p>Pending Orders</p>
        <h3><?= $pending_orders ?></h3>
    </div>
</div>
            </div>
            <h3>Manage Products</h3>
            <div class="filter-bar">
                <form method="get">
                    <label for="category">Category:</label>
                    <select name="category" id="category" onchange="this.form.submit()">
                        <option value="0">All</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= $selected_category == $cat['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <input type="text" name="search" placeholder="Search product..." value="<?= htmlspecialchars($search) ?>">
                    <select name="sort" onchange="this.form.submit()">
                        <option value="newest" <?= $sort == 'newest' ? 'selected' : '' ?>>Newest</option>
                        <option value="oldest" <?= $sort == 'oldest' ? 'selected' : '' ?>>Oldest</option>
                        <option value="price_asc" <?= $sort == 'price_asc' ? 'selected' : '' ?>>Price ↑</option>
                        <option value="price_desc" <?= $sort == 'price_desc' ? 'selected' : '' ?>>Price ↓</option>
                        <option value="stock_asc" <?= $sort == 'stock_asc' ? 'selected' : '' ?>>Stock ↑</option>
                        <option value="stock_desc" <?= $sort == 'stock_desc' ? 'selected' : '' ?>>Stock ↓</option>
                    </select>
                    <button type="submit">Filter</button>
                </form>
            </div>
            <div class="products-table">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Discount</th>
                            <th>Status</th>
                            <th>Category</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $counter = 1; ?>
                        <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?= $counter++ ?></td>
                            <td>
                                <?php if ($product['image']): ?>
                                    <img src="../assets/img/products/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-thumbnail">
                                <?php else: ?>
                                    <div class="no-image">No Image</div>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($product['name']) ?></td>
                            <td style="max-width:200px;white-space:pre-line;overflow:hidden;text-overflow:ellipsis;">
                                <?= htmlspecialchars(mb_strimwidth($product['description'], 0, 120, '...')) ?>
                            </td>
                            <td>$<?= number_format($product['price'], 2) ?></td>
                            <td><?= $product['stock'] ?></td>
                            <td>
                                <?php if ($product['discount'] > 0): ?>
                                    <span class="badge badge-diskon"><?= $product['discount'] ?>%</span>
                                <?php else: ?>
                                    <span style="color:#aaa;">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (isset($product['active']) && $product['active']): ?>
                                    <span class="badge badge-active">Active</span>
                                <?php else: ?>
                                    <span class="badge badge-inactive">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars(ucfirst($product['category_name'])) ?></td>
                            <td>
                                <a href="produk/view.php?id=<?= $product['id'] ?>" class="action-btn view-btn" title="View">View</a>
                                <a href="produk/edit.php?id=<?= $product['id'] ?>" class="action-btn edit-btn" title="Edit">Edit</a>
                                <a href="produk/delete.php?id=<?= $product['id'] ?>&redirect=../manage-products.php?<?= http_build_query($_GET) ?>" class="action-btn delete-btn" onclick="return confirm('Are you sure?')">Delete</a>
                                <?php if (isset($product['active']) && $product['active']): ?>
                                    <a href="produk/toggle.php?id=<?= $product['id'] ?>&action=deactivate&redirect=../manage-products.php?<?= http_build_query($_GET) ?>" class="action-btn toggle-btn" title="Deactivate">Deactivate</a>
                                <?php else: ?>
                                    <a href="produk/toggle.php?id=<?= $product['id'] ?>&action=activate&redirect=../manage-products.php?<?= http_build_query($_GET) ?>" class="action-btn toggle-btn" title="Activate">Activate</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (count($products) === 0): ?>
                        <tr>
                            <td colspan="10" style="text-align:center;color:#aaa;">No products found.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>