<?php
session_start();
require 'db.php';
require 'functions.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'user') {
    header('Location: login.php?role=user');
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = get_user_id($_SESSION['username']);
    $location = $_POST['location'];
    $water_condition = $_POST['water_condition'];
    $description = $_POST['description'];
    $evidence = ''; // Logic untuk handle upload file bukti dapat ditambahkan nanti

    // Handle file upload
    if (isset($_FILES['evidence']) && $_FILES['evidence']['error'] == 0) {
        $upload_dir = 'uploads/';
        $evidence = $upload_dir . basename($_FILES['evidence']['name']);
        if (move_uploaded_file($_FILES['evidence']['tmp_name'], $evidence)) {
            $message = 'Pengaduan berhasil dikirim.';
        } else {
            $message = 'Gagal meng-upload file.';
        }
    }

    if (create_complaint($user_id, $location, $water_condition, $description, $evidence)) {
        header('Location: dashboard_user.php');
        exit;
    } else {
        $message = 'Gagal membuat pengaduan. Silakan coba lagi.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Pengaduan Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Buat Pengaduan Baru</h2>
        <?php if ($message): ?>
            <div class="alert alert-danger"><?php echo $message; ?></div>
        <?php endif; ?>
        <form method="POST"  enctype="multipart/form-data">
            <div class="mb-3">
                <label for="location" class="form-label">Lokasi</label>
                <input type="text" class="form-control" id="location" name="location" required>
            </div>
            <div class="mb-3">
                <label for="water_condition" class="form-label">Kondisi Air</label>
                <select class="form-select" id="water_condition" name="water_condition" required>
                    <option value="bening">Bening</option>
                    <option value="keruh">Keruh</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Keterangan</label>
                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="evidence" class="form-label">Bukti Pendukung</label>
                <input type="file" class="form-control" id="evidence" name="evidence">
            </div>
            <button type="submit" class="btn btn-primary">Kirim Pengaduan</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
