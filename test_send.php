<?php
// test_send.php
// Tool untuk mengetes kirim email ke alamat bebas
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config/email_config.php';
require_once 'libraries/PHPMailer/src/Exception.php';
require_once 'libraries/PHPMailer/src/PHPMailer.php';
require_once 'libraries/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$msg = "";
if (isset($_POST['kirim'])) {
    $target_email = $_POST['email'];
    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug = 2; // Verbose debug
        $mail->Debugoutput = 'html';

        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = SMTP_PORT;

        $mail->setFrom(SMTP_FROM_EMAIL, 'Test Sistem');
        $mail->addAddress($target_email);

        $mail->isHTML(true);
        $mail->Subject = 'Test Kirim Email ke ' . $target_email;
        $mail->Body = 'Halo! Jika email ini masuk, berarti sistem BERHASIL mengirim ke alamat ini.';

        $mail->send();
        $msg = "<div class='alert alert-success'>✅ Sukses! Email terkirim ke $target_email. <br>Cek Inbox/Spam.</div>";
    } catch (Exception $e) {
        $msg = "<div class='alert alert-danger'>❌ Gagal! Error: " . $mail->ErrorInfo . "</div>";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Test Kirim Email Bebas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-5">
    <div class="card shadow" style="max-width: 600px; margin: auto;">
        <div class="card-header bg-primary text-white">Manual Email Tester</div>
        <div class="card-body">
            <?= $msg; ?>
            <form method="POST">
                <div class="mb-3">
                    <label>Masukkan Email Tujuan (Beda Email):</label>
                    <input type="email" name="email" class="form-control" required
                        placeholder="contoh: akun.lain@yahoo.com">
                </div>
                <button type="submit" name="kirim" class="btn btn-success w-100">Coba Kirim Sekarang</button>
            </form>
            <hr>
            <p class="small text-muted">Akan muncul "log" di bawah jika tombol ditekan. Baca bagian bawah.</p>
        </div>
    </div>
</body>

</html>