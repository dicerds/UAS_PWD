<?php
require_once __DIR__ . '/../config/koneksi.php';

$res = mysqli_query($conn, "SHOW COLUMNS FROM users");
if (!$res) {
    echo "Error: " . mysqli_error($conn) . "\n";
    exit(1);
}

echo "Columns in users table:\n";
while ($row = mysqli_fetch_assoc($res)) {
    echo "- {$row['Field']} ({$row['Type']})" . ($row['Null'] == 'NO' ? ' NOT NULL' : '') . "\n";
}

// show first 5 rows
echo "\nSample rows:\n";
$res2 = mysqli_query($conn, "SELECT id, username, name, email, otp_code, otp_expiry FROM users LIMIT 5");
if ($res2) {
    while ($r = mysqli_fetch_assoc($res2)) {
        print_r($r);
    }
} else {
    echo "Select failed: " . mysqli_error($conn) . "\n";
}
