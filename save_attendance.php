
<?php
$conn = new mysqli("sql310.infinityfree.com", "if0_40824636", "3UgIlYTVyj3", "if0_40824636_studentportal");

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "DB Connection Failed"]));
}

$id = $_POST['AttendanceID'];
$status = $_POST['Status'];

$sql = "UPDATE attendance 
        SET Status = ? 
        WHERE AttendanceID = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $status, $id);
$stmt->execute();

$conn->close();
?>
