<?php
include 'config/koneksi.php';

echo "<h3>Debug: List All Users</h3>";

$query = mysqli_query($conn, "SELECT id, email, role, is_active FROM users");
if ($query && mysqli_num_rows($query) > 0) {
    echo "<table border='1'><tr><th>ID</th><th>Email</th><th>Role</th><th>Active</th></tr>";
    while ($row = mysqli_fetch_assoc($query)) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['email'] . "</td>";
        echo "<td>" . $row['role'] . "</td>";
        echo "<td>" . $row['is_active'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<b style='color:red'>No users found in database! User table is empty.</b>";
    echo "<br>Error: " . mysqli_error($conn);
}
?>