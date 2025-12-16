<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login" || $_SESSION['role'] != 'admin') {
    header("location: ../auth/login.php");
    exit();
}

$res_seminar = mysqli_query($conn, "SELECT COUNT(*) as total FROM seminars");
$row_seminar = mysqli_fetch_assoc($res_seminar);

$res_peserta = mysqli_query($conn, "SELECT COUNT(*) as total FROM registrations");
$row_peserta = mysqli_fetch_assoc($res_peserta);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="bg-light">
    <script src="../assets/script.js"></script>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#"><i class="fas fa-user-shield"></i> Admin Panel</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="../auth/logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Dashboard Admin</h1>
        <p class="text-muted">Kelola data seminar dan peserta dari sini.</p>

        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card text-white bg-info mb-3 shadow">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title">Seminars</h5>
                                <h2 class="mb-0"><?= $row_seminar['total']; ?></h2>
                            </div>
                            <i class="fas fa-calendar-alt fa-3x opacity-50"></i>
                        </div>
                        <hr>
                        <a href="seminar.php" class="text-white text-decoration-none">Kelola Seminar <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-white bg-success mb-3 shadow">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title">Pendaftaran</h5>
                                <h2 class="mb-0"><?= $row_peserta['total']; ?></h2>
                            </div>
                            <i class="fas fa-users fa-3x opacity-50"></i>
                        </div>
                        <hr>
                        <a href="users.php?view=pendaftaran" class="text-white text-decoration-none">Lihat Pendaftar <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card text-white bg-secondary mb-3 shadow">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title">Laporan</h5>
                                <p class="mb-0 small">Export Data</p>
                            </div>
                            <i class="fas fa-file-pdf fa-3x opacity-50"></i>
                        </div>
                        <hr>
                        <a href="report_pdf.php" target="_blank" class="text-white text-decoration-none">Download PDF <i class="fas fa-download"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>