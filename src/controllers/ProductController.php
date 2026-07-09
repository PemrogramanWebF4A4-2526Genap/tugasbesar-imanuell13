<?php

require_once __DIR__ . '/../config/database.php';

function upload_file($field, $dir)
{
    if (empty($_FILES[$field]['name'])) {
        return null;
    }

    $name = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $_FILES[$field]['name']);
    $target = __DIR__ . '/../uploads/' . $dir . '/' . $name;

    move_uploaded_file($_FILES[$field]['tmp_name'], $target);

    return $name;
}

if (isset($_POST['save_product'])) {
    require_role(['seller', 'admin']);

    $seller_id = $_SESSION['user']['id'];
    $name = clean($_POST['name']);
    $description = clean($_POST['description']);
    $price = (float)$_POST['price'];
    $stock = (int)$_POST['stock'];
    $category_id = (int)$_POST['category_id'];
    $warranty = (int)$_POST['warranty_months'];

    $image = upload_file('image', 'products') ?: 'default-product.jpg';

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO products(seller_id,name,description,price,stock,category_id,image,warranty_months) VALUES(?,?,?,?,?,?,?,?)"
    );

    mysqli_stmt_bind_param($stmt, 'issdiisi', $seller_id, $name, $description, $price, $stock, $category_id, $image, $warranty);

    mysqli_stmt_execute($stmt);

    flash('success', 'Produk berhasil ditambahkan.');

    header('Location: ../views/seller/dashboard.php');
    exit;
}

if (isset($_POST['update_product'])) {
    require_role(['seller', 'admin']);

    $id = (int)$_POST['id'];
    $name = clean($_POST['name']);
    $description = clean($_POST['description']);
    $price = (float)$_POST['price'];
    $stock = (int)$_POST['stock'];
    $category_id = (int)$_POST['category_id'];
    $warranty = (int)$_POST['warranty_months'];

    $image = upload_file('image', 'products');

    if ($image) {
        $stmt = mysqli_prepare(
            $conn,
            "UPDATE products SET name=?,description=?,price=?,stock=?,category_id=?,image=?,warranty_months=? WHERE id=?"
        );

        mysqli_stmt_bind_param($stmt, 'ssdiisii', $name, $description, $price, $stock, $category_id, $image, $warranty, $id);
    } else {
        $stmt = mysqli_prepare(
            $conn,
            "UPDATE products SET name=?,description=?,price=?,stock=?,category_id=?,warranty_months=? WHERE id=?"
        );

        mysqli_stmt_bind_param($stmt, 'ssdiiii', $name, $description, $price, $stock, $category_id, $warranty, $id);
    }

    mysqli_stmt_execute($stmt);

    flash('success', 'Produk diperbarui.');

    header('Location: ../views/seller/dashboard.php');
    exit;
}

if (isset($_GET['delete_product'])) {
    require_role(['seller', 'admin']);

    $id = (int)$_GET['delete_product'];

    mysqli_query($conn, "DELETE FROM products WHERE id=$id");

    header('Location: ../views/seller/dashboard.php');
    exit;
}

?>