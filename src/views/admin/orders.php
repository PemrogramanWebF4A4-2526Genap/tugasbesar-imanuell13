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
    <h3>Manage Semua Pesanan</h3>

    <table class="table bg-white">
        <tr>
            <th>Invoice</th>
            <th>Pembeli</th>
            <th>Total</th>
            <th>Status</th>
            <th>Bukti Transfer</th>
            <th>Payment</th>
        </tr>

        <?php
        $q = mysqli_query(
            $conn,
            "SELECT o.*,u.name buyer,pay.status pay_status,pay.id pay_id,pay.proof
             FROM orders o
             JOIN users u ON o.buyer_id=u.id
             LEFT JOIN payments pay ON o.id=pay.order_id
             ORDER BY o.id DESC"
        );

        while ($o = mysqli_fetch_assoc($q)):
        ?>
            <tr>
                <td><?= $o['invoice_no'] ?></td>

                <td><?= htmlspecialchars($o['buyer']) ?></td>

                <td><?= rupiah($o['total_amount']) ?></td>

                <td><?= $o['status'] ?></td>

                <td>
                    <?php if (!empty($o['proof'])): ?>
                        <a
                            href="../../uploads/payments/<?= htmlspecialchars($o['proof']) ?>"
                            target="_blank"
                        >
                            <img
                                src="../../uploads/payments/<?= htmlspecialchars($o['proof']) ?>"
                                alt="Bukti Transfer"
                                style="width:80px;height:80px;object-fit:cover;border-radius:8px;border:1px solid #ddd;"
                            >
                        </a>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>

                <td>
                    <?= $o['pay_status'] ?: '-' ?>

                    <?php if ($o['pay_status'] == 'waiting_verification'): ?>
                        <a
                            class="btn btn-sm btn-success"
                            href="../../controllers/PaymentController.php?verify=<?= $o['pay_id'] ?>"
                        >
                            Verifikasi
                        </a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php include __DIR__ . '/../public/footer.php'; ?>