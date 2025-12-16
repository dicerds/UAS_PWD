<?php
session_start();
$config_file = 'config/email_config.php';

if (isset($_POST['save_config'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];


    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid!";
    } else {
        $content = "<?php
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', '$email');  
define('SMTP_PASSWORD', '$password');     
define('SMTP_FROM_EMAIL', '$email'); 
define('SMTP_FROM_NAME', 'Panitia Seminar');      


define('MAIL_DEBUG_MODE', false);
?>";

        if (file_put_contents($config_file, $content)) {
            $success = "Konfigurasi berhasil disimpan! Silakan coba registrasi sekarang.";
        } else {
            $error = "Gagal menulis file config. Cek permission folder.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Setup SMTP Email</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>

<body class="auth-body">
    <div class="auth-card" style="max-width: 600px; flex-direction: column;">
        <div class="text-center mb-4">
            <h3 class="fw-bold text-primary">⚙️ Konfigurasi Email Pengirim</h3>
            <p class="text-muted">Agar sistem bisa mengirim OTP, mohon isi akun Gmail Anda.</p>
        </div>

        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?= $success; ?></div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Gmail Address</label>
                <input type="email" name="email" class="form-control" required placeholder="email.anda@gmail.com">
                <small class="text-muted">Gunakan akun Gmail yang aktif.</small>
            </div>

            <div class="mb-3">
                <label class="form-label">App Password (Bukan Password Login!)</label>
                <input type="password" name="password" class="form-control" required placeholder="xxxx xxxx xxxx xxxx">
                <small class="text-muted">
                    <a href="https://myaccount.google.com/apppasswords" target="_blank">Klik disini</a> untuk membuat
                    App Password (pilih 'Mail' dan 'Windows Computer').
                </small>
            </div>

            <button type="submit" name="save_config" class="btn btn-primary w-100 py-2">
                Simpan Konfigurasi
            </button>
        </form>

        <div class="mt-4 alert alert-info small">
            <strong>Catatan:</strong><br>
            1. Anda harus mengaktifkan <strong>2-Step Verification</strong> di Google Account.<br>
            2. Password yang dimasukkan ADALAH <strong>App Password</strong> (16 karakter), BUKAN password login biasa.
        </div>

        <div class="text-center mt-3">
            <a href="index.php">Kembali ke Home</a>
        </div>
    </div>
</body>

</html>