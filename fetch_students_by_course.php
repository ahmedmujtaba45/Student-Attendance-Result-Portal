<?php
header('Content-Type: application/json');

$courseID = $_GET['CourseID'] ?? "";

if (empty($courseID)) {
    echo json_encode([]);
    exit();
}

$conn = new mysqli("sql310.infinityfree.com", "if0_40824636", "3UgIlYTVyj3", "if0_40824636_studentportal");

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "DB Connection Failed"]));
}

// Fetch students enrolled in this course via Studentcourse table
$sql = "SELECT s.StudentID, s.Name 
        FROM student s
        JOIN studentcourse sc ON s.StudentID = sc.StudentID
        WHERE sc.CourseID = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $courseID); // CourseID is INT
$stmt->execute();
$result = $stmt->get_result();

$students = [];
while($row = $result->fetch_assoc()) {
    $students[] = $row;
}

$stmt->close();
$conn->close();
echo json_encode($students);
?>