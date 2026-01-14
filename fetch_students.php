<?php
$conn = new mysqli("sql310.infinityfree.com", "if0_40824636", "3UgIlYTVyj3", "if0_40824636_studentportal");

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "DB Connection Failed"]));
}
$result = $conn->query("SELECT * FROM student");

echo "<table class='table table-bordered'>";
echo "<thead><tr><th>S.No</th><th>ID</th><th>Name</th><th>Email</th><th>Department</th><th>Actions</th></tr></thead>";
echo "<tbody>";

$serial = 1; // Start serial number from 1
while($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>".$serial++."</td>"; // Serial number
    echo "<td>".$row['StudentID']."</td>";
    echo "<td>".$row['Name']."</td>";
    echo "<td>".$row['Email']."</td>";
    echo "<td>".$row['Department']."</td>";
    echo "<td>
            <a href='edit_student.php?id={$row['StudentID']}' class='btn btn-sm btn-warning'>Edit</a>
            <a href='delete_student.php?id={$row['StudentID']}' class='btn btn-sm btn-danger'>Delete</a>   
         </td>";
    echo "</tr>";
}
echo "</tbody></table>";
$conn->close();
?>
