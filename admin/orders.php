<?php
require_once '../includes/auth.php';
require_once '../config.php';

// Hanya admin yang boleh akses
if ($_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Filter by status
$status_filter = $_GET['status'] ?? 'all';

$where = [];
$params = [];

if ($status_filter !== 'all') {
    $where[] = "o.status = ?";
    $params[] = $status_filter;
}

$where_sql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

// Ambil semua pesanan
$stmt = $conn->prepare("
    SELECT o.*, u.name AS user_name 
    FROM orders o 
    LEFT JOIN users u ON o.user_id = u.id 
    $where_sql 
    ORDER BY o.created_at DESC
");
$stmt->execute($params);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle View Order
$view_order = null;
if (isset($_GET['view'])) {
    $view_id = intval($_GET['view']);
    $stmt = $conn->prepare("SELECT o.*, u.name AS user_name FROM orders o LEFT JOIN users u ON o.user_id = u.id WHERE o.id = ?");
    $stmt->execute([$view_id]);
    $view_order = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Handle Delete Order
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $conn->beginTransaction();
    try {
        $conn->exec("DELETE FROM order_items WHERE order_id = $delete_id");
        $conn->exec("DELETE FROM orders WHERE id = $delete_id");
        $conn->commit();
        header('Location: orders.php?status=' . $status_filter);
        exit;
    } catch (PDOException $e) {
        $conn->rollBack();
        $error = "Gagal menghapus pesanan: " . $e->getMessage();
    }
}

// Handle Update Status
$update_modal = false;
$order_to_edit = null;

if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->execute([$edit_id]);
    $order_to_edit = $stmt->fetch(PDO::FETCH_ASSOC);
    $update_modal = true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $order_id = intval($_POST['order_id']);
    $new_status = $_POST['new_status'];

    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$new_status, $order_id]);

    header("Location: orders.php?view=$order_id&status=" . $status_filter);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Orders - Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">

    <style>
        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        th, td {
            padding: 14px 16px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
            vertical-align: middle;
        }

        th {
            background-color: #f8f9fc;
            color: #4e73df;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 13px;
        }

        tr:hover {
            background-color: #f8f9fc;
        }

        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            color: white;
        }

        .badge-pending { background: #e74a3b; }
        .badge-processing { background: #f6c23e; color: #222; }
        .badge-shipped { background: #1cc88a; }
        .badge-completed { background: #36b9cc; }
        .badge-cancelled { background: #888; }

        .action-btn {
            display: inline-block;
            margin: 2px 4px;
            padding: 6px 10px;
            border-radius: 5px;
            font-size: 12px;
            cursor: pointer;
            color: white;
            border: none;
            text-decoration: none;
            transition: background 0.2s ease-in-out;
        }

        .btn-view { background: #36b9cc; }
        .btn-edit { background: #f6c23e; color: #222; }
        .btn-delete { background: #e74a3b; }

        .action-btn:hover {
            opacity: 0.85;
        }

        /* Modal Overlay */
        .overlay,
        .modal {
            display: <?= isset($_GET['view']) || isset($_GET['edit']) ? 'block' : 'none' ?>;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 1000;
        }

        .overlay {
            background-color: rgba(0,0,0,0.5);
        }

        .modal {
            background-color: #fff;
            width: 500px;
            max-width: 90%;
            padding: 24px;
            border-radius: 10px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.15);
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            z-index: 1001;
            position: fixed;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid #eee;
        }

        .modal-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        .close-modal {
            font-size: 20px;
            font-weight: bold;
            color: #aaa;
            cursor: pointer;
        }

        .close-modal:hover {
            color: #333;
        }

        .modal-body {
            margin-top: 16px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 6px;
            color: #444;
        }

        select, input[type="text"] {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.2s ease;
        }

        select:focus,
        input[type="text"]:focus {
            border-color: #4e73df;
            outline: none;
            box-shadow: 0 0 0 2px rgba(78, 115, 223, 0.25);
        }

        .modal-footer {
            margin-top: 20px;
            text-align: right;
        }

        .btn-save {
            background: #4e73df;
            color: white;
            padding: 10px 16px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-save:hover {
            background: #2e59d9;
        }

        .product-thumbnail {
            width: 48px;
            height: 48px;
            object-fit: cover;
            border-radius: 6px;
            margin-right: 10px;
            border: 1px solid #ddd;
        }

        .order-products-table {
            width: 100%;
            margin-top: 12px;
            border-top: 1px solid #eee;
        }

        .order-products-table td,
        .order-products-table th {
            padding: 8px 12px;
            font-size: 13px;
            border-bottom: 1px solid #eee;
        }

        .order-products-table img {
            width: 32px;
            height: 32px;
            border-radius: 4px;
            margin-right: 8px;
        }

        .summary-row {
            font-weight: bold;
            background-color: #f1f3f7;
        }

        @media (max-width: 600px) {
            .modal {
                width: 95%;
                padding: 20px;
            }
            .product-thumbnail {
                width: 32px;
                height: 32px;
            }

        }
/* Style untuk filter bar */
.filter-bar {
    margin-bottom: 20px;
    background-color: #f8f9fc;
    padding: 16px 20px;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    max-width: 600px; /* Kotak tidak terlalu lebar */
    width: 100%;
    margin-left: auto;
    margin-right: auto;
}

.filter-bar form {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    align-items: center;
    justify-content: space-between;
}

.filter-bar select,
.filter-bar button {
    font-size: 14px;
    padding: 8px 12px;
    border-radius: 5px;
    border: 1px solid #d1d3e2;
    background-color: white;
    min-width: 120px;
    transition: all 0.2s ease-in-out;
}

.filter-bar select:focus,
.filter-bar button:focus {
    outline: none;
    border-color: #4e73df;
    box-shadow: 0 0 0 2px rgba(78, 115, 223, 0.25);
}

.filter-bar input[type="text"] {
    flex: 1 1 200px;
    min-width: 200px;
}

.filter-bar button {
    background-color: #4e73df;
    color: white;
    cursor: pointer;
    white-space: nowrap;
}

.filter-bar button:hover {
    background-color: #2e59d9;
}
    </style>
</head>
<body>
<div class="admin-container">
    <?php include '../includes/admin-sidebar.php'; ?>
    <div class="main-content">
        <?php include '../includes/admin-topbar.php'; ?>
        <div class="dashboard-content manage-products-content">
            <h2>Daftar Pesanan</h2>

            <!-- Filter Status -->
            <div style="margin-bottom: 20px;">
                <form method="get">
                    <select name="status" onchange="this.form.submit()">
                        <option value="all" <?= $status_filter === 'all' ? 'selected' : '' ?>>Semua</option>
                        <option value="pending" <?= $status_filter === 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="processing" <?= $status_filter === 'processing' ? 'selected' : '' ?>>Processing</option>
                        <option value="shipped" <?= $status_filter === 'shipped' ? 'selected' : '' ?>>Dikirim</option>
                        <option value="completed" <?= $status_filter === 'completed' ? 'selected' : '' ?>>Selesai</option>
                        <option value="cancelled" <?= $status_filter === 'cancelled' ? 'selected' : '' ?>>Dibatalkan</option>
                    </select>
                    <button type="submit">Filter</button>
                </form>
            </div>

            <!-- Table Orders -->
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>No. Pesanan</th>
                        <th>Nama Pengguna</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?= $order['id'] ?></td>
                            <td><?= htmlspecialchars($order['order_number']) ?></td>
                            <td><?= htmlspecialchars($order['user_name'] ?: 'Guest') ?></td>
                            <td>$<?= number_format($order['total_price'], 2) ?></td>
                            <td>
                                <?php
                                    // Ganti match() dengan switch-case untuk kompatibilitas PHP < 8
                                    $status_class = 'badge-pending';
                                    switch ($order['status']) {
                                        case 'pending':
                                            $status_class = 'badge-pending';
                                            break;
                                        case 'processing':
                                            $status_class = 'badge-processing';
                                            break;
                                        case 'shipped':
                                            $status_class = 'badge-shipped';
                                            break;
                                        case 'completed':
                                            $status_class = 'badge-completed';
                                            break;
                                        case 'cancelled':
                                            $status_class = 'badge-cancelled';
                                            break;
                                    }
                                ?>
                                <span class="badge <?= $status_class ?>"><?= ucfirst($order['status']) ?></span>
                            </td>
                            <td><?= date('d M Y, H:i', strtotime($order['created_at'])) ?></td>
                            <td>
                                <a href="?view=<?= $order['id'] ?>&status=<?= $status_filter ?>" class="action-btn btn-view">Lihat</a>
                                <a href="?edit=<?= $order['id'] ?>&status=<?= $status_filter ?>" class="action-btn btn-edit">Edit</a>
                                <a href="?delete=<?= $order['id'] ?>&status=<?= $status_filter ?>" onclick="return confirm('Yakin hapus?')" class="action-btn btn-delete">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <?php if (count($orders) === 0): ?>
                        <tr>
                            <td colspan="7" style="text-align:center;color:#aaa;">Tidak ada pesanan ditemukan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal View Order -->
<?php if ($view_order): ?>
    <div class="overlay" onclick="window.location.href='orders.php?status=<?= $status_filter ?>'"></div>
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title">Detail Pesanan #<?= htmlspecialchars($view_order['order_number']) ?></div>
            <span class="close-modal" onclick="window.location.href='orders.php?status=<?= $status_filter ?>'">&times;</span>
        </div>
        <div class="modal-body">
            <p><strong>Nama:</strong> <?= htmlspecialchars($view_order['user_name'] ?: 'Guest') ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($view_order['email'] ?? '-') ?></p>
            <p><strong>Alamat:</strong><br><?= nl2br(htmlspecialchars($view_order['shipping_address'])) ?></p>
            <p><strong>Total:</strong> $<?= number_format($view_order['total_price'], 2) ?></p>
            <p><strong>Status:</strong>
                <span class="<?= $status_class ?> badge"><?= ucfirst($view_order['status']) ?></span>
            </p>
            <p><strong>Metode Bayar:</strong> <?= ucfirst(htmlspecialchars($view_order['payment_method'])) ?></p>
            <p><strong>Dibuat pada:</strong> <?= date('d M Y, H:i', strtotime($view_order['created_at'])) ?></p>

            <h3>Produk yang Dipesan</h3>
            <table class="order-products-table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Jumlah</th>
                        <th>Harga</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Ambil item pesanan
                    $stmt_items = $conn->prepare("SELECT oi.*, p.name, p.image FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
                    $stmt_items->execute([$view_order['id']]);
                    while ($item = $stmt_items->fetch()):
                    ?>
                        <tr>
                            <td>
                                <img src="../assets/img/products/<?= htmlspecialchars($item['image'] ?? 'default.jpg') ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="product-thumbnail">
                                <?= htmlspecialchars($item['name']) ?>
                            </td>
                            <td><?= $item['quantity'] ?></td>
                            <td>$<?= number_format($item['price'], 2) ?></td>
                            <td>$<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                        </tr>
                    <?php endwhile; ?>
                    <tr class="summary-row">
                        <td colspan="3" style="text-align:right;">Total:</td>
                        <td>$<?= number_format($view_order['total_price'], 2) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<!-- Modal Edit Status -->
<?php if ($update_modal && $order_to_edit): ?>
    <div class="overlay" onclick="window.location.href='orders.php?status=<?= $status_filter ?>'"></div>
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title">Ubah Status Pesanan #<?= $order_to_edit['order_number'] ?></div>
            <span class="close-modal" onclick="window.location.href='orders.php?status=<?= $status_filter ?>'">&times;</span>
        </div>
        <div class="modal-body">
            <form method="post">
                <input type="hidden" name="order_id" value="<?= $order_to_edit['id'] ?>">
                <div class="form-group">
                    <label for="new_status">Pilih Status Baru</label>
                    <select name="new_status" id="new_status" required>
                        <option value="pending" <?= $order_to_edit['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="processing" <?= $order_to_edit['status'] == 'processing' ? 'selected' : '' ?>>Processing</option>
                        <option value="shipped" <?= $order_to_edit['status'] == 'shipped' ? 'selected' : '' ?>>Dikirim</option>
                        <option value="completed" <?= $order_to_edit['status'] == 'completed' ? 'selected' : '' ?>>Selesai</option>
                        <option value="cancelled" <?= $order_to_edit['status'] == 'cancelled' ? 'selected' : '' ?>>Dibatalkan</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="update_status" class="btn-save">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>

</body>
</html>