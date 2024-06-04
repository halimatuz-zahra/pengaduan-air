<?php
require 'db.php';
require 'functions.php';

$role = $_GET['role'];
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if (validate_user($username, $password, $role)) {
        session_start();
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;

        if ($role == 'user') {
            header('Location: dashboard_user.php');
        } else {
            header('Location: dashboard_admin.php');
        }
        exit;
    } else {
        $message = 'Invalid username or password!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: <?php echo $role == 'user' ? '#f8f9fa' : '#343a40'; ?>;
            color: <?php echo $role == 'user' ? '#000' : '#fff'; ?>;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Login - <?php echo ucfirst($role); ?></h2>
        <?php if ($message): ?>
            <div class="alert alert-danger"><?php echo $message; ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
            <?php if ($role == 'user'): ?>
                <a href="register.php?role=user" class="btn btn-link">Register</a>
            <?php else: ?>
                <a href="register.php?role=admin" class="btn btn-link">Register</a>
                <!-- <p class="mt-3">Please contact your manager to get your username and password.</p> -->
            <?php endif; ?>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
