<?php

require_once __DIR__ . '/../config/database.php';

if (isset($_POST['save_review'])) {
    require_role('buyer');

    $product = (int)$_POST['product_id'];
    $user = $_SESSION['user']['id'];
    $rating = (int)$_POST['rating'];
    $comment = clean($_POST['comment']);

    $photo = null;

    if (!empty($_FILES['photo']['name'])) {
        $photo = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $_FILES['photo']['name']);

        move_uploaded_file(
            $_FILES['photo']['tmp_name'],
            __DIR__ . '/../uploads/reviews/' . $photo
        );
    }

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO reviews(product_id,user_id,rating,comment,photo) VALUES(?,?,?,?,?)"
    );

    mysqli_stmt_bind_param($stmt, 'iiiss', $product, $user, $rating, $comment, $photo);
    mysqli_stmt_execute($stmt);

    flash('success', 'Review berhasil ditambahkan.');

    header('Location: ../views/buyer/orders.php');
    exit;
}

if (isset($_POST['save_all_review'])) {
    require_role('buyer');

    $user = $_SESSION['user']['id'];

    foreach ($_POST['product_id'] as $product_id) {
        $product = (int)$product_id;
        $rating = (int)($_POST['rating'][$product] ?? 5);
        $comment = clean($_POST['comment'][$product] ?? '');

        if ($comment == '') {
            continue;
        }

        $photo = null;
        $file_key = 'photo_' . $product;

        if (!empty($_FILES[$file_key]['name'])) {
            $photo = time() . '_' . $product . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $_FILES[$file_key]['name']);

            move_uploaded_file(
                $_FILES[$file_key]['tmp_name'],
                __DIR__ . '/../uploads/reviews/' . $photo
            );
        }

        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO reviews(product_id,user_id,rating,comment,photo) VALUES(?,?,?,?,?)"
        );

        mysqli_stmt_bind_param($stmt, 'iiiss', $product, $user, $rating, $comment, $photo);
        mysqli_stmt_execute($stmt);
    }

    flash('success', 'Semua review berhasil ditambahkan.');

    header('Location: ../views/buyer/orders.php');
    exit;
}

?>