<?php
// CLI test for send_mail_otp
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../config/email_config.php';
require_once __DIR__ . '/mailer.php';

// Use the configured from-address as target for a safe self-test
$target = defined('SMTP_FROM_EMAIL') ? SMTP_FROM_EMAIL : 'test@example.com';
$otp = rand(100000, 999999);

echo "Running send_mail_otp() to: $target with OTP: $otp\n";

$result = send_mail_otp($target, $otp);

echo "Result:\n";
print_r($result);

// If PHPMailer produced extra debug, try to show last ErrorInfo if present
if (is_array($result) && isset($result['message'])) {
    echo "Message: " . $result['message'] . "\n";
}

exit(0);
