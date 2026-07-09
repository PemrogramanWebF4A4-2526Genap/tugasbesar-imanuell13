<?php require_once 'src/config/database.php'; ?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Digital Zone</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="src/assets/css/style.css">
</head>
<body>

<?php include 'src/views/public/navbar.php'; ?>

<?php
require_role('buyer');

$order_id = (int)($_GET['order_id'] ?? 0);

$o = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT * FROM orders WHERE id=$order_id AND buyer_id=" . $_SESSION['user']['id']
    )
);
?>

<div class="container py-4">
    <div class="card p-4">
        <h3>Konfirmasi Pembayaran</h3>

        <?php if ($o): ?>
            <p>
                Invoice: <b><?= $o['invoice_no'] ?></b> |
                Total: <b><?= rupiah($o['total_amount']) ?></b>
            </p>

            <form
                method="post"
                enctype="multipart/form-data"
                action="src/controllers/PaymentController.php"
            >
                <input
                    type="hidden"
                    name="order_id"
                    value="<?= $o['id'] ?>"
                >

                <select class="form-select mb-3" name="payment_method">
                    <option>Transfer Bank</option>
                    <option>E-Wallet</option>
                    <option>COD</option>
                </select>

                <input
                    class="form-control mb-3"
                    type="file"
                    name="proof"
                    accept="image/*,.pdf"
                >

                <button
                    class="btn btn-success"
                    name="confirm_payment"
                >
                    Kirim Bukti
                </button>
            </form>

        <?php else: ?>
            <div class="alert alert-danger">
                Pesanan tidak ditemukan.
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'src/views/public/footer.php'; ?>