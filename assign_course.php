<?php
include 'db.php';

$studentID = $_POST['StudentID']; // From the Dropdown
$courseID = $_POST['CourseID'];   // From the Text Input

// 1. Check if Course exists & Get DeptID
$courseCheck = $conn->query("SELECT DeptID FROM course WHERE CourseID = '$courseID'");
if($courseCheck->num_rows == 0) {
    echo "<script>alert('Error: Course ID not found!'); window.location.href='admindashboard.cshtml';</script>";
    exit();
}
$courseData = $courseCheck->fetch_assoc();
$deptID = $courseData['DeptID'];

// 2. Check if already assigned
$duplicateCheck = $conn->query("SELECT * FROM studentcourse WHERE StudentID = '$studentID' AND courseID = '$courseID'");
if($duplicateCheck->num_rows > 0) {
    echo "<script>alert('Error: This student is already assigned to this course!'); window.location.href='admindashboard.cshtml';</script>";
    exit();
}

// 3. Assign Course
$sql = "INSERT INTO studentcourse (StudentID, CourseID, DeptID) VALUES ('$studentID', '$courseID', '$deptID')";

if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Course assigned successfully!'); window.location.href='admindashboard.cshtml';</script>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
?>