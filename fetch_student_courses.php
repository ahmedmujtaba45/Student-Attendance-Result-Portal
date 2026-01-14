<?php
header('Content-Type: application/json');
$studentID = $_GET['StudentID'] ?? "";

if (empty($studentID)) {
    echo json_encode([]);
    exit();
}

$conn = new mysqli("sql310.infinityfree.com", "if0_40824636", "3UgIlYTVyj3", "if0_40824636_studentportal");

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "DB Connection Failed"]));
}

// Link Student to Course via Studentcourse junction table
$sql = "SELECT c.CourseID, c.CourseName 
        FROM course c
        JOIN studentcourse sc ON c.CourseID = sc.CourseID
        WHERE sc.StudentID = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $studentID);
$stmt->execute();
$result = $stmt->get_result();

$courses = [];
while($row = $result->fetch_assoc()) {
    $courses[] = $row;
}

$stmt->close();
$conn->close();
echo json_encode($courses);
?>