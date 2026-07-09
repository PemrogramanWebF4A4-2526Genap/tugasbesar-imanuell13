<?php

require_once __DIR__ . '/../config/database.php';

function valid_upload($file)
{
    $allowed = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];
    return isset($file['tmp_name']) && is_uploaded_file($file['tmp_name']) && in_array($file['type'], $allowed, true) && $file['size'] <= 2 * 1024 * 1024;
}

if (isset($_POST['confirm_payment'])) {
    require_role('buyer');

    $order_id = (int)($_POST['order_id'] ?? 0);
    $method = clean($_POST['payment_method'] ?? 'Transfer Bank');
    $proof = null;

    if (!empty($_FILES['proof']['name'])) {
        if (!valid_upload($_FILES['proof'])) {
            flash('error', 'Bukti pembayaran harus JPG/PNG/PDF maksimal 2MB.');
            redirect_to('payment.php?order_id=' . $order_id);
        }

        $proof = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $_FILES['proof']['name']);
        move_uploaded_file($_FILES['proof']['tmp_name'], __DIR__ . '/../uploads/payments/' . $proof);
    }

    $status = 'waiting_verification';
    $stmt = mysqli_prepare($conn, "INSERT INTO payments(order_id,payment_method,proof,status) VALUES(?,?,?,?)");
    mysqli_stmt_bind_param($stmt, 'isss', $order_id, $method, $proof, $status);
    mysqli_stmt_execute($stmt);

    mysqli_query($conn, "UPDATE orders SET status='paid_waiting_verification' WHERE id=$order_id");
    $order = mysqli_fetch_assoc(mysqli_query($conn, "SELECT buyer_id,invoice_no FROM orders WHERE id=$order_id"));
    if ($order) {
        $msg = 'Bukti pembayaran untuk invoice ' . $order['invoice_no'] . ' sudah dikirim dan menunggu verifikasi admin.';
        create_notification($conn, (int)$order['buyer_id'], $msg);
        send_email_notification($conn, (int)$order['buyer_id'], 'Konfirmasi Pembayaran', $msg);
    }

    flash('success', 'Bukti pembayaran terkirim.');
    redirect_to('src/views/buyer/orders.php');
}

if (isset($_GET['verify'])) {
    require_role('admin');

    $id = (int)$_GET['verify'];
    mysqli_query($conn, "UPDATE payments SET status='verified' WHERE id=$id");
    $payment = mysqli_fetch_assoc(mysqli_query($conn, "SELECT order_id FROM payments WHERE id=$id"));
    if ($payment) {
        auto_update_order_status($conn, (int)$payment['order_id']);
        $order = mysqli_fetch_assoc(mysqli_query($conn, "SELECT buyer_id,invoice_no FROM orders WHERE id=" . (int)$payment['order_id']));
        if ($order) {
            $msg = 'Pembayaran invoice ' . $order['invoice_no'] . ' sudah diverifikasi. Pesanan diproses.';
            create_notification($conn, (int)$order['buyer_id'], $msg);
            send_email_notification($conn, (int)$order['buyer_id'], 'Pembayaran Diverifikasi', $msg);
        }
    }

    redirect_to('src/views/admin/orders.php');
}

?>