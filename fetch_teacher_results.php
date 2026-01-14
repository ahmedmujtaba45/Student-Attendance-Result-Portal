<?php
$conn = new mysqli("sql310.infinityfree.com", "if0_40824636", "3UgIlYTVyj3", "if0_40824636_studentportal");

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "DB Connection Failed"]));
}
$teacherID = $_GET['TeacherID'];

// Added r.CourseID, r.StudentID, r.ExamTitle
$sql = "SELECT r.ExamTitle, r.Marks, r.Grade, c.CourseName, r.CourseID, s.Name as StudentName, r.StudentID
        FROM results r
        JOIN course c ON r.CourseID = c.CourseID
        JOIN student s ON r.StudentID = s.StudentID
        WHERE r.TeacherID = ?
        ORDER BY r.CourseID ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $teacherID);
$stmt->execute();
$result = $stmt->get_result();

echo "<table class='table table-hover mt-3 align-middle'>
        <thead class='table-primary'>
            <tr>
                <th>Exam</th>
                <th>Course</th>
                <th>Student</th>
                <th>Marks</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>";

while($row = $result->fetch_assoc()) {
    $gradeColor = ($row['Grade'] == 'F') ? 'text-danger fw-bold' : 'text-success fw-bold';
    
    // Params: CourseID, ExamTitle
    $editParams = "'{$row['CourseID']}', '{$row['ExamTitle']}'";
    // Params: Type, StudentID, CourseID, UniqueIdentifier (ExamTitle)
    $delParams  = "'result', '{$row['StudentID']}', '{$row['CourseID']}', '{$row['ExamTitle']}'";

    echo "<tr>
            <td>{$row['ExamTitle']}</td>
            <td>{$row['CourseName']}</td>
            <td>{$row['StudentName']}</td>
            <td>{$row['Marks']} <span class='$gradeColor'>({$row['Grade']})</span></td>
            <td>
                <button class='btn btn-sm btn-outline-primary' onclick=\"editResRow($editParams)\">Edit</button>
                <button class='btn btn-sm btn-outline-danger' onclick=\"deleteRow($delParams)\">Delete</button>
            </td>
          </tr>";
}

echo "</tbody></table>";
?>