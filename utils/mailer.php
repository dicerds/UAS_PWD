<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


require_once __DIR__ . '/../config/email_config.php';


require_once __DIR__ . '/../libraries/PHPMailer/src/Exception.php';
require_once __DIR__ . '/../libraries/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../libraries/PHPMailer/src/SMTP.php';

function send_mail_otp($to_email, $otp_code)
{
    if (MAIL_DEBUG_MODE) {
        // Provide OTP and an activation link in debug mode for convenience
        $activation_link = 'http://localhost/seminar_app/auth/activate.php?email=' . urlencode($to_email) . '&otp=' . urlencode($otp_code);
        return [
            'status' => true,
            'message' => "DEBUG MODE: Email bypassed. OTP Code is: $otp_code",
            'debug_otp' => $otp_code,
            'activation_link' => $activation_link
        ];
    }

    $mail = new PHPMailer(true);

    try {

        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = SMTP_PORT;

        $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
        $mail->addAddress($to_email);

        $mail->isHTML(true);
        $mail->Subject = 'Kode Verifikasi Akun Seminar App';

        // Build activation link (one-click activation)
        $activation_link = 'http://localhost/seminar_app/auth/activate.php?email=' . urlencode($to_email) . '&otp=' . urlencode($otp_code);

        $email_template = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #e0e0e0; border-radius: 10px; overflow: hidden;'>
            <div style='background-color: #4e54c8; padding: 20px; text-align: center; color: white;'>
                <h2 style='margin: 0;'>Seminar App</h2>
            </div>
            <div style='padding: 30px; background-color: #ffffff;'>
                <p style='font-size: 16px; color: #333;'>Halo,</p>
                <p style='font-size: 16px; color: #333;'>Terima kasih telah mendaftar. Untuk mengaktifkan akun Anda, silakan gunakan kode verifikasi (OTP) berikut ini:</p>
                
                <div style='text-align: center; margin: 30px 0;'>
                    <span style='background-color: #f3f4f6; padding: 15px 30px; font-size: 24px; font-weight: bold; letter-spacing: 5px; color: #4e54c8; border-radius: 5px; border: 1px dashed #4e54c8;'>$otp_code</span>
                </div>

                <div style='text-align: center; margin-bottom: 30px;'>
                    <a href='$activation_link' style='background-color: #28a745; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;'>Verifikasi Akun Saya</a>
                </div>

                <p style='font-size: 14px; color: #666;'>Kode ini hanya berlaku selama <strong>15 menit</strong>. Jangan berikan kode ini kepada siapapun.</p>
                <p style='font-size: 14px; color: #666;'>Jika Anda tidak merasa mendaftar, silakan abaikan email ini.</p>
                
                <hr style='border: none; border-top: 1px solid #eee; margin: 20px 0;'>
                <p style='font-size: 12px; color: #999; text-align: center;'>&copy; " . date('Y') . " Seminar App Team.</p>
            </div>
        </div>";

        $mail->Body = $email_template;

        $mail->send();
        return ['status' => true, 'message' => 'Email sent'];
    } catch (Exception $e) {
        return [
            'status' => false,
            'message' => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}",
            'exception' => $e->getMessage()
        ]; 
    }
}
?>