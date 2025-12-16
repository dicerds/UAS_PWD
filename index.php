<?php
// 1. Panggil jembatan koneksi
include 'config/koneksi.php';

// 2. Perintah SQL untuk ambil data seminar
$query = "SELECT * FROM seminars ORDER BY date_event ASC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pendaftaran Seminar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg bg-white shadow-sm">
  <div class="container">
    <a class="navbar-brand" href="/seminar_app/">Seminar App</a>
    <div>
      <a href="auth/login.php" class="btn btn-sm btn-login me-2">Login</a>
      <a href="auth/register.php" class="btn btn-sm btn-register">Daftar</a>
    </div>
  </div>
</nav>

<div class="container mt-5">
    <h1 class="mb-4">Daftar Seminar Tersedia</h1>

    <div class="row g-4">
    <?php 
    // 3. Looping data (Munculkan semua seminar yang ada di DB)
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_assoc($result)){
            ?>
            <div class="col-md-6">
                <div class="seminar-card">
                    <div class="card-body">
                        <h4 class="card-title"><?= htmlspecialchars($row['title']); ?></h4>
                        <div class="card-info mb-2"><strong>Tanggal:</strong>&nbsp; <?= htmlspecialchars($row['date_event']); ?> &nbsp;|&nbsp; <strong>Lokasi:</strong>&nbsp; <?= htmlspecialchars($row['location']); ?></div>
                        <p class="mb-2 text-muted"><?= nl2br(htmlspecialchars($row['description'])); ?></p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">Kuota: <?= intval($row['quota']); ?> orang</small>
                            <a href="detail_seminar.php?id=<?= intval($row['id']); ?>" class="btn btn-detail">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    } else {
        echo "<div class='alert alert-info'>Belum ada seminar yang tersedia.</div>";
    }
    ?>
    </div>

</div>

<footer class="mt-5 py-4 bg-white text-center">
    <div class="container">
        <small class="text-muted">&copy; <?= date('Y'); ?> Seminar App</small>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>