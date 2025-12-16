<?php
include 'config/koneksi.php';

$table = 'seminars';
$result = mysqli_query($conn, "SHOW COLUMNS FROM `$table`");

if (!$result) {
    echo "Error: " . mysqli_error($conn) . "\n";
    exit;
}

echo "Columns in '$table':\n";
while ($row = mysqli_fetch_assoc($result)) {
    echo "- " . $row['Field'] . " (" . $row['Type'] . ")\n";
}
?>