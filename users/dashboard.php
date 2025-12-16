<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['id'];
$query_user = mysqli_query($conn, "SELECT * FROM users WHERE id = '$user_id'");
$user = mysqli_fetch_assoc($query_user);

if ($user && empty($user['profile_pic'])) {
    $user['profile_pic'] = 'default_profile.png';
}

$query_seminar = mysqli_query($conn, "SELECT * FROM seminars ORDER BY date_event ASC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Peserta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 0;
            margin-bottom: 30px;
            border-radius: 0 0 20px 20px;
        }

        .card-seminar {
            transition: transform 0.2s;
            border: none;
        }

        .card-seminar:hover {
            transform: translateY(-5px);
        }
    </style>
</head>

<body>
    <script src="../assets/script.js"></script>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php"><i class="fas fa-graduation-cap"></i> SEMINAR APP</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="dashboard.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
                    <li class="nav-item"><a class="nav-link" href="../tiket_saya.php">Tiket Saya</a></li>
                    <li class="nav-item"><a class="nav-link text-danger" href="../auth/logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="hero text-center">
        <div class="container">
            <?php
            $profile_pic = $user['profile_pic'] ?? 'default_profile.png';
            $profile_pic_path = '../uploads/profile/' . ($profile_pic ? $profile_pic : 'default_profile.png');

            if (!file_exists(dirname(__DIR__) . '/uploads/profile/' . $profile_pic)) {
                $profile_pic_path = 'https://via.placeholder.com/100?text=Profile';
            }
            ?>
            <img src="<?= $profile_pic_path; ?>" alt="Profile" class="rounded-circle border border-3 border-white mb-3"
                width="100" height="100" style="object-fit:cover;">
            <h2>Halo, <?= $user['name']; ?>!</h2>
            <p class="lead">Selamat datang di portal pendaftaran seminar.</p>
        </div>
    </div>

    <div class="container mb-5">
        <h3 class="mb-4 text-center border-bottom pb-2">Seminar Tersedia</h3>
        <div class="row">
            <?php if (mysqli_num_rows($query_seminar) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($query_seminar)): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card card-seminar h-100 shadow-sm">
                            <img src="<?= $row['image'] ? '../uploads/' . $row['image'] : 'https://via.placeholder.com/400x200?text=No+Image'; ?>"
                                class="card-img-top" alt="Seminar Image" style="height: 200px; object-fit: cover;">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?= $row['title']; ?></h5>
                                <p class="card-text text-muted small mb-2">
                                    <i class="far fa-calendar-alt"></i> <?= date('d M Y', strtotime($row['date_event'])); ?>
                                    &nbsp;
                                    <i class="far fa-clock"></i> <?= date('H:i', strtotime($row['time_event'])); ?>
                                </p>
                                <div class="mt-auto">
                                    <a href="../detail_seminar.php?id=<?= $row['id']; ?>" class="btn btn-primary w-100">Lihat
                                        Detail</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <div class="alert alert-info">Belum ada seminar.</div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>