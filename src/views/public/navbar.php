<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container">
        <a
            class="navbar-brand fw-bold d-flex align-items-center"
            href="/UAS_INFO2425_202410715013_IMANUEL/index.php"
        >
            <img
                src="/UAS_INFO2425_202410715013_IMANUEL/src/assets/images/logo.png"
                alt="Digital Zone"
                width="34"
                height="34"
                class="me-2"
            >

            Digital Zone
        </a>

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#nav"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/UAS_INFO2425_202410715013_IMANUEL/index.php">
                        Home
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/UAS_INFO2425_202410715013_IMANUEL/cart.php">
                        Keranjang
                    </a>
                </li>

                <?php if (isset($_SESSION['user'])): ?>

                    <?php if ($_SESSION['user']['role'] == 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/UAS_INFO2425_202410715013_IMANUEL/src/views/admin/dashboard.php">
                                Admin
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if ($_SESSION['user']['role'] == 'seller'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/UAS_INFO2425_202410715013_IMANUEL/src/views/seller/dashboard.php">
                                Seller
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if ($_SESSION['user']['role'] == 'buyer'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/UAS_INFO2425_202410715013_IMANUEL/src/views/buyer/dashboard.php">
                                Buyer
                            </a>
                        </li>
                    <?php endif; ?>

                <?php endif; ?>
            </ul>

            <div class="d-flex gap-2">
                <?php if (isset($_SESSION['user'])): ?>

                    <span class="navbar-text text-white small">
                        <?= htmlspecialchars($_SESSION['user']['name']) ?>
                        (<?= $_SESSION['user']['role'] ?>)
                    </span>

                    <a
                        class="btn btn-outline-light btn-sm"
                        href="/UAS_INFO2425_202410715013_IMANUEL/logout.php"
                    >
                        Logout
                    </a>

                <?php else: ?>

                    <a
                        class="btn btn-outline-light btn-sm"
                        href="/UAS_INFO2425_202410715013_IMANUEL/login.php"
                    >
                        Login
                    </a>

                    <a
                        class="btn btn-warning btn-sm"
                        href="/UAS_INFO2425_202410715013_IMANUEL/register.php"
                    >
                        Register
                    </a>

                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>