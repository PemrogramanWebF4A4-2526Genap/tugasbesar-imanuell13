<?php

require_once __DIR__ . '/../../config/database.php';
require_role('admin');

$total_buyer = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM users WHERE role='buyer'"));
$total_seller = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM users WHERE role='seller'"));

$produk_terlaris = mysqli_query($conn, "
    SELECT 
        p.name,
        COALESCE(SUM(oi.quantity), 0) AS total_terjual
    FROM products p
    LEFT JOIN order_items oi ON p.id = oi.product_id
    GROUP BY p.id, p.name
    ORDER BY total_terjual DESC
    LIMIT 5
");

$kategori_terlaris = mysqli_query($conn, "
    SELECT 
        c.name,
        COALESCE(SUM(oi.quantity), 0) AS total_terjual
    FROM categories c
    LEFT JOIN products p ON c.id = p.category_id
    LEFT JOIN order_items oi ON p.id = oi.product_id
    WHERE c.name IS NOT NULL AND c.name <> ''
    GROUP BY c.id, c.name
    ORDER BY total_terjual DESC
    LIMIT 5
");

?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Digital Zone</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/UAS_INFO2425_202410715013_IMANUEL/src/assets/css/style.css">
</head>
<body>

<?php include __DIR__ . '/../public/navbar.php'; ?>

<div class="container py-4">
    <h2 class="fw-bold mb-4">Dashboard Admin</h2>

    <div class="row g-3">
        <div class="col-md-3">
            <div class="dashboard-box">
                <h6 class="fw-semibold text-secondary mb-2">User</h6>
                <h2 class="fw-bold mb-0">
                    <?= mysqli_num_rows(mysqli_query($conn, "SELECT id FROM users")) ?>
                </h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="dashboard-box">
                <h6 class="fw-semibold text-secondary mb-2">Produk</h6>
                <h2 class="fw-bold mb-0">
                    <?= mysqli_num_rows(mysqli_query($conn, "SELECT id FROM products")) ?>
                </h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="dashboard-box">
                <h6 class="fw-semibold text-secondary mb-2">Pesanan</h6>
                <h2 class="fw-bold mb-0">
                    <?= mysqli_num_rows(mysqli_query($conn, "SELECT id FROM orders")) ?>
                </h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="dashboard-box">
                <h6 class="fw-semibold text-secondary mb-2">Omzet</h6>
                <h2 class="fw-bold mb-0">
                    <?= rupiah(mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(SUM(total_amount),0) t FROM orders"))['t']) ?>
                </h2>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-1">
        <div class="col-md-6">
            <div class="dashboard-box">
                <h6 class="fw-semibold text-secondary mb-2">Total Buyer</h6>
                <h2 class="fw-bold mb-0"><?= $total_buyer ?></h2>
            </div>
        </div>

        <div class="col-md-6">
            <div class="dashboard-box">
                <h6 class="fw-semibold text-secondary mb-2">Total Seller</h6>
                <h2 class="fw-bold mb-0"><?= $total_seller ?></h2>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <a class="btn btn-primary" href="users.php">Manage User</a>
        <a class="btn btn-secondary" href="products.php">Kategori & Produk</a>
        <a class="btn btn-success" href="orders.php">Pesanan</a>
        <a class="btn btn-warning" href="settings.php">System Settings</a>
    </div>

    <div class="row g-3 mt-4">
        <div class="col-md-6">
            <div class="card p-3">
                <h5 class="fw-bold mb-3">Produk Terlaris</h5>

                <table class="table">
                    <tr>
                        <th>Produk</th>
                        <th>Terjual</th>
                    </tr>

                    <?php while ($p = mysqli_fetch_assoc($produk_terlaris)): ?>
                        <tr>
                            <td><?= htmlspecialchars($p['name']) ?></td>
                            <td><?= $p['total_terjual'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card p-3">
                <h5 class="fw-bold mb-3">Kategori Terlaris</h5>

                <table class="table">
                    <tr>
                        <th>Kategori</th>
                        <th>Terjual</th>
                    </tr>

                    <?php while ($k = mysqli_fetch_assoc($kategori_terlaris)): ?>
                        <tr>
                            <td><?= htmlspecialchars($k['name']) ?></td>
                            <td><?= $k['total_terjual'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../public/footer.php'; ?>