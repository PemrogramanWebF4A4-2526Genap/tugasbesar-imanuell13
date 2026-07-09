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
    <h3>System Settings & Email Notification Log</h3>

    <?php if ($m = flash('success')): ?>
        <div class="alert alert-success"><?= $m ?></div>
    <?php endif; ?>

    <div class="row g-3">
        <div class="col-md-5">
            <div class="card p-3">
                <h5>Pengaturan Sistem</h5>
                <?php $settings = mysqli_query($conn, "SELECT * FROM system_settings ORDER BY setting_key"); ?>
                <form method="post" action="../../controllers/AdminController.php">
                    <?php while ($s = mysqli_fetch_assoc($settings)): ?>
                        <label class="form-label mt-2"><?= e($s['setting_key']) ?></label>
                        <input class="form-control" name="settings[<?= e($s['setting_key']) ?>]" value="<?= e($s['setting_value']) ?>">
                    <?php endwhile; ?>
                    <button class="btn btn-primary mt-3" name="update_settings">Simpan Settings</button>
                </form>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card p-3">
                <h5>Log Email Notification</h5>
                <table class="table table-sm bg-white">
                    <tr><th>User</th><th>Subject</th><th>Status</th><th>Waktu</th></tr>
                    <?php
                    $logs = mysqli_query($conn, "SELECT l.*,u.email FROM email_logs l LEFT JOIN users u ON l.user_id=u.id ORDER BY l.id DESC LIMIT 20");
                    while ($l = mysqli_fetch_assoc($logs)):
                    ?>
                        <tr>
                            <td><?= e($l['email']) ?></td>
                            <td><?= e($l['subject']) ?></td>
                            <td><span class="badge bg-success"><?= e($l['status']) ?></span></td>
                            <td><?= e($l['created_at']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../public/footer.php'; ?>