<?php

require_once __DIR__ . '/../../config/database.php';
require_role('seller');

$seller_id = $_SESSION['user']['id'];

$total_produk = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) total 
    FROM products 
    WHERE seller_id = $seller_id
"))['total'];

$total_pesanan = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(DISTINCT o.id) total
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    JOIN products p ON oi.product_id = p.id
    WHERE p.seller_id = $seller_id
"))['total'];

$total_terjual = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COALESCE(SUM(oi.quantity),0) total
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE p.seller_id = $seller_id
"))['total'];

$total_omzet = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COALESCE(SUM(oi.quantity * oi.price),0) total
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE p.seller_id = $seller_id
"))['total'];

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
    <?php require_once __DIR__ . '/../../controllers/ProductController.php'; ?>

    <h2 class="fw-bold mb-4">Dashboard Penjual</h2>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="dashboard-box">
                <h6 class="fw-semibold text-secondary mb-2">Total Produk</h6>
                <h2 class="fw-bold mb-0"><?= $total_produk ?></h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="dashboard-box">
                <h6 class="fw-semibold text-secondary mb-2">Total Pesanan</h6>
                <h2 class="fw-bold mb-0"><?= $total_pesanan ?></h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="dashboard-box">
                <h6 class="fw-semibold text-secondary mb-2">Produk Terjual</h6>
                <h2 class="fw-bold mb-0"><?= $total_terjual ?></h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="dashboard-box">
                <h6 class="fw-semibold text-secondary mb-2">Omzet Produk</h6>
                <h2 class="fw-bold mb-0"><?= rupiah($total_omzet) ?></h2>
            </div>
        </div>
    </div>

    <a class="btn btn-primary mb-3" href="add_product.php">Tambah Produk</a>
    <a class="btn btn-secondary mb-3" href="orders.php">Pesanan Masuk</a>

    <table class="table bg-white">
        <tr>
            <th>Produk</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Garansi</th>
            <th>Aksi</th>
        </tr>

        <?php
        $q = mysqli_query(
            $conn,
            "SELECT * FROM products WHERE seller_id=" . $_SESSION['user']['id'] . " ORDER BY id DESC"
        );

        while ($p = mysqli_fetch_assoc($q)):
        ?>
            <tr>
                <td><?= htmlspecialchars($p['name']) ?></td>
                <td><?= rupiah($p['price']) ?></td>
                <td><?= $p['stock'] ?></td>
                <td><?= $p['warranty_months'] ?> bulan</td>
                <td>
                    <a class="btn btn-sm btn-warning" href="edit_product.php?id=<?= $p['id'] ?>">
                        Edit
                    </a>

                    <a
                        onclick="return confirmDelete()"
                        class="btn btn-sm btn-danger"
                        href="../../controllers/ProductController.php?delete_product=<?= $p['id'] ?>"
                    >
                        Hapus
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php include __DIR__ . '/../public/footer.php'; ?>