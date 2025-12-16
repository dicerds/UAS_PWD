<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location: ../auth/login.php");
    exit();
}

$id = $_SESSION['id'];
$query = mysqli_query($conn, "SELECT * FROM users WHERE id = '$id'");
$user = mysqli_fetch_assoc($query);

if (isset($_POST['update'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = $_POST['password'];

    $image = $user['profile_pic'];
    if ($_FILES['profile_pic']['name']) {
        $target_dir = "../uploads/profile/";
        $target_file = $target_dir . basename($_FILES["profile_pic"]["name"]);
        move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file);
        $image = $_FILES["profile_pic"]["name"];
    }

    $sql = "UPDATE users SET name='$name', phone='$phone', profile_pic='$image'";
    
    if (!empty($password)) {
        $pass_hash = password_hash($password, PASSWORD_DEFAULT);
        $sql .= ", password='$pass_hash'";
    }
    
    $sql .= " WHERE id='$id'";
    mysqli_query($conn, $sql);
    
    $_SESSION['name'] = $name;
    echo "<script>alert('Profil berhasil diupdate!'); window.location='profile.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">

</head>
<body class="bg-light">
    <script src="../assets/script.js"></script>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between">
                        <h5 class="mb-0">Edit Profil Saya</h5>
                        <a href="dashboard.php" class="btn btn-sm btn-light text-primary">Kembali</a>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <?php 
                                $pic = $user['profile_pic'] ? '../uploads/profile/'.$user['profile_pic'] : 'https://via.placeholder.com/100';
                            ?>
                            <img src="<?= $pic ?>" class="rounded-circle border" width="100" height="100" style="object-fit:cover;">
                        </div>

                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label>Username</label>
                                <input type="text" class="form-control" value="<?= $user['username']; ?>" disabled>
                            </div>
                            <div class="mb-3">
                                <label>Email</label>
                                <input type="email" class="form-control" value="<?= $user['email']; ?>" disabled>
                            </div>
                            <div class="mb-3">
                                <label>Nama Lengkap</label>
                                <input type="text" name="name" class="form-control" value="<?= $user['name']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label>No HP</label>
                                <input type="text" name="phone" class="form-control" value="<?= $user['phone']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label>Ganti Password (Biarkan kosong jika tidak diganti)</label>
                                <input type="password" name="password" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Ganti Foto Profil</label>
                                <input type="file" name="profile_pic" class="form-control">
                            </div>
                            <button type="submit" name="update" class="btn btn-primary w-100">Simpan Perubahan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>