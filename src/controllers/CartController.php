<?php

require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

function cart_total_items()
{
    return array_sum($_SESSION['cart'] ?? []);
}

function json_response($data)
{
    if (ob_get_length()) {
        ob_clean();
    }

    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

if (isset($_POST['add_to_cart'])) {
    $id = (int)($_POST['product_id'] ?? 0);
    $qty = max(1, (int)($_POST['qty'] ?? 1));

    $stmt = mysqli_prepare($conn, "SELECT stock FROM products WHERE id=? LIMIT 1");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $product = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    if (!$product || $product['stock'] <= 0) {
        $message = 'Produk tidak tersedia atau stok habis.';

        if (isset($_POST['ajax'])) {
            json_response([
                'success' => false,
                'message' => $message,
                'cart_count' => cart_total_items()
            ]);
        }

        flash('error', $message);
        redirect_to('index.php');
    }

    $current = $_SESSION['cart'][$id] ?? 0;
    $_SESSION['cart'][$id] = min($current + $qty, (int)$product['stock']);

    $message = 'Produk masuk keranjang.';

    if (isset($_POST['ajax'])) {
        json_response([
            'success' => true,
            'message' => $message,
            'cart_count' => cart_total_items()
        ]);
    }

    flash('success', $message);
    redirect_to('cart.php');
}

if (isset($_POST['update_cart'])) {
    foreach (($_POST['qty'] ?? []) as $id => $q) {
        $id = (int)$id;
        $q = (int)$q;

        if ($q <= 0) {
            unset($_SESSION['cart'][$id]);
            continue;
        }

        $stmt = mysqli_prepare($conn, "SELECT stock FROM products WHERE id=? LIMIT 1");
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $product = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

        if ($product) {
            $_SESSION['cart'][$id] = min($q, (int)$product['stock']);
        }
    }

    flash('success', 'Keranjang diperbarui.');
    redirect_to('cart.php');
}

if (isset($_GET['remove'])) {
    unset($_SESSION['cart'][(int)$_GET['remove']]);
    redirect_to('cart.php');
}

redirect_to('index.php');
exit;

?>