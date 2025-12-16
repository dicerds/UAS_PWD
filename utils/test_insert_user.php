<?php
require_once __DIR__ . '/../config/koneksi.php';

$email = 'testuser+' . time() . '@example.com';
$name = 'Test User';
$pass = password_hash('password123', PASSWORD_DEFAULT);

$sql = "INSERT INTO users (name, email, password, role, is_active) VALUES ('" . mysqli_real_escape_string($conn, $name) . "', '" . mysqli_real_escape_string($conn, $email) . "', '" . mysqli_real_escape_string($conn, $pass) . "', 'user', 0)";

if (mysqli_query($conn, $sql)) {
    echo "Insert succeeded. New ID: " . mysqli_insert_id($conn) . "\n";
} else {
    echo "Insert FAILED: " . mysqli_error($conn) . "\n";
}

// show count
$r = mysqli_query($conn, "SELECT COUNT(*) AS c FROM users");
$c = mysqli_fetch_assoc($r)['c'];
echo "Total users: $c\n";
