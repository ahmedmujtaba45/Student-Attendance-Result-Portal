<?php
header('Content-Type: application/json');

// Read JSON Input
$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (!$data) { exit(json_encode(["status" => "error", "message" => "Invalid Data"])); }

$conn = new mysqli("sql310.infinityfree.com", "if0_40824636", "3UgIlYTVyj3", "if0_40824636_studentportal");

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "DB Connection Failed"]));
}

$id = $data['TeacherID'];
$name = $data['Name'];
$email = $data['Email'];
$pass = $data['Password'];

// --- SQL Query ---
// We ONLY update Name, Email, and Password.
// We DO NOT touch 'DeptID' or 'Department', ensuring the department link stays safe.
$stmt = $conn->prepare("UPDATE teacher SET Name=?, Email=?, Password=? WHERE TeacherID=?");
$stmt->bind_param("ssss", $name, $email, $pass, $id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Profile updated successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Update failed: " . $conn->error]);
}

$stmt->close();
$conn->close();
?>