<?php
// debug_email.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h3>📧 Email Debug Mailer</h3>";
echo "<p>Sedang mencoba mengirim email test...</p>";
echo "<hr>";

// Cek file
$files = [
    'config/email_config.php',
    'utils/mailer.php'
];

foreach ($files as $f) {
    if (file_exists($f)) {
        echo "✅ File ditemukan: $f<br>";
    } else {
        echo "❌ File TIDAK ditemukan: $f<br>";
        exit;
    }
}

require_once 'config/email_config.php';
// Override debug mode constant if needed (hacky but useful for testing)
// We will manually instantiate PHPMailer to control DebugOutput

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'libraries/PHPMailer/src/Exception.php';
require_once 'libraries/PHPMailer/src/PHPMailer.php';
require_once 'libraries/PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);

try {
    // Enable verbose debug output
    $mail->SMTPDebug = 2; // 2 = Client and Server messages
    $mail->Debugoutput = 'html';

    $mail->isSMTP();
    $mail->Host = SMTP_HOST;
    $mail->SMTPAuth = true;
    $mail->Username = SMTP_USERNAME;
    $mail->Password = SMTP_PASSWORD;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = SMTP_PORT;

    $mail->setFrom(SMTP_FROM_EMAIL, 'Debug Tester');
    $mail->addAddress(SMTP_FROM_EMAIL); // Kirim ke diri sendiri

    $mail->isHTML(true);
    $mail->Subject = 'Test Email Connection';
    $mail->Body = 'Jika anda melihat email ini, berarti koneksi SMTP BERHASIL!';

    $mail->send();
    echo "<hr><h2 style='color: green;'>✅ SUKSES! Email berhasil dikirim.</h2>";
    echo "Cek inbox/spam email anda: " . SMTP_FROM_EMAIL;

} catch (Exception $e) {
    echo "<hr><h2 style='color: red;'>❌ GAGAL! Error:</h2>";
    echo "Mailer Error: " . $mail->ErrorInfo;
}
?>