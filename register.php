<?php
// Redirect wrapper to the real register page inside /auth
header('Location: auth/register.php');
exit;