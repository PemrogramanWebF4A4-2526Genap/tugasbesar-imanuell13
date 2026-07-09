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

<main>
    <div class="container py-4">
        <h3>Keranjang Belanja</h3>

        <?php if ($m = flash('error')): ?>
            <div class="alert alert-danger">
                <?= $m ?>
            </div>
        <?php endif; ?>

        <?php if ($m = flash('success')): ?>
            <div class="alert alert-success">
                <?= $m ?>
            </div>
        <?php endif; ?>

        <form method="post" action="src/controllers/CartController.php">
            <table class="table table bg-white">
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                    <th></th>
                </tr>

                <?php
                $total = 0;

                foreach ($_SESSION['cart'] ?? [] as $id => $qty):
                    $p = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id=" . (int) $id));

                    if (!$p) continue;

                    $sub = $p['price'] * $qty;
                    $total += $sub;
                ?>
                    <tr>
                        <td><?= htmlspecialchars($p['name']) ?></td>
                        <td><?= rupiah($p['price']) ?></td>
                        <td>
                            <input
                                class="form-control"
                                type="number"
                                min="1"
                                name="qty[<?= $id ?>]"
                                value="<?= $qty ?>"
                            >
                        </td>
                        <td><?= rupiah($sub) ?></td>
                        <td>
                            <a
                                class="btn btn-sm btn-danger"
                                href="src/controllers/CartController.php?remove=<?= $id ?>"
                            >
                                Hapus
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <h5>Total: <?= rupiah($total) ?></h5>

            <button class="btn btn-secondary" name="update_cart">
                Update
            </button>

            <?php if (!empty($_SESSION['cart'])): ?>
                <a href="checkout.php" class="btn btn-primary">
                    Checkout
                </a>
            <?php else: ?>
                <a href="index.php" class="btn btn-primary">
                    Belanja Sekarang
                </a>
            <?php endif; ?>
        </form>
    </div>
</main>

<?php include 'src/views/public/footer.php'; ?>

</body>
</html>