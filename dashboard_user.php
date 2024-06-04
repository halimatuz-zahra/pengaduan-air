<?php
session_start();
require 'db.php';
require 'functions.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'user') {
    header('Location: login.php?role=user');
    exit;
}

$username = $_SESSION['username'];
$user_id = get_user_id($username); // Dapatkan user_id dari username
$complaints = get_user_complaints($user_id); // Dapatkan pengaduan user

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Masyarakat</title>
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
                <a class="nav-link active" aria-current="page" href="dashboard_user.php"><i class="fas fa-exclamation-circle me-2"></i>Pengaduan</a>
                <a class="nav-link" href="responses.php"><i class="fas fa-reply me-2"></i>Balasan</a>
                <a class="nav-link" href="profile.php"><i class="fas fa-user me-2"></i>Profil Pengguna</a>
            </nav>
        </div>
        <div class="content">
            <h2>Pengaduan</h2>
            <a href="input_pengaduan.php" class="btn btn-primary mb-3">Buat Pengaduan Baru</a>
            <!-- Daftar pengaduan -->
            <?php if (count($complaints) > 0): ?>
                <ul class="list-group" id="download">
                    <?php foreach ($complaints as $index => $complaint): ?>
                        <li class="list-group-item">
                            <h5><?php echo htmlspecialchars($complaint['location']); ?></h5>
                            <p><?php echo htmlspecialchars($complaint['description']); ?></p>
                            <small>Kondisi: <?php echo htmlspecialchars($complaint['water_condition']); ?></small><br>
                            <small>Dibuat pada: <?php echo htmlspecialchars($complaint['created_at']); ?></small><br>
                            <?php if (!empty($complaint['evidence'])): ?>
                                <img src="<?php echo htmlspecialchars($complaint['evidence']); ?>" alt="Bukti Pengaduan" style="max-width: 100%; height: auto;">
                            <?php endif; ?>
                        </li>
                        <li class="list-group-item">
                            <a href="edit_pengaduan.php?id=<?php echo $complaint['complaint_id']?>" class="btn btn-warning">Edit</a>
                            <a href="delete_pengaduan.php?id=<?php echo $complaint['complaint_id']?>" class="btn btn-danger">Hapus</a>
                            <a href="download.php?url=<?php echo $complaint['evidence']?>" class="btn btn-primary mb-3">Download</a>
                            <!-- <button id="downloadBtn" class="btn btn-warning">Download Struk</button> -->
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
    <script>
        document.querySelectorAll('#downloadBtn').forEach(function(button) {
            button.addEventListener('click', function() {
                var index = this.getAttribute('data-index');
                var element = document.querySelectorAll('.list-group-item')[index * 2]; // Adjust index for the complaint item
                html2pdf().from(element).save();
            });
        });
    </script>
</body>
</html>
