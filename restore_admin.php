<?php
include 'config/koneksi.php';

$email = 'admin@seminar.com';
$password_plain = 'admin';
$password_hash = password_hash($password_plain, PASSWORD_DEFAULT);
$name = 'Administrator';
$phone = '08123456789';

// Check if admin exists
$check = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email'");
if (mysqli_num_rows($check) == 0) {
    $sql = "INSERT INTO users (name, email, password, phone, role, is_active) 
            VALUES ('$name', '$email', '$password_hash', '$phone', 'admin', 1)";

    if (mysqli_query($conn, $sql)) {
        echo "Admin user restored successfully!<br>";
        echo "Email: $email<br>";
        echo "Password: $password_plain<br>";
    } else {
        echo "Error restoring admin: " . mysqli_error($conn);
    }
} else {
    echo "Admin user already exists. Updating password...<br>";
    $update = mysqli_query($conn, "UPDATE users SET password = '$password_hash', role='admin', is_active=1 WHERE email = '$email'");
    if ($update) {
        echo "Admin password reset to: $password_plain";
    } else {
        echo "Error updating admin: " . mysqli_error($conn);
    }
}
?>