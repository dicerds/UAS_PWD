<?php
header('Content-Type: application/json');
include '../config/koneksi.php';

$check_col = mysqli_query($conn, "SHOW COLUMNS FROM users LIKE 'username'");
if (mysqli_num_rows($check_col) == 0) {
    mysqli_query($conn, "ALTER TABLE users ADD COLUMN username VARCHAR(50) UNIQUE AFTER id");
}

$type = isset($_GET['type']) ? $_GET['type'] : '';
$value = isset($_GET['value']) ? mysqli_real_escape_string($conn, $_GET['value']) : '';

if (empty($type) || empty($value)) {
    echo json_encode(['exists' => false]);
    exit();
}

$exists = false;

if ($type === 'username') {
    $query = mysqli_query($conn, "SELECT id FROM users WHERE username = '$value'");
    $exists = mysqli_num_rows($query) > 0;
} elseif ($type === 'email') {
    $query = mysqli_query($conn, "SELECT id FROM users WHERE email = '$value'");
    $exists = mysqli_num_rows($query) > 0;
}

echo json_encode(['exists' => $exists]);
?>