<?php

include 'config/koneksi.php';

echo "<h2>Database Migration Script</h2>";
echo "<p>Updating database schema...</p>";


$check_username = mysqli_query($conn, "SHOW COLUMNS FROM users LIKE 'username'");
if (mysqli_num_rows($check_username) == 0) {

    mysqli_query($conn, "ALTER TABLE users ADD COLUMN username VARCHAR(50) UNIQUE AFTER id");
    echo "<p>✓ Added username column to users table</p>";
    

    $users = mysqli_query($conn, "SELECT id, email FROM users WHERE username IS NULL OR username = ''");
    while ($user = mysqli_fetch_assoc($users)) {
        $username = strtolower(explode('@', $user['email'])[0]);
        $username = preg_replace('/[^a-z0-9_]/', '', $username);
        $counter = 1;
        $original_username = $username;

        while (true) {
            $check = mysqli_query($conn, "SELECT id FROM users WHERE username = '$username'");
            if (mysqli_num_rows($check) == 0) {
                break;
            }
            $username = $original_username . $counter;
            $counter++;
        }
        
        mysqli_query($conn, "UPDATE users SET username = '$username' WHERE id = " . $user['id']);
    }
    echo "<p>✓ Generated usernames for existing users</p>";
} else {
    echo "<p>✓ Username column already exists</p>";
}

$check_date_event = mysqli_query($conn, "SHOW COLUMNS FROM seminars LIKE 'date_event'");
if (mysqli_num_rows($check_date_event) == 0) {

    $check_date = mysqli_query($conn, "SHOW COLUMNS FROM seminars LIKE 'date'");
    if (mysqli_num_rows($check_date) > 0) {

        mysqli_query($conn, "ALTER TABLE seminars CHANGE date date_event DATE NOT NULL");
        echo "<p>✓ Renamed date column to date_event</p>";
    } else {

        mysqli_query($conn, "ALTER TABLE seminars ADD COLUMN date_event DATE NOT NULL AFTER description");
        echo "<p>✓ Added date_event column</p>";
    }
} else {
    echo "<p>✓ date_event column already exists</p>";
}


$check_time_event = mysqli_query($conn, "SHOW COLUMNS FROM seminars LIKE 'time_event'");
if (mysqli_num_rows($check_time_event) == 0) {

    $check_time = mysqli_query($conn, "SHOW COLUMNS FROM seminars LIKE 'time'");
    if (mysqli_num_rows($check_time) > 0) {

        mysqli_query($conn, "ALTER TABLE seminars CHANGE time time_event TIME NOT NULL");
        echo "<p>✓ Renamed time column to time_event</p>";
    } else {

        mysqli_query($conn, "ALTER TABLE seminars ADD COLUMN time_event TIME NOT NULL AFTER date_event");
        echo "<p>✓ Added time_event column</p>";
    }
} else {
    echo "<p>✓ time_event column already exists</p>";
}

echo "<h3 style='color: green;'>Migration completed successfully!</h3>";
echo "<p><a href='index.php'>Go to Home</a></p>";
?>

