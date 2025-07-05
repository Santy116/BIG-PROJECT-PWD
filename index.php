<?php
require_once 'config.php';
require_once 'includes/header.php';

$categories = $conn->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);

// Pagination logic
$items_per_page = 12;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($current_page < 1) $current_page = 1;

// Filter logic
$category_filter = isset($_GET['category']) ? $_GET['category'] : 'all';
$search_query = isset($_GET['q']) ? trim($_GET['q']) : '';

// Build SQL query
$sql = "SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE 1=1";
$params = [];
if ($category_filter !== 'all') {
    $sql .= " AND p.category_id = :category_id";
    $params[':category_id'] = $category_filter;
}

if (!empty($search_query)) {
    $sql .= " AND (p.name LIKE :search OR p.description LIKE :search)";
    $params[':search'] = "%$search_query%";
}

// Get total items for pagination
$stmt = $conn->prepare($sql);
$stmt->execute($params);
$total_items = $stmt->rowCount();
$total_pages = ceil($total_items / $items_per_page);

// Adjust current page if out of bounds
if ($current_page > $total_pages && $total_pages > 0) {
    $current_page = $total_pages;
}

// Add pagination to query
$offset = ($current_page - 1) * $items_per_page;
$sql .= " LIMIT $offset, $items_per_page";

// Get products
$stmt = $conn->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();
?>

<!-- Explore Popular Categories -->
<link rel="stylesheet" href="/PWDTUBES_WalBayExpress/assets/css/main.css">
<style>
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

@media (max-width: 450px) {
    .banner-slide img {
        height: 200px; /* Tinggi banner untuk layar kecil */
        border-radius: 10px;
    }
    .scrollable-banner {
        border-radius: 10px;
    }
}
.scrollable-banner {
    position: relative;
    max-width: 1200px; /* Lebar maksimum banner */
    margin: 20px auto;
    overflow: hidden; /* Sembunyikan bagian yang keluar dari kontainer */
    border-radius: 10px;
    box-shadow: 0 4px 24px rgba(76, 99, 255, 0.08);
    background: #fff;
}

.banner-container {
    display: flex;
    transition: transform 0.5s ease-in-out; /* Animasi transisi antar slide */
    width: 100%; /* Pastikan hanya satu slide yang terlihat */
}

.banner-slide {
    flex: 0 0 100%; /* Setiap slide mengambil 100% lebar kontainer */
    display: flex;
    justify-content: center;
    align-items: center;
    box-sizing: border-box;
}

.banner-slide img {
    width: 100%; /* Gambar memenuhi lebar slide */
    height: auto; /* Tinggi gambar menyesuaikan proporsi */
    object-fit: cover; /* Memastikan gambar tidak terdistorsi */
    border-radius: 10px;
}

