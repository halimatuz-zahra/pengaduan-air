<?php
session_start();
require 'db.php';
require 'functions.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php?role=admin');
    exit;
}

$username = $_SESSION['username'];
$complaints = get_all_complaints();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $complaint_id = $_POST['complaint_id'];
    $status = $_POST['status'];
    $response = $_POST['response'];
    $evidence = ''; // Logic untuk handle upload file bukti dapat ditambahkan nanti

    if (isset($_FILES['evidence']) && $_FILES['evidence']['error'] == 0) {
        $upload_dir = 'uploads/';
        $evidence = $upload_dir . basename($_FILES['evidence']['name']);
        move_uploaded_file($_FILES['evidence']['tmp_name'], $evidence);
    }

    if (create_response($complaint_id, $username, $status, $response, $evidence)) {
        header('Location: dashboard_admin.php');
        exit;
    } else {
        $message = 'Gagal memberikan tanggapan. Silakan coba lagi.';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"> <!-- Font Awesome for icons -->
    <style>
        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }
        .sidebar {
            height: 100%;
            background-color: #343a40;
            padding: 20px;
            position: fixed;
        }
        .sidebar .nav-link {
            color: #fff;
        }
        .sidebar .nav-link.active {
            background-color: #495057;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">Dashboard</span>
            <div class="d-flex">
                <span class="navbar-text me-3"><?php echo $username; ?></span>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </nav>
    <div class="d-flex">
        <div class="sidebar">
            <nav class="nav flex-column">
                    <a class="nav-link active" aria-current="page" href="dashboard_admin.php"><i class="fas fa-exclamation-circle me-2"></i>Balasan</a></li>
                    <a class="nav-link" href="profile.php"><i class="fas fa-user me-2"></i>Profil Pengguna</a>
            </nav>
        </div>
        <div class="content">
            <h2>Balasan</h2>
            <?php if (!empty($message)): ?>
                <div class="alert alert-danger"><?php echo $message; ?></div>
            <?php endif; ?>
            <?php if (count($complaints) > 0): ?>
                <ul class="list-group">
                    <?php foreach ($complaints as $complaint): ?>
                        <li class="list-group-item">
                            <h5><?php echo htmlspecialchars($complaint['location']); ?></h5>
                            <p><?php echo htmlspecialchars($complaint['description']); ?></p>
                            <small>Kondisi: <?php echo htmlspecialchars($complaint['water_condition']); ?></small><br>
                            <small>Dibuat pada: <?php echo htmlspecialchars($complaint['created_at']); ?></small>
                            <form method="POST" class="mt-3">
                                <input type="hidden" name="complaint_id" value="<?php echo $complaint['complaint_id']; ?>">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="belum ditanggapi">Belum ditanggapi</option>
                                        <option value="dalam proses">Dalam proses</option>
                                        <option value="sudah selesai">Sudah selesai</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="response" class="form-label">Tanggapan</label>
                                    <textarea class="form-control" id="response" name="response" rows="3" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="evidence" class="form-label">Bukti Pendukung</label>
                                    <input type="file" class="form-control" id="evidence" name="evidence">
                                </div>
                                <button type="submit" class="btn btn-primary">Kirim Tanggapan</button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Tidak ada pengaduan.</p>
            <?php endif; ?>
        </div>
    </div>
    <!-- footer -->
    <footer class="mt-auto bg-dark p-3 text-center" style="color: white; font-weight: bold;">
        <p>Web Pengaduan Air &copy; 2024</p>
    </footer>
    <!-- akhir footer -->
</body>
</html>
