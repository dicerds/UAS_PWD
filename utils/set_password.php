<?php
require __DIR__ . '/../config/koneksi.php';
$email = $argv[1] ?? 'testuser2@example.com';
$password = $argv[2] ?? 'Test1234!';
$hash = password_hash($password, PASSWORD_DEFAULT);
$sql = "UPDATE users SET password='$hash' WHERE email='".mysqli_real_escape_string($conn,$email)."'";
$res = mysqli_query($conn, $sql);
if ($res) {
    echo "Password updated for $email\n";
} else {
    echo "Update failed: " . mysqli_error($conn) . "\n";
}
