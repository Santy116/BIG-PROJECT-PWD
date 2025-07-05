<?php
session_start();
require_once '../config.php';

// Handler AJAX (wishlist toggle)
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_SERVER['CONTENT_TYPE']) &&
    strpos($_SERVER['CONTENT_TYPE'], 'application/json') === 0
) {
    $input = json_decode(file_get_contents('php://input'), true);
    $product_id = intval($input['product_id'] ?? 0);

    $stmt = $conn->prepare("SELECT * FROM products WHERE id = :id");
    $stmt->execute([':id' => $product_id]);
    $product = $stmt->fetch();

    if ($product) {
        $new_status = !$product['wishlist'];
        $update_stmt = $conn->prepare("UPDATE products SET wishlist = :wishlist WHERE id = :id");
        $update_stmt->execute([':wishlist' => $new_status, ':id' => $product_id]);
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'wishlist' => $new_status]);
        exit;
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Product not found']);
        exit;
    }
}
// Handler form submission (add to cart)
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    (
        !isset($_SERVER['CONTENT_TYPE']) ||
        strpos($_SERVER['CONTENT_TYPE'], 'application/x-www-form-urlencoded') === 0 ||
        strpos($_SERVER['CONTENT_TYPE'], 'multipart/form-data') === 0
    ) &&
    isset($_POST['product_id'])
) {
    $product_id = intval($_POST['product_id'] ?? 0);
    $quantity = intval($_POST['quantity'] ?? 1);

    // Ambil data produk dari database
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();

    if ($product && $quantity > 0) {
        if (isset($_SESSION['cart'][$product_id]) && is_array($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image'],
                'quantity' => $quantity,
                'discount' => $product['discount'],
                'category_id' => $product['category_id']
            ];
        }
    }
    header('Location: /PWDTUBES_WalBayExpress/menus/keranjang.php');
    exit;
}
// Query wishlist SELALU dijalankan sebelum HTML
$stmt = $conn->query("SELECT * FROM products WHERE wishlist = TRUE");
$wishlist_items = $stmt->fetchAll();

require_once '../includes/header.php';
?>

