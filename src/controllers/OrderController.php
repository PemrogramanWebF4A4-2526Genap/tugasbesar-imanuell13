<?php

require_once __DIR__ . '/../config/database.php';

if (isset($_POST['checkout'])) {
    require_role('buyer');

    if (empty($_SESSION['cart'])) {
        redirect_to('cart.php');
    }

    $address = clean($_POST['address'] ?? '');
    $method = clean($_POST['payment_method'] ?? 'Transfer Bank');
    $shipping_type = clean($_POST['shipping_type'] ?? 'Reguler');
    $shipping = calculate_shipping_cost($shipping_type);
    $buyer = (int)$_SESSION['user']['id'];
    $total = $shipping;

    foreach ($_SESSION['cart'] as $id => $qty) {
        $id = (int)$id;
        $qty = (int)$qty;
        $stmt = mysqli_prepare($conn, "SELECT price,stock,name FROM products WHERE id=? LIMIT 1");
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $p = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

        if (!$p || $p['stock'] < $qty) {
            flash('error', 'Stok produk tidak mencukupi. Silakan update keranjang.');
            redirect_to('cart.php');
        }
        $total += $p['price'] * $qty;
    }

    $invoice = 'INV' . date('YmdHis') . rand(10, 99); // auto generate invoice
    $status = 'pending_payment';

    mysqli_begin_transaction($conn);
    try {
        $stmt = mysqli_prepare($conn, "INSERT INTO orders(buyer_id,invoice_no,total_amount,shipping_address,shipping_cost,status) VALUES(?,?,?,?,?,?)");
        mysqli_stmt_bind_param($stmt, 'isssds', $buyer, $invoice, $total, $address, $shipping, $status);
        mysqli_stmt_execute($stmt);
        $order_id = mysqli_insert_id($conn);

        foreach ($_SESSION['cart'] as $id => $qty) {
            $id = (int)$id;
            $qty = (int)$qty;
            $stmt = mysqli_prepare($conn, "SELECT price FROM products WHERE id=? LIMIT 1");
            mysqli_stmt_bind_param($stmt, 'i', $id);
            mysqli_stmt_execute($stmt);
            $p = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

            $stmt = mysqli_prepare($conn, "INSERT INTO order_items(order_id,product_id,quantity,price) VALUES(?,?,?,?)");
            mysqli_stmt_bind_param($stmt, 'iiid', $order_id, $id, $qty, $p['price']);
            mysqli_stmt_execute($stmt);

            // auto update stok
            $stmt = mysqli_prepare($conn, "UPDATE products SET stock=stock-? WHERE id=? AND stock>=?");
            mysqli_stmt_bind_param($stmt, 'iii', $qty, $id, $qty);
            mysqli_stmt_execute($stmt);
        }

        $msg = 'Pesanan ' . $invoice . ' berhasil dibuat. Ongkir ' . $shipping_type . ' otomatis sebesar ' . rupiah($shipping) . '. Silakan konfirmasi pembayaran.';
        create_notification($conn, $buyer, $msg);
        send_email_notification($conn, $buyer, 'Invoice ' . $invoice, $msg);
        mysqli_commit($conn);
    } catch (Throwable $e) {
        mysqli_rollback($conn);
        flash('error', 'Checkout gagal: ' . $e->getMessage());
        redirect_to('checkout.php');
    }

    unset($_SESSION['cart']);
    redirect_to('payment.php?order_id=' . $order_id);
}

if (isset($_POST['update_order_status'])) {
    require_role(['seller', 'admin']);

    $id = (int)($_POST['order_id'] ?? 0);
    $status = clean($_POST['status'] ?? 'processing');
    $allowed = ['processing', 'shipped', 'completed', 'cancelled'];
    if (!in_array($status, $allowed, true)) {
        $status = 'processing';
    }

    $stmt = mysqli_prepare($conn, "UPDATE orders SET status=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, 'si', $status, $id);
    mysqli_stmt_execute($stmt);

    $order = mysqli_fetch_assoc(mysqli_query($conn, "SELECT buyer_id,invoice_no FROM orders WHERE id=$id"));
    if ($order) {
        $msg = 'Status pesanan ' . $order['invoice_no'] . ' diperbarui menjadi ' . $status . '.';
        create_notification($conn, (int)$order['buyer_id'], $msg);
        send_email_notification($conn, (int)$order['buyer_id'], 'Update Pesanan', $msg);
    }

    if (user_role() === 'admin') {
        redirect_to('src/views/admin/orders.php');
    }
    redirect_to('src/views/seller/orders.php');
}

?>