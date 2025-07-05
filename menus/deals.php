<?php
require_once '../config.php';
require_once '../includes/header.php';

// Ambil kategori untuk filter
$categories = $conn->query("SELECT id, name FROM categories ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

// Ambil filter dari GET
$category = $_GET['category'] ?? 'all';
$q = trim($_GET['q'] ?? '');
$sort = $_GET['sort'] ?? 'discount';

// Query builder
$sql = "SELECT p.* FROM products p WHERE p.discount >= 30";
$params = [];

if ($category !== 'all' && $category !== '') {
    $sql .= " AND p.category_id = :category_id";
    $params[':category_id'] = $category;
}
if ($q !== '') {
    $sql .= " AND p.name LIKE :q";
    $params[':q'] = "%$q%";
}

switch ($sort) {
    case 'price_asc': $sql .= " ORDER BY (p.price * (1 - p.discount/100)) ASC"; break;
    case 'price_desc': $sql .= " ORDER BY (p.price * (1 - p.discount/100)) DESC"; break;
    case 'newest': $sql .= " ORDER BY p.created_at DESC"; break;
    default: $sql .= " ORDER BY p.discount DESC, p.created_at DESC";
}

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();
?>

<style>
/* ===================== */
/* CONTAINER & TITLE     */
/* ===================== */
.container {
    max-width: 1200px;
    margin: 40px auto 60px auto;
    padding: 28px 16px 40px 16px;
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 8px 32px rgba(60,80,120,0.10), 0 1.5px 4px rgba(60,80,120,0.07);
    min-height: 80vh;
}
.deals-title {
    font-size: 2.3rem;
    font-weight: 800;
    color: #ff5e62;
    text-align: center;
    margin-bottom: 8px;
    letter-spacing: 1px;
}
.deals-subtitle {
    text-align: center;
    color: #444;
    margin-bottom: 32px;
    font-size: 1.13rem;
}

/* ===================== */
/* FILTER BAR            */
/* ===================== */
.deals-filter-bar {
    display: flex;
    flex-wrap: wrap;
    gap: 14px;
    margin: 0 0 32px 0;
    justify-content: center;
    align-items: center;
    background: #f8f9fc;
    border-radius: 14px;
    padding: 18px 12px;
    box-shadow: 0 1px 8px rgba(76,99,255,0.06);
}
.deals-filter-select, .deals-filter-search {
    padding: 10px 18px;
    border-radius: 22px;
    border: 1.5px solid #e3e9f7;
    font-size: 1rem;
    background: #fff;
    color: #333;
    outline: none;
    transition: border 0.18s;
    min-width: 140px;
}
.deals-filter-search {
    min-width: 200px;
}
.deals-filter-select:focus, .deals-filter-search:focus {
    border-color: #4f8cff;
}
.deals-filter-btn {
    background: linear-gradient(90deg, #4f8cff 0%, #6c63ff 100%);
    color: #fff;
    border: none;
    border-radius: 22px;
    padding: 10px 28px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.18s, transform 0.18s;
    box-shadow: 0 2px 8px rgba(76,99,255,0.10);
}
.deals-filter-btn:hover {
    background: linear-gradient(90deg, #6c63ff 0%, #4f8cff 100%);
    transform: scale(1.04);
}

/* ===================== */
/* DEALS GRID & CARD     */
/* ===================== */
.deals-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 32px;
}
.deals-card {
    background: #fff7f7;
    border-radius: 16px;
    box-shadow: 0 2px 16px rgba(255,94,98,0.07), 0 1.5px 4px rgba(60,80,120,0.06);
    padding: 18px 14px 16px 14px;
    display: flex;
    flex-direction: column;
    align-items: center;
    transition: box-shadow 0.18s, transform 0.18s, background 0.18s;
    position: relative;
    min-height: 340px;
}
.deals-card:hover {
    box-shadow: 0 8px 32px rgba(255,94,98,0.16), 0 1.5px 4px rgba(60,80,120,0.09);
    transform: translateY(-4px) scale(1.025);
    background: #fff0f0;
}

/* ===================== */
/* IMAGE & BADGE         */
/* ===================== */
.deals-image {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    margin-bottom: 18px;
    position: relative;
    min-height: 140px;
}
.deals-image img {
    width: 120px;
    height: 120px;
    object-fit: contain;
    border-radius: 10px;
    background: #fff;
    box-shadow: 0 1px 8px rgba(255,94,98,0.08);
    transition: transform 0.18s;
}
.deals-card:hover .deals-image img {
    transform: scale(1.07) rotate(-2deg);
}
.deals-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    background: linear-gradient(90deg, #ff5e62 0%, #ff9966 100%);
    color: #fff;
    font-size: 1rem;
    font-weight: 700;
    padding: 6px 16px;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(255,94,98,0.13);
    letter-spacing: 0.5px;
    z-index: 2;
    border: 2px solid #fff;
}

/* ===================== */
/* INFO & PRICE          */
/* ===================== */
.deals-info h3 {
    font-size: 1.08rem;
    font-weight: 600;
    color: #ff5e62;
    margin-bottom: 8px;
    min-height: 40px;
    text-align: center;
}
.deals-price {
    margin-bottom: 12px;
    font-size: 1.08rem;
    display: flex;
    justify-content: center;
    align-items: baseline;
    gap: 10px;
}
.deals-original {
    color: #b0b0b0;
    text-decoration: line-through;
    font-size: 1rem;
}
.deals-discounted {
    color: #ff5e62;
    font-weight: 700;
    font-size: 1.13rem;
}

/* ===================== */
/* BUTTON                */
/* ===================== */
.deals-btn {
    background: linear-gradient(90deg, #4f8cff 0%, #6c63ff 100%);
    color: #fff;
    border: none;
    border-radius: 22px;
    padding: 10px 32px;
    font-size: 1.08rem;
    font-weight: 600;
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(76,99,255,0.10);
    transition: background 0.18s, transform 0.18s, box-shadow 0.18s;
    letter-spacing: 0.5px;
}
.deals-btn:hover {
    background: linear-gradient(90deg, #6c63ff 0%, #4f8cff 100%);
    transform: scale(1.06);
    box-shadow: 0 6px 24px rgba(76,99,255,0.18);
}

/* ===================== */
/* RESPONSIVE            */
/* ===================== */
@media (max-width: 900px) {
    .container {
        padding: 10px 2vw 18px 2vw;
        border-radius: 10px;
    }
    .deals-grid {
        grid-template-columns: 1fr 1fr;
        gap: 18px;
    }
}
@media (max-width: 700px) {
    .container {
        padding: 8px 1vw 12px 1vw;
        border-radius: 0;
        box-shadow: none;
    }
    .deals-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    .deals-card {
        min-height: 220px;
        padding: 8px 2px 10px 2px;
    }
    .deals-image img {
        width: 80px;
        height: 80px;
    }
    .deals-filter-bar {
        flex-direction: column;
        gap: 10px;
        align-items: stretch;
        padding: 12px 4px;
    }
    .deals-filter-search {
        min-width: 120px;
    }
}
</style>
<div class="container">
    <h1 class="deals-title">🔥 Hot Deals</h1>
    <p class="deals-subtitle">Nikmati penawaran terbaik & diskon terbesar hari ini!</p>
    
    <form class="deals-filter-bar" method="get" autocomplete="off">
        <select name="category" class="deals-filter-select">
            <option value="all" <?= $category === 'all' ? 'selected' : '' ?>>All Categories</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= $category == $cat['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="text" name="q" class="deals-filter-search" placeholder="Search deals..." value="<?= htmlspecialchars($q) ?>">
        <select name="sort" class="deals-filter-select">
            <option value="discount" <?= $sort === 'discount' ? 'selected' : '' ?>>Biggest Discount</option>
            <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Newest</option>
            <option value="price_asc" <?= $sort === 'price_asc' ? 'selected' : '' ?>>Lowest Price</option>
            <option value="price_desc" <?= $sort === 'price_desc' ? 'selected' : '' ?>>Highest Price</option>
        </select>
        <button type="submit" class="deals-filter-btn">Filter</button>
    </form>

    <div class="deals-grid">
        <?php foreach ($products as $product): ?>
            <div class="deals-card">
                <div class="deals-image">
                    <img src="../assets/img/products/<?= htmlspecialchars($product['image'] ?? 'default.jpg') ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                    <div class="deals-badge">-<?= $product['discount'] ?>%</div>
                </div>
                <div class="deals-info">
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <div class="deals-price">
                        <span class="deals-original">$<?= number_format($product['price'], 2) ?></span>
                        <span class="deals-discounted">$<?= number_format($product['price'] * (1 - $product['discount']/100), 2) ?></span>
                    </div>
                    <form action="keranjang.php" method="post">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="deals-btn">Add to Cart</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if (empty($products)): ?>
            <div style="grid-column:1/-1;text-align:center;color:#aaa;font-size:1.2rem;padding:40px 0;">
                No hot deals available right now.
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>