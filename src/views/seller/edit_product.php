<?php

require_once __DIR__ . '/../../config/database.php';
require_role('seller');

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
    <?php
    require_once __DIR__ . '/../../controllers/ProductController.php';

    $id = (int)($_GET['id'] ?? 0);

    $p = mysqli_fetch_assoc(
        mysqli_query(
            $conn,
            "SELECT * FROM products WHERE id=$id AND seller_id=" . $_SESSION['user']['id']
        )
    );
    ?>

    <h3>Edit Produk</h3>

    <?php if ($p): ?>
        <form method="post" enctype="multipart/form-data" action="../../controllers/ProductController.php">
            <input type="hidden" name="id" value="<?= $p['id'] ?>">

            <?php $cats = mysqli_query($conn, "SELECT * FROM categories ORDER BY name"); ?>

            <input
                class="form-control mb-2"
                name="name"
                placeholder="Nama Produk"
                value="<?= htmlspecialchars($p['name'] ?? '') ?>"
                required
            >

            <textarea
                class="form-control mb-2"
                name="description"
                placeholder="Deskripsi"
                required
            ><?= htmlspecialchars($p['description'] ?? '') ?></textarea>

            <input
                class="form-control mb-2"
                type="number"
                name="price"
                placeholder="Harga"
                value="<?= $p['price'] ?? '' ?>"
                required
            >

            <input
                class="form-control mb-2"
                type="number"
                name="stock"
                placeholder="Stok"
                value="<?= $p['stock'] ?? '' ?>"
                required
            >

            <select class="form-select mb-2" name="category_id">
                <?php while ($c = mysqli_fetch_assoc($cats)): ?>
                    <option
                        value="<?= $c['id'] ?>"
                        <?= isset($p) && $p['category_id'] == $c['id'] ? 'selected' : '' ?>
                    >
                        <?= htmlspecialchars($c['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <input
                class="form-control mb-2"
                type="number"
                name="warranty_months"
                placeholder="Garansi bulan"
                value="<?= $p['warranty_months'] ?? 12 ?>"
            >

            <input
                class="form-control mb-3"
                type="file"
                name="image"
                accept="image/*"
            >

            <button class="btn btn-primary" name="update_product">
                Update
            </button>
        </form>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../public/footer.php'; ?>