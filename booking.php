<?php
session_start();
include 'config/koneksi.php';

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM seminars WHERE id = '$id'");
$row = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Seminar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css"> 
</head>
<body class="bg-light">

    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                    <img src="<?= $row['image'] ? 'uploads/'.$row['image'] : 'https://via.placeholder.com/800x400'; ?>" class="card-img-top" alt="Banner" style="height: 350px; object-fit: cover;">
                    
                    <div class="card-body p-5">
                        <span class="badge bg-primary mb-2">Seminar</span>
                        <h2 class="fw-bold mb-3"><?= $row['title']; ?></h2>
                        
                        <div class="d-flex gap-4 text-muted mb-4">
                            <div><i class="far fa-calendar"></i> <?= $row['date_event']; ?></div>
                            <div><i class="far fa-clock"></i> <?= $row['time_event']; ?></div>
                            <div><i class="fas fa-map-marker-alt"></i> <?= $row['location']; ?></div>
                        </div>

                        <hr>
                        <h5 class="fw-bold">Deskripsi Acara</h5>
                        <p class="text-secondary" style="line-height: 1.8;"><?= nl2br($row['description']); ?></p>
                        
                        <hr class="my-4">
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted d-block">Harga Tiket</small>
                                <h3 class="text-primary fw-bold"><?= ($row['price'] == 0) ? 'GRATIS' : 'Rp '.number_format($row['price']); ?></h3>
                            </div>

                            <?php if (isset($_SESSION['status']) && $_SESSION['status'] == "login"): ?>
                                <a href="booking.php?id=<?= $row['id']; ?>" class="btn btn-success btn-lg px-5 rounded-pill shadow">Daftar Sekarang</a>
                            <?php else: ?>
                                <a href="auth/login.php" class="btn btn-secondary btn-lg px-5 rounded-pill">Masuk untuk Daftar</a>
                            <?php endif; ?>
                        </div>

                        <div class="mt-4 text-center">
                            <a href="index.php" class="text-decoration-none text-muted">&larr; Kembali ke Daftar Seminar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>