<?php
// session_start();
// if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
//     header('Location: /PWDTUBES_WalBayExpress/login.php');
//     exit;
// }
require_once '../../includes/auth.php';
require_once '../../config.php';

if (!isset($_GET['id'])) {
    header('Location: ../dashboard.php');
    exit();
}

$product_id = $_GET['id'];
$error = '';
$success = '';

// Ambil data kategori
$category_list = $conn->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);

// Get product data
$stmt = $conn->prepare("SELECT * FROM products WHERE id = :id");
$stmt->execute([':id' => $product_id]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: ../dashboard.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $category_id = trim($_POST['category_id']);
    $stock = trim($_POST['stock']);
    $current_image = $product['image'];

    // Validasi
    if (empty($name) || empty($price) || empty($category_id) || empty($stock)) {
        $error = 'Please fill in all required fields';
    } elseif (!is_numeric($price) || $price <= 0) {
        $error = 'Price must be a positive number';
    } elseif (!is_numeric($stock) || $stock < 0) {
        $error = 'Stock must be a non-negative number';
    } else {
        // Handle file upload
        $image = $current_image;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../../assets/img/products/';
            $file_ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($file_ext, $allowed_ext)) {
                // Delete old image if exists
                if ($image && file_exists($upload_dir . $image)) {
                    unlink($upload_dir . $image);
                }
                $image = uniqid('product_', true) . '.' . $file_ext;
                move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $image);
            } else {
                $error = 'Invalid file type. Only JPG, JPEG, PNG, GIF are allowed.';
            }
        }
        // Remove image if requested
        if (!$error && isset($_POST['remove_image']) && $image) {
            $upload_dir = '../../assets/img/products/';
            if (file_exists($upload_dir . $image)) {
                unlink($upload_dir . $image);
            }
            $image = null;
        }

        if (!$error) {
            try {
                $stmt = $conn->prepare("UPDATE products SET 
                                      name = :name, 
                                      description = :description, 
                                      price = :price, 
                                      category_id = :category_id, 
                                      image = :image, 
                                      stock = :stock,
                                      updated_at = NOW()
                                      WHERE id = :id");
                $stmt->execute([
                    ':name' => $name,
                    ':description' => $description,
                    ':price' => $price,
                    ':category_id' => $category_id,
                    ':image' => $image,
                    ':stock' => $stock,
                    ':id' => $product_id
                ]);

                $success = 'Product updated successfully!';
                // Refresh product data
                $stmt = $conn->prepare("SELECT * FROM products WHERE id = :id");
                $stmt->execute([':id' => $product_id]);
                $product = $stmt->fetch();
            } catch (PDOException $e) {
                $error = 'Database error: ' . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - WalBayExpress_CRUD</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <?php include '../../includes/admin-sidebar.php'; ?>
        
        <!-- Main Content -->
        <div class="main-content">
            <!-- Topbar -->
            <?php include '../../includes/admin-topbar.php'; ?>
            
            <!-- Edit Product Content -->
            <div class="dashboard-content">
                <div class="form-container">
                    <h2>Edit Product</h2>
                    
                    <?php if ($error): ?>
                        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                    <?php endif; ?>
                    
                    <form action="edit.php?id=<?= $product_id ?>" method="post" enctype="multipart/form-data" class="product-form">
                        <div class="form-group">
                            <label for="name">Product Name <span class="required">*</span></label>
                            <input type="text" id="name" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" rows="4"><?= htmlspecialchars($product['description']) ?></textarea>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="price">Price <span class="required">*</span></label>
                                <input type="number" id="price" name="price" step="0.01" min="0" value="<?= htmlspecialchars($product['price']) ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="stock">Stock <span class="required">*</span></label>
                                <input type="number" id="stock" name="stock" min="0" value="<?= htmlspecialchars($product['stock']) ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="category_id">Category <span class="required">*</span></label>
                                <select id="category_id" name="category_id" required>
                                    <option value="">Select Category</option>
                                    <?php foreach ($category_list as $cat): ?>
                                        <option value="<?= $cat['id'] ?>" <?= ($product['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars(ucfirst($cat['name'])) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="image">Product Image</label>
                            <?php if ($product['image']): ?>
                                <div class="current-image">
                                    <img src="../../assets/img/products/<?= htmlspecialchars($product['image']) ?>" alt="Current Image" class="image-preview">
                                    <label>
                                        <input type="checkbox" name="remove_image" value="1"> Remove image
                                    </label>
                                </div>
                            <?php endif; ?>
                            <input type="file" id="image" name="image" accept="image/*">
                            <small>Max size: 2MB (JPG, PNG, GIF)</small>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="save-btn">Update Product</button>
                            <a href="../dashboard.php" class="cancel-btn">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="../../assets/js/admin.js"></script>
</body>
</html>