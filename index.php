<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pengaduan Air Bersih</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"> <!-- Font Awesome for icons -->
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }
        .hero {
            background: url('https://source.unsplash.com/1600x900/?water') no-repeat center center;
            background-size: cover;
            color: white;
            height: 70vh;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            text-shadow: 2px 2px 4px #000000;
            margin-top: 56px; /* Height of the navbar */
        }
        .hero h1 {
            font-size: 4rem;
            color: white;
            -webkit-text-stroke: 1px black; /* Outline for webkit browsers */
        }
        .hero p {
            font-size: 1.5rem;
            color: white;
            -webkit-text-stroke: 0.5px black; /* Outline for webkit browsers */
        }
        .content {
            flex: 1;
            padding: 20px;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 10px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Pengaduan Air Bersih</a>
            <div class="d-flex">
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        Login
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item" href="login.php?role=user">Masyarakat</a></li>
                        <li><a class="dropdown-item" href="login.php?role=admin">Admin</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <div class="hero">
        <div>
            <h1>Selamat Datang di Sistem Pengaduan Air Bersih</h1>
            <p>Sistem ini dibuat untuk memudahkan masyarakat dalam melaporkan kondisi air bersih.</p>
        </div>
    </div>
    <div class="content">
        
    </div>
    <footer class="footer">
        <p>Web Pengaduan Air &copy; 2024</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>