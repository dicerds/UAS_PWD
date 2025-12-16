<?php
include 'config/koneksi.php';


$check = mysqli_query($conn, "SELECT * FROM seminars");
if (mysqli_num_rows($check) == 0) {

    $title = "Seminar Nasional AI 2025";
    $desc  = "Seminar membahas masa depan Artificial Intelligence di Indonesia. Menghadirkan pembicara ahli dari Google dan OpenAI.";
    $date  = "2025-12-20";
    $time  = "09:00:00";
    $loc   = "Gedung Serbaguna Jakarta";
    

    $lat   = -6.2088;
    $lng   = 106.8456;
    
    $sql = "INSERT INTO seminars (title, description, date, time, location, latitude, longitude, max_participants, price, image) 
            VALUES ('$title', '$desc', '$date', '$time', '$loc', '$lat', '$lng', 200, 50000, 'ai_seminar.jpg')";
            
    if (mysqli_query($conn, $sql)) {
        echo "Dummy seminar created successfully.";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo "Seminars already exist.";
}
?>
