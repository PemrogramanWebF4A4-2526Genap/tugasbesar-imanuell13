<?php require_once 'src/config/database.php'; ?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Digital Zone</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="src/assets/css/style.css">
</head>
<body>

<?php include 'src/views/public/navbar.php'; ?>

<main>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card p-4">
                <h3>Login</h3>

                <?php if ($m = flash('error')): ?>
                    <div class="alert alert-danger">
                        <?= $m ?>
                    </div>
                <?php endif; ?>

                <?php if ($m = flash('success')): ?>
                    <div class="alert alert-success">
                        <?= $m ?>
                    </div>
                <?php endif; ?>

                <form method="post" action="src/controllers/AuthController.php">
                    <input
                        class="form-control mb-3"
                        type="email"
                        name="email"
                        placeholder="Email"
                        required
                    >

                    <input
                        class="form-control mb-3"
                        type="password"
                        name="password"
                        placeholder="Password"
                        required
                    >

                    <div class="form-check mb-3">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            name="remember"
                        >

                        <label class="form-check-label">
                            Remember me
                        </label>
                    </div>

                    <button class="btn btn-primary w-100" name="login">
                        Login
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
</main>

<?php include 'src/views/public/footer.php'; ?>

</body>
</html>