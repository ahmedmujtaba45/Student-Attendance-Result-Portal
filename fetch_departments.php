<?php
// connect to database
$conn = new mysqli("sql310.infinityfree.com", "if0_40824636", "3UgIlYTVyj3", "if0_40824636_studentportal");

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "DB Connection Failed"]));
}

header('Content-Type: application/json');

$sql = "SELECT DeptID, DeptName FROM department";
$result = $conn->query($sql);

$departments = [];
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $departments[] = $row;
    }
}

echo json_encode($departments);
?>