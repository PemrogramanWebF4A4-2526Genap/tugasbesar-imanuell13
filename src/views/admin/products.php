<?php

require_once __DIR__ . '/../../config/database.php';
require_role('admin');

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
    <?php require_once __DIR__ . '/../../controllers/AdminController.php'; ?>

    <h3>Manage Kategori & Produk</h3>

    <form class="card p-3 mb-3" method="post" action="../../controllers/AdminController.php">
        <input class="form-control mb-2" name="name" placeholder="Nama kategori">

        <textarea class="form-control mb-2" name="description" placeholder="Deskripsi"></textarea>

        <button class="btn btn-primary" name="save_category">
            Tambah Kategori
        </button>
    </form>

    <table class="table bg-white">
        <tr>
            <th>Produk</th>
            <th>Seller</th>
            <th>Kategori</th>
            <th>Stok</th>
        </tr>

        <?php
        $q = mysqli_query(
            $conn,
            "SELECT p.*,u.name seller,c.name category
             FROM products p
             LEFT JOIN users u ON p.seller_id=u.id
             LEFT JOIN categories c ON p.category_id=c.id
             ORDER BY p.id DESC"
        );

        while ($p = mysqli_fetch_assoc($q)):
        ?>
            <tr>
                <td><?= htmlspecialchars($p['name']) ?></td>
                <td><?= htmlspecialchars($p['seller']) ?></td>
                <td><?= htmlspecialchars($p['category']) ?></td>
                <td><?= $p['stock'] ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php include __DIR__ . '/../public/footer.php'; ?>