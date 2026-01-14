<?php
include 'db.php';

$sql = "SELECT * FROM course ORDER BY CourseID ASC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table class='table table-bordered table-striped'>";
    echo "<thead class='table-dark'><tr><th>Course ID</th><th>Course Name</th><th>Dept ID</th></tr></thead>";
    echo "<tbody>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["CourseID"] . "</td>";
        echo "<td>" . $row["CourseName"] . "</td>";
        echo "<td>" . $row["DeptID"] . "</td>";
        echo "</tr>";
    }
    echo "</tbody></table>";
} else {
    echo "<div class='alert alert-info'>No courses found. Add a new course using the button above.</div>";
}
?>