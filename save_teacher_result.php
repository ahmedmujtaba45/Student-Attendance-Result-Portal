<?php
session_start();
if (!isset($_SESSION['UserID']) || $_SESSION['Role'] !== 'teacher') {
    exit("Not allowed");
}

$conn = new mysqli("sql310.infinityfree.com", "if0_40824636", "3UgIlYTVyj3", "if0_40824636_studentportal");

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "DB Connection Failed"]));
}

$ResultID = $_POST['ResultID'] ?? '';
$StudentID = $_POST['StudentID'];
$CourseID = $_POST['CourseID'];
$Marks = $_POST['Marks'];
$Grade = $_POST['Grade'];

if($ResultID) {
    $stmt = $conn->prepare("UPDATE results SET StudentID=?, CourseID=?, Marks=?, Grade=? WHERE ResultID=?");
    $stmt->bind_param("ssisi", $StudentID, $CourseID, $Marks, $Grade, $ResultID);
} else {
    $stmt = $conn->prepare("INSERT INTO results(StudentID, CourseID, Marks, Grade) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $StudentID, $CourseID, $Marks, $Grade);
}

$stmt->execute();
$stmt->close();
$conn->close();
?>
