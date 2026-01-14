<?php

$conn = new mysqli("sql310.infinityfree.com", "if0_40824636", "3UgIlYTVyj3", "if0_40824636_studentportal");

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "DB Connection Failed"]));
}

$sql = "SELECT r.ResultID, s.Name AS Student, t.Name AS Teacher, c.CourseName, r.Marks, r.Grade
        FROM results r
        JOIN student s ON r.StudentID = s.StudentID
        JOIN teacher t ON r.TeacherID = t.TeacherID
        JOIN course c ON r.CourseID = c.CourseID";
$result = $conn->query($sql);

echo "<table class='table table-bordered'>
<tr>
<th>S.No</th>
<th>Result ID</th>
<th>Student</th>
<th>Teacher</th>
<th>Course</th>
<th>Marks</th>
<th>Grade</th>
</tr>";

$serial = 1; // initialize serial number
while($row = $result->fetch_assoc()){
    echo "<tr>
            <td>".$serial++."</td>
            <td>{$row['ResultID']}</td>
            <td>{$row['Student']}</td>
            <td>{$row['Teacher']}</td>
            <td>{$row['CourseName']}</td>
            <td>{$row['Marks']}</td>
            <td>{$row['Grade']}</td>
          </tr>";
}

echo "</table>";
$conn->close();
?>
