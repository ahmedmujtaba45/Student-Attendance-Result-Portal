<?php
include 'db.php';

header('Content-Type: application/json');

$sql = "SELECT StudentID, Name FROM student ORDER BY Name ASC";
$result = $conn->query($sql);

$students = [];
while($row = $result->fetch_assoc()) {
    $students[] = $row;
}

echo json_encode($students);
?>