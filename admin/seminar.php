<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login" || $_SESSION['role'] != 'admin') {
    header("location: ../auth/login.php");
    exit();
}

if (isset($_POST['simpan'])) {
    $title = $_POST['title'];
    $date_event = $_POST['date_event'];
    $time_event = $_POST['time_event'];
    $location = $_POST['location'];
    $price = $_POST['price'];
    $max_participants = $_POST['max_participants'];

    $image = "";
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../uploads/";
        $image = time() . "_" . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir . $image);
    }

    if (!empty($_POST['id'])) {
        $id = $_POST['id'];
        $sql = "UPDATE seminars SET title='$title', date_event='$date_event', time_event='$time_event', location='$location', price='$price', max_participants='$max_participants'";
        if ($image) {
            $sql .= ", image='$image'";
        }
        $sql .= " WHERE id='$id'";
        mysqli_query($conn, $sql);
    } else {
        $query = "INSERT INTO seminars (title, date_event, time_event, location, price, max_participants, image) 
                  VALUES ('$title', '$date_event', '$time_event', '$location', '$price', '$max_participants', '$image')";
        mysqli_query($conn, $query);
    }
    header("location: seminar.php");
    exit();
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM seminars WHERE id = '$id'");
    header("location: seminar.php");
    exit();
}

$data_edit = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = mysqli_query($conn, "SELECT * FROM seminars WHERE id='$id'");
    $data_edit = mysqli_fetch_assoc($result);
}

$view = isset($_GET['view']) ? $_GET['view'] : 'table';
if (isset($_GET['edit'])) {
    $view = 'form';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Seminar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">

</head>
<body class="bg-light">
<script src="../assets/script.js"></script>
<div class="container mt-5 mb-5">
    
    <?php if ($view == 'form'): ?>
        <div class="card shadow">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><?= isset($data_edit) ? 'Edit Seminar' : 'Tambah Seminar'; ?></h5>
                <a href="seminar.php" class="btn btn-sm btn-light">Kembali</a>
            </div>
            <div class="card-body">
                <form action="seminar.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= isset($data_edit) ? $data_edit['id'] : ''; ?>">
                    
                    <div class="mb-3">
                        <label>Judul Seminar</label>
                        <input type="text" name="title" class="form-control" value="<?= isset($data_edit) ? $data_edit['title'] : ''; ?>" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Tanggal</label>
                            <input type="date" name="date_event" class="form-control" value="<?= isset($data_edit) ? $data_edit['date_event'] : ''; ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Waktu</label>
                            <input type="time" name="time_event" class="form-control" value="<?= isset($data_edit) ? $data_edit['time_event'] : ''; ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Lokasi</label>
                        <input type="text" name="location" class="form-control" value="<?= isset($data_edit) ? $data_edit['location'] : ''; ?>" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Harga (Rp)</label>
                            <input type="number" name="price" class="form-control" value="<?= isset($data_edit) ? $data_edit['price'] : ''; ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Maksimal Peserta</label>
                            <input type="number" name="max_participants" class="form-control" value="<?= isset($data_edit) ? $data_edit['max_participants'] : ''; ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Gambar Banner</label>
                        <input type="file" name="image" class="form-control">
                        <?php if (isset($data_edit) && $data_edit['image']): ?>
                            <small class="text-muted">Gambar saat ini: <?= $data_edit['image']; ?></small>
                        <?php endif; ?>
                    </div>

                    <button type="submit" name="simpan" class="btn btn-primary">Simpan Data</button>
                </form>
            </div>
        </div>

    <?php else: ?>
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Daftar Seminar</h3>
            <div>
                <a href="index.php" class="btn btn-secondary">Kembali</a>
                <a href="seminar.php?view=form" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Seminar</a>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Gambar</th>
                                <th>Judul</th>
                                <th>Waktu</th>
                                <th>Lokasi</th>
                                <th>Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $query = mysqli_query($conn, "SELECT * FROM seminars ORDER BY date_event DESC");
                            $no = 1;
                            while ($row = mysqli_fetch_assoc($query)): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td>
                                        <?php if($row['image']): ?>
                                            <img src="../uploads/<?= $row['image']; ?>" width="50">
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $row['title']; ?></td>
                                    <td><?= $row['date_event']; ?><br><small><?= $row['time_event']; ?></small></td>
                                    <td><?= $row['location']; ?></td>
                                    <td>Rp <?= number_format($row['price']); ?></td>
                                    <td>
                                        <a href="seminar.php?edit=<?= $row['id']; ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                        <a href="seminar.php?delete=<?= $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah anda yakin?');"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>

</div>

</body>
</html>