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

if (empty($_SESSION['cart'])) {
    echo '
    <div class="container py-5">
        <div class="alert alert-warning">
            Keranjang kosong.
        </div>
    </div>';

    include 'src/views/public/footer.php';
    exit;
}
?>

<div class="container py-4">
    <div class="card p-4">
        <h3>Checkout</h3>

        <form method="post" action="src/controllers/OrderController.php">
            <textarea
                class="form-control mb-3"
                name="address"
                placeholder="Alamat pengiriman"
                required
            ></textarea>

            <select class="form-select mb-3" name="payment_method">
                <option>Transfer Bank</option>
                <option>COD</option>
                <option>E-Wallet</option>
            </select>

            <label class="form-label">Pengiriman (ongkir dihitung otomatis)</label>
            <select class="form-select mb-3" name="shipping_type" id="shippingType">
                <option value="Reguler" data-cost="15000">Reguler - Rp 15.000</option>
                <option value="Express" data-cost="30000">Express - Rp 30.000</option>
                <option value="Same Day" data-cost="45000">Same Day - Rp 45.000</option>
            </select>

            <button class="btn btn-primary" name="checkout">
                Buat Pesanan
            </button>
        </form>
    </div>
</div>

<?php include 'src/views/public/footer.php'; ?>