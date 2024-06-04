<?php
session_start();
require 'db.php';
require 'functions.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'user') {
    header('Location: login.php?role=user');
    exit;
}

$username = $_SESSION['username'];
$user_id = get_user_id($username);
$complaints = get_user_complaints($user_id);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Balasan - Masyarakat</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <!-- Font Awesome for icons -->
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
        <a class="nav-link" aria-current="page" href="dashboard_user.php"><i
            class="fas fa-exclamation-circle me-2"></i>Pengaduan</a>
        <a class="nav-link active" href="responses.php"><i class="fas fa-reply me-2"></i>Balasan</a>
        <a class="nav-link" href="profile.php"><i class="fas fa-user me-2"></i>Profil Pengguna</a>
      </nav>
    </div>
    <div class="content">
      <h2>Balasan</h2>
      <?php if (count($complaints) > 0): ?>
      <ul class="list-group">
        <?php foreach ($complaints as $complaint): 
    $formated_date_complaints = date('d F Y H:i', strtotime($complaint['created_at']));
            ?>
        <li class="list-group-item">
          <h5><?php echo htmlspecialchars($complaint['location']); ?></h5>
          <p><?php echo htmlspecialchars($complaint['description']); ?></p>
          <small>Kondisi: <?php echo htmlspecialchars($complaint['water_condition']); ?></small><br>
          <small>Status: <?php echo htmlspecialchars($complaint['status']); ?></small><br>
          <small>Dibuat pada: <?php echo htmlspecialchars($formated_date_complaints); ?></small>
          <?php
            $responses = get_complaint_responses($complaint['complaint_id']);
            if (count($responses) > 0):
                foreach ($responses as $response):
                    $formated_date_responses = date('d F Y H:i', strtotime($response['created_at']));
            ?>
          <div class="mt-3">
            <h6>Balasan oleh: <?php echo htmlspecialchars($response['admin_name']); ?></h6>
            <p><?php echo htmlspecialchars($response['description']); ?></p>
            <?php if (isset($response['status'])): ?>
            <small>Status: <?php echo htmlspecialchars($response['status']); ?></small><br>
            <?php endif; ?>
            <small>Diberikan pada: <?php echo htmlspecialchars($formated_date_responses); ?></small>
          </div>
          <?php
                                endforeach;
                            else:
                            ?>
          <p>Belum ada balasan.</p>
          <?php endif; ?>
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
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
  </script>
</body>

</html>