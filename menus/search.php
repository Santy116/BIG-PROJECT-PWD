<?php
require_once '../config.php';

$search_query = $_GET['q'] ?? '';

if (empty($search_query)) {
    header('Location: index.php');
    exit;
}

require_once '../includes/header.php';

// Perbaiki query: bind parameter berbeda untuk setiap LIKE
$sql = "SELECT p.*, c.name AS category_name
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE p.name LIKE :query1
        OR p.description LIKE :query2
        OR c.name LIKE :query3";
        
$stmt = $conn->prepare($sql);
$stmt->execute([
    ':query1' => "%$search_query%",
    ':query2' => "%$search_query%",
    ':query3' => "%$search_query%"
]);
$products = $stmt->fetchAll();
?>
<link rel="stylesheet" href="/PWDTUBES_WalBayExpress/assets/css/main.css">
<link rel="stylesheet" href="/PWDTUBES_WalBayExpress/assets/css/menu.css">
<style>
    
.watch-btn {
    background: #f7faff;
    border: 1px solid #ddd;
    border-radius: 6px;
    padding: 0.5em;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.2s, border 0.2s, transform 0.2s;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

.watch-btn:hover {
    background: #e6f0ff;
    border-color: #2176ff;
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(33, 118, 255, 0.2);
}

.watch-icon {
    width: 10px;
    height: 20px;
    object-fit: contain;
    transition: transform 0.2s;
}

.watch-btn:hover .watch-icon {
    transform: rotate(10deg);
}

.product-card img {
    width: 100%;
    height: auto; /* Mengatur tinggi gambar secara otomatis berdasarkan lebar */
    aspect-ratio: 1 / 1; /* Menjaga gambar tetap berbentuk persegi */
    object-fit: cover; /* Memastikan gambar memenuhi area tanpa distorsi */
    border-bottom: 1px solid #f0f0f0;
    transition: filter 0.18s;
}
</style>
<div class="container">
    <h1>Search Results for "<?= htmlspecialchars($search_query) ?>"</h1>
    
    <div class="product-grid">
        <?php if (count($products) > 0): ?>
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <div class="product-image-container">
                        <a href="#" class="product-image-link">
                            <img src="/PWDTUBES_WalBayExpress/assets/img/products/<?= htmlspecialchars($product['image'] ?? 'placeholder.jpg') ?>" 
                                 alt="<?= htmlspecialchars($product['name']) ?>" 
                                 class="product-image">
                        </a>
                        <div class="product-badges">
                            <span class="free-shipping-badge">Free Shipping</span>
                            <?php if ($product['price'] < 50): ?>
                                <span class="deal-badge">Hot Deal</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title">
                            <a href="#"><?= htmlspecialchars($product['name']) ?></a>
                        </h3>
                        <div class="product-price">
                            $<?= number_format($product['price'], 2) ?>
                            <?php if ($product['price'] > 100): ?>
                                <span class="original-price">$<?= number_format($product['price'] * 1.2, 2) ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="product-shipping">
                            <span class="shipping-text">Free Shipping</span>
                            <?php if ($product['stock'] > 0): ?>
                                <span class="stock-available">In Stock</span>
                            <?php else: ?>
                                <span class="stock-out">Out of Stock</span>
                            <?php endif; ?>
                        </div>
                        <div class="product-actions">
                            <form action="/PWDTUBES_WalBayExpress/menus/keranjang.php" method="post" style="flex:1;">
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" name="add_to_cart" class="add-to-cart-btn">Add to Cart</button>
                            </form>
                            <button type="button" 
                                class="watch-btn <?= !empty($product['wishlist']) ? 'active' : '' ?>" 
                                data-product-id="<?= $product['id'] ?>">
                                <img src="/PWDTUBES_WalBayExpress/assets/img/logo/e-commerce (1).png" alt="Watch" class="watch-icon" style="width:25px;height:25px;">
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-results">
                <img src="/PWDTUBES_WalBayExpress/assets/img/logo/page-not-found.png" alt="No results found">
                <h3>No products found</h3>
                <p>Try adjusting your search or filter to find what you're looking for.</p>
                <a href="?" class="clear-filters-btn">Clear all filters</a>
            </div>
        <?php endif; ?>
    </div>
</div>
<script src="/PWDTUBES_WalBayExpress/assets/js/wishlist.js"></script>
<?php require_once '../includes/footer.php'; ?>