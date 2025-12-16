<?php
session_start();
include 'config/koneksi.php';

if (!isset($_GET['id'])) {
    header("location: dashboard.php");
    exit();
}

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM seminars WHERE id = '$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "Seminar tidak ditemukan.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Detail Seminar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">


    <style>
        .hero-img {
            width: 100%;
            height: 350px;
            object-fit: cover;
        }
    </style>
</head>

<body class="bg-light">

    <script src="assets/script.js"></script>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">SEMINAR APP</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="dashboard.php">Kembali</a>
            </div>
        </div>
    </nav>

    <img src="<?= $data['image'] ? 'uploads/' . $data['image'] : 'https://via.placeholder.com/1200x400?text=Seminar+Banner'; ?>"
        class="hero-img">

    <div class="container mt-n5" style="margin-top: -50px; position: relative;">
        <div class="card shadow p-4">
            <h1 class="mb-3"><?= $data['title']; ?></h1>
            <div class="row align-items-center mb-4">
                <div class="col-md-6">
                    <p class="text-muted mb-1"><i class="far fa-calendar-alt"></i>
                        <?= date('d M Y', strtotime($data['date_event'])); ?></p>
                    <p class="text-muted mb-1"><i class="far fa-clock"></i>
                        <?= date('H:i', strtotime($data['time_event'])); ?></p>
                    <p class="text-muted mb-1"><i class="fas fa-map-marker-alt"></i> <?= $data['location']; ?></p>
                </div>
                <div class="col-md-6 text-md-end">
                    <h3 class="text-primary">Rp <?= number_format($data['price'], 0, ',', '.'); ?></h3>
                </div>
            </div>

            <hr>


            <div class="alert alert-info d-flex align-items-center" role="alert" id="geo-box">
                <i class="fas fa-route fa-2x me-3"></i>
                <div>
                    <h5>Lokasi Anda</h5>
                    <p class="mb-0" id="distance-msg">Menghitung jarak ke lokasi seminar...</p>
                </div>
            </div>

            <div class="mt-3">
                <h4>Deskripsi</h4>
                <p><?= nl2br($data['description']); ?></p>
            </div>

            <div class="mt-4 text-center">

                <?php

                if (isset($_SESSION['id'])) {
                    $uid = $_SESSION['id'];
                    $check_reg = mysqli_query($conn, "SELECT * FROM registrations WHERE user_id = '$uid' AND seminar_id = '$id'");
                    if (mysqli_num_rows($check_reg) > 0) {
                        echo '<button class="btn btn-success btn-lg" disabled>Anda Sudah Terdaftar</button>';
                    } else {
                        echo '<a href="booking.php?id=' . $id . '" class="btn btn-primary btn-lg px-5">Daftar Sekarang</a>';
                    }
                } else {
                    echo '<a href="auth/login.php" class="btn btn-secondary">Login untuk Mendaftar</a>';
                }
                ?>
            </div>
        </div>
    </div>


    <script>

        const seminarLat = <?= $data['latitude'] ? $data['latitude'] : 0; ?>;
        const seminarLng = <?= $data['longitude'] ? $data['longitude'] : 0; ?>;

        function toRad(value) {
            return value * Math.PI / 180;
        }

        function calculateDistance(lat1, lon1, lat2, lon2) {
            var R = 6371;
            var dLat = toRad(lat2 - lat1);
            var dLon = toRad(lon2 - lon1);
            var lat1 = toRad(lat1);
            var lat2 = toRad(lat2);

            var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.sin(dLon / 2) * Math.sin(dLon / 2) * Math.cos(lat1) * Math.cos(lat2);
            var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            var d = R * c;
            return d.toFixed(2);
        }

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                const userLat = position.coords.latitude;
                const userLng = position.coords.longitude;

                if (seminarLat != 0 && seminarLng != 0) {
                    let dist = calculateDistance(userLat, userLng, seminarLat, seminarLng);
                    document.getElementById('distance-msg').innerHTML =
                        `Jarak dari lokasi Anda: <strong>${dist} km</strong>`;
                } else {
                    document.getElementById('distance-msg').innerHTML = "Lokasi seminar tidak terdata dengan koordinat GPS.";
                }
            }, function (error) {
                document.getElementById('distance-msg').innerHTML = "Gagal mengambil lokasi Anda (Izinkan Geolocation di browser).";
            });
        } else {
            document.getElementById('distance-msg').innerHTML = "Browser Anda tidak mendukung Geolocation.";
        }
    </script>

    <br><br>
</body>

</html>