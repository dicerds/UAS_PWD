<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login" || $_SESSION['role'] != 'admin') {
    header("location: ../auth/login.php");
    exit();
}

if (isset($_GET['hapus_user'])) {
    $id = $_GET['hapus_user'];
    mysqli_query($conn, "DELETE FROM users WHERE id = '$id'");
    header("location: users.php?view=pengguna");
    exit();
}

if (isset($_GET['hapus_reg'])) {
    $id = $_GET['hapus_reg'];
    mysqli_query($conn, "DELETE FROM registrations WHERE id = '$id'");
    header("location: users.php?view=pendaftaran");
    exit();
}

$view = isset($_GET['view']) ? $_GET['view'] : 'pengguna';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Pengguna & Pendaftaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">

</head>
<body class="bg-light">
<script src="../assets/script.js"></script>
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Kelola Data Peserta</h3>
        <a href="index.php" class="btn btn-secondary">Kembali ke Dashboard</a>
    </div>

    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link <?= $view == 'pengguna' ? 'active' : ''; ?>" href="users.php?view=pengguna">Data Pengguna</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $view == 'pendaftaran' ? 'active' : ''; ?>" href="users.php?view=pendaftaran">Data Pendaftaran Seminar</a>
        </li>
    </ul>

    <?php if ($view == 'pengguna'): ?>
        
        <div class="card shadow">
            <div class="card-header bg-white">
                <h5 class="mb-0">Daftar Akun Pengguna</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Nama Lengkap</th>
                                <th>Email</th>
                                <th>No. HP</th>
                                <th>Role</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $query_user = mysqli_query($conn, "SELECT * FROM users ORDER BY name ASC");
                            $no = 1;
                            while ($u = mysqli_fetch_assoc($query_user)): 
                            ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $u['name']; ?></td>
                                    <td><?= $u['email']; ?></td>
                                    <td><?= $u['phone']; ?></td>
                                    <td>
                                        <span class="badge <?= $u['role'] == 'admin' ? 'bg-danger' : 'bg-info'; ?>">
                                            <?= ucfirst($u['role']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if($u['role'] != 'admin'): ?>
                                            <a href="users.php?hapus_user=<?= $u['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus pengguna ini beserta seluruh data pendaftarannya?');">
                                                <i class="fas fa-trash"></i> Hapus
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    <?php elseif ($view == 'pendaftaran'): ?>
        
        <?php
        $seminar_id = isset($_GET['seminar_id']) ? $_GET['seminar_id'] : '';
        $sem_query = mysqli_query($conn, "SELECT * FROM seminars");

        $sql = "SELECT r.*, u.name as user_name, u.email, u.phone, s.title as seminar_title 
                FROM registrations r 
                JOIN users u ON r.user_id = u.id 
                JOIN seminars s ON r.seminar_id = s.id";

        if ($seminar_id) {
            $sql .= " WHERE r.seminar_id = '$seminar_id'";
        }
        $sql .= " ORDER BY r.registration_date DESC";
        $query_reg = mysqli_query($conn, $sql);
        ?>

        <div class="card shadow mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <input type="hidden" name="view" value="pendaftaran">
                    <div class="col-auto">
                        <select name="seminar_id" class="form-select">
                            <option value="">-- Semua Seminar --</option>
                            <?php while($s = mysqli_fetch_assoc($sem_query)): ?>
                                <option value="<?= $s['id']; ?>" <?= $seminar_id == $s['id'] ? 'selected' : ''; ?>>
                                    <?= $s['title']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                    <div class="col-auto">
                        <a href="report_pdf.php?seminar_id=<?= $seminar_id; ?>" target="_blank" class="btn btn-danger">
                            <i class="fas fa-file-pdf"></i> Export PDF
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-header bg-white">
                <h5 class="mb-0">Riwayat Pendaftaran Masuk</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Tanggal Daftar</th>
                                <th>Nama Peserta</th>
                                <th>Email</th>
                                <th>Seminar</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(mysqli_num_rows($query_reg) > 0): ?>
                                <?php $no=1; while($row = mysqli_fetch_assoc($query_reg)): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $row['registration_date']; ?></td>
                                    <td><?= $row['user_name']; ?></td>
                                    <td><?= $row['email']; ?></td>
                                    <td><?= $row['seminar_title']; ?></td>
                                    <td>
                                        <span class="badge bg-success"><?= $row['status']; ?></span>
                                    </td>
                                    <td>
                                        <a href="users.php?hapus_reg=<?= $row['id']; ?>&view=pendaftaran" class="btn btn-sm btn-danger" onclick="return confirm('Hapus data pendaftaran ini?');">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="7" class="text-center">Belum ada data pendaftaran.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    <?php endif; ?>
</div>

</body>
</html>