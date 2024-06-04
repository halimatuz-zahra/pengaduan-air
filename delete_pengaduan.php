<?php
session_start();
require 'db.php';
require 'functions.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'user') {
    header('Location: login.php?role=user');
    exit;
}

$user_id = get_user_id($_SESSION['username']);
$complaint_id = $_GET['id'];
$complaint = get_complaint_by_id($complaint_id);

if ($complaint['user_id'] != $user_id) {
    header('Location: dashboard_user.php');
    exit;
}

if (delete_complaint($complaint_id)) {
    header('Location: dashboard_user.php');
    exit;
} else {
    $message = 'Gagal menghapus pengaduan. Silakan coba lagi.';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hapus Pengaduan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Hapus Pengaduan</h2>
        <?php if ($message): ?>
            <div class="alert alert-danger"><?php echo $message; ?></div>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
