<?php
$server   = "localhost";
$username = "root";
$password = "";


$conn = mysqli_connect($server, $username, $password);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


$sqlFile = 'db_schema.sql';
if (!file_exists($sqlFile)) {
    die("Error: SQL file not found.");
}

$sqlContent = file_get_contents($sqlFile);


if (mysqli_multi_query($conn, $sqlContent)) {
    do {

        if ($result = mysqli_store_result($conn)) {
            mysqli_free_result($result);
        }
    } while (mysqli_next_result($conn));
    
    echo "Database setup completed successfully!";
} else {
    echo "Error executing SQL: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
