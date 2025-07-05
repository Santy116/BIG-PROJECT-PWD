<?php
require_once '../../includes/auth.php';
require_once '../../config.php';

if (!isset($_GET['id'])) {
    header('Location: ../dashboard.php');
    exit();
}

$product_id = $_GET['id'];

// Get product data to delete image
$stmt = $conn->prepare("SELECT image FROM products WHERE id = :id");
$stmt->execute([':id' => $product_id]);
$product = $stmt->fetch();

if ($product) {
    // Hapus gambar jika ada
    if (!empty($product['image'])) {
        $image_path = '../../assets/img/products/' . $product['image'];
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }
    // Hapus produk dari database
    $stmt = $conn->prepare("DELETE FROM products WHERE id = :id");
    $stmt->execute([':id' => $product_id]);

    $_SESSION['success_message'] = 'Product deleted successfully';
} else {
    $_SESSION['error_message'] = 'Product not found';
}


if (isset($_GET['redirect']) && $_GET['redirect']) {
    $redirect = filter_var($_GET['redirect'], FILTER_SANITIZE_URL);
    header('Location: ' . $redirect);
} else {
    header('Location: ../dashboard.php');
}
exit();
?>