.carousel__control {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: linear-gradient(135deg, #4c63ff 60%, #2593b8 100%);
    border: none;
    color: #fff;
    padding: 12px 14px;
    cursor: pointer;
    z-index: 10;
    border-radius: 50%;
    box-shadow: 0 2px 12px rgba(76, 99, 255, 0.18), 0 1.5px 6px rgba(37, 147, 184, 0.10);
    transition: background 0.22s, box-shadow 0.22s, transform 0.18s;
    outline: none;
    opacity: 0.92;
    font-size: 1.15rem;
    display: flex;
    align-items: center;
    justify-content: center;
}
.carousel__control:hover, .carousel__control:focus {
    background: linear-gradient(135deg, #2593b8 60%, #4c63ff 100%);
    box-shadow: 0 4px 18px rgba(76, 99, 255, 0.25), 0 2px 8px rgba(37, 147, 184, 0.13);
    transform: translateY(-50%) scale(1.08);
    opacity: 1;
}

.carousel__control--prev {
    left: 10px;
}

.carousel__control--next {
    right: 10px;
}

.carousel__control svg {
    width: 16px;
    height: 16px;
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
</style>
<div class="scrollable-banner">
    <button class="carousel__control carousel__control--prev" type="button" aria-label="Go to previous banner">
        <img src="/PWDTUBES_WalBayExpress/assets/img/logo/left-arrow (1).png" alt="Previous" style="width:24px;height:24px;">
    </button>
    <div class="banner-container">
        <div class="banner-slide">
            <img src="/PWDTUBES_WalBayExpress/assets/img/banner/5f828400-98fd-4af4-9559-4b3148ed472f.jpg" alt="Banner 1">
        </div>
        <div class="banner-slide">
            <img src="/PWDTUBES_WalBayExpress/assets/img/banner/176dd7ad-2394-496f-9141-426c0aa43669.jpg" alt="Banner 2">
        </div>
        <div class="banner-slide">
            <img src="/PWDTUBES_WalBayExpress/assets/img/banner/14484194-d8df-4c8b-986c-0c90e161cfb9.jpg" alt="Banner 3">
        </div>
    </div>
    <button class="carousel__control carousel__control--next" type="button" aria-label="Go to next banner">
        <img src="/PWDTUBES_WalBayExpress/assets/img/logo/right-arrow (1).png" alt="Next" style="width:24px;height:24px;">
    </button>
    <div class="banner-dots">
        <span class="dot active" data-slide="0"></span>
        <span class="dot" data-slide="1"></span>
        <span class="dot" data-slide="2"></span>
    </div>
</div>

<div class="shopping-easy-banner">
    <div class="shopping-easy-banner-content">
        <h2>Shopping made easy</h2>
        <p>Enjoy reliability, secure deliveries and hassle-free returns.</p>
    </div>
    <a href="/PWDTUBES_WalBayExpress/user/register.php" class="start-now-btn">Start now</a>
</div>
<div class="popular-categories">
    <h2>Explore Popular Categories</h2>
    <div class="scroll-buttons">
        <button id="scroll-left" class="scroll-btn left">&#8592;</button>
        <button id="scroll-right" class="scroll-btn right">&#8594;</button>
    </div>
    <div class="category-tiles">
        <div class="category-tile">
            <img src="/PWDTUBES_WalBayExpress/assets/img/category/electronics.png" alt="Electronics" class="category-image">
            <p class="category-name">Electronics</p>
        </div>
        <div class="category-tile">
            <img src="/PWDTUBES_WalBayExpress/assets/img/category/fashion.png" alt="Fashion" class="category-image">
            <p class="category-name">Fashion</p>
        </div>
        <div class="category-tile">
            <img src="/PWDTUBES_WalBayExpress/assets/img/category/home and garden.png" alt="Home & Garden" class="category-image">
            <p class="category-name">Home & Garden</p>
        </div>
        <div class="category-tile">
            <img src="/PWDTUBES_WalBayExpress/assets/img/category/luxury.png" alt="Luxury" class="category-image">
            <p class="category-name">Luxury</p>
        </div>
        <div class="category-tile">
            <img src="/PWDTUBES_WalBayExpress/assets/img/category/p & s.png" alt="P & S" class="category-image">
            <p class="category-name">P & S</p>
        </div>
        <div class="category-tile">
            <img src="/PWDTUBES_WalBayExpress/assets/img/category/repurbished.png" alt="Refurbished" class="category-image">
            <p class="category-name">Refurbished</p>
        </div>
        <div class="category-tile">
            <img src="/PWDTUBES_WalBayExpress/assets/img/category/sneakers.png" alt="Sneakers" class="category-image">
            <p class="category-name">Sneakers</p>
        </div>
        <div class="category-tile">
            <img src="/PWDTUBES_WalBayExpress/assets/img/category/sports .png" alt="Sports" class="category-image">
            <p class="category-name">Sports</p>
        </div>
        <div class="category-tile">
            <img src="/PWDTUBES_WalBayExpress/assets/img/category/gadgets.png" alt="Gadgets" class="category-image">
            <p class="category-name">Gadgets</p>
        </div>
        <div class="category-tile">
            <img src="/PWDTUBES_WalBayExpress/assets/img/category/bags.png" alt="Pre-loved Luxury" class="category-image">
            <p class="category-name">Pre-loved Luxury</p>
        </div>
        <div class="category-tile">
            <img src="/PWDTUBES_WalBayExpress/assets/img/category/toys.png" alt="Toys" class="category-image">
            <p class="category-name">Toys</p>
        </div>
    </div>
</div>

<div class="ebay-style-container">
    <div class="page-title">
        <h1>Products</h1>
        <div class="results-count">Showing <?= ($offset + 1) ?>-<?= min($offset + $items_per_page, $total_items) ?> of <?= $total_items ?> results</div>
    </div>
    
    <div class="filter-section">
        <div class="filter-dropdown">
            <label for="sort-by">Sort by:</label>
            <select id="sort-by" class="filter-select">
                <option value="default">Best Match</option>
                <option value="price_asc">Price: Low to High</option>
                <option value="price_desc">Price: High to Low</option>
                <option value="newest">Newest First</option>
            </select>
        </div>
        
        <div class="category-tabs">
            <a href="?category=all" class="category-tab <?= $category_filter === 'all' ? 'active' : '' ?>">All</a>
            <?php foreach ($categories as $cat): ?>
                <a href="?category=<?= $cat['id'] ?>" class="category-tab <?= $category_filter == $cat['id'] ? 'active' : '' ?>">
                    <?= htmlspecialchars(ucfirst($cat['name'])) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    
    <div class="product-grid">
        <?php if (count($products) > 0): ?>
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <div class="product-image-container">
                        <a href="#" class="product-image-link">
                            <img src="assets/img/products/<?= htmlspecialchars($product['image'] ?? 'placeholder.jpg') ?>" 
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
                            <form action="menus/keranjang.php" method="post" style="flex:1;">
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" name="add_to_cart" class="add-to-cart-btn">Add to Cart</button>
                            </form>
                            <button type="button" 
                                class="watch-btn <?= $product['wishlist'] ? 'active' : '' ?>" 
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
    
    <?php if ($total_pages > 1): ?>
        <div class="pagination">
            <?php if ($current_page > 1): ?>
                <a href="?<?= http_build_query(array_merge($_GET, ['page' => 1])) ?>" class="page-link first-page">First</a>
                <a href="?<?= http_build_query(array_merge($_GET, ['page' => $current_page - 1])) ?>" class="page-link prev-page">Previous</a>
            <?php endif; ?>
            
            <?php 
            // Show page numbers
            $start_page = max(1, $current_page - 2);
            $end_page = min($total_pages, $current_page + 2);
            
            if ($start_page > 1) {
                echo '<span class="page-dots">...</span>';
            }
            
            for ($i = $start_page; $i <= $end_page; $i++): ?>
                <a href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>" 
                   class="page-link <?= $i === $current_page ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; 
            
            if ($end_page < $total_pages) {
                echo '<span class="page-dots">...</span>';
            }
            ?>
            
            <?php if ($current_page < $total_pages): ?>
                <a href="?<?= http_build_query(array_merge($_GET, ['page' => $current_page + 1])) ?>" class="page-link next-page">Next</a>
                <a href="?<?= http_build_query(array_merge($_GET, ['page' => $total_pages])) ?>" class="page-link last-page">Last</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
<script src="/PWDTUBES_WalBayExpress/assets/js/scroll-index.js"></script>
<script src="/PWDTUBES_WalBayExpress/assets/js/banner.js"></script>
<script src="/PWDTUBES_WalBayExpress/assets/js/wishlist.js"></script>
<?php require_once 'includes/footer.php'; ?>