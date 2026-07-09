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
    <h3>Pesanan Produk</h3>

    <?php
    if (isset($_GET['detail'])):
        $order_id = (int) $_GET['detail'];

        $detail = mysqli_query($conn, "
            SELECT 
                products.name,
                order_items.quantity,
                order_items.price
            FROM order_items
            JOIN products ON products.id = order_items.product_id
            WHERE order_items.order_id = $order_id
        ");

        $order_total = mysqli_query($conn, "
            SELECT total_amount, shipping_cost
            FROM orders
            WHERE id = $order_id
            LIMIT 1
        ");

        $ot = mysqli_fetch_assoc($order_total);
        $shipping_cost = $ot['shipping_cost'] ?? 0;
        $total_amount = $ot['total_amount'] ?? 0;
        $subtotal_produk = $total_amount - $shipping_cost;
    ?>

    <div class="card p-3 mb-4">
        <h5>Detail Pesanan</h5>

        <table class="table table-bordered">
            <tr>
                <th>Produk</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Subtotal</th>
            </tr>

            <?php while ($d = mysqli_fetch_assoc($detail)): ?>
                <tr>
                    <td><?= htmlspecialchars($d['name']) ?></td>
                    <td><?= $d['quantity'] ?></td>
                    <td><?= rupiah($d['price']) ?></td>
                    <td><?= rupiah($d['quantity'] * $d['price']) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>

        <div class="mt-3">
            <div class="d-flex justify-content-between">
                <span>Subtotal Produk</span>
                <strong><?= rupiah($subtotal_produk) ?></strong>
            </div>

            <div class="d-flex justify-content-between">
                <span>Ongkir</span>
                <strong><?= rupiah($shipping_cost) ?></strong>
            </div>

            <hr>

            <div class="d-flex justify-content-between fs-5">
                <span>Total Pesanan</span>
                <strong><?= rupiah($total_amount) ?></strong>
            </div>
        </div>
    </div>

    <?php endif; ?>

    <table class="table bg-white">
        <tr>
            <th>Invoice</th>
            <th>Pembeli</th>
            <th>Produk</th>
            <th>Status</th>
            <th>Update</th>
        </tr>

        <?php
        $q = mysqli_query(
            $conn,
            "SELECT DISTINCT o.*,u.name buyer
             FROM orders o
             JOIN users u ON o.buyer_id=u.id
             JOIN order_items oi ON o.id=oi.order_id
             JOIN products p ON oi.product_id=p.id
             WHERE p.seller_id=" . $_SESSION['user']['id'] . "
             ORDER BY o.id DESC"
        );

        while ($o = mysqli_fetch_assoc($q)):
        ?>
            <tr>
                <td><?= $o['invoice_no'] ?></td>
                <td><?= htmlspecialchars($o['buyer']) ?></td>
                <td><a href="orders.php?detail=<?= $o['id'] ?>">Detail</a></td>
                <td><?= $o['status'] ?></td>
                <td>
                    <form method="post" action="../../controllers/OrderController.php" class="d-flex gap-1">
                        <input type="hidden" name="order_id" value="<?= $o['id'] ?>">

                        <select name="status" class="form-select form-select-sm">
                            <option>processing</option>
                            <option>shipped</option>
                            <option>completed</option>
                        </select>

                        <button class="btn btn-sm btn-primary" name="update_order_status">
                            OK
                        </button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php include __DIR__ . '/../public/footer.php'; ?>