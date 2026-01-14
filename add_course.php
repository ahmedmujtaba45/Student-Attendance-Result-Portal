<?php
include 'db.php';

$courseID = $_POST['CourseID'];
$courseName = $_POST['CourseName'];
$deptID = $_POST['DeptID'];

// Check if Course ID exists
$check = $conn->query("SELECT * FROM course WHERE CourseID = '$courseID'");
if($check->num_rows > 0) {
    echo "<script>alert('Error: Course ID already exists!'); window.location.href='admindashboard.cshtml';</script>";
    exit();
}

$sql = "INSERT INTO course (CourseID, CourseName, DeptID) VALUES ('$courseID', '$courseName', '$deptID')";

if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Course added successfully!'); window.location.href='admindashboard.cshtml';</script>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
?>