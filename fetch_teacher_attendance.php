<?php
$conn = new mysqli("sql310.infinityfree.com", "if0_40824636", "3UgIlYTVyj3", "if0_40824636_studentportal");

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "DB Connection Failed"]));
}
$teacherID = $_GET['TeacherID'];

// Added a.CourseID and a.StudentID to the SELECT so we can use them in buttons
$sql = "SELECT a.Date, a.Time, a.Status, c.CourseName, a.CourseID, s.Name as StudentName, a.StudentID
        FROM attendance a
        JOIN course c ON a.CourseID = c.CourseID
        JOIN student s ON a.StudentID = s.StudentID
        WHERE a.TeacherID = ?
        ORDER BY a.Date DESC, a.Time DESC LIMIT 50";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $teacherID);
$stmt->execute();
$result = $stmt->get_result();

echo "<table class='table table-hover mt-3 align-middle'>
        <thead class='table-primary'>
            <tr>
                <th>Date</th>
                <th>Course</th>
                <th>Student</th>
                <th>Status</th>
                <th>Actions</th> </tr>
        </thead>
        <tbody>";

while($row = $result->fetch_assoc()) {
    $statusColor = ($row['Status'] == 'Present') ? 'success' : 'danger';
    $time = date("h:i A", strtotime($row['Time']));
    
    // Pass Raw IDs to the JavaScript functions
    $editParams = "'{$row['CourseID']}', '{$row['Date']}'";
    $delParams  = "'attendance', '{$row['StudentID']}', '{$row['CourseID']}', '{$row['Date']}'";

    echo "<tr>
            <td>{$row['Date']} <br> <small class='text-muted'>$time</small></td>
            <td>{$row['CourseName']}</td>
            <td>{$row['StudentName']}</td>
            <td><span class='badge bg-$statusColor'>{$row['Status']}</span></td>
            <td>
                <button class='btn btn-sm btn-outline-primary' onclick=\"editAttRow($editParams)\">Edit</button>
                <button class='btn btn-sm btn-outline-danger' onclick=\"deleteRow($delParams)\">Delete</button>
            </td>
          </tr>";
}

echo "</tbody></table>";
?>