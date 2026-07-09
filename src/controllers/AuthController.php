<?php

require_once __DIR__ . '/../config/database.php';

if (isset($_POST['register'])) {

    $name = clean($_POST['name']);
    $email = clean($_POST['email']);
    $role = clean($_POST['role']);
    $password = $_POST['password'] ?? '';

    if (strlen($password) < 6) {
        flash('error', 'Password minimal 6 karakter');
        header('Location: ../../register.php');
        exit;
    }

    if (!in_array($role, ['buyer', 'seller'])) {
        $role = 'buyer';
    }

    $hash = password_hash($password, PASSWORD_BCRYPT);
    $status = $role == 'seller' ? 'pending' : 'active';

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO users(name,email,password,role,status) VALUES(?,?,?,?,?)"
    );

    mysqli_stmt_bind_param($stmt, 'sssss', $name, $email, $hash, $role, $status);

    if (mysqli_stmt_execute($stmt)) {
        flash('success', 'Register berhasil. Seller perlu verifikasi admin.');
        header('Location: ../../login.php');
    } else {
        flash('error', 'Email sudah digunakan atau data tidak valid.');
        header('Location: ../../register.php');
    }

    exit;
}

if (isset($_POST['login'])) {

    $email = clean($_POST['email']);
    $password = $_POST['password'] ?? '';

    $stmt = mysqli_prepare(
        $conn,
        "SELECT * FROM users WHERE email=? LIMIT 1"
    );

    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);

    $user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    if ($user && password_verify($password, $user['password'])) {

        if ($user['status'] != 'active') {
            flash('error', 'Akun belum aktif/diverifikasi admin.');
            header('Location: ../../login.php');
            exit;
        }

        $_SESSION['user'] = [
            'id'    => $user['id'],
            'name'  => $user['name'],
            'email' => $user['email'],
            'role'  => $user['role']
        ];

        header('Location: ../../index.php');
        exit;
    }

    flash('error', 'Email atau password salah.');
    header('Location: ../../login.php');
    exit;
}

?>