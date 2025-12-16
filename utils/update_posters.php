<?php
include __DIR__ . '/../config/koneksi.php';


$sql_ai = "UPDATE seminars SET image = 'poster_ai.png' WHERE title LIKE '%Artificial%' OR title LIKE '%AI%' OR title LIKE '%Robot%'";
if (mysqli_query($conn, $sql_ai)) {
    echo "Updated AI Poster. Rows affected: " . mysqli_affected_rows($conn) . "\n";
} else {
    echo "Error updating AI: " . mysqli_error($conn) . "\n";
}


$sql_cyber = "UPDATE seminars SET image = 'poster_cyber.png' WHERE title LIKE '%Cyber%' OR title LIKE '%Security%' OR title LIKE '%Hacking%'";
if (mysqli_query($conn, $sql_cyber)) {
    echo "Updated Cyber Poster. Rows affected: " . mysqli_affected_rows($conn) . "\n";
} else {
    echo "Error updating Cyber: " . mysqli_error($conn) . "\n";
}
?>