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

// Join Results with Course to get the Course Name
$sql = "SELECT r.ResultID, c.CourseName, r.Marks, r.Grade
        FROM results r
        JOIN course c ON r.CourseID = c.CourseID
        WHERE r.StudentID = ? 
        ORDER BY r.ResultID DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $studentID);
$stmt->execute();
$result = $stmt->get_result();

$results = [];
while($row = $result->fetch_assoc()) {
    $results[] = $row;
}

$stmt->close();
$conn->close();
echo json_encode($results);
?>