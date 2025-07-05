<?php
require_once '../config.php';

// Ambil semua kategori
$categories = $conn->query("SELECT * FROM categories ORDER BY name")->fetchAll();

// Ambil kategori yang dipilih (jika ada)
$selected_category = isset($_GET['category']) ? intval($_GET['category']) : 0;

// Query produk dengan filter kategori jika dipilih
$sql = "SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id";
if ($selected_category) {
    $sql .= " WHERE p.category_id = ?";
}
$sql .= " ORDER BY p.created_at DESC";
$stmt = $conn->prepare($sql);
if ($selected_category) {
    $stmt->execute([$selected_category]);
} else {
    $stmt->execute();
}
$products = $stmt->fetchAll();
?>
<style>
    .dashboard-content h3 {
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 20px;
        color: #1a4fa3;
        letter-spacing: 1px;
    }

    .products-table {
        background: #f4f8fd;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(26,79,163,0.06);
        padding: 20px;
        overflow-x: auto;
    }

    .products-table table {
        width: 100%;
        border-collapse: collapse;
        font-size: 1rem;
    }

    .products-table th, .products-table td {
        padding: 12px 10px;
        text-align: left;
        border-bottom: 1px solid #d0e2fa;
    }

    .products-table th {
        background: #e3efff;
        color: #1a4fa3;
        font-weight: 600;
    }

    .products-table tr:hover {
        background: #d0e2fa;
    }

    .product-thumbnail {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid #b3d1f7;
        background: #e3efff;
    }

    .no-image {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #e3efff;
        color: #1a4fa3;
        border-radius: 6px;
        font-size: 0.9rem;
        border: 1px solid #b3d1f7;
    }
</style>
<title>All Products - Admin</title>
<link rel="stylesheet" href="../assets/css/admin.css">
<div class="admin-container">
    <?php include '../includes/admin-sidebar.php'; ?>
    <div class="main-content">
        <?php include '../includes/admin-topbar.php'; ?>
        <div class="dashboard-content">
            <h3>All Products</h3>
            <form method="get" style="margin-bottom:16px;">
                <label for="category">Filter by Category:</label>
                <select name="category" id="category" onchange="this.form.submit()">
                    <option value="0">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= $selected_category == $cat['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
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
                            <td>$<?= number_format($product['price'], 2) ?></td>
                            <td><?= $product['stock'] ?></td>
                            <td><?= htmlspecialchars(ucfirst($product['category_name'])) ?></td>
                            <td>
                                <a href="produk/edit.php?id=<?= $product['id'] ?>" class="action-btn edit-btn">Edit</a>
                                <a href="produk/delete.php?id=<?= $product['id'] ?>&redirect=../products.php<?= $selected_category ? '?category=' . $selected_category : '' ?>" class="action-btn delete-btn" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>
