<?php
session_start();
include '../config/koneksi.php';

$verify_email = isset($_SESSION['verify_email']) ? $_SESSION['verify_email'] : '';
$debug_otp = isset($_SESSION['debug_otp']) ? $_SESSION['debug_otp'] : null;

// One-click activation via link: ?email=...&otp=...
if (isset($_GET['email']) && isset($_GET['otp'])) {
    $g_email = mysqli_real_escape_string($conn, $_GET['email']);
    $g_otp = mysqli_real_escape_string($conn, $_GET['otp']);

    // Prefill email field
    $verify_email = $g_email;

    $check_otp_get = mysqli_query($conn, "SELECT * FROM users WHERE email = '$g_email' AND otp_code = '$g_otp'");
    if (mysqli_num_rows($check_otp_get) > 0) {
        $data_get = mysqli_fetch_assoc($check_otp_get);
        if (strtotime($data_get['otp_expiry']) >= time()) {
            $update_get = mysqli_query($conn, "UPDATE users SET is_active = 1, otp_code = NULL, otp_expiry = NULL WHERE email = '$g_email'");
            if ($update_get) {
                unset($_SESSION['debug_otp']);
                unset($_SESSION['last_otp_sent_time']);
                unset($_SESSION['debug_activation_link']);
                echo "<script>alert('Akun berhasil diaktifkan lewat link! Silakan login.'); window.location='login.php';</script>";
                exit();
            } else {
                $resend_error = 'Terjadi kesalahan saat mengaktifkan akun.';
            }
        } else {
            $resend_error = 'Kode OTP sudah kadaluarsa. Silakan minta kode baru.';
        }
    } else {
        $resend_error = 'Link aktivasi tidak valid atau sudah digunakan.';
    }
}

$resend_msg = '';
$resend_error = '';
$can_resend_in = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resend_otp'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $now = time();
    $cooldown = 60; // cooldown dalam detik

    if (isset($_SESSION['last_otp_sent_time']) && ($now - $_SESSION['last_otp_sent_time']) < $cooldown) {
        $can_resend_in = $cooldown - ($now - $_SESSION['last_otp_sent_time']);
        $resend_error = "Silakan tunggu $can_resend_in detik sebelum mengirim ulang kode.";
    } else {
        $check = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email'");
        if (mysqli_num_rows($check) > 0) {
            $otp_code = rand(100000, 999999);
            $otp_expiry = date('Y-m-d H:i:s', strtotime('+15 minutes'));
            $update = mysqli_query($conn, "UPDATE users SET otp_code = '$otp_code', otp_expiry = '$otp_expiry' WHERE email = '$email'");
            if ($update) {
                $send = send_mail_otp($email, $otp_code);
                if ($send['status']) {
                    $_SESSION['last_otp_sent_time'] = $now;
                    $_SESSION['debug_otp'] = isset($send['debug_otp']) ? $send['debug_otp'] : $otp_code;
                    $debug_otp = $_SESSION['debug_otp'];
                    $resend_msg = 'Kode OTP sudah dikirim ulang. Cek inbox/spam.';
                    $can_resend_in = $cooldown;
                } else {
                    $resend_error = 'Gagal mengirim email. Error: ' . ($send['message'] ?? 'Unknown');
                }
            } else {
                $resend_error = 'Gagal menyimpan OTP baru ke database.';
            }
        } else {
            $resend_error = 'Email tidak ditemukan di sistem.';
        }
    }

    $verify_email = $email;
}

