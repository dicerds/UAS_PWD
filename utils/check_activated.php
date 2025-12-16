<?php
require __DIR__ . '/../config/koneksi.php';
$emails = [
    'testuser+1765871722@example.com',
    'testuser2@example.com',
    'test3@example.com'
];
foreach ($emails as $e) {
    $email = mysqli_real_escape_string($conn, $e);
    $q = mysqli_query($conn, "SELECT email,is_active FROM users WHERE email='$email'");
    if ($q) {
        $r = mysqli_fetch_assoc($q);
        if ($r) {
            echo $r['email'] . " -> is_active=" . $r['is_active'] . "\n";
        } else {
            echo $email . " -> NOT FOUND\n";
        }
    } else {
        echo "Query failed: " . mysqli_error($conn) . "\n";
    }
}
