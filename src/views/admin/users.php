<?php

require_once __DIR__ . '/../../config/database.php';
require_role('admin');

?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Digital Zone</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/UAS_INFO2425_202410715013_IMANUEL/src/assets/css/style.css">
</head>
<body>

<?php include __DIR__ . '/../public/navbar.php'; ?>

<div class="container py-4">
    <?php require_once __DIR__ . '/../../controllers/AdminController.php'; ?>

    <h3>Manage User</h3>

    <table class="table bg-white">
        <tr>
            <th>Nama</th>
            <th>Email</th>
            <th>Role</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>

        <?php
        $q = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");

        while ($u = mysqli_fetch_assoc($q)):
        ?>
            <tr>
                <td><?= htmlspecialchars($u['name']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td><?= $u['role'] ?></td>
                <td><?= $u['status'] ?></td>
                <td>
                    <?php if ($u['role'] == 'seller' && $u['status'] == 'pending'): ?>
                        <a
                            class="btn btn-sm btn-success"
                            href="../../controllers/AdminController.php?verify_seller=<?= $u['id'] ?>"
                        >
                            Verifikasi
                        </a>
                    <?php endif; ?>

                    <?php if ($u['role'] != 'admin'): ?>
                        <a
                            onclick="return confirmDelete()"
                            class="btn btn-sm btn-danger"
                            href="../../controllers/AdminController.php?delete_user=<?= $u['id'] ?>"
                        >
                            Hapus
                        </a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php include __DIR__ . '/../public/footer.php'; ?>