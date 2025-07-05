<?php
require_once '../config.php';
require_once '../includes/header.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// --- BERSIHKAN CART LAMA YANG BUKAN ARRAY (HANYA SEKALI, BOLEH DIHAPUS SETELAHNYA) ---
foreach ($_SESSION['cart'] as $key => $item) {
    if (!is_array($item) || !isset($item['quantity'])) {
        unset($_SESSION['cart'][$key]);
    }
}

// Add to cart action
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'] ?? 1;

    // Ambil data produk dari database
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();

    if ($product) {
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
}

// Update cart action
if (isset($_POST['update_cart'])) {
    foreach ($_POST['quantities'] as $product_id => $quantity) {
        if ($quantity > 0 && isset($_SESSION['cart'][$product_id]) && is_array($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] = $quantity;
        } else {
            unset($_SESSION['cart'][$product_id]);
        }
    }
}

// Remove item action
if (isset($_GET['remove'])) {
    $product_id = $_GET['remove'];
    unset($_SESSION['cart'][$product_id]);
}

// Get cart products
$cart_products = [];
$total = 0;

if (!empty($_SESSION['cart'])) {
    $ids = array_keys($_SESSION['cart']);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    $stmt = $conn->prepare("SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id IN ($placeholders)");
    $stmt->execute($ids);
    $db_products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Gabungkan data dari session dan database
    foreach ($db_products as $product) {
        $cart_item = $_SESSION['cart'][$product['id']];
        $quantity = (is_array($cart_item) && isset($cart_item['quantity'])) ? $cart_item['quantity'] : 0;
        $price = $product['price'];
        $discount = $product['discount'];
        $subtotal = $price * $quantity;
        if ($discount > 0) {
            $subtotal = $subtotal * (1 - $discount/100);
        }
        $total += $subtotal;

        $cart_products[] = [
            'id' => $product['id'],
            'name' => $product['name'],
            'image' => $product['image'],
            'category' => $product['category_name'],
            'price' => $price,
            'discount' => $discount,
            'quantity' => $quantity,
            'subtotal' => $subtotal
        ];
    }
}
?>

<link rel="stylesheet" href="/PWDTUBES_WalBayExpress/assets/css/menu.css">
<style>
    .empty-cart {
    text-align: center;
    margin: 50px 0;
}

.empty-cart img {
    width: 150px; /* Atur lebar gambar */
    height: auto; /* Tinggi gambar menyesuaikan proporsi */
    margin-bottom: 20px; /* Tambahkan jarak bawah */
}

.empty-cart p {
    font-size: 18px;
    color: #555;
    margin-bottom: 20px;
}

.empty-cart .btn {
    display: inline-block;
    padding: 10px 20px;
    background-color: #007bff;
    color: #fff;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.empty-cart .btn:hover {
    background-color: #0056b3;
}

.recommended-products {
    margin-top: 50px;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.recommended-products h2 {
    font-size: 24px;
    margin-bottom: 20px;
    color: #333;
    text-align: center;
}

.product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 20px;
}

.product-card {
    background: #fff;
    border: 1px solid #e6e6e6;
    border-radius: 8px;
    overflow: hidden;
    transition: transform 0.3s, box-shadow 0.3s;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.product-image-container {
    position: relative;
    padding-top: 75%; /* Aspect ratio 4:3 */
    overflow: hidden;
}

.product-image {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-info {
    padding: 15px;
    text-align: center;
}

.product-title a {
    font-size: 16px;
    font-weight: bold;
    color: #333;
    text-decoration: none;
    transition: color 0.3s ease;
}

.product-title a:hover {
    color: #007bff;
}

.product-price {
    margin-top: 10px;
    font-size: 18px;
    font-weight: bold;
    color: #007bff;
}

.original-price {
    font-size: 14px;
    color: #999;
    text-decoration: line-through;
    margin-left: 8px;
}

.add-to-cart-btn {
    margin-top: 10px;
    padding: 10px 20px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.add-to-cart-btn:hover {
    background-color: #0056b3;
}
</style>
<div class="container">
    <h1>Your Shopping Cart</h1>
    <?php if (empty($cart_products)): ?>
        <div class="empty-cart">
            <img src="/PWDTUBES_WalBayExpress/assets/img/logo/cart.png" alt="Empty Cart">
            <p>Your cart is empty</p>
            <a href="/PWDTUBES_WalBayExpress/index.php" class="btn">Continue Shopping</a>
        </div>
    <?php else: ?>
        
        <form action="keranjang.php" method="post">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_products as $product): ?>
                        <tr>
                            <td>
                                <div class="cart-product">
                                    <img src="/PWDTUBES_WalBayExpress/assets/img/products/<?= htmlspecialchars($product['image'] ?? 'default.jpg') ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                                    <div>
                                        <h3><?= htmlspecialchars($product['name']) ?></h3>
                                        <p><?= htmlspecialchars($product['category']) ?></p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php if ($product['discount'] > 0): ?>
                                    <span class="original-price">$<?= number_format($product['price'], 2) ?></span>
                                    <span class="discounted-price">$<?= number_format($product['price'] * (1 - $product['discount']/100), 2) ?></span>
                                <?php else: ?>
                                    <span>$<?= number_format($product['price'], 2) ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <input type="number" name="quantities[<?= $product['id'] ?>]" 
                                       value="<?= $product['quantity'] ?>" min="1" class="quantity-input">
                            </td>
                            <td>$<?= number_format($product['subtotal'], 2) ?></td>
                            <td>
                                <a href="keranjang.php?remove=<?= $product['id'] ?>" class="remove-btn">Remove</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <div class="cart-summary">
                <div class="cart-total">
                    <h3>Cart Total: $<?= number_format($total, 2) ?></h3>
                </div>
                <div class="cart-actions">
                    <button type="submit" name="update_cart" class="update-btn">Update Cart</button>
                    <a href="/PWDTUBES_WalBayExpress/menus/checkout.php" class="checkout-btn">Proceed to Checkout</a>
                </div>
            </div>
        </form>
    <?php endif; ?>
</div>
<?php require_once '../includes/footer.php'; ?>