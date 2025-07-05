<?php
require_once '../config.php';
require_once '../includes/header.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['cart'])) {
    header('Location: keranjang.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $zip = trim($_POST['zip']);
    $payment_method = $_POST['payment'];

    if (empty($name) || empty($email) || empty($address) || empty($city) || empty($zip)) {
        $error = 'Silakan lengkapi semua field wajib.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email tidak valid.';
    } else {
        // Ambil data produk dari cart
        $ids = array_keys($_SESSION['cart']);
        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        $stmt = $conn->prepare("SELECT id, name, price FROM products WHERE id IN ($placeholders)");
        $stmt->execute($ids);
        $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $total_price = 0;

        foreach ($cart_items as &$item) {
            $qty = is_array($_SESSION['cart'][$item['id']]) && isset($_SESSION['cart'][$item['id']]['quantity']) 
                   ? (int)$_SESSION['cart'][$item['id']]['quantity'] 
                   : 1;

            $price = is_numeric($item['price']) ? (float)$item['price'] : 0.0;
            $item['quantity'] = $qty;
            $item['subtotal'] = $price * $qty;
            $total_price += $item['subtotal'];
        }

        try {
            // Simpan pesanan ke database
            $order_number = 'ORD-' . time() . '-' . rand(1000, 9999);
            $user_id = $_SESSION['user_id'] ?? null;

            $stmt = $conn->prepare("INSERT INTO orders (user_id, order_number, total_price, payment_method, shipping_address, status) VALUES (?, ?, ?, ?, ?, 'pending')");
            $stmt->execute([$user_id, $order_number, $total_price, $payment_method, "$address, $city, ZIP: $zip"]);

            $order_id = $conn->lastInsertId();

            // Simpan item pesanan
            $stmt_item = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");

            foreach ($cart_items as $item) {
                $stmt_item->execute([$order_id, $item['id'], $item['quantity'], $item['price']]);
            }

            // Kosongkan keranjang
            $_SESSION['cart'] = [];

            // Redirect dengan sukses
            $success = "Terima kasih! Pesanan Anda telah diterima. Nomor pesanan: #$order_number";
        } catch (Exception $e) {
            $error = "Gagal menyimpan pesanan: " . $e->getMessage();
        }
    }
}

// Ambil data produk untuk ringkasan
$cart_products = [];
$total = 0;

if (!empty($_SESSION['cart'])) {
    $ids = array_keys($_SESSION['cart']);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    $stmt = $conn->prepare("SELECT id, name, price, image FROM products WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($products as $row) {
        $quantity = 1;
        if (isset($_SESSION['cart'][$row['id']])) {
            if (is_array($_SESSION['cart'][$row['id']])) {
                $quantity = $_SESSION['cart'][$row['id']]['quantity'] ?? 1;
            } else {
                $quantity = is_numeric($_SESSION['cart'][$row['id']]) ? (int)$_SESSION['cart'][$row['id']] : 1;
            }
        }

        $price = is_numeric($row['price']) ? (float)$row['price'] : 0.0;
        $subtotal = $price * $quantity;

        $row['quantity'] = $quantity;
        $row['subtotal'] = $subtotal;
        $cart_products[] = $row;
        $total += $subtotal;
    }
}
?>

<link rel="stylesheet" href="/PWDTUBES_WalBayExpress/assets/css/menu.css">
<style>
    /* CSS Ringkasan & Form Checkout */
    .container { padding: 24px; max-width: 1000px; margin: auto; }
    .checkout-container { display: flex; flex-wrap: wrap; gap: 32px; }
    .checkout-form, .order-summary { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
    .form-group input, .form-group textarea { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 5px; }
    .payment-method { display: flex; align-items: center; margin: 6px 0; }
    .summary-item { display: flex; justify-content: space-between; padding: 8px 0; }
    .summary-total { font-weight: bold; display: flex; justify-content: space-between; margin-top: 16px; }
    .btn { background: #4e73df; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 5px; }
    .btn:hover { background: #2e59d9; }
    .back-btn { background: #858796; text-decoration: none; color: white; padding: 10px 20px; border-radius: 5px; }
    .back-btn:hover { background: #737584; }
</style>

<div class="container">
    <h1>Checkout</h1>

    <?php if ($error): ?>
        <div style="color: red;"><?= $error ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div style="text-align:center;">
            <img src="/PWDTUBES_WalBayExpress/assets/img/check.png" alt="Success" style="width:80px;">
            <h2><?= $success ?></h2>
            <a href="/PWDTUBES_WalBayExpress/index.php" class="btn">Lanjut Belanja</a>
        </div>
    <?php else: ?>
        <div class="checkout-container">
            <!-- Shipping Info -->
            <div class="checkout-form">
                <h2>Alamat Pengiriman</h2>
                <form method="post">
                    <div class="form-group">
                        <label for="name">Nama Lengkap *</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Alamat Pengiriman *</label>
                        <textarea id="address" name="address" rows="3" required></textarea>
                    </div>
                    <div class="form-row" style="display:flex;gap:10px;">
                        <div class="form-group" style="flex:1;">
                            <label for="city">Kota *</label>
                            <input type="text" id="city" name="city" required>
                        </div>
                        <div class="form-group" style="flex:1;">
                            <label for="zip">Kode Pos *</label>
                            <input type="text" id="zip" name="zip" required>
                        </div>
                    </div>

                    <h2>Metode Pembayaran</h2>
                    <label class="payment-method">
                        <input type="radio" name="payment" value="credit_card" checked> Master Card
                    </label>
                    <label class="payment-method">
                        <input type="radio" name="payment" value="paypal"> PayPal
                    </label>
                    <label class="payment-method">
                        <input type="radio" name="payment" value="bank_transfer"> Transfer Bank
                    </label>

                    <div style="margin-top:20px;">
                        <a href="/PWDTUBES_WalBayExpress/menus/keranjang.php" class="back-btn">← Kembali ke Keranjang</a>
                        <button type="submit" class="btn" style="margin-left:10px;">Lanjutkan ke Pembayaran</button>
                    </div>
                </form>
            </div>

            <!-- Order Summary -->
            <div class="order-summary">
                <h2>Ringkasan Pesanan</h2>
                <?php foreach ($cart_products as $product): ?>
                    <div class="summary-item">
                        <span>
                            <img src="../assets/img/products/<?= htmlspecialchars($product['image'] ?? 'default.jpg') ?>" alt="<?= htmlspecialchars($product['name']) ?>" style="width:38px;height:38px;margin-right:10px;">
                            <?= htmlspecialchars($product['name']) ?> x <?= $product['quantity'] ?>
                        </span>
                        <span>$<?= number_format((float)$product['subtotal'], 2) ?></span>
                    </div>
                <?php endforeach; ?>
                <hr>
                <div class="summary-total">
                    <span>Total:</span>
                    <span>$<?= number_format($total, 2) ?></span>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>