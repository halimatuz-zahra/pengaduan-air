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

if ($complaint['complaint_id'] != $complaint_id) {
    header('Location: dashboard_user.php');
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $location = $_POST['location'];
    $water_condition = $_POST['water_condition'];
    $description = $_POST['description'];
    $evidence = ''; // Logic untuk handle upload file bukti dapat ditambahkan nanti

    $evidence = $complaint['evidence']; // Default evidence ke value yang lama
    if (isset($_FILES['evidence']) && $_FILES['evidence']['error'] == 0) {
        $upload_dir = 'uploads/';
        $evidence = $upload_dir . basename($_FILES['evidence']['name']);
        move_uploaded_file($_FILES['evidence']['tmp_name'], $evidence);
    } else {
        $evidence = $complaint['evidence']; // Tetap gunakan bukti lama jika tidak ada upload baru
    }
    
    if (update_complaint($complaint_id, $location, $water_condition, $description, $evidence)) {
        header('Location: dashboard_user.php');
        exit;
    } else {
        $message = 'Gagal mengupdate pengaduan. Silakan coba lagi.';
    }
    
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pengaduan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Pengaduan</h2>
        <?php if ($message): ?>
            <div class="alert alert-danger"><?php echo $message; ?></div>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="location" class="form-label">Lokasi</label>
                <input type="text" class="form-control" id="location" name="location" value="<?php echo htmlspecialchars($complaint['location']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="water_condition" class="form-label">Kondisi Air</label>
                <select class="form-select" id="water_condition" name="water_condition" required>
                    <option value="bening" <?php echo $complaint['water_condition'] == 'bening' ? 'selected' : ''; ?>>Bening</option>
                    <option value="keruh" <?php echo $complaint['water_condition'] == 'keruh' ? 'selected' : ''; ?>>Keruh</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Keterangan</label>
                <textarea class="form-control" id="description" name="description" rows="3" required><?php echo htmlspecialchars($complaint['description']); ?></textarea>
            </div>
            <div class="mb-3">
        <label for="evidence" class="form-label">Bukti Pendukung</label>
        <?php if (!empty($complaint['evidence'])): ?>
            <br><img src="<?php echo htmlspecialchars($complaint['evidence']); ?>" alt="Bukti Pengaduan" style="max-width: 100%; height: auto;">
        <?php endif; ?>
        <input type="file" class="form-control" id="evidence" name="evidence">
    </div>
            <button type="submit" class="btn btn-primary">Update Pengaduan</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
