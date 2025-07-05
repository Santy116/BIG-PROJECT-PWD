<?php
require_once '../../config.php';

$id = intval($_GET['id'] ?? 0);
if (!$id) {
    header('Location: ../manage-products.php');
    exit;
}

$stmt = $conn->prepare("SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    echo "<p>Product not found.</p>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Product - Admin</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
    <style>
    .product-view-container {
        max-width: 600px;
        margin: 40px auto;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        padding: 32px 28px;
    }
    .product-view-container h2 {
        margin-top: 0;
        color: #1a4fa3;
    }
    .product-view-container .product-image {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #eee;
        background: #fafafa;
        margin-bottom: 18px;
    }
    .product-view-container .no-image {
        width: 120px;
        height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f6c23e;
        color: #fff;
        border-radius: 8px;
        font-size: 1.1em;
        border: 1px solid #eee;
        margin-bottom: 18px;
    }
    .product-view-container .field-label {
        font-weight: 600;
        color: #444;
        width: 120px;
        display: inline-block;
    }
    .product-view-container .back-link {
        display: inline-block;
        margin-top: 24px;
        color: #2563eb;
        text-decoration: none;
        font-weight: 600;
    }
    .product-view-container .back-link:hover {
        text-decoration: underline;
    }
    </style>
</head>
<body>
<?php include '../../includes/admin-sidebar.php'; ?>
<?php $page_title = "Manage Products"; ?>
<?php include '../../includes/admin-topbar.php'; ?>
<div class="product-view-container">
    <h2>Product Details</h2>
    <?php if ($product['image']): ?>
        <img src="../../assets/img/products/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-image">
    <?php else: ?>
        <div class="no-image">No Image</div>
    <?php endif; ?>
    <div><span class="field-label">Name:</span> <?= htmlspecialchars($product['name']) ?></div>
    <div><span class="field-label">Description:</span> <?= nl2br(htmlspecialchars($product['description'])) ?></div>
    <div><span class="field-label">Price:</span> $<?= number_format($product['price'], 2) ?></div>
    <div><span class="field-label">Stock:</span> <?= $product['stock'] ?></div>
    <div><span class="field-label">Discount:</span> <?= $product['discount'] ?>%</div>
    <div><span class="field-label">Status:</span>
        <?php if (isset($product['active']) && $product['active']): ?>
            <span style="color: #27ae60;">Active</span>
        <?php else: ?>
            <span style="color: #e74a3b;">Inactive</span>
        <?php endif; ?>
    </div>
    <div><span class="field-label">Category:</span> <?= htmlspecialchars($product['category_name']) ?></div>
    <a href="../manage-products.php" class="back-link">&larr; Back to Products</a>
</div>
</body>
</html>