<?php require_once 'src/config/database.php'; ?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Produk - Digital Zone</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="src/assets/css/style.css">
</head>
<body>

<?php include 'src/views/public/navbar.php'; ?>

<div class="container py-4">
    <?php
    $id = (int)($_GET['id'] ?? 0);

    $product_q = mysqli_query($conn, "
        SELECT p.*, c.name AS category, u.name AS seller
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN users u ON p.seller_id = u.id
        WHERE p.id = $id
        LIMIT 1
    ");

    $p = mysqli_fetch_assoc($product_q);

    if (!$p):
    ?>
        <div class="alert alert-danger">Produk tidak ditemukan.</div>
    <?php else: ?>

    <a href="index.php" class="btn btn-secondary btn-sm mb-3">Kembali</a>

    <div class="row g-4">
        <div class="col-md-5">
            <div class="card p-3">
                <img src="src/uploads/products/<?= htmlspecialchars($p['image']) ?>"
                     class="w-100"
                     style="height:350px;object-fit:contain;"
                     onerror="this.src='src/assets/images/banner.jpg'">
            </div>
        </div>

        <div class="col-md-7">
            <div class="card p-4">
                <span class="badge bg-primary mb-2" style="width:max-content;">
                    <?= htmlspecialchars($p['category']) ?>
                </span>

                <h3><?= htmlspecialchars($p['name']) ?></h3>

                <p class="fw-bold text-primary fs-4">
                    <?= rupiah($p['price']) ?>
                </p>

                <p>
                    Stok: <?= $p['stock'] ?> | Garansi: <?= $p['warranty_months'] ?> bulan
                </p>

                <h5>Deskripsi Produk</h5>
                <p><?= nl2br(htmlspecialchars($p['description'])) ?></p>

                <form method="post" action="src/controllers/CartController.php" class="d-flex gap-2 ajax-cart-form">
                    <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                    <input type="hidden" name="ajax" value="1">
                    <input type="number" min="1" name="qty" value="1" class="form-control">
                    <button type="submit" class="btn btn-warning" name="add_to_cart">Cart</button>
                </form>
            </div>
        </div>
    </div>

    <div class="card p-4 mt-4">
        <h4>Review Produk</h4>

        <?php
        $reviews = mysqli_query($conn, "
            SELECT r.*, u.name AS reviewer
            FROM reviews r
            LEFT JOIN users u ON r.user_id = u.id
            WHERE r.product_id = $id
            ORDER BY r.id DESC
        ");
        ?>

        <?php if (mysqli_num_rows($reviews) > 0): ?>
            <?php while ($r = mysqli_fetch_assoc($reviews)): ?>
                <div class="border rounded p-3 mb-3">
                    <div class="fw-bold">
                        <?= htmlspecialchars($r['reviewer'] ?? 'User') ?>
                        - ⭐ <?= htmlspecialchars($r['rating']) ?>/5
                    </div>

                    <p class="mb-2"><?= htmlspecialchars($r['comment']) ?></p>

                    <?php if (!empty($r['photo'])): ?>
                        <img src="src/uploads/reviews/<?= htmlspecialchars($r['photo']) ?>"
                             class="rounded"
                             style="width:120px;height:120px;object-fit:cover;">
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-muted">Belum ada review.</p>
        <?php endif; ?>
    </div>

    <?php endif; ?>
</div>

<?php include 'src/views/public/footer.php'; ?>

<script>
document.querySelectorAll('.ajax-cart-form').forEach(function(form) {
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(form);
        formData.append('add_to_cart', '1');

        fetch(form.action, {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(text => {
            const data = JSON.parse(text);
            alert(data.message);
        })
        .catch(error => {
            alert('Terjadi kesalahan saat menambahkan produk ke keranjang.');
        });
    });
});
</script>

</body>
</html>