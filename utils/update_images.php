<?php
include 'config/koneksi.php';

$updates = [
    "UPDATE seminars SET image = 'seminar_ai.png', description = 'Unlock the future with AI! Learn how AI is transforming design, creativity, and innovation from smart tools to cutting-edge techniques. Join us to explore the art of intelligence together.' WHERE title LIKE '%AI%' OR title LIKE '%Artificial%'",
    "UPDATE seminars SET image = 'seminar_cyber.png', description = 'Cybercrime Awareness. Think before you click. Protect your passwords. Update regularly. Beware of phishing. Join our workshop to learn how to stay safe in the digital world.' WHERE title LIKE '%Cyber%' OR title LIKE '%Security%'"
];

foreach ($updates as $sql) {
    if (mysqli_query($conn, $sql)) {
        echo "Updated: " . substr($sql, 0, 50) . "...\n";
    } else {
        echo "Error: " . mysqli_error($conn) . "\n";
    }
}
?>