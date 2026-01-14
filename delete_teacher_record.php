<?php
header('Content-Type: application/json');

$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (!$data) { exit(json_encode(["status" => "error", "message" => "Invalid Data"])); }

$conn = new mysqli("sql310.infinityfree.com", "if0_40824636", "3UgIlYTVyj3", "if0_40824636_studentportal");

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "DB Connection Failed"]));
}

$type = $data['type']; // 'attendance' or 'result'
$studentID = $data['studentID'];
$courseID = $data['courseID'];
$identifier = $data['identifier']; // Date (for attendance) OR ExamTitle (for result)

if($type === 'attendance') {
    // Delete specific student attendance for that date/course
    $stmt = $conn->prepare("DELETE FROM attendance WHERE StudentID=? AND CourseID=? AND Date=?");
    $stmt->bind_param("sis", $studentID, $courseID, $identifier);
} elseif ($type === 'result') {
    // Delete specific result
    $stmt = $conn->prepare("DELETE FROM results WHERE StudentID=? AND CourseID=? AND ExamTitle=?");
    $stmt->bind_param("sis", $studentID, $courseID, $identifier);
} else {
    exit(json_encode(["status" => "error", "message" => "Invalid Type"]));
}

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Record Deleted"]);
} else {
    echo json_encode(["status" => "error", "message" => "Deletion Failed"]);
}

$conn->close();
?>