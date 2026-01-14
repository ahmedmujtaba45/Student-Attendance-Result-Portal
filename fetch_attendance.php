<?php

$conn = new mysqli("sql310.infinityfree.com", "if0_40824636", "3UgIlYTVyj3", "if0_40824636_studentportal");

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "DB Connection Failed"]));
}

$sql = "SELECT * FROM attendance a
        JOIN course c ON a.CourseID = c.CourseID";
$result = $conn->query($sql);

echo "<table class='table table-bordered'>
<tr>
<th>S.No</th>
<th>ID</th>
<th>Student</th>
<th>Teacher</th>
<th>Course</th>
<th>Date</th>
<th>Time</th>
<th>Status</th>
<th>Action</th>
</tr>";

$serial = 1; // initialize serial number
while($r = $result->fetch_assoc()){
    echo "<tr>
    <td>".$serial++."</td>
    <td>{$r['AttendanceID']}</td>
    <td>{$r['StudentID']}</td>
    <td>{$r['TeacherID']}</td>
    <td>{$r['CourseName']}</td>
    <td>{$r['Date']}</td>
    <td>{$r['Time']}</td>
    <td>{$r['Status']}</td>
    <td>
        <button class='btn btn-sm btn-danger'
        onclick='deleteAttendance({$r['AttendanceID']})'>Delete</button>
        <!-- Uncomment below to enable edit
        <button class='btn btn-sm btn-warning' onclick='editAttendance({$r['AttendanceID']}, \"{$r['Status']}\")'>Edit</button>
        -->
    </td>
    </tr>";
}

echo "</table>";
$conn->close();
?>
