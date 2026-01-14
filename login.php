<?php
$conn = new mysqli("sql310.infinityfree.com", "if0_40824636", "3UgIlYTVyj3", "if0_40824636_studentportal");

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "DB Connection Failed"]));
}

$role = $_POST['role'];
$email = $_POST['email'];
$password = $_POST['password'];

if ($role == "" || $email == "" || $password == "") {
    echo json_encode(["status" => "error", "message" => "All fields required"]);
    exit();
}

// ADMIN LOGIN
if ($role === "admin") {
    $stmt = $conn->prepare("SELECT AdminID, Name, Password FROM admin WHERE Email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        echo json_encode(["status" => "error"]);
        exit();
    }

    $stmt->bind_result($id, $name, $dbPass);
    $stmt->fetch();

    if ($password === $dbPass) {
        echo json_encode(["status" => "success", "role" => "admin", "id" => $id, "name" => $name]);
    } else {
        echo json_encode(["status" => "error"]);
    }
    exit();
}

// TEACHER LOGIN
if ($role === "teacher") {
    $stmt = $conn->prepare("SELECT TeacherID, Name, Password FROM teacher WHERE Email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        echo json_encode(["status" => "error"]);
        exit();
    }

    $stmt->bind_result($id, $name, $dbPass);
    $stmt->fetch();

    if ($password === $dbPass) {
        echo json_encode(["status" => "success", "role" => "teacher", "id" => $id, "name" => $name]);
    } else {
        echo json_encode(["status" => "error"]);
    }
    exit();
}

// STUDENT LOGIN
if ($role === "student") {
    $stmt = $conn->prepare("SELECT StudentID, Name, Password FROM student WHERE Email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        echo json_encode(["status" => "error"]);
        exit();
    }

    $stmt->bind_result($id, $name, $dbPass);
    $stmt->fetch();

    if ($password === $dbPass) {
        echo json_encode(["status" => "success", "role" => "student", "id" => $id, "name" => $name]);
    } else {
        echo json_encode(["status" => "error"]);
    }
    exit();
}

echo json_encode(["status" => "error"]);
exit();
?>
