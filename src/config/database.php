<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'electroshop_db';

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die('Koneksi database gagal: ' . mysqli_connect_error());
}

mysqli_set_charset($conn, 'utf8mb4');

function clean($data)
{
    return htmlspecialchars(trim($data ?? ''), ENT_QUOTES, 'UTF-8');
}

function rupiah($angka)
{
    return 'Rp ' . number_format((float)$angka, 0, ',', '.');
}

function is_login()
{
    return isset($_SESSION['user']);
}

function user_role()
{
    return $_SESSION['user']['role'] ?? 'guest';
}

function require_login()
{
    if (!is_login()) {
        header('Location: login.php');
        exit;
    }
}

function require_role($roles)
{
    require_login();

    if (!in_array(user_role(), (array)$roles)) {
        header('Location: index.php');
        exit;
    }
}

function flash($key, $msg = null)
{
    if ($msg !== null) {
        $_SESSION['flash'][$key] = $msg;
        return;
    }

    if (isset($_SESSION['flash'][$key])) {
        $m = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $m;
    }

    return null;
}

function e($data)
{
    return htmlspecialchars((string)($data ?? ''), ENT_QUOTES, 'UTF-8');
}

function app_url($path = '')
{
    $base = '/UAS_INFO2425_202410715013_IMANUEL/';
    return $base . ltrim($path, '/');
}

function redirect_to($path)
{
    header('Location: ' . app_url($path));
    exit;
}

function create_notification($conn, $user_id, $message)
{
    $stmt = mysqli_prepare($conn, "INSERT INTO notifications(user_id,message) VALUES(?,?)");
    mysqli_stmt_bind_param($stmt, 'is', $user_id, $message);
    mysqli_stmt_execute($stmt);
}

function send_email_notification($conn, $user_id, $subject, $message)
{
    // Simulasi email untuk localhost/XAMPP. Data disimpan ke tabel email_logs agar dapat didemokan.
    $stmt = mysqli_prepare($conn, "INSERT INTO email_logs(user_id,subject,message,status) VALUES(?,?,?,'sent')");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'iss', $user_id, $subject, $message);
        mysqli_stmt_execute($stmt);
    }
}

function calculate_shipping_cost($shipping_type)
{
    $costs = [
        'Reguler' => 15000,
        'Express' => 30000,
        'Same Day' => 45000,
    ];

    return $costs[$shipping_type] ?? 15000;
}

function auto_update_order_status($conn, $order_id)
{
    $order_id = (int)$order_id;
    $payment = mysqli_fetch_assoc(mysqli_query($conn, "SELECT status FROM payments WHERE order_id=$order_id ORDER BY id DESC LIMIT 1"));

    if ($payment && $payment['status'] === 'verified') {
        mysqli_query($conn, "UPDATE orders SET status='processing' WHERE id=$order_id AND status='paid_waiting_verification'");
    }
}

?>