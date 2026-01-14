<?php
$conn = new mysqli("sql310.infinityfree.com", "if0_40824636", "3UgIlYTVyj3", "if0_40824636_studentportal");

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "DB Connection Failed"]));
}

if ($_POST) {
    $id = $_POST['TeacherID'];
    $name = $_POST['Name'];
    $email = $_POST['Email'];
    $password = $_POST['Password'];
    $department = $_POST['Department'];

    $stmt = $conn->prepare("INSERT INTO teacher(TeacherID, Name, Email, Password, Department) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $id, $name, $email, $password, $department);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
header("Location: admindashboard.cshtml");
exit();
?>
