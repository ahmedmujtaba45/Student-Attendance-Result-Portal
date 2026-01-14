<?php
session_start();
if (!isset($_SESSION['UserID']) || $_SESSION['Role'] !== 'teacher') {
    exit("Not allowed");
}

$conn = new mysqli("sql310.infinityfree.com", "if0_40824636", "3UgIlYTVyj3", "if0_40824636_studentportal");

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "DB Connection Failed"]));
}

$AttendanceID = $_POST['AttendanceID'] ?? '';
$StudentID = $_POST['StudentID'];
$CourseID = $_POST['CourseID'];
$Date = $_POST['Date'];
$Time = $_POST['Time'];
$Status = $_POST['Status'];

if($AttendanceID) {
    $stmt = $conn->prepare("UPDATE attendance SET StudentID=?, CourseID=?, Date=?, Time=?, Status=? WHERE AttendanceID=?");
    $stmt->bind_param("sssssi", $StudentID, $CourseID, $Date, $Time, $Status, $AttendanceID);
} else {
    $stmt = $conn->prepare("INSERT INTO attendance(StudentID, CourseID, Date, Time, Status) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $StudentID, $CourseID, $Date, $Time, $Status);
}

$stmt->execute();
$stmt->close();
$conn->close();
?>
