<?php
header('Content-Type: application/json');

$conn = new mysqli("sql310.infinityfree.com", "if0_40824636", "3UgIlYTVyj3", "if0_40824636_studentportal");

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "DB Connection Failed"]));
}

$teacherID = $_GET['TeacherID'] ?? '';

if(empty($teacherID)) {
    echo json_encode(["error" => "No Teacher ID provided"]);
    exit();
}

// JOIN Teacher with Department to get the Name (e.g. "Software Engineering")
$sql = "SELECT t.TeacherID, t.Name, t.Email, t.Password, d.DeptName 
        FROM teacher t 
        LEFT JOIN department d ON t.DeptID = d.DeptID 
        WHERE t.TeacherID = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $teacherID);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode($row);
} else {
    echo json_encode(["error" => "Teacher not found"]);
}

$stmt->close();
$conn->close();
?>