<style>
body {
    background: #f4faff;
    font-family: 'Segoe UI', Arial, sans-serif;
    margin: 0;
    padding: 0;
}
.wishlist-hero {
    background: #e6f0ff;
    color: #205295;
    padding: 1.5rem 1rem 1rem 1rem;
    border-radius: 14px;
    margin: 2rem auto 2rem auto;
    text-align: center;
    box-shadow: 0 2px 10px #b6c6e622;
    max-width: 480px;
    width: 95%;
}
.wishlist-hero h2 {
    font-size: 1.6rem;
    margin-bottom: 0.4rem;
    font-weight: 700;
    color: #205295;
}
.wishlist-hero p {
    font-size: 1rem;
    margin-bottom: 0.5rem;
    color: #3b4a6b;
    opacity: 0.92;
}
.wishlist-hero .wishlist-icon {
    width: 44px;
    height: 44px;
    margin-bottom: 0.8rem;
    filter: drop-shadow(0 2px 6px #e0e7ffcc);
}
.wishlist-table {
    width: 98%;
    max-width: 650px;
    margin: 0 auto 40px auto;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 10px #b6c6e118;
    border: 1.5px solid #dbeafe;
    overflow: hidden;
}
.wishlist-table th, .wishlist-table td {
    border-bottom: 1px solid #e5e7eb;
    padding: 12px 6px;
    text-align: center;
}
.wishlist-table th {
    background: #e6f0ff;
    font-weight: 600;
    color: #205295;
    font-size: 1em;
    border-top: 1px solid #e5e7eb;
}
.wishlist-table td {
    font-size: 0.98em;
    vertical-align: middle;
    color: #374151;
    background: #fff;
}
.wishlist-table tr:last-child td {
    border-bottom: none;
}
.wishlist-table img {
    width: 48px;
    height: 48px;
    object-fit: cover;
    border-radius: 8px;
    box-shadow: 0 1px 4px #b6c6e111;
    background: #f3f4f6;
}
.wishlist-empty {
    text-align: center;
    margin: 2.5rem auto 2rem auto;
    color: #a0aec0;
    max-width: 400px;
}
.wishlist-empty img {
    width: 80px;
    margin-bottom: 1rem;
    opacity: 0.7;
}
.back-to-shop-btn {
    display: block;
    margin: 2rem auto 0 auto;
    background: #2563eb;
    color: #fff;
    padding: 0.6em 1.7em;
    border-radius: 7px;
    font-size: 1em;
    font-weight: 600;
    text-decoration: none;
    box-shadow: 0 2px 8px #a5b4fc22;
    transition: background 0.16s, box-shadow 0.16s, transform 0.11s;
    border: none;
    max-width: 220px;
    text-align: center;
}
.back-to-shop-btn:hover {
    background: #205295;
    box-shadow: 0 4px 12px #a5b4fc33;
    transform: translateY(-2px) scale(1.03);
}
.add-to-cart-btn {
    background: #2563eb;
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 0.4em 1em;
    font-size: 0.98em;
    font-weight: 600;
    cursor: pointer;
    box-shadow: 0 1px 4px #a5b4fc22;
    transition: background 0.16s, box-shadow 0.16s, transform 0.11s;
}
.add-to-cart-btn:hover {
    background: #205295;
    box-shadow: 0 3px 10px #a5b4fc33;
    transform: translateY(-2px) scale(1.03);
}
.wishlist-table td span {
    display: inline-block;
    padding: 0.25em 0.9em;
    border-radius: 5px;
    font-size: 0.95em;
    font-weight: 500;
    background: #e6f0ff;
}
.wishlist-table td span[style*="color:#2ecc40"] {
    background: #e6fbe8;
    color: #27ae60 !important;
}
.wishlist-table td span[style*="color:#e74c3c"] {
    background: #fdeaea;
    color: #e74c3c !important;
}
@media (max-width: 700px) {
    .wishlist-table th, .wishlist-table td { padding: 7px 2px; font-size: 0.93em; }
    .wishlist-hero h2 { font-size: 1.1rem; }
    .wishlist-hero .wishlist-icon { width: 30px; height: 30px; }
    .wishlist-table img { width: 30px; height: 30px; }
    .wishlist-hero, .wishlist-empty { max-width: 98%; }
    .wishlist-table { max-width: 99%; }
}
</style>

<div class="wishlist-hero">
    <img src="/PWDTUBES_WalBayExpress/assets/img/logo/e-commerce (1).png" alt="Wishlist" class="wishlist-icon">
    <h2>Your Wishlist</h2>
    <p>All your favorite products in one place.<br>Ready to shop anytime you want!</p>
</div>

<?php if (count($wishlist_items) > 0): ?>
    <table class="wishlist-table">
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Add to Cart</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($wishlist_items as $item): ?>
            <tr>
                <td>
                    <img src="/PWDTUBES_WalBayExpress/assets/img/products/<?= htmlspecialchars($item['image'] ?? 'placeholder.jpg') ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                </td>
                <td style="font-weight:600;"><?= htmlspecialchars($item['name']) ?></td>
                <td style="color:#2563eb;font-weight:600;">$<?= number_format($item['price'], 2) ?></td>
                <td>
                    <?php if ($item['stock'] > 0): ?>
                        <span style="color:#2ecc40;font-weight:600;">In Stock</span>
                    <?php else: ?>
                        <span style="color:#e74c3c;font-weight:600;">Out of Stock</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($item['stock'] > 0): ?>
                    
                    <form action="" method="post" style="margin:0;">
                        <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="add-to-cart-btn">Add to Cart</button>
                    </form>
                    <?php else: ?>
                        <button class="add-to-cart-btn" style="background:#e5e7eb;color:#aaa;cursor:not-allowed;" disabled>Out of Stock</button>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <div class="wishlist-empty">
        <img src="/PWDTUBES_WalBayExpress/assets/img/no-results.png" alt="No Wishlist">
        <h3>Your wishlist is empty</h3>
        <p>Start adding your favorite products by clicking the <b>Watch</b> button!</p>
    </div>
<?php endif; ?>

<a href="/PWDTUBES_WalBayExpress/index.php" class="back-to-shop-btn">← Back to Shop</a>
<?php require_once '../includes/footer.php';