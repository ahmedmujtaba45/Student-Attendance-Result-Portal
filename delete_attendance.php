<?php
$conn = new mysqli("sql310.infinityfree.com", "if0_40824636", "3UgIlYTVyj3", "if0_40824636_studentportal");

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "DB Connection Failed"]));
}
$id = $_GET['id'];
$conn->query("DELETE FROM attendance WHERE AttendanceID=$id");
$conn->close();
?>
