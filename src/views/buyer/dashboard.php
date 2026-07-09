<?php

require_once __DIR__ . '/../../config/database.php';
require_role('buyer');

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
    <h3>Dashboard Pembeli</h3>

    <div class="dashboard-box">
        Selamat datang, <?= htmlspecialchars($_SESSION['user']['name']) ?>.
        Anda dapat melihat pesanan, tracking order, dan membuat review.
    </div>

    <a class="btn btn-primary mt-3" href="orders.php">
        Lihat Pesanan
    </a>
</div>

<?php include __DIR__ . '/../public/footer.php'; ?>