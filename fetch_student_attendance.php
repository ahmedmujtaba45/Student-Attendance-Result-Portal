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

// Join Attendance with Course to get the Course Name
$sql = "SELECT a.AttendanceID, c.CourseName, a.Date, a.Time, a.Status
        FROM attendance a
        JOIN course c ON a.CourseID = c.CourseID
        WHERE a.StudentID = ? 
        ORDER BY a.Date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $studentID);
$stmt->execute();
$result = $stmt->get_result();

$attendance = [];
while($row = $result->fetch_assoc()) {
    $attendance[] = $row;
}

$stmt->close();
$conn->close();
echo json_encode($attendance);
?>