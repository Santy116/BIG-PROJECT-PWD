<?php
require_once '../config.php';
require_once '../includes/header.php';

// Ambil produk Sports dari database
$query = "SELECT p.*, c.name AS category_name 
          FROM products p 
          JOIN categories c ON p.category_id = c.id 
          WHERE c.name = 'sports' 
          ORDER BY p.created_at DESC";
$stmt = $conn->query($query);
$products = [];
if ($stmt) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $products[] = $row;
    }
}

// Fungsi untuk menandai produk baru (misal: 14 hari terakhir)
function isNewProduct($created_at) {
    $now = new DateTime();
    $created = new DateTime($created_at);
    $interval = $now->diff($created);
    return $interval->days <= 14;
}
?>
<link rel="stylesheet" href="/PWDTUBES_WalBayExpress/assets/css/kategori.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<style>
/* CSS tambahan jika diperlukan */
.product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 2rem 1.5rem;
    margin: 2.5rem 0;
    justify-items: center;
    align-items: stretch;
    width: 100%;
    max-width: 1200px;
    margin-left: auto;
    margin-right: auto;
}
.product-card {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 4px 18px #2176ff18;
    overflow: hidden;
    transition: box-shadow 0.22s, transform 0.18s;
    position: relative;
    display: flex;
    flex-direction: column;
}
.product-card:hover {
    box-shadow: 0 8px 32px #2176ff33;
    transform: translateY(-4px) scale(1.025);
}
.product-card img {
    width: 100%;
    height: auto;
    aspect-ratio: 1 / 1;
    object-fit: cover;
    border-bottom: 1px solid #f0f0f0;
    transition: filter 0.18s;
}
.product-card:hover img {
    filter: brightness(0.97) saturate(1.08);
}
.product-info {
    padding: 1.1rem;
    flex: 1;
}
.product-meta {
    font-size: 0.97em;
    color: #7a8ca3;
    margin-bottom: 0.5em;
    display: flex;
    gap: 1em;
}
.product-info h2 {
    font-size: 1.18em;
    margin: 0.3em 0 0.7em 0;
    color: #1a2a3a;
    font-weight: 600;
    letter-spacing: 0.01em;
}
.rating {
    color: #f7b731;
    margin-bottom: 0.5em;
    font-size: 1.05em;
}
.price {
    font-weight: bold;
    color: #2176ff;
    font-size: 1.13em;
    margin-top: 0.5em;
    display: block;
    letter-spacing: 0.01em;
}
.stock {
    font-size: 0.99em;
    color: #2ecc40;
    margin-top: 0.3em;
}
.stock.out-stock {
    color: #e74c3c;
}
.product-actions {
    display: flex;
    gap: 0.5em;
    padding: 0.8em 1em 1em 1em;
    align-items: center;
    border-top: 1px solid #f0f0f0;
    background: #f7faff;
}
.btn, .btn-cart {
    background: linear-gradient(90deg, #2176ff 60%, #43a7fd 100%);
    color: #fff;
    border: none;
    border-radius: 7px;
    padding: 0.5em 1.1em;
    cursor: pointer;
    font-size: 1em;
    transition: background 0.18s, box-shadow 0.18s;
    display: flex;
    align-items: center;
    gap: 0.4em;
    box-shadow: 0 1px 4px #2176ff11;
}
.btn:hover, .btn-cart:hover:enabled {
    background: linear-gradient(90deg, #1857b8 60%, #2176ff 100%);
    box-shadow: 0 2px 8px #2176ff22;
}
.btn-cart[disabled] {
    background: #ccc;
    cursor: not-allowed;
}
.wishlist {
    background: none;
    border: none;
    color: #e74c3c;
    font-size: 1.25em;
    cursor: pointer;
    margin-left: auto;
    transition: color 0.18s, transform 0.15s;
}
.wishlist.fas, .wishlist:active {
    color: #c0392b;
    transform: scale(1.15);
}
.badge-new {
    position: absolute;
    top: 14px;
    left: 14px;
    background: linear-gradient(90deg, #f7b731 70%, #ffe066 100%);
    color: #fff;
    font-size: 0.97em;
    padding: 0.28em 0.8em;
    border-radius: 9px;
    font-weight: bold;
    box-shadow: 0 2px 8px #f7b73133;
    z-index: 2;
    display: flex;
    align-items: center;
    gap: 0.3em;
    letter-spacing: 0.01em;
}
.icon-category {
    display: flex;
    justify-content: center;
    margin-bottom: 1.5em;
}
.icon-category img {
    width: 180px !important;
    height: 180px !important;
    object-fit: cover;
    border-radius: 12px; /* Tidak bulat, hanya sedikit rounded */
    box-shadow: 0 4px 16px #2176ff22;
    border: 3px solid #fff;
    background: #f7faff;
    transition: box-shadow 0.18s, transform 0.15s;
}
.icon-category img:hover {
    box-shadow: 0 8px 32px #2176ff33;
    transform: scale(1.08) rotate(-2deg);
}
@media (max-width: 900px) {
    .product-grid {
        grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
        gap: 1.2rem;
    }
}
@media (max-width: 600px) {
    .product-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
        padding: 0 0.5rem;
    }
    .product-card img {
        height: 140px;
    }
    .icon-category img {
        width: 64px !important;
        height: 64px !important;
    }
}

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
<main>
    <div class="icon-category">
        <img src="/PWDTUBES_WalBayExpress/assets/img/category/sports.jpg" alt="Sports" >
    </div>
    <h1>Sports Products</h1>
    <p>Discover a variety of sports products for your active lifestyle!</p>
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
                                <img src="/PWDTUBES_WalBayExpress/assets/img/logo/e-commerce (1).png" alt="Watch" class="watch-icon">
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
</main>
<script>
    // Wishlist toggle (simple, tanpa animasi)
    document.querySelectorAll('.wishlist').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const icon = btn.querySelector('i');
            icon.classList.toggle('fas');
            icon.classList.toggle('fa');
        });
    });
</script>
<script src="/PWDTUBES_WalBayExpress/assets/js/wishlist.js"></script>
<?php require_once '../includes/footer.php'; ?>