if (isset($_POST['verify'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $otp = mysqli_real_escape_string($conn, $_POST['otp']);

    $check_otp = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email' AND otp_code = '$otp'");

    if (mysqli_num_rows($check_otp) > 0) {
        $data = mysqli_fetch_assoc($check_otp);

        if (strtotime($data['otp_expiry']) >= time()) {
            $update = mysqli_query($conn, "UPDATE users SET is_active = 1, otp_code = NULL, otp_expiry = NULL WHERE email = '$email'");

            if ($update) {
                unset($_SESSION['debug_otp']);
                unset($_SESSION['last_otp_sent_time']);
                unset($_SESSION['debug_activation_link']);
                echo "<script>alert('Akun berhasil diaktifkan! Silakan login.'); window.location='login.php';</script>";
            } else {
                echo "<script>alert('Terjadi kesalahan pada sistem.');</script>";
            }
        } else {
            echo "<script>alert('Kode OTP sudah kadaluarsa. Silakan daftar ulang.');</script>";
        }
    } else {
        echo "<script>alert('Kode OTP salah atau Email tidak ditemukan!');</script>";
    }
}

// Debug-only: force activation without OTP when MAIL_DEBUG_MODE is enabled
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['force_activate']) && defined('MAIL_DEBUG_MODE') && MAIL_DEBUG_MODE) {
    $email_force = mysqli_real_escape_string($conn, $_POST['email']);
    $res = mysqli_query($conn, "UPDATE users SET is_active = 1, otp_code = NULL, otp_expiry = NULL WHERE email = '$email_force'");
    if ($res) {
        unset($_SESSION['debug_otp']);
        unset($_SESSION['last_otp_sent_time']);
        unset($_SESSION['debug_activation_link']);
        echo "<script>alert('Akun berhasil diaktifkan (DEBUG)! Silakan login.'); window.location='login.php';</script>";
        exit();
    } else {
        $resend_error = 'Gagal mengaktifkan akun (DEBUG): ' . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Verifikasi OTP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">

</head>

<body class="auth-body">
    <script src="../assets/script.js"></script>
    <div class="auth-card" style="max-width: 500px; flex-direction: column;">
        <div class="text-center mb-4">
            <h3 class="fw-bold text-primary"><i class="fas fa-shield-alt"></i> Verifikasi OTP</h3>
            <p class="text-muted">Masukkan kode 6 digit yang telah dikirim ke email
                <strong><?= htmlspecialchars($verify_email); ?></strong>.</p>

            <?php if (!empty($resend_msg)): ?>
                <div class="alert alert-success text-center"><?= htmlspecialchars($resend_msg); ?></div>
            <?php endif; ?>

            <?php if (!empty($resend_error)): ?>
                <div class="alert alert-danger text-center"><?= htmlspecialchars($resend_error); ?></div>
            <?php endif; ?>

            <?php if (!empty($debug_otp)): ?>
                <div class="mb-3">
                    <div class="p-3 bg-light text-center rounded">
                        <div class="h4 mb-1 fw-bold text-primary"><?= htmlspecialchars($debug_otp); ?></div>
                        <div class="small text-muted">Kode OTP (DEBUG)</div>
                        <?php if (!empty($_SESSION['debug_activation_link'])): ?>
                            <div class="mt-2">
                                <a href="<?= htmlspecialchars($_SESSION['debug_activation_link']); ?>" class="btn btn-sm btn-success">🔗 Aktifkan Sekarang (DEBUG)</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($verify_email); ?>"
                    required placeholder="Masukkan email yang menerima OTP">
                <small class="text-muted">Masukkan alamat email yang menerima kode OTP. Jika Anda tidak menerima, cek folder <strong>Spam</strong> atau <a href="register.php">Daftar Ulang</a>.</small>
            </div>
            <div class="mb-4">
                <label class="form-label">Kode OTP</label>
                <input type="text" name="otp" id="otpInput" class="form-control text-center fw-bold text-primary" maxlength="6"
                    required placeholder="X X X X X X" style="letter-spacing: 5px; font-size: 1.5rem;" value="<?= htmlspecialchars($debug_otp ?? ''); ?>">
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <small class="text-muted">Masukkan 6 digit Kode OTP lalu tekan <strong>Verifikasi Akun</strong>.</small>
                    <?php if (!empty($debug_otp)): ?>
                        <button type="button" id="copyOtpBtn" class="btn btn-sm btn-outline-secondary">📋 Salin Kode</button>
                    <?php endif; ?>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" name="verify" class="btn btn-primary py-2">
                    <i class="fas fa-check-circle"></i> Verifikasi Akun
                </button>
            </div>
        </form>

        <div class="text-center mt-3">
            <small class="text-muted">Tidak menerima kode? Cek folder Spam atau <a href="register.php">Daftar
                    Ulang</a></small>

            <form method="POST" class="mt-3 d-flex justify-content-center align-items-center">
                <input type="hidden" name="email" value="<?= htmlspecialchars($verify_email); ?>">
                <button type="submit" name="resend_otp" id="resendBtn" class="btn btn-outline-primary">🔁 Kirim Ulang Kode OTP</button>
                <small id="resendTimer" data-remaining="<?= intval($can_resend_in); ?>" class="text-muted ms-2"></small>
            </form>

            <?php if (defined('MAIL_DEBUG_MODE') && MAIL_DEBUG_MODE): ?>
                <form method="POST" class="mt-2 text-center">
                    <input type="hidden" name="email" value="<?= htmlspecialchars($verify_email); ?>">
                    <button type="submit" name="force_activate" class="btn btn-success">⚡ Aktifkan Sekarang (Debug)</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>