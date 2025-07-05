<?php
require_once '../config.php';
require_once '../includes/header.php';

$stmt = $conn->prepare("SELECT * FROM products WHERE discount > 0");
$stmt->execute();
$products = $stmt->fetchAll();
?>

<link rel="stylesheet" href="/PWDTUBES_WalBayExpress/assets/css/menu.css">
<div class="container">
    <h1>Special Promotions</h1>
    
    <div class="promo-banner">
        <img src="/PWDTUBES_WalBayExpress/assets/img/banner/14484194-d8df-4c8b-986c-0c90e161cfb9.jpg" alt="Special Offers">
        <div class="banner-text">
            <h2>Limited Time Offers</h2>
            <p>Save up to 50% on selected items</p>
        </div>
    </div>
    
    <div class="product-grid">
        <?php foreach ($products as $product): ?>
            <div class="product-card">
                <div class="product-image">
                    <img src="../assets/img/products/<?= htmlspecialchars($product['image'] ?? 'default.jpg') ?>" 
                         alt="<?= htmlspecialchars($product['name']) ?>">
                    <div class="discount-badge">-<?= $product['discount'] ?>%</div>
                </div>
                <div class="product-info">
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <div class="price">
                        <span class="original-price">$<?= number_format($product['price'], 2) ?></span>
                        <span class="discounted-price">$<?= number_format($product['price'] * (1 - $product['discount']/100), 2) ?></span>
                    </div>
                    <div class="actions">
                        <form action="keranjang.php" method="post">
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" name="add_to_cart" class="add-to-cart">Add to Cart</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<script src="/PWDTUBES_WalBayExpress/assets/js/wishlist.js"></script>
<?php require_once '../includes/footer.php'; ?>