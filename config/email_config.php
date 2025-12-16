<?php
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'dummyseminar25@gmail.com');
define('SMTP_PASSWORD', 'evmsegiadbizgmxb');
define('SMTP_FROM_EMAIL', 'dummyseminar25@gmail.com');
define('SMTP_FROM_NAME', 'Panitia Seminar');

// Allow safe re-definition when included multiple times
if (!defined('MAIL_DEBUG_MODE')) {
    define('MAIL_DEBUG_MODE', false); // DISABLED: enable only for local debugging when needed
}
?>