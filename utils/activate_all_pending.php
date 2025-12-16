<?php
require_once __DIR__ . '/../config/koneksi.php';

// Find inactive users
$res = mysqli_query($conn, "SELECT email FROM users WHERE is_active = 0");
if (!$res) {
    echo "Query failed: " . mysqli_error($conn) . "\n";
    exit(1);
}

$emails = [];
while ($r = mysqli_fetch_assoc($res)) {
    $emails[] = $r['email'];
}

$count = count($emails);
echo "Found $count inactive user(s).\n";
if ($count === 0) exit(0);

// Activate them
$escaped = array_map(function($e) use ($conn) {
    return "'" . mysqli_real_escape_string($conn, $e) . "'";
}, $emails);
$in = implode(',', $escaped);

$update_sql = "UPDATE users SET is_active = 1, otp_code = NULL, otp_expiry = NULL WHERE email IN ($in)";
if (mysqli_query($conn, $update_sql)) {
    echo "Activated: " . mysqli_affected_rows($conn) . " user(s).\n\n";
    echo "Activated emails:\n";
    foreach ($emails as $em) {
        echo "- $em\n";
    }
} else {
    echo "Activation failed: " . mysqli_error($conn) . "\n";
    exit(1);
}

exit(0);
