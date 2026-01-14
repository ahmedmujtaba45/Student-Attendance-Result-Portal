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

$id = $data['StudentID'];
$name = $data['Name'];
$email = $data['Email'];
$pass = $data['Password'];

// Update query (ID is used for WHERE clause, not updated)
$stmt = $conn->prepare("UPDATE student SET Name=?, Email=?, Password=? WHERE StudentID=?");
$stmt->bind_param("ssss", $name, $email, $pass, $id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Profile updated successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Update failed: " . $conn->error]);
}

$stmt->close();
$conn->close();
?>