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

    <div class="container py-4">
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

        <div class="hero p-5 mb-4 text-center">
            <img
                src="src/assets/images/logo.png"
                alt="Digital Zone"
                width="90"
                height="90"
                class="mb-3">

            <h1 class="fw-bold">Digital Zone</h1>

            <p class="lead mb-0">
                Belanja laptop, smartphone, aksesoris, dan perangkat elektronik lengkap dengan sistem garansi.
            </p>
        </div>

        <?php
        $search = $_GET['search'] ?? '';
        $category_id = $_GET['category_id'] ?? '';

        $categories = mysqli_query($conn, "SELECT * FROM categories ORDER BY name ASC");

        $where = "WHERE 1=1";

        if ($search != '') {
            $safe_search = mysqli_real_escape_string($conn, $search);
            $where .= " AND (p.name LIKE '%$safe_search%' OR p.description LIKE '%$safe_search%')";
        }

        if ($category_id != '') {
            $safe_category = (int)$category_id;
            $where .= " AND p.category_id = $safe_category";
        }
        ?>

        <form method="get" class="card p-3 mb-4">
            <div class="row g-2">
                <div class="col-md-6">
                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Cari produk..."
                        value="<?= htmlspecialchars($search) ?>">
                </div>

                <div class="col-md-4">
                    <select name="category_id" class="form-select">
                        <option value="">Semua Kategori</option>

                        <?php while ($cat = mysqli_fetch_assoc($categories)): ?>
                            <option
                                value="<?= $cat['id'] ?>"
                                <?= ($category_id == $cat['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-warning w-100">
                        Cari
                    </button>
                </div>
            </div>
        </form>

        <div class="row g-4">
            <?php
            $products = mysqli_query($conn, "
            SELECT p.*,c.name category,u.name seller
            FROM products p
            LEFT JOIN categories c ON p.category_id=c.id
            LEFT JOIN users u ON p.seller_id=u.id
            $where
            ORDER BY p.id DESC
        ");

            if (mysqli_num_rows($products) == 0):
            ?>
                <div class="col-12">
                    <div class="alert alert-warning">
                        Produk tidak ditemukan.
                    </div>
                </div>
            <?php
            endif;

            while ($p = mysqli_fetch_assoc($products)):
            ?>
                <div class="col-md-4">
                    <div class="card h-100 p-3">
                        <img
                            class="product-img w-100"
                            src="src/uploads/products/<?= htmlspecialchars($p['image']) ?>"
                            onerror="this.src='src/assets/images/banner.jpg'">

                        <div class="card-body px-0">
                            <span class="badge bg-primary">
                                <?= htmlspecialchars($p['category']) ?>
                            </span>

                            <h5 class="mt-2">
                                <?= htmlspecialchars($p['name']) ?>
                            </h5>

                            <p class="text-muted small">
                                <?= htmlspecialchars(substr($p['description'], 0, 85)) ?>...
                            </p>

                            <p class="fw-bold text-primary mb-1">
                                <?= rupiah($p['price']) ?>
                            </p>

                            <p class="small">
                                Stok: <?= $p['stock'] ?> | Garansi: <?= $p['warranty_months'] ?> bulan
                            </p>

                            <form method="post" action="src/controllers/CartController.php" class="d-flex gap-2 ajax-cart-form">
                                <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                                <input type="hidden" name="ajax" value="1">
                                <input type="number" min="1" name="qty" value="1" class="form-control">

                                <button type="submit" class="btn btn-warning" name="add_to_cart">
                                    Cart
                                </button>
                            </form>

                            <a href="product_detail.php?id=<?= $p['id'] ?>" class="btn btn-detail w-100">
                                Detail Produk
                            </a>

                            <?php
                            $summary_q = mysqli_query($conn, "
                            SELECT
                                ROUND(AVG(rating), 1) AS avg_rating,
                                COUNT(*) AS total_review
                            FROM reviews
                            WHERE product_id = " . (int)$p['id'] . "
                        ");

                            $summary = mysqli_fetch_assoc($summary_q);
                            ?>

                            <div class="mt-2">
                                <small class="text-warning fw-bold">
                                    ⭐ <?= $summary['avg_rating'] ?: '0' ?>
                                </small>
                                <small class="text-muted">
                                    (<?= $summary['total_review'] ?> Review)
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
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
                        console.log('Response:', text);

                        try {
                            const data = JSON.parse(text);

                            alert(data.message);

                            if (data.success) {
                                console.log('Jumlah cart:', data.cart_count);
                            }
                        } catch (e) {
                            alert('Response bukan JSON. Tekan F12 lalu buka tab Console.');
                            console.log('Raw Response:', text);
                        }
                    })
                    .catch(error => {
                        console.error('Fetch Error:', error);
                        alert('Terjadi kesalahan saat menambahkan produk ke keranjang.');
                    });
            });
        });
    </script>

</body>

</html>