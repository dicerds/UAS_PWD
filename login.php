<?php
// Redirect wrapper to the real login page inside /auth
header('Location: auth/login.php');
exit;