<?php
session_start();
include '../config/koneksi.php';

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $cek_user = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    $user = mysqli_fetch_assoc($cek_user);

    if ($user) {
        if (password_verify($password, $user['password'])) {
            if ($user['is_active'] == 1) {
                $_SESSION['id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['status'] = "login";

                if ($user['role'] == 'admin') {
                    header("location: ../admin/index.php");
                } else {
                    header("location: ../users/dashboard.php");
                }
                exit();
            } else {
                $error_msg = "Akun belum diaktivasi! <a href='activate.php' class='alert-link'>Verifikasi Sekarang</a>";
                $_SESSION['verify_email'] = $email;
            }
        } else {
            $error_msg = "Email atau Password Salah!";
        }
    } else {
        $error_msg = "Email atau Password Salah!";
    }
}

if (isset($_SESSION['status']) && $_SESSION['status'] == "login") {
    header("location: ../users/dashboard.php");
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Seminar App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">

</head>

<body class="auth-body">
    <script src="assets/script.js"></script>
    <div class="auth-card">
        <div class="auth-image">
            <div>
                <img src="https://cdn-icons-png.flaticon.com/512/9187/9187604.png" alt="Login Illustration"
                    class="mb-3">
                <h3>Selamat Datang!</h3>
                <p>Akses ribuan materi seminar eksklusif dan tingkatkan karirmu.</p>
            </div>
        </div>

        <div class="auth-form">
            <div class="mb-4">
                <h2 class="fw-bold text-primary"><i class="fas fa-sign-in-alt"></i> Login</h2>
                <p class="text-muted">Masuk ke akun Anda</p>
            </div>

            <?php if (isset($error_msg)): ?>
                <div class="alert alert-danger py-2">
                    <i class='fas fa-exclamation-circle'></i> <?= $error_msg; ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['pesan'])): ?>
                <div class="alert alert-info py-2">
                    <?php
                    if ($_GET['pesan'] == "gagal") {
                        echo "Email atau Password Salah!";
                    } else if ($_GET['pesan'] == "belum_aktif") {
                        echo "Akun belum diaktivasi! Cek email dulu.";
                    } else if ($_GET['pesan'] == "sukses_aktif") {
                        echo "Akun aktif! Silakan login.";
                    } else if ($_GET['pesan'] == "logout") {
                        echo "Anda berhasil logout.";
                    }
                    ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required placeholder="email@contoh.com">
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required placeholder="******">
                </div>
                <button type="submit" name="login" class="btn btn-primary w-100 py-2">
                    Masuk
                </button>
            </form>

            <div class="text-center mt-3">
                <small class="text-muted">
                    Belum punya akun? <a href="register.php" class="fw-bold text-primary">Daftar sekarang</a> <br>
                    <a href="../index.php" class="text-muted">Kembali ke Home</a>
                </small>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>