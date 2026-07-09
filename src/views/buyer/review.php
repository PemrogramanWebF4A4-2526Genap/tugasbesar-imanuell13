<?php

require_once __DIR__ . '/../../config/database.php';
require_role('buyer');

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
    require_once __DIR__ . '/../../controllers/ReviewController.php';

    $order = (int)($_GET['order_id'] ?? 0);

    $items = mysqli_query(
        $conn,
        "SELECT oi.*,p.name
         FROM order_items oi
         JOIN products p ON oi.product_id=p.id
         WHERE oi.order_id=$order"
    );
    ?>

    <h2 class="fw-bold mb-4">Review Produk</h2>

    <form method="post" enctype="multipart/form-data" action="../../controllers/ReviewController.php">
        <input type="hidden" name="order_id" value="<?= $order ?>">

        <?php while ($i = mysqli_fetch_assoc($items)): ?>
            <div class="card p-3 mb-3">
                <h5 class="fw-bold mb-3"><?= htmlspecialchars($i['name']) ?></h5>

                <input
                    type="hidden"
                    name="product_id[]"
                    value="<?= $i['product_id'] ?>"
                >

                <label class="form-label">Rating</label>
                <select class="form-select mb-2" name="rating[<?= $i['product_id'] ?>]">
                    <option value="5">5 - Sangat Baik</option>
                    <option value="4">4 - Baik</option>
                    <option value="3">3 - Cukup</option>
                </select>

                <label class="form-label">Komentar</label>
                <textarea
                    class="form-control mb-2"
                    name="comment[<?= $i['product_id'] ?>]"
                    required
                ></textarea>

                <label class="form-label">Foto Review</label>
                <input
                    type="file"
                    class="form-control mb-2"
                    name="photo_<?= $i['product_id'] ?>"
                >
            </div>
        <?php endwhile; ?>

        <button class="btn btn-primary" name="save_all_review">
            Simpan Semua Review
        </button>
    </form>
</div>

<?php include __DIR__ . '/../public/footer.php'; ?>