<?php
session_start();
include '../config/koneksi.php';
include '../utils/mailer.php';

if (isset($_POST['register'])) {
    $check_col = mysqli_query($conn, "SHOW COLUMNS FROM users LIKE 'username'");
    if (mysqli_num_rows($check_col) == 0) {
        mysqli_query($conn, "ALTER TABLE users ADD COLUMN username VARCHAR(50) UNIQUE AFTER id");
    }

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = $_POST['password'];

    // Validasi No HP (Harus Angka & Minimal 10 digit)
    if (!is_numeric($phone) || strlen($phone) < 10) {
        $error_msg = "Nomor HP tidak valid! Harus angka dan minimal 10 digit.";
    }

    // Validasi Password (Minimal 6 Karakter)
    if (strlen($password) < 6) {
        $error_msg = "Password terlalu pendek! Minimal 6 karakter.";
    }

    $cek_username = mysqli_query($conn, "SELECT id FROM users WHERE username = '$username'");
    if (mysqli_num_rows($cek_username) > 0) {
        $error_msg = "Username sudah terdaftar!";
    }

    $cek_email = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email'");
    if (mysqli_num_rows($cek_email) > 0) {
        $error_msg = "Email sudah terdaftar! Silakan login.";
    }

    if (!isset($error_msg)) {
        $pass_hash = password_hash($password, PASSWORD_DEFAULT);
        $otp_code = rand(100000, 999999);
        $otp_expiry = date('Y-m-d H:i:s', strtotime('+15 minutes'));

        $sql = "INSERT INTO users (username, name, email, password, phone, role, is_active, otp_code, otp_expiry) 
                VALUES ('$username', '$name', '$email', '$pass_hash', '$phone', 'user', 0, '$otp_code', '$otp_expiry')";

        if (mysqli_query($conn, $sql)) {
            $send = send_mail_otp($email, $otp_code);

            if ($send['status']) {
                $_SESSION['verify_email'] = $email;
                // If running in debug mode, store and show the OTP so developer can proceed without real email
                if (defined('MAIL_DEBUG_MODE') && MAIL_DEBUG_MODE && isset($send['debug_otp'])) {
                    $_SESSION['debug_otp'] = $send['debug_otp'];
                    if (isset($send['activation_link'])) {
                        $_SESSION['debug_activation_link'] = $send['activation_link'];
                    }
                    $otp_msg = $send['debug_otp'];
                    echo "<script>alert('Registrasi Berhasil! Kode OTP (DEBUG): $otp_msg \nGunakan kode ini untuk verifikasi pada halaman berikut.'); window.location='activate.php';</script>";
                    exit();
                } else {
                    echo "<script>alert('Registrasi Berhasil! Kode OTP telah dikirim ke email Anda.'); window.location='activate.php';</script>";
                    exit();
                }
            } else {
                // mysqli_query($conn, "DELETE FROM users WHERE email = '$email'"); // Rollback disabled for debugging
                $debug_err = isset($send['message']) ? $send['message'] : 'Unknown error';
                echo "<script>alert('Data tersimpan, tapi Gagal kirim email! Error: $debug_err. Hubungi Admin.'); window.location='login.php';</script>";
                $error_msg = "Gagal kirim email. Data user TETAP DISIMPAN untuk investigasi.";
            }
        } else {
            $error_msg = "Database Error: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Baru - Seminar App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">
</head>

<body class="auth-body">
    <script src="../assets/script.js"></script>
    <div class="auth-card" style="max-width: 900px;">
        <div class="auth-image" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
            <div>
                <img src="https://cdn-icons-png.flaticon.com/512/745/745205.png" alt="Register Illustration"
                    class="mb-3">
                <h3>Bergabunglah Bersama Kami!</h3>
                <p>Dapatkan ilmu baru dan sertifikat resmi dari seminar pilihan.</p>
            </div>
        </div>

        <div class="auth-form">
            <h2 class="fw-bold text-success mb-4"><i class="fas fa-user-plus"></i> Daftar Peserta</h2>

            <?php if (isset($error_msg)): ?>
                <div class="alert alert-danger"><?= $error_msg; ?></div>
           <?php endif; ?>

            <form action="" method=" POST" id="registerForm">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Username <span class="text-danger">*</span></label>
                        <input type="text" name="username" id="username" class="form-control" required
                            placeholder="username">
                        <div id="usernameMsg"></div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required placeholder="Nama Anda">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" id="email" class="form-control" required
                        placeholder="emailmu@gmail.com">
                    <div id="emailMsg"></div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nomor HP <span class="text-danger">*</span></label>
                    <input type="text" name="phone" class="form-control" required placeholder="0812xxxx"
                        pattern="[0-9]+" minlength="10" title="Minimal 10 digit angka">
                </div>

                <div class="mb-3">
                    <label class="form-label">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control" required
                        placeholder="Minimal 6 karakter" minlength="6">
                </div>

                <button type="submit" name="register" class="btn btn-success w-100 py-2">
                    <i class="fas fa-paper-plane"></i> Daftar Sekarang
                </button>
                </form>

                <div class="text-center mt-3">
                    <small class="text-muted">
                        Sudah punya akun? <a href="login.php" class="fw-bold text-success">Login disini</a> <br>
                        <a href="../index.php" class="text-muted">Kembali ke Home</a>
                    </small>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>