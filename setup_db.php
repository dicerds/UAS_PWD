<?php
// setup_db.php
$host = 'localhost';
$user = 'root';
$pass = ''; // Default XAMPP password

// 1. Connect without DB selected
$conn = mysqli_connect($host, $user, $pass);
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

echo "<h3>🛠️ Database Setup Tool</h3>";

// 2. Create Database
$sql_db = "CREATE DATABASE IF NOT EXISTS db_seminar";
if (mysqli_query($conn, $sql_db)) {
    echo "✅ Database `db_seminar` siap.<br>";
} else {
    echo "❌ Gagal buat database: " . mysqli_error($conn) . "<br>";
}

// 3. Select Database
mysqli_select_db($conn, 'db_seminar');

// 4. Create Tables
$sql_users = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    role ENUM('user', 'admin') DEFAULT 'user',
    is_active TINYINT(1) DEFAULT 0,
    otp_code VARCHAR(6),
    otp_expiry DATETIME,
    profile_pic VARCHAR(255) DEFAULT 'default_profile.png',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (mysqli_query($conn, $sql_users)) {
    echo "✅ Tabel `users` siap.<br>";
} else {
    echo "❌ Gagal buat tabel users: " . mysqli_error($conn) . "<br>";
}

// 5. Create Default Admin
$password_admin = password_hash('admin', PASSWORD_DEFAULT);
$sql_admin = "INSERT IGNORE INTO users (username, name, email, password, phone, role, is_active) 
              VALUES ('admin', 'Administrator', 'admin@seminar.com', '$password_admin', '08123456789', 'admin', 1)";

if (mysqli_query($conn, $sql_admin)) {
    echo "✅ User Admin Default siap (Email: admin@seminar.com, Pass: admin).<br>";
} else {
    echo "⚠️ Admin sudah ada atau gagal query: " . mysqli_error($conn) . "<br>";
}

echo "<hr><h4 style='color: green'>Selesai! Silakan coba login.</h4>";
?>