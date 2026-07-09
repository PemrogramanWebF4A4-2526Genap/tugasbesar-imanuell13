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
    <h3>Pesanan Saya</h3>

    <?php if ($m = flash('success')): ?>
        <div class="alert alert-success">
            <?= $m ?>
        </div>
    <?php endif; ?>

    <table class="table bg-white">
        <tr>
            <th>Invoice</th>
            <th>Total</th>
            <th>Status</th>
            <th>Tanggal</th>
            <th>Review</th>
        </tr>

        <?php
        $q = mysqli_query(
            $conn,
            "SELECT * FROM orders WHERE buyer_id=" . $_SESSION['user']['id'] . " ORDER BY id DESC"
        );

        while ($o = mysqli_fetch_assoc($q)):
        ?>
            <tr>
                <td><?= $o['invoice_no'] ?></td>

                <td><?= rupiah($o['total_amount']) ?></td>

                <td>
                    <span class="badge bg-info">
                        <?= $o['status'] ?>
                    </span>
                </td>

                <td><?= $o['created_at'] ?></td>

                <td>
                    <a
                        class="btn btn-sm btn-warning"
                        href="review.php?order_id=<?= $o['id'] ?>"
                    >
                        Review
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php include __DIR__ . '/../public/footer.php'; ?>