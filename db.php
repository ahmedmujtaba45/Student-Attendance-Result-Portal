<?php
$host = "sql310.infinityfree.com";      // Database host
$user = "if0_40824636";           // Database username
$pass = "3UgIlYTVyj3";               // Database password
$dbname = "if0_40824636_studentportal"; // Your database name

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}
?>
