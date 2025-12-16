<?php

mysqli_report(MYSQLI_REPORT_OFF);

include 'config/koneksi.php';

$queries = [
    "ALTER TABLE seminars ADD COLUMN location VARCHAR(200) AFTER time_event",
    "ALTER TABLE seminars ADD COLUMN price DECIMAL(10, 2) DEFAULT 0.00 AFTER location",
    "ALTER TABLE seminars ADD COLUMN max_participants INT DEFAULT 100 AFTER price",
    "ALTER TABLE seminars ADD COLUMN image VARCHAR(255) AFTER max_participants",
    "ALTER TABLE seminars ADD COLUMN latitude DECIMAL(10, 8) DEFAULT 0 AFTER image",
    "ALTER TABLE seminars ADD COLUMN longitude DECIMAL(11, 8) DEFAULT 0 AFTER latitude"
];

foreach ($queries as $sql) {
    if (mysqli_query($conn, $sql)) {
        echo "Success: Added column\n";
    } else {
        echo "Note: " . mysqli_error($conn) . " (Query: " . substr($sql, 0, 30) . "...)\n";
    }
}
?>