<?php
require_once '../../includes/auth.php';
require_once '../../config.php';

$error = '';
$success = '';

// Ambil kategori dari database
$category_list = $conn->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $category_id = trim($_POST['category_id']);
    $stock = trim($_POST['stock']);

    // Validasi
    if (empty($name) || empty($price) || empty($category_id) || empty($stock)) {
        $error = 'Please fill in all required fields';
    } elseif (!is_numeric($price) || $price <= 0) {
        $error = 'Price must be a positive number';
    } elseif (!is_numeric($stock) || $stock < 0) {
        $error = 'Stock must be a non-negative number';
    } else {
        // Handle file upload
        $image = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../../assets/img/products/';
            $file_ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($file_ext, $allowed_ext)) {
                $image = uniqid('product_', true) . '.' . $file_ext;
                move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $image);
            } else {
                $error = 'Invalid file type. Only JPG, JPEG, PNG, GIF are allowed.';
            }
        }

        if (!$error) {
            try {
                $stmt = $conn->prepare("INSERT INTO products (name, description, price, category_id, image, stock, created_at, updated_at) 
                                        VALUES (:name, :description, :price, :category_id, :image, :stock, NOW(), NOW())");
                $stmt->execute([
                    ':name' => $name,
                    ':description' => $description,
                    ':price' => $price,
                    ':category_id' => $category_id,
                    ':image' => $image,
                    ':stock' => $stock
                ]);
                $success = 'Product added successfully!';
                // Reset form
                $name = $description = $price = $category_id = $stock = '';
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
    <title>Add Product - WalBayExpress_CRUD</title>
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
            
            <!-- Add Product Content -->
            <div class="dashboard-content">
                <div class="form-container">
                    <h2>Add New Product</h2>
                    
                    <?php if ($error): ?>
                        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                    <?php endif; ?>
                    
                    <form action="create.php" method="post" enctype="multipart/form-data" class="product-form">
                        <div class="form-group">
                            <label for="name">Product Name <span class="required">*</span></label>
                            <input type="text" id="name" name="name" value="<?= isset($name) ? htmlspecialchars($name) : '' ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" rows="4"><?= isset($description) ? htmlspecialchars($description) : '' ?></textarea>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="price">Price <span class="required">*</span></label>
                                <input type="number" id="price" name="price" step="0.01" min="0" value="<?= isset($price) ? htmlspecialchars($price) : '' ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="stock">Stock <span class="required">*</span></label>
                                <input type="number" id="stock" name="stock" min="0" value="<?= isset($stock) ? htmlspecialchars($stock) : '' ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="category_id">Category <span class="required">*</span></label>
                                <select id="category_id" name="category_id" required>
                                    <option value="">Select Category</option>
                                    <?php foreach ($category_list as $cat): ?>
                                        <option value="<?= $cat['id'] ?>" <?= (isset($category_id) && $category_id == $cat['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars(ucfirst($cat['name'])) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="image">Product Image</label>
                            <input type="file" id="image" name="image" accept="image/*">
                            <small>Max size: 2MB (JPG, PNG, GIF)</small>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="save-btn">Save Product</button>
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