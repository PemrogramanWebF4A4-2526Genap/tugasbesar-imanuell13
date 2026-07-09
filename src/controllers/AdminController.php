<?php

require_once __DIR__ . '/../config/database.php';

if (isset($_GET['verify_seller'])) {
    require_role('admin');

    $id = (int)$_GET['verify_seller'];

    mysqli_query($conn, "UPDATE users SET status='active' WHERE id=$id AND role='seller'");

    header('Location: ../views/admin/users.php');
    exit;
}

if (isset($_GET['delete_user'])) {
    require_role('admin');

    $id = (int)$_GET['delete_user'];

    mysqli_query($conn, "DELETE FROM users WHERE id=$id AND role!='admin'");

    header('Location: ../views/admin/users.php');
    exit;
}

if (isset($_POST['save_category'])) {
    require_role('admin');

    $name = clean($_POST['name']);
    $desc = clean($_POST['description']);

    $stmt = mysqli_prepare($conn, "INSERT INTO categories(name,description) VALUES(?,?)");

    mysqli_stmt_bind_param($stmt, 'ss', $name, $desc);
    mysqli_stmt_execute($stmt);

    header('Location: ../views/admin/products.php');
    exit;
}


if (isset($_POST['update_settings'])) {
    require_role('admin');

    foreach (($_POST['settings'] ?? []) as $key => $value) {
        $key = clean($key);
        $value = clean($value);
        $stmt = mysqli_prepare($conn, "INSERT INTO system_settings(setting_key,setting_value) VALUES(?,?) ON DUPLICATE KEY UPDATE setting_value=VALUES(setting_value)");
        mysqli_stmt_bind_param($stmt, 'ss', $key, $value);
        mysqli_stmt_execute($stmt);
    }

    flash('success', 'System settings berhasil diperbarui.');
    redirect_to('src/views/admin/settings.php');
}

?>