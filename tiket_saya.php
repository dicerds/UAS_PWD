<?php
session_start();
include 'config/koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location: auth/login.php");
    exit();
}

$user_id = $_SESSION['id'];
$query = mysqli_query($conn, "SELECT r.*, s.title, s.date_event, s.time_event, s.location, s.image 
                              FROM registrations r 
                              JOIN seminars s ON r.seminar_id = s.id 
                              WHERE r.user_id = '$user_id' 
                              ORDER BY r.registration_date DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tiket Saya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="bg-light">
    <script src="assets/script.js"></script>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="users/dashboard.php">SEMINAR APP</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="users/dashboard.php">Dashboard</a>
                <a class="nav-link active" href="#">Tiket Saya</a>
                <a class="nav-link text-danger" href="auth/logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <h3 class="mb-4"><i class="fas fa-ticket-alt"></i> Tiket Seminar Saya</h3>
        <div class="row">
            <?php while ($row = mysqli_fetch_assoc($query)): ?>
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="row g-0 h-100">
                            <div class="col-md-4">
                                <img src="<?= $row['image'] ? 'uploads/'.$row['image'] : 'https://via.placeholder.com/200'; ?>" class="img-fluid rounded-start h-100" style="object-fit: cover;">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title"><?= $row['title']; ?></h5>
                                    <p class="card-text mb-1"><i class="far fa-calendar"></i> <?= $row['date_event']; ?> | <?= $row['time_event']; ?></p>
                                    <p class="card-text mb-2"><i class="fas fa-map-marker-alt"></i> <?= $row['location']; ?></p>
                                    <span class="badge bg-success">Terdaftar</span>
                                    <p class="card-text mt-2"><small class="text-muted">Didaftar pada: <?= $row['registration_date']; ?></small></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
            
            <?php if(mysqli_num_rows($query) == 0): ?>
                <div class="col-12 text-center py-5">
                    <p class="text-muted">Belum ada tiket. Yuk daftar seminar dulu!</p>
                    <a href="index.php" class="btn btn-primary">Cari Seminar</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>