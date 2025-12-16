<?php
session_start();
include 'config/koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location: auth/login.php");
    exit();
}

$id = $_SESSION['id'];


mysqli_query($conn, "DELETE FROM registrations WHERE user_id = '$id'");


if (mysqli_query($conn, "DELETE FROM users WHERE id = '$id'")) {
    session_destroy();
    echo "<script>alert('Akun berhasil dihapus. Sampai jumpa!'); window.location='index.php';</script>";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>